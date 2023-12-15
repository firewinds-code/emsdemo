<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$loc = '';
$VH = '';
$oh = '';
$qh = '';
$th = '';
$SiteSpoc = '';
$ach = '';
$loc = '';
$ph = '';
if (isset($_REQUEST['loc']) && $_REQUEST['loc'] != "") {
	if (isset($_REQUEST['loc']) && $_REQUEST['loc'] != "") {
		$loc = $_REQUEST['loc'];
	}
	if (isset($_REQUEST['VH']) && $_REQUEST['VH'] != "") {
		$VH = $_REQUEST['VH'];
	}
	if (isset($_REQUEST['oh']) && $_REQUEST['oh'] != "") {
		$oh = $_REQUEST['oh'];
	}
	if (isset($_REQUEST['qh']) && $_REQUEST['qh'] != "") {
		$qh = $_REQUEST['qh'];
	}
	if (isset($_REQUEST['th']) && $_REQUEST['th'] != "") {
		$th = $_REQUEST['th'];
	}
	if (isset($_REQUEST['SiteSpoc']) && $_REQUEST['SiteSpoc'] != "") {
		$SiteSpoc = $_REQUEST['SiteSpoc'];
	}
	if (isset($_REQUEST['ach']) && $_REQUEST['ach'] != "") {
		$ach = $_REQUEST['ach'];
	}

	if (isset($_REQUEST['ph']) && $_REQUEST['ph'] != "") {
		$ph = $_REQUEST['ph'];
	}
	// if ($type == "ah") {
	// 	$sql = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and location="' . $loc . '" and emp_status="Active" and ((Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD")) and personal_details.EmployeeID is not null Union select "CE03070003" as EmployeeID, "Sachin Siwach" as EmployeeName Union select "CE07147134" as EmployeeID, "Nitin Sahni" as EmployeeName order by EmployeeName';
	// } else if ($type == "vh") {
	// 	$sql = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and emp_status="Active" and ((Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD")) and personal_details.EmployeeID is not null Union select "CE03070003" as EmployeeID, "Sachin Siwach" as EmployeeName 
	// 	Union select "CE07147134" as EmployeeID, "Nitin Sahni" as EmployeeName order by EmployeeName';
	// } else if ($type == "site") {
	// 	$sql = "select distinct(t2.EmpName) as EmployeeName, t1.EmployeeID from employee_map as t1 join EmpID_Name as  t2 on t1.EmployeeID=t2.EmpID where loc='" . $loc . "'  and emp_status='Active' and df_id not in (74,77) order by EmpName;";
	// }
	$resulthtml = '';
	$sql = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and emp_status="Active" and ((Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD")) and personal_details.EmployeeID is not null Union select "CE03070003" as EmployeeID, "Sachin Siwach" as EmployeeName Union select "CE07147134" as EmployeeID, "Nitin Sahni" as EmployeeName Union select "CE05101779" as EmployeeID, "Banpreet Kaur" as EmployeeName order by EmployeeName';
	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if (count($result) > 0 && $result) {
		$resulthtml .= '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			if ($VH == $value['EmployeeID']) {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '" selected>' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			} else {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '">' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			}
		}
	} else {
		$resulthtml .= '<option value="NA" >---Select---</option>';
	}
	$resulthtml .= '||NA||';


	$sql = "select distinct(t2.EmpName) as EmployeeName, t1.EmployeeID from employee_map as t1 join EmpID_Name as  t2 on t1.EmployeeID=t2.EmpID where emp_status='Active' and df_id not in (74,77) order by EmpName;";

	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if (count($result) > 0 && $result) {
		$resulthtml .= '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			if ($SiteSpoc == $value['EmployeeID']) {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '" selected>' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			} else {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '">' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			}
		}
	} else {
		$resulthtml .= '<option value="NA" >---Select---</option>';
	}
	$resulthtml .= '||NA||';


	$sql = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM employee_map left outer join personal_details on employee_map.EmployeeID=personal_details.EmployeeID  left outer join df_master on employee_map.df_id=df_master.df_id left outer join designation_master on designation_master.ID=df_master.des_id where   emp_status="Active" and location="' . $loc . '" and emp_status="Active" and ((Designation like "%Manager%") or Designation in ("Business Analyst","Director","Vice President","Assistant Vice President","Chief Executive Officer","OSD")) and personal_details.EmployeeID is not null Union select "CE03070003" as EmployeeID, "Sachin Siwach" as EmployeeName Union select "CE07147134" as EmployeeID, "Nitin Sahni" as EmployeeName Union select "CE05101779" as EmployeeID, "Banpreet Kaur" as EmployeeName order by EmployeeName';
	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if (count($result) > 0 && $result) {
		$resulthtml .= '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			if ($oh == $value['EmployeeID']) {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '" selected>' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			} else {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '">' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			}
		}
	} else {
		$resulthtml .= '<option value="NA" >---Select---</option>';
	}
	$resulthtml .= '||NA||';


	if (count($result) > 0 && $result) {
		$resulthtml .= '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			if ($qh == $value['EmployeeID']) {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '" selected>' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			} else {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '">' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			}
		}
	} else {
		$resulthtml .= '<option value="NA" >---Select---</option>';
	}
	$resulthtml .= '||NA||';

	if (count($result) > 0 && $result) {
		$resulthtml .= '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			if ($th == $value['EmployeeID']) {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '" selected>' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			} else {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '">' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			}
		}
	} else {
		$resulthtml .= '<option value="NA" >---Select---</option>';
	}
	$resulthtml .= '||NA||';

	if (count($result) > 0 && $result) {
		$resulthtml .= '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			if ($ach == $value['EmployeeID']) {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '" selected>' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			} else {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '">' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			}
		}
	} else {
		$resulthtml .= '<option value="NA" >---Select---</option>';
	}
	$resulthtml .= '||NA||';

	$sql = 'select t1.EmployeeID,t4.EmpName as EmployeeName from employee_map t1 left join df_master t2 on t1.df_id=t2.df_id left join designation_master t3 on t2.des_id=t3.ID left join EmpID_Name t4 on t1.EmployeeID=t4.EmpID where emp_status="Active" and t3.ID in(5,8,10,13,15,16,22,23) order by t4.EmpName;';
	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if (count($result) > 0 && $result) {
		$resulthtml .= '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			if ($ph == $value['EmployeeID']) {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '" selected>' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			} else {
				$resulthtml .= '<option value="' . $value['EmployeeID'] . '">' . $value['EmployeeName'] . '(' . $value['EmployeeID'] . ')' . '</option>';
			}
		}
	} else {
		$resulthtml .= '<option value="NA" >---Select---</option>';
	}
	$resulthtml .= '||NA||';
}

echo $resulthtml;
