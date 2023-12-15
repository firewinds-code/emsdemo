<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$action = cleanUserInput($_GET['action']);
$id = cleanUserInput($_GET['id']);
if (isset($action) and $action == 'getperson' and $id != "") {
	if ($id == 'Administration') {
		//$id='cm.client_name= 1';
		$id = '1';
		$id2 = "";
	} else
	if ($id == 'Information Technology') {
		//$id='cm.client_name=13';
		$id = '13';
		$id2 = "";
	} else
	if ($id == 'Human Resource') {
		//$id='cm.client_name=10';
		$id = '10';
		$id2 = "";
	} else
	if ($id == 'Operation') {
		$id = "";
		$id2 = "1,2,9,10,12,13,15";
	}

	$sql = 'call getPersonName("' . $id . '","' . $id2 . '")';
	$query = "";
	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	if (count($result) > 0 && $result) {
		echo '<option value="NA" >---Select---</option>';
		//echo "total row count=".mysql_num_rows($result);

		foreach ($rowData as $key => $value) {
			echo '<option value="' . $value['EmployeeID'] . '" >' . $value['EmployeeName'] . '</option>';
		}
	} else {
		echo '<option value="NA" >---Select---</option>';
	}
}
