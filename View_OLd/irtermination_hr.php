<?php
$Deduction = $Months = NULL;
$Deduction = 'No';
$Months = '0';
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH . 'AppCode/nHead.php');
$alert_msg = '';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_POST['btn_Issue_Save'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		if (isset($_POST['TerminationDateHR'])) {
			$TerDate = cleanUserInput($_POST['TerminationDateHR']);
		} else {
			$TerDate = cleanUserInput($_POST['TerminationDateAH']);
		}
		if (isset($_POST['PLIDeductionYN'])) {
			$Deduction = cleanUserInput($_POST['PLIDeductionYN']);
			$Months = cleanUserInput($_POST['Months']);
		}
		$IRTStatusHR = cleanUserInput($_POST['IRTStatusHR']);
		$remark = addslashes($_POST['remark']);
		$DataID = cleanUserInput($_POST['DataID']);
		$Insert = "update warning_rth set hr_status=?,`Termination Date`=?,`hr_remark`=?,`PLI_Deduction`=?,`Months`=?,hr_Datetime= now() where id=?;";
		$upQ = $conn->prepare($Insert);
		$upQ->bind_param("sssssi", $IRTStatusHR, $TerDate, $remark, $Deduction, $Months, $DataID);
		$upQ->execute();
		$result = $upQ->get_result();
		if ($upQ->affected_rows === 1) {
			// $myDB->rawQuery($Insert);
			// $rowCount = $myDB->count;
			// if (empty($mysql_error)) {
			//////////////////////////////////////////////////////////////////////////////////
			$file_counter = 0;
			if (is_array($_FILES)) {
				$count = 0;
				foreach ($_FILES['txt_doc_name_']['name'] as $name => $value) {
					$count++;
					if (is_uploaded_file($_FILES['txt_doc_name_']['tmp_name'][$name])) {
						$sourcePath = $_FILES['txt_doc_name_']['tmp_name'][$name];
						//$targetPath = $_SERVER['DOCUMENT_ROOT'].'ems/'."wt_docs/".basename($_FILES['txt_doc_name_']['name'][$name]);
						$targetPath = ROOT_PATH . "wt_docs/" . basename($_FILES['txt_doc_name_']['name'][$name]);
						//$targetPath = URL."ir_Documents/".basename($_FILES['txt_doc_name_']['name'][$name]);
						$FileType = pathinfo($targetPath, PATHINFO_EXTENSION);
						//		$val_stype = trim($_POST['txt_doc_stype_'.$count]);
						$uploadOk = 1;
						$validation_check = 0;
						if ($_FILES['txt_doc_name_']['size'][$name] > 400000) {
							echo "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 400KB File only.'); }); </script>";
							$uploadOk = 0;
						}
						if ($validation_check === 0) {
							$Empid = cleanUserInput($_POST['EmployeeID']);
							$dateid = cleanUserInput($_POST['DataID']);
							$userID = clean($_SESSION['__user_logid']);
							$IRTStatusHR = cleanUserInput($_POST['IRTStatusHR']);

							if (move_uploaded_file($sourcePath, $targetPath)) {
								$ext = pathinfo(basename($_FILES['txt_doc_name_']['name'][$name]), PATHINFO_EXTENSION);
								$filename = $Empid . '_' . preg_replace('/\s+/', '', $IRTStatusHR) . '_' . date("dmYhis") . '.' . $ext;
								$file = rename($targetPath, ROOT_PATH . 'wt_docs/' . $filename);
								//$file=rename($targetPath,$_SERVER['DOCUMENT_ROOT'].'ems/wt_docs/'.$filename);
								// $myDB = new MysqliDb();
								$txt_doc_value_ = cleanUserInput($_POST['txt_doc_value_' . $count]);
								$sqlInsertDoc = "insert into warning_rth_documents(EmployeeID,DataId,Document,Title,UploadedBy,`By`,UploadedDate)values(?,?,?,?,?,'HR',Now());";
								$insQ = $conn->prepare($sqlInsertDoc);
								$insQ->bind_param("sssss", $Empid, $dateid, $filename, $txt_doc_value_, $userID);
								$insQ->execute();
								$result = $insQ->get_result();
								// $result = $myDB->rawQuery($sqlInsertDoc);
							}
						}
					}
				}
			}
			//////////////////////////////////////////////////////////////////////////////////
			$dsfsdf = cleanUserInput($_POST['IRTStatusHR']);
			if ($dsfsdf == 'Approved') {
				$ah_remark = '';
				if (isset($_POST['ah_Remark'])) {
					$ah_remark = cleanUserInput($_POST['ah_Remark']);
				}
				$now = 'Now()';
				$subtype = cleanUserInput($_POST['SubType']);
				//$now=date('Y-m-d H:i:s');
				/*$Insert="INSERT INTO exit_emp(EmployeeID,dol,rsnofleaving,hrcmt,optcmt,createdby,disposition)VALUES('".$_POST['EmployeeID']."',".$now.",'TER','".addslashes($_POST['remark'])."','".addslashes($ah_remark)."','".$_SESSION['__user_logid']."','TER');";*/
				if ($subtype != "" && $_POST['remark'] != "") {
					$emp = cleanUserInput($_POST['EmployeeID']);
					$userid = clean($_SESSION['__user_logid']);
					$remarks = addslashes($_POST['remark']);
					$myDB = new MysqliDb();
					$Insert = 'call exit_employee("' . $emp . '",' . $now . ',"' . $subtype . '","' . $remarks . '","' . addslashes($ah_remark) . '","' . $userid . '","TER")';


					$result = $myDB->rawQuery($Insert);

					echo "<script>$(function(){ toastr.success('Request Approved.'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Invalid request , subtype is not selected .'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.success('Request Approved.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.success('Error-'.) }); </script>";
		}
	}
}
?>

<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [
				/*{
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
				},
				/*'copy',*/
				'pageLength'
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
<style>
	[type="checkbox"]+label {
		font-size: 14px !important;
		margin-top: 28px;
	}

	.modal-body {
		max-height: 452px !important;
		overflow: auto;
	}

	/*
.modal .modal-content > div {
    padding: 0px;
}*/
</style>
<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Refer to HR</span>
	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<div class="form-div">
			<h4>Refer to HR Requests
				<!-- <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Issue"><i class="material-icons">add</i></a> -->
			</h4>
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div id="myModal_content" class="modal modal_big">
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Refer to HR Requests</h4>
						<div class="modal-body">
							<div class="col s12 m12">

								<div class="input-field col s4 m4">
									<input type="hidden" id="DataID" name="DataID" />
									<input type="text" readonly id="EmployeeID" name="EmployeeID" required />
									<label for="empID">Employee ID</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" readonly id="EmployeeName" name="EmployeeName" required />
									<label for="empID">Employee Name</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" class="form-control" readonly id="AHStatus" name="AHStatus" />
									<label for="empID">AH Status</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" class="form-control" required readonly id="SubType" name="SubType" />
									<label for="empID">Sub Type</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" class="form-control" id="TerminationDateAH" name="TerminationDateAH" readonly />
									<label for="empID">Date Given By AH</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" class="form-control" id="TerminationDateHR" name="TerminationDateHR" />
									<label for="empID">Date HR</label>
								</div>

								<!-- <div class="input-field col s12 m12">
					<textarea name="ah_Remark" id="ah_Remark" readonly maxlength="1000" class="materialize-textarea"></textarea>
					<label for="ah_Remark">AH Remark</label>
				</div>-->
								<div class="input-field col s12 m12">
									<ul class="collapsible">
										<li>
											<div class="collapsible-header topic">Previous History</div>
											<div class="collapsible-body">
												<div id="AppendDoc"></div>
											</div>
										</li>

								</div>
								<!--<div class="input-field col s12 m12">
					<!--<div id="AppendDoc" style=" overflow-y: auto; width: 28%;">-->

								<div class="input-field col s12 m12">
									<div class="form-group">
										<button type="button" name="btn_docAdd" id="btn_docAdd" title="Add Doc Row in Table Down" class="btn waves-effect waves-green"><i class="fa fa-plus"></i> Add Document</button>
										<button type="button" name="btnDoccan" id="btnDoccan" title="Remove Doc Row in Table Down" class="btn waves-effect waves-red close-btn"><i class="fa fa-minus"></i> Remove Document</button>
									</div>
								</div>

								<table class="table table-hovered table-bordered" id="childtable">
									<thead class="bg-danger">
										<tr>
											<th class="hidden">Doc ID</th>
											<th>Document File</th>
											<th>Document Name</th>
										</tr>
									</thead>
									<tbody>
										<tr class="trdoc" id="trdoc_1">
											<td class="doccount hidden">1</td>
											<td><input name="txt_doc_name_[]" type="file" id="txt_doc_name_1" class="form-control clsInput file_input" /></td>
											<td><input type="text" value="" name="txt_doc_value_1" id="txt_doc_value_1" /></td>
										</tr>
									</tbody>
								</table>
								<div class="input-field col s12 m12">
									<textarea name="remark" id="remark" required minlength="250" maxlength="250" class="materialize-textarea"></textarea>
									<label for="remark">HR Remark</label>
								</div>
								<SPAN ID='A'></SPAN>
								<div class="input-field col s4 m4">
									<select id="IRTStatusHR" name="IRTStatusHR" required>
										<option value="NA">---Select---</option>
										<option value="Approved">Approved</option>
										<option value="Decline">Decline</option>
										<?php
										$SearchBy = cleanUserInput($_POST['SearchBy']);
										if (isset($SearchBy) && $SearchBy == 'Refer to HR') {
											//echo '<option value="Warnig Letter">Warnig Letter</option>';
										} ?>
									</select>
									<label for="IRTStatusAH" class="active-drop-down active">HR Status</label>
								</div>

								<div class="input-field col s4 m4" id="pli" style="display: none;">
									<input type="checkbox" id="PLIDeductionYN" name="PLIDeductionYN" value="Yes" style="margin-top: 10px;" />
									<label for="PLIDeductionYN">PLI Deduction</label>
								</div>

								<div class="input-field col s4 m4" id="month" style="display: none;">
									<select id="Months" name="Months">
										<optgroup>
											<option selected value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
										</optgroup>
									</select>
									<label for="Months" class="active-drop-down active">PLI Deduction</label>
								</div>

							</div>
							<div class="input-field col s12 m12 right-align">
								<input type="hidden" class="form-control hidden" id="empid" name="empid" />
								<button type="submit" name="btn_Issue_Save" id="btn_Issue_Save" class="btn waves-effect waves-green">Submit</button>
								<button type="submit" name="btn_Issue_Edit" id="btn_Issue_Edit" class="btn waves-effect waves-green hidden">Save</button>
								<button type="button" name="btn_Issue_Can" id="btn_Issue_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>
						</div>

					</div>
				</div>

				</form>
				<form method="post">
					<div class="input-field col s12 m12 left-align">
						<div class="input-field col s10 m10">
							<select id="SearchBy" name="SearchBy">
								<option value="NA">--Select--</option>
								<option value="Refer to HR">Refer to HR</option>
								<!--<option value="Warnig Letter">Warnig Letter</option>-->
							</select>
							<label for="SearchBy" class="active-drop-down active">Search By</label>
						</div>
						<div class="ibtn btn-danger btn-lg">
							<button type="submit" name="btnSearch" id="btnSearch" data-toggle="modal" class="btn waves-effect waves-green">Search</button>
						</div>
					</div>
					<div id="pnlTable">
						<?php
						#$sqlConnect = "SELECT * FROM irtable where hr_status is null and ah_status is not null;";
						if (isset($_POST['btnSearch'])) {
							#$sqlConnect = "SELECT * FROM warning_rth where `ah_status`='".$_POST['SearchBy']."'and hr_status is null;";
							if (clean($_SESSION["__location"]) == "1") {
								$SearchBy = cleanUserInput($_POST['SearchBy']);
								$sqlConnect = "SELECT wr.* FROM warning_rth wr inner join personal_details pd on wr.EmployeeID = pd.EmployeeID where ((wr.ah_status = ? and wr.hr_status is null) or (wr.ah_status = 'Refer to QH' and wr.qh_status ='Approved' and wr.hr_status is null)) and pd.location in (1,2);";
								$selectQu = $conn->prepare($sqlConnect);
								$selectQu->bind_param("s", $SearchBy);
								$selectQu->execute();
								$result = $selectQu->get_result();
							} else {
								$SearchBy = cleanUserInput($_POST['SearchBy']);
								$loc = clean($_SESSION["__location"]);
								$sqlConnect = "SELECT wr.* FROM warning_rth wr inner join personal_details pd on wr.EmployeeID = pd.EmployeeID where ((wr.ah_status = ? and wr.hr_status is null) or (wr.ah_status = 'Refer to QH' and wr.qh_status ='Approved' and wr.hr_status is null)) and pd.location in ('" . $loc . "');";
								$selectQu = $conn->prepare($sqlConnect);
								$selectQu->bind_param("s", $SearchBy);
								$selectQu->execute();
								$result = $selectQu->get_result();
							}
							//echo $sqlConnect;

							// $myDB = new MysqliDb();
							// $result = $myDB->rawQuery($sqlConnect);
							// $mysql_error = $myDB->getLastError();
							// if (empty($mysql_error)) {
							// } 
						?>
							<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Employee ID</th>
										<th class="hidden">DataID</th>
										<th class="hidden">AH Status</th>
										<th class="hidden">AH SubType</th>
										<th class="hidden">remark</th>
										<th class="hidden">TerminationDateAH</th>
										<th>Employee Name</th>
										<th>Designation</th>
										<th>DOJ</th>
										<th style="text-align: center">Process</th>
										<th>Account Head</th>
										<!--<th>Report To</th>-->
										<th>Request Raised On</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($result as $key => $value) {

										echo '<tr>';
										echo '<td class="EmployeeID">' . $value['EmployeeID'] . '</td>';
										echo '<td class="ah_status hidden">' . $value['ah_status'] . '</td>';
										echo '<td class="SubType hidden">' . $value['ah_subtype'] . '</td>';
										echo '<td class="DataID hidden">' . $value['id'] . '</td>';
										echo '<td class="remark hidden">' . $value['ah_remark'] . '</td>';
										echo '<td class="TerminationDateAH hidden">' . $value['Termination Date'] . '</td>';
										echo '<td class="EmployeeName">' . $value['EmployeeName'] . '</td>';
										echo '<td class="designation">' . $value['Designation'] . '</td>';
										echo '<td class="doj">' . date("d-m-Y", strtotime($value['DOJ'])) . '</td>';
										echo '<td class="Process">' . $value['Process'] . '</td>';
										echo '<td class="account_head">' . $value['Account Head'] . '</td>';
										/*echo '<td class="ReportTo">'.$value['Report To'].'</td>';*/
										echo '<td class="ah_Datetime">' . $value['ah_Datetime'] . '</td>';
										echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['EmployeeID'] . '" data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						<?php
						}
						?>
					</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {

	});

	$('.modal').modal({
		onOpenStart: function(elm) {

		},
		onCloseEnd: function(elm) {
			$('#btn_Client_Can').trigger("click");
		}
	});
	$('#IRTStatusHR').change(function() {
		$('#month').hide();
		$('#month').val('1');
		$('#pli').hide();
		$('#PLIDeductionYN').prop('checked', false);
		/*if($(this).val()=='Warnig Letter')
		{
			$('#pli').show();
		}*/
	});
	$('input[type="checkbox"]').click(function() {
		$('#month').hide();
		$('#month').val('1');
		if ($(this).prop("checked") == true) {
			$('#month').show();
		} else if ($(this).prop("checked") == false) {
			$('#month').hide();
			$('#month').val('1')
		}
		$('select').formSelect();
	});
	$('select').formSelect();
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
	$('#btn_Issue_Can').on('click', function() {
		$('#btn_Issue_Save').removeClass('hidden');
		$('#btn_Issue_Edit').addClass('hidden');
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
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();

	});

	$('#btnSearch').on('click', function() {
		var validate = 0;
		if ($('#SearchBy').val() == 'NA') {
			validate = 1;
			$('#SearchBy').addClass('has-error');
			//$('#txtID').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
			if ($('#spantxt_SearchBy').length == 0) {
				$('<span id="spantxt_SearchBy" class="help-block">Required *</span>').insertAfter('#SearchBy');
			}
		}

		if (validate == 1) {
			return false;
		}

	});

	$('#btn_Issue_Save').on('click', function() {

		var validate = 0;
		var alert_msg = '';
		/*if($('#PLIDeductionYN').prop("checked") == true)
            {
                if($('#month').val()=='NA')
	             {
	             	$('#month').attr('required');
	             	validate=1;	
	             	$('#month').addClass('has-error');
				 }
            }*/
		$("input,select,textarea").each(function() {
			var spanID = "span" + $(this).attr('id');
			$(this).removeClass('has-error');
			if ($('#remark').val() != "") {
				str = $('#remark').val();
				var repeats = /(.)\1{3,}/;
				if (repeats.test(str)) {
					validate = 1;
					$('#A').addClass("has-error help-block");
					$('#A').html('Remark should not contain Repeat character.');
					validate = 1;
				}
				var str1 = $('#remark').val();
				str2 = '<';
				str3 = '>';
				if (str1.indexOf(str2) != -1 || str1.indexOf(str3) != -1) {
					$('#A').addClass("has-error help-block");
					$('#A').html('Remark should not contain "<" or  ">" character.');
					validate = 1;
				}
			}
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
			//$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
			//$('#alert_message').show().attr("class","SlideInRight animated");
			//$('#alert_message').delay(50000).fadeOut("slow");
			return false;
		}

	});

	function EditData(el) {
		var tr = $(el).closest('tr');
		var EmployeeID_ = tr.find('.EmployeeID').text();
		var DataID = tr.find('.DataID').text();
		var remark = tr.find('.remark').text();
		var EmployeeName = tr.find('.EmployeeName').text();
		var Process = tr.find('.Process').text();
		var sub_process = tr.find('.sub_process').text();
		var account_head = tr.find('.account_head').text();
		var clientname = tr.find('.clientname').text();
		var designation = tr.find('.designation').text();
		var ReportTo = tr.find('.ReportTo').text();
		var ah_status = tr.find('.ah_status').text();
		var SubType = tr.find('.SubType').text();
		var ah_Datetime = tr.find('.ah_Datetime').text();
		var TerminationDateAH = tr.find('.TerminationDateAH').text();

		$('#AHStatus').val(ah_status);
		$('#SubType').val(SubType);
		$('#TerminationDateAH').val(TerminationDateAH);
		$('#TerminationDateHR').val(TerminationDateAH);
		$('#EmployeeID').val(EmployeeID_);
		$('#DataID').val(DataID);
		$('#ah_Remark').val(remark);
		$('#EmployeeName').val(EmployeeName);
		$('#Process').val(Process);
		$('#SubProcess').val(sub_process);
		$('#AccountHead').val(account_head);
		$('#Clientname').val(clientname);
		$('#Designation').val(designation);
		$('#ReportTo').val(ReportTo);
		$('#ReportTo').val(ReportTo);
		$('#ReportTo').val(ReportTo);
		$('#txtFile').html($(el).parents('td').parents('tr').find('.cls_Task_file').text());
		$('#txtFile').attr('href', 'Document/' + $(el).parents('td').parents('tr').find('.cls_Task_file').text());
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}
		});
		$('#TerminationDateHR').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			minDate: new Date(TerminationDateAH),
			maxDate: new Date()
		});
		$('select').formSelect();
		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "View/bindDocIR1.php?empid=" + EmployeeID_
		}).done(function(data) {
			$('#AppendDoc').html(data)
		});
		/*$.ajax({ url: <?php echo '"' . URL . '"'; ?>+"View/BingPreviousHistoryForTermination.php?empid="+EmployeeID_ }).done(function(data) {$('#AppendDoc').html(data)});*/
	}

	function Download(el) {
		if ($(el).attr("data") != '') {
			function getImageDimensions(path, callback) {
				var img = new Image();
				img.onload = function() {
					callback({
						width: img.width,
						height: img.height,
						srcsrc: img.src
					});
				}
				img.src = path;
			}

			$.ajax({
				//url:"../Docs/"+$(el).attr("data"),
				url: "../wt_docs/" + $(el).attr("data"),
				type: 'HEAD',
				error: function() {
					alert('No File Exist');
				},
				success: function() {
					imgcheck = function(filename) {
						return (filename).split('.').pop();
					}
					//imgchecker = imgcheck("../Docs/"+$(el).attr("data"));
					imgchecker = imgcheck("../wt_docs/" + $(el).attr("data"));

					if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
						//getImageDimensions("../Docs/"+$(el).attr("data"),function(data){
						getImageDimensions("../wt_docs/" + $(el).attr("data"), function(data) {
							var img = data;

							$('<img>', {
								src: "../wt_docs/" + $(el).attr("data")
							}).watermark({
								//text: '? For Cogent E Services Ltd.',
								text: 'Cogent E Services Ltd.',
								//path:'../Style/images/cogent-logobkp.png',
								textWidth: 370,
								opacity: 1,
								textSize: (img.height / 15),
								nH: img.height,
								nW: img.width,
								textColor: "rgb(0,0,0,0.4)",
								outputType: 'jpeg',
								gravity: 'sw',
								done: function(imgURL) {
									var link = document.createElement('a');
									link.href = imgURL;
									link.download = $(el).attr("data");
									document.body.appendChild(link);
									link.click();
								}
							});
						});
					} else if (imgchecker.match(/(pdf)$/i)) {
						window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src=" + "../../wt_docs/" + $(el).attr("data"));
					} else {
						//window.open("../Docs/"+$(el).attr("data"));
						window.open("../wt_docs/" + $(el).attr("data"));
					}

				}
			});

			/*$('.schema-form-section img').watermark({
					    
				  	});*/

		} else {
			alert('No File Exist');
		}
	}
	$('#btn_docAdd').click(function() {

		$count = $(".trdoc").length;
		$id = "trdoc_" + parseInt($count + 1);
		$('#doc_child').val(parseInt($count + 1));
		$tr = $("#trdoc_1").clone().attr("id", $id);
		$('#childtable tbody').append($tr);
		$tr.children("td:first-child").html(parseInt($count + 1));
		$tr.children("td:nth-child(2)").children("input").attr({
			"id": "txt_doc_name_" + parseInt($count + 1),
			"name": "txt_doc_name_[]"
		}).val('');
		$tr.children("td:nth-child(3)").children("input").attr({
			"id": "txt_doc_value_" + parseInt($count + 1),
			"name": "txt_doc_value_" + parseInt($count + 1)
		}).empty();
	});
	$('#btnDoccan').click(function() {
		$count = $(".trdoc").length;
		if ($count > 1) {
			$('#childtable tbody').children("tr:last-child").remove();
			$('#doc_child').val(parseInt($count - 1));
		}
	});
	$('#btn_Issue_Can').on('click', function() {

		$('#remark').val('');
		$('#txt_doc_value_1').val('');

		$($('[id^=trdoc_]')).each(function() {
			//alert(this.id);

			if (this.id != 'trdoc_1') {
				$(this).remove();
			}
		});
		$('#btn_Issue_Save').removeClass('hidden');
		$('#btn_Issue_Edit').addClass('hidden');
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
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>