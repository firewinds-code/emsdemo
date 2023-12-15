<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

if (isset($_SESSION)) {
	$clean_u_logid = clean($_SESSION['__user_logid']);
	if (!isset($clean_u_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$todate = $fromdate = $searchBy = $msg = "";
$classvarr = "'.byID'";
$style = "display:none";
?>

<script>
	$(document).ready(function() {

		$('.statuscheck').addClass('hidden');
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		}
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		/*table.dataTable thead({
			 scrollY: 192,				        
			
		});*/
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollX: '100%',
			scrollCollapse: true,
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

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		$('.byDate').addClass('hidden');
		$('.byDept').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');
		$('#searchBy').change(function() {
			$('.byID').addClass('hidden');
			$('.byDate').addClass('hidden');
			$('.byDept').addClass('hidden');
			$('#txt_ED_joindate_to').val('');
			$('#txt_ED_joindate_from').val('');
			$('#txt_ED_Dept').val('NA');
			$('#ddl_ED_Emp_Name').val('');
			if ($(this).val() == 'By ID') {
				$('.byID').removeClass('hidden');
			} else if ($(this).val() == 'By Date') {
				$('.byDate').removeClass('hidden');
			} else if ($(this).val() == 'By Dept') {
				$('.byDept').removeClass('hidden');
			}


		});
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Updated Mo. No. Employee Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Updated Mo. No. Employee Report <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Updated Mo. No. Employee Report</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">

							<div class="form-group">
								<div class="form-inline col-sm-12" id="rpt_container">
									<div class="input-field col s6 m6">

										<input type="text" class="form-control" name="txt_dateFrom" readonly='true' id="txt_dateFrom" value="<?php $clean_date_to = cleanUserInput($_POST['txt_dateTo']);
																																				$clean_date_from = cleanUserInput($_POST['txt_dateFrom']);
																																				if (isset($clean_date_to)) {
																																					echo $clean_date_from;
																																				} else {
																																					echo date('Y-m-d');
																																				} ?>" />
										<label for="txt_dateFrom">From Date</label>
									</div>
									<div class="input-field col s6 m6">
										<input type="text" class="form-control" name="txt_dateTo" readonly='true' id="txt_dateTo" value="<?php if (isset($clean_date_to)) {
																																				echo $clean_date_to;
																																			} else {
																																				echo date('Y-m-d');
																																			} ?>" />
										<label for="txt_dateTo">To Date</label>
									</div>
									<div class="input-field col s12 m12 right-align">

										<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
										<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
										<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
									</div>

								</div>
							</div>

						</div>
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
					$error = "";
					if (isset($_POST['btn_view'])) {
						$todate = cleanUserInput($_POST['txt_dateTo']);
						$fromdate = cleanUserInput($_POST['txt_dateFrom']);
						if ($todate != "" && $fromdate != "") {

							$sqlConnect = "call updated_contact('$fromdate' , '$todate')";
							/*$sqlConnect="SELECT b.EmployeeName,a.EmployeeID,a.mobile,a.altmobile,a.em_contact,
							c.mobile,c.altmobile,c.em_contact,a.modifiedon from contact_details a inner join personal_details b
							on a.EmployeeID=b.EmployeeID  inner join tbl_contact_log c on a.EmployeeID=c.EmployeeID
							where (a.mobile!=c.mobile  or a.altmobile!=c.altmobile )
							and cast(a.modifiedon as date) BETWEEN '$fromdate' AND '$todate' 
							and cast(c.created_on as date) BETWEEN '$fromdate' AND '$todate' ";*/
							$myDB = new MysqliDb();
							$result = $myDB->rawQuery($sqlConnect);
							$mysql_error = $myDB->getLastError();
						}
						if (empty($mysql_error)) { ?>
							<div class="panel panel-default" style="margin-top: 10px;">
								<div class="panel-body">
									<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Srl. No. </th>
												<th>Employee ID</th>
												<th>Employee Name</th>
												<th>Mobile Number</th>
												<th>Alternate Mobile Number</th>
												<th>Emergency Mobile Number</th>
												<th>Email ID</th>
												<th>Updated On</th>

											</tr>
										</thead>
										<tbody>
											<?php
											$count = 0;
											foreach ($result as $key => $value) {
												$count++;
												echo '<tr>';
												echo '<td id="countc' . $count . '">' . $count . '</td>';
												echo '<td class="Process" id="empid' . $count . '">' . $value['EmployeeID'] . '</td>';
												echo '<td class="Process" id="empid' . $count . '">' . $value['EmployeeName'] . '</td>';
												echo '<td class="SubProcess"  id="empname' . $count . '" >' . $value['mobile'] . '</td>';
												echo '<td class="SubProcess"  id="empname' . $count . '" >' . $value['altmobile'] . '</td>';
												echo '<td class="SubProcess"  id="empname' . $count . '" >' . $value['em_contact'] . '</td>';
												echo '<td class="SubProcess"  id="empname' . $count . '" >' . $value['emailid'] . '</td>';
												echo '<td class="clientname" id="clientname' . $count . '">' . date('d-m-Y H:i:s', strtotime($value['created_on'])) . '</td>';
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
					<?php
						} else {
							echo '<div id="div_error" class="slideInDown animated hidden">Data Not Found (May be You Not Have Any Employee Updated ):: <code >' . $error . '</code> </div>';
						}
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


		$('#div_error').removeClass('hidden');
		$('#btnCancel').click(function() {
			$('.statuscheck').addClass('hidden');;
		});
		$('#btnSave1').click(function() {
			//alert('hide');
			var remark = $('#remark').val().trim();
			if (remark == "") {
				validate = 1;
				alert_msg = '<li> Remark should not be empty</li>';
			}
			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");

				return false;
			}
		});
		// This code for cancel button trigger click and also for model close
		$('#btn_Can').on('click', function() {
			$('#txt_Client_Name').val('');
			$('#hid_Client_ID').val('');
			$('#txt_Client_ach').val('NA');
			$('#txt_Client_dept').val('NA');
			$('#txt_Client_proc').val('');
			$('#txt_Client_oh').val('NA');
			$('#txt_Client_qh').val('NA');
			$('#txt_Client_th').val('NA');

			$('#txt_Client_th').val('');
			$('#txt_Client_subproc').val('');
			$('#btn_Client_Save').removeClass('hidden');
			$('#btn_Client_Edit').addClass('hidden');
			//$('#btn_Can').addClass('hidden');
			$('#txt_ERSPOC').val('NA');
			$('#txt_ITID').val('');
			$('#txt_HRID').val('');
			$('#txt_ReportsTo').val('');
			$('#txt_Stipen').val('');
			$('#txt_StipendDays').val('');


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
			$('select').formSelect();

		});

	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');

	}

	function getEditData(id) {
		var Process = $('#Process' + id).html();
		var SubProcess = $('#sub_process' + id).html();
		var empid = $('#empid' + id).html();
		var empname = $('#empname' + id).html();
		$('#ProcessEdit').val(Process);
		$('#SubProcessEdit').val(SubProcess);
		$('#empidEdit').val(empid);
		$('#empnameEdit').val(empname);

		$('.statuscheck').removeClass('hidden');
		$('#hiddenid').val(id);
		$('#editdataid').show();
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>