<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$result = 0;

$bID = clean($_REQUEST['bid']);
$empId = clean($_REQUEST['empid']);
$ans = clean($_REQUEST['ans']);
$qnum = clean($_REQUEST['qnum']);

if (isset($bID, $empId)) {
	$string = "";
	if (isset($ans) && $ans != "") {
		if ($empId != "" && $bID != "") {
			$ans_array = explode(',', $ans);
			$qnum_array = explode(',', $qnum);

			// $select = $myDB->rawQuery("Select id from brf_quiz_attempted where BriefingId='" . $bID . "' and EmployeeID='" . $empId . "' ");
			$selectQry = "Select id from brf_quiz_attempted where BriefingId=? and EmployeeID=? ";
			$stmt = $conn->prepare($selectQry);
			$stmt->bind_param("is", $bID, $empId);
			$stmt->execute();
			$select = $stmt->get_result();
			// print_r($select);
			// die;
			// $mysql_error = $myDB->getLastError();
			// $rowCount = $myDB->count;
			if ($select->num_rows < 1) {
				for ($i = 1; $i < count($ans_array); $i++) {
					// $sql = "Insert into brf_quiz_attempted set  AttemptedDate='" . date('Y-m-d H:i:s') . "' , EmployeeID='" . $empId . "', BriefingId='" . $bID . "' , QuestionId='" . $qnum_array[$i] . "',Answer='" . $ans_array[$i] . "'";
					$sql = "Insert into brf_quiz_attempted set  AttemptedDate='" . date('Y-m-d H:i:s') . "' , EmployeeID=?, BriefingId='?, QuestionId=?,Answer=?";
					$stmt = $conn->prepare($sql);
					$stmt->bind_param("is", $empId, $bID, $qnum_array[$i], $ans_array[$i]);
					$stmt->execute();
					$result = $stmt->get_result();
					// print_r($result);
					// die;
					// $result = $myDB->rawQuery($sql);
				}
			}
		}
		if ($result) {
			echo 'yes';
		}
	}
}
