<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);

$sql = 'select ijp,incentive,pli,statusHead from corrective_action_formhead where corrective_Formid=? order by id desc limit 1';
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("i", $id);
$selectQ->execute();
$result = $selectQ->get_result();

if ($result->num_rows > 0 && $result) {

	foreach ($result as $key => $value) {
		foreach ($value as $k => $v) {
			if (trim($v) != '') {
				echo $v . '|$|';
			} else {
				echo 'Not Exist';
			}
		}
	}
	//echo '<br />';
} else {
	echo 'Not Exist';
}
