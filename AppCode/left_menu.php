<script>
$(function() {
    $(".collapsible li").removeClass("current").removeClass("active");
    $(".collapsible a").removeClass("current").removeClass("active");

    var loc_left_menu = window.location.href;
    if ($(".collapsible a[href ='" + loc_left_menu + "']").length > 0) {
        if ($(".collapsible a[href ='" + loc_left_menu + "']").length == 1) {
            $(".collapsible a[href ='" + loc_left_menu + "']").addClass("current");
            $(".collapsible a[href ='" + loc_left_menu + "']").closest("li.level1").addClass("active current");
            $(".collapsible a[href ='" + loc_left_menu + "']").closest("li.level2").addClass("active current");

        } else {

        }

    } else {

        if (loc_left_menu == <?php echo '"' . URL . 'View/"'; ?> || loc_left_menu ==
            <?php echo '"' . URL . 'View/index"'; ?>) {
            /*$("#menu_search_empsrch").addClass("current");
				$("#menu_search_empsrch").closest("li.level1").addClass("active current");
				$("#menu_search_empsrch").closest("li.level2").addClass("active current");
			*/
        }

    }

});
</script>
<style>
#main-menu #menu-content li.level2 a {
    display: inline;
}

#main-menu #menu-content li.level2 a.current {

    display: inline-block;

    padding-top: 6px;
}
</style>
<?php
$location = clean($_SESSION["__location"]);
$EmployeeID = clean($_SESSION['__user_logid']);
$__user_Name = clean($_SESSION['__user_Name']);
$user_profile = clean($_SESSION["__user_profile"]);
$user_type = clean($_SESSION["__user_type"]);
$_user_Desg = clean($_SESSION['__user_Desg']);
$ut_temp_check = clean($_SESSION["__ut_temp_check"]);
$user_Dept = clean($_SESSION['__user_Dept']);
$user_process = clean($_SESSION['__user_process']);
$cm_id = clean($_SESSION["__cm_id"]);
$status_th = clean($_SESSION['__status_th']);
$status_oh = clean($_SESSION['__status_oh']);
$status_qh = clean($_SESSION['__status_qh']);
$status_ah = clean($_SESSION['__status_ah']);
$status_vh = clean($_SESSION['__status_vh']);
$status_rt = clean($_SESSION["__status_rt"]);
$user_status = clean($_SESSION["__user_status"]);
$status_er = clean($_SESSION['__status_er']);
$_gender = clean($_SESSION['__gender']);
$status_tr = clean($_SESSION['__status_tr']);
$status_qa = clean($_SESSION['__status_qa']);
$user_client_ID = clean($_SESSION['__user_client_ID']);
$cloc = '';
if (isset($location)) {
	$cloc = $location;
}

$pmagespath = '';
if ($cloc == "1") {

	$pmagespath = '';
} else if ($cloc == "2") {
	$pmagespath = '';
} else if ($cloc == "3") {

	$pmagespath = 'Meerut/';
} else if ($cloc == "4") {
	$pmagespath = "Bareilly/";
} else if ($cloc == "5") {

	$pmagespath = "Vadodara/";
} else if ($cloc == "6") {
	$pmagespath = "Manglore/";
} else if ($cloc == "7") {
	$pmagespath = "Bangalore/";
}

?>
<div id="ohrm-small-logo">
    <div style="background-image: url('<?php echo STYLE . '/images/Cogent.png'; ?>');background-position: 20px 5px;
    background-size: 85px 35px;"></div>
</div>
<a class="circle black-text" id="side-menu-hamburger" href="javascript:;">
    <i class="tiny material-icons ng-binding">close</i>
</a>
<div id="menu-container">
    <div id="main-menu">
        <div>
            <div id="menu-profile-background">
                <img alt="user" src="<?php

										if (file_exists("../" . $pmagespath . "Images/" . $user_profile) && $user_profile != '') {
											echo "../" . $pmagespath . "Images/" . $user_profile;
										} else
							if (file_exists("../Images/" . $user_profile) && $user_profile != '') {
											echo "../Images/" . $user_profile;
										} else {
											echo "../Style/images/agent-icon.png";
										}
										?>" />
            </div>
            <div id="menu-profile" class="center">
                <a href="<?php echo URL . 'View/info?empid=' . $EmployeeID; ?>"><img src="<?php

																							if (file_exists("../" . $pmagespath . "Images/" . $user_profile) && $user_profile != '') {
																								echo "../" . $pmagespath . "Images/" . $user_profile;
																							} else
								if (file_exists("../Images/" . $user_profile) && $user_profile != '') {
																								echo "../Images/" . $user_profile;
																							} else {
																								echo "../Style/images/agent-icon.png";
																							}
																							?>" style="border: 0;width: 75px;height: 75px;border-radius: 51px;margin: 14px auto;" /></a>

                <a id="user-dropdown" data-gutter="200" data-alignment="left" data-constrainwidth="false"
                    class="dropdown-trigger waves-effect waves-light" href="javascript:;" data-activates="user_menu"
                    data-target='user_menu' ohrm-dropdown="">
                    <span id="account-name"><?php echo $__user_Name ?> </span>
                    <br>
                    <span id="account-job"><?php echo $_user_Desg ?></span><br>
                    <span id="account-hold"
                        style="font-size: 10px;"><?php echo $user_Dept . '(' . $user_process . ')'; ?></span>

                </a>

                <ul id="user_menu" class="dropdown-content">
                    <li><a href="<?php echo URL . 'View/info?empid=' . $EmployeeID; ?>"><i
                                class="Tiny material-icons">airplay</i>&nbsp;&nbsp;&nbsp;&nbsp;Profile </a></li>
                    <li><a href="<?php echo URL . 'View/b_list.php'; ?>"><i
                                class="Tiny material-icons">art_track</i>&nbsp;&nbsp;&nbsp;&nbsp;B'Day List</a></li>
                    <li><a href="<?php echo URL . 'View/Logout'; ?>"><i
                                class="Tiny material-icons">block</i>&nbsp;&nbsp;&nbsp;&nbsp;Logout</a></li>
                </ul>

            </div>
            <div id="my-shortcuts">
                <a id="shortcut-menu-trigger" data-gutter="200" data-alignment="left" data-constrainwidth="false"
                    class="dropdown-trigger waves-effect waves-orange" href="javascript:;"
                    data-activates="shortcut_menu" data-target='shortcut_menu'>
                    <span class="material-icons left-menu-icon">ohrm_shortcuts</span>
                    <span class="left-menu-title">My Shortcuts</span>
                </a>
                <ul id="shortcut_menu" class="dropdown-content" style="">
                    <li><a href="<?php echo URL . 'View/index'; ?>">Home</a></li>
                    <?php
					if (($user_type == 'ADMINISTRATOR' && $ut_temp_check == 'ADMINISTRATOR') || ($user_type == 'HR')) { ?>
                    <li><a href="<?php echo URL . 'View/enrolled_employee.php'; ?>">Add Employee</a></li>
                    <li><a href="<?php echo URL . 'View/admin'; ?>">DashBoard</a></li>

                    <?php
					} ?>

                    <!--<?php
						if ($cm_id == '27' || $cm_id == '58' || $cm_id == '83' || $cm_id == '45' || $cm_id == '46' || $cm_id == '126' || $cm_id == '90') { ?>
					  	<li><a href="<?php echo URL . 'View/atnd1'; ?>">My Attendance</a></li> -->



                    <!--<?php } else { ?>-->
                    <li><a href="<?php echo URL . 'View/atnd'; ?>">My Attendance</a></li>
                    <!--<?php
						} ?>-->


                    <li><a href="<?php echo URL . 'FileContainer/ISMS Awareness Training.pdf'; ?>" target="_blank">ISMS
                            Awareness</a></li>
                </ul>
            </div>
            <div id="menu-content">
                <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
                    <?php if (($status_th == $EmployeeID || $status_oh == $EmployeeID || $status_qh == $EmployeeID) || ($status_ah != 'No' && $status_ah == $EmployeeID && $status_ah != '') || ($status_vh != "" && $status_vh == $EmployeeID)) {
					?>

                    <li class="level1 Administrator no-padding parent" id="menu_pim_viewPimModule">
                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-dashboard"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Dashboard</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>

                        <div class="collapsible-body">
                            <?php
								if (isset($EmployeeID) && $EmployeeID != "") { ?>

                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <!--<a class="waves-effect waves-orange " href="check_session.php?db=dashboard" target="_blank">-->
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/check_session.php?db=dashboard'; ?>"
                                        target="_blank">
                                        <span class="left-menu-title">Delivery Dashboard</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/check_session.php?db=dashboard2'; ?>"
                                        target="_blank">
                                        <span class="left-menu-title">Quality Dashboard</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/check_session.php?db=quality'; ?>" target="_blank">
                                        <span class="left-menu-title">Designation Wise Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>
                                <?php if ($EmployeeID == 'CEK07120002' || $EmployeeID == 'CE07147134' || $EmployeeID == 'CE03070003' || $EmployeeID == 'CE031929841' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE0221936010') { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/check_session.php?db=panindia'; ?>"
                                        target="_blank">
                                        <span class="left-menu-title">Pan India Dashboard</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>

                                <?php  } ?>
                            </ul>
                            <?php } ?>
                        </div>
                    </li>
                    <?php
					}
					if ($ut_temp_check == 'ADMINISTRATOR' || $ut_temp_check == 'HR') {
					?>
                    <li class="level1 Administrator no-padding parent" id="menu_pim_viewPimModule">
                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-cogs"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Administrator</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <?php
									if ($EmployeeID == 'CE03070003' || $EmployeeID == 'CE031929841') {
									?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'lib/dreports'; ?>"
                                        id="menu_Attendance_atnd">
                                        <span class="left-menu-title">Report Manager</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_gatepass.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Gate Pass Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php
									} ?>

                                <?php
									if ($user_type == 'ADMINISTRATOR' && $ut_temp_check == 'ADMINISTRATOR') { ?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Masters</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MsDept'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Department</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MsDesg'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Designation</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MsCli'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Client</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/client_subprocess_manage.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Process Staus</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/trnE'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Transfer</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/trnP'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Transfer Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/salms'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Salary Certificate Master</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <!--<li class="level3">
											<a class="waves-effect waves-orange" href="<?php echo URL . 'View/MsAtndUp123'; ?>">
												<span class="material-icons left-menu-icon"></span>
												<span class="left-menu-title">Attendance Upload</span>
												<span class="material-icons collapsible-indicator"></span>
											</a>
										</li>-->

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ithdk_handle_mails.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">IT-Helpdesk Mails</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/wmt'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Welcome Mail Template</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/module-master'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Module Master</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/master-report'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Module Master Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ReportManager'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Dynamic Report Master</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ReportData'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Dynamic Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MsAprMaster'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Appraisal Master</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/Ref_Master'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Reference Master</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <!-- <li class="level3">
														<a class="waves-effect waves-orange" href="<?php echo URL . 'View/MsRefData'; ?>">
															<span class="material-icons left-menu-icon"></span>
															<span class="left-menu-title">Reference Scheme</span>
															<span class="material-icons collapsible-indicator"></span>
														</a>
													</li> -->
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_ref_register.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Reference Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MsIssue'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Issue</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/edit-downtime.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Downtime</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/edit-downtime_time_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">CT/OJT Downtime</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/edit-buddy_downtime_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Buddy Downtime</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/exc_calc.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Attendance</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/salary_slab_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Salary Slab</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Master Data</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/district_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">District</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/edu_board_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Education Board</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/edu_specialization_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Education Specialization</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/Versant_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Test Master</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/bank_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Bank Master</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Consultancy</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MsConsult'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Master Consultancy</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MngConsult'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Manage Consultancy</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Human Resources</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MsAnnounce'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Announcements</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MsUpdates'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Updates</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/alteration.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Alteration</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/AlterationReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Alteration Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/announcement_inproc_master.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">InProccess Announce</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php
													if ($EmployeeID == 'CE03070003' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE031929841') {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_payroll.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Payroll</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/upload_sal_contents.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Upload Payroll Content</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ipp_handle.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Home Page Edit</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/Login_trnfr.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Proxy Login</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>


                                            <?php
													} ?>
                                        </ul>
                                    </div>
                                </li>

                                <?php
									} ?>
                                <?php
									if (($user_type == 'ADMINISTRATOR' && $ut_temp_check == 'ADMINISTRATOR') || ($user_type == 'HR')) { ?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">

                                        <span class="left-menu-title">More</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/enrolled_employee.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Add Employee</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/admin'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">DashBoard</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3 hidden">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/MsInfo'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">HR Info</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>


                                        </ul>
                                    </div>
                                </li>
                                <?php
									} ?>
                            </ul>
                        </div>
                    </li>
                    <?php
					}
					?>
                    <!--<li class="level1 search no-padding parent">
               <a class="waves-effect waves-orange " href="<?php echo URL . 'View/index.php'; ?>" id="menu_search_empsrch">             <i class="fa fa-lg fa-home"></i>&nbsp;&nbsp;&nbsp;&nbsp;
	               <span class="left-menu-title">Home</span>
               
               </a>
				               
			</li>-->


                    <?php
					if ($user_type != 'MIS' && $user_type != 'EXECUTIVE' && $ut_temp_check != 'CENTRAL MIS') {
					?>

                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/empsrch'; ?>"
                            id="menu_search_empsrch">
                            <i class="fa fa-lg fa-search"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Employee Search</span>

                        </a>

                    </li>
                    <?php
					}
					?>

                    <?php
					if ($EmployeeID == 'CE03070003' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE03146043') {

					?>
                    <li class="level1 Attendance no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-calendar">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Aadhar</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo "https://demo.cogentlab.com/erpm/Controller/conLog_ems.php?empid=" . $EmployeeID . "&tfs=1&login=admin"; ?>"
                                        id="menu_Aadhar_aadh" target="_blank">
                                        <span class="left-menu-title">Aadhar Verification</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/avr.php'; ?>"
                                        id="menu_Aadhar_aadh">
                                        <span class="left-menu-title">Aadhar Report</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>

                            </ul>
                        </div>
                    </li>

                    <?php
					}
					?>

                    <?php
					$itheldeskuser = array('CE10091236', 'CE0820912502', 'CE021929775', 'CE06080411', 'CE061510258', 'CE121513568', 'CE03070014', 'CE11091308', 'CE051829442', 'CE032030314', 'CE04146130', 'CE081829502', 'CE0721938918', 'CE0321936392', 'CE0321936799', 'CE0821939544');
					if (in_array($EmployeeID, $itheldeskuser)) {

					?>
                    <li class="level1 Attendance no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-calendar">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">IT Helpdesk</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">


                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/ithdk_raise_request_web.php'; ?>"
                                        id="ithdk_raise_request_web">
                                        <span class="left-menu-title">Raise Ticket</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/ithdk_issue_list_and_handling.php'; ?>"
                                        id="ithdk_issue_list_and_handling">
                                        <span class="left-menu-title">View Ticket</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>

                            </ul>
                        </div>
                    </li>

                    <?php
					}
					?>

                    <li class="level1 Attendance no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-calendar">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Attendance</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/atnd'; ?>"
                                        id="menu_Attendance_atnd">
                                        <span class="left-menu-title">My Attendance</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>

                                <?php


								// $myDB = new MysqliDb();
								$sel = 'select type_ from roster_temp where EmployeeID = ? and DateOn =cast(now() as date) order by id desc limit 1';
								$selectQ = $conn->prepare($sel);
								$selectQ->bind_param("s", $EmployeeID);
								$selectQ->execute();
								$results = $selectQ->get_result();
								$rst = $results->fetch_row();

								$exp1 = $exp2 = 0;
								if ($results->num_rows > 0) {
									if (intval($rst[0]) != 0) {
										if ($rst[0] == '4') {
											$exp1 = 1;
											$exp2 = 0;
								?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_Attendance_addReq"
                                        href="<?php echo URL . 'View/addReq1'; ?>">
                                        <span class="left-menu-title">Exception Request [Split]</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <?php
										} else {
											$exp2 = 1;
											$exp1 = 0;
										?>


                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_Attendance_addReq"
                                        href="<?php echo URL . 'View/addReq'; ?>">
                                        <span class="left-menu-title">Exception Request</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <?php
										}
									} else {
										$exp2 = 1;
										$exp1 = 0;
										?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_Attendance_addReq"
                                        href="<?php echo URL . 'View/addReq'; ?>">
                                        <span class="left-menu-title">Exception Request</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <?php
									}
								} else {
									$exp2 = 1;
									$exp1 = 0;
									?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_Attendance_addReq"
                                        href="<?php echo URL . 'View/addReq'; ?>">
                                        <span class="left-menu-title">Exception Request</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <?php
								}
								/*$myDB=new MysqliDb();
					$rst1 = $myDB->query('select count(*) as count from whole_details_peremp where ReportTo = "'.$_SESSION['__user_logid'].'" or account_head = "'.$_SESSION['__user_logid'].'"');
					$count_rst = $rst1[0]['count'];*/
								if ($status_rt == $EmployeeID || $status_ah == $EmployeeID)
								//if($count_rst > 0)
								{
									if ($exp1 == 1) {
									?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_Attendance_addReq"
                                        href="<?php echo URL . 'View/addReq'; ?>">
                                        <span class="left-menu-title">Exception Request</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>

                                <?php
									} elseif ($exp2 == 1) {
									?>


                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_Attendance_addReq"
                                        href="<?php echo URL . 'View/addReq1'; ?>">
                                        <span class="left-menu-title">Exception Request [Split]</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <?php
									}
								}
								?>


                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_DownTime_addDwt"
                                        href="<?php echo URL . 'View/addDwt'; ?>">

                                        <span class="left-menu-title">DownTime Request</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <?php
								if ($EmployeeID == 'CE061510258' || $EmployeeID == 'CE06080411') {
								?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_DownTime_downtime_bulk"
                                        href="<?php echo URL . 'View/downtime_bulk.php'; ?>">

                                        <span class="left-menu-title">DownTime Request [ BULK ]</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <?php
								}
								?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_addleave"
                                        href="<?php echo URL . 'View/addleave'; ?>">

                                        <span class="left-menu-title">Leave Request</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " id="menu_addleave"
                                        href="<?php echo URL . 'View/rpt_co_pl.php'; ?>">

                                        <span class="left-menu-title">Paid Leave & Comp off Status</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>

                            </ul>
                        </div>
                    </li>

                    <?php
					if ($_gender == 'FEMALE') {


					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/POSH'; ?>">
                            <i class="fa fa-lg fa-tasks "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">POSH SPOC</span>
                        </a>
                    </li>

                    <?php  } ?>

                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/ER'; ?>">
                            <i class="fa fa-lg fa-tasks "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">ER SPOC</span>
                        </a>
                    </li>
                    <?php
					// $myDB = new MysqliDb();
					$thcount = 0;
					$rstcount = 'select distinct EmpID from report_map where EmpID=?';
					$selectQr = $conn->prepare($rstcount);
					$selectQr->bind_param("s", $EmployeeID);
					$selectQr->execute();
					$rst_count = $selectQr->get_result();

					if ($rst_count->num_rows > 0) {


					?>
                    <li class="level1 Reports no-padding parent" id="menu_pim_viewPimModule">
                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-file-text">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Reports <span
                                    style="color:red;font-weight: bold;background: transparent;padding: 5px;border-radius: 5px;border: 0px solid #0b7d92;text-shadow: 1px 1px 1px #ffffff, 1px 1px 1px #000000;font-family: sans-serif;"
                                    class="animated  flash">New</span></span></span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>

                        <div class="collapsible-body">

                            <ul class="collapsible collapsible-accordion">


                                <li class="level3">
                                    <?php
										// $myDB = new MysqliDb();
										$countmenu = 0;
										$count = 'select distinct reportID from report_map where EmpID=?';
										$selectQry = $conn->prepare($count);
										$selectQry->bind_param("s", $EmployeeID);
										$selectQry->execute();
										$countmenu = $selectQry->get_result();
										if ($countmenu->num_rows > 0) {
											foreach ($countmenu as $key => $value) {
												if ($value['reportID'] == '1') {

										?>
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/rpt_at_spl'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Attendance</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <?php
												} else if ($value['reportID'] == '2') {

												?>

                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/rs_rpt_spl'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                    <?php
												} else if ($value['reportID'] == '3') {
												?>
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/rpt_exc_spl'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Exception</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                    <?php
												} else if ($value['reportID'] == '4') {
												?>
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/ex_lh_spl'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Leave</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <?php
												} else if ($value['reportID'] == '5') {
												?>
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_ncns_spl'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">NCNS</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <?php
												} else if ($value['reportID'] == '6') {
												?>
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/dt_rpt_spl'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">DownTime</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <?php }
											}
										} ?>
                                </li>


                            </ul>

                        </div>
                    </li>

                    <?php }  ?>


                    <?php
					if ($EmployeeID == 'CE12102224' || $EmployeeID == 'CE03070003' || $EmployeeID == 'CE01145570' || $EmployeeID == 'CE0820912500' || $EmployeeID == 'MU03200198' || $EmployeeID == 'CE04159316' || $EmployeeID == 'CEK07120002' || $EmployeeID == 'CFK08190181' || $EmployeeID == 'CMK112073699' || $EmployeeID == 'CEV102073966' || $EmployeeID == 'CEK031925550' || $EmployeeID == 'MU01221218') {


					?>
                    <li class="level1 ManageEmail no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-file-text">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Consultancy Module</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <?php
									if ($EmployeeID == 'CE01145570') {
									?>

                                <li class="level3">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/MsConsult'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Master Consultancy</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level3">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/MngConsult'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Manage Consultancy</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php }  ?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/upConslt'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload Data</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/updatestat'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Manage Candidate Status</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
									if ($EmployeeID == 'CE12102224' || $EmployeeID == 'CE03070003' || $EmployeeID == 'CE01145570') {

									?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/rpt_cref'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Report Candidate Status</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/rpt_cmaster'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Report Candidate Master</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php
									}
									?>

                            </ul>
                        </div>
                    </li>
                    <?php
					}
					?>


                    <?php
					if ($EmployeeID == 'CE10091236' || $EmployeeID == 'CE03070003' || $EmployeeID == 'CE031929841') {
					?>
                    <li class="level1 ManageEmail no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa fa fa-envelope">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Manage Email</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/manage_email.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Add / Remove</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/manage_email_module.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Assign</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/manage_module.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Edit Module</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <?php
					}
					?>
                    <?php if (($status_ah != 'No' && $status_ah == $EmployeeID && $status_ah != '') ||  ($EmployeeID == 'CE03070003' || $EmployeeID == 'CE031929841')) {
					?>
                    <li class="level1 IncentiveManagement no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-server">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Incentive</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <?php
									if ($EmployeeID == 'CE03070003'  || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE031929841') {
									?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/inc_IncentiveCriteriaApproved.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Manage</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
									}

									if (($status_ah != 'No' && $status_ah == $EmployeeID && $status_ah != '') && ($EmployeeID != 'CE03070003' || $EmployeeID == 'CE031929841')) {
									?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/inc_IncentiveCriteriaForAH.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Scheme</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
									}

									?>
                            </ul>
                        </div>
                    </li>
                    <?php
					} ?>

                    <!--Report Start for Gaurav Sharma-->
                    <?php
					if ($EmployeeID == 'CE081930104') {

					?>

                    <li class="level1 Reports no-padding parent" id="menu_pim_viewPimModule">
                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-file-text">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Reports</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Attendance Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">


                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_at'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Attendance</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>


                                        </ul>
                                    </div>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_interview_map.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Interview Map</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <?php
					}
					?>
                    <!--Report End for Gaurav Sharma-->



                    <!--Report Start-->
                    <?php
					if (($status_th == $EmployeeID || $status_oh == $EmployeeID || $status_qh == $EmployeeID) || ((($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '') || (($status_er != 'No' && $status_er == $EmployeeID) && $status_er != '') || $user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS' || $user_type == 'HR' || $EmployeeID == 'CE09134997')  || ($status_vh != '' && $status_vh == $EmployeeID)) {

					?>
                    <li class="level1 Reports no-padding parent" id="menu_pim_viewPimModule">
                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-file-text">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Reports</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <?php if ($EmployeeID == 'CE03070003'  || $EmployeeID == 'CE01145570' || $EmployeeID == 'CE12102224') { ?>

                                <li class="level3">
                                    <a class=" waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_health_insurance.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Health Insurance</span>
                                        <!--<span class="material-icons "></span>-->
                                    </a>
                                </li>
                                <li class="level3">
                                    <a class=" waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_mob_manulal_atnd.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Manual Atnd Login</span>
                                    </a>
                                </li>
                                <li class="level3">
                                    <a class=" waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_manual_atnd.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Manual Atnd Summary</span>
                                    </a>
                                </li>
                                <?php
									}
									?>

                                <?php if ((($status_er != 'No' && $status_er == $EmployeeID) && $status_er != '') || ($user_type == 'HR' && (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != ''))) {
									?>

                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">ER Reports</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_ab'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Phone Number</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_exit'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Exit Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_erh2h'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Grievance Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_res'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Resign Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>

                                <?php  } ?>

                                <?php
									if ((($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '') || $user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS' || $EmployeeID == 'CE09134997'  || ($status_vh != '' && $status_vh == $EmployeeID)) {
									?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Attendance Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">

                                            <?php
													if ($user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS') {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/hybrid_report'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Hybrid</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php
													}
													?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_at'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Attendance</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rs_rpt'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Roster</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/cap_report.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">CAP Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php if ($ut_temp_check == 'COMPLIANCE' || $user_type == 'ADMINISTRATOR' || $EmployeeID == 'CE071829485') {
													?>


                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rs_apr'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">APR</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rs_raw_apr'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">APR RAW Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/cosmoid'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">CosmoID Manage</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
													}
													?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_WeeklyRoster.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Roster vs Present</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_AttendanceTrack.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Biometric vs APR</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <?php if ($user_type == 'ADMINISTRATOR' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE01145570' || $EmployeeID == 'CE09134997') {
										?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Email and SMS</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">


                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/termination'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Termination</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/releivingexp'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Relieving & Experience</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/sentmessageack'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Sent Message Acknowledge</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/bdaymsg'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Birthday Message Send</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <?php
										}
										?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Miscellaneous</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <?php if ($EmployeeID == 'CE10091236' || $EmployeeID == 'CE03070003' || $EmployeeID == 'CE01145570') {
													?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_ncns_sms_status.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">NCNS SMS Status</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
													}
													?>
                                            <?php if ($ut_temp_check == 'COMPLIANCE' || $user_type == 'ADMINISTRATOR') {
													?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_appoint'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Appointment Letter</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php if ($EmployeeID == 'CE01145570') { ?>
                                            <li class="level1 search no-padding parent">
                                                <a class="waves-effect waves-orange "
                                                    href="<?php echo URL . 'View/onlineALreport.php'; ?>"> <i
                                                        class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span class="left-menu-title">Appointment Acknowledgement</span>
                                                </a>
                                            </li>
                                            <?php }
													}
													?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/lg_rpt'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Login</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rs_pr_rpt'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">WO Preference</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/mis_Report.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">MIS Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_Perf.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Performance Acknowledgment</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
													if ($user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS') {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/Tdesh'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Training Dashboard</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/empcycle'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Employee Cycle</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php
														if ($user_type == 'ADMINISTRATOR') {
														?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/updated-mobilenum-report.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Updated Contact</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
														}
														?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_missing.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Missing APR</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_doc_status.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">DOC Status</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/TestReport'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Test Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/bgvReport'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">BGV Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/covidAckReport'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Covid Acknowledge Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rptAdharDigilocker.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Aadhar updation from digilocker
                                                    </span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rptCodeOfConductPolicy.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Code of conduct policy </span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php
													}
													?>
                                        </ul>
                                    </div>
                                </li>
                                <?php
										if ($EmployeeID == 'CE03070003' || $EmployeeID == 'CE031929841') {
										?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Payroll</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_payroll_locked.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Payroll Locked</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_payroll_esic.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Payroll ESIC</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_payroll_pf.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Payroll PF</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <?php
										}
										?>
                                <?php
										if ($user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS') {
										?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Employee Movement</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ClientToClientMoveReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Client to Client</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ProcessToProcessMoveReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Process to Process</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/TLtoTLMoveReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">TL to TL</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/QAtoQAMoveReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">QA to QA</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/resign_module_report.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Resign Cases</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_NCNS_all.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">NCNS Cases</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_NCNS_admin.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">NCNS LIST</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Training Module Reports</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_ECLog.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Training Module Log</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_TRDaily.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Trainer Daily Log</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_QRDaily.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Quality Daily Log</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_THLog.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">TH Logs</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>

                                <?php
										}
										?>
                                <?php
									} ?>
                                <?php if ($user_type == 'HR' || $user_type == 'ADMINISTRATOR' || $EmployeeID == 'CE09134997' || $ut_temp_check == 'COMPLIANCE') {
									?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">HR Reports</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_at'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Attendance</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/reftohr.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Reffer to HR</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_after.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Profile Details</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php if ($user_type == 'ADMINISTRATOR' || $EmployeeID == 'CE09134997' || $EmployeeID == 'CE01145570') { //CE09134997 Sanoj pandey ID
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/offer_latter_download.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Appointment Letter</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_hold_validator.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Hold Appointment</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/cap_report.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">CAP Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php if ($EmployeeID == 'CE01145570') {

														?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_ref_register.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Reference Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <?php
													}
													?>
                                        </ul>
                                    </div>
                                </li>

                                <?php
									}
									if ($user_type == 'ADMINISTRATOR') {
									?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/rpt_Issue.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Grievance Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/rpt_dna.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Attendance DNA</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/rpt_emp_response.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">EMS Training Request</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php
									}
									if ($user_type == 'ADMINISTRATOR' || $EmployeeID == 'CE01145570') { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_interview_map.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Interview Map</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_health_ins.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Health Insurance</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/salrpt'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Salary Certificate Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_vaccine.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Vaccination Status</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php	}
									?>
                                <?php

									if (($status_th == $EmployeeID || $status_oh == $EmployeeID || $status_qh == $EmployeeID) && !((($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '') || $user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS')) {
									?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Attendance Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_at'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Attendance</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rs_rpt'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Roster</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                                <?php
									}
									?>
                            </ul>
                        </div>
                    </li>
                    <?php
					}
					?>
                    <!--Report End-->

                    <!--  Bangalore Reports Start  -->

                    <?php
					if ($EmployeeID == 'CE12102224' || $EmployeeID == 'CEK07120002' || $EmployeeID == 'CFK08190181' || $EmployeeID == 'CFK09190482' || $EmployeeID == 'CEK10120054' || $EmployeeID == 'CEK042175803') {

					?>

                    <li class="level1 Reports no-padding parent" id="menu_pim_viewPimModule">
                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-file-text">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Bangalore Reports</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>

                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/bgvReport-su'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">BGV Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/rygReport-su'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">RYG Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/issueReport-su'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Grievance Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <?php  }  ?>

                    <!--  Bangalore Reports End  -->

                    <!--TMS Start-->
                    <?php
					if ($status_th != 'No' || $status_oh != 'No' || $status_qh != 'No' || $status_tr != 'No' || $status_qa != 'No') {
					?>
                    <li class="level1 TMS no-padding parent" id="menu_pim_viewPimModule">
                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-th">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">TMS</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/rpt_Training_DashBoard.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Training Dashboard</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
									if ($EmployeeID == 'CE12102224' || $EmployeeID == 'CE071829485') {

									?>

                                <!--<li class="level2">
		            		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/bta'; ?>">
			            		<span class="material-icons left-menu-icon"></span>
			            		<span class="left-menu-title">WFM Batch Creation</span>
			            		<span class="material-icons collapsible-indicator"></span>
		            		</a>
	            		</li>
	            		
	            		<li class="level2">
		            		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/aib'; ?>">
			            		<span class="material-icons left-menu-icon"></span>
			            		<span class="left-menu-title">WFM Batch Assign</span>
			            		<span class="material-icons collapsible-indicator"></span>
		            		</a>
	            		</li>
	            		
	            		<li class="level2">
		            		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/dcn'; ?>">
			            		<span class="material-icons left-menu-icon"></span>
			            		<span class="left-menu-title">De-link Candidate</span>
			            		<span class="material-icons collapsible-indicator"></span>
		            		</a>
	            		</li>
	            		
	            			            		
	            		<li class="level2">
		            		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/irt'; ?>">
			            		<span class="material-icons left-menu-icon"></span>
			            		<span class="left-menu-title">Interview Report</span>
			            		<span class="material-icons collapsible-indicator"></span>
		            		</a>
	            		</li>
	            		
	            		<li class="level2">
		            		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/rpt_assign'; ?>">
			            		<span class="material-icons left-menu-icon"></span>
			            		<span class="left-menu-title">Assign Report</span>
			            		<span class="material-icons collapsible-indicator"></span>
		            		</a>
	            		</li>-->

                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/mcm'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Movement</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php  }  ?>

                                <!--<?php
										if ($EmployeeID == 'CE12102224' || $EmployeeID == 'CE071829485' || $user_type == 'HR') {

										?>
				        
				        	<li class="level2">
		            		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/btr'; ?>">
			            		<span class="material-icons left-menu-icon"></span>
			            		<span class="left-menu-title">WFM Batch Report</span>
			            		<span class="material-icons collapsible-indicator"></span>
		            		</a>
	            		</li>
				        
				        <?php } ?>-->

                                <?php
									if (($status_th != 'No' && $status_th == $EmployeeID) or ($status_qh != 'No' && $status_qh == $EmployeeID) or ($status_oh != 'No' && $status_oh == $EmployeeID) or ($status_ah != 'No' && $status_ah == $EmployeeID)) {
									?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/empcycle'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Employee Cycle</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
									}

									?>
                                <?php

									if (($status_th != 'No' && $status_th == $EmployeeID) && $status_th != '') {
										$thcount = 0;
										$myDB = new MysqliDb();

										$rst_count_th_list = $myDB->query('call get_count_thlist("' . $EmployeeID . '")');
										if ($rst_count_th_list) {
											$thcount = $rst_count_th_list[0]['count_th'];
										}
									?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/batch_master.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Create New Batch</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>


                                <?php
										if ($thcount > 0) {
										?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/th'; ?>"
                                        title="List of Employee For Traning Batch Assign Stage">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Training Head<span
                                                class="badge"><?php echo $thcount; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
										}
										?>


                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/logth'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Training Head Log</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/extth'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Training Extend</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>


                                <?php
										//style="background: linear-gradient(195deg,#8A8A8A, #FFFFFF);border-color:gray;"	
									}
									?>
                                <?php

									if (($status_th != 'No' && $status_th == $EmployeeID) && $status_th != '') {
										$myDB = new MysqliDb();
										$thcount = 0;
										$rst_count_th_list = $myDB->query('call get_count_thlist_after("' . $EmployeeID . '")');
										if ($rst_count_th_list) {
											$thcount = $rst_count_th_list[0]['count_th'];
										}
									?>
                                <?php
										if ($thcount > 0) {
										?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/afterth'; ?>"
                                        title="List of Employee For Traning Batch After Training">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">TH to QH Mapping<span
                                                class="badge"><?php echo $thcount; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
										}

										$myDB = new MysqliDb();
										$thcount_over = 0;
										$rst_count_th_listover = $myDB->query('call get_count_thlist_over("' . $EmployeeID . '")');
										if ($rst_count_th_listover) {
											$thcount_over = $rst_count_th_listover[0]['count_th'];
										}
										?>
                                <?php
										if ($thcount_over > 0) {
										?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/overth'; ?>"
                                        title="List of Employee For Traning Batch Incomplete Training">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">TH Overrule<span
                                                class="badge"><?php echo $thcount_over; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
										}
										?>
                                <?php
										//style="background: linear-gradient(195deg,#8A8A8A, #FFFFFF);border-color:gray;"	
									}
									?>
                                <?php
									if (($status_tr != 'No' && $status_tr == $EmployeeID) && $status_tr != '') {
										$myDB = new MysqliDb();
										$trcount = 0;
										$rst_count_tr_list = $myDB->query('call get_count_trlist("' . $EmployeeID . '")');
										if ($rst_count_tr_list) {
											$trcount = $rst_count_tr_list[0]['count_tr'];
										}
									?>
                                <?php
										if ($trcount > 0) {
										?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/tr'; ?>"
                                        title="List of Employee For Traning Status">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Trainer<span
                                                class="badge"><?php echo $trcount; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/td'; ?>"
                                        title="List of Employee For Traning Status">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Trainer Daily Report<span
                                                class="badge"><?php echo $trcount; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
										}
										?>
                                <?php
									}
									?>
                                <?php
									if (($status_qh != 'No' && $status_qh == $EmployeeID) && $status_qh != '') {
										$myDB = new MysqliDb();
										$qhcount = 0;
										$rst_count_qh_list = $myDB->query('call get_count_qhlist("' . $EmployeeID . '")');
										if ($rst_count_qh_list) {
											$qhcount = $rst_count_qh_list[0]['count_qh'];
										}
									?>
                                <?php
										if ($qhcount > 0) {
										?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/qh'; ?>"
                                        title="List of Employee For Traning Complete Traning and OJT Process">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Quality Head<span
                                                class="badge"><?php echo $qhcount; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
										}
										?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/logqh'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Quality Head Log</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php

										$myDB = new MysqliDb();
										$qhcount = 0;
										$rst_count_qh_list = $myDB->query('call get_count_qhlist_after("' . $EmployeeID . '")');
										if ($rst_count_qh_list) {
											$qhcount = $rst_count_qh_list[0]['count_qh'];
										}
										?>
                                <?php
										if ($qhcount > 0) {
										?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/afterqh'; ?>"
                                        title="List of Employee For Traning Complete and in OJT Process ">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">QH to OH Mapping<span
                                                class="badge"><?php echo $qhcount; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php
										}
										?>
                                <?php

										$myDB = new MysqliDb();
										$qhcount_o = 0;
										$rst_count_qh_list_o = $myDB->query('call get_count_qhlist_overrule("' . $EmployeeID . '")');
										if ($rst_count_qh_list) {
											$qhcount_o = $rst_count_qh_list_o[0]['count_qh'];
										}
										?>
                                <?php
										if ($qhcount_o > 0) {
										?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/overqa'; ?>"
                                        title="List of Employee For Traning Complete and Decertify in OJT  Process ">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">QA Overrule<span
                                                class="badge"><?php echo $qhcount_o; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
										}
										?>

                                <?php

										$myDB = new MysqliDb();
										$qh_ops_count = 0;
										$rst_count_qh_ops = 0;
										/*$myDB->query('call get_count_qhlist_ops("'.$_SESSION['__user_logid'].'")');
						if($rst_count_qh_ops)
						{
							$qh_ops_count = $rst_count_qh_ops[0]['count_qhlist'];
							
						}*/

										?>

                                <?php
										if ($qh_ops_count > 0) {
										?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/lqh'; ?>"
                                        title="List of Employee Who Complete OJT and Reporting Quality is You ">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">QA Mapping<span
                                                class="badge"><?php echo $qh_ops_count; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php
										}
										?>

                                <?php
									}
									?>
                                <?php
									if (($status_qa != 'No' && $status_qa == $EmployeeID) && $status_qa != '') {
										$myDB = new MysqliDb();
										$qacount = 0;
										$rst_count_qa_list = $myDB->query('call get_count_qalist("' . $EmployeeID . '")');
										if ($rst_count_qa_list) {
											$qacount = $rst_count_qa_list[0]['count_qa'];
										}
									?>

                                <?php
										if ($qacount > 0) {
										?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/qa'; ?>"
                                        title="List of Employee Who have Complete Traning and In OJT Check List">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Quality Analyst<span
                                                class="badge"><?php echo $qacount; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/qd'; ?>"
                                        title="Add a Report Comment per day for the batch alligned">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Quality Daily Report<span
                                                class="badge"><?php echo $qacount; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
										}
										?>

                                <?php
									}
									?>
                                <!--Assign TL-->
                                <?php
									if (($status_oh != 'No' && $status_oh == $EmployeeID) && $status_oh != '') {
										$myDB = new MysqliDb();
										$oh_count = 0;
										$rst_count_oh = $myDB->query('call get_count_ohlist("' . $EmployeeID . '")');
										if ($rst_count_oh) {
											$oh_count = $rst_count_oh[0]['count_oh'];
										}
									?>

                                <?php
										if ($oh_count > 0) {
										?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/oh'; ?>"
                                        title="List of Employee Who have Complete OJT and Reporting To You">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Operation Head<span
                                                class="badge"><?php echo $oh_count; ?></span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php
										}
										?>

                                <?php
									}
									?>

                                <!--Assign TL END-->
                            </ul>
                        </div>
                    </li>

                    <?php
					}
					?>
                    <!--TMS END-->

                    <!--Message Start-->
                    <?php
					if ($status_ah != 'No' && $status_ah == $EmployeeID || $_SESSION['empid'] == "Yes" || $_SESSION['__user_logid'] == 'CE0821939593') {

					?>

                    <li class="level1 Dissemination no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-server">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Dissemination</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/team_msg.php'; ?>"
                                        id="team_msg">
                                        <span class="left-menu-title">Message</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/team_msg_report.php'; ?>" id="team_msg_report">
                                        <span class="left-menu-title">Message Report</span>

                                    </a>

                                </li>

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/team_alert_ActiveUsers.php'; ?>"
                                        id="team_alert_ActiveUsers">
                                        <span class="left-menu-title">Active User</span>
                                    </a>
                                </li>
                                <?php if ($status_ah == $EmployeeID && $cm_id == "47") { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/desimination_matrix.php'; ?>"
                                        id="desimination_matrix">
                                        <span class="left-menu-title">Desimination Matrix</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php } ?>



                            </ul>
                        </div>
                    </li>

                    <?php } ?>
                    <!--Message END-->


                    <!--QMS Start-->
                    <li class="level1 QMS no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-recycle">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">QMS</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <?php if ($user_status > 3) { ?>

                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Briefing</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">

                                            <?php

												if (($status_th != 'No'  && $status_th == $EmployeeID) || ($status_qh != 'No'  && $status_qh == $EmployeeID) || ($status_oh != 'No'  && $status_oh == $EmployeeID) || ($status_ah != 'No'  && $status_ah == $EmployeeID) || $user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS') {
												?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/BriefingMaster.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Briefing</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/briefingAcknowledgeReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Acknowledge Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/BriefingQuizReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Quiz Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/BriefingCoverageReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Briefing Coverage</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/QuizResponsesReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Quiz Responses</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
													if ($user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS') { ?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/BriefingDashboard.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Briefing Dashboard</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php } ?>


                                            <?php
												} else {


												?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/BriefingAgent.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Briefing</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/agentAckReport.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php }
												?>
                                        </ul>
                                    </div>
                                </li>
                                <?php } ?>

                                <!--<li class="level2">
									<a class="waves-effect waves-orange" href="<?php echo URL . 'View/qms.php'; ?>" target="_blank">
										<span class="material-icons left-menu-icon"></span>
										<span class="left-menu-title">Quality</span>
										<span class="material-icons collapsible-indicator"></span>
									</a>
							</li>-->
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/qmscloud.php'; ?>"
                                        target="_blank">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Quality<span
                                                style="color:red;font-weight: bold;background: transparent;padding: 5px;border-radius: 5px;border: 0px solid #0b7d92;text-shadow: 1px 1px 1px #ffffff, 1px 1px 1px #000000;font-family: sans-serif;"
                                                class="animated  flash">Cloud</span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <!--<li class="level2">
											<a class="waves-effect waves-orange" href="<?php echo URL . 'View/pkt.php'; ?>" target="_blank">
												<span class="material-icons left-menu-icon"></span>
												<span class="left-menu-title">PKT<span style="color:red;font-weight: bold;background: transparent;padding: 5px;border-radius: 5px;border: 0px solid #0b7d92;text-shadow: 1px 1px 1px #ffffff, 1px 1px 1px #000000;font-family: sans-serif;" class="animated  flash">New</span></span>
												<span class="material-icons collapsible-indicator"></span>
											</a>
									</li>-->

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/pktcloud.php'; ?>"
                                        target="_blank">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">PKT<span
                                                style="color:red;font-weight: bold;background: transparent;padding: 5px;border-radius: 5px;border: 0px solid #0b7d92;text-shadow: 1px 1px 1px #ffffff, 1px 1px 1px #000000;font-family: sans-serif;"
                                                class="animated  flash">Cloud</span></span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <!--QMS END-->

                    <?php
					if ($EmployeeID == "CE12102224") {

					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/asset_link.php'; ?>"
                            target="_blank">
                            <i class="fa fa-lg fa-tasks "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Asset<span
                                    style="color:red;font-weight: bold;background: transparent;padding: 5px;border-radius: 5px;border: 0px solid #0b7d92;text-shadow: 1px 1px 1px #ffffff, 1px 1px 1px #000000;font-family: sans-serif;"
                                    class="animated  flash">New</span></span>
                        </a>
                    </li>
                    <?php } ?>

                    <!--Employee Movement Start-->

                    <?php
					if ((($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '') || (($status_oh != 'No' && $status_oh == $EmployeeID) && $status_oh != '') || ($_user_Desg != '' && $_user_Desg != 'CSA' && $_user_Desg != 'Senior CSA')) {
					?>
                    <li class="level1 EmployeeMovement no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-exchange">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Movement</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <?php

									if (($user_type == 'ADMINISTRATOR') || ($user_type == 'HR' && (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '')) || (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '') || ($user_type == 'HOD' && $user_client_ID == '13') || (($status_oh != 'No' && $status_oh == $EmployeeID) && $status_oh != '')) {
									?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Resign</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <?php
													if ($user_type == 'ADMINISTRATOR' && $ut_temp_check == 'ADMINISTRATOR') {
													?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rsg_admin'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Resign Edit</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php
													}
													?>

                                            <?php
													if ($user_type == 'ADMINISTRATOR') {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rsg_sh'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Site Head - Resign Check</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
													}
													?>
                                            <?php
													if ($user_type == 'HR' && (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '')) {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rsg_acc'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">HR - Resign Accept</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rsg_hr'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">HR - Resign Check</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/can_rsg_hr'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">HR - Resign Revoke</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
													}
													?>
                                            <?php

													if (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '') {

													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rsg_req'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">AH - Resign Request</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rsg_ah'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">AH - Resign Check</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/can_rsg'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">AH - Resign Revoke</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>



                                            <?php
													}
													?>
                                            <?php
													if ($user_type == 'HOD' && $user_client_ID == '13') {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rsg_it'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">IT - Resign Check</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>


                                            <?php
													}
													?>
                                            <?php

													if (($status_oh != 'No' && $status_oh == $EmployeeID) && $status_oh != '') {

													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rsg_oh'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">OH - Resign Check</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
													}
													?>



                                        </ul>
                                    </div>
                                </li>
                                <?php
									} ?>
                                <?php if (($_user_Desg != '' && $_user_Desg != 'CSA' && $_user_Desg != 'Senior CSA') || ($status_oh == $EmployeeID)) { ?>

                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Move ReportsTo</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <?php if ($_user_Desg != '' && $_user_Desg != 'CSA' && $_user_Desg != 'Senior CSA') { ?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/TL-2-TL-movement.php?action=tr'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Initiate By ReportsTo</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/TL-2-TL-movement.php?action=acc'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Approve By New ReportsTo</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php }
													if (($status_oh != 'No' && $status_oh == $EmployeeID) && $status_oh != '') {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/transfer_OH_to_TL.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Assign ReportsTo By OH</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php } ?>
                                        </ul>
                                    </div>
                                </li>
                                <?php } ?>
                                <?php
									if (($_user_Desg != '' && $_user_Desg != 'CSA' && $_user_Desg != 'Senior CSA') || ($status_oh == $EmployeeID)) {
									?>


                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Move QA Ops</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <?php
													if ($_user_Desg != '' && $_user_Desg != 'CSA' && $_user_Desg != 'Senior CSA') { ?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/QA_to_Qa_movement_qac.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Initiate By Current QA</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/QA_to_Qa_movement_qan.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Approve By New QA</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php
													}
													?>
                                            <?php
													if (($status_qh != 'No' && $status_qh == $EmployeeID) && $status_qh != '') {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/QA_to_Qa_movement_qh.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Assign QA By QH</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>




                                            <?php
													} ?>

                                        </ul>
                                    </div>
                                </li>
                                <?php
									}
									?>


                                <?php
									if (($user_type == 'HR' && (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '')) || (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '')) {
									?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">NCNS</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <?php
													if ($user_type == 'HR' && (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '')) {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ncns_acc'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">HR - NCNS Accept</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
													}
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ncns_req'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">AH - NCNS Request</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                                <?php
									}
									?>

                                <!--<?php
										if (($user_type == 'HR' && (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '')) || (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '')) {
										?>
		            	<li class="level2 no-padding parent active">
									<a class="collapsible-header waves-effect waves-orange active">
										<span class="material-icons left-menu-icon"></span>
										<span class="left-menu-title">Client to Client</span>
										<span class="material-icons collapsible-indicator"></span>
									</a>			
									<div class="collapsible-body" >
										<ul class="collapsible collapsible-accordion">
										<?php
											if ($user_type == 'HR' && (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '')) {
										?>					            		
									            <li class="level3">
														<a class="waves-effect waves-orange" href="<?php echo URL . 'View/cth'; ?>">
															<span class="material-icons left-menu-icon"></span>
															<span class="left-menu-title">HR - Client Transfer Approve</span>
															<span class="material-icons collapsible-indicator"></span>
														</a>
												</li>
												
									            <?php
											}
												?>
										<?php

											if (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '') {

										?>
										        <li class="level3">
													<a class="waves-effect waves-orange" href="<?php echo URL . 'View/ctc?action=tr'; ?>">
														<span class="material-icons left-menu-icon"></span>
														<span class="left-menu-title">AH - Client Transfer</span>
														<span class="material-icons collapsible-indicator"></span>
													</a>
												</li>
										         <li class="level3">
													<a class="waves-effect waves-orange" href="<?php echo URL . 'View/ctc?action=acc'; ?>">
														<span class="material-icons left-menu-icon"></span>
														<span class="left-menu-title">AH - Client Accept</span>
														<span class="material-icons collapsible-indicator"></span>
													</a>
												</li>										        
										            
										        <?php
											}
												?>
										</ul>
									</div>
						</li>				    
				    <?php
										}
					?>-->

                                <?php
									if ((($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '') || (($status_oh != 'No' && $status_oh == $EmployeeID) && $status_oh != '')) {
									?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Process to Process</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <?php
													if (($status_ah != 'No' && $status_ah == $EmployeeID) && $status_ah != '') {

													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ato'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">AH - Process Transfer Approve</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php
													}
													?>
                                            <?php


													if (($status_oh != 'No' && $status_oh == $EmployeeID) && $status_oh != '') {

													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/oto?action=tr'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">OH - Process Transfer</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/oto?action=acc'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">OH - Process Accept</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php
													}
													?>
                                        </ul>
                                    </div>
                                </li>
                                <?php
									}
									?>
                                <?php
									if ($user_type == 'HR'  || $status_ah != 'No' || $user_type == 'CENTRAL MIS') {
									?>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Termination</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <?php
													if ($status_ah != 'No' && $status_ah == $EmployeeID) { ?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/ah_wt'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Termination Request</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <?php	}

													if ($user_type == 'HR') {
													?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/hr_wt'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Warning/RTH</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php
													}
													if ($EmployeeID == 'CE01080195') {
													?>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/qh_wt'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Proceed HR</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <?php } ?>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/wt_rpt'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <?php
									}
									?>

                            </ul>
                        </div>
                    </li>

                    <?php
					} ?>
                    <!--Employee Movement End-->

                    <!--Reference Scheme managed by vertical head Start-->


                    <?php if ($status_vh != 'No' && $status_vh == $EmployeeID && $EmployeeID == "CE10091236") {
					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/Ref_VH'; ?>">
                            <i class="fa fa-lg fa-tasks "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Reference Scheme</span>
                        </a>
                    </li>
                    <?php  } ?>

                    <!--Reference Scheme managed by vertical head End-->

                    <!--Roster Start for Non Zomato-->

                    <?php
					if ($status_ah != 'No' && $status_ah == $EmployeeID && $status_oh != 'No' && $status_oh == $EmployeeID && $cm_id != "88" && $cm_id != "239" && $cm_id != "265" && $cm_id != "270" && $cm_id != "420" && $cm_id != "444" && $cm_id != "445" && $cm_id != "471" && $cm_id != "472" && $cm_id != "473" && $cm_id != "474") {
					?>
                    <li class="level1 Roster Module no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-safari">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Roster Module</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/roster_module.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">AH - Roster Template</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/upload_account_roster.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">AH - Upload Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/roster_module_oh.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">OH - Roster Template</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/upload_oh_roster.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">OH - Upload Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <?php
					} elseif ($status_ah != 'No' && $status_ah == $EmployeeID && $status_oh == 'No' && $cm_id != "88" && $cm_id != "239" && $cm_id != "265" && $cm_id != "270" && $cm_id != "420" && $cm_id != "444" && $cm_id != "445" && $cm_id != "471" && $cm_id != "472" && $cm_id != "473" && $cm_id != "474") {
					?>
                    <li class="level1 Roster Module no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-safari">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Roster Module AH</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/roster_module.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">AH - Roster Template</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/upload_account_roster.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">AH - Upload Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <?php
					} elseif ($status_ah == 'No' && $status_oh == $EmployeeID && $status_oh != 'No' && $cm_id != "88" && $cm_id != "239" && $cm_id != "265" && $cm_id != "270" && $cm_id != "420" && $cm_id != "444" && $cm_id != "445" && $cm_id != "471" && $cm_id != "472" && $cm_id != "473" && $cm_id != "474") {
					?>
                    <li class="level1 Roster Module no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-safari">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Roster Module OH</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/roster_module_oh.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">OH - Roster Template</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/upload_oh_roster.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">OH - Upload Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <?php
					}
					?>
                    <!--Roster End for Non Zomato-->


                    <!--Roster Start for Zomato-->
                    <?php
					if ($status_ah != 'No' && $status_ah == $EmployeeID && $status_oh != 'No' && $status_oh == $EmployeeID && ($cm_id == "88" || $cm_id == "239" || $cm_id == "265" || $cm_id == "270" || $cm_id == "420" || $cm_id == "444" || $cm_id == "445" || $cm_id == "471" || $cm_id == "472" || $cm_id == "473" || $cm_id == "474")) {
					?>
                    <li class="level1 Roster Module no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-safari">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Roster Module</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rosterz_module.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">AH - Roster Template</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/upload_account_rosterz.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">AH - Upload Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rosterz_module_oh.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">OH - Roster Template</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/upload_oh_rosterz.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">OH - Upload Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <?php
					} elseif ($status_ah != 'No' && $status_ah == $EmployeeID && $status_oh == 'No' && ($cm_id == "88" || $cm_id == "239" || $cm_id == "265" || $cm_id == "270" || $cm_id == "420" || $cm_id == "444" || $cm_id == "445" || $cm_id == "471" || $cm_id == "472" || $cm_id == "473" || $cm_id == "474")) {
					?>
                    <li class="level1 Roster Module no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-safari">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Roster Module AH</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rosterz_module.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">AH - Roster Template</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/upload_account_rosterz.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">AH - Upload Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <?php
					} elseif ($status_ah == 'No' && $status_oh == $EmployeeID && $status_oh != 'No' && ($cm_id == "88" || $cm_id == "239" || $cm_id == "265" || $cm_id == "270" || $cm_id == "420" || $cm_id == "444" || $cm_id == "445" || $cm_id == "471" || $cm_id == "472" || $cm_id == "473" || $cm_id == "474")) {
					?>
                    <li class="level1 Roster Module no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-safari">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Roster Module OH</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rosterz_module_oh.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">OH - Roster Template</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/upload_oh_rosterz.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">OH - Upload Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <?php
					}
					?>
                    <!--Roster End for Zomato-->


                    <!--MIS Task Satar-->
                    <?php
					if ($user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS' || $user_type == 'MIS') {
					?>
                    <li class="level1 MISTask Module no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-upload">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">MIS Task</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/UpPer'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload Performance</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/UpRanking.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload Rank</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <?php
									if ($user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS' || $EmployeeID == 'CE111513513' || $EmployeeID == 'CE101930161' || $EmployeeID == 'CE0421937626' || $EmployeeID == 'CE091829551' || $EmployeeID == 'CE0121935859' || $EmployeeID == 'CE081511111') {
									?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/UpAtnd'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload APR</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php if ($EmployeeID == 'CE01145570') { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/salup'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload Salary Details</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php if ($EmployeeID == 'CE03070003') { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/UpIOS'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload IOS User</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php } ?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/UpPerM'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload Performance Manual</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/up_cosmo_raw'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload Cosmo Raw</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/upload_team_quality.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload Quality</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <!--<li class="level2">
								<a class="waves-effect waves-orange" href="<?php echo URL . 'View/access_card.php'; ?>">
									<span class="material-icons left-menu-icon"></span>
									<span class="left-menu-title">Access Card</span>
									<span class="material-icons collapsible-indicator"></span>
								</a>
						</li>-->
                                <?php if ($ut_temp_check == 'COMPLIANCE' || $user_type == 'ADMINISTRATOR' || $EmployeeID = 'CE12102224') {
									?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/UpBIO'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload Biometric</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/rptConsult'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Consultancy Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/UpRos'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Upload Roster</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/MsAtndUp'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Attendance Upload</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/nestor_Details.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Nestor Manage</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/bqm_details.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">BQM Manage</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/buddy_details.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Buddy Support Manage</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>



                                <?php
									}
									//access given  to sachin sir and Vijayram
									if ($EmployeeID == 'CE03070003' || $EmployeeID == 'CE01145570') {
									?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/security_answer.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Security Answer</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <?php
									}
									?>

                            </ul>
                        </div>
                    </li>

                    <?php
					}
					?>
                    <!--MIS Task END-->

                    <?php
					if ($_user_Desg == "CSE" || $_user_Desg == "C.S.E." || $_user_Desg == "Sr. C.S.E" || $_user_Desg == "C.S.E" || $_user_Desg == "Senior Customer Care Executive" || $_user_Desg == "Customer Care Executive" || $_user_Desg == "CSA" || $_user_Desg == "Senior CSA") {
					?>
                    <?php
						if ($cm_id != '88' && $cm_id != "239" && $cm_id != "265" && $cm_id != "270" && $cm_id != "420" && $cm_id != "444" && $cm_id != "445") {
						?>

                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/ChPer'; ?>"> <i
                                class="fa fa-lg fa-bar-chart"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">My Performance</span>
                        </a>

                    </li>

                    <?php
						}
						?>

                    <?php
					} else if (($status_ah == 'No' || $status_ah == '') || $user_type != 'ADMINISTRATOR') {
					?>
                    <li class="level1 TeamReports no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-cog">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Team's Reports</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/rpt_pr_rpt'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">WO Preference Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2 no-padding parent active">
                                    <a class="collapsible-header waves-effect waves-orange active">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Attendance Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul class="collapsible collapsible-accordion">
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/tm_at'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Attendance Report</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_Roster_for_rt.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Roster</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>

                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_WeeklyRoster.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Roster vs Present</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                            <li class="level3">
                                                <a class="waves-effect waves-orange"
                                                    href="<?php echo URL . 'View/rpt_AttendanceTrack.php'; ?>">
                                                    <span class="material-icons left-menu-icon"></span>
                                                    <span class="left-menu-title">Biometric vs APR</span>
                                                    <span class="material-icons collapsible-indicator"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/pftracker'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Profile Tracker</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_QualityTeam.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Team Quality</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_BrifingTeam.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Team Briefing</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/ChPer'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">My Performance</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_TeamPerformance.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Team Performance</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <?php
					}
					?>
                    <!-- <li class="level1 MISTask Module no-padding parent">
			<a class="collapsible-header waves-effect waves-orange">
			   <span class="fa fa-lg fa-upload">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
			   <span class="left-menu-title">Change Request</span>
			   <span class="material-icons collapsible-indicator"></span>
			</a>
			   <div class="collapsible-body">
			       <ul class="collapsible collapsible-accordion">	
			       <?php if ($user_type == 'HR') { ?>	               	   
			        <li class="level2">
							<a class="waves-effect waves-orange" href="<?php echo URL . 'View/AcceptBankRequest'; ?>">
								<span class="material-icons left-menu-icon"></span>
								<span class="left-menu-title">Approve Bank Detail</span>
								<span class="material-icons collapsible-indicator"></span>
							</a>
					</li>
					<?php } ?>
					<li class="level2">
							<a class="waves-effect waves-orange" href="<?php echo URL . 'View/bankRequest'; ?>">
								<span class="material-icons left-menu-icon"></span>
								<span class="left-menu-title">Request Bank Detail</span>
								<span class="material-icons collapsible-indicator"></span>
							</a>
					</li>
					</ul>
			</div>
		</li>	-->
                    <li class="level1 HappytoHelp no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-bank"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Happy to Help</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/addissue'; ?>">
                                        <span class="left-menu-title">My Request</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/viewissue'; ?>">
                                        <span class="left-menu-title">Request Status</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <?php if ($EmployeeID == 'CE01145570' || $EmployeeID == 'CE021929762') { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/rpt_Issue.php'; ?>">
                                        <span class="left-menu-title">Grievance Report</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>

                                <?php } ?>

                            </ul>
                        </div>
                    </li>
                    <?php
					if ($user_type == 'ADMINISTRATOR' || $user_type == 'AUDIT') {
					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'View/offer_latter_report.php'; ?>"> <i
                                class="fa fa-lg fa-book"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Appointment Letter</span>
                        </a>

                    </li>

                    <?php
					}
					?>

                    <?php
					if ($EmployeeID == 'CE09134997' || $EmployeeID == 'CE12102224') {
					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/rel_manual'; ?>"> <i
                                class="fa fa-lg fa-book"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Releiving Letter</span>
                        </a>

                    </li>

                    <?php
					}
					?>

                    <li class="level1 expenses no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-tasks">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class=" left-menu-title">Reimbursement</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/expense'; ?>"
                                        id="team_alert_ActiveUsers">
                                        <span class="left-menu-title">Raise</span>
                                    </a>
                                </li>

                                <?php if ($_SESSION['reviewer'] == "Yes") { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/expense_review'; ?>" id="team_alert_ActiveUsers">
                                        <span class="left-menu-title">Review</span>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php if ($_SESSION['approver'] == "Yes") { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/expense_approv'; ?>" id="team_alert_ActiveUsers">
                                        <span class="left-menu-title">Approve</span>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php if ($_SESSION['reviewer'] == "Yes" || $_SESSION['approver'] == "Yes") { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange" href="<?php echo URL . 'View/expense_rpt'; ?>"
                                        id="team_alert_ActiveUsers">
                                        <span class="left-menu-title">Report</span>
                                    </a>
                                </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </li>



                    <?php
					if ($EmployeeID == 'CE09134997') {
					?>
                    <li class="level1 Payroll no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-cog">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Payroll</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_payroll_locked.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Payroll Locked</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_payroll_esic.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Payroll ESIC</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange"
                                        href="<?php echo URL . 'View/rpt_payroll_pf.php'; ?>">
                                        <span class="material-icons left-menu-icon"></span>
                                        <span class="left-menu-title">Payroll PF</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <?php
					}
					?>

                    <?php if ($EmployeeID == 'CE051726660') { ?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'View/inactive-emp-list.php?flg=0'; ?>"> <i
                                class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Inactive CSA</span>
                        </a>

                    </li>

                    <?php }

					if ($EmployeeID == 'CE03070003' || $EmployeeID == 'CE031929841') { ?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'View/inactive-emp-list.php?flg=1'; ?>"> <i
                                class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Inactive None CSA</span>
                        </a>

                    </li>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'View/inactive-emp-list.php?flg=0'; ?>"> <i
                                class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Inactive CSA</span>
                        </a>

                    </li>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'View/inactive-remark-report.php'; ?>"> <i
                                class="fa fa-lg fa-cube"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Inactive</span>
                        </a>

                    </li>

                    <?php	}
					?>
                    <li class="level1 ChangeSecurity no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-lock">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Change Security</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/chgsec'; ?>">
                                        <span class="left-menu-title">Change Security Key</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/chngpwd'; ?>">
                                        <span class="left-menu-title">Change Password</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>


                            </ul>
                        </div>
                    </li>

                    <?php

					if ($_SESSION["__Appraisal"] == "Yes" || $EmployeeID == 'CE03070003' || $EmployeeID == 'CE031929841' || ($_SESSION["__AprRecomender"] != 'No' && $_SESSION["__AprRecomender"] == $EmployeeID)) { ?>
                    <li class="level1 search no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-th">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Appraisal</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>

                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">


                                <?php
									//if($_SESSION['__user_logid'] == 'CE03070003' || $_SESSION['__user_logid'] == 'CE10091236' || $_SESSION['__user_logid']=='CE031929841')
									//if($_SESSION["__AprRecomender"] != 'No' && $_SESSION["__AprRecomender"]==$_SESSION['__user_logid'])
									if ($_SESSION["__Appraisal"] == "Yes") {
									?>
                                <!-- Strat for all-->

                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/AppraisalForm.php'; ?>">
                                        <span class="left-menu-title">Appraisal Form</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <!--end for ALL-->


                                <?php
									}
									?>

                                <?php

									if ($_SESSION["__AprRecomender"] != 'No' && $_SESSION["__AprRecomender"] == $EmployeeID) {

									?>

                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/AppraisalAH.php'; ?>">
                                        <span class="left-menu-title">Appraisal AH</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>

                                <?php
									}
									?>

                                <?php
									if ($EmployeeID == 'CE03070003') {

										//Madhur sir CE031929841
									?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/AppraisalApprover.php'; ?>">
                                        <span class="left-menu-title">Appraisal Approver</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/AppraisalReport.php'; ?>">
                                        <span class="left-menu-title">Appraisal Report</span>
                                        <span class="material-icons collapsible-indicator"></span>

                                    </a>

                                </li>
                                <?php
									}
									?>

                            </ul>
                        </div>
                    </li>
                    <?php
					}

					?>

                    <li class="level1 search no-padding parent">

                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-th">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Warning Letter</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>

                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/CAP_FormEmp'; ?>">
                                        <span class="left-menu-title">Acknowledgement</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>


                                <?php

								if ($status_th != 'No' || $status_oh != 'No' || $status_qh != 'No') {
								?>
                                <li class="level2">

                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/CAP_Form'; ?>">
                                        <span class="left-menu-title">Raise Request</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>
                                <?php
								}
								?>

                                <?php

								if (($user_type == 'HR' && $status_ah != 'No') || $status_ah != 'No') {
								?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/CAP_FormAR'; ?>">
                                        <span class="left-menu-title">AH/HR Approval</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>
                                <?php
								}
								?>

                            </ul>
                        </div>
                    </li>

                    <?php if ($EmployeeID == 'CE01145570' || $EmployeeID == 'CE12102224') { ?>
                    <li class="level1 search no-padding parent">

                        <a class="collapsible-header waves-effect waves-orange active">
                            <span class="fa fa-lg fa-th">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">BGV</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>

                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">

                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/bgvdoc'; ?>">
                                        <span class="left-menu-title">Upload Data</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>

                                <li class="level2">

                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/bgvrpt'; ?>">
                                        <span class="left-menu-title">Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>


                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php if ($EmployeeID == 'CE01145570' || $EmployeeID == 'CE03070003' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE1021940504' || $EmployeeID == 'CE12102224' || $EmployeeID == 'CE03146043') { ?>
                    <li class="level1 ManageEmail no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-file-text">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">IJP Module</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>


                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <?php if ($EmployeeID == 'CE01145570' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE03070003' || $EmployeeID == 'CE12102224' || $EmployeeID == 'CE03146043') { ?>
                                <li class="level2">
                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/create_IJP'; ?>">
                                        <span class="left-menu-title">IJP Creation</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>
                                <?php } ?>
                                <?php if ($EmployeeID == 'CE1021940504' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE03070003' || $EmployeeID == 'CE12102224') { ?>
                                <li class="level2">

                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/sch_ijp'; ?>">
                                        <span class="left-menu-title">IJP Schedule</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>
                                <li class="level2">

                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/ijp_actn'; ?>">
                                        <span class="left-menu-title">IJP Action</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>
                                <?php } ?>
                                <li class="level2">

                                    <a class="waves-effect waves-orange " href="<?php echo URL . 'View/ijp_rpt'; ?>">
                                        <span class="left-menu-title">IJP Report</span>
                                        <span class="material-icons collapsible-indicator"></span>
                                    </a>

                                </li>


                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
					$statusdate = date('d');
					if (($statusdate >= '10')  && ($statusdate <= '30')) {
						if ($statusdate >= '10'  && ($statusdate <= '15')) {
							// $myDB = new MysqliDb();
							if (isset($EmployeeID) && $EmployeeID != "") {
								$Getinfo = "select ReportTo from status_table where ReportTo=?";
								$selectQy = $conn->prepare($Getinfo);
								$selectQy->bind_param("s", $EmployeeID);
								$selectQy->execute();
								$resultrrt = $selectQy->get_result();

								if ($resultrrt->num_rows > 0) { ?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/RYGreportsto.php'; ?>">
                            <i class="fa fa-lg fa-tasks "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">RYG Status</span>
                        </a>
                    </li>
                    <?php
								}
							}
						} elseif ($statusdate >= '16'  && $statusdate <= '20' &&  $status_oh == $EmployeeID) {

							?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/RYGoh.php'; ?>">
                            <i class="fa fa-lg fa-tasks "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">RYG Status</span>
                        </a>
                    </li>
                    <?php

						} elseif ($statusdate >= '21'  && $statusdate <= '30' &&  $status_ah == $EmployeeID)
						//elseif($statusdate >='8'  && $statusdate<='11' &&  $status_ah==$_SESSION['__user_logid']) 
						{

						?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/RYGah.php'; ?>">
                            <i class="fa fa-lg fa-tasks "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">RYG Status</span>
                        </a>
                    </li>
                    <?php

						}
					}
					//}			

					?>

                    <?php
					if ($EmployeeID == 'CE03070003'  || $EmployeeID == 'CE01145570' || $EmployeeID == 'CE12102224') {

					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/geo_location.php'; ?>">
                            <i class="fa fa-lg fa-tasks "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">GEO Address Report</span>
                        </a>
                    </li>

                    <?php  } ?>


                    <?php
					if ($status_oh == $EmployeeID || $status_ah == $EmployeeID || $user_type == 'ADMINISTRATOR' || $user_type == 'CENTRAL MIS') {


					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/rpt_ryg_status.php'; ?>">
                            <i class="fa fa-lg fa-tasks "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Report RYG</span>
                        </a>
                    </li>

                    <?php  } ?>

                    <?php $itheldeskuser = array('CE10091236', 'CE01145570', 'CE03070003', 'CE021929775', 'CE121513568', 'CE061510258', 'CE0321936799');
					if (in_array($EmployeeID, $itheldeskuser)) { ?>
                    <li class="level1 search no-padding parent">

                        <a class=" waves-effect waves-orange"
                            href="<?php echo URL . 'View/ithdk_ticket_report.php'; ?>">
                            <i class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">IT Help Desk</span>
                        </a>

                    </li>
                    <?php
					}
					?>

                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/ho_list_all.php'; ?>"> <i
                                class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Holiday List</span>
                        </a>

                    </li>

                    <!--<?php if ($EmployeeID == 'CE10091236' || $EmployeeID == 'CE091829551') { ?>
		  <li class="level1 search no-padding parent">
			        		<a class="waves-effect waves-orange " href="<?php echo 'https://cogenteservices.in/candidate_info/emp_list1.php'; ?>" target="_blank">        			<i class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
				            <span class="left-menu-title">HR Verification</span>
				            </a>
					               
		  </li>
				  
		  <?php } ?>-->
                    <?php if ($user_client_ID == "44") {
						// $myDB = new MysqliDb();
						$countarray = "SELECT EmployeeID FROM self_undertaking where EmployeeID=? ";
						$selectQr = $conn->prepare($countarray);
						$selectQr->bind_param("s", $EmployeeID);
						$selectQr->execute();
						$count_array = $selectQr->get_result();

						if ($count_array->num_rows < 1) {
					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/undertaking.php'; ?>"> <i
                                class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Undertaking</span>
                        </a>

                    </li>
                    <?php }
						if ($EmployeeID == 'CE0820912495') { ?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/rpt_undertaking.php'; ?>"> <i
                                class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Undertaking Report</span>
                        </a>

                    </li>
                    <?php  }
					} ?>
                    <?php
					/* $myDB = new MysqliDb();
	        $icl_idea_check = $myDB->query("select EmployeeID from whole_details_peremp where EmployeeID ='".$_SESSION['__user_logid']."' and clientname like 'Idea';");*/
					if ($_SESSION["__user_client_name"] == "Idea") {
					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'View/ICL - Third Party ISPCC.php'; ?>"> <i
                                class="fa fa-lg fa-certificate"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">ICL Policy-Idea</span>
                        </a>

                    </li>
                    <?php
					}
					?>

                    <?php
					/* $myDB = new MysqliDb();
	        $icl_kent_check = $myDB->query("select EmployeeID from whole_details_peremp where EmployeeID ='".$_SESSION['__user_logid']."' and clientname like 'kent';");*/
					if ($_SESSION["__user_client_name"] == "kent") {
					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'View/ICL - Third Party ISPCC.php'; ?>"> <i
                                class="fa fa-lg fa-certificate"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Kent ISMS Policy</span>
                        </a>

                    </li>
                    <?php
					}
					?>


                    <?php
					/* $myDB = new MysqliDb();
	        $icl_idea_check = $myDB->query("select EmployeeID from whole_details_peremp where EmployeeID ='".$_SESSION['__user_logid']."' and clientname like 'Idea';");*/
					if ($_SESSION["__user_client_name"] == "Idea") {
					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/HSW_Policy.php'; ?>"> <i
                                class="fa fa-lg fa-certificate"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">HSW Policy</span>
                        </a>

                    </li>
                    <?php
					}
					?>
                    <?php

					if (substr($EmployeeID, 0, 3) != 'CCE' && substr($EmployeeID, 0, 3) != 'EXT') {
						//$myDB = new MysqliDb();
						//$query= $myDB->query("SELECT validatetime  from doc_al_status  where validate=1 and EmployeeID='".$_SESSION['__user_logid']."'  and substr(EmployeeID,1,3)!='CCE'");
						$flag = 0;
						if (($cm_id == "520" || $cm_id == "521") && round((time() - strtotime($_SESSION["__DOJ"])) / (60 * 60 * 24)) <= 20) {
							$flag = 1;
						}
						//if(count($query) > 0 && $query && $_SESSION['__user_status']==6)
						if ($flag == 0) {


							if ($_SESSION['__user_status'] == 6) {
								$datetime1 = new DateTime($_SESSION['__DOJ']);
								$month = $datetime1->format('m');
								$year = $datetime1->format('y');

								if (($month >= '01' && '19' == $year) || $year > '19') {
									include('notEligibleEmpAppointLet.php');
									if (!in_array($EmployeeID, $NotApplicable)) {


										//$datetime2 = new DateTime(date('Y-m-d'));
										//$interval = $datetime1->diff($datetime2);
										//if($interval->format('%y')=='0' && $interval->format('%m')=='0'  && $interval->format('%d')<='60'){
					?>

                    <li class="level1 ChangeSecurity no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-tasks">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Get Appointment Letter</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level1 search no-padding parent">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/ALsendOnEmail.php'; ?>">
                                        <i class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <span class="left-menu-title">Get Appointment Letter</span>
                                    </a>

                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php
									}
								}
							}
						}
					}

					if ($user_type == 'ADMINISTRATOR' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE031929841') {
						?>
                    <li class="level1 ChangeSecurity no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange">
                            <span class="fa fa-lg fa-tasks">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Appointment Acknowledgement</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                        <div class="collapsible-body">
                            <ul class="collapsible collapsible-accordion">
                                <li class="level1 search no-padding parent">
                                    <a class="waves-effect waves-orange "
                                        href="<?php echo URL . 'View/onlineALreport.php'; ?>"> <i
                                            class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <span class="left-menu-title">Appointment Acknowledgement</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php
					}
					if ($EmployeeID == 'CE03070003' || $EmployeeID == 'CE09134997') {
						/*sachin sir and sanoj sir can access these link */
					?>
                    <li class="level1 ChangeSecurity no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange"
                            href="<?php echo URL . 'View/upload_bank_esiUan_details.php'; ?>">
                            <span class="fa fa-lg fa-tasks">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Upload Bank Details</span>
                        </a>
                    </li>
                    <li class="level1 ChangeSecurity no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange"
                            href="<?php echo URL . 'View/upload_esicard.php'; ?>">
                            <span class="fa fa-lg fa-tasks">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Upload ESIC Card</span>
                        </a>
                    </li>
                    <li class="level1 ChangeSecurity no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange"
                            href="<?php echo URL . 'View/rpt_esic.php'; ?>">
                            <span class="fa fa-lg fa-tasks">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Uploaded ESIC Card</span>
                        </a>
                    </li>
                    <?php
					}
					// $myDB = new MysqliDb();
					$Select = "select * from esicard where EmployeeID=? and status=0 ";
					$selectQur = $conn->prepare($Select);
					$selectQur->bind_param("s", $EmployeeID);
					$selectQur->execute();
					$esiQuery = $selectQur->get_result();
					if ($esiQuery->num_rows > 0) {
					?>
                    <li class="level1 ChangeSecurity no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange"
                            href="<?php echo URL . 'View/download_esicard.php'; ?>">
                            <span class="fa fa-lg fa-tasks">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">ESIC Card</span>
                        </a>

                    </li>
                    <?php
					}
					if ($EmployeeID == 'CE111930187' ||  $EmployeeID == 'CE03070003' ||  $EmployeeID == 'CE01145570' ||  $EmployeeID == 'CE071829465') {
					?>
                    <li class="level1 ChangeSecurity no-padding parent">
                        <a class="collapsible-header waves-effect waves-orange"
                            href="<?php echo URL . 'View/print_IDcard.php'; ?>">
                            <span class="fa fa-lg fa-tasks">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Print Id Card</span>
                        </a>

                    </li>
                    <?php
					}
					?>

                    <!--<?php
						$myDB = new MysqliDb();
						$query = $myDB->query("SELECT EmpID from covid_access  where EmpID='" . $EmployeeID . "'");
						if (count($query) > 0) {

						?>
			
			<li class="level1 search no-padding parent">
        		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/Covid.php'; ?>" >             												<i class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
	            	<span class="left-menu-title">Quarantine</span>
	            </a>	               
		  </li> 
		  <li class="level1 search no-padding parent">
        		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/rptCovid.php'; ?>" >             												<i class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
	            	<span class="left-menu-title">Quarantine Report</span>
	            </a>	               
		  </li> 
			
			<?php
						}
			?> -->

                    <!--if(($status_ah!='No' && $status_ah==$_SESSION['__user_logid'] && $status_ah!='') || ($_SESSION["__status_vh"]==$_SESSION['__user_logid']) || ($status_oh==$_SESSION['__user_logid'])  || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE031929841' || $EmployeeID == 'CE091930141'){-->

                    <!-- <li class="level1 ChangeSecurity no-padding parent">
               <a class="collapsible-header waves-effect waves-orange">
	               <span class="fa fa-lg fa-tasks">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
	               <span class="left-menu-title">Contract</span>
	               <span class="material-icons collapsible-indicator"></span>
               </a>
	               <div class="collapsible-body">
		               <ul class="collapsible collapsible-accordion"> 
		             <?php if ($EmployeeID == 'CE10091236' || $EmployeeID == 'CE031929841' || $EmployeeID == 'CE091930141') {
						?>
					
					           <li class="level1 search no-padding parent">
					        		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/ctrctpram_master.php'; ?>" >             																		<i class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
						            	<span class="left-menu-title">Master</span>
						            </a>	               
							  </li> 
							  <li class="level1 search no-padding parent">
					        		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/CreateContract.php'; ?>" >             			<i class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
						            	<span class="left-menu-title">Create New</span>
						            </a>	               
							  </li> 
							  <li class="level1 search no-padding parent">
							  
					        		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/view_contract_log.php'; ?>" >             												<i class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
						            	<span class="left-menu-title">View Log</span>
						            </a>	               
							  </li> 
							  <?php } ?>
							  <?php
								if (($status_ah != 'No' && $status_ah == $EmployeeID && $status_ah != '') || ($_SESSION["__status_vh"] == $EmployeeID) || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE031929841' || $EmployeeID == 'CE091930141') {
									//if($EmployeeID == 'CE10091236' || $EmployeeID == 'CE031929841' || $EmployeeID == 'CE091930141' ){
								?>
							  <li class="level1 search no-padding parent">
					        		<a class="waves-effect waves-orange " href="<?php echo URL . 'View/CtrctDetails_list.php'; ?>" >             	<i class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
						            	<span class="left-menu-title">View Contract</span>
						            </a>	               
							  </li>
							  
							  <?php } ?>
		               </ul>
	               </div>
	        </li>-->
                    <?php /*  } */
					if ($user_client_ID == "40") {
						// $myDB = new MysqliDb();
						$sql = "SELECT id from zerotolerancepolicy_ack where  EmployeeID=?";
						$selectQu = $conn->prepare($sql);
						$selectQu->bind_param("s", $EmployeeID);
						$selectQu->execute();
						$query = $selectQu->get_result();

						if ($query->num_rows < 1) {

					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'View/ZeroTolerancePolicy.php'; ?>">
                            <i class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">ZERO TOLERANCE POLICY</span>
                        </a>
                    </li>

                    <?php  }
						if ($status_ah != '' and  ($EmployeeID == $status_ah)) {

						?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'View/rpt_ZeroTolerancePolicy.php'; ?>">
                            <i class="fa fa-lg fa-certificate "></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">ZERO TOLERANCE REPORT</span>
                        </a>
                    </li>
                    <?php }
					}
					?>

                    <?php
					// $myDB = new MysqliDb();
					$ijpQry = "select distinct EmployeeID from ijp_emp where flag='0' and EmployeeID= ? ";
					$selectQuy = $conn->prepare($ijpQry);
					$selectQuy->bind_param("s", $EmployeeID);
					$selectQuy->execute();
					$ijpRes = $selectQuy->get_result();
					// $ijpRes = $myDB->query($ijpQry);
					if ($ijpRes->num_rows > 0) {
						//if ($_SESSION["ijpEmp"] == "Yes") {
					?>
                    <li class="level1">
                        <a class="waves-effect waves-orange" href="<?php echo URL . 'View/ijp_ack'; ?>">
                            <span class="fa fa-lg fa-check">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">IJP Acknowledge</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                    </li>
                    <?php } ?>

                    <?php
					// $myDB = new MysqliDb();
					$ijpQrys = "Select i.id,i.EmployeeID from ijp_emp i join ijp_master m on i.ijpID=m.id where i.flag='1' and i.EmployeeID=? and inter_flag='0' and m.schedule_intro IS NOT NULL";
					$selectQury = $conn->prepare($ijpQrys);
					$selectQury->bind_param("s", $EmployeeID);
					$selectQury->execute();
					$ijpResult = $selectQury->get_result();
					// $ijpResult = $myDB->query($ijpQrys);
					if ($ijpResult->num_rows > 0) {

						//if ($_SESSION["ijpackEmp"] == "Yes") {
					?>
                    <li class="level1">
                        <a class="waves-effect waves-orange" href="<?php echo URL . 'View/ijp_intro'; ?>">
                            <span class="fa fa-lg fa-check">&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">IJP Interview Acknowledge</span>
                            <span class="material-icons collapsible-indicator"></span>
                        </a>
                    </li>
                    <?php } ?>


                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/salCert'; ?>"> <i
                                class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Get Salary Certificate</span>
                        </a>

                    </li>

                    <?php
					if ($_SESSION["salEmp"] == "Yes") {
					?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/sal-detail'; ?>"> <i
                                class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">View Salary Details</span>
                        </a>

                    </li>

                    <?php } ?>

                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/cp'; ?>">
                            <i class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Company Policy</span>
                        </a>
                    </li>
                    <?php if ($EmployeeID == "CE10091236" || $EmployeeID == "CFK08190181" || $EmployeeID == "CE011929747" || $EmployeeID == "CE12102224" || $EmployeeID == "CE01145570" || $EmployeeID == "CE0321936918" || $EmployeeID == "CEV102073966" || $ut_temp_check == 'ADMINISTRATOR') { ?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange "
                            href="<?php echo URL . 'QrSetup/qrinfo_videolist.php'; ?>">
                            <i class="fa fa-lg fa-list"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">Reference List</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="level1 search no-padding parent">
                        <a class="waves-effect waves-orange " href="<?php echo URL . 'View/Logout'; ?>">
                            <i class="fa fa-lg fa-power-off"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="left-menu-title">LogOut</span>
                        </a>
                    </li>


                </ul>
            </div>
        </div>

    </div>
</div>

<!--<?php
	$myDB = new MysqliDb();
	$result = $myDB->query('call get_mapvalidation_ws_check("' . $EmployeeID . '")');
	if ($result) {
		if (count($result) > 0) {
		} else {
	?>
				  <div id="validate_div" class="animated infinite flash"><i class="fa fa-exclamation-triangle"></i><a href="<?php echo URL . 'View/palert'; ?>" style="color:  darkorange;">Profile Incomplete</a></div>
				<?php
			}
		} else {
				?>
				  <div id="validate_div" class="animated infinite flash"><i class="fa fa-exclamation-triangle"></i><a href="<?php echo URL . 'View/palert'; ?>" style="color:  darkorange;">Profile Incomplete</a></div>
				<?php
			}

				?>-->