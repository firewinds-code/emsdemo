<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
ini_set('display_errors', '1');
echo "Executing API for OJT Dashboard  Start";
 echo "<br>";
//store_execution_time('Start');

 $query="select Alias as Batch_Alias,traing as StartCount, ActiveCount, t.cm_id, t.BatchID, t.BacthName, t.client, t.clientid,locationName,location, StartDate,EndDate,TrainerName,TrainerID,present,ifnull(ncns,0)ncns,process, sub_process,process_id from 
(select count(t2.EmployeeID)ActiveCount, t2.cm_id,bt.BacthID as BatchID,Alias,BacthName,client,clientid from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID left join status_training tr on tr.EmployeeID=t2.EmployeeID left join batch_master bt on bt.BacthID=tr.BatchID where bt.createdon>'2020-10-01' and t1.Status in (4,5) group by cm_id,bt.BacthID,BacthName,client,clientid,cast(bt.createdon as date))t
left join
(select ifnull( count(t.EmployeeID),0)traing,BatchID,cm_id,cast(min(createdon) as date)StartDate,cast(max(Final_OJT_date) as date)EndDate,reporttoname(max(Quality))TrainerName,Quality as TrainerID from status_quality t inner join AllEmpID e on t.EmployeeID=e.EmployeeID where cast(createdon as date)>=cast('2020-10-01' as date) group by BatchID,cm_id) tr1 on tr1.BatchID=t.BatchID and tr1.cm_id=t.cm_id 
left join 
(select count(t.EmployeeID) ncns,e.cm_id,s.BatchID from login_ncns_smsmail t left join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID where status in (4,5) and cast(t.createdOn as date)=cast(now() as  date)  and type='NCNS' group by cm_id,s.BatchID)nc on nc.cm_id=tr1.cm_id and nc.BatchID=tr1.BatchID
left join
( select count(a.EmployeeID) present,a.cm_id,s.BatchID from ActiveEmpID a inner Join (SELECT distinct EmpID FROM biopunchcurrentdata where dateOn=cast(now() as date)) b  on b.EmpID=a.EmployeeID left join status_table s on a.EmployeeID=s.EmployeeID where status in(4,5) group by cm_id  ,s.BatchID
)b on b.cm_id=tr1.cm_id and b.BatchID=tr1.BatchID
left join
(select Distinct pm.process_id, a.process as process, a.sub_process, a.client_name,a.cm_id,a.location,lm.location as locationName from new_client_master a inner Join location_master lm on lm.id=a.location left join process_map pm on a.process=pm.process )emp on emp.cm_id=tr1.cm_id ";
//where StartDate is not null and EndDate>=cast(now() as date)
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
	foreach($ClientD as $val){
			$last_sync_time= 1000 * strtotime(date('Y-m-d h:i:s'));
			$StartDate= 1000 * strtotime(date($val['StartDate']));
			$EndDate= 1000 * strtotime(date($val['EndDate']));
	$para="date=".$milliDate."&client_id=".$val['clientid']."&client_name=".$val['client']."&process_id=".$val['process_id']."&process_name=".$val['process']."&sub_process_id=".$val['cm_id']."&sub_process_name=".$val['sub_process']."&location_id=".$val['location']."&location_name=".$val['locationName']."&batch_name=".$val['BacthName']."&trainer_name=".$val['TrainerName']."&trainer_id=".$val['TrainerID']."&start_date=".$StartDate."&end_date=".$EndDate."&start_count=".$val['StartCount']."&active_count=".$val['ActiveCount']."&present=".$val['present']."&ncns=".$val['ncns']."&training_type=ojt&last_sync_time=".$last_sync_time."&batch_alias=".$val['Batch_Alias'];
		// echo "<br>";
		 $i++;
					

					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => "http://172.105.61.198/web/index.php?r=/training-eod-stats-rest/create",
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
echo "<br>Executing API for OJT Dashboard with No. of records=".$i;
//store_execution_time($i.' End');
/*function store_execution_time($i){
	 	$myDB= new MysqliDb();
		$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='OJT dashbord ".$i."'");
}*/
?>