<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $Status1 = 0;
//Mailer function
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
$user_logid = $_SESSION['__user_logid'];
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
/*if (isset($_SESSION)) {
	if (!isset($user_logid) || $user_logid == "") {
		$location = URL . 'Login';
		header("Location: $location");
	} else {
		if ($user_logid == '' || $user_logid == null) {
			echo '<a href="' . URL . 'Login" >Go To Login </a>';
			exit();
		} else if (!($user_logid == 'CE01145570' || $user_logid == 'CE03070003' || $user_logid == 'CE021929762' ||  $user_logid == 'CE0621938742' || $user_logid == 'CE0421937404' || $user_logid == 'CE0321936918')) {
			die("access denied ! It seems like you try for a wrong action.");
			exit();
		}
	}
}*/
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid']) || $_SESSION['__user_logid'] == "") {
		$location = URL . 'Login';
		header("Location: $location");
		exit;
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit;
}

// if (isset($_SESSION)) {
// 	if (!isset($user_logid) || $user_logid == "") {
// 		$location = URL . 'Login';
// 		header("Location: $location");
// 		exit;
// 	}
// } else {
// 	$location = URL . 'Login';
// 	header("Location: $location");
// 	exit;
// }
$oldremark = $reqid = $mymsg = '';
$reqby = $referEmailID = $refferedBy = '';
// $IDDSAad = isset($_REQUEST['ID*DSAad']);
$DSAad = clean($_REQUEST['ID*DSAad']);
$DSAadArr = explode("_", $DSAad);
// echo "<pre>";
// print_r($DSAadArr);
$urlId = encryptor('decrypt', $DSAadArr[1]);
// $urlId = base64_decode($DSAadArr[1]);
$urlIdPre = $DSAadArr[0];
$urlIdPost = $DSAadArr[2];
// echo $urlIdPre;
$finalUrlId = $urlIdPre . '_' . $urlId . '_' . $urlIdPost;
// echo $finalUrlId;
$hid = isset($_POST['hidID']);
if ($DSAad || $hid) {
	if ($DSAad) {
		// $DSAad = clean($_REQUEST['ID*DSAad']);
		// $exop = explode('_', $DSAad);
		if (!empty($_REQUEST['status'])) {
			$stus = clean($_REQUEST['status']);
			$Status1 = explode('_', $stus);
			// echo "<pre>";
			// print_r($Status1);
			$Status1 = $Status1[0];
		}

		// $reqid = $exop[1];
		$reqid = $urlId;
	} else {
		$reqid = cleanUserInput($_POST['hidID']);
	}
}
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$user_Logid = clean($_SESSION['__user_logid']);
	$loCation = clean($_SESSION["__location"]);
}

if (isset($_POST['btn_pay_review'])) {
	$rev_sql = "update issue_tracker set status='Payout Reviewed',reviewed_date=now() where id=?";
	$stmupdt = $conn->prepare($rev_sql);
	$stmupdt->bind_param("i", $reqid);
	$stmupdt->execute();
	if ($stmupdt->affected_rows === 1) {
		echo "<script>$(function(){ toastr.success('Request Reviewed'); }); </script>";
		echo "<script>location.href='viewissue'; </script>";
	} else {
		echo "<script>$(function(){ toastr.error('Something Went Wrong'); }); </script>";
	}
}
$btnsave = isset($_POST['btnSave']);
if ($btnsave) {
	// echo "<pre>";
	// print_r($_POST);
	// echo $issue_date[0];
	// die;
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$status_txt = cleanUserInput($_POST['status_txt']);
		$user_name = clean($_SESSION['__user_Name']);
		$createdby = clean($_SESSION['__user_logid']);
		$rmk = cleanUserInput($_POST['remark']);
		$remark = '(' . date('Y-m-d h:i:s') . ')  : ' . $rmk;
		if ($status_txt == 'Inprogress' || $status_txt == 'InProgress' || $status_txt == 'Resolve') {
			//$oldremark = cleanUserInput($_POST['oldremark']);
			$myDB = new MysqliDb();
			//$query = 'call inprog_issueticket("' . addslashes(trim($oldremark)) . ' | ' . addslashes(trim($remark)) . '","' . $reqid . '","' . $user_name . '");';
			$query = 'call inprog_issueticket("' . $status_txt . '","' . addslashes(trim($remark)) . '","' . $reqid . '","' . $user_name . '");';
			$result = $myDB->query($query);
			//echo $query;
			$mysql_error = $myDB->getLastError();
			//<b class='text-danger'>Mr. ".$_SESSION['__user_Name']."</b>
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Issue Request Submitted, Thank You.'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Query Not Submitted " . $mysql_error . "'); }); </script>";
			}
		} else {

			if ($status_txt == 'Payout Request') {

				//$sqlupdt = "update issue_tracker set status=?,handler_remark=? where id=? ";				
				//$stmupdt = $conn->prepare($sqlupdt);
				//$stmupdt->bind_param("ssi", $status_txt, $remark, $hidID);
				//$stmupdt->execute();
				$query = 'call inprog_issueticket("' . $status_txt . '","' . addslashes(trim($remark)) . '","' . $reqid . '","' . $user_name . '");';
				$result = $myDB->query($query);
				//echo $query;
				//$mysql_error = $myDB->getLastError();

				$issue_date = $_POST['issue_date'];
				$issue_type = $_POST['issue_type'];
				$payout_days = $_POST['payout_days'];
				$amount_type = $_POST['amount_type'];
				$req_remark = $_POST['req_remark'];

				for ($i = 0; $i < sizeof($issue_date); $i++) {

					$sqlpay = "insert into issue_tracker_payout(issue_tracker_id, issue_date, issue_type, payout_days, amount_type, req_remark, created_by) values(?,?,?,?,?,?,?)";
					$stmt = $conn->prepare($sqlpay);
					$stmt->bind_param("issssss",  $reqid, $issue_date[$i], $issue_type[$i], $payout_days[$i], $amount_type[$i], $req_remark[$i], $user_logid);
					$stmt->execute();
					$stmtresult = $stmt->get_result();
				}
				if ($stmt->affected_rows === 1) {
					echo "<script>$(function(){ toastr.success('Payout Request inserted'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Something Went Wrong'); }); </script>";
				}
			} else if ($status_txt == 'Payout Inprogress') {

				//$sqlupdt = "update issue_tracker set status=?,handler_remark=? where id=? ";
				//$stmupdt = $conn->prepare($sqlupdt);
				//$stmupdt->bind_param("ssi", $status_txt, $remark, $hidID);
				//$stmupdt->execute();
				//if ($stmt->affected_rows === 1) {
				$query = 'call inprog_issueticket("' . $status_txt . '","' . addslashes(trim($remark)) . '","' . $reqid . '","' . $user_name . '");';
				$result = $myDB->query($query);
				//echo $query;
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					echo "<script>$(function(){ toastr.success('Request Submitted'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Something Went Wrong'); }); </script>";
				}
			} else {
				//$remark = '(' . date('Y-m-d h:i:s') . ')  : ' . $_POST['remark'];
				//$oldremark = cleanUserInput($_POST['oldremark']);
				//$createdby = clean($_SESSION['__user_logid']);

				//$refertto = 'NA';
				/*$refer_to = isset($_POST['refer_to']);
				if ($refer_to) {
					$refertto = cleanUserInput($_POST['refer_to']);
					$sql = "SELECT ofc_emailid,mobile from  contact_details where EmployeeID=? ";
					$selectQ = $conn->prepare($sql);
					$selectQ->bind_param("s", $refertto);
					$selectQ->execute();
					$result = $selectQ->get_result();
					$selectQuery = $result->fetch_row();
					if (isset($selectQuery[0])) {
						$referEmailID = $selectQuery[0];
					} else {
						$referEmailID = "";
					}
				} else {
					$refertto = 'NA';
				}
				$query='call check_issueticket("'.$oldremark.' | '.$remark.'","'.$reqid.'","'.$_POST['status_txt'].'","'.$refertto.'","'.$_SESSION['__user_Name'].'");';
				
				$query = 'call check_issueticket("' . addslashes(trim($oldremark)) . ' | ' . addslashes(trim($remark)) . '","' . $reqid . '","' . $status_txt . '","' . $refertto . '","' . $user_name . '");';
				$result = $myDB->query($query);
				//echo $query;
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					echo "<script>$(function(){ toastr.success('Issue Request Submitted, Thank You.') }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Query Not Submitted " . addslashes($mysql_error) . "') }); </script>";
				}*/
			}
		}
	}
}

if (isset($_POST['btn_edit_payout'])) {
	// echo "<pre>";
	// print_r($_POST);
	// die;
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$hideID = $_POST['hideID'];
		$payout_days = $_POST['payout_days'];
		$amount_type = $_POST['amount_type'];
		$amount = $_POST['amount'];
		$payout_status = $_POST['payout_status'];
		$payout_req_remark = $_POST['payout_req_remark'];

		$sqlfinl = "update issue_tracker_payout set payout_days=?,amount_type=?,amount=?,payout_status=?,payout_remarks=?,updated_by=?,updated_at=now()  where id=? ";
		$sqlfinl_stmt = $conn->prepare($sqlfinl);
		$sqlfinl_stmt->bind_param("ssssssi", $payout_days, $amount_type, $amount, $payout_status, $payout_req_remark, $user_logid, $hideID);
		$sqlfinl_stmt->execute();
		$stmt_res = $sqlfinl_stmt->get_result();
		if ($sqlfinl_stmt->affected_rows === 1) {
			echo "<script>$(function(){ toastr.success('Updated Successfully'); })</script>";
		} else {
			echo "<script>$(function(){ toastr.error('Something Went Wrong'); })</script>";
		}
	}
}
?>

<script>
	$(function() {
		$('#myTable,#myTablePayout').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [
				'pageLength'
			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"sScrollY": "192",
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {

				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Happy to Help</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Happy to Help</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Payout Details</h4>
						<div class="modal-body" style="height: 26rem;">
							<form method="POST">

								<?php $_SESSION["token"] = csrfToken(); ?>
								<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

								<input type="hidden" class="form-control hidden" id="hideID" name="hideID" />

								<div class="input-field col s4 m4">
									<input type="text" name="issue_date" id="issue_date">
									<label for="issue_date">Issue Date</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" name="issue_type" id="issue_type">
									<label for="issue_type">Issue Type</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" name="payout_days" id="payout_days" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
									<label for="payout_days">Payout Days</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" name="amount_type" id="amount_type">
									<label for="amount_type">Payout Type</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" name="req_remark" id="req_remark">
									<label for="req_remark">Requester Remark</label>
								</div>
								<div class="input-field col s4 m4" style="display:none;">
									<input type="text" name="amount" id="amount" value="0">
									<label for="amount">Amount</label>
								</div>
								<div class="input-field col s4 m4">
									<select name="payout_status" id="payout_status">
										<option value="NA">--Select--</option>
										<option value="Approve">Approve</option>
										<option value="Reject">Reject</option>
									</select>
									<label for="payout_status" class="active-drop-down active">Payout Status</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" name="payout_req_remark" id="payout_req_remark">
									<label for="payout_req_remark">Final Remark</label>
								</div>


								<div class="input-field col s12 m12 right-align">
									<button type="submit" name="btn_edit_payout" id="btn_edit_payout" class="btn waves-effect waves-green ">Save</button>

									<button type="button" name="btn_can_payout" id="btn_can_payout" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
								</div>
							</form>
						</div>
					</div>
				</div>


				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<?php
				$issue = $req_remark = $reqName = $issue_handler = '';
				if (!empty($reqid)) {
					//echo 'call get_issuetracker_byID("'.$reqid.'")';
					$myDB = new MysqliDb();
					$result = $myDB->query('call get_issuetracker_byID("' . $reqid . '")');
					if (count($result) > 0 && $result) {
						foreach ($result as $key => $value) {
							$ndate = date('Y-m-d');
							echo '<div class="col s12 m12">';
							$emp_id = $value['requestby'];
							echo '<a onclick="submitform(\'' . $emp_id . '\',\'' . $ndate . '\');" ><div class="card input-field col s12 m12" style="padding: 10px;text-align: center;font-weight: bold;"> Check Biometric and Roster</div></a>';

							//$appLink ='<a onclick="submitform(\''.$emp_id.'\',\''.$DateTo.'\');" > Check Biometric and Roster</a>';							

							echo '<div class="input-field col s3 m3">Case ID</div>
									<div class="input-field col s9 m9"><b>' . $reqid . '</b></div>';

							echo '<div class="input-field col s3 m3">Request By</div>
									<div class="input-field col s9 m9"><b>' . $value['EmployeeName'] . '</b></div>';

							echo '<div class="input-field col s3 m3">Mobile No</div>
									<div class="input-field col s9 m9"><b>' . $value['mobileNo'] . '</b></div>';

							$reqName = $value['EmployeeName'];
							$reqby = $value['requestby'];

							echo '<div class="input-field col s3 m3">Issue </div>
									<div class="input-field col s9 m9"><b>' . $value['queary'] . '</b></div>';

							$issue = $value['queary'];
							//echo '<div class="input-field col s3 m3">Request TO </div><div class="input-field col s9 m9"><b>'.$value['EmployeeName'].'</b></div>';						
							echo '<div class="input-field col s3 m3">Belongs TO </div>
									<div class="input-field col s9 m9"><b>' . $value['bt'] . '</b></div>';
							$oldremark = $value['handler_remark'];

							echo '<div class="input-field col s3 m3">Communicated With </div>
									<div class="input-field col s9 m9"><b>' . $value['committed_with'] . '</b></div>';

							echo '<div class="input-field col s3 m3">Concern off </div>
									<div class="input-field col s9 m9"><b>' . $value['concern_off'] . '</b></div>';

							$datetime1 = new DateTime(date('Y-m-d H:i:s'));
							$datetime2 = new DateTime($value['request_date']);
							$interval = $datetime2->diff($datetime1);
							if ($value['status'] == 'Pending') {
								if ($value['tat'] > ($interval->days * 24) + $interval->h) {
									$class = "bg-danger";
									$text = '<code>Tat Miss</code>';
								} else {
									$class = 'bg-success';
									$text = '<code>In Tat</code>';
								}
							} else {
								$class = 'text-primary bg-warning';
								$text = '';
							}
							echo '<div class="input-field col s3 m3">Tat</div>
									<div class="input-field col s9 m9"><b>' . $value['tat'] . ' Hour  </b>' . $text . '</div>';

							echo '<div class="input-field col s3 m3">Request Time </div>
									<div class="input-field col s9 m9"><b>' . $value['request_date'] . '</b></div>';

							if ($value['status'] == 'Pending' || $value['status'] == 'Reopen' || $value['status'] == 'Inprogress' || $value['status'] == 'InProgress' || $value['status'] == 'Payout Request' || $value['status'] == 'Payout Inprogress' || $value['status'] == 'Payout Reviewed') {

								echo '<div class="input-field col s3 m3">Request Status </div>';

								echo '<div class="input-field col s9 m9"><b> ' . $value['status'] . ' </b></div>';

								$req_remark = $value['requester_remark'];
								echo '<div class="input-field col s3 m3">Requester Remark </div>
										<div class="input-field col s9 m9"><b> ' . $value['requester_remark'] . ' </b></div>';

								echo '<div class="input-field col s3 m3">Handler Remark </div>
										<div class="input-field col s9 m9"><b> ' . $value['handler_remark'] . ' </b></div>';


								echo '</div>';

								$sqlpayout = "select id, issue_tracker_id, issue_date, issue_type, payout_days, amount_type,amount, req_remark,payout_remarks,payout_status, created_by,created_at from issue_tracker_payout where issue_tracker_id=? ";
								$payoutstm = $conn->prepare($sqlpayout);
								$payoutstm->bind_param("i", $reqid);
								$payoutstm->execute();
								$res_payoutstm = $payoutstm->get_result();
								if ($res_payoutstm->num_rows > 0) { ?>
									<br />
									<style>
										.cardss {
											box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .16), 0 2px 10px 0 rgba(0, 0, 0, .12) !important
										}
									</style>

									<div class="had-container pull-left row cardss" style="margin-top: 10px;width: 100%;padding: 15px;">
										<div class="">
											<table id="myTablePayout" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th>Action</th>
														<th>Issue Date</th>
														<th>Issue Type</th>
														<th>Payout Days</th>
														<th>Payout Type</th>
														<!--<th>Amount</th>-->
														<th>Requested Remark</th>
														<th>Payout Status</th>
														<?php if ($_SESSION['__user_logid'] != "CE01145570") { ?>
															<th>Payout Remark</th>
														<?php } ?>
														<th>Created At</th>
														<th class="hidden">ID</th>
													</tr>
												</thead>
												<tbody>
													<?php $naCount = 0;
													$totCount = 0;
													foreach ($res_payoutstm as $key => $value) {
														$totCount++;
														if ($_SESSION['__user_logid'] == "CE01145570") { ?>

															<tr>
																<td>
																	<?php if ($_SESSION['__user_logid'] == "CE01145570" && $value['payout_status'] != "Approve" && $value['payout_status'] != "Reject") { ?>
																		<i class="material-icons btn-primary edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="<?php echo $value['id'] ?>" data-position="left" data-tooltip="Edit">ohrm_edit</i>
																	<?php } else {
																		$naCount++;
																		echo "NA";
																	} ?>
																</td>
																<td class="issue_date"><?php echo $value['issue_date'] ?></td>
																<td class="issue_type"><?php echo $value['issue_type'] ?></td>
																<td class="payout_days"><?php echo $value['payout_days'] ?></td>
																<td class="amount_type"><?php echo $value['amount_type'] ?></td>
																<!--<td class="amount"><?php //echo $value['amount'] 
																						?></td>-->
																<td class="req_remark"><?php echo $value['req_remark'] ?></td>
																<td class="payout_status"><?php echo $value['payout_status'] ?></td>
																<?php if ($_SESSION['__user_logid'] != "CE01145570") { ?>
																	<td class="payout_remarks"><?php echo $value['payout_remarks'] ?></td>
																<?php } ?>
																<td class="created_at"><?php echo $value['created_at'] ?></td>
																<td class="hidden payoutID"><?php echo $value['id'] ?></td>
															</tr>
														<?php } else if ($_SESSION['__user_logid'] != "CE01145570") { ?>
															<tr>
																<td>

																	<?php
																	echo "NA";
																	?>
																</td>
																<td class="issue_date"><?php echo $value['issue_date'] ?></td>
																<td class="issue_type"><?php echo $value['issue_type'] ?></td>
																<td class="payout_days"><?php echo $value['payout_days'] ?></td>
																<td class="amount_type"><?php echo $value['amount_type'] ?></td>
																<!--<td class="amount"><?php //echo $value['amount'] 
																						?></td>-->
																<td class="req_remark"><?php echo $value['req_remark'] ?></td>
																<td class="payout_status"><?php echo $value['payout_status'] ?></td>
																<td class="payout_remarks"><?php echo $value['payout_remarks'] ?></td>
																<td class="created_at"><?php echo $value['created_at'] ?></td>
																<td class="hidden payoutID"><?php echo $value['id'] ?></td>
															</tr>
													<?php }
													} ?>
												</tbody>
											</table>
										</div>
									</div>
								<?php } ?>

								<?php //echo $totCount."=>".$naCount;
								?>

								<?php if ($_SESSION['__user_logid'] == "CE01145570") { ?>
									<div class="input-field col s12 m12 left-align">
										<button type="submit" id="btn_pay_review" class="btn waves-effect waves-green" name="btn_pay_review" <?php echo ($totCount == $naCount) ? "" : "disabled"; ?>>
											Payout Reviewed
										</button>
										<p></p>
									</div>
								<?php } ?>

								<?php if ($_SESSION['__user_logid'] != "CE01145570") { ?>

									<div class="input-field col s12 m12">

										<select name="status_txt" id="status_txt">
											<option value="NA">--Select--</option>
											<option value="Inprogress">Inprogress</option>
											<option value="Resolve">Resolve</option>
											<!-- <option value="Refer">Refer</option> -->
											<option value="Payout Request">Payout Request</option>
											<option value="Payout Inprogress">Payout Inprogress</option>
										</select>
										<label for="status_txt" class="active-drop-down active">Status</label>
									</div>

									<div class="input-field col s12 m12" id="addmorefields_div">
										<input type="hidden" name="issuesdata" />
										<div class="form-inline addChildbutton " style="margin-bottom: 10px;">
											<div class="form-group">
												<button type="button" name="btn_addfields" id="btn_addfields" onclick="" title="Add Row in Table Down" class="btn waves-effect waves-green">
													<i class="fa fa-plus"></i> Add Fields</button>
												<button type="button" name="btn_removefields" id="btn_removefields" title="Remove Row in Table Down" class="btn waves-effect modal-action modal-close waves-red close-btn">
													<i class="fa fa-minus"></i> Remove Fields</button>
											</div>
										</div>
										<table class="table table-hovered table-bordered" id="issuepayout">

											<thead class="bg-danger">
												<tr>
													<th>Date</th>
													<th>Issue Type</th>
													<th>Payout Days</th>
													<th>Amount Type</th>
													<th>Requested Remark</th>
												</tr>
											</thead>

											<tbody>

												<input type="hidden" name="rowcountid" id="rowcountid">

												<tr class="trdoc" id="trdoc_1">
													<td>
														<input type="text" name="issue_date[]" id="issue_datess">
													</td>
													<td>
														<input type="text" name="issue_type[]" id="issue_typess">
													</td>
													<td>
														<input type="text" name="payout_days[]" id="payout_daysss" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
													</td>
													<td>
														<input type="text" name="amount_type[]" id="amount_typess">
													</td>
													<td>
														<input type="text" name="req_remark[]" id="req_remarkss">
													</td>
												</tr>
											</tbody>

										</table>
									</div>

									<div class="input-field col s12 m12 hidden" id="refer_div">

										<select name="refer_to" id="refer_to">
											<option value="">---select---</option>
											<?php
											$loc = clean($_SESSION["__location"]);
											$resultemp = 'select * from happy_to_help_refer_emp where location = ? order by EmployeeName';
											$selQ = $conn->prepare($resultemp);
											$selQ->bind_param("i", $loc);
											$selQ->execute();
											$result_emp = $selQ->get_result();
											foreach ($result_emp as $key => $valEmp) { ?>
												<option value="<?php echo $valEmp['EmployeeID']; ?>"><?php echo $valEmp['EmployeeName']; ?></option>
											<?php
											}
											?>
										</select>
										<label for="refer_to" class="active-drop-down active">Refer To</label>
									</div>

									<div class="input-field col s12 m12">
										<textarea name="remark" id="remark" class="materialize-textarea"></textarea>
										<label for="remark">Remark</label>
									</div>

									<div class="input-field col s12 m12 right-align">
										<button type="submit" id="btnSave" class="btn waves-effect waves-green" name="btnSave">
											Submit <i class="fa fa-send"></i>
										</button>
										<p></p>
									</div>
								<?php } ?>

				<?php
								$userid = clean($_SESSION['__user_logid']);
							} elseif ($value['status'] == 'Refer' && $value['lo1'] == $userid) {
								echo '<div class="input-field col s3 m3">Request Status </div><div class="input-field col s9 m9"><b> ' . $value['status'] . ' </b></div>';
								$req_remark = $value['requester_remark'];
								echo '<div class="input-field col s3 m3">Requester Remark </div><div class="input-field col s9 m9"><b> ' . $value['requester_remark'] . ' </b></div>';
								echo '<div class="input-field col s3 m3">Handler Remark </div><div class="input-field col s9 m9"><b> ' . $value['handler_remark'] . ' ' . $value['refercomments'] . '</b></div>';
								echo '</div>';


								echo '<div class="input-field col s12 m12">
													<select name="status_txt" id="status_txt" >
													<option>Inprogress</option>
													<option>Resolve</option>
													</select>
												<label for="status_txt" class="active-drop-down active">Status</label>
												</div>
												
										<div class="input-field col s12 m12">
											<textarea name="remark" id="remark" class="materialize-textarea"></textarea>
											<label>Remark</label>
										</div>
										
										<div class="input-field col s12 m12 right-align">
										<button type="submit" id="btnSave" class="btn waves-effect waves-green" name="btnSave">Submit</button>
										</div>';
							} else {
								echo '<div class="input-field col s3 m3">Request Status </div><div class="input-field col s9 m9"><b> ' . $value['status'] . ' </b></div>';
								$req_remark = $value['requester_remark'];
								echo '<div class="input-field col s3 m3">Requester Remark </div><div class="input-field col s9 m9"><b> ' . $value['requester_remark'] . ' </b></div>';
								echo '<div class="input-field col s3 m3">Handler Remark </div><div class="input-field col s9 m9"><b> ' . $value['handler_remark'] . ' </b></div>';
								echo '</div>';
							}
						}
					}
					//$btnsaves = isset($_POST['btnSave']);
					/*if ($btnsaves) {

						if (isset($user_Logid) and $user_Logid != '' and $loCation != '') {
							$myDB = new MysqliDb();
							$dataContact = $myDB->query("call get_contact('" . $reqby . "')");
							$mailID = $dataContact[0]['emailid'];

							//if(!empty($mailID))
							//{
							// $myDB = new MysqliDb();
							$pagename = 'check_issue';

							$select_email = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=? and b.location =?";
							$selectQ = $conn->prepare($select_email);
							$selectQ->bind_param("si", $pagename, $loCation);
							$selectQ->execute();
							$select_email_array = $selectQ->get_result();

							$emailid = $mailID;
							$mail = new PHPMailer;
							$mail->isSMTP();
							$mail->Host = EMAIL_HOST;  // Specify main and backup SMTP servers
							$mail->SMTPAuth = EMAIL_AUTH;                               // Enable SMTP authentication
							$mail->Username = EMAIL_USER;                 // SMTP username
							$mail->Password = EMAIL_PASS;                           // SMTP password
							$mail->SMTPSecure = EMAIL_SMTPSecure;
							$mail->Port = EMAIL_PORT;
							$mail->setFrom(EMAIL_FROM, 'EMS:Cogent Grievance System');


							if ($select_email_array->num_rows > 0 && $select_email_array) {
								foreach ($select_email_array as $Key => $val) {
									$email_address = $val['email_address'];

									if ($email_address != "") {
										$mail->AddAddress($email_address);
									}
									$cc_email = $val['ccemail'];

									if ($cc_email != "") {
										$mail->addCC($cc_email);
									}
								}
							}
							$userName = clean($_SESSION['__user_Name']);
							$userLogID = clean($_SESSION['__user_logid']);
							$referto = isset($_POST['refer_to']);
							if ($referto && $referEmailID != "") {
								$mail->AddAddress($referEmailID);
								$refferedBy = "<b>Reffered By : </b>" . $userName . "(" . $userLogID . ")<br>";
							}

							$refID_id = $reqid;
							if ($Status1 == 'Reopen') {
								$ss1 = 'Re-' . $_POST['status_txt'];
							} else {
								$ss1 = cleanUserInput($_POST['status_txt']);
							}

							$loc = clean($_SESSION["__location"]);
							if ($loc == "1") {
								$EMS_CenterName = "Noida";
							} else if ($loc == "2") {
								$EMS_CenterName = "Mumbai";
							} else if ($loc == "3") {
								$EMS_CenterName = "Meerut";
							} else if ($loc == "4") {
								$EMS_CenterName = "Bareilly";
							} else if ($loc == "5") {
								$EMS_CenterName = "Vadodara";
							} else if ($loc == "6") {
								$EMS_CenterName = "Mangalore";
							} else if ($loc == "7") {
								$EMS_CenterName = "Bangalore";
							} else if ($loc == "8") {
								$EMS_CenterName = "Nashik";
							} else if ($loc == "9") {
								$EMS_CenterName = "Anantapur";
							} else if ($loc == "10") {
								$EMS_CenterName = "Gurgaon";
							} else if ($loc == "11") {
								$EMS_CenterName = "Hyderabad";
							}

							$mail->Subject = 'Happy to help ' . $EMS_CenterName . ', Reference #' . $refID_id . ' :' . $ss1;

							$mail->isHTML(true);
							$myDB = new MysqliDb();
							$info_emp = $myDB->query('call get_info_for_Issue_tracker("' . $reqby . '")');

							$remark = cleanUserInput($_POST['remark']);
							$pwd_ = '<span>Dear Sir,<br/><br/><span><b>Please find below the concern raised in happy to help.</b></span>.<br /><br/> <b>Concern Subject: ' . $issue . '</b>.<br /><br /><b>Concern:</b> ' . $req_remark . '.<br/><br/><br/>Concern Feedback : ' . $remark . '<br/> Thank You</b>.<br/>Regard,<br/>' . strtoupper($reqName) . '(<b>&nbsp;' . $reqby . '&nbsp;</b>)<br/><b>Designation  &nbsp;:&nbsp;</b>' . strtoupper($info_emp[0]['Designation']) . '<br/><b>Process &nbsp;:&nbsp;</b>' . $info_emp[0]['Process'] . '&nbsp;(&nbsp;' . $info_emp[0]['sub_process'] . '&nbsp;)<br /><b>Account Head &nbsp;:&nbsp;</b>' . $info_emp[0]['AccountHead'] . '<br /><b>Report To &nbsp;:&nbsp;</b>' . $info_emp[0]['ReportTo'] . '<br />' . $refferedBy;

							$mail->Body = $pwd_;
							if (!$mail->send()) {
								$emp = clean($_SESSION['__user_logid']);
								$mymsg .= '<div class="alert alert-success">Mailer Error:' . $mail->ErrorInfo . '</div>';

								$module = 'Happy to Help : Check Issue';
								$error_message = $mail->ErrorInfo;
								$error_log_add = "call Add_email_error_log('" . $module . "','" . $error_message . "','" . $emp . "')";
								$myDB = new MysqliDb();
								$myDB->query($error_log_add);
							} else {
								$emp = clean($_SESSION['__user_logid']);
								$mymsg .= '<div class="alert alert-success">Mail Send successfully.' . '</div>';

								$module = 'Happy to Help : Check Issue';
								$error_message = "email sent successfully";
								$error_log_add = "call Add_email_error_log('" . $module . "','" . $error_message . "','" . $emp . "')";
								$myDB = new MysqliDb();
								$myDB->query($error_log_add);
							}
						}
					}*/
				}
				?>
				<input type="hidden" name="hidID" id="hidID" value="<?php echo $reqid; ?>" />
				<input type="hidden" name="oldremark" id="oldremark" value="<?php echo $oldremark; ?>" />


				<div class="input-field col s12 m12 right-align">
					<a class="btn waves-effect waves-green" href="<?php echo URL . 'View/viewissue'; ?>">Request Page</a>
				</div>



				<?php
				// $myDB = new MysqliDb();
				$chktask = 'select distinct whole_dump_emp_data.EmployeeID,whole_dump_emp_data.emp_status,status,whole_dump_emp_data.EmployeeName,Process,sub_process,pd1.EmployeeName as `AccountHead`,pd2.EmployeeName as `ReportTo`,dept_name,designation ,date_format(whole_dump_emp_data.DOB,"%d %M,%Y") as DOB,date_format(whole_dump_emp_data.DOJ,"%d %M,%Y") as DOJ,mobile,altmobile,emailid,cm.client_name from whole_dump_emp_data left outer join contact_details cd on  cd.EmployeeID = whole_dump_emp_data.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = whole_dump_emp_data.account_head left outer join personal_details pd2 on pd2.EmployeeID = whole_dump_emp_data.ReportTo left outer join client_master cm on cm.client_id = whole_dump_emp_data.client_name where whole_dump_emp_data.EmployeeID = ?';
				$selQr = $conn->prepare($chktask);
				$selQr->bind_param("s", $reqby);
				$selQr->execute();
				$chk_task = $selQr->get_result();

				// $my_error = $myDB->getLastError();
				//var_dump($chk_task);	
				if ($chk_task->num_rows > 0) {
					$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><div class=""><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
					$table .= '<th>Employee ID</th>';
					$table .= '<th>Employee Name</th>';
					$table .= '<th>Account Head</th>';
					$table .= '<th>Mobile No.</th>';
					$table .= '<th>Alt. Mobile No.</th>';
					$table .= '<th>Email ID</th>';
					$table .= '<th>DOB</th>';
					$table .= '<th>DOJ</th>';
					$table .= '<th>Employee Status</th>';
					$table .= '<th>Cycle Status</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Client Name</th>';
					$table .= '<th>Department Name</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th>';
					$table .= '<th>ReportTo</th>';
					$table .= '<thead><tbody>';

					foreach ($chk_task as $key => $value) {

						$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td>' . $value['AccountHead'] . '</td>';
						$table .= '<td>' . $value['mobile'] . '</td>';
						$table .= '<td>' . $value['altmobile'] . '</td>';
						$table .= '<td>' . $value['emailid'] . '</td>';

						$table .= '<td>' . $value['DOB'] . '</td>';
						$table .= '<td>' . $value['DOJ'] . '</td>';
						$table .= '<td>' . $value['emp_status'] . '</td>';
						if ($value['status'] == 1) {
							$table .= '<td>In HR List</td>';
						} elseif ($value['status'] == 2) {
							$table .= '<td>In TH List</td>';
						} elseif ($value['status'] == 3) {
							$table .= '<td>In Training</td>';
						} elseif ($value['status'] == 4) {
							$table .= '<td>In OJT</td>';
						} elseif ($value['status'] == 5) {
							$table .= '<td>In OJT</td>';
						} elseif ($value['status'] == 6) {
							$table .= '<td>On Floor</td>';
						} else {
							$table .= '<td>-</td>';
						}
						$table .= '<td>' . $value['designation'] . '</td>';
						$table .= '<td>' . $value['client_name'] . '</td>';
						$table .= '<td>' . $value['dept_name'] . '</td>';
						$table .= '<td>' . $value['Process'] . '</td>';
						$table .= '<td>' . $value['sub_process'] . '</td>';
						$table .= '<td>' . $value['ReportTo'] . '</td>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Data Found') }); </script>";
				}
				?>

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {

		$("#addmorefields_div").hide();

		$('#status_txt').on('change', function() {
			if (this.value == "Payout Request") {
				$("#addmorefields_div").show();
			} else {
				$("#addmorefields_div").hide();
			}
		});


		//Model Assigned and initiation code on document load
		$('.modal').modal({
			onOpenStart: function(elm) {},
			onCloseEnd: function(elm) {
				$('#btn_can_payout').trigger("click");
			}
		});


		// This code for cancel button trigger click and also for model close
		$('#btn_can_payout').on('click', function() {
			$('#process').prop('readonly', false);
			$('#requirement').prop('disabled', false);
			$('#txt_dateFrom_updt').prop('readonly', false);
			$('#txt_dateTo_updt').prop('readonly', false);
			$('#ot_reason').prop('disabled', false);

			$('#btn_edit_payout').addClass('hidden');

			// This code for remove error span from input text on model close and cancel
			$(".has-error").each(function() {
				if ($(this).hasClass("has-error")) {
					$(this).removeClass("has-error");
					$(this).next("span.help-block").remove();
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}
					if ($(this).hasClass('select-dropdown')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}

				}
			});
			// This code active label on value assign when any event trigger and value assign by javascript code.
			$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
			$('select').formSelect();
		});

		// This code for remove error span from input text on model close and cancel
		$(".has-error").each(function() {
			if ($(this).hasClass("has-error")) {
				$(this).removeClass("has-error");
				$(this).next("span.help-block").remove();
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}
				if ($(this).hasClass('select-dropdown')) {
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}

			}
		});

		$("#btn_edit_payout").click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('#payout_days').val() == '') {
				$('#payout_days').addClass("has-error");
				if ($('#spanpayout_days').length == 0) {
					$('<span id="spanpayout_days" class="help-block">Required *</span>').insertAfter('#payout_days');
				}
				validate = 1;
			}

			if ($('#amount_type').val() == '') {
				$('#amount_type').addClass("has-error");
				if ($('#spanamount_type').length == 0) {
					$('<span id="spanamount_type" class="help-block">Required *</span>').insertAfter('#amount_type');
				}
				validate = 1;
			}
			if ($('#amount').val() == '') {
				$('#amount').addClass("has-error");
				if ($('#spanamount').length == 0) {
					$('<span id="spanamount" class="help-block">Required *</span>').insertAfter('#amount');
				}
				validate = 1;
			}

			if ($('#payout_status').val() == 'NA') {
				$('#payout_status').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spanpayout_status').length == 0) {
					$('<span id="spanpayout_status" class="help-block">Required *</span>').insertAfter('#payout_status');
				}
				validate = 1;
			}

			if ($('#payout_req_remark').val() == '') {
				$('#payout_req_remark').addClass("has-error");
				if ($('#spanpayout_req_remark').length == 0) {
					$('<span id="spanpayout_req_remark" class="help-block">Required *</span>').insertAfter('#payout_req_remark');
				}
				validate = 1;
			}
			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			}
		})
	})


	function EditData(el) {
		var tr = $(el).closest('tr');
		var payoutID = tr.find('.payoutID').text();
		var issue_date = tr.find('.issue_date').text();
		var issue_type = tr.find('.issue_type').text();
		var payout_days = tr.find('.payout_days').text();
		var amount_type = tr.find('.amount_type').text();
		var req_remark = tr.find('.req_remark').text();

		$('#hideID').val(payoutID);
		$('#issue_date').val(issue_date).prop("disabled", true);
		$('#issue_type').val(issue_type).prop("readonly", true);
		$('#payout_days').val(payout_days);
		$('#amount_type').val(amount_type);
		$('#req_remark').val(req_remark).prop("readonly", true);

		$('#btn_edit_payout').removeClass('hidden');
		$('#btn_can_payout').removeClass('hidden');
		$('#myModal_content').modal('open');

		// $('#btn_can_payout').click(function() {
		//     location.reload();
		// });


		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}
	$("#issue_datess").datetimepicker({
		timepicker: false,
		format: 'Y-m-d',
		scrollMonth: false,
	})

	// $("#addmorefields_div").hide();

	// $('#status_txt').on('change', function() {
	// 	if (this.value == "Payout Request") {
	// 		$("#addmorefields_div").show();
	// 	} else {
	// 		$("#addmorefields_div").hide();
	// 	}
	// });


	// function getThisVal(val) {
	// 	if (val.value == "Payout Request") {
	// 		$("#addmorefields_div").show();
	// 	} else {
	// 		$("#addmorefields_div").hide();

	// 	}

	// }

	$(function() {

		$('#btn_addfields').click(function() {
			let datepickerOptions = {
				timepicker: false,
				format: 'Y-m-d',
				scrollMonth: false,
			};
			var count = $(".trdoc").length;
			$("#rowcountid").val(count);
			var id = "trdoc_" + parseInt(count + 1);
			$('#issuesdata').val(parseInt(count + 1));
			var tr = $("#trdoc_1").clone().attr("id", id);
			$('#issuepayout tbody').append(tr);
			// tr.children("td:first-child").html(parseInt(count + 1));

			tr.children("td:nth-child(1)").children("input").attr({
				"id": "issue_date" + parseInt(count + 1),
				"name": "issue_date[]",
			}).val('').datetimepicker(datepickerOptions);

			tr.children("td:nth-child(2)").children("input").attr({
				"id": "issue_type" + parseInt(count + 1),
				"name": "issue_type[]" + parseInt(count + 1),
			}).val('');
			tr.children("td:nth-child(3)").children("input").attr({
				"id": "payout_days" + parseInt(count + 1),
				"name": "payout_days[]" + parseInt(count + 1),
			}).val('');


			tr.children("td:nth-child(4)").children("input").attr({
				"id": "amount_type" + parseInt(count + 1),
				"name": "amount_type[]" + parseInt(count + 1)
			}).val('');

			tr.children("td:nth-child(5)").children("input").attr({
				"id": "req_remark" + parseInt(count + 1),
				"name": "req_remark[]" + parseInt(count + 1)
			}).val('');

			changeevent();
		});
		$('#btn_removefields').click(function() {
			count = $(".trdoc").length;
			if (count > 1) {
				$('#issuepayout tbody').children("tr:last-child").remove();
				$('#issuesdata').val(parseInt(count - 1));
			}
		});
	});

	function checkRepeat(str) {
		var repeats = /(.)\1{3,}/;
		return repeats.test(str)
	}

	$(function() {
		$('#btnSave').click(function() {
			var validate = 0;
			var alert_msg = '';
			if ($('#status_txt').val() == 'Refer') {
				if ($('#refer_to').val() == "") {
					$(function() {
						toastr.error("Refer to should not empty");
					});
					return false;
				}
			}

			if ($('#status_txt').val() == 'Payout Request') {
				$(".trdoc").each(function() {
					var row = $(this);
					// console.log(row);
					var issueDate = row.find('input[name^="issue_date"]');
					var issueType = row.find('input[name^="issue_type"]');
					var payoutDays = row.find('input[name^="payout_days"]');
					var amountType = row.find('input[name^="amount_type"]');
					var reqRemark = row.find('input[name^="req_remark"]');

					if (issueDate.val() == '') {
						issueDate.addClass("has-error");
						if (issueDate.next('.help-block').length === 0) {
							$('<span class="help-block">Required *</span>').insertAfter(issueDate);
						}
						validate = 1;
					} else {
						issueDate.removeClass("has-error");
						issueDate.next('.help-block').remove();
					}

					if (issueType.val() == '') {
						issueType.addClass("has-error");
						if (issueType.next('.help-block').length === 0) {
							$('<span class="help-block">Required *</span>').insertAfter(issueType);
						}
						validate = 1;
					} else {
						issueType.removeClass("has-error");
						issueType.next('.help-block').remove();
					}

					if (amountType.val() == '') {
						amountType.addClass("has-error");
						if (amountType.next('.help-block').length === 0) {
							$('<span class="help-block">Required *</span>').insertAfter(amountType);
						}
						validate = 1;
					} else {
						amountType.removeClass("has-error");
						amountType.next('.help-block').remove();
					}

					if (payoutDays.val() == '') {
						payoutDays.addClass("has-error");
						if (payoutDays.next('.help-block').length === 0) {
							$('<span class="help-block">Required *</span>').insertAfter(payoutDays);
						}
						validate = 1;
					} else {
						payoutDays.removeClass("has-error");
						payoutDays.next('.help-block').remove();
					}

					if (reqRemark.val() == '') {
						reqRemark.addClass("has-error");
						if (reqRemark.next('.help-block').length === 0) {
							$('<span class="help-block">Required *</span>').insertAfter(reqRemark);
						}
						validate = 1;
					} else {
						reqRemark.removeClass("has-error");
						reqRemark.next('.help-block').remove();
					}
				});

				if (validate == 1) {
					$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
					$('#alert_message').show().attr("class", "SlideInRight animated");
					$('#alert_message').delay(50000).fadeOut("slow");
					return false;
				}
			}

			if ($('#remark').val().replace(/^\s+|\s+$/g, '') == "") {
				$(function() {
					toastr.error("Remark should not be empty");
				});
				return false;

			}
			if ($('#status_txt').val() == "NA") {
				$(function() {
					toastr.error("Select Atleast One");
				});
				return false;

			}
			if (checkRepeat($('#remark').val())) {
				$(function() {
					toastr.error("Remark should not contain Repeat character");
				});
				return false;
			}

			var remarkStr = $('#remark').val();
			if ((remarkStr.indexOf('>') >= 0) || (remarkStr.indexOf('<') >= 0)) {
				if ($('#sremark2').length == 0) {

					$(function() {
						toastr.error(' >  sign  and  <  sign not allow.');
					});
					return false;
				}
			}

		});

		$('#querysub').change(function() {
			var tval = $(this).val();
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getHandler.php?id=" + tval + "&loc=" + <?php echo '"' . $_SESSION['__location'] . '"'; ?>
			}).done(function(data) { // data what is sent back by the php page

				$('#handler').html(data);
				$('#handler').val('NA');
			});
		});
		$('#queryto').change(function() {
			var tval = $(this).val();

			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getIssue.php?id=" + tval + "&loc=" + <?php echo '"' . $_SESSION['__location'] . '"'; ?>
			}).done(function(data) { // data what is sent back by the php page

				$('#querysub').html(data);
				$('#querysub').val('NA');
			});
		});
		$('#alertdiv').delay(5000).fadeOut("slow");

		$('#status_txt').change(function() {

			if ($(this).val() == 'Refer') {
				$('#refer_div').removeClass('hidden');
			} else {
				$('#refer_div').addClass('hidden');
			}
		});
	});

	function submitform(emp_id, DateTo) {
		$('#p_EmpID').val(emp_id);
		$('#pdate').val(DateTo);
		document.getElementById('sendID').submit();
	}


	// $('#payout_date').datetimepicker({
	// 	timepicker: false,
	// 	multidate: true,
	// 	format: 'Y-m-d',
	// 	scrollMonth: false,
	// });
</script>
</form>
<form target='_blank' id='sendID' name='sendID' method='post' action='view_BioMetric_one.php' style="min-height: 5px;height: 5px;">
	<input type='text' name='p_EmpID' id='p_EmpID'>
	<input type='hidden' name='date' id='pdate'>
</form>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>