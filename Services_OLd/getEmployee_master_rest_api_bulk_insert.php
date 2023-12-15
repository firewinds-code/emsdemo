<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
ini_set('display_errors', '1');
echo "Executing API for employee-master-rest insert bulk Start";
echo "<br>";
store_execution_time('Start');

//$query="SELECT `Alias` as `training_batch_alias`,`BacthName` as `training_batch_name`,lower(`function`) as function_name,lower(Designation) as designation,`e`.`EmployeeID` AS `employee_id`, `e`.`dateofjoin` AS `date_of_join`, `e`.`emp_status` AS `employee_status`, `p`.`EmployeeName` AS `employee_name`, `p`.`Gender` AS `gender`, `c`.`client_id` AS `client_id`, `c`.`client_name` AS `client_name`, `pm`.`process_id` AS `process_id`, `l`.`location` AS `location_name`, `nc`.`process` AS `process_name`, `e`.`cm_id` AS `sub_process_id`, `nc`.`sub_process` AS `sub_process_name`, `p`.`location` AS `location_id`, `nc`.`VH` AS `vertical_head`, `nc`.`account_head` AS `account_head`, `nc`.`oh` AS `operations_head`, `nc`.`qh` AS `quality_head`, `nc`.`th` AS `training_head`, (CASE WHEN (`s`.`Status` = 2) THEN 'In Training' WHEN (`s`.`Status` = 3) THEN 'In Training' WHEN (`s`.`Status` = 4) THEN 'In OJT' WHEN (`s`.`Status` = 5) THEN 'In JOT' WHEN (`s`.`Status` = 6) THEN 'OnFloor' END) AS `employee_process_status`, `s`.`BatchID` AS `training_batch_id`, `s`.`InTraining` AS `training_in`, `s`.`OutTraining` AS `training_out`, `s`.`InOJT` AS `ojt_in`, `sq`.`Final_OJT_date` AS `ojt_out`, ifnull(s.OnFloor,`s`.`mapped_date`) AS `floor_date`, `st`.`Trainer` AS `trainer_id`, `sq`.`Quality` AS `qa_trainer_id`,s.ReportTo as reports_to FROM (((((((((select EmployeeID,dateofjoin,emp_status,cm_id,createdon,`df_id` from `employee_map` where `emp_status` = 'Active')e left join df_master on df_master.df_id= e.df_id left join  designation_master d on d.ID=df_master.des_id left join function_master f on f.id= df_master.function_id left join `personal_details` `p` ON ((`p`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN (select c.* from `new_client_master` c left join  client_status_master cs on c.cm_id=cs.cm_id where cs.cm_id is null) `nc` ON ((`nc`.`cm_id` = `e`.`cm_id`))) LEFT JOIN `status_table` `s` ON ((`s`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN `client_master` `c` ON ((`c`.`client_id` = `nc`.`client_name`))) LEFT JOIN `status_training` `st` ON ((`st`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN `status_quality` `sq` ON ((`sq`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN (select distinct process_id,process from process_map) `pm` ON ((`nc`.`process` = `pm`.`process`))) LEFT JOIN `location_master` `l` ON ((`l`.`id` = `p`.`location`)))  left join batch_master bt on bt.BacthID=`s`.`BatchID` WHERE (`pm`.`process_id` IS NOT NULL) and (`s`.`Status` IN (2, 3, 4, 5, 6))  and left(e.EmployeeID,2)!='TE' and `e`.`EmployeeID` in ('CE0121935762','CE1220935724','CE0121935878')";
$query = "SELECT `Alias` as `training_batch_alias`,`BacthName` as `training_batch_name`,lower(`function`) as function_name,lower(Designation) as designation,`e`.`EmployeeID` AS `employee_id`, `e`.`dateofjoin` AS `date_of_join`, `e`.`emp_status` AS `employee_status`, `p`.`EmployeeName` AS `employee_name`, `p`.`Gender` AS `gender`, `c`.`client_id` AS `client_id`, `c`.`client_name` AS `client_name`, `pm`.`process_id` AS `process_id`, `l`.`location` AS `location_name`, `nc`.`process` AS `process_name`, `e`.`cm_id` AS `sub_process_id`, `nc`.`sub_process` AS `sub_process_name`, `p`.`location` AS `location_id`, `nc`.`VH` AS `vertical_head`, `nc`.`account_head` AS `account_head`, `nc`.`oh` AS `operations_head`, `nc`.`qh` AS `quality_head`, `nc`.`th` AS `training_head`, (CASE WHEN (`s`.`Status` = 2) THEN 'In Training' WHEN (`s`.`Status` = 3) THEN 'In Training' WHEN (`s`.`Status` = 4) THEN 'In OJT' WHEN (`s`.`Status` = 5) THEN 'In JOT' WHEN (`s`.`Status` = 6) THEN 'OnFloor' END) AS `employee_process_status`, `s`.`BatchID` AS `training_batch_id`, `s`.`InTraining` AS `training_in`, `s`.`OutTraining` AS `training_out`, `s`.`InOJT` AS `ojt_in`, `sq`.`Final_OJT_date` AS `ojt_out`, ifnull(s.OnFloor,`s`.`mapped_date`) AS `floor_date`, `st`.`Trainer` AS `trainer_id`, `sq`.`Quality` AS `qa_trainer_id`,s.ReportTo as reports_to FROM (((((((((select EmployeeID,dateofjoin,emp_status,cm_id,createdon,`df_id` from `employee_map` where `emp_status` = 'Active')e left join df_master on df_master.df_id= e.df_id left join  designation_master d on d.ID=df_master.des_id left join function_master f on f.id= df_master.function_id left join `personal_details` `p` ON ((`p`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN (select c.* from `new_client_master` c left join  client_status_master cs on c.cm_id=cs.cm_id where cs.cm_id is null) `nc` ON ((`nc`.`cm_id` = `e`.`cm_id`))) LEFT JOIN `status_table` `s` ON ((`s`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN `client_master` `c` ON ((`c`.`client_id` = `nc`.`client_name`))) LEFT JOIN `status_training` `st` ON ((`st`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN `status_quality` `sq` ON ((`sq`.`EmployeeID` = `e`.`EmployeeID`))) LEFT JOIN (select distinct process_id,process from process_map) `pm` ON ((`nc`.`process` = `pm`.`process`))) LEFT JOIN `location_master` `l` ON ((`l`.`id` = `p`.`location`)))  left join batch_master bt on bt.BacthID=`s`.`BatchID` WHERE (`pm`.`process_id` IS NOT NULL) and (`s`.`Status` IN (2, 3, 4, 5, 6))  and left(e.EmployeeID,2)!='TE' and cast(e.createdon as date)=cast(now() as date)";
//dateofjoin =cast(now() as date) ";
// createdon

//echo $query; die;
$myDB = new MysqliDb();
$ClientD = $myDB->query($query);
echo $rowCount = $myDB->count;
echo "<br><br>";
$date = date('Y-m-d');
//$date=date('2020-10-03');
$milliDate = 1000 * strtotime($date);
$i = 0;
$ddArray = array();
if ($rowCount > 0) {

	$last_sync_time = 1000 * strtotime(date('Y-m-d h:i:s'));
	$para = json_encode($ClientD);
	//die;
	$i++;

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "http://172.105.61.198/web/index.php?r=/employee-master-rest/bulk-insert",
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
} else {
	echo "data not found";
}
echo "<br>Executing API for employee-master-rest insert bulk $rowCount";
store_execution_time($rowCount . 'End');
function store_execution_time($i)
{
	$myDB = new MysqliDb();
	// $ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='Employee-master-rest insert bulk".$i."'");

	$conn = $myDB->dbConnect();

	$ie =  'Employee-master-rest insert bulk' . $i;
	$query = "Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution=?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $ie);

	$ClientD = $stmt->execute();
}
