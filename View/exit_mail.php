<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
#require(ROOT_PATH.'AppCode/nHead.php');
include_once(__dir__.'/../Services/sendsms_API.php');

error_reporting(E_ALL);
ini_set('display_errors', 0);

require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';


$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;
$ResultSMS = $emailStatus = $msg = $response = $emailAddress = '';

			$myDB=new MysqliDb();
			$chk_task=$myDB->query('select "CMK07194077" as EmployeeID');
		
			//$tablename='whole_details_peremp';
			$my_error= $myDB->getLastError();
			$table = '';
			if(count($chk_task) > 0 && $chk_task)
			{  
				foreach($chk_task as $key=>$value)
				{
					
						$myDB=new MysqliDb();
						$rst_contact = $myDB->rawQuery('select t2.EmployeeName, mobile, emailid,t4.gender,t4.img,DATE_FORMAT(t3.dol,"%d %b %Y")as `dol`,t2.account_head,t2.ReportTo,t2.location from contact_details t1 join whole_dump_emp_data t2 on t1.EmployeeID=t2.EmployeeID inner join exit_emp t3 on t1.EmployeeID=t3.EmployeeID inner join personal_details t4 on t1.EmployeeID=t4.EmployeeID where t2.EmployeeID= "'.$value['EmployeeID'].'" limit 1');
									 		
				 		if(!empty($rst_contact[0]['EmployeeName']))
				 		{
				 			$dir_loc="";
				 			if($rst_contact[0]['location']=="3")
				 			{
								$dir_loc="Meerut/";
							}
							else if($rst_contact[0]['location']=="4")
				 			{
								$dir_loc="Bareilly/";
							}
							else if($rst_contact[0]['location']=="5")
				 			{
								$dir_loc="Vadodara/";
							}
							else if($rst_contact[0]['location']=="6")
				 			{
								$dir_loc="Manglore/";
							}
							else if($rst_contact[0]['location']=="7")
				 			{
								$dir_loc="Bangalore/";
							}
							if(trim($rst_contact[0]['img'])!='')
				 			{
								//$imgsrc = '../Images/'. $rst_contact[0]['img'];
								$imgsrc = '../'.$dir_loc.'Images/'. $rst_contact[0]['img'];
								//echo $imgsrc = 'https://ems.cogentlab.com/erpm/'.$dir_loc.'Images/'. $rst_contact[0]['img'].'<br/>';
								if(file_exists($imgsrc))
								{
									//$imgsrc = 'https://ems.cogentlab.com/erpm/Images/'. $rst_contact[0]['img'];
									$imgsrc = 'https://ems.cogentlab.com/erpm/'.$dir_loc.'Images/'. $rst_contact[0]['img'];
								}
								else
								{
									$imgsrc = 'https://ems.cogentlab.com/erpm/Images/profile_logo.jpg';
								}
							}
							else
							{
								$imgsrc = 'https://ems.cogentlab.com/erpm/Images/profile_logo.jpg';
							}
							
				 			//echo $imgsrc;
				 			$reportto='';
				 			$reporttoname='NA';
				 			if($rst_contact[0]['account_head'] == $value['EmployeeID'])
				 			{
								$reportto=$rst_contact[0]['ReportTo'];
							}
							else
							{
								$reportto=$rst_contact[0]['account_head'];
							}
							
							$myDB=new MysqliDb();
							$rst_report = $myDB->rawQuery('select EmployeeName from personal_details where EmployeeID= "'.$reportto.'" ');
							if(!empty($rst_report[0]['EmployeeName']))
							{
								$reporttoname = $rst_report[0]['EmployeeName'];
							}
				 			$gender='He';
							$mail = new PHPMailer;
							$mail->isSMTP();
							$mail->Host = EMAIL_HOST; 
							$mail->SMTPAuth = EMAIL_AUTH;
							$mail->Username = EMAIL_USER;   
							$mail->Password = EMAIL_PASS;                        
							$mail->SMTPSecure = EMAIL_SMTPSecure;
							$mail->Port = EMAIL_PORT; 
							$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
							//$mail->AddAddress($rst_contact[0]['emailid']);
							$mail->AddAddress('md.masood@cogenteservices.com');
							$emailAddress = $rst_contact[0]['emailid'];
							$mail->Subject = 'Employee Exit Announcement';
							$mail->isHTML(true);
							if($rst_contact[0]['gender']=='Male')
							{
								$gender='He';
							}
							else
							{
								$gender='She';
							}
							$body_='<html>
							<head>
	<style>
		.imgcss
		{
			display: block;
    width: 310px !important;
    max-width: 100% !important;
		}
	</style>
</head>
	<body>
		<table width="100%">
			<tr>
				<td style="width: 50%; padding-left:0 px; vertical-align: top; padding-top: 10px;"><img src="https://ems.cogentlab.com/erpm/Style/images/exit_header_img1.png"/></td>
				
				<td rowspan="2" style="width: 50%; "><img src="https://ems.cogentlab.com/erpm/Style/images/exit_header_img2.png"/></td>
				
			</tr>
			<tr>
				<td style="text-align: left; padding-left: 256px;font-family: Verdana; font-size: 35px; font-weight: bold;" >'.$rst_contact[0]['EmployeeName'].'</td>
				
			</tr>
			
			<tr>
				<td width="70%" style="padding-left: 50px; padding-right: 50px;">
					<table style="width: 100%; text-align: left;font-family: Verdana; font-size: 17px;">
						<tr>
							<td>
								Dear Employees,
							</td>
						</tr>
						<tr>
							<td style="padding-top: 25px;">
								This is to notify that <b>'.$rst_contact[0]['EmployeeName'].'</b> is moving out of the company, effective <b>'.$rst_contact[0]['dol'].'</b>. '.$gender.' has decided to leave because of better opportunity. 
							</td>
						</tr>
						<tr>
							<td style="padding-top: 25px;">
								As of <b>'.$rst_contact[0]['dol'].'</b>, please direct all department related questions to <b>'.$reporttoname.'</b> until we are able to secure a replacement.
							</td>
						</tr>
						
						<tr>
							<td style="padding-top: 25px;">
								We wish <b>'.$rst_contact[0]['EmployeeName'].'</b> good luck for his/ her future.


							</td>
						</tr>	
						
					</table>
				</td>
				<td align="left" width="30%" style="padding-top: 25px;"><img src='.$imgsrc.' class="imgcss" width="250"/></td>
				
			</tr>
			
		</table>
	</body>
</html>';
		echo $body_;
							$mail->Body = $body_;
							/*if(!$mail->send())
						 	{
						 		
						 		echo $emailStatus = 'Mailer Error:'. $mail->ErrorInfo;
						  	} 
							else
							{
								
							   echo  $emailStatus =  'Mail Send successfully.';
							}*/
		 
						}  
					
						/*$myDB=new MysqliDb();
							echo 'insert into exit_mail set employeeid="'.$EmployeeID.'",EmailAddress="'.addslashes($emailid).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server"';
							$sms_status = $myDB->rawQuery('insert into exit_mail set employeeid="'.$EmployeeID.'",EmailAddress="'.addslashes($emailid).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server"');*/
					
				 	
					
				}
			}		
	
	

?>

