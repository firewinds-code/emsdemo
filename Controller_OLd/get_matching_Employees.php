<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$fname = clean($_REQUEST['fname']);
$lname = clean($_REQUEST['lname']);
$dob = clean($_REQUEST['dob']);
$sql = 'call get_all_matching_data_forEmployee("' . $fname . '","' . $lname . '","' . $dob . '")';
$img = '';
$myDB = new MysqliDb();
$result_all = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result_all) > 0 && $result_all) {
	echo "<div class='PopUp slideDown animated row'>";

	echo "<h4 class='text-success'>Filled details match with following Employees </h4>";
	echo "<div class='col s12 m12' style='overflow: auto;max-height:400px;'>
		<div class='card'><table class='table table-bordered table-responsive data' id='emp_table'>";
	echo '<thead><tr>';
	echo "<th>EmployeeID</th>";
	echo "<th>EmployeeName</th>";
	echo "<th>FatherName</th>";
	echo "<th>DOB</th>";
	echo "<th>Status</th>";
	echo "<th>Designation</th>";
	echo "<th>Process</th>";
	echo "<th>Sub Process</th>";
	echo "<th>Client Name</th>";
	echo "<th>Department</th>";
	echo '</tr></thead><tbody>';
	foreach ($result_all as $key => $value) {

		echo '<tr>';
		echo '<td><a href="' . URL . 'View/empsave?empid=' . $value['EmployeeID'] . '" class="button button-action button-pill" style="font-size: 12px;padding: 0px;height: 25px;line-height: 25px;" target="_blank">' . $employeeName = $value['EmployeeID'] . "</a></td>";
		echo "<td>" . $value['EmployeeName'] . '</td>';
		echo "<td>" . $value['FatherName'] . '</td>';
		echo "<td>" . $value['DOB'] . '</td>';
		echo "<td>" . $value['emp_status'] . '</td>';
		echo "<td>" . $value['designation'] . '</td>';
		echo "<td>" . $value['Process'] . '</td>';
		echo "<td>" . $value['sub_process'] . '</td>';
		echo "<td>" . $value['clientname'] . '</td>';
		echo "<td>" . $value['dept_name'] . '</td>';
		echo '</tr>';
	}
	echo '</tbody></table></div></div>';
	echo '</div> 
		<div class="col s12 m12 right-align">
		<button type="submit" name="btn_Personal_Add1"  title="Add Employee"  id="btn_Personal_Add1" class="btn waves-effect waves-green"><b>Create New Employee</b></button>
		<button type="button" id="imgBtn_close" class="btn waves-effect modal-action modal-close waves-red close-btn imgBtn_close">Cancel</button></div>';
	echo '<p></p>';
	echo '</div>';
} else {
	echo "<div class='PopUp slideDown animated row'>";

	echo "<div class='col s12 m12'><div class='card'><h4>No Employee Match Found</h4></div></div>";
	echo '<div class="col s12 m12 right-align">
		       <button type="submit" name="btn_Personal_Add1" title="Add Employee" id="btn_Personal_Add1" class="btn waves-effect waves-green"><b>Create New Employee</b></button>
		       <button type="button" id="imgBtn_close" class="btn waves-effect modal-action modal-close waves-red close-btn imgBtn_close">Cancel</button>
		      </div>';
	echo '<p></p>';
	echo '</div>';
	echo '</div>';
}
