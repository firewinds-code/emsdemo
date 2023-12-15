<?php

require_once(__dir__ . '/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
include_once("../Services/sendsms_API1.php");

$myDB = new MysqliDb();

$sql = "select password from employee_map where EmployeeID='" . $_REQUEST['id'] . "';";
$password  = $myDB->query($sql);
if (count($password) > 0) {
    if ($password[0]['password'] != md5($_REQUEST['chg_pwd'])) {
        $chng_pwd = 'call change_pwd("' . md5($_REQUEST['chg_pwd']) . '","' . $_REQUEST['id'] . '")';
        $result  = $myDB->rawQuery($chng_pwd);
        $mysql_error = $myDB->getLastError();
        $rowCount = $myDB->count;

        if (empty($mysql_error)) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
}
