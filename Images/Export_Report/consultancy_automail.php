<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
// require_once(__dir__.'/../Config/DBConfig.php');
require_once(CLS.'MysqliDb.php');
//require_once(LIB.'PHPExcel/IOFactory.php');
error_reporting(E_ALL);
ini_set('display_errors', 0);

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
			$automail = 'automail_consultancy_Noida';
			
		}
		else if($val['id']=="3")
		{
			$automail = 'automail_consultancy_Meerut';
		}
		else if($val['id']=="4")
		{
			$automail = 'automail_consultancy_Bareilly';
		}
		else if($val['id']=="5")
		{
			$automail = 'automail_consultancy_Vadodara';
		}
		else if($val['id']=="6")
		{
			$automail = 'automail_consultancy_Mangalore';
		}
		else if($val['id']=="7")
		{
			$automail = 'automail_consultancy_Bangalore';
		}
		else if($val['id']=="8")
		{
			$automail = 'automail_consultancy_Bangalore_Flipkart';
		}
		
		settimestamp($automail,'Start');
		 $columnNames =array("EmployeeID","EmployeeName","Client","Process","Subprocess","AccountHead","ReportsTo","ConsulatancyName","ContactPerson","ContactNo.","DOJ","Tenure(Days)","PayOut(Rs.)","DueDate");
			
			echo "SELECT a.EmployeeID,a.DOJ,a.Tenure,a.Payout,a.Due_date, b.ConsultancyName,b.ContactPerson,b.ContactNo,c.EmployeeName,c.account_head,c.ReportTo,c.clientname,c.Process,c.sub_process,p.EmployeeName as AHname,R.EmployeeName as ReportsTOname FROM consultancy_empref a INNER JOIN consultancy_master b ON a.consultancy_id=b.id INNER JOIN whole_details_peremp c on c.employeeID= a.employeeID inner Join personal_details p on c.account_head=p.EmployeeID INNER JOIN personal_details R  ON R.employeeID=c.ReportTo where a.Due_date=CURRENT_DATE and c.location in('".$val['id']."')";
			$myDB=new MysqliDb();
			$chk_task=$myDB->rawQuery("SELECT a.EmployeeID,a.DOJ,a.Tenure,a.Payout,a.Due_date, b.ConsultancyName,b.ContactPerson,b.ContactNo,c.EmployeeName,c.account_head,c.ReportTo,c.clientname,c.Process,c.sub_process,p.EmployeeName as AHname,R.EmployeeName as ReportsTOname FROM consultancy_empref a INNER JOIN consultancy_master b ON a.consultancy_id=b.id INNER JOIN whole_details_peremp c on c.employeeID= a.employeeID inner Join personal_details p on c.account_head=p.EmployeeID INNER JOIN personal_details R  ON R.employeeID=c.ReportTo where a.Due_date=CURRENT_DATE and c.location in('".$val['id']."')");
			$rg_status='';
			echo count($chk_task);
		if(count($chk_task) > 0 && $chk_task)
		{  
				$fileName = 'automail_consultancy.csv';
				if($val['id']=="1")
				{
					$fileName = 'automail_consultancy_Noida.csv';
					
				}
				else if($val['id']=="3")
				{
					$fileName = 'automail_consultancy_Meerut.csv';
				}
				else if($val['id']=="4")
				{
					$fileName = 'automail_consultancy_Bareilly.csv';
				}
				else if($val['id']=="5")
				{
					$fileName = 'automail_consultancy_Vadodara.csv';
				}
				else if($val['id']=="6")
				{
					$fileName = 'automail_consultancy_Mangalore.csv';
				}
				else if($val['id']=="7")
				{
					$fileName = 'automail_consultancy_Bangalore.csv';
				}
				else if($val['id']=="8")
				{
					$fileName = 'automail_consultancy_Bangalore_Flipkart.csv';
				}
				
				$fp = fopen($fileName, 'w');
				
    			fputcsv($fp, $columnNames);
			foreach($chk_task as $key=>$value)
			{
			
				$row1=array($value['EmployeeID'],$value['EmployeeName'],$value['clientname'],$value['Process'],$value['sub_process'],$value['AHname'],$value['ReportsTOname'],$value['ConsultancyName'],$value['ContactPerson'],$value['ContactNo'],$value['DOJ'],$value['Tenure'],$value['Payout'],$value['Due_date']);
				fputcsv($fp, $row1);
				
			}
	
			}
			else
			{
				$table="No Data Found  ...";
				
			}
	settimestamp($automail,'END');
	 
		
	echo "filesize=".$file_size = filesize($fileName);
	if(file_exists($fileName) && !empty($fileName)){
	$count= 0;
	
		$myDB=new MysqliDb();
		$pagename='ConsultancyReport';
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
				
		$mail->Subject = 'EMS '.$EMS_CenterName.', Consultancy Due Date Report ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
	
		$mysqlError = $myDB->getLastError();
		$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Consultancy Due Date Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
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