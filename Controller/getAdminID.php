<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$client_id = '';
$loc = '';
$adminID = '';
$ITID = '';
$resulthtml = '';
if (isset($_REQUEST['loc']) && $_REQUEST['loc'] != "") {
    $loc = clean($_REQUEST['loc']);
    $adminID = clean($_REQUEST['adminID']);
    $ITID = clean($_REQUEST['ITID']);
    $client_id = clean($_REQUEST['client_id']);

    $sqlclient = "select concat(t3.EmpName,'(',t3.EmpID,')') as Emp,t3.EmpID from new_client_master t1 join employee_map t2 on t1.cm_id=t2.cm_id join EmpID_Name t3 on t2.EmployeeID=t3.EmpID where location=? and Process='Administration' and sub_process='Administration' and emp_status='Active' and t1.cm_id not in (select cm_id from client_status_master) order by t3.EmpName";
    $sql = $conn->prepare($sqlclient);
    $sql->bind_param("i", $loc);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0 && $result) {
        $resulthtml .= '<option value="NA" >---Select---</option>';
        foreach ($result as $key => $value) {
            if ($adminID == $value['EmpID']) {
                $resulthtml .= '<option value="' . $value['EmpID'] . '" selected>' . $value['Emp'] .  '</option>';
            } else {
                $resulthtml .= '<option value="' . $value['EmpID'] . '">' . $value['Emp'] . '</option>';
            }
        }
    } else {
        $resulthtml .= '<option value="NA" >---Select---</option>';
    }

    $resulthtml .= '||NA||';

    $sqlclient = "select concat(t3.EmpName,'(',t3.EmpID,')') as Emp,t3.EmpID from new_client_master t1 join employee_map t2 on t1.cm_id=t2.cm_id join EmpID_Name t3 on t2.EmployeeID=t3.EmpID where location=? and Process='Information Technology' and sub_process='Information Technology' and emp_status='Active' and t1.cm_id not in (select cm_id from client_status_master) order by t3.EmpName";
    $sql = $conn->prepare($sqlclient);
    $sql->bind_param("i", $loc);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0 && $result) {
        $resulthtml .= '<option value="NA" >---Select---</option>';
        foreach ($result as $key => $value) {
            if ($ITID == $value['EmpID']) {
                $resulthtml .= '<option value="' . $value['EmpID'] . '" selected>' . $value['Emp'] .  '</option>';
            } else {
                $resulthtml .= '<option value="' . $value['EmpID'] . '">' . $value['Emp'] . '</option>';
            }
        }
    } else {
        $resulthtml .= '<option value="NA" >---Select---</option>';
    }

    $resulthtml .= '||NA||';

    $sqlclient = "select s.cm_id,c.client_name,n.process,n.sub_process from new_client_master_spoc s join new_client_master n on n.cm_id=s.cm_id join client_master c on c.client_id=n.client_name where s.cm_id=?";
    $sql = $conn->prepare($sqlclient);
    $sql->bind_param("i", $client_id);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0 && $result) {
        $resulthtml .= '<option value="NA" >---Select---</option>';
        foreach ($result as $key => $value) {
            $resulthtml .= '<option value="' . $value['client_name'] . '" selected>' . $value['client_name'] .  '</option>';
        }
    } else {
        $resulthtml .= '<option value="NA" >---Select---</option>';
    }

    $resulthtml .= '||NA||';

    if ($result->num_rows > 0 && $result) {
        $resulthtml .= '<option value="NA" >---Select---</option>';
        foreach ($result as $key => $value) {
            $resulthtml .= '<option value="' . $value['process'] . '" selected>' . $value['process'] .  '</option>';
        }
    } else {
        $resulthtml .= '<option value="NA" >---Select---</option>';
    }

    $resulthtml .= '||NA||';

    if ($result->num_rows > 0 && $result) {
        $resulthtml .= '<option value="NA" >---Select---</option>';
        foreach ($result as $key => $value) {
            $resulthtml .= '<option value="' . $value['cm_id'] . '" selected>' . $value['sub_process'] .  '</option>';
        }
    } else {
        $resulthtml .= '<option value="NA" >---Select---</option>';
    }

    $resulthtml .= '||NA||';
}


echo $resulthtml;
