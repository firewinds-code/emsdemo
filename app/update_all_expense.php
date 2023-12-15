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
        $selectQry =  "update expense_food set approverStatus='".$data['approverStatus']."', reviewerStatus='".$data['reviewerStatus']."', req_status='" . $data['req_status'] . "',mgrComment='" . $data['mgrComment'] . "',mgrStatus='" . $data['mgrStatus'] . "',modified_at=now() where id='" . $data['id'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);
        $result['msg'] = "Done";
        $result['status'] = 1;
    } else if (isset($data['appkey']) && $data['appkey'] == 'TravelRequest') {
        $selectQry =  "update expense_travel set approverStatus='".$data['approverStatus']."', reviewerStatus='".$data['reviewerStatus']."', req_status='" . $data['req_status'] . "',mgrComment='" . $data['mgrComment'] . "',mgrStatus='" . $data['mgrStatus'] . "',modified_at=now() where id='" . $data['id'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);
        $result['msg'] = "Done";
        $result['status'] = 1;
    } else if (isset($data['appkey']) && $data['appkey'] == 'HotelRequest') {
        $selectQry =  "update expense_hotel set approverStatus='".$data['approverStatus']."',reviewerStatus='".$data['reviewerStatus']."', req_status='" . $data['req_status'] . "',mgrComment='" . $data['mgrComment'] . "',mgrStatus='" . $data['mgrStatus'] . "',modified_at=now() where id='" . $data['id'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);
        $result['msg'] = "Done";
        $result['status'] = 1;
    } else if (isset($data['appkey']) && $data['appkey'] == 'MiscellaneousRequest') {
        $selectQry =  "update expense_miscellaneous set approverStatus='".$data['approverStatus']."',reviewerStatus='".$data['reviewerStatus']."', req_status='" . $data['req_status'] . "',mgrComment='" . $data['mgrComment'] . "' ,modified_at=now()  where id='" . $data['id'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);

        $result['msg'] = "Done";
        $result['status'] = 1;
    }
} else {
    $result['msg'] = "Data not found.";
    $result['status'] = 0;
}

echo json_encode($result);
