<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Only for user type administrator
$user_type = clean($_SESSION['__user_type']);
if ($user_type != 'ADMINISTRATOR') {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}
// Global variable used in Page Cycle
$alert_msg = '';

// Trigger Button-Save Click Event and Perform DB Action
$btn_ref_Save = cleanUserInput(isset($_POST['btn_ref_Save']));
if ($btn_ref_Save) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_ref_Type = cleanUserInput($_POST['txt_ref_Type']);
		$_type = (isset($txt_ref_Type) ? $txt_ref_Type : null);
		$txt_ref_cp = cleanUserInput($_POST['txt_ref_cp']);
		$_cp = (isset($txt_ref_cp) ? $txt_ref_cp : null);
		$txt_ref_cn = cleanUserInput($_POST['txt_ref_cn']);
		$_cn = (isset($txt_ref_cn) ? $txt_ref_cn : null);
		$txt_ref_rn = cleanUserInput($_POST['txt_ref_rn']);
		$_rn = (isset($txt_ref_rn) ? $txt_ref_rn : null);
		$txt_ref_payout = cleanUserInput($_POST['txt_ref_payout']);
		$_paytm = (isset($txt_ref_payout) ? $txt_ref_payout : null);

		$createBy = clean($_SESSION['__user_logid']);
		$Insert = 'call add_ref_data("Consultancy","' . $_cp . '","' . $_cn . '","' . $_rn . '","' . $_paytm . '","' . $createBy . '")';
		$myDB = new MysqliDb();

		$myDB->rawQuery($Insert);
		$mysql_error = $myDB->getLastError();
		$rowCount = $myDB->count;
		if (empty($mysql_error)) {
			if ($rowCount > 0) {
				echo "<script>$(function(){ toastr.success('Added Successfully.'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Data already exists.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Not Added. $mysql_error'); }); </script>";
		}
	}
}
// Trigger Button-Edit Click Event and Perform DB Action
$btn_ref_Edit = cleanUserInput(isset($_POST['btn_ref_Edit']));
if ($btn_ref_Edit) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$DataID = cleanUserInput($_POST['hid_ref_ID']);
		$txt_ref_Type = cleanUserInput($_POST['txt_ref_Type']);
		$_type = (isset($txt_ref_Type) ? $txt_ref_Type : null);
		$txt_ref_cp = cleanUserInput($_POST['txt_ref_cp']);
		$_cp = (isset($txt_ref_cp) ? $txt_ref_cp : null);
		$txt_ref_cn = cleanUserInput($_POST['txt_ref_cn']);
		$_cn = (isset($txt_ref_cn) ? $txt_ref_cn : null);
		$txt_ref_rn = cleanUserInput($_POST['txt_ref_rn']);
		$_rn = (isset($txt_ref_rn) ? $txt_ref_rn : null);
		$txt_ref_payout = cleanUserInput($_POST['txt_ref_payout']);
		$_paytm = (isset($txt_ref_payout) ? $txt_ref_payout : null);

		$ModifiedBy = clean($_SESSION['__user_logid']);
		$Update = 'call save_ref_data("Consultancy","' . $_cp . '","' . $_cn . '","' . $_rn . '","' . $_paytm . '","' . $ModifiedBy . '","' . $DataID . '")';


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
				/* {
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
	<span id="PageTittle_span" class="hidden">Reference Master Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Reference Master Details <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Reference"><i class="material-icons">add</i></a></h4>

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
						<h4 class="col s12 m12 model-h4">Manage Client Master Details</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
							<div class="col s12 m12">

								<div class="input-field col s6 m6 hidden">
									<input type="text" class="form-control" readonly="true" value="Consultancy" id="txt_ref_Type" name="txt_ref_Type" />
									<label for="txt_ref_Type">Source Of Recruitment</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_ref_rn" name="txt_ref_rn" required />
									<label for="txt_ref_rn">Consultancy Name</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_ref_cp" name="txt_ref_cp" required />
									<label for="txt_ref_cp">Contact Person</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_ref_cn" name="txt_ref_cn" required />
									<label for="txt_ref_cn">Contact No</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_ref_payout" name="txt_ref_payout" required />
									<label for="txt_ref_payout">PayOut</label>
								</div>
							</div>
							<div class="input-field col s12 m12 right-align">
								<input type="hidden" class="form-control hidden" id="hid_ref_ID" name="hid_ref_ID" />
								<button type="submit" name="btn_ref_Save" id="btn_ref_Save" class="btn waves-effect waves-green ">Add</button>
								<button type="submit" name="btn_ref_Edit" id="btn_ref_Edit" class="btn waves-effect waves-green hidden">Save</button>
								<button type="button" name="btn_ref_Can" id="btn_ref_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>
						</div>
					</div>
				</div>
				<!--Form element model popup End-->

				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php

					$sqlConnect = 'call get_ref_data()';
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					if ($result) { ?>

						<div class="panel panel-default" style="margin-top: 10px;">
							<div class="panel-body">
								<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Sr No.</th>
											<th class="hidden">Ref. ID</th>
											<th>Type</th>
											<th>Contact Person</th>
											<th>Contact No.</th>
											<th>Ref. Name</th>
											<th>Payout</th>
											<th>Manage Ref.</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										foreach ($result as $key => $value) {
											$count++;
											echo '<tr>';
											echo '<td class="SrNO">' . $count . '</td>';
											echo '<td class="ref_id hidden">' . $value['ref_id'] . '</td>';
											echo '<td class="Type">' . $value['Type'] . '</td>';
											echo '<td class="ContactPerson">' . $value['ContactPerson'] . '</td>';
											echo '<td class="ContactNo">' . $value['ContactNo'] . '</td>';
											echo '<td class="RefName">' . $value['RefName'] . '</td>';
											echo '<td class="Payout">' . $value['Payout'] . '</td>';

											echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['ref_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';

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
		$('#btn_ref_Can').on('click', function() {
			$('#hid_ref_ID').val('');
			$('#txt_ref_Type').val('Consultancy');
			$('#txt_ref_cp').val('');
			$('#txt_ref_cn').val('');
			$('#txt_ref_rn').val('');
			$('#txt_ref_payout').val('');
			$('#btn_ref_Save').removeClass('hidden');
			$('#btn_ref_Edit').addClass('hidden');
			//$('#btn_ref_Can').addClass('hidden');

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
		$('#btn_ref_Edit,#btn_ref_Save').on('click', function() {
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

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			}

		});

	});

	// This code for trigger edit on all data table also trigger model open on a Model ID
	function EditData(el) {
		var tr = $(el).closest('tr');
		var ref_id = tr.find('.ref_id').text();
		var Type = tr.find('.Type').text();
		var ContactPerson = tr.find('.ContactPerson').text();
		var ContactNo = tr.find('.ContactNo').text();
		var RefName = tr.find('.RefName').text();
		var Payout = tr.find('.Payout').text();
		if (ref_id <= 4) {
			$('#alert_msg').html('<p class="text-danger">Not Updatable Item you selected ...</p>');
			$('#alert_message').show().attr("class", "SlideInRight animated");
			$('#alert_message').delay(5000).fadeOut("slow");
			return false;
		}

		$('#hid_ref_ID').val(ref_id);
		$('#txt_ref_Type').val(Type);
		$('#txt_ref_cp').val(ContactPerson);
		$('#txt_ref_cn').val(ContactNo);
		$('#txt_ref_rn').val(RefName);
		$('#txt_ref_payout').val(Payout);

		$('#btn_ref_Save').addClass('hidden');
		$('#btn_ref_Edit').removeClass('hidden');
		//$('#btn_ref_Can').removeClass('hidden');

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
	// function ApplicationDataDelete(el) {
	// 	var currentUrl = window.location.href;
	// 	var Cnfm = confirm("Do You Want To Delete This ");
	// 	if (Cnfm) {
	// 		var xmlhttp;
	// 		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
	// 			xmlhttp = new XMLHttpRequest();
	// 		} else { // code for IE6, IE5
	// 			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	// 		}
	// 		xmlhttp.onreadystatechange = function() {
	// 			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


	// 				var Resp = xmlhttp.responseText;
	// 				alert(Resp);
	// 				window.location.href = currentUrl;



	// 			}
	// 		}

	// 		xmlhttp.open("GET", "../Controller/deleteRef.php?ID=" + el.id, true);
	// 		xmlhttp.send();
	// 	}
	// }
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>