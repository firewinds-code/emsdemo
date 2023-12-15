<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');

$servername = "172.104.207.201";
$username = "localconnect";
$password = "Ra34L@L9#yF793";
//*/

// Create connection
$conn = new mysqli($servername, $username, $password);
//print_r($conn);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully" . '<br/>';
    $myDB = new MysqliDb();
    $sql = "call test();";
    $data = $myDB->query($sql);
    var_dump($data);
}
