<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
    $delid = $_REQUEST['id'];
} else {
    $delid = '';
}
$sql = 'delete from comp_issue_file where id=?';
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("i", $delid);
$selectQ->execute();
$result = $selectQ->get_result();

if ($selectQ->affected_rows === 1) {
    echo "Done|Row Deleted Successfully";
} else {
    echo "No|Row Not Deleted Try Again";
}
