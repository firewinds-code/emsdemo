<?php
require_once(__dir__.'/../Config/init.php');
$empname=$_REQUEST['empname'];
$path=ROOT_PATH."appointpdf/".$empname.".pdf";
if(move_uploaded_file($_FILES['pdf']['tmp_name'], $path)){
	echo "1";
}else{
	echo "2";
}

?>