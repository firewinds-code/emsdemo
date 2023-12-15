<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$ID = clean($_REQUEST['ID']);
$emp = clean($_REQUEST['Emp']);
$emp1 = clean($_REQUEST['Emp1']);
$sql = 'call sp_getDTMsgTrail("' . $ID . '")';


$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();

if (count($result) > 0 && $result) {

	foreach ($result as $key => $value) {
		if ($emp == $emp1) {
			if ($emp != $value['Empid']) {
				echo '<p style="padding: 5px;border: 1px solid gray;background: white;box-shadow: 0px 0px 1px 1px rgba(128, 128, 128, 0.21),0px 0px 1px 1px rgba(128, 128, 128, 0.21) inset;border-radius: 4px;"><span style="color:green;">Handler Remarks</span> <kbd>' . $value['CreatedOn'] . '</kbd> : ' . $value['Comments'] . '</p>';
			} else {
				echo '<p style="padding: 5px;border: 1px solid gray;background: white;box-shadow: 0px 0px 1px 1px rgba(128, 128, 128, 0.21),0px 0px 1px 1px rgba(128, 128, 128, 0.21) inset;border-radius: 4px;"><span style="color:green;">' . $value['CreatedBy'] . '</span> <kbd>' . $value['CreatedOn'] . '</kbd> : ' . $value['Comments'] . '</p>';
			}
		} else {
			echo '<p style="padding: 5px;border: 1px solid gray;background: white;box-shadow: 0px 0px 1px 1px rgba(128, 128, 128, 0.21),0px 0px 1px 1px rgba(128, 128, 128, 0.21) inset;border-radius: 4px;"><span style="color:green;">' . $value['CreatedBy'] . '</span> <kbd>' . $value['CreatedOn'] . '</kbd> : ' . $value['Comments'] . '</p>';
		}
	}
	echo '<br />';
} else {
	echo 'No Comment ';
}
