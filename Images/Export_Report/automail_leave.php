<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(LIB.'PHPExcel/IOFactory.php');
require_once(CLS.'MysqliDb.php');
error_reporting(E_ALL);
ini_set('display_errors', 0);

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
			$automail = 'AutoEmail_Leave_Noida';
			
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_Leave_Meerut';
			
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_Leave_Bareilly';
			
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_Leave_Vadodara';
			
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_Leave_Mangalore';
			
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_Leave_Bangalore';
			
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_Leave_Bangalore_Flipkart';
			
		}
		
		settimestamp($automail,'Strat');
date_default_timezone_set('Asia/Kolkata');
	 
	  	$objPHPExcel = new PHPExcel();
	    $objPHPExcel->getActiveSheet()->setCellValue('A1',"EmployeeID");
	    $objPHPExcel->getActiveSheet()->setCellValue('B1',"EmployeeName");
	    $objPHPExcel->getActiveSheet()->setCellValue('C1',"OPS Status");
		$objPHPExcel->getActiveSheet()->setCellValue('D1',"Account Head Status");
		$objPHPExcel->getActiveSheet()->setCellValue('E1',"FinalStatus");
		$objPHPExcel->getActiveSheet()->setCellValue('F1',"DateCreated");
		$objPHPExcel->getActiveSheet()->setCellValue('G1',"DateFrom");
		$objPHPExcel->getActiveSheet()->setCellValue('H1',"DateTo");
		$objPHPExcel->getActiveSheet()->setCellValue('I1',"Leave Status");
		$objPHPExcel->getActiveSheet()->setCellValue('J1',"Count Of Leave");
		$objPHPExcel->getActiveSheet()->setCellValue('K1',"Designation");
		$objPHPExcel->getActiveSheet()->setCellValue('L1',"Dept Name");
		$objPHPExcel->getActiveSheet()->setCellValue('M1',"DOJ");
		$objPHPExcel->getActiveSheet()->setCellValue('N1',"Client");
		$objPHPExcel->getActiveSheet()->setCellValue('O1',"Process");
		$objPHPExcel->getActiveSheet()->setCellValue('P1',"Sub Process");
		$objPHPExcel->getActiveSheet()->setCellValue('Q1',"Supervisor");
		$objPHPExcel->getActiveSheet()->setCellValue('R1',"Account Head");
		$objPHPExcel->getActiveSheet()->setCellValue('S1',"Ops Head");
		$objPHPExcel->getActiveSheet()->setCellValue('T1',"ModifiedBy");
		$objPHPExcel->getActiveSheet()->setCellValue('U1',"ModifiedOn");
		$objPHPExcel->getActiveSheet()->setCellValue('V1',"Approved By");
		$objPHPExcel->getActiveSheet()->setCellValue('W1',"Comments");
		$rowcount = 2;
			
			$myDB=new MysqliDb();
			//$date_To = date('M',time()); 
			$date_From= date('Y-m').'-01'; 
			$date_To= date('Y-m-d', strtotime($date_From . "+3 months") ); 
			echo 'call sp_getLeaveStatus("CE03070003","'.$date_From.'","'.$date_To.'","Active","'.$val['id'].'")';
			$chk_task=$myDB->query('call sp_getLeaveStatus("CE03070003","'.$date_From.'","'.$date_To.'","Active","'.$val['id'].'")');			
			$tablename='whole_details_peremp';
			$my_error= $myDB->getLastError();
			$table = '';
			if(count($chk_task) > 0 && $chk_task)
			{  
				foreach($chk_task as $key=>$value)
				{
					$comment = explode('|',$value['Comments']);
					$string1 = 'Ops Head By Server';
					$string2 = 'Account Head By Server';
					$modify = (empty($value['DateModified']))?'':'('.date('Y-m-d',strtotime($value['DateModified'])).')';
					$attr_val = $modify.' '.$value['ModifiedBy'];
					$attr = '';
					foreach ($comment as $url) {
					
					    if (preg_match("/\b$string1\b/i", $url)) 
					    { 
					        $attr  .=  $url.' | ';      
					    }
					    if (preg_match("/\b$string2\b/i", $url)) 
					    {
							$attr  .=  $url.' | ';      
						}
						
					}
					if(!empty($attr))
					$attr_val = $attr;	
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowcount,$value['EmployeeID']);
				    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowcount,$value['EmployeeName']);
				    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowcount,$value['HRStatusID']);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowcount,$value['MngrStatusID']);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowcount,$value['FinalStatus']);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowcount,$value['DateCreated']);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$rowcount,$value['DateFrom']);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowcount,$value['DateTo']);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$rowcount,$value['LeaveType']);
					if($value['LeaveType'] == 'Leave')
					{
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowcount,$value['TotalLeaves']);
					}else
					{
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowcount,round(intval($value['TotalLeaves'])/2,1));
					}
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$rowcount,$value['designation']);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$rowcount,$value['dept_name']);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$rowcount,$value['DOJ']);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$rowcount,$value['clientname']);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$rowcount,$value['Process']);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$rowcount,$value['sub_process']);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowcount,$value['Supervisor']);
					if($value['ManagerComment'] == ' Approved By SERVER ')
					{
						$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,'SERVER');
					}
					elseif($value['FinalStatus'] == 'Pending' || $value['FinalStatus'] == '')
					{
						$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,'');
					}
					else
					{
						$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,'ACCOUNT HEAD');
					}
					if($value['HRComents'] == ' Approved By SERVER ')
					{
						$objPHPExcel->getActiveSheet()->setCellValue('S'.$rowcount,'SERVER');
					}
					
					else
					{
						$objPHPExcel->getActiveSheet()->setCellValue('S'.$rowcount,'OPS HEAD');
					}
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$rowcount,$value['account_head']);
					$objPHPExcel->getActiveSheet()->setCellValue('U'.$rowcount,$value['DateModified']);
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$rowcount,$attr_val);
					$objPHPExcel->getActiveSheet()->setCellValue('W'.$rowcount,$value['Comments']);
					
					$rowcount++;
				}
			}
			else
			{
				$table="No Data Found  ... ".$my_error."";				
			}
settimestamp($automail,'END');
	  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
	  $file = '';
	  	if($val['id']=="1")
		{
			$file = 'automail_leave_Noida.csv';
			
		}
		else if($val['id']=="3")
		{
			$file = 'automail_leave_Meerut.csv';
		}
		else if($val['id']=="4")
		{
			$file = 'automail_leave_Bareilly.csv';
		}
		else if($val['id']=="5")
		{
			$file = 'automail_leave_Vadodara.csv';
		}
		else if($val['id']=="6")
		{
			$file = 'automail_leave_Mangalore.csv';
		}
		else if($val['id']=="7")
		{
			$file = 'automail_leave_Bangalore.csv';
		}
		else if($val['id']=="8")
		{
			$file = 'automail_leave_Bangalore_Flipkart.csv';
		}
	  $objWriter->save($file);
		
	
	$file_size = filesize($file);

	$count= 0;
	
		$myDB=new MysqliDb();
		$pagename='automail_leave';
		echo "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'";
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
		if(count($select_email_array) > 0)		
		{
			foreach($select_email_array as $Key=>$val)
			{				
			   echo $email_address=$val['email_address'];
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
		
		
		$mail->Subject = 'EMS '.$EMS_CenterName.', Leave Report ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
	
		$mysqlError = $myDB->getLastError();
		$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Leave Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
		
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