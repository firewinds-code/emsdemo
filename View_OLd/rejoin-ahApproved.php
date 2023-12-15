<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

//for email
// require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
// require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$last_to = $last_from = $last_to = $dept = $emp_nam = $status = '';
$classvarr = "'.byID'";
$searchBy = $Body = $tableContent = '';
$msg = '';
if (isset($_POST['btnSave'])) {
	echo 'sdfg';
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		function getEmailID($empid)
		{
			$myDB = new MysqliDb();
			$conn = $myDB->dbConnect();

			if ($empid != "") {
				// $email_array = $myDB->rawQuery("SELECT ofc_emailid,emailid from contact_details  t1 inner join employee_map t2 on t1.EmployeeID=t2.EmployeeID where t1.EmployeeID='" . $empid . "' and t2.emp_status='Active'");
				$email_arrayQry = "SELECT ofc_emailid,emailid from contact_details  t1 inner join employee_map t2 on t1.EmployeeID=t2.EmployeeID where t1.EmployeeID=? and t2.emp_status='Active'";
				// $error = $myDB->getLastError();
				// $rowCount = $myDB->count;
				$stmt = $conn->prepare($email_arrayQry);
				$stmt->bind_param("s", $empid);
				$stmt->execute();
				$email_array = $stmt->get_result();
				$email_arrayRow = $email_array->fetch_row();
				$rowCount = $stmt->num_rows();

				if ($email_array && $rowCount > 0) {
					if ($email_arrayRow[0] != "") {
						$employeeEmail = $email_arrayRow[0]; //['ofc_emailid'];
					}
					return $employeeEmail;
				}
			}
		}
		$AH_ID = $user_logid;
		if (isset($_POST['cb'])) {
			$checked_arr = $_POST['cb'];

			$count_check = count($checked_arr);
			if ($count_check > 0) {

				$max_key = max(array_keys($_POST['cb']));
				$min_key = min(array_keys($_POST['cb']));
				$tableContent = "";
				$OH_ARRAY = array();
				$AH_ARRAY = array();
				$HR_ARRAY = array();
				for ($p = $max_key; $p >= $min_key; $p--) {
					if (isset($checked_arr[$p])) {

						$dateofjoin = cleanUserInput($_POST['dateofjoin'])[$p];
						$AH_status = cleanUserInput($_POST['AH_status'])[$p];
						$client_name = cleanUserInput($_POST['client_name'])[$p];
						$process = cleanUserInput($_POST['process'])[$p];
						$subprocess = cleanUserInput($_POST['subprocess'])[$p];
						$employee_name = cleanUserInput($_POST['empname'])[$p];
						$empID = cleanUserInput($checked_arr[$p]);
						if ($empID != "") {
							$myDB = new MysqliDb();
							$save = 'call update_rejoined_employee("' . $empID . '","' . $AH_status . '","' . $AH_ID . '")';
							$resulti = $myDB->rawQuery($save);
							$mysql_error = $myDB->getLastError();
							$rowCount2 = $myDB->count;
							if ($rowCount2 > 0 && $AH_status == 'Approve') {
								$tableContent .= "<tr><td>" . $empID . "</td><td>" . $employee_name . "</td><td>" . $client_name . "</td><td>" . $process . "</td><td>" . $subprocess . "</td><td>" . $dateofjoin . "</td></tr>";
								$OH_ARRAY[] = cleanUserInput($_POST['oh'])[$p];
								$AH_ARRAY[] = cleanUserInput($_POST['account_head'])[$p];
								$HR_ARRAY[] = cleanUserInput($_POST['HR_ID'])[$p];
							}
						}
					}
				}
				if ($tableContent != "") {
					$myDB = new MysqliDb();
					$pagename = 'rejoin-ahApproved';
					$select_email_array = $myDB->rawQuery("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='" . $pagename . "'");

					$error = $myDB->getLastError();
					$rowCount = $myDB->count;
					$EMPID_ARRAY = array_unique(array_merge($OH_ARRAY, $AH_ARRAY, $HR_ARRAY));
					//$EMPID_ARRAY=array_merge($OH_ARRAY,$AH_ARRAY,$HR_ARRAY);
					$Subject_ = 'Rejoined Inactive Employee : ' . EMS_CenterName;
					$mail = new PHPMailer;
					$mail->isSMTP(); // Set mailer to use SMTP
					$mail->Host = EMAIL_HOST;
					$mail->SMTPAuth = EMAIL_AUTH;
					$mail->Username = EMAIL_USER;
					$mail->Password = EMAIL_PASS;
					$mail->SMTPSecure = EMAIL_SMTPSecure;
					$mail->Port = EMAIL_PORT;
					$mail->setFrom(EMAIL_FROM, 'EMS:Rejoin Inactive Employee');
					//  $mail->AddAddress('rinku.kumari@cogenteservices.in');	
					if ($rowCoun > 0) {
						foreach ($select_email_array as $key => $email_array) {
							$email_address = $email_array['email_address'];
							if ($email_address != "") {
								$mail->AddAddress($email_address);
							}
							$cc_email = $email_array['ccemail'];
							if ($cc_email != "") {
								$mail->addCC($cc_email);
							}
						}
					}

					for ($m = 0; $m < count($EMPID_ARRAY); $m++) {
						$emp_id = $EMPID_ARRAY[$m];
						$email_id = getEmailID($emp_id);
						$mail->addCC($email_id);
					}

					$mail->Subject = $Subject_;

					$Body .= "Hi all,<br>Below listed Employee has been rejoined with effected Date(" . date('d-m-Y') . ")<br><br>";
					$Body .= " <table border='1'>";
					$Body .= "<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Client Name</b></td><td><b>Process</b></td><td><b>Sub-Process</b></td><td><b>Joining Date</b></td></tr>";
					$Body .= $tableContent;
					$Body .= "</table><br><br>Thanks and Regards <br /> <b>Cogent  EMS </b>";
					$mail->isHTML(true);
					$mail->Body = $Body;
					if (!$mail->send()) {
						$lblMMAILmsg = 'Mail could not be sent.';
						$lblMMAILmsg = 'Mailer Error: ' . $mail->ErrorInfo;
						// echo $lblMMAILmsg;
					} else {
						$lblMMAILmsg = 'and Mail Sent successfully.';
					}
				}
				if ($rowCount2 > 0) {
					//$msg='<p class="text-success">Employee Updated Successfully...'. $lblMMAILmsg.'...</p>';
					echo "<script>$(function(){ toastr.success('Employee Updated Successfully " . $lblMMAILmsg . "'); }); </script>";
				} else {
					//$msg='<p class="text-danger">Employee Not Updated::Error :- <code>'.$mysql_error.'</code></p>';
					echo "<script>$(function(){ toastr.error('Employee Not Updated ::Error :- <code>" . $mysql_error . "</code>'); }); </script>";
				}
			} else {
				//$msg='<p class="text-danger">Employee Not Approved ::Error :- <code>No User Selected ....</code></p>';
				echo "<script>$(function(){ toastr.error('Employee Not Approved ::Error :- <code>No User Selected.</code>'); }); </script>";
			}
		} else {
			//$msg='<p class="text-danger">Employee Not Approved ::Error :- <code>No User Selected ....</code></p>';
			echo "<script>$(function(){ toastr.error('Employee Not Approved ::Error :- <code>No User Selected.</code>'); }); </script>";
		}
	}
}
?>
<script>
	$(document).ready(function() {
		$('.statuscheck').addClass('hidden');
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');

		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');
		$('#searchBy').change(function() {
			$('.byID').addClass('hidden');
			if ($(this).val() == 'By ID') {
				$('.byID').removeClass('hidden');
			}
		});
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Approve Rejoin Inactive Employee</span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Approve Rejoin Inactive Employee </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12 right-align ">
					<button type="submit" value="Submit" name="btnSave" id="btnSave1" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Submit</button>
				</div>
				<!--<div class="input-field col s6 m6 ">
						<button type="button" value="Cancel" name="btnCan" id="btnCan"  class="btn waves-effect waves-red">Cancel</button>
				</div>-->


				<div id="pnlTable">
					<?php
					$AH_ID = $user_logid;

					// define("P_AH_ID",  $AH_ID);
					$sqlConnect = 'call get_rejoin_empList("' . $AH_ID . '")';
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($rowCount > 0) { ?>
						<div class="had-container pull-left row card dataTableInline">
							<div class="">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">Employee</label></th>
											<th class="hidden">EmployeeID</th>
											<th>EmployeeName</th>
											<th>JoiningDate</th>
											<th>RelevingDate</th>
											<th>Designation</th>
											<th>ClientName</th>
											<th>Process</th>
											<th>SubProcess</th>
											<th>AccountHead</th>
											<th>ReportTo</th>
											<th>Reason of Leaving</th>
											<th>HR Comment </th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										$i = 0;
										foreach ($result as $key => $value) {
											$i++;
											$doj_date = "";
											$dol_date = "";

											$dol_date = ((!empty($value['dol'])) ? date('Y-m-d', strtotime($value['dol'])) : '');
											$doj_date = ((!empty($value['DOJ'])) ? date('Y-m-d', strtotime($value['DOJ'])) : '');
											echo '<tr>';
											echo '<td class="EmpId aaaa"><input type="checkbox" id="cb' . $i . '" class="cb_child" name="cb[' . $count . ']" value="' . $value['EmployeeID'] . '"><label for="cb' . $i . '" style="color: #059977;font-size: 14px;font-weight: bold;}">' . $value['EmployeeID'] . '</label></td>';
											echo '<td class="EmployeeID hidden aaaa"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
											echo '<td class="EmployeeName aaaa"  id="empname' . $count . '" >' . $value['EmployeeName'] . '</td>';
											echo '<td class="doj aaaa" id="doj' . $count . '"  >' . $doj_date . '</td>';
											echo '<td class="dol_date  aaaa" id="dol_date' . $count . '"  >' . $dol_date . '</td>';
											echo '<td class="designation  aaaa" id="designation' . $count . '"  >' . $value['designation'] . '</td>';
											echo '<td class="clientname  aaaa" id="clientname' . $count . '">' . $value['clientname'] . '</td>';
											echo '<td class="Process  aaaa" id="Process' . $count . '">' . $value['Process'] . '</td>';
											echo '<td class="sub_process  aaaa" id="sub_process' . $count . '">' . $value['sub_process'] . '</td>';
											echo '<td class="AccountHead aaaa" id="AccountHead' . $count . '">' . $value['AccountHead'] . '</td>';
											echo '<td class="ReportsTo aaaa" id="ReportsTo' . $count . '">' . $value['ReportsTo'] . '</td>';
											echo '<td class="rsnofleaving aaaa" id="rsnofleaving' . $count . '">' . $value['rsnofleaving'] . '</td>';
										?>
											<input type='hidden' name="dateofjoin[<?php echo $count; ?>]" value="<?php echo $doj_date; ?>">
											<input type='hidden' name="empname[<?php echo $count; ?>]" value="<?php echo $value['EmployeeName']; ?>" class='EmployeeName_h'>
											<input type='hidden' name="client_name[<?php echo $count; ?>]" value="<?php echo $value['clientname']; ?>">
											<input type='hidden' name="process[<?php echo $count; ?>]" value="<?php echo $value['Process']; ?>">
											<input type='hidden' name="subprocess[<?php echo $count; ?>]" value="<?php echo $value['sub_process']; ?>">
											<td class="comment aaaa" style="padding: 0px;max-height: 30px;min-height: 30px;"><textarea readonly name='ah_comment[<?php echo $count; ?>]' id="comment<?php echo  $count; ?>" class="materialize-textarea ahcomment "><?php echo  $value['hr_comment']; ?></textarea></td>
											<td class="active_status aaaa" style="padding: 0px;max-height: 30px;min-height: 30px;">
												<select name="AH_status[<?php echo $count; ?>]" id="status<?php echo  $count; ?>" class="form-control AH_statusc">
													<option value="NA">Select Status</option>
													<option value="Approve" <?php if ($value['AH_status'] == 'Approve') { ?> selected <?php } ?>>Approve</option>
													<option value="Reject" <?php if ($value['AH_status'] == 'Reject') { ?> selected <?php } ?>>Reject</option>
												</select>
											</td>
											<input type='hidden' name="account_head[<?php echo $count; ?>]" value="<?php echo $value['account_head']; ?>">
											<input type='hidden' name="oh[<?php echo $count; ?>]" value="<?php echo $value['oh']; ?>">
											<input type='hidden' name="HR_ID[<?php echo $count; ?>]" value="<?php echo $value['HR_ID']; ?>">
										<?php
											echo '</tr>';
											$count++;
										}
										?>
										<script>
											$("input:checkbox").click(function() {
												if ($('input.cb_child:checkbox:checked').length > 0) {
													var tcount = $('input.cb_child:checkbox:checked').length;
													checklistdata();
												} else {
													$('.statuscheck').addClass('hidden');
													$('#docTable').html('');
													$('#docstable').addClass('hidden');
												}
											});
										</script>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					} else {
						//echo '<div id="div_error" class="slideInDown animated hidden">Data Not Found (May be You Not Have Any Employee Assigned ):: <code >'.$error.'</code> </div>';
						echo "<script>$(function(){ toastr.info('Data Not Found.(May be You Not Have Any Employee Assigned.).<code >" . $error . "</code>'); }); </script>";
					}


					?>

				</div>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(5000).fadeOut("slow");
		}
		$('#btnSave1').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				alert_msg += '<li> Check Atleast One Employee ....  </li>';
			} else {
				var inputElements = document.getElementsByClassName('cb_child');
				var AH_statusc = document.getElementsByClassName('AH_statusc');
				var EmployeeName = document.getElementsByClassName('EmployeeName_h');
				for (var i = 0; inputElements[i]; ++i) {
					if (inputElements[i].checked) {
						AH_status = AH_statusc[i].value.trim();
						empname = EmployeeName[i].value.trim();
						if (AH_status == "NA") {
							validate = 1;
							alert_msg += '<li> Please select status of ' + empname + '</li>';
							break;
						}
					}
				}
			}

			if (validate == 1) {

				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			}
		});
		$('#btnCan').click(function() {
			$("input:checkbox").prop('checked', false);
			$('.statuscheck').addClass('hidden');
			$('#docTable').html('');
			$('#docstable').addClass('hidden');
		});
		$("input:checkbox").click(function() {
			if ($('input:checkbox:checked').length > 0) {
				checklistdata();
			} else {

				$('.statuscheck').addClass('hidden');
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
			}
		});
		$('#div_error').removeClass('hidden');
		$("#cbAll").change(function() {
			$("input:checkbox").prop('checked', $(this).prop("checked"));
		});
		$("input:checkbox").change(function() {
			if ($('input.cb_child:checkbox:checked').length > 0) {
				checklistdata();
				if ($('input.cb_child:checkbox:checked').length == $('input.cb_child:checkbox').length) {

					$("#cbAll").prop("checked", true);
				} else {
					$("#cbAll").prop("checked", false);
				}
			} else {
				$("#cbAll").prop("checked", false);

				$('.statuscheck').addClass('hidden');
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
			}
		});


	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');

	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>