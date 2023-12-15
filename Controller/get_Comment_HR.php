<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);
if ($id && $id != "") {
    // $sql = "SELECT id, MasterId, EmployeeId, EmployeeName,`Comment`, Logintype, CreatedOn FROM apprisalcomment where MasterId=? ";
    $sql = "SELECT t1.`Comment`,t2.AHStatus,t2.HRStatus FROM apprisalcomment t1 right join apprisalmaster t2 on t1.MasterId=t2.id where MasterId=?   ";
    $selectQ = $conn->prepare($sql);
    $selectQ->bind_param("i", $id);
    $selectQ->execute();
    $result = $selectQ->get_result();

    if ($result->num_rows > 0 && $result) {
        foreach ($result as $key => $value) {
        }
        echo '<p> <b>Final-Status : </b><span>' . $value['HRStatus'] . '</span> </p>';
    }
    // else {
    //     echo "<script>$(function(){ toastr.error('No Comment.') }); </script>";
    // }
}
