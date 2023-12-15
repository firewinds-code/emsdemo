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
// Only for user type administrator
$clean_u_type = clean($_SESSION['__user_type']);
if (!($clean_u_type == 'HR' || $clean_u_type == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == "CE12102224")) {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}
// Global variable used in Page Cycle
$alert_msg = '';

// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_Updates_Save'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$clean_update_name = cleanUserInput($_POST['txt_Updates_Name']);
		$_Name = (isset($clean_update_name) ? $clean_update_name : null);
		$clean_update_body = cleanUserInput($_POST['txt_Updates_Body']);
		$_Body = (isset($clean_update_body) ? $clean_update_body : null);
		$createBy = clean($_SESSION['__user_logid']);
		$Insert = 'call add_Updates("' . $_Name . '","' . $_Body . '","' . $createBy . '")';
		$myDB = new MysqliDb();
		$myDB->query($Insert);
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Updates Added Successfully'); }); </script>";
		} else {
			echo "<script>$(function(){ toastr.success('Updates not Added :: '.$mysql_error.'); }); </script>";
		}
	}
}
?>

<script>
	//contain load event for data table and other importent rand required trigger event and searches if any
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				/*   {
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
	<span id="PageTittle_span" class="hidden">Updates Master Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<?php $_SESSION["token"] = csrfToken(); ?>
			<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

			<!-- Header for Form If any -->
			<h4>Updates Master Details
				<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="<?php echo URL . 'View/updates.php' ?>" data-position="bottom" data-tooltip="View" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a>
				<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Updates"><i class="material-icons">add</i></a>
			</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Manage Updates Master</h4>
						<div class="modal-body">
							<div class="input-field col s12 m12">
								<input type="text" id="txt_Updates_Name" name="txt_Updates_Name" required />
								<label for="txt_Updates_Name">Updates Heading</label>
							</div>

							<div class="input-field col s12 m12">
								<textarea id="txt_Updates_Body" name="txt_Updates_Body" class="materialize-textarea" required></textarea>
								<label for="txt_Updates_Body">Updates Body</label>

							</div>

							<div class="input-field col s12 m12 right-align">
								<button type="submit" name="btn_Updates_Save" id="btn_Updates_Save" class="btn waves-effect waves-green">Add</button>
								<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
							</div>

						</div>
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php
					$sqlConnect = "SELECT id,Heading,Body from updates";
					// $myDB = new MysqliDb();
					// $result = $myDB->query($sqlConnect);
					$stmt = $conn->prepare($sqlConnect);
					$stmt->execute();
					$result = $stmt->get_result();
					$mysql_error = $myDB->getLastError();
					if ($result) {
						// if (empty($mysql_error)) { 
					?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>ID</th>
									<th>Header</th>
									<th>Body</th>
									<th>Manage Updates</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="id">' . $value['id'] . '</td>';
									echo '<td class="Heading">' . $value['Heading'] . '</td>';
									echo '<td class="Body">' . $value['Body'] . '</td>';
									echo '<td><img alt="Delete" class="imgBtn" src="../Style/images/users_delete.png" id="' . $value['id'] . '" onclick="javascirpt:return ApplicationDataDelete(this);"/> </td>';
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
		$('#btn_Can').on('click', function() {

			$('#txt_Updates_Name').val('');
			$('#txt_Updates_Body').val('');

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

		$('btn_Updates_Save').on('click', function() {
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


	// This code for trigger del*t*	
	function ApplicationDataDelete(el) {
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
					window.location.replace("../View/updates_master.php");
				}
			}
			xmlhttp.open("GET", "../Controller/DeleteUpdates.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>