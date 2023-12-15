<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
#require(ROOT_PATH.'AppCode/nHead.php');
include_once(__dir__.'/../Services/sendsms_API1.php');

error_reporting(E_ALL);
ini_set('display_errors', 0);

require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';


$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;
$ResultSMS = $emailStatus = $msg = $response = $emailAddress = '';
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}
settimestamp('Auto_welcome_msg','Start');
			$myDB=new MysqliDb();
			$chk_task=$myDB->query('select t1.EmployeeID,t1.EmployeeName,t2.mobile,t2.emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.EmployeeID="CE01145570";');
		
			//$tablename='whole_details_peremp';
			$my_error= $myDB->getLastError();
			$table = '';
			if(count($chk_task) > 0 && $chk_task)
			{  
				foreach($chk_task as $key=>$value)
				{
					$ncns_count=0;
					
					 
				 	$msg = '';
				 	$ems_url='https://ems.cogentlab.com/erpm/Login';
					if(!empty($value['mobile']))
					{
						$TEMPLATEID='1707161726427920288';
						//$msg ="Dear ".$value['EmployeeName'].", A very warm welcome to Cogent family. Please use ".$ems_url." to sign up and complete your profile. EMS shall be used for attendance, Roster, leaves etc.";
						$var = $value['EmployeeName']."/".$value['EmployeeID'];
						//$var = $value['EmployeeName'].",. This is your Employee ID:- ".$value['EmployeeID'];
						echo $msg ="Dear ".$var.", A very warm welcome to Cogent family. Please use ".$ems_url." to sign up and complete your profile. EMS shall be used for attendance, Roster, leaves etc.";
						$url = SMS_URL;
						$token = SMS_TOKEN;
						$credit = SMS_CREDIT;
						$sender = SMS_SENDER;
						$message = $msg;
						$number = $value['mobile'];
						$sendsms = new sendsms($url,$token);
						$message_id = $sendsms->sendmessage($credit,$sender,$message,$number,$TEMPLATEID);
						$response = $message_id;
						$ResultSMS=$response;
					
						$lbl_msg = ' SMS : '.$response;
										
					}
			 		
			 		if(!empty($value['emailid']))
			 		{
						$mail = new PHPMailer;
						$mail->isSMTP();
						$mail->Host = EMAIL_HOST; 
						$mail->SMTPAuth = EMAIL_AUTH;
						$mail->Username = EMAIL_USER;   
						$mail->Password = EMAIL_PASS;                        
						$mail->SMTPSecure = EMAIL_SMTPSecure;
						$mail->Port = EMAIL_PORT; 
						$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
						$mail->AddAddress($value['emailid']);
						$emailAddress = $value['emailid'];
						$mail->Subject = 'Cogent - Welcome & EMS Signup';
						$mail->isHTML(true);
						$msg='Dear '.$value['EmployeeName'].'<br /><br />A very warm welcome from Cogent family. We look forward to a fruitful 
association with you and help each other grow.<br /><br /> In order to facilitate the day to day operations and assist you with various work related tasks we encourage the use of our in-house tool EMS (Employee Management System). EMS is the centralized tool for facilitating self-help activities, your attendance & roster information, leave & exception management etc.. <br /><br/>This is your Employee ID:- '.$value['EmployeeID'].' <br /><br/>We request you to sign-up and complete your profile on Cogent EMS. EMS can be accessed at '.$url.' <br /><br />In case of any problems please approach the HR/Trainer/Supervisor.';
						//$body_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Hi '.$rst_contact[0]['EmployeeName'].'.</b></span><br /><br/>you have not logged IN your EMS (Date : '.date('d/m/Y',strtotime("-2 days")).') ) kindly login and check your roster and attendance etc regularly to ensure your attendance data is correct. <div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
						
						$body_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear '.$value['EmployeeName'].',</b></span><br /><br/> A very warm welcome from Cogent family. We look forward to a fruitful association with you and help each other grow. <br/><br/> In order to facilitate the day to day operations and assist you with various work related tasks we encourage the use of our in-house tool EMS (Employee Management System). EMS is the centralized tool for facilitating self-help activities, your attendance & roster information, leave & exception management etc.. <br/><br/> This is your Employee ID:- '.$value['EmployeeID'].' <br /><br/>We request you to sign-up and complete your profile on Cogent EMS. EMS can be accessed at '.$ems_url.' <br/><br/> In case of any problems please approach the HR/Trainer/Supervisor. <div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
		
						$mail->Body = $body_;
						if(!$mail->send())
					 	{
					 		//settimestamp('Auto_welcome_msg','Email Not Sent');
					 		$emailStatus = 'Mailer Error:'. $mail->ErrorInfo;
					  	} 
						else
						{
							//settimestamp('Auto_welcome_msg','Email Sent');
						    $emailStatus =  'Mail Send successfully.';
						}
	 
					}  
					
					$myDB=new MysqliDb();
					//echo 'insert into welcome_msg_smsmail set employeeid="'.$value['EmployeeID'].'", smsstatus="'.addslashes($response).'",sms_text="'.addslashes($msg).'",EmailAddress="'.addslashes($emailAddress).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server" ';
					$sms_status = $myDB->rawQuery('insert into welcome_msg_smsmail set employeeid="'.$value['EmployeeID'].'", smsstatus="'.addslashes($response).'",sms_text="'.addslashes($msg).'",EmailAddress="'.addslashes($emailAddress).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server",mobile="'.$value['mobile'].'" ');
					 
			    }
			}		
	
	settimestamp('Auto_welcome_msg','End');

?>

