<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);

$sql = "select id,EmployeeID,HRRemark1,HRRemark2,HRRemark3,HRRemark4,HRRemark5,HRRemark6,HRRemark7,HRRemark8,HRRemark9,HRRemark10 from apprisalremarks where MasterId=? ";
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("i", $id);
$selectQ->execute();
$result = $selectQ->get_result();

if ($result->num_rows > 0 && $result) {
	foreach ($result as $key => $value) {
		foreach ($value as $k => $Score) {
			echo $Score . '|$|';
		}
	}
} else {
	echo "<script>$(function(){ toastr.error('Applicant Score does not exist.') }); </script>";
}
