<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

// if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224') {
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
    <span id="PageTittle_span" class="hidden">Employee Block Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Employee Block Report </h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php
                $_SESSION["token"] = csrfToken();
                ?>
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

                <?php
                if (isset($_POST['btn_view'])) {
                    // $myDB = new MysqliDb();
                    // $conn = $myDB->dbConnect();


                ?>

                    <div id="pnlTable">
                        <?php
                        // $sqlConnect = "SELECT * FROM expense_food where Month(created_at)='" . $month . "' and YEAR(created_at)='" . $year . "'";

                        // $sqlConnect = "select t1.EmployeeID,t2.EmpName,t9.Designation,t6.client_name,t5.process,t5.sub_process,t3.location,t1.aadharNo,t7.disposition,t7.createdon,t1.created_at from emp_block t1 left join EmpID_Name t2 on t1.EmployeeID=t2.EmpID left join location_master t3 on t3.id=t2.loc left join employee_map t4 on t4.EmployeeID=t1.EmployeeID left join new_client_master t5 on t5.cm_id=t4.cm_id left join client_master t6 on t6.client_id=t5.client_name left join exit_emp t7 on t7.EmployeeID=t1.EmployeeID left join df_master t8 on t8.df_id=t4.df_id left join designation_master t9 on t9.ID=t8.des_id where cast(created_at as date) between '" . $date_From . "' and '" . $date_To . "'";
                        $sqlConnect = "select t1.EmployeeID,t2.EmpName,t9.Designation,t6.client_name,t5.process,t5.sub_process,t3.location,t1.aadharNo,t7.disposition,t7.createdon,t1.created_at from emp_block t1 left join EmpID_Name t2 on t1.EmployeeID=t2.EmpID left join location_master t3 on t3.id=t2.loc left join employee_map t4 on t4.EmployeeID=t1.EmployeeID left join new_client_master t5 on t5.cm_id=t4.cm_id left join client_master t6 on t6.client_id=t5.client_name left join exit_emp t7 on t7.EmployeeID=t1.EmployeeID left join df_master t8 on t8.df_id=t4.df_id left join designation_master t9 on t9.ID=t8.des_id where cast(created_at as date) between ? and ? ";
                        $stmts = $conn->prepare($sqlConnect);
                        $stmts->bind_param("ss", $date_From, $date_To);
                        $stmts->execute();
                        $result = $stmts->get_result();
                        // print_r($result);
                        // die;
                        if ($result->num_rows > 0) {
                            // $myDB = new MysqliDb();
                            // $result = $myDB->rawQuery($sqlConnect);
                            // $mysql_error = $myDB->getLastError();
                            // if ($myDB->count > 0) { 
                        ?>
                            <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>

                                        <th>EmployeeID</th>
                                        <th>Employee Name</th>
                                        <th>Designation</th>
                                        <th>Client</th>
                                        <th>Process</th>
                                        <th>Subprocess</th>
                                        <th>Location</th>
                                        <th>Aadhar No</th>
                                        <th>Disposition</th>
                                        <th>InActive Date</th>
                                        <th>Block Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($result as $key => $value) { ?>
                                        <tr>

                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['EmpName']; ?></td>
                                            <td><?php echo $value['Designation']; ?></td>
                                            <td><?php echo $value['client_name']; ?></td>
                                            <td><?php echo $value['process']; ?></td>
                                            <td><?php echo $value['sub_process']; ?></td>
                                            <td><?php echo $value['location']; ?></td>
                                            <td><?php echo $value['aadharNo']; ?></td>
                                            <td><?php echo $value['disposition']; ?></td>
                                            <td><?php echo $value['createdon']; ?></td>
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
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>