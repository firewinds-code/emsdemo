<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json");
$POST = file_get_contents('php://input');
$data = json_decode($POST, true);
// print_r($data);
$response = array();
$result['msg'] = '';
// $data['EmployeeID']='';
if($data['EmployeeID']!='' && $data['appkey']=='getApproverReviewer' ){
$insertQry = "Select * from expense_matrix Where EmployeeID='".$data['EmployeeID']."'";

$myDB = new MysqliDb();
$response = $myDB->rawQuery($insertQry);
if(count($response)>0)
{
    $result['msg'] = "Data found";
    $result['status'] = 1;
    $result['is_reviewer']=$response[0]['is_reviewer'];
    $result['is_approver']=$response[0]['is_approver'];
}
else
{
    $result['msg'] = "Data not found";
$result['status'] = 0;
$result['is_reviewer']='No';
$result['is_approver']='No';
}


}else{
$result['msg'] = "Invalid Request";
$result['status'] = 0;
}
echo json_encode($result);