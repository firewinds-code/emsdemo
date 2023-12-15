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



if ($__user_logid) {
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}

$processQRY = "select t2.EmpID,EmpName,DATE_FORMAT(dateofjoin, '%d-%b-%Y') as dateofjoin,t5.client_name,DATE_FORMAT(t1.CreatedOn, '%d-%b-%Y %H:%i') as CreatedOn from asset_employee t1 join EmpID_Name t2 on t1.EmpID=t2.EmpID join employee_map t3 on t1.EmpID=t3.EmployeeID join new_client_master t4 on t3.cm_id=t4.cm_id join client_master t5 on t4.client_name=t5.client_id where t1.empid=?";
$stmt = $conn->prepare($processQRY);
$stmt->bind_param("s", $__user_logid);
$stmt->execute();
$resultQry = $stmt->get_result();
$ahoh_cmid = $resultQry->fetch_row();


if (isset($_POST['btn_save'])) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {

        // echo $__user_logid;
        // $empid = cleanUserInput($_POST['empID']);
        // $remarks = cleanUserInput($_POST['txt_Comment']);

        $remarks = addslashes($remarks);
        $query = "update asset_employee set Ack_flag=1,Ack_date=now() where EmpID=? ";
        $insert = $conn->prepare($query);
        $insert->bind_param("s", $__user_logid);
        $insert->execute();
        $result = $insert->get_result();
        // $result = $myDB->query($query);
        $_SESSION['__asset_decl'] = 'No';
        echo "<script>location.href='index.php'; </script>";
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
    <span id="PageTittle_span" class="hidden"> Asset Acknowledge</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4 style="background-color: #19AEC4; color: white;"> Asset Acknowledge </h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <script>
                    $(function() {
                        $('#txt_dateFrom,#txt_dateTo').datetimepicker({
                            timepicker: false,
                            format: 'Y-m-d'
                        });
                        $('#myTable').DataTable({
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [5, 10, 25, 50, -1],
                                ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                            ],

                            "bProcessing": true,
                            "bDestroy": true,
                            "bAutoWidth": true,
                            "sScrollY": "192",
                            "sScrollX": "100%",
                            "bScrollCollapse": true,
                            "bLengthChange": false,
                            "fnDrawCallback": function() {

                                $(".check_val_").prop('checked', $("#chkAll").prop('checked'));
                            }
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

                <?php $_SESSION["token"] = csrfToken(); ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <div class="input-field col s12 m12">
                    <div class="col s12 m12">



                        <div class="input-field col s4 m4">
                            <input type="text" name="txt_empid" id="txt_empid" value="<?php echo $ahoh_cmid[0] ?>" readonly>
                            <label for="txt_empid">Employee ID</label>
                        </div>

                        <div class="input-field col s4 m4">
                            <input type="text" name="txt_empname" id="txt_empname" value="<?php echo $ahoh_cmid[1] ?>" readonly>
                            <label for="txt_empname">Employee Name</label>
                        </div>

                        <div class="input-field col s4 m4">
                            <input type="text" name="txt_doj" id="txt_doj" value="<?php echo $ahoh_cmid[2] ?>" readonly>
                            <label for="txt_doj">Date of join</label>
                        </div>

                        <div class="input-field col s4 m4">
                            <input type="text" name="txt_client" id="txt_client" value="<?php echo $ahoh_cmid[3] ?>" readonly>
                            <label for="txt_client">Client</label>
                        </div>

                        <div class="input-field col s4 m4">
                            <input type="text" name="txt_issue" id="txt_issue" value="<?php echo $ahoh_cmid[4] ?>" readonly>
                            <label for="txt_issue">Issue Date</label>
                        </div>


                    </div>

                </div>
                <?php

                $chk_task = $myDB->query('select * from asset_employee_details where EmpID="' . $__user_logid . '"');
                $my_error = $myDB->getLastError();
                if (empty($my_error)) {
                    $table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
                    $table .= '<th>Asset</th>';
                    $table .= '<th>Asset Type</th>';
                    $table .= '<th>Brand</th>';
                    $table .= '<th>Model No</th>';
                    $table .= '<th>Serial No</th><thead><tbody>';

                    foreach ($chk_task as $key => $value) {

                        $table .= '<tr><td>' . $value['Asset'] . '</td>';
                        $table .= '<td>' . $value['Asset_type'] . '</td>';
                        $table .= '<td>' . $value['Brand'] . '</td>';
                        $table .= '<td>' . $value['ModelNo'] . '</td>';

                        $table .= '<td>' . $value['SerialNo'] . '</td></tr>';
                    }
                    $table .= '</tbody></table></div></div>';
                    echo $table;
                } else {
                    echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
                }


                ?>
                <br /><br />
                <div class="input-field col s12 m12 right-align">
                    <button type="submit" name="btn_save" id="btn_save" class="btn waves-effect waves-green">Acknowledge</button>
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

<?php
//if($induction_popup_flag==1 && $totalDays!=0 && $flag1==0){
?>
<script src="../Script/bootstrap2.min.js"></script>
<style>
    .disablediv {
        pointer-events: none;
        opacity: 70% !important;
    }
</style>

<?php
//} 
?>