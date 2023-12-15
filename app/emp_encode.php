<?php

// date_default_timezone_set('Asia/Kolkata');
//  $empId=$_REQUEST['empId'];
// echo base64_encode($empId);

header("Content-Type: application/json");
$POST = file_get_contents('php://input');
$data = json_decode($POST, true);
$response = array();

$empId = $data['empId'];
$encodeEmpID = base64_encode($empId);

$response['msg'] = 'Data found';
$response['status'] = 1;
$response['response'] = $encodeEmpID;


echo json_encode($response);
