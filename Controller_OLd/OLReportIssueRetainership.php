<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = $myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);
if (isset($user_logid)) {
	$EmpID = clean($_REQUEST['EmpID']);

	$Update = 'update doc_al_status set Retainership_Agreement=1 where EmployeeID=?';
	$select = $conn->prepare($Update);
	$select->bind_param("s", $EmpID);
	$select->execute();
	$myDB = $select->get_result();
	// $myDB->rawQuery($Update);
	if ($myDB->num_rows > 0) {
		echo 1;
	}
}
