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
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;

$user_logid = clean($_SESSION['__user_logid']);

if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;
		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$show = ' hidden';
$alert_msg = '';
$link = $btn_view = $btn_view1 = '';
$btn_add = isset($_POST['btn_add']);
if ($btn_add) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$show = '';
		$empID = cleanUserInput($_POST['txtEmployeeID']);
		$cardNo = cleanUserInput($_POST['txtCardNo']);
		$txtSiteNo = cleanUserInput($_POST['txtSiteNo']);
		$myDB = new MysqliDb();
		$flag = $myDB->query('call manage_AccessCard("' . $empID . '","' . $cardNo . '","' . $user_logid . '","' . $txtSiteNo . '")');
		$error = $myDB->getLastError();
		if (empty($error)) {
			echo "<script>$(function(){ toastr.success('Record saved successfully.'); }); </script>";
		} else {
			echo "<script>$(function(){ toastr.success('Record not saved. $error'); }); </script>";
		}
		$show = ' hidden';
		/*//$alert_msg = 'Click to link button given below for Appointment Letter';
		$link = '<div id="div_offerltr"><a href="#" class="button button-action" data_empID="'.$empID.'" id="a_print_card"><i class="fa fa-link"></i> Appointment Letter for '.$empName.'</a></div>';*/
	}
} else {
	$show = ' hidden';
	$empName = $empID = '';
}
?>
<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$("#myTable .text-danger").css('color', 'red');
		$("#myTable .text-success").css('color', 'green');
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [{
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
	<span id="PageTittle_span" class="hidden">Access Card</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Access Card</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php

				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div class="col s12 m12">

					<div class="input-field col s4 m4">

						<select name="txtEmployeeID" id="txtEmployeeID" required>
							<?php
							$myDB = new MysqliDb();
							$result = $myDB->query('select EmployeeID,EmployeeName from whole_details_peremp order by EmployeeName');
							if (count($result) > 0) {
								foreach ($result as $key => $val) {
									echo '<option value="' . $val['EmployeeID'] . '">' . $val['EmployeeName'] . '( ' . $val['EmployeeID'] . ' )</option>';
								}
							}

							?>
						</select>
						<label for="txtEmployeeID" class="active-drop-down active">Employee</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" name="txtSiteNo" id="txtSiteNo" required>
						<label for="txtSiteNo">Site No.</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" name="txtCardNo" id="txtCardNo" required>
						<label for="txtCardNo">Access Card</label>
					</div>

					<div class="input-field col s12 m12 right-align">
						<button type="submit" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green" name="btn_add" id="btn_add">Submit</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>

					<?php
					$myDB = new MysqliDb();
					$chk_task = $myDB->query('select access_card_master.*,whole_details_peremp.EmployeeName,whole_details_peremp.DOJ,whole_details_peremp.designation,whole_details_peremp.dept_name,whole_details_peremp.clientname,whole_details_peremp.Process,whole_details_peremp.sub_process from whole_details_peremp inner join access_card_master on access_card_master.EmployeeID = whole_details_peremp.EmployeeID left outer join personal_details on access_card_master.CreatedBy = personal_details.EmployeeID left outer join personal_details pd1 on access_card_master.modifiedby = pd1.EmployeeID');
					$my_error = $myDB->getLastError();
					if (count($chk_task) > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><div class="">
				<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Site Code</th>';
						$table .= '<th>Access Card</th>';
						$table .= '<th>Created By</th>';
						$table .= '<th>Created On</th>';

						$table .= '<th>Modified By</th>';
						$table .= '<th>Modified On</th>';

						$table .= '<th>Confirmation</th>';
						$table .= '<th>Confirm On</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Dept Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th><thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['site_code'] . '</td>';
							$table .= '<td>' . $value['card_no'] . '</td>';
							$table .= '<td>' . $value['CreatedBy'] . '</td>';
							$table .= '<td>' . $value['createdOn'] . '</td>';
							$table .= '<td>' . $value['modifiedby'] . '</td>';
							$table .= '<td>' . $value['modifiedOn'] . '</td>';
							if ($value['confirmation'] == 1) {
								$table .= '<td class="text-success"><b>Done</b></td>';
							} else {
								$table .= '<td class="text-danger"><b>Pending</b></td>';
							}

							$table .= '<td>' . $value['conf_On'] . '</td>';

							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['dept_name'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td></tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.info('No Data Found " . $my_error . "'); }); </script>";
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
	// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.

	$('#btn_add').on('click', function() {
		var validate = 0;
		var alert_msg = '';
		// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
		$("input,select,textarea").each(function() {
			var spanID = "span" + $(this).attr('id');
			$(this).removeClass('has-error');
			if ($(this).is('select')) {
				$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
			}
			var attr_req = $(this).attr('required');
			if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
				validate = 1;
				$(this).addClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
				}
				if ($('#' + spanID).length == 0) {
					$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
				}
				var attr_error = $(this).attr('data-error-msg');
				if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
					$('#' + spanID).html('Required *');
				} else {
					$('#' + spanID).html($(this).attr("data-error-msg"));
				}



			}
		})

		if (validate == 1) {
			$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
			$('#alert_message').show().attr("class", "SlideInRight animated");
			$('#alert_message').delay(50000).fadeOut("slow");
			return false;
		}

	});
</script>