<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$ID = clean($_REQUEST['ID']);
$sql = 'select t2.statusHead,t3.statusHr from corrective_action_form t1 left join ( select * from corrective_action_formhead where corrective_Formid=? order by id desc limit 1) t2 on t1.id = t2.corrective_Formid left join (select * from corrective_action_formhr where corrective_Formid=? order by id desc limit 1) t3 on t1.id=t3.corrective_Formid where t1.id=?';
$sel = $conn->prepare($sql);
$sel->bind_param("iii", $ID, $ID, $ID);
$sel->execute();
$result = $sel->get_result();
// $result = $myDB->query($sql);
// $mysql_error = $myDB->getLastError();
if ($result->num_rows > 0 && $result) {

	foreach ($result as $key => $value) {
		foreach ($value as $k => $v) {
			if (trim($v) != '')
				echo $v . '|$|';
		}
	}
	//echo '<br />';
} else {
	echo '';
}
