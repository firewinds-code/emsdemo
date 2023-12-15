<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json");
$POST = file_get_contents('php://input');
// $data = json_decode($POST, true);
$response = array();
$result['msg'] = '';

$EmployeeID = $_POST['EmployeeID'];
$datefrom = $_POST['dateFrom'];
$dateto = $_POST['dateTo'];
$noofdays = $_POST['noOfdays'];
$amount = $_POST['amount'];
$receipt_no = $_POST['receipt_no'];
$hotelName = $_POST['hotelName'];
$remarks = $_POST['remarks'];
$reqStatus='Pending';
$reviewerStatus='Pending';
$approverStatus='Pending';
$reqType='HotelRequest';
$empName=$_POST['empName'];


if (isset($_FILES['receipt_image']) && $_FILES['receipt_image'] != null) {

    $tempPath  =  $_FILES['receipt_image']['tmp_name'];
    $fileName  =  $_FILES['receipt_image']['name'];
    $dir_locationToSave = __DIR__ . '/../ExpenseHotel/';
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get  extension
    $fileNameFinal = $EmployeeID . '_' . date('Y-m-d_His') . '.' . $fileExt;
    move_uploaded_file($tempPath, $dir_locationToSave . $fileNameFinal);
   
}
if($EmployeeID!=''){
$insertQry = "INSERT INTO expense_hotel(EmployeeID,dateFrom,dateTo,noOfdays,amount, receipt_no, receipt_image,remarks,req_status,reqType,empName,reviewerStatus,approverStatus,hotelName) VALUES('" . $_POST['EmployeeID'] . "','" . $datefrom . "','" . $dateto . "','" . $noofdays . "','" . $amount . "','" . $receipt_no . "','" . $fileNameFinal . "','" . $remarks . "','".$reqStatus."','".$reqType."','".$empName."','".$reviewerStatus."','".$approverStatus."','".$hotelName."') ";
$myDB = new MysqliDb();
$response = $myDB->rawQuery($insertQry);
$result['msg'] = "Raised request succesffully";
$result['status'] = 1;
}else{
$result['msg'] = "Invalid request";
$result['status'] = 0;

}
echo json_encode($result);
