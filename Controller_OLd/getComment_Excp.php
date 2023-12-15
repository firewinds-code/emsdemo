<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
// $myDB=new MysqliDb();

$ID = clean($_REQUEST['ID']);
$emp = clean($_REQUEST['Emp']);
$emp2 = clean($_REQUEST['Emp1']);
$sql = 'call sp_getMsgTrail("' . $ID . '")';

$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result) > 0 && $result) {

	foreach ($result as $key => $value) {
		if ($emp == $emp2) {
			if ($emp != $value['CreatedBy']) {
				echo '<p><span><b>Handler Remarks</b> </span> <span class="blue-text">' . $value['CreatedOn'] . '</span> : ' . $value['Comments'] . '</p>';
			} else {
				echo '<p><span><b>' . $value['EmployeeName'] . ' (' . $value['CreatedBy'] . ')</b> </span> <span class="blue-text">' . $value['CreatedOn'] . '</span> : ' . $value['Comments'] . '</p>';
			}
		} else {
			echo '<p><span><b>' . $value['EmployeeName'] . ' (' . $value['CreatedBy'] . ')</b> </span> <span class="blue-text">' . $value['CreatedOn'] . '</span> : ' . $value['Comments'] . '</p>';
		}
	}
	echo '<br />';
} else {
	echo 'No Comment ';
}
