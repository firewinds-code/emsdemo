<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);
if ($id && $id != "") {
	$sql = "select t1.id, EmployeeName, EmployeeId, Doj, WarningLetter, WarningCount, LastDateFill, Q1, Q2, Q3, Q4, Q6, `Process`, SubProcess,Cm_id, AH, ReportTo, RatingPerPMS, RatingManagr, RatingAH, RatingHR,PromotionRecomend,PromotionPost,AHStatus,HRStatus,t1.CreatedOn,t1.CreatedBy,HoldForMonth,PostponeForMonth,DisputeFlag,NeedTraining,Relocate,PromotionID,Designation from apprisalmaster t1 left join designation_master t2 on t1.PromotionID=t2.id where t1.id=?";
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
	// else {
	// 	echo 'No Comment ';
	// }
}
