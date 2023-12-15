<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json");
$POST = file_get_contents('php://input');
// $data = json_decode($POST, true);
$response = array();
$result['msg'] = '';
//print_r($POST );
//exit;

$fileNameFinal='';
$returnDate='';
$car_km_receipt='';
$car_toll_receipt='';
$car_parking_receipt='';
$EmployeeID = $_POST['EmployeeID'];
$date = $_POST['date'];
$placefrom = $_POST['placeFrom'];
$placeto = $_POST['placeTo'];
$modeoftravel = $_POST['modeOftravel'];
$amount = $_POST['amount'];
// $receipt_no='';
$receipt_no = $_POST['receipt_no'];
// $receipt_image = $_POST['receipt_image'];
$remarks = $_POST['remarks'];
$reqStatus='Pending';
$reviewerStatus='Pending';
$approverStatus='Pending';
$reqType='TravelRequest';
$empName=$_POST['empName'];
$returnDate=$_POST['returnDate'];
$car_km=$_POST['car_km'];



if (isset($_FILES['receipt_image']) && $_FILES['receipt_image'] != '') {

    $tempPath  =  $_FILES['receipt_image']['tmp_name'];
    $fileName  =  $_FILES['receipt_image']['name'];
    $dir_locationToSave = __DIR__ . '/../ExpenseTravel/';
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get  extension
    $fileNameFinal = $EmployeeID . '_' . date('Y-m-d_His') . '.' . $fileExt;
    move_uploaded_file($tempPath, $dir_locationToSave . $fileNameFinal);
   
}
if (isset($_FILES['car_km_receipt']) && $_FILES['car_km_receipt'] != '') {

    $tempPath  =  $_FILES['car_km_receipt']['tmp_name'];
    $fileName  =  $_FILES['car_km_receipt']['name'];
    $dir_locationToSave = __DIR__ . '/../ExpenseTravel/';
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get  extension
    $car_km_receipt = $EmployeeID . '_km' . date('Y-m-d_His') . '.' . $fileExt;
    move_uploaded_file($tempPath, $dir_locationToSave . $car_km_receipt);
   
}if (isset($_FILES['car_toll_receipt']) && $_FILES['car_toll_receipt'] != '') {

    $tempPath  =  $_FILES['car_toll_receipt']['tmp_name'];
    $fileName  =  $_FILES['car_toll_receipt']['name'];
    $dir_locationToSave = __DIR__ . '/../ExpenseTravel/';
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get  extension
    $car_toll_receipt = $EmployeeID . '_toll' . date('Y-m-d_His') . '.' . $fileExt;
    move_uploaded_file($tempPath, $dir_locationToSave . $car_toll_receipt);
   
}if (isset($_FILES['car_parking_receipt']) && $_FILES['car_parking_receipt'] != '') {

    $tempPath  =  $_FILES['car_parking_receipt']['tmp_name'];
    $fileName  =  $_FILES['car_parking_receipt']['name'];
    $dir_locationToSave = __DIR__ . '/../ExpenseTravel/';
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get  extension
    $car_parking_receipt = $EmployeeID . '_parking' . date('Y-m-d_His') . '.' . $fileExt;
    move_uploaded_file($tempPath, $dir_locationToSave . $car_parking_receipt);
   
}
if($EmployeeID!=''){
 $insertQry = "INSERT INTO expense_travel(EmployeeID,date,placeFrom,placeTo,modeOftravel,amount, receipt_no, receipt_image,remarks,req_status,reqType,empName,reviewerStatus,approverStatus,returnDate,car_km,car_km_receipt,car_toll_receipt,car_parking_receipt) VALUES('" . $_POST['EmployeeID'] . "','" . $date . "','" . $placefrom . "','" . $placeto . "','" . $modeoftravel . "','" . $amount . "','" . $receipt_no . "','" . $fileNameFinal . "','" . $remarks . "','".$reqStatus."','".$reqType."','".$empName."','".$reviewerStatus."','".$approverStatus."','".$returnDate."','".$car_km."','".$car_km_receipt."','".$car_toll_receipt."','".$car_parking_receipt."')";

$myDB = new MysqliDb();
$response = $myDB->rawQuery($insertQry);
$result['msg'] = "Raised request succesffully";
$result['status'] = 1;
}else{
$result['msg'] = "Invalid request";
$result['status'] = 0;

}
echo json_encode($result);
