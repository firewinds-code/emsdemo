<?php

header("Content-Type: application/json");
$POST = file_get_contents('php://input');
//$data = json_decode($POST, true);
$response = array();
$result['msg'] = 'Data found';
$result['status'] = 1;
$result['phone']='01204832566';


echo json_encode($result);
