<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$EmpID = clean($_REQUEST['EmpID']);
$validateBy = clean($_SESSION['__user_logid']);
if (isset($validateBy)) {

	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$update = 'update doc_al_status set handover=1,handoverby=?,handovertime = now() where EmployeeID=?';
	$upQu = $conn->prepare($update);
	$upQu->bind_param("ss", $validateBy, $EmpID);
	$upQu->execute();
	$result = $upQu->get_result();
	//echo 'update doc_al_status set handover=1,handoverby="'.$validateBy.'",handovertime = now() where EmployeeID="'.$EmpID.'"';
	if ($upQu->affected_rows === 1) {
		// if ($myDB->count > 0) {
		echo 1;
	}
}
