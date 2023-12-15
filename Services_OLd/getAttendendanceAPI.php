<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');

ini_set('display_errors', '1');
function store_execution_time($i)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	//$ClientD = $myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='Get Attendance create bulk " . $i . "'");

	$ie = 'Get Attendance Update bulk ' . $i;
	$ClientD = "Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution=?";

	$stmt = $conn->prepare($ClientD);
	$stmt->bind_param("s", $ie);
	if (!$stmt) {
		echo "failed to run";
		die;
	}
	$inst = $stmt->execute();
}
echo "Executing API for  Get Attendance create bulk API Start";
echo "<br>";
store_execution_time('Start');

$query = "call Get_Attendance_create_bulk()";
//  $query = "call Get_Attendance_create_bulk_test('2021-06-26')";

// $query="select 'Active' as status,'NIL' as reason,'NIL' as remarks,FLOOR(UNIX_TIMESTAMP(NOW(3))*1000) created_at,FLOOR(UNIX_TIMESTAMP(curdate())*1000) `date`,w.EmployeeID employee_id,b.InTime office_entry,b.OutTime office_exit ,case when b.InTime is null then 'no' else 'yes' end  present, case when le.EmployeeID is null then 'no' else 'yes' end  `leave` ,case when wo is null then 'no' else 'yes' end  wo ,case when ncns is null then 'no' else 'yes' end  ncns from  ActiveEmpID w left Join (SELECT DateOn,CAST(MIN(`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`PunchTime`),MIN(`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime`, EmpID FROM biopunchcurrentdata where dateOn=cast(now() as date) group by Dateon ,EmpID ) b on w.EmployeeID=b.EmpID left join  (select distinct EmployeeID from leavehistry  where cast(DateFrom as date)>=cast(now() as date) and cast(DateTo as date)<=cast(now() as date)  and MngrStatusID='Approve') le on le.EmployeeID=w.EmployeeID  left join  ( select  EmployeeID  as wo from roster_temp  where InTime='WO' and  dateOn=cast(now() as date) ) r on r.wo=w.EmployeeID  left join (select  EmployeeID as ncns  from login_ncns_smsmail  where  cast(createdOn as date)=cast(now() as  date)  and type='NCNS' ) nct on nct.ncns=w.EmployeeID";


//$query="select 'Active' as status,'NIL' as reason,'NIL' as remarks,FLOOR(UNIX_TIMESTAMP(NOW(3))*1000) created_at,FLOOR(UNIX_TIMESTAMP(curdate())*1000) `date`,w.EmployeeID employee_id,b.InTime office_entry,b.OutTime office_exit ,case when b.InTime is null then 'no' else 'yes' end  present, case when le.EmployeeID is null then 'no' else 'yes' end  `leave` ,case when wo is null then 'no' else 'yes' end  wo ,case when ncns is null then 'no' else 'yes' end  ncns from  api_ActiveEmpID w left Join(SELECT DateOn,CAST(MIN(`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`PunchTime`),MIN(`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime`, EmpID FROM api_biopunchcurrentdata where dateOn=cast(now() as date) group by Dateon ,EmpID ) b on w.EmployeeID=b.EmpID left join  (select distinct EmployeeID from api_leavehistry  where cast(DateFrom as date)>=cast(now() as date) and cast(DateTo as date)<=cast(now() as date)  and MngrStatusID='Approve') le on le.EmployeeID=w.EmployeeID  left join  ( select  EmployeeID  as wo from api_roster_temp  where InTime='WO' and  dateOn=cast(now() as date) ) r on r.wo=w.EmployeeID  left join (select  EmployeeID as ncns  from api_login_ncns_smsmail  where  cast(createdOn as date)=cast(now() as  date)  and type='NCNS' ) nct on nct.ncns=w.EmployeeID;";
//  where df_id='74'

echo "<br>";
//die;
$i = 0;
$myDB = new MysqliDb();

$EmpBio = $myDB->query($query);
//print_r($EmpBio);


$rowCount = $myDB->count;
if ($rowCount > 0) {
	$para = json_encode($EmpBio);

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "http://172.105.61.198/web/index.php?r=/attendance-rest/bulk-insert",
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
		echo "<br>";
	}
}
echo "<br>Executing API for Get Attendance create bulk End";
store_execution_time('End');
