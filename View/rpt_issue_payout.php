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
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
    if (!isset($user_logid)) {
        $location = URL . 'Login';
        header("Location: $location");
        exit();
    } else {

        if (isset($_POST['txt_dateTo'])) {
            if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
                $date_To = cleanUserInput($_POST['txt_dateTo']);
                $date_From = cleanUserInput($_POST['txt_dateFrom']);
            }
        } else {
            $date_To = date('Y-m-d', time());
            $date_From = date('Y-m-d', time());
        }
    }
} else {
    $location = URL . 'Login';
    header("Location: $location");
}

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
    $txt_location = cleanUserInput($_POST['txt_location']);
}
?>

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
                }, 'pageLength'

            ],
            "bProcessing": true,
            "bDestroy": true,
            "bAutoWidth": true,
            "iDisplayLength": 25,
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Grievance Reports</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Grievance Reports</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php
                $_SESSION["token"] = csrfToken();
                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s3 m3">

                        <input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
                    </div>
                    <div class="input-field col s3 m3">

                        <input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
                    </div>
                    <div class="input-field col s3 m3">

                        <select id="txt_location" name="txt_location" required>
                            <option value="NA">----Select----</option>
                            <?php
                            $sqlBy = 'select id,location from location_master;';
                            $myDB = new MysqliDb();
                            $resultBy = $myDB->rawQuery($sqlBy);
                            $mysql_error = $myDB->getLastError();
                            if (empty($mysql_error)) {
                                echo '<option value="0"  >ALL</option>';
                                foreach ($resultBy as $key => $value) {
                                    echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <label for="txt_location" class="active-drop-down active">Location</label>
                    </div>
                    <div class="input-field col s2 m2">
                        <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
                        <!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
                    </div>
                </div>
                <?php
                $myDB = new MysqliDb();

                $_location = (isset($txt_location) ? $txt_location : null);
                if ($_location == "ALL") {
                } else {
                }
				//echo 'call get_issueReport_payout("' . $date_From . '","' . $date_To . '", "' . $_location . '")';
                $chk_task = $myDB->query('call get_issueReport_payout("' . $date_From . '","' . $date_To . '", "' . $_location . '")');
                $my_error = $myDB->getLastError();

                if (count($chk_task) > 0 && $chk_task) {
                    $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
                    $table .= '<th>Case ID</th>';
                    $table .= '<th>Employee ID</th>';
                    $table .= '<th>Employee Name</th>';
                    $table .= '<th>Issue Date</th>';
                    $table .= '<th>Issue Type</th>';
                    $table .= '<th>Payout Days</th>';
                    $table .= '<th>Payout Type</th>';
                    //$table .= '<th>Amount</th>';
                    $table .= '<th>Requested Remark </th>';
                    $table .= '<th>Payout Status</th>';
                    $table .= '<th>Payout Remark</th>';
                    $table .= '<th>Created At</th>';
                    $table .= '<thead><tbody>';

                    foreach ($chk_task as $key => $value) {
                        $table .= '<tr><td>' . $value['issue_tracker_id'] . '</td>';
                        $table .= '<td>' . $value['requestby'] . '</td>';
                        $table .= '<td>' . $value['EmpName'] . '</td>';
                        $table .= '<td>' . $value['issue_date'] . '</td>';
                        $table .= '<td>' . $value['issue_type'] . '</td>';
                        $table .= '<td>' . $value['payout_days'] . '</td>';
                        $table .= '<td>' . $value['amount_type'] . '</td>';
                     //   $table .= '<td>' . $value['amount'] . '</td>';
                        $table .= '<td>' . $value['req_remark'] . '</td>';
                        $table .= '<td>' . $value['payout_status'] . '</td>';
                        $table .= '<td>' . $value['payout_remarks'] . '</td>';
                        $table .= '<td>' . $value['created_at'] . '</td></tr>';
                    }
                    $table .= '</tbody></table></div>';
                    echo $table;
                } else {
                    echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
                }
                ?>

            </div>
            <!--Reprot / Data Table End -->
        </div>
        <!--Form container End -->
    </div>
    <!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>

<script>
    $("#btn_view").click(function() {
        //alert($("#txt_location").val());
        if ($('#txt_location').val() == "NA") {
            alert('Please select location');
            return false;
        }
    });

    $('#btn_export').on('click', function() {
        var date_f = $('#txt_dateFrom').val();
        var date_t = $('#txt_dateTo').val();
        var loc = $('#txt_location').val();
        var sp = 'call get_issueReport("' + date_f + '","' + date_t + '","' + loc + '")';
        // alert(sp)
        var url = "textExport.php?sp=" + sp;
        // alert(url)
        window.location.href = url;
        return false;
    })
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>