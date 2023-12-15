<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$bid = clean($_REQUEST['bid']);
$empid = clean($_REQUEST['empid']);
$ans = clean($_REQUEST['ans']);
$ackid = clean($_REQUEST['ackid']);

if (isset($bid, $empid)) {
	$string = "";
	$ans = "";
	if (isset($ans) && $ans != "") {
		$ans_array = explode(',', $ans);
		for ($i = 1; $i < count($ans_array); $i++) {
			$string .= ", answer" . $i . "='" . $ans_array[$i] . "' ";
		}
	}

	if ($ackid == "") {

		$id_array = $myDB->query("select max(id) as id from tbl_brifing_accknowledge");
		foreach ($id_array as $key => $value) {
			$ackid = $value['id'];
		}
	}

	if ($empid != "" && $bid != "" && $ackid != "") {
		// $sql = "update tbl_brifing_accknowledge set  quiz_attempted_date='" . date('Y-m-d H:i:s') . "' $string ,quiz_attempt='Yes' where EmployeeID='" . $empid . "'  and brifing_id='" . $bid . "' and id='" . $ackid . "'";
		$dtStr = date('Y-m-d H:i:s') . $string;
		echo $sql = "update tbl_brifing_accknowledge set  quiz_attempted_date=?  ,quiz_attempt='Yes' where EmployeeID=?  and brifing_id=? and id=?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ssii', $dtStr, $empid, $bid, $ackid);
		$stmt->execute();
		$result = $stmt->get_result();
		// print_r($result);
		// die;
		// $result = $myDB->query($sql);
		// $check_error  = $myDB->getLastError();
		if ($result) {
			echo 'yes';
		}
	}
	echo 'yes';
}
