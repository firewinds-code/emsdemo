<?php
require_once(__dir__ . '/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$actionType = clean($_REQUEST['actionType']);
$ID = clean($_REQUEST['id']);
if ($actionType == 'delete') {
	if (isset($ID) && $actionType == 'delete') {
		$DeleteQuery = "delete from vaccination_data where id=?";
		// $myDB = new MysqliDb();
		// $conn = $myDB->dbConnect();
		$del = $conn->prepare($DeleteQuery);
		$del->bind_param("i", $ID);
		$del->execute();
		$result = $del->get_result();
		// $result = $myDB->query($DeleteQuery);

		$data['status'] = true;
	} else {
		$data['status'] = false;
	}
}


echo json_encode($data);
