<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
// $myDB=new MysqliDb();
$ID = clean($_REQUEST['ID']);
$sql = 'call sp_delete_dt("' . $ID . '")';
$result = $myDB->rawQuery($sql);
$row_count = $myDB->count;
$mysql_error = $myDB->getLastError();

if (empty($mysql_error)) {

	echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Request is Deleted successfuly  </span></span>';

	/*if(count($row_count) > 0)
		{
			echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Request is Deleted successfuly  </span></span>';
		}
		else
		{
			echo 'Data Not Deleted ...';
		}*/
} else {
	echo 'Data Not Deleted ..' . $mysql_error;
}
