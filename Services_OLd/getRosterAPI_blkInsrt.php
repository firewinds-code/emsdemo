<?php
require_once(__dir__.'/../Config/init.php');
// require_once(CLS.'MysqliDb.php');

ini_set('display_errors', '1');
function store_execution_time($i){
$myDB= new MysqliDb();
$ClientD=$myDB->query("Insert into process_eod_status_log set start_time=now(),end_time=now(),No_of_execution='Roster bulk Insert ".$i."'");
		
}
echo "Executing API for  Get Roster create bulk API Start";
 echo "<br>";
store_execution_time('Start');

echo  $query="select EmployeeID as employee_id, FLOOR(UNIX_TIMESTAMP(curdate())*1000) as date_on,InTime as in_time, OutTime as out_time from roster_temp where DateOn= cast(now() as date)";
  // and EmployeeID in ('CE10091236','CE121622565') 

 echo "<br>";
 //die;
 $i=0;
 $myDB= new MysqliDb();

$EmpBio=$myDB->query($query);
 //print_r($EmpBio);die;

$rowCount = $myDB->count;
if($rowCount>0)
{
	$para= json_encode($EmpBio);
print_r($para);
echo "<br>";
		$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "http://172.105.61.198/web/index.php?r=/employee-roaster-rest/bulk-insert",
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
echo "<br>Executing API for Get Roster create bulk End";
store_execution_time('End');
