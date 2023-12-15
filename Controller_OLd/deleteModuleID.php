<?php

require_once(__dir__ . '/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$ID = clean($_REQUEST['ID']);
$sql = 'delete from module_master where ID = ?';
$del = $conn->prepare($sql);
$del->bind_param("i", $ID);
$del->execute();
$result = $del->get_result();
// $myDB = new MysqliDb();
// $result = $myDB->rawQuery($sql);
//$row_count = mysql_affected_rows();
// $mysql_error = $myDB->getLastError();
// if (empty($mysql_error)) {
if ($del->affected_rows === 1) {
	echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Request is Deleted successfuly  </span></span>';

	/*if(count($result) > 0)
		{
			echo 'Done|<span class="text-success">Done ! <span class="text-warning"> Request is Deleted successfuly  </span></span>';
		}
		else
		{
			echo 'Data Not Deleted ...';
		}*/
} else {
	echo 'Data Not Deleted ..';
}
