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

$EmployeeID = clean($_SESSION['__user_type']);

if ($EmployeeID == 'ADMINISTRATOR' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE12102224') {
	// proceed further
} else {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}
// Global variable used in Page Cycle
$alert_msg = '';

// Trigger Button-Save Click Event and Perform DB Action
$btn_df_Save = isset($_POST['btn_df_Save']);
if ($btn_df_Save) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$_cm_id		=	cleanUserInput($_POST['txt_cm_id']);
		$min_value  = 	cleanUserInput($_POST['txt_minValue']);
		$max_value  = 	cleanUserInput($_POST['txt_maxValue']);
		$avg_value  = 	cleanUserInput($_POST['txt_AverageValue']);

		$resultcheck = 'SELECT * FROM tbl_salary_slab_by_cps where cm_id = ?';
		$selectQ = $conn->prepare($resultcheck);
		$selectQ->bind_param("i", $_cm_id);
		$selectQ->execute();
		$result_check = $selectQ->get_result();

		if ($result_check->num_rows > 0 && $result_check) {
			echo "<script>$(function(){ toastr.error('Already exists delete saved entry first') }); </script>";
		} else {
			$createBy = clean($_SESSION['__user_logid']);
			$Insert = 'insert into tbl_salary_slab_by_cps(cm_id, min_lim, max_lim,avg_sal) VALUES(?,?,?,?)';
			$insert = $conn->prepare($Insert);
			$insert->bind_param("iiii", $_cm_id, $min_value, $max_value, $avg_value);
			$insert->execute();
			$rowCount = $insert->get_result();
			// $myDB->rawQuery($Insert);
			// $mysql_error = $myDB->getLastError();
			// $rowCount = $myDB->count;
			// if (empty($mysql_error)) {
			if ($insert->affected_rows === 1) {
				echo "<script>$(function(){ toastr.success('Added Successfully') }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Not Added :: ') }); </script>";
			}
			// } else {
			// 	echo "<script>$(function(){ toastr.error('Not Added :: '.$mysql_error.') }); </script>";
			// }
		}
	}
}

// Trigger Button-Edit Click Event and Perform DB Action
$btn_df_Edit = isset($_POST['btn_df_Edit']);
if ($btn_df_Edit) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$_cm_id		=	cleanUserInput($_POST['txt_cm_id']);
		$min_value  = 	cleanUserInput($_POST['txt_minValue']);
		$max_value  = 	cleanUserInput($_POST['txt_maxValue']);
		$avg_value  = 	cleanUserInput($_POST['txt_AverageValue']);

		if (empty($_POST['txtEditID'])) {
			echo "<script>$(function(){ toastr.error('No data found to update') }); </script>";
		} else {
			$createBy = clean($_SESSION['__user_logid']);
			$ID = cleanUserInput($_POST['txtEditID']);
			$Update = 'update tbl_salary_slab_by_cps set cm_id =?, min_lim =?, max_lim = ?, avg_sal = ? where id=?';
			$updates = $conn->prepare($Update);
			$updates->bind_param("iiiii", $_cm_id, $min_value, $max_value, $avg_value, $ID);
			$updates->execute();
			$rowCount = $updates->get_result();
			// $result = $myDB->rawQuery($Update);
			// $mysql_error = $myDB->getLastError();
			// $rowCount = $myDB->count;
			// if (empty($mysql_error)) {
			if ($updates->affected_rows === 1) {
				echo "<script>$(function(){ toastr.success('Updated Successfully') }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Not Updated :: ') }); </script>";
			}
		}
	}
}
?>

<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollCollapse: true,
			"iDisplayLength": 25,
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Salary Slab Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Salary Slab Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Salary Slab"><i class="material-icons">add</i></a></h4>

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
						<h4 class="col s12 m12 model-h4">Manage Salary Slab Master</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">

							<div class="input-field col s6 m6">
								<select id="txt_location" name="txt_location" required onchange="javascript:return getProcess(this,'');">
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

							<div class="input-field col s6 m6">
								<select id="txt_cm_id" name="txt_cm_id" required></select>
								<!--<select class="form-control" id="txt_cm_id" name="txt_cm_id" required>
				            <optgroup>
				            	<option value="NA">---Select---</option>
				            	<?php
								$sqlBy = 'select * from new_client_master inner join client_master  on new_client_master.client_name = client_master.client_id order by new_client_master.client_name';
								$myDB = new MysqliDb();
								$resultBy = $myDB->rawQuery($sqlBy);
								$mysql_error = $myDB->getLastError();
								$rowCount = $myDB->count;
								if (empty($mysql_error)) {
									foreach ($resultBy as $key => $value) {

										echo '<option value="' . $value['cm_id'] . '"  >' . $value['client_name'] . ' | ' . $value['process'] . ' | ' . $value['sub_process'] . '</option>';
									}
								}

								?>
				            </optgroup>
				            </select>-->
								<label for="txt_cm_id" class="active-drop-down active">Process</label>
							</div>
							<div class="input-field col s6 m6">
								<input type="number" class="form-control" id="txt_minValue" name="txt_minValue" required />
								<label for="txt_minValue">Minimum Salary</label>
							</div>
							<div class="input-field col s6 m6">
								<input type="number" class="form-control" id="txt_maxValue" name="txt_maxValue" required />
								<label for="txt_maxValue">Maximum Salary</label>
							</div>
							<div class="input-field col s6 m6">
								<input type="number" class="form-control" id="txt_AverageValue" name="txt_AverageValue" required />
								<label for="txt_AverageValue">Average Salary</label>
							</div>

							<div class="input-field col s12 m12 right-align">
								<input type="hidden" class="form-control" id="txtEditID" name="txtEditID" style="max-width: 300px;min-width: 300px;" />
								<button type="submit" name="btn_df_Save" id="btn_df_Save" class="btn waves-effect waves-green">Save</button>
								<button type="submit" name="btn_df_Edit" id="btn_df_Edit" class="btn waves-effect waves-green hidden">Update</button>
								<button type="button" name="btn_df_Can" id="btn_df_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>
						</div>
					</div>
				</div>

				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
					$sqlConnect = 'SELECT t1.id, t1.cm_id, t1.min_lim, t1.max_lim, t1.avg_sal, t3.client_name, t2.process, t4.location, t2.sub_process FROM tbl_salary_slab_by_cps t1 inner join new_client_master t2 on t2.cm_id = t1.cm_id inner join client_master t3 on t2.client_name = t3.client_id inner join location_master t4 on t4.id=t2.location left outer join client_status_master cs on cs.cm_id=t2.cm_id where cs.cm_id is null  order by client_name';
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) { ?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="hidden">Process</th>
									<th>Process</th>
									<th>Minimum Value</th>
									<th>Maximum Value</th>
									<th>Average</th>
									<th>Location</th>
									<th>Manage</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="id hidden">' . $value['id'] . '</td>';
									echo '<td class="cm_id" data="' . $value['cm_id'] . '">' . $value['client_name'] . ' | ' . $value['process'] . ' | ' . $value['sub_process'] . '</td>';
									echo '<td class="min_lim">' . $value['min_lim'] . '</td>';
									echo '<td class="max_lim">' . $value['max_lim'] . '</td>';
									echo '<td class="avg_sal">' . $value['avg_sal'] . '</td>';
									echo '<td class="loc">' . $value['location'] . '</td>';
									echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['id'] . '" data-position="left" data-tooltip="Edit">ohrm_edit</i></td>';
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
				/*$('#txt_cm_id').attr('disabled', false);
				$('#txt_location').attr('disabled', false);*/
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
		$('#btn_df_Can').on('click', function() {

			/*$('#txt_cm_id').attr('disabled', false);
	$('#txt_location').attr('disabled', false);*/
			$('#txtEditID').val('');
			$('#txt_maxValue').val('');
			$('#txt_minValue').val('');
			$('#txt_cm_id').val('NA');
			$('#txt_AverageValue').val('NA');
			$('#btn_df_Save').removeClass('hidden');
			$('#btn_df_Edit').addClass('hidden');
			//$('#btn_df_Can').addClass('hidden');
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

		$('#btn_df_Save,#btn_df_Edit').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			/*$('#txt_cm_id').attr('disabled', false);
	$('#txt_location').attr('disabled', false);*/
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			if ($('#txt_location').val() == 'NA' || $('#txt_location').val() == '') {
				$('#txt_location').addClass("has-error");
				if ($('#spantxt_location').length == 0) {
					$('<span id="spantxt_location" class="help-block">Required *</span>').insertAfter('#txt_location');
				}
				validate = 1;
			}
			if ($('#txt_cm_id').val() == 'NA') {
				$('#txt_cm_id').addClass("has-error");
				if ($('#spantxt_cm_id').length == 0) {
					$('<span id="spantxt_cm_id" class="help-block">Required *</span>').insertAfter('#txt_cm_id');
				}
				validate = 1;
			}

			if ($('#txt_minValue').val() == '') {
				$('#txt_minValue').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_minValue').length == 0) {
					$('<span id="spantxt_minValue" class="help-block">Required *</span>').insertAfter('#txt_minValue');
				}
				validate = 1;
			}
			if ($('#txt_maxValue').val() == '') {
				$('#txt_maxValue').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_maxValue').length == 0) {
					$('<span id="spantxt_maxValue" class="help-block">Required *</span>').insertAfter('#txt_maxValue');
				}
				validate = 1;
			}
			if ($('#txt_AverageValue').val() == '') {
				$('#txt_AverageValue').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_AverageValue').length == 0) {
					$('<span id="spantxt_AverageValue" class="help-block">Required *</span>').insertAfter('#txt_AverageValue');
				}
				validate = 1;
			}

			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");*/
				return false;
			}

		});

	});

	function EditData(el) {
		var tr = $(el).closest('tr');
		var id = tr.find('.id').text();
		var minVal = tr.find('.min_lim').text();
		var maxVal = tr.find('.max_lim').text();
		var AverageValue = tr.find('.avg_sal').text();
		var cm_id = tr.find('.cm_id').attr('data');

		$('#txtEditID').val(id);
		$('#txt_maxValue').val(maxVal);
		$('#txt_minValue').val(minVal);
		//$('#txt_cm_id').val(cm_id);
		$('#txt_AverageValue').val(AverageValue);

		$('#btn_df_Save').addClass('hidden');
		$('#btn_df_Edit').removeClass('hidden');
		//$('#btn_df_Can').removeClass('hidden');
		$('#txt_location').val('NA');
		var location = tr.find('.loc').text();

		$("#txt_location option:contains(" + location + ")").attr('selected', 'selected');
		getProcess($('#txt_location').val(), cm_id);

		/*$('#txt_cm_id').attr('disabled', true);
		$('#txt_location').attr('disabled', true);*/

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

	function getProcess(el, el1) {
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
				$('#txt_cm_id').html(Resp);
				$('select').formSelect();
			}

		}
		//$('#txt_clientname').val($("#txt_client option:selected").text());
		var location = <?php echo $_SESSION["__location"] ?>;
		xmlhttp.open("GET", "../Controller/getprocessByLocation.php?loc=" + $('#txt_location').val() + "&cmid=" + el1, true);
		xmlhttp.send();
		//$('#txt_cm_id').val(el1);
	}

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
			xmlhttp.open("GET", "../Controller/delete_salary_slab_master.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>