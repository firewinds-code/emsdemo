<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 0);
$empid = $_REQUEST['empid'];

if (empty($_REQUEST['empid'])) {

    die;
}

// print_r($empid);
$array = explode('(', $empid);
$gt =  substr_replace($array[1], "", -1);

$myDB = new MysqliDb();
//echo 'call get_autofill_data_searchTable("'.$empid.'")';
$result = $myDB->query("select t1.EmpID,t1.EmpName,t2.location,t3.emp_status,t4.cm_id,concat(t5.client_name,'|',t4.process,'|',t4.sub_process) as Process,
concat(t4.VH,' | ',t4.account_head,' | ',t4.oh) as Process_details,
concat(t6.EmpName,' | ',t7.EmpName,' | ', t8.EmpName) as Process_details_Names
 from EmpID_Name t1 
left join location_master t2 on t1.loc=t2.id  
left join employee_map t3 on t1.EmpID=t3.EmployeeID
left join new_client_master t4 on t3.cm_id=t4.cm_id
left join client_master t5 on t4.client_name=t5.client_id
left join EmpID_Name t6 on t4.VH=t6.EmpID
left join EmpID_Name t7 on t4.account_head=t7.EmpID
left join EmpID_Name t8 on t4.oh=t8.EmpID
where t1.EmpID='" . $gt . "' ");


echo json_encode($result);
