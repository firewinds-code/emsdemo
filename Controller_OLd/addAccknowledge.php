<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
//if(isset($_REQUEST['bid'],$_REQUEST['empid'])){
// $myDB=new MysqliDb();
$empid = clean($_REQUEST['empid']);
$bid = clean($_REQUEST['bid']);
if ($empid != "" && $bid != "") {
	$select = "Select id from tbl_brifing_accknowledge where brifing_id=? and EmployeeID=? ";
	$sel = $conn->prepare($select);
	$sel->bind_param("is", $bid, $empid);
	$sel->execute();
	$results = $sel->get_result();

	if ($results->num_rows < 1) {
		$sql = "insert into tbl_brifing_accknowledge set brifing_id=?,EmployeeID=? ";
		// $result = $myDB->query($sql);
		$ins = $conn->prepare($select);
		$ins->bind_param("is", $bid, $empid);
		$ins->execute();
		$result = $ins->get_result();
		if ($result) {
			echo 'yes';
		}
	} else {
		echo 'yes';
	}
}
//}
