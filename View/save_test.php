<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH . 'AppCode/nHead.php');
if (isset($_REQUEST['empid'])) {
	$EmployeeID = $_REQUEST['empid'];
}
$createBy = $_SESSION['__user_logid'];
$imsrc = URL . 'Style/images/agent-icon.png';
$EmployeeID = $btnShow = $dir_location = $dir = '';
$versant_array = array();
if (isset($_REQUEST['empid'])) {
	$EmployeeID = $_REQUEST['empid'];
	$url = URL . "Services/getVersant.php?empid=" . $EmployeeID;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	if ($output != "") {
		$versant_array = json_decode($output);
	}


	// close curl resource to free up system resources 
	curl_close($ch);

	$myDB = new MysqliDb();
	$EmployeeID = strtoupper($_REQUEST['empid']);
	$sql = 'select location from personal_details where EmployeeID = "' . $EmployeeID . '"';
	$result = $myDB->rawQuery($sql);
	$mysql_error = $myDB->getLastError();
	if (empty($mysql_error)) {
		$loc = $result[0]['location'];
	}
}


if ($loc == "1" || $loc == "2") {
	$dir_location = '';
} else if ($loc == "3") {
	$dir_location = 'Meerut/';
} else if ($loc == "4") {
	$dir_location = "Bareilly/";
} else if ($loc == "5") {
	$dir_location = "Vadodara/";
} else if ($loc == "6") {
	$dir_location = "Manglore/";
} else if ($loc == "7") {
	$dir_location = "Bangalore/";
} else if ($loc == "8") {
	$dir_location = "Nashik/";
} else if ($loc == "9") {
	$dir_location = "Anantapur/";
} else if ($loc == "10") {
	$dir_location = "Gurgaon/";
} else if ($loc == "11") {
	$dir_location = "Hyderabad/";
}
$target_dir = ROOT_PATH . $dir_location . 'TestDocs/';
$dir = $dir_location . 'TestDocs';

if (isset($_POST['testscore']) and $_POST['testscore'] != "" and $_POST['tfilename'] != "" and $_POST['testname'] != "") {
	if (isset($_FILES["File"]["name"]) and $_FILES["File"]["name"] != "") {

		$target_file = $target_dir . basename($_FILES["File"]["name"]);
		$FileType = pathinfo($target_file, PATHINFO_EXTENSION);
		$basef1 = $_REQUEST['empid'] . $_POST['tfilename'] . '.' . $FileType;



		if (move_uploaded_file($_FILES["File"]["tmp_name"], $target_file)) {
			$ext = pathinfo(basename($_FILES["File"]["name"]), PATHINFO_EXTENSION);
			$file = rename($target_file, $target_dir . $basef1);
		}
		$sqlTest = "select ID from test_score where EmpID = '" . $_REQUEST['empid'] . "' and testid='" . addslashes($_POST['testid']) . "' ";
		$myDB =  new MysqliDb();
		$sqlTest_result = $myDB->rawQuery($sqlTest);
		if ($sqlTest_result) {
			echo "<script>$(function(){ toastr.warning('Test ID already exists !!! '); }); </script>";
		} else {
			$Query = "call insert_testscore ('" . $_REQUEST['empid'] . "','" . $_POST['testname'] . "','" . addslashes($_POST['testid']) . "','" . $_POST['testscore'] . "','" . $basef1 . "','" . $createBy . "')";

			$msg1 =  $Query;
			$myDB =  new MysqliDb();
			$Results = $myDB->rawQuery($Query);
			$mysql_error = $myDB->getLastError();
			echo "<script>$(function(){ toastr.success('Test Record Save Successfully '); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.warning('Test Record Not Save'); }); </script>";
	}
}





?>

<script>
	$(document).ready(function() {
		var usrtype = <?php echo "'" . $_SESSION["__user_type"] . "'"; ?>;
		var userlogid = <?php echo "'" . $_SESSION["__user_logid"] . "'"; ?>;
		if (usrtype === 'ADMINISTRATOR' || usrtype === 'HR' || userlogid == 'CE01145570' || userlogid == 'CE12102224') {} else if (usrtype === 'AUDIT') {
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('button:not(.drawer-toggle)').remove();

			$('.imgbtnEdit').remove();
			$('.imgBtnUploadDelete').remove();

		} else if (usrtype === 'CENTRAL MIS') {

			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
		} else {
			$('input,button:not(.drawer-toggle),select,textarea').remove();
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
			window.location = <?php echo '"' . URL . '/undefined"'; ?>;
		}
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Test Document</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>

		<!-- Sub Main Div for all Page -->

		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Test Details</h4>


			<div class="schema-form-section row">
				<form method="POST" action="<?php echo URL . 'View/save_test.php'; ?>">
					<h4 class="input-field col s12 m12 center-align">Add Test Score</h4>
					<!--<div class="col s3">
			
			<input type="text" name="emp_id" id="emp_id" class="input-field col s12 m12 l6" placeholder="Employee Id">
			
		</div>-->
					<div class="col s3">
						<select class="input-field col s12 m12 l6" id="testname" name="testname" required>
							<option value="">Select Test Name</option>
							<?php
							$sql = 'select distinct cert_name,filename from certification_require_by_cmid order by cert_name';
							$result = $myDB->rawQuery($sql);
							$mysql_error = $myDB->getLastError();
							if (count($result) > 0) {
								foreach ($result as $val) {
							?>
									<option value="<?php echo $val['cert_name']; ?>" id="<?php echo $val['filename']; ?>"><?php echo $val['cert_name']; ?></option>
							<?php
								}
							} else {
								echo "<option value='' selected='true'>Not Required</option>";
							}
							?>

						</select>

					</div>

					<div class="col s3">
						<input type="hidden" name="tfilename" id="tfilename">
						<input type="hidden" name="location" id="location" value="<?php echo $loc; ?>" />
						<input type="hidden" name="dir" id="dir" value="<?php echo $dir; ?>" />
						<input type="text" name="testid" id="testid" class="input-field col s12 m12 l6" placeholder="Test ID" required>

					</div>
					<div class="col s3">

						<input type="text" name="testscore" id="testscore" class="input-field col s12 m12 l6" placeholder="Test Score" onkeypress="JavaScript:return isNumber(event)" required maxlength="5">

					</div>
					<div class="col s3">
						<span><label>Upload</label></span>
						<input type="file" name="File" id="File" class="input-field col s12 m12 l6" style="padding-top: 0px;" required>


					</div>
					<div class="input-field col s12 m12 right-align">
						<button type="submit" name="btn_ED_Search" title="Save" id="btn_ED_Search" class="btn waves-effect waves-green">Save</button>

					</div>
				</form>

			</div>
			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				$sqlConnect = "select * from test_score where EmpID='" . $EmployeeID . "' ";
				$myDB = new MysqliDb();
				$result = $myDB->query($sqlConnect);
				if ($result) {
				?>
					<table id="myTable1" class="data dataTable no-footer" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>EmployeeID</th>
								<th>Test Name</th>
								<th>Test ID </th>
								<th>Test Score</th>
								<th>Download File</th>
								<?php if ($createBy == 'CE01145570') { ?>
									<th>Delete</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($result as $key => $value) {
								echo '<tr>';
								echo '<td class="EmpID">' . $value['EmpID'] . '</td>';
								echo '<td class="test_name">' . $value['test_name'] . '</td>';
								echo '<td class="testid">' . $value['testid'] . '</td>';
								echo '<td class="doc_value">' . $value['test_score'] . '</td>';
								echo '<td class="manage_item" style="text-align:center">Download file<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return Download(this);"  data="' . $value['file'] . '" id="' . $value['ID'] . '"  data-tooltip="' . $value['file'] . '">ohrm_file_download</i></td>'; ?>

								<?php if ($createBy == 'CE01145570') { ?>
									<td class="manage_item"><i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" data-position="left" data-tooltip="Delete" onclick="javascript:return delete_data(this);" data-item="<?php echo $value['ID']; ?>">ohrm_delete</i></td>
								<?php } ?>
							<?php echo '</tr>';
							} ?>

						</tbody>
					</table>
				<?php
				}
				?>

			</div>
			<script>
				$(document).ready(function() {
					validate = 0;
					$('input[type="text"]').click(function() {
						$(this).removeClass('has-error');
					});
					$('select').click(function() {
						$(this).removeClass('has-error');
					});
					$('#btn_ED_Search').on('click', function() {
						var validate = 0;
						var alert_msg = '';
						// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
						$("input,select,textarea").each(function() {
							var spanID = "span" + $(this).attr('id');
							console.log($(this).attr('id'));
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
								/*if(!(typeof attr_error !== typeof undefined && attr_error !== false))
						    {
								//$('#'+spanID).html('Required *');	
							}
							else
							{
								$('#'+spanID).html($(this).attr("data-error-msg"));
							}*/
							}
						})

						if (validate == 1) {

							return false;
						}
					});
					$('#testname').change(function() {
						var dataid = $('#testname').find(':selected').attr('id');
						$('#tfilename').val(dataid);

					})
				});

				function Download(el) {
					var file = $(el).attr("data");
					var filepath = $('#dir').val(); //TestDocs";
					//alert($('#dir').val());
					//dir
					// filepath = <?php echo ROOT_PATH; ?>.'TestDocs/';
					if (file != '') {
						function getImageDimensions(path, callback) {
							var img = new Image();
							img.onload = function() {
								callback({
									width: img.width,
									height: img.height,
									srcsrc: img.src
								});
							}
							img.src = path;
						}
						$.ajax({
							url: "../" + filepath + "/" + file,
							type: 'HEAD',
							error: function() {
								alert('No File Exist');
							},
							success: function() {
								imgcheck = function(filename) {
									return (filename).split('.').pop();
								}
								imgchecker = imgcheck("../" + filepath + "/" + file);

								if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
									getImageDimensions("../" + filepath + "/" + file, function(data) {
										var img = data;

										$('<img>', {
											src: "../" + filepath + "/" + file
										}).watermark({
											text: 'Cogent E Services Pvt. Ltd.',
											textWidth: 370,
											opacity: 1,
											textSize: (img.height / 15),
											nH: img.height,
											nW: img.width,
											textColor: "rgb(0,0,0,0.4)",
											outputType: 'jpeg',
											gravity: 'sw',
											done: function(imgURL) {
												var link = document.createElement('a');
												link.href = imgURL;
												link.download = file;
												document.body.appendChild(link);
												link.click();

											}
										});

									});
								}
								// else if (imgchecker.match(/(pdf)$/i)) {
								// 	window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src=" + "../../" + filepath + "/" + file);
								// } 
								else {
									window.open("../" + filepath + "/" + file);
								}

							}
						});

					} else {
						alert('No File Exist');
					}
				}

				function delete_data(el) {
					if (confirm('Are you sure want to delete??')) {
						$item = $(el);
						$.ajax({
							url: "../Controller/delete_save_testData.php?id=" + $(el).attr('data-item'),
							success: function(result) {
								var data = result.split('|');
								toastr.success(data[1]);
								if (data[0] == 'Done') {
									$item.closest('td').parent('tr').remove();
								}
							}
						});
					}
				}
			</script>

			<script>
				function isNumber(evt) {
					evt = (evt) ? evt : window.event;
					var charCode = (evt.which) ? evt.which : evt.keyCode;
					if (charCode > 31 && (charCode < 48 || charCode > 57)) {
						return false;
					}
					return true;
				}

				function Check(e) {

					var keyCode = (e.keyCode ? e.keyCode : e.which);
					if (keyCode > 47 && keyCode < 58) {
						e.preventDefault();
					}
				}
			</script>
		</div>
		<!--Form container End -->
	</div>
	<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>