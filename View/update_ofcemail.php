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
    <span id="PageTittle_span" class="hidden">Official EmailID Update</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Official EmailID Update</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s5 m5">
                        <span>EmployeeID</span>
                        <input type="text" class="form-control" name="txt_empid" id="txt_empid" />
                    </div>
                    <div class="input-field col s5 m5">
                        <span>Ofc EmailID</span>
                        <input type="text" class="form-control" name="txt_email" id="txt_email" />
                    </div>

                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="update" id="update">Update</button>
                    </div>
                </div>
                <?php
                if (isset($_POST['update'])) {
                    $txt_empid = cleanUserInput($_POST['txt_empid']);
                    $txt_email = cleanUserInput($_POST['txt_email']);
                    $updateQry = "call update_ofceamil('" . $txt_empid . "','" . $txt_email . "')";
                    $result = $myDB->query($updateQry);
                    $mysql_error = $myDB->getLastError();
                    if (empty($mysql_error)) {
                        echo "<script>$(function(){ toastr.success('Ofc EmailID Updated Successfully.'); }); </script>";
                    } else {
                        echo "<script>$(function(){ toastr.error('Ofc EmailID Not Update.'" . $mysql_error . "); }); </script>";
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

        function validateEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        $('#update').click(function() {
            validate = 0;
            var alert_msg = '';

            if ($('#txt_empid').val() == '') {
                $('#txt_empid').addClass('has-error');
                if ($('#spanMessage_empid').size() == 0) {
                    $('<span id="spanMessage_empid" class="help-block"></span>').insertAfter('#txt_empid');
                }
                $('#spanMessage_empid').html('Employee Id can not be Empty');
                validate = 1;

            }

            var txt_email = $('#txt_email').val().trim();
            if (txt_email == "") {
                $('#txt_email').addClass('has-error');
                validate = 1;
                if ($('#stxt_email').size() == 0) {
                    $('<span id="stxt_email" class="help-block">Email ID should not be empty.</span>').insertAfter('#txt_email');
                }

            } else {
                if (!validateEmail(txt_email)) {
                    $('#txt_email').addClass('has-error');
                    validate = 1;
                    if ($('#stxt_email1').size() == 0) {
                        $('<span id="stxt_email1" class="help-block">Email ID is not valid, please try again.</span>').insertAfter('#txt_email');
                    }
                }
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