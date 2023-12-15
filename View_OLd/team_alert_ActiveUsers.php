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

$clean_status_ah = clean($_SESSION["__status_ah"]);
$clean_userid = clean($_SESSION['__user_logid']);
$clean_emid = clean($_SESSION['empid']);

if (($clean_status_ah != 'No' && $clean_status_ah == $clean_userid) || $clean_emid == "Yes") {
} else {
    $location = URL . 'Error';
    header("Location: $location");
    exit();
}
?>

<div id="content" class="content">
    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Team Alert Active User</span>
    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">
        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <h4>Team Alert Active User</h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">
                <div id="pnlTable">
                    <?php
                    $getdata = "select distinct t1.EmployeeID, t2.EmpName as EmployeeName from team_desimination_login  t1 left join EmpID_Name  t2 on t1.EmployeeID=t2.EmpID where t1.CreatedOn between date_sub(now(), INTERVAL 5 minute) and now()";
                    $result = $myDB->rawQuery($getdata);
                    //print_r($result);
                    $mysql_error = $myDB->getLastError();

                    ?>
                    <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>EmployeeID</th>
                                <th>Employee Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($result as $value) { ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $value['EmployeeID'] ?></td>
                                    <td><?php echo $value['EmployeeName'] ?></td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //contain load event for data table and other importent rand required trigger event and searches if any

    $(function() {
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
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>