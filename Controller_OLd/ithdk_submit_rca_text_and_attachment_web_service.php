<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 	
ini_set('display_errors', '1');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$Data = $_POST;
$response = array();
$response['msg'] = '';

/* print_r($Data);
die; */




if (isset($Data['appkey']) && $Data['appkey'] == "submitRca" && isset($Data['rcaText']) && !empty($Data['rcaText']) && isset($Data['isAttachement']) && !empty($Data['isAttachement']) && isset($Data['client']) && !empty($Data['client']) && isset($Data['rowId']) && !empty($Data['rowId']) && isset($Data['handlerEmpId']) && !empty($Data['handlerEmpId']) && isset($Data['handlerEmail']) && isset($Data['requesterEmail']) && isset($Data['location']) && !empty($Data['location'])) {

	$rowid = $Data['rowId'];
	$handlerEmpId = $Data['handlerEmpId'];
	$rcaText = $Data['rcaText'];
	$isAttachment = $Data['isAttachement'];

	//Mail Related Var
	$handlerEmail = $Data['handlerEmail'];
	$requesterEmail = $Data['requesterEmail'];
	$requesterName  = $Data['requesterName'];
	$handlerName  = $Data['handlerName'];
	$ticketId  = $Data['ticketId'];
	$location  = $Data['location'];
	$dir_locationToSave = "";
	$processClientList = str_replace(" ", "", $Data['client']);
	$processClientListArray = explode(",", $processClientList);
	$processClientListString = implode("','", $processClientListArray);

	$fileNameFinal = "";



	$sql = "";

	//GET Details from Row ID/////////////////

	$getDetail = "SELECT id, ticket_id, process_client, process, priorty, category, issue_type, issue_disc, total_agents, agent_impacted, requester_empId, requester_name, requester_email, requester_mobile, location, tat, exten_tat, issue_status, handler_empId, handler_name, handler_mobile, handler_email, inprogress_remark, inprogress_date, closing_remark, closing_date, rca_text, rca_attachement, rca_date, created_date FROM ithdk_ticket_details where id =? limit 1;";
	$sql = $conn->prepare($getDetail);
	$sql->bind_param("i", $rowid);
	$sql->execute();
	$allDetail = $sql->get_result();
	$allDetails = $allDetail->fetch_row();

	// $allDetails = $myDB->rawQuery($getDetail);

	if ($allDetail->num_rows > 0) {

		if (isset($_FILES['attachFile']) &&  !empty($_FILES['attachFile']) && $isAttachment == 'yes') {
			$fileName  =  $_FILES['attachFile']['name'];
			$tempPath  =  $_FILES['attachFile']['tmp_name'];
			$fileSize  =  $_FILES['attachFile']['size'];

			$upload_path = 'uploads/'; // set upload folder path 
			$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get  extension
			$fileNameFinal = $handlerEmpId . '_' . date('Y-m-d_His') . '.' . $fileExt;

			// check file size '5MB'
			if ($fileSize < 5000000) {
				$dir_locationToSave = __DIR__ . '/../Upload/';
				if (move_uploaded_file($tempPath, $dir_locationToSave . $fileNameFinal)) { // move file from system temporary 	path

					$sql = "update ithdk_ticket_details set rca_text = ? , rca_date = now() , rca_attachement = ? where id = ?";
					$update = $conn->prepare($sql);
					$update->bind_param("ssi", $rcaText, $fileNameFinal, $rowid);
					$update->execute();
					$result = $update->get_result();
				} else {
					$result['status'] = 0;
					$result['msg'] = "Can not save your data, Please try again.";
				}
			} else {
				$result['status'] = 0;
				$result['msg'] = "Submission failed, Attachment size should not be greater than 5 MB.";
			}
		} else {
			$sql = "update ithdk_ticket_details set rca_text = ? , rca_date = now() where id = ?";
			$update = $conn->prepare($sql);
			$update->bind_param("si", $rcaText, $rowid);
			$update->execute();
			$result = $update->get_result();
		}

		if (!empty($sql)) {

			// $result = $myDB->rawQuery($sql);

			//Get The List Ah, Vh Along With The Default Mail Address To Send Depending Upon Adddress.

			//AH/////////////////

			$getAh = "SELECT distinct  cd.ofc_emailid as AHEmail FROM ems.new_client_master nc inner join client_master c on nc.client_name=c.client_id left join contact_details cd on account_head=cd.EmployeeID where  c.client_name in (?);";
			$sel = $conn->prepare($getAh);
			$sel->bind_param("s", $processClientListString);
			$sel->execute();
			$ahReults = $sel->get_result();
			// $ahReults = $myDB->rawQuery($getAh);

			//VH/////////////////

			$getVH = "SELECT distinct cd.ofc_emailid as vhEmail FROM ems.new_client_master nc inner join client_master c on nc.client_name=c.client_id left join contact_details cd on vh=cd.EmployeeID where  c.client_name in (?);";
			$select = $conn->prepare($getVH);
			$select->bind_param("s", $processClientListString);
			$select->execute();
			$vhReults = $select->get_result();
			// $vhReults = $myDB->rawQuery($getVH);


			//IT Persons EMAil/////////////////
			$like = '%' . $location . '%';
			$getIT = "SELECT  email, emailType, location FROM ems.ithdk_master_email_address  where location  like ?";
			$selects = $conn->prepare($getIT);
			$selects->bind_param("i", $like);
			$selects->execute();
			$itPersonRes = $selects->get_result();
			// $itPersonRes = $myDB->rawQuery($getIT);


			//Send Email eqarding Ticket.
			if (empty($myDB->getLastError())) {

				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = 'mail.cogenteservices.com';
				$mail->SMTPAuth = EMAIL_AUTH;
				$mail->Username = 'central.ithelpdesk@cogenteservices.com';
				$mail->Password = 'Secure#123';
				$mail->SMTPSecure = EMAIL_SMTPSecure;
				$mail->Port = EMAIL_PORT;
				$mail->setFrom('central.ithelpdesk@cogenteservices.com', 'Cogent : Central IT Help Desk');

				if (!empty($requesterEmail))
					$mail->addBCC($requesterEmail);
				if (!empty($handlerEmail))
					$mail->addBCC($handlerEmail);

				//Adding Ah to the Mail

				if ($ahReults->num_rows > 0) {
					foreach ($ahReults as $Key => $val) {
						$email_address = $val['AHEmail'];
						if ($email_address != "") {
							$mail->addBCC($email_address);
						}
					}
				}

				//Adding VH to the Mail

				if ($vhReults->num_rows > 0) {
					foreach ($vhReults as $Key => $val) {
						$email_address = $val['vhEmail'];
						if ($email_address != "") {
							$mail->addBCC($email_address);
						}
					}
				}


				//Adding It Persons to The Mail
				if ($itPersonRes->num_rows > 0) {
					foreach ($itPersonRes as $Key => $val) {

						$email_address = $val['email'];
						if ($email_address != "") {

							if (strtoupper($val['emailType']) == 'TO') {
								$mail->addBCC($email_address);
							} else {
								$mail->addBCC($email_address);
							}
						}
					}
				}




				$mail->Subject = "IT Helpdesk Ticket - " . $ticketId;
				$mail->isHTML(true);

				//Add Attachment If Added
				if (isset($_FILES['attachFile']) &&  !empty($_FILES['attachFile']) && $isAttachment == 'yes') {
					$mail->addAttachment($dir_locationToSave . $fileNameFinal);
				}
				$empname = $requesterName;

				$msgOld = "";

				/////////////////////OLD MAil CLOSE Content
				if ($allDetails[25] != null && !empty($allDetails[25])) {

					$msgOld = "<br/><br/><hr><hr><b> CLOSE STATUS EMAIL - " . $allDetails[25] . " </b><br/><br/><br/>Dear <b>" . $empname . ",</b><br/><br/>Greetings for the day!<br/><br/><br/><b>Ticket ID - " . $ticketId . "</b> Has been closed please find the closer remark mentioned below. <br/><br/>Client :<b>" . $processClientListString . "</b><br/><br/>Location :<b>" . $location . "</b><br/>TAT :<b> " . $totalTat . " Hour </b> <br/><br/>Handler Remark :<b> " . $allDetails[24] . "</b><br/><br/>";
				}

				/////////////////////OLD MAil INPROGRESS Content
				if ($allDetails[23] != null && !empty($allDetails[23])) {
					$totaltat = (int)$allDetails[15] + (int)$allDetails[16];

					$msgOld = $msgOld . "<br/><br/><hr><hr><b> INPROGRESS STATUS EMAIL - " . $allDetails[23] . "</b><br/><br/><br/>Dear <b>" . $empname . ",</b><br/><br/>Greetings for the day!<br/><br/><br/>Update Reference <b>Ticket ID - " . $ticketId . "</b> <br/><br/>TAT :<b> " . $totaltat . " Hour </b> <br/>Location :<b> " . $location . " </b> <br/>Client :<b> " . $processClientListString . " </b> <br/><br/>Handler Remark :<b> " . $allDetails[22] . "</b><br/><br/>";
				}

				/////////////////////OLD MAil Content OPEN
				$msgOld = $msgOld . "<br/><br/><hr><hr><b> OPEN STATUS EMAIL - " . $allDetails[29] . "</b><br/><br/><br/>Dear <b>" . $empname . ",</b><br/><br/>Greetings for the day!<br/><br/>Apologies for inconvenience caused to you.<br/><br/><br/><br/>We acknowledge your concern with <b>Ticket ID - " . $ticketId . "</b> <br/><br/>Client :<b> " . $processClientListString . "</b><br/>Location :<b> " . $location . " </b><br/>TAT :<b> " . $allDetails[15] . " Hour </b><br/>Process :<b>" . $allDetails[3] . "</b><br/>Total Agents :<b>" . $allDetails[8] . "</b><br/>Impacted Agents :<b>" . $allDetails[9] . "</b><br/><br/>Issue Description<b> : " . $allDetails[7] . "</b>";

				$msg = "Dear <b>" . $empname . ",</b><br/><br/>Greetings for the day!<br/><br/><br/>Please find the RCA against the <b>Ticket ID - " . $ticketId . "</b> <br/><br/>Client :<b> " . $processClientListString . "</b><br/><br/>Location :<b> " . $location . "</b><br/><br/>RCA : <b>" . $rcaText . "</b>" . $msgOld;

				$pwd_ = '<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>' . $msg . '<br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />' . 'Warm Regards,<br />' . strtoupper($handlerName) . '<br/><br/>Central IT Helpdesk<br/><b>01204832560</b><div>';

				$mail->Body = $pwd_;


				if (empty($myDB->getLastError()) && $mail->send()) {
					$response['status'] = 1;
					$response['msg'] = 'RCA submitted successfully.';
				} else {
					$response['status'] = 0;
					$response['msg'] = 'RCA  submition failed.';
				}
			} else {
				$response['status'] = 0;
				$response['msg'] = 'Status updated but mail not sent.';
			}
		} else {
			$response['status'] = 0;
			$response['msg'] = 'Server error, please try again later.';
		}
	} else {
		$response['status'] = 0;
		$response['msg'] = 'Invalid Request.';
	}
} else {

	$response['status'] = 0;
	$response['msg'] = 'Bad Request';
}

echo json_encode($response);
