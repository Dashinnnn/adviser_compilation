<?php
include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['auth_user']['student_id']) || $_SESSION['auth_user']['student_id'] == 0) {
    echo "<script>window.location.href='index.php'</script>";
    exit;
}

// Handle file upload
if (isset($_POST['submit'])) {
    $student_id = $_SESSION['auth_user']['student_id'];
    $target_dir = "uploads/moa/";
    
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = basename($_FILES["moaDocument"]["name"]);
    $target_file = $target_dir . $student_id . "_" . $file_name;
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if file already exists and delete if it does
    if (file_exists($target_file)) {
        unlink($target_file);
    }
    
    // Check file size (limit to 10MB)
    if ($_FILES["moaDocument"]["size"] > 10000000) {
        $_SESSION['status'] = "Sorry, your file is too large.";
        $_SESSION['alert'] = "Warning";
        $_SESSION['status-code'] = "warning";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    $allowed_types = array("jpg", "jpeg", "png", "pdf", "doc", "docx");
    if (!in_array($fileType, $allowed_types)) {
        $_SESSION['status'] = "Sorry, only JPG, JPEG, PNG, PDF, DOC & DOCX files are allowed.";
        $_SESSION['alert'] = "Warning";
        $_SESSION['status-code'] = "warning";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["moaDocument"]["tmp_name"], $target_file)) {
            try {
                // Check for existing record
                $checkSql = "SELECT id FROM endorsement_documents WHERE student_id = :student_id AND document_type = 'MOA'";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $checkStmt->execute();
                
                if ($checkStmt->rowCount() > 0) {
                    $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
                    $sql = "UPDATE endorsement_documents 
                            SET document_name = :document_name, 
                                uploaded_path = :uploaded_path, 
                                upload_date = NOW(),
                                status = 'pending' 
                            WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':document_name', $file_name, PDO::PARAM_STR);
                    $stmt->bindParam(':uploaded_path', $target_file, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
                } else {
                    $sql = "INSERT INTO endorsement_documents (student_id, document_name, document_type, placeholder_path, uploaded_path, upload_date, status) 
                            VALUES (:student_id, :document_name, :document_type, '', :uploaded_path, NOW(), 'pending')";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                    $stmt->bindParam(':document_name', $file_name, PDO::PARAM_STR);
                    $stmt->bindValue(':document_type', 'MOA', PDO::PARAM_STR);
                    $stmt->bindParam(':uploaded_path', $target_file, PDO::PARAM_STR);
                }

                if ($stmt->execute()) {
                    $_SESSION['status'] = "The file has been uploaded successfully.";
                    $_SESSION['alert'] = "Success";
                    $_SESSION['status-code'] = "success";
                } else {
                    $_SESSION['status'] = "Error updating database.";
                    $_SESSION['alert'] = "Error";
                    $_SESSION['status-code'] = "error";
                }
            } catch (PDOException $e) {
                $_SESSION['status'] = "Database error: " . $e->getMessage();
                $_SESSION['alert'] = "Error";
                $_SESSION['status-code'] = "error";
            }
        } else {
            $_SESSION['status'] = "Error: File upload failed.";
            $_SESSION['alert'] = "Error";
            $_SESSION['status-code'] = "error";
        }
    }
    echo "<script>window.location.href='moa.php'</script>";
    exit;
}

// Check if user has an existing document
try {
    $sql = "SELECT * FROM endorsement_documents WHERE student_id = :student_id AND document_type = 'MOA'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $existing_document = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['status'] = "Database error: " . $e->getMessage();
    $_SESSION['alert'] = "Error";
    $_SESSION['status-code'] = "error";
    $existing_document = null;
}

// Set document status
$document_status = "";
$status_class = "";
if ($existing_document) {
    $status = $existing_document['status'] ?? 'pending';
    switch ($status) {
        case 'accepted':
            $document_status = "Your Memorandum of Agreement has been accepted.";
            $status_class = "status-accepted";
            break;
        case 'denied':
            $document_status = "Your Memorandum of Agreement has been denied. Please resubmit.";
            $status_class = "status-denied";
            break;
        default:
            $document_status = "Your Memorandum of Agreement has been submitted and is under review.";
            $status_class = "status-pending";
            break;
    }
}
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>OJT Web Portal: Student Profile</title>
        <!-- ================= Favicon ================== -->
        <link rel="shortcut icon" href="images/Picture1.png">

        <!-- Common -->
        <link href="css/lib/font-awesome.min.css" rel="stylesheet">
        <link href="css/lib/themify-icons.css" rel="stylesheet">
        <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
        <link href="css/lib/bootstrap.min.css" rel="stylesheet">
        <link href="css/lib/helper.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
        <link href="endorsement-css/endorsement-moa.css" rel="stylesheet">

    </head>


    <body>
        <!---------NAVIGATION BAR-------->
        <?php require_once 'templates/stud_navbar.php'; ?>
        <!---------NAVIGATION BAR ENDS-------->

        <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto; position: relative;">
            <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
                <div>
                    <div>
                        <div>
                            <a href="endorsement.php" class="back-button">
                                <span class="back-icon"><img src="images/less-than.png" alt="Back"></span>
                                Back
                            </a>
                        </div>

                        <div>
                            <h1 class="moa-title">Memorandum of Agreement Document Template</h1>
                        </div>

                        <div class="template-box">
                            <a href="templates/endorsement/New_MOA_Template.docx" download="New_MOA_Template.docs" class="btn1-download-template"><img src="images/doc.png" alt="" class="doc-icon"><u>New_MOA_Template.docx</u></a>
                            <a href="templates/endorsement/New_MOA_Template.docx" download="New_MOA_Template.docx" class="btn2-download-template btn-download">
                                <i class="fa fa-download"></i>
                            </a>
                        </div>
                        
                        <div>
                            <h1 class="title">Memorandum of Agreement</h1>
                        </div>

                        <div class="box">
                            <div class="content">
                                <div class="document-area" id="documentArea">
                                <?php if ($existing_document): ?>
                                <?php 
                                $file_ext = strtolower(pathinfo($existing_document['uploaded_path'], PATHINFO_EXTENSION));
                                $cache_buster = '?t=' . time(); 
                                if (in_array($file_ext, ['jpg', 'jpeg', 'png'])): 
                                ?>
                                    <img id="documentImage" src="<?php echo $existing_document['uploaded_path'] . $cache_buster; ?>" alt="Document Image">
                                <?php elseif (in_array($file_ext, ['pdf', 'doc', 'docx'])): ?>
                                    <div class="file-icon"><i class="fa fa-file-<?php echo ($file_ext == 'pdf') ? 'pdf' : 'text'; ?>-o"></i></div>
                                    <div class="embed-container">
                                        <?php if ($file_ext == 'pdf'): ?>
                                            <embed src="<?php echo $existing_document['uploaded_path'] . $cache_buster; ?>" type="application/pdf">
                                        <?php else: ?>
                                            <div>Document preview not available. Click View to open.</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div id="fileName" class="file-name"><?php echo $existing_document['document_name']; ?></div>
                                    <?php else: ?>
                                        <div id="placeholderText">No uploads to show</div>
                                        <img id="documentImage" class="hidden">
                                        <div id="fileName" class="file-name hidden"></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="action-buttons">
                                    <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
                                        <input type="file" id="fileInput" name="moaDocument" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                                    
                                        <?php if ($existing_document): ?>
                                            <!-- Buttons shown when a document exists -->
                                            <button type="button" class="btn btn-actions action" id="viewBtn">
                                                <i class="fa fa-eye btn-icon"></i> View
                                            </button>
                                            <button type="button" class="btn btn-actions action" id="editBtn">
                                                <i class="fa fa-edit btn-icon"></i> Edit file
                                            </button>
                                            <button type="button" class="btn btn-actions action" id="printBtn">
                                                <i class="fa fa-print btn-icon"></i> Print
                                            </button>
                                            
                                            <!-- Submit button disabled for existing documents if status is accepted -->
                                            <?php if (isset($existing_document['status']) && $existing_document['status'] == 'accepted'): ?>
                                            <button type="submit" name="submit" class="btn btn-submit action disabled" id="submitBtn" disabled>
                                                <i class="fa fa-paper-plane btn-icon"></i> Submit
                                            </button>
                                            <?php else: ?>
                                            <button type="submit" name="submit" class="btn btn-submit action" id="submitBtn">
                                                <i class="fa fa-paper-plane btn-icon"></i> Submit
                                            </button>
                                            <?php endif; ?>

                                            
                                            
                                            <!-- Status notification -->
                                            <?php if (!empty($document_status)): ?>
                                            <div class="status-notification <?php echo $status_class; ?>" id="statusNotification" style="display: block;">
                                                <span class="close-btn" id="closeStatus">×</span>
                                                <?php echo $document_status; ?>
                                            </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <!-- Upload button and disabled buttons when no document exists -->
                                            <button type="button" class="btn btn-upload action" id="uploadBtn">
                                                <i class="fa fa-upload btn-icon"></i> Upload
                                            </button>
                                            <button type="button" class="btn btn-disabled" id="viewBtn" disabled>
                                                View
                                            </button>
                                            <button type="button" class="btn btn-disabled" id="editBtn" disabled>
                                                Edit file
                                            </button>
                                            <button type="button" class="btn btn-disabled" id="printBtn" disabled>
                                                Print
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');
            const viewBtn = document.getElementById('viewBtn');
            const editBtn = document.getElementById('editBtn');
            const printBtn = document.getElementById('printBtn');
            const documentImage = document.getElementById('documentImage');
            const placeholderText = document.getElementById('placeholderText');
            const fileName = document.getElementById('fileName');
            const uploadForm = document.getElementById('uploadForm');
            const uploadBtn = document.getElementById('uploadBtn');
            const documentArea = document.getElementById('documentArea');
            const closeStatusBtn = document.getElementById('closeStatus');
            
            // Check if file was already submitted
            const fileAlreadySubmitted = <?php echo isset($existing_document) && $existing_document ? 'true' : 'false'; ?>;
            
            // Check document status
            const documentStatus = '<?php echo isset($existing_document["status"]) ? $existing_document["status"] : ""; ?>';
            
            // Close status notification when X is clicked
            if (closeStatusBtn) {
                closeStatusBtn.addEventListener('click', function() {
                    const statusNotification = document.getElementById('statusNotification');
                    if (statusNotification) {
                        statusNotification.style.display = 'none';
                    }
                });
            }
            
            // Apply custom styling to remove scrollbars and icons
            const styleElement = document.createElement('style');
            styleElement.textContent = `
                /* Your original styling JavaScript here */
            `;
            document.head.appendChild(styleElement);
            
            // Upload button click handler (if it exists)
            if (uploadBtn) {
                uploadBtn.addEventListener('click', function() {
                    fileInput.click();
                });
            }

            // File input change handler
            fileInput.addEventListener('change', function(event) {
                if (event.target.files.length > 0) {
                    const file = event.target.files[0];

                    // Update UI to show file is selected
                    if (placeholderText) {
                        placeholderText.classList.add('hidden');
                    }

                    fileName.textContent = file.name;
                    fileName.classList.remove('hidden');

                    // Enable buttons
                    viewBtn.removeAttribute('disabled');
                    viewBtn.classList.remove('btn-disabled');
                    viewBtn.innerHTML = '<i class="fa fa-eye btn-icon"></i> View';

                    editBtn.removeAttribute('disabled');
                    editBtn.classList.remove('btn-disabled');
                    editBtn.innerHTML = '<i class="fa fa-edit btn-icon"></i> Edit file';

                    printBtn.removeAttribute('disabled');
                    printBtn.classList.remove('btn-disabled');
                    printBtn.innerHTML = '<i class="fa fa-print btn-icon"></i> Print';

                    // Create a submit button if it doesn't exist
                    let submitBtn = document.getElementById('submitBtn');
                    if (!submitBtn) {
                        submitBtn = document.createElement('button');
                        submitBtn.className = 'btn btn-submit action';
                        submitBtn.id = 'submitBtn';
                        submitBtn.name = 'submit';
                        submitBtn.type = 'submit';
                        submitBtn.innerHTML = '<i class="fa fa-paper-plane btn-icon"></i> Submit';
                        uploadForm.appendChild(submitBtn);
                    } else {
                        // If submit button exists, enable it for new uploads (unless status is accepted)
                        if (documentStatus !== 'accepted') {
                            submitBtn.removeAttribute('disabled');
                            submitBtn.classList.remove('disabled');
                        }
                    }

                    // Display preview if it's an image
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Clear previous content
                            clearPreviewArea();
                            
                            // Display the image preview
                            documentImage.src = e.target.result;
                            documentImage.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        // For PDF files
                        documentImage.classList.add('hidden');
                        
                        // Clear previous content
                        clearPreviewArea();
                        
                        // Create a clean PDF preview if browser supports it
                        if (window.FileReader && window.Blob) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const pdfObject = document.createElement('object');
                                pdfObject.type = 'application/pdf';
                                pdfObject.data = e.target.result;
                                pdfObject.className = 'clean-pdf-preview';
                                pdfObject.style.width = '100%';
                                pdfObject.style.height = '500px';
                                pdfObject.style.border = 'none';
                                
                                // Clear any existing preview and append the new one
                                clearPreviewArea();
                                documentArea.insertBefore(pdfObject, fileName);
                            };
                            reader.readAsDataURL(file);
                        }
                    } else {
                        // For other file types
                        documentImage.classList.add('hidden');
                        clearPreviewArea();
                        // Just show the filename, no preview
                    }
                }
            });
            
            // Helper function to clear preview area
            function clearPreviewArea() {
                // Remove any existing content
                const embedContainer = document.querySelector('.embed-container');
                if (embedContainer) {
                    embedContainer.remove();
                }
                
                const fileIconElement = document.querySelector('.file-icon');
                if (fileIconElement) {
                    fileIconElement.remove();
                }
                
                // Remove any PDF objects/embeds
                const pdfObjects = documentArea.querySelectorAll('object, embed, iframe.pdf-viewer, .clean-pdf-preview');
                pdfObjects.forEach(obj => obj.remove());
                
                // Remove any document viewer container
                const viewerContainer = document.querySelector('.document-viewer-container');
                if (viewerContainer) {
                    viewerContainer.remove();
                }
            }

            // Helper function to create embedded document viewer
            function createEmbeddedViewer(parentElement) {
                // Clear any existing viewer
                const existingViewer = document.querySelector('.document-viewer-container');
                if (existingViewer) {
                    existingViewer.remove();
                }
                
                // Create viewer container
                const viewerContainer = document.createElement('div');
                viewerContainer.className = 'document-viewer-container';
                
                // Create close button
                const closeBtn = document.createElement('button');
                closeBtn.className = 'close-viewer';
                closeBtn.innerHTML = '&times;';
                closeBtn.onclick = function() {
                    viewerContainer.remove();
                };
                
                // Create viewer element
                const viewer = document.createElement('div');
                viewer.className = 'document-viewer';
                
                // Add elements to container
                viewerContainer.appendChild(closeBtn);
                viewerContainer.appendChild(viewer);
                
                // Add to parent element (the box)
                parentElement.appendChild(viewerContainer);
                
                return {
                    container: viewerContainer,
                    viewer: viewer
                };
            }

            // View button click handler - now shows document in the box
            viewBtn.addEventListener('click', function() {
                // Get parent box element - either documentArea or its parent
                const boxElement = documentArea.closest('.box') || documentArea;
                
                // Create embedded viewer
                const { container, viewer } = createEmbeddedViewer(boxElement);
                
                <?php if ($existing_document): ?>
                    const filePath = "<?php echo $existing_document['uploaded_path']; ?>";
                    const fileExt = filePath.split('.').pop().toLowerCase();
                    const fileName = "<?php echo $existing_document['document_name']; ?>";
                    
                    if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                        // Image files
                        const imgElement = document.createElement('img');
                        imgElement.src = `${filePath}?t=<?php echo time(); ?>`;
                        imgElement.className = 'document-image-view';
                        
                        viewer.appendChild(imgElement);
                    } else if (fileExt === 'pdf') {
                        // PDF files
                        const objElement = document.createElement('object');
                        objElement.data = `${filePath}?t=<?php echo time(); ?>`;
                        objElement.type = 'application/pdf';
                        objElement.width = '100%';
                        objElement.height = '100%';
                        
                        viewer.appendChild(objElement);
                    } else {
                        // Other file types
                        sweetAlert("Notice", "This file type may not be viewable in browser.", "info");
                        container.remove();
                    }
                <?php else: ?>
                    if (fileInput.files.length > 0) {
                        const file = fileInput.files[0];
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const imgElement = document.createElement('img');
                                imgElement.src = e.target.result;
                                imgElement.className = 'document-image-view';
                                
                                viewer.appendChild(imgElement);
                            };
                            reader.readAsDataURL(file);
                        } else if (file.type === 'application/pdf') {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const objElement = document.createElement('object');
                                objElement.data = e.target.result;
                                objElement.type = 'application/pdf';
                                objElement.width = '100%';
                                objElement.height = '100%';
                                
                                viewer.appendChild(objElement);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // Other file types
                            sweetAlert("Notice", "This file type may not be viewable in browser.", "info");
                            container.remove();
                        }
                    } else {
                        sweetAlert("Notice", "Please upload a file first to view it.", "info");
                        container.remove();
                    }
                <?php endif; ?>
            });

            // Edit button click handler - Triggers new file selection
            editBtn.addEventListener('click', function() {
                // If document is accepted, show warning before allowing edit
                if (documentStatus === 'accepted') {
                    sweetAlert({
                        title: "Warning",
                        text: "This document has already been accepted. Editing will require resubmission and review. Continue?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, edit file",
                        closeOnConfirm: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            fileInput.click();
                        }
                    });
                } else {
                    fileInput.click();
                }
            });

            // Print button click handler - clean print
            printBtn.addEventListener('click', function() {
                <?php if ($existing_document): ?>
                    const filePath = "<?php echo $existing_document['uploaded_path']; ?>";
                    const fileExt = filePath.split('.').pop().toLowerCase();
                    
                    if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                        // Image files
                        const printWindow = window.open('', '_blank');
                        printWindow.document.write(`
                            <!DOCTYPE html>
                            <html>
                            <head>
                                <title>Print: <?php echo $existing_document['document_name']; ?></title>
                                <style>
                                    body { margin: 0; padding: 0; }
                                    img { max-width: 100%; }
                                </style>
                            </head>
                            <body>
                                <img src="${filePath}?t=<?php echo time(); ?>">
                            </body>
                            </html>
                        `);
                        printWindow.document.close();
                        
                        // Delay printing slightly to ensure content is loaded
                        setTimeout(function() {
                            printWindow.print();
                            // printWindow.close();
                        }, 500);
                    } else if (fileExt === 'pdf') {
                        // PDF files
                        const printWindow = window.open(filePath, '_blank');
                        printWindow.addEventListener('load', function() {
                            setTimeout(function() {
                                printWindow.print();
                            }, 500);
                        });
                    } else {
                        // Other file types
                        window.open(filePath, '_blank');
                    }
                <?php else: ?>
                    if (fileInput.files.length > 0) {
                        const file = fileInput.files[0];
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const printWindow = window.open('', '_blank');
                                printWindow.document.write(`
                                    <!DOCTYPE html>
                                    <html>
                                    <head>
                                        <title>Print: ${file.name}</title>
                                        <style>
                                            body { margin: 0; padding: 0; }
                                            img { max-width: 100%; }
                                        </style>
                                    </head>
                                    <body>
                                        <img src="${e.target.result}">
                                    </body>
                                    </html>
                                `);
                                printWindow.document.close();
                                printWindow.focus();
                                
                                // Delay printing slightly to ensure content is loaded
                                setTimeout(function() {
                                    printWindow.print();
                                }, 500);
                            };
                            reader.readAsDataURL(file);
                        } else if (file.type === 'application/pdf') {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const printWindow = window.open('', '_blank');
                                printWindow.document.write(`
                                    <!DOCTYPE html>
                                    <html>
                                    <head>
                                        <title>Print: ${file.name}</title>
                                        <style>
                                            body, html { margin: 0; padding: 0; height: 100%; }
                                            object { width: 100%; height: 100%; }
                                        </style>
                                    </head>
                                    <body>
                                        <object data="${e.target.result}" type="application/pdf"></object>
                                    </body>
                                    </html>
                                `);
                                printWindow.document.close();
                                printWindow.focus();
                                
                                // Delay printing slightly to ensure content is loaded
                                setTimeout(function() {
                                    printWindow.print();
                                }, 500);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            sweetAlert("Notice", "Please upload the file first to print it.", "info");
                        }
                    } else {
                        sweetAlert("Notice", "Please upload a file first to print it.", "info");
                    }
                <?php endif; ?>
            });
            
            // Initialize button states and setup clean PDF display for existing documents
            if (fileAlreadySubmitted) {
                // Enable all buttons for existing document
                viewBtn.removeAttribute('disabled');
                viewBtn.classList.remove('btn-disabled');
                viewBtn.classList.add('btn-actions');
                
                editBtn.removeAttribute('disabled');
                editBtn.classList.remove('btn-disabled');
                editBtn.classList.add('btn-actions');
                
                printBtn.removeAttribute('disabled');
                printBtn.classList.remove('btn-disabled');
                printBtn.classList.add('btn-actions');
                
                // Disable submit button if document is accepted
                const existingSubmitBtn = document.getElementById('submitBtn');
                if (existingSubmitBtn && documentStatus === 'accepted') {
                    existingSubmitBtn.setAttribute('disabled', 'disabled');
                    existingSubmitBtn.classList.add('disabled');
                }
                
                // Clean up existing PDF display if this is a PDF
                <?php if ($existing_document && isset($file_ext) && $file_ext == 'pdf'): ?>
                    // Find any existing PDF display and clean it up
                    const existingPdfEmbed = documentArea.querySelector('embed[type="application/pdf"], object[type="application/pdf"], iframe.pdf-viewer');
                    if (existingPdfEmbed) {
                        // Apply clean styling
                        existingPdfEmbed.style.width = '100%';
                        existingPdfEmbed.style.height = '500px';
                        existingPdfEmbed.style.border = 'none';
                        
                        // Remove any toolbar or icon elements that might be siblings
                        const parentElement = existingPdfEmbed.parentElement;
                        Array.from(parentElement.children).forEach(child => {
                            if (child !== existingPdfEmbed && !child.classList.contains('file-name')) {
                                child.remove();
                            }
                        });
                    }
                <?php endif; ?>
            }
        });
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
