<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 0);
$dir = $_REQUEST['dir'];


if (is_dir($dir)) {
    echo 'true';
} else {
    echo 'false';
}
