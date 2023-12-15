<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
ini_set('display_errors', '1');
echo "Executing API for Process EOD Status  Update  Start";
echo "<br>";

//store_execution_time('Start');
$today=date('Y-m-d');

for($j=1;$j<=2;$j++){
//for($j=16;$j<=20;$j++){
	
$day='a.D'.date("j", strtotime($today. "-$j day"));
$date=date("Y-m-d", strtotime($today. "-$j day"));

$query="select api_id,client_id, t1.cm_id, client_name, process_id,process, sub_process, location, locationName,ifnull(active_on_floor,0) as active_on_floor,ifnull(rostered,0)- (ifnull(leaves,0)) rostered, ifnull(ttrostered,0)ttrostered, ifnull(active_ojt,0) active_ojt ,ifnull(active_in_training,0) active_in_training,ifnull(present,0)present ,ifnull(on_floor_attrition,0)on_floor_attrition, ifnull(hd,0) hd,ifnull(leaves,0) leaves, ifnull(wo,0) wo,ifnull(absent,0) absent ,ifnull(notice,0)notice,ifnull(ncns,0)ncns 

from 
(
select cm.client_id,ncm.cm_id,cm.client_name,process_id, process,ncm.sub_process,ncm.location,ncm.locationName from client_master cm inner join (select Distinct pm.process_id, a.process as process, a.sub_process, a.client_name,a.cm_id,a.location,lm.location as locationName from new_client_master a inner Join location_master lm on lm.id=a.location 
left join process_map pm on a.process=pm.process
where a.cm_id Not IN(select cm_id from client_status_master) and  process_id is not null) ncm on cm.client_id=ncm.client_name
)t1 left join 
(
select count(t1.EmployeeID) active_on_floor,t2.cm_id from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID where status in(4,5,6) and df_id=74  group by cm_id
) t2 on t1.cm_id=t2.cm_id 
left join 
(
select count(t.EmployeeID) rostered,e.cm_id from roster_temp t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID  where DateOn=cast('".$date."' as  date)  and InTime not in('HO','WO') and status in(4,5,6)  and df_id=74 group by cm_id
) t3 on t1.cm_id=t3.cm_id 
left join 
(

select count(t.EmployeeID) ttrostered,e.cm_id from roster_temp t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID  where DateOn=cast('".$date."' as  date) and  cast(InTime as time) <=cast('".$date."' as time ) and InTime not in('HO','WO') and status in(4,5,6) and df_id=74  group by cm_id
) tr3 on t1.cm_id=tr3.cm_id 
left join 
(
select ifnull(count(".$day."),0) present,b.cm_id from calc_atnd_master a left Join employee_map b  on b.EmployeeID=a.EmployeeID left join status_table s on a.EmployeeID=s.EmployeeID where status in(4,5,6) and ".$day." like 'P%' and a.Month=Month('".$date."') and a.Year=Year('".$date."')  and df_id=74  group by cm_id 
) t6 on t1.cm_id=t6.cm_id 
left join 
(
select ifnull(count(".$day."),0) absent,b.cm_id from calc_atnd_master a left Join employee_map b  on b.EmployeeID=a.EmployeeID left join status_table s on a.EmployeeID=s.EmployeeID where status in(4,5,6)  and (".$day."='A' || ".$day."='LANA' ) and a.Month=Month('".$date."') and a.Year=Year('".$date."') and df_id=74  group by cm_id
) t6a on t1.cm_id=t6a.cm_id 
left join
(
select count(t1.EmployeeID) active_ojt,t2.cm_id from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID  where status in (4,5) and df_id=74  group by cm_id
) t4 on t1.cm_id=t4.cm_id 
left join 
(
select count(t1.EmployeeID) active_in_training,t2.cm_id from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID where status in (2,3) and df_id=74   group by cm_id
) t5 on t1.cm_id=t5.cm_id 
left Join 
(
select count(t1.EmployeeID) on_floor_attrition,e.cm_id from exit_emp t1 Inner join ActiveEmpID e on t1.EmployeeID=e.EmployeeID left join status_table s on t1.EmployeeID=s.EmployeeID where status in (4,5,6) and month(t1.dol)=month('".$date."') and year(t1.dol)=year('".$date."') and df_id=74  group by cm_id
)t7 on t1.cm_id=t7.cm_id 
left join
(
select ifnull(count(".$day."),0)hd,b.cm_id from calc_atnd_master a left Join employee_map b  on b.EmployeeID=a.EmployeeID left join status_table s on a.EmployeeID=s.EmployeeID where status in(4,5,6) and ".$day." like 'H%' and ".$day." !='HO' and a.Month=Month('".$date."') and a.Year=Year('".$date."') and df_id=74  group by cm_id 
)t8 on t1.cm_id=t8.cm_id 
left join
(
select ifnull(count(".$day."),0) leaves,b.cm_id from calc_atnd_master a left Join employee_map b  on b.EmployeeID=a.EmployeeID left join status_table s on a.EmployeeID=s.EmployeeID where status in(4,5,6) and ((".$day." like 'L%' and ".$day."!='LANA')|| ".$day."='CO' ) and a.Month=Month('".$date."') and a.Year=Year('".$date."') and df_id=74  group by cm_id 
)t9 on t1.cm_id=t9.cm_id 
left join
(
select ifnull(count(".$day."),0) wo,b.cm_id from calc_atnd_master a left Join employee_map b  on b.EmployeeID=a.EmployeeID left join status_table s on a.EmployeeID=s.EmployeeID where status in(4,5,6) and ".$day." in('WO','WONA','HO') and a.Month=Month('".$date."') and a.Year=Year('".$date."')  and df_id=74 group by cm_id 
)t11 on t1.cm_id=t11.cm_id  
left join
(
select ifnull(count(t.EmployeeID),0) ncns,e.cm_id from login_ncns_smsmail t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID where status in (4,5,6) and cast(t.createdOn as date)='".$date."'  and type='NCNS'  and df_id=74 group by cm_id
)t12 on t1.cm_id=t12.cm_id
left join
(
select ifnull(count(t.EmployeeID),0) notice,e.cm_id from resign_details t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID where status in (4,5,6) and '".$date."' between nt_start and nt_end  and df_id=74 group by cm_id
)t13 on t1.cm_id=t13.cm_id inner join  process_apiid_log on t1.cm_id=process_apiid_log.cm_id where `date`='".$date."' ";
 //echo "<br>";
//	echo $query;
 	//echo "<br>"; echo "<br>";
 	//die;
 $myDB= new MysqliDb();
$ClientD=$myDB->query($query);
$rowCount = $myDB->count;
$date1=date('Y-m-d');
//$date=date('2020-10-03');
$milliDate = 1000 * strtotime($date1);
$i=0;

$ddArray=array();
if($rowCount>0)
{
	foreach($ClientD as $val){
		$api_id=$val['api_id'];
		if($api_id!="")
		{
			$on_notice=$val['notice'];
			$rostered_expected=$val['ttrostered'];
			$on_floor_ncns_active=$val['ncns'];
			$last_sync_time= 1000 * strtotime(date('Y-m-d h:i:s'));
		//active_on_floor=1&rostered=1&present=1&hd=1&leaves=0&wo=2&absent=0&on_floor_attrition=1&active_ojt=1&active_in_training=1
		$para="rostered=".$val['rostered']."&present=".$val['present']."&hd=".$val['hd']."&leaves=".$val['leaves']."&wo=".$val['wo']."&absent=".$val['absent']."&last_sync_time=".$last_sync_time;
	
		// echo "<br>";
		 $i++;
	$curl = curl_init();

					curl_setopt_array($curl, array(
					//  CURLOPT_URL => "http://172.105.61.198/web/index.php?r=%2Fprocess-eod-stats-rest%2Fupdate&id=".$api_id,
					    CURLOPT_URL => "http://172.105.61.198/web/index.php?r=/process-eod-stats-rest/custom-update&id=".$api_id,
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 30,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "PUT",
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
					 // echo $response;
					 // echo "<br>";
					}
					
			}else{
				echo "API ID not found<br>";
			}
		}	
}else{
	echo "data not found";
}
echo "<br>Executing API for Process EOD End ".$date." Update with No.  records of =".$i;
//store_execution_time($i.' End '.$j);


}
/*function store_execution_time($i){
	 	$myDB= new MysqliDb();
		$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='Update day-1 API ".$i."'");
}*/
?>