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
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
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
		$thisPage = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}
		$btn_view = isset($_POST['btn_view']);
		if ($btn_view) {
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
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [
				/*  
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
	<span id="PageTittle_span" class="hidden">Login History Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Login History Report <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

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
						<h4 class="col s12 m12 model-h4">Login History Report</h4>
						<div class="modal-body">

							<div class="input-field col s6 m6">
								<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
							</div>

							<div class="input-field col s6 m6">
								<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
							</div>

							<div class="input-field col s12 m12 right-align">
								<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
								<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
								<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
							</div>
						</div>
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php
					//echo 'Hi';
					$userlogID = clean($_SESSION['__user_logid']);
					$btnview = isset($_POST['btn_view']);
					if ($btnview) {
						$myDB = new MysqliDb();
						$chk_task = 'call sp_getLogin_Report("' . $userlogID . '","' . $date_From . '","' . $date_To . '")';
						$data = $myDB->rawQuery($chk_task);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {
							$table = '<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						  <thead><tr>';
							$table .= '<th>LoginCount</th>';
							$table .= '<th>EmployeeID</th>';
							$table .= '<th>LoginFrom</th>';
							$table .= '<th>Location</th>';
							$table .= '<th>DateOn</th>';
							$table .= '<th>EmployeeName</th>';
							$table .= '<th>AccountHead</th>';
							$table .= '<th>Designation</th>';
							$table .= '<th>Dept Name</th>';
							$table .= '<th>DOJ</th>';
							$table .= '<th>Client</th>';
							$table .= '<th>Process</th>';
							$table .= '<th>Sub Process</th><thead><tbody>';

							foreach ($data as $key => $value) {
								print_r($data);
								// echo 'xvvc' . $value['loginCount'];
								$table .= '<tr><td>' . $value['loginCount'] . '</td>';
								$table .= '<td>' . $value['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['LoginFrom'] . '</td>';
								$table .= '<td>' . $value['location'] . '</td>';
								$table .= '<td>' . $value['DateOn'] . '</td>';
								$table .= '<td>' . $value['EmployeeName'] . '</td>';
								$table .= '<td>' . $value['AccountHead'] . '</td>';
								$table .= '<td>' . $value['designation'] . '</td>';
								$table .= '<td>' . $value['dept_name'] . '</td>';
								$table .= '<td>' . $value['DOJ'] . '</td>';
								$table .= '<td>' . $value['clientname'] . '</td>';
								$table .= '<td>' . $value['Process'] . '</td>';
								$table .= '<td>' . $value['sub_process'] . '</td></tr>';
							}
							$table .= '</tbody></table>';
							echo $table;
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found  ... " . $mysql_error . "') }); </script>";
						}
					}


					?>

				</div>
				<div id="myModal" class="modal fade" role="dialog">
					<div class="modal-dialog" id="dilogData" style="width: 800px;">
						<!-- Modal content-->
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

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			}
			var datfrom = $('#txt_dateFrom').val();
			var datto = $('#txt_dateTo').val();
			// alert(datto);
			if (datto) {
				var usrid = <?php echo "'" . $_SESSION["__user_logid"] . "'"; ?>;
				// alert(usrid)
				var sp = "call sp_getLogin_Report('" + usrid + "','" + datfrom + "','" + datto + "')";
				var url = "textExport.php?sp=" + sp;
				//alert(url);
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