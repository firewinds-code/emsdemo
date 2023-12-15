<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
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
$alert_msg = '';

// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_Announcement_Save'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$_Name = cleanUserInput($_POST['txt_title']);
		$_Body = cleanUserInput($_POST['txt_body']);
		$_Footer = cleanUserInput($_POST['txt_Footer']);
		if (empty($_Name) || empty($_Body) || empty($_Body)) {
			echo "<script>$(function(){ toastr.success('Info not Added,Field Not be Empty'); }); </script>";
		} else {
			$array = array(
				'Header' => $_Name,
				'Body' => $_Body,
				'Footer' => $_Footer
			);
			$myDB = new MysqliDb();
			$result = $myDB->query('loginfo', $array, 'id=1');
			$mysql_error = $myDB->getLastError();
			if ($result) {
				echo "<script>$(function(){ toastr.success('Info Updated Successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.success('Info not Updated " . $mysql_error . "'); }); </script>";
			}
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
	<span id="PageTittle_span" class="hidden">LogIn Page Info</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>LogIn Page Info</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<form name="indexForm" role="form" id="indexForm" method="post" action="<?php echo ($_SERVER['REQUEST_URI']); ?>">
					<?php

					$_SESSION["token"] = csrfToken();
					?>
					<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
					<div id="pnlTable">
						<?php
						$sqlConnect = "select id,Header,Body,Footer from loginfo";
						$myDB = new MysqliDb();
						$result = $myDB->rawQuery($sqlConnect);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) { ?>
							<div class="input-field col s12 m12">
								<input type="text" id="txt_title" name="txt_title" value="<?php echo $result[0]['Header']; ?>" />
								<label for="txt_title">Title:</label>
							</div>

							<div class="input-field col s12 m12">
								<textarea class="materialize-textarea" id="txt_body" name="txt_body"><?php echo $result[0]['Body']; ?></textarea>
								<label for="txt_body">Body</label>
							</div>

							<div class="input-field col s12 m12">
								<input type="text" id="txt_Footer" name="txt_Footer" value="<?php echo $result[0]['Footer']; ?>" />
								<label for="txt_Footer">Footer:</label>
							</div>

							<div class="input-field col s12 m12 right-align">
								<button type="submit" name="btn_Announcement_Save" id="btn_Announcement_Save" class="btn waves-effect waves-green">Update</button>
							</div>

						<?php
						}
						?>
					</div>
				</form>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(5000).fadeOut("slow");
		}


		$('#btn_Announcement_Save').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			$('#txt_title').closest('div').removeClass('has-error');
			$('#txt_Footer').closest('div').removeClass('has-error');
			$('#txt_body').closest('div').removeClass('has-error');
			if ($('#txt_title').val() == '') {
				$('#txt_title').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li> Heading can not be Empty </li>';
			}
			if ($('#txt_Footer').val() == '') {
				$('#txt_Footer').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li> Footer can not be Empty </li>';
			}
			if ($('#txt_body').val() == '') {
				$('#txt_body').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li> Body can not be Empty </li>';
			}
			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}

		});
	});

	// function ApplicationDataDelete(el) {
	// 	var currentUrl = window.location.href;
	// 	var Cnfm = confirm("Do You Want To Delete This ");
	// 	if (Cnfm) {
	// 		var xmlhttp;
	// 		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
	// 			xmlhttp = new XMLHttpRequest();
	// 		} else { // code for IE6, IE5
	// 			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	// 		}
	// 		xmlhttp.onreadystatechange = function() {
	// 			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


	// 				var Resp = xmlhttp.responseText;
	// 				alert(Resp);
	// 				window.location.href = currentUrl;



	// 			}
	// 		}

	// 		xmlhttp.open("GET", "../Controller/DeleteAnnouncement.php?ID=" + el.id, true);
	// 		xmlhttp.send();
	// 	}
	// }
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>