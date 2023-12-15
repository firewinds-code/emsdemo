<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
$sql="select id,EmployeeID,HRRemark1,HRRemark2,HRRemark3,HRRemark4,HRRemark5,HRRemark6,HRRemark7,HRRemark8,HRRemark9,HRRemark10 from apprisalremarks where MasterId='".$_REQUEST['ID']."' ";
$myDB=new MysqliDb();
$result=$myDB->query($sql);
$mysql_error=$myDB->getLastError();
if(count($result) > 0 && $result)
{
    foreach($result as $key=>$value)
    {
		foreach($value as $k => $Score)
		{
			echo $Score.'|$|';
		}
    }	
}
else
{
	echo "<script>$(function(){ toastr.error('Applicant Score does not exist.') }); </script>";
}
?>