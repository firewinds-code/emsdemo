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
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if (isset($_SESSION)) {
	$clean_user_log_id = clean($_SESSION['__user_logid']);
	if (!isset($clean_user_log_id)) {
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
	});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Trainer Daily Remark Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Trainer Daily Remark Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s5 m5">
					<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
				</div>
				<div class="input-field col s5 m5">
					<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
				</div>
				<div class="input-field col s2 m2">
					<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
					if (isset($_POST['btn_view'])) {
						$myDB = new MysqliDb();
						$sqlConnect = 'call getReport_TRDaily("' . $date_From . '","' . $date_To . '")';
						$myDB = new MysqliDb();
						$chk_task = $myDB->rawQuery($sqlConnect);
						$my_error = $myDB->getLastError();
						if (empty($my_error)) {
							$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
			   			 <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';

							$table .= '<th>EmployeeID</th>';
							$table .= '<th>EmployeeName</th>';
							$table .= '<th>Remarks</th>';
							$table .= '<th>Trainer</th>';
							$table .= '<th>Created On</th>';
							$table .= '<th>Designation</th>';
							$table .= '<th>DOJ</th>';
							$table .= '<th>Client</th>';
							$table .= '<th>Process</th>';
							$table .= '<th>Sub Process</th>';
							$table .= '<th>Supervisor</th>';
							$table .= '<th>Location</th>';
							$table .= '<thead><tbody>';

							foreach ($chk_task as $key => $value) {
								$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['EmployeeName'] . '</td>';
								$table .= '<td>' . trim($value['body']) . '</td>';
								$table .= '<td>' . $value['Trainer'] . '</td>';
								$table .= '<td>' . $value['createdon'] . '</td>';
								$table .= '<td>' . $value['designation'] . '</td>';
								$table .= '<td>' . $value['DOJ'] . '</td>';
								$table .= '<td>' . $value['clientname'] . '</td>';
								$table .= '<td>' . $value['Process'] . '</td>';
								$table .= '<td>' . $value['sub_process'] . '</td>';
								$table .= '<td>' . $value['ReportTo'] . '</td>';
								$table .= '<td>' . $value['location'] . '</td>';
								$table .= '</tr>';
							}
							$table .= '</tbody></table></div>';
							echo $table;
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
						}
					}

					?>

				</div>

				<!--Reprot / Data Table End -->

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>