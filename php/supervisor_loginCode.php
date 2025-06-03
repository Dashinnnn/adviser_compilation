<?php 
	

include '../connection/config.php';
session_start();
error_reporting(0);
		
		if(isset($_POST['LogIn']))
		{
            
            
			$email = $_POST['studEmail'];
			$password = md5($_POST['studPword']);
			
			$stmt = $conn->prepare("SELECT * FROM supervisor WHERE supervisor_email=? AND supervisor_password=? ");
			$stmt->execute([$email, $password]);
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$supervisor_id = $data['id'];
						$supervisor_UNIQUEid = $data['uniqueID'];
						$supervisor_company = $data['company_name'];
                        $pword = $data['supervisor_password'];
						$verifystatus = $data['verify_status'];
					}

					$_SESSION['auth'] = true;
					$_SESSION['auth_user'] = [

						'supervisor_id' => $supervisor_id,
						'supervisor_uniqueID' => $supervisor_UNIQUEid,
						'supervisor_company' => $supervisor_company,
					];


			         if($pword == $password)
			         {

			         	if ($verifystatus == 'Not Verified') {

                            $_SESSION['alert'] = "Account Verification";
							$_SESSION['status'] = "Verify your Account";
							$_SESSION['status-code'] = "info";
							header("location: ../supervisor/supervisor_verify_account.php?id=$supervisor_id");
							}else{

							date_default_timezone_set('Asia/Manila');
							$date = date('F / d l / Y');
							$time = date('g:i A');
							$logs = 'You successfully logged in to your account.';
							$online_offline_status = 'Online';

							$sql = $conn->prepare("INSERT INTO supervisor_system_notification(supervisor_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
            				$sql->execute([$supervisor_id, $logs, $date, $time]);

							$sql2 = $conn->prepare("UPDATE supervisor SET online_offlineStatus = ? WHERE id = ?");
            				$sql2->execute([$online_offline_status, $supervisor_id]);


                            $_SESSION['alert'] = "Success";
							$_SESSION['status'] = "Log In Success";
							$_SESSION['status-code'] = "success";
							header("location: ../supervisor/dashboard.php");
				        
				       
			        	 }
			         }else{           
                            $_SESSION['alert'] = "Oppss...";
	        				$_SESSION['status'] = "Incorrect Log In Details";
							$_SESSION['status-code'] = "info";
		        			header("location: ../supervisor/index.php");
	        }
			        
				
		}

?>
