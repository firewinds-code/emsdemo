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

$clean_status_ah = clean($_SESSION["__status_ah"]);
$clean_user_log_id = clean($_SESSION['__user_logid']);
$clean_empid = clean($_SESSION['empid']);

if ($clean_user_log_id) {
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}

if ($_POST['txt_dateTo']) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $date_To = cleanUserInput($_POST['txt_dateTo']);
        $date_From = cleanUserInput($_POST['txt_dateFrom']);
    }
} else {
    $date_To = date('Y-m-d', time());
    $date_From = date('Y-m-d', time());
}

?>

<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Incentive Hours Count Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <h4 style="background-color: #19AEC4; color: #fff;">Incentive Hours Count Report</h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php $_SESSION["token"] = csrfToken();                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
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

                <div id="pnlTable">
                    <?php
                    $uLogID = clean($_SESSION['__user_logid']);
                    if (isset($_POST['btn_view'])) {
                        $sqlConnect = "select t1.cm_id,t5.location,t4.client_name,t3.process,t3.sub_process,t1.ot_hrs1,t2.ot_hrs2,ot_hrs1-ot_hrs2 as ot_hrs_diff from (select cm_id,sum(ot_hrs) as ot_hrs1 from ot_raise_req_mis where mis_approve_status='Approve' and (start_date between '" . $date_From . "' and '" . $date_To . "' or end_date between '" . $date_From . "' and '" . $date_To . "') group by cm_id)t1
                        join
                        (select cm_id,sum(ot_hrs) as ot_hrs2 from ot_upload where Date between '" . $date_From . "' and '" . $date_To . "'
                        group by cm_id)t2
                        on t1.cm_id=t2.cm_id
                        join new_client_master t3 on t3.cm_id=t1.cm_id
                        join client_master t4 on t3.client_name=t4.client_id
                        join location_master t5 on t5.id=t3.location";
                    } else {
                        $sqlConnect = "select t1.cm_id,t5.location,t4.client_name,t3.process,t3.sub_process,t1.ot_hrs1,t2.ot_hrs2,ot_hrs1-ot_hrs2 as ot_hrs_diff from (select cm_id,sum(ot_hrs) as ot_hrs1 from ot_raise_req_mis where mis_approve_status='Approve'
                        and year(start_date)=year(now()) and month(start_date)=month(now())
                        group by cm_id)t1
                        join
                        (select cm_id,sum(ot_hrs) as ot_hrs2 from ot_upload where year(Date) =year(now()) and month(Date) =month(now())
                        group by cm_id)t2
                        on t1.cm_id=t2.cm_id
                        join new_client_master t3 on t3.cm_id=t1.cm_id
                        join client_master t4 on t3.client_name=t4.client_id
                        join location_master t5 on t5.id=t3.location";
                    }
                    //echo $sqlConnect;
                    $stmt = $conn->prepare($sqlConnect);
                    $stmt->execute();
                    $resultQry = $stmt->get_result();
                    $count = $resultQry->num_rows;
                    if ($count > 0) {
                    ?>
                        <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Client</th>
                                    <th>Process</th>
                                    <th>Subprocess</th>
                                    <th>Total Delivered Hours</th>
                                    <th>Pending Incentive Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($resultQry as $key => $value) { ?>
                                    <tr>
                                        <td><?php echo $value['location']; ?></td>
                                        <td><?php echo $value['client_name']; ?></td>
                                        <td><?php echo $value['process']; ?></td>
                                        <td><?php echo $value['sub_process']; ?></td>
                                        <td><?php echo $value['ot_hrs1']; ?></td>
                                        <td><?php echo $value['ot_hrs_diff']; ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    <?php } else {
                        echo "<script>$(function(){ toastr.error('No Records Found'); }); </script>";
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
    $(function() {
        $('#alert_msg_close').click(function() {
            $('#alert_message').hide();
        });
        if ($('#alert_msg').text() == '') {
            $('#alert_message').hide();
        } else {
            $('#alert_message').delay(10000).fadeOut("slow");
        }

        $('#txt_dateFrom,#txt_dateTo').datetimepicker({
            timepicker: false,
            format: "Y-m-d",
            scrollMonth: false
        })
    });


    $('#btn_view').click(function() {
        var validate = 0;
        var alert_msg = '';

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

        if (validate == 1) {
            $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
            $('#alert_message').show().attr("class", "SlideInRight animated");
            $('#alert_message').delay(50000).fadeOut("slow");
            return false;
        }
    })
</script>