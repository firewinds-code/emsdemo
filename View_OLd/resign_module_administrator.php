<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$status = '';
$batch_id = '';
$classvarr = "'.byID'";
$searchBy = '';
$msg = '';
$btnsave = isset($_POST['btnSave']);
if ($btnsave) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$createBy = $_SESSION['__user_logid'];
		$cb = isset($_POST['cb']);
		if ($cb) {
			$checked_arr = $_POST['cb'];
			$count_check = count($checked_arr);
			if ($count_check > 0) {
				$myDB = new MysqliDb();

				foreach ($checked_arr as $val) {
					$mysql_error = '';
					$Counter = $val;
					$EmpID = cleanUserInput($_POST['txt_EmployeeID_' . $Counter]);
					$Status = cleanUserInput($_POST['txt_Status_' . $Counter]);
					$nt_start = cleanUserInput($_POST['nt_start_' . $Counter]);
					$nt_end = cleanUserInput($_POST['nt_end_' . $Counter]);


					if ($Status != 'NA' || $Status != 'Na') {

						$result_del = ('DELETE from alert_details where EmployeeID =? and ((type like "RESIGN Accept%") or (type like "RESIGN Reject%"))');

						$stmt = $conn->prepare($result_del);
						$stmt->bind_param("s", $EmpID);
						$delt = $stmt->execute();
						$error = $myDB->getLastError();

						if ($Status == 'Accept') {
							$Status = 1;
							$sql = 'UPDATE resign_details set accept = ? ,rg_status=1,nt_start=?,nt_end=? where EmployeeID = ?';

							$stmt = $conn->prepare($sql);
							$stmt->bind_param("ssss", $Status, $nt_start, $nt_end, $EmpID);
							$result_upd = $stmt->execute();
							// 
							// $myDB = new MysqliDb();
							// $result_upd = $myDB->rawQuery($sql);
							$mysql_error .= $myDB->getLastError();
						} else {
							$Status = 2;
							$sql = 'UPDATE resign_details set accept =? ,rg_status = 9,nt_start=?,nt_end=? where EmployeeID = ?';
							$stmt = $conn->prepare($sql);
							$stmt->bind_param("ssss", $Status, $nt_start, $nt_end, $EmpID);
							$result_upd = $stmt->execute();
						}

						if ($Status == 1) {
							$result = 'SELECT nt_start,nt_end  from resign_details where EmployeeID =? and rg_status = 1 and accept = 1 order by accept_time desc limit 1;';

							$stmt = $conn->prepare($result);
							$stmt->bind_param("s",  $EmpID);
							$result2 = $stmt->execute();

							$mysql_error = $myDB->getLastError();
							$request_for = 'RESIGN Accept|' . $result2[0]['nt_start'] . '|' . $result2[0]['nt_end'];


							// 
							$time = date('Y-m-d H:i:s', time());

							$resul = 'INSERT INTO alert_details(EmployeeID,alert_start,alert_end,type,createdon,createdby)VALUES(?,?,?,?,?,?)';

							$stmt = $conn->prepare($resul);
							$stmt->bind_param("ssssss",  $EmpID, $result2[0]['nt_start'], $result2[0]['nt_end'], $request_for, $time, $createBy);
							$result3 = $stmt->execute();

							$mysql_error .= $myDB->getLastError();
						} else {
							$result = 'SELECT nt_start,nt_end  from resign_details where EmployeeID = ? and rg_status = 9 and accept = 2 order by accept_time desc limit 1;';
							$stmt = $conn->prepare($result);
							$stmt->bind_param("s",  $EmpID);
							$result2 = $stmt->execute();

							$request_for = 'RESIGN Reject|' . $result2[0]['nt_start'] . '|' . $result2[0]['nt_end'];

							$date = date('Y-m-d', time());
							$strtime = date('Y-m-d', strtotime('+3 days'));
							$time = date('Y-m-d H:i:s', time());
							// 
							$result33 = 'INSERT INTO alert_details(EmployeeID,alert_start,alert_end,type,createdon,createdby)VALUES(?,?,?,?,?,?)';

							$stmt = $conn->prepare($result33);
							$stmt->bind_param("ssssss",  $EmpID, $date, $strtime, $request_for, $time, $createBy);
							$result2 = $stmt->execute();


							$mysql_error .= $myDB->getLastError();
						}
						//echo 'update resign_details set accept = "'.$Status.'" ,accept_time = now(),rg_status=9,accepter_remark ="'.$Remark.'",accepter ="'.$createBy.'" where EmployeeID = "'.$EmpID.'" and rg_status = 0';

						/*$sql='update resign_details set accept = "'.$Status.'" ,accept_time = now(),rg_status=9,accepter_remark ="'.$Remark.'",accepter ="'.$createBy.'" where EmployeeID = "'.$EmpID.'" and rg_status = 0';
						
						$resulti = $myDB->rawQuery($sql);
						$mysql_error=$myDB->getLastError();	
						if($result3)
						{
							$msg='<p class="text-success">Request Updated Successfully...</p>';
														
						}
						else
						{
							$msg='<p class="text-danger">Request Not Updated ::Error :- <code>'.$mysql_error.'</code></p>';
						}*/
						if (empty($mysql_error)) {
							//$msg='<p class="text-success">Request Updated Successfully...</p>';
							echo "<script>$(function(){ toastr.success('Request Updated Successfully for " . $EmpID . "'); }); </script>";
						} else {
							//$msg='<p class="text-danger">Request Not Updated ::Error :- <code>'.$mysql_error.'</code></p>';
							echo "<script>$(function(){ toastr.error('Request Not Updated for " . $EmpID . "::Error :- <code>" . $mysql_error . "</code>'); }); </script>";
						}
					}
				}
			} else {
				//$msg='<p class="text-danger">Request Not Updated ::Error :- <code>No Employee Selected ....</code></p>';
				echo "<script>$(function(){ toastr.error('Request Not Updated ::Error :- <code>No Employee Selected ....</code>'); }); </script>";
			}
		} else {
			//$msg='<p class="text-danger">Request Not Updated ::Error :- <code>No Employee Selected ....</code></p>';
			echo "<script>$(function(){ toastr.error('Request Not Updated ::Error :- <code>No Employee Selected ....</code>'); }); </script>";
		}
	}
}
?>

<script>
	$(document).ready(function() {
		$('.datetimepicker_text').each(function() {
			$(this).datetimepicker({
				timepicker: false,
				format: 'Y-m-d'
			});
		});

		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			scrollY: '500px',
			"iDisplayLength": 2000,
			scrollCollapse: true,
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

		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
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
	<span id="PageTittle_span" class="hidden">Employee transfer to HR - Resigned </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee transfer to HR - Resigned </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12 right-align">
					<button type="submit" value="Update Request " name="btnSave" id="btnSave" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Update Request</button>
				</div>

				<div id="pnlTable">
					<div class="had-container pull-left row card dataTableInline">
						<div class="">
							<?php
							$myDB = new MysqliDb();
							$admns = clean($_SESSION['__user_type']);
							$chk_taskq = 'SELECT rs.id, rs.EmployeeID,wd.EmployeeName,rs.remark, rs.file, rs.rg_status, rs.nt_start, rs.nt_end, rs.accept_time, rs.accept, rs.revoke_accept,rs.revoke_status, rs.accepter_remark, rs.accepter, rs.status_ah, rs.status_oh, rs.status_sitehead, rs.status_it, rs.status_hr, rs.final_acceptance, rs.createdon, rs.createdby, rs.revoke_ah, rs.revoke_hr, rs.revoke_on, rs.revoke_comment, rs.rv_hr_remark, wd.Process,wd.clientname,wd.sub_process,rs.rv_ah_remark,pd1.img as EmpImage from resign_details rs inner join whole_details_peremp wd on wd.EmployeeID = rs.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = rs.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where final_acceptance is null  and   ("ADMINISTRATOR" =  ?) and curdate() >= nt_start and curdate()<= nt_end';

							$stmt = $conn->prepare($chk_taskq);
							$stmt->bind_param("s", $admns);
							$stmt->execute();
							$chk_task = $stmt->get_result();
							$count = $chk_task->num_rows;
							if ($chk_task->num_rows > 0) {

								/*echo 'select rs.id, rs.EmployeeID,wd.EmployeeName,rs.remark, rs.file, rs.rg_status, rs.nt_start, rs.nt_end, rs.accept_time, rs.accept, rs.revoke_accept,rs.revoke_status, rs.accepter_remark, rs.accepter, rs.status_ah, rs.status_oh, rs.status_sitehead, rs.status_it, rs.status_hr, rs.final_acceptance, rs.createdon, rs.createdby, rs.revoke_ah, rs.revoke_hr, rs.revoke_on, rs.revoke_comment, rs.rv_hr_remark, wd.Process,wd.clientname,wd.sub_process,rs.rv_ah_remark,pd1.img as EmpImage from resign_details rs inner join whole_details_peremp wd on wd.EmployeeID = rs.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = rs.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where final_acceptance is null  and   ("ADMINISTRATOR" =  "'.$_SESSION['__user_type'].'") and curdate() >= nt_start and curdate()<= nt_end';*/
								$error = $myDB->getLastError();
								$rowCount = $myDB->count;
								// if ($chk_task && $rowCount > 0) { 
							?>

								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
											<th class="hidden">EmployeeID</th>
											<th class="hidden">EmployeeID</th>
											<th class="">Employee Name</th>

											<th class="hidden edit_info_header">HR Status</th>
											<th class="hidden edit_info_header">Notice Start</th>
											<th class="hidden edit_info_header">Notice End</th>
											<th class="edit_view_header">Client</th>
											<th class="edit_view_header">Process</th>
											<th class="edit_view_header">Sub Process</th>
											<th class="">Remark </th>

										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										foreach ($chk_task as $key => $value) {
											$count++;
											echo '<tr>';
											echo '<td class="EmpId"><input type="checkbox" id="cb' . $count . '" class="cb_child" name="cb[]" value="' . $count . '"><label for="cb' . $count . '" >' . strtoupper($value['EmployeeID']) . '</label></td>';

											echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  class="ckeckdata" data="' . strtoupper($value['EmployeeID']) . '">' . strtoupper($value['EmployeeID']) . '</a></td>';
											echo '<td class="FullName  ">' . $value['EmployeeName'] . '</td>';




											echo '<td class="hidden" ><input  type="input" name="txt_EmployeeID_' . $count . '" id="txt_EmployeeID_' . $count . '" readonly="true" value="' . $value['EmployeeID'] . '" /></td>';
											if ($value['rg_status']  > 0 &&  $value['rg_status']  < 9) {
												echo '<td class="edit_info_body hidden"  ><select   name="txt_Status_' . $count . '" id="txt_Status_' . $count . '"  title="HR Selected Status"><option value="Accept" selected>Accept</option>	<option value="Reject">Reject</option></select></td>';
											} elseif ($value['rg_status'] < 1) {
												echo '<td class="edit_info_body hidden"  ><select   name="txt_Status_' . $count . '" id="txt_Status_' . $count . '"  title="HR Selected Status"><option value="NA">---Select---</option><option value="Accept">Accept</option>	<option value="Reject">Reject</option></select></td>';
											} else {
												echo '<td class="edit_info_body hidden"  ><select   name="txt_Status_' . $count . '" id="txt_Status_' . $count . '"  title="HR Selected Status"><option value="Accept">Accept</option>	<option value="Reject" selected>Reject</option></select></td>';
											}

											echo '<td class="edit_info_body hidden"  ><input type="text"  name="nt_start_' . $count . '" id="nt_start_' . $count . '" readonly="true"  value="' . $value['nt_start'] . '" class="datetimepicker_text" readonly="true"/></td>';

											echo '<td class="edit_info_body hidden"  ><input type="text"  name="nt_end_' . $count . '" id="nt_end_' . $count . '" readonly="true" value="' . $value['nt_end'] . '" class="datetimepicker_text" readonly="true"/></td>';


											echo '<td class="client_name edit_view">' . $value['clientname'] . '</td>';
											echo '<td class="process edit_view">' . $value['Process'] . '</td>';
											echo '<td class="sub_process edit_view">' . $value['sub_process'] . '</td>';
											echo '<td class="remark  ">' . $value['remark'] . '</td>';
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
						</div>
					</div>

				<?php
							} else {
								echo "<script>$(function(){ toastr.success('Congratulations, all employees have been aligned to concern departments.<code >" . $error . "</code>'); }); </script>";
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
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

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

		$("input:checkbox").click(function() {

		});

		$("#cbAll").change(function() {
			$("input:checkbox").prop('checked', $(this).prop("checked"));
		});
		$("input:checkbox").change(function() {
			checkbox_check();
			if ($('input.cb_child:checkbox:checked').length > 0) {
				if ($('input.cb_child:checkbox:checked').length == $('input.cb_child:checkbox').length) {

					$("#cbAll").prop("checked", true);
				} else {
					$("#cbAll").prop("checked", false);
				}
			} else {
				$("#cbAll").prop("checked", false);


			}
		});
		$('#btnSave').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				alert_msg += '<li> Check Atleast On Employee.</li>';
			}
			$('input.cb_child:checkbox:checked').each(function() {
				if ($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 50 && $(this).parent('td').closest('tr').find('select[id^="txt_Status_"]').val() == 'Reject') {
					validate = 1;
					alert_msg += '<li> Remark Can\'t be Empty or not less than 50 For any Rejected Requests...</li>';
				}



			});
			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
		      		$('#alert_message').show().attr("class","SlideInRight animated");
		      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;
					*/
				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			}
		});

	});

	function checkbox_check() {

		$('.edit_info_body').addClass('hidden');
		$('.edit_info_header').addClass('hidden');
		$('.edit_view').removeClass('hidden');
		$('.edit_view_header').removeClass('hidden');
		if ($('input.cb_child:checkbox:checked').length > 0) {
			$('input.cb_child:checkbox:checked').each(function() {

				$(this).closest('tr').find('.edit_info_body').removeClass('hidden');
				$(this).closest('tr').find('.edit_view').addClass('hidden');
				$('.edit_info_header').removeClass('hidden');
				$('.edit_view_header').addClass('hidden');
			});

		}

	}
</script>