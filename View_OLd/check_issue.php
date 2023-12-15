<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $Status1 = 0;
//Mailer function
// require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
// require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
$user_logid = clean($_SESSION['__user_logid']);
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_SESSION)) {
	if (!isset($user_logid) || $user_logid == "") {
		$location = URL . 'Login';
		header("Location: $location");
		exit;
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit;
}
$oldremark = $reqid = $mymsg = '';
$reqby = $referEmailID = $refferedBy = '';
// $IDDSAad = isset($_REQUEST['ID*DSAad']);
$DSAad = clean($_REQUEST['ID*DSAad']);
$DSAadArr = explode("_", $DSAad);
// echo "<pre>";
// print_r($DSAadArr);
$urlId = base64_decode($DSAadArr[1]);
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
$btnsave = isset($_POST['btnSave']);
if ($btnsave) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		if ($_POST['status_txt'] == 'InProgress') {
			$rmk = cleanUserInput($_POST['remark']);
			$remark = '(' . date('Y-m-d h:i:s') . ')  : ' . $rmk;
			$oldremark = cleanUserInput($_POST['oldremark']);
			$createdby = clean($_SESSION['__user_logid']);
			$user_name = clean($_SESSION['__user_Name']);
			$myDB = new MysqliDb();
			//$query='call inprog_issueticket("'.$oldremark.' | '.$remark.'","'.$reqid.'","'.$_SESSION['__user_Name'].'");';
			$query = 'call inprog_issueticket("' . addslashes(trim($oldremark)) . ' | ' . addslashes(trim($remark)) . '","' . $reqid . '","' . $user_name . '");';
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
			$remark = '(' . date('Y-m-d h:i:s') . ')  : ' . $_POST['remark'];
			$oldremark = cleanUserInput($_POST['oldremark']);
			$createdby = clean($_SESSION['__user_logid']);

			$refertto = 'NA';
			$refer_to = isset($_POST['refer_to']);
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
			//$query='call check_issueticket("'.$oldremark.' | '.$remark.'","'.$reqid.'","'.$_POST['status_txt'].'","'.$refertto.'","'.$_SESSION['__user_Name'].'");';
			$status_txt = cleanUserInput($_POST['status_txt']);
			$user_name = clean($_SESSION['__user_Name']);
			$query = 'call check_issueticket("' . addslashes(trim($oldremark)) . ' | ' . addslashes(trim($remark)) . '","' . $reqid . '","' . $status_txt . '","' . $refertto . '","' . $user_name . '");';
			$result = $myDB->query($query);
			//echo $query;
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Issue Request Submitted, Thank You.') }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Query Not Submitted " . addslashes($mysql_error) . "') }); </script>";
			}
		}
	}
}
?>

<script>
	$(function() {
		$('#myTable').DataTable({
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

							if ($value['status'] == 'Pending' || $value['status'] == 'Reopen' || $value['status'] == 'InProgress') {
								echo '<div class="input-field col s3 m3">Request Status </div>
										<div class="input-field col s9 m9"><b> ' . $value['status'] . ' </b></div>';

								$req_remark = $value['requester_remark'];
								echo '<div class="input-field col s3 m3">Requester Remark </div>
										<div class="input-field col s9 m9"><b> ' . $value['requester_remark'] . ' </b></div>';

								echo '<div class="input-field col s3 m3">Handler Remark </div>
										<div class="input-field col s9 m9"><b> ' . $value['handler_remark'] . ' </b></div>';

								echo '</div>';
				?>
								<div class="input-field col s12 m12">

									<select name="status_txt" id="status_txt">
										<option value="InProgress">InProgress</option>
										<option value="Resolve">Resolve</option>
										<option value="Refer">Refer</option>
									</select>
									<label for="status_txt" class="active-drop-down active">Status</label>
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
													<option>InProgress</option>
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
					$btnsaves = isset($_POST['btnSave']);
					if ($btnsaves) {

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
					}
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

			if ($('#remark').val().replace(/^\s+|\s+$/g, '') == "") {

				$(function() {
					toastr.error("Remark should not be empty");
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

			/*if($('#remark').val().length < 250)
	        {
			
				
				  $(function(){ toastr.error('Remark should be greater than 250 character'); });
				   	return false;
			}*/

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
</script>
</form>
<form target='_blank' id='sendID' name='sendID' method='post' action='view_BioMetric_one.php' style="min-height: 5px;height: 5px;">
	<input type='text' name='p_EmpID' id='p_EmpID'>
	<input type='hidden' name='date' id='pdate'>
</form>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>