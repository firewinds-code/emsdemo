<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$EmployeeID = clean($_SESSION['__user_logid']);

if (isset($_SESSION)) {

	if (!isset($EmployeeID)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$ceatedBy = $EmployeeID;
$date = date('Y-m-d H-i-s');
//print_r($_SESSION);
$clientID = $module_name = $searchBy = $id = '';
$classvarr = "'.byID'";
$clean_get_id = cleanUserInput($_GET['id']);
if (isset($clean_get_id) && $clean_get_id != "") {
	$id = $clean_get_id;
}
$cmid = cleanUserInput($_POST['cm_id']);
$cert_name = cleanUserInput($_POST['cert_name']);
$id = cleanUserInput($_POST['id']);
if (isset($cmid, $cert_name) and trim($_POST['filename']) != "" and trim($_POST['cert_name']) != "" and $id  == '') {
	$cm_id = trim(addslashes($_POST['cm_id']));
	$cert_name = trim(addslashes($_POST['cert_name']));
	$filename = trim(addslashes($_POST['filename']));
	$filename = str_replace("", "_", $filename);
	// $myDB = new MysqliDb();
	if ($cm_id != ""  && $cert_name != "" && $filename != "") {
		$select_query = "select id from certification_require_by_cmid where cm_id=? and cert_name=?";
		$selectQ = $conn->prepare($select_query);
		$selectQ->bind_param("is", $cm_id, $cert_name);
		$selectQ->execute();
		$resultBy = $selectQ->get_result();
		// $resultBy = $myDB->rawQuery($select_query);
		// $mysql_error = $myDB->getLastError();
		if ($resultBy->num_rows > 0) {
			echo "<script>$(function(){ toastr.info('Duplicate entry not allowed'); }); </script>";
		} else {
			$insertQuery = "INSERT INTO  certification_require_by_cmid set cert_name=?,filename=?,cm_id=?,createdby=?";
			$insert = $conn->prepare($insertQuery);
			$insert->bind_param("ssis", $cert_name, $filename, $cm_id, $ceatedBy);
			$insert->execute();
			$resultBy = $insert->get_result();
			// $resultBy = $myDB->rawQuery($insertQuery);
			echo "<script>$(function(){ toastr.success('Test Added Successfully'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Please enter all fields'); }); </script>";
	}
}
//print_r($_POST);
$savemodule = cleanUserInput($_POST['savemodule']);
$ID = cleanUserInput($_POST['id']);
if (isset($_POST['savemodule']) && $ID  != "") {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		// echo 'dgdf';
		// die;
		$cm_id = trim(addslashes($_POST['cm_id']));
		$cert_name = trim(addslashes($_POST['cert_name']));
		$filename = trim(addslashes($_POST['filename']));
		$filename = str_replace(" ", "", $filename);
		$id = $_POST['id'];
		// $myDB = new MysqliDb();
		if ($cm_id != ""  && $cert_name != "" && $filename != "") {

			$updateQuery = "Update  certification_require_by_cmid set cert_name=?,filename=?,cm_id=?,updatedby=?  where id=?";
			$update = $conn->prepare($updateQuery);
			$update->bind_param("ssisi", $cert_name, $filename, $cm_id, $ceatedBy, $id);
			$update->execute();
			$resultBy = $update->get_result();

			// $resultBy = $myDB->query($updateQuery);
			echo "<script>$(function(){ toastr.success('Test Updated Successfully'); }); </script>";
		}
	}
}
?>
<script>
	$(document).ready(function() {
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

		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');

		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Test Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Test Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Test"><i class="material-icons">ohrm_filter</i></a> </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Add/Edit Test</h4>

						<div class="modal-body">
							<div id="medit">

								<div class="input-field col s4 m4">
									<select id="txt_location" name="txt_location" required>
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

								<div class="input-field col s4 m4">

									<select name="cm_id" id="cm_id" title="cm_id" required>
										<option value=''>Select Process</option>

									</select>
									<label for="cm_id" class="active-drop-down active">Process</label>
								</div>
								<div class="input-field col s4 m4">
									<input type="text" name="cert_name" id="cert_name" title="cert_name" value="" required>
									<label for="cert_name">Test Name</label>
								</div>
								<div class="input-field col s6 m6">
									<input type="text" name="filename" onkeydown="Check(event);" id="filename" title="filename" value="" required>
									<label for="filename">Test File Name(without space)</label>
								</div>
							</div>
							<input type='hidden' name='id' id='id' value=''>
							<div class="input-field col s12 m12 right-align">
								<button type="submit" name="savemodule" id="savemodule" class="btn waves-effect waves-green" style="display:none;">Save</button>
								<button type="submit" name="addmodule" id="addmodule" class="btn waves-effect waves-green" style="display:none;">Add</button>
								<button type="button" name="cancle" id='cancelID' class="btn waves-effect waves-green">Cancel</button>
							</div>
						</div>
					</div>
				</div>


				<?php
				//$sqlConnect = "SELECT a.ID, a.cm_id, a.cert_name, a.filename,b.process,b.sub_process from  certification_require_by_cmid  a Inner Join new_client_master b where a.cm_id=b.cm_id "; 
				$sqlConnect = "SELECT a.ID, a.cm_id, a.cert_name, a.filename,b.process,b.sub_process,c.client_name,d.location,d.id from  certification_require_by_cmid  a Inner Join new_client_master b inner join client_master c on b.client_name=c.client_id join location_master d on b.location=d.id where a.cm_id=b.cm_id";
				$myDB = new MysqliDb();
				$result = $myDB->query($sqlConnect);
				//print_r($result);
				$error = $myDB->getLastError();
				if ($result) { ?>
					<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th> Srl.No.</th>
								<th>Test </th>
								<th> Client </th>
								<th> Process </th>
								<th> Sub-Process </th>
								<th> Filename </th>
								<th> Location </th>
								<th> Manage </th>
								<th class="hidden"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							foreach ($result as $key => $val) {

								$ID = $val['ID'];
								echo '<tr>';
								echo '<td class="module_id" >' . $i . '</td>';
								echo '<td class="cert_name">' . $val['cert_name'] . '</td>';
								echo '<td class="cm_id">' . $val['client_name'] . '</td>';
								echo '<td class="cm_id">' . $val['process'] . '</td>';
								echo '<td class="cm_id">' . $val['sub_process'] . '</td>';
								echo '<td class="filename">' . $val['filename'] . '</td>';
								echo '<td class="location">' . $val['location'] . '</td>';
							?>

								<td class="manage_item">
									<a onclick="editData('<?php echo $ID; ?>','<?php echo $val['cert_name']; ?>','<?php echo $val['cm_id']; ?>','<?php echo $val['filename']; ?>','<?php echo $val['id']; ?>');"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a>
								</td>

							<?php
								echo '<td class="hidden">' . $val['id'] . '</td>';
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
</div>

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

		$('#ContentAdd').click(function() {
			$('#medit').show();
			$('#cert_name').val('');
			$('#cm_id').val('');
			$('#filename').val('');
			$('select').formSelect();
			$('#id').val('');
			$('#savemodule').hide();
			$('#addmodule').show();
		});
		// This code for cancel button trigger click and also for model close
		$('#cancelID').on('click', function() {
			$('#module_name').val('');
			$('#myModal_content').modal('close');
			//$('#medit').hide();
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

		$('#savemodule ,#addmodule').on('click', function() {
			var validate = 0;
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			$("input,select,textarea").each(function() {
				var spanID = "span" + $(this).attr('id');
				$(this).removeClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
				var attr_req = $(this).attr('required');
				if (($(this).val().trim() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
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
				return false;
			}
		});
		$('#medit').hide();

		$('#txt_location').change(function() {
			//alert('kavya');
			var tval = $(this).val();
			getProcess(tval);
			//alert(tval);
			//alert(<?php echo '"' . URL . '"'; ?>+"Controller/getProcessNameByLocation.php?id="+tval);

		});

	});

	function getProcess(val) {
		//alert(val);
		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByLocation.php?id=" + val
		}).done(function(data) { // data what is sent back by the php page
			$('#cm_id').html(data);
			$('select').formSelect();
		});
	}

	function getProcess1(val, id) {
		//alert(val);
		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByLocation.php?id=" + val
		}).done(function(data) { // data what is sent back by the php page
			$('#cm_id').html(data);
			$('#cm_id').val(id);
			$('select').formSelect();
		});
	}

	function editData(id, cert_name, cm_id, filename, locid) {

		$('#medit').show();
		$('#cert_name').val(cert_name);

		$('#txt_location').val(locid);
		$('#filename').val(filename);
		$('select').formSelect();
		$('#id').val(id);
		$('#savemodule').show();
		$('#addmodule').hide();
		//alert(id); alert(locid);
		//$('#cm_id').empty().append('<option selected="selected" value="">Select Process</option>');
		getProcess1($('#txt_location').val(), cm_id);
		$('#cm_id').val(cm_id);
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
</script>
<script>
	function Check(e) {

		var keyCode = (e.keyCode ? e.keyCode : e.which);
		if (keyCode > 47 && keyCode < 58) {
			e.preventDefault();
		}
	}
</script>