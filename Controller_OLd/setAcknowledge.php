<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$bid = clean($_REQUEST['bid']);
$empid = clean($_REQUEST['empid']);
$tqnum = clean($_REQUEST['tqnum']);

if (isset($bid, $empid)) {
	if ($empid != "" && $bid  != "") {
		// $select = $myDB->query("Select id from tbl_brifing_accknowledge where brifing_id='" . $bid  . "' and EmployeeID='" . $empid . "' ");
		$selectQry = "Select id from tbl_brifing_accknowledge where brifing_id=? and EmployeeID=? ";
		$stmt = $conn->prepare($selectQry);
		$stmt->bind_param("is", $bid, $empid);
		$stmt->execute();
		$select = $stmt->get_result();
		if ($select->num_rows < 1) {
			// $sql = "insert into tbl_brifing_accknowledge set brifing_id='" . $bid  . "', total_question='" . $_REQUEST['tqnum'] . "',EmployeeID='" . $empid . "',quiz_attempt='No' ";
			$sql = "insert into tbl_brifing_accknowledge set brifing_id=?, total_question=?,EmployeeID=?,quiz_attempt='No' ";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("iis", $bid, $tqnum, $empid);
			$stmt->execute();
			$result = $stmt->get_result();
			// $result = $myDB->query($sql);
			// $error = $myDB->getLastError();
			if ($result) {
				echo 'yes';
			}
		} else {
			echo 'yes';
		}
	}
}
