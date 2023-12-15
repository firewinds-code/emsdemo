<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 

$Request_Emp = '';
$_Description = $_Name = $alert_msg = '';
$msg_alret = '';

if (isset($_POST['btn_change_password']) && !empty($_POST['txt_pwd_empid'])) {
	$_newpassord = (isset($_POST['txt_chg_pwd']) ? $_POST['txt_chg_pwd'] : null);
	$userempid = $_POST['txt_pwd_empid'];
	$password_hash = md5($_newpassord);
	$chng_pwd = 'call change_pwd("' . $password_hash . '","' . $userempid . '")';


	$myDB = new MysqliDb();
	$result  = $myDB->rawQuery($chng_pwd);
	$mysql_error = $myDB->getLastError();
	$rowCount = $myDB->count;

	if (empty($mysql_error)) {
		$alert_msg =  "<script>$(function(){ toastr.success(' Password Changed Successfully'); }); </script>";
		$msg_alret =  '<div style="border: 1px solid #bfbfbf;margin-bottom:  20px;padding: 10px;border-radius: 10px;box-shadow: 1px 1px 1px 1px #d0d0d0;"><code><a style="font-size:18px;" href="' . URL . 'View/Login' . '">LogIn Here</a></code></div>';
		$_Description = $_Type = $_Name = '';
	} else {
		//$alert_msg='<span class="text-danger"><b>Message :</b> Data not updated :: '.$mysql_error.'</span>';

		$alert_msg =  "<script>$(function(){ toastr.error(' Record not updated." . $mysql_error . "'); }); </script>";
	}
}


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
	<title></title>

	<?php include(ROOT_PATH . 'AppCode/head.mpt'); ?>
	<?php include(ROOT_PATH . 'AppCode/DataTable.mpt'); ?>
	<link rel="stylesheet" href="<?php echo STYLE . 'Theme2.css'; ?>">
	</link>

	<link href="../FileContainer/crosscover-1.0.2/dist/css/crosscover.min.css" rel="stylesheet">
	<script src="../FileContainer/crosscover-1.0.2/dist/js/crosscover.min.js" charset="utf-8"></script>
	<link rel="stylesheet" href="<?php echo STYLE . 'jquery.datetimepicker.css'; ?>" />
	<script src="<?php echo SCRIPT . 'jquery.datetimepicker.full.min.js'; ?>"></script>
	<script>
		$(function() {
			$(document).prop('title', $("#PageTittle_span").text());
			$("span.page-title").html($("#PageTittle_span").text());
			$("#preloader").hide();
		});
	</script>
	<style>
		.short {
			font-weight: bold;
			color: #FF0000;
			font-size: larger;
		}

		.weak {
			font-weight: bold;
			color: orange;
			font-size: larger;
		}

		.good {
			font-weight: bold;
			color: #2D98F3;
			font-size: larger;
		}

		.strong {
			font-weight: bold;
			color: limegreen;
			font-size: larger;
		}

		.ui-datepicker-calendar tbody {

			border: 1px solid #bdbdbd;

		}

		/* DatePicker Container */
		.ui-datepicker {
			width: 250px;
			height: auto;
			margin: 5px auto 0;
			font: 9pt Arial, sans-serif;
			-webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
			-moz-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
			box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
		}

		.ui-datepicker a {
			text-decoration: none;
		}

		/* DatePicker Table */
		.ui-datepicker table {
			width: 100%;
		}

		.ui-datepicker-header {
			background: #1daec5;
			color: #e0e0e0;
			font-weight: bold;
			-webkit-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, 1);
			-moz-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
			box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
			text-shadow: 1px -1px 0px #1daec5;
			filter: dropshadow(color=#337ab7, offx=1, offy=-1);
			line-height: 30px;
			border-width: 1px 0 0 0;
			border-style: solid;
			border-color: #20c9e4;
		}

		.ui-datepicker-title {
			text-align: center;
		}

		.ui-datepicker-prev,
		.ui-datepicker-next {
			display: inline-block;
			width: 30px;
			height: 30px;
			text-align: center;
			cursor: pointer;
			background-repeat: no-repeat;
			line-height: 600%;
			overflow: hidden;
		}

		.ui-datepicker-prev {
			float: left;
			background-position: center -30px;
		}

		.ui-datepicker-next {
			float: right;
			background-position: center 0px;
		}

		.ui-datepicker thead {
			background-color: #f7f7f7;
			background-image: -moz-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
			background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f7f7f7), color-stop(100%, #f1f1f1));
			background-image: -webkit-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
			background-image: -o-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
			background-image: -ms-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
			background-image: linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f7f7f7', endColorstr='#f1f1f1', GradientType=0);
			border-bottom: 1px solid #bbb;
		}

		.ui-datepicker th {
			text-transform: uppercase;
			font-size: 6pt;
			padding: 5px 0;
			color: #666666;
			text-shadow: 1px 0px 0px #fff;
			filter: dropshadow(color=#fff, offx=1, offy=0);
		}

		.ui-datepicker tbody td {
			padding: 0;
			border-right: 1px solid #bbb;
		}

		.ui-datepicker tbody td:last-child {
			border-right: 0px;
		}

		.ui-datepicker tbody tr {
			border-bottom: 1px solid #bbb;
		}

		.ui-datepicker tbody tr:last-child {
			border-bottom: 0px;
		}

		.ui-datepicker td span,
		.ui-datepicker td a {
			display: inline-block;
			font-weight: bold;
			text-align: center;
			width: 100%;
			line-height: 30px;
			color: #666666;
			text-shadow: 1px 1px 0px #fff;
			filter: dropshadow(color=#fff, offx=1, offy=1);
		}

		.ui-datepicker-calendar .ui-state-default {
			background: #ededed;
			background: -moz-linear-gradient(top, #ededed 0%, #dedede 100%);
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ededed), color-stop(100%, #dedede));
			background: -webkit-linear-gradient(top, #ededed 0%, #dedede 100%);
			background: -o-linear-gradient(top, #ededed 0%, #dedede 100%);
			background: -ms-linear-gradient(top, #ededed 0%, #dedede 100%);
			background: linear-gradient(top, #ededed 0%, #dedede 100%);
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dedede', GradientType=0);
			-webkit-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
			-moz-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
			box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
		}

		.ui-datepicker-calendar .ui-state-hover {
			background: #f7f7f7;
		}

		.ui-datepicker-calendar .ui-state-active {
			background: #6eafbf;
			-webkit-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
			-moz-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
			box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
			color: #e0e0e0;
			text-shadow: 0px 1px 0px #4d7a85;
			filter: dropshadow(color=#4d7a85, offx=0, offy=1);
			border: 1px solid #55838f;
			position: relative;
			margin: 0px;
		}

		.ui-datepicker-unselectable .ui-state-default {
			background: #f4f4f4;
			color: #b4b3b3;
		}

		.ui-datepicker-calendar td:first-child .ui-state-active {

			margin-left: 0;
		}

		.ui-datepicker-calendar td:last-child .ui-state-active {

			margin-right: 0;
		}

		.ui-datepicker-calendar tr:last-child .ui-state-active {

			margin-bottom: 0;
		}

		.ui-datepicker-month,
		.ui-datepicker-year {
			color: #fff;
			font-weight: bold;
		}

		.ui-datepicker select.ui-datepicker-month,
		.ui-datepicker select.ui-datepicker-year {
			font-size: 16px;
			border-radius: 15px;
			border: 1px solid #0070d0;
			padding-left: 15px;
		}

		.ui-state-default,
		.ui-widget-content .ui-state-default,
		.ui-widget-header .ui-state-default {
			border: none;
		}

		.ui-state-highlight {
			color: #1c94c4 !important;
		}
	</style>
</head>

<body>
	<div id="preloader" class="center-align">
		<div style="" class="preloader-wrapper active big">
			<div class="spinner-layer">
				<div class="circle-clipper left">
					<div class="circle">

					</div>
				</div>
				<div class="gap-patch">
					<div class="circle">

					</div>
				</div>
				<div class="circle-clipper right">
					<div class="circle">

					</div>
				</div>
			</div>
		</div>
	</div>

	<form name="indexForm" id="indexForm" method="post" action="<?php echo ($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
		<script src="<?php echo SCRIPT . 'pwdchk.js'; ?>"></script>
		<script>
			$(document).ready(function() {
				$('input').blur(function() {
					$('#txt_chg_pwd1').removeClass('has-error');
					$('#txt_chg_pwd1').removeClass('has-success');
					if ($('#txt_chg_pwd').val() === $('#txt_chg_pwd1').val()) {
						$('#txt_chg_pwd1').addClass('has-success');
					} else {
						$('#txt_chg_pwd1').addClass('has-error');
						toastr.info("Password not matched.");
					}
				});
			});
		</script>
		<script>
			$(function() {
				var date_now = new Date();
				var get_Year = date_now.getFullYear();

				var get_Year = get_Year - 18;
				var months = date_now.getMonth();
				if (months < 12) {
					months = months + 1;
				} else {
					months = 1;
				}
				var days = date_now.getDate();
				if (days < 10) {
					days = '0' + days;
				}
				if (months < 10) {
					months = '0' + months;
				}
				var minDates = get_Year + '/' + months + '/' + days;

				var defaultDatez = get_Year + '-' + months + '-' + days;
				$('#txt_dob').datetimepicker({
					format: 'Y-m-d',
					timepicker: false,
					maxDate: minDates,
					yearEnd: get_Year,
					defaultDate: defaultDatez
				});
				//$('#txt_dob').datetimepicker({ format:'Y-m-d', timepicker:false});
				$('#txt_dob').val("").attr("readonly", "true");
			});
			$(document).ready(function() {
				$('input.clschangepwd').keyup(function() {

					$('#txt_chg_pwd1').removeClass('has-error');
					$('#txt_chg_pwd1').removeClass('has-success');
					// alert($('#txt_chg_pwd').val()+','+$('#txt_chg_pwd1').val())
					if ($('#txt_chg_pwd').val() === $('#txt_chg_pwd1').val()) {
						$('#alert_message').fadeOut();
						$('#txt_chg_pwd1').addClass('has-success');
					} else {

						$('#txt_chg_pwd1').addClass('has-error');
						$('#alert_message').show();
						$('#alert_msg').html("Password not matched.");

					}
				});
			});
		</script>

		<!-- This div not contain a End on this Page because this activity already done in footer Page -->
		<div id="content" class="content">

			<!-- Header Text for Page and Title -->
			<span id="PageTittle_span" class="hidden">PASSWORD RECOVERY</span>

			<!-- Main Div for all Page -->
			<div class="pim-container ">

				<!-- Sub Main Div for all Page -->
				<div class="form-div">

					<!-- Header for Form If any -->
					<h4 class="white darken-3">
						<div style="
	    background-image: url('https://localhost/erpm/Style//images/cogent-logo.png');
	    background-position: 0 5px;
	    background-size: 85px 35px;
	    width: 100px;
	    float: left;
	    height: 40px;
	    background-repeat: no-repeat;
	    "></div>PASSWORD RECOVERY
					</h4>

					<!-- Form container if any -->
					<div class="schema-form-section row">
						<?php echo $alert_msg; ?>

						<div class="scheduler-border">

							<?php
							echo $msg_alret;
							if (isset($_POST['btn_save'])) {
								$empid = $_POST['txt_empid'];
								$seqans = $_POST['txtans'];
								$myDB = new MysqliDb();
								$result = $myDB->rawQuery('call get_password("' . $empid . '","' . $seqans . '")');
								$er = $myDB->getLastError();
								$rowCount = $myDB->count;
								if ($rowCount > 0) {
									foreach ($result as $key => $value) {
							?>
										<div class="input-field col s6 m6 ">
											<input type="password" class=" password clschangepwd" id="txt_chg_pwd" name="txt_chg_pwd" placeholder="****" autocomplete="off" /><span id="result"></span>
											<label for="txt_chg_pwd">New Password</label>
										</div>
										<div class="input-field col s6 m6 ">
											<input type="password" class=" clschangepwd" id="txt_chg_pwd1" name="txt_chg_pwd1" placeholder="****" autocomplete="off" />
											<label for="txt_chg_pwd1">Confirm Password</label>
										</div>
										<input type="hidden" id="txt_pwd_empid" name="txt_pwd_empid" value="<?php echo $empid; ?>" />
										<div class="input-field col s12 m12 right-align ">
											<button type="submit" name="btn_change_password" id="btn_change_password" class="btn waves-effect waves-green" onclick="return confirm('Are you want to proceed?');">Change Password</button>
										</div>
									<?php
									}
								} else {
									?>
									<div id="div_error" class="col s12"><a href="pwd_rcvr.php">Wrong Answer Of Security Question Try Again.</a> </div>
								<?php

									//echo "<script>$(function(){ toastr.error('Wrong Answer Of Security Question Try Again...') }); </script>";
								}
							} else {
								?>
								<div class="input-field col s6 m6 ">

									<input type="text" class=" clsInput" name="txt_empid" id="txt_empid" placeholder="Empoyee ID" autocomplete="off" />
									<label for="txt_empid">Employee ID </label>
								</div>
								<div class="input-field col s6 m6 ">
									<input type="text" class=" clsInput" name="txt_dob" id="txt_dob" placeholder="YYYY-MM-DD" autocomplete="off" />
									<label for="txt_dob">DOB </label>

								</div>
								<div class="input-field col s12 m12 right-align" id="btndiv">
									<button type="button" name="btn_check" id="btn_check" value="Check" class="btn waves-effect waves-green">Check</button>
								</div>
								<div id="secques" class="input-field col s12 m12   hidden">

									<div class="input-field col s6 m6 ">
										<input type="password" name="txtans" id="txtans" class=" clsInput" placeholder="Enter Your Answer" autocomplete="off" />
										<p id="lbleq"></p>
									</div>
									<div class="input-field col s12 m12 rigth-align hidden" id="btndiv1">
										<button type="submit" name="btn_save" id="btn_save" value="Submit" class="btn waves-effect waves-green">Submit</button>
									</div>
								</div>

								<div id="div_error" class="col-sm-12 slideInDown animated hidden pull-left">
								</div>
						</div>
					<?php
							}
					?>


					</div>

				</div>

				<!--Form container End -->
			</div>
			<!--Main Div for all Page End -->
		</div>
		<!--Content Div for all Page End -->
		</div>
		<script>
			$(function() {
				$('#alert_msg_close').click(function() {
					$('#alert_message').hide();
				});

				if ($('#alert_msg').text() == '') {
					$('#alert_message').hide();
				} else {
					$('#alert_message').delay(5000).fadeOut("slow");
				}
				$("#txt_empid").focus().trigger("click");
				$('#btn_check').click(function() {
					var validate = 0;
					var alert_msg = '';
					$('#txt_empid').removeClass('has-error');
					$('#txt_dob').removeClass('has-error');
					if ($('#txt_empid').val() == '') {
						$('#txt_empid').addClass('has-error');
						validate = 1;
					}
					if ($('#txt_dob').val() == '') {
						$('#txt_dob').addClass('has-error');
						validate = 1;
					}


					if (validate == 1) {
						$('#div_error').removeClass('hidden').html('<p class="text-danger">Please Fill Red Bordered Fields...</p>');
						return false;
					} else {
						var empid = $('#txt_empid').val();
						var dob = $('#txt_dob').val();

						$('#div_error').addClass('hidden').html('');
						$.ajax({
							url: <?php echo '"' . URL . '"'; ?> + "Controller/getSecurity.php?id=" + empid + "&dob=" + dob
						}).done(function(data) { // data what is sent back by the php page

							//alert(data);
							if (data.trim() != '') {
								$('#txt_empid').attr('readonly', true);
								$('#txt_dob').attr('readonly', true);
								$('#btndiv1').removeClass('hidden');
								$('#secques').removeClass('hidden');
								$('#btndiv').addClass('hidden');
								$('#lbleq').html('<b>Question  </b>' + data);

							} else {
								$('#txt_empid').attr('readonly', true);
								$('#txt_dob').attr('readonly', true);
								$('#btndiv1').addClass('hidden');
								$('#btndiv').removeClass('hidden');
								$('#secques').addClass('hidden');
								$('#div_error').removeClass('hidden').html('<p class="text-danger">No User Found ,Try agian...</p>');
							}

						});
					}

				});
				$('#btn_change_password').on('click', function() {
					var validate = 0;
					var alert_msg = '';
					$('#txt_chg_pwd').removeClass('has-error');
					$('#txt_chg_pwd').removeClass('has-error');
					if ($('#txt_chg_pwd').val() == '' || !($('#result').hasClass('strong') || $('#result').hasClass('good'))) {
						//alert($('#txt_chg_pwd').val());
						$('#txt_chg_pwd').addClass('has-error');
						validate = 1;
						alert_msg += 'Password Should not be empty or Too Short<br />';
					}
					if (!($('#txt_chg_pwd').val() === $('#txt_chg_pwd1').val())) {
						return false;
					}
					if (validate == 1) {
						/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
						$('#alert_message').show().attr("class","SlideInRight animated");
						$('#alert_message').delay(5000).fadeOut("slow");
						*/
						$(function() {
							toastr.error(alert_msg)
						});
						return false;
					}

				});
			});
		</script>
		<div class="center-align">
			<div class="footer">Cogent EMS<br>
				&copy; <?php echo date('Y') . '  -  ' . date('Y', strtotime("next year")); ?><a href="http://www.cogenteservices.com" target="_blank"> Cogent ES </a> All rights reserved.</div>
		</div>
	</form>
</body>

</html>