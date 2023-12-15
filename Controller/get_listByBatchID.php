<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

$batchID = clean($_REQUEST['batchID']);
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_REQUEST['batchID'])) {

    $select = " select nc.client_name,location from batch_master bt join new_client_master nc on bt.cm_id=nc.cm_id where bt.BacthID=?";
    $sel = $conn->prepare($select);
    $sel->bind_param("i", $batchID);
    $sel->execute();
    $results = $sel->get_result();
    $result = $results->fetch_row();
    $loc = $result[1];
    $client = $result[0];

    $select1 = "select EmpID_Name.EmpID,EmpID_Name.EmpName,Designation from df_master inner join employee_map on employee_map.df_id=df_master.df_id inner join designation_master on designation_master.ID=df_master.des_id inner join EmpID_Name on EmpID_Name.EmpID=employee_map.EmployeeID inner join status_table on status_table.EmployeeID=employee_map.EmployeeID join new_client_master nc on employee_map.cm_id = nc.cm_id where (des_id not in (9,12,33,34,35,36) and function_id in (10,8,7) and employee_map.emp_status='Active' and EmpID_Name.EmpID is not null ) and status_table.Status = 6 and nc.location=? and nc.client_name=? order by EmpName;";
    $sel1 = $conn->prepare($select1);
    $sel1->bind_param("ii", $loc, $client);
    $sel1->execute();
    $results1 = $sel1->get_result();
    if ($results1->num_rows > 0 && $results1) {
        echo '<option value="NA" >---Select---</option>';
        foreach ($results1 as $key => $value) {
            echo '<option value="' . $value['EmpID'] . '">' . $value['EmpName'] . '(' . $value['EmpID'] . ')' . '</option>';
        }
    } else {
        echo '<option value="NA" >---Select---</option>';
    }
}
