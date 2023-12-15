<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$ID = clean($_REQUEST['ID']);

$sql = 'delete from trainee_doc where ID=?';
$del = $conn->prepare($sql);
$del->bind_param("i", $ID);
$del->execute();
$result = $del->get_result();
// $myDB = new MysqliDb();
// $result = $myDB->query($sql);
// $mysql_error = $myDB->getLastError();
// if (empty($mysql_error)) {
if ($del->affected_rows === 1) {
	echo "done|Document Deleted Affected Row are :" . $result->num_rows;
	$file = clean($_REQUEST['file']);
	if (unlink(ROOT_PATH . 'TraineeDocs/' . $file)) {
		echo '<b>file Deleted Successfully</b>';
	} else {
		echo '<b>file not deleted</b>';
	}
} else {
	echo "Row Not Deleted Try Again :";
}
