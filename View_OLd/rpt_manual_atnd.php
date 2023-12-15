<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
ini_set("display_errors", "0");

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logID = clean($_SESSION['__user_logid']);
if (!isset($user_logID)) {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
} else {
	if ($user_logID == 'CE10091236' || $user_logID == 'CE03070003' || $user_logID == 'CE01145570') {


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
	} else {
		$location = URL . 'Login';
		echo "<script>location.href='" . $location . "'</script>";
	}
}


$msg = $searchBy = $empid = '';
$classvarr = "'.byID'";
$icardStatus = '0';
if ($isPostBack && isset($_POST['txt_dateTo'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$date_To = cleanUserInput($_POST['txt_dateTo']);
		$date_From = cleanUserInput($_POST['txt_dateFrom']);
	}
} else {
	$date_To = date('Y-m-d', time());
	$d = new DateTime($date_To);
	$d->modify('first day of this month');
	//$date_From= $d->format('Y-m-d');
	$date_From = date('Y-m-d', time());
}

//id, emp_id, AtndDate, InTime, OutTime, Attendance, NetHours, Performance, created_at
// $sql = "select emp_id as EmployeeID, AtndDate, InTime, OutTime, Attendance, NetHours, Performance, created_at from manualattendance where  cast(AtndDate as date) between '" . $date_From . "' and '" . $date_To . "' ";
$sql = "select emp_id as EmployeeID, AtndDate, InTime, OutTime, Attendance, NetHours, Performance, created_at from manualattendance where  cast(AtndDate as date) between ? and ? ";

$selectQ = $conn->prepare($sql);
$selectQ->bind_param("ss", $date_From, $date_To);
$selectQ->execute();
$result = $selectQ->get_result();
// print_r($result);
?>

<script>
	$(document).ready(function() {
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
			"iDisplayLength": 10,
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
	<span id="PageTittle_span" class="hidden">Manual Attendance Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manual Attendance Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s3 m3">

						<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s3 m3">

						<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
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
								<th>EmployeeID</th>
								<th>Attendance Date</th>
								<th>InTime</th>
								<th>OutTime</th>
								<th>Attendance</th>
								<th>NetHours</th>
								<th>Performance</th>
								<th>Created At</th>

							</tr>
						</thead>
						<tbody>
							<?php

							$count = 1;
							if ($result->num_rows > 0) {
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td  id="countc' . $count . '">' . $count . '</td>';
									echo '<td  id="empid' . $count . '" >' . $value['EmployeeID'] . '</td>';
									echo '<td  id="AtndDate' . $count . '"  >' . $value['AtndDate'] . '</td>';
									echo '<td  id="InTime' . $count . '"  >' . $value['InTime'] . '</td>';
									echo '<td  id="OutTime' . $count . '"  >' . $value['OutTime'] . '</td>';
									echo '<td  id="Attendance' . $count . '"  >' . $value['Attendance'] . '</td>';
									echo '<td  id="NetHours' . $count . '"  >' . $value['NetHours'] . '</td>';
									echo '<td  id="Performance' . $count . '"  >' . $value['Performance'] . '</td>';
									echo '<td  id="created_at' . $count . '"  >' . $value['created_at'] . '</td>';
									echo '</tr>';
									$count++;
								}
							} else {
								echo "<tr><td colspan='6'>Data not found</td></tr>";
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