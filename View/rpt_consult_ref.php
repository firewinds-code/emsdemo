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
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else if (!($_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE03070003' || $_SESSION['__user_logid'] == 'CE01145570' || $_SESSION['__user_logid'] == 'CE0820912500')) {
		die("access denied ! It seems like you try for a wrong action.");
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Report Consultancy Reference</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Report Consultancy Reference </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

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
							}, 'pageLength'],
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





				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s4 m4">
						<input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<input type="text" class="form-control" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>

					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
							<i class="fa fa-search"></i> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>
				</div>
				<?php
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();

					$chk_task = $myDB->query('call rpt_consultancy_ref_data("' . $date_From . '","' . $date_To . '")');
					$my_error = $myDB->getLastError();
					if (empty($my_error) && $chk_task) {
						$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>Candidate Name</th>';
						$table .= '<th>Mobile</th>';
						$table .= '<th>EmailID</th>';
						$table .= '<th>Status</th>';
						$table .= '<th>Consultancy Name</th>';
						$table .= '<th>Location</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>remarks</th>';
						$table .= '<th>Action Date</th>';
						$table .= '<th>upload date</th>';
						$table .= '<th>Docs</th><thead><tbody>';


						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['candidate_name'] . '</td>';
							$table .= '<td>' . $value['mobile'] . '</td>';
							$table .= '<td>' . $value['candidate_email'] . '</td>';
							$table .= '<td>' . $value['Status'] . '</td>';
							$table .= '<td>' . $value['ConsultancyName'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['remarks'] . '</td>';
							$table .= '<td>' . $value['Action Date'] . '</td>';
							$table .= '<td>' . $value['upload date'] . '</td>';
							if ($value['Docs'] == "NA") {
								$table .= '<td>' . $value['Docs'] . '</td></tr>';
							} else {
								$imgpath = URL . 'Consultancy/' . $value['Docs'];
								$table .= '<td><i class="material-icons download_item imgBtn imgBtnDownload tooltipped" data-position="left" data-tooltip="Download File"><a href=' . $imgpath . ' target="_blank">ohrm_file_download</a></i></td></tr>';
							}
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
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
	$(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(10000).fadeOut("slow");
		}
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>