<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
date_default_timezone_set('Asia/Kolkata');

//empid
if (isset($_REQUEST['empid']) && $_REQUEST['empid'] != '') {
	$empid = base64_decode(urldecode($_REQUEST['empid']));
	//$empid = $_REQUEST['empid'];
} else {
	$location = URL;
	header("Location: $location");
}
$edu = $empname = $verified = $filename = $edu_name = '';

$MSG = '';
if (isset($_POST['UploadBtn'])) {
	if (isset($_FILES['fileToUpload']['name']) && $_FILES['fileToUpload']['name'] != "") {

		$filePath = ROOT_PATH . "emp_edu/";
		$sourcePath = $_FILES['fileToUpload']['tmp_name'];
		$targetPath = $filePath . basename($_FILES['fileToUpload']['name']);

		$FileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
		$uploadOk = 1;
		if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf") {
			$msg = "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg,png and pdf files are allowed.'); }); </script>";
			$uploadOk = 0;
		}
		if ($_FILES['fileToUpload']['size'] > 2000000) {
			$msg = "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 2MB File only.'); }); </script>";
			$uploadOk = 0;
		}

		if ($uploadOk == 1) {
			$targetPath = $filePath . basename($_FILES['fileToUpload']['name']);
			if (move_uploaded_file($sourcePath, $targetPath)) {

				$ext = pathinfo(basename($_FILES['fileToUpload']['name']), PATHINFO_EXTENSION);

				$edu = $_POST['hidden_edu'];
				//die;
				if ($edu == "Graduation") {
					$edu_name = "graduation";
				} else if ($edu == "Post Graduation") {
					$edu_name = "postgraduation";
				}

				$filename = $empid . '_' . $edu_name . '.' . $ext;
				$file = rename($targetPath, $filePath . $filename);
				if (file_exists($filePath . $filename)) {
					$myDB = new MysqliDb();
					$sqlInsertDoc = 'update emp_edu set filename="' . $filename . '",modifiedon=now(),flag=1,verified_flag=3 where EmpID="' . $empid . '" ';
					$result = $myDB->rawQuery($sqlInsertDoc);
					$row_count = $myDB->count;
					if ($row_count > 0) {

						$msg = "<script>$(function(){ toastr.success('The file has been uploaded.'); }); </script>";
					}
				}
			} else {
				$msg = "<script>$(function(){ toastr.error('The file not uploaded.'); }); </script>";
			}
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
			<span id="PageTittle_span" class="hidden">Upload Education Details</span>

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
	    "></div>Upload Education Details
					</h4>

					<!-- Form container if any -->
					<div class="schema-form-section row">

						<div class="scheduler-border">
							<?php
							$myDB = new MysqliDb();
							$conn = $myDB->dbConnect();
							$sqlquery = "select t1.EmpID,EmpName, edu_type,verified_flag,filename from emp_edu t1 left join EmpID_Name t2 on t1.EmpID=t2.EmpID where t1.EmpID=?";
							$selectQ = $conn->prepare($sqlquery);
							$selectQ->bind_param("s", $empid);
							$selectQ->execute();
							$results = $selectQ->get_result();
							$result = $results->fetch_row();
							// $result = $myDB->query($sqlquery);
							if ($results->num_rows > 0) {
								$edu = $result[2];
								$empname = $result[1];
								$verified = $result[3];
								$filename = trim($result[4]);
							}

							if ($verified == '0' || $verified == '2') {
							?>
								<input type="hidden" id="hidden_edu" name="hidden_edu" value="<?php echo $edu; ?>" />
								<div class="file-field input-field col s12 m12" id="divMsg">
									<b>
										<p> Hi <?php echo $empname ?> ,Please upload your <?php echo $edu; ?> document</p>

									</b>
								</div>
								<div class="file-field input-field col s6 m6">
									<div class="btn">
										<span>Browse File</span>
										<input type="file" id="fileToUpload" name="fileToUpload" required style="text-indent: -99999em;">

									</div>
									<div class="file-path-wrapper">
										<input class="file-path" type="text" style="">
									</div>
								</div>
								<div class="input-field col s12 m12">
									<input type="submit" name="UploadBtn" id="UploadBtn" value="Upload" class="btn waves-effect waves-green" />

								</div>

								<div class="col s12 m12 l12 no-padding">
									<?php
									if (!empty($msg)) {
										echo $msg;
									}
									?>
								</div>
							<?php
							} else { ?>

								<div class="file-field input-field col s12 m12">
									<b>
										<p style="text-align: justify;"> Hi <?php echo $empname ?> , your <?php echo $edu; ?> document has been uploaded.</p>

									</b>
								</div>
							<?php } ?>
						</div>

					</div>

					<!--Form container End -->
				</div>
				<!--Main Div for all Page End -->
			</div>
			<!--Content Div for all Page End -->
		</div>

		<div class="center-align">
			<div class="footer">Cogent EMS<br>

			</div>
		</div>
	</form>
</body>

</html>