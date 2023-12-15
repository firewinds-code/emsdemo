<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
ini_set('display_errors', '1');
echo "Executing API for employee-master-rest  Start";
 echo "<br>";
//store_execution_time('Start');
 
/*$query=" SELECT `e`.`EmployeeID` AS `EmployeeID`, `e`.`dateofjoin` AS `dateofjoin`, `e`.`emp_status` AS `emp_status`, `p`.`EmployeeName` AS `EmployeeName`, `p`.`Gender` AS `Gender`, `c`.`client_id` AS `client_id`, `c`.`client_name` AS `client_name`, `pm`.`process_id` AS `process_id`, `l`.`location` AS `location`, `nc`.`process` AS `process`, `e`.`cm_id` AS `SubProcessID`, `nc`.`sub_process` AS `sub_process`, `p`.`location` AS `LocationID`, `nc`.`VH` AS `VH`, `nc`.`account_head` AS `account_head`, `nc`.`oh` AS `oh`, `nc`.`qh` AS `qh`, `nc`.`th` AS `th`, (CASE WHEN (`s`.`Status` = 2) THEN 'In Training' WHEN (`s`.`Status` = 3) THEN 'In Training' WHEN (`s`.`Status` = 4) THEN 'In OJT' WHEN (`s`.`Status` = 5) THEN 'In JOT' WHEN (`s`.`Status` = 6) THEN 'OnFloor' END) AS `Status`, `s`.`BatchID` AS `BatchID`, `s`.`InTraining` AS `InTraining`, `s`.`OutTraining` AS `OutTraining`, `s`.`InOJT` AS `InOJT`, `sq`.`Final_OJT_date` AS `OutOJT`, ifnull(s.OnFloor,`s`.`mapped_date`) AS `FloorDate`, `st`.`Trainer` AS `Trainer`, `sq`.`Quality` AS `QATrainer` FROM
 (((((((((select EmployeeID,dateofjoin,emp_status,cm_id from `employee_map` where `emp_status` = 'Active' and `df_id` = 74)e JOIN `personal_details` `p` ON ((`p`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN (select * from `new_client_master` where cm_id not in (select cm_id from client_status_master)) `nc` ON ((`nc`.`cm_id` = `e`.`cm_id`))) LEFT JOIN `status_table` `s` ON ((`s`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN `client_master` `c` ON ((`c`.`client_id` = `nc`.`client_name`))) LEFT JOIN `status_training` `st` ON ((`st`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN `status_quality` `sq` ON ((`sq`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN (select distinct process_id,process from process_map) `pm` ON ((`nc`.`process` = `pm`.`process`))) LEFT JOIN `location_master` `l` ON ((`l`.`id` = `p`.`location`))) WHERE (`pm`.`process_id` IS NOT NULL) and (`s`.`Status` IN (2 , 3, 4, 5, 6))";*/
 $query="SELECT lower(`function`) as function,lower(Designation) as designation,`e`.`EmployeeID` AS `EmployeeID`, `e`.`dateofjoin` AS `dateofjoin`, `e`.`emp_status` AS `emp_status`, `p`.`EmployeeName` AS `EmployeeName`, `p`.`Gender` AS `Gender`, `c`.`client_id` AS `client_id`, `c`.`client_name` AS `client_name`, `pm`.`process_id` AS `process_id`, `l`.`location` AS `location`, `nc`.`process` AS `process`, `e`.`cm_id` AS `SubProcessID`, `nc`.`sub_process` AS `sub_process`, `p`.`location` AS `LocationID`, `nc`.`VH` AS `VH`, `nc`.`account_head` AS `account_head`, `nc`.`oh` AS `oh`, `nc`.`qh` AS `qh`, `nc`.`th` AS `th`, (CASE WHEN (`s`.`Status` = 2) THEN 'In Training' WHEN (`s`.`Status` = 3) THEN 'In Training' WHEN (`s`.`Status` = 4) THEN 'In OJT' WHEN (`s`.`Status` = 5) THEN 'In JOT' WHEN (`s`.`Status` = 6) THEN 'OnFloor' END) AS `Status`, `s`.`BatchID` AS `BatchID`, `s`.`InTraining` AS `InTraining`, `s`.`OutTraining` AS `OutTraining`, `s`.`InOJT` AS `InOJT`, `sq`.`Final_OJT_date` AS `OutOJT`, ifnull(s.OnFloor,`s`.`mapped_date`) AS `FloorDate`, `st`.`Trainer` AS `Trainer`, `sq`.`Quality` AS `QATrainer` FROM
 (((((((((select EmployeeID,dateofjoin,emp_status,cm_id,`df_id` from `employee_map` where `emp_status` = 'Active')e left join df_master on df_master.df_id= e.df_id left join  designation_master d on d.ID=df_master.des_id left join function_master f on f.id= df_master.function_id
 left join `personal_details` `p` ON ((`p`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN (select c.* from `new_client_master` c left join  client_status_master cs on c.cm_id=cs.cm_id where cs.cm_id is null) `nc` ON ((`nc`.`cm_id` = `e`.`cm_id`))) LEFT JOIN `status_table` `s` ON ((`s`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN `client_master` `c` ON ((`c`.`client_id` = `nc`.`client_name`))) LEFT JOIN `status_training` `st` ON ((`st`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN `status_quality` `sq` ON ((`sq`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN (select distinct process_id,process from process_map) `pm` ON ((`nc`.`process` = `pm`.`process`))) LEFT JOIN `location_master` `l` ON ((`l`.`id` = `p`.`location`)))  WHERE (`pm`.`process_id` IS NOT NULL) and (`s`.`Status` IN (2 , 3, 4, 5, 6))";

$myDB= new MysqliDb();
$ClientD=$myDB->query($query);
$rowCount = $myDB->count;
$date=date('Y-m-d');
//$date=date('2020-10-03');
$milliDate = 1000 * strtotime($date);
$i=0;
$ddArray=array();
if($rowCount>0)
{
	foreach($ClientD as $val)
	{
		$last_sync_time= 1000 * strtotime(date('Y-m-d h:i:s'));
	echo	$para="date=".$milliDate."&employee_id=".$val['EmployeeID']."&date_of_join=".$val['dateofjoin']."&employee_status=".$val['emp_status']."&employee_name=".$val['EmployeeName']."&gender=".$val['Gender']."&client_id=".$val['client_id']."&client_name=".$val['client_name']."&process_id=".$val['process_id']."&process_name=".$val['process']."&sub_process_id=".$val['SubProcessID']."&sub_process_name=".$val['sub_process']."&location_id=".$val['LocationID']."&location_name=".$val['location']."&vertical_head=".$val['VH']."&account_head=".$val['account_head']."&operations_head=".$val['oh']."&quality_head=".$val['qh']."&training_head=".$val['th']."&employee_process_status=".$val['Status']."&training_batch_id=".$val['BatchID']."&training_batch_alias=".$val['Batch_Alias']."&training_batch_name=".$val['BacthName']."&training_in=".$val['InTraining']."&training_out=".$val['OutTraining']."&ojt_in=".$val['InOJT']."&ojt_out=".$val['OutOJT']."&floor_date=".$val['FloorDate']."&trainer_id=".$val['Trainer']."&qa_trainer_id=".$val['QATrainer']."&function_name=".$val['function']."&designation=".$val['designation']; 
	//date=1605810600000&employee_id=CE041615976&date_of_join=2016-04-18&employee_status=Active&employee_name=Amit Kumar Nagar&gender=Male&client_id=20&client_name=Paytm&process_id=4&process_name=PayTM QC&sub_process_id=20&sub_process_name=QC CHECK&location_id=1&location_name=Noida&vertical_head=CE011929750&account_head=CE06159987&operations_head=CE06159987&quality_head=CE06159987&training_head=CE06159987&employee_process_status=OnFloor&training_batch_id=1&training_batch_alias=&training_batch_name=Batch 1&training_in=&training_out=&ojt_in=&ojt_out=&floor_date=2018-02-01 08:00:02&trainer_id=CE121621736&qa_trainer_id=CE031615501
		
		 $i++;
					
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://172.105.61.198/web/index.php?r=employee-master-rest/create",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => $para,
			  CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache",
			    "content-type: application/x-www-form-urlencoded"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			echo $response;
			  echo "<br>";
			}
	
		}	
}else{
	echo "data not found";
}
echo "<br>Executing API for employee-master-rest with No. of records=".$i;
//store_execution_time($i.'End');
/*function store_execution_time($i){
	 	$myDB= new MysqliDb();
		$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='employee-master-rest ".$i."'");
}*/

		
?>