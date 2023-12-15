<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 0);


if (empty($_REQUEST['term'])) {

    $data['msg'] = "Please Give some input";
    $data['status'] = "0";
    echo json_encode($data);
    die;
}

if (strlen($_REQUEST['term']) <= 2) {

    $data['msg'] = "Minimum 3 Latters is Required";
    $data['status'] = "0";
    echo json_encode($data);
    die;
}

$term = $_REQUEST['term'];
$myDB = new MysqliDb();
//echo 'call get_autofill_data_searchTable("'.$term.'")';
$result = $myDB->query('call get_autofill_data_forComplaint("' . $term . '")');
foreach ($result  as $key => $value) {
    $data[] = $value['EmpName'] . ' (' . $value['EmpID'] . ')';
}
echo json_encode($data);
