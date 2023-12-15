<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
if (isset($_GET['empid']) && $_GET['empid'] != '') {
    $myDB = new MysqliDb();
    $insert = $myDB->query("Insert into `promoted_csa_emp_doc` set  EmployeeID='" . $_GET['empid'] . "', download_flag=1");
}
