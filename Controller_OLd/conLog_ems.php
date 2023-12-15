<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$empid = clean($_REQUEST['empid']);
if (isset($empid)) {
	$sqlConnect = 'call check_login_partial("' . $empid . '")';
	$myDB = new MysqliDb();
	$result = $myDB->query($sqlConnect);
	$error = $myDB->getLastError();
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

			// $data_emppwd = $myDB->query("select password from employee_map where EmployeeID ='" . $value['EmployeeID'] . "'");
			$data_emppwdQry = "select password from employee_map where EmployeeID =?";
			$stmt = $conn->prepare($data_emppwdQry);
			$stmt->bind_param("s", $value['EmployeeID']);
			$stmt->execute();
			$data_emppwd = $stmt->get_result();
			$data_emppwdRow = $data_emppwd->fetch_row();

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
			$_SESSION["__cm_id"] = $value['cm_id'];
			$_SESSION["__user_Comp"] = 'Cogent E Services Ltd.'; //$value['user_login']['CompanyName'];
			$_SESSION["__user_refrance"] =  $data_emppwdRow[0]; //['password'];
			$_SESSION["__DOJ"] = $value['dateofjoin'];
			$_SESSION["__OnFloor"] = $value['OnFloor'];
			$_SESSION["__password_utime"] = $value['password_updated_time'];

			if (empty($value['ReportTo'])) {
			} else {
				$_SESSION["__ReportTo"] = $value['ReportTo'];
				$_SESSION["__ReportToName"] = $value['Reportto_Name'];
			}

			//// Appraisal Session Start //////////////////////

			$_SESSION["__Appraisal"] = "No";
			/*if($value['df_id']=='74' || $value['df_id']=='77')
			{
				$_SESSION["__Appraisal"] = "No";
			}
			else
			{
				$datediff = time() - strtotime($value['dateofjoin']);
				$datediff =  round($datediff / (60 * 60 * 24));
				
				if($datediff < 350)
				{
					$_SESSION["__Appraisal"] = "No";
				}
				else
				{
					$_SESSION["__AppraisalMonth"] = $value['AppraisalMonth'];
					if($value['AppraisalMonth'] !='NA')
					{
						$CurMonth=date("m");
						$date = date_parse($value['AppraisalMonth']);
			  		  	$effectiveDate = date('Y').'-'.$date['month'].'-01';
					 
					  				
						//$UpdateMonth = strtotime("-1 months", strtotime($effectiveDate));
						$UpdateMonth = strtotime($effectiveDate);
						$UpdateMonth=date('m',$UpdateMonth);
						if($CurMonth==$UpdateMonth)
						{
							$_SESSION["__Appraisal"] = "Yes";
						}
						else
						{
							$_SESSION["__Appraisal"] = "No";
						}
					}
					else
					{
						$_SESSION["__Appraisal"] = "No";
					}
				}
			}*/

			//// Appraisal Session End //////////////////////

		}

		if (isset($_REQUEST['login']) && clean($_REQUEST['login']) == "admin") {
			$location = URL . 'View/avmanual.php';
		} else {
			$location = URL . 'View/aadhar_verification.php?tfs=' . clean($_REQUEST['tfs']);
		}


		header("location:$location");
	} else {
		$_SESSION['MsgLg'] = 'Your UserID or Password is incorrect ,Try again:<br/> <b>LogIn Failed</b>';
		//echo 'Your UserID or Password is incorrect ,Try again: <b>LogIn Failed</b>';;
		echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
	}
}
