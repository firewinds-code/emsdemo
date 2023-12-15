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
 		echo "<script>location.href='https://dashboard.cogentlab.com/dashboard/ems-login?nm=".$_SESSION['__user_Name']."&img=".$img."&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=delivery'</script>";
 		
	exit();
 	}
 	else if($_GET['db']=='dashboard'){
 		//echo "<script>location.href='http://172.105.61.198/web/index.php?r=dashboard/delivery&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=delivery'</script>";
 		echo "<script>location.href='https://analytics.cogentlab.com/web/index.php?r=dashboard/delivery&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=delivery'</script>";
 		
	exit();
 	}else
 	if($_GET['db']=='dashboard2'){
 		//echo "<script> location.href='http://172.105.61.198/web/index.php?r=dashboard2/quality&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=quality'</script>";
 		echo "<script> location.href='https://analytics.cogentlab.com/web/index.php?r=dashboard2/quality&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=quality'</script>";
	exit();
 	}
 	else
 	if($_GET['db']=='quality'){
 		//echo "<script> location.href='http://172.105.61.198/web/index.php?r=/quality/report-designation&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=quality-report-designation'</script>";
 		echo "<script> location.href='https://analytics.cogentlab.com/web/index.php?r=/quality/report-designation&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=quality-report-designation'</script>";
 		
	exit();
 	}else
 	if($_GET['db']=='panindia'){
 		//echo "<script> location.href='http://172.105.61.198/web/index.php?r=dashboard/delivery-aggregation&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=delivery-aggregation'</script>";
 		echo "<script> location.href='https://analytics.cogentlab.com/web/index.php?r=dashboard/delivery-aggregation&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip."&url=delivery-aggregation'</script>";
 		
	exit();
 	}else{
 		echo "parameter not valid";
 	}
}else{
	header("location: index.php");
	exit();
}
?>