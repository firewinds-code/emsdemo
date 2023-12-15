<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$ReqID = clean($_REQUEST['ID']);
if (isset($ReqID)) {
	$id = explode('_', $ReqID);
	$sql = 'delete from tbl_salary_slab_by_cps where ID=' . $id[2];
	$myDB = new MysqliDb();
	$result = $myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error = $myDB->getLastError();

	if (empty($mysql_error)) {
		echo "data deleted";
	} else {
		echo "Row Not Deleted Try Again :" . $mysql_error;
	}
}
