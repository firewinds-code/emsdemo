<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_REQUEST['client_name1']) && ($_REQUEST['location1'])) {
    $Client = clean($_REQUEST['client_name1']);
    $Location = clean($_REQUEST['location1']);
    $Query = "select distinct(process) from new_client_master where client_name=? and location=?";
    $sql = $conn->prepare($Query);
    $sql->bind_param("ii", $Client, $Location);
    $sql->execute();
    $result = $sql->get_result();
    $count = $result->num_rows;

    $toption = '<option value="NA"> -Select Process- </option>';
    $toption1 = '<option value="NA"> -Select Sub_process- </option>';
    $toption2 = '<option value="NA"> -Select reports_to- </option>';
    foreach ($result as $r) {
        //print_r($r);
        $toption .= "<option value='" . $r['process'] . "'>" . $r['process'] . "</option>";
    }
    $data['process1'] = $toption;
    $data['sub_process1'] = $toption1;
    $data['reports_to1'] = $toption2;
}
echo json_encode($data);
