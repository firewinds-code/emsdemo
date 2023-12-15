<?php
 $q= htmlspecialchars($_GET["q"]) ;
//$location="http://192.168.202.252/ems/View/info?empid=CE10091236";
//header('Location: /ems/View/atnd');
$isvalid=0;
if (strpos($q, 'attendance') !== false) {
  $location= "/ems/View/atnd";
  $isvalid=1;
}
else if (strpos($q, 'profile') !== false) {
		 $user=$_COOKIE["usrnm"];
  $location= "/ems/View/info?empid=" .$user;
  $isvalid=1;
}
else if (strpos($q, 'exception') !== false) {
$location= "/ems/View/addReq";
$isvalid=1;
}
else
{
	$msg= "Sorry, I am not getting you, please try again...";	
	$location="http://localhost/demo/speachtosearch.php?id=".$msg;
	header("Location: $location");
}
if($isvalid==1)
header("Location: $location");
?>