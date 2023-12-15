<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

if (($_SESSION["__status_ah"] != 'No' && $_SESSION["__status_ah"] == $_SESSION['__user_logid']) || $_SESSION['empid']=="Yes") {
    
}
else
{
	$location = URL . 'Error';
    header("Location: $location");
    exit();
}

$processQRY = "select cm_id, concat(t2.client_name,'|',t1.process,'|',t1.sub_process) as Process from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where account_head='" . $_SESSION["__status_ah"] . "' order by process;";
$myDB = new MysqliDb();
$result = $myDB->rawQuery($processQRY);
// echo "<pre>";
// print_r($result);

if (isset($_POST['btn_text_send'])) {
    $msg = $_POST['msg'];
    $cm_id = $_POST['team_process'];

    //select EmployeeID from ActiveEmpID where cm_id=56 and df_id=74;
    $insert_msg = 'INSERT INTO team_msg(sender_empid,sender_name,msg,to_empid,to_empname) SELECT "' . $_SESSION["__user_logid"] . '","' . $_SESSION["__user_Name"] . '","' . $msg . '",EmployeeID,EmpName FROM ActiveEmpID t1 join EmpID_Name t2 on t1.employeeID = t2.EmpID WHERE cm_id="' . $cm_id . '" '; //"'.$cmid.'"  and df_id=74
    $myDB = new MysqliDb();
    $myDB->query($insert_msg);
    // echo "<pre>";
    // print_r($resultdata);

    //INSERT INTO team_msg(sender_empid,msg,to_empid) select 'CE10091236','hello',EmployeeID from ActiveEmpID where cm_id=47 

    $mysql_error = $myDB->getLastError();
    if (empty($mysql_error)) {
        echo "<script>$(function(){toastr.success('Message Send Successfully'); }); </script>";
    } else {
        echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
    }
}


?>

<style>
    .error {
        color: red;
    }

    #data-container {
        display: block;
        background: #2a3f54;

        max-height: 250px;
        overflow-y: auto;
        z-index: 9999999;
        position: absolute;
        width: 100%;

    }

    #data-container li {
        list-style: none;
        padding: 5px;
        border-bottom: 1px solid #fff;
        color: #fff;
    }

    #data-container li:hover {
        background: #26b99a;
        cursor: pointer;
    }

    .form-control:focus {
        border-color: #d01010;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(233, 102, 139, 0.6);

    }

    #overlay {
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height: 100%;
        display: none;
        background: rgba(0, 0, 0, 0.6);
    }

    .cv-spinner {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px #ddd solid;
        border-top: 4px #2e93e6 solid;
        border-radius: 50%;
        animation: sp-anime 0.8s infinite linear;
    }

    @keyframes sp-anime {
        100% {
            transform: rotate(360deg);
        }
    }

    .is-hide {
        display: none;
    }
</style>

<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">Team Message</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>Team Message</h4>
            <div class="schema-form-section row">

                <div class="input-field col s12 m12">
                    <div class="col s12 m12">

                        <div class="input-field col s8 m8 l8">
                            <select id="team_process" name="team_process">
                                <option Selected="True" Value="NA">-Select One-</option>
                                <?php

                                foreach ($result as $key => $value) {
                                    echo '<option value="' . $value['cm_id'] . '">' . $value['Process'] . '</option>';
                                } ?>

                            </select>
                            <label for="team_process" class="active-drop-down active">Process</label>

                        </div>

                        <div class="input-field col s12 m12 ">
                            <textarea class="form-control materialize-textarea" name="msg" id="msg" cols="50" rows="60"></textarea>
                            <label for="msg">Write Your Message</label>
                            <div id="data-container"></div>
                        </div>
                    </div>

                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" name="btn_text_send" id="btn_text_send" class="btn waves-effect waves-green align-right">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Content Div for all Page End -->
</div>

<script>
    $(document).ready(function() {
        $('#btn_text_send').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#msg').val().trim() == '') {
                $('#msg').addClass('has-error');
                if ($('#spanmsg').size() == 0) {
                    $('<span id="spanmsg" class="help-block">Required *</span>').insertAfter('#msg');
                }
                validate = 1;
            }
            
            if ($('#team_process').val().trim() == 'NA') {
                $('#team_process').addClass('has-error');
                if ($('#spanteam_process').size() == 0) {
                    $('<span id="spanteam_process" class="help-block">Required *</span>').insertAfter('#team_process');
                }
                validate = 1;
            }
            
            if (validate == 1) {
                
                //alert('1');
                return false;
            }
        });
    });
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>