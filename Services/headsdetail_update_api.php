<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb_replica.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '0');

echo "Executing API for head details update  Start";
 echo "<br>";
//store_execution_time('Start');
		
		$sql="select  t1.client_id,pm.`process_id` as process_id,cm_id as sub_process_id,location_id , VH as vertical_head,   account_head,oh as operations_head , qh as quality_head , th as training_head,site_head,  'active' as  'status' from (select b.client_id, b.client_name,a.cm_id,a.`process`,a.sub_process,a.location as location_id, a.account_head,a.th, a.oh,a.qh,a.VH,s.site_head,a.modifiedon from new_client_master a inner join client_master b on a.client_name=b.client_id inner join site_head_master s on a.location=s.locationid where a.cm_id Not IN(select  cm_id  from client_status_master)) t1 left join (select distinct process_id,process from process_map) pm on t1.`process`=pm.`process` left join location_master lm on t1.location_id =lm.id where cast(t1.modifiedon as date)=cast(now() as date)";

	 	$myDB=new MysqliDb();
		$result = $myDB->query($sql);
		$i=0;
		if(count($result)>0)	
		{	 	
			foreach($result as $val)
			{
			 	 $para="client_id=".$val['client_id']."&process_id=".$val['process_id']."&sub_process_id=".$val['sub_process_id']."&location_id=".$val['location_id']."&vertical_head=".$val['vertical_head']."&account_head=".$val['account_head']."&operations_head=".$val['operations_head']."&quality_head=".$val['quality_head']."&training_head=".$val['training_head']."&site_head=".$val['site_head']."";
			 	
			 
	//echo "<br><br>";
		$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "http://172.105.61.198/web/index.php?r=policy-rest/custom-update",
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
			echo 'response='.$response;
			  echo "<br>";
			  echo "<br>";
			}
			$i++;
	}
		
}else{
	echo "data not found";
}

echo "<br>Executing API for head details update End ";
//store_execution_time($i.'End');
/*function store_execution_time($i){
	 	$myDB= new MysqliDb();
		$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='Update Head details  ".$i." ' ");
}
*/
		
?>
