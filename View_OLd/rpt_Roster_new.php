<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $EmpStatus = $countSubproc = 0;
$monthCount = '';

/*if(time() >= strtotime('08:30:00') && time() <= strtotime('09:30:00'))
{
	$location= URL.'Login';
	echo "<script>location.href='".$location."'</script>";
}*/

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

		if ($isPostBack && isset($_POST['txt_dateMonth'])) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$date_To = cleanUserInput($_POST['txt_dateMonth']);
				$date_From = cleanUserInput($_POST['txt_dateYear']);
				$dept = cleanUserInput($_POST['txt_dept']);
			}
		} else {
			$date_To = date('M', time());
			$date_From = date('Y', time());
			$dept = clean($_SESSION['__user_process']);
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();


if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	if (!empty($_POST['emp_status'])) {
		$EmpStatus = cleanUserInput($_POST['emp_status']);
	}
	if (!empty($_POST['clientName'])) {
		$clientname = cleanUserInput($_POST['clientName']);
	}
	if (!empty($_POST['monthCount'])) {
		$monthCount = cleanUserInput($_POST['monthCount']);
	}
}
?>



<script>
	$(function() {
		/*$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});*/

		$.fn.dataTable.ext.search.push(
			function(settings, data, dataIndex) {
				var proc = $('#txt_process').val().toLowerCase();
				var sproc = $('#txt_Subproc').val().toLowerCase();
				var process = data[39]; // use data for the age column
				var subprocess = data[40]; // use data for the age column


				if (process.toLowerCase().indexOf(proc) >= 0 && subprocess.toLowerCase().indexOf(sproc) >= 0) {

					return true;

				} else {
					return false;
				}




			}
		);

		// DataTable
		var table = $('#myTable').DataTable({
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
		});;
		$('#txt_Subproc, #txt_process').keyup(function() {
			table.draw();
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
	<span id="PageTittle_span" class="hidden">New Roster Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>New Roster Report <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Filter Roster Report</h4>
						<div class="modal-body" style="height:400px ;">

							<div class="input-field col s6 m6">

								<Select name="txt_dateMonth" id="txt_dateMonth">
									<option id='<?php echo date("m", strtotime("first day of previous month")) ?>'><?php echo date("M", strtotime("first day of previous month"))  ?></option>
									<option id='<?php echo date('m') ?>'' selected '><?php echo date('M')  ?></option>
									<option id='<?php echo date("m", strtotime("first day of next month")) ?>'><?php echo date("M", strtotime("first day of next month"))  ?></option>
								</Select>
							</div>
							<div class="input-field col s6 m6">
								<Select name="txt_dateYear" id="txt_dateYear">
									<option id='<?php echo date('Y') ?>'><?php echo date('Y')  ?></option>
								</Select>
								<input type='hidden' name='monthCount' id='monthCount'>
							</div>

							<div class="input-field col s6 m6">

								<Select name="emp_status" style="min-width: 200px;" id="status">
									<option value='Active' <?php $emp_status = cleanUserInput($_POST['emp_status']);
															if (isset($emp_status) && $emp_status == 'Active') {
																echo "selected";
															} ?>>Active</option>
									<option value='InActive' <?php $emp_status = cleanUserInput($_POST['emp_status']);
																if (isset($emp_status) && $emp_status == 'InActive') {
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
									//$result = $myDB->query('select distinct t1.client_name ID,t3.client_name from new_client_master t1 join report_map t2 on t1.cm_id=t2.processID join client_master t3 on t1.client_name=t3.client_id where t2.EmpID="' . $_SESSION['__user_logid'] . '" and reportID=2');

									$EMPID = clean($_SESSION['__user_logid']);
									$Query = 'select distinct t1.client_name ID,t3.client_name from new_client_master t1 join report_map t2 on t1.cm_id=t2.processID join client_master t3 on t1.client_name=t3.client_id where t2.EmpID=? and reportID=2';

									$stmt = $conn->prepare($Query);
									$stmt->bind_param("s", $EMPID);
									if (!$stmt) {
										echo "failed to run";
										die;
									}
									$stmt->execute();
									$result = $stmt->get_result();

									// $my_error = $myDB->getLastError();
									foreach ($result as $key => $value) {
										echo '<option value="' . $value['ID'] . '">' . $value['client_name'] . '</option>';
									} ?>
								</select>
								<label for="clientName" class="active-drop-down active">Client Name</label>
							</div>
							<div class="input-field col s12 m12 right-align">

								<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
								<!--<button type="submit" class="button button-3d-action" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
								<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>

						</div>
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<?php
				$myDB = new MysqliDb();
				// if (!empty($_POST['emp_status'])) {
				// 	$EmpStatus = cleanUserInput($_POST['emp_status']);
				// }
				// if (!empty($_POST['clientName'])) {
				// 	$clientname = cleanUserInput($_POST['clientName']);
				// }

				if ($EmpStatus == 'Active') {
					$tablename = 'whole_details_peremp';
				} elseif ($EmpStatus == 'InActive') {
					$tablename = 'view_for_report_inactive';
				}
				$curMonth = date('m');
				$curYear = date('Y');
				// if (!empty($_POST['monthCount'])) {
				// 	$monthCount = cleanUserInput($_POST['monthCount']);
				// }
				// echo $monthCount;
				//seleceted month(01,02....,10,11)
				$date_selected = $date_From . '-' . $monthCount . '-01'; //selected month and year(2017,2018...)
				$dateLimit = date('Y-m-01', strtotime('-3 months'));
				// die;


				if ($monthCount != "") {
					// echo 'gg';
					// die;

					if (($dateLimit < $date_selected)) {

						$viewTable = 'roster_master_dump';
						$chk_task = 'call sp_get_roster_Report_new("' . clean($_SESSION['__user_logid']) . '","' . $date_To . '","' . $date_From . '","' . $EmpStatus . '","' . $clientname . '")';
					} else {
						// $viewTable = 'roster_history_dump';
						// echo $chk_task = 'call sp_get_roster_history_Report_new("' . $_SESSION['__user_logid'] . '","' . $date_To . '","' . $date_From . '","' . $EmpStatus . '","' . $clientname . '")';
					}
					// echo $chk_task;
					$sql = $myDB->rawQuery($chk_task);

					$my_error = $myDB->getLastError();
					if ($myDB->count > 0) {
						$table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Month</th>';
						$table .= '<th>Year</th>';
						$table .= '<th>D1</th>';
						$table .= '<th>D2</th>';
						$table .= '<th>D3</th>';
						$table .= '<th>D4</th>';
						$table .= '<th>D5</th>';
						$table .= '<th>D6</th>';
						$table .= '<th>D7</th>';
						$table .= '<th>D8</th>';
						$table .= '<th>D9</th>';
						$table .= '<th>D10</th>';
						$table .= '<th>D11</th>';
						$table .= '<th>D12</th>';
						$table .= '<th>D13</th>';
						$table .= '<th>D14</th>';
						$table .= '<th>D15</th>';
						$table .= '<th>D16</th>';
						$table .= '<th>D17</th>';
						$table .= '<th>D18</th>';
						$table .= '<th>D19</th>';
						$table .= '<th>D20</th>';
						$table .= '<th>D21</th>';
						$table .= '<th>D22</th>';
						$table .= '<th>D23</th>';
						$table .= '<th>D24</th>';
						$table .= '<th>D25</th>';
						$table .= '<th>D26</th>';
						$table .= '<th>D27</th>';
						$table .= '<th>D28</th>';
						$table .= '<th>D29</th>';
						$table .= '<th>D30</th>';
						$table .= '<th>D31</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Gender</th>';
						$table .= '<th>Dept Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Type</th>';
						$table .= '<th>Supervisor</th><th>Location</th></tr></thead><tbody>';

						foreach ($sql as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['Month'] . '</td>';
							$table .= '<td>' . $value['Year'] . '</td>';
							$table .= '<td>' . $value['D1'] . '</td>';
							$table .= '<td>' . $value['D2'] . '</td>';
							$table .= '<td>' . $value['D3'] . '</td>';
							$table .= '<td>' . $value['D4'] . '</td>';
							$table .= '<td>' . $value['D5'] . '</td>';
							$table .= '<td>' . $value['D6'] . '</td>';
							$table .= '<td>' . $value['D7'] . '</td>';
							$table .= '<td>' . $value['D8'] . '</td>';
							$table .= '<td>' . $value['D9'] . '</td>';
							$table .= '<td>' . $value['D10'] . '</td>';
							$table .= '<td>' . $value['D11'] . '</td>';
							$table .= '<td>' . $value['D12'] . '</td>';
							$table .= '<td>' . $value['D13'] . '</td>';
							$table .= '<td>' . $value['D14'] . '</td>';
							$table .= '<td>' . $value['D15'] . '</td>';
							$table .= '<td>' . $value['D16'] . '</td>';
							$table .= '<td>' . $value['D17'] . '</td>';
							$table .= '<td>' . $value['D18'] . '</td>';
							$table .= '<td>' . $value['D19'] . '</td>';
							$table .= '<td>' . $value['D20'] . '</td>';
							$table .= '<td>' . $value['D21'] . '</td>';
							$table .= '<td>' . $value['D22'] . '</td>';
							$table .= '<td>' . $value['D23'] . '</td>';
							$table .= '<td>' . $value['D24'] . '</td>';
							$table .= '<td>' . $value['D25'] . '</td>';
							$table .= '<td>' . $value['D26'] . '</td>';
							$table .= '<td>' . $value['D27'] . '</td>';
							$table .= '<td>' . $value['D28'] . '</td>';
							$table .= '<td>' . $value['D29'] . '</td>';
							$table .= '<td>' . $value['D30'] . '</td>';
							$table .= '<td>' . $value['D31'] . '</td>';

							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['Gender'] . '</td>';
							$table .= '<td>' . $value['dept_name'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['type_'] . '</td>';
							$table .= '<td>' . $value['Supervisor'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td></tr>';
						}
						$table .= '</tbody></table>';
						echo $table;

						echo '<div class="input-field col s6 m6">
				 		<input type="text" id="txt_process" name="txt_process"/>
				 		<label for="txt_process">Process</label>
					 </div>
				 	<div class="input-field col s6 m6">
				 		<input type="text" id="txt_Subproc" name="txt_Subproc"/>
				 		<label for="txt_Subproc">Sub Process</label>
				 	</div>';
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found for applied filter. '); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.info('Please apply filter first.'); }); </script>";
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
		$('#txt_dateMonth').on('change', function() {
			var monthc = $('#txt_dateMonth option:selected').attr('id');
			$('#monthCount').val(monthc);
			$('#txt_dateYear').empty();
			if (monthc != 12) {
				var year = new Date().getFullYear();
				//alert(year);
				$('#txt_dateYear').append(new Option(year, year));
			} else {
				var yy = new Date().getFullYear() - 1;
				//alert(yy);
				$('#txt_dateYear').append(new Option(yy, yy));
			}
		})
		$('#btn_view').on('click', function() {
			var monthc = $('#txt_dateMonth option:selected').attr('id');
			$('#monthCount').val(monthc);
		})
	});
</script>

<script>
	$(function() {
		$('#txt_dateMonth').on('change', function() {
			var monthc = $('#txt_dateMonth option:selected').attr('id');
			$('#monthCount').val(monthc);
		})
		$('#btn_view').on('click', function() {
			var monthc = $('#txt_dateMonth option:selected').attr('id');
			$('#monthCount').val(monthc);
		})
	});
</script>
<script>
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
				var usrid = <?php echo "'" . clean($_SESSION["__user_logid"]) . "'"; ?>;
				// alert(usrid);
				var loc = <?php echo "'" . clean($_SESSION["__location"]) . "'"; ?>;
				var date_to = $('#txt_dateMonth').val();
				// alert(date_to);
				var date_from = $('#txt_dateYear').val();
				// alert(date_from);
				var status = $('#status').val();
				//var type = 'ADMINISTRATOR';
				//'call sp_get_atnd_Report_new("' . $_SESSION['__user_logid'] . '","' . $date_To . '","' . $date_From . '","' . $clientname . '") ';
				var sp = "call sp_get_roster_Report_new('" + usrid + "','" + date_to + "','" + date_from + "','" + status + "','" + dept + "')";
				var url = "textExport.php?sp=" + sp;
				alert(url);
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