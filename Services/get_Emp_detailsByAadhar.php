<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb_replica.php');
header("Content-Type: application/json; charset=UTF-8");
// $_POST = file_get_contents('php://input');
// $Data=json_decode($_POST,true);
$response = array();
$myDB =  new MysqliDb();
//print_r($Data);
if (isset($_REQUEST['aadhar']) && $_REQUEST['aadhar'] != '') {

  //Insert data To forgt_password attemp Table.
  $bQuerry = "call getdetailsByAadhar('" . $_REQUEST['aadhar'] . "')";

  $responseIn = $myDB->query($bQuerry);

  if (empty($myDB->getLastError()) && count($responseIn) > 0) {

    $result['msg'] = "data Found.";
    $result['status'] = 1;
    $result['data'] = $responseIn;
  } else {
    $result['msg'] = "Unable to get data, Please try agian later.";
    $result['status'] = 0;
  }
} else {
  $result['msg'] = "Bad Request";
  $result['status'] = 0;
}

echo  json_encode($result);
