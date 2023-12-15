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


if ($_POST['txt_dateTo']) {
    $date_To = $_POST['txt_dateTo'];
    $date_From = $_POST['txt_dateFrom'];
} else {
    $date_To = date('Y-m-d', time());
    $date_From = date('Y-m-d', time());
}

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Bajaj Finance Declaration Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Bajaj Finance Declaration Report </h4>

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
                        // $sqlConnect = "SELECT * FROM expense_food where Month(created_at)='" . $month . "' and YEAR(created_at)='" . $year . "'";

                        $sqlConnect = "select EmployeeID,EmpName,address,FatherName,designation,created_on from bajaj_finance_decl where cast(created_on as date) between '" . $date_From . "' and '" . $date_To . "'";

                        $myDB = new MysqliDb();
                        $result = $myDB->rawQuery($sqlConnect);
                        $mysql_error = $myDB->getLastError();
                        $rowCount = $myDB->count;
                        if ($rowCount > 0) { ?>
                            <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>EmployeeID</th>
                                        <th>Employee Name</th>
                                        <th>Address</th>
                                        <th>Father Name</th>
                                        <th>Designation</th>
                                        <th>Created_on</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($result as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['EmpName']; ?></td>
                                            <td><?php echo $value['address']; ?></td>
                                            <td><?php echo $value['FatherName']; ?></td>
                                            <td><?php echo $value['designation']; ?></td>
                                            <td><?php echo $value['created_on']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } else {
                            echo "<script>$(function(){ toastr.error('No Data Found " . $mysql_error . "'); }); </script>";
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
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>