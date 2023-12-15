<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);

$sql = "SELECT id, MasterId, EmployeeId, EmployeeName,`Comment`, Logintype, CreatedOn FROM apprisalcomment where MasterId=? ";
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("i", $id);
$selectQ->execute();
$result = $selectQ->get_result();

if ($result->num_rows > 0 && $result) {
    foreach ($result as $key => $value) {
        // echo $value['EmployeeName'];
        // die;
        echo '<p><span style="color: #218ea0;">' . $value['EmployeeName'] . ' (<b>' . $value['EmployeeId'] . '</b>) - </span> <span style=" font-weight: bold;">' . $value['CreatedOn'] . ' </span> : ' . $value['Comment'] . '. </p>';
    }
} else {
    echo "<script>$(function(){ toastr.error('No Comment.') }); </script>";
}
