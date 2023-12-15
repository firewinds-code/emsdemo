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
	$clean_user_logid = clean($_SESSION['__user_logid']);
	if (!isset($clean_user_logid)) {
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
	<span id="PageTittle_span" class="hidden">Sent Message Acknowledge</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Sent Message Acknowledge Report </h4>

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
					<!--<div class="input-field col s4 m4">
			<Select name="emp_status"  style="min-width: 200px;"  id="status" >
				<option value='Active' <?php
										$clean_emp_status = cleanUserInput($_POST['emp_status']);
										if (isset($clean_emp_status) && $clean_emp_status == 'Active') {
											echo "selected";
										} ?> >Active</option>
				<option value='InActive' <?php if (isset($clean_emp_status) && $clean_emp_status == 'InActive') {
												echo "selected";
											} ?> >InActive</option>
			</Select>
		</div>	-->

					<div class="input-field col s4 m4 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>

				</div>
				<?php
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();
					/*$empStatus=$_POST['emp_status'];
				if($empStatus=='Active'){
					$tablename='whole_details_peremp';
				}elseif($empStatus=='InActive'){
					$tablename='view_for_report_inactive';
				}*/

					$select = "select a.text_msg,a.msg_date,a.to_empid,a.`status`,a.ackstatus,a.acknowledgedate,acknowledge as ackremark,  w.EmployeeName,w.clientname,w.`Process`,sub_process from tbl_chat_message a left join whole_dump_emp_data w on a.to_empid=w.EmployeeID where a.acknowledgedate between ?  and ? ";
					$selectQury = $conn->prepare($select);
					$selectQury->bind_param("ss", $date_From, $date_To);
					$selectQury->execute();
					$chk_task = $selectQury->get_result();

					// $my_error = $myDB->getLastError();
					if ($chk_task->num_rows > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								  <div class=""  >																											                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>MSG</th>';
						$table .= '<th>Employee ID</th>';
						$table .= '<th>Employee Name</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Message Sent Date & Time</th>';
						$table .= '<th>Acknowledgement Date & Time</th>';
						$table .= '<th>Acknowledgement Remarks</th>';
						$table .= '<thead><tbody>';


						foreach ($chk_task as $key => $value) {
							$table .= '<tr><td>' . wordwrap($value['text_msg'], 80, "<br>\n") . '</td>';
							$table .= '<td>' . $value['to_empid'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['msg_date'] . '</td>';
							$table .= '<td>' . $value['acknowledgedate'] . '</td>';
							$table .= '<td>' . wordwrap($value['ackremark'], 20, "<br>\n") . '</td>';
							$table .= '</tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
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
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>