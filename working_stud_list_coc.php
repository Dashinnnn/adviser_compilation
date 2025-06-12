<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

$coordinatorID = $_SESSION['auth_user']['coordinators_id'];
$stmt = $conn->prepare("SELECT assigned_section, second_assigned_section FROM coordinators_account WHERE id = ?");
$stmt->execute([$coordinatorID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data === false || (!isset($data['assigned_section']) && !isset($data['second_assigned_section']))) {
    die("Error: No assigned sections found for coordinator ID $coordinatorID");
}

$assignedSection = $data['assigned_section'] ?? null;
$secondAssignedSection = $data['second_assigned_section'] ?? null;

// Debug to check section values
var_dump($assignedSection, $secondAssignedSection); 

// Fetch students for first section with submitted eval_hte documents
$studentsFirstSection = [];
if (!empty($assignedSection)) {
    $stmt = $conn->prepare("
        SELECT s.* 
        FROM students_data s
        LEFT JOIN endorsement_documents ed ON s.id = ed.student_id 
        WHERE s.stud_section = ? 
        AND s.is_working_student = 'yes' 
        AND ed.document_type = 'coc' 
        AND ed.student_id IS NOT NULL
    ");
    $stmt->execute([$assignedSection]);
    $studentsFirstSection = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch students for second section with submitted coc documents
$studentsSecondSection = [];
if (!empty($secondAssignedSection)) {
    $stmt = $conn->prepare("
        SELECT s.* 
        FROM students_data s
        LEFT JOIN endorsement_documents ed ON s.id = ed.student_id 
        WHERE s.stud_section = ? 
        AND s.is_working_student = 'yes' 
        AND ed.document_type = 'coc' 
        AND ed.student_id IS NOT NULL
    ");
    $stmt->execute([$secondAssignedSection]);
    $studentsSecondSection = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle Excel download
if (isset($_GET['export']) && in_array($_GET['export'], ['excel', 'csv', 'pdf']) && isset($_GET['section'])) {
    ob_clean();
    $section = $_GET['section'] === 'second' ? $secondAssignedSection : $assignedSection;
    $students = $_GET['section'] === 'second' ? $studentsSecondSection : $studentsFirstSection;

    if (empty($section)) {
        die("Error: Selected section is not assigned.");
    }

    if ($_GET['export'] == 'excel') {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'COMPANY NAME');
        $sheet->setCellValue('B1', 'SIS NO.');
        $sheet->setCellValue('C1', 'FULL NAME');
        $sheet->setCellValue('D1', 'STATUS');

        $row = 2;
        foreach ($students as $trainee) {
            $sheet->setCellValue('A' . $row, $trainee['stud_hte'] ?? 'N/A');
            $sheet->setCellValue('B' . $row, $trainee['student_ID'] ?? 'N/A');
            $fullName = trim(($trainee['first_name'] ?? '') . ' ' . ($trainee['middle_name'] ?? '') . ' ' . ($trainee['last_name'] ?? ''));
            $sheet->setCellValue('C' . $row, $fullName ?: 'N/A');
            $sheet->setCellValue('D' . $row, $trainee['ojt_status'] ?? 'N/A');
            $row++;
        }

        $filename = "WorkingStudents_COC_Section_{$section}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    if ($_GET['export'] == 'csv') {
        $filename = "WorkingStudents_COC_Section_{$section}.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['COMPANY NAME', 'SIS NO.', 'FULL NAME', 'STATUS']);

        foreach ($students as $trainee) {
            $fullName = trim(($trainee['first_name'] ?? '') . ' ' . ($trainee['middle_name'] ?? '') . ' ' . ($trainee['last_name'] ?? ''));
            fputcsv($output, [
                $trainee['stud_hte'] ?? 'N/A',
                $trainee['student_ID'] ?? 'N/A',
                $fullName ?: 'N/A',
                $trainee['ojt_status'] ?? 'N/A'
            ]);
        }

        fclose($output);
        exit;
    }

    if ($_GET['export'] == 'pdf') {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator('OJT Web Portal');
        $pdf->SetAuthor('Coordinator');
        $pdf->SetTitle("Working Students COC Section {$section}");
        $pdf->SetSubject('Trainee Data');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 12);

        $pdf->Cell(0, 10, "SECTION {$section}", 0, 1, 'C');
        $pdf->Ln(5);

        $html = '<table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr style="background-color:#700000; color:white; text-align:center;">
                            <th width="20%">COMPANY NAME</th>
                            <th width="20%">SIS NO.</th>
                            <th width="40%">FULL NAME</th>
                            <th width="20%">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($students as $trainee) {
            $fullName = trim(($trainee['first_name'] ?? '') . ' ' . ($trainee['middle_name'] ?? '') . ' ' . ($trainee['last_name'] ?? ''));
            $html .= '<tr>
                        <td width="20%">' . htmlspecialchars($trainee['stud_hte'] ?? 'N/A') . '</td>
                        <td width="20%" style="text-align:center;">' . htmlspecialchars($trainee['student_ID'] ?? 'N/A') . '</td>
                        <td width="40%">' . htmlspecialchars($fullName ?: 'N/A') . '</td>
                        <td width="20%">' . htmlspecialchars($trainee['ojt_status'] ?? 'N/A') . '</td>
                      </tr>';
        }

        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $filename = "WorkingStudents_COC_Section_{$section}.pdf";
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
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
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
        .trainee-table th[colspan="6"] { 
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
        }
        .dl-btn-cont {
            display: flex; 
            justify-content: space-between;
            gap: 10px;
        }
        .section-container {
            margin-bottom: 40px;
        }

        .back-btn { 
            border: none;
            margin: 10px 10px 25px 0px;
            font-size: 20px;
            background-color: #fff;
        }
        .back-btn:hover { color: black; }
        fieldset {
            border: 2px solid #8B0000;
            border-radius: 1.5rem;
            padding: 10px 20px;
            margin: 10px 20px;
            flex: 2;
        }
    </style>
</head>
<body>
    <?php require_once 'templates/coordinators_navbar.php'; ?>
    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div class="back-btn" onclick="backBtn()">
            <i class="fa-solid fa-arrow-left"></i> Back
            </div>
            <div class="page-header">
                <div class="page-title"><br><h1 style="font-size: 16px;"><b>Certificate of Completion (Student)</b></h1><br><br></div>
            </div>

            <!-- First Section -->
            <?php if (!empty($assignedSection)): ?>
                <div class="section-container">
                    <div class="top-function">
                        <div class="dl-btn-cont">
                            <button class="dl-btn" id="dl-btn-first" onclick="toggleDownloadValue('first')">
                                <i class="fa-solid fa-download"></i>
                                Download
                            </button>
                            <div>
                                <button class="hidden-btn" id="cv-btn-first" style="display: none;" onclick="window.location.href='?export=csv§ion=first'"><i class="fa-solid fa-file-csv"></i></button>
                                <button class="hidden-btn" id="ex-btn-first" style="display: none;" onclick="window.location.href='?export=excel§ion=first'"><i class="fa-solid fa-file-excel"></i></button>
                                <button class="hidden-btn" id="pdf-btn-first" style="display: none;" onclick="window.location.href='?export=pdf§ion=first'"><i class="fa-solid fa-file-pdf"></i></button>
                            </div> 
                        </div>
                        <div class="search-cont">
                            <input type="text" class="search-box" placeholder="Search trainee..." id="traineeSearchFirst">
                            <button class="search-btn" data-table="first">Search</button>
                        </div>
                    </div>
                    <br><br>
                    <div class="table-container">
                        <table class="trainee-table" id="table-first">
                            <thead>
                                <tr>
                                    <th colspan="6">SECTION <?php echo htmlspecialchars($assignedSection); ?></th>
                                </tr>
                                <tr>
                                    <th>REF ID.</th>
                                    <th>COMPANY NAME</th>
                                    <th>SIS NO.</th>
                                    <th>FULL NAME</th>
                                    <th>STATUS</th>
                                    <th>VIEW EVALUATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($studentsFirstSection)): ?>
                                    <?php foreach ($studentsFirstSection as $trainee): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($trainee['id'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($trainee['stud_hte'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($trainee['student_ID'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($trainee['first_name'] ?? ''); ?>
                                                <?php echo htmlspecialchars($trainee['middle_name'] ?? ''); ?>
                                                <?php echo htmlspecialchars($trainee['last_name'] ?? ''); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($trainee['ojt_status'] ?? 'N/A'); ?></td>
                                            <td><button class="view-profile">View</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center;">No students with submitted evaluations found in section <?php echo htmlspecialchars($assignedSection); ?>.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <br><br>
            <!-- Second Section -->
            <?php if (!empty($secondAssignedSection)): ?>
                <div class="section-container">
                    <div class="top-function">
                        <div class="dl-btn-cont">
                            <button class="dl-btn" id="dl-btn-second" onclick="toggleDownloadValue('second')">
                                <i class="fa-solid fa-download"></i>
                                Download
                            </button>
                            <div>
                                <button class="hidden-btn" id="cv-btn-second" style="display: none;" onclick="window.location.href='?export=csv§ion=second'"><i class="fa-solid fa-file-csv"></i></button>
                                <button class="hidden-btn" id="ex-btn-second" style="display: none;" onclick="window.location.href='?export=excel§ion=second'"><i class="fa-solid fa-file-excel"></i></button>
                                <button class="hidden-btn" id="pdf-btn-second" style="display: none;" onclick="window.location.href='?export=pdf§ion=second'"><i class="fa-solid fa-file-pdf"></i></button>
                            </div> 
                        </div>
                        <div class="search-cont">
                            <input type="text" class="search-box" placeholder="Search trainee..." id="traineeSearchSecond">
                            <button class="search-btn" data-table="second">Search</button>
                        </div>
                    </div>
                    <br><br>
                    <div class="table-container">
                        <table class="trainee-table" id="table-second">
                            <thead>
                                <tr>
                                    <th colspan="6">SECTION <?php echo htmlspecialchars($secondAssignedSection); ?></th>
                                </tr>
                                <tr>
                                    <th>REF ID.</th>
                                    <th>COMPANY NAME</th>
                                    <th>SIS NO.</th>
                                    <th>FULL NAME</th>
                                    <th>STATUS</th>
                                    <th>VIEW EVALUATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($studentsSecondSection)): ?>
                                    <?php foreach ($studentsSecondSection as $trainee): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($trainee['id'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($trainee['stud_hte'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($trainee['student_ID'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($trainee['first_name'] ?? ''); ?>
                                                <?php echo htmlspecialchars($trainee['middle_name'] ?? ''); ?>
                                                <?php echo htmlspecialchars($trainee['last_name'] ?? ''); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($trainee['ojt_status'] ?? 'N/A'); ?></td>
                                            <td><button class="view-profile">View</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center;">No students with submitted evaluations found in section <?php echo htmlspecialchars($secondAssignedSection); ?>.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>

    <script>
        function backBtn() {
            window.history.back();
        }

        $(document).ready(function() {
            $('.search-btn').on('click', function() {
                var tableId = $(this).data('table');
                var searchTerm = $('#traineeSearch' + tableId.charAt(0).toUpperCase() + tableId.slice(1)).val().toLowerCase();
                $('#table-' + tableId + ' tbody tr').each(function() {
                    var fullName = $(this).find('td:eq(3)').text().toLowerCase();
                    if (fullName.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('.search-box').on('input', function() {
                var tableId = $(this).attr('id').includes('First') ? 'first' : 'second';
                if ($(this).val() === '') {
                    $('#table-' + tableId + ' tbody tr').show();
                }
            });
        });

        let dlValueFirst = false;
        let dlValueSecond = false;

        function toggleDownloadValue(section) {
            const dlValue = section === 'first' ? dlValueFirst : dlValueSecond;
            const newValue = !dlValue;
            if (section === 'first') {
                dlValueFirst = newValue;
            } else {
                dlValueSecond = newValue;
            }

            const cvDl = document.getElementById("cv-btn-" + section);
            const exDl = document.getElementById("ex-btn-" + section);
            const pdfDl = document.getElementById("pdf-btn-" + section);

            cvDl.style.display = newValue ? "inline-block" : "none";
            exDl.style.display = newValue ? "inline-block" : "none";
            pdfDl.style.display = newValue ? "inline-block" : "none";
        }

        $(".view-profile").on("click", function() {
            var studID = $(this).closest("tr").find("td:nth-child(1)").text().trim();
            window.location.href = "coc_evaluation.php?id=" + encodeURIComponent(studID);
        });
    </script>
</body>
</html>