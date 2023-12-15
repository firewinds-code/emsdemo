<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_REQUEST['txt_location'])) {
    $loCation = clean($_REQUEST['txt_location']);

    $sqlclient = "select distinct(t3.client_name),t3.client_id from new_client_master t1 left join new_client_master_spoc t2 on t1.cm_id=t2.cm_id join client_master t3 on t1.client_name=t3.client_id join location_master t4 on t1.location=t4.id where t1.cm_id not in (select cm_id from client_status_master) and t2.id is null and t1.location=? order by t3.client_name";
    $sql = $conn->prepare($sqlclient);
    $sql->bind_param("i", $loCation);
    $sql->execute();
    $result = $sql->get_result();
    $count = $result->num_rows;

    $toption = '<option value="NA"> -Select Client Name- </option>';
    $toption1 = '<option value="NA"> -Select Process- </option>';
    $toption2 = '<option value="NA"> -Select Sub_process- </option>';

    foreach ($result as $key => $value) {
        $toption .= "<option value=" . $value['client_id'] . ">" . $value['client_name'] . "</option>";
    }

    $data['txt_Client_Name'] = $toption;
    $data['txt_Client_proc'] = $toption1;
    $data['txt_Client_subproc'] = $toption2;
}
echo json_encode($data);
