<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_REQUEST['location1']) && ($_REQUEST['client_name1']) && ($_REQUEST['process1'])) {
    $LOc = clean($_REQUEST['location1']);
    $client_name = clean($_REQUEST['client_name1']);
    $Process = clean($_REQUEST['process1']);
    $Query = "select sub_process,cm_id from new_client_master where process=? and location=? and client_name=? and cm_id not in (select cm_id from client_status_master)";
    $sql = $conn->prepare($Query);
    $sql->bind_param("sii", $Process, $LOc, $client_name);
    $sql->execute();
    $result = $sql->get_result();
    $res = $result->fetch_row();

    $toption = '<option value="NA"> -Select Sub Process- </option>';
    $toption1 = '<option value="NA"> -Select Reports_to- </option>';

    foreach ($result as $r) {
        //print_r($r);
        $toption .= "<option value='" . $r['cm_id'] . "'>" . $r['sub_process'] . "</option>";
    }
    $data['sub_process1'] = $toption;
    $data['reports_to1'] = $toption1;
}

echo json_encode($data);
