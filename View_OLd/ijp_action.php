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
$connn = $myDB->dbConnect();
$sess = $_SESSION['__user_logid'] == 'CE10091236' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE0821939593' || $_SESSION['__user_logid'] == 'CE1021940504';
// Global variable used in Page Cycle
if ($sess) {
    // proceed further
} else {
    $location = URL . 'Error';
    header("Location: $location");
    exit();
}
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
    $post_int = cleanUserInput($_POST['ijp']);

    $remark = cleanUserInput($_POST['remark']);
    $emp_select_action = cleanUserInput($_POST['emp_select']);
    $ijpID = cleanUserInput($_POST['ijp_post']);
    $EMP = implode(cleanUserInput(',', $_POST['tcid']));
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">IJP Action</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>IJP Action </h4>
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        dom: 'Bfrtip',
                        "info": false,
                        "paging": false,
                        "iDisplayLength": 25,
                        scrollCollapse: true,
                        // lengthMenu: [
                        //     [5, 10, 25, 50, -1],
                        //     ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                        // ],
                        buttons: [

                            {
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
                            // ,
                            // 'pageLength'

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
                <?php //$post_int = cleanUserInput($_POST['ijp']); 
                ?>
                <input type="hidden" name="ijp_post" value="<?php echo $post_int; ?>" />
                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s12 m12">
                        <select id="ijp" name="ijp" value=<?php echo $post_int; ?>>
                            <option value="NA">----Select----</option>
                            <?php
                            $sqlBy = 'SELECT id,ijp_name from ijp_master where id in (SELECT distinct ijpID from ijp_emp where flag=1 and inter_flag=1 and (status IS NULL or status=""));';
                            $myDB = new MysqliDb();
                            $resultBy = $myDB->rawQuery($sqlBy);
                            $mysql_error = $myDB->getLastError();
                            if (empty($mysql_error)) {
                                foreach ($resultBy as $key => $value) {
                                    echo '<option value="' . $value['id'] . '"  >' . $value['ijp_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <label for="IJP" class="active-drop-down active">IJP</label>
                    </div>
                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="btn_search" id="btn_search">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>


                <div class="input-field col s5 m5" id="employ">
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
                </div>

                <?php
                $btn_search = isset($_POST['btn_search']);
                if (($btn_search)) {
                    $_SESSION['int_id'] = $post_int;
                    $sqlConnect = "SELECT t.EmployeeID,t1.EmployeeName,t8.Designation,t6.client_name,t5.process,t5.sub_process,t9.location from ijp_emp t left Join  personal_details t1 on t.EmployeeID=t1.EmployeeID left join employee_map t2 on t1.EmployeeID=t2.EmployeeID left join new_client_master t5 on t5.cm_id=t2.cm_id left join client_master t6 on t6.client_id=t5.client_name left join df_master t7 on t7.df_id=t2.df_id left join designation_master t8 on t8.ID=t7.des_id left join location_master t9 on t9.id=t1.location WHERE t2.emp_status='Active' and  t.flag='1' and t.inter_flag='1' and (t.status IS NULL or t.status='') and t.ijpID=? ";
                    $stmt = $connn->prepare($sqlConnect);
                    $stmt->bind_param('s', $post_int);
                    if (!$stmt) {
                        echo "failed";
                    }
                    $stmt->execute();
                    $resultSql = $stmt->get_result();
                    $count = $resultSql->num_rows;
                    // $result = $myDB->query($sqlConnect);
                    // $my_error = $myDB->getLastError();
                    if ($count > 0) {

                        $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                        $table .= '<th><input type="checkbox" name="cAll" id="cAll" value="ALL"><label for="cAll"></label></th>';
                        $table .= '<th class="hidden">EmployeeID</th>';
                        $table .= '<th>EmployeeID</th>';
                        $table .= '<th>Employee Name</th>';
                        $table .= '<th>Client</th>';
                        $table .= '<th>Process</th>';
                        $table .= '<th>Sub Process</th>';
                        $table .= '<th>Location</th>';
                        $table .= '<th>Designation</th><thead><tbody>';
                        $i = 1;
                        foreach ($resultSql as $key => $value) {
                            $table .=  '<tr><td class="EmpId"><input type="checkbox" id="cb' . $i . '" class="cb_child" name="tcid[' . $i . ']" value="' . $value['EmployeeID'] . '"><label for="cb' . $i . '" ></label></td>';
                            $table .= '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
                            $table .= '<td>' . $value['EmployeeID'] . '</td>';
                            $table .= '<td>' . $value['EmployeeName'] . '</td>';
                            $table .= '<td>' . $value['client_name'] . '</td>';
                            $table .= '<td>' . $value['process'] . '</td>';
                            $table .= '<td>' . $value['sub_process'] . '</td>';
                            $table .= '<td>' . $value['location'] . '</td>';
                            $table .= '<td>' . $value['Designation'] . '</td></tr>';
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
                }
                ?>
                <?php
                $btn = isset($_POST['btn']);
                if (($btn)) {
                    // $remark = cleanUserInput($_POST['remark']);
                    // $emp_select_action = cleanUserInput($_POST['emp_select']);
                    // $ijpID = cleanUserInput($_POST['ijp_post']);
                    // $EMP = implode(cleanUserInput(',', $_POST['tcid']));
                    $emp_id = explode(',', $EMP);
                    $_SESSION['empselect'] = $emp_id;
                    foreach ($emp_id as $key => $value) {
                        $emp = $value;
                        $emp_action = "UPDATE ijp_emp set remarks=?,status=? where EmployeeID=? and ijpID=? ";
                        $stmt1 = $connn->prepare($emp_action);

                        $stmt1->bind_param("sssi", $remark, $emp_select_action, $emp, $ijpID);

                        if (!$stmt1) {
                            echo "failed to run";
                            die;
                        }

                        $updtQry = $stmt1->execute();
                        // $myDB = new MysqliDb();
                        // $result = $myDB->query($emp_action);
                    }
                    // if ($stmt1->affected_rows === 1) {
                    echo "<script>$(function(){ toastr.success('Record Successfully'); }); </script>";
                    // } else {
                    //     echo "<script>$(function(){ toastr.success('Not Recorded'); }); </script>";
                    // }
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
                $('#emp_select').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanemp_select').length == 0) {
                    $('<span id="spanemp_select" class="help-block">Required *</span>').insertAfter('#emp_select');
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