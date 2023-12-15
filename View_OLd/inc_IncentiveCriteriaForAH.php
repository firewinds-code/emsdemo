<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$Id = "";
$Incentive_Type = $StartDate = $Rate = $criteria1 = $criteria2 = $cm_id = $process = $Request_Status = $BaseCriteria = $EndDate = '';
$classvarr = "'.byID'";
$searchBy = '';
$Id = "";
$level1 = "";
$level2 = "";
$rate2 = "";
$criteria11 = "";
$criteria12 = "";
$rate3 = "";
$criteria13 = "";
$criteria23 = "";

$user_logid = clean($_SESSION['__user_logid']);
?>
<script>
	$(document).ready(function() {
		$('#StartDate, #EndDate').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			minDate: new Date(),
			scrollInput: false,
		});
		$('.statuscheck').addClass('hidden');
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 10,
			scrollX: '100%',
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

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
				}, 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-excel').attr('id', 'buttons_excel');
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
	<span id="PageTittle_span" class="hidden">Incentive Module</span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Incentive </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div id="pnlTable">
					<?php
					//$value['StartDate']
					$sqlConnect = "call inc_GetIncentiveCriteria('" . $user_logid . "')";
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					//echo $sqlConnect;
					$error = $myDB->getLastError();
					if ($result) { ?>
						<div class="had-container pull-left row card dataTableInline" id="tbl_div">
							<div class="">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>SN.</th>
											<th>Incentive Type</th>
											<th>StartDate</th>
											<th>EndDate</th>
											<th>BaseCriteria</th>
											<th>Amount1</th>
											<th>ShiftIN1 / PDays1</th>
											<th>ShiftOUT1 / ADays1</th>
											<th>Amount2</th>
											<th>ShiftIN2 / PDays2</th>
											<th>ShiftOUT2 / ADays2</th>
											<th>Amount3</th>
											<th>ShiftIN3 / PDays3</th>
											<th>ShiftOUT3 / ADays3</th>
											<th>Applicable For</th>
											<th>Process</th>
											<th>Sub-Process</th>

										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										///  print_r($result);
										foreach ($result as $key => $value) {
											$count++;
											$level1 = "";
											$level2 = "";
											$rate2 = "";
											$criteria11 = "";
											$criteria12 = "";
											$rate3 = "";
											$criteria13 = "";
											$criteria23 = "";


											if ($value['Incentive_Type'] == 'Attendance' || $value['Incentive_Type'] == 'Woman') {
												$level1 = 'Present Days';
												$level2 = 'Absent Days';

												if (isset($value['Rate2']) && $value['Rate2'] != "") {
													$rate2 = $value['Rate2'];
													$criteria11 = $value['criteria12'] . " Present Days";
													$criteria12 = $value['criteria22'] . " Absent Days";
												}
												if (isset($value['Rate3']) && $value['Rate3'] != "") {
													$rate3 = $value['Rate3'];
													$criteria13 = $value['criteria13'] . " Present Days";
													$criteria23 = $value['criteria23'] . " Absent Days";
												}
											} else {
												$level1 = '(ShiftIN)';
												$level2 = '(ShiftOut)';
											}
											echo '<tr>';
											echo '<td id="countc' . $count . '">' . $count . '</td>';
											echo '<td class="Incentive_Type" id="Incentive_Type' . $count . '">' . $value['Incentive_Type'] . '</td>';
											echo '<td class="StartDate"  id="StartDate' . $count . '" >' . $value['StartDate'] . '</td>';
											echo '<td class="EndDate" id="EndDate' . $count . '"  >' . $value['EndDate'] . '</td>';
											echo '<td class="BaseCriteria" id="BaseCriteria' . $count . '"  >' . $value['BaseCriteria'] . '</td>';
											echo '<td class="Rate" id="Rate_edit' . $count . '">' . $value['Rate'] . '</td>';
											echo '<td class="criteria1" id="criteria1_edit' . $count . '">' . $value['criteria1'] . ' ' . $level1 . '</td>';
											echo '<td class="criteria2" id="criteria2_edit' . $count . '">' . $value['criteria2'] . ' ' . $level2 . '</td>';
											echo '<td class="Rate" id="Rate_edit' . $count . '">' . $rate2 . '</td>';
											echo '<td class="criteria1" id="criteria1_edit' . $count . '">' . $criteria11 . '</td>';
											echo '<td class="criteria2" id="criteria2_edit' . $count . '">' . $criteria12 . '</td>';
											echo '<td class="Rate" id="Rate_edit' . $count . '">' . $rate3 . '</td>';
											echo '<td class="criteria1" id="criteria1_edit' . $count . '">' . $criteria13 . '</td>';
											echo '<td class="criteria2" id="criteria2_edit' . $count . '">' . $criteria23 . '</td>';
											echo '<td class="ApplicableFor" id="ApplicableFor' . $count . '">' . $value['ApplicableFor'] . '</td>';
											echo '<td class="Process" id="Process' . $count . '">' . $value['Process'] . '</td>';
											echo '<td class="Process" id="Process' . $count . '">' . $value['sub_process'] . '</td>';

											echo "</tr>";
										?>

										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					} else {
						//echo '<div id="div_error" class="slideInDown animated hidden">Data Not Found :: <code >'.$error.'</code> </div>';
						echo "<script>$(function(){ toastr.info('Data Not Found <code >" . $error . "</code>'); }); </script>";
					}


					?>
				</div>
			</div>


			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	function getEditData(editID) {
		$(".schema-form-section input,.schema-form-section text").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}
		});
		$('.statuscheck').removeClass('hidden');
		$('#datastatus').show();
		$('#btnEdit').show();
		$('#btnSave1').hide();

		var Incentive_Type = incType = $('#Incentive_Type' + editID).html();
		if (incType == 'Split' || incType == 'Night/Late Evening' || incType == 'Morning') {
			$("#BaseCriteria").val('Login Window');
			$("#BaseCriteria2").val('Login Window');
			$('select').formSelect();
		} else {
			$("#BaseCriteria").val('Present Days');
			$("#BaseCriteria2").val('Present Days');
			$('select').formSelect();
		}
		if (incType == 'Split') {
			criteria1 = "<option value=''>---Select---</option><option value='5 AM'>5 AM</option><option value='6 AM'>6 AM</option> <option value='7 AM'>7 AM</option> <option value='8 AM'>8 AM</option>";
			$("#criteria1").html(criteria1);
			criteria2 = "<option value=''>---Select---</option><option value='8 PM'>8 PM</option> <option value='9 PM'>9 PM</option> <option value='10 PM'>10 PM</option><option value='11 PM'>11 PM</option> ";
			$("#criteria2").html(criteria2);
			$('select').formSelect();

		} else
		if (incType == 'Attendance') {
			$('#newField').show();
			criteria1 = " <option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24 Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";
			$("#c1").html('Present :');
			$("#criteria1").html(criteria1);
			$("#criteria12").html(criteria1);
			$("#criteria13").html(criteria1);
			criteria2 = "<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
			//alert(criteria2);	
			$("#c2").html('Absent :');
			$("#criteria2").html(criteria2);
			$("#criteria22").html(criteria2);
			$("#criteria23").html(criteria2);

			$('select').formSelect();
		} else
		if (incType == 'Night/Late Evening') {
			criteria1 = "<option value=''>---Select---</option>";
			var criteria1 = "";
			for (i = 1; i <= 12; i++) {
				criteria1 += "<option value='" + i + " PM'>" + i + " PM</option>";
			}
			criteria1 += "<option value='12 AM'>12 AM</option>";
			$("#criteria1").html(criteria1);

			criteria2 = "<option value=''>---Select---</option><option value='9 PM'>9 PM</option>  <option value='10 PM'>10 PM</option>  <option value='11 PM'>11 PM</option>  <option value='12 PM'>12 PM</option>";

			for (j = 1; j <= 9; j++) {
				criteria2 += "<option value='" + j + " AM'>" + j + " AM</option>";

			}
			$("#criteria2").html(criteria2);

			$('select').formSelect();
		} else
		if (incType == 'Morning') {
			criteria1 = "<option value=''>---Select---</option>";
			var criteria1 = "";
			for (i = 4; i <= 7; i++) {
				criteria1 += "<option value='" + i + " AM'>" + i + " AM</option>";
			}
			$("#criteria1").html(criteria1);
			var criteria2 = "";
			criteria2 = "<option value=''>---Select---</option>	<option value='1 PM'>1 PM</option>  <option value='2 PM'>2 PM</option>  <option value='3 PM'>3 PM</option>  <option value='4 PM'>4 PM</option>";
			$("#criteria2").html(criteria2);
			$('select').formSelect();
		} else
		if (incType == 'Woman') {

			$('#newField').show();
			criteria1 = "<option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";
			$("#c1").html('Present :');
			$("#criteria1").html(criteria1);
			$("#criteria12").html(criteria1);
			$("#criteria13").html(criteria1);
			criteria2 = "<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
			$("#c2").html('Absent :');
			$("#criteria2").html(criteria2);
			$("#criteria22").html(criteria2);
			$("#criteria23").html(criteria2);
			$('select').formSelect();
		}

		var StartDate = $('#StartDate' + editID).html();
		var EndDate = $('#EndDate' + editID).html();
		//	var BaseCriteria= $('#BaseCriteria'+editID).html();
		var Rate = $('#Rate_edit' + editID).html();
		var Rate2 = $('#Rate2' + editID).val();
		var Rate3 = $('#Rate3' + editID).val();
		var criteria1 = $('#criteria1_edit' + editID).html();
		if (criteria1.indexOf(";") >= 0) {
			ct = criteria1.split(';');
			criteria1 = '>' + ct[1];
		}

		var criteria2 = $('#criteria2_edit' + editID).html();
		var criteria12 = $('#criteria12' + editID).val();
		var criteria22 = $('#criteria22' + editID).val();
		var criteria13 = $('#criteria13' + editID).val();
		var criteria23 = $('#criteria23' + editID).val();
		var cm_id = $('#cm_id' + editID).val();
		var process = $('#Process' + editID).html();
		//alert(process);
		var selectProcess = "<option  value='" + cm_id + "'>" + process + "</option>";
		var Request_Status = $('#Request_Status' + editID).html();
		var ApplicableFor = $('#ApplicableFor' + editID).html();
		var incSstatus = $('#incStatus' + editID).val();
		var editId = $('#id' + editID).val();
		$('#Incentive_Type').val(Incentive_Type);
		$('#StartDate').val(StartDate);
		$('#EndDate').val(EndDate);
		//$('#BaseCriteria2').val(BaseCriteria);
		//$('#BaseCriteria').val(BaseCriteria);
		$('#Rate').val(Rate);
		$('#Rate2').val(Rate2);
		$('#Rate3').val(Rate3);
		$('#criteria1').val(criteria1);
		$('#criteria12').val(criteria12);
		$('#criteria13').val(criteria13);
		$('#criteria2').val(criteria2);
		$('#criteria22').val(criteria22);
		$('#criteria23').val(criteria23);
		$('#cm_id').html(selectProcess);
		$('select').formSelect();
		$('#userProcess').val(process);
		$('#ApplicableFor').val(ApplicableFor);
		$('#Request_Status').val(Request_Status);
		$('#incentiveStatus').val(incSstatus);
		$('#editId').val(editId);
		$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}
		});
		$('select').formSelect();
	}
	$(document).ready(function() {
		$('#btnCancel').click(function() {
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
			$('.statuscheck').addClass('hidden');
		});


		$("#Incentive_Type").on('change', function() {
			var incType = $("#Incentive_Type").val();
			if (incType == 'Split' || incType == 'Night/Late Evening' || incType == 'Morning') {
				$("#BaseCriteria").val('Login Window');
				$("#BaseCriteria2").val('Login Window');
				$("#c1").html('Shift IN :');
				$("#c2").html('Shift OUT :');
				$('#newField').hide();
				$("#criteria12").val('');
				$("#criteria13").val('');
				$("#criteria23").val('');
				$("#criteria22").val('');
				$("#Rate2").val('');
				$("#Rate3").val('');

			} else {
				$("#BaseCriteria").val('Present Days');
				$("#BaseCriteria2").val('Present Days');
				$("#c1").html('Present :');
				$("#c2").html('Absent :');
				$('#newField').show();
			}
			var criteria1 = "";
			var criteria2 = "";
			if (incType == 'Split') {
				criteria1 = "<option value=''>---Select---</option><option value='5 AM'>5 AM</option><option value='6 AM'>6 AM</option> <option value='7 AM'>7 AM</option> <option value='8 AM'>8 AM</option>";
				$("#criteria1").html(criteria1);
				criteria2 = "<option value=''>---Select---</option><option value='8 PM'>8 PM</option> <option value='9 PM'>9 PM</option> <option value='10 PM'>10 PM</option><option value='11 PM'>11 PM</option> ";
				$("#criteria2").html(criteria2);
				$('select').formSelect();
			} else
			if (incType == 'Attendance') {
				$('#newField').show();
				criteria1 = " <option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24 Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";
				$("#c1").html('Present :');
				$("#criteria1").html(criteria1);
				$("#criteria12").html(criteria1);
				$("#criteria13").html(criteria1);
				criteria2 = "<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
				//alert(criteria2);	
				$("#c2").html('Absent :');
				$("#criteria2").html(criteria2);
				$("#criteria22").html(criteria2);
				$("#criteria23").html(criteria2);
				$('select').formSelect();
			} else
			if (incType == 'Night/Late Evening') {
				criteria1 = "<option value=''>---Select---</option>";
				criteria1 += "<option value='12 PM'>12 PM</option>";
				for (i = 1; i < 12; i++) {
					criteria1 += "<option value='" + i + " PM'>" + i + " PM</option>";
				}
				criteria1 += "<option value='12 AM'>12 AM</option>";
				$("#criteria1").html(criteria1);

				criteria2 = "<option value=''>---Select---</option>	<option value='9 PM'>9 PM</option><option value='10 PM'>10 PM</option><option value='11 PM'>11 PM</option><option value='12 PM'>12 PM</option>";

				for (j = 1; j <= 9; j++) {
					criteria2 += "<option value='" + j + " AM'>" + j + " AM</option>";

				}
				$("#criteria2").html(criteria2);
				$('select').formSelect();

			} else
			if (incType == 'Morning') {
				criteria1 = "<option value=''>---Select---</option>";
				for (i = 4; i <= 7; i++) {
					criteria1 += "<option value='" + i + " AM'>" + i + " AM</option>";
				}
				$("#criteria1").html(criteria1);
				criteria2 = "<option value=''>---Select---</option>	<option value='1 PM'>1 PM</option>  <option value='2 PM'>2 PM</option>  <option value='3 PM'>3 PM</option>  <option value='4 PM'>4 PM</option>";
				$("#criteria2").html(criteria2);
				$('select').formSelect();
			} else
			if (incType == 'Woman') {
				$('#newField').show();
				criteria1 = "<option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";
				$("#c1").html('Present :');
				$("#criteria1").html(criteria1);
				$("#criteria12").html(criteria1);
				$("#criteria13").html(criteria1);
				criteria2 = "<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
				$("#c2").html('Absent :');
				$("#criteria2").html(criteria2);
				$("#criteria22").html(criteria2);
				$("#criteria23").html(criteria2);
				$('select').formSelect();
			}
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}
			});
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}
			});
		});
		$('#cm_id').change(function() {
			var Process = $('#cm_id option:selected').attr('id');
			$('#userProcess').val(Process);
			$('select').formSelect();
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
			$('select').formSelect();
		});
		$('#criteria1').change(function() {
			$('#criteria2').val('');
			var incType = $("#Incentive_Type").val();
			if (incType == 'Attendance') {
				$('#criteria2').val('');
				$('select').formSelect();
			} else
			if (incType == 'Night/Late Evening') {
				var criteria1 = $('#criteria1').val();
				//alert(criteria1);
				if (criteria1 == '1 PM') {
					$('#criteria2').val('10 PM');
				} else
				if (criteria1 == '2 PM') {
					$('#criteria2').val('11 PM');
				} else
				if (criteria1 == '3 PM') {
					$('#criteria2').val('12 PM');
				} else
				if (criteria1 == '4 PM') {
					$('#criteria2').val('1 AM');
				} else
				if (criteria1 == '5 PM') {
					$('#criteria2').val('2 AM');
				} else
				if (criteria1 == '6 PM') {
					$('#criteria2').val('3 AM');
				} else
				if (criteria1 == '7 PM') {
					$('#criteria2').val('4 AM');
				} else
				if (criteria1 == '8 PM') {
					$('#criteria2').val('5 AM');
				} else
				if (criteria1 == '9 PM') {
					$('#criteria2').val('6 AM');
				} else
				if (criteria1 == '10 PM') {
					$('#criteria2').val('7 AM');
				} else
				if (criteria1 == '11 PM') {
					$('#criteria2').val('8 AM');
				} else
				if (criteria1 == '12 PM') {
					$('#criteria2').val('9 PM');
				} else
				if (criteria1 == '12 AM') {
					$('#criteria2').val('9 AM');
				}
				$('select').formSelect();
			} else
			if (incType == 'Morning') {
				var criteria1 = $('#criteria1').val();
				if (criteria1 == '4 AM') {
					$('#criteria2').val('1 PM');
				} else
				if (criteria1 == '5 AM') {
					$('#criteria2').val('2 PM');
				} else
				if (criteria1 == '6 AM') {
					$('#criteria2').val('3 PM');
				} else
				if (criteria1 == '7 AM') {
					$('#criteria2').val('4 PM');
				}
				$('select').formSelect();
			}
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});

		});
		$('#btnEdit').click(function() {
			validate = 0;
			alert_msg = "";
			incType = $('#Incentive_Type').val();
			if ($('#Incentive_Type').val() == "") {
				validate = 1;
				//alert_msg+='<li> Please select Incentive Type</li>';
				$('#Incentive_Type').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_Incentive_Type').size() == 0) {
					$('<span id="span_Incentive_Type" class="help-block">Please select Incentive Type</span>').insertAfter('#Incentive_Type');
				}

			}
			if (incType == 'Split' || incType == 'Night/Late Evening' || incType == 'Morning') {
				var level1 = 'Shift IN';
				var level2 = 'Shift OUT';
			} else {
				var level1 = 'Present day';
				var level2 = 'Absent day';
			}
			var StartDate = $('#StartDate').val().trim();
			if (StartDate == '') {
				validate = 1;
				$('#StartDate').addClass('has-error');
				if ($('#stxt_StartDate').size() == 0) {
					$('<span id="stxt_StartDate" class="help-block">Please select Start Date</span>').insertAfter('#StartDate');
				}

			}
			var EndDate = $('#EndDate').val().trim();
			if (EndDate == '') {
				validate = 1;
				$('#EndDate').addClass('has-error');
				if ($('#stxt_EndDate').size() == 0) {
					$('<span id="stxt_EndDate" class="help-block">Please select End Date</span>').insertAfter('#EndDate');
				}

			}
			var cm_id = $('#cm_id').val().trim();
			if (cm_id == "") {
				validate = 1;
				$('#cm_id').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_cm_id').size() == 0) {
					$('<span id="span_cm_id" class="help-block">Please select process</span>').insertAfter('#cm_id');
				}
			}
			//var Process=	$('#cm_id option:selected').attr('id');
			//$('#userProcess').val(Process);
			var Rate = $('#Rate').val().trim();
			if (Rate == "") {
				validate = 1;
				$('#Rate').addClass('has-error');
				if ($('#stxt_Rate').size() == 0) {
					$('<span id="stxt_Rate" class="help-block">Incentive Amount should not be empty</span>').insertAfter('#Rate');
				}
			}
			var criteria1 = $('#criteria1').val().trim();
			if (criteria1 == "") {
				validate = 1;
				$('#criteria1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_criteria1').size() == 0) {
					$('<span id="span_criteria1" class="help-block"> Please select ' + level1 + '</span>').insertAfter('#criteria1');
				}
			}
			var criteria2 = $('#criteria2').val().trim();
			if (criteria2 == "") {
				validate = 1;
				$('#criteria2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_criteria2').size() == 0) {
					$('<span id="span_criteria2" class="help-block">Please select ' + level2 + '</span>').insertAfter('#criteria2');
				}
			}
			var Rate2 = $('#Rate2').val().trim();
			var criteria12 = $('#criteria12').val().trim();
			var criteria22 = $('#criteria22').val().trim();
			if (!((Rate2 == "" && criteria12 == "" && criteria22 == "") || (Rate2 != "" && criteria12 != "" && criteria22 != ""))) {
				validate = 1;
				//alert_msg+='<li>Incentive Amount2, Present day and Absent day should not be empty</li>';
				if (Rate2 == "") {
					$('#Rate2').addClass('has-error');
					if ($('#stxt_Rate2').size() == 0) {
						$('<span id="stxt_Rate2" class="help-block">Incentive Amount should not be empty</span>').insertAfter('#Rate2');
					}
				}
				if (criteria12 == "") {
					$('#criteria12').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#span_criteria12').size() == 0) {
						$('<span id="span_criteria12" class="help-block"> Please select present day </span>').insertAfter('#criteria12');
					}
				}
				if (criteria22 == "") {
					$('#criteria22').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#span_criteria22').size() == 0) {
						$('<span id="span_criteria22" class="help-block"> Please select absent day </span>').insertAfter('#criteria22');
					}
				}

			} else {
				$('#span_criteria22').html('');
				$('#span_criteria12').html('');
				$('#stxt_Rate2').html('');
				$('#criteria22').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
				$('#criteria12').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
				$('#Rate2').removeClass('has-error');
			}
			var Rate3 = $('#Rate3').val().trim();
			var criteria13 = $('#criteria13').val().trim();
			var criteria23 = $('#criteria23').val().trim();
			if (!((Rate3 == "" && criteria13 == "" && criteria23 == "") || (Rate3 != "" && criteria13 != "" && criteria23 != ""))) {
				validate = 1;
				if (Rate3 == "") {
					$('#Rate3').addClass('has-error');
					if ($('#stxt_Rate3').size() == 0) {
						$('<span id="stxt_Rate3" class="help-block">Incentive Amount should not be empty</span>').insertAfter('#Rate3');
					}
				}
				if (criteria13 == "") {
					$('#criteria13').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#span_criteria13').size() == 0) {
						$('<span id="span_criteria13" class="help-block"> Please select present day </span>').insertAfter('#criteria13');
					}
				}
				if (criteria23 == "") {
					$('#criteria23').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if ($('#span_criteria23').size() == 0) {
						$('<span id="span_criteria23" class="help-block"> Please select absent day </span>').insertAfter('#criteria23');
					}
				}

			} else {
				$('#span_criteria23').html('');
				$('#span_criteria13').html('');
				$('#stxt_Rate3').html('');
				$('#criteria23').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
				$('#criteria13').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
				$('#Rate3').removeClass('has-error');
			}


			if (validate == 1) {
				if (alert_msg != "") {
					$(function() {
						toastr.error(alert_msg)
					});
				}
				return false;
			}
			return confirm('Are you want to proceed?');
		});



	});

	function checklistdata() {
		//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
		$('.statuscheck').removeClass('hidden');

	}

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>