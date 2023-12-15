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
/*if($_SESSION['__user_type']!='ADMINISTRATOR')
{
	$location= URL.'Error'; 
	header("Location: $location");
	exit();
}*/
// Global variable used in Page Cycle
$alert_msg = '';

// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_add'])) {
	$txt_cname = (isset($_POST['txt_name']) ? $_POST['txt_name'] : null);
	$_cp = (isset($_POST['txt_ref_cp']) ? $_POST['txt_ref_cp'] : null);
	$_cn = (isset($_POST['txt_ref_cn']) ? $_POST['txt_ref_cn'] : null);
	$txt_refName = (isset($_POST['txt_refName']) ? $_POST['txt_refName'] : null);
	$txt_email = (isset($_POST['txt_email']) ? $_POST['txt_email'] : null);

	$createBy = $_SESSION['__user_logid'];
	//$Insert = 'call add_consultancy("' . $txt_cname . '","' . $_cp . '","' . $_cn . '","' . $txt_refName . '","' . $createBy . '","' . $txt_email . '")';
	$Insert = 'insert into consultancy_master set ConsultancyName="' . $txt_cname . '", ContactPerson="' . $_cp . '", ContactNo="' . $_cn . '", RefName="' . $txt_refName . '", CreatedBy="' . $createBy . '", EmailID = "' . $txt_email . '"';
	$myDB = new MysqliDb();

	$res = $myDB->rawQuery($Insert);
	$insertId = $myDB->getInsertId();
	// echo ($insertId);
	// die;
	$mysql_error = $myDB->getLastError();
	$rowCount = $myDB->count;

	if (empty($mysql_error)) {
		if ($rowCount > 0) {
			echo "<script>$(function(){ toastr.success('Added Successfully.'); }); </script>";
			$txt_cname = str_replace(" ", "%20", $txt_cname);
			$int_url = URL . "QrSetup/GenerateQRCodeAPI.php?empId=" . $insertId . "&location=Consultancy&qrtype=2";

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $int_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			$data = curl_exec($curl);
			$resp = json_decode($data);
		} else {
			echo "<script>$(function(){ toastr.error('Data already exists.'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Data already exists.'); }); </script>";
	}
}
// Trigger Button-Edit Click Event and Perform DB Action
if (isset($_POST['btn_save'])) {
	$DataID = $_POST['hid_ID'];

	$txt_cname = (isset($_POST['txt_name']) ? $_POST['txt_name'] : null);
	$_cp = (isset($_POST['txt_ref_cp']) ? $_POST['txt_ref_cp'] : null);
	$_cn = (isset($_POST['txt_ref_cn']) ? $_POST['txt_ref_cn'] : null);
	$txt_refName = (isset($_POST['txt_refName']) ? $_POST['txt_refName'] : null);
	$txt_email = (isset($_POST['txt_email']) ? $_POST['txt_email'] : null);

	$ModifiedBy = $_SESSION['__user_logid'];
	$Update = 'call save_consultancy("' . $txt_cname . '","' . $_cp . '","' . $_cn . '","' . $txt_refName . '","' . $ModifiedBy . '","' . $DataID . '","' . $txt_email . '")';


	$myDB = new MysqliDb();
	if (!empty($DataID) || $DataID != '') {
		$result = $myDB->rawQuery($Update);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Updated Successfully.'); }); </script>";
			$_Comp = $_Hod = $_Name = '';
			$_Hod = "NA";
		} else {
			echo "<script>$(function(){ toastr.success('Not updated. $mysql_error'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.success('Something is wrong Plase click to Edit Button First.If Not Resolved then contact to technical person.'); }); </script>";
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
	<span id="PageTittle_span" class="hidden">Consultancy Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Consultancy Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Reference"><i class="material-icons">add</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Manage Consultancy Master Details</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
							<div class="col s12 m12">
								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_name" name="txt_name" maxlength="100" required />
									<label for="txt_name">Consultancy Name</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_ref_cp" name="txt_ref_cp" maxlength="50" required />
									<label for="txt_ref_cp">Contact Person</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_ref_cn" name="txt_ref_cn" maxlength="10" required />
									<label for="txt_ref_cn">Contact No</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_email" name="txt_email" required />
									<label for="txt_ref_cn">EMail ID</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_refName" name="txt_refName" maxlength="50" required />
									<label for="txt_refName">Referenced By</label>
								</div>
								<div class="input-field col s6 m6" id="divqr">
									<a download target="_blank" id="ahrefqr"></a>
								</div>
							</div>
							<div class="input-field col s12 m12 right-align">
								<input type="hidden" class="form-control hidden" id="hid_ID" name="hid_ID" />
								<button type="submit" name="btn_add" id="btn_add" class="btn waves-effect waves-green ">Add</button>
								<button type="submit" name="btn_save" id="btn_save" class="btn waves-effect waves-green hidden">Save</button>
								<button type="button" name="btn_ref_Can" id="btn_ref_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>
						</div>
					</div>
				</div>
				<!--Form element model popup End-->

				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php

					$sqlConnect = "select id, ConsultancyName, ContactPerson, ContactNo, RefName, EmailID from consultancy_master order by ConsultancyName desc";
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					if ($result) { ?>

						<div class="panel panel-default" style="margin-top: 10px;">
							<div class="panel-body">
								<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
									<thead>
										<tr>

											<th>ConsultancyName</th>
											<th>Contact Person</th>
											<th>Contact No.</th>
											<th>Ref. Name</th>
											<th>EMail ID</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										foreach ($result as $key => $value) {
											$count++;
											echo '<tr>';
											echo '<td class="ConsultancyName">' . $value['ConsultancyName'] . '</td>';
											echo '<td class="ContactPerson">' . $value['ContactPerson'] . '</td>';
											echo '<td class="ContactNo">' . $value['ContactNo'] . '</td>';
											echo '<td class="RefName">' . $value['RefName'] . '</td>';
											echo '<td class="EmailID">' . $value['EmailID'] . '</td>';

											echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';

											/*<td><img alt="Edit" class="imgBtn imgBtnEdit" onclick="javascript:return EditData(this);" src="../Style/images/users_edit.png" id="'.$value['ref_master']['ref_id'].'" /> <img alt="Delete" class="imgBtn" src="../Style/images/users_delete.png" id="'.$value['ref_master']['ref_id'].'" onclick="javascirpt:return ApplicationDataDelete(this);"/> </td>*/






											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					<?php
					}
					?>
				</div>
				<!--Reprot / Data Table End -->
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#divqr').hide();
		//Model Assigned and initiation code on document load	
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_ref_Can').trigger("click");
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
		$('#btn_ref_Can').on('click', function() {
			$('#hid_ID').val('');
			$('#txt_ref_Type').val('Consultancy');
			$('#txt_ref_cp').val('');
			$('#txt_ref_cn').val('');
			$('#txt_name').val('');
			$('#txt_refName').val('');
			$('#btn_add').removeClass('hidden');
			$('#btn_save').addClass('hidden');
			//$('#btn_ref_Can').addClass('hidden');
			$('#divqr').hide();
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

		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.   
		$('#btn_save,#btn_add').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			$('#spantxt_email').val('');
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
					if ($('#' + spanID).size() == 0) {
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
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if (!emailReg.test($('#txt_email').val())) {
				$('#txt_email').css('border-color', 'red');
				if ($('#spantxt_email').size() == 0) {
					$('<span id="spantxt_email" class="help-block">InValid E-Mail ID</span>').insertAfter('#txt_email');
				}
				return false;
			}

			if (validate == 1) {
				return false;
			}

		});
		$('#txt_ref_cn').keydown(function(event) {
			if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||

				// Allow: Ctrl+A
				(event.keyCode == 65 && event.ctrlKey === true) ||

				// Allow: Ctrl+V
				(event.ctrlKey == true && (event.which == '118' || event.which == '86')) ||

				// Allow: Ctrl+c
				(event.ctrlKey == true && (event.which == '99' || event.which == '67')) ||

				// Allow: Ctrl+x
				(event.ctrlKey == true && (event.which == '120' || event.which == '88')) ||

				// Allow: home, end, left, right
				(event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
			} else {
				// Ensure that it is a number and stop the keypress
				if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
					event.preventDefault();
				}
			}
		});

	});

	// This code for trigger edit on all data table also trigger model open on a Model ID
	function EditData(el) {
		var tr = $(el).closest('tr');
		var id = tr.find('.edit_item').attr('id');
		var ConsultancyName = tr.find('.ConsultancyName').text();
		var ContactPerson = tr.find('.ContactPerson').text();
		var ContactNo = tr.find('.ContactNo').text();
		var RefName = tr.find('.RefName').text();
		var EmailID = tr.find('.EmailID').text();

		$('#hid_ID').val(id);
		$('#divqr').show();
		$('#txt_name').val(ConsultancyName);
		$('#txt_ref_cp').val(ContactPerson);
		$('#txt_ref_cn').val(ContactNo);
		$('#txt_refName').val(RefName);
		$('#txt_email').val(EmailID);
		$('#btn_add').addClass('hidden');
		$('#btn_save').removeClass('hidden');
		//$('#btn_ref_Can').removeClass('hidden');

		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/getQRID.php?id=" + id
		}).done(function(data) { // data what is sent back by the php page
			if (data != 'No') {

				$("#ahrefqr").attr("href", "../QrSetup/Consultancy/" + data);
				$("#ahrefqr").text('QR Download');
			} else {
				$("#ahrefqr").attr("href", "#");
				$("#ahrefqr").text('No File Exist');
			}
			$('select').formSelect();
		});

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

			xmlhttp.open("GET", "../Controller/deleteRef.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>