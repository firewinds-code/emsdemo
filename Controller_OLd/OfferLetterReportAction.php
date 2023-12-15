<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = $myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$EmpID = clean($_REQUEST['EmpID']);
$Comment = clean($_REQUEST['Comment']);
$validateBy = clean($_SESSION['__user_logid']);
if (isset($validateBy)) {

	$Update = 'update doc_al_status set validate=2,validateby=?,validatetime = now(),comment=? where EmployeeID=?';
	$select = $conn->prepare($Update);
	$select->bind_param("sss", $validateBy, $Comment, $EmpID);
	$select->execute();
	$myDB = $select->get_result();
	// $myDB->rawQuery($Update);
	if ($myDB->num_rows > 0) {
		echo 1;
	}
}
