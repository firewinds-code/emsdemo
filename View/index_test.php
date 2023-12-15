<?php

require_once(__dir__ . '/../Config/init.php');

//echo $location; die;
require_once(CLS . 'MysqliDb.php');

date_default_timezone_set('Asia/Kolkata');

$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
//echo URL;die;
//$location= URL.'Login'; 
//echo $location; die;
require(ROOT_PATH . 'AppCode/nHead.php');
echo $_SESSION["__Appraisal"];
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

//echo $_SESSION["__Asset_Approval"];
if (isset($_SESSION)) {
	//echo "password time=".$_SESSION["__password_utime"];
	$expiryDate = $pc_days = '0';
	if ($_SESSION["__password_utime"] != "") {
		$pc_days = findDays($_SESSION["__password_utime"]);
		$expiryDate = 30 - $pc_days;
		if ($pc_days >= 30) {
			$_SESSION['MsgLg'] = 'Your Password has been expired. Please use forgot password to reset';
			$location = URL . 'Login';
			//echo $location; 
			echo "<script>location.href='" . $location . "'</script>";
		}
	}
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		echo "<script>location.href='" . $location . "'</script>";
	} else {
		if (isset($_SESSION["vrdate"]) && $_SESSION["vrdate"] != "") {
?>
			<div id="myModal25" class="modal" style="margin-top: 307px; z-index: 9;margin-left: 255px;">
				<!-- Modal content-->
				<div class="modal-content">
					<h4 class="col s12 m12 model-h4">Aadhar Verification</h4>
					<div class="modal-body">
						<div>Please verify your Aadhar card within <?php echo (30 - $_SESSION["vrdate"]); ?> day(s) to continue your employment with the organization.</div><br>
						<button type="button" class="btn waves-effect modal-action modal-close waves-red close-btn" id="mmclose25">Acknowledge</button>

					</div>
				</div>
			</div>
<?php
		}
		/*
		$myDB = new MysqliDb();
		$calc_check = $myDB->query('call get_login_calatnd_history("' . $_SESSION['__user_logid'] . '")');
		$re = $myDB->getLastError();

		if (!$calc_check) {
			if ($_SESSION["__cm_id"] == "88" || $_SESSION["__cm_id"] == "152" || $_SESSION["__cm_id"] == "239" || $_SESSION["__cm_id"] == "265" || $_SESSION["__cm_id"] == "270" || $_SESSION["__cm_id"] == "420" || $_SESSION["__cm_id"] == "444" || $_SESSION["__cm_id"] == "445") {
				$myDB = new MysqliDb();
				$myDB->query('call save_login_calatnd_history("' . $_SESSION['__user_logid'] . '")');
				$url = URL . 'View/calcRange_zomato.php?empid=' . $_SESSION['__user_logid'] . '&type=one&from=' . date('Y-m-d', strtotime('-1 days'));

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				$data = curl_exec($curl);
			} else if (($_SESSION["__cm_id"] != "88" && $_SESSION["__cm_id"] != "152" && $_SESSION["__cm_id"] != "239" && $_SESSION["__cm_id"] != "265" && $_SESSION["__cm_id"] != "270" && $_SESSION["__cm_id"] != "420" && $_SESSION["__cm_id"] != "444" && $_SESSION["__cm_id"] != "445" && $_SESSION["__cm_id"] != "253" && $_SESSION["__cm_id"] != "307" && $_SESSION["__cm_id"] != "310" && $_SESSION["__cm_id"] != "252" && $_SESSION["__cm_id"] != "457" && $_SESSION["__cm_id"] != "458" && $_SESSION["__cm_id"] != "459" && $_SESSION["__cm_id"] != "460" && $_SESSION["__cm_id"] != "520" && $_SESSION["__cm_id"] != "521" && $_SESSION["__cm_id"] != "531" && $_SESSION["__cm_id"] != "461" && $_SESSION["__cm_id"] != "531" && $_SESSION["__cm_id"] != "535") && $_SESSION["__user_status"] != '1') {
				$myDB = new MysqliDb();
				$myDB->query('call save_login_calatnd_history("' . $_SESSION['__user_logid'] . '")');
				$url = URL . 'View/calcRange.php?empid=' . $_SESSION['__user_logid'] . '&type=one&from=' . date('Y-m-d', strtotime('-1 days'));
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				$data = curl_exec($curl);
			} else if ($_SESSION["__user_status"] != '1' && $_SESSION["__user_Desg"] != 'CSA') {
				$myDB = new MysqliDb();
				$myDB->query('call save_login_calatnd_history("' . $_SESSION['__user_logid'] . '")');
				$url = URL . 'View/calcRange.php?empid=' . $_SESSION['__user_logid'] . '&type=one&from=' . date('Y-m-d', strtotime('-1 days'));
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				$data = curl_exec($curl);
			}
			if ($_SESSION["__login_type"] != "Briefing") {
				if ('2021-11-13' >= $_SESSION['__DOJ']) {
					$myDB = new MysqliDb();
					$covidQuery = "SELECT id FROM `signup_policy_ack` where EmployeeID = '" . $_SESSION['__user_logid'] . "' ";
					$rscovidack = $myDB->query($covidQuery);
					if (count($rscovidack) < 1) {
						echo "<script>location.href='signup_policy.php'</script>";
						exit();
					}
				}
			}
		}
*/
		/* Code for covid acknowledge */
		$myDB = new MysqliDb();

		$clientid = clean($_SESSION["__user_client_ID"]);
		$emp = clean($_SESSION['__user_logid']);

		// $dayofweek = date("w", strtotime(date('Y-m-d')));
		// if ($dayofweek == 0) {
		// 	$currentMondayDate = date('Y-m-d', strtotime('monday last week'));
		// } else {
		// 	$currentMondayDate = date('Y-m-d', strtotime('monday this week'));
		// }


		// if ($_SESSION["__login_type"] != "Briefing") {
		// 	echo $covidQuery = "SELECT createdOn FROM `ack_covid_weekly_form` where EmployeeID = '" . $_SESSION['__user_logid'] . "' and cast(createdOn as date) between '" . $currentMondayDate . "' and cast(NOW() as date)  ";
		// 	die;
		// 	// echo "<br>";
		// 	$rscovidack = $myDB->query($covidQuery);
		// 	count($rscovidack);

		// 	if (count($rscovidack) < 1) {				//echo 'there';
		// 		echo "<script>location.href='weekly_ack_covid19_form.php'</script>";
		// 		exit();
		// 	}
		// }
		//bsr
		/*if ($_SESSION['__covid_weekly'] == 'Yes') {
			echo "<script>location.href='weekly_ack_covid19_form.php'</script>";
			exit();
		}*/

		if ($_SESSION['__nda_decl'] == 'Yes') {
			echo "<script>location.href='NDA_policy.php'</script>";
			exit();
		}

		if ($_SESSION['__isms_decl'] == 'Yes') {
			echo "<script>location.href='isms_policies_declaration.php'</script>";
			exit();
		}
		if ($_SESSION['__asset_decl'] == 'Yes') {
			echo "<script>location.href='asset_ack.php'</script>";
			exit();
		}

		$covidQuery = "select EmpID from induction_master where EmpID=? and EmpFlag=1";
		// echo "<br>";
		$selectQ = $conn->prepare($covidQuery);
		$selectQ->bind_param("s", $emp);
		$selectQ->execute();
		$rscovidack = $selectQ->get_result();

		// $rscovidack = $myDB->query($covidQuery);
		// count($rscovidack);

		if ($rscovidack->num_rows > 0) {				//echo 'there';
			echo "<script>location.href='induction_ack.php'</script>";
			exit();
		}

		$covidQuery = "select EmpID from emp_edu where EmpID=? and flag=0";
		// echo "<br>";
		$selectQ = $conn->prepare($covidQuery);
		$selectQ->bind_param("s", $emp);
		$selectQ->execute();
		$rscovidack = $selectQ->get_result();

		// $rscovidack = $myDB->query($covidQuery);
		// count($rscovidack);

		if ($rscovidack->num_rows > 0) {				//echo 'there';
			echo "<script>location.href='emp_edu_ack.php'</script>";
			exit();
		}

		// IJP new start
		/*
		$ijpQuery = "select * from ijp_req r left join ijp_ack a on r.empid=a.empid where r.empid=? and emp_ack is null;";
		// echo "<br>";
		$selectQ = $conn->prepare($ijpQuery);
		$selectQ->bind_param("s", $emp);
		$selectQ->execute();
		$rscovidack = $selectQ->get_result();

		// $rscovidack = $myDB->query($covidQuery);
		// count($rscovidack);

		if ($rscovidack->num_rows > 0) {				//echo 'there';
			echo "<script>location.href='ijp_ack.php'</script>";
			exit();
		}
		*/

		if ($clientid == 156) {
			$sqlquery = "select * from hathway_decl where employeeID= ?";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $emp);
			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();
			$count = $result->num_rows;
			if ($count == 0) {
				echo "<script>location.href='hathway_declaration.php'</script>";
				exit();
			}
			// if (count($rscovidack) < 1) {				//echo 'there';
			// 	echo "<script>location.href='weekly_ack_covid19_form.php'</script>";
			// 	exit();
			// }
		}

		if ($clientid == 147) {
			$sqlquery = "select * from tataaig_decl where employeeID= ?";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $emp);
			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();
			$count = $result->num_rows;
			if ($count == 0) {
				echo "<script>location.href='tata_aig_declaration.php'</script>";
				exit();
			}
			// if (count($rscovidack) < 1) {				//echo 'there';
			// 	echo "<script>location.href='weekly_ack_covid19_form.php'</script>";
			// 	exit();
			// }
		}

		if ($clientid == 147) {
			$sqlquery = "select * from tataaig_decl_self where EmployeeID= ?";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $emp);
			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();
			$count = $result->num_rows;
			if ($count == 0) {
				echo "<script>location.href='tata_aig_declaration_self.php'</script>";
				exit();
			}
			// if (count($rscovidack) < 1) {				//echo 'there';
			// 	echo "<script>location.href='weekly_ack_covid19_form.php'</script>";
			// 	exit();
			// }
		}

		if ($clientid == 152) {
			$sqlquery = "select * from airtel_pay_bank_decl where EmployeeID= ?";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $emp);
			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();
			$count = $result->num_rows;
			if ($count == 0) {
				echo "<script>location.href='airtel_pay_bank_declaration.php'</script>";
				exit();
			}
			// if (count($rscovidack) < 1) {				//echo 'there';
			// 	echo "<script>location.href='weekly_ack_covid19_form.php'</script>";
			// 	exit();
			// }
		}

		if ($clientid == 151) {

			$sqlquery = "select * from dicv_decl where employeeID= ?";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $emp);

			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();

			$count = $result->num_rows;
			if ($count == 0) {
				echo "<script>location.href='dicv_declaration.php'</script>";
				exit();
			}
		}

		if ($clientid == 103) {

			$sqlquery = "select * from bajaj_finance_decl where employeeID= ?";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $emp);

			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();

			$count = $result->num_rows;
			if ($count == 0) {
				echo "<script>location.href='bajaj_finance_decl.php'</script>";
				exit();
			}
		}

		if ($clientid == 6) {
			$sqlquery = "select * from den_decl where employeeID= ?";
			$stmt = $conn->prepare($sqlquery);
			$stmt->bind_param("s", $emp);
			$stmt->execute();
			$result = $stmt->get_result();
			//$resultraw = $result->fetch_row();
			$count = $result->num_rows;
			if ($count == 0) {
				echo "<script>location.href='den_declaration.php'</script>";
				exit();
			}
		}

		/* message page show first on 20 nov 2020 start*/
		if ($_SESSION["__login_type"] != "Briefing") {
			$myDB = new MysqliDb();
			$unreadmsg = $myDB->query("SELECT msg_date FROM tbl_chat_message where trim(to_empid) = '" . $_SESSION['__user_logid'] . "' and ackstatus=0 ");
			if (count($unreadmsg) > 0) {

				$messagepage = URL . 'View/message-popup.php';
				echo "<script>location.href='" . $messagepage . "'</script>";
				exit();
			}
		}

		/* message page show first on 20 nov 2020 END*/
		if ($_SESSION["__login_type"] != "Briefing") {
			$myDB = new MysqliDb();
			$rsttd = $myDB->query("SELECT created_on FROM tbl_contact_log where EmployeeID = '" . $_SESSION['__user_logid'] . "' order by created_on desc limit 1; ");
			if (!empty($rsttd) && $rsttd) {
				$iTime_in = new DateTime($rsttd[0]['created_on']);
				$iTime_out = new DateTime();
				$interval = $iTime_in->diff($iTime_out);
				if ($interval->format("%a") > 15) {
					$linctosurvey = URL . 'View/update_mobile_alert.php';
					echo "<script>location.href='" . $linctosurvey . "'</script>";
					exit();
				}
			} else {
				$linctosurvey = URL . 'View/update_mobile_alert.php';
				echo "<script>location.href='" . $linctosurvey . "'</script>";
				exit();
			}
		}
	}
} else {
	$location = URL . 'Login';
	//echo $location; die;
	echo "<script>location.href='" . $location . "'</script>";
}

function findDays($date)
{
	$datetime1 = new DateTime($date);
	$datetime2 = new DateTime(date("Y-m-d"));
	$difference = $datetime1->diff($datetime2);
	return $totalDays = $difference->days;
}
/*
$cm_id = $_SESSION["__cm_id"];
$user_status = $_SESSION['__user_status'];
$induction_popup_flag = 0;
$myDB = new MysqliDb();
$induction_array = $myDB->query("select days_of_rotation,days_from_floor,days_from_joining from new_client_master where cm_id='" . $cm_id . "'");
$days_of_rotation = '';
$days_from_floor = '';
$days_from_joining = '';
$createdDate = '';
$totalDays = '';
$flag1 = 0;
if (count($induction_array) > 0) {
	$days_of_rotation = $induction_array[0]['days_of_rotation'];
	$days_from_floor = $induction_array[0]['days_from_floor'];
	$days_from_joining = $induction_array[0]['days_from_joining'];
}
$DOJ = $_SESSION['__DOJ'];
$DOJDays = findDays($DOJ);
$OnFloor = '';
if (isset($_SESSION["__OnFloor"])) {
	$OnFloor = $_SESSION["__OnFloor"];
}
$response = '';
$myDB = new MysqliDb();
$qfarray = $myDB->query("Select EmployeeID,createdOn,response  from   emp_question_response where EmployeeID='" . $_SESSION['__user_logid'] . "'  order by id desc limit 1 ");
if (isset($qfarray[0]['createdOn'])) {
	$createdDate = $qfarray[0]['createdOn'];
	$totalDays = findDays($createdDate);
	$response = $qfarray[0]['response'];
}
if ($DOJDays == $days_from_joining) {
	$induction_popup_flag = 1;
} else
if (($days_from_floor == findDays($OnFloor)) and ($user_status == 6)) {
	$induction_popup_flag = 1;
} else
if ($totalDays > 15 && $response != 'No' && $response != '') {
	$induction_popup_flag = 1;
}
if ($induction_popup_flag == 1 && $totalDays != 0) {
	if (isset($_POST['saveq'])) {
		$myDB = new MysqliDb();
		$myDB->query("insert into emp_question_response set EmployeeID='" . $_SESSION['__user_logid'] . "',response='" . $_POST['qresponse'] . "',remarks='" . addslashes($_POST['remarks']) . "' ");
		$flag1 = 1;
	}
}
*/

if (isset($_POST['btn_handover'])) {
	$validateBy = $_SESSION['__user_logid'];
	if ($_POST['verify'] == 'Id Card') {
		$myDB = new MysqliDb();
		$myDB->query('update doc_al_status set ID_Card_Ack=1 where EmployeeID="' . $validateBy . '"');
	} else {
		$myDB = new MysqliDb();
		$myDB->query('update doc_al_status set taken=1,takentime = now() where EmployeeID="' . $validateBy . '"');
	}
}

if (isset($_POST['btn_accessCard'])) {
	$validateBy = $_SESSION['__user_logid'];
	$myDB = new MysqliDb();
	$myDB->query('update access_card_master set confirmation=1,conf_On = now() where EmployeeID="' . $validateBy . '"');
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">HOME</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<?php
		if ($induction_popup_flag == 1 && $totalDays != 0 && $flag1 == 0) {
		?>
			<div class="modal fade" id="myModal2" role="dialog" style='background:#f0efee;width:900px;'>
				<div class="modal-dialog" style="">
					<!-- Modal content-->

					<div class="modal-content">
						<div>
							<div><b>Dear <?php echo ucwords(strtolower($_SESSION['__user_Name'])); ?></b></div><br>
							<div><b>The Intent of EMS is to empower the employees for self-care and help. We are hoping that you are able to use it as per your convenience.</b></div><br>
							<div><b>We understand that there might be some gaps on understanding the features. We will like to extend training for you to understand the areas where you think you need help. We will be reaching out to you to extend the required help and trainings.</b></div></br>
							<form method="post" action='index.php'>
								<div><b>Do you feel that need a training for effective use of Employee Management System (EMS)?</b></div></br>
								<div class=" col s3 m3">
									<input type="radio" id="yr" name="qresponse" value="Yes" />
									<label for="yr">Yes</label>
								</div>
								<div class="col s3 m3 ">
									<input type="radio" id="nr" name="qresponse" value="No" />
									<label for="nr">No</label>
								</div></br>
								<div class="col s12 m12 ">

									<label for="remarks"><b>Remarks:</b></label></br></br>
									<input type="textarea" id="remarks" name="remarks" placeholder="Please suggest problem areas you need knowledge for" maxlength="500" style="width:500px;height:80px;"> </textarea>
								</div><br>

								<div class="col s12 m12  right-align">
									<input type="submit" name="saveq" id="saveq" class="btn waves-effect waves-green" onclick="return validateFq();">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php }  ?>

		<?php
		if ($pc_days > 30 and $pc_days < 55) {
		?>
			<!-- Sub Main Div for all Page -->
			<div id="myModal4" class="modal" style="margin-top: 4%; margin-left: 255px; z-index:9 ;">
				<!-- Modal content-->
				<div class="modal-content">
					<h4 class="col s12 m12 model-h4">Change Your Password</h4>
					<div class="modal-body">
						<div>Please change your password, it is expiring in <?php echo $expiryDate; ?> day(s)</div><br>
						<button type="button" class="btn waves-effect modal-action modal-close waves-red close-btn" id="mmclose">Cancel</button>
						<button type="button" class="btn waves-effect waves-red close-btn pull-right" id="updatep">Change Password</a></button>
					</div>
				</div>
			</div>
		<?php
		}

		$ijpQry = 'select t1.remarks from ijp_master t1 left join ijp_emp t2 on t1.id=t2.ijpID where t2.EmployeeID="' . $_SESSION["__user_logid"] . '" and flag=0 order by t1.id desc';
		$myDB = new MysqliDb();
		$ijpRes1 = $myDB->query($ijpQry);

		$remarks = $ijpRes1[0]['remarks'];

		$ijp = 'select * from ijp_emp where EmployeeID="' . $_SESSION["__user_logid"] . '" and flag=0';
		$myDB = new MysqliDb();
		$ijpRes = $myDB->query($ijp);
		if ($myDB->count > 0) { ?>
			<!-- Sub Main Div for all Page -->
			<div id="myModalIJP" class="modal" style="margin-top: 4%; margin-left: 255px; z-index:9; overflow:hidden;">
				<!-- Modal content-->
				<div class="modal-content" style="background-color: #F1F1F1;">
					<h4 class="col s12 m12 model-h4 blink">
						<!-- <canvas id="canvas"></canvas> -->
						<p style="color: #0910e9; margin-top:-1.2rem; font-size:medium;">Congratulation! you are eligible for Internal job Post </p><canvas id="canvas"></canvas>
					</h4>
					<div class="modal-body">
						<div><?php echo $remarks;  ?></div><br>
						<button type="button" class="btn waves-effect modal-action modal-close waves-red close-btn pull-right" id="mmcloseijp">OK</button>

					</div>
				</div>
			</div>


		<?php }

		$Empid = clean($_SESSION["__user_logid"]);
		$Empname = clean($_SESSION["__user_Name"]);
		$currdate = date("Y-m-d");
		// $month15 = date('Y-m-15', strtotime('-1 month'));
		// // echo "<br>";
		// $monthend = date('Y-m-d', strtotime('last day of previous month'));
		// // echo "<br>";
		// $monthlastday = date('d', strtotime($monthend));
		$__AppraisalMonth = clean($_SESSION["__AppraisalMonth"]);
		$appraisaldata = 'select EmployeeID,AppraisalMonth from salary_details where EmployeeID=? and AppraisalMonth=?';
		$selectQu = $conn->prepare($appraisaldata);
		$selectQu->bind_param("ss", $Empid, $__AppraisalMonth);
		$selectQu->execute();
		$appraisalRes = $selectQu->get_result();
		$appraisalrow = $appraisalRes->fetch_row();
		// $appraisalrow[1];
		$new_effectiveDate = date('Y') . '-' . $appraisalrow[1] . '-' . '15';
		// echo "<br>";
		$prev_month_ts = strtotime($new_effectiveDate .  '-1 month');
		// echo "<br>";
		$prev_month = date('Y-m-d', $prev_month_ts);
		// echo "<br>";
		$prev_month_ld = strtotime($new_effectiveDate .  'last day of previous month');
		// echo "<br>";
		$prev_month_ldat = date('Y-m-d', $prev_month_ld);
		// echo "<br>";
		// echo $monthlastday = date('d', strtotime($prev_month_ldat));


		if ($_SESSION["__Appraisal"] == "Yes" && $currdate >= $prev_month && $currdate <= $prev_month_ldat) {	?>

			<!-- Sub Main Div for all Page -->
			<h4 class="col s12 m12 model-h4 blink">
				<!-- <canvas id="canvas"></canvas> -->
				<p style="color: #fff; margin-top:-0.5rem; font-size:medium;">Hi <?php echo $Empname ?>, Congratulation your appraisal is due kindly fill the form between 15th to 30th (Month)</p>
				<button type="button" class="btn waves-effect modal-action modal-close waves-red close-btn pull-right" id="mclose">OK</button>
			</h4>
		<?php
		}

		?>





		<form method="post">
			<div class="form-div">
				<h4>HOME</h4>
				<style>
					.blink {
						animation: blinker 2.5s linear infinite;
						color: white;
						font-family: sans-serif;
					}

					@keyframes blinker {
						50% {
							opacity: 0;
						}
					}
				</style>

				<style>
					h2 {
						display: block;
						font-size: 1.5em;
					}

					.crosscover-item>img {
						display: block;
						width: 100%;
						height: 100%;
					}

					.crosscover {
						background-color: #fff;
					}

					.crosscover-item.is-active {
						z-index: 0 !important;
					}
				</style>

				<div class="schema-form-section row" style="padding-left: 42px;height: 630px !important;">

					<?php

					// if($_SESSION["__cm_id"] == "88" )
					// {
					?>
					<!--<img src="../Images/helpdeskzommato.JPG" width="934" height="508" ></img>-->
					<?php // } 
					?>
					<!--
		<h3 style="color: #1593FF;text-align: center;border-bottom: 2px solid #1AC11A;box-shadow: 0px 4px 4px -3px rgba(0, 0, 0, 0.86);margin: 0px;padding: 5px;">Welcome To <span style="color: #03A60F;font-weight: bold;"> EMPLOYEE MANAGEMENT SYSTEM </span></h3>-->
					<?php

					$myDB = new MysqliDb();
					$chk_task = $myDB->query('call check_task_show("' . $_SESSION['__user_logid'] . '")');
					if (!empty($chk_task) && $chk_task) {
						if ($_SESSION['__user_logid'] == "CE10091236") {
							echo '<div id="div_bVish" class="slideInDown animated row"  style="position:relative;border: 1px solid #f4f6f7;border-radius: 8px;padding: 15px;/* box-shadow: 0px 0px 6px 0px rgba(43, 196, 218, 0.26),0px 0px 6px 1px rgba(0, 120, 189, 0.38) inset; */background-image: url(../Style/img/happy-birthday.png);background-size: 100% 100%;height: 400px;margin-top: 20px;"><h1 class="text-center" style="color: #2196F3;text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.67),1px 1px 1px #165A6F,1px 1px 1px #0C4252,1px 1px 1px #195D84;margin: 2px;">Happy Birthday</h1><h3 style="color: #6B4F23;padding: 0px 30px;text-shadow:1px 1px 1px #BDBDBD;background: #ffffff80;"> Happy Birthday <span style="color: #497B08;">' . $_SESSION['__user_Name'] . '</span> !! Have a wonderful happy, healthy birthday and many more to come.We hope that today is the beginning of a great year for you. Happy Birthday. May you have another year of good times and great accomplishments. Here\'s to the boss! </h3><p style="text-align: right;color: green;font-weight: bold;margin-top: 130px;">cogent ems team </p></div>';
						} else {
							echo '<div id="div_bVish" class="slideInDown animated row"  style="position:relative;border: 1px solid #f4f6f7;border-radius: 8px;padding: 15px;/* box-shadow: 0px 0px 6px 0px rgba(43, 196, 218, 0.26),0px 0px 6px 1px rgba(0, 120, 189, 0.38) inset; */background-image: url(../Style/img/happy-birthday.png);background-size: 100% 100%;height: 400px;margin-top: 20px;"><h1 class="text-center" style="color: #2196F3;text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.67),1px 1px 1px #165A6F,1px 1px 1px #0C4252,1px 1px 1px #195D84;margin: 2px;">Happy Birthday</h1><h3 style="color: #6B4F23;padding: 0px 30px;text-shadow:1px 1px 1px #BDBDBD;background: #ffffff80;"> Happy Birthday <span style="color: #497B08;">' . $_SESSION['__user_Name'] . '</span> !! Have a wonderful happy, healthy birthday and many more to come.We hope that today is the beginning of a great year for you. Happy Birthday. </h3><p style="text-align: right;color: green;font-weight: bold;margin-top:130px;">cogent ems team</p></div>';
						}
					}

					?>
					<?php
					if (file_exists('../IndexEditPage/content_current/index_help.php')) {
						include('../IndexEditPage/content_current/index_help.php');
						if ($_SESSION["__login_type"] != "Briefing") {
							include('../View/ref_registration1.php');
						}
					} else {
					?>


						<section class="crousal" style="height: 500px !important;">
							<div class="crosscover" style="background-image: url('../Style/img/slidemain.jpg');background-position: 100% 100%;background-size: 100% 100%;margin-top: 10px;">

								<div class="crosscover-list">
									<a class="crosscover-item" style="background-size: 100% 100%;" target="_self">
										<img src="../Style/img/slide4.jpg" alt="image01" />
									</a>
									<div class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;">
										<img src="../Style/img/slide3.1.jpg" alt="image02" />
									</div>
									<a class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;" target="_self">
										<img src="../Style/img/slide3.1.jpg" alt="image03" />
									</a>
									<div class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;">
										<img src="../Style/img/slider2.png" alt="image04" />
									</div>
									<a class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;" target="_self">
										<img src="../Style/img/slide5.jpg" alt="image05" />
									</a>
								</div>

							</div>
						</section>

					<?php
					}
					?>
					<hr class="soften" />
					<section style="margin-top:40px">

						<?php

						if ($_SESSION['__user_type'] == "ADMINISTRATOR" || $_SESSION['__user_logid'] == "CE10091236") {
							$myDB = new MysqliDb();
							$result = $myDB->query("select (select count( distinct EmployeeID)   from ActiveEmpID) Employee, count(distinct nc.client_name) client,count(distinct nc.process) process ,count(distinct nc.cm_id) subprocess from new_client_master nc left join client_status_master cs on nc.cm_id=cs.cm_id where cs.cm_id is null");

							//# Employee, client, process, subprocess
							//'13546', '61', '98', '218'
							$counEmployee = $result[0]['Employee'];
							$countClient = $result[0]['client'];
							$countProcess = $result[0]['process'];
							$countSubproc = $result[0]['subprocess'];

						?>

							<div class="row">
								<div class="col s6 m3 zoomInDown animated">
									<div class="card">
										<div class="card-image">
											<img src="<?php echo STYLE . 'Theme/webnet/index_tab_1.png' ?>" style="height: 188px;">
											<span class="card-title white-text text-darken-4 orange darken-3">Employee - <?php echo $counEmployee; ?></span>
										</div>
										<!-- <div class="card-content">
					          <p><?php echo $counEmployee; ?></p>
					        </div>	-->
									</div>
								</div>

								<div class="col s6 m3 zoomInDown animated">
									<!--zoomInDown animated-->
									<div class="card">
										<div class="card-image">
											<img src="<?php echo STYLE . 'Theme/webnet/index_tab_2.png' ?>" style="    height: 188px;">
											<span class="card-title white-text text-darken-4 orange darken-3">Client - <?php echo $countClient; ?></span>
										</div>
										<!--<div class="card-content">
					          <p><?php echo $countClient; ?></p>
					        </div>-->
									</div>
								</div>


								<div class="col s6 m3 zoomInDown animated">
									<div class="card">
										<div class="card-image">
											<img src="<?php echo STYLE . 'Theme/webnet/index_tab_3.png' ?>" style="height: 188px;">
											<span class="card-title white-text text-darken-4 orange darken-3">Process - <?php echo $countProcess; ?></span>
										</div>
										<!--<div class="card-content">
					          <p><?php echo $countProcess; ?></p>
					        </div>-->
									</div>
								</div>

								<div class="col s6 m3 zoomInDown animated">
									<div class="card">
										<div class="card-image">
											<img src="<?php echo STYLE . 'Theme/webnet/index_tab_4.png' ?>" style="height: 188px;">
											<span class="card-title white-text text-darken-4 orange darken-3">Sub Process - <?php echo $countSubproc; ?></span>
										</div>
										<!-- <div class="card-content">
						          <p><?php echo $countSubproc; ?></p>
						        </div>-->
									</div>
								</div>
							</div>

						<?php
						}
						?>


					</section>
				</div>

				<?php
				$myDB = new MysqliDb();
				$data_val = $myDB->query('SELECT * FROM doc_al_status where EmployeeID = "' . $_SESSION['__user_logid'] . '" and validate = 1 and handover = 1;');
				if (count($data_val) > 0 && $data_val[0]['handover'] == 1 &&  $data_val[0]['taken'] == 1  &&  date('Ymd', strtotime($data_val[0]['takentime'])) == date('Ymd', time()) && $data_val) {
				?>
					<div class="had-container">
						<a onclick="javascript:return get_popUp('done',<?php echo "'on (" . date('d M,Y h:m:s A', strtotime($data_val[0]['takentime'])) . ")'"; ?>,'Appointment Letter');" style="text-shadow: 1px 1px 1px white,1px 1px 1px gray;font-weight: bold;cursor: pointer;border: 2px solid #ec7a03;padding: 10px;border-radius: 10px;box-shadow: 1px 1px 8px -2px #ec1818,1px 1px 8px -2px #867f7f inset;color: #c16a0f;width: calc(100% - 30px);margin-bottom: 10px;margin-left: 15px;margin-right: 15px;" class="waves-effect waves-red modal-trigger" href="#myModal"> <i class="fa fa-thumbs-o-up"></i> Appointment Letter confirmation has done. </a>
					</div>
				<?php
				} else
	if (count($data_val) > 0 && $data_val[0]['handover'] == 1 &&  $data_val[0]['taken'] == 0 && $data_val) {
				?>
					<div class="had-container no-padding">
						<a onclick="javascript:return get_popUp('','','Appointment Letter');" style="text-shadow: 1px 1px 1px white,1px 1px 1px gray;font-weight: bold;cursor: pointer;border: 2px solid #ea5700;padding: 10px;border-radius: 10px;box-shadow: 1px 1px 8px -2px gray,1px 1px 8px -2px gray inset;color: #8a371e;width: calc(100% - 30px);margin-bottom: 10px;margin-left: 15px;margin-right: 15px;" class="waves-effect waves-red modal-trigger" href="#myModal"><i class="fa fa-exclamation-triangle"></i> Appointment Letter is <b>Handover</b> to you, Kindly acknowledge the same.</a>
					</div>
				<?php
				}
				$myDB = new MysqliDb();
				$ID_Card_val = $myDB->query('SELECT ID_Card,ID_Card_Ack  FROM doc_al_status where EmployeeID = "' . $_SESSION['__user_logid'] . '" ');
				if (count($ID_Card_val) > 0 &&  $ID_Card_val[0]['ID_Card_Ack'] == '0' && $ID_Card_val[0]['ID_Card'] == '1') {
				?>
					<div class="had-container no-padding">
						<a onclick="javascript:return get_popUp('','','Id Card');" style="text-shadow: 1px 1px 1px white,1px 1px 1px gray;font-weight: bold;cursor: pointer;border: 2px solid #ea5700;padding: 10px;border-radius: 10px;box-shadow: 1px 1px 8px -2px gray,1px 1px 8px -2px gray inset;color: #8a371e;width: calc(100% - 30px);margin-bottom: 10px;margin-left: 15px;margin-right: 15px;" class="waves-effect waves-red modal-trigger" href="#myModal3"><i class="fa fa-exclamation-triangle"></i> Id Card is <b>Issued</b> to you, Kindly acknowledge the same.</a>
					</div>
				<?php
				}
				$myDB = new MysqliDb();
				$accessData = $myDB->query('SELECT * FROM access_card_master where EmployeeID = "' . $_SESSION['__user_logid'] . '" and confirmation = 0;');
				if (count($accessData) > 0 && $accessData[0]['confirmation'] == 0 && $accessData) {
				?>
					<div class="had-container no-padding">
						<a class="waves-effect waves-red modal-trigger" style="text-shadow: 1px 1px 1px white,1px 1px 1px gray;font-weight: bold;cursor: pointer;border: 2px solid #ec7a03;padding: 10px;border-radius: 10px;box-shadow: 1px 1px 8px -2px #ec1818,1px 1px 8px -2px #867f7f inset;color: #c16a0f;width: calc(100% - 30px);margin-bottom: 10px;margin-left: 15px;margin-right: 15px;" href="#myModal1"> <i class="fa fa-thumbs-o-up"></i> Access Card confirmation . </a>
					</div>
					<div id="myModal1" class="modal">
						<!-- Modal content-->
						<div class="modal-content">
							<h4 class="col s12 m12 model-h4">Confirmation of Access Card</h4>
							<div class="modal-body">
								<span class="text-warning" style="float: left;margin-right: 100px;"><b>Confirmation ! </b> I hereby confirm that i have received my Access Card [<b>#<?php echo $accessData[0]['card_no']; ?></b>].</span>
								<!--
	        <button type="submit" class="btn waves-effect waves-green" name="btn_accessCard" id="btn_accessCard" >Confirm</button>-->
								<input type="submit" class="btn waves-effect waves-green" value="Confirm" name="btn_accessCard" id="btn_accessCard">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn waves-effect modal-action modal-close waves-red close-btn">Close</button>
						</div>
					</div>
				<?php
				}
				?>
				<div id="myModal" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Confirmation of Appointment Letter</h4>
						<div class="modal-body">
							<p>No Data Found</p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn waves-effect modal-action modal-close waves-red close-btn">Close</button>
					</div>
				</div>
				<div id="myModal3" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Confirmation of Id Card</h4>
						<div class="modal-body">
							<p>No Data Found</p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn waves-effect modal-action modal-close waves-red close-btn">Close</button>
					</div>
				</div>
				<style>
					div.formpage {
						width: 750px !important;
					}

					.tableArrange {
						padding-left: 15px;
						padding-top: 15px;
					}

					.draggable {
						width: 90px;
						height: 90px;
						padding: 0.5em;
						float: left;
						margin: 0 10px 10px 0;
					}

					#containment-wrapper-1 {
						width: 95%;
						height: 150px;
						border: 2px solid #ccc;
						padding: 10px;
					}

					.panel-preview {
						cursor: auto !important;
					}


					.no-border .top .left,
					.no-border .top .right,
					.no-border .top .middle {
						background: none !important;
					}

					.no-border .bottom .left,
					.no-border .bottom .right,
					.no-border .bottom .middle {
						background: none !important;
					}

					.no-border .maincontent {
						border: none !important;
					}

					.config-listing {
						margin-top: 10px;
						width: 100%;
					}

					.config-listing thead tr th {
						text-align: center;
						padding: 5px 10px;
					}

					.config-listing tbody tr td {
						text-align: left;
						padding: 3px 3px 3px 5px;
					}

					.config-listing thead tr {
						background-color: #F28C38;
					}

					.config-listing tbody tr {
						background-color: #FFF3D6;
					}

					.config-wrapper {
						margin-bottom: 30px;

					}

					.ui-widget-content {
						background: none !important;
					}

					/*
.ui-resizable, .ui-draggable { position: absolute !important;}*/

					.panel_draggable {
						padding: 0px;
						float: left;
						display: block;
						margin: 0px;
						cursor: move;
						overflow: hidden;
						position: relative !important;
					}

					tr.tableArrangetr td {
						padding-bottom: 3px;
						padding-left: 10px;
					}

					fieldset.panel_resizable legend {
						font-weight: bold;
						font-size: 14px;
						margin-left: 10px;
						padding-top: 1px;
					}

					.panel_wrapper {
						overflow: hidden !important;

					}

					.clear {
						float: none !important;
						clear: both !important;
					}

					.outerbox {
						display: inline-block;
						float: left;
					}

					.dashboardCard-title {
						text-align: center;
						font-size: 14px;
						font-weight: 700;
						line-height: 50px;
						margin-left: -12px;
						padding-left: 30px;
						padding-right: 30px;
						height: 40px;
						margin-top: 0;
						margin-bottom: 0;
						padding-top: 0px;
						padding-bottom: 0px;
					}

					div.dashboardCard-title-for-card {
						font-size: 14px;
						font-weight: 700;
						line-height: 50px;
						margin-top: 0;
						margin-left: -16px;
						padding-left: 50px;
						padding-right: 30px;
						text-align: left;

					}

					.panel_resizable {
						border: 0px !important;
					}

					.collection .collection-item.avatar {
						min-height: 30px;
						padding: 15px;
					}

					.collection .collection-item:hover,
					.collection .collection-item:active,
					.collection .collection-item:focus {
						background-color: #fff;
					}

					.item-container .collection .collection-item.avatar .title {
						font-size: 13px;
					}

					#anouncementOnDashboard .collection .collection-item.avatar,
					#updatesOnDashboard .collection .collection-item.avatar {

						min-height: 70px !important;
						border-bottom: 1px solid #e0e0e0;
						padding-left: 30px !important;
						padding-right: 50px !important;
					}
				</style>
				<div id="card" class="col s12 no-padding" style="box-shadow: none;background: #fff;">
					<div id="card" class="col s6" style="box-shadow: none;background: #fff;">
						<div id="panel_resizable_2_9" class="card ohrm-card " style=" height: 400px;border: 1px solid #f2f2f2;">
							<div class="dashboardCard-title-for-card">Announcement</div>

							<div class="" style="height: 100%;" id="dashboard__viewAnnouncementOnDashboard">
								<p></p>
								<div class="col s12 m12 center">

									<div class="preloader-wrapper big active">
										<div class="spinner-layer spinner-blue">
											<div class="circle-clipper left">
												<div class="circle"></div>
											</div>
											<div class="gap-patch">
												<div class="circle"></div>
											</div>
											<div class="circle-clipper right">
												<div class="circle"></div>
											</div>
										</div>

										<div class="spinner-layer spinner-red">
											<div class="circle-clipper left">
												<div class="circle"></div>
											</div>
											<div class="gap-patch">
												<div class="circle"></div>
											</div>
											<div class="circle-clipper right">
												<div class="circle"></div>
											</div>
										</div>

										<div class="spinner-layer spinner-yellow">
											<div class="circle-clipper left">
												<div class="circle"></div>
											</div>
											<div class="gap-patch">
												<div class="circle"></div>
											</div>
											<div class="circle-clipper right">
												<div class="circle"></div>
											</div>
										</div>

										<div class="spinner-layer spinner-green">
											<div class="circle-clipper left">
												<div class="circle"></div>
											</div>
											<div class="gap-patch">
												<div class="circle"></div>
											</div>
											<div class="circle-clipper right">
												<div class="circle"></div>
											</div>
										</div>
									</div>

								</div>
							</div>


							<script type="text/javascript">
								$(document).ready(function() {
									var moduleUrl = <?php echo "'" . URL . 'View/announcement.php' . "'"; ?>;
									var divId = 'dashboard__viewAnnouncementOnDashboard';

									var loaderCallback = function() {
										$.ajax({
											url: moduleUrl,
											cache: true,
											success: function(obj) {
												$.ajaxSetup({
													// Enable caching of AJAX responses
													cache: true
												});
												$("#" + divId).html(obj);
											},
											complete: function() {
												$("#" + divId).removeClass('loadmask');

											}
										});

									};

									if (document.readyState == 'complete') {
										loaderCallback();
									} else {
										$(window).load(function() {
											loaderCallback();
										});
									}
								});
							</script>
						</div>
					</div>
					<div id="card" class="col s6" style="box-shadow: none;background: #fff;">
						<div id="panel_resizable_2_9" class="card ohrm-card " style=" height: 400px;border: 1px solid #f2f2f2;">
							<div class="dashboardCard-title-for-card">Updates</div>

							<div class="" style="height: 100%;" id="dashboard__viewUpdatesOnDashboard">
								<p></p>
								<div class="col s12 m12 center">

									<div class="preloader-wrapper big active">
										<div class="spinner-layer spinner-blue">
											<div class="circle-clipper left">
												<div class="circle"></div>
											</div>
											<div class="gap-patch">
												<div class="circle"></div>
											</div>
											<div class="circle-clipper right">
												<div class="circle"></div>
											</div>
										</div>

										<div class="spinner-layer spinner-red">
											<div class="circle-clipper left">
												<div class="circle"></div>
											</div>
											<div class="gap-patch">
												<div class="circle"></div>
											</div>
											<div class="circle-clipper right">
												<div class="circle"></div>
											</div>
										</div>

										<div class="spinner-layer spinner-yellow">
											<div class="circle-clipper left">
												<div class="circle"></div>
											</div>
											<div class="gap-patch">
												<div class="circle"></div>
											</div>
											<div class="circle-clipper right">
												<div class="circle"></div>
											</div>
										</div>

										<div class="spinner-layer spinner-green">
											<div class="circle-clipper left">
												<div class="circle"></div>
											</div>
											<div class="gap-patch">
												<div class="circle"></div>
											</div>
											<div class="circle-clipper right">
												<div class="circle"></div>
											</div>
										</div>
									</div>

								</div>
							</div>


							<script type="text/javascript">
								$(document).on("click", ".head-avatar", function() {
									var itm_check = $(this).children("i").text();
									$(".head-avatar").closest("span").siblings("div.body-avatar").addClass("hidden");
									$(".head-avatar").children("i").text("keyboard_arrow_down");

									if (itm_check == "keyboard_arrow_down") {
										$(this).closest("span").siblings("div.body-avatar").removeClass("hidden");
										$(this).children("i").text("keyboard_arrow_up");
									}



								});
								$(document).ready(function() {

									$('#updatep').click(function() {
										location.href = 'changepwd.php';
									});
									$('#mmcloseijp').click(function() {
										location.href = 'ijp_acknowledge.php';
									});

									$('#mclose').click(function() {
										location.href = 'AppraisalForm.php';
									});

									$('#myModal4').show();
									$('#mmclose').click(function() {
										$('#myModal4').hide();
									});

									$('#myModalIJP').show();
									$('#mmcloseijp').click(function() {
										$('#myModalIJP').hide();
									});

									$('#myModal25').show();
									$('#mmclose25').click(function() {
										$('#myModal25').hide();
									});
									var moduleUrl = <?php echo "'" . URL . 'View/updates.php' . "'"; ?>;
									var divId = 'dashboard__viewUpdatesOnDashboard';

									var loaderCallback = function() {
										$.ajax({
											url: moduleUrl,
											cache: true,
											success: function(obj) {
												$.ajaxSetup({
													// Enable caching of AJAX responses
													cache: true
												});
												$("#" + divId).html(obj);
											},
											complete: function() {
												$("#" + divId).removeClass('loadmask');

											}
										});

									};

									if (document.readyState == 'complete') {
										loaderCallback();
									} else {
										$(window).load(function() {
											loaderCallback();
										});
									}
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

</div>

<?php
if ($induction_popup_flag == 1 && $totalDays != 0 && $flag1 == 0) {
?>
	<script src="../Script/bootstrap2.min.js"></script>
	<style>
		.disablediv {
			pointer-events: none;
			opacity: 70% !important;
		}
	</style>

<?php
}
?>

<?php if ($_GET['param'] == 'IJP') { ?>
	<script>
		$(document).ready(function() {
			toastr.success('Thank you, your IJP consent submitted');
		});
	</script>
<?php } ?>

<script>
	function validateFq() {


		var rates = document.getElementsByName('qresponse');
		var rate_value;
		for (var i = 0; i < rates.length; i++) {
			if (rates[i].checked) {
				rate_value = rates[i].value;
			}
		}
		if (!(rate_value == 'Yes' || rate_value == 'No')) {
			alert('Please mark yes / no button');
			return false;
		}

		if (rate_value == 'Yes') {
			if ($('#remarks').val() == "") {
				alert('Please enter your remark');
				return false;
			}
			var remrklen = ($('#remarks').val().length);
			if (remrklen < 100) {
				alert('Please emter minimum 100 character');
				return false;
			}
		}

	}
	$(function() {

		$('button').click(function(event) {
			event.preventDefault();
			//$(document).on("keydown", disableF5);

		});

		$('#div_bVish').delay(60000).fadeOut();
		$('.modal').modal();
	});

	function get_popUp(el, el1, poptext) {
		if (el != '' && el == 'done') {
			$('.modal-body').html('<div><span class="text-warning" style="float: left;margin-right: 100px;"></span></div><div class="alert alert-success">Confirmation of ' + poptext + ' has done by you <b>' + el1 + '</b>.</div>');

		} else if (el == '' && el != 'done') {
			$('.modal-body').html('<span class="text-warning" style="float: left;margin-right: 100px;">Confirmation ! ' + poptext + ' Handover</span><input type="hidden" name="verify" value="' + poptext + '"><input type="submit" class="waves-effect waves-light btn" value="Confirm" name="btn_handover" id="btn_handover">');

		}


	}
</script>
<script>
	let W = window.innerWidth;
	let H = window.innerHeight;
	const canvas = document.getElementById("canvas");
	const context = canvas.getContext("2d");
	const maxConfettis = 150;
	const particles = [];
	const possibleColors = [
		"DodgerBlue",
		"OliveDrab",
		"Gold",
		"Pink",
		"SlateBlue",
		"LightBlue",
		"Gold",
		"Violet",
		"PaleGreen",
		"SteelBlue",
		"SandyBrown",
		"Chocolate",
		"Crimson"
	];

	function randomFromTo(from, to) {
		return Math.floor(Math.random() * (to - from + 1) + from);
	}

	function confettiParticle() {
		this.x = Math.random() * W; // x
		this.y = Math.random() * H - H; // y
		this.r = randomFromTo(11, 33); // radius
		this.d = Math.random() * maxConfettis + 11;
		this.color =
			possibleColors[Math.floor(Math.random() * possibleColors.length)];
		this.tilt = Math.floor(Math.random() * 33) - 11;
		this.tiltAngleIncremental = Math.random() * 0.07 + 0.05;
		this.tiltAngle = 0;
		this.draw = function() {
			context.beginPath();
			context.lineWidth = this.r / 2;
			context.strokeStyle = this.color;
			context.moveTo(this.x + this.tilt + this.r / 3, this.y);
			context.lineTo(this.x + this.tilt, this.y + this.tilt + this.r / 5);
			return context.stroke();
		};
	}

	function Draw() {
		const results = [];
		// Magical recursive functional love
		requestAnimationFrame(Draw);
		context.clearRect(0, 0, W, window.innerHeight);
		for (var i = 0; i < maxConfettis; i++) {
			results.push(particles[i].draw());
		}
		let particle = {};
		let remainingFlakes = 0;
		for (var i = 0; i < maxConfettis; i++) {
			particle = particles[i];
			particle.tiltAngle += particle.tiltAngleIncremental;
			particle.y += (Math.cos(particle.d) + 3 + particle.r / 2) / 2;
			particle.tilt = Math.sin(particle.tiltAngle - i / 3) * 15;
			if (particle.y <= H) remainingFlakes++;
			// If a confetti has fluttered out of view,
			// bring it back to above the viewport and let if re-fall.
			if (particle.x > W + 30 || particle.x < -30 || particle.y > H) {
				particle.x = Math.random() * W;
				particle.y = -30;
				particle.tilt = Math.floor(Math.random() * 10) - 20;
			}
		}
		return results;
	}
	window.addEventListener(
		"resize",
		function() {
			W = window.innerWidth;
			H = window.innerHeight;
			canvas.width = window.innerWidth;
			canvas.height = window.innerHeight;
		},
		false
	);
	// Push new confetti objects to `particles[]`
	for (var i = 0; i < maxConfettis; i++) {
		particles.push(new confettiParticle());
	}
	// Initialize
	canvas.width = W;
	canvas.height = H;
	Draw();
</script>
<!---->
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>