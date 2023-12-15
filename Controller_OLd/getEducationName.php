<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);

if ($id == 'NA') {
	echo '<option value="NA" >---Select---</option>';
} elseif ($id == 'Other') {
	echo '<option value="NA" >---Select---</option><option >Other</option>';
} else {
	$sql = 'SELECT * FROM ems.education_name where edu_lvl =?';

	$selectQ = $conn->prepare($sql);
	$selectQ->bind_param("i", $id);
	$selectQ->execute();
	$result = $selectQ->get_result();

	if ($result->num_rows > 0 && $result) {

		echo '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			echo '<option>' . $value['edu_name'] . '</option>';
		}
	} else {
		echo '<option value="NA" >---Select---</option>';
	}
}
