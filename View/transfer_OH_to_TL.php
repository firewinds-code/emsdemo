<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// used for email
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
if (isset($_SESSION)) {
	//echo "cm_id= ".$_SESSION["__cm_id"];
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		echo "<script>location.href='" . $location . "'</script>";
		//header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	//header("Location: $location");
	echo "<script>location.href='" . $location . "'</script>";
}
$movedate_new = '';
$Body = "";
$cm_id = "";
if (isset($_GET['cm_id']) && $_GET['cm_id'] != '') {
	$cm_id = $_GET['cm_id'];
}
$tableContent = "";
$old_client = $status = $comment = '';
$classvarr = "'.byID'";
$searchBy = '';
$updatedBy = $_SESSION['__user_logid'];
$client_name = '';
$flag = "";

$msg = '';

if (isset($_POST['transfer_client'])) {

	$counttol = 0;
	$status = $_POST['status'];
	$new_process = $_POST['new_process'];
	$comment = $_POST['comment'];
	$tcid_array = $_POST['tcid'];
	$cdate = date('d');
	if ($cdate >= 25 && $cdate <= 30) {
		if (isset($_POST['tcid'])) {
			$date = date("Y-m-d h:i:s");

			$checked_arr = $_POST['tcid'];
			$new_reportsto = $_POST['new_reportsto'];
			$count_check = count($status);
			if ($new_process != "") {
				$max_key = max(array_keys($_POST['EmployeeName']));
				$min_key = min(array_keys($_POST['EmployeeName']));
				for ($i = $max_key; $i >= $min_key; $i--) {
					if (isset($checked_arr[$i]) && $checked_arr[$i] != "" && $comment[$i] != "") {
						$empID = $checked_arr[$i];
						$moveid = $_POST['moveid'][$i];
						$movedate = $_POST['txt_movedate'][$i];
						if ($status[$i] == 'OHReject') {
							$flag = 'OHR';
						} else
					if ($status[$i] == 'OHApprove') {
							$flag = 'toNRT';
						} else
					if ($status[$i] == 'Pending') {
							$flag = 'toOH';
						}
						if ($flag != "") {

							$save = "UPDATE tbl_tl2_tl_movement set OH_comment='" . $comment[$i] . "',status='" . $status[$i] . "',flag='" . $flag . "',OH_UpdatedOn='" . $date . "',Updated_by='" . $updatedBy . "',updated_on='" . $date . "',OH_updated_by='" . $updatedBy . "',move_date='" . $movedate . "',new_ReportsTo='" . $new_reportsto . "' where EmployeeID='" . $checked_arr[$i] . "' and id='" . $moveid . "'";
							if ($flag == 'toNRT') {

								$EmpInfoQuery = "select EmployeeName , concat(clientname,' | ',Process,' | ',sub_process) AS Client from whole_details_peremp where EmployeeID='" . $checked_arr[$i] . "'";
								$myDB = new MysqliDb();
								$data_array = $myDB->rawQuery($EmpInfoQuery);
								$mysql_error = $myDB->getLastError();
								$rowCount = $myDB->count;

								if ($rowCount > 0) {

									$tableContent .= "<tr><td>" . $checked_arr[$i] . "</td><td>" . $data_array[0]['EmployeeName'] . "</td><td>" . $data_array[0]['Client'] . "</td><td>" . $movedate . "</td></tr>";
								}
							}
							$myDB = new MysqliDb();
							$data_array = $myDB->rawQuery($save);
							$mysql_error = $myDB->getLastError();
							$rowCount = $myDB->count;
							if ($rowCount > 0) {
								$counttol++;
							}
						}
					}
				}
				//echo $tableContent;
				if ($flag == 'toNRT') {
					if (($counttol > 0) && empty($mysql_error)) {

						$sender_data = "SELECT ofc_emailid,emailid  FROM contact_details  where EmployeeID='" . $new_reportsto . "'";
						$sender_AH = "";
						$sender_OH = "";
						$EmailTo = "";
						$email_array = array();
						$myDB = new MysqliDb();
						$data_array = $myDB->rawQuery($sender_data);
						$mysql_error = $myDB->getLastError();
						$rowCount = $myDB->count;
						if ($rowCount > 0) {

							if ($data_array[0]['ofc_emailid'] != "") {
								$EmailTo = $data_array[0]['ofc_emailid'];
							}
						}


						$pagename = 'transfer_OH_to_TL';
						$select_email_array = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='" . $pagename . "'";
						$myDB = new MysqliDb();
						$emaildata = $myDB->rawQuery($select_email_array);
						$mysql_error = $myDB->getLastError();
						$rowCount = $myDB->count;
						/**
						Coding for Send Email
						 */
						$Subject_ = 'Employee Movement(ReportsTo) Noida : Notification for approve the employee';
						$mail = new PHPMailer;
						$mail->isSMTP(); // Set mailer to use SMTP
						$mail->Host = 'mail.cogenteservices.in';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true; // Enable SMTP authentication
						$mail->Username = 'ems@cogenteservices.in'; // SMTP username
						$mail->Password = '987654321'; // SMTP password*/
						$mail->SMTPSecure = 'TLS'; // Enable TLS encryption, `ssl` also accepted
						/*$mail->Port = 587;*/
						$mail->Port = 25;
						$mail->setFrom('ems@cogenteservices.in', 'EMS:Employee Movement');
						//$mail->AddAddress('rinku.kumari@cogenteservices.in');
						$mail->AddAddress($EmailTo);
						$mail->Subject = $Subject_;
						if ($rowCount > 0) {
							foreach ($emaildata as $key => $email_array) {

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
						$Body .= "Hi Team,<br>Following TL to TL movement has been initiated. Please act accordingly<br><br>
				        <table border='1'>";
						$Body .= "<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Sub-Process Info</b></td><td><b>Move Date</b></td></tr>";
						$Body .= $tableContent;
						$Body .= "</table><br><br>Thanks EMS Team";
						$mail->isHTML(true);

						$mail->Body = $Body;
						if (!$mail->send()) {
							$lblMMAILmsg = 'Mail could not be sent.';
							$lblMMAILmsg = 'Mailer Error: ' . $mail->ErrorInfo;
						} else {
							$lblMMAILmsg = 'and Mail Sent to new ReportsTo.';
						}

						// $msg='<p class="text-success">Data Updated Successfully...'.$lblMMAILmsg.'</p>';
						echo "<script>$(function(){ toastr.success('Data Updated Successfully. " . $lblMMAILmsg . "'); }); </script>";
					} else {
						//$msg='<p class="text-danger">Data Not Updated ::Error :- <code>'.$mysql_error.'</code></p>';
						echo "<script>$(function(){ toastr.error('Data Not Updated :-" . $mysql_error . "'); }); </script>";
					}
				}
			}
		}
	} else {
		echo "<script>$(function(){ toastr.error('You can update between 25th to 30th of the month'); }); </script>";
	}
}
?>

<script>
	$(document).ready(function() {

		var movedate = $('#movedate2').val();

		$('#move_date').val(movedate);
		// ; //var formatted = $.datepicker.formatDate("M d, yy", new Date(movedate));
		//alert(formatted);
		var date = new Date();
		date.setDate(date.getDate() + 4);
		var newdate = date;
		$('#move_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false,
			minDate: new Date(movedate)
		});
		$('.statuscheck').addClass('hidden');

		//$('.txt_move_date').datetimepicker({ format:'Y-m-d', timepicker:false});
		//$('#txt_ED_joindate_from').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollX: '100%',
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
		$('.byDate').addClass('hidden');
		$('.byDept').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee Movement : Sub-Process to Sub-Process </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Movement : Sub-Process to Sub-Process </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div class="input-field col s6 m6 8">
					<?php
					$sqlBy = "SELECT distinct b.process,b.cm_id,concat(b.clientname,'|',b.process,'|',b.sub_process) AS Client FROM tbl_tl2_tl_movement a Inner Join  whole_details_peremp b on  a.cm_id=b.cm_id   where b.oh='" . $_SESSION['__user_logid'] . "' and a.status='Pending' and a.flag='toOH'";
					$myDB = new MysqliDb();
					$resultBy = $myDB->rawQuery($sqlBy);
					$mysql_error = $myDB->getLastError();
					$rowCount = $myDB->count;
					?>
					<select id="queryfrom" name="new_process">
						<option value="NA">----Select----</option>
						<?php

						if ($resultBy) {
							$selec = '';
							//print_r($resultBy);
							foreach ($resultBy as $key => $value) {
								$select = '';
								if ($cm_id != '' && $value['cm_id'] == $cm_id) {
									$select = "selected";
								}
								echo '<option value="' . $value['cm_id'] . '"  ' . $select . ' >' . $value['Client'] . '</option>';
							}
						}

						?>
					</select>
					<label for="txt_Client_ach" class="active-drop-down active">To Sub-Process</label>
				</div>

				<div class="input-field col s6 m6 ">
					<?php
					if (isset($_GET['cm_id']) && $_GET['cm_id'] != '') {
						$cm_id = $_GET['cm_id'];

						$sqlBy = "select h.EmployeeID,h.EmployeeName,h.cm_id,h.designation from whole_details_peremp h where h.des_id not in (9,12,33,34,35,36) and h.cm_id='" . $cm_id . "'";
						$myDB = new MysqliDb();
						$resultBy = $myDB->rawQuery($sqlBy);
						$mysql_error = $myDB->getLastError();
						$rowCount = $myDB->count;
					?>
						<select id="new_reportsto" name="new_reportsto">
							<option value="">----Select----</option>
							<?php

							if ($rowCount > 0) {

								foreach ($resultBy as $key => $value) {
									$select = '';

									echo '<option value="' . $value['EmployeeID'] . '"  ' . $select . ' >' . $value['EmployeeName'] . '(' . $value['designation'] . ')</option>';
								}
							}

							?>
						</select>
						<label for="txt_Client_ach" class="active-drop-down active">To ReportsTo</label>
					<?php } ?>

				</div>

				<div class="input-field col s12 m12 statuscheck right-align">
					<!--Update Move Date :-->
					<input type="hidden" name="move_date" id="move_date" value="" readonly />
					<button type="submit" name="transfer_client" id="client_action" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green  ">Update</button>
				</div>

				<div id="pnlTable">
					<?php
					$sqlConnect = " select a.EmployeeID, a.EmployeeName,a.designation,a.emp_level,concat(a.clientname,' | ',a.Process,' | ',a.sub_process) as Client,DATE_FORMAT(b.move_date,'%Y-%m-%d') AS move_date,b.id as moveid,b.status,b.OH_comment  ,DATE_FORMAT(b.created_on,'%Y-%m-%d') AS created_on from whole_details_peremp a,tbl_tl2_tl_movement b where a.EmployeeID=b.EmployeeID and b.cm_id='" . $cm_id . "' and b.flag='toOH' and b.status='Pending'";
					$myDB = new MysqliDb();
					$resultBy = $myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					$rowCount = $myDB->count;

					if ($rowCount > 0) { ?>

						<div class="had-container pull-left row card dataTableInline ">
							<div class="">
								<table id="myTable" class="data dataTable no-footer row-border " cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Serial No.</th>
											<th><input type="checkbox" name="cbAll" id="cbAll" value="ALL"><label for="cbAll">Employee</label></th>
											<th class="hidden">EmployeeID</th>
											<th>EmployeeName</th>
											<th>Designation</th>
											<th>Level</th>
											<th>Current Process</th>
											<th>Move Date</th>
											<th>Transfer Date</th>
											<th>Status</th>
											<th>Comment</th>
										</tr>
									</thead>
									<tbody id="emplist">
										<?php
										$count = $rowCount;
										$i = 0;
										$j = 1;
										foreach ($resultBy as $key => $data_array) {
											$ohComment = "";
											if ($data_array['OH_comment'] != "") {
												$ohComment = $data_array['OH_comment'];
											}
											echo '<tr>';
											echo "<td  >" . $j . "</td>";
											echo '<td class="EmpId"><input type="checkbox" id="cb' . $i . '" class="cb_child" name="tcid[' . $i . ']" value="' . $data_array['EmployeeID'] . '"><label for="cb' . $i . '" >' . $data_array['EmployeeID'] . '</label></td>';
											echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  class="ckeckdata" data="' . $data_array['EmployeeID'] . '">' . $data_array['EmployeeID'] . '</a></td>';
											echo '<td class="client_name">' . $data_array['EmployeeName'] . '</td>';
											echo '<td class="designation">' . $data_array['designation'] . '</td>';
											echo '<td class="emp_level">' . $data_array['emp_level'] . '</td>';
											echo '<td class="Client">' . $data_array['Client'] . '</td>';
											echo '<td class="move_date">
										<input class="txt_move_date" type="text" name="txt_movedate[' . $i . ']" value="' . $data_array['move_date'] . '" readonly /></td>';
											echo '<td class="created_on">' . $data_array['created_on'] . '</td>';

										?>
											<input class='empclass' type='hidden' name='EmployeeName[<?php echo $i; ?>]' value="<?php echo $data_array['EmployeeName']; ?>">
											<input type="hidden" name="moveid[<?php echo  $i; ?>]" id="moveid<?php echo  $i; ?>" class="moveid" value="<?php echo $data_array['moveid']; ?>">
											<td class="active_status">
												<select name="status[<?php echo $i; ?>]" id="status<?php echo  $i; ?>">
													<option value="Pending" <?php if ($data_array['status'] == 'Pending') { ?> selected <?php } ?>>Pending</option>
													<option value="OHReject" <?php if ($data_array['status'] == 'OHReject') { ?> selected <?php } ?>>Reject</option>
													<option value="OHApprove" <?php if ($data_array['status'] == 'OHApprove') { ?> selected <?php } ?>>Approve</option>
												</select>
											</td>
											<td class="comment"><textarea name='comment[<?php echo $i; ?>]' id="comment<?php echo  $i; ?>" class="materialize-textarea materialize-textarea-size ahcomment"><?php echo  stripcslashes($ohComment); ?></textarea></td>

										<?php
											echo '</tr>';
											$i++;
											$j++;
										}


										?>
										<script>
											$("input:checkbox").click(function() {
												if ($('input:checkbox:checked').length > 0) {
													checklistdata();
												} else {
													$('#client_to').val('No');
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
						echo "<script>$(function(){ toastr.error('Please select your subprocess.'); }); </script>";
					}


					?>

				</div>
			</div>
			<!--<input type='hidden' name='movedate2' id="movedate2" value="<?php echo date('Y-m-d', strtotime($movedate_new)); ?>" >	-->
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
		$('.statuscheck').addClass('hidden');
		$('#client_action').click(function() {
			var validate = 0;
			var alert_msg = '';
			var currentTime = new Date()
			var day = currentTime.getDate()
			if (!(day >= 25 && day <= 30)) {

				validate = 1;
				alert_msg = '<li>You can assign between 25th to 30th  of the month </li>';
			}
			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				alert_msg += '<li> Check Atleast On Employee ....  </li>';
			} else {
				if ($('#new_reportsto').val() == "") {
					validate = 1;
					alert_msg += '<li> Please select new reports to  </li>';
				}
				var checkedValue = null;
				var inputElements = document.getElementsByClassName('cb_child');
				var hrComment = document.getElementsByClassName('ahcomment');
				var empclass = document.getElementsByClassName('empclass');
				for (var i = 0; inputElements[i]; ++i) {
					if (inputElements[i].checked) {
						checkedValue = hrComment[i].value.trim();
						var empname = empclass[i].value.trim();
						if (checkedValue == "") {
							validate = 1;
							alert_msg += '<li> Write the comment for ' + empname + '  </li>';
							break;
						} else {
							var statusval = document.getElementById('status' + i).value;
							if (statusval == 'Pending') {
								validate = 1;
								alert_msg += '<li> Please change the status for ' + empname + '  </li>';
								break;
							}
						}

					}
				}

			}

			if (validate == 1) {
				//$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				//$('#alert_message').show().attr("class","SlideInRight animated");
				//$('#alert_message').delay(5000).fadeOut("slow");

				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			}
			onclick = "return confirm('Are you want to proceed?');"

		});
		$('#btnCan').click(function() {
			$("input:checkbox").prop('checked', false);
			$('#client_to').val('NA');
			$('.statuscheck').addClass('hidden');
			$('#docTable').html('');
			$('#docstable').addClass('hidden');
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
				$('#client_to').val('NA');
				$('.statuscheck').addClass('hidden');
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
			}
		});
		$('#client_to').change(function() {
			var tolientid = $('#client_to').val();
			//alert( tolientid);
			if (tolientid == 'NA') {
				$('#transfer_client').addClass('hidden');
			} else {
				$('#transfer_client').removeClass('hidden');
			}
		});
		$('#queryfrom').change(function() {
			var tval = $(this).val().trim();

			if (tval != "") {
				location.href = 'transfer_OH_to_TL.php?cm_id=' + tval;
			}
		});
	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');

	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>