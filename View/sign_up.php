<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
date_default_timezone_set('Asia/Kolkata');
$msg = "";
if (isset($_POST['submit']) && isset($_POST['policy']) && $_POST['policy'] == 'Yes') {
	if (($_POST['emp_id'] == "" || $_POST['emp_id'] == "NA") || ($_POST['password'] == "" || $_POST['password'] == "NA") || ($_POST['password_confirmation'] == "" || $_POST['password_confirmation'] == "NA") || ($_POST['dob'] == "" || $_POST['dob'] == "NA") || ($_POST['doj'] == "" || $_POST['doj'] == "NA") || ($_POST['sec_qusn'] == "" || $_POST['sec_qusn'] == "NA") || ($_POST['sec_asn'] == "" || $_POST['sec_asn'] == "NA") || $_POST['policy'] == '1') {

		$msg = "<script>$(function(){ toastr.error('Any of the field should not be empty or contain <b>NA</b>.'); }); </script>";
	} else {
		$empid = rawurlencode($_POST['emp_id']);
		$password = rawurlencode($_POST['password']);
		$password_confirmation = $_POST['password_confirmation'];
		$dob = rawurlencode($_POST['dob']);
		$doj = rawurlencode($_POST['doj']);
		$sec_qusn = rawurlencode(addslashes($_POST['sec_qusn']));
		$sec_asn = rawurlencode(addslashes($_POST['sec_asn']));
		/**
				 call API for sinup
		 */
		$url2 = URL . "Services/signup.php?empid=" . $empid . "&password=" . $password . "&dob=" . $dob . "&doj=" . $doj . "&seq=" . $sec_qusn . "&sqa=" . $sec_asn . "&ak=ces";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url2);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$data2 = curl_exec($curl);
		if ($data2 != "") {
			$data_array = json_decode($data2);
			if ($data_array->status == 1) {
				$msg = '<a href="' . URL . 'Login' . '">click here to Login</a>';
				$msg .= "<script>$(function(){ toastr.success('Password and Security Keys was updated.'); }); </script>";
			} else
					if ($data_array->status == 0) {

				$msg = "<script>$(function(){ toastr.error('Error when Password and Security Keys was updated try agian.'); }); </script>";
			} elseif ($data_array->status == 2) {
				$msg = "<script>$(function(){ toastr.error('You have allready enrolled or DOB and DOJ is not valid.'); }); </script>";
			} elseif ($data_array->status == 3) {
				$msg = "<script>$(function(){ toastr.error('invalid request.'); }); </script>";
			}
		} else {
			$msg = "<script>$(function(){ toastr.error('internal error: signup API is not calling.'); }); </script>";
		}
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
	<script>
		$(function() {
			// $( "#dob" ).datepicker({ dateFormat: 'yy-mm-dd' ,maxDate: '-18y',yearRange:  "-50:+0"});
			// $( "#doj" ).datepicker({ dateFormat: 'yy-mm-dd',maxDate: '0d',yearRange:  "-50:+0"});
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
			$('#dob').datetimepicker({
				format: 'Y-m-d',
				timepicker: false,
				maxDate: minDates,
				yearEnd: get_Year,
				defaultDate: defaultDatez
			});
			//$('#txt_dob').datetimepicker({ format:'Y-m-d', timepicker:false});
			$('#dob').val("").attr("readonly", "true");
			$("#doj").datetimepicker({
				format: 'Y-m-d',
				timepicker: false,
				maxDate: '0'
			});
			$('#doj').val("").attr("readonly", "true");
			$('input').keyup(function() {

				$('#password').removeClass('has-error');
				$('#password_confirmation').removeClass('has-error');
				$('#password_confirmation').removeClass('has-success');
				// alert($('#txt_chg_pwd +','+$('#txt_chg_pwd1').val())
				if ($('#password').val() === $('#password_confirmation').val()) {
					$('#alert_message').fadeOut();
					$('#password_confirmation').addClass('has-success');
				} else {

					$('#password_confirmation').addClass('has-error');


				}
			});
			$('#password').keyup(function() {
				$('#result').html(checkStrength($('#password').val()))
			})

			function checkStrength(password) {
				var strength = 0
				if (password.length < 6) {
					$('#result').removeClass()
					$('#result').addClass('short')
					return 'Too short'
				}
				if (password.length > 7) strength += 1
				// If password contains both lower and uppercase characters, increase strength value.
				if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
				// If it has numbers and characters, increase strength value.
				if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
				// If it has one special character, increase strength value.
				if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// If it has two special characters, increase strength value.
				if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// Calculated strength value, we can return messages
				// If value is less than 2
				if (strength < 2) {
					$('#result').removeClass()
					$('#result').addClass('weak')
					return 'Weak'
				} else if (strength == 2) {
					$('#result').removeClass()
					$('#result').addClass('good')
					return 'Good'
				} else {
					$('#result').removeClass()
					$('#result').addClass('strong')
					return 'Strong'
				}
			}
		});
	</script>
	<style>
		/* Credit to bootsnipp.com for the css for the color graph */
		.colorgraph {
			height: 5px;
			border-top: 0;
			background: #c4e17f;
			border-radius: 5px;
			background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
			background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
			background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
			background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
		}

		input[type="text"] {
			min-width: auto;
			min-width: auto;
			border-radius: 2px;
		}

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
			background: #337ab7;
			color: #e0e0e0;
			font-weight: bold;
			-webkit-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, 2);
			-moz-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
			box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
			text-shadow: 1px -1px 0px #337ab7;
			filter: dropshadow(color=#337ab7, offx=1, offy=-1);
			line-height: 30px;
			border-width: 1px 0 0 0;
			border-style: solid;
			border-color: #0a4373;
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
			height: 29px;
			margin-bottom: 0;
		}

		.ui-datepicker-month,
		.ui-datepicker-year {
			color: #fff;
			font-weight: 100;
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

		.tooltipx {
			color: #000000;
			outline: none;
			cursor: help;
			text-decoration: none;
			position: relative;
			opacity: 1;
			font-size: 16px;
		}

		.tooltipx span {
			margin-left: -999em;
			position: absolute;
		}

		.tooltipx:hover {
			color: green;

		}

		.tooltipx:hover span {
			border-radius: 5px 5px;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.1);
			-webkit-box-shadow: 5px 5px rgba(0, 0, 0, 0.1);
			-moz-box-shadow: 5px 5px rgba(0, 0, 0, 0.1);
			font-family: Calibri, Tahoma, Geneva, sans-serif;
			position: absolute;
			left: 1em;
			top: 2em;
			z-index: 99;
			margin-left: 0;
			width: 350px;
		}

		.tooltipx:hover img {
			border: 0;
			margin: -26px 0 0 -55px;
			float: left;
			position: absolute;
		}

		.tooltipx:hover em {

			font-family: Candara, Tahoma, Geneva, sans-serif;
			font-size: 1.2em;
			font-weight: bold;
			display: block;
			padding: 0.2em 0 0.6em 0;
		}

		.classic {
			padding: 0.8em 1em;
		}

		.custom {
			padding: 0.5em 0.8em 0.8em 2em;
		}

		* html a:hover {
			background: transparent;
		}

		.classic {
			background: #FFFFAA;
			border: 1px solid #FFAD33;
		}

		.critical {
			background: #FFCCAA;
			border: 1px solid #FF3334;
		}

		.help {
			background: #9FDAEE;
			border: 1px solid #2BB0D7;
		}

		.info {
			background: #9FDAEE;
			border: 1px solid #2BB0D7;
		}

		.warning {
			background: #FFFFAA;
			border: 1px solid #FFAD33;
		}

		.tooltipx:hover span {
			color: #636b13;
			background: #CDDC39;
			border: 1px solid #7c861b;
			text-shadow: 1px 1px 1px rgba(19, 18, 18, 0.22);
		}
	</style>
</head>

<body>
	<form name="indexForm" id="indexForm" method="post" action="<?php echo ($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
		<!-- This div not contain a End on this Page because this activity already done in footer Page -->
		<div id="content" class="content">

			<!-- Header Text for Page and Title -->
			<span id="PageTittle_span" class="hidden">SIGN UP</span>

			<!-- Main Div for all Page -->
			<div class="pim-container ">

				<!-- Sub Main Div for all Page -->
				<div class="form-div">

					<!-- Header for Form If any -->
					<h4 class="white darken-3">
						<div style="
	    background-image: url('https://ems.cogentlab.com/erpm/Style/images/cogent-logo.png');
		
	    background-position: 0 5px;
	    background-size: 85px 35px;
	    width: 100px;
	    float: left;
	    height: 40px;
	    background-repeat: no-repeat;
	    "></div>SIGN UP
					</h4>

					<!-- Form container if any -->
					<div class="schema-form-section row">

						<div class="scheduler-border">
							<div class="input-field col s12 m12 l12">
								<input type="text" name="emp_id" id="emp_id" tabindex="1">
								<label for="emp_id">Employee ID</label>
							</div>
							<div class="col s12 m12 l12 no-padding">
								<div class="input-field col s6 m6 l6">
									<input type="text" name="dob" id="dob" tabindex="2" autocomplete="off" />
									<label for="dob">DOB YYYY-MM-DD</label>
								</div>
								<div class="input-field col s6 m6 l6">
									<input type="text" name="doj" id="doj" tabindex="3" autocomplete="off" />
									<label for="doj">DOJ YYYY-MM-DD</label>
								</div>
							</div>
							<div class="col s12 m12 l12 no-padding">
								<div class="input-field col s6 m6 l6">
									<input type="password" name="password" id="password" tabindex="4" autocomplete="off" />
									<label for="password">Password</label>
									<span id="result"></span>
								</div>
								<div class="input-field col s6 m6 l6">
									<input type="password" name="password_confirmation" id="password_confirmation" tabindex="5" autocomplete="off" />
									<label for="password_confirmation">Confirm Password</label>

								</div>
							</div>
							<div class="input-field col s12 m12 l12">
								<input type="text" name="sec_qusn" id="sec_qusn" tabindex="6" autocomplete="off" />
								<label for="sec_qusn">Security Question</label>
							</div>
							<div class="input-field col s12 m12 l12">
								<input type="text" name="sec_asn" id="sec_asn" tabindex="7" autocomplete="off" />
								<label for="sec_asn">Security Answer</label>
							</div>

							<br />
							<div>
								<br />
								<p><b><u>CODE OF CONDUCT POLICY</u></b> &nbsp;&nbsp; ( <span style="color: red;"> Please read carefully and signup</span> )</p>
								<p colspan='2' style="padding-top:15px;"><b>Policy brief & purpose</b> </p>
								<p style="text-align: justify;">Our Employee Code of Conduct company policy outlines our expectations regarding employees’ behavior towards their colleagues, supervisors, clients and overall organization. We promote freedom of expression and open communication. But we expect all employees to follow our code of conduct. They should avoid offending, participating in serious disputes and disrupting our workplace. We also expect them to foster a well-organized, respectful and collaborative environment. </p>
								<p><b>Scope</b></p>
								<p>This policy applies to all our employees regardless of employment agreement or rank.</p>
								<p><b>1. Compliance with law</b></p>
								<p style="text-align: justify;">All employees must protect our company’s legality. They should comply with all environmental, safety and fair dealing laws. We expect employees to be ethical and responsible when dealing with our company’s finances, products, partnerships and public image.</p>
								<p><b>2. Employee Grievances and resolution</b></p>
								<p style="text-align: justify;">We acknowledge the importance of offering employees a means to resolve their grievances in cases where their immediate supervisors or the HR team at their office locations are unable to address them.</p>

								<p style="text-align: justify;">To cater to this, Cogent has established multiple channels through the Employee Management System (EMS) for issue resolution. Management contacts and a helpline (Happy to Help) are accessible to all employees, and they are strongly encouraged to utilize these resources.</p>
								<p style="text-align: justify;">Engaging in actions that aim to tarnish the company's image deliberately and with malicious intent, such as raising issues on social media platforms like LinkedIn, Twitter, Facebook, YouTube, Instagram, or similar platforms, or seeking assistance from client management without following internal channels, is considered unnecessary and avoidable. The company views such behavior as detrimental to its reputation and reserves the right to take disciplinary action against individuals who engage in such activities.</p>

								<p><b>3. Respect in the workplace</b></p>
								<p style="text-align: justify;">All employees should respect their colleagues. We won’t allow any kind of discriminatory behavior, harassment or victimization. This includes any harassment in workplace including Sexual Harassment – refer to our POSH guidelines. Employees should conform with our equal opportunity policy in all aspects of their work, from recruitment and performance evaluation to interpersonal relations.</p>
								<p><b>4. Job duties and authority</b></p>
								<p style="text-align: justify;">All employees should fulfil their job duties with integrity and respect toward customers, stakeholders and the community. </p>
								<p style="text-align: justify;">We don’t tolerate malicious, deceitful or petty conduct for e.g. data manipulation, fraudulent activity on customer accounts etc. These are huge red flags and, if you’re discovered, you may face progressive discipline or immediate termination / criminal prosecution, depending on the severity of the issue.</p>
								<p style="text-align: justify;">Working under the influence of alcohol or drugs, or consuming alcohol or drugs during hours of work, including paid and unpaid breaks, is unacceptable behavior. Employees found in possession of illegal drugs or using illegal drugs while at work will be reported to the police and their employment terminated with immediate effect.</p>
								<p><b>5. Company asset</b></p>
								<p style="text-align: justify;">Employee shouldn’t misuse company equipment or use it frivolously. A company asset provided to the employee in office or at a remote / home location must be maintained properly and returned in good working condition on due completion of the assignment / project. Failure to do so may lead to financial recovery or legal action. </p>
								<p style="text-align: justify;">Should respect all kinds of incorporeal property. This includes trademarks, copyright and other property (information, reports etc.) Employees should use them only to complete their job duties.</p>
								<p><b>6. Absenteeism and tardiness</b></p>
								<p style="text-align: justify;">Employees should follow their schedules. We expect employees to be punctual when coming to and leaving from work.</p>
								<p><b>7. Conflict of interest</b></p>
								<p style="text-align: justify;">We expect employees to avoid any personal, financial or other interests that might hinder their capability or willingness to perform their job duties.</p>
								<p><b>8. Dual Employment</b></p>
								<p style="text-align: justify;">To ensure that employees provide their full time and energy to their current job, Cogent does not permit dual employment. An employee must be formally relieved of his / her services with their previous employer before taking up any employment opportunity with Cogent. Failure to do so may lead to immediate termination of employment.</p>
								<p style="align-content: center;"><b><u>EMPLOYEE DECLARATION</u></b></p>
								<p style="text-align: justify;">I , do hereby declare that I have fully read and understood the Code of Conduct policy of Cogent E Services and agree to comply with the same. I understand that any non-compliance to the above policies may lead to disciplinary sanctions that can include up to termination of employment and even criminal prosecution under applicable laws.</p>

							</div>
							<div class="input-field col s12 m3 l3">
								<input type="checkbox" name="policy" id="policy" tabindex="7" value='1' />
								<label for="policy">Acknowledged</label>
								<div id='checkval' style="color:red;display:none;padding-top:15px;">Please Confirm Code Of Conduct Policy</div>
							</div>
							<br /><br />
							<div class="col s12 m12 l12 no-padding right-align">
								<button type="submit" id="submit" name="submit" class="btn waves-effect waves-green" tabindex="8">Register</button>
								<a href="<?php echo URL . 'View/'; ?>" class="btn waves-effect waves-light close-btn" tabindex="9">Cancel</a>
							</div>
							<div class="col s12 m12 l12 no-padding">
								<?php
								if (!empty($msg)) {
									echo $msg;
								}
								?>
							</div>
						</div>

					</div>

					<!--Form container End -->
				</div>
				<!--Main Div for all Page End -->
			</div>
			<!--Content Div for all Page End -->
		</div>
		<script>
			$(document).on("click", ".has-error", function() {
				$('#password').removeClass('has-error');
				$('#password_confirmation').removeClass('has-error');
				$('#emp_id').removeClass('has-error');
				$('#dob').removeClass('has-error');
				$('#doj').removeClass('has-error');
				$('#sec_qusn').removeClass('has-error');
				$('#sec_asn').removeClass('has-error');
			});
			$(function() {


				$("#submit").click(function() {

					var validate = 0;
					$('#password').removeClass('has-error');
					$('#password_confirmation').removeClass('has-error');
					$('#emp_id').removeClass('has-error');
					$('#dob').removeClass('has-error');
					$('#doj').removeClass('has-error');
					$('#sec_qusn').removeClass('has-error');
					$('#sec_asn').removeClass('has-error');


					if ($('#emp_id').val() == "" || $('#emp_id').val() == "NA") {
						$('#emp_id').addClass('has-error');
						validate = 1;
					}
					if ($('#password').val() == "" || $('#password').val() == "NA") {
						$('#password').addClass('has-error');
						validate = 1;
					}
					if ($('#password_confirmation').val() == "" || $('#password_confirmation').val() == "NA") {
						$('#password_confirmation').addClass('has-error');
						validate = 1;
					}

					if ($('#dob').val() == "" || $('#dob').val() == "NA") {
						$('#dob').addClass('has-error');
						validate = 1;
					}
					if ($('#doj').val() == "" || $('#doj').val() == "NA") {
						$('#doj').addClass('has-error');
						validate = 1;
					}

					if ($('#sec_qusn').val() == "" || $('#sec_qusn').val() == "NA") {
						$('#sec_qusn').addClass('has-error');
						validate = 1;
					}
					if ($('#sec_asn').val() == "" || $('#sec_asn').val() == "NA") {
						$('#sec_asn').addClass('has-error');
						validate = 1;
					}
					if ($('#result').html() == 'Too short') {
						$('#password').addClass('has-error');
						validate = 1;
					}

					if ($('#password').val() != $('#password_confirmation').val()) {
						$('#password').addClass('has-error');
						$('#password_confirmation').addClass('has-error');
						validate = 1;
					}
					if (!$('#policy').prop('checked')) {

						$('#checkval').show();
						validate = 1;
					} else {
						$('#policy').val('Yes');
						$('#checkval').hide();
					}
					// alert($('#policy').val());
					if (validate == 1) {
						return false;
					}
				});
			});
			$(document).on("click blur focus change", '.input-field input:not([type="checkbox"]),.input-field textarea', function() {

				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {

					if ($(element).val().length > 0) {
						$(this).siblings('label, i').addClass('active');
					} else {
						if ($(this).attr("id") != $(':focus').attr("id"))
							$(this).siblings('label, i').removeClass('active');
					}

				});
			});

			function openPopup() {
				window.open('signup_policy_popup.php', "", "height=300,width=500");

			}
		</script>
		<div class="center-align">
			<div class="footer">Cogent EMS<br>
				&copy; <?php echo date('Y') . '  -  ' . date('Y', strtotime("next year")); ?><a href="http://www.cogenteservices.com" target="_blank"> Cogent ES </a> All rights reserved.</div>
		</div>
	</form>
</body>

</html>