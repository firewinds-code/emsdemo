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


// Global variable used in Page Cycle

if ($_SESSION['reviewer'] == "Yes" || $_SESSION['approver'] == "Yes") {
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}
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
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
    $reqType = $_POST['reqType'];
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Expense Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Expense Report </h4>

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

                    <div class="input-field col s4 m4">
                        <Select class="form-control" name="reqType" id="reqType">
                            <option value='food' <?php if (isset($_POST['reqType']) && $_POST['reqType'] == 'food') {
                                                        echo "selected";
                                                    } ?>>Food</option>
                            <option value='travel' <?php if (isset($_POST['reqType']) && $_POST['reqType'] == 'travel') {
                                                        echo "selected";
                                                    } ?>>Travel</option>
                            <option value='hotel' <?php if (isset($_POST['reqType']) && $_POST['reqType'] == 'hotel') {
                                                        echo "selected";
                                                    } ?>>Hotel</option>
                             <option value='mobile' <?php if (isset($_POST['reqType']) && $_POST['reqType'] == 'mobile') {
                                                        echo "selected";
                                                    } ?>>Mobile</option>
                            <option value='miscellaneous' <?php if (isset($_POST['reqType']) && $_POST['reqType'] == 'miscellaneous') {
                                                                echo "selected";
                                                            } ?>>Miscellaneous</option>
                        </Select>
                    </div>
                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>

                <?php
                $btn_view = isset($_POST['btn_view']);
                if ($btn_view) {
                    $myDB = new MysqliDb();
                    if ($reqType == 'food') { ?>
                        <h4>Food Report</h4>
                        <div id="pnlTable">
                            <?php
                            // $sqlConnect = "SELECT * FROM expense_food where Month(created_at)='" . $month . "' and YEAR(created_at)='" . $year . "'";

                            // $sqlConnect = "select ef.*,t4.client_name,t3.process,t3.sub_process,t6.Designation,t7.location from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.ID join location_master t7 on t2.loc=t7.id join expense_food ef on ef.EmployeeID=t2.EmpID  where cast(ef.created_at as date) between '" . $date_From . "' and '" . $date_To . "'";
                            $sqlConnect = "select ef.*,t4.client_name,t3.process,t3.sub_process,t6.Designation,t7.location from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.ID join location_master t7 on t2.loc=t7.id join expense_food ef on ef.EmployeeID=t2.EmpID  where cast(ef.created_at as date) between ? and ?";
                            $stmt = $conn->prepare($sqlConnect);
                            $stmt->bind_param("ss", $date_From, $date_To);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) { ?>
                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>EmployeeID</th>
                                            <th>Employee Name</th>
                                            <th>Client</th>
                                            <th>Process</th>
                                            <th>Subprocess</th>
                                            <th>Designation</th>
                                            <th>Location</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                            <th>Request Type</th>
                                            <th>Manager Status</th>
                                            <th>Manager Comment</th>
                                            <th>Review Status</th>
                                            <th>Review Comment</th>
                                            <th>Receipt No</th>
                                            <th>Receipt Image</th>
                                            <th>Created On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        foreach ($result as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value['EmployeeID']; ?></td>
                                                <td><?php echo $value['empName']; ?></td>
                                                <td><?php echo $value['client_name']; ?></td>
                                                <td><?php echo $value['process']; ?></td>
                                                <td><?php echo $value['sub_process']; ?></td>
                                                <td><?php echo $value['Designation']; ?></td>
                                                <td><?php echo $value['location']; ?></td>
                                                <td><?php echo $value['date']; ?></td>
                                                <td><?php echo $value['amount']; ?></td>
                                                <td><?php echo $value['remarks']; ?></td>
                                                <td><?php echo $value['reqType']; ?></td>
                                                <td><?php echo $value['mgrStatus']; ?></td>
                                                <td><?php echo $value['mgrComment']; ?></td>
                                                <td><?php echo $value['reviewerStatus']; ?></td>
                                                <td><?php echo $value['reviewComment']; ?></td>
                                                <td><?php echo $value['receipt_no']; ?></td>
                                                <td>
                                                    <a style="background-color: #eee; text-decoration: underline;" href="../ExpenseFood/<?php echo $value['receipt_image']; ?>" target="_blank" download><?php echo $value['receipt_image']; ?></a>
                                                </td>
                                                <td><?php echo $value['created_at']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else {
                                echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
                            } ?>
                        </div>

                    <?php } else  if ($reqType == 'travel') { ?>
                        <h4>Travel Report</h4>
                        <div id="pnlTable">
                            <?php
                            // $sqlConnect = "SELECT * FROM expense_travel where Month(created_at)='" . $month . "' and YEAR(created_at)='" . $year . "'";

                            // $sqlConnect = "select et.*,t4.client_name,t3.process,t3.sub_process,t6.Designation,t7.location from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.ID join location_master t7 on t2.loc=t7.id join expense_travel et on et.EmployeeID=t2.EmpID  where cast(et.created_at as date) between '" . $date_From . "' and '" . $date_To . "'";
                            $sqlConnect = "select et.*,t4.client_name,t3.process,t3.sub_process,t6.Designation,t7.location from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.ID join location_master t7 on t2.loc=t7.id join expense_travel et on et.EmployeeID=t2.EmpID  where cast(et.created_at as date) between ? and ?";
                            $stmt = $conn->prepare($sqlConnect);
                            $stmt->bind_param("ss", $date_From, $date_To);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) { ?>

                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>EmployeeID</th>
                                            <th>Employee Name</th>
                                            <th>Client</th>
                                            <th>Process</th>
                                            <th>Subprocess</th>
                                            <th>Designation</th>
                                            <th>Location</th>
                                            <th>Date</th>
                                            <th>Place From</th>
                                            <th>Place To</th>
                                            <th>Mode Of Travel</th>
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                            <th>Request Type</th>
                                            <th>Manager Status</th>
                                            <th>Manager Comment</th>
                                            <th>Review Status</th>
                                            <th>Review Comment</th>
                                            <th>Receipt No</th>
                                            <th>Receipt Image</th>
                                            <th>Created On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        foreach ($result as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value['EmployeeID']; ?></td>
                                                <td><?php echo $value['empName']; ?></td>
                                                <td><?php echo $value['client_name']; ?></td>
                                                <td><?php echo $value['process']; ?></td>
                                                <td><?php echo $value['sub_process']; ?></td>
                                                <td><?php echo $value['Designation']; ?></td>
                                                <td><?php echo $value['location']; ?></td>
                                                <td><?php echo $value['date']; ?></td>
                                                <td><?php echo $value['placeFrom']; ?></td>
                                                <td><?php echo $value['placeTO']; ?></td>
                                                <td><?php echo $value['modeOftravel']; ?></td>
                                                <td><?php echo $value['amount']; ?></td>
                                                <td><?php echo $value['remarks']; ?></td>
                                                <td><?php echo $value['reqType']; ?></td>
                                                <td><?php echo $value['mgrStatus']; ?></td>
                                                <td><?php echo $value['mgrComment']; ?></td>
                                                <td><?php echo $value['reviewerStatus']; ?></td>
                                                <td><?php echo $value['reviewComment']; ?></td>
                                                <td><?php echo $value['receipt_no']; ?></td>
                                                <td>
                                                    <a style="background-color: #eee; text-decoration: underline;" href="../ExpenseTravel/<?php echo $value['receipt_image']; ?>" target="_blank" download><?php echo $value['receipt_image']; ?></a>
                                                </td>
                                                <td><?php echo $value['created_at']; ?></td>

                                            <?php

                                        } ?>

                                    </tbody>
                                </table>
                            <?php } else {
                                echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
                            } ?>
                        </div>
                    <?php } else if ($reqType == 'hotel') { ?>
                        <h4>Hotel Report</h4>
                        <div id="pnlTable">
                            <?php
                            // $sqlConnect = "SELECT * FROM expense_hotel where Month(created_at)='" . $month . "' and YEAR(created_at)='" . $year . "'";

                            // $sqlConnect = "select eh.*,t4.client_name,t3.process,t3.sub_process,t6.Designation,t7.location from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.ID join location_master t7 on t2.loc=t7.id join expense_hotel eh on eh.EmployeeID=t2.EmpID  where cast(eh.created_at as date) between '" . $date_From . "' and '" . $date_To . "'";
                            $sqlConnect = "select eh.*,t4.client_name,t3.process,t3.sub_process,t6.Designation,t7.location from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.ID join location_master t7 on t2.loc=t7.id join expense_hotel eh on eh.EmployeeID=t2.EmpID  where cast(eh.created_at as date) between ? and ?";
                            $stmt = $conn->prepare($sqlConnect);
                            $stmt->bind_param("ss", $date_From, $date_To);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) { ?>
                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>EmployeeID</th>
                                            <th>Employee Name</th>
                                            <th>Client</th>
                                            <th>Process</th>
                                            <th>Subprocess</th>
                                            <th>Designation</th>
                                            <th>Location</th>
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>No Of Days</th>
                                            <th>Visited Location</th>
                                            <th>Visited Client Name</th>
                                            <th>Hotel Name</th>
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                            <th>Request Type</th>
                                            <th>Manager Status</th>
                                            <th>Manager Comment</th>
                                            <th>Review Status</th>
                                            <th>Review Comment</th>
                                            <th>Receipt No</th>
                                            <th>Receipt Image</th>
                                            <th>Created On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        foreach ($result as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value['EmployeeID']; ?></td>
                                                <td><?php echo $value['empName']; ?></td>
                                                <td><?php echo $value['client_name1']; ?></td>
                                                <td><?php echo $value['process']; ?></td>
                                                <td><?php echo $value['sub_process']; ?></td>
                                                <td><?php echo $value['Designation']; ?></td>
                                                <td><?php echo $value['location']; ?></td>
                                                <td><?php echo $value['dateFrom']; ?></td>
                                                <td><?php echo $value['dateTo']; ?></td>
                                                <td><?php echo $value['noOfdays']; ?></td>
                                                <td><?php echo $value['visited_location']; ?></td>
                                                <td><?php echo $value['client_name']; ?></td>
                                                <td><?php echo $value['hotelName']; ?></td>
                                                <td><?php echo $value['amount']; ?></td>
                                                <td><?php echo $value['remarks']; ?></td>
                                                <td><?php echo $value['reqType']; ?></td>
                                                <td><?php echo $value['mgrStatus']; ?></td>
                                                <td><?php echo $value['mgrComment']; ?></td>
                                                <td><?php echo $value['reviewerStatus']; ?></td>
                                                <td><?php echo $value['reviewComment']; ?></td>
                                                <td><?php echo $value['receipt_no']; ?></td>
                                                <td>
                                                    <a style="background-color: #eee; text-decoration: underline;" href="../ExpenseHotel/<?php echo $value['receipt_image']; ?>" target="_blank" download><?php echo $value['receipt_image']; ?></a>
                                                </td>
                                                <td><?php echo $value['created_at']; ?></td>
                                            </tr>
                                        <?php

                                        } ?>

                                    </tbody>
                                </table>
                            <?php } else {
                                echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
                            } ?>
                        </div>
                    <?php } else if ($reqType == 'miscellaneous') { ?>
                        <h4>Miscellaneous Report</h4>
                        <div id="pnlTable">
                            <?php
                            // $sqlConnect = "SELECT * FROM expense_miscellaneous where Month(created_at)='" . $month . "' and YEAR(created_at)='" . $year . "'";

                            // $sqlConnect = "select em.*,t4.client_name,t3.process,t3.sub_process,t6.Designation,t7.location from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.ID join location_master t7 on t2.loc=t7.id join expense_miscellaneous em on em.EmployeeID=t2.EmpID  where cast(em.created_at as date) between '" . $date_From . "' and '" . $date_To . "'";
                            $sqlConnect = "select em.*,t4.client_name,t3.process,t3.sub_process,t6.Designation,t7.location from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.ID join location_master t7 on t2.loc=t7.id join expense_miscellaneous em on em.EmployeeID=t2.EmpID  where cast(em.created_at as date) between ? and ?";
                            $stmt = $conn->prepare($sqlConnect);
                            $stmt->bind_param("ss", $date_From, $date_To);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) { ?>
                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>EmployeeID</th>
                                            <th>Employee Name</th>
                                            <th>Client</th>
                                            <th>Process</th>
                                            <th>Subprocess</th>
                                            <th>Designation</th>
                                            <th>Location</th>
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                            <th>Date</th>
                                            <th>Request Type</th>
                                            <th>Manager Status</th>
                                            <th>Manager Comment</th>
                                            <th>Review Status</th>
                                            <th>Review Comment</th>
                                            <th>Created On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        foreach ($result as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value['EmployeeID']; ?></td>
                                                <td><?php echo $value['empName']; ?></td>
                                                <td><?php echo $value['client_name']; ?></td>
                                                <td><?php echo $value['process']; ?></td>
                                                <td><?php echo $value['sub_process']; ?></td>
                                                <td><?php echo $value['Designation']; ?></td>
                                                <td><?php echo $value['location']; ?></td>
                                                <td><?php echo $value['amount']; ?></td>
                                                <td><?php echo $value['remarks']; ?></td>
                                                <td><?php echo $value['date']; ?></td>
                                                <td><?php echo $value['reqType']; ?></td>
                                                <td><?php echo $value['mgrStatus']; ?></td>
                                                <td><?php echo $value['mgrComment']; ?></td>
                                                <td><?php echo $value['reviewerStatus']; ?></td>
                                                <td><?php echo $value['reviewComment']; ?></td>
                                                <td><?php echo $value['created_at']; ?></td>
                                            </tr>
                                        <?php

                                        } ?>

                                    </tbody>
                                </table>
                            <?php } else {
                                echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
                            } ?>
                        </div>
                    <?php } else if ($reqType == 'mobile') { ?>
                        <h4>Mobile Report</h4>
                        <div id="pnlTable">
                            <?php
                           
                            $sqlConnect = "select ef.*,t4.client_name,t3.process,t3.sub_process,t6.Designation,t7.location from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.ID join location_master t7 on t2.loc=t7.id join expense_mobile ef on ef.EmployeeID=t2.EmpID  where cast(ef.created_at as date) between ? and ?";
                            $stmt = $conn->prepare($sqlConnect);
                            $stmt->bind_param("ss", $date_From, $date_To);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) { ?>
                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>EmployeeID</th>
                                            <th>Employee Name</th>
                                            <th>Client</th>
                                            <th>Process</th>
                                            <th>Subprocess</th>
                                            <th>Designation</th>
                                            <th>Location</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                            <th>Request Type</th>
                                            <th>Manager Status</th>
                                            <th>Manager Comment</th>
                                            <th>Review Status</th>
                                            <th>Review Comment</th>
                                            <th>Receipt No</th>
                                            <th>Receipt Image</th>
                                            <th>Created On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        foreach ($result as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value['EmployeeID']; ?></td>
                                                <td><?php echo $value['empName']; ?></td>
                                                <td><?php echo $value['client_name']; ?></td>
                                                <td><?php echo $value['process']; ?></td>
                                                <td><?php echo $value['sub_process']; ?></td>
                                                <td><?php echo $value['Designation']; ?></td>
                                                <td><?php echo $value['location']; ?></td>
                                                <td><?php echo $value['date']; ?></td>
                                                <td><?php echo $value['amount']; ?></td>
                                                <td><?php echo $value['remarks']; ?></td>
                                                <td><?php echo $value['reqType']; ?></td>
                                                <td><?php echo $value['mgrStatus']; ?></td>
                                                <td><?php echo $value['mgrComment']; ?></td>
                                                <td><?php echo $value['reviewerStatus']; ?></td>
                                                <td><?php echo $value['reviewComment']; ?></td>
                                                <td><?php echo $value['receipt_no']; ?></td>
                                                <td>
                                                    <a style="background-color: #eee; text-decoration: underline;" href="../ExpenseMobile/<?php echo $value['receipt_image']; ?>" target="_blank" download><?php echo $value['receipt_image']; ?></a>
                                                </td>
                                                <td><?php echo $value['created_at']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else {
                                echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
                            } ?>
                        </div>

                    <?php }
                } ?>

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