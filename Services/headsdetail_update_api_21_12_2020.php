<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '0');

echo "Executing API for head details bulk  Start";
 echo "<br>";
store_execution_time('Start');

		$sql="select  t1.client_id,client_name,t1.`process` process_name,pm.`process_id` as process_id,cm_id as sub_process_id,sub_process as sub_process_name,pm.`process_id` as new_process_id, cm_id as new_sub_process_id, t1.`process` as new_process_name, sub_process as new_sub_process_name,t1.client_id as new_client_id, client_name as new_client_name , lm.location as location_name,location_id , VH as vertical_head,account_head,oh as operations_head , qh as quality_head , th as training_head from (select b.client_id, b.client_name,a.cm_id,a.`process`,sub_process,location as location_id, account_head,th, oh,qh,VH from (select c.* from `new_client_master` c left join  client_status_master cs on c.cm_id=cs.cm_id where cs.cm_id is null) a inner join client_master b on a.client_name=b.client_id ) t1 left join (select distinct process_id,process from process_map) pm on t1.`process`=pm.`process` left join location_master lm on t1.location_id =lm.id where pm.process_id is not null and process_id=17 ";

	 	$myDB=new MysqliDb();
		$result = $myDB->query($sql);
if(count($result)>0)
{	 	
		echo $para=(json_encode($result));	
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://172.105.61.198/web/index.php?r=/policy-rest/update&id=5fcef3584240aa23a3212b16",
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
			echo $response;
			  echo "<br>";
			}
	
		
}else{
	echo "data not found";
}

echo "<br>Executing API for head deatils bulk End ";
store_execution_time($i.'End');
function store_execution_time($i){
	 	$myDB= new MysqliDb();
		$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='Head details bulk ".$i." ' ");
}

		
?>
