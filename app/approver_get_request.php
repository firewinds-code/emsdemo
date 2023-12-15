<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$POST = file_get_contents('php://input');
$data = json_decode($POST, true);
$result = array();
if (isset($data) && count($data) > 0) {
    if (isset($data['appkey']) && $data['appkey'] == 'FoodRequest') {
        $selectQry = "SELECT * FROM expense_food Where reviewerStatus='Approve' AND  approverStatus='Pending'";
        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($selectQry);
        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['FoodRequest'] = $response;
    }
    if (isset($data['appkey']) && $data['appkey'] == 'HotelRequest') {
        $selectQry = "SELECT * FROM expense_hotel  Where reviewerStatus='Approve'  AND  approverStatus='Pending'";
        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($selectQry);
        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['HotelRequest'] = $response;
    }
    if (isset($data['appkey']) && $data['appkey'] == 'TravelRequest') {
        $selectQry = "SELECT * FROM expense_travel  Where reviewerStatus='Approve'  AND  approverStatus='Pending'";
        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($selectQry);
        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['TravelRequest'] = $response;
    }
    if (isset($data['appkey']) && $data['appkey'] == 'MiscellaneousRequest') {
        $selectQry = "SELECT * FROM expense_miscellaneous  Where reviewerStatus='Approve'  AND  approverStatus='Pending'";
        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($selectQry);
        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['MiscellaneousRequest'] = $response;
    }
}
echo json_encode($result);
