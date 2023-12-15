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
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_SESSION)) {
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$date_From = date("Y-m-d", strtotime($_POST['DateFrom']));
	$date_To = date("Y-m-d", strtotime($_POST['DateTo']));
}
?>
<script>
	$(document).ready(function() {
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
			"iDisplayLength": 10,
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
	<span id="PageTittle_span" class="hidden">IT Help Desk</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Tickets Report<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

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
						<h4 class="col s12 m12 model-h4">Report</h4>
						<div class="modal-body">

							<div class="input-field col s6 m6">
								<input type="text" id="DateFrom" name="DateFrom">
								<label for="DateFrom">Date From</label>
							</div>
							<div class="input-field col s6 m6">
								<input type="text" id="DateTo" name="DateTo">
								<label for="DateFrom">Date To</label>
							</div>
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
			$btn_view = isset($_POST['btn_view']);
			if (($btn_view)) {

				/* 	if($_SESSION["__user_logid"] == 'CE03070003' || $_SESSION["__user_logid"] == 'CE09134997' || $_SESSION["__user_logid"] == 'CE01145570' || $_SESSION["__user_logid"] == 'CE12102224')
      			 { */
				$query = "SELECT id, ticket_id, process_client, process, priorty, category, issue_type, issue_disc, agent_impacted, total_agents, requester_empId, requester_name, requester_email, requester_mobile, location, tat, exten_tat, issue_status, handler_empId, handler_name, handler_mobile, handler_email, inprogress_remark, inprogress_date, closing_remark, closing_date, rca_text, rca_attachement, rca_date, created_date FROM ems.ithdk_ticket_details where created_date between cast(? as date) and cast(? as date);";

				$selectQury = $conn->prepare($query);
				$selectQury->bind_param("ss", $date_From, $date_To);
				$selectQury->execute();
				$result = $selectQury->get_result();

				// $result = $myDB->query($query);
				// $my_error = $myDB->getLastError();

				if ($result->num_rows > 0) {
					$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">';
					$table = '<div>';

					$table = '<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';

					$table .= '<th>S No</th>';
					$table .= '<th>ID</th>';
					$table .= '<th>Ticket ID</th>';
					$table .= '<th>Client</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Priority</th>';
					$table .= '<th>Location</th>';
					$table .= '<th>TAT(Hour)</th>';
					$table .= '<th>TAT Extension(Hour)</th>';
					$table .= '<th>Issue Status</th>';
					$table .= '<th>Category</th>';
					$table .= '<th>Issue Type</th>';
					$table .= '<th>Issue Desc</th>';
					$table .= '<th>Total Agents</th>';
					$table .= '<th>Agent Impacted</th>';
					$table .= '<th>Requester EmpID</th>';
					$table .= '<th>Requester Name</th>';
					$table .= '<th>Requester Email</th>';
					$table .= '<th>Requester Mobile</th>';
					$table .= '<th>Handler EmpID</th>';
					$table .= '<th>Handler Name</th>';
					$table .= '<th>Handler Email</th>';
					$table .= '<th>Handler Mobile</th>';
					$table .= '<th>InProgrees Remark</th>';
					$table .= '<th>InProgress Date</th>';
					$table .= '<th>Closing Remark</th>';
					$table .= '<th>Closing Date</th>';
					$table .= '<th>RCA</th>';
					$table .= '<th>RCA Attachment</th>';
					$table .= '<th>RCA Date</th>';
					$table .= '<th>Created Date</th></thead><tbody></tr>';
					$i = 1;
					foreach ($result as $key => $value) {
						$table .= '<tr><td>' . $i . '</td>';
						$table .= '<td class="id">' . $value['id'] . '</td>';
						$table .= '<td class="ticketId">' . $value['ticket_id'] . '</td>';
						$table .= '<td class="client">' . $value['process_client'] . '</td>';
						$table .= '<td class="process">' . $value['process'] . '</td>';
						$table .= '<td class="priority">' . $value['priorty'] . '</td>';
						$table .= '<td  class="locationIssue">' . $value['location'] . '</td>';
						$table .= '<td  class="tat">' . $value['tat'] . '</td>';
						$table .= '<td  class="Extetat">' . $value['exten_tat'] . '</td>';
						$table .= '<td  class="issStatus">' . $value['issue_status'] . '</td>';
						$table .= '<td class="category">' . $value['category'] . '</td>';
						$table .= '<td class="issueType">' . $value['issue_type'] . '</td>';
						$table .= '<td  class="issueDesc">' . $value['issue_disc'] . '</td>';
						$table .= '<td  class="totalAgents">' . $value['total_agents'] . '</td>';
						$table .= '<td  class="agentImpacted">' . $value['agent_impacted'] . '</td>';
						$table .= '<td  class="reqEmpId">' . $value['requester_empId'] . '</td>';
						$table .= '<td  class="reqName">' . $value['requester_name'] . '</td>';
						$table .= '<td  class="reqEmail">' . $value['requester_email'] . '</td>';
						$table .= '<td  class="reqMobile">' . $value['requester_mobile'] . '</td>';
						$table .= '<td  class="handEmpId">' . $value['handler_empId'] . '</td>';
						$table .= '<td  class="handName">' . $value['handler_name'] . '</td>';
						$table .= '<td  class="handEmail">' . $value['handler_email'] . '</td>';
						$table .= '<td  class="handMobile">' . $value['handler_mobile'] . '</td>';
						$table .= '<td  class="InProgRemark">' . $value['inprogress_remark'] . '</td>';
						$table .= '<td  class="InProgDate">' . $value['inprogress_date'] . '</td>';
						$table .= '<td  class="closingRemark">' . $value['closing_remark'] . '</td>';
						$table .= '<td  class="closingDate">' . $value['closing_date'] . '</td>';
						$table .= '<td  class="rcaText">' . $value['rca_text'] . '</td>';
						$table .= '<td  class="rcaAttach">' . $value['rca_attachement'] . '</td>';
						$table .= '<td  class="rcaDate">' . $value['rca_date'] . '</td>';
						$table .= '<td  class="createdDate">' . $value['created_date'] . '</td></tr>';

						$i++;
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Record found.'); }); </script>";
				}
				//}	
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
	$(document).ready(function() {
		$('#DateFrom').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$('#DateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
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