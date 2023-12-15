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

if (isset($_SESSION)) {
    if (!isset($_SESSION['__user_logid'])) {
        $location = URL . 'Login';
        echo "<script>location.href='" . $location . "'</script>";
        exit();
    } else {
        if (!($_SESSION['__user_logid'] == 'CE03070003' || $_SESSION['__user_logid'] == 'CE10091236' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE09134997')) {
            $location = URL . 'View/';
            echo "<script>location.href='" . $location . "'</script>";
            exit();
        }
    }
} else {
    $location = URL . 'Login';
    echo "<script>location.href='" . $location . "'</script>";
    exit();
}

function df($urlFile)
{
    $file_name  =   basename($urlFile);
    //save the file by using base name
    $fn         =   file_put_contents($file_name, file_get_contents($urlFile));
    header("Expires: 0");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Content-type: application/file");
    header('Content-length: ' . filesize($file_name));
    header('Content-disposition: attachment; filename="' . basename($file_name) . '"');
    readfile($file_name);
}



?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">
    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Payroll Report</span>
    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">
        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <!-- Header for Form If any -->
            <h4> Payroll Report </h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">

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
                    <?php
                    // $d = new DateTime('first day of this month');
                    // echo  "<select name='date' id='date'>";
                    // for ($i = 0; $i < 3; $i++) {
                    //     $target = $d;
                    //     echo "<option value='" . $target->format("1/m/Y") . "'>" . $target->format("F Y") . "</option>";
                    //     $d->modify('first day of next month');
                    // }
                    // echo "</select>";
                    ?>
                    <div class="input-field col s4 m4">
                        <select name="center" id="center">
                            <option value="NA">Select Center</option>
                            <?php
                            $sql = "select id,location from location_master";
                            $myDB = new MysqliDb();
                            $result = $myDB->query($sql);
                            $mysqlError = $myDB->getLastError();
                            if (empty($mysqlError)) {
                                foreach ($result as $val) {
                                    echo "<option value=" . $val['id'] . ">" . $val['location'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-field col s4 m4">
                        <select name="month" id="month">
                            <option value="NA">Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>

                    <div class="input-field col s4 m4">
                        <select name="year" id="year">
                            <option value="NA">Select Year</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>

                </div>

                <div class="input-field col s12 m12 right-align">
                    <button type="button" name="btn_view" id="btn_view" class="btn waves-effect waves-green">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
                <!-- <div class="input-field col s12 m12 right-align" id="btn_search" style="display: none;">
                    <button> <a id="down_load" href="">Download</a></button>
                </div> -->

            </div>

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
</script>

<script>
    $(document).ready(function() {
        $("#btn_view").click(function() {
            // var dt = new Date();
            // var month = dt.getMonth();
            // var dt_to = $.datepicker.formatDate('MM yy', new Date());
            var center_val = $('#center option:selected').val();
            var month_val = $('#month option:selected').val();
            var year_val = $('#year option:selected').val();
            $.ajax({
                url: "../Controller/get_dataFor_LM_CR.php?center=" + center_val + "&month=" + month_val + "&year=" + year_val,
                success: function(response) {
                    if (response == "") {
                        alert('File not found');
                    } else {
                        $(location).attr('href', response);
                    }
                }
            });
        });
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>