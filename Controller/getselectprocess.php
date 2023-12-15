<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_REQUEST['txt_location']) && ($_REQUEST['txt_location'])) {
    $txt_location = clean($_REQUEST['txt_location']);
    $txt_Client_Name = clean($_REQUEST['txt_Client_Name']);
    $Query = "select distinct(t1.process) from new_client_master t1 left join new_client_master_spoc t2 on t1.cm_id=t2.cm_id join client_master t3 on t1.client_name=t3.client_id join location_master t4 on t1.location=t4.id where t1.cm_id not in (select cm_id from client_status_master) and t2.id is null and t1.location=? and t3.client_id=? order by t3.client_name";
    $sql = $conn->prepare($Query);
    $sql->bind_param("is", $txt_location, $txt_Client_Name);
    $sql->execute();
    $result = $sql->get_result();
    $count = $result->num_rows;

    $toption = '<option value="NA"> -Select Process- </option>';
    $toption1 = '<option value="NA"> -Select Sub_process- </option>';

    foreach ($result as $r) {
        //print_r($r);
        $toption .= "<option value='" . $r['process'] . "'>" . $r['process'] . "</option>";
    }
    $data['txt_Client_proc'] = $toption;
    $data['txt_Client_subproc'] = $toption1;
}
echo json_encode($data);
