<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$user_logid = clean($_SESSION['__user_logid']);
$user_client_name = clean($_SESSION["__user_client_name"]);
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}
$alert = '';
if (isset($_POST['ics_submit'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$myDB = new MysqliDb();
		$array_of_values = array("EmployeeID" => $user_logid);
		$flag = $myDB->insert("ics_tp_ispcc_acknowledge", $array_of_values);
		if ($flag) {
			//$alert = 'Your acknowledgement for this document is done...';
		}
	}
}
?>
<script>
	$(function() {
		/*$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});*/



		// DataTable
		var table = $('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				{
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
				'print',
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
				}, 'copy', 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"sScrollY": "300",
			"bScrollCollapse": true,
			"bLengthChange": false

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});;

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
	<?php if ($user_client_name == "Idea") { ?>
		<span id="PageTittle_span" class="hidden">ICL Policy - Idea</span>
	<?php } else if ($user_client_name == "Kent") { ?>
		<span id="PageTittle_span" class="hidden">ICL Policy - Kent</span>
	<?php } ?>


	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->

			<?php if ($user_client_name == "Idea") { ?>
				<h4>ICL Policy - Idea</h4>
			<?php } else if ($user_client_name == "Kent") { ?>

				<h4>ICL Policy - Kent</h4>
			<?php } ?>


			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php if ($user_client_name == "Idea") { ?>
					<embed src="../FileContainer/ICL.pdf#view=fit" type="application/pdf" style="height: 400px;overflow-y: auto;width :100%" />
				<?php } else if ($user_client_name == "Kent") { ?>
					<embed src="../FileContainer/ISMS Kent.pdf#view=fit" type="application/pdf" style="height: 400px;overflow-y: auto;width :100%" />
				<?php } ?>


				<?php
				$myDB = new MysqliDb();
				// $default = "select EmployeeID from ics_tp_ispcc_acknowledge where EmployeeID='" . $user_logid . "' ";
				$default = "select EmployeeID from ics_tp_ispcc_acknowledge where EmployeeID=? ";
				$stmt = $conn->prepare($default);
				$stmt->bind_param("s", $user_logid);
				$stmt->execute();
				$check_emp_icl = $stmt->get_result();
				// print_r($check_emp_icl);
				// die;
				// $check_emp_icl = $myDB->query($default);
				if ($check_emp_icl->num_rows > 0 && $check_emp_icl) {
				?>
					<div class="alert alert-success" style="margin: 0px;margin-top: 5px;border-left: 2px solid green;border-right: 2px solid green;font-size: 16px;text-shadow: 1px 1px 1px #00000026;font-weight: bold;font-family: serif;">Your acknowledgement for this document is done...</div>
				<?php
				} else {
				?>
					<input type="submit" name="ics_submit" id="ics_submit" class="btn wave wave-light" style="margin-top: 10px;" value="Acknowledge" />
				<?php
				}

				?>


			</div>
		</div>
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>