<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
//require_once(CLS.'MysqliDb.php');
if(isset($_POST['btn_login'])){

	$sqlConnect = 'call temp_user_login("'.$_POST['txt_usrId'].'","'.$_POST['txt_usr_pwd'].'")';
	$myDB=new MysqliDb();
	//$myDB = new MysqliDb ();
	$result=$myDB->query($sqlConnect);
	$error=$myDB->getLastError();
	$cookie_name = "reload";
	setcookie($cookie_name, 'hidden', time() + (86400 * 30), "/"); // 86400 = 1 day
				
	if($result){
		foreach($result as $key=>$value)
		{
			$_SESSION["__user_logid"] = $value['EmployeeID'];
            $_SESSION["__user_type"] = $value['emp_level'];
            $_SESSION["__user_Name"] = $value['EmployeeName'];
            $_SESSION["__user_Dept"] = 'NA';
            $_SESSION["__user_Dept_ID"] = 'NA';
            $_SESSION["__user_client_ID"] =  'NA';
            $_SESSION["__user_process"] =  'NA';
            $_SESSION["__user_subprocess"] =  'NA';
            
            $_SESSION["__user_Desg"] =  'NA';
            $_SESSION["__user_Function"] =  'NA';
            $_SESSION["__status_ah"] =  'NA';
            $_SESSION["__status_qh"] =  'NA';
            $_SESSION["__status_oh"] =  'NA';
            $_SESSION["__status_th"] =  'NA';
            $_SESSION["__status_tr"] =  'NA';
            $_SESSION["__status_qa"] =  'NA';
            $_SESSION["__user_profile"] =  'NA';
            $_SESSION["__user_Comp"] = 'Cogent E Services Ltd.';//$value['user_login']['CompanyName'];
            /*if(empty($value['t2']['ReportTo']))
            {
            	$_SESSION['MsgLg']='You are not mapped with any Supervisor:<br/><b> LogIn Failed</p>';
				//echo 'You are not mapped with any Supervisor:<br/><b> LogIn Failed</p>';
				echo "<script type='text/javascript'>location.href = '".URL."LogIn';</script>";
				exit();
			}*/
           /* $_SESSION["__user_logid"] = $value['EmployeeID'];
            $_SESSION["__user_type"] = $value['emp_level'];
            $_SESSION["__user_Name"] = $value['EmployeeName'];
            $_SESSION["__user_Dept"] = $value['dept_name'];
            $_SESSION["__user_Dept_ID"] = $value['dept_id'];
            $_SESSION["__user_Desg"] = $value['designation'];
            $_SESSION["__status_ah"] = $value['account_head'];
            $_SESSION["__status_qh"] = $value['QH'];
            $_SESSION["__status_oh"] = $value['OH'];
            $_SESSION["__status_th"] = $value['TH'];
            $_SESSION["__user_Comp"] = 'Cogent E Services Ltd.';//$value['user_login']['CompanyName'];*/
		}
		if(isset($_POST['logchek']))
		{
			$cookie_name = "usrnm";
			$cookie_value = $_SESSION["__user_logid"];
			setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
		}
		require(ROOT_PATH.'Controller/log_create.php');
		$Action=new PHPLog_Action($_SESSION['__user_logid'],"Login TO", $_SESSION["__user_Name"]." Log In To EMS");

		$location=URL.'View/emp_updataionForm.php';
		$myDB = new MysqliDb();
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
		$rst_login = $myDB->query('INSERT INTO login_history(EmployeeID,IP) VALUES("'.$_SESSION['__user_logid'].'","'.get_client_ip_ref().'")');
		
		header("location:$location");
	}
	else{
		$_SESSION['MsgLg']='Your UserID or Password is incorrect ,Try again:<br/> <b>LogIn Failed</b>';
		//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
		echo "<script type='text/javascript'>location.href = '".URL."View/temp_login.php';</script>";

	}

}


?>
