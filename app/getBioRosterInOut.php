<?php
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
//$Data=array();
$flag=array();
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);	
ini_set('display_errors', '1');
//var_dump($Data);
//$dataarray=array();
$EmployeeID=$Data['EmployeeID'];
$date=$Data['date'];
$dtype=$Data['dtype'];
$query='';
if($dtype=='bio'){
	 $query="SELECT EmpID,DateOn,CAST(MIN(`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`PunchTime`),MIN(`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime` FROM biopunchcurrentdata where dateOn='".$date."' AND Empid = '".$EmployeeID."' group by Dateon ,EmpID";
}else
if($dtype=='roster'){
	 $query="SELECT InTime as InTime, OutTime as OutTime,DateOn,work_from FROM roster_temp  where DateOn='".$date."' AND EmployeeID = '".$EmployeeID."' ";	
}else
if($dtype=='bioros'){
 	$query="select bio.EmpID,bio.InTime,bio.OutTime,r.InTime as roasterIn, r.OutTime as roasterOut, r.DateON from  roster_temp r Left join 
(SELECT EmpID,DateOn,CAST(MIN(`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`PunchTime`),MIN(`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime` FROM biopunchcurrentdata where DateOn='".$date."' AND Empid = '".$EmployeeID."' group by Dateon ,EmpID) bio on   bio.DateOn=r.DateOn where r.DateOn='".$date."' and r.EmployeeID='".$EmployeeID."' ";	
}
if($query){

	$myDB = new MysqliDb();
	$flag = $myDB->query($query);
	if(count($flag)>0)
	{
		$error = $myDB->getLastError();	
		if($flag[0]['InTime']=='' || $flag[0]['InTime']==NULL)
		{
			$flag['status']=1;
			$flag['msg']='Bio InTime not found ';
		}
		else{
			$flag['status']=1;
			$flag['msg']='Got Data ';	
		}
		if($flag[0]['OutTime']=='' || $flag[0]['OutTime']==NULL)
		{
			$flag['status']=1;
			$flag['msg']='Bio OutTime not found ';
		}else{
			$flag['status']=1;
			$flag['msg']='Got Data ';	
		}
		
	}else{
		$flag['status']=0;
		$flag['msg']='Data not found ';
	}
	
}
else{
		$flag['status']=0;
		$flag['msg']='Data not found ';
	}	
if(count($flag)>0){
	$dataarray=json_encode($flag);
print_r($dataarray);
					
}

?>