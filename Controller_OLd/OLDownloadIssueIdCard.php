<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);
$user_name = clean($_SESSION['__user_Name']);
if (isset($user_logid)) {
	$EmpID = clean($_REQUEST['EmpID']);

	$select = 'select download_history from doc_al_status where EmployeeID=?';
	$selectQury = $conn->prepare($select);
	$selectQury->bind_param("s", $EmpID);
	$selectQury->execute();
	$result = $selectQury->get_result();
	$download_hostory = $result->fetch_row();
	$download_text = $download_hostory[0] . ' , ' . $user_name . '[' . $user_logid . ']' . '(' . date('Y-m-d H:i:s', time()) . ') ID_Card Downloaded';


	$update = 'update doc_al_status set ID_Card=1,download_history = ? where EmployeeID=?';
	$upQu = $conn->prepare($update);
	$upQu->bind_param("ss", $download_text, $EmpID);
	$upQu->execute();
	$result = $upQu->get_result();
	if ($upQu->affected_rows === 1) {
		// if ($myDB->count > 0) {
		echo 1;
	}
}
