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

$old_client = $status = $comment = '';
$classvarr = "'.byID'";
$searchBy = '';
$updatedBy = clean($_SESSION['__user_logid']);
$Loggin_location = clean($_SESSION["__location"]);
// $Loggin_location = '6';
$client_name = '';
$hr_comment = "";
$cm_id = cleanUserInput($_GET['cm_id']);
if (isset($cm_id) && $cm_id != '') {
	$client_name = cleanUserInput($_GET['cm_id']);
}
$msg = '';
$rowCount = "";
$transfer_client = isset($_POST['transfer_client']);
if ($transfer_client && $_POST['transfer_client'] == 'Update') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$status = cleanUserInput($_POST['status']);
		$old_client = cleanUserInput($_POST['old_client']);
		$comment = cleanUserInput($_POST['comment']);
		$tcid_array = cleanUserInput($_POST['tcid']);
		$flag = "toHR";

		if (isset($_POST['tcid'])) {

			$date = date("Y-m-d h:i:s");
			$checked_arr = cleanUserInput($_POST['tcid']);

			//$movedate=$_POST['move_date'];
			// $count_check = count($status);
			if ($old_client != "") {
				$max_key = max(array_keys($_POST['comment']));
				$min_key = min(array_keys($_POST['comment']));
				for ($i = $max_key; $i >= $min_key; $i--) {

					if (isset($checked_arr[$i]) && $checked_arr[$i] != "" && $comment[$i] != "") {
						$empID = $checked_arr[$i];
						$move = cleanUserInput($_POST['moveid']);
						$txt_movedate = cleanUserInput($_POST['txt_movedate']);

						$moveid =	$move[$i];
						$movedate = $txt_movedate[$i];
						if ($status[$i] == 'Reject') {
							$flag = 'toONC';
						} else
					if ($status[$i] == 'Approve') {
							$flag = 'toNC';
						} else
					if ($status[$i] == 'Pending') {
							$flag = 'toHR';
						}

						$save = "UPDATE tbl_client_toclient_move set hr_comment='" . $comment[$i] . "',status='" . $status[$i] . "',flag=?,HR_updated_on=?,HR_updated_by=?,Updated_by=?,updated_on=?,move_date=? where old_cm_id=? and EmployeeID='" . $checked_arr[$i] . "'  and id=? and move_location=?";
						//echo "<br><br>";
						$updateQ = $conn->prepare($save);
						$updateQ->bind_param("ssssssiii",  $flag, $date, $updatedBy, $updatedBy, $date, $movedate, $old_client, $moveid, $Loggin_location);
						$updateQ->execute();
						$resultBy = $updateQ->get_result();
						// print_r($resultBy);
						// $resultBy = $myDB->rawQuery($save);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
					}
				}
				if ($updateQ->affected_rows === 1) {
					// if ($rowCount > 0) {
					// $msg='<p class="text-success">Data Updated Successfully...</p>';
					echo "<script>$(function(){ toastr.success('Data Updated Successfully...'); }); </script>";
				} else {
					//$msg='<p class="text-danger">Data Not Updated ::Error :- <code>'.$mysql_error.'</code></p>';
					echo "<script>$(function(){ toastr.error('Data Not Updated'); }); </script>";
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
	<span id="PageTittle_span" class="hidden">Employee Movement : Client to Client </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Movement : Client to Client </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s6 m6 ">
					<?php
					//   $sqlBy ="SELECT  cm_id, concat( client_master.client_name,' | ',process,' | ',sub_process) as Client FROM new_client_master inner join client_master on new_client_master.client_name = client_master.client_id  INNER JOIN tbl_client_toclient_move  ON new_client_master.cm_id=tbl_client_toclient_move.old_cm_id  where  tbl_client_toclient_move.status='Pending'  group by new_client_master.cm_id order by new_client_master.client_name";

					$sqlBy = "SELECT  new_client_master.cm_id, concat( client_master.client_name,' | ',process,' | ',sub_process) as Client FROM new_client_master inner join client_master on new_client_master.client_name = client_master.client_id  INNER JOIN tbl_client_toclient_move  ON new_client_master.cm_id=tbl_client_toclient_move.old_cm_id  Inner join employee_map  map on map.EmployeeID=tbl_client_toclient_move.EmployeeID inner join personal_details on personal_details.EmployeeID = tbl_client_toclient_move.EmployeeID  where tbl_client_toclient_move.status='Pending' and map.emp_status='Active' and personal_details.location =? group by new_client_master.cm_id order by new_client_master.client_name";
					$selectQ = $conn->prepare($sqlBy);
					$selectQ->bind_param("i", $Loggin_location);
					$selectQ->execute();
					$resultBy = $selectQ->get_result();
					// print_r($resultBy);
					// die;
					// 
					//echo $sqlBy;						
					// $myDB = new MysqliDb();
					// $resultBy = $myDB->rawQuery($sqlBy);
					// $mysql_error = $myDB->getLastError();
					// $rowCount = $myDB->count;
					?>
					<select id="queryfrom" name="old_client">
						<option value="NA">----Select----</option>
						<?php

						if ($resultBy->num_rows > 0) {
							$selec = '';
							//print_r($resultBy);
							foreach ($resultBy as $key => $value) {
								$select = '';
								if ($client_name != '' && $value['cm_id'] == $client_name) {
									$select = "selected";
								}
								echo '<option value="' . $value['cm_id'] . '"  ' . $select . ' >' . $value['Client'] . '</option>';
							}
						}

						?>
					</select>
					<label for="queryfrom" class="active-drop-down active">From Client</label>
				</div>
			</div>
			<div class="statuscheck">
				<div class="input-field col s12 m12 right-align ">
					<input type="hidden" name="move_date" id="move_date" value="" readonly />
					<button type="submit" value="Update" name="transfer_client" id="client_action" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Update</button>
				</div>
			</div>

			<div id="pnlTable">
				<?php
				if (isset($_GET['cm_id']) && $_GET['cm_id'] != "") {
					$old_client_name = cleanUserInput($_GET['cm_id']);
					$loc = clean($_SESSION["__location"]);
					// $loc = '6';
					$sqlConnect = "select a.EmployeeID,b.new_cm_id,b.status,b.id as moveid,b.hr_comment, a.EmployeeName,a.clientname ,a.Process,a.sub_process,a.designation,a.emp_level,a.emp_status,DATE_FORMAT(b.move_date,'%Y-%m-%d') AS move_date,DATE_FORMAT(b.created_on,'%Y-%m-%d') AS created_on from whole_details_peremp a inner Join tbl_client_toclient_move b on a.EmployeeID=b.EmployeeID and b.old_cm_id=? and  b.flag='toHR' and b.status='Pending' and a.location=?";
					$selQr = $conn->prepare($sqlConnect);
					$selQr->bind_param("ii", $old_client_name, $loc);
					$selQr->execute();
					$result = $selQr->get_result();
					// $myDB = new MysqliDb();
					// $result = $myDB->rawQuery($sqlConnect);
					// $mysql_error = $myDB->getLastError();
					// $rowCount = $myDB->count;
					function getClientName($cmid)
					{
						$myDB = new MysqliDb();
						$conn = $myDB->dbConnect();
						$loca = clean($_SESSION["__location"]);
						$selec = "Select concat(clientname,' | ',process,' | ',sub_process) AS newClient from whole_details_peremp where cm_id=? and location=? ";
						$selQr = $conn->prepare($selec);
						$selQr->bind_param("ii", $cmid, $loca);
						$selQr->execute();
						$selecName = $selQr->get_result();
						$selName = $selecName->fetch_row();
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;

						if ($selecName->num_rows > 0) {
							$name = $selName[0];
							return $name;
						}
					}

					if ($result->num_rows > 0) { ?>

						<div class="had-container pull-left row card">
							<div class="">
								<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Serial No.</th>
											<th><input type="checkbox" name="cbAll" id="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
											<th class="hidden">EmployeeID</th>
											<th>Employee Name</th>
											<th>Designation</th>
											<th>Level</th>
											<th>New Client</th>
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
										foreach ($result as $key => $data_array) {
											$hr_comment = "";
											if ($data_array['hr_comment'] != "") {
												$hr_comment = $data_array['hr_comment'];
											}
											$data_array['hr_comment'];
											$new_cmid = $data_array['new_cm_id'];

											$new_client_array = getClientName($new_cmid);

											echo '<tr>';
											echo "<td  >" . $j . "</td>";
											echo '<td class="EmpId"><input type="checkbox" id="cb' . $i . '" class="cb_child" name="tcid[' . $i . ']" value="' . $data_array['EmployeeID'] . '"><label for="cb' . $i . '" >' . $data_array['EmployeeID'] . '</label></td>';
											echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"   class="ckeckdata" data="' . $data_array['EmployeeID'] . '">' . $data_array['EmployeeID'] . '</a></td>';
											echo '<td class="EmployeeName">' . $data_array['EmployeeName'] . '</td>';
											echo '<td class="EmployeeName">' . $data_array['designation'] . '</td>';
											echo '<td class="EmployeeName">' . $data_array['emp_level'] . '</td>';

											echo '<td class="client_name">' . $new_client_array . '</td>';
											echo '<td class="move_date" ><input class="txt_move_date  type="text" name="txt_movedate[' . $i . ']" value="' . $data_array['move_date'] . '" readonly /></td>';
											echo '<td class="sub_process">' . $created_on = $data_array['created_on'] . '</td>';
											$movedate_new = $data_array['move_date'];
										?>
											<input class='empclass' type='hidden' name='EmployeeName[<?php echo  $i; ?>]' value="<?php echo $data_array['EmployeeName']; ?>">
											<td class="active_status">
												<select name="status[<?php echo  $i; ?>]" id="status<?php echo  $i; ?>">
													<option value="Pending" <?php if ($data_array['status'] == 'Pending') { ?> selected <?php } ?>>Pending</option>
													<option value="Reject" <?php if ($data_array['status'] == 'Reject') { ?> selected <?php } ?>>Reject</option>
													<option value="Approve" <?php if ($data_array['status'] == 'Approve') { ?> selected <?php } ?>>Approve</option>

												</select>
											</td>
											<td class="comment"><textarea name='comment[<?php echo  $i; ?>]' id="comment<?php echo  $i; ?>" class="materialize-textarea materialize-textarea-size hrcomment">
										<?php echo  $hr_comment; ?></textarea></td>
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
					}
				} else {
					echo "<script>$(function(){ toastr.error('Please select your client.'); }); </script>";
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
				var hrComment = document.getElementsByClassName('hrcomment');
				var empclass = document.getElementsByClassName('empclass');
				for (var i = 0; inputElements[i]; ++i) {
					if (inputElements[i].checked) {
						checkedValue = hrComment[i].value.trim();
						empname = empclass[i].value.trim();
						if (checkedValue == "") {
							validate = 1;
							alert_msg += '<li> Write the comment for ' + empname + ' </li>';
							break;
						} else {
							var statusval = document.getElementById('status' + i).value;
							// 	alert(statusval);
							if (statusval == 'Pending') {
								validate = 1;
								alert_msg += '<li> Please change the status for ' + empname + ' </li>';
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
					return false;*/
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
			if (tolientid == 'NA') {
				$('#transfer_client').addClass('hidden');
			} else {
				$('#transfer_client').removeClass('hidden');
			}
		});
		$('#queryfrom').change(function() {
			var tval = $(this).val().trim();
			if (tval != "") {
				location.href = 'transfer_client_to_hr.php?cm_id=' + tval;
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