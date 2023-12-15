<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
// $myDB = new MysqliDb();
//echo " select releiving_experience_doc,appointment_offerletter_doc, salaryslip_bankstatement_doc from experince_details where exp_id='".$_REQUEST['ID']."' ";
$ID = clean($_REQUEST['ID']);
$select = "select releiving_experience_doc,appointment_offerletter_doc, salaryslip_bankstatement_doc from experince_details  where exp_id=?";
$selectQ = $conn->prepare($select);
$selectQ->bind_param("i", $ID);
$selectQ->execute();
$results = $selectQ->get_result();
$selectaql = $results->fetch_row();
// $mysql_error = $myDB->getLastError();
$document = clean($_SERVER['DOCUMENT_ROOT']);
if (isset($selectaql[0])) {
	if (file_exists($document . "/erpm/Docs/Experience/" . $selectaql[0])) {
		@unlink($document . "/erpm/Docs/Experience/" . $selectaql[0]);
	}
	if (file_exists($document . "/erpm/Docs/offerletter/" . $selectaql[1])) {
		@unlink($document . "/erpm/Docs/offerletter/" . $selectaql[1]);
	}
	if (file_exists($document . "/erpm/Docs/salaryslip/" . $selectaql[2])) {
		@unlink($document . "/erpm/Docs/salaryslip/" . $selectaql[2]);
	}
}

$sql = 'delete from experince_details where exp_id=?';
$del = $conn->prepare($sql);
$del->bind_param("i", $ID);
$del->execute();
$result = $del->get_result();
// $myDB = new MysqliDb();
// $result = $myDB->rawQuery($sql);
echo 'done|<b>file Deleted Successfully</b>';
