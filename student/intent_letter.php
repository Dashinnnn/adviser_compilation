<?php
include '../connection/config.php';

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['auth_user']['student_id']) || $_SESSION['auth_user']['student_id']==0){
    echo"<script>window.location.href='index.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Find your H.T.E</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/Picture1.png">
    <!-- Styles -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <style>
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .instructions {
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .documents-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 100px;
            margin-bottom: 30px;
            width: 100%;
        }
        
        .document-card {
            background-color: #8B0000;
            border-radius: 8px;
            width: 250px;
            height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .document-card:hover {
            transform: translateY(-5px);
        }
        
        .document-icon {
            width: 100px;
            height: 120px;
            margin-bottom: 15px;
            background-color: white;
            padding: 5px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .document-icon img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .document-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .document-subtitle {
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        .icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        
        .icon-large {
            font-size: 40px;
            color: #8B0000;
        }
        
        /* Updated Modal Styles to match the provided image */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 50px auto;
            width: 90%;
            max-width: 850px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            border: 1px solid #ddd;
        }
        
        .modal-header {
            background-color: #8B0000;
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            position: relative;
            color: white;
            padding-left: 80px;
        }
        
        .modal-header h2 {
            color: white;
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }
        
        .modal-body {
            padding: 30px;
            display: flex;
            gap: 20px;
        }
        
        .document-preview {
            flex: 1;
            background-color: #f5f5f5;
            border-radius: 5px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            text-align: center;
        }
        
        .no-document-message {
            color: #888;
            font-style: italic;
        }
        
        .action-buttons {
            width: 200px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .btn {
            padding: 12px 15px;
            border-radius: 5px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            width: 100%;
        }
        
        .btn i, .btn svg {
            margin-right: 10px;
        }
        
        .btn-upload {
            background-color: #8B0000;
            color: white;
        }
        
        .btn-upload:hover {
            background-color: #700000;
        }
        
        .btn-secondary {
            background-color: #ccc;
            color: #333;
        }
        
        .btn-secondary:hover {
            background-color: #bbb;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .back-button {
            display: flex;
            align-items: center;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .back-button:hover {
            color: #eee;
            text-decoration: none;
        }
        
        .back-button i {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <!---------NAVIGATION BAR-------->
    <?php require_once 'templates/stud_navbar.php'; ?>
    <!---------NAVIGATION BAR ENDS-------->

    <div class="content-wrap" style="height: 80%; width: 100%;margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div>
                <div>
                    <div>
                        <div class="page-header">
                            <div class="page-title"><br>
                                <h1 style="font-size: 16px;"><b>ENDORSEMENT PROCESS</b></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-wrap">
    
                <div class="instructions">
                    <p>Download the necessary templates first. After filling in all the information, upload it as a Word document.</p>
                    <p>Note: Prioritize the medical process to get a schedule as soon as possible if you are having your medical exam at ITECH.</p>
                </div>
                
                <div class="documents-container">
                    <!-- First row -->
                    <div class="document-card" onclick="showModal('Memorandum of Agreement')">
                        <div class="document-icon">
                            <img src="/api/placeholder/80/100" alt="MOA Document">
                        </div>
                        <div class="document-title">Memorandum of Agreement</div>
                        <div class="document-subtitle">(MOA)</div>
                    </div>
                    
                    <div class="document-card" onclick="showModal('Internship Agreement')">
                        <div class="document-icon">
                            <img src="/api/placeholder/80/100" alt="Internship Agreement">
                        </div>
                        <div class="document-title">Internship Agreement</div>
                    </div>
                    
                    <div class="document-card" onclick="showModal('Consent Form')">
                        <div class="document-icon">
                            <img src="/api/placeholder/80/100" alt="Consent Form">
                        </div>
                        <div class="document-title">Consent Form</div>
                    </div>
                    
                    <!-- Second row -->
                    <div class="document-card" onclick="showModal('Intent Letter')">
                        <div class="document-icon">
                            <img src="/api/placeholder/80/100" alt="Intent Letter">
                        </div>
                        <div class="document-title">Intent Letter</div>
                    </div>
                    
                    <div class="document-card" onclick="showModal('Medical Certificate')">
                        <div class="document-icon">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#8B0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="12" y1="18" x2="12" y2="12"></line>
                                    <line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="document-title">Medical Certificate</div>
                    </div>
                    
                    <div class="document-card" onclick="showModal('Resume')">
                        <div class="document-icon">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#8B0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <circle cx="12" cy="13" r="3"></circle>
                                    <path d="M7 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="document-title">Resume</div>
                    </div>
                    
                    <!-- Third row -->
                    <div class="document-card" onclick="showModal('Non-Disclosure Agreement')">
                        <div class="document-icon">
                            <div class="icon-container">
                                <div style="font-size: 40px; font-weight: bold; color: #8B0000;">NDA</div>
                            </div>
                        </div>
                        <div class="document-title">Non-Disclosure Agreement</div>
                    </div>
                    
                    <div class="document-card" onclick="showModal('Insurance')">
                        <div class="document-icon">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#8B0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="17" y1="11" x2="23" y2="11"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="document-title">Insurance</div>
                    </div>
                </div>
                
                <!-- Updated Modal to match the image -->
                <div id="documentModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <a href="#" class="back-button" onclick="closeModal()">
                                <i class="fa fa-chevron-left"></i> Back
                            </a>
                            <h2 id="modalTitle">Memorandum of Agreement</h2>
                        </div>
                        
                        <div class="modal-body">
                            <div id="documentPreview" class="document-preview">
                                <p class="no-document-message">No uploads to show</p>
                            </div>
                            
                            <div class="action-buttons">
                                <button class="btn btn-upload" id="uploadButton" onclick="uploadDocument()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                    Upload
                                </button>
                                
                                <button id="viewButton" class="btn btn-secondary" onclick="viewDocument()" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    View
                                </button>
                                
                                <button id="editButton" class="btn btn-secondary" onclick="editDocument()" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                    </svg>
                                    Edit file
                                </button>
                                
                                <button id="printButton" class="btn btn-secondary" onclick="printDocument()" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                        <rect x="6" y="14" width="12" height="8"></rect>
                                    </svg>
                                    Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>
    
    <script>
        // Get the modal element
        const modal = document.getElementById("documentModal");
        
        // Global variables
        let currentDocument = "";
        let documentUploaded = false;
        const viewButton = document.getElementById("viewButton");
        const editButton = document.getElementById("editButton");
        const printButton = document.getElementById("printButton");
        
        // Function to show modal
        function showModal(documentName) {
            currentDocument = documentName;
            document.getElementById("modalTitle").textContent = documentName;
            
            // Check if document is already uploaded
            checkDocumentStatus(documentName);
            
            // Display the modal
            modal.style.display = "block";
        }
        
        // Function to close modal
        function closeModal() {
            modal.style.display = "none";
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
        
        // Function to check document status
        function checkDocumentStatus(documentName) {
            // Use AJAX to check if the document exists in the database
            $.ajax({
                url: 'check_document.php',
                type: 'POST',
                data: { 
                    document_name: documentName, 
                    student_id: <?php echo $_SESSION['auth_user']['student_id']; ?> 
                },
                success: function(response) {
                    try {
                        const result = JSON.parse(response);
                        documentUploaded = result.exists;
                        
                        // Update UI based on document status
                        viewButton.disabled = !documentUploaded;
                        editButton.disabled = !documentUploaded;
                        printButton.disabled = !documentUploaded;
                        
                        // Update preview area
                        document.getElementById("documentPreview").innerHTML = 
                            documentUploaded ? 
                            `<img src="images/document-preview.png" alt="Document Preview" style="max-width: 100%; max-height: 100%;">` :
                            `<p class="no-document-message">No uploads to show</p>`;
                    } catch (e) {
                        console.error("Error parsing JSON response:", e);
                        swal("Error!", "Failed to check document status.", "error");
                    }
                },
                error: function() {
                    swal("Error!", "Failed to check document status.", "error");
                }
            });
        }
        
        // Upload document function
        function uploadDocument() {
            // Create file input
            const fileInput = document.createElement("input");
            fileInput.type = "file";
            fileInput.accept = ".doc,.docx,.pdf";
            
            // Trigger file browser
            fileInput.click();
            
            // Handle file selection
            fileInput.onchange = function() {
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    
                    // Create FormData object
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('document_name', currentDocument);
                    formData.append('student_id', <?php echo $_SESSION['auth_user']['student_id']; ?>);
                    
                    // Send file to server using AJAX
                    $.ajax({
                        url: 'upload_document.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            try {
                                const result = JSON.parse(response);
                                if (result.status === 'success') {
                                    swal("Success!", "Document uploaded successfully.", "success");
                                    documentUploaded = true;
                                    
                                    // Update UI
                                    viewButton.disabled = false;
                                    editButton.disabled = false;
                                    printButton.disabled = false;
                                    
                                    // Update preview
                                    document.getElementById("documentPreview").innerHTML = 
                                        `<img src="images/document-preview.png" alt="Document Preview" style="max-width: 100%; max-height: 100%;">`;
                                } else {
                                    swal("Error!", result.message, "error");
                                }
                            } catch (e) {
                                console.error("Error parsing JSON response:", e);
                                swal("Error!", "Failed to upload document.", "error");
                            }
                        },
                        error: function() {
                            swal("Error!", "Failed to upload document.", "error");
                        }
                    });
                }
            };
        }
        
        // View document function
        function viewDocument() {
            if (!documentUploaded) return;
            
            // Redirect to document view page
            window.open(`view_document.php?document=${encodeURIComponent(currentDocument)}&student_id=<?php echo $_SESSION['auth_user']['student_id']; ?>`, '_blank');
        }
        
        // Edit document function
        function editDocument() {
            if (!documentUploaded) return;
            
            // Download the document for editing
            window.location.href = `download_document.php?document=${encodeURIComponent(currentDocument)}&student_id=<?php echo $_SESSION['auth_user']['student_id']; ?>&mode=edit`;
        }
        
        // Print document function
        function printDocument() {
            if (!documentUploaded) return;
            
            // Open print dialog
            window.open(`print_document.php?document=${encodeURIComponent(currentDocument)}&student_id=<?php echo $_SESSION['auth_user']['student_id']; ?>`, '_blank');
        }
    </script>

    <?php 
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
        <script>
        sweetAlert("<?php echo $_SESSION['alert']; ?>", "<?php echo $_SESSION['status']; ?>", "<?php echo $_SESSION['status-code']; ?>");
        </script>
    <?php
    unset($_SESSION['status']);
    }
    ?>

</body>
</html>