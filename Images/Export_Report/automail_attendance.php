<?php
//http://192.168.202.252/ems/Export_Report/automail_headcount.php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
date_default_timezone_set('Asia/Kolkata');
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}

$myDB=new MysqliDb();
$loc=$myDB->query('call get_locationID()');
if(count($loc) > 0 && $loc)
{  
	foreach($loc as $key=>$val)
	{
		$automail = '';
		if($val['id']=="1")
		{
			$automail = 'AutoEmail_Attendance_Noida';
			
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_Attendance_Meerut';
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_Attendance_Bareilly';
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_Attendance_Vadodara';
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_Attendance_Mangalore';
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_Attendance_Bangalore';
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_Attendance_Bangalore_Flipkart';
		}
		
		
		settimestamp($automail,'Start');
	 $columnNames =array("EmployeeID","EmployeeName","Month","Year","D1","D2","D3","D4","D5","D6","D7","D8","D9","D10","D11","D12","D13","D14","D15","D16","D17","D18","D19","D20","D21","D22","D23","D24","D25","D26","D27","D28","D29","D30","D31","DOJ","Designation","Dept Name","Process","Sub Process","Client","Supervisor","function","DOD","LWD","Employee Status","Employee Stage");
			$myDB=new MysqliDb();
			$date_To = date('M',time()); 
			$date_From= date('Y',time()); 
			//echo 'call sp_get_atnd_Report("CE03070003","'.$date_To.'","'.$date_From.'","ALL","CENTRAL MIS","'.$val['id'].'")';
			$chk_task=$myDB->query('call sp_get_atnd_Report("CE03070003","'.$date_To.'","'.$date_From.'","ALL","CENTRAL MIS","'.$val['id'].'")');
			$my_error= $myDB->getLastError();	
			$table = '';
			$fileName = '';
			if($val['id']=="1")
			{
				$fileName = 'automail_Attendence_Noida.csv';
				
			}
			else if($val['id']=="3")
			{
				$fileName = 'automail_Attendence_Meerut.csv';
			}
			else if($val['id']=="4")
			{
				$fileName = 'automail_Attendence_Bareilly.csv';
			}
			else if($val['id']=="5")
			{
				$fileName = 'automail_Attendence_Vadodara.csv';
			}
			else if($val['id']=="6")
			{
				$fileName = 'automail_Attendence_Mangalore.csv';
			}
			else if($val['id']=="7")
			{
				$fileName = 'automail_Attendence_Bangalore.csv';
			}
			else if($val['id']=="8")
			{
				$fileName = 'automail_Attendence_Bangalore_Flipkart.csv';
			}
			
			if(count($chk_task) > 0 && $chk_task)
			{  
				
				$fp = fopen($fileName, 'w');
    			fputcsv($fp, $columnNames);
				foreach($chk_task as $key=>$value)
				{
					$string=array();
					$myBD_tmp = new MysqliDb();
					$temp_result= $myBD_tmp->query('select rsnofleaving,dol,disposition from exit_emp where EmployeeID  = "'.$value['EmployeeID'].'" order by id desc limit 1');
					if(!empty($temp_result[0]['rsnofleaving']) && $value['emp_status'] == 'InActive' && !empty($temp_result[0]['dol']))
					{
						$i = 1;
						while($i <= 31)
						{
							$date_1 = date('Y-m-d',strtotime($temp_result[0]['dol']));
							$date_2 = date('Y-m-d',strtotime($value['Year'].'/'.$value['Month'].'/'.$i));
							if($date_2 == $date_1)
							{
								if(strtoupper($temp_result[0]['disposition']) == 'RES' || strtoupper($temp_result[0]['rsnofleaving']) == 'RES' )
								{
									$string[]=$value['D'.$i];
								}
								else if(!empty($temp_result[0]['disposition']))
								{
									$string[]=$temp_result[0]['disposition'];
								}
								else
								{
									$string[]=$temp_result[0]['rsnofleaving'];
								}
							}
							else if($date_2 > $date_1)
							{
								if(!empty($temp_result[0]['disposition']))
								{
									$string[]=$temp_result[0]['disposition'];
								}
								else
								{
									$string[]=$temp_result[0]['rsnofleaving'];
								}
							}
							else
							{
								$string[]=$value['D'.$i];
							}
							
							$i++;
						}
						
						
					}
					else
					{
						$i = 1;
						while($i <= 31)
						{
							$string[]=$value['D'.$i];
							$i++;
						}
						
					}
					if(!empty($temp_result[0]['rsnofleaving']) && $value['emp_status'] == 'InActive' && !empty($temp_result[0]['dol']))
					{
						$date_of_leaving=date('Y-m-d',strtotime($temp_result[0]['dol']));
					}
					else
					{
						
						$date_of_leaving="";
					}
				$row1=array($value['EmployeeID'],$value['EmployeeName'],$value['Month'],$value['Year']);
					$new_array=array_merge($row1,$string);
					if($value['DOD']!=NULL && $value['DOD']!=""  && $value['DOD']!="NA" ){
						$dod=date('Y-m-d',strtotime($value['DOD']));
					}else{
						$dod="";
					}
					array_push($new_array,$value['DOJ'],$value['designation'],$value['dept_name'],$value['Process'],$value['sub_process'],$value['clientname'],$value['Supervisor'],$value['function'],$dod,$date_of_leaving,$value['emp_status'],$value['EmployeeLevel']);
					
			fputcsv($fp, $new_array);
				}
			}
			else
			{
				$table="No Data Found  ... ".$my_error."";
			}

settimestamp($automail,'END');
		echo "filesize=".$file_size = filesize($fileName);
		if(file_exists($fileName) && !empty($fileName)){
		$myDB=new MysqliDb();
		$pagename='automail_attendance';
		echo "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'";
		$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'");
		$count= 0;
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = EMAIL_HOST; 
		$mail->SMTPAuth = EMAIL_AUTH;
		$mail->Username = EMAIL_USER;   
		$mail->Password = EMAIL_PASS;                        
		$mail->SMTPSecure = EMAIL_SMTPSecure;
		$mail->Port = EMAIL_PORT; 
		$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
		if(count($select_email_array)>0){
			foreach($select_email_array as $select_email_array_val)
			{
				$email_address=$select_email_array_val['email_address'];
				if($email_address!=""){
					$mail->AddAddress($email_address);
				}
				$cc_email=$select_email_array_val['ccemail'];
				if($cc_email!=""){
					$mail->addCC($cc_email);
				}
			}
			
		}
		
		//$mail->AddAddress('md.masood@cogenteservices.com');
		$mail->AddAttachment($fileName);
		$EMS_CenterName='';
		if($val['id']=="1")
		{
			$EMS_CenterName = 'Noida';
			
		}
		else if($val['id']=="3")
		{
			$EMS_CenterName = 'Meerut';
		}
		else if($val['id']=="4")
		{
			$EMS_CenterName = 'Bareilly';
		}
		else if($val['id']=="5")
		{
			$EMS_CenterName = 'Vadodara';
		}
		else if($val['id']=="6")
		{
			$EMS_CenterName = 'Mangalore';
		}
		else if($val['id']=="7")
		{
			$EMS_CenterName = 'Bangalore';
		}
		else if($val['id']=="8")
		{
			$EMS_CenterName = 'Bangalore Flipkart';
		}
		
		$mail->Subject = 'EMS '.$EMS_CenterName.', Attendance Report ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
			$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Attendance Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
		
		$mail->Body = $pwd_;
		
		$mymsg = '';
		if(!$mail->send())
	 	{
	 		settimestamp($automail,'Email Not Sent');
	 		echo '.Mailer Error:'. $mail->ErrorInfo;
	  	} 
		else
		 {
		    settimestamp($automail,'Email Sent');
		    echo  '.Mail Send successfully.';
		 }
		}
		
	}
}

		

		

?>