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
$connn = $myDB->dbConnect();

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
    $ijVal = cleanUserInput($_POST['ijp']);
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">IJP Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> IJP Report</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php

                $_SESSION["token"] = csrfToken();
                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <script>
                    $(function() {
                        $('#myTable').DataTable({
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [10, 25, 50, -1],
                                ['10 rows', '25 rows', '50 rows', 'Show all']
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
                            "sScrollY": '200px',
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

                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s12 m12">
                        <select name="ijp" id="ijp">
                            <option value="NA">Select IJP</option>
                            <?php
                            $sqlBy = 'select id,ijp_name from ijp_master where id in (select distinct ijpID from ijp_emp where flag=1 and inter_flag !=0)';
                            $resultBy = $myDB->rawQuery($sqlBy);
                            $mysql_error = $myDB->getLastError();
                            if (empty($mysql_error)) {
                                foreach ($resultBy as $key => $value) {
                                    echo '<option value="' . $value['id'] . '"  >' . $value['ijp_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="view" id="view">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
                <?php
                $view = isset($_POST['view']);
                if (($view)) {
                    //  $sqlConnect = "select t.EmployeeID,t1.EmployeeName, case when flag=1 then 'Intrested' when flag=2 then 'not' end as flag, case when inter_flag=1 then 'Intrested' when inter_flag=2 then 'not interview' end as int_flag from ijp_emp t left Join  personal_details t1 on t.EmployeeID=t1.EmployeeID where ijpID='" . $_POST['ijp'] . "' and flag!=0";

                    // $ijVal = cleanUserInput($_POST['ijp']);

                    // $connn = new mysqli('192.168.24.118', 'root', 'India@123', 'ems');
                    if ($connn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $query = "select t.EmployeeID,t.emp_ack_remark,t.status,t.remarks,t1.EmployeeName,t8.Designation,t6.client_name,t5.process,t5.sub_process,t9.location, case when t.flag=1 then 'Interested' when t.flag=2 then 'Not Interested' end as flag, case when t.inter_flag=1 then 'Interested' when t.inter_flag=2 then 'Not Interested' end as int_flag from ijp_emp t left Join  personal_details t1 on t.EmployeeID=t1.EmployeeID left join employee_map t2 on t1.EmployeeID=t2.EmployeeID left join new_client_master t5 on t5.cm_id=t2.cm_id left join client_master t6 on t6.client_id=t5.client_name left join df_master t7 on t7.df_id=t2.df_id left join designation_master t8 on t8.ID=t7.des_id left join location_master t9 on t9.id=t1.location WHERE t2.emp_status='Active' and  t.flag!=0 and t.ijpID=?";

                    $stmt = $connn->prepare($query);
                    $stmt->bind_param("i", $ijVal);
                    if (!$stmt) {
                        echo "failed to run";
                        die;
                    }
                    $stmt->execute();
                    $result1234 = $stmt->get_result();
                    $count = $result1234->num_rows;

                    if ($result1234->num_rows > 0) {
                        $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                        $table .= '<th>EmployeeID</th>';
                        $table .= '<th>Employee Name</th>';
                        $table .= '<th>Client</th>';
                        $table .= '<th>Process</th>';
                        $table .= '<th>Sub Process</th>';
                        $table .= '<th>Location</th>';
                        $table .= '<th>Designation</th>';
                        $table .= '<th>Acknowledge</th>';
                        $table .= '<th>Interview Acknowledge</th>';
                        $table .= '<th>Employee Interview Remarks</th>';

                        $table .= '<th>Status</th>';
                        $table .= '<th>HR Remark</th><thead><tbody>';
                        // while ($value = $result1234->fetch_assoc()) {

                        foreach ($result1234 as $key => $value) {
                            $table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
                            $table .= '<td>' . $value['EmployeeName'] . '</td>';
                            $table .= '<td>' . $value['client_name'] . '</td>';
                            $table .= '<td>' . $value['process'] . '</td>';
                            $table .= '<td>' . $value['sub_process'] . '</td>';
                            $table .= '<td>' . $value['location'] . '</td>';
                            $table .= '<td>' . $value['Designation'] . '</td>';
                            $table .= '<td>' . $value['flag'] . '</td>';
                            $table .= '<td>' . $value['int_flag'] . '</td>';
                            $table .= '<td>' . $value['emp_ack_remark'] . '</td>';

                            $table .= '<td>' . $value['status'] . '</td>';
                            $table .= '<td>' . $value['remarks'] . '</td></tr>';
                        }
                        $table .= '</tbody></table></div></div>';
                        echo $table;
                    } else {
                        echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
                    }
                }

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

    $(document).ready(function() {
        $('#view').on('click', function() {
            var validate = 0;
            var alert_msg = '';

            if ($('#ijp').val() == 'NA') {
                $('#ijp').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanijp').size() == 0) {
                    $('<span id="spanijp" class="help-block">Required *</span>').insertAfter('#ijp');
                }
                validate = 1;
            }

            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(5000).fadeOut("slow");
                return false;
            }
        });
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>