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
$connn = $myDB->dbConnect();
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
    $post_int = cleanUserInput($_POST['ijp']);
    $scheduledate = cleanUserInput($_POST['schedule_intro']);
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Employee Acknowledge</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Employee Acknowledge </h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php
                $_SESSION["token"] = csrfToken();
                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <script>
                    $(function() {
                        $('#schedule_intro').datetimepicker({
                            // timepicker: false,
                            format: 'Y-m-d H:i:00',
                            minDate: '0',
                            scrollInput: false,
                        });

                    });
                </script>
                <?php //$post_int = cleanUserInput($_POST['ijp']); 
                ?>
                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s12 m12">
                        <select id="ijp" name="ijp" value=<?php echo $post_int; ?>>
                            <option value="NA">----Select----</option>
                            <?php
                            $sqlBy = 'select id,ijp_name from ijp_master where id in (select distinct ijpID from ijp_emp where flag=1 and inter_flag =0);';
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


                <?php
                $btn_search = isset($_POST['btn_search']);
                if ($btn_search) {

                    $_SESSION['int_id'] = $post_int;

                    $sqlConnect = "select t.EmployeeID,t1.EmployeeName,t8.Designation,t6.client_name,t5.process,t5.sub_process,t9.location from ijp_emp t left Join  personal_details t1 on t.EmployeeID=t1.EmployeeID left join employee_map t2 on t1.EmployeeID=t2.EmployeeID left join new_client_master t5 on t5.cm_id=t2.cm_id left join client_master t6 on t6.client_id=t5.client_name left join df_master t7 on t7.df_id=t2.df_id left join designation_master t8 on t8.ID=t7.des_id left join location_master t9 on t9.id=t1.location WHERE t2.emp_status='Active' and t.ijpID=? and  (t.flag='1' and t.inter_flag=0) ";
                    $stmt = $connn->prepare($sqlConnect);
                    $stmt->bind_param("i", $post_int);
                    if (!$stmt) {
                        echo "Failed";
                        die;
                    }
                    $stmt->execute();
                    $Qryresult = $stmt->get_result();
                    $count = $Qryresult->num_rows;
                    // $myDB = new MysqliDb();
                    // $result = $myDB->query($sqlConnect);
                    // echo ($sqlConnect);
                    // die;
                    if ($count > 0) {

                        $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                        $table .= '<th>EmployeeID</th>';
                        $table .= '<th>Employee Name</th>';
                        $table .= '<th>Client</th>';
                        $table .= '<th>Process</th>';
                        $table .= '<th>Sub Process</th>';
                        $table .= '<th>Location</th>';
                        $table .= '<th>Designation</th><thead><tbody>';

                        foreach ($Qryresult as $key => $value) {
                            $table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
                            $table .= '<td>' . $value['EmployeeName'] . '</td>';
                            $table .= '<td>' . $value['client_name'] . '</td>';
                            $table .= '<td>' . $value['process'] . '</td>';
                            $table .= '<td>' . $value['sub_process'] . '</td>';
                            $table .= '<td>' . $value['location'] . '</td>';
                            $table .= '<td>' . $value['Designation'] . '</td></tr>';
                        }
                        $table .= '</tbody></table></div></div>';
                        echo $table;
                    } else {
                        echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
                    }

                ?>
                    <br>
                    <div class="input-field col s4 m4">
                        <label>Schedule Introview</label>
                        <input type="text" class="form-control" name="schedule_intro" id="schedule_intro" value="<?php echo $scheduledate; ?>" />
                    </div>
                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="btn" id="btn">Schedule</button>
                    </div>

                <?php }

                $btn = isset($_POST['btn']);
                if ($btn) {
                    // $scheduledate = cleanUserInput($_POST['schedule_intro']);
                    $inID = clean($_SESSION['int_id']);
                    $schedule = "Update ijp_master set schedule_intro=? where id=?";
                    $stmt1 = $connn->prepare($schedule);
                    $stmt1->bind_param("si", $scheduledate, $inID);
                    if (!$stmt1) {
                        echo "failed to run";
                        die;
                    }
                    $updt = $stmt1->execute();
                    if ($stmt1->affected_rows === 1) {
                        // $myDB = new MysqliDb();
                        // $result = $myDB->query($schedule);
                        echo "<script>$(function(){ toastr.success('Scheduled Successfully'); }); </script>";
                    }
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
            if ($('#schedule_intro').val() == '') {
                $('#schedule_intro').addClass('has-error');
                if ($('#spanschedule_intro').length == 0) {
                    $('<span id="spanschedule_intro" class="help-block"></span>').insertAfter('#schedule_intro');
                }
                $('#spanschedule_intro').html('Required');
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