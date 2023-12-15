<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb_replica.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '0');

echo "Executing Create API for head details Start";
echo "<br>";
//store_execution_time('Start'); 

$sql = "select  t1.client_id,client_name,t1.`process` process_name,pm.`process_id` as process_id,cm_id as sub_process_id,sub_process as sub_process_name,  lm.location as location_name,location_id , VH as vertical_head,   account_head,oh as operations_head , qh as quality_head , th as training_head,site_head,  'active' as  'status' from (select b.client_id, b.client_name,a.cm_id,a.`process`,a.sub_process,a.location as location_id, a.account_head,a.th, a.oh,a.qh,a.VH,s.site_head,a.createdon from new_client_master a inner join client_master b on a.client_name=b.client_id inner join site_head_master s on a.location=s.locationid where a.cm_id Not IN(select  cm_id  from client_status_master)) t1 left join (select distinct process_id,process from process_map) pm on t1.`process`=pm.`process` left join location_master lm on t1.location_id =lm.id	 
		 where cast(t1.createdon as date)= DATE_SUB(cast(now() as date), interval 1 day)";
//where client_name='Tata Play' ";
//where cm_id='56' ";
$myDB = new MysqliDb();
$result = $myDB->query($sql);
if (count($result) > 0) {
	//echo   $para=(json_encode($result));	
	foreach ($result as $val) {
		$para = "client_id=" . $val['client_id'] . "&client_name=" . $val['client_name'] . "&process_name=" . $val['process_name'] . "&process_id=" . $val['process_id'] . "&sub_process_id=" . $val['sub_process_id'] . "&sub_process_name=" . $val['sub_process_name'] . "&location_name=" . $val['location_name'] . "&location_id=" . $val['location_id'] . "&vertical_head=" . $val['vertical_head'] . "&account_head=" . $val['account_head'] . "&operations_head=" . $val['operations_head'] . "&quality_head=" . $val['quality_head'] . "&training_head=" . $val['training_head'] . "&site_head=" . $val['site_head'] . "";

		//$data="client_id=125&process_id=11&sub_process_id=99&location_id=1&client_name=test1&process_name=test2&sub_process_name=test3&location_name=test4&vertical_head=v01&account_head=a01&operations_head=oh01&quality_head=q01&training_head=t01&site_head=s01&status=active
		echo "<br>";
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://172.105.61.198/web/index.php?r=policy-rest/create",
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
} else {
	echo "data not found";
}

echo "<br>Executing API for head details End ";
//store_execution_time($i.'End');
// function store_execution_time($i){
// 	 	$myDB= new MysqliDb();
// 		$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='Create Head details ".$i." ' ");
// }
