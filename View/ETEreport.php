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

if ($_SESSION['__user_logid'] == 'CE091930141' || $_SESSION['__user_logid'] == 'CE03070003' || $_SESSION['__user_logid'] == 'CE12102224') {
    // proceed further
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">ETE Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>ETE Report</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">
                <script>
                    $(function() {
                        $('#txt_dateFrom,#txt_dateTo').datetimepicker({
                            timepicker: false,
                            format: 'Y-m-d',
                            maxDate: '0',
                            scrollInput: false
                        });
                        $('#myTable').DataTable({
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [25, 50, -1],
                                ['25 rows', '50 rows', 'Show all']
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
                            }, 'pageLength'],
                            "bProcessing": true,
                            "bDestroy": true,
                            "bAutoWidth": true,
                            "sScrollY": "100%",
                            "sScrollX": "100%",
                            "bScrollCollapse": false,
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

                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s4 m4">
                        <span>Date From</span>
                        <input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
                    </div>
                    <div class="input-field col s4 m4">
                        <span>Date To</span>
                        <input type="text" class="form-control" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
                    </div>

                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
                <?php
                // if (isset($_POST['btn_view'])) {
                //     $date_From = cleanUserInput($_POST['txt_dateFrom']);
                //     $date_To = cleanUserInput($_POST['txt_dateTo']);
                //     $sqlConnect = "call getExcelReport('" . $date_From . "','" . $date_To . "')";
                //     $result = $myDB->rawQuery($sqlConnect);
                //     $mysql_error = $myDB->getLastError();
                //     if (empty($mysql_error)) {
                //         $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                //         <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                //         $table .= '<th>Location</th>';
                //         $table .= '<th>EmployeeID</th>';
                //         $table .= '<th>Employee Name</th>';
                //         $table .= '<th>DOJ</th>';
                //         $table .= '<th>Designation</th>';
                //         $table .= '<th>Dept Name</th>';
                //         $table .= '<th>Client</th>';
                //         $table .= '<th>Process</th>';
                //         $table .= '<th>Sub Process</th>';
                //         $table .= '<th>Function</th>';
                //         $table .= '<th>Employee Stage</th>';
                //         $table .= '<th>Experience Status</th>';
                //         $table .= '<th>Fte/Pte</th>';
                //         $table .= '<th>Education</th>';
                //         $table .= '<th>Test Status</th><thead><tbody>';

                //         foreach ($result as $key => $value) {
                //             $table .= '<tr><td>' . $value['Location'] . '</td>';
                //             $table .= '<td>' . $value['EmployeeID'] . '</td>';
                //             $table .= '<td>' . $value['EmployeeName'] . '</td>';
                //             $table .= '<td>' . $value['DOJ'] . '</td>';
                //             $table .= '<td>' . $value['Designation'] . '</td>';
                //             $table .= '<td>' . $value['Dept Name'] . '</td>';
                //             $table .= '<td>' . $value['Client'] . '</td>';
                //             $table .= '<td>' . $value['process'] . '</td>';
                //             $table .= '<td>' . $value['sub_process'] . '</td>';
                //             $table .= '<td>' . $value['Function'] . '</td>';
                //             $table .= '<td>' . $value['Employee Stage'] . '</td>';
                //             $table .= '<td>' . $value['Experience Status'] . '</td>';
                //             $table .= '<td>' . $value['Fte/Pte'] . '</td>';
                //             $table .= '<td>' . $value['Education'] . '</td>';
                //             $table .= '<td>' . $value['Test Status'] . '</td></tr>';
                //         }
                //         $table .= '</tbody></table></div></div>';
                //         echo $table;
                //     } else {
                //         echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
                //     }
                // }

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
    });
    $('#btn_view').on('click', function() {
        var date_from = $('#txt_dateFrom').val();
        var date_to = $('#txt_dateTo').val();
        var sp = "call getETEReport('" + date_from + "','" + date_to + "')";
        var url = "textExport.php?sp=" + sp;
        // alert(url);
        window.location.href = url;
        return false;
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>