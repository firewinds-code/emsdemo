<?php

require_once(__dir__ . '/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');

if (isset($_REQUEST['ID']) && $_REQUEST['ID'] != '') {

	$sql = 'call delete_req_atnd("' . $_REQUEST['ID'] . '")';
	$myDB = new MysqliDb();
	$result = $myDB->rawQuery($sql);
	//$row_count = mysql_affected_rows();
	$mysql_error = $myDB->getLastError();
	if (empty($mysql_error)) {

		echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Request is Deleted successfuly  </span></span>';
	} else {
		echo 'Data Not Deleted ..' . $mysql_error;
	}
}
