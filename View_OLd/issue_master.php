<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
// Only for user type administrator
if (clean($_SESSION['__user_type']) != 'ADMINISTRATOR') {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}
// Global variable used in Page Cycle
$alert_msg = '';
$btn_issue_save = isset($_POST['btn_Issue_Save']);
if (($btn_issue_save)) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_Issue_Name = cleanUserInput($_POST['txt_Issue_Name']);
		$txt_Issue_bt = cleanUserInput($_POST['txt_Issue_bt']);
		$txt_Issue_handler = cleanUserInput($_POST['txt_Issue_handler']);
		$txt_Issue_tat = cleanUserInput($_POST['txt_Issue_tat']);

		$_Name = (isset($txt_Issue_Name) ? $txt_Issue_Name : null);
		$_bt = (isset($txt_Issue_bt) ? $txt_Issue_bt : null);
		$_handler = (isset($txt_Issue_handler) ? $txt_Issue_handler : null);
		$_tat = (isset($txt_Issue_tat) ? $txt_Issue_tat : null);
		$createBy = clean($_SESSION['__user_logid']);
		$Insert = 'call add_issue("' . $_Name . '","' . $_bt . '","' . $_handler . '","' . $createBy . '","' . $_tat . '")';
		$myDB = new MysqliDb();

		$myDB->rawQuery($Insert);
		$mysql_error = $myDB->getLastError();
		$rowCount = $myDB->count;
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Issue saved Successfully.'); }); </script>";
		} else {
			echo "<script>$(function(){ toastr.success('Issue not added.'.$mysql_error) }); </script>";
		}
	}
}
$btn_issue_edit = isset($_POST['btn_Issue_Edit']);
if (($btn_issue_edit)) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_Issue_Name = cleanUserInput($_POST['txt_Issue_Name']);
		$txt_Issue_bt = cleanUserInput($_POST['txt_Issue_bt']);
		$txt_Issue_handler = cleanUserInput($_POST['txt_Issue_handler']);
		$txt_Issue_tat = cleanUserInput($_POST['txt_Issue_tat']);

		$DataID = cleanUserInput($_POST['hid_Issue_ID']);
		$_Name = (isset($txt_Issue_Name) ? $txt_Issue_Name : null);
		$_bt = (isset($txt_Issue_bt) ? $txt_Issue_bt : null);
		$_handler = (isset($txt_Issue_handler) ? $txt_Issue_handler : null);
		$_tat = (isset($txt_Issue_tat) ? $txt_Issue_tat : null);
		$ModifiedBy = clean($_SESSION['__user_logid']);
		$Update = 'call save_issue("' . $_Name . '","' . $_bt . '","' . $_handler . '","' . $ModifiedBy . '","' . $_tat . '","' . $DataID . '")';
		// die;


		$myDB = new MysqliDb();
		if (!empty($DataID) || $DataID != '') {
			$result = $myDB->rawQuery($Update);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Issue updated successfully.'); }); </script>";
				$_Comp = $_Hod = $_Name = '';
				$_Hod = "NA";
			} else {
				echo "<script>$(function(){ toastr.error('Issue not updated.$mysql_error') }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First.If Not Resolved then contact to technical person.'); }); </script>";
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Issue Master Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Issue Master Details <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Issue"><i class="material-icons">add</i></a></h4>

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
						<h4 class="col s12 m12 model-h4">Manage Issue</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
							<div class="col s12 m12">

								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_Issue_Name" name="txt_Issue_Name" required />
									<label for="txt_Issue_Name">Issue</label>
								</div>
								<div class="input-field col s6 m6">
									<select type="text" class="form-control" id="txt_Issue_bt" name="txt_Issue_bt" required>
										<option value="NA">---Select---</option>
										<option value="Human Resource">Human Resource</option>
										<option value="Information Technology">Information Technology</option>
										<option value="Operation">Operation</option>
										<option value="Administration">Administration</option>
										<?php

										/*$sqlBy = array(
													'table' => 'dept_master',
													'fields' => 'dept_id,dept_name',
													'condition' =>"1"); 
												$myDB=new MysqliDb();
												$resultBy=$myDB->select($sqlBy);
												if($resultBy){													
													$selec='';	
													foreach($resultBy as $key=>$value){
																											
														echo '<option value="'.$value['dept_master']['dept_id'].'" '.$selec.' >'.$value['dept_master']['dept_name'].'</option>';
													}
		
												}*/

										?>
									</select>
									<label for="txt_Issue_bt" class="active-drop-down active">Belongs To</label>
								</div>
							</div>
							<div class="col s12 m12">
								<div class="input-field col s6 m6">
									<select class="form-control" id="txt_Issue_handler" name="txt_Issue_handler" required>
										<option value="NA">---Select---</option>
										<option value="CE03070003">Sachin Siwach</option>
									</select>
									<label for="txt_Issue_handler" class="active-drop-down active">Handler To</label>
								</div>
								<div class="input-field col s6 m6">
									<select class="form-control" id="txt_Issue_tat" name="txt_Issue_tat" required>
										<option value="NA">---Select---</option>
										<option value='1'>1 Hour</option>
										<option value='2'>2 Hour</option>
										<option value='3'>3 Hour</option>
										<option value='4'>4 Hour</option>
										<option value='5'>5 Hour</option>
										<option value='6'>6 Hour</option>
										<option value='7'>7 Hour</option>
										<option value='8'>8 Hour</option>
										<option value='9'>9 Hour</option>
										<option value='10'>10 Hour</option>
										<option value='11'>11 Hour</option>
										<option value='12'>12 Hour</option>
										<option value='13'>13 Hour</option>
										<option value='14'>14 Hour</option>
										<option value='15'>15 Hour</option>
										<option value='16'>16 Hour</option>
										<option value='17'>17 Hour</option>
										<option value='18'>18 Hour</option>
										<option value='19'>19 Hour</option>
										<option value='20'>20 Hour</option>
										<option value='21'>21 Hour</option>
										<option value='22'>22 Hour</option>
										<option value='23'>23 Hour</option>
										<option value='24'>24 Hour</option>
										<option value='25'>25 Hour</option>
										<option value='26'>26 Hour</option>
										<option value='27'>27 Hour</option>
										<option value='28'>28 Hour</option>
										<option value='29'>29 Hour</option>
										<option value='30'>30 Hour</option>
										<option value='31'>31 Hour</option>
										<option value='32'>32 Hour</option>
										<option value='33'>33 Hour</option>
										<option value='34'>34 Hour</option>
										<option value='35'>35 Hour</option>
										<option value='36'>36 Hour</option>
										<option value='37'>37 Hour</option>
										<option value='38'>38 Hour</option>
										<option value='39'>39 Hour</option>
										<option value='40'>40 Hour</option>
										<option value='41'>41 Hour</option>
										<option value='42'>42 Hour</option>
										<option value='43'>43 Hour</option>
										<option value='44'>44 Hour</option>
										<option value='45'>45 Hour</option>
										<option value='46'>46 Hour</option>
										<option value='47'>47 Hour</option>
										<option value='48'>48 Hour</option>
										<option value='49'>49 Hour</option>
										<option value='50'>50 Hour</option>
										<option value='51'>51 Hour</option>
										<option value='52'>52 Hour</option>
										<option value='53'>53 Hour</option>
										<option value='54'>54 Hour</option>
										<option value='55'>55 Hour</option>
										<option value='56'>56 Hour</option>
										<option value='57'>57 Hour</option>
										<option value='58'>58 Hour</option>
										<option value='59'>59 Hour</option>
										<option value='60'>60 Hour</option>
										<option value='61'>61 Hour</option>
										<option value='62'>62 Hour</option>
										<option value='63'>63 Hour</option>
										<option value='64'>64 Hour</option>
										<option value='65'>65 Hour</option>
										<option value='66'>66 Hour</option>
										<option value='67'>67 Hour</option>
										<option value='68'>68 Hour</option>
										<option value='69'>69 Hour</option>
										<option value='70'>70 Hour</option>
										<option value='71'>71 Hour</option>
										<option value='72'>72 Hour</option>

									</select>
									<label for="txt_Issue_tat" class="active-drop-down active">TAT</label>
								</div>
							</div>
							<div class="input-field col s12 m12 right-align">
								<input type="hidden" class="form-control hidden" id="hid_Issue_ID" name="hid_Issue_ID" />
								<button type="submit" name="btn_Issue_Save" id="btn_Issue_Save" class="btn waves-effect waves-green">Add</button>
								<button type="submit" name="btn_Issue_Edit" id="btn_Issue_Edit" class="btn waves-effect waves-green hidden">Save</button>
								<button type="button" name="btn_Issue_Can" id="btn_Issue_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>
						</div>
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php
					$sqlConnect = 'call get_issue()';
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) { ?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Issue ID</th>
									<th>Issue</th>
									<th>Belongs TO</th>
									<th class="hidden">Hnadler</th>
									<th>Handler Name</th>
									<th>Tat</th>
									<th>Manage Issue</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="issue_id">' . $value['id'] . '</td>';
									echo '<td class="queary">' . $value['queary'] . '</td>';
									echo '<td class="bt">' . $value['bt'] . '</td>';
									echo '<td class="EmployeeName">' . $value['EmployeeName'] . '</td>';
									echo '<td class="handler hidden">' . $value['handler'] . '</td>';
									echo '<td class="tat">' . $value['tat'] . '</td>';
									echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['id'] . '" data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php
					}
					?>
				</div>
				<!--Reprot / Data Table End -->
			</div>
		</div>
		<!--Form container End -->
	</div>
	<!--Main Div for all Page End -->
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
		$('#btn_Issue_Can').on('click', function() {

			$('#txt_Issue_Name').val('');
			$('#hid_Issue_ID').val('');
			$('#txt_Issue_handler').val('NA');
			$('#txt_Issue_bt').val('NA');
			$('#txt_Issue_tat').val('NA');
			$('#btn_Issue_Save').removeClass('hidden');
			$('#btn_Issue_Edit').addClass('hidden');
			//$('#btn_Issue_Can').addClass('hidden');
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

		$('#btn_Issue_Edit,#btn_Issue_Save').on('click', function() {
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
	});



	// This code for trigger edit on all data table also trigger model open on a Model ID
	function EditData(el) {
		var tr = $(el).closest('tr');
		var issue_id = tr.find('.issue_id').text();
		var queary = tr.find('.queary').text();
		var bt = tr.find('.bt').text();
		var handler = tr.find('.handler').text();
		// alert(handler);
		var EmployeeName = tr.find('.EmployeeName').text();
		var tat = tr.find('.tat').text();
		$('#hid_Issue_ID').val(issue_id);
		$('#txt_Issue_Name').val(queary);
		$('#txt_Issue_bt').val(bt);
		$('#txt_Issue_handler').empty().append('<option value="NA">---Select---</option><option value="' + handler + '" selected="true">' + EmployeeName + '</option>');
		$('#txt_Issue_tat').val(tat);
		$('#btn_Issue_Save').addClass('hidden');
		$('#btn_Issue_Edit').removeClass('hidden');
		//$('#btn_Issue_Can').removeClass('hidden');

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

	// 		xmlhttp.open("GET", "../Controller/DeleteIssue.php?ID=" + el.id, true);
	// 		xmlhttp.send();
	// 	}
	// }
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>