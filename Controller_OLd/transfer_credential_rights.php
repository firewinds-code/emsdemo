<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
//error_reporting(E_ALL); ini_set('display_errors', '1');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_POST['btn_transfer'])) {


	$txt_usrId = cleanUserInput($_POST['txt_usrId']);

	if ($txt_usrId === 'CE03070003' || $txt_usrId === 'CE07147134') {
		$location = URL . 'View/index';
		header("location:$location");
		exit();
		die();
	} else {


		$sender_id = clean($_SESSION["__user_logid"]);
		/*$password_hash = md5($_POST['txt_usr_pwd']);*/
		$sqlConnect = 'call check_login_partial("' . $txt_usrId . '")';

		$myDB = new MysqliDb();
		$result = $myDB->query($sqlConnect);
		$error = $myDB->getLastError();
		$cookie_name = "reload";
		setcookie($cookie_name, 'hidden', time() + (86400 * 30), "/"); // 86400 = 1 day

		function get_client_ip_ref()
		{
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
			else if (getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if (getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if (getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if (getenv('HTTP_FORWARDED'))
				$ipaddress = getenv('HTTP_FORWARDED');
			else if (getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}

		$clientip = get_client_ip_ref();
		$clientip1 = filter_var($clientip, FILTER_VALIDATE_IP);

		if (count($result) > 0 && $result) {
			foreach ($result as $key => $value) {

				$_SESSION["__user_logid"] = $value['EmployeeID'];

				if ($value['emp_level'] == "SITEADMIN") {
					$_SESSION["__user_type"] = 'ADMINISTRATOR';
					$_SESSION["__ut_temp_check"] = $value['emp_level'];
				} else if ($value['emp_level'] == "COMPLIANCE") {
					$_SESSION["__user_type"] = 'CENTRAL MIS';
					$_SESSION["__ut_temp_check"] = $value['emp_level'];
				} else {
					$_SESSION["__user_type"] = $value['emp_level'];
					$_SESSION["__ut_temp_check"] = $value['emp_level'];
				}

				$myDB = new MysqliDb();
				// $data_emppwd = $myDB->query("select password from employee_map where EmployeeID ='" . $value['EmployeeID'] . "'");
				$emID = $value['EmployeeID'];
				$data_emppwdQry = "select password from employee_map where EmployeeID =?";
				$stmt = $conn->prepare($data_emppwdQry);
				$stmt->bind_param("s", $emID);
				$stmt->execute();
				$data_emppwd = $stmt->get_result();
				$data_emppwds = $data_emppwd->fetch_row();
				$_SESSION["__user_Name"] = $value['EmployeeName'];
				$_SESSION["__user_Dept"] = $value['dept_name'];
				$_SESSION["__user_Dept_ID"] = $value['dept_id'];
				$_SESSION["__user_client_ID"] = $value['client_id'];
				$_SESSION["__user_process"] = $value['process'];
				$_SESSION["__user_subprocess"] = $value['sub_process'];
				$_SESSION["__user_status"] = $value['Status'];
				$_SESSION["__user_Desg"] = $value['Designation'];
				$_SESSION["__user_Function"] = $value['function'];
				$_SESSION["__status_ah"] = $value['AH'];
				$_SESSION["__status_qh"] = $value['QH'];
				$_SESSION["__status_oh"] = $value['OH'];
				$_SESSION["__status_th"] = $value['TH'];
				$_SESSION["__status_tr"] = $value['TR'];
				$_SESSION["__status_qa"] = $value['QA'];
				$_SESSION["__user_profile"] = $value['img'];
				$_SESSION["__user_Comp"] = 'Cogent E Services Ltd.'; //$value['user_login']['CompanyName'];
				$_SESSION["__user_refrance"] =  $data_emppwds[0];
				if (empty($value['ReportTo'])) {
					$_SESSION['MsgLg'] = 'You are not mapped with any Supervisor:<br/><b> LogIn Failed</p>';
					//echo 'You are not mapped with any Supervisor:<br/><b> LogIn Failed</p>';
					echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
					exit();
				}
				$myDB = new MysqliDb();
				$data_to_insert = array("EmployeeID" => $value['EmployeeID'], "IP" => $clientip1, "createdby" => $sender_id);
				$flag_ltrf = $myDB->insert("login_trf_entry", $data_to_insert);
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
			if (isset($_POST['logchek'])) {
				$cookie_name = "usrnm";
				$cookie_value = clean($_SESSION["__user_logid"]);
				setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
			}
			require(ROOT_PATH . 'Controller/log_create.php');
			$Action = new PHPLog_Action(clean($_SESSION['__user_logid']), "Login TO (WITH PROXY)", clean($_SESSION["__user_Name"]) . " Log In To EMS");

			$location = URL . 'View/index.php';
			//$myDB  = new mysql();
			//$rst_check = $myDB->query("select dateofjoin from employee_map where EmployeeID = '".$_POST['txt_usrId']."' ");
			//$doj = $rst_check[0]['employee_map']['dateofjoin'];
			/*if(strtotime($rst_check[0]['employee_map']['dateofjoin']) < strtotime("2016-08-01") && $_SESSION['__user_logid'] != 'CE08070107')
			{
				
				$myDB=new mysql();	
				$result1=$myDB->query('select EmployeeID from updated_personal_details where EmployeeID ="'.$_POST['txt_usrId'].'" limit 1');
				if($result1)
				{
					//echo $result1;				
					if(count($result1) > 0)
					{
						
					}
					else
					{
						$_SESSION['MsgLg']='Hi '.$_SESSION["__user_Name"].', Kindly fill <br /> <b>Employee Registration Form</b> to Login EMS :';
						//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
						echo "<script type='text/javascript'>location.href = '".URL."LogIn';</script>";
						exit();
					}
					
				}
				else
				{
					$_SESSION['MsgLg']='Hi '.$_SESSION["__user_Name"].', Kindly fill <br /><b>Employee Registration Form</b> to Login EMS :';
					//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
					echo "<script type='text/javascript'>location.href = '".URL."LogIn';</script>";
					exit();
				}
			}*/

			$myDB = new MysqliDb();

			// $rst_login = $myDB->query('INSERT INTO login_history(EmployeeID,IP) VALUES("' . $_SESSION['__user_logid'] . '","' . get_client_ip_ref() . '")');
			$user_logid = clean($_SESSION['__user_logid']);
			$rst_loginQry = 'INSERT INTO login_history(EmployeeID,IP) VALUES(?,?)';
			$stmt = $conn->prepare($rst_loginQry);
			$stmt->bind_param("ss", $user_logid, $clientip1);
			$stmt->execute();
			$rst_login = $stmt->get_result();
			header("location:$location");
		} else {
			$_SESSION['MsgLg'] = 'Your UserID or Password is incorrect ,Try again:<br/> <b>LogIn Failed</b>';
			//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
			echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
		}
	}
}
