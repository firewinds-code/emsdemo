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
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">NDA Policy Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> NDA Policy Report</h4>

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
                ?>

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
                        <button type="submit" class="btn waves-effect waves-green" name="view" id="view">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
                <?php
                if (isset($_POST['view'])) {
                    $sqlConnect = "select t1.EmployeeID,t1.EmpName,t4.client_name,t3.process,t3.sub_process,t1.createdon from nda_policies_decl t1 left join employee_map t2 on t1.EmployeeID=t2.EmployeeID
                    left join new_client_master t3 on t2.cm_id=t3.cm_id left join client_master t4 on t3.client_name=t4.client_id where cast(t1.createdon as date) between ? and ? ";
                    $sql = $conn->prepare($sqlConnect);
                    $sql->bind_param("ss", $date_From, $date_To);
                    $sql->execute();
                    $result = $sql->get_result();

                    if ($result->num_rows > 0) {
                        $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                        $table .= '<th>EmployeeID</th>';
                        $table .= '<th>Employee Name</th>';
                        $table .= '<th>Client</th>';
                        $table .= '<th>Process</th>';
                        $table .= '<th>Sub Process</th>';
                        $table .= '<th>Created On</th><thead><tbody>';

                        foreach ($result as $key => $value) {
                            //$date = date_create($value['createdon']);
                            //$Createdon = date_format($date, "Y-m-d");
                            $table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
                            $table .= '<td>' . $value['EmpName'] . '</td>';
                            $table .= '<td>' . $value['client_name'] . '</td>';
                            $table .= '<td>' . $value['process'] . '</td>';
                            $table .= '<td>' . $value['sub_process'] . '</td>';
                            $table .= '<td>' . $value['createdon'] . '</td></tr>';
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