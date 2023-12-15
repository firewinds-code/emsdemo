<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");
$POST = file_get_contents('php://input');
// $data = json_decode($POST, true);
//print_r($data);
//exit;
$response = array();
$result['msg'] = '';

$EmployeeID = $_POST['EmployeeID'];
$date = $_POST['date'];
$amount = $_POST['amount'];
$remarks = $_POST ['remarks'];
$reqStatus='Pending';
$reviewerStatus='Pending';
$approverStatus='Pending';
$reqType='MiscellaneousRequest';
$empName=$_POST['empName'];
$receipt_no=$_POST['receipt_no'];
if (isset($_FILES['receipt_image']) && $_FILES['receipt_image'] != '') {

    $tempPath  =  $_FILES['receipt_image']['tmp_name'];
    $fileName  =  $_FILES['receipt_image']['name'];
    $dir_locationToSave = __DIR__ . '/../ExpenseMiscellaneous/'; 
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get  extension
    $fileNameFinal = $EmployeeID . '_' . date('Y-m-d_His') . '.' . $fileExt;
    move_uploaded_file($tempPath, $dir_locationToSave . $fileNameFinal);
   
}else{
  $fileNameFinal = '';
}


if($EmployeeID!=''){
  $insertQry = "INSERT INTO expense_miscellaneous(EmployeeID,date,amount,remarks,req_status,reqType,empName,reviewerStatus,approverStatus,receipt_image,receipt_no) VALUES('" . $_POST ['EmployeeID'] . "','" . $date . "','" . $amount . "','" . $remarks . "','".$reqStatus."','".$reqType."','".$empName."','".$reviewerStatus."','".$approverStatus."','".$fileNameFinal."','".$receipt_no."') ";

$myDB = new MysqliDb();
$response = $myDB->rawQuery($insertQry);
$result['msg'] = "Raised request succesffully";
$result['status'] = 1;
}else{
$result['msg'] = "Invalid request";
$result['status'] = 0;
}
echo json_encode($result);
