<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
date_default_timezone_set('Asia/Kolkata');

$dt_First = date('Y-m-d',strtotime('next monday'));
$dt_Last = date('Y-m-d',strtotime('next monday +6 days'));

$insert_exp = 'call sp_autoWOL("'.$dt_First.'","'.$dt_Last.'")';

echo($insert_exp);
$myDB = new mysql();
$exp_ins_flag = $myDB->query($insert_exp);
														
?>