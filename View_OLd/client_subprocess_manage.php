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
// Only for user type administrator
$user_type = clean($_SESSION['__user_type']);
$user_logid = clean($_SESSION['__user_logid']);
if ($user_type == 'ADMINISTRATOR' || $user_logid == 'CE10091236' || $user_logid == 'CE12102224') {
	// proceed further
} else {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}
// Global variable used in Page Cycle
$alert_msg = '';

// Trigger Button-Save Click Event and Perform DB Action
$btn_Client_Save = isset($_POST['btn_Client_Save']);
if ($btn_Client_Save) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$_CMID = cleanUserInput($_POST['txt_Client_Name']);
		// echo cleanUserInput($_POST['txt_status']);
		// exit;
		$myDB = new MysqliDb();
		$data_validateq = ("SELECT cm_id from client_status_master where cm_id = ?");
		//print_r($data_validate);
		$stmt = $conn->prepare($data_validateq);
		$stmt->bind_param("s", $_CMID);
		$stmt->execute();
		$data_validate = $stmt->get_result();
		$mysql_error = $myDB->getLastError();
		$dataID = $dataIDs = '';
		$txt_status = cleanUserInput($_POST['txt_status']);
		if ($data_validate->num_rows > 0  && $data_validate && $txt_status == 'Active') {

			// echo 1;
			// die;
			// $ids = implode(',', $_CMID);
			// $_CMID[] = $_CMID;
			$id2 = explode(',', $_CMID);
			// echo $id2;
			// die;
			$count = count($id2);
			$placeholders = implode(',', array_fill(0, $count, '?'));
			$bindStr = str_repeat('i', $count);
			$sql_delete = "DELETE FROM client_status_master where cm_id in ($placeholders)";
			// $myDB = new MysqliDb();
			// $myDB->query($sql_delete);
			$stmt = $conn->prepare($sql_delete);
			$stmt->bind_param("$bindStr", ...$id2);
			$stmt->execute();

			$mysql_error = $myDB->getLastError();
			if ($stmt) {
				echo "<script>$(function(){ toastr.success('Process Activated Successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Process not Activated " . $mysql_error . "'); }); </script>";
			}
		} elseif ($txt_status == 'Inactive' && $data_validate) {
			// echo "inactive";
			// die;
			$sql_insert = "call insert_inactive_client(" . $_CMID . "); ";
			$myDB = new MysqliDb();
			$myDB->query($sql_insert);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Process Deactivated Successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Process not Deactivated " . $mysql_error . "'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.info('Wrong action'); }); </script>";
		}
	}
}
?>

<script>
	//contain load event for data table and other importent rand required trigger event and searches if any
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollCollapse: true,
			"iDisplayLength": 25,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [
				/*  {
				      extend: 'csv',
				      text: 'CSV',
				      extension: '.csv',
				      exportOptions: {
				          modifier: {
				              page: 'all'
				          }
				      },
				      title: 'table'
				  }, 						         
				  'print',*/
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
				},
				/* 'copy',*/
				'pageLength'

			]
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
	<span id="PageTittle_span" class="hidden">Client Master Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Client Process Details</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Client Process Details</h4>
						<div class="modal-body">
							<div class="input-field col s6 m6">
								<select id="txt_Client_Name" name="txt_Client_Name" required>
									<option value="NA">---Select---</option>
									<?php
									$myDB =  new MysqliDb();
									$data_clinet = $myDB->query('select cm_id, account_head, dept_id, process, oh, qh, th,er_scop, sub_process, Stipend, StipendDays, client_id, client_master.client_name from new_client_master inner join client_master on client_master.client_id = new_client_master.client_name order by client_master.client_name ;');
									if (count($data_clinet) > 0 && $data_clinet) {
										foreach ($data_clinet as $key => $value) {
											echo '<option value="' . $value['cm_id'] . '" >' . $value['client_name'] . '|' . $value['process'] . '|' . $value['sub_process'] . '</option>';
										}
									}
									?>
								</select>
								<label for="txt_joindate_to" class="active-drop-down active">Client Name</label>
							</div>

							<div class="input-field col s6 m6">
								<select id="txt_status" name="txt_status" required>
									<option value="NA">---Select---</option>
									<option>Active</option>
									<option>Inactive</option>
								</select>
								<label for="txt_status" class="active-drop-down active">Status</label>
							</div>
							<div class="input-field col s12 m12 right-align">
								<input type="hidden" class="form-control hidden" id="hid_Client_ID" name="hid_Client_ID" />
								<button type="submit" name="btn_Client_Save" id="btn_Client_Save" class="btn waves-effect waves-green">Update</button>
								<button type="button" name="btn_Client_Can" id="btn_Client_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>
						</div>
					</div>
				</div>
				<div id="pnlTable">
					<?php
					$sqlConnect = 'select client_id,client_master.client_name,process,sub_process,client_status_master.id,new_client_master.cm_id,t1.location  from new_client_master inner join client_master on client_master.client_id = new_client_master.client_name left outer join client_status_master on new_client_master.cm_id = client_status_master.cm_id join location_master t1 on new_client_master.location=t1.id';
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					if ($result) { ?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>ID</th>
									<th>Client</th>
									<th>Process</th>
									<th>Sub Process</th>
									<th>Location</th>
									<th>Status</th>
									<th>Manage Client</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="client_id">' . $value['client_id'] . '</td>';
									echo '<td class="client_name">' . $value['client_name'] . '</td>';
									echo '<td class="process">' . $value['process'] . '</td>';
									echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
									echo '<td class="location">' . $value['location'] . '</td>';
									if (empty($value['id'])) {
										echo '<td class="text-fff green">Active</td>';
										echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascirpt:return EditData(this,\'Inactive\');" id="' . $value['cm_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i></td>';
									} else {
										echo '<td class="text-fff orange">Inactive</td>';

										echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascirpt:return EditData(this,\'Active\');" id="' . $value['cm_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i></td>';
									}

									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php
					}
					?>
					<!--Reprot / Data Table End -->
				</div>
				<!--Form container End -->
			</div>
			<!--Sub Main Div for all Page End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {
		//Model Assigned and initiation code on document load
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Department_Can').trigger("click");
			}
		});
		// This code for cancel button trigger click and also for model close
		$('#btn_Client_Can').on('click', function() {
			$('#txt_Client_Name').val('');
			$('#hid_Client_ID').val('');
			$('#btn_Client_Save').removeClass('hidden');
			$('#btn_Client_Edit').addClass('hidden');
			//$('#btn_Client_Can').addClass('hidden');
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
		});

		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
		$('#btn_Client_Save').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			if ($('#txt_Client_Name').val() == 'NA') {
				$('#txt_Client_Name').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_Name').length == 0) {
					$('<span id="spantxt_Client_Name" class="help-block">Required *</span>').insertAfter('#txt_Client_Name');
				}
				validate = 1;
			}
			if ($('#txt_status').val() == 'NA') {
				$('#txt_status').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_status').length == 0) {
					$('<span id="spantxt_status" class="help-block">Required *</span>').insertAfter('#txt_status');
				}
				validate = 1;
			}

			if (validate == 1) {
				return false;
			}
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
		// This code for remove error span from all element contain .has-error class on listed events
		$(document).on("click blur focus change", ".has-error", function() {
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
		});
	});
	// This code for trigger edit on all data table also trigger model open on a Model ID
	function EditData(el, elv) {
		$('#txt_Client_Name').val(el.id);
		$('#txt_status').val(elv);
		$('#modal-content').modal('open');
		$("#modal-content input,#modal-content textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>