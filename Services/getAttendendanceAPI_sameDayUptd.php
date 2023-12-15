<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');

ini_set('display_errors', '1');
/*function store_execution_time($i){
$myDB= new MysqliDb();
$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='Get Attendance same Day update ".$i."'");
		
}*/
echo "Executing API for Get Attendance create bulk API Start";
 echo "<br>";
//store_execution_time('Start');

  $query="select 'Active' as status,'NIL' as reason,'NIL' as remarks,FLOOR(UNIX_TIMESTAMP(NOW(3))*1000) created_at,FLOOR(UNIX_TIMESTAMP(curdate())*1000) `date`,w.EmployeeID employee_id,b.InTime office_entry,b.OutTime office_exit ,case when b.InTime is null then 'no' else 'yes' end  present, case when le.EmployeeID is null then 'no' else 'yes' end  `leave` ,case when wo is null then 'no' else 'yes' end  wo ,case when ncns is null then 'no' else 'yes' end  ncns from  ActiveEmpID w left Join 
(SELECT DateOn,CAST(MIN(`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`PunchTime`),MIN(`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime`, EmpID FROM biopunchcurrentdata where dateOn=cast(now() as date) group by Dateon ,EmpID ) b on w.EmployeeID=b.EmpID left join  (select distinct EmployeeID from leavehistry  where cast(DateFrom as date)>=cast(now() as date) and cast(DateTo as date)<=cast(now() as date)  and MngrStatusID='Approve') le on le.EmployeeID=w.EmployeeID 
 left join  ( select  EmployeeID  as wo from roster_temp  where InTime='WO' and  dateOn=cast(now() as date) ) r on r.wo=w.EmployeeID 
 left join (select  EmployeeID as ncns  from login_ncns_smsmail  where  cast(createdOn as date)=cast(now() as  date)  and type='NCNS' ) nct on nct.ncns=w.EmployeeID;";
 // where w.cm_id in (427,440,455)
 //  where w.EmployeeID='CE10091236' and df_id='74' 192,194,196,311,312,313,314,427,440,455,487,488

 echo "<br>";
 //die;
 $i=0;
 $myDB= new MysqliDb();

$EmpBio=$myDB->query($query);
 //print_r($EmpBio);


$rowCount = $myDB->count;
if($rowCount>0)
{
	$para= json_encode($EmpBio);
	
		$curl = curl_init();
				curl_setopt_array($curl, array(
	CURLOPT_URL => "http://172.105.61.198/web/index.php?r=/attendance-rest/bulk-update",
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
//store_execution_time('End');

?>