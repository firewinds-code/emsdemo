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

/*$myDB=new MysqliDb();
$loc=$myDB->query('call get_locationID()');
if(count($loc) > 0 && $loc)
{  
	foreach($loc as $key=>$val)
	{*/
		$automail = '';$location='';
		$automail = 'AutoEmail_MasterData';
		/*if($val['id']=="1")
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
		}*/
		
		settimestamp($automail,'Start');
date_default_timezone_set('Asia/Kolkata');
				$columnNames =array("Sr. No.","Location","Employee ID","Employee Name","Client","Process","Sub Process","Date of Joining","CTC","Designation","Mobile Number","Alternet No","Gender","D.O.B","DOD","Employee Type","Payroll Type","Father's Name","Father D.O.B","Mother's Name","Mother's D.O.B","Marital Status","Spouse Name","Spouse DOB","Bank Name", "Bank A/C Number", "Name as per Bank","IFSC Code","Pan Number","Aadhaar Number","Permanent Address","Current Address","Email ID Personnel","Email ID Official","Emergency Contact Number","Emergency Relation","Nominee Name","Nominee Relation");
				
				$myDB=new MysqliDb();
			  //$rquery = "select distinct t1.*,concat('\'',adhar.dov_value,'\'') as AdharCard,pan.dov_value as PanCard from view_empinfoall t1 LEFT JOIN  (select distinct EmployeeID,dov_value from doc_details where doc_stype='Aadhar Card' group by EmployeeID order by createdon desc) adhar on t1.EmployeeID=adhar.EmployeeID LEFT JOIN (select distinct EmployeeID, dov_value from doc_details where doc_stype='PAN Card' group by EmployeeID order by createdon desc) pan  on t1.EmployeeID=pan.EmployeeID inner join personal_details t2 on t1.EmployeeID=t2.EmployeeID where t2.location in (".$location.")";
			  
			  $rquery = "select distinct t1.*,concat('\'',adhar.dov_value,'\'') as AdharCard,pan.dov_value as PanCard from view_empinfoall t1 LEFT JOIN  (select distinct EmployeeID,dov_value from doc_details where doc_stype='Aadhar Card' group by EmployeeID order by createdon desc) adhar on t1.EmployeeID=adhar.EmployeeID LEFT JOIN (select distinct EmployeeID, dov_value from doc_details where doc_stype='PAN Card' group by EmployeeID order by createdon desc) pan  on t1.EmployeeID=pan.EmployeeID inner join personal_details t2 on t1.EmployeeID=t2.EmployeeID";
			  
				//echo $rquery;
			$chk_task=$myDB->query($rquery);
		
			$my_error= $myDB->getLastError();
			
			$rg_status='';
			//$fileName = '';
			$fileName = 'automail_masterdata.csv';
			$fileName1 = 'automail_child_details.csv';
			$fileName2 = 'automail_dependent_details.csv';
			/*if($val['id']=="1")
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
			}*/
			
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
					$row1=array($i,$value['location'],$value['EmployeeID'],$value['EmployeeName'],$value['client_name'],$value['Process'],$value['sub_process'],$value['DOJ'],$value['ctc'],$value['designation'],$value['mobile'],$value['altmobile'],$value['Gender'],$value['DOB'],$value['DOD'],$value['emptype'],$emptype,$value['FatherName'],$value['father_dob'],$value['MotherName'],$value['mother_dob'],$value['MarriageStatus'],$value['Spouse'],$value['spouse_dob'],$value['BankName'],$value['BankAccountNo'],$value['name_asper_bank'],$value['IFSC_code'],$value['PanCard'],$value['AdharCard'],$value['address_p'],$value['address'],$value['emailid'],$value['ofc_emailid'],$value['em_contact'],$value['em_relation'],$value['nominee_name'],$value['nominee_relation']
					);
					fputcsv($fp, $row1);
					$i++;
				}
					
			}
			else
			{
			echo	$table="No Data Found  ...";
				
			}
			
			$columnNames =array("Employee ID","Child Name","Child Dob","Child Gender","BloodGroup");
				
			$myDB=new MysqliDb();
			  			  
			  $rquery = "select t1.EmployeeID, ChildName, ChildDob,ChildGender,t1.BloodGroup from child_details t1 inner join employee_map t2 on t1.EmployeeID=t2.EmployeeID inner join personal_details t3 on t2.EmployeeID=t3.EmployeeID where t2.emp_status='Active' and (CAST(dateofjoin AS DATE) >= CAST((NOW() + INTERVAL -(1) MONTH) AS DATE) or cast(t3.modifiedon as date)>=cast(date_add(now(), interval -1 month) as date)) order by t1.EmployeeID;";
			  
				//echo $rquery;
			$chk_task=$myDB->query($rquery);
		
			$my_error= $myDB->getLastError();
			
			if(count($chk_task) > 0 && $chk_task)
			{  
				
				$fp = fopen($fileName1, 'w');
    			fputcsv($fp, $columnNames);
    			$i=1;
				foreach($chk_task as $key=>$value)
				{	
					$row1=array($value['EmployeeID'],$value['ChildName'],$value['ChildDob'],$value['ChildGender'],$value['BloodGroup']
					);
					fputcsv($fp, $row1);
					$i++;
				}
					
			}
			
			$columnNames =array("Employee ID","Dependent Name","Relation","Dependent Dob");
				
			$myDB=new MysqliDb();
			  			  
			  $rquery = "select t1.EmployeeID, DependentName,Relation,DependentDob from dependent_details t1 inner join employee_map t2 on t1.EmployeeID=t2.EmployeeID inner join personal_details t3 on t2.EmployeeID=t3.EmployeeID where t2.emp_status='Active' and (CAST(dateofjoin AS DATE) >= CAST((NOW() + INTERVAL -(1) MONTH) AS DATE) or cast(t3.modifiedon as date)>=cast(date_add(now(), interval -1 month) as date)) order by t1.EmployeeID;";
			  
				//echo $rquery;
			$chk_task=$myDB->query($rquery);
		
			$my_error= $myDB->getLastError();
			
			if(count($chk_task) > 0 && $chk_task)
			{  
				
				$fp = fopen($fileName2, 'w');
    			fputcsv($fp, $columnNames);
    			$i=1;
				foreach($chk_task as $key=>$value)
				{	
					$row1=array($value['EmployeeID'],$value['DependentName'],$value['Relation'],$value['DependentDob']);
					fputcsv($fp, $row1);
					$i++;
				}
					
			}
			
			
			
	 settimestamp($automail,'END');
		echo "<br>";
		echo "filesize=".$file_size = filesize($fileName);
		if(file_exists($fileName) && !empty($fileName)){
		$myDB=new MysqliDb();
		$pagename='automail_masterdata';
	
		$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='1'");
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
		
		//$mail->AddAddress("sanoj.pandey@cogenteservices.com");
		
		$mail->AddAttachment($fileName);
		$mail->AddAttachment($fileName1);
		$mail->AddAttachment($fileName2);
		$EMS_CenterName='';
		/*if($val['id']=="1")
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
		}*/
				
		/*$mail->Subject = 'EMS '.$EMS_CenterName.', Master Data ['.date('d M,Y',time()).']';*/
		$mail->Subject = 'EMS Master Data ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
	
		$mysqlError = $myDB->getLastError();
		$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Master Data</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
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
		
	/*}	
}*/


?>