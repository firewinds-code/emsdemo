<?php
/*
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://172.105.61.198/web/index.php?r=attendance-rest/create",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 500,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "employeeId=CE10091236&date=1604293745405&office_entry=1604293825962&office_exit=1604293825962&status=P&reason=in&remarks=loggedin",
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
}*/




$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL =>  "http://172.105.61.198/web/index.php?r=process-eod-stats-rest/create",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
 
  CURLOPT_CONNECTTIMEOUT=> 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "date=1604341800000&client_id=8&client_name=FedEx&process_id=8&process_name=FedEx&sub_process_id=8&sub_process_name=FedEx&location=1&location_name=Noida&active_on_floor=50&rostered=70&present=61&hd=10&leaves=1&wo=5&absent=10&on_floor_attrition=10&active_ojt=19&active_in_training=15",
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
}
?>