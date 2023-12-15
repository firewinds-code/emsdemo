<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$loc = '';

if (isset($_REQUEST['loc']) && $_REQUEST['loc'] != "") {
	$loc = $_REQUEST['loc'];
	$type = $_REQUEST['type'];
	if (isset($_REQUEST['val']) && $_REQUEST['val'] != "") {
		$val = $_REQUEST['val'];
	}

	//$sql='call get_process_byclient("'.$_REQUEST['id'].'","'.$loc.'")';
	//$sql='select nc.*,cm.*,t1.location from new_client_master nc inner join client_master cm  on nc.client_name = cm.client_id inner join location_master t1 on t1.id = nc.location where t1.id="'.$loc.'" order by cm.client_name';

	if ($type == "ah") {
		$sql = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and location="' . $loc . '" and emp_status="Active" and ((Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD")) and personal_details.EmployeeID is not null Union select "CE03070003" as EmployeeID, "Sachin Siwach" as EmployeeName 
		Union select "CE07147134" as EmployeeID, "Nitin Sahni" as EmployeeName order by EmployeeName';
	} else if ($type == "vh") {
		$sql = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and emp_status="Active" and ((Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD")) and personal_details.EmployeeID is not null Union select "CE03070003" as EmployeeID, "Sachin Siwach" as EmployeeName 
		Union select "CE07147134" as EmployeeID, "Nitin Sahni" as EmployeeName order by EmployeeName';
	} else if ($type == "hr") {
		$sql = 'select EmployeeID,EmployeeName from whole_details_peremp where sub_process like"Human Resource%" and location="' . $loc . '" Union select "CE03070003" as EmployeeID, "Sachin Siwach" as EmployeeName 
		Union select "CE07147134" as EmployeeID, "Nitin Sahni" as EmployeeName order by EmployeeName';
	} else if ($type == "excep") {
		$sql = "select t2.EmployeeID,trim(t2.EmployeeName) as EmployeeName from employee_map t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID where t1.emp_status='Active' and df_id not in (74,77) order by trim(t2.EmployeeName)";
	} else if ($type == "site") {
		$sql = "select distinct(t2.EmpName) as EmployeeName, t1.EmployeeID from employee_map as t1 join EmpID_Name as  t2 on t1.EmployeeID=t2.EmpID where loc='" . $loc . "'  and emp_status='Active' and df_id not in (74,77) order by EmpName;";
	}


	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if (count($result) > 0 && $result) {
		echo '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			if ($val == $value['EmployeeID']) {
				echo '<option value="' . $value['EmployeeID'] . '" selected>' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			} else {
				echo '<option value="' . $value['EmployeeID'] . '">' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			}
		}
	} else {
		echo '<option value="NA" >---Select---</option>';
	}
}
