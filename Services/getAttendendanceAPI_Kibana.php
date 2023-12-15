<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
ini_set('display_errors', '1');

echo "Executing API for  Get Attendance Update bulk  API Start";
 echo "<br>";
store_execution_time('start');
$today=date('Y-m-d');

for($j=1;$j<=3;$j++){
	
$day='D'.date("j", strtotime($today. "-$j day"));
$date=date("Y-m-d", strtotime($today. "-$j day"));
//InTime as office_entry,OutTime as office_exit ,
  $query="select 'Active' as status,'NIL' as reason,'NIL' as remarks,FLOOR(UNIX_TIMESTAMP(NOW(3))*1000) created_at,FLOOR(UNIX_TIMESTAMP('".$date."')*1000) `date`,w.EmployeeID employee_id, Leaves,  wo ,present,case when ncns is null then 'no' else 'yes' end ncns from ActiveEmpID w  left Join (select case when ".$day." like 'P%'  then 'yes' else 'no'  end as present, case when ".$day."='WO' then 'yes'  else 'no' end 'wo',case when ".$day."='L' || ".$day."='LWP' then 'yes'  else 'no' end  'leaves' ,EmployeeID from calc_atnd_master  where   Month=Month('".$date."') and Year=Year('".$date."')  )a  on a.EmployeeID=w.EmployeeID left join (select EmployeeID as ncns from login_ncns_smsmail where cast(createdOn as date)='".$date."' and type='NCNS' ) nct on nct.ncns=w.EmployeeID ;";
 // where where w.EmployeeID='CE10091236' df_id='74'
// left join (SELECT DateOn,CAST(MIN(`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`PunchTime`),MIN(`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime`, EmpID FROM biopunchcurrentdata where dateOn='".$date."' group by Dateon ,EmpID )b on w.EmployeeID=b.EmpID
 echo "<br>";

 $myDB= new MysqliDb();
//echo $query;
 //echo "<br>";
 
  echo "<br>";
$EmpBio=$myDB->query($query);
// print_r($EmpBio);


$rowCount = $myDB->count;
if($rowCount>0)
{
	
 $para= json_encode($EmpBio);
 //print_r($para);
 	echo "<br>";
		$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "http://172.105.61.198/web/index.php?r=/attendance-rest/bulk-update",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
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
store_execution_time('End'.$j);
}
}
echo "<br>Executing API for Get Attendance create bulk Update End";

function store_execution_time($i){
	 	$myDB= new MysqliDb();
		$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='Get Attendance Update bulk ".$i."'");
}
?>