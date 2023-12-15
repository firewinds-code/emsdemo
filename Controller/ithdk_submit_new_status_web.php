<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
// Main contain Header file which contains html , head , body , one default form 	
$Data=$_POST;
$response = array();
$response['msg']='';

	
if(isset($Data['appkey']) && $Data['appkey']=="submitStatus" && isset($Data['rowId']) && !empty($Data['rowId']) && isset($Data['currStatus']) && !empty($Data['currStatus']) && isset($Data['client']) && !empty($Data['client']) && isset($Data['newStatus']) && !empty($Data['newStatus'])&& isset($Data['remark']) && !empty($Data['remark'])&& isset($Data['handlerEmail']) && isset($Data['requesterEmail']) && isset($Data['location']) && !empty($Data['location']) )
{
			
	 $rowid = $Data['rowId'];
	 $currStatus = $Data['currStatus'];
	 $newStatus = $Data['newStatus'];
	 $remark = $Data['remark'];
	 
	 //Mail Related Var
	 $handlerEmail = $Data['handlerEmail'];
	 $requesterEmail = $Data['requesterEmail'];
	 $requesterName  = $Data['requesterName'];
	 $handlerName  = $Data['handlerName'];
	 $ticketId  = $Data['ticketId'];
	 $location  = $Data['location'];
	 $tat  = $Data['tat'];
	 $ExtTat  = $Data['extTat'];
	 $handlerRemark  = $Data['remark'];
	 $processClientList = str_replace(" ","",$Data['client']);
	 $processClientListArray = explode (",", $processClientList); 
	 $processClientListString = implode("','",$processClientListArray);
	 $totalTat =(int)$tat+(int)$ExtTat;
	 
	$isNewStatusInProg = 0;
	

	
	if(strtoupper($newStatus) == 'INPROGRESS'){
		$isNewStatusInProg = 1;
		
	}else{
		$isNewStatusInProg = 0;
		
	}
	
	//GET Details from Row ID/////////////////
	$myDB=new MysqliDb();
	$getDetail="SELECT id, ticket_id, process_client, process, priorty, category, issue_type, issue_disc, total_agents, agent_impacted, requester_empId, requester_name, requester_email, requester_mobile, location, tat, exten_tat, issue_status, handler_empId, handler_name, handler_mobile, handler_email, inprogress_remark, inprogress_date, closing_remark, closing_date, rca_text, rca_attachement, rca_date, created_date FROM ithdk_ticket_details where id ='".$rowid."' limit 1;";	
	$allDetails=$myDB->rawQuery($getDetail);
	
	if(empty($myDB->getLastError()) && count($allDetails) > 0 ){
		
	
	$myDB=new MysqliDb();
	$sql="";
	
	if($isNewStatusInProg == 1){
		$sql="update ithdk_ticket_details set inprogress_remark = '".$remark."' , inprogress_date = now() , exten_tat='".$ExtTat."', issue_status = '".$newStatus."' where id = '".$rowid."';";
	}else{
		$sql="update ithdk_ticket_details set closing_remark = '".$remark."' , closing_date = now() , issue_status = '".$newStatus."' where id = '".$rowid."';";
	}
	
	
		
		
		$result=$myDB->rawQuery($sql);
		
		
		
				//Get The List Ah, Vh Along With The Default Mail Address To Send Depending Upon Adddress.
				
				//AH/////////////////
				$myDB=new MysqliDb();
				$getAh="SELECT distinct  cd.ofc_emailid as AHEmail FROM ems.new_client_master nc inner join client_master c on nc.client_name=c.client_id left join contact_details cd on account_head=cd.EmployeeID where  c.client_name in ('".$processClientListString ."');";	
				$ahReults=$myDB->rawQuery($getAh);
				
				//VH/////////////////
				$myDB=new MysqliDb();
				$getVH="SELECT distinct cd.ofc_emailid as vhEmail FROM ems.new_client_master nc inner join client_master c on nc.client_name=c.client_id left join contact_details cd on vh=cd.EmployeeID where  c.client_name in ('".$processClientListString ."');";	
				$vhReults=$myDB->rawQuery($getVH);
				
				
				//IT Persons EMAil/////////////////
				$myDB=new MysqliDb();
				$getIT="SELECT  email, emailType, location FROM ems.ithdk_master_email_address  where location  like '".$location."'";	
				$itPersonRes=$myDB->rawQuery($getIT);
				
				
				//Send Email eqarding Ticket.
				if(empty($myDB->getLastError())){
					
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = 'mail.cogenteservices.com'; 
					$mail->SMTPAuth = EMAIL_AUTH;
					$mail->Username = 'central.ithelpdesk@cogenteservices.com';   
					$mail->Password = 'Secure#123';                        
					$mail->SMTPSecure = EMAIL_SMTPSecure;
					$mail->Port = EMAIL_PORT; 
					$mail->setFrom('central.ithelpdesk@cogenteservices.com', 'Cogent : Central IT Help Desk');
					
					
					if(!empty($requesterEmail))
					$mail->addBCC($requesterEmail);
					if(!empty($handlerEmail))
					$mail->addBCC($handlerEmail);
					
					 //Adding Ah to the Mail
					if(count($ahReults) > 0){
						foreach($ahReults as $Key=>$val)
						{
							$email_address = $val['AHEmail'];
							if($email_address!=""){
							$mail->addBCC($email_address);
							}
						
						}
					}
					
					//Adding VH to the Mail
					
					if(count($vhReults) > 0){
						foreach($vhReults as $Key=>$val)
						{
							$email_address = $val['vhEmail'];
							if($email_address!=""){
							$mail->addBCC($email_address);
							}
						}
					}
					
					
					//Adding It Persons to The Mail
					if(count($itPersonRes) > 0){
						foreach($itPersonRes as $Key=>$val)
						{
							
							$email_address = $val['email'];
							if($email_address!=""){
								
								if(strtoupper($val['emailType']) == 'TO'){
									$mail->addBCC($email_address);
								}else{
									$mail->addBCC($email_address);
								}
							}
						}
					} 
					
					
					
					$mail->Subject = "IT Helpdesk Ticket - ".$ticketId;
					$mail->isHTML(true);
					$empname = $requesterName;
					$msg ='';
					
					
					//If Request For Inprogress Then Send MAil According to In Progress.
					if(strtoupper($newStatus) == 'INPROGRESS'){
						
						/////////////////////OLD MAil Content OPEN
						$msgOld ="<br/><br/><hr><hr><b> OPEN STATUS EMAIL - ".$allDetails[0]["created_date"]." </b><br/><br/><br/>Dear <b>" .$empname.",</b><br/><br/>Greetings for the day!<br/><br/>Apologies for inconvenience caused to you.<br/><br/><br/><br/>We acknowledge your concern with <b>Ticket ID - ".$ticketId."</b> <br/><br/>Client :<b> ".$processClientListString."</b><br/>Location :<b> ".$location." </b><br/>TAT :<b> ".$tat." Hour </b><br/>Process :<b>".$allDetails[0]["process"]."</b><br/>Total Agents :<b>".$allDetails[0]["total_agents"]."</b><br/>Impacted Agents :<b>".$allDetails[0]["agent_impacted"]."</b><br/><br/>Issue Description<b>: ".$allDetails[0]["issue_disc"]."</b>";
						
						
						$msg ="Dear <b>" .$empname.",</b><br/><br/>Greetings for the day!<br/><br/><br/>Update Reference <b>Ticket ID - ".$ticketId."</b> <br/><br/>TAT :<b> ".$totalTat." Hour </b> <br/>Location :<b> ".$location." </b> <br/>Client :<b> ".$processClientListString." </b> <br/><br/>Handler Remark :<b> ".$handlerRemark."</b><br/><br/>".$msgOld ;
					}else{
							
						$msgOld ="";	
						if($allDetails[0]["inprogress_date"] != null && !empty($allDetails[0]["inprogress_date"])){
							/////////////////////OLD MAil INPROGRESS Content
							$msgOld ="<br/><br/><hr><hr><b> INPROGRESS STATUS EMAIL - ".$allDetails[0]["inprogress_date"]." </b><br/><br/><br/>Dear <b>" .$empname.",</b><br/><br/>Greetings for the day!<br/><br/><br/>Update Reference <b>Ticket ID - ".$ticketId."</b> <br/><br/>TAT :<b> ".$totalTat." Hour </b> <br/>Location :<b> ".$location." </b> <br/>Client :<b> ".$processClientListString." </b> <br/><br/>Handler Remark :<b> ".$allDetails[0]["inprogress_remark"]."</b><br/><br/>";
						}
						
						/////////////////////OLD MAil Content OPEN
						$msgOld =$msgOld."<br/><br/><hr><hr><b> OPEN STATUS EMAIL - ".$allDetails[0]["created_date"]."</b><br/><br/><br/>Dear <b>" .$empname.",</b><br/><br/>Greetings for the day!<br/><br/>Apologies for inconvenience caused to you.<br/><br/><br/><br/>We acknowledge your concern with <b>Ticket ID - ".$ticketId."</b> <br/><br/>Client :<b> ".$processClientListString."</b><br/>Location :<b> ".$location." </b><br/>TAT :<b> ".$tat." Hour </b><br/>Process :<b>".$allDetails[0]["process"]."</b><br/>Total Agents :<b>".$allDetails[0]["total_agents"]."</b><br/>Impacted Agents :<b>".$allDetails[0]["agent_impacted"]."</b><br/><br/>Issue Description<b> : ".$allDetails[0]["issue_disc"]."</b>";
						
						
						$msg ="Dear <b>" .$empname.",</b><br/><br/>Greetings for the day!<br/><br/><br/><b>Ticket ID - ".$ticketId."</b> Has been closed please find the closer remark mentioned below. <br/><br/>Client :<b>".$processClientListString."</b><br/><br/>Location :<b> ".$location."</b><br/>TAT :<b> ".$totalTat." Hour </b> <br/><br/>Handler Remark :<b> ".$handlerRemark."</b><br/><br/>".$msgOld;
						
						
						
					}
					
						$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>'.$msg.'<br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Warm Regards,<br />'.strtoupper($handlerName).'<br/><br/>Central IT Helpdesk<br/><b>01204832560</b><div>';
					
					$mail->Body = $pwd_;
					
		
					if (empty($myDB->getLastError()) && $mail->send())
					{

						$response['status']=1;
						$response['msg']='  Status updated Successfully.';
						
					}else{
						$response['status']=0;
						$response['msg']='Status updated but mail not sent.';
						
					}
				}else{
					$response['status']=0;
					$response['msg']='Status updated but mail not sent.';
				}
		
		
	}else{
		$response['status']=0;
		$response['msg']='Invalid Request.';
	}
	
}else{
	
	$response['status']=0;
	$response['msg']='Bad Request';
}
  
 echo json_encode($response);       

?>