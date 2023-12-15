<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$myDB = new MysqliDb();
$connn = $myDB->dbConnect();

$action = clean($_GET['action']);
if (isset($action) and $action == 'sendmsg') {
	$message_text = clean($_GET['message']);
	$logid = clean($_GET['logid']);
	$logname = clean($_GET['logname']);
	$recipient_name = clean($_GET['empid']);

	$value = explode(",", $recipient_name);

	if ($recipient_name != "" && $message_text != "") {
		$myDB = new MysqliDb();
		foreach ($value as $key => $val) {
			$sql = "call Add_Chat_message('" . $message_text . "','" . trim($val) . "','" . $logid . "','" . $logname . "')";
			$resultBy = $myDB->query($sql);
			$error = $myDB->getLastError();
		}
		echo $tableValue = "<p>Message Send Successfully</p>";
	}
}
