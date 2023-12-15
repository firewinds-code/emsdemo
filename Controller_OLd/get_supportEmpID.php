<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$ID = clean($_REQUEST['id']);
$sql = "select emp_status,df_id from employee_map where EmployeeID=?";
$sel = $conn->prepare($sql);
$sel->bind_param("i", $ID);
$sel->execute();
$result = $sel->get_result();
$res = $result->fetch_row();
// $result = $myDB->query($sql);
// $mysql_error = $myDB->getLastError();
$res = 0;
if ($result->num_rows > 0 && $result) {
	if (strtolower($res[0]) == 'inactive') {
		$res = 1;
	} else if ($res[1] == '74' || $res[1] == '77') {
		$res = 2;
	}
} else {
	$res = 3;
}

echo $res;
