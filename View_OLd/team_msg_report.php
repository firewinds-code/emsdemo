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


if (($clean_status_ah != 'No' && $clean_status_ah == $clean_user_log_id) || $clean_empid == "Yes") {
} else {
    $location = URL . 'Error';
    header("Location: $location");
    exit();
}
?>

<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Team Message Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <h4>Team Message Report</h4>
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

                <div id="pnlTable">
                    <?php
                    $uLogID = clean($_SESSION['__user_logid']);
                    $sqlConnect = 'select id, sender_empid,sender_name, msg, to_empid,to_empname, created_at, acknowledge, ack_datetime  from  team_msg_ack where sender_empid=?
                    union 
                    select id, sender_empid,sender_name, msg, to_empid,to_empname, created_at, (NOW()), null from team_msg where sender_empid=? ';
                    $stmt = $conn->prepare($sqlConnect);
                    $stmt->bind_param("ss", $uLogID, $uLogID);
                    if (!$stmt) {
                        echo "failed to run";
                        die;
                    }
                    $stmt->execute();
                    $resultQry = $stmt->get_result();
                    $count = $resultQry->num_rows;
                    // $result = $myDB->rawQuery($sqlConnect);
                    // $mysql_error = $myDB->getLastError();
                    if ($count > 0) {
                    ?>
                        <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Sender EmployeeID</th>
                                    <th>Sender Employee Name</th>
                                    <th>Message</th>
                                    <th>To EmployeeID</th>
                                    <th>To Employee Name</th>
                                    <th>Acknowledge</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;

                                foreach ($resultQry as $key => $value) {
                                    $numDate = mktime($value['acknowledge']); ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $value['sender_empid']; ?></td>
                                        <td><?php echo $value['sender_name']; ?></td>
                                        <td><?php echo $value['msg']; ?></td>
                                        <td><?php echo $value['to_empid']; ?></td>
                                        <td><?php echo $value['to_empname']; ?></td>
                                        <td><?php echo (is_numeric($numDate)) ?  'Pending' : $value['acknowledge']; ?></td>

                                    </tr>



                                <?php

                                } ?>

                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>