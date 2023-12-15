<?php  
// Server Config file  
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
header("Content-Type: application/json; charset=UTF-8");
$Data=array();
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$alert_msg['msg']='';
$refertto='NA';
$approveByName=$refferedBy='';
$myDB= new MysqliDb();
ini_set('display_errors', '1');
if(isset($Data['appkey']) &&  $Data['appkey']== 'HTHea' )
{
					$remark='('.date('Y-m-d h:i:s').'):'.$Data['remark'];
		            $oldremark=$Data['oldremark'];
					$reqid =$Data['requestid'];
					$ApproveStatus=$Data['status'];
					$approveBy=$Data['approveBy'];
					$locationID=$Data['locationID'];
					$reqbyID=$Data['reqbyID'];
					$issue=$Data['query_detail'];
					$reqName=$Data['EmployeeName'];
					if(isset($Data['approveByName'])){
						$approveByName=$Data['approveByName'];
					}
			if($ApproveStatus == 'InProgress')
			{
	                $query='call inprog_issueticket("'.addslashes(trim($oldremark)).' | '.addslashes(trim($remark)).'","'.$reqid.'","'.$approveByName.'");';
					$result = $myDB->rawQuery($query);
					$mysql_error=$myDB->getLastError();
					$rowCount = $myDB->count;
					if(empty($mysql_error))
					{
						$result['status']=1;
						$result['msg']='Issue Request Updated.';	
						
					}
					else
					{
						$result['status']=0;
						$result['msg']='Request Not Updated "'.$mysql_error.'"';
					}
			}
			else 
			{
				$refertto=$Data['ReferToID'];
				if($refertto!="NA"){
					$myDB= new MysqliDb();
					$selectQuery=$myDB->query("SELECT ofc_emailid,mobile from  contact_details where EmployeeID='".$refertto."' ") ;
					if(isset($selectQuery[0]['ofc_emailid'])){
						$referEmailID=$selectQuery[0]['ofc_emailid'];
					}else{
						$referEmailID="";
					}
				}
			
		        $query='call check_issueticket("'.addslashes(trim($oldremark)).' | '.addslashes(trim($remark)).'","'.$reqid.'","'.$ApproveStatus.'","'.$refertto.'","'.$approveByName.'");';
				$result = $myDB->rawQuery($query);
				$mysql_error=$myDB->getLastError();
				$rowCount = $myDB->count;
				if(empty($mysql_error))
				{
					$result['status']=1;
					$result['msg']='Issue Request Updated.';	
					
				}
				else
				{
					$result['status']=0;
					$result['msg']='Request Not Updated "'.$mysql_error.'"';
				}			
					
					
					
			}
			
			
			if($approveBy!="" and $locationID!='')
			{
							  
				$myDB=new MysqliDb();
				$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='check_issue' and b.location ='".$locationID."'");		
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = EMAIL_HOST;       // Specify main and backup SMTP servers
				$mail->SMTPAuth = EMAIL_AUTH;   // Enable SMTP authentication
				$mail->Username = EMAIL_USER;  // SMTP username
				$mail->Password = EMAIL_PASS;  // SMTP password
				$mail->SMTPSecure = EMAIL_SMTPSecure;
				$mail->Port = EMAIL_PORT;
				$mail->setFrom(EMAIL_FROM, 'EMS:Cogent Grievance System');
				if(count($select_email_array) > 0 && $select_email_array)
				{
					foreach($select_email_array as $Key=>$val)
		        	{
		        		$email_address = $val['email_address'];
						
						if($email_address!=""){
							$mail->AddAddress($email_address);
						}
						$cc_email=$val['ccemail'];
						
						if($cc_email!=""){
							$mail->addCC($cc_email);
						}	
					}
				}
									
				if($refertto!='NA' && $referEmailID!="" )
				{		$mail->AddAddress($referEmailID);			
						$refferedBy="<b>Reffered By : </b>".$approveByName."(".$approveBy.")<br>";									
				}
																	
				 $refID_id = $reqbyID;
				if($ApproveStatus=='Reopen')
				{
					$ss1='Re-'.$ApproveStatus;
				}
				else
				{
					$ss1=$ApproveStatus;
				}
				
				if($locationID=="1")
				{
					$EMS_CenterName = "Noida";
				}
				else if($locationID=="2")
				{
					$EMS_CenterName = "Mumbai";
				}
				else if($locationID=="3")
				{
					$EMS_CenterName = "Meerut";
				}
				else if($locationID=="4")
				{
					$EMS_CenterName = "Bareilly";
				}
				else if($locationID=="5")
				{
					$EMS_CenterName = "Vadodara";
				}
				else if($locationID=="6")
				{
					$EMS_CenterName = "Mangalore";
				}
				else if($locationID=="7")
				{
					$EMS_CenterName = "Bangalore";
				}
				else if($locationID=="8")
				{
					$EMS_CenterName = "Bangalore Flipkart";
				}
		
				$mail->Subject = 'Happy to help '.$EMS_CenterName.', Reference #'.$refID_id.' :'.$ss1;
				
				$mail->isHTML(true);
				$myDB = new MysqliDb();
				$info_emp = $myDB->query('call get_info_for_Issue_tracker("'.$reqbyID.'")');
				
			
				 $pwd_='<span>Dear Sir,<br/><br/><span><b>Please find below the concern raised in happy to help.</b></span>.<br /><br/> <b>Concern Subject: '.$issue.'</b>.<br /><br /><b>Concern:</b> '.$oldremark.'.<br/><br/><br/>Concern Feedback : '.$Data['remark'].'<br/> Thank You</b>.<br/>Regard,<br/>'.strtoupper($reqName).'(<b>&nbsp;'.$reqbyID.'&nbsp;</b>)<br/><b>Designation  &nbsp;:&nbsp;</b>'.strtoupper($info_emp[0]['Designation']).'<br/><b>Process &nbsp;:&nbsp;</b>'.$info_emp[0]['Process'].'&nbsp;(&nbsp;'.$info_emp[0]['sub_process'].'&nbsp;)<br /><b>Account Head &nbsp;:&nbsp;</b>'.$info_emp[0]['AccountHead'].'<br /><b>Report To &nbsp;:&nbsp;</b>'.$info_emp[0]['ReportTo'].'<br />'.$refferedBy;
				
				$mail->Body = $pwd_;
				if(!$mail->send())
				 	{
				 		$module='APP: Happy to Help : Check Issue';
				 		$error_message=$mail->ErrorInfo;
						$error_log_add="call Add_email_error_log('".$module."','".$error_message."','".$approveBy."')";
						$myDB = new MysqliDb();
						$myDB->query($error_log_add);
				    
				  	} 
				else
				 {
			   		$module='Happy to Help : Check Issue';
			 		$error_message="email sent successfully";
					$error_log_add="call Add_email_error_log('".$module."','".$error_message."','".$approveBy."')";
					$myDB = new MysqliDb();
					$myDB->query($error_log_add);
				 }
		}		 		
}
else
{
	$result['status']=0;
	$result['msg']="Appkey is not match.";
}
echo json_encode($result);
?>