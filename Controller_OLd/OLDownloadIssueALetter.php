<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);
$user_name = clean($_SESSION['__user_Name']);
$EmpID = clean($_REQUEST['EmpID']);
if (isset($user_logid)) {
	$select = 'select download_history from doc_al_status where EmployeeID=?';
	$selectQury = $conn->prepare($select);
	$selectQury->bind_param("s", $EmpID);
	$selectQury->execute();
	$download_hostory = $selectQury->get_result();
	$download = $download_hostory->fetch_row();
	$download_text = $download[0] . ' , ' . $user_name . '[' . $user_logid . ']' . '(' . date('Y-m-d H:i:s', time()) . ') Appointment_Letter Downloaded';

	$update = 'update doc_al_status set Appointment_Letter=1,download_history = ? where EmployeeID=?';
	$updateQ = $conn->prepare($update);
	$updateQ->bind_param("ss", $download_text, $EmpID);
	$updateQ->execute();
	$result = $updateQ->get_result();
	if ($updateQ->affected_rows === 1) {
		// if ($myDB->count > 0) {
		echo 1;
	}
}
