<?php

require_once(__dir__ . '/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
include_once("../Services/sendsms_API1.php");

$myDB = new MysqliDb();
if (isset($_REQUEST['reqtype']) && $_REQUEST['reqtype'] == "OTPSubmit") {
    // $_REQUEST['txt_otp'];
    // echo $_REQUEST['code'];
    // die;
    if ($_REQUEST['txt_otp'] == $_REQUEST['code']) {
        echo 1;
    } else {
        echo 0;
    }
}
