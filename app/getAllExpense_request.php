<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$POST = file_get_contents('php://input');
$data = json_decode($POST, true);
$response1 = array();
$result['msg'] = '';
if (isset($data) && count($data) > 0) {
    if (isset($data['appkey']) && $data['appkey'] == 'FoodRequest') {
        $selectQry =  "SELECT * FROM expense_food where EmployeeID='" . $data['EmployeeID'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);
        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['FoodRequest'] = $response1;
    } else if (isset($data['appkey']) && $data['appkey'] == 'TravelRequest') {
        $selectQry =  "SELECT * FROM expense_travel where EmployeeID='" . $data['EmployeeID'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);
        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['TravelRequest'] = $response1;
    } else if (isset($data['appkey']) && $data['appkey'] == 'HotelRequest') {
        $selectQry =  "SELECT * FROM expense_hotel where EmployeeID='" . $data['EmployeeID'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);
        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['HotelRequest'] = $response1;
    } else if (isset($data['appkey']) && $data['appkey'] == 'MiscellaneousRequest') {
        $selectQry =  "SELECT * FROM expense_miscellaneous where EmployeeID='" . $data['EmployeeID'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);

        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['MiscellaneousRequest'] = $response1;
    }
} else {
    $result['msg'] = "Data not found.";
    $result['status'] = 0;
}


echo json_encode($result);
