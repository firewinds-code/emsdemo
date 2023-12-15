<?php
$EmployeeID = cleanUserInput($_SESSION['__user_logid']);
$NotApplicable = array();
if (isset($_SESSION)) {
	if (!isset($EmployeeID)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}
$sqlQry = "SELECT * FROM emp_auth WHERE EmployeeID= ?";
$stmt = $conn->prepare($sqlQry);
$stmt->bind_param('s', $EmployeeID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
// echo "<pre>";
// print_r($row);
// print_r($row['flag']);
if ($row['flag'] == 0) {
	// $location = URL . 'View/index.php';
	session_unset();
	session_destroy();
	echo "<script type='text/javascript'>location.href = '" . URL . "LogIn';</script>";
}

/*if($_SESSION['__cm_id'] == '47')
	{
		$myDB=new MysqliDb();
		$result = $myDB->query("SELECT aadhar_status FROM aadhar_verifiaction where EmployeeID = '".$_SESSION['__user_logid']."' and aadhar_status ='verified' limit 1; ");
		if(count($result) <= 0)
		{
			$location= "https://demo.cogentlab.com/erpm/Controller/conLog_ems.php?empid=".$_SESSION['__user_logid']."&tfs=1";
			//$location= URL.'View/aadhar_verification.php';
			echo "<script>location.href='".$location."'</script>";
		}
	}*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
	<title></title>
	<?php include_once(ROOT_PATH . 'AppCode/head.mpt'); ?>
	<?php include(ROOT_PATH . 'AppCode/DataTable.mpt'); ?>
	<link rel="stylesheet" href="<?php echo STYLE . 'Theme2.css'; ?>">
	<link href="../FileContainer/crosscover-1.0.2/dist/css/crosscover.min.css" rel="stylesheet">
	<script src="../FileContainer/crosscover-1.0.2/dist/js/crosscover.min.js" charset="utf-8"></script>
	<link rel="stylesheet" href="<?php echo STYLE . 'jquery.datetimepicker.css'; ?>" />
	<script src="<?php echo SCRIPT . 'jquery.datetimepicker.full.min.js'; ?>"></script>
	<script src="<?php echo SCRIPT . 'base64.js'; ?>"></script>


	<script>
		function toTitleCase(str) {
			return str.replace(/(?:^|\s)\w/g, function(match) {
				return match.toUpperCase();
			});
		}

		$(function() {
			$(document).prop('title', "  " + $("#PageTittle_span").text() + "    ");
			$("span.page-title").html($("#PageTittle_span").text());
			$("#preloader").hide();
			$("select").find('option:eq(0)').each(function() {
				if ($(this).text().toUpperCase().indexOf('-SELECT-') >= 0 && ($(this).val().toUpperCase() == "" || $(this).val().toUpperCase() == "NA")) {
					var lbl_text = $(this).closest("div.select-wrapper").next("label.active").text();
					var PreSuffix = 'Select ';
					if (lbl_text.toUpperCase().indexOf('SELECT') >= 0) {
						PreSuffix = '';
					}
					if (lbl_text != '' && lbl_text != undefined) {
						lbl_text = lbl_text;
					}
					var finalOption = toTitleCase(PreSuffix + lbl_text);
					$(this).text(finalOption);
				}
			});
			$("select").formSelect();
		});
	</script>
	<style>
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
		<div class="preloader-wrapper active big">
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
		<div id="wrapper">
			<section id="content" class="content">
				<div id="left-menu" class="menu-visible disablediv">
					<?php require_once(ROOT_PATH . 'AppCode/left_menu.php'); ?>
				</div>
				<div id="right-side" class="menu-visible">
					<header id="header-holder">
						<div id="primary-header" class="customized-orange">
							<div class="navbar disablediv">
								<nav class="subNav z-depth-0">
									<div class="nav-wrapper customized-orange">
										<ul class="left">
											<li class=""></li>
											<li><span class="page-title">Home</span>
												<!--<span class="page-title appended-title"></span>-->
											</li>
										</ul>
										<ul class="right" ng-if="navbar.permissions.read">

											<?php
											if ($_SESSION["__alert"] == "") {
												$sql_alert1 = 'select count(*) as Alert from alert_details left outer join whole_dump_emp_data on whole_dump_emp_data.EmployeeID = alert_details.EmployeeID  where (curdate() between alert_details.alert_start and alert_end) and  (alert_details.EmployeeID = ? or account_head = ? or oh =  ? or th = ? or ReportTo = ?  or  qh = ? or "CE03070003" =  ? or "CE031929841" =  ? or  (select true from whole_details_peremp where cm_id  = "37" and des_id in  (1,5,7,8,10) and  EmployeeID = ?))';
												$selectQ = $conn->prepare($sql_alert1);
												$selectQ->bind_param("sssssssss", $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID);
												$selectQ->execute();
												$results = $selectQ->get_result();
												$count_alert_hdr = $results->fetch_row();
												// $alert_con = new MysqliDb();
												// $count_alert_hdr = $alert_con->query($sql_alert1);
												$tmp_alert_hdr_count = (empty(clean($count_alert_hdr[0])) ? '0' : clean($count_alert_hdr[0]));
												$_SESSION["__alert"] = $tmp_alert_hdr_count;
											} else {
												$tmp_alert_hdr_count = $_SESSION["__alert"];
											}

											#$tmp_alert_hdr_count = 0;
											if ($_SESSION["__announce"] == "") {
												$sql_announce1 = 'select count(*) as Announce from whole_details_peremp inner join announcement_inproc on whole_details_peremp.cm_id = announcement_inproc.cm_id left outer join acknowledge_details on whole_details_peremp.EmployeeID = acknowledge_details.EmployeeID and announcement_inproc.id = acknowledge_details.action_id where whole_details_peremp.EmployeeID = ? and acknowledge_details.EmployeeID is null';
												// $announce_con = new MysqliDb();
												// $count_annunce_hdr = $announce_con->query($sql_announce1);
												$selectQr = $conn->prepare($sql_announce1);
												$selectQr->bind_param("s", $EmployeeID);
												$selectQr->execute();
												$results = $selectQr->get_result();
												$count_annunce_hdr = $results->fetch_row();

												$tmp_announce_hdr_count =  (empty(clean($count_annunce_hdr[0])) ? '0' : clean($count_annunce_hdr[0]));
												$_SESSION["__announce"] = $tmp_announce_hdr_count;
											} else {
												$tmp_announce_hdr_count = $_SESSION["__announce"];
											}

											if ($_SESSION["__chatmsg"] == "") {
												$sql_message1 = 'select  count(*) Message from tbl_chat_message t1 where to_empid =  ?';
												// $message_con = new MysqliDb();
												// $count_message_hdr = $message_con->query($sql_message1);
												$selectQry = $conn->prepare($sql_message1);
												$selectQry->bind_param("s", $EmployeeID);
												$selectQry->execute();
												$results = $selectQry->get_result();
												$count_message_hdr = $results->fetch_row();

												$tmp_message_hdr_count =   (empty(clean($count_message_hdr[0])) ? '0' : clean($count_message_hdr[0]));
												$_SESSION["__chatmsg"] = $tmp_message_hdr_count;
											} else {
												$tmp_message_hdr_count = $_SESSION["__chatmsg"];
											}

											$total_notification_count = $tmp_alert_hdr_count + $tmp_announce_hdr_count + $tmp_message_hdr_count;

											?>

											<style>
												.disabled {
													pointer-events: none;
													opacity: 0.6;
												}
											</style>
											<?php
											$class = '';
											if ($tmp_alert_hdr_count == 0) {
												$class = 'disabled';
											} else {
												$class = 'highlight-icon';
											}
											?>
											<li class="<?php echo $class; ?>" style="padding-top: 6px;padding-left:4px">
												<a class="dropdown-button handCurser tooltipped" data-position="bottom" data-tooltip="Alerts" href="<?php echo URL . 'View/alert_module.php'; ?>">
													<span class="quickAccessIcon material-icons">notifications_active</span>
													<span class="noOfNotifications"><?php echo $tmp_alert_hdr_count; ?></span>
												</a>
											</li>

											<li class="highlight-icon" style="padding-top: 6px;padding-left:4px">
												<a class="dropdown-button handCurser tooltipped" data-position="bottom" data-tooltip="Announcement" href="<?php echo URL . 'View/announcement_inproc.php'; ?>">
													<span class="quickAccessIcon material-icons">announcement</span>
													<span class="noOfNotifications"><?php echo $tmp_announce_hdr_count; ?></span>
												</a>
											</li>

											<li class="highlight-icon" style="padding-top: 6px;padding-left:4px">
												<a class="dropdown-button handCurser tooltipped" data-position="bottom" data-tooltip="Message" href="<?php echo URL . 'View/message-popup.php'; ?>">
													<span class="quickAccessIcon material-icons">markunread</span>
													<span class="noOfNotifications"><?php echo $tmp_message_hdr_count; ?></span>
												</a>
											</li>

											<?php
											if (($_SESSION['__status_th'] == $_SESSION['__user_logid'] || $_SESSION['__status_oh'] == $_SESSION['__user_logid'] || $_SESSION['__status_qh'] == $_SESSION['__user_logid']) || ((($_SESSION['__status_ah'] != 'No' && $_SESSION['__status_ah'] == $_SESSION['__user_logid']) && $_SESSION['__status_ah'] != ''))) {

												// $pendancy_con = new MysqliDb();
												$sql = "select sum(count) count from pendancywith where EmployeeID=?";
												$selectQury = $conn->prepare($sql);
												$selectQury->bind_param("s", $EmployeeID);
												$selectQury->execute();
												$results = $selectQury->get_result();
												$countpendancy_hdr = $results->fetch_row();

												$tmp_countpendancy_hdr = (empty(clean($countpendancy_hdr[0])) ? '0' : clean($countpendancy_hdr[0]));

											?>
												<li class="highlight-icon <?php if ($tmp_countpendancy_hdr > 0) { ?> animated infinitedt flash <?php } ?>" style="padding-top: 6px;padding-left:4px">
													<a class="dropdown-button handCurser tooltipped" data-position="bottom" data-tooltip="Pendency: Leave, Exception and Downtime" href="<?php echo URL . 'View/dashboard_common.php'; ?>">
														<span class="quickAccessIcon material-icons">insert_invitation</span>
														<span class="noOfNotifications"><?php echo $tmp_countpendancy_hdr; ?></span>
													</a>
												</li>
											<?php
											}
											?>

											<li class="highlight-icon" style="padding-top: 6px;padding-left:4px">
												<a class="dropdown-button handCurser tooltipped" data-position="bottom" data-tooltip="Referral Bonanza" href="<?php echo URL . 'View/ref_registration.php'; ?>">
													<span class="quickAccessIcon material-icons">people</span>

												</a>
											</li>
										</ul>
									</div>
								</nav>
							</div>

						</div>
					</header>
					<?php ini_set("display_errors", "0");	 ?>