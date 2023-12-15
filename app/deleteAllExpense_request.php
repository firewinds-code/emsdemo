<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$POST = file_get_contents('php://input');
$data = json_decode($POST, true);
$result = array();
if (isset($data) && count($data) > 0) {
    if (isset($data['appkey']) && $data['appkey'] == 'DeleteFoodRequest') {
        $deleteQry = "DELETE FROM expense_food WHERE id='" . $data['id'] . "'";
        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($deleteQry);
        $result['msg'] = "Deleted";
        $result['status'] = 1;
    }
    if (isset($data['appkey']) && $data['appkey'] == 'DeleteTravelRequest') {
        $deleteQry = "DELETE FROM expense_travel WHERE id='" . $data['id'] . "'";
        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($deleteQry);
        $result['msg'] = "Deleted";
        $result['status'] = 1;
    }
    if (isset($data['appkey']) && $data['appkey'] == 'DeleteHotelRequest') {
        $deleteQry = "DELETE FROM expense_hotel WHERE id='" . $data['id'] . "'";
        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($deleteQry);
        $result['msg'] = "Deleted";
        $result['status'] = 1;
    }
    if (isset($data['appkey']) && $data['appkey'] == 'DeleteMiscellaneousRequest') {
        $deleteQry = "DELETE FROM expense_miscellaneous WHERE id='" . $data['id'] . "'";
        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($deleteQry);
        $result['msg'] = "Deleted";
        $result['status'] = 1;
    }
}
echo json_encode($result);
