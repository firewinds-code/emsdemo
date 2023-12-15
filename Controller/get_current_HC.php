<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 
$cmid = clean($_REQUEST['cmid']);
if (isset($_REQUEST['cmid'])) {
    $myDB = new MysqliDb();
    $conn = $myDB->dbConnect();
    $sqlCH = "select distinct count(t1.EmployeeID) as count from employee_map t1 join status_table t2 on t1.EmployeeID=t2.EmployeeID where cm_id=? and emp_status='Active' and t2.status=6 and t1.df_id in (74,77,146,147,148,149)";
    $stmtCH = $conn->prepare($sqlCH);
    $stmtCH->bind_param("i", $cmid);
    $stmtCH->execute();
    $ch_result = $stmtCH->get_result();
    $stmtCHcount = $ch_result->fetch_row();
    $ch_count = $stmtCHcount[0];
}
echo $ch_count;
