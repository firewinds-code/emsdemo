<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

$myDB = new MysqliDb();
$sql = "select case when df_id in (74,77,146,147,148,149) then 'CSA' else 'Support' end as desig from employee_map where EmployeeID= '" . $_REQUEST['empid'] . "'";
//$sql='call getIssubybt("'.$_REQUEST['id'].'", "'.$_REQUEST['loc'].'")';

$result = $myDB->query($sql);
$desig = $result[0]['desig'];

//$sql1 = "select issue_type,emp_level from issue_master_new where belongs_to='" . $_REQUEST['id'] . "' order by issue_type";
$sql1 = "select queary,emp_level FROM issue_master where bt='" . $_REQUEST['id'] . "' and location='" . $_REQUEST['loc'] . "' order by queary";
//$sql='call getIssubybt("'.$_REQUEST['id'].'", "'.$_REQUEST['loc'].'")';

$result1 = $myDB->query($sql1);
$mysql_error = $myDB->getLastError();
if (count($result1) > 0 && $result1) {
	echo '<option value="NA" >---Select---</option>';
	foreach ($result1 as $key => $value) {
		if ($value['emp_level'] == 'Both' || $value['emp_level'] == $desig) {
			echo '<option value="' . $value['queary'] . '" >' . $value['queary'] . '</option>';
		} else if ($value['emp_level'] == 'If Applicable') {
			//echo '<option value="' . $value['queary'] . '" >' . $value['queary'] . '</option>';
			if ($value['queary'] == 'ESIC') {
				$sql = "select * from salary_details where EmployeeID='" . $_REQUEST['empid'] . "' and ctc<=21050";

				$result = $myDB->query($sql);
				if (count($result) > 0 && $result) {
					echo '<option value="' . $value['queary'] . '" >' . $value['queary'] . '</option>';
				}
			}
			if ($value['queary'] == 'Insurance') {
				$sql = "select * from salary_details where EmployeeID='" . $_REQUEST['empid'] . "' and ctc>21050";

				$result = $myDB->query($sql);
				if (count($result) > 0 && $result) {
					echo '<option value="' . $value['queary'] . '" >' . $value['queary'] . '</option>';
				}
			}
			if ($value['queary'] == 'Provident Fund') {
				$sql = "select * from salary_details where EmployeeID='" . $_REQUEST['empid'] . "' and ctc<=15800";

				$result = $myDB->query($sql);
				if (count($result) > 0 && $result) {
					echo '<option value="' . $value['queary'] . '" >' . $value['queary'] . '</option>';
				}
			}
		}
	}
} else {
	echo '<option value="NA" >---Select---</option>';
}
