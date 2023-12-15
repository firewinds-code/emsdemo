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
// Global variable used in Page Cycle
$alert_msg = '';
// Trigger Button-Save Click Event and Perform DB Action
$btn_AprMaster_Save = isset($_POST['btn_AprMaster_Save']);
if ($btn_AprMaster_Save) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_Client = cleanUserInput($_POST['txt_Client']);
		$txt_EmpID = cleanUserInput($_POST['txt_EmpID']);
		$_cmid = (isset($txt_Client) ? $txt_Client : null);
		$_EmpID = (isset($txt_EmpID) ? $txt_EmpID : null);

		$EmpID = substr($_EmpID, strpos($_EmpID, "(") + 1, (strpos($_EmpID, ")")) - (strpos($_EmpID, "(") + 1));

		$createBy = clean($_SESSION['__user_logid']);
		$Insert = 'call sp_insert_MasterApr("' . $_cmid . '","' . $EmpID . '","' . $createBy . '")';
		$myDB = new MysqliDb();
		$myDB->rawQuery($Insert);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {

			if ($myDB->count > 0) {
				echo "<script>$(function(){ toastr.success('Appraisal Master Matrix Added Successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Appraisal Master Matrix Not Added, May be Duplicate Entry Found check manualy'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Appraisal Master Matrix not Added. Some error occured " . $mysql_error . "'); }); </script>";
		}
	}
}
// Trigger Button-Edit Click Event and Perform DB Action
$btn_AprMaster_Edit = isset($_POST['btn_AprMaster_Edit']);
if ($btn_AprMaster_Edit) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$DataID = cleanUserInput($_POST['hid_ID']);
		$txt_Client = cleanUserInput($_POST['txt_Client']);
		$txt_EmpID = cleanUserInput($_POST['txt_EmpID']);
		$_cm_id = (isset($txt_Client) ? $txt_Client : null);
		$_EmpID = (isset($txt_EmpID) ? $txt_EmpID : null);
		$ModifiedBy = clean($_SESSION['__user_logid']);
		if (strpos($_EmpID, '(')) {
			$EmpID = substr($_EmpID, strpos($_EmpID, "(") + 1, (strpos($_EmpID, ")")) - (strpos($_EmpID, "(") + 1));
		} else {
			$EmpID = $_EmpID;
		}
		$Update = 'call sp_Update_MasterAppraisal("' . $DataID . '","' . $_cm_id . '","' . $EmpID . '","' . $ModifiedBy . '")';
		$myDB = new MysqliDb();
		if (!empty($DataID) || $DataID != '') {
			$myDB->rawQuery($Update);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				if ($myDB->count > 0) {
					echo "<script>$(function(){ toastr.success('Appraisal Master Matrix Updated Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Appraisal Master Matrix Not Updated, May be Duplicate Entry Found check manualy'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.success('Appraisal Master Matrix Not Updated. Some error occurred'); }); </script>";

				//echo "<script>$(function(){ toastr.error('Master Reference Scheme Not Updated: Some error occurred...); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First. If Not Resolved then contact to technical person'); }); </script>";
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
				,
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
	.error {
		color: red;
	}

	#data-container {
		display: block;
		background: #2a3f54;

		max-height: 250px;
		overflow-y: auto;
		z-index: 9999999;
		position: absolute;
		width: 100%;

	}

	#data-container li {
		list-style: none;
		padding: 5px;
		border-bottom: 1px solid #fff;
		color: #fff;
	}

	#data-container li:hover {
		background: #26b99a;
		cursor: pointer;
	}

	.form-control:focus {
		border-color: #d01010;
		outline: 0;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(233, 102, 139, 0.6);

	}

	#overlay {
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height: 100%;
		display: none;
		background: rgba(0, 0, 0, 0.6);
	}

	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}

	@keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}

	.is-hide {
		display: none;
	}
</style>

<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Appraisal Master</span>

	<div class="pim-container row" id="div_main">
		<div class="form-div">
			<h4>Appraisal Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Department"><i class="material-icons">add</i></a></h4>
			<div class="schema-form-section row">
				<?php

				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div id="myModal_content" class="modal">

					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Manage Appraisal Master</h4>
						<div class="modal-body" style="max-height: 650px;float:left;overflow: auto;">
							<div class="col s12 m12">

								<div class="input-field col s4 m4">
									<select id="txt_location" name="txt_location" required onchange="javascript:return getProcess(this);">
										<option value="NA">----Select----</option>
										<?php
										$sqlBy = 'select id,location from location_master;';
										$myDB = new MysqliDb();
										$resultBy = $myDB->rawQuery($sqlBy);
										$mysql_error = $myDB->getLastError();
										if (empty($mysql_error)) {
											foreach ($resultBy as $key => $value) {
												echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
											}
										}
										?>
									</select>
									<label for="txt_location" class="active-drop-down active">Location</label>
								</div>

								<div class="input-field col s8 m8">
									<select id="txt_Client" name="txt_Client" required>
										<!--<option value="NA">----Select----</option>	
						    <?php
							$sqlBy = 'select distinct concat(t2.client_name,"|",t1.process,"|",t1.sub_process) as Process,t1.cm_id from new_client_master t1 join client_master t2 on t1.client_name = t2.client_id order by process';
							$myDB = new MysqliDb();
							$resultBy = $myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if (empty($mysql_error)) {
								foreach ($resultBy as $key => $value) {
									echo '<option value="' . $value['cm_id'] . '"  >' . $value['Process'] . '</option>';
								}
							}
							?>-->
									</select>
									<label for="txt_Client" class="active-drop-down active">Process</label>
								</div>

								<div class="input-field col s8 m8">
									<input type="text" id="txt_EmpID" name="txt_EmpID" required />
									<label for="txt_EmpID">Recomender Employee ID</label>
									<div id="data-container"></div>

								</div>

								<div id="bom_list" class="col-md-8 col-sm-8 col-xs-12">

								</div>

							</div>

							<div class="col s12 m12" style="height: 80px;">

							</div>

							<div class="input-field col s12 m12 right-align">

								<input type="hidden" class="form-control hidden" id="hid_ID" name="hid_ID" />
								<button type="submit" name="btn_AprMaster_Save" id="btn_AprMaster_Save" class="btn waves-effect waves-green">Add</button>
								<button type="submit" name="btn_AprMaster_Edit" id="btn_AprMaster_Edit" class="btn waves-effect waves-green hidden">Save</button>
								<button type="button" name="btn_AprMaster_Can" id="btn_AprMaster_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>

							</div>
						</div>
					</div>
				</div>

				<div id="pnlTable">
					<?php
					//$sqlConnect = array('table' => 'dept_master','fields' => 'dept_id,dept_name','condition' =>"1"); 
					$sqlConnect = "select t2.id,t2.cm_id,t1.location,t1.process,t1.sub_process,t2.EmpID,t2.createdby,t2.createdon,t3.location as loc from new_client_master t1 join apraisal_matrix t2 on t1.cm_id= t2.cm_id join location_master t3 on t1.location=t3.id ;";
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) { ?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="hidden">ID</th>
									<th class="hidden">cmid</th>
									<th class="hidden">location</th>
									<th>Location</th>
									<th>Process</th>
									<th>Sub Process</th>
									<th>Employee ID</th>
									<th>Created By</th>
									<th>Created On</th>
									<th>Manage</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="id hidden">' . $value['id'] . '</td>';
									echo '<td class="cm_id hidden">' . $value['cm_id'] . '</td>';
									echo '<td class="location hidden">' . $value['location'] . '</td>';
									echo '<td class="loc">' . $value['loc'] . '</td>';
									echo '<td class="process">' . $value['process'] . '</td>';
									echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
									echo '<td class="EmpID">' . $value['EmpID'] . '</td>';
									echo '<td class="createdby">' . $value['createdby'] . '</td>';
									echo '<td class="createdon">' . $value['createdon'] . '</td>';
									echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
									//<i class="material-icons  imgBtn imgBtnEdit tooltipped delete_item" id="'.$value['dept_id'].'"  onclick="javascirpt:return ApplicationDataDelete(this);" data-position="left" data-tooltip="Delete">ohrm_delete</i>

								?>

								<?php

									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php }  ?>
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
				$('#btn_AprMaster_Can').trigger("click");
			}
		});
		// This code for cancel button trigger click and also for model close
		$('#btn_AprMaster_Can').on('click', function() {
			$('#txt_location').val('NA');
			$('#txt_Client').empty();
			//alert('can');
			//$('#txt_Client').val('NA');
			$('#txt_EmpID').val('');

			$('#hid_ID').val('');
			$('#btn_AprMaster_Save').removeClass('hidden');
			$('#btn_AprMaster_Edit').addClass('hidden');
			$('select').formSelect();
			//$('#btn_AprMaster_Can').addClass('hidden');

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

		$('#btn_AprMaster_Edit,#btn_AprMaster_Save').on('click', function() {
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
		// This code for remove error span from all element contain .has-error class on listed events

		$('#txt_location').change(function() {
			//alert('kavya');
			var tval = $("#txt_location").val();
			// var tval = $(this).val();
			//alert(tval);
			//alert(<?php echo '"' . URL . '"'; ?>+"Controller/getProcessNameByLocation.php?id="+tval);
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByLocation.php?id=" + tval
			}).done(function(data) { // data what is sent back by the php page
				$('#txt_Client').html(data);
				$('select').formSelect();
			});
		});

		$('#txt_EmpID').keyup(function() {
			var term = $(this).val();

			var resp_data_format = "";
			$.ajax({
				url: "../Controller/autocomplete_employee_active.php",
				data: {
					term: term,

				},
				method: "get",
				dataType: "json",
				success: function(response) {
					for (var i = 0; i < response.length; i++) {
						resp_data_format = resp_data_format + "<li class='select_country'>" + response[i] + "</li>";
					};
					$("#data-container").html(resp_data_format);
				}
			});
		});

		$(document).on("click", ".select_country", function() {
			var selected_country = $(this).html();
			$('#txt_EmpID').val(selected_country);
			$('#data-container').html('');
			var empid = $('#txt_EmpID').val().substr($('#txt_EmpID').val().lastIndexOf("(") + 1, ($('#txt_EmpID').val().lastIndexOf(")") - $('#txt_EmpID').val().lastIndexOf("(")) - 1);


		});
	});


	// This code for trigger edit on all data table also trigger model open on a Model ID

	function EditData(el) {
		var tr = $(el).closest('tr');
		var ID = tr.find('.ID').text();
		var cm_id = tr.find('.cm_id').text();
		var EmpID = tr.find('.EmpID').text();
		var location = tr.find('.location').text();

		//alert(EmpID);
		$('#hid_ID').val(ID);
		$('#txt_location').val(location);
		$('#txt_Client').empty();

		if ($.isNumeric(location)) {
			// alert(location)
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByLocation.php?id=" + location
			}).done(function(data) { // data what is sent back by the php page
				$('#txt_Client').html(data);
				$('#txt_Client').val(cm_id);
				$('select').formSelect();
			});
		}


		//$('#txt_Client').val(subprocess);
		$('#txt_EmpID').val(EmpID);
		$('#txt_Client').val(cm_id);
		$('select').formSelect();

		$('#btn_AprMaster_Save').addClass('hidden');
		$('#btn_AprMaster_Edit').removeClass('hidden');
		//$('#btn_AprMaster_Can').removeClass('hidden');
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
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

	// 		xmlhttp.open("GET", "../Controller/DeleteDepartment.php?ID=" + el.id, true);
	// 		xmlhttp.send();
	// 	}
	// }

	function getProcess(el) {
		var getProcessVal = $("#txt_location").val();
		// alert(getProcessVal)
		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByLocation.php?id=" + getProcessVal
		}).done(function(data) { // data what is sent back by the php page
			$('#txt_Client').html(data);
			$('select').formSelect();
		});
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>