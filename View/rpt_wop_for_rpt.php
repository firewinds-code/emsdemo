<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
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
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				/*   {
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
				}
				/*,'copy'*/
				, 'pageLength'
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
	<span id="PageTittle_span" class="hidden">Report Roster Pref</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Report Roster Pref</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div class="input-field col s5 m5">
					<input type="text" class="form-control" name="txt_dateFrom" style="min-width: 250px;" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
				</div>
				<div class="input-field col s5 m5">
					<input type="text" class="form-control" name="txt_dateTo" style="min-width: 250px;" id="txt_dateTo" value="<?php echo $date_To; ?>" />
				</div>

				<div class="input-field col s2 m2">

					<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
				</div>

				<!--Form element End-->
				<!--Reprot / Data Table start -->
				<?php
				$myDB = new MysqliDb();
				$chk_task = $myDB->query('call sp_getRoster_pref_byReportTO("' . $_SESSION['__user_logid'] . '","' . $date_From . '","' . $date_To . '")');
				//echo 'call sp_getRoster_pref_byReportTO("'.$_SESSION['__user_logid'].'","'.$date_From.'","'.$date_To.'")';
				$my_error = $myDB->getLastError();
				if (empty($my_error)) {

					$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data"><thead><tr>';

					$table .= '<th>Employee ID</th>';
					$table .= '<th>Employee Name</th>';

					$table .= '<th>Week</th>';
					$table .= '<th>Month</th>';
					$table .= '<th>First Pre.</th>';
					$table .= '<th>Second Pre.</th>';

					$table .= '<th>Account Head</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Dept. Name</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th><thead><tbody>';

					foreach ($chk_task as $key => $value) {


						$table .= '<tr>';
						$table .= '<td>' . $value['EmpID'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';

						$table .= '<td>' . $value['WeekNo'] . '</td>';
						$table .= '<td>' . date("F", strtotime(date("F", strtotime(substr($value['WeekNo'], 0, strpos($value['WeekNo'], 'To') - 1))))) . '</td>';
						$table .= '<td>' . $value['FirstPre'] . '</td>';
						$table .= '<td>' . $value['SecondPre'] . '</td>';
						$table .= '<td>' . $value['AccountHead'] . '</td>';
						$table .= '<td>' . $value['designation'] . '</td>';
						$table .= '<td>' . $value['dept_name'] . '</td>';
						$table .= '<td>' . $value['Process'] . '</td>';
						$table .= '<td>' . $value['sub_process'] . '</td></tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.info('No Data Found. " . $my_error . " '); }); </script>";
				}

				?>

				<div id="myModal" class="modal fade" role="dialog">
					<div class="modal-dialog" id="dilogData" style="width: 800px;">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Spl Report <b> Roster By Client</b> </h4>
							</div>
							<div class="modal-body">
								<p>No Data Found</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
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
<script>
	function get_popUp(el) {
		var empID = $(el).attr('DataID');
		$('.modal-body').html('<span class="text-warning">Processing Data ,Please wait for a moment...</span>  ');
		$('.modal-body').load("../Controller/getDetail_LoginRpt.php?ID=" + empID + "&DateFrom=" + $('#txt_dateFrom').val() + "&DateTo=" + $('#txt_dateTo').val());
		$('#dilogData').draggable();

	}
</script>