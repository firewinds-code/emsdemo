<?php

require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
$mymsg='';
            $myDB = new MysqliDb();
			 $dataContact = $myDB->query("call get_contact('".$emp_id."')");
			 $mailID = $dataContact[0]['emailid'];//id
			if(true)
			{
				$myDB=new MysqliDb();
				$pagename='add_issue';
				
				
				
				 $select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$location."'");	
				$emailid=$mailID;
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = EMAIL_HOST; 
				$mail->SMTPAuth = EMAIL_AUTH;
				$mail->Username = EMAIL_USER;   
				$mail->Password = EMAIL_PASS;                        
				$mail->SMTPSecure = EMAIL_SMTPSecure;
				$mail->Port = EMAIL_PORT; 
				$mail->setFrom(EMAIL_FROM,  'EMS:Cogent Grievance System');
				if(count($select_email_array) > 0 && $select_email_array){
				foreach($select_email_array as $Key=>$val)
		        	{
		        		$email_address = $val['email_address'];
						
						if($email_address!=""){
							$mail->AddAddress($email_address);
						}
						$cc_email=$val['ccemail'];
						
						if($cc_email!=""){
							$mail->addCC($cc_email);
						}			
							
						
					}
									
				}			
				$myDB = new MysqliDb();
				 $refID = $myDB->query("select max(id) as id from issue_tracker  where requestby ='".$emp_id."' and cast(request_date as date)= curdate() limit 1;");//id of that request is insert into issurtracker table
				$refID_id = $refID[0]['id'];
				$EMS_CenterName = "";
				//$mail->Subject = 'Happy to help '.EMS_CenterName.', Reference #'.$refID_id;
				
				if($location=="1")
				{
					$EMS_CenterName = "Noida";
				}
				else if($location=="2")
				{
					$EMS_CenterName = "Mumbai";
				}
				else if($location=="3")
				{
					$EMS_CenterName = "Meerut";
				}
				else if($location=="4")
				{
					$EMS_CenterName = "Bareilly";
				}
				else if($location=="5")
				{
					$EMS_CenterName = "Vadodara";
				}
				else if($location=="6")
				{
					$EMS_CenterName = "Mangalore";
				}
				else if($location=="7")
				{
					$EMS_CenterName = "Bangalore";
				}
				else if($location=="8")
				{
					$EMS_CenterName = "Bangalore Flipkart";
				}
				
				$mail->Subject = 'Happy to help '.$EMS_CenterName.', Reference #'.$refID_id;
				
				$mail->isHTML(true);
				$myDB = new MysqliDb();
				$info_emp = $myDB->query('call get_info_for_Issue_tracker("'.$emp_id.'")');
				$error = $myDB->getLastError();
		//echo $query;
				if(empty($error))
				{
				
				 $pwd_='<span>Dear Sir,<br/><br/><span><b>Please find below the concern raised in happy to help.</b></span>.<br /><br/> <b>Concern Subject: '.$issue.'</b>.<br /><br /><b>Concern:</b> '.$remark.'.<br/><br/><br/> Thank You</b>.<br/>Regard,<br/>'.strtoupper($employeeName).'(<b>&nbsp;'.$emp_id.'&nbsp;</b>)<br/><b>Designation  &nbsp;:&nbsp;</b>'.strtoupper($designation).'<br/><b>Process &nbsp;:&nbsp;</b>'.$info_emp[0]['Process'].'&nbsp;(&nbsp;'.$info_emp[0]['sub_process'].'&nbsp;)<br /><b>Account Head &nbsp;:&nbsp;</b>'.$info_emp[0]['AccountHead'].'<br /><b>Report To &nbsp;:&nbsp;</b>'.$info_emp[0]['ReportTo'].'<br />';
				$mail->Body = $pwd_;
				}
				if(!$mail->send())
				 	{
				 		$mymsg .='.Mailer Error:'. $mail->ErrorInfo;
				 		$module='Happy to Help : Add Issue';
				 		$error_message=$mail->ErrorInfo;
						$error_log_add="call Add_email_error_log('".$module."','".$error_message."','".$emp_id."')";
						$myDB = new MysqliDb();
						$myDB->query($error_log_add);
				
				  	} 
				else
				 {
				    	$mymsg .='.Mail Send successfully.';
				   		$module='Happy to Help : Add Issue';
				 		$error_message="email sent successfully";
					 	$error_log_add="call Add_email_error_log('".$module."','".$error_message."','".$emp_id."')";
						$myDB = new MysqliDb();
						$myDB->query($error_log_add);
				 }
			}
?>