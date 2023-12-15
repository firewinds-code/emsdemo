<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
 if(isset($_SESSION['__user_logid']) && $_SESSION['__user_logid']!=""){ 
 	function get_client_ip_ref() 
	{
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}
	$ip=get_client_ip_ref();
 	$EmployeeID=$_SESSION['__user_logid'];
 	$Query="Select EmployeeID,token from login_history where EmployeeID='".$EmployeeID."'  order by ID desc limit 1";
		//and token='".$token."'
		$rtoken='';
		$myDB=new MysqliDb();
		$response =$myDB->query($Query);
		$mysql_error=$myDB->getLastError();
		if($mysql_error=="" and count($response)>0)
		{
			$rtoken=$response[0]['token'];
		}
		if($_GET['db']=='ovse'){  
 		$img="https://ems.cogentlab.com/erpm/".$_GET['img'];
 		echo "<script>location.href='https://portal.cogentlab.com/revenue/ems-login/?nm=".$_SESSION['__user_Name']."&img=".$img."&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=revenue'</script>";
 		
	exit();
 	}
 	else{
 		echo "parameter not valid";
 	}
}else{
	header("location: index.php");
	exit();
}
