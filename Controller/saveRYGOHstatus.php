<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
  
if(isset($_REQUEST['saves']) && $_REQUEST['saves']=='saves' &&  $_REQUEST['rygstatus']!="" && $_REQUEST['substatus']!="" &&  $_REQUEST['rygsr']!="" &&  $_REQUEST['empid']!="" &&  $_REQUEST['oh']!="")
{
	$empid=$_REQUEST['empid'];
	$rygsrid=addslashes($_REQUEST['rygsr']);
	$rygstatus=$_REQUEST['rygstatus'];
	$substatus=$_REQUEST['substatus'];
	$oh=$_REQUEST['oh'];
	$myDB=new MysqliDb();
	$q="select EmployeeID from ryg_oh where EmployeeID='".$empid."'  and Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE())";
	$result_q =$myDB->query($q);
	if(count($result_q) > 0)
	{
		 $sqlUpd="update ryg_oh set  ryg_status='".$rygstatus."',ryg_substatus='".$substatus."', ryg_remark='".$rygsrid."' where EmployeeID='".$empid."'and Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE())";
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
		 $sql="INSERT ryg_oh  set  EmployeeID='".$empid."', oh_id='".$oh."', ryg_status='".$rygstatus."',ryg_substatus='".$substatus."',ryg_remark='".$rygsrid."',created_on=now()";
		$result=$myDB->query($sql);
		$mysql_error=$myDB->getLastError();
		if(empty($mysql_error) ){
			echo "Inserted ";
		}else{
			echo "error: ".$mysql_error;
		}
	}
	
	
	
}
?>

