<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
$sql="select id,EmployeeID,ApplicantRemark1,ApplicantRemark2,ApplicantRemark3,ApplicantRemark4,ApplicantRemark5,ApplicantRemark6,ApplicantRemark7,ApplicantRemark8,ApplicantRemark9,ApplicantRemark10 from apprisalremarks where MasterId='".$_REQUEST['ID']."' ";
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