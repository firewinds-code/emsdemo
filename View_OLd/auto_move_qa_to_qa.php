<?php
// Server Config file

require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$data_qa_ops = $myDB->query('select tbl_qa_to_qa_movement.EmployeeID,tbl_qa_to_qa_movement.NewQA from tbl_qa_to_qa_movement inner join whole_details_peremp on whole_details_peremp.EmployeeID = tbl_qa_to_qa_movement.EmployeeID where tbl_qa_to_qa_movement.Status = 4 and whole_details_peremp.status = 6');
if (count($data_qa_ops) > 0 && $data_qa_ops) {
	foreach ($data_qa_ops as $key => $Employee) {
		// print_r($Employee);
		// die;
		$EmployeeID = $Employee['EmployeeID'];
		$newQA = $Employee['NewQA'];
		if (!empty($EmployeeID) && !empty($newQA) && intval(date("d")) >= 1 && intval(date("d")) <= 3) {

			// $query_update = "update status_table set Qa_ops = '" . $newQA . "' where EmployeeID = '" . $EmployeeID . "' ";
			$query_update = "update status_table set Qa_ops = ? where EmployeeID = ?";
			$stmt = $conn->prepare($query_update);
			$stmt->bind_param("ss", $newQA, $EmployeeID);
			$stmt->execute();
			$flag = $stmt->get_result();
			// $flag = $myDB->rawQuery($query_update);
			// $mysql_error = $myDB->getLastError();
			// $rowCount = $myDB->count;
			// echo $mysql_error . '<br />';
			// echo $mysql_error . '<br />';
			echo $EmployeeID . ' Updated<br />';
		} else {
			echo $EmployeeID . ' Not Updated<br />';
		}
	}
}
