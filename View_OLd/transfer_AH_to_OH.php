<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
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
$Loggin_location = clean($_SESSION["__location"]);
$ahComment = "";
$old_client = $status = $comment = '';
$classvarr = "'.byID'";
$searchBy = '';
$moveid = "";
$updatedBy = clean($_SESSION['__user_logid']);
$client_name = '';
if (isset($_GET['process']) && $_GET['process'] != '') {
	$client_name = cleanUserInput($_GET['process']);
}
$msg = '';
if (isset($_POST['transfer_client']) && cleanUserInput($_POST['transfer_client']) == 'Update') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$counttol = 0;

		// if (!is_numeric($_POST['status'])) {
		// 	$status = ($_POST['status']);
		// }
		$status = cleanUserInput($_POST['status']);
		$new_process = cleanUserInput($_POST['new_process']);
		$comment = cleanUserInput($_POST['comment']);
		$tcid_array = cleanUserInput($_POST['tcid']);
		$tr_required = cleanUserInput($_POST['tr_required']);
		$tcid = isset($_POST['tcid']);
		if ($tcid) {
			$date = date("Y-m-d h:i:s");
			$checked_arr = cleanUserInput($_POST['tcid']);
			// $count_check = count($status);
			if ($new_process != "") {
				$max_key = max(array_keys($_POST['EmployeeName']));
				$min_key = min(array_keys($_POST['EmployeeName']));
				for ($i = $max_key; $i >= $min_key; $i--) {

					if (isset($checked_arr[$i]) && $checked_arr[$i] != "" && $comment[$i] != "") {
						$empID = $checked_arr[$i];
						$movedate = $_POST['txt_movedate'][$i];
						$moveid =	$_POST['moveid'][$i];
						$reuire = $tr_required[$i];
						if ($status[$i] == 'AHReject') {
							$flag = 'AHR';
						} else
					if ($status[$i] == 'AHApprove') {
							$flag = 'toOH';
						} else
					if ($status[$i] == 'Pending') {
							$flag = 'toAH';
						}

						// $save = "UPDATE tbl_oh_tooh_move set AH_comment='" . $comment[$i] . "',status='" . $status[$i] . "',flag='" . $flag . "',AH_updated_on='" . $date . "',tr_required='" . $reuire . "',Updated_by='" . $updatedBy . "',updated_on='" . $date . "',AH_updated_by='" . $updatedBy . "',move_date='" . $movedate . "' where EmployeeID='" . $checked_arr[$i] . "'  and id='" . $moveid . "'";
						$save = "UPDATE tbl_oh_tooh_move set AH_comment='" . $comment[$i] . "',status='" . $status[$i] . "',flag=?,AH_updated_on=?,tr_required=?,Updated_by=?,updated_on=?,AH_updated_by=?,move_date=? where EmployeeID='" . $checked_arr[$i] . "'  and id=?";
						// $resultBy = $myDB->rawQuery($save);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						$stmt = $conn->prepare($save);
						$stmt->bind_param("isssssss", $flag, $date, $reuire, $updatedBy, $date, $updatedBy, $movedate,  $moveid);
						$stmt->execute();
						$resultBy = $stmt->get_result();
						if ($resultBy->num_rows > 0) {
							$counttol++;
						}
					}
				}
				if (($counttol > 0) && $resultBy) {
					echo "<script>$(function(){ toastr.success('Data Updated Successfully...'); }); </script>";
				} else {
					//$msg='<p class="text-danger">Data Not Updated ::Error :- <code>'.$mysql_error.'</code></p>';
					echo "<script>$(function(){ toastr.error('Data Not Updated ::Error :'); }); </script>";
				}
			}
		}
	}
}
?>
<script>
	$(document).ready(function() {
		$('.statuscheck').addClass('hidden');
		var dateToday = new Date();
		$('.txt_move_date').datepicker({
			minDate: dateToday,
			dateFormat: 'yy-mm-dd',

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
	<span id="PageTittle_span" class="hidden">Employee Movement Process to Process </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Movement Process to Process </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s10 m10 ">
					<?php
					// $sqlBy = "SELECT distinct b.process,b.cm_id,concat(c.client_name,'|',b.process,'|',b.sub_process) AS Client FROM tbl_oh_tooh_move a,new_client_master b,client_master c where a.new_cm_id=b.cm_id and c.client_id=b.client_name and b.account_head='" . $updatedBy . "'  and a.status='Pending' and a.flag='toAH' and a.move_location='" . $Loggin_location . "'";
					$sqlBy = "SELECT distinct b.process,b.cm_id,concat(c.client_name,'|',b.process,'|',b.sub_process) AS Client FROM tbl_oh_tooh_move a,new_client_master b,client_master c where a.new_cm_id=b.cm_id and c.client_id=b.client_name and b.account_head=?  and a.status='Pending' and a.flag='toAH' and a.move_location=?";
					// $resultBy2 = $myDB->rawQuery($sqlBy);
					// $mysql_error = $myDB->getLastError();
					// $rowCount = $myDB->count;
					$stql = $conn->prepare($sqlBy);
					$stql->bind_param("si", $updatedBy, $Loggin_location);
					$stql->execute();
					$resultBy2 = $stql->get_result();
					?>
					<select id="queryfrom" name="new_process">
						<option value="NA">----Select----</option>
						<?php

						if ($resultBy2->num_rows > 0) {
							$selec = '';
							//print_r($resultBy);
							foreach ($resultBy2 as $key => $value) {
								$select = '';
								if ($client_name != '' && $value['cm_id'] == $client_name) {
									$select = "selected";
								}
								echo '<option value="' . $value['cm_id'] . '"  ' . $select . ' >' . $value['Client'] . '</option>';
							}
						}

						?>
					</select>
					<label for="queryfrom" class="active-drop-down active">To Process</label>
				</div>


				<div class="input-field col s2 m2 right-align">
					<!--Update Move Date :-->
					<input type="submit" value="Update" name="transfer_client" id="client_action" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green" />

					<!-- <input  type="button" value="Cancel" name="btnCan" id="btnCan" class="button button-3d-highlight button-rounded"/>-->
				</div>

				<div id="pnlTable">
					<?php
					if (isset($_GET['process']) && cleanUserInput($_GET['process']) != "") {

						//$new_client_name='11';
						$old_client_name = cleanUserInput($_GET['process']);
						$sqlConnect = " select a.EmployeeID,b.id as moveid , a.EmployeeName,a.designation,a.emp_level,concat(a.clientname,' | ',a.Process,' | ',a.sub_process) as Client,DATE_FORMAT(b.move_date,'%Y-%m-%d') AS move_date,DATE_FORMAT(b.created_on,'%Y-%m-%d') AS created_on,b.status,b.AH_comment,b.tr_required from whole_details_peremp a,tbl_oh_tooh_move b where a.EmployeeID=b.EmployeeID and b.new_cm_id=? and b.flag='toAH' and b.status='Pending' and b.move_location=?";
						// $resultBy = $myDB->rawQuery($sqlConnect);
						// $mysql_error = $myDB->getLastError();
						// $rowCount2 = $myDB->count;
						$stm = $conn->prepare($sqlConnect);
						$stm->bind_param("ss", $old_client_name, $Loggin_location);
						$stm->execute();
						$resultBy = $stm->get_result();

						if ($resultBy->num_rows > 0) { ?>

							<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								<div class="">
									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Serial No.</th>
												<th><input type="checkbox" name="cbAll" id="cbAll" value="ALL"><label for="cbAll">Employee</label></th>
												<th class="hidden">EmployeeID</th>
												<th>EmployeeName</th>
												<th>Designation</th>
												<th>Level</th>
												<th>Training Required</th>
												<th>Current Process</th>
												<th>Move Date</th>
												<th>Transfer Date</th>
												<th>Status</th>
												<th>Comment</th>
											</tr>
										</thead>
										<tbody id="emplist">
											<?php

											$count = $rowCount2;
											$i = 0;
											$j = 1;
											foreach ($resultBy as $key => $data_array) {
												$ahComment = "";
												if ($data_array['AH_comment'] != "") {
													$ahComment = addslashes($data_array['AH_comment']);
												}
												echo '<tr>';
												echo "<td >" . $j . "</td>";
												echo '<td class="EmpId"><input type="checkbox" id="cb' . $i . '" class="cb_child" name="tcid[' . $i . ']" value="' . $data_array['EmployeeID'] . '"><label for="cb' . $i . '" >' . $data_array['EmployeeID'] . '</label></td>';
												echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  class="ckeckdata" data="' . $data_array['EmployeeID'] . '">' . $data_array['EmployeeID'] . '</a></td>';
												echo '<td class="client_name">' . $data_array['EmployeeName'] . '</td>';
												echo '<td class="designation">' . $data_array['designation'] . '</td>';
												echo '<td class="emp_level">' . $data_array['emp_level'] . '</td>';		?>
												<td class="active_status">
													<select name="tr_required[<?php echo  $i; ?>]" id="tr_required<?php echo  $i; ?>" class="form-control">
														<option value="r" <?php if ($data_array['tr_required'] == 'r') { ?> selected <?php } ?>>Require</option>
														<option value="nr" <?php if ($data_array['tr_required'] == 'nr') { ?> selected <?php } ?>>Not Require</option>
													</select>
												</td>
												<?php
												echo '<td class="Client">' . $data_array['Client'] . '</td>';
												echo '<td class="move_date"><input class="txt_move_date " type="text" name="txt_movedate[' . $i . ']" value="' . $data_array['move_date'] . '" readonly /></td>';
												echo '<td class="sub_process">' . $data_array['created_on'] . '</td>';

												?>
												<input class='empclass' type='hidden' name='EmployeeName[<?php echo $i; ?>]' value="<?php echo $data_array['EmployeeName']; ?>">
												<td class="active_status">
													<select name="status[<?php echo $i; ?>]" id="status<?php echo  $i; ?>">
														<option value="Pending" <?php if ($data_array['status'] == 'Pending') { ?> selected <?php } ?>>Pending</option>
														<option value="AHReject" <?php if ($data_array['status'] == 'AHReject') { ?> selected <?php } ?>>Reject</option>
														<option value="AHApprove" <?php if ($data_array['status'] == 'AHApprove') { ?> selected <?php } ?>>Approve</option>

													</select>
												</td>
												<td class="comment"><textarea name='comment[<?php echo $i; ?>]' id="comment<?php echo  $i; ?>" class="materialize-textarea materialize-textarea-size ahcomment"><?php echo  $ahComment; ?></textarea></td>
												<input type="hidden" name='moveid[<?php echo  $i; ?>]' id='moveid<?php echo  $i; ?>' class='moveid' value="<?php echo $data_array['moveid']; ?>">
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
							echo "<script>$(function(){ toastr.error('Data not found.'); }); </script>";
						}
					} else {
						echo "<script>$(function(){ toastr.info('Employee not transfter,Please select your client.'); });</script>";
					}


					?>
					<!-- datatable End --->
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
		$('#client_action').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				alert_msg += '<li> Check Atleast On Employee ....  </li>';
			} else {
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
				location.href = 'transfer_AH_to_OH.php?process=' + tval;
			}
			/*   $.ajax({
				  url: <?php echo '"' . URL . '"'; ?>+"Controller/getClientEmployeeList.php?id="+tval+"&action=getclient"
				}).done(function(data) { // data what is sent back by the php page
				//alert(data);
					$('#emplist').html(data);
					$('#emplist').val('NA');
			});*/
		});
	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');

	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>