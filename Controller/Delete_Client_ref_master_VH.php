<?php

require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');

$sql = 'Delete from client_ref_master where id="' . $_REQUEST['id'] . '" ';
$myDB = new MysqliDb();
$result = $myDB->rawQuery($sql);
$row_count = $myDB->count;
$mysql_error = $myDB->getLastError();

if (empty($mysql_error)) {
    echo "Done|Row Deleted Successfully";
} else {
    echo "No|Row Not Deleted Try Again :" . $mysql_error;
}
