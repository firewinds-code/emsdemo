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
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

// if ($_SESSION['__user_type'] == 'ADMINISTRATOR') {
//     // proceed further
// } else {
//     $location = URL;
//     echo "<script>location.href='" . $location . "'</script>";
// }


$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;

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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">
    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Incentive Final Report</span>
    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">
        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <!-- Header for Form If any -->
            <h4> Incentive Final Report </h4>
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

                        <!-- <button type="button" name="btn_Excel" id="btn_Excel" class="btn waves-effect waves-info" onclick="javascript:return downloadexcel(this);"><i class="fa-solid fa-file-export"></i>Export</button> -->
                    </div>
                </div>

                <?php
                if (isset($_POST['btn_view'])) {
                ?>
                    <div id="pnlTable">
                        <?php
                        $sqlConnect = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) as Process,t3.cm_id, t3.requirement_type, t3.start_date, t3.end_date, t3.ot_reason, t3.approved_fte, t3.curr_headcount, t3.ot_hrs, t3.per_hrs_ot_amt, t3.ot_type, t3.ot_window_status, t3.window_timing, t3.remark, t3.mis_approve_status, t3.mis_remark, t3.created_by, t3.created_at,t4.requirement_type as final_requirement_type, t4.start_date as final_start_date, t4.end_date as final_end_date, t4.ot_reason as final_ot_reason, t4.approved_fte as final_approved_fte, t4.curr_headcount as final_curr_headcount, t4.ot_hrs as final_ot_hrs, t4.per_hrs_ot_amt as final_per_hrs_ot_amt, t4.ot_type as final_ot_type, t4.ot_window_status as final_ot_window_status, t4.window_timing as final_window_timing, t4.mis_approve_status, t4.mis_remark, t4.created_by as updated_by_mis, t4.created_at as updated_at_mis from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id join ot_raise_req t3 on t1.cm_id=t3.cm_id left join ot_raise_req_mis t4 on t3.id=t4.otraise_req_id where cast(t3.created_at as date) between ? and ? ";
                        $stmts = $conn->prepare($sqlConnect);
                        $stmts->bind_param("ss", $date_From, $date_To);
                        $stmts->execute();
                        $result = $stmts->get_result();
                        if ($result->num_rows > 0) { ?>
                            <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Process</th>
                                        <th>CM_ID</th>
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
                                        <th>Final Requirement Type</th>
                                        <th>Final Start Date</th>
                                        <th>Final End Date</th>
                                        <th>Final Incentive Reason</th>
                                        <th>Final Approved Fte</th>
                                        <th>Final Current Headcount</th>
                                        <th>Final Incentive Hours</th>
                                        <th>Final Per Hour Incentive Amount</th>
                                        <th>Final Incentive Type</th>
                                        <th>Final Incentive Window Status</th>
                                        <th>Final Window Timing</th>
                                        <th>Approver Status</th>
                                        <th>Approver Remark</th>
                                        <th>Created_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($result as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $value['Process']; ?></td>
                                            <td><?php echo $value['cm_id']; ?></td>
                                            <td><?php echo $value['requirement_type']; ?></td>
                                            <td><?php echo $value['start_date']; ?></td>
                                            <td><?php echo $value['end_date']; ?></td>
                                            <td><?php echo $value['ot_reason']; ?></td>
                                            <td><?php echo $value['approved_fte']; ?></td>
                                            <td><?php echo $value['curr_headcount']; ?></td>
                                            <td><?php echo $value['ot_hrs']; ?></td>
                                            <td><?php echo $value['per_hrs_ot_amt']; ?></td>
                                            <td><?php echo $value['ot_type']; ?></td>
                                            <td><?php echo $value['ot_window_status']; ?></td>
                                            <td><?php echo $value['window_timing']; ?></td>
                                            <td><?php echo $value['remark']; ?></td>
                                            <td><?php echo $value['final_requirement_type']; ?></td>
                                            <td><?php echo $value['final_start_date']; ?></td>
                                            <td><?php echo $value['final_end_date']; ?></td>
                                            <td><?php echo $value['final_ot_reason']; ?></td>
                                            <td><?php echo $value['final_approved_fte']; ?></td>
                                            <td><?php echo $value['final_curr_headcount']; ?></td>
                                            <td><?php echo $value['final_ot_hrs']; ?></td>
                                            <td><?php echo $value['final_per_hrs_ot_amt']; ?></td>
                                            <td><?php echo $value['final_ot_type']; ?></td>
                                            <td><?php echo $value['final_ot_window_status']; ?></td>
                                            <td><?php echo $value['final_window_timing']; ?></td>
                                            <td><?php echo $value['mis_approve_status']; ?></td>
                                            <td><?php echo $value['mis_remark']; ?></td>
                                            <td><?php echo $value['created_at']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } else {
                            echo "<script>$(function(){ toastr.error('No Records Found'); }); </script>";
                        }
                        ?>
                    </div>
            </div>
        <?php }
        ?>

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
            $('#alert_message').delay(10000).fadeOut("slow");
        }

        $('#txt_dateFrom,#txt_dateTo').datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });
    });

    // function downloadexcel(el) {
    //     $item = $(el);
    //     var date_from = $('#txt_dateFrom').val();
    //     var date_to = $('#txt_dateTo').val();
    //     var sp = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) as Process,t3.cm_id, t3.requirement_type, t3.start_date, t3.end_date, t3.ot_reason, t3.approved_fte, t3.curr_headcount, t3.ot_hrs, t3.per_hrs_ot_amt, t3.ot_type, t3.ot_window_status, t3.window_timing, t3.remark, t3.mis_approve_status, t3.mis_remark, t3.created_by, t3.created_at,t4.requirement_type as final_requirement_type, t4.start_date as final_start_date, t4.end_date as final_end_date, t4.ot_reason as final_ot_reason, t4.approved_fte as final_approved_fte, t4.curr_headcount as final_curr_headcount, t4.ot_hrs as final_ot_hrs, t4.per_hrs_ot_amt as final_per_hrs_ot_amt, t4.ot_type as final_ot_type, t4.ot_window_status as final_ot_window_status, t4.window_timing as final_window_timing, t4.mis_remark, t4.created_by as updated_by_mis, t4.created_at as updated_at_mis from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id join ot_raise_req t3 on t1.cm_id=t3.cm_id left join ot_raise_req_mis t4 on t3.id=t4.otraise_req_id  where cast(t3.created_at as date) between '" + date_from + "' and '" + date_to + "' ";
    //     // var sp = "select window_timing from ot_raise_req where cast(created_at as date) between '" + date_from + "' and '" + date_from + "' ";
    //     var url = "textExport_incentive.php?sp=" + sp;
    //     // alert(url);
    //     // return false;
    //     window.location.href = url;
    // }
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>