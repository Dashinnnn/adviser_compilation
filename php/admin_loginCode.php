<?php 
	

include '../connection/config.php';
session_start();
error_reporting(0);
		
		if(isset($_POST['LogIn']))
		{
            
			$email = $_POST['adminEmail'];
			$password = md5($_POST['adminPword']);
			
			$stmt = $conn->prepare("SELECT * FROM admin_account WHERE admin_email=? AND admin_password=? ");
			$stmt->execute([$email, $password]);
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$admin_id = $data['id'];
						$admin_UNIQUEid = $data['uniqueID'];
						$coordinator_course_handled = $data['position'];
                        $pword = $data['admin_password'];
						$verifystatus = $data['verify_status'];
					}

					$_SESSION['auth'] = true;
					$_SESSION['auth_user'] = [

						'admin_id' => $admin_id,
						'admin_uniqueID' => $admin_UNIQUEid,
						'coordinator_courseHANDLED' => $coordinator_course_handled,
					];


			         if($pword == $password)
			         {

			         	if ($verifystatus == 'Not Verified') {

                            $_SESSION['alert'] = "Account Verification";
							$_SESSION['status'] = "Verify your Account";
							$_SESSION['status-code'] = "info";
							header("location: ../adminportal/admin_verify_account.php?id=$admin_id");
							}else{

							date_default_timezone_set('Asia/Manila');
							$date = date('F / d l / Y');
							$time = date('g:i A');
							$logs = 'You successfully logged in to your account.';
							$online_offline_status = 'Online';

							$sql = $conn->prepare("INSERT INTO admin_system_notification(admin_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
            				$sql->execute([$admin_id, $logs, $date, $time]);

							$sql2 = $conn->prepare("UPDATE admin_account SET online_offlineStatus = ? WHERE id = ?");
            				$sql2->execute([$online_offline_status, $admin_id]);


                            $_SESSION['alert'] = "Success";
							$_SESSION['status'] = "Log In Success";
							$_SESSION['status-code'] = "success";
							header("location: ../adminportal/dashboard.php");
				        
				       
			        	 }
			         }else{           
                            $_SESSION['alert'] = "Oppss...";
	        				$_SESSION['status'] = "Incorrect Log In Details";
							$_SESSION['status-code'] = "info";
		        			header("location: ../adminportal/index.php");
	        }
			        
				
		}

?>
