<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
 if(isset($Data['appkey']) && $Data['appkey']=="ces" && isset($Data['empid']) && $Data['empid']!="" )
 {
    $empid=$Data['empid'];
 	$password=$Data['password'];
 	$dob=$Data['dob'];
 	$doj=$Data['doj'];
 	$sec_qusn=$Data['seq'];
 	$sec_asn=$Data['sqa'];
////insert ack
		    $myDB =  new MysqliDb();
			$QueryInsert = "insert into  signup_policy_ack (EmployeeID) values('".$empid."');" ;
			$res =$myDB->query($QueryInsert);
			$MysqliError=$myDB->getLastError();
			if (empty($MysqliError)) 
			{
				$myDB = new MysqliDb();
				$result['status']='';
				$p_data = $myDB->query("select EmployeeID from whole_details_peremp where EmployeeID = '".$empid."' and cast(DOB as date)= '".$dob."' and cast(DOJ as date)= '".$doj."' and secques is null and secans is null");
					if(count($p_data) > 0 && $p_data)
					{
							$myDB =  new MysqliDb();
							$password_hash = md5($password);
							$QueryUpdate = "update employee_map set password = '".$password_hash."',secques = '".$sec_qusn."',secans = '".$sec_asn."',password_updated_time=now() where EmployeeID = '".$empid."' and cast(dateofjoin as date)= '".$doj."'" ;
							$res =$myDB->query($QueryUpdate);
							$MysqliError=$myDB->getLastError();
							if ($MysqliError=="") {
								$result['status']=1;
								$result['msg']="Sign - Up successful.";
							} else {
							 $result['status']=0;
							 $result['msg']="Unable to sign up , Please try again later.";
							} 			
					}
					else
					{
						
						$result['status']=0;
						 $result['msg']="Wrong information OR you have already enrolled.";
								
					}
			} 
			else
			{
			    $result['status']=0;
				$result['msg']="Unable to sign up , Please try again later.";
			} 	
 //
 
 
 
 

}
else
{
	$result['status']=0;
	$result['msg']="Invalid request";		
}
		  		
echo  json_encode($result);
?>

