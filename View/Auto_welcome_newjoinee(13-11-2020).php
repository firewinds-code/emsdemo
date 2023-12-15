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
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}
settimestamp('Auto_welcome_newjoinee','Start');
			$myDB=new MysqliDb();
			$chk_task=$myDB->query('select EmployeeID,location from whole_details_peremp where cast(DOJ as date)=cast(date_add(now(), interval -1 day) as date) and des_id in (5,7,8,10,13,14,15,16,22);');
		
			//$tablename='whole_details_peremp';
			$my_error= $myDB->getLastError();
			$table = '';
			if(count($chk_task) > 0 && $chk_task)
			{  
				foreach($chk_task as $key=>$value)
				{
					$Joinee_EmpID = $value['EmployeeID'];					
					$Joinee_loc = $value['location'];
					
					$myDB=new MysqliDb();
					$chk_task1=$myDB->query('select t1.EmployeeID,t2.emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location="'.$Joinee_loc.'" and t1.EmployeeID !="'.$Joinee_EmpID.'" and t1.des_id not in (9,12);');
		
					
					$my_error= $myDB->getLastError();
					
					foreach($chk_task1 as $key=>$value)
					{
						sendnotification($Joinee_EmpID,$value['EmployeeID'],$value['emailid']);
					 	
					}
					
					$myDB=new MysqliDb();
					$chk_task2=$myDB->query('select t1.EmployeeID,t2.emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!="'.$Joinee_loc.'" and t1.des_id in (1,5,7,8,10,13,14,15,16,22);');
		
			
					$my_error= $myDB->getLastError();
					
					foreach($chk_task2 as $key=>$value)
					{
						//sendnotification($Joinee_EmpID,$value['EmployeeID'],$value['emailid']);
					 	
					}
					
				}
			}
			
			
			$myDB=new MysqliDb();
			$chk_task=$myDB->query('select EmployeeID,location,client_name from whole_details_peremp where cast(DOJ as date)=cast(date_add(now(), interval -1 day) as date) and des_id in (1,2,3,4,6,11);');
		
			//$tablename='whole_details_peremp';
			$my_error= $myDB->getLastError();
			$table = '';
			if(count($chk_task) > 0 && $chk_task)
			{
				foreach($chk_task as $key=>$value)
				{
					$Joinee_EmpID = $value['EmployeeID'];					
					$Joinee_loc = $value['location'];
					$Joinee_client = $value['client_name'];
					
					$myDB=new MysqliDb();
					$chk_task1=$myDB->query('select t1.EmployeeID,t2.emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location="'.$Joinee_loc.'" and t1.EmployeeID !="'.$Joinee_EmpID.'" and t1.des_id not in (9,12);');
		
			//$tablename='whole_details_peremp';
					$my_error= $myDB->getLastError();
					
					foreach($chk_task1 as $key=>$value)
					{
						//sendnotification($Joinee_EmpID,$value['EmployeeID'],$value['emailid']);
					}
					
					
					$myDB=new MysqliDb();
					$chk_task2=$myDB->query('select t1.EmployeeID,t2.emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!="'.$Joinee_loc.'" and t1.client_name ="'.$Joinee_client.'" and t1.des_id not in (9,12);');
		
			//$tablename='whole_details_peremp';
					$my_error= $myDB->getLastError();
					
					foreach($chk_task2 as $key=>$value)
					{
						//sendnotification($Joinee_EmpID,$value['EmployeeID'],$value['emailid']);
					}
					
				}
			}
			
			settimestamp('Auto_welcome_newjoinee','End');
			
			function sendnotification($Joinee_EmpID,$Sender_EmployeeID,$emailid)
			{
				$myDB=new MysqliDb();
				$rst_contact = $myDB->rawQuery('select t1.EmployeeName, t1.designation,DOJ,Process,t1.Gender,t1.img,ReportTo,t3.EmployeeName as "ReportToName",mobile,emailid,t4.location from whole_details_peremp t1 join contact_details t2 on t1.EmployeeID=t2.EmployeeID inner join personal_details t3 on t3.EmployeeID=t1.ReportTo inner join location_master t4 on t1.location=t4.id where t1.EmployeeID= "'.$Joinee_EmpID.'"');
				 	
							 		
		 		if(!empty($rst_contact[0]['EmployeeName']))
				{
		 			echo $imgsrc = 'https://ems.cogentlab.com/erpm/Images/'. $rst_contact[0]['img'];
		 			if($rst_contact[0]['Gender']=='Female')
		 			{
						$gender = 'She';
						$gender1 = 'her';
					}
					else
					{
						$gender = 'He';
						$gender1 = 'his';
					}
					$empname = $rst_contact[0]['EmployeeName'];
		 			$rst_edu = $myDB->rawQuery('select edu_level,edu_name,board,specialization from education_details where EmployeeID= "'.$Joinee_EmpID.'" order by edu_level desc limit 1');
					if(!empty($rst_edu[0]['edu_level']))
					{
						if($rst_edu[0]['edu_level']=="Basic")
						{
							$higher_edu = '12th';
						}
						else
						{
							$higher_edu = $rst_edu[0]['edu_level'];
							if($higher_edu=='Graduation')
							{
								$higher_edu='Graduate';
							}
							else if($higher_edu=='Post Graduation')
							{
								$higher_edu='Post Graduate';
							}
							
						}
						$university = $rst_edu[0]['board'];
						$degree = $rst_edu[0]['specialization'];
					}
							
					$myDB=new MysqliDb();
					$chk_exp=$myDB->query('select * from experince_details where employer is not null and employer !="" and EmployeeID= "'.$Joinee_EmpID.'"');
					$years = $months = $days = $diff=0;
					$employer = $latest_exp = '';
					//$tablename='whole_details_peremp';
					$my_error= $myDB->getLastError();
					$table = '';
					if(count($chk_exp) > 0 && $chk_exp)
					{  
						foreach($chk_exp as $key=>$value)
						{
						//echo $value['from']. ','.$value['to'].'-';
							$date1 = $value['from'];
							$date2 = $value['to'];

							$diff = $diff + abs(strtotime($date2) - strtotime($date1));
							if($employer !='')
							{
								$dateTimestamp1 = strtotime($latest_exp); 
								$dateTimestamp2 = strtotime($value['to']); 
								if ($dateTimestamp1 > $dateTimestamp2)
								{
										
								}
								else
								{
									$employer = $value['employer'];
									$latest_exp = $value['to'];
								}	
							}
							else
							{
								$employer = $value['employer'];
								$latest_exp = $value['to'];
							}
						
						

						}	
			 		}
			 		else
			 		{
						$exp = 'NA';
					}
					$years = $years + floor($diff / (365*60*60*24));
					$months = $months + floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
					$days = $days + floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
							
					//echo 'Days-'. $days.',Month-'.$months.',Year-'.$years;
					if($years ==0 && $months ==0)
					{
						$exp = 'NA';
						$expdiv = '';
					}
					else
					{
						if($years !=0 && $months !=0)
						{
							$exp = $years.' Years'.' and '.$months.' months'; 
							$expdiv = '<tr><td style="padding-top: 25px;"><b>'.$rst_contact[0]['EmployeeName'].'</b> carry of total work experience of <b>'.$exp.'</b> . '.$gender.' previously worked with <b>'.$employer.'</b>.</td></tr>';
							
							
							
						}
					}
					
					$doj = $rst_contact[0]['DOJ'];
					$time=strtotime($doj);
					$day=date("j",$time);
					$month=date("M",$time);
					$year=date("Y",$time);
					if($day=="1")
					{
						$doj = $day."<sup>st</sup> ".$month.' '.$year;
					}
					else if($day=="2")
					{
						$doj = $day."<sup>nd</sup> ".$month.' '.$year;
					}
					else if($day=="3")
					{
						$doj = $day."<sup>rd</sup> ".$month.' '.$year;
					}
					else 
					{
						$doj = $day."<sup>th</sup> ".$month.' '.$year;
					}
							
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = EMAIL_HOST; 
					$mail->SMTPAuth = EMAIL_AUTH;
					$mail->Username = EMAIL_USER;   
					$mail->Password = EMAIL_PASS;                        
					$mail->SMTPSecure = EMAIL_SMTPSecure;
					$mail->Port = EMAIL_PORT; 
					$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
					//$mail->AddAddress($emailid);
					$mail->AddAddress('md.masood@cogenteservices.com');
					//$emailAddress = $rst_contact[0]['emailid'];
					$mail->Subject = 'Welcome Aboard';
					$mail->isHTML(true);
					$body_='<html>
	<body>
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
		<table width="100%">
			<tr>
				<td style="width:60%;padding-left:6%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style=" border-left: dashed  #ccc 2px;border-top: dashed  #ccc 2px;border-bottom: dashed  #ccc 2px;border-right: dashed  #ccc 2px;"><img src="https://ems.cogentlab.com/erpm/Style/images/cogent-logobkp1.png" style="margin-left: 54px;"/><span style="font-family: Verdana; font-size: 30px;padding-left: 100px;">Introducing</span> <br/><br/><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family: Verdana; font-size: 36px; font-weight: bold;padding-left: 80px;">'.$rst_contact[0]['EmployeeName'].'</span></span></div></td>
				
				
				<td style="width: 40%; " align="center"><img src="https://ems.cogentlab.com/erpm/Style/images/Picture3.png"/></td>
						
			</tr>
			
			<tr style="background-color: #fafafa;">
				<td width="60%" style="padding-left: 50px;">
					<table style="width: 100%; text-align: left;font-family: Verdana; font-size: 20px; padding-top: 25px;">
						<tr>
							<td>
								We are please to introduce <b>'.$rst_contact[0]['EmployeeName'].'</b> who has joined us as <b>'.$rst_contact[0]['designation'].'</b> on <b>';
								
								$body_.=$doj;
								$body_.='
								</b>.
							</td>
						</tr>
						<tr>
							<td style="padding-top: 25px;">
								'.$gender.' is a <b>'.$higher_edu.'</b> from <b>'.$university.'('.$degree.')</b>.
							</td>
						</tr>';
						
						$body_.=$expdiv;
						
						$body_.='<tr>
							<td style="padding-top: 25px;">
								<b>'.$rst_contact[0]['EmployeeName'].'</b> will work with <b>'.$rst_contact[0]['Process'].'</b> and report to <b>'.$rst_contact[0]['ReportToName'].'</b> at <b>'.$rst_contact[0]['location'].'</b>. '.$gender.' can be reached at <b>'.$rst_contact[0]['mobile'].'</b>

							</td>
						</tr>	
						<tr>
							<td style="padding-top: 25px;">
								We are confident that <b>'.$rst_contact[0]['EmployeeName'].'</b> will fulfill '.$gender1.' role to the best of '.$gender1.' ability to maintain our standard of care and delivery

							</td>
						</tr>
						<tr>
							<td style="padding-top: 25px;">
								Welcome aboard <b>'.$rst_contact[0]['EmployeeName'].'</b>!
							</td>
						</tr>
					</table>
				</td>
				<td align="left" width="30%" style="padding-top: 25px;"><img src='.$imgsrc.' class="imgcss" width="250"/></td>
				
			</tr>
			
		</table>
	</body>
</html>';
		
				$mail->Body = $body_;
				if(!$mail->send())
			 	{
			 		
			 		echo $emailStatus = 'Mailer Error:'. $mail->ErrorInfo;
			  	} 
				else
				{
					
				   echo  $emailStatus =  'Mail Send successfully.';
				}
				
				$myDB=new MysqliDb();
				echo 'insert into welcome_msg_smsmail_newjoinee set employeeid="'.$Sender_EmployeeID.'",EmailAddress="'.addslashes($emailid).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server"';
				$sms_status = $myDB->rawQuery('insert into welcome_msg_smsmail_newjoinee set employeeid="'.$Sender_EmployeeID.'",EmailAddress="'.addslashes($emailid).'",emailStatus="'.addslashes($emailStatus).'" ,createdBy= "Server"');
		 
			}   
					
				
			
			}		
				 	
					 
			    	
	
	

?>

