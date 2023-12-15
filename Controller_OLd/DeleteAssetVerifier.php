<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$ID = clean($_REQUEST['ID']);
//$sql='call remove_client_new('.$_REQUEST['ID'].')';
$sql = "DELETE FROM `ems`.`asset_verifier` WHERE (`id` = ?)";
// $myDB = new MysqliDb();
// $result = $myDB->rawQuery($sql);
// $row_count = $myDB->count;
// $mysql_error = $myDB->getLastError();
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("i", $ID);
$selectQ->execute();
$res = $selectQ->get_result();
// if (empty($mysql_error)) {
if ($selectQ->affected_rows === 1) {
	echo "Row Deleted.";
} else {
	echo "Row Not Deleted Try Again :";
}
