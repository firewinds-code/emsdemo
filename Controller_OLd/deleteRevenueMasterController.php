<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$id = $_REQUEST['id'];
$mnth = $_REQUEST['mnth'];
$tableName = 'revenue_master_'.$mnth;

$sql = "delete from $tableName where id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$Result2 = $stmt->get_result();
if ($stmt->affected_rows === 1) {
	
	echo "Deleted Successfully";
} else {
	echo "Not Deleted Try Again";
}

