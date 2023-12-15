<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
//require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
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
$status = '';
$batch_id = '';
$classvarr = "'.byID'";
$searchBy = '';
$msg = '';
$btn_save = isset($_POST['btnSave']);
if ($btn_save) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$createBy = clean($_SESSION['__user_logid']);
		$cb = isset($_POST['cb']);
		if ($cb) {
			$checked_arr = $_POST['cb'];
			$count_check = count($checked_arr);
			if ($count_check > 0) {
				$myDB = new MysqliDb();
				$mysql_error = '';
				foreach ($_POST['cb'] as $val) {
					$Counter = cleanUserInput($val);
					$EmpID = cleanUserInput($_POST['txt_EmployeeID_' . $Counter]);
					$Status = cleanUserInput($_POST['txt_Status_' . $Counter]);

					if ($Status != 'NA' || $Status != 'Na') {
						if ($Status == 'Accept') {
							$Status = 1;
							$rv_hr_remark = cleanUserInput($_POST['txt_remark_' . $EmpID]);
							$sql = 'update resign_details set revoke_accept = "2",rg_status=11, revoke_hr="' . date('Y-m-d H:i:s') . '",rv_hr_remark=?  where EmployeeID = ?';
							// $myDB = new MysqliDb();
							// $result_upd = $myDB->rawQuery($sql);
							// $rowCount = $myDB->count;
							$upQ = $conn->prepare($sql);
							$upQ->bind_param("ss", $rv_hr_remark, $EmpID);
							$upQ->execute();
							$result_upd = $upQ->get_result();
							$Status = 1;
							$sql1 = 'delete from alert_details where EmployeeID = ? and type like "RESIGN Accept%"';
							$upQ = $conn->prepare($sql1);
							$upQ->bind_param("s", $EmpID);
							$upQ->execute();
							$result_upd = $upQ->get_result();
							// $myDB = new MysqliDb();
							// $result_upd = $myDB->rawQuery($sql1);
							// $rowCount = $myDB->count;
							$sql1 = 'INSERT INTO alert_details(EmployeeID,alert_start,alert_end,type,createdon,createdby)VALUES(?,"' . date('Y-m-d', time()) . '","' . date('Y-m-d', strtotime('+1 day')) . '","' . "Resign cancellation request approved by HR" . '","' . date('Y-m-d H:i:s', time()) . '",?)';
							// $myDB = new MysqliDb();
							// $result_upd = $myDB->rawQuery($sql1);
							$upQ = $conn->prepare($sql1);
							$upQ->bind_param("ss", $EmpID, $createBy);
							$upQ->execute();
							$result_upd = $upQ->get_result();
							// $mysql_error = $myDB->getLastError();
							// $rowCount = $myDB->count;
						} else {
							// $myDB = new MysqliDb();
							$rv_hr_remark = $_POST['txt_remark_' . $EmpID];
							$Status = 2;
							$sql = 'update resign_details set revoke_accept = "3", revoke_hr="' . date('Y-m-d H:i:s') . '",rv_hr_remark=?  where EmployeeID = ?';
							$upQ = $conn->prepare($sql);
							$upQ->bind_param("ss", $rv_hr_remark, $EmpID);
							$upQ->execute();
							$result_upd = $upQ->get_result();
							// $result_upd = $myDB->rawQuery($sql);
							// $mysql_error = $myDB->getLastError();
							// $rowCount = $myDB->count;
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
						// if ($rowCount > 0) {
						if ($upQ->affected_rows === 1) {
							echo "<script>$(function(){ toastr.success('Request Updated Successfully.'); }); </script>";
						} else {
							echo "<script>$(function(){ toastr.error('Request Not Updated.'); }); </script>";
						}
					}
				}
			} else {

				echo "<script>$(function(){ toastr.error('Request Not Updated ::Error :- <code>No Employee Selected ....</code>'); }); </script>";
			}
		} else {
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
	<span id="PageTittle_span" class="hidden">Employee Resigned Cancel Requests </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Resigned Cancel Requests </h4>

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
					<?php

					$location = clean($_SESSION["__location"]);
					//$chk_task=$myDB->rawQuery('select *,pd1.img from resign_details left outer join whole_details_peremp on whole_details_peremp.EmployeeID = resign_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = resign_details.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where final_acceptance is null  and   (rg_status != 9 and  revoke_status = 1 and revoke_accept = 1)'); 
					if ($location == "1") {
						$chktask = 'select  wh.EmployeeID,wh.EmployeeName,rs.remark,rs.nt_start,rs.rg_status ,rs.nt_end,wh.Process,wh.sub_process,wh.clientname,pd1.img from resign_details rs left outer join whole_details_peremp wh on wh.EmployeeID = rs.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = rs.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo  where final_acceptance is null  and   (rg_status != 9 and  revoke_status = 1 and revoke_accept = 1) and pd1.location in (1,2)';
						$selectQ = $conn->prepare($chktask);
						$selectQ->execute();
						$chk_task = $selectQ->get_result();
					} else {
						$chktask = 'select  wh.EmployeeID,wh.EmployeeName,rs.remark,rs.nt_start,rs.rg_status ,rs.nt_end,wh.Process,wh.sub_process,wh.clientname,pd1.img from resign_details rs left outer join whole_details_peremp wh on wh.EmployeeID = rs.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = rs.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo  where final_acceptance is null  and   (rg_status != 9 and  revoke_status = 1 and revoke_accept = 1) and pd1.location in (?)';
						$selectQ = $conn->prepare($chktask);
						$selectQ->bind_param("i", $location);
						$selectQ->execute();
						$chk_task = $selectQ->get_result();
					}



					//echo $sqlConnect;
					// $error = $myDB->getLastError();
					// $rowCount = $myDB->count;
					if ($chk_task && $chk_task->num_rows > 0) { ?>

						<div class="had-container pull-left row card dataTableInline">
							<div class="">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
											<th class="hidden">EmployeeID</th>
											<th class="hidden">EmployeeID</th>
											<th class="">Employee Name</th>
											<th class="">Remark </th>
											<th class="hidden edit_info_header">HR Status</th>
											<th class="hidden edit_info_header">Notice Start</th>
											<th class="hidden edit_info_header">Notice End</th>
											<th class="edit_view_header">Client</th>
											<th class="edit_view_header">Process</th>
											<th class="edit_view_header">Sub Process</th>
											<th class="hidden edit_info_header">Remark</th>

										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										foreach ($chk_task as $key => $value) {
											$count++;
											echo '<tr>';
											echo '<td class="EmpId"><input type="checkbox" id="cb' . $count . '" class="cb_child" name="cb[]" value="' . $count . '"><label for="cb' . $count . '" >' . $value['EmployeeID'] . '</label></td>';

											echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"   class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
											echo '<td class="FullName  ">' . $value['EmployeeName'] . '</td>';

											echo '<td class="remark  ">' . $value['remark'] . '</td>';


											echo '<td class="hidden" ><input  type="input" name="txt_EmployeeID_' . $count . '" id="txt_EmployeeID_' . $count . '" readonly="true" value="' . $value['EmployeeID'] . '" /></td>';
											if ($value['rg_status']  > 0 &&  $value['rg_status']  < 9) {
												echo '<td class="edit_info_body hidden"  ><select   name="txt_Status_' . $count . '" id="txt_Status_' . $count . '"  title="HR Selected Status"><option value="Accept" selected>Accept</option>	<option value="Reject">Reject</option></select></td>';
											} elseif ($value['rg_status'] < 1) {
												echo '<td class="edit_info_body hidden"  ><select   name="txt_Status_' . $count . '" id="txt_Status_' . $count . '"  title="HR Selected Status"><option value="NA">---Select---</option><option value="Accept">Accept</option>	<option value="Reject">Reject</option></select></td>';
											} else {
												echo '<td class="edit_info_body hidden"  ><select   name="txt_Status_' . $count . '" id="txt_Status_' . $count . '"  title="HR Selected Status"><option value="Accept">Accept</option>	<option value="Reject" selected>Reject</option></select></td>';
											}

											echo '<td class="edit_info_body hidden"  >' . $value['nt_start'] . '</td>';

											echo '<td class="edit_info_body hidden"  >' . $value['nt_end'] . '</td>';


											echo '<td class="client_name edit_view">' . $value['clientname'] . '</td>';
											echo '<td class="process edit_view">' . $value['Process'] . '</td>';
											echo '<td class="sub_process edit_view">' . $value['sub_process'] . '</td>';
											echo '<td class="remark edit_info_body hidden"><textarea class="materialize-textarea materialize-textarea-size" name="txt_remark_' . $value['EmployeeID'] . '"></textarea></td>';

											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					} else {
						//echo '<div id="div_error" class="slideInDown animated hidden">Congratulations, all employees have been aligned to concern departments. <code >'.$error.'</code> </div>';
						echo "<script>$(function(){ toastr.success('Congratulations, all employees have been aligned to concern departments.<code ></code>'); }); </script>";
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
				alert_msg += '<li> Check Atleast On Employee ....  </li>';
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
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>