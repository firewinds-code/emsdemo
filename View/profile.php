<?php 
$empid1=$_REQUEST['iid'];
$empid2=explode("cmid",$empid1);
$empid=$empid2[0];
$empid3=explode("doj",$empid2[1]);
$cmid=$empid3[0];

$empid4 = explode("page",$empid3[1]);
$doj = $empid4[0];

$empid5 = explode("empname",$empid4[1]);
if(isset($empid5[0])){
	$page = $empid5[0];
}
$empname='';
if(isset($empid5[1])){
	$empname = $empid5[1];
}


$empname = str_replace("-"," ",$empname);
#$empname = trim($empname);

$location="https://cogentems.in/candidate_info/index.php?iid=".$empid."&cmid=".$cmid."&doj=".$doj."&page=".$page."&name=".$empname;
echo "<script>location.href='".$location."'</script>";
?>
