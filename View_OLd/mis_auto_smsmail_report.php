<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$type = '';
$user_logid = clean($_SESSION['__user_logid']);
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
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

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST)) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$date_To = cleanUserInput($_POST['txt_dateTo']);
				$date_From = cleanUserInput($_POST['txt_dateFrom']);
				$type = cleanUserInput($_POST['txt_Type']);
				$date_on = cleanUserInput($_POST['txt_dateOn']);
			}
		} else {
			$date_To = date('Y-m-d', time());
			$date_From = date('Y-m-d', time());
			$type = '---Select---';
			$date_on = date('Y-m-d', time());
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
?>

<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
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
			"iDisplayLength": 25,
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
		$('#txt_dateOn').datepicker({
			dateFormat: 'yy-mm-dd'
		});
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Auto SMS-Mail Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Auto SMS-Mail Report <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

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
						<h4 class="col s12 m12 model-h4">Auto SMS-Mail Report</h4>
						<div class="modal-body">


							<div class="input-field col s6 m6">

								<select name="txt_Type" id="txt_Type">
									<option <?php echo ($type == '---Select---') ? ' selected' : ''; ?>>---Select---</option>
									<option <?php echo ($type == 'EMS Login Notification') ? ' selected' : ''; ?>>EMS Login Notification</option>
									<option <?php echo ($type == 'NCNS Notification') ? ' selected' : ''; ?>>NCNS Notification</option>
									<option <?php echo ($type == 'Welcome Notification') ? ' selected' : ''; ?>>Welcome Notification</option>

								</select>
								<label for="txt_Type" class="active-drop-down active">Report For</label>
							</div>
							<div class="input-field col s6 m6 rpt_mis_th">
								<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
								<label for="txt_dateFrom" class="active-drop-down active">From</label>
							</div>
							<div class="input-field col s6 m6">
								<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
								<label for="txt_dateTo" class="active-drop-down active">To</label>
							</div>

							<div class="input-field col s6 m6 rpt_mis hidden">
								<input name="txt_dateOn" value="<?php echo $date_on; ?>" id="txt_dateOn" />
								<label for="txt_dateOn">Date On</label>
							</div>

							<div class="input-field col s12 m12 right-align">

								<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
								<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
								<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
							</div>

						</div>
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<?php

				/*if($date_To >= date('Y',time()) && $date_From >= date('m',time()))
			{
				$date_by = date('Y-m-d',time());
			}
			else
			{
				$date_by = date('Y-m-t',strtotime($date_To.'-'.(($date_From < 10)?'0'.$date_From:$date_From).'-25'));
			}*/
				$query = "";
				if ($type == 'EMS Login Notification') {
					$query = 'select employeeid,mobile, EmailAddress,emailStatus,createdOn as DateTime from login_ncns_smsmail where type="Login" and cast(createdOn as date) between ? and ?';
					$selectQ = $conn->prepare($query);
					$selectQ->bind_param("ss", $date_From, $date_To);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();
				} else if ($type == 'NCNS Notification') {
					$query = 'select employeeid,mobile, EmailAddress,emailStatus,createdOn as DateTime from login_ncns_smsmail where type="NCNS" and cast(createdOn as date) between ? and ? ';
					$selectQ = $conn->prepare($query);
					$selectQ->bind_param("ss", $date_From, $date_To);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();
					// print_r($chk_task);
					// die;
				} else if ($type == 'Welcome Notification') {
					$query = 'select employeeid,mobile, EmailAddress,emailStatus,createdOn as DateTime from welcome_msg_smsmail where cast(createdOn as date) between ? and ? ';
					$selectQ = $conn->prepare($query);
					$selectQ->bind_param("ss", $date_From, $date_To);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();
				}

				if ($type != "---Select---") {

					// $chk_task = $selectQ->get_result();
					// $chk_task = $myDB->query($query);
					// $my_error = $myDB->getLastError();
					if ($chk_task->num_rows > 0 && $chk_task) {


						$table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>Mobile</th>';
						$table .= '<th>Email Address</th>';
						$table .= '<th>Email Status</th>';
						$table .= '<th>DateTime</th>';
						$table .= '</thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['employeeid'] . '</td>';
							$table .= '<td>' . $value['mobile'] . '</td>';
							$table .= '<td>' . $value['EmailAddress'] . '</td>';
							$table .= '<td>' . $value['emailStatus'] . '</td>';
							$table .= '<td>' . $value['DateTime'] . '</td>';
							$table .= '</tr>';
						}
						$table .= '</tbody></table>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Record found. '); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.info('Select Type of Report'); }); </script>";
				}


				?>

				<!--Reprot / Data Table End -->
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>


<script>
	$(function() {
		$('#txt_Type').change(function() {

			if ($(this).val() == 'Sign In Out Report' || $(this).val() == 'Total PuchInOut') {

				$('.rpt_mis').removeClass('hidden');
				$('.rpt_mis_th').addClass('hidden');
			} else if ($(this).val() == 'Exception Pending With') {
				$('.rpt_mis').addClass('hidden');
				$('.rpt_mis_th').addClass('hidden');
			} else {
				$('.rpt_mis').addClass('hidden');
				$('.rpt_mis_th').removeClass('hidden');
			}

		});
		$('#txt_Type').trigger('change');
	});

	$(document).ready(function() {
		//Model Assigned and initiation code on document load
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Can').trigger("click");
			}
		});

		// This code for cancel button trigger click and also for model close
		$('#btn_Can').on('click', function() {
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
		$('#btn_view').on('click', function() {
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

			if ($('#txt_Type').val() == "---Select---") {
				return false;
			}

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
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

	});
</script>