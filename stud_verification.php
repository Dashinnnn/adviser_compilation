<?php 
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

ob_start();

include '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_error_log');

session_start(); 
if (!isset($_SESSION['auth_user']['coordinators_id']) || $_SESSION['auth_user']['coordinators_id'] == 0) {
    header('Location: index.php');
    exit;
}

$coordinator_ID = $_SESSION['auth_user']['coordinators_id'];
$stmt = $conn->prepare("SELECT assigned_section, second_assigned_section FROM coordinators_account WHERE id = ?");
$stmt->execute([$coordinator_ID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data === false || (!isset($data['assigned_section']) && !isset($data['second_assigned_section']))) {
    die("Error: No assigned section found for coordinator ID: $coordinator_ID");
}

$section = [];
if (!empty($data['assigned_section'])) {
    $section[] = $data['assigned_section'];
} 

if (!empty($data['second_assigned_section'])) {
    $section[] = $data['second_assigned_section'];
}

$stmt = $conn->prepare("SELECT * FROM students_data WHERE stud_section IN (" . implode(',', array_fill(0, count($section), '?')) . ")");
$stmt->execute($section);
$studList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle export requests
if (isset($_GET['export'])) {
    ob_clean();

    if ($_GET['export'] == 'excel') {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'SIS NO.');
        $sheet->setCellValue('B1', 'FULLNAME');
        $sheet->setCellValue('C1', 'COURSE');
        $sheet->setCellValue('D1', 'YEAR & SECTION');
        $sheet->setCellValue('E1', 'STATUS');

        $row = 2;
        foreach ($studList as $trainee) {
            $fullName = trim(($trainee['first_name'] ?? '') . ' ' . ($trainee['middle_name'] ?? '') . ' ' . ($trainee['last_name'] ?? ''));
            $sheet->setCellValue('A' . $row, $trainee['student_ID'] ?? 'N/A');
            $sheet->setCellValue('B' . $row, $fullName ?: 'N/A');
            $sheet->setCellValue('C' . $row, $trainee['stud_course'] ?? 'N/A');
            $sheet->setCellValue('D' . $row, $trainee['stud_section'] ?? 'N/A');
            $sheet->setCellValue('E' . $row, $trainee['verification_status'] ?? 'N/A');
            $row++;
        }

        $filename = "Student_Verification_List.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    if ($_GET['export'] == 'csv') {
        $filename = "Student_Verification_List.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['SIS NO.', 'FULLNAME', 'COURSE', 'YEAR & SECTION', 'STATUS']);

        foreach ($studList as $trainee) {
            $fullName = trim(($trainee['first_name'] ?? '') . ' ' . ($trainee['middle_name'] ?? '') . ' ' . ($trainee['last_name'] ?? ''));
            fputcsv($output, [
                $trainee['student_ID'] ?? 'N/A',
                $fullName ?: 'N/A',
                $trainee['stud_course'] ?? 'N/A',
                $trainee['stud_section'] ?? 'N/A',
                $trainee['verification_status'] ?? 'N/A'
            ]);
        }

        fclose($output);
        exit;
    }

    if ($_GET['export'] == 'pdf') {
        ob_clean();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator('OJT Web Portal');
        $pdf->SetAuthor('Coordinator');
        $pdf->SetTitle("Student Verification List");
        $pdf->SetSubject('Student Data');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 12);

        $pdf->Cell(0, 10, "Student Verification List", 0, 1, 'C');
        $pdf->Ln(5);

        $html = '<table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr style="background-color:#700000; color:white; text-align:center;">
                            <th width="20%">SIS NO.</th>
                            <th width="20%">FULLNAME</th>
                            <th width="20%">COURSE</th>
                            <th width="20%">YEAR & SECTION</th>
                            <th width="20%">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($studList as $trainee) {
            $fullName = trim(($trainee['first_name'] ?? '') . ' ' . ($trainee['middle_name'] ?? '') . ' ' . ($trainee['last_name'] ?? ''));
            $html .= '<tr>
                        <td width="20%">' . htmlspecialchars($trainee['student_ID'] ?? 'N/A') . '</td>
                        <td width="20%">' . htmlspecialchars($fullName ?: 'N/A') . '</td>
                        <td width="20%">' . htmlspecialchars($trainee['stud_course'] ?? 'N/A') . '</td>
                        <td width="20%" style="text-align:center;">' . htmlspecialchars($trainee['stud_section'] ?? 'N/A') . '</td>
                        <td width="20%">' . htmlspecialchars($trainee['verification_status'] ?? 'N/A') . '</td>
                      </tr>';
        }

        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $filename = "Student_Verification_List.pdf";
        $pdf->Output($filename, 'D');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Find your H.T.E</title>
    <link rel="shortcut icon" href="images/Picture1.png">
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .top-function { 
            display: flex;
            justify-content: space-between;
        }
        .dl-btn { 
            background-color: #ffc107;
            padding: 8px; border-radius: .5rem;
            color: #333; font-weight: bold;
            border: none; 
            cursor: pointer;
        }
        .dl-btn:hover { 
            background-color: #ffc107;
            color: #8B0000; 
        }
        .search-cont { 
            display: flex;
            flex-direction: row;
            justify-content: space-around; 
            gap: 10px;
        }
        .search-box {
            width: 100%;
            padding: 8px; 
            border-radius: 4px; 
            border: 2px solid #8B0000; 
        }
        .search-btn {
            padding: 8px 20px; 
            background-color: #8B0000; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }
        .hidden-btn {
            padding: 8px 20px; 
            background-color: rgb(73, 70, 70); 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }
        .table-container { 
            width: 100%; 
            overflow-x: auto; 
            margin-bottom: 20px; 
        }
        .trainee-table { 
            width: 100%; 
            border-collapse: collapse;
        }
        .trainee-table th { 
            background-color: #fff; 
            color: #700000; 
            text-align: center; 
            padding: 20px 50px; 
            min-width: 300px; 
            border: 2px solid #700000; 
            font-weight: 600; 
        }
        .trainee-table th[colspan="5"] { 
            background-color: #700000;
            color: #fff; 
            text-align: center; 
            padding: 20px 50px; 
            border: 2px solid #fff; 
            font-weight: 600; 
        }
        tbody tr td {
            color: #000;
            text-align: center;
            padding: 20px 50px; 
            border: 2px solid #700000;
            font-weight: 600; 
        }
        tbody tr:nth-child(odd) {
            background-color: rgb(221, 218, 218);
            border: 2px solid #700000;
        }
        .view-profile {
            background-color: #8B0000;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin: 2px;
        }
        .view-profile:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        .dl-btn-cont {
            display: flex; 
            justify-content: space-between;
            gap: 10px;
        }
        .section-container {
            margin-bottom: 40px;
        }
        .actions-container {
            display: flex;
            justify-content: center;
            gap: 5px; 
            flex-wrap: nowrap; 
        }
        /* Modal and Overlay Styles */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .overlay.active {
            display: block;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 600px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            padding: 20px;
        }
        .modal.active {
            display: block;
        }
        .modal-content {
            text-align: center;
            margin-top:30px;
            margin-bottom:10px;
        }
        .modal-content img {
            max-width: 100%;
            height: auto;
            border: 2px solid #8B0000;
            border-radius: 4px;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            color: #8B0000;
            cursor: pointer;
        }

        /* SweetAlert2 Custom Styling */
        .swal2-popup, 
        .swal-custom-popup {
            border-radius: 40px !important;
            padding: 80px 30px 40px 30px !important;
            background-color: #700000 !important;
            position: relative;
            border: 2px solid rgba(255, 193, 7, 0.3) !important;
        }

        .swal2-icon, 
        .swal-custom-icon {
            position: absolute !important;
            left: 50% !important;
            top: 35px !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
            z-index: 2 !important;
            background-color: #700000 !important;
            border: 3px solid #ffc107 !important;
            color: #ffc107 !important;
            animation: pulse 1.5s infinite !important;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
        }

        .swal2-icon.swal2-warning .swal2-icon-content {
            color: #ffc107 !important;
        }

        .swal2-icon.swal2-warning {
            margin-top: -20px !important;
        }

        .swal-confirm-proceed,
        .swal-cancel-proceed {
            background-color: rgb(255, 255, 255) !important;
            color: #000000 !important;
            border: none !important;
            border-radius: 6px !important;
            padding: 10px 25px !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
            margin: 0 5px !important;
        }

        .swal-confirm-proceed:hover, 
        .swal-cancel-proceed:hover {
            background-color: #ffc107 !important;
            color: #700000 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
        }

        .swal-text-white {
            color: #fff !important;
            font-size: 16px;
            line-height: 1.6;
        }

        .title-color {
            color: #ffc107 !important;
            font-size: 24px !important;
            font-weight: 600 !important;
            margin-bottom: 15px !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .swal-html-container {
            max-height: 60vh;
            overflow-y: auto;
            padding-right: 5px;
            margin-right: -5px;
        }

        .swal-html-container::-webkit-scrollbar{
            width: 6px;  
        }

        .swal-html-container::-webkit-scrollbar-track{
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .swal-html-container::-webkit-scrollbar-thumb{
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;  
        }

        .swal-html-container::-webkit-scrollbar-thumb:hover{
            background: rgba(255, 193, 7, 0.5);  
        }
    </style>  
</head>
<body>
    <?php require_once 'templates/coordinators_navbar.php';?>
    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div class="page-header">
                <div class="page-title"><br><h1 style="font-size: 16px;"><b>Student Validation</b></h1><br><br></div>
            </div>
            <div class="section-container">
                <div class="top-function">
                    <div class="dl-btn-cont">
                        <button class="dl-btn" id="dl-btn" onclick="toggleDownloadValue()">
                            <i class="fa-solid fa-download"></i>
                            Download
                        </button>
                        <div>
                            <button class="hidden-btn" id="cv-btn" style="display: none;" onclick="window.location.href='?export=csv'"><i class="fa-solid fa-file-csv"></i></button>
                            <button class="hidden-btn" id="ex-btn" style="display: none;" onclick="window.location.href='?export=excel'"><i class="fa-solid fa-file-excel"></i></button>
                            <button class="hidden-btn" id="pdf-btn" style="display: none;" onclick="window.location.href='?export=pdf'"><i class="fa-solid fa-file-pdf"></i></button>
                        </div>
                    </div>
                    <div class="search-cont">
                        <input type="text" class="search-box" placeholder="Search trainee..." id="traineeSearch">
                        <button class="search-btn">Search</button>
                    </div>
                </div>
                <br><br>
                <div class="table-container">
                    <table class="trainee-table" id="table-first">
                        <thead>
                            <tr>
                                <th>SIS NO.</th>
                                <th>FULL NAME</th>
                                <th>COURSE</th>
                                <th>YEAR & SECTION</th>
                                <th>STATUS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($studList)) :?>
                                <?php foreach ($studList as $student):?>
                                    <?php
                                    $fullName = trim("{$student['last_name']}, {$student['first_name']} " . ($student['middle_name'] ? substr($student['middle_name'], 0, 1) . '.' : ''));
                                    ?>
                                    <tr data-student-id="<?php echo htmlspecialchars($student['id'] ?? ''); ?>">    
                                        <td><?php echo htmlspecialchars($student['student_ID'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($fullName); ?></td>
                                        <td><?php echo htmlspecialchars($student['stud_course'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($student['stud_section'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($student['verification_status'] ?? 'N/A')); ?></td>
                                        <td>
                                            <div class="actions-container">
                                                <button class="view-profile" onclick="viewCor(<?php echo htmlspecialchars($student['id'] ?? ''); ?>)">View COR</button>
                                                <button class="view-profile accept-btn" 
                                                        onclick="updateStatus(<?php echo htmlspecialchars($student['id'] ?? ''); ?>, 'accept', '<?php echo addslashes($fullName); ?>')"
                                                        <?php echo ($student['verification_status'] ?? '') === 'accept' ? 'disabled' : ''; ?>>
                                                    Accept
                                                </button>
                                                <button class="view-profile reject-btn" 
                                                        onclick="updateStatus(<?php echo htmlspecialchars($student['id'] ?? ''); ?>, 'reject', '<?php echo addslashes($fullName); ?>')"
                                                        <?php echo ($student['verification_status'] ?? '') === 'reject' ? 'disabled' : ''; ?>>
                                                    Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" style="text-align:center;">No registered student found in section.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for displaying COR image -->
    <div class="overlay" id="overlay"></div>
    <div class="modal" id="corModal">
        <span class="close-btn" onclick="closeModal()">×</span>
        <div class="modal-content">
            <img src="images/sampleCor.png" alt="COR Image" id="corImage">
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('.search-btn').on('click', function() {
                var searchTerm = $('#traineeSearch').val().toLowerCase().trim();
                $('.trainee-table tbody tr').each(function() {
                    var fullName = $(this).find('td:eq(1)').text().toLowerCase().trim();
                    if (fullName.includes(searchTerm)) {
                        $(this).show(); 
                    } else {
                        $(this).hide(); 
                    }
                });
            });
 
            $('#traineeSearch').on('input', function() {
                if ($(this).val().trim() === '') {
                    $('.trainee-table tbody tr').show(); 
                }
            });
        });

        let dlValue = false;

        function toggleDownloadValue() {
            dlValue = !dlValue;
            console.log(dlValue);

            const cvDl = document.getElementById("cv-btn");
            const exDl = document.getElementById("ex-btn");
            const pdfDl = document.getElementById("pdf-btn");

            cvDl.style.display = dlValue ? "inline-block" : "none";
            exDl.style.display = dlValue ? "inline-block" : "none";
            pdfDl.style.display = dlValue ? "inline-block" : "none";
        }

        function printTable() {
            window.print();
        }

        function viewCor(studentId) {
            const overlay = document.getElementById('overlay');
            const modal = document.getElementById('corModal');
            modal.classList.add('active');
            overlay.classList.add('active');
        }

        function closeModal() {
            const overlay = document.getElementById('overlay');
            const modal = document.getElementById('corModal');
            modal.classList.remove('active');
            overlay.classList.remove('active');
        }

        function updateStatus(studentId, status, studentName) {
            if (!studentId) {
                showCustomAlert({
                    title: 'Error',
                    html: 'Invalid student ID.',
                    icon: 'error',
                    confirmText: 'OK'
                });
                return;
            }
            showCustomAlert({
                title: 'Confirm',
                html: `You are about to mark ${studentName}'s verification status as <strong>${status}</strong>.`,
                showCancelButton: true,
                confirmText: `Yes, ${status} it`,
                cancelText: 'Cancel',
                icon: 'warning',
                onConfirm: () => {
                    $.post('update_verification_status.php', { 
                        student_id: studentId, 
                        status: status
                    }, function(response) {
                        if (response.message && response.message.includes("updated")) {
                            showCustomAlert({
                                title: 'Success',
                                html: `${studentName}'s verification status updated to ${status}.`,
                                icon: 'success',
                                confirmText: 'OK',
                                onConfirm: () => location.reload()
                            });
                        } else {
                            showCustomAlert({
                                title: 'Error',
                                html: response.message || 'Unknown error occurred.',
                                icon: 'error',
                                confirmText: 'OK'
                            });
                        }
                    }, 'json').fail(function() {
                        showCustomAlert({
                            title: 'Error',
                            html: 'Failed to update status.',
                            icon: 'error',
                            confirmText: 'OK'
                        });
                    });
                }
            });
        }

        function showCustomAlert(options) {
            const defaultOptions = {
                title: 'Alert',
                html: '',
                confirmText: 'OK',
                cancelText: 'Cancel',
                onConfirm: () => console.log('Confirmed'),
                onCancel: () => console.log('Canceled'),
                showCancelButton: false,
                icon: 'warning'
            };

            const config = { ...defaultOptions, ...options };
            const contentLength = config.html.length;
            const popupClass = contentLength > 100 ? 'swal-custom-popup swal-large-content' : 'swal-custom-popup';

            const swalOptions = {
                title: config.title,
                html: `<div class="swal-text-white">${config.html}</div>`,
                icon: config.icon,
                showCancelButton: config.showCancelButton,
                confirmButtonText: config.confirmText,
                cancelButtonText: config.cancelText,
                width: contentLength > 100 ? '600px' : '500px',
                customClass: {
                    popup: popupClass,
                    icon: 'swal-custom-icon',
                    title: 'title-color',
                    confirmButton: 'swal-confirm-proceed',
                    cancelButton: 'swal-cancel-proceed',
                    htmlContainer: 'swal-html-container'
                },
                didOpen: () => {
                    const container = document.querySelector('.swal-html-container');
                    if (container && container.scrollHeight > 300) {
                        container.style.maxHeight = '400px';
                        container.style.overflowY = 'auto';
                        container.style.padding = '0 10px 0 0';
                        container.style.marginRight = '-10px';
                    }
                },
                willClose: () => {
                    const container = document.querySelector('.swal-html-container');
                    if (container) {
                        container.style.maxHeight = '';
                        container.style.overflowY = '';
                        container.style.padding = '';
                        container.style.marginRight = '';
                    }
                }
            };

            return Swal.fire(swalOptions).then((result) => {
                if (result.isConfirmed && typeof config.onConfirm === 'function') {
                    config.onConfirm();
                } else if (result.dismiss === Swal.DismissReason.cancel && typeof config.onCancel === 'function') {
                    config.onCancel();
                }
                return result;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('overlay');
            overlay.addEventListener('click', function() {
                closeModal();
            });
        });
    </script>
</body>
</html>