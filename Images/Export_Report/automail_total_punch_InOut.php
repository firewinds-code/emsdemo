<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
error_reporting(1);

ini_set('memory_limit', '300M'); 
ini_set('display_errors', '1');
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
			$automail = 'AutoEmail_total_punch_Noida';
			
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_total_punch_Meerut';
			
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_total_punch_Bareilly';
			
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_total_punch_Vadodara';
			
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_total_punch_Mangalore';
			
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_total_punch_Bangalore';
			
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_total_punch_Bangalore_Flipkart';
			
		}
	
		settimestamp($automail,'Start');
			$myDB=new MysqliDb();
			$date=date('Y-m-d');
			//$date_on1=date('Y-m-').'01';
			//$date_on1= date('Y-m-d', strtotime($date . "-11 Day") ); 	
			$date_on1= date('Y-m-d', strtotime($date . "-5 Day") ); 	
			$date_on2= date('Y-m-d', strtotime($date . "-1 Day") ); 	
			$columnNames =array("EmpID","PunchTime","Date","Source");
		//echo 'call mis_RowPunch_Report_Automail("'.$date_on1.'","'.$date_on2.'","ADMINISTRATOR","'.$val['id'].'")';
			$rows=$myDB->query('call mis_RowPunch_Report_Automail("'.$date_on1.'","'.$date_on2.'","ADMINISTRATOR","'.$val['id'].'")');
			
			$fileName = '';
			
	
			if(count($rows) > 0 && $rows)
			{
				if($val['id']=="1")
				{
					$fileName = 'automail_total_punch_InOut_Noida.csv';
					
				}
				else if($val['id']=="3")
				{
					$fileName = 'automail_total_punch_InOut_Meerut.csv';
				}
				else if($val['id']=="4")
				{
					$fileName = 'automail_total_punch_InOut_Bareilly.csv';
				}
				else if($val['id']=="5")
				{
					$fileName = 'automail_total_punch_InOut_Vadodara.csv';
				}
				else if($val['id']=="6")
				{
					$fileName = 'automail_total_punch_InOut_Mangalore.csv';
				}
				else if($val['id']=="7")
				{
					$fileName = 'automail_total_punch_InOut_Bangalore.csv';
				}
				else if($val['id']=="8")
				{
					$fileName = 'automail_total_punch_InOut_Bangalore_Flipkart.csv';
				}
				$fp = fopen($fileName, 'w');
					
				fputcsv($fp, $columnNames);
				foreach($rows as $key=>$value)
			    {	$source='';
			    	if($value['EmployeeID']==""){
			    		$source="Manual";
			    	}else
		    		if($value['EmployeeID']=="App"){
		    			$source="Mobile App";
					}
					else
		    		if($value['EmployeeID']!="App" && $value['EmployeeID']!="" ){
		    			$source="Employee";
					}
					$row1=array($value['EmpID'],$value['PunchTime'],$value['Date'],$source);
					fputcsv($fp, $row1);
				}
				
			}
			else
			{
				$table="No Data Found  ...";
			}
			settimestamp($automail,'END');	
		
		
		if(file_exists($fileName) && !empty($fileName)){
			echo "filesize=".$file_size = filesize($fileName);	
		$myDB=new MysqliDb();
		$pagename='automail_total_punch_InOut';
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
				//$mail->AddAddress('rinku.kumari@cogenteservices.in');
				if(count($select_email_array)>0){
					foreach($select_email_array as $key=>$email_array){
						$email_address=$email_array['email_address'];
						if($email_address!=""){
							$mail->AddAddress($email_address);
						}
						$cc_email=$email_array['ccemail'];
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
		
				$mail->Subject = 'EMS '.$EMS_CenterName.', Total Punch InOut Report ['.date('d M,Y',time()).']';
				$mail->isHTML(true);
				$mysqlError = $myDB->getLastError();
				$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Total Punch InOut Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
				
				$mail->Body = $pwd_;
				
				$mymsg = '';
				if(!$mail->send())
			 	{settimestamp($automail,'Email Not Sent');
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
		 	
