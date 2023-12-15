<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$sql = 'select QrCode from employee_qrcode where employeeid="' . $_REQUEST['id'] . '"';
$myDB = new MysqliDb();
$result = $myDB->rawQuery($sql);
$mysql_error = $myDB->getLastError();
if ($myDB->count > 0) {
	echo $result[0]['QrCode'];
} else {
	echo 'No';
}
