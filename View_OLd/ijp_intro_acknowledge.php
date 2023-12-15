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


// Global variable used in Page Cycle

// if ($_SESSION['ijpackEmp'] == "Yes") {
// } else {
//     $location = URL . 'error';
//     echo '<script language="javascript">window.location.href ="' . $location . '"</script>';
//     exit();
// }
if ($_POST['ack_check'] == 1) {
    $flag = 1;
} else {
    $flag = 2;
}
$btn_ack = isset($_POST['btn_ack']);
if (($btn_ack)) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $ack_chk = isset($_POST['ack_check']);
        if (($ack_chk)) {
            $remark = cleanUserInput($_POST['remark']);
            $empID = clean($_SESSION['__user_logid']);
            $ijpID = cleanUserInput($_POST['ijp']);

            $upQry = "update ijp_emp set inter_flag=?, emp_ack_remark=? where EmployeeID=? and ijpID=? ";
            $stmt = $connn->prepare($upQry);
            $stmt->bind_param("issi", $flag, $remark, $empID, $ijpID);
            if (!$stmt) {
                echo "failed to run";
                die;
            }
            $updt = $stmt->execute();
            // echo $stmt->affected_rows;
            $resultQryUp = $stmt->get_result();
            print_r($resultQryUp);
            // $res = $myDB->query($upQry);
            // $mysql_error = $myDB->getLastError();
            if ($stmt->affected_rows === 1) {
                // $myDB = new MysqliDb();
                // $res = $myDB->query($upQry);
                // $mysql_error = $myDB->getLastError();
                // if (empty($mysql_error)) {
                echo "<script>$(function(){toastr.success('Acknowledged')})</script>";

                $location = URL . 'View/index.php';
                echo "<script>location.href='" . $location . "'</script>";
                header("Location: $location");
                exit();
            } else {
                echo "<script>$(function(){toastr.error('Try Again')})</script>";
            }
        } else {
            echo "<script>$(function(){toastr.error('Please acknowledge first.')})</script>";
        }
    }
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Employee Interview Acknowledge</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Employee Interview Acknowledge </h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <!-- <div class="input-field col s12 m12" id="rpt_container"> -->
                <form action="" method="POST">
                    <?php
                    $_SESSION["token"] = csrfToken();
                    ?>
                    <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                    <div class="input-field col s10 m10">
                        <select name="ijp" id="ijp">
                            <option value="NA">Select IJP</option>
                            <?php
                            $emplID = clean($_SESSION['__user_logid']);

                            $sqlBy = 'select m.id,m.ijp_name from ijp_emp i join ijp_master m on i.ijpID=m.id where EmployeeID=? and i.inter_flag="0" and i.flag=1 and m.schedule_intro IS NOT NULL';
                            $stmt1 = $connn->prepare($sqlBy);
                            $stmt1->bind_param("s", $emplID);
                            if (!$stmt1) {
                                echo "failed to run";
                                die;
                            }
                            $stmt1->execute();
                            $resultQry = $stmt1->get_result();
                            $count = $resultQry->num_rows;
                            // $myDB = new MysqliDb();
                            // $resultBy = $myDB->rawQuery($sqlBy);
                            // $mysql_error = $myDB->getLastError();
                            if ($resultQry) {
                                foreach ($resultQry as $key => $value) {
                                    echo '<option value="' . $value['id'] . '"  >' . $value['ijp_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div><br><br><br><br><br>

                    <h3 id='inter'>Your Interview Scheduled on <b id='date'></b> for <b id="para"></b> </h3><br><br>
                    <input type="radio" name="ack_check" id="ack_check1" value="1">
                    <label id="lbl" for="ack_check1">Interested</label>

                    <input type="radio" name="ack_check" id="ack_check2" value="2">
                    <label for="ack_check2">Not Interested</label>
                    <br><br><br><br>
                    <div class="input-field col s12 m12" id="remarks">
                        <label>Remarks</label>
                        <input type="text" class="form-control" name="remark" id="remark" />
                    </div>
                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" name="btn_ack" id="btn_ack" class="btn btn-primary">Acknowledge</button>
                    </div>
                </form>

                <!-- </div> -->

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
    $('#remarks').hide();
    $('#inter').hide();
    $("#ijp").change(function() {
        $('#inter').show();
        if ($('#ijp').val() == 'NA') {
            $('#inter').hide();
        }
        var ijp = $(this).val();
        $.ajax({
            url: '../Controller/get_ijp.php',
            type: 'GET',
            data: {
                ijp: ijp,
            },
            dataType: 'json',
            success: function(response) {
                //  alert(response);
                var data = $("#para").html(response.ijp_name);
                // alert(data);
                $("#date").html(response.schedule_intro);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#ack_check2').click(function() {
            if ($('#ack_check2').val() == '2') {
                $('#remarks').show();
            }
        });
        $('#ack_check1').click(function() {
            if ($('#ack_check1').val() == '1') {
                $('#remarks').hide();
            }
        });
        $('#btn_ack').click(function() {
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
        if ($('#ack_check2').val() == '2') {
            var validate = 0;
            var alert_msg = '';
            if ($('#remark').val() == '') {
                $('#remark').addClass("has-error");
                if ($('#spanremark').length == 0) {
                    $('<span id="spanremark" class="help-block">Required *</span>').insertAfter('#remark');
                }
                validate = 1;
            }
            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(5000).fadeOut("slow");
                return false;
            }
        }
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>