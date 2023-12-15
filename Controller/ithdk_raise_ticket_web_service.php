<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 	

$Data=$_POST;
$response = array();
$response['msg']='';



	
if(isset($Data['appkey']) && $Data['appkey']=="raise_ticket" && isset($Data['handlerEmpId']) && !empty($Data['handlerEmpId']) && isset($Data['requesterEmpId']) && !empty($Data['requesterEmpId']) && isset($Data['client']) && !empty($Data['client']) && isset($Data['category']) && !empty($Data['category'])&& isset($Data['issueType']) && !empty($Data['issueType']) && isset($Data['location']) && !empty($Data['location']) && isset($Data['tat']) && !empty($Data['tat'])  && isset($Data['agentImpacted']) && !empty($Data['agentImpacted']) && isset($Data['totalAgents']) && !empty($Data['totalAgents']))
{
			
		
	 $handlerEmpId = $Data['handlerEmpId'];	
	 $requesterEmpId = $Data['requesterEmpId'];	
	 $priorty = $Data['priorty'];	
	 $processFinalNew = $Data['process'];	
	 $processList = $Data['client'];
	 $processClient = str_replace("[","",$Data['client']);
	 $processClient = str_replace("]","",$processClient);
	 $processClientList = str_replace(" ","",$processClient);
	 $processClientListArray = explode (",", $processClientList); 
	 $processClientListString = implode("','",$processClientListArray);; 
	 
		
	
	 //$process = $Data['process'];
	 $category = $Data['category'];
	 $issueType = $Data['issueType'];
	 $location = $Data['location'];
	 $Tat = $Data['tat'];
	 $AgentImpacted = $Data['agentImpacted'];
	 $TotalAgents = $Data['totalAgents'];
	 $IssueDesc = $Data['issueDisc'];
	 $requesterName = '';
	 $requesterEmail = '';
	 $requesterMobile = '';
	 $handlerName = $Data['handlerName'];
	 $handlerEmail = $Data['handlerEmail'];
	  $handlerMobile = $Data['handlerMobile'];	
	 
	// echo 'Aag ya';

		$myDB=new MysqliDb();
		 $selectReq="select p.EmployeeName , c.mobile, c.ofc_emailid   from personal_details p 
		left join contact_details c on  p.EmployeeID = c.EmployeeID where p.EmployeeID = '".$requesterEmpId."'";	
		$requDetails=$myDB->rawQuery($selectReq);
			
		
			if(empty($myDB->getLastError()) && count($requDetails) > 0){
				$requesterName = $requDetails[0]['EmployeeName'];
				$requesterEmail = $requDetails[0]['ofc_emailid'];
				$requesterMobile = $requDetails[0]['mobile'];
					

				$myDB=new MysqliDb();
				 $sql="Insert into ithdk_ticket_details(process_client, process, priorty, category, issue_type, issue_disc, total_agents, agent_impacted, requester_empId, requester_name, requester_email, requester_mobile, location, tat, issue_status, handler_empId, handler_name, handler_mobile, handler_email) VALUES ('".$processClient."','".$processFinalNew."','".$priorty."','".$category."','".$issueType."','".$IssueDesc."','".$TotalAgents."','".$AgentImpacted."','".$requesterEmpId."','".$requesterName."','".$requesterEmail."','".$requesterMobile."','".$location."','".$Tat."','Open','".$handlerEmpId."','".$handlerName."','".$handlerMobile."','".$handlerEmail."')";	
					
				$insert=$myDB->rawQuery($sql);	
				
				
			
			if(empty($myDB->getLastError())){
					
				$insertId = $myDB->getInsertId();
				$ticketId = '';
				$num_length = strlen((string)$insertId);
				
				
				
				switch($num_length){
					
					case 1: 
					$ticketId = date('Ymd').'000'.$insertId;
					break;
					
					case 2: 
					$ticketId = date('Ymd').'00'.$insertId;
					break;
					
					case 3: 
					$ticketId = date('Ymd').'0'.$insertId;
					break;
					
					case 4: 
					$ticketId = date('Ymd').$insertId;
					break;
					
					default : 
					
					 $lastNum = substr((string)$insertId, -4);
					 $ticketId = date('Ymd').$lastNum;
					break;
				}
				
				//Uppdate The Ticket In the Database
				$myDB=new MysqliDb();
				$updateQ="update ithdk_ticket_details set ticket_id = '".$ticketId."' where id = '".$insertId."'";	
				$update=$myDB->rawQuery($updateQ);
				
				
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
					
					$msg ="Dear <b>" .$empname.",</b><br/><br/>Greetings for the day!<br/><br/>Apologies for inconvenience caused to you.<br/><br/><br/><br/>We acknowledge your concern with <b>Ticket ID - ".$ticketId."</b> <br/><br/>Client :<b> ".$processClient."</b><br/>Location :<b> ".$location." </b><br/>TAT :<b> ".$Tat." Hour </b><br/>Process :<b>".$processFinalNew."</b><br/>Total Agents :<b>".$TotalAgents."</b><br/>Impacted Agents :<b>".$AgentImpacted."</b><br/><br/>Issue Description<b> : ".$IssueDesc."</b>";
					
						$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>'.$msg.'<br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Warm Regards,<br />'.strtoupper($handlerName).'<br/><br/>Central IT Helpdesk<br/><b>01204832560</b><div>';
					
					$mail->Body = $pwd_;
					
						
					if(!$mail->send())
					{
						$response['status']=0;
						$response['msg']='Ticket created but mail not sent.';
					} 
					else
					 {
						$response['status']=1;
						$response['msg']='Ticket created and mail sent successfully.';
					 }
					
					
					
				}else{
					$response['status']=0;
					$response['msg']='Issue created but ticket not raised, Please try again.';
				}	

			
				
		
			}else{
				$response['status']=0;
				$response['msg']='Invalid Data';
			}
				 
					
					
		}else{
			$response['status']=0;
			$response['msg']='Invalid Requester EmployeeID.';
		} 
	
    }else{
    	
    	$response['status']=0;
	    $response['msg']='Bad Request';
    }
  
 echo json_encode($response);       
 
 
 
 

?>