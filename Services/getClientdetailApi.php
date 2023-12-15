<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
ini_set('display_errors', '1');
echo "Executing API for Process EOD Status Start";
store_execution_time('Client DetailApi Start');
 $query="select client_id, t1.cm_id, client_name, process_id,process, sub_process, location, locationName,ifnull(active_on_floor,0) as active_on_floor,ifnull(rostered,0)- (ifnull(leaves,0)) rostered, case when (ifnull(ttrostered,0)-ifnull(leaves,0))<0 then 0 else ifnull(ttrostered,0)-ifnull(leaves,0) end ttrostered, ifnull(active_ojt,0) active_ojt ,ifnull(active_in_training,0) active_in_training,ifnull(present,0)present ,ifnull(on_floor_attrition,0)on_floor_attrition, ifnull(hd,0) hd,ifnull(leaves,0) leaves, ifnull(wo,0) wo, case when  ifnull((ttrostered -present-ifnull(leaves,0)),0)<0 then 0 else ifnull((ttrostered-present-ifnull(leaves,0)),0) end as absent,ifnull(notice,0)notice,ifnull(ncns,0)ncns 
from 
(
select cm.client_id,ncm.cm_id,cm.client_name,process_id, process,ncm.sub_process,ncm.location,ncm.locationName from client_master cm inner join (select Distinct pm.process_id, a.process as process, a.sub_process, a.client_name,a.cm_id,a.location,lm.location as locationName from new_client_master a inner Join location_master lm on lm.id=a.location left join process_map pm on a.process=pm.process where a.cm_id Not IN(select cm_id from client_status_master) and process_id is not null) ncm on cm.client_id=ncm.client_name and client_id not in (1,5,10,13,15,39)
)t1 left join 
(
select count(t1.EmployeeID) active_on_floor,t2.cm_id from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID where status in(4,5,6) and df_id=74 group by cm_id
) t2 on t1.cm_id=t2.cm_id 
left join 
(
select count(t.EmployeeID) rostered,e.cm_id from roster_temp t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID  where DateOn=cast(now() as  date)  and InTime not in('HO','WO') and status in(4,5,6) and df_id=74 group by cm_id
) t3 on t1.cm_id=t3.cm_id 
left join 
(
select count(t.EmployeeID) ttrostered,e.cm_id from roster_temp t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID  where DateOn=cast(now() as  date) and  cast(InTime as time) <=cast(now() as time ) and InTime not in('HO','WO') and status in(4,5,6) and df_id=74 group by cm_id
) tr3 on t1.cm_id=tr3.cm_id 
left join 
(
select count(a.EmployeeID) present,a.cm_id from ActiveEmpID a inner Join (SELECT distinct EmpID FROM biopunchcurrentdata where dateOn=cast(now() as date)) b  on b.EmpID=a.EmployeeID left join status_table s on a.EmployeeID=s.EmployeeID where status in(4,5,6) and df_id=74 group by cm_id 
) t6 on t1.cm_id=t6.cm_id 
left join
(
select count(t1.EmployeeID) active_ojt,t2.cm_id from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID
where status in (4,5) and df_id=74 group by cm_id
) t4 on t1.cm_id=t4.cm_id 
left join 
(
select count(t1.EmployeeID) active_in_training,t2.cm_id from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID
where status in (2,3) and df_id=74 group by cm_id
) t5 on t1.cm_id=t5.cm_id 
left Join 
(
select count(t1.EmployeeID) on_floor_attrition,e.cm_id from exit_emp t1 Inner join employee_map e on t1.EmployeeID=e.EmployeeID left join status_table s on t1.EmployeeID=s.EmployeeID where status in (4,5,6) and month(t1.dol)=month(now()) and year(t1.dol)=year(now()) and df_id=74 group by cm_id
)t7 on t1.cm_id=t7.cm_id 
left join
(
select ifnull( count(t1.EmployeeID),0) hd,t2.cm_id from leavehistry t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID left join status_table s on t2.EmployeeID=s.EmployeeID where status in (4,5,6) and  (cast(t1.DateFrom as date)<=cast(now() as date) and cast(t1.DateTo as date)>=cast(now() as date) ) and LeaveType='Half Day' and MngrStatusID='Approve' and df_id=74 group by cm_id
)t8 on t1.cm_id=t8.cm_id 
left join
(
select ifnull( count(t1.EmployeeID),0) leaves,t2.cm_id from leavehistry t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID left join status_table s on t2.EmployeeID=s.EmployeeID where status in (4,5,6) and (cast(t1.DateFrom as date)<=cast(now() as date) and cast(t1.DateTo as date)>=cast(now() as date) ) and LeaveType='Leave' and MngrStatusID='Approve' and df_id=74 group by cm_id
)t9 on t1.cm_id=t9.cm_id 
left join
(
select ifnull(count(t.EmployeeID),0) wo,e.cm_id from roster_temp t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID where status in (4,5,6) and DateOn=cast(now() as  date)  and InTime='WO' and df_id=74 group by cm_id
)t11 on t1.cm_id=t11.cm_id  
left join
(
select ifnull(count(t.EmployeeID),0) ncns,e.cm_id from login_ncns_smsmail t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID where status in (4,5,6) and cast(t.createdOn as date)=cast(now() as  date)  and type='NCNS' and df_id=74 group by cm_id
)t12 on t1.cm_id=t12.cm_id
left join
(
select ifnull(count(t.EmployeeID),0) notice,e.cm_id from resign_details t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID where status in (4,5,6) and cast(now() as date) between nt_start and nt_end and df_id=74 group by cm_id
)t13 on t1.cm_id=t13.cm_id";

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
		$on_notice=$val['notice'];
		$rostered_expected=$val['ttrostered'];
		$on_floor_ncns_active=$val['ncns'];
		$last_sync_time= 1000 * strtotime(date('Y-m-d h:i:s'));
		$para="date=".$milliDate."&client_id=".$val['client_id']."&client_name=".$val['client_name']."&process_id=".$val['process_id']."&process_name=".$val['process']."&sub_process_id=".$val['cm_id']."&sub_process_name=".$val['sub_process']."&location=".$val['location']."&location_name=".$val['locationName']."&active_on_floor=".$val['active_on_floor']."&rostered=".$val['rostered']."&present=".$val['present']."&hd=".$val['hd']."&leaves=".$val['leaves']."&wo=".$val['wo']."&absent=".$val['absent']."&on_floor_attrition=".$val['on_floor_attrition']."&active_ojt=".$val['active_ojt']."&active_in_training=".$val['active_in_training']."&rostered_expected=".$rostered_expected."&on_floor_ncns_active=".$on_floor_ncns_active."&last_sync_time=".$last_sync_time."&on_notice=".$on_notice;
		//echo $para;
		// echo "<br>";
		 echo "<br>";
		 $i++;
		 $curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "http://172.105.61.198/web/index.php?r=process-eod-stats-rest/create",
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
			} else 
			{
				$ddArray=json_decode($response);
				//print_r($ddArray);
				if($ddArray->_id!="")
				{
				  	
				$APIID=$ddArray->_id;
				$myDB= new MysqliDb();
				$insertQuery="Insert into process_apiid_log set api_id='".$APIID."',cm_id='".$val['cm_id']."',date='".$date."'";
				$myDB->query($insertQuery);
		

				}
			}
			
		}	
}else{
	echo "data not found";
}
echo "<br>Executing API for Process EOD End with No. of records=".$i;
store_execution_time($i.' Client DetailApi End');
function store_execution_time($var){
	 	$myDB= new MysqliDb();
		$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='".$var."'");
}
?>