<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$DOJ = $lastDate = $p = $wo = $l = $lwp = $hwp = $h = $a = $month = '';
$sql = "select EmployeeID, FirstName, LastName, EmployeeName, DOB, MotherName, Gender, BloodGroup, MarriageStatus, Spouse,MarriageDate, ChildStatus, FatherName, emp_level, emp_status, password, cm_id, df_id, DOJ, Process, sub_process,account_head, oh, qh, th, client_name, clientname, id, `function`, des_id, designation, dept_id, dept_name, status,ReportTo, Qa_ops, BatchID, Quality, DOD, Trainer, TL from whole_dump_emp_data where EmployeeID='" . $_REQUEST['EmpID'] . "' ";
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$my_error = $myDB->getLastError();
$rowCount = $myDB->count;
if ($rowCount > 0) {
	foreach ($result as $key => $value) {
		$DOJ = $value['DOJ'];
		$cm_id = $value['cm_id'];
		$ReportTo = $value['ReportTo'];
	}
}

$effectiveDate = strtotime("+2 months", strtotime($DOJ)); // returns timestamp
$lastDateCon = date('m-d', $effectiveDate);
$lastDate = date("Y-" . $lastDateCon);
$DateFromDOJ = date('d', strtotime($DOJ));
if ($DateFromDOJ >= 15) {
	$UpdateMonth = strtotime("+1 months", strtotime($DOJ));
	$UpdateMonth = date('Y-m-01', $UpdateMonth);
} else {
	$UpdateMonth = $DOJ;
}
$ConvertMD = date('m-d', strtotime($UpdateMonth));
$ConcatCurrentY = date("Y-" . $ConvertMD);

$to = $ConcatCurrentY;
$to = date("Y-m-01", strtotime($to . ' - 1 month'));
$months = date("Y-m-01", strtotime($ConcatCurrentY . ' - 12 month'));
$from = $months;
$sqlBy = 'call Apr_ApplicantPerformance("' . $_REQUEST['EmpID'] . '","' . $from . '","' . $to . '")';
$myDB = new MysqliDb();
$resultBy = $myDB->rawQuery($sqlBy);
$mysql_error = $myDB->getLastError();

if (empty($mysql_error)) {
	foreach ($resultBy as $key => $value) {
		$month = date("F", mktime(0, 0, 0, $value['Month'], 1));
		$year = $value['year'];
		$L1 = $value['L1'];
		$V1 = $value['V1'];
		$L2 = $value['L2'];
		$V2 = $value['V2'];
		$L3 = $value['L3'];
		$V3 = $value['V3'];
		$L4 = $value['L4'];
		$V4 = $value['V4'];
		$L5 = $value['L5'];
		$V5 = $value['V5'];

		echo '<div class="input-field col s12 m12">
	     	<fieldset>
			<legend><b>' . $month . '-' . $year . '</b></legend>      
			  <table class="table table-bordered">
			    <thead>
			      <tr>
			        <th>' . $L1 . '</th>
			        <th>' . $L2 . '</th>
			        <th>' . $L3 . '</th>
			        <th>' . $L4 . '</th>
			        <th>' . $L5 . '</th>
			      </tr>
			    </thead>
			    <tbody>
			      <tr>
			        <td>' . $V1 . '</td>
			        <td>' . $V2 . '</td>
			        <td>' . $V3 . '</td>
			        <td>' . $V4 . '</td>
			        <td>' . $V5 . '</td>
			      </tr>
			     </tbody>
			    </table>
			  </fieldset>
     		 </div>';
	}
}
