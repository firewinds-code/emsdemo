<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Only for user type administrator
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	} else {
		if ($_SESSION['__user_logid'] == '' || $_SESSION['__user_logid'] == null) {
			echo '<a href="' . URL . 'Login" >Go To Login </a>';
			exit();
		} else if (!($_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE03070003' || $_SESSION['__user_logid'] == 'CE01145570' || $_SESSION['__user_logid'] == 'CE0820912500' || $_SESSION['__user_logid'] == 'MU03200198' || $_SESSION['__user_logid'] == 'CE04159316' || $_SESSION['__user_logid'] == 'CEK07120002' || $_SESSION['__user_logid'] == 'CFK08190181' || $_SESSION['__user_logid'] == 'CMK11171884' || $_SESSION['__user_logid'] == 'CEV102073966' || $_SESSION['__user_logid'] == 'CMK052277987' || $_SESSION['__user_logid'] == 'CEK031925550' || $_SESSION['__user_logid'] == 'MU01221218' || $_SESSION['__user_logid'] == 'CMK092279225' || $_SESSION['__user_logid'] == 'CEV072176972' || $_SESSION['__user_logid'] == 'CEB112112076' || $_SESSION['__user_logid'] == 'CEV042382253' || $_SESSION['__user_logid'] == 'CEK052385371' || $_SESSION['__user_logid'] == 'CEK092281591' || $_SESSION['__user_logid'] == 'CEG08230001')) {
			die("access denied ! It seems like you try for a wrong action.");
			exit();
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
// Global variable used in Page Cycle
$alert_msg = '';

//print_r($_POST);
//Array ( [txt_ref_Type] => 1 [txt_empmap_client] => 1 [txt_empmap_process] => Administration [txt_empmap_subprocess] => Administration [txt_payout] => 3433 [txt_tenure] => 23 [hid_ref_ID] => [btn_ref_Save] => )

// Trigger Button-Edit Click Event and Perform DB Action
if (isset($_POST['btn_ref_Edit'])) {
	//die;
	$DataID = $_POST['hid_ref_ID'];
	$ConsultID = $_POST['hid_consultID'];
	$locID = $_POST['hid_locid'];
	$createdon = $_POST['hid_createdon'];
	$hid_payout = trim((isset($_POST['hid_payout']) ? $_POST['hid_payout'] : null));
	$hid_tenaure = trim((isset($_POST['hid_tenaure']) ? $_POST['hid_tenaure'] : null));

	$txt_empmap_subprocess = "0";
	$filename = "";
	$txt_name = (isset($_POST['txt_name']) ? $_POST['txt_name'] : null);
	$txt_mobile = (isset($_POST['txt_mobile']) ? $_POST['txt_mobile'] : null);
	$txt_email = (isset($_POST['txt_email']) ? $_POST['txt_email'] : null);
	$txt_status = (isset($_POST['txt_status']) ? $_POST['txt_status'] : null);
	$txt_remarks = (isset($_POST['txt_remarks']) ? $_POST['txt_remarks'] : null);
	//$txt_status=(isset($_POST['txt_status'])? $_POST['txt_status'] : null);
	$createBy = $_SESSION['__user_logid'];

	$ModifiedBy = $_SESSION['__user_logid'];
	$validate = 0;

	if ($txt_status == "1") {
		$txt_empmap_subprocess = (isset($_POST['txt_empmap_subprocess']) ? $_POST['txt_empmap_subprocess'] : null);
		$sourcePath = $_FILES['fileToUpload']['tmp_name'];
		$filePath = ROOT_PATH . "Consultancy/";
		$targetPath = $filePath . basename($_FILES['fileToUpload']['name']);
		$FileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

		if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf" && $FileType != "msg") {
			echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg,png,msg and pdf files are allowed.'); }); </script>";
			$uploadOk = 0;
			$validate = 1;
		} else if ($_FILES["fileToUpload"]["size"] > 5000000) {
			echo "<script>$(function(){ toastr.error('Sorry, your file is too large " . $_FILES["fileToUpload"]["size"] . " ') }); </script>";
			$uploadOk = 0;
			$validate = 1;
		} else {
			if (move_uploaded_file($sourcePath, $targetPath)) {
				$ext = pathinfo(basename($_FILES['fileToUpload']['name']), PATHINFO_EXTENSION);
				$filename = $txt_mobile . '_' . date("mdY_s") . '.' . $ext;
				$file = rename($targetPath, $filePath . $filename);
			}
			if (file_exists($filePath . $filename)) {
				//$validate = 1;
			} else {
				echo "<script>$(function(){ toastr.error('Sorry, your file is not uploaded') }); </script>";
				$validate = 1;
			}
		}
	}

	if ($validate == "0") {
		$Update = 'update consultancy_data set flag =1 where  id = "' . $DataID . '" ';
		$myDB = new MysqliDb();
		$result = $myDB->rawQuery($Update);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			$Update = 'insert into consultancy_candidate_status (consult_id,location,cm_id,candidate_name,mobile,candidate_email,`status`,mail_attached,remarks,createdby,mis_uploaddate,payout,tenuare) values("' . $ConsultID . '","' . $locID . '","' . $txt_empmap_subprocess . '","' . $txt_name . '","' . $txt_mobile . '","' . $txt_email . '","' . $txt_status . '","' . $filename . '","' . $txt_remarks . '","' . $ModifiedBy . '","' . $createdon . '","' . $hid_payout . '","' . $hid_tenaure . '") ';
			$myDB = new MysqliDb();
			$result = $myDB->rawQuery($Update);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Record has updated.... ') }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Sorry, Record not updated.... " . $mysql_error . " ') }); </script>";
			}
		}
	}
	//$Update='call save_manage_consultancy("'.$consultancy_id.'","'.$txt_empmap_subprocess.'","'.$txt_payout.'","'.$txt_tenure.'","'.$ModifiedBy.'","'.$DataID.'","'.$txt_location.'","'.$txt_startdate.'","'.$txt_enddate.'","'.$txt_status.'")';


	/*$myDB=new MysqliDb();
	if(!empty($DataID)||$DataID!='')
	{
		$result=$myDB->rawQuery($Update);
			$rowCount = $myDB->count;
		$mysql_error = $myDB->getLastError();
		
		if($rowCount > 0 )
		{
			echo "<script>$(function(){ toastr.success('Updated Successfully.'); }); </script>";
			$_Comp=$_Hod=$_Name='';
			$_Hod="NA";
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Data already exists'); }); </script>";
		}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First.If Not Resolved then contact to technical person.'); }); </script>";
	}*/
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
				}

				, 'pageLength'

			]

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
	<span id="PageTittle_span" class="hidden">Manage Consultancy Candidate</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Consultancy Candidate</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Manage Consultancy Candidate</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
							<div class="col s12 m12">

								<div class="input-field col s6 m6 ">
									<input type="text" class="form-control" id="txt_consultname" name="txt_consultname" readonly="true" required />
									<label for="txt_consultname">Consultancy Name</label>

								</div>

								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_location" name="txt_location" readonly="true" required />
									<label for="txt_location">Location</label>
								</div>

								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_name" name="txt_name" readonly="true" required />
									<label for="txt_name">Candidate Name</label>
								</div>

								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_mobile" name="txt_mobile" readonly="true" required />
									<label for="txt_mobile">Mobile</label>
								</div>

								<div class="input-field col s6 m6">
									<input type="text" class="form-control" id="txt_email" name="txt_email" readonly="true" required />
									<label for="txt_email">Email ID</label>
								</div>

								<div class="input-field col s6 m6">

									<select id="txt_status" name="txt_status" required>
										<option value="NA">--Select Status---</option>
										<option value="1">Selected</option>
										<option value="0">Rejected</option>

									</select>
									<label for="txt_status" class="active-drop-down active">Status * </label>

								</div>

								<div id="divselect">
									<div class="input-field col s6 m6">

										<select id="txt_empmap_subprocess" name="txt_empmap_subprocess" required>


										</select>
										<label for="txt_empmap_subprocess" class="active-drop-down active">Process * </label>

									</div>



									<div class="file-field input-field col s6 m6">
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

								</div>

								<div class="input-field col s12 m12">
									<input type="text" class="form-control" id="txt_remarks" name="txt_remarks" required />
									<label for="txt_remarks">Remarks *</label>
								</div>


							</div>
							<div class="input-field col s12 m12 right-align">
								<input type="hidden" class="form-control hidden" id="hid_ref_ID" name="hid_ref_ID" />
								<input type="hidden" class="form-control hidden" id="hid_consultID" name="hid_consultID" />
								<input type="hidden" class="form-control hidden" id="hid_locid" name="hid_locid" />
								<input type="hidden" class="form-control hidden" id="hid_createdon" name="hid_createdon" />
								<input type="hidden" class="form-control hidden" id="hid_payout" name="hid_payout" />
								<input type="hidden" class="form-control hidden" id="hid_tenaure" name="hid_tenaure" />
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

					$sqlConnect = 'select t1.id,consult_name,t3.id as `consultid`,t1.location as locid,t2.location,mobile,candidate_name,candidate_email,t1.createdon from consultancy_data t1 join location_master t2 on t1.location=t2.id join consultancy_master t3 on t1.consult_name = t3.ConsultancyName where cast(t1.createdon as date) >= date_sub(cast(now() as date), interval 15 day) and t1.flag=0 and t1.location="' . $_SESSION["__location"] . '";';
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					if ($result) { ?>

						<div class="panel panel-default" style="margin-top: 10px;">
							<div class="panel-body">
								<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
									<thead>
										<tr>

											<th class="hidden">ID</th>
											<th class="hidden">locid</th>
											<th class="hidden">consultid</th>
											<th>createdon</th>
											<th>Consultancy Name</th>
											<th>Candidate Name</th>
											<th>Mobile</th>
											<th>Email ID</th>
											<th>Location</th>

											<th>Action</th>


										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										foreach ($result as $key => $value) {
											$count++;
											echo '<tr>';
											echo '<td class="id hidden">' . $value['id'] . '</td>';
											echo '<td class="locid hidden">' . $value['locid'] . '</td>';
											echo '<td class="consultid hidden">' . $value['consultid'] . '</td>';
											echo '<td class="createdon">' . $value['createdon'] . '</td>';
											echo '<td class="cname" id="' . $value['consult_name'] . '">' . $value['consult_name'] . '</td>';
											echo '<td class="cand_name" id="' . $value['candidate_name'] . '">' . $value['candidate_name'] . '</td>';
											echo '<td class="mobile">' . $value['mobile'] . '</td>';
											echo '<td class="candidate_email">' . $value['candidate_email'] . '</td>';
											echo '<td class="location">' . $value['location'] . '</td>';

											echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';

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
			$('#txt_consultname').val('');
			$('#txt_location').val('');
			$('#txt_name').val('');
			$('#txt_status').val('NA');
			$('#txt_empmap_subprocess').empty();

			$('#txt_remarks').val('');
			$('#txt_remarks').removeClass('has-error');
			$('#spantxt_remarks').html('');
			$('select').formSelect();
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
		$('#btn_ref_Edit').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->

			if ($('#txt_status').val() == "NA") {
				$('#txt_status').addClass('has-error');
				validate = 1;
				if ($('#statusremark').size() == 0) {
					$('<span id="statusremark" class="help-block">*</span>').insertAfter('#txt_status');
				}
			}

			if ($('#txt_status').val() == "1") {
				if ($('#txt_empmap_subprocess').val() == "NA") {
					$('#txt_empmap_subprocess').addClass('has-error');
					validate = 1;
					if ($('#processremark1').size() == 0) {
						$('<span id="processremark1" class="help-block">*</span>').insertAfter('#txt_empmap_subprocess');
					}
				}

				if ($('#fileToUpload').val() == '') {
					$('#fileToUpload').addClass('has-error');
					validate = 1;
					if ($('#fileremarks').size() == 0) {
						$('<span id="fileremarks" class="help-block">Please select file first...</span>').insertAfter('#fileToUpload');
					}
				} else {

				}
			}



			if ($('#txt_remarks').val().length < 10) {
				$('#txt_remarks').addClass('has-error');
				validate = 1;
				if ($('#sremark1').size() == 0) {
					$('<span id="sremark1" class="help-block">Remark should be greater than 10 character.</span>').insertAfter('#txt_remarks');
				}
			} else {
				$('#txt_remarks').removeClass('has-error');
				$('#spantxt_remarks').html('');
			}



			if (validate == 1) {
				return false;
			}

		});

		$('#txt_location').change(function() {
			getProcess($(this).val());
		});

		$('#txt_status').change(function() {
			if ($(this).val() == "1") {
				$('#divselect').show();
			} else {
				$('#divselect').hide();
			}
		});

		$('#txt_empmap_subprocess').change(function() {
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/gettenure_pay.php?locid=" + $('#hid_locid').val() + "&conid=" + $('#hid_consultID').val() + "&cmid=" + $(this).val()
			}).done(function(data) { // data what is sent back by the php page
				var result = data.split('|$|');
				$('#hid_payout').val(result[0]);
				$('#hid_tenaure').val(result[1]);
			});
		});


	});
	// This code for trigger edit on all data table also trigger model open on a Model ID
	function EditData(el) {
		var tr = $(el).closest('tr');
		var id = tr.find('.id').text();
		//var locid = tr.find('.locid').attr('id');
		var locid = tr.find('.locid').text();
		var consultid = tr.find('.consultid').text();
		var createdon = tr.find('.createdon').text();
		var cname = tr.find('.cname').attr('id');
		var cand_name = tr.find('.cand_name').text();
		var mobile = tr.find('.mobile').text();
		var candidate_email = tr.find('.candidate_email').text();
		var location = tr.find('.location').text();

		$('#hid_ref_ID').val(id);
		$('#hid_consultID').val(consultid);
		$('#hid_locid').val(locid);
		$('#hid_createdon').val(createdon);
		$('#txt_consultname').val(cname);
		$('#txt_location').val(location);
		$('#txt_name').val(cand_name);
		$('#txt_mobile').val(mobile);
		$('#txt_email').val(candidate_email);
		$('#txt_status').val('NA');
		$('#txt_remarks').val('');
		$('#txt_empmap_subprocess').empty();

		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByConsultancy.php?locid=" + locid + "&conid=" + consultid
		}).done(function(data) { // data what is sent back by the php page
			$('#txt_empmap_subprocess').html(data);
			//$('#txt_empmap_subprocess').val(cmid);
			$('select').formSelect();
		});


		$('select').formSelect();

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

			xmlhttp.open("GET", "../Controller/deleteRef.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}

	function getProcess(elid) {
		//alert(elid);
		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByLocation.php?id=" + elid
		}).done(function(data) { // data what is sent back by the php page
			$('#txt_empmap_subprocess').html(data);
			$('select').formSelect();
		});
	}

	$('.datepicker').datepicker({
		dateFormat: 'yy-mm-dd'
	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>