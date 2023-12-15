<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
  
if(isset($_REQUEST['saves']) && $_REQUEST['saves']=='saves' &&  $_REQUEST['rygstatus']!="" && $_REQUEST['substatus']!="" &&  $_REQUEST['rygsr']!="" &&  $_REQUEST['empid']!="" &&  $_REQUEST['reporto']!="")
{
	$empid=$_REQUEST['empid'];
	$rygsrid=addslashes($_REQUEST['rygsr']);
	$rygstatus=$_REQUEST['rygstatus'];
	$substatus=$_REQUEST['substatus'];
	 $reporto=$_REQUEST['reporto'];
	$myDB=new MysqliDb();
	$q="select EmployeeID from ryg_reportto where EmployeeID='".$empid."'  and Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE())";
	$result_q =$myDB->query($q);
	if(count($result_q) > 0)
	{
		$sqlUpd="update ryg_reportto set  ryg_status='".$rygstatus."',ryg_substatus='".$substatus."', ryg_remark='".$rygsrid."' where EmployeeID='".$empid."' and Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE())";
		$result=$myDB->query($sqlUpd);
		$mysql_error=$myDB->getLastError();
		if(empty($mysql_error) ){
			echo "updated";
		}else{
			echo "error: ".$mysql_error;
		}
	}
	else
	{
		$sql="INSERT ryg_reportto  set  EmployeeID='".$empid."', reportto_id='".$reporto."', ryg_status='".$rygstatus."',ryg_substatus='".$substatus."', ryg_remark='".$rygsrid."',created_on=now() ";
		$result=$myDB->query($sql);
		$mysql_error=$myDB->getLastError();
		if(empty($mysql_error) ){
			echo "updated";
		}else{
			echo "error: ".$mysql_error;
		}
	}
	
	
	
}else{
	echo "paramere should not be blank";
}
?>

