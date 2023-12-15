<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(LIB.'PHPExcel/IOFactory.php');
require_once(CLS.'MysqliDb.php');
ini_set('memory_limit','512M');
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
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
			$automail = 'AutoEmail_Exception_Noida';
			
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_Exception_Meerut';
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_Exception_Bareilly';
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_Exception_Vadodara';
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_Exception_Mangalore';
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_Exception_Bangalore';
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_Exception_Bangalore_Flipkart';
		}
		
		settimestamp($automail,'Start');
		date_default_timezone_set('Asia/Kolkata');
	 
	  	$objPHPExcel = new PHPExcel();
	    $objPHPExcel->getActiveSheet()->setCellValue('A1',"EmployeeID");
	    $objPHPExcel->getActiveSheet()->setCellValue('B1',"EmployeeName");
	    $objPHPExcel->getActiveSheet()->setCellValue('C1',"Exception");
		$objPHPExcel->getActiveSheet()->setCellValue('D1',"MgrStatus");
		$objPHPExcel->getActiveSheet()->setCellValue('E1',"HeadStatus");
		$objPHPExcel->getActiveSheet()->setCellValue('F1',"DateOn");
		$objPHPExcel->getActiveSheet()->setCellValue('G1',"DateFrom");
		$objPHPExcel->getActiveSheet()->setCellValue('H1',"DateTo");
		$objPHPExcel->getActiveSheet()->setCellValue('I1',"Current Attendance");
		$objPHPExcel->getActiveSheet()->setCellValue('J1',"Updated Attendance");
		$objPHPExcel->getActiveSheet()->setCellValue('K1',"Roster In");
		$objPHPExcel->getActiveSheet()->setCellValue('L1',"Roster Out");
		$objPHPExcel->getActiveSheet()->setCellValue('M1',"Designation");
		$objPHPExcel->getActiveSheet()->setCellValue('N1',"Dept Name");
		$objPHPExcel->getActiveSheet()->setCellValue('O1',"DOJ");
		$objPHPExcel->getActiveSheet()->setCellValue('P1',"Client");
		$objPHPExcel->getActiveSheet()->setCellValue('Q1',"Process");
		$objPHPExcel->getActiveSheet()->setCellValue('R1',"Sub Process");
		$objPHPExcel->getActiveSheet()->setCellValue('S1',"Supervisor");
		$objPHPExcel->getActiveSheet()->setCellValue('T1',"ModifiedBy");
		$objPHPExcel->getActiveSheet()->setCellValue('U1',"Approved By");
		$objPHPExcel->getActiveSheet()->setCellValue('V1',"ModifiedOn");
		$objPHPExcel->getActiveSheet()->setCellValue('W1',"Comments");
	
		$rowcount = 2;
			
			$myDB=new MysqliDb();
			$date_To =  date('Y-m-d');
			$date_From= date('Y-m').'-01';
			echo 'call sp_getExceptionReport("CE03070003","'.$date_From.'","'.$date_To.'","Active","'.$val['id'].'")' . '<br/>';
			$chk_task=$myDB->query('call sp_getExceptionReport("CE03070003","'.$date_From.'","'.$date_To.'","Active","'.$val['id'].'")');
		//	echo 'call sp_getExceptionReport("CE03070003","'.$date_From.'","'.$date_To.'","Active")';
			$tablename='whole_details_peremp';
			$my_error= $myDB->getLastError();
			$table = '';
			if(count($chk_task) > 0 && $chk_task)
			{  
				foreach($chk_task as $key=>$value)
				{

					$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowcount,$value['EmployeeID']);
				    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowcount,$value['EmployeeName']);
				    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowcount,$value['Exception']);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowcount,$value['MgrStatus']);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowcount,$value['HeadStatus']);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowcount,$value['CreatedOn']);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$rowcount,$value['DateFrom']);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowcount,$value['DateTo']);
					if($value['Exception'] == 'Biometric issue' || $value['Exception'] == 'Biometric Issue')
					{
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$rowcount,$value['Current_Att']);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowcount,$value['Update_Att']);
					}else{
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$rowcount,'NA');
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowcount,'NA');
					}
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$rowcount,$value['ShiftIn']);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$rowcount,$value['ShiftOut']);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$rowcount,$value['designation']);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$rowcount,$value['dept_name']);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$rowcount,$value['DOJ']);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$rowcount,$value['clientname']);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowcount,$value['Process']);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,$value['sub_process']);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$rowcount,$value['Supervisor']);
					if($value['ModifiedBy'] == 'SERVER')
					{
						$objPHPExcel->getActiveSheet()->setCellValue('T'.$rowcount,'SERVER');
					}else
					{
						$objPHPExcel->getActiveSheet()->setCellValue('T'.$rowcount,'ACCOUNT HEAD');
					}
					
					$comment = explode('|',$value['Comments']);
					
					$string = 'Approved by  SERVER';
					$modify = (empty($value['ModifiedOn']))?'':'('.date('Y-m-d',strtotime($value['ModifiedOn'])).')';
					$attr_val = $modify.' '.$value['ModifiedBy'];
					foreach ($comment as $url) {
						
					    if (preg_match("/\b$string\b/i", $url)) 
					    { 
					        $attr_val  =  $url;      
					    }
					}
					$objPHPExcel->getActiveSheet()->setCellValue('U'.$rowcount,$attr_val);
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$rowcount,$modify);
					$objPHPExcel->getActiveSheet()->setCellValue('W'.$rowcount,$value['Comments']);
					$rowcount++;
				}
				
				
				
			}
			else
			{
				$table="No Data Found  ... ".$my_error."";
				
			}
			
	
	settimestamp($automail,'END');
	$file = 'automail_exception.csv';
	if($val['id']=="1")
	{
		$file = 'automail_exception_Noida.csv';
		
	}
	else if($val['id']=="3")
	{
		$file = 'automail_exception_Meerut.csv';
	}
	else if($val['id']=="4")
	{
		$file = 'automail_exception_Bareilly.csv';
	}
	else if($val['id']=="5")
	{
		$file = 'automail_exception_Vadodara.csv';
	}
	else if($val['id']=="6")
	{
		$file = 'automail_exception_Mangalore.csv';
	}
	else if($val['id']=="7")
	{
		$file = 'automail_exception_Bangalore.csv';
	}
	else if($val['id']=="8")
	{
		$file = 'automail_exception_Bangalore_Flipkart.csv';
	}
	
	$file_size = filesize($file);
	  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
	  $objWriter->save($file);
	
	
	$count= 0;
		$myDB=new MysqliDb();
		$pagename='automail_exception';
		echo "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'". '<br/>';
		$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'");	
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
		if(count($select_email_array) > 0)		
		{
			foreach($select_email_array as $Key=>$val)
			{				
			   $email_address=$val['email_address'];
				if($email_address!=""){
					$mail->AddAddress($email_address);
				}
				$cc_email=$val['ccemail'];
				if($cc_email!=""){
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
				
			
		$mail->Subject = 'EMS '.$EMS_CenterName.', Exception Report ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
	
		$mysqlError =$myDB->getLastError();
		$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Exception Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
		
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
					



?>