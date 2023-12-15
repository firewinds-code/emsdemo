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
settimestamp('Auto_msg_onfloor','Start');
			$myDB=new MysqliDb();
			$chk_task=$myDB->query('select t1.EmployeeID, EmployeeName,Gender,t2.mobile from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID left join tbl_client_toclient_move t3 on t1.EmployeeID=t3.EmployeeID where cast(OnFloor as date)=cast(date_add(now(), interval -1 day) as date) and t3.EmployeeID is null;');
		
			//$tablename='whole_details_peremp';
			$my_error= $myDB->getLastError();
			$table = '';
			if(count($chk_task) > 0 && $chk_task)
			{  
				foreach($chk_task as $key=>$value)
				{
											
					if(!empty($value['mobile']))
					{
						$gender='';
						if($value['Gender']=='Male')
						{
							$gender = 'Mr.';
						}
						else if($value['Gender']=='FeMale')
						{
							$gender = 'Mrs.';
						}
						$TEMPLATEID='1707161526667431517';
						//$msg ="Hi ".$value['EmployeeName'].", Your appointment letter is available in EMS. Now you can download the same. Thanks!";
						$msg ="Hi ".$value['EmployeeName'].", Your appointment letter is available in EMS. Now you can download the same. Thanks! - Cogent E Services";
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
						/*$myDB=new MysqliDb();
						$sms_status = $myDB->rawQuery('insert into ncns_sms set employeeid="'.$empid.'", smsstatus="'.addslashes($response).'", createdBy="'.$_SESSION['__user_logid'].'"');*/
				
					}
			 		
				 		/*if(!empty($rst_contact[0]['emailid']))
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
							$mail->AddAddress($rst_contact[0]['emailid']);
							$emailAddress = $rst_contact[0]['emailid'];
							$mail->Subject = 'EMS login missed - reminder';
							$mail->isHTML(true);
							$body_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear '.$rst_contact[0]['EmployeeName'].',</b></span><br /><br/> you have not logged-in your EMS since(Date: '.date('d/m/Y',strtotime("-2 days")).'). Kindly login EMS daily to check your roster & attendance correctness. <div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
		
							$mail->Body = $body_;
							if(!$mail->send())
						 	{
						 		//settimestamp('Login_NCNS','Email Not Sent');
						 		$emailStatus = 'Mailer Error:'. $mail->ErrorInfo;
						  	} 
							else
							{
								//settimestamp('Login_NCNS','Email Sent');
							    $emailStatus =  'Mail Send successfully.';
							}
		 
						}  */
					
						$myDB=new MysqliDb();
						//echo 'insert into login_ncns_smsmail set employeeid="'.$value['EmployeeID'].'", smsstatus="'.addslashes($response).'",sms_text="'.addslashes($msg).'",EmailAddress="'.addslashes($emailAddress).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server" ';
						$sms_status = $myDB->rawQuery('insert into onfloor_smsmail set employeeid="'.$value['EmployeeID'].'", smsstatus="'.addslashes($response).'",sms_text="'.addslashes($msg).'" ,createdBy= "Server",mobile="'.$value['mobile'].'" ');
					
				 	
					
				}
			}		
	
	settimestamp('Auto_msg_onfloor','End');
