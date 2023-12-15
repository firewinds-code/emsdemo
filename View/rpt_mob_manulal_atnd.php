<?php
// Server Config file
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
ini_set("display_errors", "0");
if (!isset($_SESSION['__user_logid'])) {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
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
}


$msg = $searchBy = $empid = '';
$classvarr = "'.byID'";
$icardStatus = '0';
if ($isPostBack && isset($_POST['txt_dateFrom'])) {
	$date_From = $_POST['txt_dateFrom'];
} else {
	$date_From = date('Y-m-d', time());
}
//$sql="select distinct t1.emp_id as EmployeeID, t.EmployeeName, curdate() as Date,t.client_name as Client,t.Process,t.sub_process as SubProcess,case when t2.IP is null then 'Not Viewed' else 'Vewed' end as Status from manualattendance t1 left join (select distinct EmployeeID, IP from login_history_lab where cast(CreatedOn as date)='".$date_From."')t2 on t1.emp_id=t2.EmployeeID left join View_EmpinfoActive t on t1.emp_id=t.EmployeeID";
$sql = "select distinct t1.emp_id as EmployeeID, LEFT(ip, length(ip)-4) as EmployeeName,AtndDate, case when t2.IP is null then 'Not Viewed' else 'Viewed' end as Status,CreatedOn as ViewDate from manualattendance t1 left join (select max(CreatedOn) CreatedOn,EmployeeID,IP from login_history_lab where cast(CreatedOn as date)='" . $date_From . "' group by EmployeeID, IP )t2 on t1.emp_id=t2.EmployeeID where t1.AtndDate='" . $date_From . "' order by ViewDate desc";

$myDB = new MysqliDb();
$result = $myDB->query($sql);

?>

<script>
	$(function() {
		$('#txt_dateFrom').datetimepicker({
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



<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Mobile Manual Attendance</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Mobile Manual Attendance</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s3 m3">

						<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>

					<div class="input-field col s3 m3">

						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>

				</div>
			</div>

			<div id="pnlTable">

				<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
					<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						<!--<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">-->
						<thead>
							<tr>
								<th>SN.</th>
								<th>Employee ID</th>
								<th>Employee Name</th>
								<th>AtndDate</th>
								<th>Status</th>
								<th>ViewDate</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if (count($result) > 0) {
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td  id="countc' . $count . '">' . $count . '</td>';
									echo '<td  id="EmployeeID' . $count . '" >' . $value['EmployeeID'] . '</td>';
									echo '<td  id="EmployeeName' . $count . '" >' . $value['EmployeeName'] . '</td>';
									echo '<td  id="AtndDate' . $count . '"  >' . $value['AtndDate'] . '</td>';
									echo '<td  id="ViewDate' . $count . '"  >' . $value['ViewDate'] . '</td>';
									echo '<td  id="Status' . $count . '"  >' . $value['Status'] . '</td>';
							?>
							<?php
									echo '</tr>';
									$count++;
								}
							} else {
							}
							?>

						</tbody>
					</table>
				</div>


			</div>

			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->

		<!--Content Div for all Page End -->
	</div>

	<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>