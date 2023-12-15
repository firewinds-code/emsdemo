<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

//$location= 'http://192.168.204.175/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.$_SESSION['__user_refrance']; 

$ippr="";
function set_server_ip()
{
	if($_SERVER['HTTP_HOST']=="192.168.20.233")
	{
		$ippr="192.168.20.243";
	}

	else if($_SERVER['HTTP_HOST']=="10.85.91.248")
	{
		$ippr="10.85.91.8";
	}
	else if($_SERVER['HTTP_HOST']=="192.168.202.252")
	{
		$ippr="192.168.202.60";
	}
	else if($_SERVER['HTTP_HOST']=="192.168.254.199")
	{
		$ippr="192.168.254.221";
	}
	else
	{
		if($_SESSION["__cm_id"] == '7' || $_SESSION["__cm_id"] == '9' || $_SESSION["__cm_id"] == '10' || $_SESSION["__cm_id"] == '11' || $_SESSION["__cm_id"] == '12' || $_SESSION["__cm_id"] == '13' || $_SESSION["__cm_id"] == '14' || $_SESSION["__cm_id"] == '15' || $_SESSION["__cm_id"] == '16' || $_SESSION["__cm_id"] == '66' || $_SESSION["__cm_id"] == '67' || $_SESSION["__cm_id"] == '68' || $_SESSION["__cm_id"] == '134' || $_SESSION["__cm_id"] == '144' || $_SESSION["__cm_id"] == '172')
		{
			$ippr="192.168.20.243";
		}
		else
		{
			$ippr="192.168.202.60";
		}
			
	}
	return $ippr;
}
function get_client_ip_ref() {
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
if(isset($_SESSION['__user_logid']))
{
	if($_SESSION["__location"] =='1' || $_SESSION["__location"] =='2')
	{
		if($_SERVER['HTTP_HOST']=="151.236.221.245"  || $_SERVER['HTTP_HOST']=="ems.cogentlab.com")
		{
		$myDB=new MysqliDb();
		$select_query="SELECT IP FROM whitelistedip where Location='A100' and IP='".get_client_ip_ref()."' ";
				$resultBy=$myDB->rawQuery($select_query);
				$mysql_error = $myDB->getLastError();
				if($myDB->count > 0)
				{
					$ippr="192.168.128.4";			
				}
				else
				{
					$ippr=set_server_ip();
				}
		}
		else
		{
			$ippr=set_server_ip();
		}	
	}
	
	else
	{
		if($_SESSION["__location"] =='3')
		{
			$ippr = '192.168.204.247';
		}
		else if($_SESSION["__location"] =='4')
		{
			$ippr = '10.31.124.176';
		}
		else if($_SESSION["__location"] =='5')
		{
			$ippr = '10.25.50.110';
		}
		else if($_SESSION["__location"] =='6')
		{
			$ippr = '172.16.201.11';
		}
		else if($_SESSION["__location"] =='7')
		{
			//$ippr = '192.168.10.11';
			$ippr = 'cogentems.com:8082';
		}
		/*else if($_SESSION["__location"] =='8')
		{
			$ippr = '192.168.10.11';
		}*/
	}
	
$location= 'http://' . $ippr .'/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.urlencode($_SESSION['__user_refrance']); 

//$location= 'http://192.168.202.60/qms/admin/login.php?logid='.$_SESSION['__user_logid'].'&refrance='.$_SESSION['__user_refrance']; 
echo "<script>location.href='".$location."'</script>";
die();
}
else
{
	$location= URL.'Login'; 
echo "<script>location.href='".$location."'</script>";
	die();
}
?>