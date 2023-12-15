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

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">IJP Ack Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> IJP Ack Report</h4>

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
                            "sScrollY": "100%",
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
                if (isset($_POST['btn_view'])) {

                    $date_From = $_POST['txt_dateFrom'];
                    $date_To = $_POST['txt_dateTo'];

                    //$sqlConnect = "select EmployeeID,month, reason, per_email_id from salary_certificate_report where created_at between '" . $date_From . "' and '" . $date_To . "'";
                    $sqlConnect = "select * from ijp_ack where cast(createdon as date) between '" . $date_From . "' and '" . $date_To . "'";
                    $myDB = new MysqliDb();
                    $result = $myDB->query($sqlConnect);
                    // echo ($sqlConnect);
                    // die;
                    $my_error = $myDB->getLastError();
                    if (empty($my_error)) {
                        $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                        $table .= '<th>EmployeeID</th>';
                        $table .= '<th>Employee Name</th>';
                        $table .= '<th>Status</th>';
                        $table .= '<th>Remarks</th>';
                        $table .= '<th>Created On</th>';
                        $table .= '<th>Ack. Date</th>';
                        $table .= '<th>Manager</th>';
                        $table .= '<th>Team Leader</th>';
                        $table .= '<th>Quality Analyst</th>';
                        $table .= '<th>Process Trainer</th>';
                        $table .= '<th>Business Compliance</th>';
                        $table .= '<th>MIS/WFM</th>';
                        $table .= '<th>HR</th>';
                        $table .= '<th>IT</th><thead><tbody>';

                        foreach ($result as $key => $value) {                           								
								 $mngr=($value['Manager']==1)?"Yes":"No";
								 $tl=($value['TeamLeader']==1)?"Yes":"No";
                            $Qa=($value['QualityAnalyst']==1)?"Yes":"No";
                            $Pt=($value['ProcessTrainer']==1)?"Yes":"No";
                            $Bc=($value['BusinessCompliance']==1)?"Yes":"No";
                            $Mis=($value['MIS_WFM']==1)?"Yes":"No";
                            $Hr=($value['HR']==1)?"Yes":"No";
                            $It=($value['IT']==1)?"Yes":"No";
                            $Ack=($value['emp_ack']==1)?"Yes":"No";

                            $date = date_create($value['ack_date']);
                            $Date = date_format($date, "d-M-y H:i:s");
                           // $Time = date_format($date, "H:i:s");
							
							$dateon = date_create($value['createdon']);
							$createdon=date_format($dateon,"d-M-y H:i:s");
							
                            $table .= '<tr><td>' . $value['empid'] . '</td>';
                            $table .= '<td>' . $value['name'] . '</td>';
                            $table .= '<td>' . $Ack . '</td>';
                            $table .= '<td>' . $value['remarks'] . '</td>';
                            $table .= '<td>' . $createdon . '</td>';
                            $table .= '<td>' . $Date . '</td>';
                            $table .= '<td>' . $mngr . '</td>';
                            $table .= '<td>' . $tl . '</td>';
                            $table .= '<td>' . $Qa . '</td>';
                            $table .= '<td>' . $Pt . '</td>';
                            $table .= '<td>' . $Bc . '</td>';
                            $table .= '<td>' . $Mis . '</td>';
                            $table .= '<td>' . $Hr . '</td>';
                            $table .= '<td>' . $It . '</td></tr>';
                        }
                        $table .= '</tbody></table></div></div>';
                        echo $table;
                    } else {
                        echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
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

        $('#btn_view').on('click', function() {
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