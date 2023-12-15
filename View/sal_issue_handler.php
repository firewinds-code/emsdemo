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
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$alert_msg = '';
// Trigger Button-Save Click Event and Perform DB Action

// Trigger Button-Edit Click Event and Perform DB Action
if (isset($_POST['btn_df_Edit'])) {
	$DataID = clean($_POST['hidden_ID']);
	$_Remarks = clean((isset($_POST['txt_HandlerRemarks']) ? $_POST['txt_HandlerRemarks'] : null));
	//$_desid = (isset($_POST['txt_df_desg']) ? $_POST['txt_df_desg'] : null);
	$ModifiedBy = clean($_SESSION['__user_logid']);
	$myDB =  new MysqliDb();
	$conn = $myDB->dbConnect();
	$update = 'update salary_issue set issue_status="Resolve", approver_remarks=? ,modifiedby=? ,modifiedon=now() where id=? ;';
	$ins = $conn->prepare($update);

	$ins->bind_param("ssi", $_Remarks, $ModifiedBy, $DataID);
	$ins->execute();

	$resu = $ins->get_result();
	if ($ins->affected_rows === 1) {
		echo "<script>$(function(){toastr.success('Record updated successfully')})</script>";
	} else {
		echo "<script>$(function(){toastr.error('Record not updated')})</script>";
	}
}
?>
<script>
	$(document).ready(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			maxDate: '0',
			scrollInput: false
		});

		$('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[10, 25, 50, -1],
				['10 rows', '25 rows', '50 rows', 'Show all']
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
			"sScrollY": '200px',
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
<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Salary Issue Portal</span>
	<div class="pim-container row" id="div_main">
		<div class="form-div">
			<h4>Salary Issue Portal</h4>

			<div class="schema-form-section row">


				<div id="myModal_dept" class="modal">
					<!-- Modal content-->
					<div class="modal-content" style="height: 435px !important">
						<h4 class="col s12 m12 model-h4">Issue Details</h4>
						<div class="modal-body" style="height: 435px !important">
							<input type="hidden" id="hidden_ID" name="hidden_ID"></input>
							<div class="input-field col s6 m6">
								<input type="text" id="txt_IssueType" name="txt_IssueType" readonly />
								<label for="txt_IssueType">Issue Type</label>

							</div>

							<div class="input-field col s6 m6">
								<input type="text" id="txt_IssueDate" name="txt_IssueDate" readonly />
								<label for="txt_IssueDate">Issue Date</label>
							</div>
							<div class="input-field col s12 m12">
								<textarea id="txt_IssueRemarks" class="materialize-textarea" name="txt_IssueRemarks" maxlength="200" readonly></textarea>

								<label for="txt_IssueRemarks">Remarks</label>
							</div>
							<div class="input-field col s12 m12">

								<textarea id="txt_HandlerRemarks" class="materialize-textarea" name="txt_HandlerRemarks" maxlength="200"></textarea>
								<label for="txt_HandlerRemarks">Handler Remarks</label>
							</div>

							<div class="input-field col s12 m12 right-align">
								<input type="hidden" class="form-control hidden" id="hid_df_ID" name="hid_df_ID" />
								<button type="submit" name="btn_df_Save" id="btn_df_Save" class="btn waves-effect waves-green">Add</button>
								<button type="submit" name="btn_df_Edit" id="btn_df_Edit" class="btn waves-effect waves-green hidden">Save</button>
								<button type="button" name="btn_df_Can" id="btn_df_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>
						</div>
					</div>
				</div>

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s4 m4">

						<input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
						<label for="txt_dateFrom" class="form-control">Date From</label>
					</div>
					<div class="input-field col s4 m4">

						<input type="text" class="form-control" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
						<label for="txt_dateTo" class="form-control">Date To</label>
					</div>
					<div class="input-field col s4 m4">
						<select class="form-control" name="txt_status" id="txt_status">
							<option value="Pending">Pending</option>
							<option value="Resolve">Resolve</option>

						</select>
						<label for="txt_status" class="active-drop-down active">Status</label>
					</div>

					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="view" id="view">
							<i class="fa fa-search"></i> Search</button>
					</div>
				</div>

				<div id="pnlTable">
					<?php

					if (isset($_POST['view'])) {
						$sqlConnect = "select t1.id,t1.EmpID, t4.EmpName,t5.client_name,t3.process,t3.sub_process,issue_date,issue_type,emp_remarks,issue_status,t1.CreatedOn from salary_issue t1 join employee_map t2 on t1.EmpID=t2.EmployeeID join new_client_master t3 on t2.cm_id=t3.cm_id join EmpID_Name t4 on t1.EmpID=t4.EmpID join client_master t5 on t3.client_name=t5.client_id  where (issue_date between ? and ?) and issue_status=? ";

						$myDB = new MysqliDb();
						$conn = $myDB->dbConnect();
						$selectQ = $conn->prepare($sqlConnect);
						$selectQ->bind_param("sss", cleanUserInput($_POST['txt_dateFrom']), cleanUserInput($_POST['txt_dateTo']), cleanUserInput($_POST['txt_status']));

						$selectQ->execute();
						$result = $selectQ->get_result();
						// $result = $myDB->query($sqlConnect);
						// echo ($sqlConnect);
						// die;
						// $my_error = $myDB->getLastError();
						if ($result->num_rows > 0) {
							$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
										<div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
							$table .= '<th>Manage</th>';
							$table .= '<th>EmployeeID</th>';
							$table .= '<th>Employee Name</th>';
							$table .= '<th>Process</th>';
							$table .= '<th>Sub Process</th>';
							$table .= '<th>Issue Date</th>';
							$table .= '<th>Issue Type</th>';
							$table .= '<th>Status</th>';
							$table .= '<th>CreatedOn</th>';
							$table .= '<th class="hidden">ID</th>';

							$table .= '<th class="hidden">emp_remarks</th><thead><tbody>';

							foreach ($result as $key => $value) {
								if ($value['issue_status'] == 'Pending') {
									$table .= '<tr><td class="manage_item"> <i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" data-position="left" data-tooltip="Edit">ohrm_edit</i></td>';
								} else {
									$table .= '<tr><td class="manage_item"></td>';
								}

								$table .= '<td class="EmpID">' . $value['EmpID'] . '</td>';
								$table .= '<td class="EmpName">' . $value['EmpName'] . '</td>';
								$table .= '<td class="process">' . $value['process'] . '</td>';
								$table .= '<td class="sub_process">' . $value['sub_process'] . '</td>';
								$table .= '<td class="issue_date">' . $value['issue_date'] . '</td>';
								$table .= '<td class="issue_type">' . $value['issue_type'] . '</td>';
								$table .= '<td class="issue_status">' . $value['issue_status'] . '</td>';
								$table .= '<td class="CreatedOn">' . $value['CreatedOn'] . '</td>';
								$table .= '<td class="id hidden">' . $value['id'] . '</td>';

								$table .= '<td class="emp_remarks hidden">' . $value['emp_remarks'] . '</td></tr>';
							}
							$table .= '</tbody></table></div></div>';
							echo $table;
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
						}
					}

					?>
				</div>
			</div>
		</div>
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
				$('#btn_df_Can').trigger("click");
			}
		});

		// This code for remove error span from all element contain .has-error class on listed events
		$(document).on("click blur focus change", ".has-error", function() {
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

		// This code for cancel button trigger click and also for model close
		$('#btn_df_Can').on('click', function() {
			$('#hidden_ID').val('');
			$('#txt_HandlerRemarks').val('');

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
			//$('#btn_df_Can').addClass('hidden');

		});

		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
		$('#btn_df_Edit').on('click', function() {
			var validate = 0;
			var alert_msg = '';

			$('#txt_HandlerRemarks').removeClass('has-error');

			if ($('#txt_HandlerRemarks').val() == '') {
				$('#txt_HandlerRemarks').addClass('has-error');
				if ($('#span_txt_HandlerRemarks').size() == 0) {
					$('<span id="span_txt_HandlerRemarks" class="help-block">Required *</span>').insertAfter('#txt_HandlerRemarks');
				}
				validate = 1;
			}

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}

		});

		$('#view').click(function() {
			var validate = 0;
			var alert_msg = '';

			$('#txt_dateFrom').removeClass('has-error');
			$('#txt_dateTo').removeClass('has-error');

			if ($('#txt_dateFrom').val() == '') {
				$('#txt_dateFrom').addClass('has-error');
				if ($('#span_txt_dateFrom').size() == 0) {
					$('<span id="span_txt_dateFrom" class="help-block">Required *</span>').insertAfter('#txt_dateFrom');
				}
				validate = 1;
			}
			if ($('#txt_dateTo').val() == '') {
				$('#txt_dateTo').addClass('has-error');
				if ($('#span_txt_dateTo').size() == 0) {
					$('<span id="span_txt_dateTo" class="help-block">Required *</span>').insertAfter('#txt_dateTo');
				}
				validate = 1;
			}

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}

		});

	});
	// This code for trigger edit on all data table also trigger model open on a Model ID
	function EditData(el) {
		var tr = $(el).closest('tr');
		var id = tr.find('.id').text();
		$('#hidden_ID').val(id);
		var issuedate = tr.find('.issue_date').text();
		var issuetype = tr.find('.issue_type').text();
		var empremarks = tr.find('.emp_remarks').text();
		// var des_id = tr.find('.des_id').text();

		//$('#hid_df_ID').val(df_if);
		$('#txt_IssueType').val(issuetype);
		$('#txt_IssueDate').val(issuedate);
		$('#txt_IssueRemarks').val(empremarks);

		$('#btn_df_Save').addClass('hidden');
		$('#btn_df_Edit').removeClass('hidden');
		//$('#btn_df_Can').removeClass('hidden');
		$('#myModal_dept').modal('open');
		$("#myModal_dept input,#myModal_dept textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}
	// This code for trigger del*t*
	function ApplicationDataDelete(el) {
		var currentUrl = window.location.href;
		var Cnfm = confirm("Do You Want To Delete This ");
		if (Cnfm) {
			var xmlhttp;
			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var Resp = xmlhttp.responseText;
					alert(Resp);
					window.location.href = currentUrl;
				}
			}
			xmlhttp.open("GET", "../Controller/Deletedf.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>