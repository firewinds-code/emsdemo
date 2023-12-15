<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
if(isset($_REQUEST['bid'],$_REQUEST['empid'])){
	$fromDate="";
	$select_newquery="";
	$fromDate=	date('Y-m-d',strtotime($_REQUEST['fromdate']));
	if($_REQUEST['estatus']=="" && $fromDate!="" ){
	$date=date('Y-m-d');
		if($fromDate<=$date){
			$cm_id=$_REQUEST['cm_id'];
			$query_Add_update="call brf_MiniWholeEmployee('".$_REQUEST['bid']."','".$cm_id."','".$_REQUEST['fromdate']."','".$_REQUEST['view_for']."')";
			$myDB=new MysqliDb();
			$resultBy=$myDB->rawQuery($query_Add_update);
			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;

		}
	}

	if($_REQUEST['empid']!="" && $_REQUEST['bid']!=""){
		$myDB=new MysqliDb();
		$select=$myDB->rawQuery("Select id from brf_acknowledge where BriefingId='".$_REQUEST['bid']."' and EmployeeID='".$_REQUEST['empid']."' ");
		$mysql_error = $myDB->getLastError();
		$rowCount = $myDB->count;
		if($rowCount<1){
			$sql="insert into brf_acknowledge set BriefingId='".$_REQUEST['bid']."',EmployeeID='".$_REQUEST['empid']."' ";
			$myDB=new MysqliDb();
			$result=$myDB->rawQuery($sql);
			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;
			if($rowCount>0){
				echo 'yes';
			}
		}else{
			echo 'yes';
		}
		
	}
}

?>
