<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
require_once(LIB . 'PHPExcel/IOFactory.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_SESSION)) {
	$user_logid = clean($_SESSION['__user_logid']);
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

$addmaster = isset($_POST['addMaster']);
if ($addmaster) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		//die;
		$EmpID = $EmpName = $EmpID2 = $EmpName2 = $EmployeeID = '';

		$txt_matrixtype = cleanUserInput($_POST['txt_matrixtype']);
		$txt_location   = cleanUserInput($_POST['txt_location']);
		$txt_Process    = cleanUserInput($_POST['txt_Process']);
		$txt_Module     = cleanUserInput($_POST['txt_Module']);
		$txt_Level      = cleanUserInput($_POST['txt_Level']);
		$txt_empid      = cleanUserInput($_POST['txt_empid']);

		$_txt_matrixtype = (isset($txt_matrixtype) ? $txt_matrixtype : null);
		$_txt_location   = (isset($txt_location) ? $txt_location : null);
		$_txt_Process    = (isset($txt_Process) ? $txt_Process : null);
		$_txt_Module     = (isset($txt_Module) ? $txt_Module : null);
		$_txt_Level      = (isset($txt_Level) ? $txt_Level : null);
		$_txt_empid      = (isset($txt_empid) ? $txt_empid : null);

		$EmpID = substr($_txt_empid, strpos($_txt_empid, "(") + 1, (strpos($_txt_empid, ")")) - (strpos($_txt_empid, "(") + 1));
		$EmpName = substr($_txt_empid, 0, strpos($_txt_empid, "(") - 1);


		if ($_txt_Level == "2") {
			$txt_empid1 = cleanUserInput($_POST['txt_empid1']);
			$EmpID2 = substr($txt_empid1, strpos($txt_empid1, "(") + 1, (strpos($txt_empid1, ")")) - (strpos($txt_empid1, "(") + 1));
			$EmpName2 = substr($txt_empid1, 0, strpos($txt_empid1, "(") - 1);
		}



		$createBy = clean($_SESSION['__user_logid']);

		if ($_txt_matrixtype == "1") {
			$Insert = 'call add_module_master("' . $_txt_location . '","' . $_txt_Process . '","' . $_txt_Module . '","' . $_txt_Level . '","' . trim($EmpID) . '","' . trim($EmpName) . '","' . trim($EmpID2) . '","' . trim($EmpName2) . '","' . $createBy . '")';

			$myDB = new MysqliDb();
			$myDB->rawQuery($Insert);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				$Insert = 'call add_module_master_new("' . $_txt_location . '","' . $_txt_Process . '","' . $_txt_Module . '","' . $_txt_Level . '","' . trim($EmpID) . '","' . trim($EmpName) . '","' . trim($EmpID2) . '","' . trim($EmpName2) . '","' . $_txt_matrixtype . '","' . $createBy . '","' . $EmployeeID . '")';

				$myDB = new MysqliDb();
				$myDB->rawQuery($Insert);
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {
					if ($myDB->count > 0) {
						echo "<script>$(function(){ toastr.success('Added Successfully'); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.error('Data not Added.'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Data not Added.'); }); </script>";
			}
		} else if ($_txt_matrixtype == "2") {
			$EmployeeID = substr(cleanUserInput($_POST['txt_empid2']), strpos(cleanUserInput($_POST['txt_empid2']), "(") + 1, (strpos(cleanUserInput($_POST['txt_empid2']), ")")) - (strpos(cleanUserInput($_POST['txt_empid2']), "(") + 1));
			$_txt_location = $_txt_Process = 0;
			$Insert = 'call add_module_master_new("' . $_txt_location . '","' . $_txt_Process . '","' . $_txt_Module . '","' . $_txt_Level . '","' . trim($EmpID) . '","' . trim($EmpName) . '","' . trim($EmpID2) . '","' . trim($EmpName2) . '","' . $_txt_matrixtype . '","' . $createBy . '","' . $EmployeeID . '")';

			$myDB = new MysqliDb();
			$myDB->rawQuery($Insert);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				if ($myDB->count > 0) {
					echo "<script>$(function(){ toastr.success('Added Successfully'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Data not Added.'); }); </script>";
			}
		} else if ($_txt_matrixtype == "3") {
			$btnUploadCheck = 1;
			$target_dir = ROOT_PATH . 'Upload/';
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$uploader = clean($_SESSION['__user_logid']);
			$FileType = pathinfo($target_file, PATHINFO_EXTENSION);

			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 5000000) {
				echo "<script>$(function(){ toastr.error('Sorry, your file is too large " . $_FILES["fileToUpload"]["size"] . " ') }); </script>";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if ($FileType != "xlsx") {
				echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed.') }); </script>";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.') }); </script>";
				// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					//echo "<script>$(function(){ toastr.error('The file ".basename( $_FILES["fileToUpload"]["name"])." has been uploaded') }); </script>";
					$document = PHPExcel_IOFactory::load($target_file);
					// Get the active sheet as an array
					$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);

					//print_r($activeSheetData.'<br/>');
					//echo "<script>$(function(){ toastr.info('Rows available In Sheet : ".(count($activeSheetData)-1)."') }); </script>";
					$row_counter = 0;
					$flag = 0;
					foreach ($activeSheetData as $row) {

						if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
							//echo 'call add_module_master_emp("Leave","'.$row['A'].'","'.$row['B'].'","'.$row['C'].'","'.$row['D'].'","'.$row['E'].'","'.$row['F'].'","'.$createBy.'")'; echo '<br/>';
							$myDB = new MysqliDb();
							$flag = $myDB->query('call add_module_master_emp("Leave","' . $row['A'] . '","' . $row['B'] . '","' . $row['C'] . '","' . $row['D'] . '","' . $row['E'] . '","' . $row['F'] . '","' . $createBy . '")');
							$mysql_error = $myDB->getLastError();
							if ($flag != 0) {
								$count++;
							}
						}

						$row_counter++;
					}

					if ($count > 0)
						echo "<script>$(function(){ toastr.success('Total " . $count . " Record are Updated Sucessfully.') }); </script>";
					else
						echo "<script>$(function(){ toastr.error('No Data Updated " . $mysql_error . " ') }); </script>";
				}
			}
		}
	}
}


?>

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

	#data-container1 {
		display: block;
		background: #2a3f54;

		max-height: 250px;
		overflow-y: auto;
		z-index: 9999999;
		position: absolute;
		width: 100%;

	}

	#data-container1 li {
		list-style: none;
		padding: 5px;
		border-bottom: 1px solid #fff;
		color: #fff;
	}

	#data-container1 li:hover {
		background: #26b99a;
		cursor: pointer;
	}


	#data-container2 {
		display: block;
		background: #2a3f54;

		max-height: 250px;
		overflow-y: auto;
		z-index: 9999999;
		position: absolute;
		width: 100%;

	}

	#data-container2 li {
		list-style: none;
		padding: 5px;
		border-bottom: 1px solid #fff;
		color: #fff;
	}

	#data-container2 li:hover {
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
			buttons: [
				'pageLength'
			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
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
	<span id="PageTittle_span" class="hidden">Manage Module Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Module Master<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="../Sample Format/Module_Master.xlsx" data-position="bottom" data-tooltip="Download Sample Format"><i class="material-icons">file_download</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="empt">

					<div class="input-field col s6 m6">
						<select id="txt_matrixtype" name="txt_matrixtype" required>
							<option value="NA">---Select---</option>
							<option value="1">Process Wise</option>
							<option value="2">Employee ID</option>
							<option value="3">Upload For EmployeeID</option>
						</select>
						<label for="txt_matrixtype" class="active-drop-down active">Type</label>
					</div>
					<!--<div class="input-field col s6 m6">
		      <input type="radio" id="rdprocess" name="emptype" value="s"/>
		      <label for="rdprocess">For Process</label>
		      
		    </div>
			<div class="input-field col s6 m6">
		      <input type="radio" id="rdemp" name="emptype" value="m"/>
		      <label for="rdemp">For Employee</label>
		    </div>-->
				</div>

				<div id="divBody">

					<div class="input-field col s12 m12" id="divProcess">
						<div class="input-field col s4 m4">
							<select id="txt_location" name="txt_location">
								<option value="NA">----Select----</option>
								<?php
								$sqlBy = 'SELECT id,location from location_master;';
								// $myDB = new MysqliDb();
								// $resultBy = $myDB->rawQuery($sqlBy);
								$stmt = $conn->prepare($sqlBy);
								$stmt->execute();
								$resultBy = $stmt->get_result();
								$count = $resultBy->num_rows;
								if ($resultBy->num_rows > 0) {

									$mysql_error = $myDB->getLastError();
									// if (empty($mysql_error)) {
									foreach ($resultBy as $key => $value) {
										echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
									}
								}
								?>
							</select>
							<label for="txt_location" class="active-drop-down active">Location</label>
						</div>


						<div class="input-field col s8 m8">
							<select id="txt_Process" name="txt_Process">

							</select>
							<label for="txt_Process" class="active-drop-down active">Process</label>
						</div>

					</div>

					<div class="input-field col s12 m12" id="divemp">

						<div class="input-field col s6 m6">
							<input type="text" id="txt_empid2" name="txt_empid2" />
							<label for="txt_empid2">Search Employee</label>
							<div id="data-container2"></div>
						</div>

					</div>

					<div class="input-field col s12 m12" id="divModule">
						<div class="input-field col s4 m4">
							<select id="txt_Module" name="txt_Module">
								<option value="NA">---Select---</option>
								<!--<option value="Leave" >Leave</option>-->
								<option value="Exception">Exception</option>
							</select>
							<label for="txt_Module" class="active-drop-down active">Module</label>
						</div>

						<div class="input-field col s4 m4">
							<select id="txt_Level" name="txt_Level">
								<option value="NA">---Select---</option>
								<option value="1">One Level</option>
								<option value="2">Two Level</option>
							</select>
							<label for="txt_Level" class="active-drop-down active">Level</label>
						</div>

					</div>

					<div class="col s12 m12" id="divlvlemp">

						<div class="input-field col s6 m6">
							<input type="text" id="txt_empid" name="txt_empid" />
							<label for="txt_empid">Search Employee</label>
							<div id="data-container"></div>
						</div>


						<div class="input-field col s6 m6" id="divlvl2emp">
							<input type="text" id="txt_empid1" name="txt_empid1" />
							<label for="txt_empid1">Search Employee</label>
							<div id="data-container1"></div>
						</div>
						<div id="bom_list1" class="col-md-8 col-sm-8 col-xs-12">

						</div>

					</div>

					<div class="file-field input-field col s6 m6" id="divUpload">
						<div class="btn">
							<span>Upload File</span>
							<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;">
							<br>
							<span class="file-size-text">Accepts up to 2MB</span>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path" type="text" style="">
						</div>
					</div>

					<input type='hidden' name='id' id="mid" value='<?php echo $id; ?>'>
					<input type='hidden' name='hidden_processid' id="hidden_processid">
					<input type='hidden' name='hidden_reportid' id="hidden_reportid">
					<div class="input-field col s12 m12 right-align">
						<br /> <br /> <br />
						<button type="submit" name="addMaster" id="addMaster" class="btn waves-effect waves-green">Submit</button>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
	$(document).ready(function() {

		$('#divBody').hide();
		$('#divlvlemp').hide();
		$('#divlvl2emp').hide();
		$('#divemp').hide();
		$('#divProcess').hide();

		$('#txt_matrixtype').change(function() {
			var val = $(this).val();
			if (val == "1") {
				$('#divBody').show();
				$('#divProcess').show();
				$('#divemp').hide();
				$('#divModule').show();
				$('#divUpload').hide();
				$('#txt_location').val('NA');
				$('#txt_Process').empty();
			} else if (val == "2") {
				$('#divBody').show();

				$('#txt_location').val('NA');
				$('#txt_Process').empty();
				$('#divProcess').hide();
				$('#divemp').show();
				$('#divModule').show();
				$('#divUpload').hide();
			} else if (val == "3") {
				$('#divBody').show();
				$('#divModule').hide();
				$('#divUpload').show();
				$('#txt_location').val('NA');
				$('#txt_Process').empty();
				$('#divProcess').hide();
				$('#divemp').hide();
				$('#divModule').hide();
			} else {
				$('#divBody').hide();
				$('#divlvlemp').hide();
				$('#divlvl2emp').hide();
				$('#divemp').hide();
				$('#divProcess').hide();
				$('#divUpload').hide();
			}
		});


		$('#txt_Level').change(function() {
			var val = $(this).val();
			if (val == 1) {
				$('#divlvlemp').show();
				$('#divlvl2emp').hide();
			} else if (val == 2) {
				$('#divlvlemp').show();
				$('#divlvl2emp').show();
			} else {
				$('#divlvlemp').hide();
				$('#divlvl2emp').hide();
			}
		});

		$('#addMaster').click(function() {
			var validate = 0;
			if ($("#txt_matrixtype").val() == "1") {
				if ($('#txt_location').val() == 'NA') {
					validate = 1;
					$('#txt_location').addClass('has-error');
					//$('#txtID').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					if ($('#spantxt_location').size() == 0) {
						$('<span id="spantxt_location" class="help-block">Required *</span>').insertAfter('#txt_location');
					}
				}

				if ($('#txt_Process').val() == 'NA' || $('#txt_Process').val() == '') {
					validate = 1;
					$('#txt_Process').addClass('has-error');
					//$('#txtID').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					if ($('#spantxt_Process').size() == 0) {
						$('<span id="spantxt_Process" class="help-block">Required *</span>').insertAfter('#txt_Process');
					}
				}
			}

			if ($("#txt_matrixtype").val() == "2") {
				if ($('#txt_empid2').val() == '') {
					validate = 1;
					$('#txt_empid2').addClass('has-error');
					//$('#txtID').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					if ($('#spantxt_empid2').size() == 0) {
						$('<span id="spantxt_empid2" class="help-block">Required *</span>').insertAfter('#txt_empid2');
					}
				}
			}

			if ($("#txt_matrixtype").val() == "3") {
				if ($('#fileToUpload').val() == '') {
					validate = 1;

					$('#fileToUpload').addClass('has-error');
					//$('#txtID').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					if ($('#spanfileToUpload').size() == 0) {
						$('<span id="spanfileToUpload" class="help-block">Required *</span>').insertAfter('#fileToUpload');
					}
				}

			}

			if ($("#txt_matrixtype").val() != "3") {


				if ($('#txt_Module').val() == 'NA') {
					validate = 1;
					$('#txt_Module').addClass('has-error');
					//$('#txtID').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					if ($('#spantxt_Module').size() == 0) {
						$('<span id="spantxt_Module" class="help-block">Required *</span>').insertAfter('#txt_Module');
					}
				}

				if ($('#txt_Level').val() == 'NA') {
					validate = 1;
					$('#txt_Level').addClass('has-error');
					//$('#txtID').parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					if ($('#spantxt_Level').size() == 0) {
						$('<span id="spantxt_Level" class="help-block">Required *</span>').insertAfter('#txt_Level');
					}
				}

				if ($('#txt_empid').val() == '') {
					validate = 1;
					$('#txt_empid').addClass('has-error');

					if ($('#spantxt_empid').size() == 0) {
						$('<span id="spantxt_empid" class="help-block">Required *</span>').insertAfter('#txt_empid');
					}
				}

			}

			if ($('#txt_Level').val() == '2') {
				if ($('#txt_empid1').val() == '') {
					validate = 1;
					$('#txt_empid1').addClass('has-error');

					if ($('#spantxt_empid1').size() == 0) {
						$('<span id="spantxt_empid1" class="help-block">Required *</span>').insertAfter('#txt_empid1');
					}
				}
			}

			if (validate == 1) {
				return false;
			}



		});

		$('#txt_location').change(function() {
			var val = $(this).val();
			$('#txt_Process').empty();
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByLocation.php?id=" + val
			}).done(function(data) { // data what is sent back by the php page
				$('#txt_Process').html(data);
				$('select').formSelect();
			});

		});

		$('#txt_empid').keyup(function() {
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

		$('#txt_empid1').keyup(function() {
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
						resp_data_format = resp_data_format + "<li class='select_country1'>" + response[i] + "</li>";
					};
					$("#data-container1").html(resp_data_format);
				}
			});
		});

		$('#txt_empid2').keyup(function() {
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
						resp_data_format = resp_data_format + "<li class='select_country2'>" + response[i] + "</li>";
					};
					$("#data-container2").html(resp_data_format);
				}
			});
		});

		$(document).on("click", ".select_country", function() {
			var selected_country = $(this).html();
			$('#txt_empid').val(selected_country);
			$('#data-container').html('');

		});

		$(document).on("click", ".select_country1", function() {
			var selected_country = $(this).html();
			$('#txt_empid1').val(selected_country);
			$('#data-container1').html('');

		});


		$(document).on("click", ".select_country2", function() {
			var selected_country = $(this).html();
			$('#txt_empid2').val(selected_country);
			$('#data-container2').html('');

		});

	});
</script>