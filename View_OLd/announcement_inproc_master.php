<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_id = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_id)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}


$btn_clfs_Save = isset($_POST['btn_clfs_Save']);
if ($btn_clfs_Save) {

	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$cm_id  = cleanUserInput($_POST['ddl_clfs_Process']);
		$heading  = cleanUserInput($_POST['txt_Announcement_Name']);
		$body  = cleanUserInput($_POST['txt_Announcement_Body']);
		$createBy = clean($_SESSION['__user_logid']);

		if (!empty($cm_id) && !empty($heading) && !empty($body) && !empty($createBy)) {
			$insert_array = array('cm_id' => $cm_id, 'announcement_heading' => $heading, 'announcement_body' => $body, 'createdby' => $createBy);
			$myDB = new MysqliDb();
			$flag = $myDB->insert('announcement_inproc', $insert_array);
			if ($flag) {
				echo "<script>$(function(){ toastr.success('Data Saved successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Data not saved'.$mysql_error.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Fields should not be empty'); }); </script>";
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
				'print',
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
				}, 'copy', 'pageLength'

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
	<span id="PageTittle_span" class="hidden">Announcement Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Announcement Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Announcement"><i class="material-icons">add</i></a></h4>

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

						<h4 class="col s12 m12 model-h4">Manage Announcement Master</h4>
						<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
							<div class="input-field col s12 m12">
								<select id="ddl_clfs_Process" name="ddl_clfs_Process" required>
									<option value="NA">----Select----</option>
									<?php

									if (clean($_SESSION['__user_type']) == 'ADMINISTRATOR') {
										$sqlBy = 'SELECT cm_id,process,sub_process,client_master.client_name from new_client_master inner join client_master on client_master.client_id = new_client_master.client_name ORDER BY process';
										$stmt2 = $conn->prepare($sqlBy);
										// $stmt2->bind_param("s",  $dfid);
										$stmt2->execute();
										$resultBy = $stmt2->get_result();
									} else {
										$userID = clean($_SESSION['__user_logid']);
										$sqlBy = 'SELECT cm_id,process,sub_process,client_master.client_name from new_client_master inner join client_master on client_master.client_id = new_client_master.client_name where new_client_master.account_head =? ORDER BY process';
										$stmt2 = $conn->prepare($sqlBy);
										$stmt2->bind_param("s", $userID);
										$stmt2->execute();
										$resultBy = $stmt2->get_result();
									}

									// $resultBy = $myDB->rawQuery($sqlBy);
									// $mysql_error = $myDB->getLastError();
									if ($resultBy) {
										foreach ($resultBy as $key => $value) {
											if ($value['process'] == $value['sub_process']) {
												echo '<option value="' . $value['cm_id'] . '"  >' . $value['sub_process'] . '</option>';
											} else {
												echo '<option value="' . $value['cm_id'] . '"  >' . $value['process'] . ' | ' . $value['sub_process'] . '</option>';
											}
										}
									}

									?>
								</select>
								<label for="ddl_clfs_Process" class="active-drop-down active">Process</label>
							</div>

							<div class="input-field col s12 m12">
								<textarea id="txt_Announcement_Name" class="materialize-textarea" name="txt_Announcement_Name" required></textarea>
								<label for="txt_Announcement_Name">Heading</label>
							</div>

							<div class="input-field col s12 m12">
								<textarea id="txt_Announcement_Body" class="materialize-textarea" name="txt_Announcement_Body" required></textarea>
								<label for="txt_Announcement_Body">Body</label>
							</div>

							<div class="input-field col s12 m12 right-align">
								<button type="submit" name="btn_clfs_Save" id="btn_clfs_Save" class="btn waves-effect waves-green">Save</button>
								<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>
						</div>
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php
					$sqlConnect = 'SELECT announcement_inproc.*,new_client_master.process,sub_process from announcement_inproc inner join new_client_master on new_client_master.cm_id = announcement_inproc.cm_id;';
					$stmt1 = $conn->prepare($sqlConnect);
					// $stmt1->bind_param("s", $_SESSION['__user_logid']);
					$stmt1->execute();
					$result = $stmt1->get_result();
					// $myDB = new MysqliDb();
					// $result = $myDB->rawQuery($sqlConnect);
					// $mysql_error = $myDB->getLastError();

					if ($result) { ?>
						<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="hidden">CM ID</th>
									<th>Header</th>
									<th>Body</th>
									<th>Process</th>
									<th>Manage Announcement</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="hidden">' . $value['cm_id'] . '</td>';
									echo '<td class="Heading">' . $value['announcement_heading'] . '</td>';
									echo '<td class="Body">' . $value['announcement_body'] . '</td>';
									if ($value['process'] == $value['sub_process']) {
										echo '<td class="process">' . $value['sub_process'] . '</td>';
									} else {
										echo '<td class="process" >' . $value['process'] . ' | ' . $value['sub_process'] . '</td>';
									}

									echo '<td style="min-width:150px;max-width:150px;width: 150px;display: block;"><img alt="Delete" class="imgBtn" src="../Style/images/users_delete.png" id="delete_' . $value['id'] . '" data_id = "' . $value['id'] . '" onclick="javascirpt:return ApplicationDataDelete(this);"/> </td>';
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php
					}
					?>
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

			},
			onCloseEnd: function(elm) {
				$('#btn_Can').trigger("click");
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
		$('#btn_Can').on('click', function() {

			$('#txt_Announcement_Name').val('');
			$('#txt_Announcement_Body').val('');
			$('#ddl_clfs_Process').val('NA');

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

		$('#btn_clfs_Save').on('click', function() {
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
	});



	function ApplicationDataDelete(el) {
		//var currentUrl = window.location.href;
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
					// alert(Resp);
					//window.location.href = currentUrl;
					window.location.replace("../View/announcement_inproc_master.php");

				}
			}

			xmlhttp.open("GET", "../Controller/delete_announcement_proc.php?ID=" + $('#' + el.id).attr('data_id'), true);
			xmlhttp.send();
		}
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>