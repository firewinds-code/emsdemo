<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$userlogID = clean($_SESSION['__user_logid']);
$user_type = clean($_SESSION['__user_type']);
if (isset($_SESSION)) {
	if (!isset($userlogID)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
	if ($user_type != 'ADMINISTRATOR' || clean($_SESSION['__user_logid']) != 'CE10091236') {
		$location = URL . 'Error';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$alert_msg = '';
// Trigger Button-Save Click Event and Perform DB Action
$btn_Desg_Save = isset($_POST['btn_Desg_Save']);
if ($btn_Desg_Save) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_Desg_Name = cleanUserInput($_POST['txt_Desg_Name']);
		$_Name = (isset($txt_Desg_Name) ? $txt_Desg_Name : null);
		$createBy = clean($_SESSION['__user_logid']);
		$Insert = 'call add_designation("' . $_Name . '","' . $createBy . '")';
		$myDB = new MysqliDb();

		$result = $myDB->rawQuery($Insert);
		$MysqlError = $myDB->getLastError();
		if (empty($MysqlError)) {
			echo "<script>$(function(){ toastr.success('Designation Added Successfully'); }); </script>";
		} else {
			echo "<script>$(function(){ toastr.error(Designation not Added :: '.$MysqlError.'); }); </script>";
		}
	}
}
// Trigger Button-Edit Click Event and Perform DB Action
$btn_Desg_Edit = isset($_POST['btn_Desg_Edit']);
if ($btn_Desg_Edit) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$DataID = cleanUserInput($_POST['hid_Desg_ID']);
		$txt_Desg_Name = cleanUserInput($_POST['txt_Desg_Name']);
		$_Name = (isset($txt_Desg_Name) ? $txt_Desg_Name : null);
		$ModifiedBy = clean($_SESSION['__user_logid']);
		$Update = 'call save_Desg("' . $_Name . '","' . $ModifiedBy . '","' . $DataID . '")';


		$myDB = new MysqliDb();
		if (!empty($DataID) || $DataID != '') {
			$result = $myDB->rawQuery($Update);
			$MysqlError = $myDB->getLastError();
			if (empty($MysqlError)) {
				echo "<script>$(function(){ toastr.success('Designation Updated Successfully'); }); </script>";
				$_Comp = $_Hod = $_Name = '';
				$_Hod = "NA";
			} else {
				echo "<script>$(function(){ toastr.error('Designation not Updated'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First :: <code>(If Not Resolved then contact to technical person)</code>'); }); </script>";
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
				/*  
				  {
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
	<span id="PageTittle_span" class="hidden">Designation Master Details</span>
	<div class="pim-container row" id="div_main">
		<div class="form-div">
			<h4>Designation Master Details
				<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="<?php echo URL . 'View/df_master.php'; ?>" data-position="bottom" data-tooltip="Function's Designation" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a>
				<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Designation"><i class="material-icons">add</i></a>
			</h4>
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Manage Designation</h4>

						<div class="modal-body">
							<div class="input-field col s6 m6">
								<input type="text" id="txt_Desg_Name" name="txt_Desg_Name" required />
								<label for="txt_Desg_Name">Designation Name</label>
							</div>
							<div class="input-field col s6 m6 right-align">
								<input type="hidden" class="form-control hidden" id="hid_Desg_ID" name="hid_Desg_ID" />
								<button type="submit" name="btn_Desg_Save" id="btn_Desg_Save" class="btn waves-effect waves-green">Add</button>
								<button type="submit" name="btn_Desg_Edit" id="btn_Desg_Edit" class="btn waves-effect waves-green hidden">Save</button>
								<button type="button" name="btn_Desg_Can" id="btn_Desg_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>

							</div>
						</div>
					</div>
				</div>

				<div id="pnlTable">
					<?php

					//$sqlConnect = array('table' => 'designation_master','fields' => 'ID,Designation','condition' =>"1"); 
					$sqlConnect = "select ID,Designation from designation_master";
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$MysqlError = $myDB->getLastError();
					if (empty($MysqlError)) { ?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th> ID</th>
									<th>Designation Name</th>
									<th>Manage Designation</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="Desg_id">' . $value['ID'] . '</td>';
									echo '<td class="Desg_name">' . $value['Designation'] . '</td>';
									echo '<td class="manage_item" >
								<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['ID'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i></td>';
									/*<i class="material-icons  imgBtn imgBtnEdit tooltipped delete_item" id="'.$value['ID'].'"  onclick="javascirpt:return ApplicationDataDelete(this);" data-position="left" data-tooltip="Delete">ohrm_delete</i> */
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

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
	$(document).ready(function() {
		//Model Assigned and initiation code on document load
		$('.modal').modal({
			onOpenStart: function(elm) {


			},
			onCloseEnd: function(elm) {
				$('#btn_Desg_Can').trigger("click");
			}
		});
		// This code for cancel button trigger click and also for model close
		$('#btn_Desg_Can').on('click', function() {
			$('#txt_Desg_Name').val('');
			$('#hid_Desg_ID').val('');
			$('#btn_Desg_Save').removeClass('hidden');
			$('#btn_Desg_Edit').addClass('hidden');
			//$('#btn_Desg_Can').addClass('hidden');

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

		$('#btn_Desg_Edit,#btn_Desg_Save').on('click', function() {
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

	});
	// This code for trigger edit on all data table also trigger model open on a Model ID

	function EditData(el) {
		var tr = $(el).closest('tr');
		var designation_id = tr.find('.Desg_id').text();
		var designation_name = tr.find('.Desg_name').text();

		$('#hid_Desg_ID').val(designation_id);
		$('#txt_Desg_Name').val(designation_name);
		$('#btn_Desg_Save').addClass('hidden');
		$('#btn_Desg_Edit').removeClass('hidden');
		//$('#btn_Desg_Can').removeClass('hidden');

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
</script>