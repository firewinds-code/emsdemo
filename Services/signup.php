<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
 if(isset($_REQUEST['ak']) && $_REQUEST['ak']=='ces')
 {

 	$password=$_REQUEST['password'];
 	$empid=$_REQUEST['empid'];
 	$dob=$_REQUEST['dob'];
 	$doj=$_REQUEST['doj'];
 	$sec_qusn=$_REQUEST['seq'];
 	$sec_asn=$_REQUEST['sqa'];
 	
$myDB = new MysqliDb();
$result['status']='';
//echo "select EmployeeID from whole_details_peremp where EmployeeID = '".$empid."' and cast(DOB as date)= '".$dob."' and cast(DOJ as date)= '".$doj."' and secques is null and secans is null";
$p_data = $myDB->query("select EmployeeID from whole_details_peremp where EmployeeID = '".$empid."' and cast(DOB as date)= '".$dob."' and cast(DOJ as date)= '".$doj."' and secques is null and secans is null");
//echo "<br>";
	if(count($p_data) > 0 && $p_data)
	{
			$myDB =  new MysqliDb();
			$password_hash = md5($password);
			$QueryUpdate = "update employee_map set password = '".$password_hash."',secques = '".$sec_qusn."',secans = '".$sec_asn."',password_updated_time=now() where EmployeeID = '".$empid."' and cast(dateofjoin as date)= '".$doj."'" ;
			$res =$myDB->query($QueryUpdate);
			$MysqliError=$myDB->getLastError();
			if ($MysqliError=="") {
				$result['status']=1;
			} else {
			 $result['status']=0;
			} 			
	}
	else
	{
		
		$result['status']=2;
				
	}
}
else
{
	
	$result['status']=3;
			
}
		  		
echo  json_encode($result);

exit;
?>

