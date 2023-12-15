<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

$edu =  clean($_REQUEST['edu']);
$empid =  clean($_REQUEST['empid']);
$mobile = $emailid = '';
$empid1 = urlencode(base64_encode($empid));
$sql = 'update emp_edu set edu_type="' . $edu . '",modifiedon=now(),flag=1 where EmpID="' . $empid . '" ';
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$conn = $myDB->dbConnect();
$mysql_error = $myDB->getLastError();
if (empty($mysql_error)) {
	//sleep(5);
	echo '1';

	$sqlquery = "select mobile,emailid from contact_details where EmployeeID=?";
	$selectQ = $conn->prepare($sqlquery);
	$selectQ->bind_param("s", $empid);
	$selectQ->execute();
	$results = $selectQ->get_result();
	$result = $results->fetch_row();
	// $result = $myDB->query($sqlquery);
	if ($results->num_rows > 0) {
		$mobile = $result[0];
		$emailid = $result[1];
	}
	if ($emailid != '') {
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = EMAIL_HOST;
		$mail->SMTPAuth = EMAIL_AUTH;
		$mail->Username = EMAIL_USER;
		$mail->Password = EMAIL_PASS;
		$mail->SMTPSecure = EMAIL_SMTPSecure;
		$mail->Port = EMAIL_PORT;
		$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
		$mail->AddAddress($emailid);
		$mail->Subject = 'Document Submission';
		$mail->isHTML(true);
		$smslink = "https://ems.cogentlab.com/erpm/View/upload_emp_edu_self.php?empid=" . $empid1;
		$body_ = '<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Hi, Please click on the link below to upload your document. <br />'  . $smslink . '.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />' . 'Regards,<br /><br/> EMS Mailer Services.<div>';
		$mail->Body = $body_;
		if (!$mail->send()) {
			//echo '.Mailer Error:' . $mail->ErrorInfo;
		} else {

			//echo  '.Mail Send successfully.';
		}
	}

	if ($mobile != '') {
		$textmsg = 'Hi, Please click on the link https://ems.cogentlab.com/erpm/View/upload_emp_edu_self.php?empid=' . $empid1 . ' to upload your document. Thanks Cogent';
		$msg = [];
		$msg['type'] = 'text';
		$msg['text'] = $textmsg;
		$response = sendwhatappmsg($msg, $mobile);
	}
} else {
	echo '2';
}

function sendwhatappmsg($msg, $whatsapp_no)
{
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.gupshup.io/sm/api/v1/msg',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => 'channel=whatsapp&source=911204832550&destination=' . $whatsapp_no . '&message=' . urlencode(json_encode($msg)) . '&src.name=CogentSupport',
		CURLOPT_HTTPHEADER => array(
			'Cache-Control: no-cache',
			'Content-Type: application/x-www-form-urlencoded',
			'apikey: pib0jql0dhryaoufencgu4imo5e0gu4l',
			'cache-control: no-cache'
		),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
