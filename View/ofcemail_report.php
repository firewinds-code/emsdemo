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

$userType = clean($_SESSION['__user_type']);
$EmployeeID = clean($_SESSION['__user_logid']);
if ($userType == 'ADMINISTRATOR' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE12102224' || $EmployeeID == 'CE021929775') {
    // proceed further
} else {
    $location = URL . 'Error';
    header("Location: $location");
    exit();
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Official Email-ID Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Official Email-ID Report</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <script>
                    $(function() {
                        $('#txt_dateFrom').datetimepicker({
                            timepicker: false,
                            format: 'Y-m-d',
                            maxDate: 0,
                            scrollInput: false
                        });
                        $('#txt_dateFrom').on('change', function() {
                            var today = new Date($('#txt_dateFrom').val());
                            yourMaxDate = new Date(today.getFullYear(), today.getMonth() + 3, 0);
                            // alert(yourMaxDate);
                            $('#txt_dateTo').datetimepicker({
                                timepicker: false,
                                format: 'Y-m-d',
                                maxDate: yourMaxDate,
                                minDate: today,
                                scrollInput: false
                            });
                            var date = new Date();

                            function toJSONLocal(date) {
                                var local = new Date(date);
                                local.setMinutes(date.getMinutes() - date.getTimezoneOffset());
                                return local.toJSON().slice(0, 10);
                            }
                            (toJSONLocal(yourMaxDate));
                        });
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
                <?php
                $date_From = cleanUserInput($_POST['txt_dateFrom']);
                $date_To = cleanUserInput($_POST['txt_dateTo']);
                $status = cleanUserInput($_POST['txt_status']);
                ?>

                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s4 m4">
                        <input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
                        <label for="txt_dateFrom">Date From</label>

                    </div>
                    <div class="input-field col s4 m4">
                        <input type="text" class="form-control" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
                        <label for="txt_dateTo">Date To</label>

                    </div>

                    <div class="input-field col s4 m4">
                        <select id="txt_status" name="txt_status">
                            <option value="Active" <?php if ($status == 'Active') echo 'selected'; ?>>Active</option>
                            <option value="InActive" <?php if ($status == 'InActive') echo 'selected'; ?>>InActive</option>
                        </select>
                        <label for="txt_status" class="active-drop-down active">Status *</label>

                    </div>

                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="view" id="view">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
                <?php
                if (isset($_POST['view'])) {

                    $sqlConnect = "select t4.location,t1.EmployeeID,t2.EmployeeName,t1.ofc_emailid,t3.emp_status,t6.Designation from contact_details t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID join employee_map t3 on t3.EmployeeID=t1.EmployeeID join location_master t4 on t2.location=t4.id left join df_master t5 on t3.df_id=t5.df_id left join designation_master t6 on t5.des_id=t6.ID where t3.df_id not in(74,77) and t3.emp_status=? and  cast(t3.dateofjoin as date) between ? and ?";
                    $sql = $conn->prepare($sqlConnect);
                    $sql->bind_param("sss", $status, $date_From, $date_To);
                    $sql->execute();
                    $result = $sql->get_result();
                    if ($result->num_rows > 0) {
                        $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                        $table .= '<th>Location</th>';
                        $table .= '<th>Employee ID</th>';
                        $table .= '<th>Employee Name</th>';
                        $table .= '<th>Designation</th>';
                        $table .= '<th>Ofc EmailID</th>';
                        $table .= '<th>Status</th><thead><tbody>';

                        foreach ($result as $key => $value) {
                            $table .= '<tr><td>' . $value['location'] . '</td>';
                            $table .= '<td>' . $value['EmployeeID'] . '</td>';
                            $table .= '<td>' . $value['EmployeeName'] . '</td>';
                            $table .= '<td>' . $value['Designation'] . '</td>';
                            $table .= '<td>' . $value['ofc_emailid'] . '</td>';
                            $table .= '<td>' . $value['emp_status'] . '</td></tr>';
                        }
                        $table .= '</tbody></table></div></div>';
                        echo $table;
                    } else {
                        echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
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
            if ($('#txt_dateFrom').val() == '') {
                $('#txt_dateFrom').addClass('has-error');
                if ($('#spantxt_dateFrom').size() == 0) {
                    $('<span id="spantxt_dateFrom" class="help-block"></span>').insertAfter('#txt_dateFrom');
                }
                $('#spantxt_dateFrom').html('Required');
                validate = 1;
            }
            if ($('#txt_dateTo').val() == '') {
                $('#txt_dateTo').addClass('has-error');
                if ($('#spantxt_dateTo').size() == 0) {
                    $('<span id="spantxt_dateTo" class="help-block"></span>').insertAfter('#txt_dateTo');
                }
                $('#spantxt_dateTo').html('Required');
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