<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

if(isset($_GET['adhar'])){
	$varadhar= $_GET['adhar'];
	echo $response = file_get_contents("http://lb.cogentlab.com:8081/Investment/checkaadhar.php?AadharNo=".$varadhar."");
}else{
	echo 'cant able to get data';
}

?>

