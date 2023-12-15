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
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}
date_default_timezone_set('Asia/Kolkata');

$myDB=new MysqliDb();
$loc=$myDB->query('call get_locationID()');
if(count($loc) > 0 && $loc)
{  
	foreach($loc as $key=>$val)
	{
		$automail = '';$location='';
		if($val['id']=="1")
		{
			$automail = 'AutoEmail_Resign_Noida';
			$location = "1,2";
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_Resign_Meerut';
			$location = "3";
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_Resign_Bareilly';
			$location = "4";
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_Resign_Vadodara';
			$location = "5";
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_Resign_Mangalore';
			$location = "6";
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_Resign_Bangalore';
			$location = "7";
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_Resign_Bangalore_Flipkart';
			$location = "8";
		}
		
settimestamp($automail,'Start');
					 $columnNames =array("EmployeeID","EmployeeName","Notice Start","Notice End","Final Status","Revoke Status","Revoke Date","Revoke Remark","Revoke Accept Date (AH)","Revoke Remark (AH)","Revoke Accept Date (HR)","Revoke Remark (HR)","HR Status","Requester Remark","HR Remark","Designation","Dept Name","DOJ","Client","Process","Sub Process","Supervisor","Account Head","Comments");
			
			$myDB=new MysqliDb();
			//$date_To = date('Y-m-d'); 
			$lastday = date('t',strtotime('today'));
			$date_To = date('Y-m-').$lastday;//date('Y-m-d'); 
			$date_From= date('Y-m').'-01';
				 $rquery = "select whole_dump_emp_data.EmployeeID,whole_dump_emp_data.EmployeeName,resign_details.nt_start,resign_details.nt_end
,resign_details.rg_status,resign_details.revoke_status,resign_details.revoke_on,resign_details.revoke_comment,resign_details.revoke_ah,
resign_details.rv_ah_remark,resign_details.revoke_hr,resign_details.rv_hr_remark,
resign_details.accept,resign_details.remark,resign_details.accepter_remark,resign_details.revoke_accept,
whole_dump_emp_data.designation,whole_dump_emp_data.dept_name,whole_dump_emp_data.DOJ,whole_dump_emp_data.clientname,
whole_dump_emp_data.Process,whole_dump_emp_data.sub_process,pd.EmployeeName as Supervisor ,whole_dump_emp_data.account_head,comments.Comments
,pd1.img from resign_details resign_details left outer join 
 (select group_concat(Concat(pd1.EmployeeName,' [',resign_comment_log.type,']: '),`Comment`) as Comments, resign_comment_log.EmployeeID from  resign_comment_log inner join resign_details on resign_comment_log.EmployeeID = resign_details.EmployeeID  left outer join personal_details pd1 on resign_comment_log.CreatedBy = pd1.EmployeeID  group by resign_comment_log.EmployeeID) as comments on comments.EmployeeID = resign_details.EmployeeID left outer join whole_dump_emp_data on whole_dump_emp_data.EmployeeID = resign_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = resign_details.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where  ((resign_details.nt_end between '".$date_From."' and '".$date_To."') or (resign_details.nt_start between '".$date_From."' and '".$date_To."')) and whole_dump_emp_data.location in (".$location.")";
			$chk_task=$myDB->query($rquery);
		
			$my_error= $myDB->getLastError();
			
			$rg_status='';
			echo count($chk_task);
		if(count($chk_task) > 0 && $chk_task)
		{  
				$fileName = '';
				if($val['id']=="1")
				{
					$fileName = 'automail_resign_Noida.csv';
					
				}
				else if($val['id']=="3")
				{
					$fileName = 'automail_resign_Meerut.csv';
				}
				else if($val['id']=="4")
				{
					$fileName = 'automail_resign_Bareilly.csv';
				}
				else if($val['id']=="5")
				{
					$fileName = 'automail_resign_Vadodara.csv';
				}
				else if($val['id']=="6")
				{
					$fileName = 'automail_resign_Mangalore.csv';
				}
				else if($val['id']=="7")
				{
					$fileName = 'automail_resign_Bangalore.csv';
				}
				else if($val['id']=="8")
				{
					$fileName = 'automail_resign_Bangalore_Flipkart.csv';
				}
				
				$fp = fopen($fileName, 'w');
				
    			fputcsv($fp, $columnNames);
			foreach($chk_task as $key=>$value)
			{
				
				if($value['rg_status'] == '1')
				{
					$rg_status ='accepted';	
				}
				elseif($value['rg_status'] == '0' || empty($value['rg_status']))
				{
					$rg_status ='pending';	
				}
				elseif($value['rg_status'] == '9')
				{
					$rg_status='decline';	
				}
				else
				{
					$rg_status ='';
				}
				
				
				if($value['revoke_status'] == 1 && empty($value['revoke_accept']) && $value['rg_status'] != 1)
				{
					$revoke_status='Revoke Request to AH by Employee';	
				}
				elseif($value['revoke_status'] == 1 && $value['revoke_accept'] == 0 )
				{
					$revoke_status='Revoke Request cancel by AH';	
				}
				elseif($value['revoke_status'] == 1 && $value['revoke_accept'] == 1 && $value['rg_status'] != 1)
				{
					$revoke_status='Revoke Request accept by AH and refer to HR';	
				}
				elseif($value['revoke_status'] == 1 && $value['revoke_accept'] == 2 && $value['rg_status'] != 1)
				{
					$revoke_status='Revoke Request accept by AH and HR';	
				}
				elseif($value['revoke_status'] == 1 && $value['revoke_accept'] == 3 )
				{
					$revoke_status='Revoke Request accept by AH and cancel by HR';	
				}
				elseif($value['rg_status'] == 1 && $value['revoke_status'] == 1)
				{
					$revoke_status='Decline by server';
				}
				else
				{
					$revoke_status='';
				}
				
				if($value['accept'] == '1')
				{
					$acceptStatus ='accepted';	
				}
				elseif($value['accept'] == '0' || empty($value['accept']))
				{
					$acceptStatus='pending';	
				}
				else
				{
					$acceptStatus='';
				}
				$row1=array($value['EmployeeID'],$value['EmployeeName'],$value['nt_start'],$value['nt_end'],$rg_status,$revoke_status,$value['revoke_on'],$value['revoke_comment'],$value['revoke_ah'],$value['rv_ah_remark'],$value['revoke_hr'],$value['rv_hr_remark'],$acceptStatus,$value['remark'],$value['accepter_remark'],$value['designation'],$value['dept_name'],$value['DOJ'],$value['clientname'],$value['Process'],$value['sub_process'],$value['Supervisor'],$value['account_head'],$value['Comments']);
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
		$pagename='automail_resign';
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
				
		$mail->Subject = 'EMS '.$EMS_CenterName.', Resign Report ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
	
		$mysqlError = $myDB->getLastError();
		$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Resign Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
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