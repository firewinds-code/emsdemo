<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
error_reporting(1);
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
			$automail = 'AutoEmail_Roster_Noida';
			
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_Roster_Meerut';
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_Roster_Bareilly';
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_Roster_Vadodara';
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_Roster_Mangalore';
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_Roster_Bangalore';
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_Roster_Bangalore_Flipkart';
		}
		
		settimestamp($automail,'Start');
			$myDB=new MysqliDb();
			$Month_To = date('M'); 
			$Year_To= date('Y'); 
			 $columnNames =array("EmployeeID","EmployeeName","D1","D2","D3","D4","D5","D6","D7","D8","D9","D10","D11","D12","D13","D14","D15","D16","D17","D18","D19","D20","D21","D22","D23","D24","D25","D26","D27","D28","D29","D30","D31","Month","Year","designation","dept_name","Process","sub_process","Supervisor","DOJ","clientname");
			
				$rows=$myDB->query('call sp_get_roster_Report("CE03070003","'.$Month_To.'","'.$Year_To.'","All","Active","'.$val['id'].'")');
				echo 'call sp_get_roster_Report("CE03070003","'.$Month_To.'","'.$Year_To.'","All","Active","'.$val['id'].'")';				
				
				$fileName = '';
					if($val['id']=="1")
					{
						$fileName = 'automail_roster_Noida.csv';
						
					}
					else if($val['id']=="3")
					{
						$fileName = 'automail_roster_Meerut.csv';
					}
					else if($val['id']=="4")
					{
						$fileName = 'automail_roster_Bareilly.csv';
					}
					else if($val['id']=="5")
					{
						$fileName = 'automail_roster_Vadodara.csv';
					}
					else if($val['id']=="6")
					{
						$fileName = 'automail_roster_Mangalore.csv';
					}
					else if($val['id']=="7")
					{
						$fileName = 'automail_roster_Bangalore.csv';
					}
					else if($val['id']=="8")
					{
						$fileName = 'automail_roster_Bangalore_Flipkart.csv';
					}
					
				if(count($rows) > 0 && $rows)
					{
					
		
						$fp = fopen($fileName, 'w');
						
					fputcsv($fp, $columnNames);
					foreach($rows as $key=>$value)
			        {
				$row1=array($value['EmployeeID'],$value['EmployeeName'],$value['D1'],$value['D2'],$value['D3'],$value['D4'],$value['D5'],$value['D6'],$value['D7'],$value['D8'],$value['D9'],$value['D10'],$value['D11'],$value['D12'],$value['D13'],$value['D14'],$value['D15'],$value['D16'],$value['D17'],$value['D18'],$value['D19'],$value['D20'],$value['D21'],$value['D22'],$value['D23'],$value['D24'],$value['D25'],$value['D26'],$value['D27'],$value['D28'],$value['D29'],$value['D30'],$value['D31'],$value['Month'],$value['Year'],$value['designation'],$value['dept_name'],$value['Process'],$value['sub_process'],$value['Supervisor'],$value['DOJ'],$value['clientname']);
				fputcsv($fp, $row1);
	              }
					}
					else
			{
				$table="No Data Found  ...";
				
			}
				
				settimestamp($automail,'END');
				$myDB=new MysqliDb();
				$pagename='automail_roster';
				echo "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'";
				$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'");		
			echo "filesize".$file_size = filesize($fileName);		
			if(file_exists($fileName) && !empty($fileName))
			{	 
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
				
				$mail->Subject = 'EMS '.$EMS_CenterName.', Roster Report ['.date('d M,Y',time()).']';
				$mail->isHTML(true);
			
				$mysqlError = $myDB->getLastError();
				$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Roster Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
				
				$mail->Body = $pwd_;
				
				$mymsg = '';
				if(!$mail->send())
			 	{
			 		settimestamp($automail,'Emsil Not Sent');
			 		echo '.Mailer Error:'. $mail->ErrorInfo;
			  	} 
				else
			 	{ settimestamp($automail,'Email Sent');
				    echo  '.Mail Send successfully.';
				}
				
			}
	}
}
		

			
		 ?>
		 	
