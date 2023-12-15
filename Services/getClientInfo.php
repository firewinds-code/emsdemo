<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '0');
/* header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);*/
$getPara=$sql=$result='';
// $password=base64_encode('d@shb0@rd');ZEBzaGIwQHJk
$clientId=0;
$pwd=base64_decode($_REQUEST['pwd']);
if(isset($_REQUEST['uid']) and $_REQUEST['uid']=='dashboard' and 'd@shb0@rd'==$pwd ){
	
if(isset($_REQUEST['cid'])){
	$clientId=$_REQUEST['cid'];
}
if(isset($_REQUEST['getPara'])){
	$getPara=$_REQUEST['getPara'];
}

if($getPara=='getLocation'){
	$sql="select location from location_master ";
}elseif($getPara=='getClient'){
	$sql="select cm.client_id,cm.client_name from client_master cm inner join 
(select count(Distinct process) as tprocess, client_name from new_client_master  where cm_id Not IN(select  cm_id  from client_status_master) group by client_name) ncm on cm.client_id=ncm.client_name order by cm.client_name";
}
elseif($getPara=='getProcess'){
	//$sql="select Distinct process, client_name from new_client_master  where cm_id Not IN(select  cm_id  from client_status_master) group by client_name";
	$sql="select  process, sub_process from new_client_master  where cm_id Not IN(select  cm_id  from client_status_master) ";
	if($clientId!='' && $clientId!=0){
		$sql.= "and client_name='".$clientId."' ";
		}
}
if($sql!=""){
 $myDB=new MysqliDb();
	$result = $myDB->query($sql);
	$myDB=new MysqliDb();
	 $myDB->query("insert into clientinfo_api_access_log set userid='".$_REQUEST['uid']."',password='".$_REQUEST['pwd']."',parameter='".$getPara."'");
	
}else{
			 $myDB=new MysqliDb();
			 $myDB->query("insert into clientinfo_api_access_log set userid='".$_REQUEST['uid']."',password='".$_REQUEST['pwd']."',parameter='".$getPara."'");
} 
echo json_encode($result);
}
else{
	 $myDB=new MysqliDb();
	 $myDB->query("insert into clientinfo_api_access_log set userid='".$_REQUEST['uid']."',password='".$_REQUEST['pwd']."',parameter='".$getPara."'");
	//echo "userid and password does  not match";
	
}		
?>

