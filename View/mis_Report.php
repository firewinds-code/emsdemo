<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
ini_set('display_errors', '1');
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$type = '';
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
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
			$date_To = $_POST['txt_dateTo'];
			$date_From = $_POST['txt_dateFrom'];
			$type = $_POST['txt_Type'];
			$date_on = $_POST['txt_dateOn'];
		} else {
			$date_To = date('Y', time());
			$date_From = date('m', time());
			$type = '---Select---';
			$date_on = date('Y-m-d', time());
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
?>
<style>
	#overlay {
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height: 100%;
		display: none;
		background: rgba(0, 0, 0, 0.6);
	}

	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.spinner {
		width: 80px;
		height: 80px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}

	@keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}

	.is-hide {
		display: none;
	}
</style>

<script>
	$(function() {
		/*$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});*/
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
<div id="overlay">
	<div class="cv-spinner">
		<span class="spinner"></span>
	</div>
</div>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">MIS Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>MIS Report <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">MIS Report</h4>
						<div class="modal-body">


							<div class="input-field col s6 m6">

								<select name="txt_Type" id="txt_Type">
									<option <?php echo ($type == '---Select---') ? ' selected' : ''; ?>>---Select---</option>
									<option <?php echo ($type == 'CO Data') ? ' selected' : ''; ?>>CO Data</option>
									<option <?php echo ($type == 'PL Data') ? ' selected' : ''; ?>>PL Data</option>
									<option <?php echo ($type == 'Sign In Out Report') ? ' selected' : ''; ?>>Sign In Out Report</option>
									<option <?php echo ($type == 'Total PuchInOut') ? ' selected' : ''; ?>>Total PuchInOut</option>
									<option <?php echo ($type == 'Total PuchInOut WFH') ? ' selected' : ''; ?>>Total PuchInOut WFH</option>
									<option <?php echo ($type == 'Exception Pending With') ? ' selected' : ''; ?>>Exception Pending With</option>
								</select>
								<label for="txt_Type" class="active-drop-down active">Report For</label>
							</div>
							<div class="input-field col s6 m6 rpt_mis_th">
								<select name="txt_dateFrom" id="txt_dateFrom">
									<option value='1' <?php echo ($date_From == '1') ? ' selected' : ''; ?>>January</option>
									<option value='2' <?php echo ($date_From == '2') ? ' selected' : ''; ?>>February</option>
									<option value='3' <?php echo ($date_From == '3') ? ' selected' : ''; ?>>March</option>
									<option value='4' <?php echo ($date_From == '4') ? ' selected' : ''; ?>>April</option>
									<option value='5' <?php echo ($date_From == '5') ? ' selected' : ''; ?>>May</option>
									<option value='6' <?php echo ($date_From == '6') ? ' selected' : ''; ?>>June</option>
									<option value='7' <?php echo ($date_From == '7') ? ' selected' : ''; ?>>July</option>
									<option value='8' <?php echo ($date_From == '8') ? ' selected' : ''; ?>>August</option>
									<option value='9' <?php echo ($date_From == '9') ? ' selected' : ''; ?>>September</option>
									<option value='10' <?php echo ($date_From == '10') ? ' selected' : ''; ?>>October</option>
									<option value='11' <?php echo ($date_From == '11') ? ' selected' : ''; ?>>November</option>
									<option value='12' <?php echo ($date_From == '12') ? ' selected' : ''; ?>>December</option>
								</select>
							</div>
							<div class="input-field col s6 m6">
								<select name="txt_dateTo" id="txt_dateTo">
									<option value='2016' <?php echo ($date_To == '2016') ? ' selected' : ''; ?>>2016</option>
									<option value='2017' <?php echo ($date_To == '2017') ? ' selected' : ''; ?>>2017</option>
									<option value='2018' <?php echo ($date_To == '2018') ? ' selected' : ''; ?>>2018</option>
									<option value='2019' <?php echo ($date_To == '2019') ? ' selected' : ''; ?>>2019</option>
									<option value='2020' <?php echo ($date_To == '2020') ? ' selected' : ''; ?>>2020</option>
									<option value='2021' <?php echo ($date_To == '2021') ? ' selected' : ''; ?>>2021</option>
									<option value='2022' <?php echo ($date_To == '2022') ? ' selected' : ''; ?>>2022</option>
									<option value='2023' <?php echo ($date_To == '2023') ? ' selected' : ''; ?>>2023</option>
									<option value='2024' <?php echo ($date_To == '2024') ? ' selected' : ''; ?>>2024</option>
									<option value='2025' <?php echo ($date_To == '2025') ? ' selected' : ''; ?>>2025</option>
									<option value='2026' <?php echo ($date_To == '2026') ? ' selected' : ''; ?>>2026</option>
									<option value='2027' <?php echo ($date_To == '2027') ? ' selected' : ''; ?>>2027</option>

								</select>
							</div>

							<div class="input-field col s6 m6 rpt_mis hidden">
								<input name="txt_dateOn" value="<?php echo $date_on; ?>" id="txt_dateOn" />
								<label for="txt_dateOn">Date On</label>
							</div>

							<div class="input-field col s12 m12 right-align">

								<button type="submit" class="btn waves-effect waves-green preloaderbtn" name="btn_view" id="btn_view">Search
								</button>
								<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
								<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
							</div>

						</div>
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<?php
				$myDB = new MysqliDb();
				if ($date_To >= date('Y', time()) && $date_From >= date('m', time())) {
					// echo $date_To;
					// echo $date_From;
					$date_by = date('Y-m-d', time());
				} else {
					$date_by = date('Y-m-d', strtotime($date_To . '-' . (($date_From < 10) ? '0' . $date_From : $date_From) . '-25'));
				}
				if ($type == 'CO Data') {
					//echo 'call mis_report_ComboOff("'.$date_by.'","'.$_SESSION['__user_logid'].'","'.$_SESSION['__user_type'].'")';
					$chk_task = $myDB->query('call mis_report_ComboOff("' . $date_by . '","' . $_SESSION['__user_logid'] . '","' . $_SESSION['__user_type'] . '")');
					$my_error = $myDB->getLastError();
					if (count($chk_task) > 0 && $chk_task) {


						$table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>GeneratedOn</th>';
						$table .= '<th>Used On</th>';
						$table .= '<th>ExpiredOn</th>';

						$table .= '<th>Designation</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Center</th></thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['GeneratedOn'] . '</td>';
							if ($value['usedOn'] != '') {
								$table .= '<td>' . $value['usedOn'] . '</td>';
							} else {
								$table .= '<td>Not Used</td>';
							}
							$table .= '<td>' . $value['ExpiredOn'] . '</td>';

							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td></tr>';
						}
						$table .= '</tbody></table>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Record found. " . $my_error . "'); }); </script>";
					}
				} elseif ($type == 'PL Data') {
					'call mis_report_paidleave("' . $date_From . '","' . $date_To . '","' . $_SESSION['__user_logid'] . '","' . $_SESSION['__user_type'] . '")';
					$chk_task = $myDB->query('call mis_report_paidleave("' . $date_From . '","' . $date_To . '","' . $_SESSION['__user_logid'] . '","' . $_SESSION['__user_type'] . '")');
					$my_error = $myDB->getLastError();;

					if (count($chk_task) > 0 && $chk_task) {
						$table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						foreach ($chk_task[0] as $key => $value) {
							$table .= '<th>' . $key . '</th>';
						}
						$table .= '</thead><tbody>';
						foreach ($chk_task as $key => $value) {

							$table .= '<tr>';

							foreach ($value as $key => $val) {
								$table .= '<td>' . $val . '</td>';
							}
							$table .= '</tr>';
						}
						$table .= '</tbody></table>';

						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Record found. " . $my_error . "'); }); </script>";
					}
				} elseif ($type == 'Sign In Out Report') {
					//echo 'call mis_SignInOut_Report("' . $date_on . '","' . $_SESSION['__user_logid'] . '","' . $_SESSION['__user_type'] . '")';
					$chk_task = $myDB->query('call mis_SignInOut_Report("' . $date_on . '","' . $_SESSION['__user_logid'] . '","' . $_SESSION['__user_type'] . '")');
					$my_error = $myDB->getLastError();;

					if (count($chk_task) > 0 && $chk_task) {
						$table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>InTime</th>';
						$table .= '<th>OutTime</th>';
						$table .= '<th>Date </th>';
						$table .= '<th>Location </th></thead><tbody>';
						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmpID'] . '</td>';
							$table .= '<td>' . $value['InTime'] . '</td>';
							$table .= '<td>' . $value['OutTime'] . '</td>';
							$table .= '<td>' . $value['Date'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td></tr>';
						}
						$table .= '</tbody></table>';

						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Record found. " . $my_error . "'); }); </script>";
					}
				} elseif ($type == 'Total PuchInOut') {
					//echo 'call mis_RowPunch_Report("'.$date_on.'","'.$_SESSION['__user_logid'].'","'.$_SESSION['__user_type'].'")';
					$chk_task = $myDB->query('call mis_RowPunch_Report("' . $date_on . '","' . $_SESSION['__user_logid'] . '","' . $_SESSION['__user_type'] . '")');
					$my_error = $myDB->getLastError();;

					if (count($chk_task) > 0 && $chk_task) {
						$table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>PunchTime</th>';
						$table .= '<th>Date </th>';
						$table .= '<th>Location </th>';
						$table .= '<th>Source </th>';
						$table .= '<th>Createdon </th>';
						$table .= '</thead><tbody>';

						foreach ($chk_task as $key => $value) {
							$source = '';
							if ($value['EmployeeID'] == "") {
								$source = "Manual";
							} else
			    		if ($value['EmployeeID'] == "App") {
								$source = "Mobile App";
							} else
			    		if ($value['EmployeeID'] != "App" && $value['EmployeeID'] != "") {
								$source = "Employee";
							}
							$table .= '<tr><td>' . $value['EmpID'] . '</td>';
							$table .= '<td>' . $value['PunchTime'] . '</td>';
							$table .= '<td>' . $value['Date'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td>';
							$table .= '<td>' . $source . '</td>';
							$table .= '<td>' . $value['createdon'] . '</td>';
							$table .= '</tr>';
						}
						$table .= '</tbody></table>';

						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Record found. " . $my_error . "'); }); </script>";
					}
				} elseif ($type == 'Total PuchInOut WFH') {
					//echo 'call mis_RowPunchWFH_Report("'.$date_on.'","'.$_SESSION['__user_logid'].'","'.$_SESSION['__user_type'].'")';
					$chk_task = $myDB->query('call mis_RowPunchWFH_Report("' . $date_on . '","' . $_SESSION['__user_logid'] . '","' . $_SESSION['__user_type'] . '")');
					$my_error = $myDB->getLastError();;

					if (count($chk_task) > 0 && $chk_task) {
						$table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>PunchTime</th>';
						$table .= '<th>Date </th>';
						$table .= '<th>Location </th>';
						$table .= '<th>Latitude </th>';
						$table .= '<th>Longitude </th>';
						$table .= '</thead><tbody>';

						foreach ($chk_task as $key => $value) {
							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['PunchTime'] . '</td>';
							$table .= '<td>' . $value['Date'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td>';
							$table .= '<td>' . $value['lat'] . '</td>';
							$table .= '<td>' . $value['lng'] . '</td>';
							$table .= '</tr>';
						}
						$table .= '</tbody></table>';

						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Record found. " . $my_error . "'); }); </script>";
					}
				} elseif ($type == 'Exception Pending With') {
					$myDB = new MysqliDb();

					if ($_SESSION['__user_type'] == "ADMINISTRATOR" || $_SESSION['__user_type'] == "CENTRAL MIS" || $_SESSION['__user_type'] == "CE021929762") {
						echo "SELECT t1.EmployeeName PendingWith,t2.Exception,count(t2.EmployeeID) Count,date_format(t2.createdon,'%d %M,%Y') PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID inner join personal_details t1 on t3.account_head=t1.EmployeeID join location_master t4 on t4.id = t1.location where t2.MgrStatus='Pending' and t3.account_head!=t2.EmployeeID group by t1.EmployeeName,t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus union SELECT 'NITIN SAHNI' Name,t2.Exception,count(t2.EmployeeID) Count,cast(t2.createdon as date) PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID join location_master t4 on t4.id = t3.location  where t2.MgrStatus='Pending' and t3.ReportTo='CE07147134' group by t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus";
						$chk_task = $myDB->query("SELECT t1.EmployeeName PendingWith,t2.Exception,count(t2.EmployeeID) Count,date_format(t2.createdon,'%d %M,%Y') PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID inner join personal_details t1 on t3.account_head=t1.EmployeeID join location_master t4 on t4.id = t1.location where t2.MgrStatus='Pending' and t3.account_head!=t2.EmployeeID group by t1.EmployeeName,t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus union SELECT 'NITIN SAHNI' Name,t2.Exception,count(t2.EmployeeID) Count,cast(t2.createdon as date) PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID join location_master t4 on t4.id = t3.location  where t2.MgrStatus='Pending' and t3.ReportTo='CE07147134' group by t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus");
					} else {

						$chk_task = $myDB->query("SELECT t1.EmployeeName PendingWith,t2.Exception,count(t2.EmployeeID) Count,date_format(t2.createdon,'%d %M,%Y') PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID inner join personal_details t1 on t3.account_head=t1.EmployeeID join location_master t4 on t4.id = t1.location where t2.MgrStatus='Pending' and t3.account_head!=t2.EmployeeID and t3.account_head='" . $_SESSION['__user_logid'] . "' group by t1.EmployeeName,t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus union SELECT 'NITIN SAHNI' Name,t2.Exception,count(t2.EmployeeID) Count,cast(t2.createdon as date) PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID join location_master t4 on t4.id = t3.location  where t2.MgrStatus='Pending' and t3.ReportTo='CE07147134' and t3.account_head='" . $_SESSION['__user_logid'] . "' group by t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus;");
					}

					$my_error = $myDB->getLastError();;
					if (count($chk_task) > 0 && $chk_task) {
						$table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>PendingWith</th>';
						$table .= '<th>Exception</th>';
						$table .= '<th>Count</th>';
						$table .= '<th>PendingSince</th>';
						$table .= '<th>MgrStatus</th>';
						$table .= '<th>Location</th><thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['PendingWith'] . '</td>';
							$table .= '<td>' . $value['Exception'] . '</td>';
							$table .= '<td>' . $value['Count'] . '</td>';
							$table .= '<td>' . $value['PendingSince'] . '</td>';
							$table .= '<td>' . $value['MgrStatus'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td></tr>';
						}
						$table .= '</tbody></table>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Record found. " . $my_error . "'); }); </script>";
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

			if ($(this).val() == 'Sign In Out Report' || $(this).val() == 'Total PuchInOut' || $(this).val() == 'Total PuchInOut WFH') {

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
					if ($('#' + spanID).size() == 0) {
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
			var codata = $('#txt_Type').val();
			var usrid = <?php echo "'" . $_SESSION["__user_logid"] . "'"; ?>;
			var usrtype = <?php echo "'" . $_SESSION["__user_type"] . "'"; ?>;
			var admin = <?php echo "'" . $_SESSION["__user_type"]  . "'"; ?>;
			// alert(codata)
			if (codata == 'CO Data') {
				// $("#preloader").show();
				// $("select").find('option:eq(0)').each(function() {
				// 	if ($(this).text().toUpperCase().indexOf('-SELECT-') >= 0 && ($(this).val().toUpperCase() == "" || $(this).val().toUpperCase() == "NA")) {
				// 		var lbl_text = $(this).closest("div.select-wrapper").next("label.active").text();
				// 		var PreSuffix = 'Select ';
				// 		// if (lbl_text.toUpperCase().indexOf('SELECT') >= 0) {
				// 		// 	PreSuffix = '';
				// 		// }
				// 		// if (lbl_text != '' && lbl_text != undefined) {
				// 		// 	lbl_text = lbl_text;
				// 		// }
				// 		// var finalOption = toTitleCase(PreSuffix + lbl_text);
				// 		// $(this).text(finalOption);
				// 	}
				// });
				var dateTo = $('#txt_dateTo').val();
				// alert(dateTo)
				var dateFrom = $('#txt_dateFrom').val();
				//alert(dateTo);
				//alert(dateFrom);
				var date = dateTo + "-" + dateFrom + "-1";
				var sp = "call mis_report_ComboOff('" + date + "','" + usrid + "','" + usrtype + "')";
				var url = "textExport.php?sp=" + sp;
				// alert(url);
				window.location.href = url;
				return false;
			} else if (codata == 'PL Data') {
				var date_From = $('#txt_dateFrom').val();
				var date_To = $('#txt_dateTo').val();
				var sp = "call mis_report_paidleave('" + date_From + "','" + date_To + "','" + usrid + "','" + usrtype + "')";
				var url = "textExport.php?sp=" + sp;
				// alert(url);
				window.location.href = url;
				return false;
			} else if (codata == 'Sign In Out Report') {
				var date_on = $('#txt_dateOn').val();
				var sp = "call mis_SignInOut_Report('" + date_on + "','" + usrid + "','" + usrtype + "')";
				var url = "textExport.php?sp=" + sp;
				//alert(url);
				window.location.href = url;
				return false;
			} else if (codata == 'Total PuchInOut') {
				var date_on = $('#txt_dateOn').val();
				var sp = "call mis_RowPunch_Report('" + date_on + "','" + usrid + "','" + usrtype + "')";
				var url = "textExport.php?sp=" + sp;
				//alert(url);
				window.location.href = url;
				return false;
			} else if (codata == 'Total PuchInOut WFH') {
				var date_on = $('#txt_dateOn').val();
				var sp = "call mis_RowPunchWFH_Report('" + date_on + "','" + usrid + "','" + usrtype + "')";
				var url = "textExport.php?sp=" + sp;
				//alert(url);
				window.location.href = url;
				return false;
			} else if (codata == 'Exception Pending With') {
				if (admin == "ADMINISTRATOR" || admin == "CENTRAL MIS"  || usrid == "CE021929762") {
					var sp = "SELECT t1.EmployeeName PendingWith,t2.Exception,count(t2.EmployeeID) Count,date_format(t2.createdon,'%d %M,%Y') PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID inner join personal_details t1 on t3.account_head=t1.EmployeeID join location_master t4 on t4.id = t1.location where t2.MgrStatus='Pending' and t3.account_head!=t2.EmployeeID group by t1.EmployeeName,t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus union SELECT 'NITIN SAHNI' Name,t2.Exception,count(t2.EmployeeID) Count,cast(t2.createdon as date) PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID join location_master t4 on t4.id = t3.location  where t2.MgrStatus='Pending' and t3.ReportTo='CE07147134' group by t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus";
				} else {
					var sp = "SELECT t1.EmployeeName PendingWith,t2.Exception,count(t2.EmployeeID) Count,date_format(t2.createdon,'%d %M,%Y') PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID inner join personal_details t1 on t3.account_head=t1.EmployeeID join location_master t4 on t4.id = t1.location where t2.MgrStatus='Pending' and t3.account_head!=t2.EmployeeID and t3.account_head='" + usrid + "' group by t1.EmployeeName,t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus union SELECT 'NITIN SAHNI' Name,t2.Exception,count(t2.EmployeeID) Count,cast(t2.createdon as date) PendingSince,t2.MgrStatus,t4.location FROM exception t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID join location_master t4 on t4.id = t3.location  where t2.MgrStatus='Pending' and t3.ReportTo='CE07147134' and t3.account_head='" + usrid + "' group by t2.Exception,cast(t2.createdon as date) ,t2.MgrStatus;";
				}
				var url = "textExport.php?sp=" + sp;
				// alert(url);
				window.location.href = url;
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