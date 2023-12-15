<?php

require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');

$selectQry = 'select * from expense_food where id="' . $_REQUEST['id'] . '"';
$myDB = new MysqliDb();
$res = $myDB->rawQuery($selectQry);

if (count($res) > 0) {
    $delLog = "insert into expense_log(EmployeeID,empName,date,reqType,remarks)values('" . $res[0]['EmployeeID'] . "','" . $res[0]['empName'] . "','" . $res[0]['date'] . "','" . $res[0]['reqType'] . "','" . $res[0]['remarks'] . "')";
    $insertQry = $myDB->rawQuery($delLog);


    $sql = 'delete from expense_food where id="' . $_REQUEST['id'] . '"';
    $myDB = new MysqliDb();
    $result = $myDB->rawQuery($sql);
    $row_count = $myDB->count;
    $mysql_error = $myDB->getLastError();

    if (empty($mysql_error)) {
        echo "Done|Row Deleted Successfully";
    } else {
        echo "No|Row Not Deleted Try Again :" . $mysql_error;
    }
}
