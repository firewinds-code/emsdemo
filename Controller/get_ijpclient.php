<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 
$myDB = new MysqliDb();
$connn = $myDB->dbConnect();

if (isset($_REQUEST['location1'])) {
    $loCation = $_REQUEST['location1'];
    $sqlclient = '';
    $sqlclient .= "select distinct t2.client_id,t2.client_name from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where";

    if (($_REQUEST['location1']) == "ALL") {
        $sqlclient .= " cm_id not in (select cm_id from client_status_master) order by t2.client_name;";
    } else {
        $sqlclient .= " location='" . $_REQUEST['location1'] . "' and cm_id not in (select cm_id from client_status_master) order by t2.client_name;";
    }
    // echo $sqlclient;
    $result = $myDB->query($sqlclient);
    // $sql = $connn->prepare($sqlclient);
    // $sql->bind_param("i", $loCation);
    // $sql->execute();
    // $result = $sql->get_result();
    // $count = $result->num_rows;

    $toption = '<option value="NA"> -Select Client Name- </option>';
    $toption = '<option value="ALL"> ALL </option>';
    foreach ($result as $key => $value) {
        $toption .= "<option value=" . $value['client_id'] . ">" . $value['client_name'] . "</option>";
    }

    $data['client_name1'] = $toption;
}
echo json_encode($data);
