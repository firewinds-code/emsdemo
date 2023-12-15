<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$Client = clean($_REQUEST['client_name1']);
$LOcation = clean($_REQUEST['location1']);
$process1 = clean($_REQUEST['process1']);
$sub_process1 = clean($_REQUEST['sub_process1']);
if (isset($Client) && ($LOcation) && ($process1) && ($sub_process1)) {

	$Query = "select distinct t3.client_name,concat(t2.EmpName,'(',t2.empid,')') as Name, t1.EmployeeID, t2.loc ,t2.EmpName  from employee_map as t1 join EmpID_Name as  t2 on t1.EmployeeID=t2.EmpID join new_client_master as t3 on t1.cm_id=t3.cm_id  where client_name=? and loc=?  and emp_status='Active' and df_id !=74 Union
	select distinct t3.client_name,concat(t2.EmpName,'(',t2.empid,')') as Name, t1.EmployeeID, t2.loc,t2.EmpName   from employee_map as t1 join EmpID_Name as  t2 on t1.EmployeeID=t2.EmpID join new_client_master as t3 on t1.cm_id=t3.cm_id  where t1.EmployeeID='CE07147134' and emp_status='Active' order by EmpName;";
	$sql = $conn->prepare($Query);
	$sql->bind_param("si", $Client, $LOcation);
	$sql->execute();
	$result = $sql->get_result();
	$count = $result->num_rows;
	if ($result->num_rows > 0) {
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