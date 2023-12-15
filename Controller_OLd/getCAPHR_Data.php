<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$ID = clean($_REQUEST['ID']);
$sql = 'select statusHr from corrective_action_formhr where corrective_Formid=? order by id desc limit 1';
$sel = $conn->prepare($sql);
$sel->bind_param("i", $ID);
$sel->execute();
$result = $sel->get_result();
// $result=$myDB->query($sql);
// $mysql_error=$myDB->getLastError();
if ($result->num_rows > 0 && $result) {

	foreach ($result as $key => $value) {
		echo $value['statusHr'];
		/*foreach($value as $k => $v)
			{
				if(trim($v)!='')
				{
					echo $v.'|$|';
				}
				else
				{
					echo 'Not Exist';
				}
					
				
				
			}*/
	}
	//echo '<br />';
} else {
	echo 'Not Exist';
}
