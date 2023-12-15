<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = $myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);
if (isset($user_logid)) {
	$EmpID = clean($_REQUEST['EmpID']);
	$Update = 'update doc_al_status set ID_Card=1 where EmployeeID=?';
	$update = $conn->prepare($updateres);
	$update->bind_param("s", $EmpID);
	$update->execute();
	$results = $update->get_result();
	if ($update->affected_rows === 1) {
		echo 1;
	}
}
