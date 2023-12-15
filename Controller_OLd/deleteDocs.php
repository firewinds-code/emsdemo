<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$ID = clean($_REQUEST['ID']);
$sql = 'delete from doc_details where doc_id=?';
// $myDB = new MysqliDb();
// $result = $myDB->rawQuery($sql);
// $row_count = $myDB->count;
// $mysql_error = $myDB->getLastError();
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("i", $ID);
$selectQ->execute();
$res = $selectQ->get_result();
//echo $_SERVER['DOCUMENT_ROOT']."/ems2apr/Docs/".$_REQUEST['type'].$_REQUEST['file'];
$file = clean($_REQUEST['file']);
$type = clean($_REQUEST['type']);
$document = clean($_SERVER['DOCUMENT_ROOT']);
if (isset($file) && $file != '' && $type  != '') {

	if (file_exists($document . "/ems/Docs/" . $type  . $file)) {
		if (unlink($document . "/ems/Docs/" . $type  . $file)) {
			echo 'done|<b>file Deleted Successfully</b>';
		} else {
			echo '<b>notdone|file not deleted</b>';
		}
	}
} else {
	echo "notdone|Row Not Deleted Try Again.";
}
