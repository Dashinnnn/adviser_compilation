<?php 
	

include '../connection/config.php';
session_start();
error_reporting(0);
		
		if(isset($_POST['LogIn']))
		{
			$email = $_POST['studEmail'];
			$password = md5($_POST['studPword']);
			
			$stmt = $conn->prepare("SELECT * FROM students_data WHERE stud_email=? AND stud_password=? ");
			$stmt->execute([$email, $password]);
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$stud_id = $data['id'];
						$stud_UNIQUEid = $data['uniqueID'];
						$stud_course = $data['stud_course'];
                        $pword = $data['stud_password'];
						$verifystatus = $data['verify_status'];
					}

					$_SESSION['auth'] = true;
					$_SESSION['auth_user'] = [

						'student_id' => $stud_id,
						'student_uniqueID' => $stud_UNIQUEid,
						'student_course' => $stud_course,
					];


			         if($pword == $password)
			         {

			         	if ($verifystatus == 'Not Verified') {

                            $_SESSION['alert'] = "Account Verification";
							$_SESSION['status'] = "Verify your Account";
							$_SESSION['status-code'] = "info";
							header("location: ../student/student_verify_account.php?id=$stud_id");
							}else{

							date_default_timezone_set('Asia/Manila');
							$date = date('F / d l / Y');
							$time = date('g:i A');
							$logs = 'You successfully logged in to your account.';
							$online_offline_status = 'Online';

							$sql = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
            				$sql->execute([$stud_id, $logs, $date, $time]);

							$sql2 = $conn->prepare("UPDATE students_data SET online_offlineStatus = ? WHERE id = ?");
            				$sql2->execute([$online_offline_status, $stud_id]);


                            $_SESSION['alert'] = "Success";
							$_SESSION['status'] = "Log In Success";
							$_SESSION['status-code'] = "success";
							header("location: ../student/dashboard.php");
				        
				       
			        	 }
			         }else{           
                            $_SESSION['alert'] = "Oppss...";
	        				$_SESSION['status'] = "Incorrect Log In Details";
							$_SESSION['status-code'] = "info";
		        			header("location: ../student/index.php");
	        }
			        
				
		}

?>
