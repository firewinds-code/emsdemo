<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');

// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

// $sql = 'Delete from client_ref_master where id="' . $_REQUEST['id'] . '" ';
// $myDB = new MysqliDb();
// $result = $myDB->rawQuery($sql);
// $row_count = $myDB->count;
//$mysql_error = $myDB->getLastError();

$ID = clean($_REQUEST['id']);
$sql = 'Delete from client_ref_master where id=? ';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ID);
$delt = $stmt->execute();

//if (empty($mysql_error)) {
if (!empty($delt)) {
    echo "Done|Row Deleted Successfully";
} else {
    echo "No|Row Not Deleted Try Again :";
}
