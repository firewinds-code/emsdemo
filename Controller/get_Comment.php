<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);
if ($id && $id != "") {
    $sql = "SELECT id, MasterId, EmployeeId, EmployeeName,`Comment`, Logintype, CreatedOn FROM apprisalcomment where MasterId=? ";
    // $sql = "SELECT t1.id, t1.MasterId, t1.EmployeeId, t1.EmployeeName,t1.`Comment`, t1.Logintype, t1.CreatedOn,t2.AHStatus,t2.HRStatus FROM apprisalcomment t1 left join apprisalmaster t2 on t1.MasterId=t2.id where MasterId=? ";
    $selectQ = $conn->prepare($sql);
    $selectQ->bind_param("i", $id);
    $selectQ->execute();
    $result = $selectQ->get_result();

    if ($result->num_rows > 0 && $result) {
        foreach ($result as $key => $value) {
            // echo $value['EmployeeName'];
            // die;
            // echo $value['AHStatus'];
            // die;
            echo '<p><span style="color: #218ea0;">' . $value['EmployeeName'] . ' (<b>' . $value['EmployeeId'] . '</b>) - </span> <span style=" font-weight: bold;">' . $value['CreatedOn'] . ' </span> : ' . $value['Comment'] . '. </p>';
        }
    }
    // else {
    //     echo "<script>$(function(){ toastr.error('No Comment.') }); </script>";
    // }
}
