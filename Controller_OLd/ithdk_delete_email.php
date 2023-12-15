<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');

//$sql='call remove_client_new('.$_REQUEST['ID'].')';
$ID = clean($_REQUEST['ID']);
$sql = "DELETE FROM `ithdk_master_email_address` WHERE (`id` = ?)";
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$del = $conn->prepare($sql);
$del->bind_param("i", $ID);
$del->execute();
$result = $del->get_result();
// $result = $myDB->rawQuery($sql);
// $row_count = $myDB->count;
// $mysql_error = $myDB->getLastError();

// if (empty($mysql_error)) {
if ($del->affected_rows === 1) {
	echo "Row Deleted.";
} else {
	echo "Row Not Deleted Try Again :";
}
