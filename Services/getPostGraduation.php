<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query= null;
$myDB=new MysqliDb();
$Query="SELECT id,edu_name FROM education_name where edu_lvl='Post Graduation'";
$res =$myDB->query($Query);
if($res)
{
	foreach($res as $key=>$value)																															{
		$result[] = $value;
	}
	$result = json_encode($result);
	echo $result;
}
else
{
	echo NULL;
}
	
	?>