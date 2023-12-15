<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$action = cleanUserInput($_GET['action']);
$empid = cleanUserInput($_GET['empid']);

if (isset($action) and $action == 'search' and $empid != "") {
	// $action = $action;
	// $empid = trim($_GET['empid']);
	if ($empid != '') {
		$sqlBy = "call get_AllEmployee_byBesic('" . $empid . "')";
		$myDB = new MysqliDb();
		$result = $myDB->query($sqlBy);
		$tableValue = "";
		//echo "hello";
		//exit;
		//if(mysql_num_rows($result)>0)

		if (count($result) > 0 && $result) {

			//$data_array=mysql_fetch_array($result);
			$tableValue .= "<table border='1' style='font-size:10px;'>";
			$tableValue .= "<tr>
					<td>Employee ID</td>
					<td>Name</td>
					<td>Process</td>
					<td>Sub Process</td>
					<td>Report To</td>
					<td>Accounting head</td>
					
				</tr>";
			foreach ($result as $key => $value) {
				$tableValue .= "<tr>
					<td>" . $empid . "</td>
					<td>" . $value['EmployeeName'] . "</td>
					<td>" . $value['Process'] . "</td>
					<td>" . $value['sub_process'] . "</td>
					<td>" . $value['ReportTo'] . "</td>
					<td>" . $value['AccountHead'] . "</td>
					
				</tr>";
			}
			$tableValue .= "</table>";
		} else {
			$tableValue .= 'EmployeeID  Not Exists';
		}
		echo $tableValue;
	}
}
