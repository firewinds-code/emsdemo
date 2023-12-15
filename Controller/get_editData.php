<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$location1 = '';
$client_name1 = '';
$process1 = '';
$sub_process1 = '';
if (isset($_REQUEST['location1'])) {
    if (isset($_REQUEST['location1']) && $_REQUEST['location1'] != "") {
        $loCation = clean($_REQUEST['location1']);
    }
    if (isset($_REQUEST['client_name1']) && $_REQUEST['client_name1'] != "") {
        $client_name1 = clean($_REQUEST['client_name1']);
    }
    if (isset($_REQUEST['process1']) && $_REQUEST['process1'] != "") {
        $process1 = clean($_REQUEST['process1']);
    }
    if (isset($_REQUEST['sub_process1']) && $_REQUEST['sub_process1'] != "") {
        $sub_process1 = clean($_REQUEST['sub_process1']);
    }

    $resulthtml = '';
    $sqlclient = "select client_id,client_name from client_master where client_id in (select distinct client_name from whole_details_peremp where location=?) order by client_name";
    $sql = $conn->prepare($sqlclient);
    $sql->bind_param("i", $loCation);
    $sql->execute();
    $result = $sql->get_result();
    $count = $result->num_rows;
    if ($count > 0 && $result) {
        $resulthtml .= '<option value="NA" >---Select---</option>';
        foreach ($result as $key => $value) {
            if ($client_name1 == $value['client_id']) {
                $resulthtml .= '<option value="' . $value['client_id'] . '" selected>' . $value['client_name'] .  '</option>';
            } else {
                $resulthtml .= '<option value="' . $value['client_id'] . '">' . $value['client_name'] . '</option>';
            }
        }
    } else {
        $resulthtml .= '<option value="NA" >---Select---</option>';
    }
    $resulthtml .= '||NA||';

    $Query = "select distinct(process) from new_client_master where client_name=? and location=?";
    $sql = $conn->prepare($Query);
    $sql->bind_param("ii", $client_name1, $loCation);
    $sql->execute();
    $result = $sql->get_result();
    $count = $result->num_rows;
    if ($count > 0 && $result) {
        $resulthtml .= '<option value="NA" >---Select---</option>';
        foreach ($result as $key => $value) {
            if ($process1 == $value['process']) {
                $resulthtml .= '<option value="' . $value['process'] . '" selected>' . $value['process'] .  '</option>';
            } else {
                $resulthtml .= '<option value="' . $value['process'] . '">' . $value['process'] . '</option>';
            }
        }
    } else {
        $resulthtml .= '<option value="NA" >---Select---</option>';
    }
    $resulthtml .= '||NA||';

    $Query = "select sub_process,cm_id from new_client_master where process=? and location=? and cm_id not in (select cm_id from client_status_master)";
    $sql = $conn->prepare($Query);
    $sql->bind_param("si", $process1, $loCation);
    $sql->execute();
    $result = $sql->get_result();
    $count = $result->num_rows;
    if ($count > 0 && $result) {
        $resulthtml .= '<option value="NA" >---Select---</option>';
        foreach ($result as $key => $value) {
            if ($sub_process1 == $value['cm_id']) {
                $resulthtml .= '<option value="' . $value['cm_id'] . '" selected>' . $value['sub_process'] .  '</option>';
            } else {
                $resulthtml .= '<option value="' . $value['cm_id'] . '">' . $value['sub_process'] . '</option>';
            }
        }
    } else {
        $resulthtml .= '<option value="NA" >---Select---</option>';
    }
    $resulthtml .= '||NA||';
}
echo $resulthtml;
