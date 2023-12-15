<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$dir_location = '';
$sess = clean($_SESSION["__location"] == "1" || $_SESSION["__location"] == "2");
$sess2 = clean($_SESSION['__user_logid'] == "CE05070035" || $_SESSION['__user_logid'] == "CE03070003"  || $_SESSION['__user_logid'] == "CE10091236" || $_SESSION['__user_logid'] == "CE061930039");
$sess3 = clean($_SESSION["__location"] == "3");
$sess4 = clean($_SESSION['__user_logid'] == "CEM061912447" || $_SESSION['__user_logid'] == "CEM071712012"  || $_SESSION['__user_logid'] == "CEM06191244");
$sess5 = clean($_SESSION["__location"] == "4");
$sess6 = clean($_SESSION["__location"] == "5");
$sess7 = clean($_SESSION["__location"] == "6" || $_SESSION["__location"] == "7");
$sess8 = clean($_SESSION["__location"] == "7");
$sess9 = clean($_SESSION['__user_logid'] == "CE05070035" || $_SESSION['__user_logid'] == "CE03070003"  || $_SESSION['__user_logid'] == "CEK061927543" || $_SESSION['__user_logid'] == "CFK09190785" || $_SESSION['__user_logid'] == "CMK06193898");

$sess10 = clean($_SESSION["__location"] == "8");
$sess11 = clean($_SESSION['__user_logid'] == "CE05070035" || $_SESSION['__user_logid'] == "CE03070003"  || $_SESSION['__user_logid'] == "CE071829466" || $_SESSION['__user_logid'] == "CFK09190785");


if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		//header("Location: $location");
		echo "<script>location.href='" . $location . "'</script>";
		exit();
	} else {
		if ($sess) {
			if ($sess2) {
				$isPostBack = false;
				$referer = "";
				$alert_msg = "";
				$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			} else {
				$location = URL . 'Login';
				echo "<script>location.href='" . $location . "'</script>";
				exit();
			}
		} else if ($sess3) {
			if ($sess4) {
				$dir_location = 'Meerut/';
				$isPostBack = false;
				$referer = "";
				$alert_msg = "";
				$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			} else {
				$location = URL . 'Login';
				echo "<script>location.href='" . $location . "'</script>";
				exit();
			}
		} else if ($sess5) {
			$dir_location = "Bareilly/";
		} else if ($sess6) {
			$dir_location = "Vadodara/";
		} else if ($sess7) {

			if ($_SESSION["__location"] == "6") {

				$dir_location = "Manglore/";
			} elseif ($sess8) {

				$dir_location = "Bangalore/";
			}


			if ($sess9) {
				$isPostBack = false;
				$referer = "";
				$alert_msg = "";
				$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			} else {
				$location = URL . 'Login';
				echo "<script>location.href='" . $location . "'</script>";
				exit();
			}
		} else if ($sess10) {
			if ($sess11) {
				$dir_location = "Banglore_Fk/";
				$isPostBack = false;
				$referer = "";
				$alert_msg = "";
				$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			} else {
				$location = URL . 'Login';
				echo "<script>location.href='" . $location . "'</script>";
				exit();
			}
		} else {
			$location = URL . 'Login';
			echo "<script>location.href='" . $location . "'</script>";
			exit();
		}
	}
} else {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
}

$show = 'hidden';
$link = $btn_view = $btn_view1 = $alert_msg = '';


$show = ' hidden';
$empName = '';
$empID = '';
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


		$('#btn_view').click(function() {
			//$('#btn_view').hide();
			var txtEmployeeID = $('#txtEmployeeID').val();

			$.ajax({
				url: "../Controller/OLReportIssueALetter.php?EmpID=" + txtEmployeeID,
				success: function(result) {
					if (result == 1) {
						$('#emp_' + txtEmployeeID).closest('tr').find('.Appointment_Letter').text('Issued');
						alert_msg = 'Appointment Letter issued successfully';
						$(function() {
							toastr.success(alert_msg);
						});


					}
				}
			});

			var popup = window.open("../Controller/get_OfferLater.php?EmpID=" + $(this).attr('data-id'), "popupWindow", "width=600px,height=600px,scrollbars=yes");


		});
		$('#btn_view1').click(function() {
			//$('#btn_view1').hide();
			var txtEmployeeID = $('#txtEmployeeID').val();
			$.ajax({
				url: "../Controller/OLReportIssueIdCard.php?EmpID=" + txtEmployeeID,
				success: function(result) {

					if (result) {
						$('#emp_' + txtEmployeeID).closest('tr').find('.ID_Card').text('Issued');
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

			var popup1 = window.open("../Controller/get_retainership.php?EmpID=" + $(this).attr('data-id'), "popupWindow", "width=600px,height=600px,scrollbars=yes");


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
			<h4>Appointment / Retainership & ID Card </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<!--Form element start--><?php
											$username = clean($_SESSION["__user_Name"]); ?>
				<input type='hidden' name='loginuser' id='loginuser' value='<?php echo $username; ?>' />
				<div class="col s12 m12 <?php echo $show; ?>" id="rpt_container">

					<div class="input-field col s12 m12">
						<input type="text" name="txtEmployeeName" readonly="true" id="txtEmployeeName" value="<?php echo $empName; ?>" />
						<input type="hidden" name="txtEmployeeID" id="txtEmployeeID" value="<?php echo $empID; ?>" />
						<input type="hidden" name="dirLoc" id="dirLoc" value="<?php echo $dir_location; ?>" />
					</div>


					<div class="input-field col s12 m12">
						<textarea class="materialize-textarea hidden" name="txtComment" id="txtComment" placeholder="Write your comment"></textarea>
					</div>
					<div class="col s12 m12">
						<!--<button type="submit" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green hidden" name="btn_hold" id="btn_hold">Hold </button>-->
						<button type="button" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green hidden" name="btn_hold" id="btn_hold">Hold </button>
						<button type="button" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green hidden" name="btn_valid" id="btn_valid">Validate </button>

						<button type="button" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green tooltipped <?php echo $btn_view; ?>" name="btn_view" id="btn_view" data-tooltip="Issue Appointment Letter">Letter</button>

						<button type="button" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green tooltipped <?php echo $btn_view1; ?>" name="btn_view1" id="btn_view1" data-tooltip="Print ID Card">ID Card</button>

						<button type="button" data-id='<?php echo $empID; ?>' class="btn waves-effect waves-green tooltipped <?php echo $btn_view1; ?>" name="btn_view2" id="btn_view2" data-tooltip="Issue Retainership Agreement">Agreement</button>

					</div>


					<div class="col s12 m12">
						<?php echo $link; ?>
					</div>
				</div>

				<!--Form element End-->

				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php
					$myDB = new MysqliDb();
					$location = clean($_SESSION["__location"]);
					$sqlConnect = 'call get_new_emp_forOfferLatter("' . $location . '")';
					$chk_task = $myDB->rawQuery($sqlConnect);
					$my_error = $myDB->getLastError();
					if (empty($my_error)) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><div class="">
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Validation</th>';
						$table .= '<th>ValidateBy</th>';
						$table .= '<th>Remarks</th>';
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

							$table .= '<tr id="emp_' . $value['EmployeeID'] . '"><td>
							<a style="font-weight: bold;cursor: pointer;color: #19aec4;    text-transform: uppercase;"  name="btn_add[]" id="btn_add_' . $value['EmployeeID'] . '" onclick="javascript:return click_buton(this);">' . $value['EmployeeID'] . '</a>
							</td>';
							$table .= '<td class="empName" >' . $value['EmployeeName'] . '</td>';
							if ($value['validate'] == '0') {
								$table .= '<td  class="validate text-danger">Not Valid</td>';
							} elseif ($value['validate'] == '1') {
								$table .= '<td  class="validate text-success">Valid</td>';
							} elseif ($value['validate'] == '2') {
								$table .= '<td  class="validate text-warning">Hold</td>';
							}
							$table .= '<td  class="validateby">' . $value['validateby'] . '</td>';
							$table .= '<td  class="comment">' . $value['comment'] . '</td>';

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
						echo "<script>$(function(){ toastr.success('No Data Found " . $my_error . "'); }); </script>";
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
<script>
	function click_buton(el) {

		$('#rpt_container').show();
		//$('#btn_view1').show();
		//$('#btn_view').show();
		//$('#btn_valid').show();
		$('#btn_hold').show();
		$('#txtEmployeeID').val($('#' + el.id).text());
		$('#txtEmployeeName').val($('#' + el.id).closest('tr').find('.empName').text() + "(" + $('#' + el.id).text() + ")");
		$('#rpt_container').removeClass('hidden');
		$('#btn_view').attr('data-id', $('#txtEmployeeID').val());
		$('#btn_view1').attr('data-id', $('#txtEmployeeID').val());
		$('#btn_view2').attr('data-id', $('#txtEmployeeID').val());

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
		if ($('#' + el.id).closest('tr').find('.validate').text() == 'Valid' && $('#' + el.id).closest('tr').find('.validate').text() != '') {
			$('#btn_valid').addClass('hidden');
			$('#btn_hold').addClass('hidden');
			$('#txtComment').addClass('hidden');

		} else if ($('#' + el.id).closest('tr').find('.validate').text() == 'Hold' && $('#' + el.id).closest('tr').find('.validate').text() != '') {
			$('#btn_valid').removeClass('hidden');
			$('#btn_hold').addClass('hidden');
			$('#txtComment').removeClass('hidden');

		} else {
			$('#btn_valid').removeClass('hidden');
			$('#btn_hold').removeClass('hidden');
			$('#txtComment').removeClass('hidden');
		}
	}
</script>
<script>
	$(document).ready(function() {
		$('#btn_hold').click(function() {
			//$('#btn_hold').hide();
			$('#rpt_container').hide();
			$("#preloader").show();
			var Comment = $('#txtComment').val();
			var txtEmployeeID = $('#txtEmployeeID').val();
			$.ajax({
				url: "../Controller/OfferLetterReportAction.php?EmpID=" + txtEmployeeID + '&Comment=' + Comment,
				success: function(result) {
					if (result) {
						$("#preloader").hide();
						var user = $('#loginuser').val();
						$('#emp_' + txtEmployeeID).closest('tr').find('.validate').text('Hold');
						$('#emp_' + txtEmployeeID).closest('tr').find('.validate').addClass('text-warning');
						$('#emp_' + txtEmployeeID).closest('tr').find('.validate').removeClass('text-danger');
						$('#emp_' + txtEmployeeID).closest('tr').find('.validateby').text(user);
						alert_msg = 'Updated successfully';
						$(function() {
							toastr.success(alert_msg);
						});
					}
				}
			});
		});
		$('#btn_view2').click(function() {
			//$('#btn_view2').hide();
			var txtEmployeeID = $('#txtEmployeeID').val();
			$.ajax({
				url: "../Controller/OLReportIssueRetainership.php?EmpID=" + txtEmployeeID,
				success: function(result) {
					//alert(result);
					if (result) {
						$('#emp_' + txtEmployeeID).closest('tr').find('.Retainership_Agreement ').text('Retainership');
						alert_msg = 'Retainership Updated successfully';
						$(function() {
							toastr.success(alert_msg);
						});
					}
				}
			});
		});
		$('#btn_valid').click(function() {
			var action = confirm('Are you sure? You want to valid it');
			if (action == true) {

				var i = 0;
				var txtEmployeeID = $('#txtEmployeeID').val();
				var dirLoc = $('#dirLoc').val();

				//var browserZoomLevel = Math.round(window.devicePixelRatio * 100);
				var Comment = $('#txtComment').val();
				var txtEmployeeID = $('#txtEmployeeID').val();
				var txtEmployeeName = $('#txtEmployeeName').val();
				$('#emp_' + txtEmployeeID).closest('tr').hide();
				$("#rpt_container").hide();
				//$("#preloader").show();
				var user = $('#loginuser').val();
				$('#emp_' + txtEmployeeID).closest('tr').hide();
				alert_msg = 'Validated successfully';
				$(function() {
					toastr.success(alert_msg);
				});

				window.open('ALetter_download_multipdf2.php?EmpID=' + txtEmployeeID + "&dirloc=" + dirLoc + '&Comment=' + Comment + '&txtEmployeeName=' + txtEmployeeName, '_blank');


			} else {
				return false;
			}

		});
	});
</script>