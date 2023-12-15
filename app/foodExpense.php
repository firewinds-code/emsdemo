<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json");
$POST = file_get_contents('php://input');
//$data = json_decode($POST, true);
$response = array();
$result['msg'] = '';
//print_r($_POST );
//exit;
$EmployeeID = $_POST['EmployeeID'];
$date = $_POST['date'];
$amount = $_POST['amount'];
$leaveStatus='Pending';
$receipt_no = $_POST['receipt_no'];
// $receipt_image = $_POST['receipt_image'];
$remarks = $_POST['remarks'];
$reqStatus='Pending';
$reviewerStatus='Pending';
$approverStatus='Pending';
$reqType='FoodRequest';
$empName= $_POST['empName'];


if (isset($_FILES['receipt_image']) && $_FILES['receipt_image'] != null) {
    $fn = $_FILES['receipt_image'];
    $fname = $fn['name'];
    $tempPath  =  $_FILES['receipt_image']['tmp_name'];
    $fileName  =  $_FILES['receipt_image']['name'];

    $dir_locationToSave= __DIR__.'/../ExpenseFood/';


    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get  extension

    $fileNameFinal = $EmployeeID . '_' . date('Y-m-d_His') . '.' . $fileExt;

    move_uploaded_file($tempPath, $dir_locationToSave . $fileNameFinal);
}
if($EmployeeID!=''){
$insertQry = "INSERT INTO expense_food(EmployeeID,date,amount, receipt_no, receipt_image,remarks,req_status,reqType,empName,reviewerStatus,approverStatus) VALUES('" . $_POST['EmployeeID'] . "','" . $date . "','" . $amount . "','" . $receipt_no . "','" . $fileNameFinal . "','" . $remarks . "','".$reqStatus."','".$reqType."','".$empName."','".$reviewerStatus."','".$approverStatus."') ";

$myDB = new MysqliDb();
$response3 = $myDB->rawQuery($insertQry);
$result['msg'] = "Request Raised sucessfully";
$result['status'] = 1;
}else{
$result['msg'] = "Invalid Request";
$result['status'] = 0;
}
echo json_encode($result);