<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Only for user type administrator
if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE01145570') {
	// proceed further
} else {
	$location = URL;
	echo "<script>location.href='" . $location . "'</script>";
}
/*
ALTER TABLE `new_client_master` 
ADD COLUMN `VH` VARCHAR(45) NULL DEFAULT NULL AFTER `days_of_rotation`;

*/
// Global variable used in Page Cycle

?>

<script>
	//contain load event for data table and other importent rand required trigger event and searches if any
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
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
	<span id="PageTittle_span" class="hidden">Client Master Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Client Master Details</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php
					$sqlConnect = 'call select_client()';
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) {
					?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th class="hidden">AH ID </th>
									<th class="hidden">VH ID </th>
									<th>A/C Head</th>
									<th class="hidden">Dept ID</th>
									<th>Dept Name</th>
									<th>Process</th>
									<th class="hidden">OH</th>
									<th>OH Name</th>
									<th class="hidden">QH</th>
									<th>QH Name</th>
									<th class="hidden">TH</th>
									<th>TH Name</th>
									<th class="hidden">ER scop</th>
									<th>Sub Process</th>
									<th>Location</th>

									<th class="hidden">HRID</th>
									<th class="hidden">ITID</th>
									<th class="hidden">ReportsTo</th>
									<th class="hidden">Exception</th>
									<th class="hidden">DT ID</th>
									<th class="hidden">Stipen</th>
									<th class="hidden">Stipen days</th>
									<th class="hidden">FromJoiningd</th>
									<th class="hidden">FromFloord</th>
									<th class="hidden">Rotation Days</th>
									<th class="hidden">locid</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="cm_id">' . $value['cm_id'] . '</td>';
									echo '<td class="client_name">' . $value['cli'] . '</td>';
									echo '<td class="account_head hidden">' . $value['account_head'] . '</td>';
									echo '<td class="VH hidden">' . $value['VH'] . '</td>';
									echo '<td class="EmployeeName">' . $value['ach'] . '</td>';
									echo '<td class="dept_id hidden">' . $value['dept_id'] . '</td>';
									echo '<td class="dept_name">' . $value['dept_name'] . '</td>';
									echo '<td class="process">' . $value['process'] . '</td>';
									echo '<td class="oh hidden">' . $value['oh'] . '</td>';
									echo '<td class="ohn">' . $value['ohn'] . '</td>';
									echo '<td class="qh hidden">' . $value['qh'] . '</td>';
									echo '<td class="qhn">' . $value['qhn'] . '</td>';
									echo '<td class="th hidden">' . $value['th'] . '</td>';
									echo '<td class="thn">' . $value['thn'] . '</td>';
									echo '<td class="er_scop hidden ">' . $value['er_scop'] . '</td>';
									echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
									echo '<td class="location">' . $value['loc_name'] . '</td>';



									echo '<td class="HRID hidden">' . $value['HRID'] . '</td>';
									echo '<td class="ITID hidden">' . $value['ITID'] . '</td>';
									echo '<td class="ReportsTo hidden">' . $value['ReportsTo'] . '</td>';
									echo '<td class="Exception hidden">' . $value['excep_spoc'] . '</td>';
									echo '<td class="dtid hidden">' . $value['ID'] . '</td>';
									echo '<td class="Stipend hidden">' . $value['Stipend'] . '</td>';
									echo '<td class="StipendDays hidden">' . $value['StipendDays'] . '</td>';
									echo '<td class="dtfromjoin hidden">' . $value['days_from_joining'] . '</td>';
									echo '<td class="dtfromfloor hidden">' . $value['days_from_floor'] . '</td>';
									echo '<td class="dtrotation hidden">' . $value['days_of_rotation'] . '</td>';
									echo '<td class="locid hidden">' . $value['location'] . '</td>';
								?>

								<?php
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php } ?>
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
				$('#btn_Client_Can').trigger("click");
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
		$('#btn_Client_Can').on('click', function() {
			$('#txt_Client_Name').val('');
			$('#hid_Client_ID').val('');
			$('#txt_Client_ach').val('NA');
			$('#txt_exception_approver').val('NA');
			$('#txt_Client_dept').val('NA');
			$('#txt_Client_proc').val('');
			$('#txt_Client_oh').val('NA');
			$('#txt_Client_qh').val('NA');
			$('#txt_Client_th').val('NA');

			$('#txt_location').val('NA');

			$('#txt_Client_th').val('');
			$('#txt_Client_subproc').val('');
			$('#btn_Client_Save').removeClass('hidden');
			$('#btn_Client_Edit').addClass('hidden');
			//$('#btn_Client_Can').addClass('hidden');
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

		// This code for submit button and form submit for all model field validation if this contain a required attributes also has some manual code validation to if needed.

		$('#btn_Client_Edit,#btn_Client_Save').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			if ($('#txt_location').val() == 'NA') {
				$('#txt_location').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_location').size() == 0) {
					$('<span id="spantxt_location" class="help-block">Required *</span>').insertAfter('#txt_location');
				}
				validate = 1;
			}
			if ($('#txt_Client_Name').val() == '') {
				$('#txt_Client_Name').addClass("has-error");
				if ($('#spantxt_Client_Name').size() == 0) {
					$('<span id="spantxt_Client_Name" class="help-block">Required *</span>').insertAfter('#txt_Client_Name');
				}
				validate = 1;
			}
			if ($('#txt_Client_ach').val() == 'NA') {
				$('#txt_Client_ach').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_ach').size() == 0) {
					$('<span id="spantxt_Client_ach" class="help-block">Required *</span>').insertAfter('#txt_Client_ach');
				}
				validate = 1;
			}
			if ($('#txt_vertical_head').val() == 'NA') {
				$('#txt_vertical_head').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_vertical_head').size() == 0) {
					$('<span id="spantxt_vertical_head" class="help-block">Required *</span>').insertAfter('#txt_vertical_head');
				}
				validate = 1;
			}
			if ($('#txt_Client_dept').val() == 'NA') {
				$('#txt_Client_dept').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_dept').size() == 0) {
					$('<span id="spantxt_Client_dept" class="help-block">Required *</span>').insertAfter('#txt_Client_dept');
				}
				validate = 1;
			}
			if ($('#txt_Client_proc').val() == '') {
				$('#txt_Client_proc').addClass("has-error");
				if ($('#spantxt_Client_proc').size() == 0) {
					$('<span id="spantxt_Client_proc" class="help-block">Required *</span>').insertAfter('#txt_Client_proc');
				}
				validate = 1;
			}
			if ($('#txt_Client_oh').val() == 'NA') {
				$('#txt_Client_oh').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_oh').size() == 0) {
					$('<span id="spantxt_Client_oh" class="help-block">Required *</span>').insertAfter('#txt_Client_oh');
				}
				validate = 1;
			}
			if ($('#txt_Client_qh').val() == 'NA') {
				$('#txt_Client_qh').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_qh').size() == 0) {
					$('<span id="spantxt_Client_qh" class="help-block">Required *</span>').insertAfter('#txt_Client_qh');
				}
				validate = 1;
			}
			if ($('#txt_Client_th').val() == 'NA') {
				$('#txt_Client_th').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_th').size() == 0) {
					$('<span id="spantxt_Client_th" class="help-block">Required *</span>').insertAfter('#txt_Client_th');
				}
				validate = 1;
			}
			if ($('#txt_ERSPOC').val() == 'NA') {
				$('#txt_ERSPOC').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_ERSPOC').size() == 0) {
					$('<span id="spantxt_ERSPOC" class="help-block">Required *</span>').insertAfter('#txt_ERSPOC');
				}
				validate = 1;
			}
			if ($('#txt_Client_subproc').val() == '') {
				$('#txt_Client_subproc').addClass("has-error");
				if ($('#spantxt_Client_subproc').size() == 0) {
					$('<span id="spantxt_Client_subproc" class="help-block">Required *</span>').insertAfter('#txt_Client_subproc');
				}
				validate = 1;
			}
			if ($('#txt_ITID').val() == '') {
				$('#txt_ITID').addClass("has-error");
				if ($('#spantxt_ITID').size() == 0) {
					$('<span id="spantxt_ITID" class="help-block">Required *</span>').insertAfter('#txt_ITID');
				}
				validate = 1;
			}
			if ($('#txt_HRID').val() == '') {
				$('#txt_HRID').addClass("has-error");
				if ($('#spantxt_HRID').size() == 0) {
					$('<span id="spantxt_HRID" class="help-block">Required *</span>').insertAfter('#txt_HRID');
				}
				validate = 1;
			}
			if ($('#txt_ReportsTo').val() == '') {
				$('#txt_ReportsTo').addClass("has-error");
				if ($('#spantxt_ReportsTo').size() == 0) {
					$('<span id="spantxt_ReportsTo" class="help-block">Required *</span>').insertAfter('#txt_ReportsTo');
				}
				validate = 1;
			}
			if ($('#txt_exception_approver').val() == 'NA') {
				$('#txt_exception_approver').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_exception_approver').size() == 0) {
					$('<span id="spantxt_exception_approver" class="help-block">Required *</span>').insertAfter('#txt_exception_approver');
				}
				validate = 1;
			}
			if ($('#txt_Stipen').val() == '') {
				$('#txt_Stipen').addClass("has-error");
				if ($('#spantxt_Stipen').size() == 0) {
					$('<span id="spantxt_Stipen" class="help-block">Required *</span>').insertAfter('#txt_Stipen');
				}
				validate = 1;
			}
			if ($('#txt_StipendDays').val() == '') {
				$('#txt_StipendDays').addClass("has-error");
				if ($('#spantxt_StipendDays').size() == 0) {
					$('<span id="spantxt_StipendDays" class="help-block">Required *</span>').insertAfter('#txt_StipendDays');
				}
				validate = 1;
			}
			//alert($("#txt_exception_approver option:selected").text());	
			$('#hid_Excp_ID').val($("#txt_exception_approver option:selected").text());
			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");*/
				alert_msg = 'Please fill all required field';

				$(function() {
					toastr.error(alert_msg);
				});
				//alert('1');
				return false;
			}
			//return false;
		});


	});


	// This code for trigger edit on all data table also trigger model open on a Model ID

	function EditData(el) {
		var tr = $(el).closest('tr');
		var client_id = tr.find('.cm_id').text();
		var client_name = tr.find('.client_name').text();
		var account_head = tr.find('.account_head').text();
		var dept_id = tr.find('.dept_id').text();
		var process = tr.find('.process').text();
		var oh = tr.find('.oh').text();
		var qh = tr.find('.qh').text();
		var th = tr.find('.th').text();
		var VH = tr.find('.VH').text();
		var dtid = tr.find('.dtid').text();
		var er_scop = tr.find('.er_scop').text();
		var sub_process = tr.find('.sub_process').text();
		var HRID = tr.find('.HRID').text();
		var ITID = tr.find('.ITID').text();
		var ReportsTo = tr.find('.ReportsTo').text();
		var Exception = tr.find('.Exception').text();

		var Stipend = tr.find('.Stipend').text();
		var StipendDays = tr.find('.StipendDays').text();
		var dtrotation = tr.find('.dtrotation').text();
		var dtfromfloor = tr.find('.dtfromfloor').text();
		var dtfromjoin = tr.find('.dtfromjoin').text();
		var location = $.trim(tr.find('.location').text());
		var locid = $.trim(tr.find('.locid').text());
		$('#txt_location').val(locid);
		//alert(location);alert(locid);
		//$("#txt_location option:contains(" + location + ")").attr('selected', 'selected');	

		/*$("#txt_location option").filter(function() {
    return this.text == location; 
}).attr('selected', true);*/

		getProcess(locid, account_head, VH, oh, qh, th, er_scop, Exception);

		//alert(account_head);
		$('#from_joiningdate').val(dtfromjoin);
		$('#from_floordate').val(dtfromfloor);
		$('#rotation_date').val(dtrotation);
		$('#hid_Client_ID').val(client_id);
		$('#txt_Client_Name').val(client_name);
		$('#txt_Client_ach').val(account_head);
		$('#txt_exception_approver').val(Exception);
		//alert(Exception);
		$('#txt_Client_dept').val(dept_id);
		$('#txt_Client_proc').val(process);
		$('#txt_Client_oh').val(oh);


		//$('#txt_location').val(location);	
		$('#txt_Client_qh').val(qh);
		$('#txt_Client_th').val(th);
		$('#txt_ERSPOC').val(er_scop);
		$('#txt_vertical_head').val(VH);
		$('#txt_HRID').val(HRID);
		$('#txt_ITID').val(ITID);
		$('#h_dtid').val(dtid);
		$('#txt_ReportsTo').val(ReportsTo);
		$('#txt_Stipen').val(Stipend);
		$('#txt_StipendDays').val(StipendDays);
		$('#txt_Client_subproc').val(sub_process);
		$('#btn_Client_Save').addClass('hidden');
		$('#btn_Client_Edit').removeClass('hidden');
		//$('#btn_Client_Can').removeClass('hidden');


		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}

	// This code for trigger edit on Sub Proc data table also trigger model open on a Model ID

	function AddSubProc(el) {
		var tr = $(el).closest('tr');
		var client_id = tr.find('.cm_id').text();
		var client_name = tr.find('.client_name').text();
		var account_head = tr.find('.account_head').text();
		var dept_id = tr.find('.dept_id').text();
		var process = tr.find('.process').text();
		var oh = tr.find('.oh').text();
		var qh = tr.find('.qh').text();
		var th = tr.find('.th').text();
		var location = tr.find('.location').text();

		$('#hid_Client_ID').val(client_id);
		$('#txt_Client_Name').val(client_name);
		$('#txt_Client_ach').val(account_head);
		$('#txt_Client_dept').val(dept_id);
		$('#txt_Client_proc').val(process);
		$('#txt_location').val(location);
		$('#txt_Client_oh').val(oh);
		$('#txt_Client_qh').val(qh);
		$('#txt_Client_th').val(th);
		$('#txt_Client_subproc').val('');
		$('#btn_Client_Save').removeClass('hidden');
		$('#btn_Client_Edit').addClass('hidden');
		//$('#btn_Client_Can').removeClass('hidden');
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}

	// This code for trigger edit on Proc data table also trigger model open on a Model ID

	function AddProc(el) {
		var tr = $(el).closest('tr');
		var client_id = tr.find('.cm_id').text();
		var client_name = tr.find('.client_name').text();
		var account_head = tr.find('.account_head').text();
		var dept_id = tr.find('.dept_id').text();
		var location = tr.find('.location').text();
		$('#hid_Client_ID').val(client_id);
		$('#txt_Client_Name').val(client_name);
		$('#txt_Client_ach').val(account_head);
		$('#txt_Client_dept').val(dept_id);
		$('#txt_location').val(location);
		$('#txt_Client_proc').val('');
		$('#txt_Client_oh').val('NA');
		$('#txt_Client_qh').val('NA');
		$('#txt_Client_th').val('NA');
		$('#txt_Client_subproc').val('');
		$('#btn_Client_Save').removeClass('hidden');
		$('#btn_Client_Edit').addClass('hidden');
		//$('#btn_Client_Can').removeClass('hidden');
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}

	// This code for trigger del*t*

	function ApplicationDataDelete(el, dtid) {
		////alert(el);
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
					//alert(Resp);
					window.location.href = currentUrl;
				}
			}
			xmlhttp.open("GET", "../Controller/DeleteClient.php?ID=" + el.id + "&dttid" + dtid, true);
			xmlhttp.send();
		}
	}

	function getProcess(el, ach, VH, oh, qh, th, er_scop, Exception) {

		var currentUrl = window.location.href;

		var xmlhttp;
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


				var Resp = xmlhttp.responseText;
				$('#txt_Client_ach').html(Resp);
				//$('#txt_vertical_head').html(Resp);
				/*$('#txt_Client_oh').html(Resp);
				$('#txt_Client_qh').html(Resp);
				$('#txt_Client_th').html(Resp);*/
				$('select').formSelect();
			}

		}

		//var location = <?php echo $_SESSION["__location"] ?>;
		//alert(el);
		//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
		xmlhttp.open("GET", "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=ah&val=" + ach, true);
		xmlhttp.send();

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=excep&val=" + Exception,
			success: function(result) {
				//alert(result);
				$("#txt_exception_approver").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=vh&val=" + VH,
			success: function(result) {
				//alert(result);
				$("#txt_vertical_head").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=ah&val=" + oh,
			success: function(result) {
				//alert(result);
				$("#txt_Client_oh").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=ah&val=" + qh,
			success: function(result) {
				//alert(result);
				$("#txt_Client_qh").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});

		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=ah&val=" + th,
			success: function(result) {
				//alert(result);
				$("#txt_Client_th").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});


		$.ajax({
			url: "../Controller/getalignmentByLocation.php?loc=" + $('#txt_location').val() + "&type=hr&val=" + er_scop,
			success: function(result) {
				//alert(result);
				$("#txt_ERSPOC").html(result);
				// binddate1(result.trim());
				$('select').formSelect();
			}
		});



	}



	function isNumber(evt) {
		var iKeyCode = (evt.which) ? evt.which : evt.keyCode
		if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
			return false;
		return true;
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>