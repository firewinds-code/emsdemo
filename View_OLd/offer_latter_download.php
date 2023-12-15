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
	} else {
		$isPostBack = false;
		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$show = ' hidden';
$link = $btn_view = $alert_msg = $btn_view1 = '';


$show = ' hidden';
$empName = $empID = '';

?>

<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$("#myTable .text-danger").css('color', 'red');
		$("#myTable .text-success").css('color', 'green');
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


		$('#btn_view').click(function() {

			var txtEmployeeID = $('#txtEmployeeID').val();

			$.ajax({
				url: "../Controller/OLDownloadIssueALetter.php?EmpID=" + txtEmployeeID,
				success: function(result) {
					if (result == 1) {
						$('#btn_add_' + txtEmployeeID).closest('tr').find('.Appointment_Letter').text('Issued');
						alert_msg = 'Appointment Letter issued successfully';
						$(function() {
							toastr.success(alert_msg);
						});
					}
				}
			});

			var popup = window.open("../Controller/get_OfferLater.php?EmpID=" + $(this).attr('data-id'), "popupWindow", "width=600px,height=600px,scrollbars=yes");

			return false;

		});
		$('#btn_view1').click(function() {
			var txtEmployeeID = $('#txtEmployeeID').val();
			$.ajax({
				url: "../Controller/OLDownloadIssueIdCard.php?EmpID=" + txtEmployeeID,
				success: function(result) {

					if (result) {
						$('#btn_add_' + txtEmployeeID).closest('tr').find('.ID_Card').text('Issued');
						alert_msg = 'Id Card issued successfully';
						$(function() {
							toastr.success(alert_msg);
						});
					}
				}
			});
			var popup1 = window.open("../Controller/get_tempCard.php?EmpID=" + $(this).attr('data-id'), "popupWindow", "width=600px,height=600px,scrollbars=yes");

		});
		$('#btn_view2').click(function() {

			var txtEmployeeID = $('#txtEmployeeID').val();
			$.ajax({
				url: "../Controller/OLDownloadRetaineship.php?EmpID=" + txtEmployeeID,
				success: function(result) {
					//alert(result);
					if (result) {
						$('#btn_add_' + txtEmployeeID).closest('tr').find('.Retainership_Agreement').text('Issued');
						alert_msg = 'Retainership Updated successfully';
						$(function() {
							toastr.success(alert_msg);
						});
					}
				}
			});
			var popup1 = window.open("../Controller/get_retainership.php?EmpID=" + $(this).attr('data-id'), "popupWindow", "width=600px,height=600px,scrollbars=yes");

		});
		$('#btn_handover').click(function() {

			var txtEmployeeID = $('#txtEmployeeID').val();
			$.ajax({
				url: "../Controller/OLDownloadHandover.php?EmpID=" + txtEmployeeID,
				success: function(result) {
					if (result) {
						$('#btn_add_' + txtEmployeeID).closest('tr').find('.handover').text('Handover');
						alert_msg = 'Handover successfully';
						$(function() {
							toastr.success(alert_msg);
						});
					}
				}
			});
		});
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Appointment / Retainership & ID Card</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Appointment / Retainership & ID Card</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="input-field col s12 m12 <?php echo $show; ?>" id="rpt_container">
					<div class="input-field col s4 m4">
						<input type="text" class="form-control" name="txtEmployeeName" readonly="" id="txtEmployeeName" value="<?php echo $empName; ?>" />
						<input type="hidden" name="txtEmployeeID" id="txtEmployeeID" value="<?php echo $empID; ?>" />
					</div>


					<div class="input-field col s8 m8">
						<button type="button" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green hidden" name="btn_handover" id="btn_handover">
							<i class="fa fa-cog"></i> Handover </button>

						<button type="button" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green <?php echo $btn_view; ?>" name="btn_view" id="btn_view">
							Issue Appointment Letter</button>

						<button type="button" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green <?php echo $btn_view1; ?>" name="btn_view1" id="btn_view1"> Issue ID Card</button>

						<button type="button" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green <?php echo $btn_view1; ?>" name="btn_view2" id="btn_view2"> Retainership Agreement</button>
					</div>

					<div class="input-field col s12 m12">
						<?php echo $link; ?>
					</div>
				</div>
				<?php
				$myDB = new MysqliDb();
				$location = clean($_SESSION["__location"]);
				$chk_task = $myDB->query('call get_new_emp_forOfferLatter_byHR("' . $location . '")');
				$my_error = $myDB->getLastError();;
				if (count($chk_task) > 0 && $chk_task) {
					$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data"><thead><tr>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>EmployeeName</th>';
					$table .= '<th>Validation</th>';
					$table .= '<th>ValidateBy</th>';
					$table .= '<th>Confirmation</th>';
					$table .= '<th>Confirmation Time</th>';
					$table .= '<th>ID Card</th>';
					$table .= '<th>Appointment Letter</th>';
					$table .= '<th>Retainership Agreement</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Dept Name</th>';
					$table .= '<th>DOJ</th>';
					$table .= '<th>DOD</th>';
					$table .= '<th>Client</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th><thead><tbody>';

					foreach ($chk_task as $key => $value) {

						$table .= '<tr><td><button type="button" class="btn waves-effect waves-green" name="btn_add[]" id="btn_add_' . $value['EmployeeID'] . '" onclick="javascript:return click_buton(this);">' . $value['EmployeeID'] . '</button></td>';
						$table .= '<td class="empName">' . $value['EmployeeName'] . '</td>';
						if ($value['handover'] == '0') {
							$table .= '<td  class="handover text-danger">Not Handover</td>';
						} else {
							$table .= '<td  class="handover text-success">Handover</td>';
						}
						$table .= '<td  class="handoverby">' . $value['HandVverby'] . '</td>';

						if ($value['taken'] == '0') {
							$table .= '<td  class="taken text-danger">Not Confirm</td>';
						} else {
							$table .= '<td  class="taken text-success">Confirmation</td>';
						}
						$table .= '<td  class="takentime">' . $value['takentime'] . '</td>';

						if ($value['ID_Card'] == '0') {
							$table .= '<td  class="ID_Card">Not Issued</td>';
						} elseif ($value['ID_Card'] == '2') {
							$table .= '<td  class="ID_Card">NA</td>';
						} else {
							$table .= '<td  class="ID_Card">Issued</td>';
						}
						if ($value['Appointment_Letter'] == '0') {
							$table .= '<td  class="Appointment_Letter">Not Issued</td>';
						} elseif ($value['Appointment_Letter'] == '2') {
							$table .= '<td  class="Appointment_Letter">NA</td>';
						} else {
							$table .= '<td  class="Appointment_Letter">Issued</td>';
						}
						if ($value['Retainership_Agreement'] == '0') {
							$table .= '<td  class="Retainership_Agreement">Not Issued</td>';
						} elseif ($value['Retainership_Agreement'] == '2') {
							$table .= '<td  class="Retainership_Agreement">NA</td>';
						} else {
							$table .= '<td  class="Retainership_Agreement">Issued</td>';
						}
						$table .= '<td>' . $value['designation'] . '</td>';
						$table .= '<td>' . $value['dept_name'] . '</td>';
						$table .= '<td>' . $value['DOJ'] . '</td>';
						$table .= '<td>' . $value['DOD'] . '</td>';
						$table .= '<td>' . $value['clientname'] . '</td>';
						$table .= '<td>' . $value['Process'] . '</td>';
						$table .= '<td>' . $value['sub_process'] . '</tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Employee validated or exists for Docs " . $my_error . "'); }); </script>";
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
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(10000).fadeOut("slow");
		}
		$('#btn_view,#btn_view1,#btn_view2').click(function() {
			$('#btn_view,#btn_view1,#btn_view2').addClass('hidden');
		});
	});

	function click_buton(el) {

		$('#txtEmployeeID').val($('#' + el.id).text());
		$('#txtEmployeeName').val($('#' + el.id).closest('tr').find('.empName').text() + "(" + $('#' + el.id).text() + ")");
		$('#rpt_container').removeClass('hidden');
		$('#btn_view').attr('data-id', $('#txtEmployeeID').val());
		$('#btn_view1').attr('data-id', $('#txtEmployeeID').val());
		$('#btn_view2').attr('data-id', $('#txtEmployeeID').val());

		$('#btn_valid').addClass('hidden');
		if ($('#' + el.id).closest('tr').find('.ID_Card').text() != "NA") {
			$('#btn_view1').removeClass('hidden');
		} else {
			$('#btn_view1').addClass('hidden');
		}
		if ($('#' + el.id).closest('tr').find('.Appointment_Letter').text() != "NA") {
			$('#btn_view').removeClass('hidden');
		} else {
			$('#btn_view').addClass('hidden');
		}
		if ($('#' + el.id).closest('tr').find('.Retainership_Agreement').text() != "NA") {
			$('#btn_view2').removeClass('hidden');
		} else {
			$('#btn_view2').addClass('hidden');
		}
		if ($('#' + el.id).closest('tr').find('.handover').text() != 'Not Handover' && $('#' + el.id).closest('tr').find('.handover').text() != '') {
			$('#btn_handover').addClass('hidden');
		} else {
			$('#btn_handover').removeClass('hidden');

		}

	}
</script>