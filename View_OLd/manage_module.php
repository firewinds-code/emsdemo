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
if (isset($_SESSION)) {

	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
//print_r($_SESSION);
$clientID = $module_name = $searchBy = $id = '';
$classvarr = "'.byID'";
$idd = isset($_GET['id']);
if ($idd && $idd != "") {
	$id = cleanUserInput($_GET['id']);
}


$delidd = isset($_GET['delid']);
if ($delidd && $delidd != "") {
	$delid = cleanUserInput($_GET['delid']);
	$delete_query = "DELETE from module_manager  where ID=?";
	$delete_query = "DELETE from manage_module_email  where moduleID=?";
	$del = $conn->prepare($delete_query);
	$del->bind_param("i", $delid);
	$del->execute();
	$resultBy = $del->get_result();
	// $resultBy = $myDB->rawQuery($delete_query);
	// $mysql_error = $myDB->getLastError();
	// if (empty($mysql_error)) {
	if ($del->affected_rows === 1) {
		echo "<script>$(function(){ toastr.error('Module Deleted Successfully'); }); </script>";
	}
}
$mod_name = ($_POST['module_name']);
$addmod = ($_POST['addmodule']);
if (isset($mod_name, $addmod)) {
	$date = date('Y-m-d');
	$createdBy = clean($_SESSION['__user_logid']);
	$module_name = cleanUserInput($_POST['module_name']);

	if ($module_name != "") {
		$select_query = "select ID from module_manager where module_name=?";
		$sel = $conn->prepare($select_query);
		$sel->bind_param("s", $module_name);
		$sel->execute();
		$resultBy = $sel->get_result();

		// $resultBy = $myDB->rawQuery($select_query);
		// $mysql_error = $myDB->getLastError();
		if ($resultBy->num_rows > 0) {
			echo "<script>$(function(){ toastr.info('Duplicate entry not allowed'); }); </script>";
		} else {
			$insertQuery = "INSERT INTO module_manager set module_name=?,createdOn=? ";
			$ins = $conn->prepare($insertQuery);
			$ins->bind_param("ss", $module_name, $date);
			$ins->execute();
			$resultBys = $ins->get_result();
			// $resultBy = $myDB->rawQuery($insertQuery);
			echo "<script>$(function(){ toastr.success('Module Added Successfully'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Please enter module Name'); }); </script>";
	}
}
//print_r($_POST);
$savemodule = isset($_POST['savemodule']);
$idd = $_POST['id'];
if ($savemodule && $idd != "") {  //print_r($_POST);
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$module_name = cleanUserInput($_POST['module_name']);
		$id = cleanUserInput($_POST['id']);
		if ($module_name != "") {
			$updateQuery = "Update  module_manager set module_name=? where ID=?";
			$update = $conn->prepare($updateQuery);
			$update->bind_param("si", $module_name, $id);
			$update->execute();
			$resultBy = $update->get_result();

			// $resultBy = $myDB->query($updateQuery);
			echo "<script>$(function(){ toastr.success('Module Name Updated Successfully'); }); </script>";
		}
	}
}
?>
<script>
	$(document).ready(function() {
		$('#from_date').datetimepicker({
			format: 'Y-m-d H:i',
			step: 30
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollX: '100%',
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
				}, 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		$('.byDate').addClass('hidden');
		$('.byDept').addClass('hidden');
		$('.byProc').addClass('hidden');
		$('.byName').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Module</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Module</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Manage Module</h4>
						<div class="modal-body">
							<?php
							$idd = isset($_GET['id']);
							if ($idd and $idd != "") {
								$Id = cleanUserInput($_GET['id']);
								$sqlConnect2 = 'select  * from  module_manager  where ID=?';
								$module_name = "";
								$selectQ = $conn->prepare($sqlConnect2);
								$selectQ->bind_param("i", $Id);
								$selectQ->execute();
								$result2 = $selectQ->get_result();

								// $result2 = $myDB->query($sqlConnect2);
								foreach ($result2 as $key => $value) {
									$module_name = $value['module_name'];
								}
							}
							?>
							<div id="medit">
								<div class="input-field col s6 m6">
									<input type="text" name="module_name" id="module_name" title="module_name" value="<?php echo $module_name; ?>" required>
									<label for="searchBy"> Module Name</label>
								</div>
							</div>


							<input type='hidden' name='id' id='id' value='<?php echo $id; ?>'>
							<div class="input-field col s12 m12 right-align">
								<button type="submit" name="savemodule" id="savemodule" class="btn waves-effect waves-green" style="display:none;">Save</button>
								<a href="<?php echo URL; ?>View/manage_module.php" class="btn waves-effect modal-action modal-close waves-red close-btn" id='cancelID'>Cancel</a>
							</div>
						</div>
					</div>
				</div>

				<?php
				$sqlConnect = "select  * from module_manager where status='1'";
				$myDB = new MysqliDb();
				$result = $myDB->query($sqlConnect);
				//print_r($result);
				$error = $myDB->getLastError();
				if ($result) { ?>
					<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th> Srl.No.</th>
								<th>Module Name</th>
								<th>Page Name</th>
								<th> Edit </th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							foreach ($result as $key => $val) {
								$module_name = $val['module_name'];
								$modulename = $val['modulename'];
								$ID = $val['ID'];
								echo '<tr>';
								echo '<td class="module_id" >' . $i . '</td>';
								echo '<td class="module_name">' . $module_name . '</td>';
								echo '<td class="module_name">' . $modulename . '</td>';
								// href="manage_module.php?id=<?php echo $ID; "
							?>
								<td class="manage_item">
									<a onclick="editData('<?php echo $ID; ?>','<?php echo $module_name; ?>');"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a>
								</td>
							<?php
								echo '</tr>';
								$i++;
							}
							?>
						</tbody>
					</table>
				<?php } else {
					echo "<script>$(function(){ toastr.error('No Data Found " . $error . "'); }); </script>";
				}

				?>
			</div>
		</div>
	</div>
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
	$(document).ready(function() {

		//Model Assigned and initiation code on document load
		$('.modal').modal({
			onOpenStart: function(elm) {


			},
			onCloseEnd: function(elm) {
				$('#btn_Department_Can').trigger("click");
			}
		});
		// This code for cancel button trigger click and also for model close
		$('#cancelID').on('click', function() {
			$('#module_name').val('');

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

		$('#savemodule').on('click', function() {
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

		$('#medit').hide();
	});

	function editData(id, module) {
		$('#medit').show();
		$('#module_name').val(module);
		$('#id').val(id);
		$('#savemodule').show();
		$('#addmodule').hide();

		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
	}
</script>