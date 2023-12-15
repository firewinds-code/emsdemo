<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
// require_once(CLS1 . 'MysqliDb_replica1.php');
// require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
// require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
include_once(__dir__ . '/../Services/sendsms_API1.php');
$Body = '';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
// function get_client_ip_ref()
// {
// 	$ipaddress = '';
// 	if (getenv('HTTP_CLIENT_IP'))
// 		$ipaddress = getenv('HTTP_CLIENT_IP');
// 	else if (getenv('HTTP_X_FORWARDED_FOR'))
// 		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
// 	else if (getenv('HTTP_X_FORWARDED'))
// 		$ipaddress = getenv('HTTP_X_FORWARDED');
// 	else if (getenv('HTTP_FORWARDED_FOR'))
// 		$ipaddress = getenv('HTTP_FORWARDED_FOR');
// 	else if (getenv('HTTP_FORWARDED'))
// 		$ipaddress = getenv('HTTP_FORWARDED');
// 	else if (getenv('REMOTE_ADDR'))
// 		$ipaddress = getenv('REMOTE_ADDR');
// 	else
// 		$ipaddress = 'UNKNOWN';
// 	return $ipaddress;
// }

// function clean($data)
// {
// 	$data = htmlspecialchars($data);
// 	$data = stripslashes($data);
// 	$data = trim($data);
// 	return $data;
// }
if (isset($_POST['btn_login'])) {

	if (trim($_POST['txt_usrId']) == "CE03070003" || trim($_POST['txt_usrId']) == "CE10091236") {
		//Do nothing
	} else {

		$myDB = new MysqliDb();
		$ip_list = $myDB->rawQuery("select distinct IP from whitelistedip where IP = '" . get_client_ip_ref() . "' order by id desc limit 1;");
		if (count($ip_list) > 0 && $ip_list) {
			//do nothing;
		} else {
			$location = URL . 'Error1.php';
			//echo "<script>location.href='".$location."'</script>";	
			header("Location: $location");
			exit();
		}
	}
	$usrid = cleanUserInput(trim($_POST['txt_usrId']));
	$pwd = cleanUserInput(trim($_POST['txt_usr_pwd']));
	//$pwd = $_POST['txt_usr_pwd'];
	$myDB = new MysqliDb();
	$Response = $myDB->query("select userid,username,clientid from login_demo where userid='" . $usrid . "' and pwd= '" . $pwd . "'");
	if (count($Response) > 0) {
		$sql = 'call briefing_login("' . $Response[0]['clientid']  . '")';
		//echo $sql;
		$result = $myDB->rawQuery($sql);
		// echo count($result);
		// die;
		//$mysql_error = $myDB->getLastError();
		if (count($result) > 0) {

			$_SESSION["__login_type"] = "Briefing";
			$_SESSION["__user_logid"] = $Response[0]['userid'];
			// echo $_SESSION["__user_logid"];
			// die;
			$_SESSION["__user_Name"] = $Response[0]['username'];
			$_SESSION["__user_Dept"] = $result[0]['dept_name'];
			$_SESSION["__user_Dept_ID"] = "";
			$_SESSION["__user_client_name"] = $result[0]['clientname'];
			$_SESSION["__user_client_ID"] = $result[0]['client_name'];
			$_SESSION["__user_process"] = $result[0]['Process'];
			$_SESSION["__user_subprocess"] = $result[0]['sub_process'];
			$_SESSION["__user_status"] = $result[0]['status'];
			$_SESSION["__user_Desg"] = $result[0]['designation'];
			$_SESSION["__user_Function"] = "";
			$_SESSION["__status_ah"] = $result[0]['AH'];
			$_SESSION["__alert"] = "";
			$_SESSION["__announce"] = "";
			$_SESSION["__chatmsg"] = "";
			$_SESSION["__status_vh"] = "";
			$_SESSION["__status_qh"] = $result[0]['QH'];
			$_SESSION["__status_oh"] = $result[0]['OH'];
			$_SESSION["__status_th"] = $result[0]['TH'];
			$_SESSION["__status_tr"] = "";
			$_SESSION["__status_qa"] = "";
			$_SESSION["__AprRecomender"] = "";
			$_SESSION["__cm_id"] = $result[0]['cm_id'];
			$_SESSION["__user_profile"] = "";
			$_SESSION["__user_Comp"] = 'Cogent E Services Ltd.'; //$value['user_login']['CompanyName'];
			$_SESSION["__user_refrance"] = $_POST['txt_usr_pwd'];
			$_SESSION["__DOJ"] = $result[0]['DOJ'];
			$_SESSION["__OnFloor"] = "";
			$_SESSION["__password_utime"] = "";
			$_SESSION["__location"] = $result[0]['location'];
			$_SESSION["__status_rt"] = "";
			$_SESSION["__gender"] = strtoupper($result[0]['Gender']);
			$_SESSION["__status_er"] = "";
			$_SESSION["salEmp"] = "No";
			$_SESSION["vrdate"] = "";
			$_SESSION["__user_type"] = "";
			$_SESSION["__ut_temp_check"] = "";
			$_SESSION["__ReportTo"] = "";
			$_SESSION["__ReportToName"] = "";
			$_SESSION['empid'] = "";
			$_SESSION["__df_id"] = "";

			$_SESSION["__IsApprover"] = "No";
			$_SESSION['reviewer'] = "No";
			$_SESSION['approver'] = "No";
			$_SESSION["__Appraisal"] = "No";
			$_SESSION["__Asset_Approval"] = "No";
			$_SESSION["__AppraisalMonth"] = "NA";
			require(ROOT_PATH . 'Controller/log_create.php');
			$Action = new PHPLog_Action($_SESSION['__user_logid'], "Login TO", $_SESSION["__user_Name"] . " Log In To EMS");

			$location = URL . 'View/index.php';

			header("Location: $location");
		} else {
			$_SESSION['MsgLg'] = 'testInvalid Credentials';
			//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
			echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
		}
	} else {
		$password_hash = md5($_POST['txt_usr_pwd']);
		//$password_hash = $_POST['txt_usr_pwd'];
		$sql = 'call check_login("' . $_POST['txt_usrId'] . '","' . $password_hash . '")';

		//echo $sql;
		$myDB = new MysqliDb();
		$result = $myDB->rawQuery($sql);
		$mysql_error = $myDB->getLastError();
		//echo $sql;
		//die;



		$rst_contact = array();
		$myDB  = new MysqliDb();
		$rst_check = $myDB->query("SELECT created_at,verify_date,aadhar_status FROM ems.aadhar_verifiaction where EmployeeID = '" . $_POST['txt_usrId'] . "' ");
		$myDB  = new MysqliDb();
		if (count($result) > 0 && isset($result[0]['cm_id']) && $result[0]['cm_id'] != "") {
			$rst_contact = $myDB->query("select t1.mobile,t2.EmployeeName as ahname from contact_details t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID where t1.EmployeeID=(select account_head from new_client_master where cm_id='" . $result[0]['cm_id'] . "') ");
		}

		if (count($rst_check) > 0) {
			$cudate = date('Y-m-d');

			if (isset($rst_check[0]['aadhar_status']) &&  $rst_check[0]['aadhar_status'] == 'pending') {
				//var_dump($rst_check);
				$start = strtotime($rst_check[0]['created_at']);
				$end = strtotime($cudate);
				$reamainingDate = ceil(abs($end - $start) / 86400);
				//$reamainingDate = '1';
				$_SESSION["vrdate"] = "";
				if (date('Y-m-d', strtotime($rst_check[0]['created_at'])) >= "2021-05-15" && $reamainingDate > '30') {
					$ahname = '';
					$ahcontact = '';
					if (count($rst_contact) > 0) {
						$ahname = $rst_contact[0]['ahname'];
						$ahcontact = $rst_contact[0]['mobile'];
					}
					/* $_SESSION['MsgLg']='Your Aadhaar card is not verified, please contact your process account head '.$ahname.' on '.$ahcontact.'';
						$url=URL.'LogIn';
						echo "<script>location.href='".$url."'</script>";
						exit;*/
				} elseif (date('Y-m-d', strtotime($rst_check[0]['created_at'])) >= "2021-05-15" && $reamainingDate <= '30') {
					//$day_remaning=
					$_SESSION["vrdate"] = $reamainingDate;
				}
			}
		}


		$cookie_name = "reload";
		setcookie($cookie_name, 'hidden', time() + (86400 * 30), "/"); // 86400 = 1 day		
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


				$_SESSION["__login_type"] = "EMS";
				$_SESSION["__user_Name"] = $value['EmployeeName'];
				$_SESSION["__user_Dept"] = $value['dept_name'];
				$_SESSION["__user_Dept_ID"] = $value['dept_id'];
				$_SESSION["__user_client_name"] = $value['client_name'];
				$_SESSION["__user_client_ID"] = $value['client_id'];
				$_SESSION["__user_process"] = $value['process'];
				$_SESSION["__user_subprocess"] = $value['sub_process'];
				$_SESSION["__user_status"] = $value['Status'];
				$_SESSION["__user_Desg"] = $value['Designation'];
				$_SESSION["__user_Function"] = $value['function'];
				$_SESSION["__status_ah"] = $value['AH'];
				$_SESSION["__alert"] = "";
				$_SESSION["__announce"] = "";
				$_SESSION["__chatmsg"] = "";
				$_SESSION["__status_vh"] = $value['VH'];
				$_SESSION["__status_qh"] = $value['QH'];
				$_SESSION["__status_oh"] = $value['OH'];
				$_SESSION["__status_th"] = $value['TH'];
				$_SESSION["__status_tr"] = $value['TR'];
				$_SESSION["__status_qa"] = $value['QA'];
				$_SESSION["__AprRecomender"] = $value['AprRecomender'];
				$_SESSION["__cm_id"] = $value['cm_id'];
				$_SESSION["__user_profile"] = $value['img'];
				$_SESSION["__user_Comp"] = 'Cogent E Services Ltd.'; //$value['user_login']['CompanyName'];
				$_SESSION["__user_refrance"] = $_POST['txt_usr_pwd'];
				$_SESSION["__DOJ"] = $value['dateofjoin'];
				$_SESSION["__OnFloor"] = $value['OnFloor'];
				$_SESSION["__password_utime"] = $value['password_updated_time'];
				$_SESSION["__location"] = $value['location'];
				$_SESSION["__status_rt"] = $value['rt'];
				$_SESSION["__gender"] = strtoupper($value['gender']);
				$_SESSION["__status_er"] = $value['ER'];
				$_SESSION["__df_id"] = $value['df_id'];
				$_SESSION["salEmp"] = "No";
				if (empty($value['ReportTo'])) {
					$_SESSION['MsgLg'] = 'Invalid Mapping';
					//echo 'You are not mapped with any Supervisor:<br/><b> LogIn Failed</p>';
					echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
					exit();
				} else {
					$_SESSION["__ReportTo"] = $value['ReportTo'];
					$_SESSION["__ReportToName"] = $value['Reportto_Name'];
				}

				$_SESSION['empid'] = "No";
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

			$myDB  = new MysqliDb();
			$empidSelect = $myDB->query("select id from desimination_matrix where empid like '%" . $_SESSION["__user_logid"] . "%' ");
			// echo "select * from desimination_matrix where empid like '%" . $_SESSION["__user_logid"] . "%' ";
			// die;
			// print_r(count($empidSelect));
			// die;
			if (count($empidSelect) == 1 && $empidSelect[0]['id'] != "") {

				$_SESSION['empid'] = "Yes";
			} else {
				$_SESSION['empid'] = "No";
			}


			if (isset($_POST['logchek'])) {
				$cookie_name = "usrnm";
				$cookie_value = $_SESSION["__user_logid"];
				setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
			}
			$_SESSION["__IsApprover"] = "No";
			$sql = 'select t1.id from module_master_new t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID where emp_status="Active" and (l1empid="' . $_POST['txt_usrId'] . '" or l2empid="' . $_POST['txt_usrId'] . '") limit 1';
			//echo $sql;
			$myDB = new MysqliDb();
			$result = $myDB->rawQuery($sql);
			if (count($result) > 0 && $result) {
				if (isset($result[0]['id']) && $result[0]['id'] != "") {
					$_SESSION["__IsApprover"] = "Yes";
				}
			}

			$myDB = new MysqliDb();
			$query = "SELECT id,EmpID from salary_master  where EmpID='" . $_SESSION['__user_logid'] . "'";
			$saldata = $myDB->query($query);
			if ($saldata > 0 && $saldata[0]['id'] != '') {
				$_SESSION["salEmp"] = "Yes";
			} else {
				$_SESSION["salEmp"] = "No";
			}

			$_SESSION['reviewer'] = "No";
			$_SESSION['approver'] = "No";

			$myDB = new MysqliDb();
			$reviewApprove = "select id,EmployeeID,is_reviewer,is_approver from expense_matrix where EmployeeID='" . $_SESSION['__user_logid'] . "'";
			$reviewResult = $myDB->rawQuery($reviewApprove);
			// echo "<pre>";
			// print_r($reviewResult);
			// die;
			if (count($reviewResult) > 0 && $reviewResult[0]['id'] != '') {
				if ($reviewResult[0]['is_reviewer'] == "Yes" || $reviewResult[0]['is_reviewer'] == "Yes" && $reviewResult[0]['is_approver'] == "Yes") {
					$_SESSION['reviewer'] = "Yes";
				} else {
					$_SESSION['reviewer'] = "No";
				}
				if ($reviewResult[0]['is_approver'] == "Yes" || $reviewResult[0]['is_reviewer'] == "Yes" && $reviewResult[0]['is_approver'] == "Yes") {
					$_SESSION['approver'] = "Yes";
				} else {
					$_SESSION['approver'] = "No";
				}
			} else {
				$_SESSION['reviewer'] = "No";
				$_SESSION['approver'] = "No";
			}

			$query = "SELECT Approver_id from training_master  where Approver_id='" . $_SESSION['__user_logid'] . "'";
			$saldata = $myDB->query($query);
			if ($saldata > 0 && $saldata[0]['Approver_id'] != '') {
				$_SESSION["training_approver"] = "Yes";
			} else {
				$_SESSION["training_approver"] = "No";
			}

			$dayofweek = date("w", strtotime(date('Y-m-d')));
			if ($dayofweek == 0) {
				$currentMondayDate = date('Y-m-d', strtotime('monday last week'));
			} else {
				$currentMondayDate = date('Y-m-d', strtotime('monday this week'));
			}

			$_SESSION['__covid_weekly'] = 'No';
			$_SESSION['__isms_decl'] = 'No';
			$_SESSION['__nda_decl'] = 'No';
			$_SESSION['__asset_decl'] = 'No';
			$_SESSION["__Asset_Approval"] = 'No';

			if ($_SESSION["__login_type"] != "Briefing") {
				$covidQuery = "SELECT createdOn FROM `ack_covid_weekly_form` where EmployeeID = '" . $_SESSION['__user_logid'] . "' and cast(createdOn as date) between '" . $currentMondayDate . "' and cast(NOW() as date)  ";

				// echo "<br>";
				$rscovidack = $myDB->query($covidQuery);
				count($rscovidack);

				if (count($rscovidack) < 1) {				//echo 'there';
					$_SESSION['__covid_weekly'] = 'Yes';
				}
			}

			$sqlquery = "SELECT createdon FROM isms_policies_decl where EmployeeID = ? and cast(createdOn as date) between '" . $currentMondayDate . "' and cast(NOW() as date)";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $_SESSION['__user_logid']);
			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();
			$count = $result->num_rows;
			if ($count == 0) {
				$_SESSION['__isms_decl'] = 'Yes';
			}

			$sqlquery = "select id from nda_policies_decl where employeeID= ?";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $_SESSION['__user_logid']);
			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();
			$count = $result->num_rows;
			if ($count == 0) {
				$_SESSION['__nda_decl'] = 'Yes';
			}

			$sqlquery = "select id from asset_employee where EmpID= ? and Ack_flag=0";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $_SESSION['__user_logid']);
			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();
			$count = $result->num_rows;
			if ($count != 0) {
				$_SESSION['__asset_decl'] = 'Yes';
			}

			if ($_SESSION['__user_logid'] == "CE111513513" || $_SESSION['__user_logid'] == "CE03070003") {
				$_SESSION["__Asset_Approval"] = "Yes";
			} else {
				$sqlquery = "select id from new_client_master_spoc where AdminID=? or ITID=? union select id from employee_map where cm_id in (select cm_id from new_client_master t1  where cm_id not in (select cm_id from client_status_master) and oh=?) and emp_status='Active' and df_id in (74,77) limit 1";
				$stmt = $conn->prepare($sqlquery);
				$stmt->bind_param("sss", $_SESSION['__user_logid'], $_SESSION['__user_logid'], $_SESSION['__user_logid']);
				$stmt->execute();
				$result = $stmt->get_result();
				//$resultraw = $result->fetch_row();
				$count = $result->num_rows;
				if ($count != 0) {
					$_SESSION["__Asset_Approval"] = 'Yes';
				}
			}

			require(ROOT_PATH . 'Controller/log_create.php');
			$Action = new PHPLog_Action($_SESSION['__user_logid'], "Login TO", $_SESSION["__user_Name"] . " Log In To EMS");

			$location = URL . 'View/index.php';


			/*$DateFromDOJ=date('d',strtotime($_SESSION["__DOJ"]));
			if($DateFromDOJ >= 15)
			{
				$UpdateMonth = strtotime("+1 months", strtotime($_SESSION["__DOJ"]));
				$UpdateMonth= date('Y-m-01',$UpdateMonth);
			}
			else
			{
				$UpdateMonth=$_SESSION["__DOJ"];
			}*/

			//// Appraisal Session Start //////////////////////

			//$_SESSION["__Appraisal"] = "No";

			if ($value['df_id'] == '74' || $value['df_id'] == '77' || $value['df_id'] == '146' || $value['df_id'] == '147' || $value['df_id'] == '148' || $value['df_id'] == '149') {
				$_SESSION["__Appraisal"] = "No";
			} else {
				$datediff = time() - strtotime($value['dateofjoin']);
				$datediff =  round($datediff / (60 * 60 * 24));

				if ($datediff < 350) {
					$_SESSION["__Appraisal"] = "No";
				} else {
					$datediff = time() - strtotime($value['dateofjoin']);
					$datediff =  round($datediff / (60 * 60 * 24));
					if ($datediff < 350) {
						$_SESSION["__Appraisal"] = "No";
					} else {
						$_SESSION["__AppraisalMonth"] = $value['AppraisalMonth'];
						if ($value['AppraisalMonth'] != 'NA') {

							$curdate = date("Y-m-d");

							$date = date_parse($value['AppraisalMonth']);

							//echo "<br>";
							$new_effectiveDate = date('Y') . '-' . $date['month'] . '-' . '15';

							//echo "<br>";
							$prev_month_ts = strtotime($new_effectiveDate .  '-1 month');
							// echo "<br>";
							$prev_month = date('Y-m-d', $prev_month_ts);
							//echo "<br>";
							$next_month_ts = strtotime($new_effectiveDate .  '+1 month');
							//echo $next_month_ts = date('Y-m-d', $next_month_ts);
							$next_month_ts = date('Y', $next_month_ts) . '-' . date('m', $next_month_ts) . '-' . '05';

							if ($value['AppraisalMonth_updt'] != null && $value['AppraisalMonth_updt'] != '' && $value['AppraisalMonth_updt'] != '0') {
								$AppraisalMonth_updt = $value['AppraisalMonth_updt'];
								$next_month_ts = date('Y-m-d', strtotime("+$AppraisalMonth_updt months", strtotime($next_month_ts)));
							}
							// echo $next_month_ts;
							// die;
							//echo '<br/>';
							// echo $prev_month_ld = strtotime($new_effectiveDate .  'last day of previous month');
							// // echo "<br>";
							// echo $prev_month_ldat = date('Y-m-d', $prev_month_ld);

							if (($curdate >= $prev_month) && ($curdate <= $next_month_ts)) {

								$sqlquery = "select EmployeeID from resign_details where EmployeeID=? and now() between nt_start and nt_end";
								$stmt = $conn->prepare($sqlquery);
								$stmt->bind_param("s", $_SESSION['__user_logid']);
								$stmt->execute();
								$result = $stmt->get_result();
								//$resultraw = $result->fetch_row();
								$count = $result->num_rows;
								if ($count == 0) {
									$_SESSION["__Appraisal"] = "Yes";
								} else {
									$_SESSION["__Appraisal"] = "No";
								}
							} else {
								$_SESSION["__Appraisal"] = "No";
							}
						} else {
							$_SESSION["__Appraisal"] = "No";
						}
					}
				}
			}

			//// Appraisal Session End //////////////////////



			//$myDB  = new MysqliDb();
			//$rst_check = $myDB->query("select dateofjoin from employee_map where EmployeeID = '".$_POST['txt_usrId']."' ");
			//$doj = $rst_check['employee_map']['dateofjoin'];
			/*if(strtotime($rst_check['employee_map']['dateofjoin']) < strtotime("2016-08-01") && $_SESSION['__user_logid'] != 'CE08070107')
			{
				
				$myDB=new MysqliDb();	
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
			/*
		generate Token For dashboard authentication
		*/
			$token = '';
			if ($_SESSION['__df_id'] != '74' && $_SESSION['__df_id'] != '77' && $_SESSION['__df_id'] != '146' && $_SESSION['__df_id'] != '147' && $_SESSION['__df_id'] != '148' && $_SESSION['__df_id'] != '149') {
				$string1 = str_shuffle('abcdefghijklmnopqrstuvwxyz');
				$random1 = substr($string1, 0, 3);
				$string2 = str_shuffle('1234567890');
				$random2 = substr($string2, 0, 3);
				$random = time() . $random1 . $random2;
				$token = md5($random);
			}

			$rst_login = $myDB->query('INSERT INTO login_history(EmployeeID,IP,source,location,token) VALUES("' . $_SESSION['__user_logid'] . '","' . get_client_ip_ref() . '","Web","' . $_SESSION["__location"] . '","' . $token . '")');

			// if ($_SESSION['__user_logid'] == 'CE03070003') {
			// 	$datetime = date('d-m-Y H:i:s');
			// 	$Subject_ = 'Login at ' . EMS_CenterName . ': ' . date('d-m-Y H:i:s');
			// 	$mail = new PHPMailer;
			// 	$mail->isSMTP(); // Set mailer to use SMTP
			// 	$mail->Host = EMAIL_HOST;
			// 	$mail->SMTPAuth = EMAIL_AUTH;
			// 	$mail->Username = EMAIL_USER;
			// 	$mail->Password = EMAIL_PASS;
			// 	$mail->SMTPSecure = EMAIL_SMTPSecure;
			// 	$mail->Port = EMAIL_PORT;
			// 	$mail->setFrom(EMAIL_FROM, 'EMS: Logged In');
			// 	$mail->AddAddress('sachin.siwach@cogenteservices.com');
			// 	$mail->Subject = $Subject_;
			// 	$Body .= "Hi Sachin,<br><br>You have logged in at " . EMS_CenterName . " <br><br>";
			// 	$Body .= "System IP: " . get_client_ip_ref() . "<br>";
			// 	$Body .= "Date & Time: $datetime<br>";
			// 	$Body .= "<br><br>Regards,<br><br>EMS Emailer";
			// 	$mail->isHTML(true);
			// 	$mail->Body = $Body;
			// 	if (!$mail->send()) {

			// 		$lblMMAILmsg = 'Mailer Error: ' . $mail->ErrorInfo;
			// 	} else {
			// 		$lblMMAILmsg = 'and Mail Send successfully.';
			// 	}
			// }

			// if ($_SESSION['__user_logid'] == 'CE03070003') {
			// 	//$msg ="Dear ".$_SESSION["__user_Name"].", You have logged in EMS ".EMS_CenterName."(Date : ".date('d/m/Y h:i:s a',time())." and System IP: ".get_client_ip_ref()." ) Regards, EMS Secure Server";
			// 	$msg = "Dear " . $_SESSION["__user_Name"] . ", You have logged in EMS " . EMS_CenterName . "(Date : " . date('d/m/Y h:i:s a', time()) . " and System IP: " . get_client_ip_ref() . " ) - Cogent E Services";

			// 	$myDB = new MysqliDb();
			// 	$rst_contact = $myDB->query('select mobile,altmobile from contact_details where EmployeeID = "' . $_SESSION['__user_logid'] . '" limit 1');
			// 	//$rst_contact = $myDB->query('select mobile,altmobile from contact_details where EmployeeID = "CE04146339" limit 1');
			// 	if (!empty($rst_contact[0]['mobile'])) {
			// 		$TEMPLATEID = '1707161752152469550';
			// 		$url = SMS_URL;
			// 		$token = SMS_TOKEN;
			// 		$credit = SMS_CREDIT;
			// 		$sender = SMS_SENDER;
			// 		$message = $msg;
			// 		$number = $rst_contact[0]['mobile'];
			// 		$sendsms = new sendsms($url, $token);
			// 		$message_id = $sendsms->sendmessage($credit, $sender, $message, $number, $TEMPLATEID);
			// 		$response = $message_id;
			// 	}
			// }
			header("location:$location");
		} else {
			$_SESSION['MsgLg'] = 'Invalid Credentials';
			//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
			echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
		}
	}
}