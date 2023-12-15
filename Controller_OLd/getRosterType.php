<?php

require_once(__dir__ . '/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
ini_set('display_errors', 0);

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$Date = clean($_REQUEST['Date']);
if ($Date != '' && $Date != null) {
	$dd = date('Y-n-j', strtotime($Date));
} else {
	$dd = date('Y-n-j', time());
}
//$dd = date('Y-n-j',strtotime($_REQUEST['Date']));
$EmpID = clean($_REQUEST['EmpID']);
if (isset($_REQUEST)) {

	$sql = 'select type_ from roster_temp where EmployeeID = ? and DateOn =? order by id desc limit 1';
	$selectQ = $conn->prepare($sql);
	$selectQ->bind_param("ss", $EmpID, $dd);
	$selectQ->execute();
	$result = $selectQ->get_result();
	$rst = $result->fetch_row();
	if ($result->num_rows > 0 && $result) {
		if (intval($rst[0]) != 0) {
			echo is_numeric($rst[0]);
		} else {
			echo 1;
		}
	} else {
		echo 1;
	}
} else {
	echo 1;
}
