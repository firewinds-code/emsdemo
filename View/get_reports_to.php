<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 

if (isset($_REQUEST['client_name1']) && ($_REQUEST['location1']) && ($_REQUEST['process1']) && ($_REQUEST['sub_process1'])) {
	//  $Query = "select distinct t3.client_name,concat(t2.EmpName,'(',t2.empid,')') as Name, t1.EmployeeID, t2.loc ,t2.EmpName  from employee_map as t1 join EmpID_Name as  t2 on t1.EmployeeID=t2.EmpID join new_client_master as t3 on t1.cm_id=t3.cm_id  where client_name='" . $_REQUEST['client_name1'] . "' and loc='" . $_REQUEST['location1'] . "'  and emp_status='Active' and df_id !=74 Union
	//  select distinct t3.client_name,concat(t2.EmpName,'(',t2.empid,')') as Name, t1.EmployeeID, t2.loc,t2.EmpName   from employee_map as t1 join EmpID_Name as  t2 on t1.EmployeeID=t2.EmpID join new_client_master as t3 on t1.cm_id=t3.cm_id  where t1.EmployeeID='CE07147134' and emp_status='Active' order by EmpName;";

	$Query = "select distinct t3.client_name,concat(t2.EmployeeName,'(',t2.EmployeeID,')') as Name, t1.EmployeeID, t2.location ,t2.EmployeeName  from employee_map as t1 join personal_details as  t2 on t1.EmployeeID=t2.EmployeeID join new_client_master as t3 on t1.cm_id=t3.cm_id  where client_name='" . $_REQUEST['client_name1'] . "' and t2.location='" . $_REQUEST['location1'] . "'  and emp_status='Active' and df_id !=74 Union
	select distinct t3.client_name,concat(t2.EmployeeName,'(',t2.EmployeeID,')') as Name, t1.EmployeeID, t2.location,t2.EmployeeName   from employee_map as t1 join personal_details as  t2 on t1.EmployeeID=t2.EmployeeID join new_client_master as t3 on t1.cm_id=t3.cm_id  where t1.EmployeeID='CE07147134' and emp_status='Active' Union
	select distinct t3.client_name,concat(t2.EmpName,'(',t2.EmpID,')') as Name, t1.EmployeeID, t2.loc ,t2.EmpName  from employee_map as t1 join EmpID_Name as  t2 on t1.EmployeeID=t2.EmpID join new_client_master as t3 on t3.account_head=t1.EmployeeID where client_name='" . $_REQUEST['client_name1'] . "' and t3.location='" . $_REQUEST['location1'] . "'  and emp_status='Active' Union
	select distinct t3.client_name,concat(t2.EmpName,'(',t2.EmpID,')') as Name, t1.EmployeeID, t2.loc ,t2.EmpName  from employee_map as t1 join EmpID_Name as  t2 on t1.EmployeeID=t2.EmpID join new_client_master as t3 on t3.oh=t1.EmployeeID where client_name='" . $_REQUEST['client_name1'] . "' and t3.location='" . $_REQUEST['location1'] . "'  and emp_status='Active' order by EmployeeName;";
	$myDB = new MysqliDb();
	$result = $myDB->query($Query);
	$mysql_error = $myDB->getLastError();
	if (empty($mysql_error)) {
		$toption = '<option value="NA"> -Select Reports_to- </option>';
		foreach ($result as $r) {
			// print_r($r);
			$toption .= "<option value='" . $r['EmployeeID'] . "'>" . $r['Name'] . "</option>";
		}
	}
	$data['EmployeeID'] = $toption;
}

echo json_encode($data);
?>
<?php


?>