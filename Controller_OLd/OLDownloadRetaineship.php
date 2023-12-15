<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);
$username = clean($_SESSION['__user_Name']);
if (isset($user_logid)) {
	$EmpID = clean($_REQUEST['EmpID']);


	$select = 'select download_history from doc_al_status where EmployeeID=?';
	$selectQury = $conn->prepare($select);
	$selectQury->bind_param("s", $EmpID);
	$selectQury->execute();
	$result = $selectQury->get_result();
	$download_hostory = $result->fetch_row();
	$download_text = $download_hostory[0] . ' , ' . $username . '[' . $user_logid . ']' . '(' . date('Y-m-d H:i:s') . ') Retainership_Agreement Downloaded';

	$update = 'update doc_al_status set Retainership_Agreement=1,download_history = ? where EmployeeID=?';
	$upQu = $conn->prepare($update);
	$upQu->bind_param("ss", $download_text, $EmpID);
	$upQu->execute();
	$result = $upQu->get_result();
	//echo 'update doc_al_status set Retainership_Agreement=1,download_history = "'.$download_text.'" where EmployeeID="'.$EmpID.'"';

}
