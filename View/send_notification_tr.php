<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
date_default_timezone_set('Asia/Kolkata');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
$myDB = new mysql();
$data_tr_avl = $myDB->query('call get_allTrainer()');
echo mysql_error();
function dt_diff($d1 ,$d2)
{

$datetime1 = new DateTime($d1);

$datetime2 = new DateTime($d2);

$difference = $datetime1->diff($datetime2);

return($difference->d);
 
}

if(count($data_tr_avl) > 0)
{
	foreach($data_tr_avl as $exp_key=>$exp_val)
	{
		$checkExists = 'select createdby from tr_daily_rpt where createdby  = "'.$exp_val['status_training']['Trainer'].'" and batchid='.$exp_val['status_training']['BatchID'];
		
		$myDB = new mysql();
		$checkRst =$myDB->query($checkExists);
		if(count($checkRst) <= 0 || empty($checkRst))
		{
					$myDB = new mysql();
					$gender_f = $myDB->query("call getGender('".$exp_val['status_training']['Trainer']."')");
					$gender_m = $gender_f[0]['personal_details']['Gender'];
					if(strtoupper($gender_m) == 'FEMALE')
				 	{
						$gender_last = 'Mrs.';
					}
					else
					{
						$gender_last = 'Mr.';
					} 	
					$myDB = new mysql();
					$dataContact = $myDB->query("call get_contact('".$exp_val['status_training']['Trainer']."')");
					$mailID = $dataContact[0]['contact_details']['emailid'];
					
					if(!empty($mailID))
					{
						$emailid=$mailID;
						$mail = new PHPMailer;
						$mail->isSMTP();
						$mail->Host = EMAIL_HOST; 
						$mail->SMTPAuth = EMAIL_AUTH;
						$mail->Username = EMAIL_USER;   
						$mail->Password = EMAIL_PASS;                        
						$mail->SMTPSecure = EMAIL_SMTPSecure;
						$mail->Port = EMAIL_PORT; 
						$mail->setFrom(EMAIL_FROM,'EMS:Cogent EMS ');
						
						//$mail->AddAddress($_POST['emailid']);
						//$mail->AddAddress($emailid);
						$mail->AddAddress('ankit.choudhary@cogenteservices.com');
						//$mail->addCC('sachin.siwach@cogenteservices.com');
						//$mail->addBCC('ankit.choudhary@cogenteservices.com');
						$myDB = new mysql();
						$refID = $myDB->query("select BacthName from batch_master where BacthID =".$exp_val['status_training']['BatchID']);
						$refID_id = $refID[0]['batch_master']['BacthName'];
					
						$mail->Subject = 'Entry miss notification for Batch :<b>'.$refID_id;
						$mail->isHTML(true);
				
						
						$pwd_='<span>Dear '.$gender_last.' '.$gender_f[0]['personal_details']['EmployeeName'].',<br/><br/><span><b>Please maintain your Daily Training log in EMS we not found any entry from a long time.</b></span>.<br /><br/><br/><br/> Thank You</b>.<br/>Regard,<br/> Cogent EMS';
						$mail->Body = $pwd_;
						
						if(!$mail->send())
						 	{
						 		$mymsg .='.Mailer Error:'. $mail->ErrorInfo;
						  	} 
						else
						 {
						    
						    $mymsg .='.Mail Send successfully.';
						 }
					}
		}
		else
		{
			$myDB = new mysql();
			#$test2 = 'call get_calcAtnd_fromDate("'.$data_ah[0][0]['account_head'].'","'.$exp_val['exception']['DateFrom'].'")';
			$_dataExts= $myDB->query('call get_Last_entryofTR("'.$exp_val['status_training']['Trainer'].'","'.$exp_val['status_training']['BatchID'].'")');
			
			#$test1 = 
			if(count($_dataExts[0]['t1']['createdby']) > 0)
			{
					$myDB = new mysql();
					$gender_f = $myDB->query("call getGender('".$exp_val['status_training']['Trainer']."')");
					$gender_m = $gender_f[0]['personal_details']['Gender'];
					if(strtoupper($gender_m) == 'FEMALE')
				 	{
						$gender_last = 'Mrs.';
					}
					else
					{
						$gender_last = 'Mr.';
					} 	
					$myDB = new mysql();
					$dataContact = $myDB->query("call get_contact('".$exp_val['status_training']['Trainer']."')");
					$mailID = $dataContact[0]['contact_details']['emailid'];
					
					if(!empty($mailID))
					{
						$myDB=new mysql();
						$pagename='send_notification_tr';
						$select_email_array=mysql_query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."'");	
						$emailid=$mailID;
						$mail = new PHPMailer;
						$mail->isSMTP();
						$mail->Host = EMAIL_HOST; 
						$mail->SMTPAuth = EMAIL_AUTH;
						$mail->Username = EMAIL_USER;   
						$mail->Password = EMAIL_PASS;                        
						$mail->SMTPSecure = EMAIL_SMTPSecure;
						$mail->Port = EMAIL_PORT; 
						$mail->setFrom(EMAIL_FROM,'EMS:Cogent EMS ');
			
						
						//$mail->AddAddress($_POST['emailid']);
						$mail->AddAddress($emailid);
						
						//$mail->addCC('sachin.siwach@cogenteservices.com');
						if(mysql_num_rows($select_email_array)>0){
							while($email_array=mysql_fetch_array($select_email_array)){
								$email_address=$email_array['email_address'];
								if($email_address!=""){
									$mail->AddAddress($email_address);
								}
								$cc_email=$email_array['ccemail'];
								if($cc_email!=""){
									$mail->addCC($cc_email);
								}
							}
							
						}			
						$myDB = new mysql();
						$refID = $myDB->query("select BacthName from batch_master where BacthID =".$exp_val['status_training']['BatchID']);
						$refID_id = $refID[0]['batch_master']['BacthName'];
					
						$mail->Subject = 'Entry miss notification for Batch :<b>'.$refID_id;
						$mail->isHTML(true);
						$pwd_='<span>Dear '.$gender_last.' '.$gender_f[0]['personal_details']['EmployeeName'].',<br/><br/><span><b>Please maintain your Daily Training log in EMS we not found any entry from a long time.</b></span>.<br /><br/><br/><br/> Thank You</b>.<br/>Regard,<br/> Cogent EMS';
						$mail->Body = $pwd_;
						
						if(!$mail->send())
						 	{
						 		$mymsg .='.Mailer Error:'. $mail->ErrorInfo;
						  	} 
						else
						 {
						    
						    $mymsg .='.Mail Send successfully.';
						 }
					}
			}
		}
		
		
		echo $mymsg;
		
		
	}
}
#call get_exceed_exp_data('CE07147134');

echo '<br /> Run for '.count($data_tr_avl).' Employee';

?>