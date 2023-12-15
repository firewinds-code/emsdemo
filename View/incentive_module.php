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
$__user_logid = clean($_SESSION['__user_logid']);
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

if ($__status_ah == $__user_logid || $__status_oh == $__user_logid) {
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}

if ($__user_logid) {
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}

$processQRY = "select distinct t3.cm_id, concat(t4.client_name,'|',t3.process,'|',sub_process,'|',t5.location) as Process from employee_map t1 join status_table t2 on t1.EmployeeID = t2.EmployeeID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join location_master t5 on t3.location=t5.id where t1.emp_status = 'Active' and t2.status=6 and t1.df_id in (74,77,146,147,148,149) and t3.cm_id not in (select cm_id from client_status_master) and (account_head=? or oh=?) order by t4.client_name";
$stmt = $conn->prepare($processQRY);
$stmt->bind_param("ss", $__status_ah, $__status_oh);
$stmt->execute();
$resultQry = $stmt->get_result();
$ahoh_cmid = $resultQry->fetch_row();
$ahoh_cmid = $ahoh_cmid[0];


if (isset($_POST['btn_save'])) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {

        $prep = array();
        // echo '<pre>';
        // print_r($_POST);
        //die;
        $process = cleanUserInput($_POST['process']);
        $requirement = cleanUserInput($_POST['requirement']);
        $txt_dateFrom = cleanUserInput($_POST['txt_dateFrom']);
        $txt_dateTo = cleanUserInput($_POST['txt_dateTo']);
        $ot_reason = cleanUserInput($_POST['ot_reason']);
        $approved_fte = cleanUserInput($_POST['approved_fte']);
        $curr_headcount = cleanUserInput($_POST['curr_headcount']);
        $ot_hrs = cleanUserInput($_POST['ot_hrs']);
        $per_hrs_ot_amt = cleanUserInput($_POST['per_hrs_ot_amt']);
        $ot_type = cleanUserInput($_POST['ot_type']);
        $ot_window_status = cleanUserInput($_POST['ot_window_status']);
        // $window_timing = cleanUserInput($_POST['window_timing']);
        // print_r($_POST['window_timing']);
        $final_window_timing = implode(',', $_POST['window_timing']);
        //die;
        $remark = cleanUserInput($_POST['remark']);
        if ($txt_dateTo >= $txt_dateFrom) {

            $sqlot = "insert into ot_raise_req(cm_id,requirement_type,start_date,end_date,ot_reason,approved_fte,curr_headcount,ot_hrs,per_hrs_ot_amt,ot_type,ot_window_status,window_timing,remark,created_by)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmtot = $conn->prepare($sqlot);
            $stmtot->bind_param('issssiiiisssss', $process, $requirement, $txt_dateFrom, $txt_dateTo, $ot_reason, $approved_fte, $curr_headcount, $ot_hrs, $per_hrs_ot_amt, $ot_type, $ot_window_status, $final_window_timing, $remark, $__user_logid);
            $stmtot->execute();
            // print_r($stmtot);
            if ($stmtot->affected_rows === 1) {
                echo "<script>$(function(){toastr.success('Request Raised Successfully'); }); </script>";
            } else {
                echo "<script>$(function(){toastr.error('Something Went Wrong !'); }); </script>";
            }
        } else {
            echo "<script>$(function(){toastr.error('End Date Should Be Greater Than Start Date !'); }); </script>";
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
    <span id="PageTittle_span" class="hidden"> Incentive Module</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4 style="background-color: #19AEC4; color: white;"> Incentive Module </h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <?php $_SESSION["token"] = csrfToken(); ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <div class="input-field col s12 m12">
                    <div class="col s12 m12">

                        <div class="input-field col s8 m8 l8">
                            <select id="process" name="process">
                                <option Selected="True" Value="NA">-Select One-</option>
                                <?php foreach ($resultQry as $key => $value) {
                                    echo '<option value="' . $value['cm_id'] . '">' . $value['Process'] . '</option>';
                                } ?>
                            </select>
                            <label for="process" class="active-drop-down active">Process</label>
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
                            <input type="text" name="txt_dateFrom" id="txt_dateFrom">
                            <label title="Date" for="txt_dateFrom">Start Date</label>
                        </div>

                        <div class="input-field col s4 m4">
                            <input type="text" name="txt_dateTo" id="txt_dateTo">
                            <label title="Date" for="txt_dateTo">End Date</label>
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
                            <input type="text" name="approved_fte" id="approved_fte" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="5">
                            <label for="approved_fte">Approved FTE</label>
                        </div>

                        <div class="input-field col s4 m4">
                            <input type="text" name="curr_headcount" id="curr_headcount" value="" readonly>
                            <label for="curr_headcount">Current Headcount</label>
                        </div>

                        <div class="input-field col s4 m4">
                            <input type="text" name="ot_hrs" id="ot_hrs" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="5">
                            <label for="ot_hrs">Incentive Hours</label>
                        </div>

                        <div class="input-field col s4 m4">
                            <input type="text" name="per_hrs_ot_amt" id="per_hrs_ot_amt" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="5">
                            <label for="per_hrs_ot_amt">Per Hours Incentive Amount </label>
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
                            <select class="selectpicker" id="window_timing" name="window_timing[]" multiple required>
                                <option value="FullDay">Full Day</option>
                                <?php foreach ($array_of_time as $val) { ?>
                                    <option value=<?php echo $val ?>><?php echo $val ?></option>
                                <?php } ?>

                            </select>
                            <label for="window_timing" class="active-drop-down active">Window Timing </label>
                        </div>

                        <div class="input-field col s12 m12 l12">
                            <textarea class="materialize-textarea" id="remark" name="remark" maxlength="290"></textarea>
                            <label for="remark">Remark</label>
                        </div>

                    </div>

                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" name="btn_save" id="btn_save" class="btn waves-effect waves-green align-right">Save</button>
                    </div>

                </div>
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
    $(document).ready(function() {
        var currentTime = new Date();
        // First Date Of the month 
        var startDateFrom = new Date(currentTime.getFullYear(), currentTime.getMonth(), 1);
        // Last Date Of the Month 
        var startDateTo = new Date(currentTime.getFullYear(), currentTime.getMonth() + 1, 0);

        $('#txt_dateFrom,#txt_dateTo').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth: false,
            minDate: 0,
            maxDate: startDateTo
        });



        $('#btn_save').click(function() {
            var validate = 0;
            var alert_msg = '';

            if ($('#process').val() == 'NA') {
                $('#process').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanprocess').length == 0) {
                    $('<span id="spanprocess" class="help-block">Required *</span>').insertAfter('#process');
                }
                validate = 1;
            }

            if ($('#requirement').val() == 'NA') {
                $('#requirement').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanrequirement').length == 0) {
                    $('<span id="spanrequirement" class="help-block">Required *</span>').insertAfter('#requirement');
                }
                validate = 1;
            }

            if ($('#txt_dateFrom').val() == '') {
                $('#txt_dateFrom').addClass("has-error");
                if ($('#spantxt_dateFrom').length == 0) {
                    $('<span id="spantxt_dateFrom" class="help-block">Required *</span>').insertAfter('#txt_dateFrom');
                }
                validate = 1;
            }

            if ($('#txt_dateTo').val() == '') {
                $('#txt_dateTo').addClass("has-error");
                if ($('#spantxt_dateTo').length == 0) {
                    $('<span id="spantxt_dateTo" class="help-block">Required *</span>').insertAfter('#txt_dateTo');
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

            if ($('#approved_fte').val() == '') {
                $('#approved_fte').addClass("has-error");
                if ($('#spanapproved_fte').length == 0) {
                    $('<span id="spanapproved_fte" class="help-block">Required *</span>').insertAfter('#approved_fte');
                }
                validate = 1;
            }
            if ($('#curr_headcount').val() == '') {
                $('#curr_headcount').addClass("has-error");
                if ($('#spancurr_headcount').length == 0) {
                    $('<span id="spancurr_headcount" class="help-block">Required *</span>').insertAfter('#curr_headcount');
                }
                validate = 1;
            }
            if ($('#ot_hrs').val() == '') {
                $('#ot_hrs').addClass("has-error");
                if ($('#spanot_hrs').length == 0) {
                    $('<span id="spanot_hrs" class="help-block">Required *</span>').insertAfter('#ot_hrs');
                }
                validate = 1;
            }
            if ($('#per_hrs_ot_amt').val() == '') {
                $('#per_hrs_ot_amt').addClass("has-error");
                if ($('#spanper_hrs_ot_amt').length == 0) {
                    $('<span id="spanper_hrs_ot_amt" class="help-block">Required *</span>').insertAfter('#per_hrs_ot_amt');
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

            if ($('#window_timing').val() == '') {
                $('#window_timing').addClass("has-error");
                if ($('#spanwindow_timing').length == 0) {
                    $('<span id="spanwindow_timing" class="help-block">Required *</span>').insertAfter('#window_timing');
                }
                validate = 1;
            }

            if ($('#remark').val() == '') {
                $('#remark').addClass("has-error");
                if ($('#spanremark').length == 0) {
                    $('<span id="spanremark" class="help-block">Required *</span>').insertAfter('#remark');
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

        $('#process').change(function() {
            var cmid = $(this).val();
            $.ajax({
                type: "get",
                url: "../Controller/get_current_HC.php",
                data: {
                    cmid: cmid
                },
                success: function(response) {
                    // alert(response)
                    $('#curr_headcount').val(response)
                }
            });
        })

    });
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        // $('#window_timing').timepicker({
        //     showMeridian: false
        // });
        // $('#window_timing').datetimepicker({
        //     format: 'H:m',
        //     pickDate: false,
        //     pickSeconds: false,
        //     pick12HourFormat: false
        // });
    });
</script>