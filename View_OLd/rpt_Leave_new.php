<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

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
			}
		} else {
			$date_To = date('Y-m-d', time());
			$date_From = date('Y-m-d', time());
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();


if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	if (!empty($_POST['clientName'])) {
		$clientname = cleanUserInput($_POST['clientName']);
	}
	$empStatus = cleanUserInput($_POST['emp_status']);
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
	<span id="PageTittle_span" class="hidden">Leave Status Report New</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Leave Status Report New</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s4 m4">
						<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<Select name="emp_status" style="min-width: 200px;" id="status">
							<option value='Active' <?php $emp_status = cleanUserInput($_POST['emp_status']);
													if (isset($emp_status) && $emp_status == 'Active') {
														echo "selected";
													} ?>>Active</option>
							<option value='InActive' <?php if (isset($emp_status) && $emp_status == 'InActive') {
															echo "selected";
														} ?>>InActive</option>
						</Select>
					</div>

					<div class="input-field col s6 m6 l6">
						<select id="clientName" name="clientName">
							<option Selected="True" Value="NA">-Select One-</option>
							<option Value="ALL">ALL</option>
							<?php
							// $myDB = new MysqliDb();
							// $result = $myDB->query('select distinct t1.client_name ID,t3.client_name from new_client_master t1 join report_map t2 on t1.cm_id=t2.processID join client_master t3 on t1.client_name=t3.client_id where t2.EmpID="' . $_SESSION['__user_logid'] . '" and reportID=4');
							$EmpID = clean($_SESSION['__user_logid']);
							$Query = 'select distinct t1.client_name ID,t3.client_name from new_client_master t1 join report_map t2 on t1.cm_id=t2.processID join client_master t3 on t1.client_name=t3.client_id where t2.EmpID=? and reportID=4';

							$stmt = $conn->prepare($Query);
							$stmt->bind_param("s", $EmpID);
							if (!$stmt) {
								echo "failed to run";
								die;
							}
							$stmt->execute();
							$result = $stmt->get_result();

							$my_error = $myDB->getLastError();
							foreach ($result as $key => $value) {
								echo '<option value="' . $value['ID'] . '">' . $value['client_name'] . '</option>';
							} ?>
						</select>
						<label for="clientName" class="active-drop-down active">Client Name</label>
					</div>
					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>

				</div>
				<?php
				$btnview = isset($_POST['btn_view']);
				if ($btnview) {
					$myDB = new MysqliDb();

					if ($empStatus == 'Active') {
						$tablename = 'whole_details_peremp';
					} elseif ($empStatus == 'InActive') {
						$tablename = 'view_for_report_inactive';
					}
					$emp = clean($_SESSION['__user_logid']);
					$chk_task = $myDB->query('call sp_getLeaveStatus_new("' . $emp . '","' . $date_From . '","' . $date_To . '","' . $empStatus . '","' . $clientname . '")');
					// echo 'call sp_getLeaveStatus_new("' . $_SESSION['__user_logid'] . '","' . $date_From . '","' . $date_To . '","' . $empStatus . '","' . $clientname . '")';
					$my_error = $myDB->getLastError();
					if (count($chk_task) > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								  <div class=""  >																											                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>OPS Status</th>';
						$table .= '<th>Account Head Status</th>';
						$table .= '<th>FinalStatus</th>';
						$table .= '<th>DateCreated</th>';
						$table .= '<th>DateFrom</th>';
						$table .= '<th>DateTo</th>';

						$table .= '<th>Leave Status</th>';
						$table .= '<th>Count Of Leave</th>';

						$table .= '<th>Designation</th>';
						$table .= '<th>Dept Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Location</th>';
						$table .= '<th>Supervisor</th>';

						$table .= '<th>Account Head</th>';
						$table .= '<th>Ops Head</th>';

						$table .= '<th>ModifiedBy</th>';
						$table .= '<th>ModifiedOn</th>';
						$table .= '<th>Approved By</th>';

						$table .= '<th>Comments</th><thead><tbody>';


						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['HRStatusID'] . '</td>';
							$table .= '<td>' . $value['MngrStatusID'] . '</td>';
							$table .= '<td>' . $value['FinalStatus'] . '</td>';
							$table .= '<td>' . $value['DateCreated'] . '</td>';
							$table .= '<td>' . $value['DateFrom'] . '</td>';
							$table .= '<td>' . $value['DateTo'] . '</td>';
							$table .= '<td>' . $value['LeaveType'] . '</td>';
							if ($value['LeaveType'] == 'Leave') {
								$table .= '<td>' . $value['TotalLeaves'] . '</td>';
							} else {
								$table .= '<td>' . round(intval($value['TotalLeaves']) / 2, 1) . '</td>';
							}

							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['dept_name'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td>';
							$table .= '<td>' . $value['Supervisor'] . '</td>';
							if ($value['ManagerComment'] == ' Approved By SERVER ') {
								$table .= '<td>SERVER</td>';
							} elseif ($value['FinalStatus'] == 'Pending' || $value['FinalStatus'] == '') {
								$table .= '<td></td>';
							} else {
								$table .= '<td>ACCOUNT HEAD</td>';
							}
							if ($value['HRComents'] == ' Approved By SERVER ') {
								$table .= '<td>SERVER</td>';
							} else {
								$table .= '<td>OPS HEAD</td>';
							}



							$table .= '<td>' . $value['account_head'] . '</td>';
							$table .= '<td>' . $value['DateModified'] . '</td>';
							$comment = explode('|', $value['Comments']);

							$string1 = 'Ops Head By Server';
							$string2 = 'Account Head By Server';
							$modify = (empty($value['DateModified'])) ? '' : '(' . date('Y-m-d', strtotime($value['DateModified'])) . ')';
							$attr_val = $modify . ' ' . $value['ModifiedBy'];
							$attr = '';
							foreach ($comment as $url) {

								if (preg_match("/\b$string1\b/i", $url)) {
									$attr  .=  $url . ' | ';
								}
								if (preg_match("/\b$string2\b/i", $url)) {
									$attr  .=  $url . ' | ';
								}
							}
							if (!empty($attr))
								$attr_val = $attr;
							$table .= '<td>' . $attr_val . '</td>';

							$table .= '<td>' . $value['Comments'] . '</td></tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found $my_error'); }); </script>";
					}
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
		if ($('#clientName').val() == 'NA') {
			$('#clientName').addClass('has-error');
			if ($('#spanclientName').length == 0) {
				$('<span id="spanclientName" class="help-block">Required*</span>').insertAfter('#clientName');
			}
			validate = 1;
		}
		if (validate == 1) {
			$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
			$('#alert_message').show().attr("class", "SlideInRight animated");
			$('#alert_message').delay(50000).fadeOut("slow");
			return false;
		}
		var dept = $('#clientName').val();
		if (dept == 'ALL') {
			//call sp_get_roster_Report("CE01145570","Apr","2022","Compliance","Active","1")
			//$chk_task = 'call sp_get_roster_Report("' . $_SESSION['__user_logid'] . '","' . $date_To . '","' . $date_From . '","' . $dept . '","' . $EmpStatus . '","' . $_SESSION["__location"] . '")';
			var usrid = <?php echo "'" . $_SESSION["__user_logid"] . "'"; ?>;
			// alert(usrid);
			var loc = <?php echo "'" . $_SESSION["__location"] . "'"; ?>;
			var date_from = $('#txt_dateFrom').val();
			// alert(date_from);
			var date_to = $('#txt_dateTo').val();
			// alert(date_to);
			var status = $('#status').val();
			//var type = 'ADMINISTRATOR';
			//'call sp_get_atnd_Report_new("' . $_SESSION['__user_logid'] . '","' . $date_To . '","' . $date_From . '","' . $clientname . '") ';
			var sp = "call sp_getLeaveStatus_new('" + usrid + "','" + date_from + "','" + date_to + "','" + status + "','" + dept + "')";
			var url = "textExport.php?sp=" + sp;
			// alert(url);
			window.location.href = url;
			return false;
		}
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>