<?php

require_once(__dir__ . '/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
include_once("../Services/sendsms_API1.php");
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

$myDB = new MysqliDb();

// $_REQUEST['seqans'] = '';
// $_REQUEST['reqtype'] = "";
// echo ('call get_password("' . $_REQUEST['id'] . '","' . $_REQUEST['seqans'] . '")');
$result = $myDB->rawQuery('call get_password("' . $_REQUEST['id'] . '","' . $_REQUEST['seqans'] . '")');
$mysql_error = $myDB->getLastError();

if (count($result) > 0 && $result) {
    // $_SESSION['code'] = $_REQUEST['code'];
    // echo  $code = $_REQUEST['code'];
    if (isset($_REQUEST['seqans']) && $_REQUEST['seqans'] != "") {
        // $empid = $_POST['txt_pwd_empid'];
        $sel = 'select mobile,emailid from contact_details where EmployeeID="' . $_REQUEST['id'] . '"';
        $myDB = new MysqliDb();
        $result = $myDB->rawQuery($sel);
        $mobilenum = $result[0]['mobile'];
        $emailid = $result[0]['emailid'];
        if (!empty($emailid)) {
            $otpNum = $_REQUEST['code'];
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = EMAIL_HOST;
            $mail->SMTPAuth = EMAIL_AUTH;
            $mail->Username = EMAIL_USER;
            $mail->Password = EMAIL_PASS;
            $mail->SMTPSecure = EMAIL_SMTPSecure;
            $mail->Port = EMAIL_PORT;
            $mail->setFrom(EMAIL_FROM,  'Cogent | Password Recovery');
            $mail->AddAddress($emailid);
            $mail->Subject = "Password Recovery";
            $mail->isHTML(true);
            $msg2 = "Hi, <br/><br/>
            To reset your password, please use the following One-Time Password (OTP) : <br/><br/>
            OTP : $otpNum <br/><br/>
            Thanks <br/>
            Team Cogent ";
            // Please use OTP $otpNum for verification purpose - COGENT";
            $mail->Body = $msg2;
            $mymsg = '';
            if (!$mail->send()) {
                echo 0;
            } else {
                echo 1;
                $response =  'Mail Send successfully';
            }
        }
        if (!empty($mobilenum)) {
            $otpNum = $_REQUEST['code'];
            $templateid = '1707165190621182046';
            $msg = "Please use OTP $otpNum for verification purpose - COGENT";
            $url = SMS_URL;
            $token = SMS_TOKEN;
            $credit = SMS_CREDIT;
            $sender = SMS_SENDER;
            $message = $msg;
            $number = $mobilenum;
            $sendsms = new sendsms($url, $token);
            $message_id = $sendsms->sendmessage($credit, $sender, $message, $number, $templateid);
        }
    }
    foreach ($result as $key => $value) {
        echo $value['password'];
    }
} else {
    echo 0;
}
