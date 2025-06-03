<?php 
	

include '../connection/config.php';
session_start();
error_reporting(0);
		
		if(isset($_POST['LogIn']))
		{
            
            
			$email = $_POST['studEmail'];
			$password = md5($_POST['studPword']);
			
			$stmt = $conn->prepare("SELECT * FROM coordinators_account WHERE coordinators_email=? AND coordinators_password=? ");
			$stmt->execute([$email, $password]);
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$coordinators_id = $data['id'];
						$coordinators_UNIQUEid = $data['uniqueID'];
						$coordinator_course_handled = $data['course_handled'];
                        $pword = $data['coordinators_password'];
						$verifystatus = $data['verify_status'];
					}

					$_SESSION['auth'] = true;
					$_SESSION['auth_user'] = [

						'coordinators_id' => $coordinators_id,
						'coordinator_uniqueID' => $coordinators_UNIQUEid,
						'coordinator_courseHANDLED' => $coordinator_course_handled,
					];


			         if($pword == $password)
			         {

			         	if ($verifystatus == 'Not Verified') {

                            $_SESSION['alert'] = "Account Verification";
							$_SESSION['status'] = "Verify your Account";
							$_SESSION['status-code'] = "info";
							header("location: ../coordinator/coordinator_verify_account.php?id=$coordinators_id");
							}else{

							date_default_timezone_set('Asia/Manila');
							$date = date('F / d l / Y');
							$time = date('g:i A');
							$logs = 'You successfully logged in to your account.';
							$online_offline_status = 'Online';

							$sql = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
            				$sql->execute([$coordinators_id, $logs, $date, $time]);

							$sql2 = $conn->prepare("UPDATE coordinators_account SET online_offlineStatus = ? WHERE id = ?");
            				$sql2->execute([$online_offline_status, $coordinators_id]);


                            $_SESSION['alert'] = "Success";
							$_SESSION['status'] = "Log In Success";
							$_SESSION['status-code'] = "success";
							header("location: ../coordinator/dashboard.php");
				        
				       
			        	 }
			         }else{           
                            $_SESSION['alert'] = "Oppss...";
	        				$_SESSION['status'] = "Incorrect Log In Details";
							$_SESSION['status-code'] = "info";
		        			header("location: ../coordinator/index.php");
	        }
			        
				
		}

?>
