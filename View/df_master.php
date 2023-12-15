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
	if ($_SESSION['__user_type'] != 'ADMINISTRATOR') {
		$location = URL . 'Error';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$alert_msg = '';
// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_df_Save'])) {
	$_funcid = (isset($_POST['txt_df_function']) ? $_POST['txt_df_function'] : null);
	$_desid = (isset($_POST['txt_df_desg']) ? $_POST['txt_df_desg'] : null);

	$createBy = $_SESSION['__user_logid'];
	$Insert = 'call add_df_data("' . $_funcid . '","' . $_desid . '")';
	$myDB = new MysqliDb();


	//$rowCount=mysql_affected_rows();
	$result = $myDB->rawQuery($Insert);
	$mysql_error = $myDB->getLastError();
	if (empty($mysql_error)) {
		if ($myDB->count > 0) {
			echo "<script>$(function(){ toastr.success('Added Successfully') }); </script>";
		} else {
			echo "<script>$(function(){ toastr.error('Data already exists') }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Not Added :: '.$mysql_error.') }); </script>";
	}
}
// Trigger Button-Edit Click Event and Perform DB Action
if (isset($_POST['btn_df_Edit'])) {
	$DataID = $_POST['hid_df_ID'];
	$_funcid = (isset($_POST['txt_df_function']) ? $_POST['txt_df_function'] : null);
	$_desid = (isset($_POST['txt_df_desg']) ? $_POST['txt_df_desg'] : null);
	$ModifiedBy = $_SESSION['__user_logid'];
	$Update = 'call save_df_data("' . $_funcid . '","' . $_desid . '","' . $DataID . '")';
	$myDB = new MysqliDb();
	if (!empty($DataID) || $DataID != '') {
		$result = $myDB->rawQuery($Update);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Updated Successfully') }); </script>";
			$_Comp = $_Hod = $_Name = '';
			$_Hod = "NA";
		} else {
			echo "<script>$(function(){ toastr.error('Not Updated :: '.$mysql_error.') }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First :: <code>(If Not Resolved then contact to technical person)</code>') }); </script>";
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

				/*  {
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
<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Designation and Function Master Details</span>
	<div class="pim-container row" id="div_main">
		<div class="form-div">
			<h4>Designation and Function Master Details
				<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_dept" data-position="bottom" data-tooltip="Add Designation"><i class="material-icons">add</i></a>
			</h4>

			<div class="schema-form-section row">


				<div id="myModal_dept" class="modal">
					<!-- Modal content-->
					<div class="modal-content" style="height: 435px !important">
						<h4 class="col s12 m12 model-h4">Designation and Function Master Details</h4>
						<div class="modal-body" style="height: 435px !important">

							<div class="input-field col s6 m6">
								<select id="txt_df_function" name="txt_df_function" required>
									<option value="NA">---Select---</option>
									<?php
									$sqlBy = 'select id,`function` from function_master order by `function`';
									$myDB = new MysqliDb();
									$result = $myDB->rawQuery($sqlBy);
									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										foreach ($result as $key => $value) {
											echo '<option value="' . $value['id'] . '"  >' . $value['function'] . '</option>';
										}
									}
									?>
								</select>
								<label for="txt_df_function" class="active-drop-down active">Function</label>
							</div>

							<div class="input-field col s6 m6">
								<select id="txt_df_desg" name="txt_df_desg" required>
									<option value="NA">---Select---</option>
									<?php
									$sqlBy = 'select ID,Designation from designation_master order by Designation';
									$myDB = new MysqliDb();
									$result = $myDB->rawQuery($sqlBy);
									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										foreach ($result as $key => $value) {
											echo '<option value="' . $value['ID'] . '"  >' . $value['Designation'] . '</option>';
										}
									}
									?>
								</select>
								<label for="txt_df_function" class="active-drop-down active">Designation</label>
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
				<div id="pnlTable">
					<?php
					$sqlConnect = 'call get_df_data()';
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					if ($result) { ?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>df ID</th>
									<th class="hidden">Function ID</th>
									<th>Function</th>
									<th class="hidden">Designation ID</th>
									<th>Designation</th>
									<th>Manage df</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="df_id">' . $value['df_id'] . '</td>';
									echo '<td class="function_id hidden">' . $value['function_id'] . '</td>';
									echo '<td class="function">' . $value['function'] . '</td>';
									echo '<td class="des_id hidden">' . $value['des_id'] . '</td>';
									echo '<td class="Designation">' . $value['Designation'] . '</td>';

									echo '<td class="manage_item" >
							<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['df_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i></td>';
									/*<i class="material-icons  imgBtn imgBtnEdit tooltipped delete_item" id="'.$value['df_id'].'"  onclick="javascirpt:return ApplicationDataDelete(this);" data-position="left" data-tooltip="Delete">ohrm_delete</i>*/
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
			$('#hid_df_ID').val('');
			$('#txt_df_function').val('NA');
			$('#txt_df_desg').val('NA');
			$('#btn_df_Save').removeClass('hidden');
			$('#btn_df_Edit').addClass('hidden');
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
		$('#btn_df_Save,#btn_df_Edit').on('click', function() {
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
		var df_if = tr.find('.df_id').text();
		var function_id = tr.find('.function_id').text();
		var des_id = tr.find('.des_id').text();

		$('#hid_df_ID').val(df_if);
		$('#txt_df_function').val(function_id);
		$('#txt_df_desg').val(des_id);

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