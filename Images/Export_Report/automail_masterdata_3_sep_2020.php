<?php
	require_once(__dir__.'/../Config/init.php');
	require_once(CLS.'php_mysql_class.php');
	require_once(CLS.'MysqliDb.php');
//require_once(LIB.'PHPExcel/IOFactory.php');
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
		$automail = '';$location='';
		if($val['id']=="1")
		{
			$automail = 'AutoEmail_MasterData_Noida';
			$location="1,2";
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_MasterData_Meerut';
			$location = "3";
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_MasterData_Bareilly';
			$location = "4";
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_MasterData_Vadodara';
			$location = "5";
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_MasterData_Mangalore';
			$location = "6";
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_MasterData_Bangalore';
			$location = "7";
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_MasterData_Bangalore_Flipkart';
			$location = "8";
		}
		
		settimestamp($automail,'Start');
date_default_timezone_set('Asia/Kolkata');
				$columnNames =array("Sr. No.","Employee ID","Employee Name","Date of Joining","Considered Date of Deployment","Designating","Client","Process","Sub Process","Employee Type","Payroll Type","Gender","Father's Name","Mother's Name","Contact Number","DOB","Current Address","Permanent Address","CTC","Take Home","Adhar Card Number","Pan Card Number");
				
				$myDB=new MysqliDb();
				echo $rquery = "select distinct t1.*,concat('\'',adhar.dov_value,'\'') as AdharCard,pan.dov_value as PanCard from View_EmpinfoActive t1 LEFT JOIN  (select distinct EmployeeID,dov_value from doc_details where doc_stype='Aadhar Card' group by EmployeeID order by createdon desc) adhar on t1.EmployeeID=adhar.EmployeeID LEFT JOIN (select distinct EmployeeID, dov_value from doc_details where doc_stype='PAN Card' group by EmployeeID order by createdon desc) pan  on t1.EmployeeID=pan.EmployeeID inner join personal_details t2 on t1.EmployeeID=t2.EmployeeID where t2.location in (".$location.")";
				//echo $rquery;
			$chk_task=$myDB->query($rquery);
		
			$my_error= $myDB->getLastError();
			
			$rg_status='';
			$fileName = '';
			if($val['id']=="1")
			{
				$fileName = 'automail_masterdata_Noida.csv';
				
			}
			else if($val['id']=="3")
			{
				$fileName = 'automail_masterdata_Meerut.csv';
			}
			else if($val['id']=="4")
			{
				$fileName = 'automail_masterdata_Bareilly.csv';
			}
			else if($val['id']=="5")
			{
				$fileName = 'automail_masterdata_Vadodara.csv';
			}
			else if($val['id']=="6")
			{
				$fileName = 'automail_masterdata_Mangalore.csv';
			}
			else if($val['id']=="7")
			{
				$fileName = 'automail_masterdata_Bangalore.csv';
			}
			else if($val['id']=="8")
			{
				$fileName = 'automail_masterdata_Bangalore_Flipkart.csv';
			}
			
			if(count($chk_task) > 0 && $chk_task)
			{  
				
				$fp = fopen($fileName, 'w');
    			fputcsv($fp, $columnNames);
    			$i=1;
				foreach($chk_task as $key=>$value)
				{	if($value['rt_type']=='1'){
						$emptype='Full Timer';
					}else
					if($value['rt_type']=='3'){
						$emptype='Part Timer';
					}
					else
					if($value['rt_type']=='4'){
						$emptype='Split';
					}
					$row1=array($i,$value['EmployeeID'],$value['EmployeeName'],$value['DOJ'],$value['DOD'],$value['designation'],$value['client_name'],$value['Process'],$value['sub_process'],$emptype,$value['emptype'],$value['Gender'],$value['FatherName'],$value['MotherName'],$value['mobile'],$value['DOB'],$value['address'],$value['address_p'],$value['ctc'],$value['takehome'],$value['AdharCard'],$value['PanCard']);
					fputcsv($fp, $row1);
					$i++;
				}
					
			}
			else
			{
			echo	$table="No Data Found  ...";
				
			}

	 settimestamp($automail,'END');
		echo "<br>";
		echo "filesize=".$file_size = filesize($fileName);
		if(file_exists($fileName) && !empty($fileName)){
		$myDB=new MysqliDb();
		$pagename='automail_masterdata';
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
				
		$mail->Subject = 'EMS '.$EMS_CenterName.', Master Data ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
	
		$mysqlError = $myDB->getLastError();
		$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Master Data for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
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