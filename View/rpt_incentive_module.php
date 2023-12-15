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
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$__user_logid = clean($_SESSION['__user_logid']);
$__user_type = clean($_SESSION['__user_type']);
$__status_oh = clean($_SESSION['__status_oh']);
$__status_ah = clean($_SESSION['__status_ah']);

// START FOR TIME DROPDOWN
$starttime = '00:00';  // your start time
$endtime = '23:30';  // End time
$duration = '30';  // split by 30 mins
$array_of_time = array();
$start_time    = strtotime($starttime); //change to strtotime
$end_time      = strtotime($endtime); //change to strtotime
$add_mins  = $duration * 60;
while ($start_time <= $end_time) // loop between time
{
    $array_of_time[] = date("H:i", $start_time);
    $start_time += $add_mins; // to check endtie=me
}
// END FOR TIME DROPDOWN
// print_r($array_of_time);
// die;
if ($__status_ah == $__user_logid || $__status_oh == $__user_logid) {
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}

if ($_POST['txt_dateTo']) {
    $date_To = $_POST['txt_dateTo'];
    $date_From = $_POST['txt_dateFrom'];
} else {
    $date_To = date('Y-m-d', time());
    $date_From = date('Y-m-d', time());
}

if (isset($_POST['btn_edit_OT_MIS'])) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        // echo "<pre>";
        // print_r($_POST);
        // die;
        $hideID = cleanUserInput($_POST['hideID']);
        $cm_id = cleanUserInput($_POST['cm_id']);
        $requirement = cleanUserInput($_POST['requirement']);
        $txt_dateFrom_updt = cleanUserInput($_POST['txt_dateFrom_updt']);
        $txt_dateTo_updt = cleanUserInput($_POST['txt_dateTo_updt']);
        $ot_reason = cleanUserInput($_POST['ot_reason']);
        $approved_fte = cleanUserInput($_POST['approved_fte']);
        $curr_headcount = cleanUserInput($_POST['curr_headcount']);
        $ot_hrs = cleanUserInput($_POST['ot_hrs']);
        $per_hrs_ot_amt = cleanUserInput($_POST['per_hrs_ot_amt']);
        $ot_type = cleanUserInput($_POST['ot_type']);
        $ot_window_status = cleanUserInput($_POST['ot_window_status']);

        // $window_timing = cleanUserInput($_POST['window_timing']);
        $window_timing = isset($_POST['window_timing']) ? implode(",", $_POST['window_timing']) : '';
        $mis_approver = cleanUserInput($_POST['mis_approver']);
        $mis_remark = cleanUserInput($_POST['mis_remark']);

        $sqlot = "call update_incentive('" . $hideID . "', '" . $cm_id . "', '" . $requirement . "', '" . $txt_dateFrom_updt . "', '" . $txt_dateTo_updt . "', '" . $ot_reason . "', '" . $approved_fte . "', '" . $curr_headcount . "', '" . $ot_hrs . "', '" . $per_hrs_ot_amt . "', '" . $ot_type . "', '" . $ot_window_status . "', '" . $window_timing . "', '" . $mis_approver . "', '" . $mis_remark . "', '" . $__user_logid . "')";
        $myDB = new MysqliDb();
        $result = $myDB->rawQuery($sqlot);
        $mysql_error = $myDB->getLastError();
        if (empty($mysql_error)) {
            echo "<script>$(function(){toastr.success('Updated Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){toastr.error('Something Went Wrong !'); }); </script>";
        }
    }
}

?>
<style>
    #dropdown li {
        position: relative;
        display: inline-block;
    }

    #dropdown span {
        padding-left: 15px;
    }
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Incentive Request Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Incentive Request Report </h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <div id="myModal_content" class="modal">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <h4 class="col s12 m12 model-h4">Incentive MIS</h4>
                        <div class="modal-body">
                            <form method="POST">

                                <?php $_SESSION["token"] = csrfToken(); ?>
                                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                                <input type="hidden" class="form-control hidden" id="hideID" name="hideID" />
                                <input type="hidden" class="form-control hidden" id="cm_id" name="cm_id" />

                                <div class="input-field col s4 m4">
                                    <input type="text" name="process" id="process">
                                    <label title="Date" class="active" for="process">Process</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select name="requirement" id="requirement">
                                        <option value="NA">--Select--</option>
                                        <option value="Internal">Internal</option>
                                        <option value="External">External</option>
                                    </select>
                                    <label for="requirement" class="active-drop-down active">Requirement</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <input type="text" name="txt_dateFrom_updt" id="txt_dateFrom_updt">
                                    <label title="Date" for="txt_dateFrom_updt">Start Date</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <input type="text" name="txt_dateTo_updt" id="txt_dateTo_updt">
                                    <label title="Date" for="txt_dateTo_updt">End Date</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select name="ot_reason" id="ot_reason">
                                        <option value="NA">--Select--</option>
                                        <option value="Deficit Manpower">Deficit Manpower</option>
                                        <option value="High Shrinkage">High Shrinkage</option>
                                        <option value="Festival Shrinkage">Festival Shrinkage</option>
                                        <option value="Planned Event">Planned Event</option>
                                        <option value="Specific Requirement">Specific Requirement</option>
                                    </select>
                                    <label title="Select Process Name" for="ot_reason" class="active-drop-down active">Incentive Reason</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <input type="text" name="approved_fte" id="approved_fte_MIS" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="5">
                                    <label for="approved_fte" class="Active">Approved FTE</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <input type="text" name="curr_headcount" id="curr_headcount_MIS" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="5">
                                    <label for="curr_headcount" class="Active">Current Headcount</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <input type="text" name="ot_hrs" id="ot_hrs_MIS" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="5">
                                    <label class="Active" for="ot_hr">Incentive Hours</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <input type="text" name="per_hrs_ot_amt" id="per_hrs_ot_amt_MIS" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="5">
                                    <label class="Active" for="per_hr_ot_amt">Per Hour Incentive Amount </label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select name="ot_type" id="ot_type">
                                        <option value="NA">--Select--</option>
                                        <option value="Hourly Incentive">Hourly Incentive </option>
                                        <option value="Hourly Incentive+WO Incentive">Hourly Incentive+WO Incentive</option>
                                    </select>
                                    <label for="ot_type" class="active-drop-down active">Incentive Type </label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select name="ot_window_status" id="ot_window_status">
                                        <option value="NA">--Select--</option>
                                        <option value="Applicable">Applicable </option>
                                        <option value="Not Applicable">Not Applicable</option>
                                    </select>
                                    <label for="ot_window_status" class="active-drop-down active">Incentive Window Status </label>
                                </div>

                                <div class="input-field col s12 m12" id="dropdown">
                                    <select class="selectpicker" id="window_timing" name="window_timing[]" multiple>
                                        <?php foreach ($array_of_time as $val) { ?>
                                            <option value=<?php echo $val ?>><?php echo $val ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="window_timing" class="active-drop-down active">Window Timing </label>
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <textarea class="materialize-textarea" id="remark" name="remark" maxlength="200"></textarea>
                                    <label for="mis_remark">Remark</label>
                                </div>


                                <div class="input-field col s4 m4">
                                    <select id="mis_approver" name="mis_approver">
                                        <option value="NA">Select</option>
                                        <option value="Approve">Approve</option>
                                        <option value="Decline">Decline</option>
                                    </select>
                                    <label for="mis_approver" class="dropdown-active active">MIS Approval Status </label>
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <textarea class="materialize-textarea" id="mis_remark" name="mis_remark" maxlength="200"></textarea>
                                    <label for="mis_remark">Remark MIS</label>
                                </div>

                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_edit_OT_MIS" id="btn_edit_OT_MIS" class="btn waves-effect waves-green ">Save</button>

                                    <button type="button" name="btn_can_OT_MIS" id="btn_can_OT_MIS" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

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

                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>

                <?php
                if (isset($_POST['btn_view'])) {
                    $myDB = new MysqliDb(); ?>

                    <div id="pnlTable">
                        <?php

                        // $sqlConnect = "select * from ot_raise_req where cast(created_at as date) between ? and ? and created_by=?";
                        if ($__user_type == "CENTRAL MIS") {
                            $sqlConnect = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) as Process,t3.*,t4.mis_approve_status from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id left join ot_raise_req t3 on t1.cm_id=t3.cm_id left join ot_raise_req_mis t4 on t3.id=t4.otraise_req_id where cast(t3.created_at as date) between ? and ?";
                            $stmts = $conn->prepare($sqlConnect);
                            $stmts->bind_param("ss", $date_From, $date_To);
                            $stmts->execute();
                        } else {
                            $sqlConnect = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) as Process,t3.*,t4.mis_approve_status as status from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id left join ot_raise_req t3 on t1.cm_id=t3.cm_id left join ot_raise_req_mis t4 on t3.id=t4.otraise_req_id where cast(t3.created_at as date) between ? and ? and t3.created_by=?";
                            $stmts = $conn->prepare($sqlConnect);
                            $stmts->bind_param("sss", $date_From, $date_To, $__user_logid);
                            $stmts->execute();
                        }
                        $resultst = $stmts->get_result();
                        if ($resultst->num_rows > 0) { ?>
                            <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Process</th>
                                        <th>Requirement Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Incentive Reason</th>
                                        <th>Approved Fte</th>
                                        <th>Current Headcount</th>
                                        <th>Incentive Hours</th>
                                        <th>Per Hour Incentive Amount</th>
                                        <th>Incentive Type</th>
                                        <th>Incentive Window Status</th>
                                        <th>Window Timing</th>
                                        <th>Remark</th>
                                        <th>Approver Status</th>
                                        <th>Approver Remark</th>
                                        <th>Created_at</th>
                                        <th class="hidden">ID</th>
                                        <th class="hidden">cm_id</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $currdate = date('Y-m-d');
                                    foreach ($resultst as $key => $value) {
                                        // print_r($value['mis_approve_status']);

                                    ?>
                                        <tr>
                                            <td>
                                                <?php if ($__user_logid == 'CE121621933') {

                                                    if ($value['mis_approve_status'] == 'Pending' && $value['mis_approve_status'] != 'Decline' || $value['mis_approve_status'] == 'Approve' && $currdate <= $value['end_date']) { ?>
                                                        <i class="material-icons btn-primary edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData_OT_MIS(this);" id="<?php echo $value['id'] ?>" data-position="left" data-tooltip="Edit">ohrm_edit</i>
                                                <?php  } else {
                                                        echo "NA";
                                                    }
                                                } else {
                                                    echo "NA";
                                                } ?>
                                            </td>
                                            <td class="process"><?php echo $value['Process']; ?></td>
                                            <td class="requirement_type"><?php echo $value['requirement_type']; ?></td>
                                            <td class="start_date"><?php echo $value['start_date']; ?></td>
                                            <td class="end_date"><?php echo $value['end_date']; ?></td>
                                            <td class="ot_reason"><?php echo $value['ot_reason']; ?></td>
                                            <td class="approved_fte"><?php echo $value['approved_fte']; ?></td>
                                            <td class="curr_headcount"><?php echo $value['curr_headcount']; ?></td>
                                            <td class="ot_hrs"><?php echo $value['ot_hrs']; ?></td>
                                            <td class="per_hrs_ot_amt"><?php echo $value['per_hrs_ot_amt']; ?></td>
                                            <td class="ot_type"><?php echo $value['ot_type']; ?></td>
                                            <td class="ot_window_status"><?php echo $value['ot_window_status']; ?></td>
                                            <td class="window_timing"><?php echo $value['window_timing']; ?></td>
                                            <td class="remark"><?php echo $value['remark']; ?></td>
                                            <td class="mis_approve_status"><?php echo $value['mis_approve_status']; ?></td>
                                            <td class="mis_remark"><?php echo $value['mis_remark']; ?></td>
                                            <td class="created_at"><?php echo $value['created_at']; ?></td>
                                            <td class="hidden otID"><?php echo $value['id']; ?></td>
                                            <td class="hidden cm_id"><?php echo $value['cm_id']; ?></td>
                                        </tr>
                                    <?php
                                    } ?>
                                </tbody>
                            </table>
                        <?php } else {
                            echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
                        } ?>
                    </div>
            </div>
        <?php } ?>

        </div>
        <!--Form container End -->
    </div>
    <!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>
<script>
    $(function() {

        // $("form input[name=txt_dateTo_updt]").prop("disabled", true);

        // $("#imgBtnEdit").on("click", function() {

        //     $("#txt_dateTo_updt").removeAttr("disabled");
        // })

        $('#alert_msg_close').click(function() {
            $('#alert_message').hide();
        });
        if ($('#alert_msg').text() == '') {
            $('#alert_message').hide();
        } else {
            $('#alert_message').delay(10000).fadeOut("slow");
        }

        var currentTime = new Date();
        // First Date Of the month 
        var startDateFrom = new Date(currentTime.getFullYear(), currentTime.getMonth(), 1);
        // Last Date Of the Month 
        var startDateTo = new Date(currentTime.getFullYear(), currentTime.getMonth() + 1, 0);

        $('#txt_dateFrom_updt,#txt_dateTo_updt').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth: false,
            minDate: 0,
            maxDate: startDateTo
        });

        $('#txt_dateFrom,#txt_dateTo').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth: false,
        });
        // $('#txt_dateFrom_updt,#txt_dateTo_updt').datetimepicker({
        //     timepicker: false,
        //     format: 'Y-m-d',
        //     scrollMonth: false,
        // });

    });

    $(document).ready(function() {
        //Model Assigned and initiation code on document load
        $('.modal').modal({
            onOpenStart: function(elm) {},
            onCloseEnd: function(elm) {
                $('#btn_can_OT_MIS').trigger("click");
            }
        });
        // This code for cancel button trigger click and also for model close
        $('#btn_can_OT_MIS').on('click', function() {
            $('#approved_fte_MIS').val('');
            $('#curr_headcount_MIS').val('');
            $('#ot_hrs_MIS').val('');
            $('#per_hrs_ot_amt_MIS').val('');
            $('#btn_edit_OT_MIS').addClass('hidden');

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



        $('#btn_edit_OT_MIS').click(function() {
            var validate = 0;
            var alert_msg = '';

            if ($('#requirement').val() == 'NA') {
                $('#requirement').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanrequirement').length == 0) {
                    $('<span id="spanrequirement" class="help-block">Required *</span>').insertAfter('#requirement');
                }
                validate = 1;
            }

            if ($('#txt_dateFrom_updt').val() == '') {
                $('#txt_dateFrom_updt').addClass("has-error");
                if ($('#spantxt_dateFrom_updt').length == 0) {
                    $('<span id="spantxt_dateFrom_updt" class="help-block">Required *</span>').insertAfter('#txt_dateFrom_updt');
                }
                validate = 1;
            }
            if ($('#txt_dateTo_updt').val() == '') {
                $('#txt_dateTo_updt').addClass("has-error");
                if ($('#spantxt_dateTo_updt').length == 0) {
                    $('<span id="spantxt_dateTo_updt" class="help-block">Required *</span>').insertAfter('#txt_dateTo_updt');
                }
                validate = 1;
            }
            if ($('#ot_reason').val() == 'NA') {
                $('#ot_reason').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanot_reason').length == 0) {
                    $('<span id="spanot_reason" class="help-block">Required *</span>').insertAfter('#ot_reason');
                }
                validate = 1;
            }
            if ($('#approved_fte_MIS').val() == '') {
                $('#approved_fte_MIS').addClass("has-error");
                if ($('#spanapproved_fte_MIS').length == 0) {
                    $('<span id="spanapproved_fte_MIS" class="help-block">Required *</span>').insertAfter('#approved_fte_MIS');
                }
                validate = 1;
            }
            if ($('#curr_headcount_MIS').val() == '') {
                $('#curr_headcount_MIS').addClass("has-error");
                if ($('#spancurr_headcount_MIS').length == 0) {
                    $('<span id="spancurr_headcount_MIS" class="help-block">Required *</span>').insertAfter('#curr_headcount_MIS');
                }
                validate = 1;
            }
            if ($('#ot_hrs_MIS').val() == '') {
                $('#ot_hrs_MIS').addClass("has-error");
                if ($('#spanot_hrs_MIS').length == 0) {
                    $('<span id="spanot_hrs_MIS" class="help-block">Required *</span>').insertAfter('#ot_hrs_MIS');
                }
                validate = 1;
            }
            if ($('#per_hrs_ot_amt_MIS').val() == '') {
                $('#per_hrs_ot_amt_MIS').addClass("has-error");
                if ($('#spanper_hrs_ot_amt_MIS').length == 0) {
                    $('<span id="spanper_hrs_ot_amt_MIS" class="help-block">Required *</span>').insertAfter('#per_hrs_ot_amt_MIS');
                }
                validate = 1;
            }
            if ($('#ot_type').val() == 'NA') {
                $('#ot_type').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanot_type').length == 0) {
                    $('<span id="spanot_type" class="help-block">Required *</span>').insertAfter('#ot_type');
                }
                validate = 1;
            }
            if ($('#ot_window_status').val() == 'NA') {
                $('#ot_window_status').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanot_window_status').length == 0) {
                    $('<span id="spanot_window_status" class="help-block">Required *</span>').insertAfter('#ot_window_status');
                }
                validate = 1;
            }
            //  if ($("#window_timing").prop('required', true)) {
            //     $('#window_timing').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
            //     if ($('#spanwindow_timing').length == 0) {
            //         $('<span id="spanwindow_timing" class="help-block">Required *</span>').insertAfter('#window_timing');
            //     }
            //     validate = 1;
            // }
            $("#window_timing").prop('required', true);
            if ($('#mis_approver').val() == 'NA') {
                $('#mis_approver').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanmis_approver').length == 0) {
                    $('<span id="spanmis_approver" class="help-block">Required *</span>').insertAfter('#mis_approver');
                }
                validate = 1;
            }

            if ($('#mis_remark').val() == '') {
                $('#mis_remark').addClass("has-error");
                if ($('#spanmis_remark').length == 0) {
                    $('<span id="spanmis_remark" class="help-block">Required *</span>').insertAfter('#mis_remark');
                }
                validate = 1;
            }
            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(50000).fadeOut("slow");
                return false;
            }
        })
    });

    function EditData_OT_MIS(el) {
        var tr = $(el).closest('tr');
        var otID = tr.find('.otID').text();
        var process = tr.find('.process').text();
        var cm_id = tr.find('.cm_id').text();
        var requirement_type = tr.find('.requirement_type').text();
        var start_date = tr.find('.start_date').text();
        var end_date = tr.find('.end_date').text();
        var ot_reason = tr.find('.ot_reason').text();
        var approved_fte = tr.find('.approved_fte').text();
        var curr_headcount = tr.find('.curr_headcount').text();
        var ot_hrs = tr.find('.ot_hrs').text();
        var per_hrs_ot_amt = tr.find('.per_hrs_ot_amt').text();
        var ot_type = tr.find('.ot_type').text();
        var ot_window_status = tr.find('.ot_window_status').text();
        var window_timing = tr.find('.window_timing').text();
        var new_window_timing = window_timing.split(",");
        var remark = tr.find('.remark').text();
        var mis_approve_status = tr.find('.mis_approve_status').text();
        var mis_remark = tr.find('.mis_remark').text();

        if (mis_approve_status == "Approve") {
            $('#hideID').val(otID);
            $('#process').val(process).prop('readonly', true);
            $('#cm_id').val(cm_id).prop('readonly', true);
            $('#requirement').val(requirement_type).prop('disabled', true);
            // $('#requirement').val(requirement_type).css("pointer-events", "none");
            $('#txt_dateFrom_updt').val(start_date).prop('readonly', true);
            $('#txt_dateTo_updt').val(end_date);
            $('#ot_reason').val(ot_reason).prop('disabled', true);
            $('#approved_fte_MIS').val(approved_fte).addClass('active').prop('readonly', true);
            $('#curr_headcount_MIS').val(curr_headcount).prop('readonly', true);
            $('#ot_hrs_MIS').val(ot_hrs).prop('readonly', true);
            $('#per_hrs_ot_amt_MIS').val(per_hrs_ot_amt).prop('readonly', true);
            $('#ot_type').val(ot_type).prop('disabled', true);
            $('#ot_window_status').val(ot_window_status).prop('disabled', true);
            $('#window_timing').val(new_window_timing).prop('disabled', true);
            $('#remark').val(remark).prop('disabled', true);
            $('#mis_approver').val(mis_approve_status).prop('disabled', true);
            $('#mis_remark').val(mis_remark).prop('disabled', true);
        } else {
            $('#hideID').val(otID);
            $('#process').val(process).prop('readonly', true);
            $('#cm_id').val(cm_id);
            $('#requirement').val(requirement_type);
            $('#txt_dateFrom_updt').val(start_date);
            $('#txt_dateTo_updt').val(end_date);
            $('#ot_reason').val(ot_reason);
            $('#approved_fte_MIS').val(approved_fte).addClass('active');
            $('#curr_headcount_MIS').val(curr_headcount).prop('readonly', true);
            $('#ot_hrs_MIS').val(ot_hrs);
            $('#per_hrs_ot_amt_MIS').val(per_hrs_ot_amt);
            $('#ot_type').val(ot_type);
            $('#ot_window_status').val(ot_window_status);
            $('#window_timing').val(new_window_timing);
            $('#remark').val(remark).prop('disabled', true);
            $('#mis_approver').val(mis_approve_status);
            $('#mis_remark').val(mis_remark);
        }
        $('#btn_edit_OT_MIS').removeClass('hidden');
        $('#btn_can_OT_MIS').removeClass('hidden');
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
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>