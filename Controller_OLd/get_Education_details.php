<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
ini_set('display_errors', 0);

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmployeeID = clean($_REQUEST['ID']);
$type = clean($_REQUEST['type']);
$year = 0;
if ($type == '12th') {
	$sql = 'select passing_year from education_details where EmployeeID=? and  edu_name="10th"';
	$selectQ = $conn->prepare($sql);
	$selectQ->bind_param("s", $EmployeeID);
	$selectQ->execute();
	$result = $selectQ->get_result();

	foreach ($result  as $key => $value) {
		$year = $value['passing_year'];
	}
} else if ($type == '10th') {

	$sql = 'select passing_year from education_details where EmployeeID=? and  edu_name="12th"';
	$selectQ = $conn->prepare($sql);
	$selectQ->bind_param("s", $EmployeeID);
	$selectQ->execute();
	$result = $selectQ->get_result();
	foreach ($result  as $key => $value) {
		$year = $value['passing_year'];
	}
}
echo $year;
