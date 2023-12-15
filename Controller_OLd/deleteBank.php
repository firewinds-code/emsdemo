<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);
// $sql = 'delete from bank_details where bank_id = ' . $_REQUEST['ID'];
$sql = 'delete from bank_details where bank_id = ?';
$stm = $conn->prepare($sql);
$stm->bind_param("i", $id);
$stm->execute();
$result = $stm->get_result();
// print_r($result);
// die;
// $result = $myDB->rawQuery($sql);
// $row_count = $myDB->count;
// $mysql_error = $myDB->getLastError();

if ($result) {
	if ($stm->affected_rows === 1) {
		echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Request is Deleted successfuly  </span></span>';
	} else {
		echo 'No|Data Not Deleted ...';
	}
} else {
	echo 'No|Data Not Deleted..';
}
