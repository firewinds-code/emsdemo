<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json");
$POST = file_get_contents('php://input');
$data = json_decode($POST, true);
$response = array();
$result['msg'] = '';


$selectQry =  "SELECT latitude,longitude,latlongaddress FROM address_geo";
$myDB = new MysqliDb();
$response1 = $myDB->rawQuery($selectQry);
$result['msg'] = "location found";
$result['status'] = "true";

echo json_encode($response1);
