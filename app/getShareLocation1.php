<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json");
$POST = file_get_contents('php://input');
$data = json_decode($POST, true);
$response = array();
$result['msg'] = '';


//$selectQry ="select latitude,longitude,latlongaddress,SUBSTRING_INDEX((SUBSTRING_INDEX(latlongaddress,',',-2)),',',1) Pincode from address_geo";
$selectQry ="select left(latitude,5) as latitude,left(longitude,5) as longitude,latlongaddress,SUBSTRING_INDEX((SUBSTRING_INDEX(latlongaddress,',',2)),',',-1) Place , count(*) as Count from address_geo group by left(latitude,5),left(longitude,5),Place;";
$myDB = new MysqliDb();
$response1 = $myDB->rawQuery($selectQry);
$result['msg'] = "location found";
$result['status'] = "true";

echo json_encode($response1);
