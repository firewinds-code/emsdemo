<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$ID = clean($_REQUEST['ID']);
$sql = 'delete from question_bank where id = ?';
// $myDB = new MysqliDb();
// $result = $myDB->rawQuery($sql);
// $row_count = $myDB->count;
// $mysql_error = $myDB->getLastError();
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();
if ($stmt->affected_rows === 1) {
	// if (empty($mysql_error)) {
	echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Request is Deleted successfuly  </span></span>';
} else {
	echo 'No|Data Not Deleted ..';
}
