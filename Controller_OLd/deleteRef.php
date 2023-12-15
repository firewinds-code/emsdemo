<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$ID = clean($_REQUEST['ID']);
$sql = 'call remove_ref(' . $ID . ')';
$myDB = new MysqliDb();
$result = $myDB->rawQuery($sql);
$mysql_error = $myDB->getLastError();
$rowEffet = $myDB->count;
if (empty($mysql_error)) {

	if ($rowEffet > 0) {
		echo 'Consultancy Deleted Successfully';
	} else {
		echo 'Consultancy not deleted may be this already used in somewhere in EMS';
	}
} else {
	echo "Row Not Deleted may be this already used in somewhere in EMS :" . $mysql_error;
}
