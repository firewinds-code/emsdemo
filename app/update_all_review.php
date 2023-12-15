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
        if(isset($data['reviewerStatus']) && $data['reviewerStatus']=='Decline'){
            $myDB = new MysqliDb();
            $query =  "update expense_food set reviewerStatus='" . $data['reviewerStatus'] . "',reviewComment='" . $data['reviewComment'] . "',req_status='Decline' ,modified_at=now()  where id='" . $data['id'] . "'";
            $myDB->rawQuery($query);
            $result['msg'] = "Successfully update";
            $result['status'] = 1;
        }else{

            $selectQry =  "update expense_food set reviewerStatus='" . $data['reviewerStatus'] . "',reviewComment='" . $data['reviewComment'] . "'  where id='" . $data['id'] . "'";
      
            $myDB = new MysqliDb();
            $response1 = $myDB->rawQuery($selectQry);
            $result['msg'] = "Successfully update";
            $result['status'] = 1;
        }
     
    } else if (isset($data['appkey']) && $data['appkey'] == 'TravelRequest') {
        if(isset($data['reviewerStatus']) && $data['reviewerStatus']=='Decline'){
            $myDB = new MysqliDb();
            $query =  "update expense_travel set reviewerStatus='" . $data['reviewerStatus'] . "',reviewComment='" . $data['reviewComment'] . "',req_status='Decline',modified_at=now()  where id='" . $data['id'] . "'";
            $myDB->rawQuery($query);
            $result['msg'] = "Successfully update";
            $result['status'] = 1;
        }else{
        $selectQry =  "update expense_travel set reviewerStatus='" . $data['reviewerStatus'] . "',reviewComment='" . $data['reviewComment'] . "' where id='" . $data['id'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);
        $result['msg'] = "Successfully update";
        $result['status'] = 1;
        }
    } else if (isset($data['appkey']) && $data['appkey'] == 'HotelRequest') {
        if(isset($data['reviewerStatus']) && $data['reviewerStatus']=='Decline'){
            $myDB = new MysqliDb();
            $query =  "update expense_hotel set reviewerStatus='" . $data['reviewerStatus'] . "',reviewComment='" . $data['reviewComment'] . "',req_status='Decline',modified_at=now()  where id='" . $data['id'] . "'";
            $myDB->rawQuery($query);
            $result['msg'] = "Successfully update";
            $result['status'] = 1;
        }else{
        $selectQry =  "update expense_hotel set reviewerStatus='" . $data['reviewerStatus'] . "',reviewComment='" . $data['reviewComment'] . "' where id='" . $data['id'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);
        $result['msg'] = "Successfully update";
        $result['status'] = 1;
        }
    } else if (isset($data['appkey']) && $data['appkey'] == 'MiscellaneousRequest') {
        if(isset($data['reviewerStatus']) && $data['reviewerStatus']=='Decline'){
            $myDB = new MysqliDb();
            $query =  "update expense_miscellaneous set reviewerStatus='" . $data['reviewerStatus'] . "',reviewComment='" . $data['reviewComment'] . "',req_status='Decline',modified_at=now()  where id='" . $data['id'] . "'";
            $myDB->rawQuery($query);
            $result['msg'] = "Successfully update";
            $result['status'] = 1;
        }else{
        $selectQry =  "update expense_miscellaneous set reviewerStatus='" . $data['reviewerStatus'] . "',reviewComment='" . $data['reviewComment'] . "' where id='" . $data['id'] . "'";
        $myDB = new MysqliDb();
        $response1 = $myDB->rawQuery($selectQry);

        $result['msg'] = "Successfully update";
        $result['status'] = 1;
        }
    }
} else {
    $result['msg'] = "Data not found.";
    $result['status'] = 0;
}

echo json_encode($result);
