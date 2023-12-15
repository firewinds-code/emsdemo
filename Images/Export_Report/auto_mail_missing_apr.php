<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
require_once(LIB.'PHPExcel/IOFactory.php');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
		$automail = '';$location='';
		if($val['id']=="1")
		{
			$automail = 'AutoEmail_missingAPR_Noida';
			$location = "1,2";
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_missingAPR_Meerut';
			$location = "3";
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_missingAPR_Bareilly';
			$location = "4";
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_missingAPR_Vadodara';
			$location = "5";
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_missingAPR_Mangalore';
			$location = "6";
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_missingAPR_Bangalore';
			$location = "7";
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_missingAPR_Bangalore_Flipkart';
			$location = "8";
		}
				
		
		settimestamp($automail,'Start');
	  	$objPHPExcel = new PHPExcel();
	    $objPHPExcel->getActiveSheet()->setCellValue('A1',"EmployeeID");
	    $objPHPExcel->getActiveSheet()->setCellValue('B1',"Date");
	    $objPHPExcel->getActiveSheet()->setCellValue('C1',"Attendance");
		$objPHPExcel->getActiveSheet()->setCellValue('D1',"InTime");
		$objPHPExcel->getActiveSheet()->setCellValue('E1',"OutTime");
		$objPHPExcel->getActiveSheet()->setCellValue('F1',"Roster InTime");
		$objPHPExcel->getActiveSheet()->setCellValue('G1',"Roster OutTime");
		$objPHPExcel->getActiveSheet()->setCellValue('H1',"APR");
		$rowcount = 2;
			
			//$date_To = date('M',time()); 
			$DateTo = date('Y-m-d',strtotime("yesterday"));
			 echo $str="select t1.EmployeeID, t1.D".intval(date('d',strtotime($DateTo)))." as Attendance,t2.InTime,t2.OutTime,t3.InTime as RInTime ,t3.OutTime as ROutTime,t4.D".intval(date('d',strtotime($DateTo)))." apr from calc_atnd_master t1 left outer join bioinout t2 on t1.EmployeeID=t2.EmpID left outer join roster_temp t3 on t1.EmployeeID=t3.EmployeeID left outer join hours_hlp t4 on t1.EmployeeID=t4.EmployeeID inner join whole_dump_emp_data t5 on t1.EmployeeID=t5.EmployeeID where t1.Month=".intval(date('m',strtotime($DateTo)))." and t1.year=".intval(date('Y',strtotime($DateTo)))." and (t1.D".intval(date('d',strtotime($DateTo)))."='A') and t2.DateOn='".$DateTo."' and (t2.InTime is not null and t2.OutTime is not null) and t3.DateOn='".$DateTo."' and t3.InTime !='WO' and t2.OutTime-t2.InTime!=0 and t4.Month=".intval(date('m',strtotime($DateTo)))." and t4.year=".intval(date('Y',strtotime($DateTo)))." and t5.location in (".$location.")";
			  $myDB=new MysqliDb();
			 $chk_task=$myDB->query($str);
			 $my_error= $myDB->getLastError();
			$table = '';
			if(count($chk_task) > 0 && $chk_task)
			{  
				foreach($chk_task as $key=>$value)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowcount,$value['EmployeeID']);
				    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowcount,$DateTo);
				    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowcount,$value['Attendance']);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowcount,$value['InTime']);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowcount,$value['OutTime']);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowcount,$value['RInTime']);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$rowcount,$value['ROutTime']);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowcount,$value['apr']);
					$rowcount++;
				}
				
				
				
			}
			else
			{
				$table="No Data Found  ... ".$my_error."";
				
			}
settimestamp($automail,'END');
$file = 'automail_missing_apr.csv';
		if($val['id']=="1")
		{
			$file = 'automail_missing_apr_Noida.csv';
			
		}
		else if($val['id']=="3")
		{
			$file = 'automail_missing_apr_Meerut.csv';
		}
		else if($val['id']=="4")
		{
			$file = 'automail_missing_apr_Bareilly.csv';
		}
		else if($val['id']=="5")
		{
			$file = 'automail_missing_apr_Vadodara.csv';
		}
		else if($val['id']=="6")
		{
			$file = 'automail_missing_apr_Mangalore.csv';
		}
		else if($val['id']=="7")
		{
			$file = 'automail_missing_apr_Bangalore.csv';
		}
		else if($val['id']=="8")
		{
			$file = 'automail_missing_apr_Bangalore_Flipkart.csv';
		}
		
	  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
	  $objWriter->save($file);
	
	$file_size = filesize($file);

	$count= 0;
	
		$myDB=new MysqliDb();;
		$pagename='auto_mail_missing_apr';
		echo "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'";
				$select_email_array=$myDB->rawQuery("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'");	 
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = EMAIL_HOST; 
		$mail->SMTPAuth = EMAIL_AUTH;
		$mail->Username = EMAIL_USER;   
		$mail->Password = EMAIL_PASS;                        
		$mail->SMTPSecure = EMAIL_SMTPSecure;
		$mail->Port = EMAIL_PORT; 
		$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
		
		//$mail->AddAddress('md.masood@cogenteservices.com');
		if(count($select_email_array)>0)
		{
			foreach($select_email_array as $select_email_array_val)
			{	
				$email_address=$select_email_array_val['email_address'];
				if($email_address!="")
				{
					$mail->AddAddress($email_address);
				}
				$cc_email=$select_email_array_val['ccemail'];
				if($cc_email!="")
				{
					$mail->addCC($cc_email);
				}
			}
			
		}
		$mail->AddAttachment($file);
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
		
		$mail->Subject = 'EMS '.$EMS_CenterName.', Missing APR Report ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
		$myDB=new MysqliDb();
		$mysqlError = $myDB->getLastError();
			$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Missing APR Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
		
		$mail->Body = $pwd_;
		
		$mymsg = '';
		if(!$mail->send())
	 	{
	 		settimestamp($automail,'Email Not Sent');
	 		echo '.Mailer Error:'. $mail->ErrorInfo;
	  	} 
		else
		 {settimestamp($automail,'Email Sent');
		    echo  '.Mail Send successfully.';
		 }
		 
	}
	
}		
	 


?>