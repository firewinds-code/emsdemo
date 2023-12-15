<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if ($_SESSION["__user_logid"] == "CE10091236" || $_SESSION["__user_logid"] == "CMK071600007" || $_SESSION["__user_logid"] == "CFK08190181" || $_SESSION["__user_logid"] == "CE011929747" || $_SESSION["__user_logid"] == "CE12102224" || $_SESSION["__user_logid"] == "CE0321936918" || $_SESSION["__user_logid"] == "CEV102073966" || $_SESSION["__user_logid"] == "CE01145570" || $_SESSION["__ut_temp_check"] == 'ADMINISTRATOR') {
} else {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}

if ($_POST['txt_dateTo']) {
	$date_To = cleanUserInput($_POST['txt_dateTo']);
	$date_From = cleanUserInput($_POST['txt_dateFrom']);
} else {
	$date_To = date('Y-m-d', time());
	$date_From = date('Y-m-d', time());
}
if (isset($_POST['statusFld'])) {
	$statusFldCheck = cleanUserInput($_POST['statusFld']);
} else {
	$statusFldCheck = '';
}
/* if (isset($_POST['btn_save'])) {
	
	$id = $_POST['hidID'];
	
	$sql1 = "insert into referral_dispo(ref_id,disposition,remarks,created_by)values('" . $id . "','" . $_POST['disposition'] . "','" . $_POST['remarks'] . "','" . $_SESSION['__user_logid'] . "')";
	$myDB = new MysqliDb();
	$res = $myDB->rawQuery($sql1);
	$mysql_error = $myDB->getLastError();
	if (empty($mysql_error)) {
		
		echo "<script>$(function(){toastr.success('Added Successfully')})</script>";
		
		
	} else {
		
		echo "<script>$(function(){toastr.error('Not Added')})</script>";
	}
} */
?>
<style>
	/* English popup CSS */
	#fade {
		display: none;
		position: relative;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: black;
		z-index: 1001;
		-moz-opacity: 0.8;
		opacity: .80;
		filter: alpha(opacity=80);
	}

	#light {
		display: none;
		position: relative;
		top: 50%;
		left: 50%;
		/* max-width: 600px;
		max-height: 360px; */
		margin-left: -300px;
		/* margin-top: -180px; */
		/* border: 2px solid #FFF; */
		background: #FFF;
		z-index: 1002;
		overflow: visible;
	}

	#boxclose {

		cursor: pointer;
		color: #fff;
		border: 1px solid #AEAEAE;
		border-radius: 3px;
		background: #222222;
		font-size: 31px;
		font-weight: bold;
		display: inline-block;
		line-height: 0px;
		padding: 11px 3px;
		position: absolute;
		right: 2px;
		top: 2px;
		z-index: 1002;
		opacity: 0.9;
	}

	.boxclose:before {
		content: "×";
	}

	#fade:hover~#boxclose {
		display: none;
	}

	/* English popup CSS */
	/* Hindi popup CSS */
	#fade1 {
		display: none;
		position: relative;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: black;
		z-index: 1001;
		-moz-opacity: 0.8;
		opacity: .80;
		filter: alpha(opacity=80);
	}

	#light1 {
		display: none;
		position: relative;
		top: 50%;
		left: 50%;
		/* max-width: 600px;
		max-height: 360px; */
		margin-left: -300px;
		/* margin-top: -180px; */
		/* border: 2px solid #FFF; */
		background: #FFF;
		z-index: 1002;
		overflow: visible;
	}

	#boxclose1 {

		cursor: pointer;
		color: #fff;
		border: 1px solid #AEAEAE;
		border-radius: 3px;
		background: #222222;
		font-size: 31px;
		font-weight: bold;
		display: inline-block;
		line-height: 0px;
		padding: 11px 3px;
		position: absolute;
		right: 2px;
		top: 2px;
		z-index: 1002;
		opacity: 0.9;
	}

	.boxclose1:before {
		content: "×";
	}

	#fade1:hover~#boxclose1 {
		display: none;
	}

	/* Hindi popup CSS */
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Reference Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Reference Report </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<script>
					//contain load event for data table and other importent rand required trigger event and searches if any
					$(document).ready(function() {
						$('#myTable').DataTable({
							dom: 'Bfrtip',
							"order": [
								[0, 'desc']
							],
							scrollX: '100%',
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
									title: 'reference_list'
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

				<div class="input-field col s12 m12" id="rpt_container">

					<div class="input-field col s4 m4">
						<input type="text" class="form-control" name="txt_dateFrom" style="min-width: 225px;" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>

					<div class="input-field col s4 m4">
						<input type="text" class="form-control" name="txt_dateTo" style="min-width: 225px;" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s4 m4">
						<select id="statusFld" name="statusFld" class="form-control">
							<option value="">Status</option>
							<!-- <option value="Pending">Pending</option> -->
							<option value="All" <?php echo $statusFldCheck == "All" ? "selected" : "" ?>>All</option>
							<option value="Selected" <?php echo $statusFldCheck == "Selected" ? "selected" : "" ?>>Selected</option>
							<option value="Rejected" <?php echo $statusFldCheck == "Rejected" ? "selected" : "" ?>>Rejected</option>
							<option value="NotContacted" <?php echo $statusFldCheck == "NotContacted" ? "selected" : "" ?>>Not Contacted</option>

						</select>
					</div>

					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
							<i class="fa fa-search"></i> Search</button>
					</div>
				</div>
				<!--Add model popup start-->
				<!--<div id="myModal_content" class="modal">  -->
				<div id="myModal_content" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-sm">
						<!-- Modal content-->
						<div class="modal-content">
							<h4 class="col s12 m12 model-h4">Referrance</h4>
							<div class="modal-body">

								<input type="hidden" name="hidID" id="hidID">

								<div class=" input-field col s6 m6">
									<select id="disposition" name="disposition">
										<option value="NA">Select</option>
										<!-- <option value="Pending">Pending</option> -->
										<option value="Selected">Selected</option>
										<option value="Rejected">Rejected</option>
										<option value="NotContacted">Not Contacted</option>
									</select>
									<label for="reviewerStatus" class="dropdown-active active">Disposition:</label>
								</div>

								<div class="input-field col s12 m12 l12">
									<input type="text" name="remarks" id="remarks" maxlength="200" />
									<label class="Active" for="remarks">Remarks</label>
								</div>

								<div class="input-field col s12 m12 right-align">
									<button type="submit" class="btn waves-effect waves-green" name="btn_save" id="btn_save" data-dismiss="modal">Save</button>

									<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Close</button>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div id="noti"></div>
				<!--Add model popup End-->

				<!--View model popup start-->
				<div id="myModal_content_view" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Referrance List</h4>
						<div class="modal-body">
							<table id="myTableHistory" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
								<thead>
									<th>Disposition</th>
									<th>Remarks</th>
									<th>Created_by</th>
									<th>Created_on</th>
								</thead>
								<tbody id="hisID">

								</tbody>
							</table>

							<div class="input-field col s12 m12 right-align">

								<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>

						</div>
					</div>
				</div>
				<!--View model popup End-->
				<?php
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();

					$reqType = $_POST['reqType'];
					$statusFld = $_POST['statusFld'];

				?>

					<div id="pnlTable">
						<?php
						$sqlConnect = "select id, oldid, RefID, EmployeeID, EmployeeName, DateOn, Designation, Client, Process, SubProcess, CandidateName, CandidateNumber, CandidateAddress, Remark, CandidateLevel, createdon, createdby, modifiedon, modifiedby, Agreed, source, video_eng, video_hi,process_hire,status,case when (CandidateLevel ='' or CandidateLevel ='CSA') then 'Customer Support Associate' else ifnull(CandidateLevel, 'Customer Support Associate') end as CandidateLevel from tbl_reference_reg_detail  where ";

						if ($_SESSION["__user_logid"] == "CFK08190181") {
							$sqlConnect = $sqlConnect . " video_eng!='' and CandidateAddress='Bangalore' and ";
						}
						if ($_SESSION["__user_logid"] == "CEV102073966") {
							$sqlConnect = $sqlConnect . " video_eng!='' and CandidateAddress='Vadodara' and ";
						}
						if ($_SESSION["__user_logid"] == "CMK071600007") {
							$sqlConnect = $sqlConnect . " video_eng!='' and CandidateAddress like '%Mang%' and ";
						}

						if ($_SESSION["__user_logid"] == "CE0321936918") {
							$sqlConnect = $sqlConnect . " CandidateLevel='Support' and ";
						} else if ($_SESSION["__user_logid"] == "CFK08190181" || $_SESSION["__user_logid"] == "CFK08190181" || $_SESSION["__user_logid"] == "CMK071600007") {
							$sqlConnect = $sqlConnect . " (CandidateLevel is null or CandidateLevel ='' or CandidateLevel='Customer Support Associate') and";
						}

						if ($statusFld != 'All') {

							$sqlConnect = $sqlConnect . " status = '" . $statusFld . "' and ";
						}
						$sqlConnect = $sqlConnect . " (cast(createdon as date) between '" . $date_From . "' and '" . $date_To . "') order by id desc";
						//echo $sqlConnect;
						//die;
						$myDB = new MysqliDb();
						$result = $myDB->rawQuery($sqlConnect);
						$mysql_error = $myDB->getLastError();
						$rowCount = $myDB->count;
						if ($rowCount > 0) { ?>
							<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th class="hidden">ID</th>
										<th>Action</th>
										<th>History</th>
										<th>Referred By (Employee)</th>
										<th>Candidate Name</th>
										<th>Candidate Phone</th>
										<th>Process Name</th>
										<th>Intro English</th>
										<th>Intro Hindi</th>
										<th>Date Time</th>
										<th>Status</th>
										<th>Applied For</th>
										<th>Location</th>

									</tr>
								</thead>
								<tbody>
									<?php foreach ($result as $row) { ?>
										<tr>
											<td class="hidden"><?php echo $row['id']; ?></td>
											<td>
												<i href="#myModal_content" class="material-icons edit_item imgBtn imgBtnEdit tooltipped modal-trigger" id="editID" value="<?php echo $row['id'] ?>" data-position="left" data-tooltip="Edit">ohrm_edit</i>
											</td>
											<td>
												<i href="#myModal_content_view" class="material-icons edit_item imgBtn imgBtnEdit tooltipped modal-trigger" id="viewID" value="<?php echo $row['id'] ?>" data-position="left" data-tooltip="Edit">ohrm_view</i>
											</td>
											<td><?php echo $row['EmployeeName']; ?></td>
											<td><?php echo $row['CandidateName']; ?></td>
											<td><?php echo $row['CandidateNumber']; ?></td>
											<td><?php echo $row['process_hire']; ?></td>
											<td>

												<?php if ($row['video_eng'] != '') { ?>
													<a href="<?php echo 'video/eng/' . $row['video_eng']; ?>" target="blank" style="color: #0068FF;text-decoration: underline;"><i class="fa fa-2x fa-play-circle-o" aria-hidden="true"></i></a>
													<!--<video width="100%" controls>
														<source src="<?php //echo 'video/eng/' . $row['video_eng']; 
																		?>" type="video/mp4">
														<source src="<?php //echo 'video/eng/' . $row['video_eng']; 
																		?>" type="video/webm">
														Your browser does not support HTML video.
													</video>-->
					</div>

				<?php } else {
													echo "No Video";
												} ?>

				</td>
				<td>
					<?php if ($row['video_hi'] != '') { ?>
						<a href="<?php echo 'video/hindi/' . $row['video_hi']; ?>" target="blank" style="color: #0068FF;text-decoration: underline;"><i class="fa fa-2x fa-play-circle-o" aria-hidden="true"></i></a>
						<!-- <video width="100%" controls>
														<source src="<?php //echo 'video/hindi/' . $row['video_hi']; 
																		?>" type="video/mp4">
														<source src="<?php //echo 'video/hindi/' . $row['video_hi']; 
																		?>" type="video/webm">
														Your browser does not support HTML video.
													</video>-->

					<?php } else {
											echo "No Video";
										} ?>

				</td>
				<td><?php echo $row['createdon']; ?></td>
				<?php if ($row['status'] == 'Selected') { ?>
					<td style="background-color:green;color:#fff;border-radius: 15px;"><?php echo $row['status'] != '' ? $row['status'] : 'NA'; ?></td>
				<?php } else if ($row['status'] == 'Rejected') { ?>
					<td style="background-color:red;color:#fff;border-radius: 15px;"><?php echo $row['status'] != '' ? $row['status'] : 'NA'; ?></td>
				<?php } else if ($row['status'] == 'NotContacted') { ?>
					<td style="background-color:yellow;color:#000;border-radius: 15px;"><?php echo $row['status'] != '' ? $row['status'] : 'NA'; ?></td>
				<?php	} else { ?>
					<td style="background-color:#fff;color:#000;border-radius: 15px;"><?php echo $row['status'] != '' ? $row['status'] : 'NA'; ?></td>
				<?php } ?>
				<td><?php echo $row['CandidateLevel']; ?></td>
				<td><?php echo $row['CandidateAddress']; ?></td>

				</tr>
			<?php } ?>
			</tbody>
			<table>
			<?php } else {
							echo "<script>$(function(){ toastr.error('No Data Found " . $mysql_error . "'); }); </script>";
						} ?>
			</div>
		</div>
	<?php }
	?>

	</div>
	<!--Form container End -->
</div>
<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>
<script>
	//function saveHistory(hidID,disposition,remarks){
	/* alert(hidID);
	alert(disposition);
	alert(remarks); */
	$('#btn_save').click(function(e) {
		e.preventDefault();
		var hidID = $('#hidID').val();
		var disposition = $('#disposition').val();
		var remarks = $('#remarks').val();
		if (disposition != '' && remarks != '') {

			$.ajax({
				type: "POST",
				data: {
					"hidID": hidID,
					"disposition": disposition,
					"remarks": remarks
				},
				url: "save_reference.php",
				success: function(response) {
					// alert(response)
					$('#noti').html(response);
					$('#disposition').val('NA');
					$('#remarks').val('');
					//$('#myModal_content').modal("hide");
					$("#myModal_content").modal('hide');
				}
			});
		} else {
			//alert("Required field manadatory");
		}

	});

	$(document).ready(function() {
		//Model Assigned and initiation code on document load

		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Can').trigger("click");
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


		$("#myTable").on('click', '#editID', function() {
			// get the current row
			var currentRow = $(this).closest("tr");
			var col1 = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
			// alert(col1);
			$('#disposition').val('NA');
			$('#remarks').val('');
			var refid = $('#hidID').val(col1);
		});

		$("#myTable").on('click', '#viewID', function() {
			// get the current row
			var currentRow = $(this).closest("tr");
			var col1 = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
			// alert(col1);

			$.ajax({
				type: "POST",
				url: "qrinfo_videoVIEW.php?id=" + col1,
				success: function(response) {
					// alert(response)
					$('#hisID').html(response);

				}
			});
		});


		$("#btn_save").on('click', function() {
			var validate = 0;
			var alert_msg = '';
			if ($('#disposition').val() == 'NA') {
				$('#disposition').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spandisposition').length == 0) {
					$('<span id="spandisposition" class="help-block">Required *</span>').insertAfter('#disposition');
				}
				validate = 1;
			}

			if ($('#remarks').val() == '') {
				$('#remarks').addClass("has-error");
				if ($('#spanremarks').length == 0) {
					$('<span id="spanremarks" class="help-block">Required *</span>').insertAfter('#remarks');
				}
				validate = 1;
			}

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			}
		});
		$("#btn_view").on('click', function() {
			var validate = 0;
			var alert_msg = '';
			if ($('#statusFld').val() == '') {
				$('#statusFld').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spanstatusFld').length == 0) {
					$('<span id="spanstatusFld" class="help-block">Required *</span>').insertAfter('#statusFld');
				}
				validate = 1;
			}



			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			}
		});
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

	//});
</script>
<script>
	$(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(10000).fadeOut("slow");
		}

		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>