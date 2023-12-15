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

// if ($_SESSION['ijpEmp'] == "Yes") {
// } else {
//     $location = URL . 'error';
//     echo '<script language="javascript">window.location.href ="' . $location . '"</script>';
//     exit();
// }
$myDB = new MysqliDb();
$connn = $myDB->dbConnect();

if ($_POST['ack_check'] == 1) {
    $flag = 1;
} else {
    $flag = 2;
}
$btn_ack = isset($_POST['btn_ack']);
if ($btn_ack) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $ack_check = isset($_POST['ack_check']);
        if ($ack_check) {
            $ijpID = cleanUserInput($_POST['ijp']);
            $empID = clean($_SESSION['__user_logid']);

            $upQry = "update ijp_emp set flag=? where EmployeeID=? and ijpID=?";
            $stmt = $connn->prepare($upQry);
            $stmt->bind_param("iii", $flag, $empID, $ijpID);
            if (!$stmt) {
                echo "failed to run";
                die;
            }
            $updt = $stmt->execute();
            // echo $stmt->affected_rows;
            // $result = $stmt->get_result();
            // $res = $myDB->query($upQry);
            // $mysql_error = $myDB->getLastError();
            if ($stmt->affected_rows === 1) {
                // if (!$connn->query($upQry) === TRUE) {
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
    <span id="PageTittle_span" class="hidden">Employee Acknowledge</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Employee Acknowledge </h4>

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
                            $sqlBy = 'select m.id,m.ijp_name from ijp_emp i join ijp_master m on i.ijpID=m.id where EmployeeID=? and i.flag="0"';
                            $stmt1 = $connn->prepare($sqlBy);
                            $stmt1->bind_param("s", $emplID);
                            if (!$stmt1) {
                                echo "failed to run";
                                die;
                            }
                            $stmt1->execute();
                            $resultQry = $stmt1->get_result();
                            $count = $resultQry->num_rows;
                            // $resultBy = $myDB->rawQuery($sqlBy);
                            // $mysql_error = $myDB->getLastError();
                            if ($resultQry) {
                                foreach ($resultQry as $key => $value) {
                                    echo '<option value="' . $value['id'] . '"  >' . $value['ijp_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <br><br><br><br><br><br>
                    <input type="radio" name="ack_check" id="ack_check1" value="1">
                    <label id="lbl" for="ack_check1">Interested</label>

                    <input type="radio" name="ack_check" id="ack_check2" value="2">
                    <label for="ack_check2">Not Interested</label>
                    <br><br>

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
</script>
<script>
    $(document).ready(function() {
        $('#btn_ack').click(function() {
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