<?php  
require_once(__dir__.'/../Config/init.php');
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
			$automail = 'AutoEmail_EmployeeCycle_Noida';
			
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_EmployeeCycle_Meerut';
			
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_EmployeeCycle_Bareilly';
			
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_EmployeeCycle_Vadodara';
			
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_EmployeeCycle_Mangalore';
			
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_EmployeeCycle_Bangalore';
			
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_EmployeeCycle_Bangalore_Flipkart';
			
		}
		
		settimestamp($automail,'Strat');
			$myDB=new MysqliDb();
				$date_To = date('Y-m-d');
				//$date_From= date('Y-m').'-01'; 
				$date_From=date('Y-m-d', strtotime($date_To . "-3 months") );  
				echo 'call EmployeeCycle_reportActive("CE03070003","All","'.$date_From.'","'.$date_To.'","'.$val['id'].'")';
				$rows=$myDB->query('call EmployeeCycle_reportActive("CE03070003","All","'.$date_From.'","'.$date_To.'","'.$val['id'].'")');
				$my_error= $myDB->getLastError();	
				
				$fileName = '';
				if($val['id']=="1")
				{
					$fileName = 'automail_EMPCYCLE_Noida.csv';
					
				}
				else if($val['id']=="3")
				{
					$fileName = 'automail_EMPCYCLE_Meerut.csv';
				}
				else if($val['id']=="4")
				{
					$fileName = 'automail_EMPCYCLE_Bareilly.csv';
				}
				else if($val['id']=="5")
				{
					$fileName = 'automail_EMPCYCLE_Vadodara.csv';
				}
				else if($val['id']=="6")
				{
					$fileName = 'automail_EMPCYCLE_Mangalore.csv';
				}
				else if($val['id']=="7")
				{
					$fileName = 'automail_EMPCYCLE_Bangalore.csv';
				}
				else if($val['id']=="8")
				{
					$fileName = 'automail_EMPCYCLE_Bangalore_Flipkart.csv';
				}
				
				if(count($rows)>0){
						
					$columnNames = array();
					$i=1;
					
					$fp = fopen($fileName, 'w');
					$columnNames =array("EmployeeID","EmployeeName","Employee Stage","Batch Name","Batch No","Designation","Client","Process","Sub Process","Employee Status","Date of Join","Date of Birth","Trainer","Training Head","Quality Analyst (OJT)","Quality Analyst (OPS)","Quality Head","Account Head","Supervisor","Created On","Training Start Date","Training End Date","Training Out Date","Re Training","OUT FROM TH","In OJT QA","Out Date","Out OJT","Re OJT","RHR Date","On Floor","Mapped Date","Training Days Overrun","OJT Days Overrun");
					fputcsv($fp,$columnNames);
					  foreach($rows as $key=>$row){
					  
					  	
						  	if(!empty($row['CertDate']) && !empty($row['OutTraining']))
							{
								$date_1  = new DateTime($row['CertDate']);
								$date_2  = new DateTime($row['OutTraining']);
								
								$diff=date_diff($date_1,$date_2);
								if($date_1 <= $date_2)
								{
									$dateDiff =$diff->format("%R%a");	
								}
								else
								{
									$dateDiff ='';
								}
								
							}
							else
							{
								$dateDiff ='';
							}
							if(!empty($row['ojt_Date']) && !empty($row['OutOJTQA']))
							{
								$date_1  = new DateTime($row['ojt_Date']);
								$date_2  = new DateTime($row['OutOJTQA']);
								$diff=date_diff($date_1,$date_2);
								if($date_1 <= $date_2)
								{
									$dateDiff2 =$diff->format("%R%a");	
								}
								else
								{
									$dateDiff2 ='';
								}
							}
							else
							{
								$dateDiff2 ='';
							}
						  array_push($row,$dateDiff,$dateDiff2);
						  fputcsv($fp, $row);
					} 
					fclose($fp);	    
				}
				
				settimestamp($automail,'END');
		echo "filesize".$file_size = filesize($fileName);		
		if(file_exists($fileName) && !empty($fileName)){
		$myDB=new MysqliDb();
		$pagename='automail_EmployeeCycle';
		echo "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'";
				$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'");	
				$mysqlError =  $myDB->getLastError();
				
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
		
				$mail->Subject = 'EMS '.$EMS_CenterName.', Employee-Cycle Report ['.date('d M,Y',time()).']';
				$mail->isHTML(true);
				$pwd_='<style>table {border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Employee-Cycle Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
				
				$mail->Body = $pwd_;
				
				$mymsg = '';
				if(!$mail->send())
			 	{settimestamp($automail,'Email Not Sent');
			 		echo '.Mailer Error:'. $mail->ErrorInfo;
			  	} 
				else
				 {settimestamp($automail,'Email Sent');
				    echo  '.Mail Send successfully.';
				 }
		}
	}
}		
		

			
		 ?>
		 	
