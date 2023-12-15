<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);

$sql = 'delete from education_details where edu_id=?';
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("i", $id);
$selectQ->execute();
$result = $selectQ->get_result();
$file = clean($_REQUEST['file']);
$document = clean($_SERVER['DOCUMENT_ROOT']);
if (isset($file) && $file != "") {
	if (file_exists($document . "/ems/Education/" . $file)) {
		if (unlink($document . "/ems/Education/" . $file)) {
			echo 'done|<b>file Deleted Successfully</b>';
		} else {
			echo '<b>file not deleted</b>';
		}
	}
} else {
	echo "Row Not Deleted Try Again :";
}
