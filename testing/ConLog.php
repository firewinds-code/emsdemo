<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
// include_once(__dir__ . '/../Services/sendsms_API1.php');
$Body = '';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

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

$login_btn = isset($_POST['btn_login']);
if ($login_btn) {
	$userID = cleanUserInput($_POST['txt_usrId']);
	if (trim($userID) == "CE03070003" || trim($userID) == "CE10091236") {
		//Do nothing
	} else {

		// $myDB = new MysqliDb();
		// $ip_list = $myDB->rawQuery("select distinct IP from whitelistedip where IP = '" . get_client_ip_ref() . "' order by id desc limit 1;");
		// $ip_list = $myDB->rawQuery("select distinct IP from whitelistedip where IP = '" . $clientip1  . "' order by id desc limit 1;");
		// if (count($ip_list) > 0 && $ip_list) {
		// 	//do nothing;
		// } else {

		// 	$location = URL . 'Error1.php';
		// 	//echo "<script>location.href='".$location."'</script>";	
		// 	header("Location: $location");
		// 	exit();
		// }
	}

	$usrid = cleanUserInput(trim($_POST['txt_usrId']));
	$pwd = cleanUserInput(trim($_POST['txt_usr_pwd']));


	$query = "select count(*) from throttling_check where  try_time > NOW() - INTERVAL 60 second && emp_id =?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $usrid);
	$stmt->execute();
	$Result = $stmt->get_result();
	$row = $Result->fetch_row(); // for fetching the index rows like $row[0];
	$res1 = $row[0];
	if ($res1 < 4) {

		// $sql = "select userid,username,clientid from login_demo where userid='" . $usrid  . "' and pwd='" . $pwd . "' ";
		$sql = "select userid,username,clientid from login_demo where userid=? and pwd=?";
		$selectQ = $conn->prepare($sql);
		$selectQ->bind_param("ss", $usrid, $pwd);
		$selectQ->execute();
		$Response = $selectQ->get_result();
		$res = $Response->fetch_row();
		// $Response = $myDB->rawQuery($sql);
		// if ($Response->num_rows > 0) {
		if ($Response->num_rows > 0) {
			$myDB = new MysqliDb();
			$sql = 'call briefing_login("' . $res[2] . '")';
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
				$txt_usr_pwd = cleanUserInput($_POST['txt_usr_pwd']);
				$_SESSION["__user_refrance"] = $txt_usr_pwd;
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

				$_SESSION["__IsApprover"] = "No";
				$_SESSION['reviewer'] = "No";
				$_SESSION['approver'] = "No";
				$_SESSION["__Appraisal"] = "No";
				$_SESSION["__AppraisalMonth"] = "NA";
				require(ROOT_PATH . 'Controller/log_create.php');
				$Action = new PHPLog_Action(clean($_SESSION['__user_logid']), "Login TO", clean($_SESSION["__user_Name"]) . " Log In To EMS");

				$location = URL . 'View/index.php';

				header("location:$location");
			} else {
				$usrid = cleanUserInput(trim($_POST['txt_usrId']));
				$query = "insert into throttling_check (emp_id,try_time) values (?,now())";
				$stmt = $conn->prepare($query);
				$stmt->bind_param("s", $usrid);
				$stmt->execute();
				// ///
				// $attempts = 2 - $res1;
				// if ($attempts <= 0) {
				// 	$_SESSION['MsgLg'] = '<script>$(function(){toastr.error("Too many failed attempts try again later")})</script>';
				// } else {
				// 	$_SESSION['MsgLg'] = '<script>$(function(){toastr.error("Invalid Credentials ' . $attempts . ' Attempts Left")})</script>';
				// }

				$_SESSION['MsgLg'] = 'Invalid Credentials';
				//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
				echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
			}
		} else {

			$query = "select count(*) from throttling_check where  try_time > NOW() - INTERVAL 60 second && emp_id =?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("s", $usrid);
			$stmt->execute();
			$Result = $stmt->get_result();
			$row = $Result->fetch_row(); // for fetching the index rows like $row[0];
			$res1 = $row[0];
			if ($res1 < 4) {
				// $sqlQry = "update emp_auth set flag=1 where EmployeeID= ?";
				// $stmt = $conn->prepare($sqlQry);
				// $stmt->bind_param('s', $userID);
				// $stmt->execute();
				// $res_sqlQry = $stmt->get_result();
				// // $row = $result->fetch_assoc();
				// // echo "<pre>";
				// // print_r($res_sqlQry);
				// // die;
				// // print_r($row['flag']);
				// if ($stmt->affected_rows === 1) {
				// $location = URL . 'View/index.php';
				$password_hash = md5($_POST['txt_usr_pwd']);
				$txt_usrId = cleanUserInput($_POST['txt_usrId']);
				//$password_hash = $_POST['txt_usr_pwd'];
				$sql = 'call check_login("' . $txt_usrId . '","' . $password_hash . '")';
				// echo $sql;
				$myDB = new MysqliDb();
				$result = $myDB->rawQuery($sql);
				$mysql_error = $myDB->getLastError();
				// echo $sql;
				// die;

				$rst_contact = array();
				// $myDB  = new MysqliDb();
				$userID = cleanUserInput($_POST['txt_usrId']);
				$rstcheck = "SELECT created_at,verify_date,aadhar_status FROM ems.aadhar_verifiaction where EmployeeID = ? ";
				$selectQ = $conn->prepare($rstcheck);
				$selectQ->bind_param("s", $userID);
				$selectQ->execute();
				$results = $selectQ->get_result();
				$rst_check = $results->fetch_row();
				// $myDB  = new MysqliDb();
				if (count($result) > 0 && isset($result[0]['cm_id']) && $result[0]['cm_id'] != "") {
					$cmid = $result[0]['cm_id'];
					$rstcontact = "select t1.mobile,t2.EmployeeName as ahname from contact_details t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID where t1.EmployeeID=(select account_head from new_client_master where cm_id=?) ";
					$selectQu = $conn->prepare($rstcontact);
					$selectQu->bind_param("i", $cmid);
					$selectQu->execute();
					$resu = $selectQu->get_result();
					$rst_contact = $resu->fetch_row();
				}

				if ($results->num_rows > 0) {
					$cudate = date('Y-m-d');

					if (isset($rst_check[2]) &&  $rst_check[2] == 'pending') {
						//var_dump($rst_check);
						$start = strtotime($rst_check[0]);
						$end = strtotime($cudate);
						$reamainingDate = ceil(abs($end - $start) / 86400);
						//$reamainingDate = '1';
						$_SESSION["vrdate"] = "";
						if (date('Y-m-d', strtotime($rst_check[0])) >= "2021-05-15" && $reamainingDate > '30') {
							$ahname = '';
							$ahcontact = '';
							if ($resu->num_rows > 0) {
								$ahname = $rst_contact[1];
								$ahcontact = $rst_contact[0];
							}
							/* echo $_SESSION['MsgLg']='Your Aadhaar card is not verified, please contact your process account head '.$ahname.' on '.$ahcontact.'';
							$url=URL.'LogIn';
							echo "<script>location.href='".$url."'</script>";
							exit;*/
						} elseif (date('Y-m-d', strtotime($rst_check[0])) >= "2021-05-15" && $reamainingDate <= '30') {
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
						$txt_usr_pwd = cleanUserInput($_POST['txt_usr_pwd']);
						$_SESSION["__user_refrance"] = $txt_usr_pwd;
						$_SESSION["__DOJ"] = $value['dateofjoin'];
						$_SESSION["__OnFloor"] = $value['OnFloor'];
						$_SESSION["__password_utime"] = $value['password_updated_time'];
						$_SESSION["__location"] = $value['location'];
						$_SESSION["__status_rt"] = $value['rt'];
						$_SESSION["__gender"] = strtoupper($value['gender']);
						$_SESSION["__status_er"] = $value['ER'];
						$_SESSION["salEmp"] = "No";
						if (empty($value['ReportTo'])) {
							echo $_SESSION['MsgLg'] = 'Invalid Mapping';
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

					$usrlogid = clean($_SESSION["__user_logid"]);
					$myDB  = new MysqliDb();
					$empidSelect = $myDB->query("select id from desimination_matrix where empid like '%" . $usrlogid . "%' ");
					// echo "select id from desimination_matrix where empid like '%" . $usrlogid . "%' ";
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
						$cookie_value = clean($_SESSION["__user_logid"]);
						setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
					}
					$_SESSION["__IsApprover"] = "No";
					$txt_usrId = cleanUserInput($_POST['txt_usrId']);
					$sql = 'select t1.id from module_master_new t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID where emp_status="Active" and (l1empid=? or l2empid=?) limit 1';
					$selectQ = $conn->prepare($sql);
					$selectQ->bind_param("ss", $txt_usrId, $txt_usrId);
					$selectQ->execute();
					$result = $selectQ->get_result();
					$resu = $result->fetch_row();
					//echo $sql;
					// $myDB = new MysqliDb();
					// $result = $myDB->rawQuery($sql);
					if ($result->num_rows > 0 && $result) {
						if (isset($resu[0]) && $resu[0] != "") {
							$_SESSION["__IsApprover"] = "Yes";
						}
					}
					$Emp = clean($_SESSION['__user_logid']);
					// $myDB = new MysqliDb();
					$query = "SELECT id,EmpID from salary_master  where EmpID=?";
					$selectQr = $conn->prepare($query);
					$selectQr->bind_param("s", $Emp);
					$selectQr->execute();
					$results = $selectQr->get_result();
					$saldata = $results->fetch_row();
					// $saldata = $myDB->query($query);
					if ($results->num_rows > 0 && $saldata[0] != '') {
						$_SESSION["salEmp"] = "Yes";
					} else {
						$_SESSION["salEmp"] = "No";
					}

					$_SESSION['reviewer'] = "No";
					$_SESSION['approver'] = "No";

					// $myDB = new MysqliDb();
					$EmpID = clean($_SESSION['__user_logid']);
					$reviewApprove = "select id,EmployeeID,is_reviewer,is_approver from expense_matrix where EmployeeID=?";
					$selectQry = $conn->prepare($reviewApprove);
					$selectQry->bind_param("s", $EmpID);
					$selectQry->execute();
					$resul = $selectQry->get_result();
					$reviewResult = $resul->fetch_row();
					// $reviewResult = $myDB->rawQuery($reviewApprove);
					// echo "<pre>";
					// print_r($reviewResult);
					// die;
					if ($resul->num_rows > 0 && $reviewResult[0] != '') {
						if ($reviewResult[2] == "Yes" || $reviewResult[2] == "Yes" && $reviewResult[3] == "Yes") {
							$_SESSION['reviewer'] = "Yes";
						} else {
							$_SESSION['reviewer'] = "No";
						}
						if ($reviewResult[3] == "Yes" || $reviewResult[2] == "Yes" && $reviewResult[3] == "Yes") {
							$_SESSION['approver'] = "Yes";
						} else {
							$_SESSION['approver'] = "No";
						}
					} else {
						$_SESSION['reviewer'] = "No";
						$_SESSION['approver'] = "No";
					}

					require(ROOT_PATH . 'Controller/log_create.php');
					$Action = new PHPLog_Action(clean($_SESSION['__user_logid']), "Login TO", clean($_SESSION["__user_Name"]) . " Log In To EMS");

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

					if ($value['df_id'] == '74' || $value['df_id'] == '77') {
						$_SESSION["__Appraisal"] = "No";
					} else {
						$datediff = time() - strtotime($value['dateofjoin']);
						$datediff =  round($datediff / (60 * 60 * 24));

						if ($datediff < 350) {
							$_SESSION["__Appraisal"] = "No";
						} else {
							$_SESSION["__AppraisalMonth"] = $value['AppraisalMonth'];
							if ($value['AppraisalMonth'] != 'NA') {
								$CurMonth = date("m");
								$date = date_parse($value['AppraisalMonth']);
								$effectiveDate = date('Y') . '-' . $date['month'] . '-01';


								//$UpdateMonth = strtotime("-1 months", strtotime($effectiveDate));
								$UpdateMonth = strtotime($effectiveDate);
								$UpdateMonth = date('m', $UpdateMonth);
								if ($CurMonth == $UpdateMonth) {
									$_SESSION["__Appraisal"] = "Yes";
								} else {
									$_SESSION["__Appraisal"] = "No";
								}
							} else {
								$_SESSION["__Appraisal"] = "No";
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
							echo $_SESSION['MsgLg']='Hi '.$_SESSION["__user_Name"].', Kindly fill <br /> <b>Employee Registration Form</b> to Login EMS :';
							//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
							echo "<script type='text/javascript'>location.href = '".URL."LogIn';</script>";
							exit();
						}
						
					}
					else
					{
						echo $_SESSION['MsgLg']='Hi '.$_SESSION["__user_Name"].', Kindly fill <br /><b>Employee Registration Form</b> to Login EMS :';
						//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
						echo "<script type='text/javascript'>location.href = '".URL."LogIn';</script>";
						exit();
					}
					

					$myDB = new MysqliDb();
					/*
					generate Token For dashboard authentication
					*/
					$token = '';
					if (clean($_SESSION['__user_Desg']) != 'CSA') {
						$string1 = generateRandomString();
						$random1 = substr($string1, 0, 3);
						$string2 = generateRandomNumber();
						$random2 = substr($string2, 0, 3);
						$random = time() . $random1 . $random2;
						$token = md5($random);
					}

					$sqlQry = "update emp_auth set flag=1 where EmployeeID= ?";
					$stmt = $conn->prepare($sqlQry);
					$stmt->bind_param('s', $userid);
					$stmt->execute();
					// $res_sqlQry = $stmt->get_result();
					// echo "ffffff";
					// print_r($res_sqlQry);
					// die;
					// print_r($row['flag']);
					//if ($stmt->affected_rows === 1) {
					// if ($resstmt) {
					// 	echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
					// }

					$userid = clean($_SESSION['__user_logid']);
					$Location = clean($_SESSION["__location"]);
					$rstlogin = 'INSERT INTO login_history(EmployeeID,IP,source,location,token) VALUES(?,?,"Web",?,?)';
					$ins = $conn->prepare($rstlogin);
					$ins->bind_param("sisi", $userid, $clientip1, $Location, $token);
					$ins->execute();
					$rst_login = $ins->get_result();

					// if (clean($_SESSION['__user_logid']) == 'CE03070003') {
					// 	$datetime = date('d-m-Y H:i:s');
					// 	$Subject_ = 'Login at : ' . date('d-m-Y H:i:s');
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
					// 	$Body .= "Hi Sachin,<br><br>You have logged in at  <br><br>";
					// 	$Body .= "System IP: " . $clientip1 . "<br>";
					// 	$Body .= "Date & Time: $datetime<br>";
					// 	$Body .= "<br><br>Regards,<br><br>EMS Emailer";
					// 	$mail->isHTML(true);
					// 	$mail->Body = $Body;
					// 	// if (!$mail->send()) {

					// 	// 	$lblMMAILmsg = 'Mailer Error: ' . $mail->ErrorInfo;
					// 	// } else {
					// 	// 	$lblMMAILmsg = 'and Mail Send successfully.';
					// 	// }
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

					$query = "select count(*) from throttling_check where  try_time > NOW() - INTERVAL 60 second && emp_id =?";
					$stmt = $conn->prepare($query);
					$stmt->bind_param("s", $usrid);
					$stmt->execute();
					$Result = $stmt->get_result();
					$row = $Result->fetch_row(); // for fetching the index rows like $row[0];
					$res1 = $row[0];
					if ($res1 < 4) {
						$query = "insert into throttling_check (emp_id,try_time) values (?,now())";
						$stmt = $conn->prepare($query);
						$stmt->bind_param("s", $usrid);
						$stmt->execute();
						$_SESSION['MsgLg'] = 'Invalid Credentials';
						//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
						echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
					} else {

						$query = "insert into throttling_check (emp_id,try_time) values (?,now())";
						$stmt = $conn->prepare($query);
						$stmt->bind_param("s", $usrid);
						$stmt->execute();

						// ///
						$attempts = 2 - $res1;
						if ($attempts <= 0) {
							$_SESSION['MsgLg'] = 'Too many failed attempts try again later';
							echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
						} else {
							$_SESSION['MsgLg'] = '<span>Error : </span> Invalid Credentials' . " $attempts Attempts Left";
							echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
							// echo '<script>$(function(){toastr.error("Invalid Credentials ' . $attempts . ' Attempts Left")})</script>';
						}
					}
				}
				// } else {
				// 	echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
				// }
			} else {
				$query = "insert into throttling_check (emp_id,try_time) values (?,now())";
				$stmt = $conn->prepare($query);
				$stmt->bind_param("s", $usrid);
				$stmt->execute();

				// ///
				$attempts = 2 - $res1;
				if ($attempts <= 0) {
					$_SESSION['MsgLg'] = 'Too many failed attempts try again later';
					echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
				} else {
					$_SESSION['MsgLg'] = '<span>Error : </span> Invalid Credentials' . " $attempts Attempts Left";
					echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
					// echo '<script>$(function(){toastr.error("Invalid Credentials ' . $attempts . ' Attempts Left")})</script>';
				}
			}
		}
	} else {
		$usrid = cleanUserInput(trim($_POST['txt_usrId']));
		$query = "insert into throttling_check (emp_id,try_time) values (?,now())";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $usrid);
		$stmt->execute();
		// ///
		$attempts = 2 - $res1;
		if ($attempts <= 0) {
			$_SESSION['MsgLg'] = 'Too many failed attempts try again later';
			echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
		} else {
			$_SESSION['MsgLg'] = '<span>Error : </span> Invalid Credentials' . " $attempts Attempts Left";
			echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
			// echo '<script>$(function(){toastr.error("Invalid Credentials ' . $attempts . ' Attempts Left")})</script>';
		}
	}
}
