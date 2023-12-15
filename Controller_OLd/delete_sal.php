<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');

// $myDB = new MysqliDb();
// $connn = $myDB->dbConnect();

$id = clean($_REQUEST['id']);
$sql = 'delete from salary_master where id=?';
$delete = $conn->prepare($sql);
$delete->bind_param("i", $id);
$delete->execute();
$result = $delete->get_result();
if ($delete->affected_rows === 1) {
	echo "<script>$(function(){ toastr.error('Data Delete'); }); </script>";
}
    // if(empty($mysql_error)){
	// 	echo "Row  Data Deleted "; 
	// }
	// else
	// {
	// 	echo "Row Not Deleted Try Again";
	// }
