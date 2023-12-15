<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
		
	$sql = "call empid_name_insert();";

 	$myDB=new MysqliDb();
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		
	
	
	$sql = "select t1.EmployeeID,t2.EmpName,t2.loc,t1.cm_id,t3.oh,t6.EmpName as OHName,t4.AdminID,t7.EmpName as Admin,t4.ITID,t8.EmpName as IT, AccountNo,concat(t9.client_name,'|',t3.process,'|',t3.sub_process) as client from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id left join new_client_master_spoc t4 on t1.cm_id=t4.cm_id left join bank_details t5 on t1.EmployeeID=t5.EmployeeID left join EmpID_Name t6 on t3.oh=t6.EmpID left join EmpID_Name t7 on t4.AdminID=t7.EmpID left join EmpID_Name t8 on t4.ITID=t8.EmpID left join client_master t9 on t3.client_name=t9.client_id  where cast(t1.createdon as date) = cast(date_add(now(), interval -1 day) as date)";

 	$myDB=new MysqliDb();
	$result = $myDB->query($sql);
	if(count($result)>0)
	{	 	
			
		foreach($result as $val)
		{
			$curl = curl_init();
			$data='
			{	"admin_id":"'.$val['AdminID'].'",
				"admin_name":"'.$val['Admin'].'",
			    "oh":"'.$val['oh'].'",
			    "oh_name":"'.$val['OHName'].'",
			    "it_spoke":"'.$val['ITID'].'",
			    "it_name":"'.$val['IT'].'",
				"emp_id":"'.$val['EmployeeID'].'",
				"emp_name":"'.$val['EmpName'].'",
				"cm_id":"'.$val['cm_id'].'",
				"acc_number":"'.$val['AccountNo'].'",
				"location":"'.$val['loc'].'",
				"client":"'.$val['client'].'"
			}';
			
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'http://45.79.123.250/asset-management/api/employe-master',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS =>$data,
			  CURLOPT_HTTPHEADER => array(
			    'Content-Type: application/json'
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			echo $response;
		}
	}

	}
