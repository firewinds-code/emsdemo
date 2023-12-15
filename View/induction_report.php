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

$user_logid = clean($_SESSION['__user_logid']);
if ($user_logid == 'CE10091236' || $user_logid == 'CE12102224' || $user_logid == 'CE0122942656' || $user_logid == 'CE03070003' || $user_logid == 'CE021929762' || $user_logid == 'CE1121941390' || $user_logid == 'CE1021940627' || $user_logid == 'CEB022110041') {
    // proceed further
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
    $txt_dateFrom = cleanUserInput($_POST['txt_dateFrom']);
    $txt_dateTo = cleanUserInput($_POST['txt_dateTo']);
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Induction Master</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Induction Report</h4>
            <script>
                $(document).ready(function() {

                    $('#txt_dateFrom').datepicker({

                        dateFormat: 'yy-mm-dd',
                        maxDate: '0',
                        scrollInput: false
                    });

                    $("#txt_dateTo").datepicker({

                        dateFormat: 'yy-mm-dd',
                        maxDate: '0',
                        scrollInput: false,

                        onSelect: function(dateStr) {

                            var max = $(this).datepicker('getDate'); // Get selected date
                            var start = $("#txt_dateFrom").datepicker("getDate");

                            var end = $("#txt_dateTo").datepicker("getDate");

                            if (start != null) {
                                var days = (end - start) / (1000 * 60 * 60 * 24);

                                if (days < 0) {

                                    alert("To Date should be greater then From Date");
                                    $("#txt_dateTo").val('');
                                    return false;
                                }
                            } else {
                                alert("Select From Date First...");
                                $("#txt_dateTo").val('');
                            }

                        }
                    });



                    $('#btn_search').on('click', function() {
                        var validate = 0;
                        var alert_msg = '';
                        if ($('#txt_dateFrom').val() == '') {
                            $('#txt_dateFrom').addClass('has-error');
                            if ($('#spantxt_dateFrom').length == 0) {
                                $('<span id="spantxt_dateFrom" class="help-block"></span>').insertAfter(
                                    '#txt_dateFrom');
                            }
                            $('#spantxt_dateFrom').html('Required');
                            validate = 1;
                        }
                        if ($('#txt_dateTo').val() == '') {
                            $('#txt_dateTo').addClass('has-error');
                            if ($('#spantxt_dateTo').length == 0) {
                                $('<span id="spantxt_dateTo" class="help-block"></span>').insertAfter(
                                    '#txt_dateTo');
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

                    // $('#myTable').DataTable({
                    //     dom: 'Bfrtip',
                    //     "info": false,
                    //     "paging": false,
                    //     "iDisplayLength": 25,
                    //     scrollCollapse: true,
                    //     scrollX: true,
                    //     // lengthMenu: [
                    //     //     [5, 10, 25, 50, -1],
                    //     //     ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                    //     // ],
                    //     buttons: [

                    //         {
                    //             extend: 'excel',
                    //             text: 'EXCEL',
                    //             extension: '.xlsx',
                    //             exportOptions: {
                    //                 modifier: {
                    //                     page: 'all'
                    //                 }
                    //             },
                    //             title: 'table'
                    //         }
                    //         /*,'copy'*/
                    //         // ,
                    //         // 'pageLength'

                    //     ]
                    //     // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
                    // });

                    // $('.buttons-copy').attr('id', 'buttons_copy');
                    // $('.buttons-csv').attr('id', 'buttons_csv');
                    // $('.buttons-excel').attr('id', 'buttons_excel');
                    // $('.buttons-pdf').attr('id', 'buttons_pdf');
                    // $('.buttons-print').attr('id', 'buttons_print');
                    // $('.buttons-page-length').attr('id', 'buttons_page_length');
                    $('#myTable').DataTable({
                        dom: 'Bfrtip',
                        "iDisplayLength": 25,
                        scrollX: '100%',
                        scrollCollapse: true,
                        lengthMenu: [
                            [5, 10, 25, 50, -1],
                            ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                        ],
                        buttons: [{
                                extend: 'excel',
                                text: 'EXCEL',
                                extension: '.xlsx',
                                // exportOptions: {
                                //     modifier: {
                                //         page: 'all'
                                //     }
                                // },14,17,20
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 16, 17, 19, 20, 22]
                                },
                                title: 'table'
                            }, 'pageLength'

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
            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php
                $_SESSION["token"] = csrfToken();
                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <?php $post_int = cleanUserInput($_POST['ijp']); ?>
                <input type="hidden" name="ijp_post" value="<?php echo $post_int; ?>" />
                <div class="input-field col s12 m12" id="rpt_container">
                    <?php //$date_From = cleanUserInput($_POST['txt_dateFrom']);
                    //$txt_dateTo = cleanUserInput($_POST['txt_dateTo']); 
                    ?>
                    <div class="input-field col s6 m6">
                        <span>Date From</span>
                        <input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $txt_dateFrom; ?>" />
                    </div>
                    <div class="input-field col s6 m6">
                        <span>Date To</span>
                        <input type="text" class="form-control" name="txt_dateTo" id="txt_dateTo" value="<?php echo $txt_dateTo; ?>" />
                    </div>

                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="btn_search" id="btn_search">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>


                <!-- <div class="input-field col s5 m5" id="employ">
                    <select id="emp_select" name="emp_select">
                        <option value='NA'>---Select---</option>
                        <option value='Selected'>Selected</option>
                        <option value='Rejected'>Rejected</option>
                    </select>
                    <label for="emp_select" class="active-drop-down active">Action</label>
                </div>
                <br>
                <div class="input-field col s12 m12" id="remarks">
                    <label>Remarks</label>
                    <input type="text" class="form-control" name="remark" id="remark" />
                </div> -->

                <?php
                if (isset($_POST['btn_search'])) {
                    //$_SESSION['int_id'] = $post_int;

                    $sqlConnect = "select t1.EmpID,t2.EmpName,case when EmpFlag=0 then 'Induction Pending' when EmpFlag=1 then 'Induction Complete' when EmpFlag=2 then 'Acknowledge' end as EmpFlag,t5.client_name,t4.process,t4.sub_process,t8.location,t7.Designation,t1.rating,t1.correct,t1.incorrect,t1.rating_ques1,t1.rating_ans1,t1.rating_ques2,t1.rating_ans2,t1.rating_ques3,t1.rating_ans3,t3.dateofjoin,emp_status,Emp_Comment from induction_master t1 join EmpID_Name t2 on t1.EmpID=t2.EmpID join employee_map t3 on t1.EmpID=t3.EmployeeID left join new_client_master t4 on t4.cm_id=t3.cm_id left join client_master t5 on t5.client_id=t4.client_name left join df_master t6 on t6.df_id=t3.df_id left join designation_master t7 on t7.ID=t6.des_id left join location_master t8 on t8.id=t2.loc where (t3.dateofjoin between ? and ?)";
                    $selectQury = $conn->prepare($sqlConnect);
                    $selectQury->bind_param("ss", $txt_dateFrom, $txt_dateTo);
                    $selectQury->execute();
                    $result = $selectQury->get_result();
                    // $result = $myDB->query($sqlConnect);
                    // echo ($sqlConnect);
                    // die;
                    // $my_error = $myDB->getLastError();
                    if ($result->num_rows > 0) {
                        if (!empty($result)) {

                            $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';


                            $table .= '<th>EmployeeID</th>';
                            $table .= '<th>Employee Name</th>';
                            $table .= '<th>Status</th>';
                            $table .= '<th>Client</th>';
                            $table .= '<th>Process</th>';
                            $table .= '<th>Sub Process</th>';
                            $table .= '<th>Location</th>';
                            $table .= '<th>Designation</th>';
                            $table .= '<th>DOJ</th>';
                            $table .= '<th>Status</th>';
                            $table .= '<th>Comment</th>';
                            $table .= '<th>Rating</th>';
                            $table .= '<th>Correct Ans</th>';
                            $table .= '<th>Incorrect Ans</th>';
                            $table .= '<th>Rating Ques1</th>';
                            $table .= '<th>Rating Ans1</th>'; //14
                            $table .= '<th style="display:none;">Rating Ans1</th>';
                            $table .= '<th>Rating Ques2</th>';
                            $table .= '<th>Rating Ans2</th>'; //17
                            $table .= '<th style="display:none;">Rating Ans2</th>';
                            $table .= '<th>Rating Ques3</th>';
                            $table .= '<th>Rating Ans3</th>'; //20
                            $table .= '<th style="display:none;">Rating Ans3</th><thead><tbody>';
                            $i = 1;
                            foreach ($result as $key => $value) {
                                $table .=  '<tr>';

                                $table .= '<td>' . $value['EmpID'] . '</td>';
                                $table .= '<td>' . $value['EmpName'] . '</td>';
                                $table .= '<td>' . $value['EmpFlag'] . '</td>';
                                $table .= '<td>' . $value['client_name'] . '</td>';
                                $table .= '<td>' . $value['process'] . '</td>';
                                $table .= '<td>' . $value['sub_process'] . '</td>';
                                $table .= '<td>' . $value['location'] . '</td>';
                                $table .= '<td>' . $value['Designation'] . '</td>';
                                $table .= '<td>' . $value['dateofjoin'] . '</td>';
                                $table .= '<td>' . $value['emp_status'] . '</td>';
                                $table .= '<td>' . $value['Emp_Comment'] . '</td>';
                                $table .= '<td>' . $value['rating'] . '</td>';
                                $table .= '<td>' . $value['correct'] . '</td>';
                                $table .= '<td>' . $value['incorrect'] . '</td>';
                                $table .= '<td>' . $value['rating_ques1'] . '</td>';

                                $table .= '<td>';
                                if (is_numeric($value['rating_ans1'])) {
                                    if ($value['rating_ans1'] < 10) {
                                        $table .= '<i class="fa fa-thumbs-down fa-lg" style="font-size: 1.3rem; color:red;"></i>';
                                    } else {
                                        $table .= '<i class="fa fa-thumbs-up fa-lg" style="font-size: 1.3rem; color:green"></i>';
                                    }
                                } else {
                                    $table .= 'NA';
                                }
                                $table .= '</td>';
                                $table .= '<td style="display:none";>';
                                if (is_numeric($value['rating_ans1'])) {
                                    if ($value['rating_ans1'] < 10) {
                                        $table .= '1';
                                    } else {
                                        $table .= '10';
                                    }
                                } else {
                                    $table .= 'NA';
                                }
                                $table .= '</td>';

                                $table .= '<td>' . $value['rating_ques2'] . '</td>';
                                $table .= '<td>';
                                if (is_numeric($value['rating_ans2'])) {
                                    if ($value['rating_ans2'] < 10) {

                                        $table .= '<i class="fa fa-thumbs-down fa-lg" style="font-size: 1.3rem; color:red;"></i>';
                                    } else {
                                        $table .= '<i class="fa fa-thumbs-up fa-lg" style="font-size: 1.3rem; color:green"></i>';
                                    }
                                } else {
                                    $table .= 'NA';
                                }
                                $table .= '</td>';
                                $table .= '<td style="display:none";>';
                                if (is_numeric($value['rating_ans2'])) {
                                    if ($value['rating_ans2'] < 10) {
                                        $table .= '1';
                                    } else {
                                        $table .= '10';
                                    }
                                } else {
                                    $table .= 'NA';
                                }
                                $table .= '</td>';

                                $table .= '<td>' . $value['rating_ques3'] . '</td>';

                                $table .= '<td>';
                                if (is_numeric($value['rating_ans3'])) {
                                    if ($value['rating_ans3'] < 10) {

                                        $table .= '<i class="fa fa-thumbs-down fa-lg" style="font-size: 1.3rem; color:red;"></i>';
                                    } else {
                                        $table .= '<i class="fa fa-thumbs-up fa-lg" style="font-size: 1.3rem; color:green"></i>';
                                    }
                                } else {
                                    $table .= 'NA';
                                }
                                $table .= '</td>';
                                $table .= '<td style="display:none";>';
                                if (is_numeric($value['rating_ans3'])) {
                                    if ($value['rating_ans3'] < 10) {
                                        $table .= '1';
                                    } else {
                                        $table .= '10';
                                    }
                                } else {
                                    $table .= 'NA';
                                }
                                $table .= '</td></tr>';
                                $i++;
                            }
                            $table .= '</tbody></table></div></div>';
                            echo $table;
                        } else {
                            echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
                        }
                ?>
                        <br>
                        <div class="input-field col s12 m12 right-align">
                            <button type="submit" class="btn waves-effect waves-green" name="btn" id="btn">Submit</button>
                        </div>

                <?php
                    } else {
                        echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
                    }
                }
                ?>
                <?php
                if (isset($_POST['btn'])) {

                    $EMP = implode(',', $_POST['tcid']);
                    $emp_id = explode(',', $EMP);
                    // print_r($emp_id);
                    // die;
                    //$_SESSION['empselect'] = $emp_id;
                    foreach ($emp_id as $key => $value) {
                        $emp = $value;

                        $hrID = clean($_SESSION['__user_logid']);
                        $emp_action = "Update induction_master set EmpFlag=1, HRID=?, HR_modified=now() where EmpID=?";
                        $upQ = $conn->prepare($emp_action);
                        $upQ->bind_param("ss", $hrID, $emp);
                        $upQ->execute();
                        $result = $upQ->get_result();
                        print_r($result);
                        // $myDB = new MysqliDb();
                        // $result = $myDB->query($emp_action);
                    }
                    echo "<script>$(function(){ toastr.success('Record Successfully'); }); </script>";
                }

                ?>

            </div>
        </div>

    </div>
    <!--Form container End -->
</div>
<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>

<script>
    // if ($('label').hasClass('Active')) {
    $('ack_check1').removeClass('Active');
    //}
</script>
<script>
    $('#remarks').hide();
    $('#employ').hide();
    $("input:checkbox").click(function() {
        if ($('input:checkbox:checked').length > 0) {
            $('#remarks').show();
            $('#employ').show();
        } else {
            $('#remarks').hide();
            $('#employ').hide();
        }

    });
    $(function() {
        $('#alert_msg_close').click(function() {
            $('#alert_message').hide();
        });
        if ($('#alert_msg').text() == '') {
            $('#alert_message').hide();
        } else {
            $('#alert_message').delay(10000).fadeOut("slow");
        }

        $("#cAll").change(function() {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
        $("input:checkbox").change(function() {
            if ($('input.cb_child:checkbox:checked').length > 0) {
                checklistdata();
                if ($('input.cb_child:checkbox:checked').length == $('input.cb_child:checkbox').length) {
                    $("#cAll").prop("checked", true);
                } else {
                    $("#cAll").prop("checked", false);
                }
            } else {
                $("#cAll").prop("checked", false);
            }
        });
    });
    $(document).ready(function() {
        $('#btn_search').click(function() {
            var validate = 0;
            var alert_msg = '';

            if ($('#ijp').val() == 'NA') {
                $('#ijp').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanijp').length == 0) {
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
        $('#btn').click(function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#emp_select').val() == 'NA') {
                $('#emp_select').parent('.select-wrapper').find('input.select-dropdown').addClass(
                    "has-error");
                if ($('#spanemp_select').length == 0) {
                    $('<span id="spanemp_select" class="help-block">Required *</span>').insertAfter(
                        '#emp_select');
                }
                validate = 1;
            }
            // if ($('#emp_select').val() == 'Rejected') {
            if ($('#remark').val() == '') {
                $('#remark').addClass("has-error");
                if ($('#spanremark').length == 0) {
                    $('<span id="spanremark" class="help-block">Required *</span>').insertAfter('#remark');
                }
                validate = 1;
            }
            // }
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