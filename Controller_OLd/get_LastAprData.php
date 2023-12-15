<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);

$sql = "select RatingAH,PromotionMonth from apprisalmaster where year(CreatedOn) in (select year(CreatedOn)-1 from apprisalmaster where id=?)";
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("i", $id);
$selectQ->execute();
$result = $selectQ->get_result();

if ($result->num_rows > 0 && $result) {

	foreach ($result as $key => $value) {
		foreach ($value as $k => $Details) {
			echo $Details . '|$|';
		}
	}
}
/*else
	{
		echo 'No Comment ';
		
	}*/
