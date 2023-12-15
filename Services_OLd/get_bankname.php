<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query = null;
// $myDB=new MysqliDb();

// $Query="select BankName from bank_master order by BankName;";
// $res =$myDB->query($Query);
///

$query = "select BankName from bank_master order by BankName;";

$stmt = $conn->prepare($query);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
	foreach ($res as $key => $value) {
		$result[] = $value;
	}
	$result = json_encode($result);
	echo $result;
} else {
	echo NULL;
}
