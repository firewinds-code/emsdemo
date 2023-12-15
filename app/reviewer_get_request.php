<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$POST = file_get_contents('php://input');
$data = json_decode($POST, true);
$result = array();
$EmployeeID = $data['EmployeeID'];
if (isset($data) && count($data) > 0) {
    if (isset($data['appkey']) && $data['appkey'] == 'FoodRequest') {
        // $selectQry = "SELECT * FROM expense_food Where reviewerStatus='Pending' AND req_status='Pending'";

        $selectQry = 'SELECT t1.* FROM ems.expense_food t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID="' . $EmployeeID . '" AND is_reviewer="Yes" AND reviewerStatus="Pending" AND req_status="Pending"';

        $myDb = new MysqliDb();
       $response = $myDb->rawQuery($selectQry);
    //    print_r($response);
        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['FoodRequest'] = $response;
    }
    if (isset($data['appkey']) && $data['appkey'] == 'HotelRequest') {
        // $selectQry = "SELECT t1.* FROM expense_hotel  Where reviewerStatus='Pending' AND req_status='Pending'";

        $selectQry = 'SELECT t1.* FROM ems.expense_hotel t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID="' . $EmployeeID . '" AND is_reviewer="Yes" AND reviewerStatus="Pending" AND req_status="Pending"';


        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($selectQry);
    //    print_r($response);

        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['HotelRequest'] = $response;
    }
    if (isset($data['appkey']) && $data['appkey'] == 'TravelRequest') {
        // $selectQry = "SELECT t1.* FROM expense_travel  Where reviewerStatus='Pending' AND req_status='Pending'";

        $selectQry = 'SELECT t1.* FROM ems.expense_travel t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID="' . $EmployeeID . '" AND is_reviewer="Yes" AND reviewerStatus="Pending" AND req_status="Pending"';


        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($selectQry);
        $result['msg'] = "data found";
        $result['status'] = 1;
        $result['TravelRequest'] = $response;
    }
    if (isset($data['appkey']) && $data['appkey'] == 'MiscellaneousRequest') {
        // $selectQry = "SELECT t1.* FROM expense_miscellaneous  Where reviewerStatus='Pending' AND req_status='Pending'";

        $selectQry = 'SELECT t1.* FROM ems.expense_miscellaneous t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID="' . $EmployeeID . '" AND is_reviewer="Yes" AND reviewerStatus="Pending" AND req_status="Pending"';


        $myDb = new MysqliDb();
        $response = $myDb->rawQuery($selectQry);
        $result['msg'] = "data found"; 
        $result['status'] = 1;
        $result['MiscellaneousRequest'] = $response;
    }
}else{
    $result['msg'] = "Bad Request";
    $result['status'] = 0;
    // $result['MiscellaneousRequest'] = $response;
}
echo json_encode($result);
