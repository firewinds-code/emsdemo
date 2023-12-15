<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$EmployeeID = $_SESSION['__user_logid'];
$myDB = new MysqliDb();
$cap_id = '';
$status = 'Pending';
if (isset($_POST['emp_status']) && $_POST['emp_status'] != "") {
    $status = $_POST['emp_status'];
}
if (isset($_POST['txt_dateTo']) && $_POST['txt_dateTo'] != "") {
    $date_To = $_POST['txt_dateTo'];
    $date_From = $_POST['txt_dateFrom'];
} else {
    $date_To = date('Y-m-d', time());
    //$date_To = date('Y-m-d', strtotime('today - 30 days'));

    //$d = new DateTime($date_To);
    // $d->modify('first day of this month');
    //$date_From= $d->format('Y-m-d');
    $date_From = date('Y-m-d', strtotime("$date_To - 30 days"));
    //$date_From= date('Y-m-d',time());


}
if (isset($_POST['submitHead'])) {
    //if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
    //echo "<pre>";print_r($_POST);exit;
    //var_dump($_POST);die();
    //echo $_POST['hidden_statushead'].' - '.$_POST['hidden_ah']; die;
    $EmployeeID = $_SESSION['__user_logid'];
    $createBy = $_SESSION['__user_logid'];

    if ($_SESSION["__user_type"] == 'HR' && $_POST['hidden_ah'] == $_SESSION['__user_logid'] && $_POST['hidden_statushead'] == 'pending') {
        $Query = "insert into corrective_action_formhead (corrective_Formid, ijp, incentive, pli, statusHead, head_comment, created_by) VALUES ('" . $_POST['headFormid'] . "','" . $_POST['ijp'] . "','" . $_POST['incentive'] . "','" . $_POST['pli'] . "','" . $_POST['statusHead'] . "','" . addslashes(trim($_POST['head_comment'])) . "','" . $EmployeeID . "')";

        $result_Hr = $myDB->rawQuery($Query);
        $mysql_error = $myDB->getLastError();

        $QueryHr = "insert into corrective_action_formhr (corrective_Formid, statusHr, hr_comment, created_by) VALUES ('" . $_POST['headFormid'] . "','" . $_POST['statusHead'] . "','" . addslashes(trim($_POST['head_comment'])) . "','" . $EmployeeID . "')";

        $result_Hr = $myDB->rawQuery($QueryHr);
        $mysql_error = $myDB->getLastError();

        if (empty($mysql_error)) {
            $myDB = new MysqliDb();
            $Query = "update corrective_action_form set issue_comment ='" . addslashes(trim($_POST['issue_comment'])) . "',statusHead = '" . $_POST['statusHead'] . "' where id='" . $_POST['headFormid'] . "'  ";

            $result_Hr = $myDB->rawQuery($Query);
            $mysql_error = $myDB->getLastError();

            $myDB = new MysqliDb();
            $Query = "update corrective_action_form set statusHr = '" . $_POST['statusHead'] . "' where id='" . $_POST['headFormid'] . "'  ";

            $result_Hr = $myDB->rawQuery($Query);
            $mysql_error = $myDB->getLastError();

            echo "<script>$(function(){ toastr.success('Status Update Successfully '); }); </script>";
            $location = URL . 'View/correctiveActionForm.php';
            echo "<script> window.location(" . $location . ")</script>";
        } else {
            echo "<script>$(function(){ toastr.warning('Status Not Updated '); }); </script>";
        }
    } else {
        $Query = "insert into corrective_action_formhead (corrective_Formid, ijp, incentive, pli, statusHead, head_comment, created_by) VALUES ('" . $_POST['headFormid'] . "','" . $_POST['ijp'] . "','" . $_POST['incentive'] . "','" . $_POST['pli'] . "','" . $_POST['statusHead'] . "','" . addslashes(trim($_POST['head_comment'])) . "','" . $EmployeeID . "')";

        $result_Hr = $myDB->rawQuery($Query);
        $mysql_error = $myDB->getLastError();
        $last_id = $myDB->getInsertId();

        if (empty($mysql_error)) {
            $myDB = new MysqliDb();
            $Query = "update corrective_action_form set issue_comment ='" . addslashes(trim($_POST['issue_comment'])) . "',statusHead = '" . $_POST['statusHead'] . "' where id='" . $_POST['headFormid'] . "'  ";

            $result_Hr = $myDB->rawQuery($Query);
            $mysql_error = $myDB->getLastError();

            echo "<script>$(function(){ toastr.success('Status Update Successfully '); }); </script>";
            $location = URL . 'View/correctiveActionFormHead_HR.php';
            echo "<script> window.location(" . $location . ")</script>";
        } else {
            echo "<script>$(function(){ toastr.warning('Status Not Updated '); }); </script>";
        }
    }


    //	exit;
    // }
}

if (isset($_POST['submitHr'])) {
    // if (isset($_POST["token1"]) && isset($_SESSION["token1"]) && $_POST["token1"] == $_SESSION["token1"]) {
    //echo "<pre>";print_r($_POST);exit;
    $EmployeeID = $_SESSION['__user_logid'];
    $createBy = $_SESSION['__user_logid'];
    $QueryHr = "insert into corrective_action_formhr (corrective_Formid, statusHr, hr_comment, created_by) VALUES ('" . $_POST['hrFormid'] . "','" . $_POST['statusHr'] . "','" . addslashes(trim($_POST['hr_comment'])) . "','" . $EmployeeID . "')";

    $result_Hr = $myDB->rawQuery($QueryHr);
    $mysql_error = $myDB->getLastError();
    $last_id = $myDB->getInsertId();
    if (empty($mysql_error)) {

        $myDB = new MysqliDb();
        $Query = "update corrective_action_form set statusHr = '" . $_POST['statusHr'] . "' where id='" . $_POST['hrFormid'] . "'  ";

        $result_Hr = $myDB->rawQuery($Query);
        $mysql_error = $myDB->getLastError();

        echo "<script>$(function(){ toastr.success('Status Update Successfully '); }); </script>";
        $location = URL . 'View/correctiveActionForm.php';
        echo "<script> window.location(" . $location . ")</script>";
    } else {
        echo "<script>$(function(){ toastr.warning('Status Not Updated '); }); </script>";
    }

    //	exit;
    //}
}

if ($_SESSION["__user_type"] == 'HR') {
    $getAllData = "select * from ( select distinct t1.*,t5.account_head from corrective_action_form t1 inner join personal_details t3 on t1.employee_id=t3.EmployeeID join employee_map t4 on t1.employee_id=t4.EmployeeID join new_client_master t5 on t4.cm_id=t5.cm_id  where t1.statusHead='Approved' and t3.location='" . $_SESSION["__location"] . "' and '" . $_SESSION["__status_ah"] . "' = '" . $_SESSION["__user_logid"] . "' and cast(t1.created_at as date) between cast('" . $date_From . "' as date) and cast('" . $date_To . "' as date) ";
    if (isset($_POST['status']) && $_POST['status'] == 'Approved') {
        $getAllData .= " and statusHr='Approved'";
    } else {
        $getAllData .= " and statusHr='pending' ";
    }

    $getAllData .= "union select distinct t1.*,t3.account_head from corrective_action_form t1 inner join whole_dump_emp_data t3 on t1.employee_id=t3.EmployeeID where t3.location='" . $_SESSION["__location"] . "' and t3.account_head='" . $_SESSION["__user_logid"] . "' and t1.statusHead='pending' and t1.statusHr='pending'  and (cast(t1.created_at as date) between cast('" . $date_From . "' as date) and cast('" . $date_To . "' as date))) t order by t.created_at ;";
} else {
    if ($EmployeeID == 'CE07147134') {
        $getAllData = "SELECT t1.*,t2.account_head FROM ems.corrective_action_form t1 join whole_dump_emp_data t2 on t1.employee_id = t2.EmployeeID where t2.account_head='" . $EmployeeID . "' or t2.ReportTo='" . $EmployeeID . "' and cast(t1.created_at as date) between cast('" . $date_From . "' as date) and cast('" . $date_To . "' as date) ";
    } else if ($_SESSION["__status_ah"] == $_SESSION['__user_logid']) {
        $getAllData = "SELECT t1.*,t2.account_head FROM ems.corrective_action_form t1 join whole_dump_emp_data t2 on t1.employee_id = t2.EmployeeID where t2.account_head='" . $EmployeeID . "' and t1.created_by !='CE07147134' and cast(t1.created_at as date) between cast('" . $date_From . "' as date) and cast('" . $date_To . "' as date) ";
    }
    if (isset($_POST['status']) && $_POST['status'] == 'Approved') {
        $getAllData .= " and statusHead='Approved'";
    } else {
        $getAllData .= " and statusHead='pending' ";
    }
    $getAllData .= " order by created_at desc;";
}
//echo $getAllData;
$allData = $myDB->rawQuery($getAllData);

?>
<style>
    .modal {
        width: 72% !important;
        overflow: hidden;
    }

    .row .col.offset-s2 {
        margin-left: 2.667%;
    }

    .modal .modal-content p {
        padding: 0px;
    }

    .custom_model {
        width: 75% !important;
    }
</style>

<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">CORRECTIVE ACTION FORM</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>CORRECTIVE ACTION FORM</h4>
            <div class="schema-form-section row">
                <form method="POST" action="<?php echo URL . 'View/correctiveActionFormHead_HR.php'; ?>" name="searchForm">
                    <div class="input-field col s12 m12" id="rpt_container">

                        <div class="input-field col s3 m3">

                            <input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
                        </div>
                        <div class="input-field col s3 m3">

                            <input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
                        </div>
                        <div class="input-field col s3 m3 left-align">

                            <Select name="status" style="min-width: 200px;" id="status">
                                <option value='pending' <?php if (isset($_POST['status']) && $_POST['status'] == 'pending') {
                                                            echo "selected";
                                                        } ?>> Pending</option>
                                <option value='Approved' <?php if (isset($_POST['status']) && $_POST['status'] == 'Approved') {
                                                                echo "selected";
                                                            } ?>>Approved</option>

                            </Select>
                        </div>
                        <div class="input-field col s3 m3">

                            <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
                            <!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
                        </div>

                    </div>
                </form>
                <div id="pnlTable">


                    <div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div>
                            <?php if (count($allData) > 0) {
                            ?>
                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>

                                            <th> Action </th>
                                            <th> Employee ID </th>
                                            <th> Name </th>
                                            <th> Issueed Date </th>
                                            <th> Position </th>
                                            <th> Department </th>
                                            <th> Supervisor Name: </th>
                                            <th> Issue Type </th>
                                            <!--<th> Description of Issue </th>-->
                                            <th> Created By </th>
                                            <th> Created At </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($allData as $value) {
                                            if ($value['statusHr'] != 'Approved') {


                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($_SESSION["__user_type"] == 'HR') {
                                                            if ($value['account_head'] == $_SESSION['__user_logid'] && $value['statusHead'] == 'pending') {

                                                        ?>
                                                                <button data-target="modal1" class="waves-effect waves-light btn-small modal-trigger req" form_id=<?php echo $value['id'] ?>>Action - AH</button>
                                                            <?php  } else { ?>
                                                                <button data-target="modal2" class="waves-effect waves-light btn-small modal-trigger req2" form_id=<?php echo $value['id'] ?>>Action - HR</button>
                                                            <?php }
                                                        } else { ?>

                                                            <button data-target="modal1" class="waves-effect waves-light btn-small modal-trigger req" form_id=<?php echo $value['id'] ?>>Action - AH</button>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php echo $value['employee_id'] ?></td>
                                                    <td><?php echo $value['employee_name'] ?></td>
                                                    <td><?php echo $value['issued_date'] ?></td>
                                                    <td><?php echo $value['position'] ?></td>
                                                    <td><?php echo $value['department'] ?></td>
                                                    <td><?php echo $value['supervisor_name'] ?></td>
                                                    <td><?php echo $value['issue_type'] ?></td>
                                                    <!--<td><?php echo $value['description_of_issue'] ?></td>-->
                                                    <td><?php echo $value['created_by'] ?></td>
                                                    <td><?php echo $value['created_at'] ?></td>
                                                </tr>

                                        <?php
                                            }
                                        } ?>

                                    </tbody>

                                </table>
                            <?php
                            } else {

                                echo "<script>$(function(){ toastr.info('No Records Found. ' ); }); </script>";
                            }
                            ?>
                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>

</div>

<div id="modal1" class="modal" style="height: 550px;">
    <div class="modal-content">

        <form method="POST" action="<?php echo URL . 'View/correctiveActionFormHead_HR.php'; ?>" enctype="multipart/form-data" name="headForm">
            <?php
            $_SESSION["token"] = csrfToken();
            ?>
            <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
            <input type="hidden" name="headFormid" id="headFormid">
            <div class="row center">
                <input type="hidden" id="hidden_statushead" name="hidden_statushead" />
                <input type="hidden" id="hidden_ah" name="hidden_ah" />
                <h4 style="color:#19aec4">Account Head Comment</h4>
                <div class="col s12">
                    <div class="row">

                        <div class="col s12">
                            <div class="input-field left">
                                <div class="col s12" id="file_container" style="margin-top: 10px;overflow: auto;">

                                </div>
                            </div>
                        </div>

                        <div class="col s4">
                            <div class="input-field center">
                                <select class="" id="ijp" name="ijp" title="Select IJP">
                                    <!--<option value="NA">Select IJP </option>-->
                                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?> </option>
                                    <?php } ?>

                                </select>
                                <label title="" for="IJP" class="active-drop-down active">Disqualify For IJP (In Months)</label>
                            </div>
                        </div>

                        <div class="col s4">
                            <div class="input-field center">
                                <select class="" id="incentive" name="incentive" title="Select Incentive ">
                                    <!--<option value="NA">Select Incentive </option>-->
                                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?> </option>
                                    <?php } ?>

                                </select>
                                <label title="" for="Incentive " class="active-drop-down active">Incentive Deduction (In Months)</label>
                            </div>
                        </div>
                        <div class="col s4">
                            <div class="input-field center">
                                <select class="" id="pli" name="pli" title="Select PLI ">
                                    <!-- <option value="NA">Select PLI </option>-->
                                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?> </option>
                                    <?php } ?>

                                </select>
                                <label title="" for="PLI" class="active-drop-down active">PLI Deduction (In Months)</label>
                            </div>
                        </div>

                        <div class="col s12">
                            <div class="input-field center">
                                <select class="" id="statusHead" name="statusHead" title="Select status " required>
                                    <option value="">Select status </option>
                                    <option value="Approved">Approved </option>
                                    <option value="Not Approved"> Not Approved </option>


                                </select>
                                <label title="" for="Status" class="active-drop-down active">Status </label>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="input-field center">
                                <textarea class="materialize-textarea" title="Comment" name="issue_comment" id="issue_comment" maxlength="500" required style="overflow: auto;"></textarea>
                                <label for="issue_comment">Issue Comment</label>
                            </div>
                        </div>

                        <div class="col s12" id="divHeadComment">
                            <div class="input-field center">
                                <textarea class="materialize-textarea" title="Comment" name="head_comment" maxlength="500" required style="overflow: auto;"></textarea>
                                <label for="head_comment"> Comment</label>
                            </div>
                        </div>

                        <div class="col s12">
                            <div class="input-field center">
                                <div class="col s12" id="comment_container" style="margin: 0px;max-height: 150px;overflow: auto;">
                                </div>
                            </div>
                        </div>


                        <div class="col s12" id="divHeadSubmit">
                            <div class="input-field">
                                <input class="validate input-field btn" type="submit" name="submitHead" id="submitHead" value="Submit">
                            </div>
                        </div>

                        <div class="col s12">
                            <div class="modal-footer">
                                <a href="#" class="btn modal-close waves-effect waves-green btn-flat">Close</a>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
        </form>
    </div>

</div>

<div id="modal2" class="modal custom_model" style="height: 550px;">
    <div class="modal-content ">

        <form method="POST" action="<?php echo URL . 'View/correctiveActionFormHead_HR.php'; ?>" enctype="multipart/form-data" name="headForm">
            <?php

            $_SESSION["token1"] = csrfToken();
            ?>
            <input type="hidden" name="token1" value="<?= $_SESSION["token1"] ?>">
            <input type="hidden" name="hrFormid" id="hrFormid">

            <div class="row center">
                <h4 style="color:#19aec4">HR Comment</h4>
                <div class="col s12">

                    <div class="row">

                        <div class="col s12">
                            <div class="input-field left">
                                <div class="col s12" id="file_container_hr" style="margin-top: 10px;overflow: auto;">

                                </div>
                            </div>
                        </div>

                        <div class="col s4">
                            <div class="input-field center">
                                <select class="" id="ijphr" name="ijphr" title="Select IJP">


                                </select>
                                <label title="" for="ijphr" class="active-drop-down active">Disqualify For IJP (In Months)</label>
                            </div>
                        </div>

                        <div class="col s4">
                            <div class="input-field center">
                                <select class="" id="incentivehr" name="incentivehr" title="Select Incentive ">


                                </select>
                                <label title="" for="incentivehr " class="active-drop-down active">Incentive Deduction (In Months)</label>
                            </div>
                        </div>

                        <div class="col s4">
                            <div class="input-field center">
                                <select class="" id="plihr" name="plihr" title="Select PLI ">


                                </select>
                                <label title="" for="plihr" class="active-drop-down active">PLI Deduction (In Months)</label>
                            </div>
                        </div>

                        <div class="col s12">
                            <div class="input-field center">
                                <select class="" id="statusHead_hr" name="statusHead_hr" title="Select PLI ">


                                </select>
                                <label title="" for="statusHead_hr" class="active-drop-down active">Status Head</label>
                            </div>
                        </div>

                        <div class="col s12">
                            <div class="input-field center">
                                <select class="" id="statusHr" name="statusHr" title="Select status" required>
                                    <option value="NA">Select status </option>
                                    <option value="Approved">Approved</option>
                                    <option value="Not Approved">Not Approved</option>


                                </select>
                                <label title="" for="statusHr" class="active-drop-down active">HR Status </label>
                            </div>
                        </div>

                        <div class="col s12" id="divCommentHR">
                            <div class="input-field center">
                                <textarea class="materialize-textarea" title="Comment" name="hr_comment" required></textarea>
                                <label for="hr_comment">Comment</label>
                            </div>
                        </div>

                        <div class="col s12">
                            <div class="input-field center">
                                <div class="col s12" id="comment_container_hr" style="margin: 0px;max-height: 150px;overflow: auto;">
                                </div>
                            </div>
                        </div>

                        <div class="col s12" id="divSubmitHR">
                            <div class="input-field">
                                <input class="validate input-field btn" type="submit" name="submitHr" value="Submit">
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
</div>
<script>
    $(document).ready(function() {

        /*$('#btn_view').click(function() {
    	
       alert('rrrr');
        
        });
        */
        $('#txt_dateFrom,#txt_dateTo').datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });
        $('.req').click(function() {

            var headFormid = $(this).attr("form_id");
            $('#hidden_statushead').val('');
            $('#hidden_ah').val('');
            $("input[name='headFormid']").val(headFormid);
            $.ajax({
                url: "../Controller/getComment_CAP.php?ID=" + $(this).attr("form_id"),
                success: function(result) {
                    //alert(result);                    
                    if (result != '') {


                        $('#comment_container').empty().append(result);

                    }
                    $('select').formSelect();
                }

            });

            $.ajax({
                url: "../Controller/getCAP_issue_comment.php?ID=" + $(this).attr("form_id"),
                success: function(result) {
                    //alert(result);                    
                    if (result != '') {
                        $.trim(result);
                        $.trim($('#issue_comment').val(result));
                        $('#issue_comment').focus();
                        //$('#comment_container').empty().append(result);

                    }
                    $('select').formSelect();
                }
            });

            $.ajax({
                url: "../Controller/getCAP_status.php?ID=" + $(this).attr("form_id"),
                success: function(result) {
                    //alert(result.length);                    
                    if (result != null) {
                        var Data = result.split('|$|');
                        var accstatus = Data[0];
                        var hrstatus = Data[1];
                        validate = 0;
                        if (accstatus == "Approved" || accstatus == "Not Approved") {

                            if (hrstatus == "Not Approved") {
                                validate = 0;
                            } else {
                                validate = 1;
                            }

                        }


                        if (validate == 1) {
                            $('#divHeadComment').addClass('hidden');
                            $('#divHeadSubmit').addClass('hidden');
                        } else {
                            $('#divHeadComment').removeClass('hidden');
                            $('#divHeadSubmit').removeClass('hidden');
                        }
                    }
                    $('select').formSelect();
                }
            });
            var val = "Not Exist";
            $.ajax({
                url: "../Controller/getCAPAH_Data.php?ID=" + $(this).attr("form_id"),
                success: function(result3) {
                    //alert(typeof val); 
                    //alert(result3);                   
                    if ($.trim(result3) == "Not Exist") {

                    } else {

                        var Data = result3.split('|$|');
                        var pli = Data[0];
                        /*if(pli!='')
            	{
					alert(pli.length);
				}*/
                        $('#ijp').val(Data[0]);
                        $('#incentive').val(Data[1]);
                        $('#pli').val(Data[2]);
                        $('#statusHead').val(Data[3]);
                        /*$('#comment_box').removeClass('hidden');
                        $('#comment_container').empty().append(result);*/
                    }
                    $('select').formSelect();
                }
            });

            $.ajax({
                url: "../Controller/getCAPAH_File.php?ID=" + $(this).attr("form_id"),
                success: function(result) {
                    //alert(result);                    
                    if (result != '') {

                        $('#file_container').empty().append(result);

                    }
                    $('select').formSelect();
                }
            });

            $.ajax({
                url: "../Controller/get_Cap_tmpdata.php?id=" + $(this).attr("form_id"),
                success: function(result) {
                    //alert(result);                    
                    if (result != '') {

                        var Data = result.split('|$|');
                        $('#hidden_statushead').val(Data[0]);
                        $('#hidden_ah').val(Data[1]);
                        /*alert($('#hidden_statushead').val());
                        alert($('#hidden_ah').val());*/

                    }
                    $('select').formSelect();
                }

            });

        });

        $('.req2').click(function() {
            var hrFormid = $(this).attr("form_id");
            $("input[name='hrFormid']").val(hrFormid);

            $.ajax({
                url: "../Controller/getCAPAH_Data.php?ID=" + $(this).attr("form_id"),
                success: function(result3) {
                    //alert(typeof val); 
                    //alert(result3);                   
                    if ($.trim(result3) == "Not Exist") {

                    } else {

                        var Data = result3.split('|$|');
                        var pli = Data[0];
                        /*if(pli!='')
            	{
					alert(pli.length);
				}*/

                        $('#ijphr').append($("<option></option>")
                            .attr("value", Data[0])
                            .text(Data[0]));

                        $('#incentivehr').append($("<option></option>")
                            .attr("value", Data[1])
                            .text(Data[1]));

                        $('#plihr').append($("<option></option>")
                            .attr("value", Data[2])
                            .text(Data[2]));

                        $('#statusHead_hr').append($("<option></option>")
                            .attr("value", Data[3])
                            .text(Data[3]));

                        /*$('#incentivehr').append($("<option></option>")
                        .attr("value", Data[3])
                        .text(Data[3]));	statusHead_hr*/


                    }
                    $('select').formSelect();
                }
            });

            $.ajax({
                url: "../Controller/getCAPAH_File.php?ID=" + $(this).attr("form_id"),
                success: function(result) {
                    //alert(result);                    
                    if (result != '') {

                        $('#file_container_hr').empty().append(result);

                    }
                    $('select').formSelect();
                }
            });

            $.ajax({
                url: "../Controller/getCAP_status.php?ID=" + $(this).attr("form_id"),
                success: function(result3) {

                    if (result3 != null) {
                        var Data = result3.split('|$|');
                        var accstatus = Data[0];
                        var hrstatus = Data[1];
                        validate = 0;

                        if (hrstatus == "Not Approved") {
                            if (accstatus == "Approved") {
                                validate = 0;
                            }

                        } else if (hrstatus == "Approved") {
                            validate = 1;
                        }

                        if (validate == 1) {
                            $('#divSubmitHR').addClass('hidden');
                            $('#divCommentHR').addClass('hidden');
                        } else {
                            $('#divSubmitHR').removeClass('hidden');
                            $('#divCommentHR').removeClass('hidden');
                        }
                        if (hrstatus == 'Approved' || hrstatus == 'Not Approved') {
                            //alert(hrstatus);
                            $('#statusHr').val(hrstatus);
                        }
                    }

                    /*if($.trim(result3) == "Not Exist")
            {
            	$('#divSubmitHR').removeClass('hidden');
				$('#divCommentHR').removeClass('hidden');		
			} 
			else
			{	
				
				$('#divSubmitHR').addClass('hidden');
				$('#divCommentHR').addClass('hidden');
				var Data  = result3;
            	//alert(Data);
				$('#statusHr').val(Data);	
				
			} */
                    $('select').formSelect();
                }
            });

            $.ajax({
                url: "../Controller/getComment_CAP.php?ID=" + $(this).attr("form_id"),
                success: function(result) {
                    //alert(result);                    
                    if (result != '') {


                        $('#comment_container_hr').empty().append(result);

                    }
                    $('select').formSelect();
                }
            });

        });
        //$(document).ready(function() {
        $('.modal').modal();
    });




    $(document).ready(function() {
        $('#alert_msg_close').click(function() {
            $('#alert_message').hide();
        });
        if ($('#alert_msg').text() == '') {
            $('#alert_message').hide();
        }


        $('#div_error').removeClass('hidden');
    });
</script>
<script>
    $(document).ready(function() {
        $('#btn_docAdd').click(function() {

            $count = $(".trdoc").length;
            if ($count > 5) {
                alert('No more Than 5 file allow');
                return false;
            }
            $id = "trdoc_" + parseInt($count + 1);
            $('#doc_child').val(parseInt($count + 1));
            $tr = $("#trdoc_1").clone().attr("id", $id);

            $('#childtable tbody').append($tr);
            $tr.children("td:first-child").html(parseInt($count + 1));


        });
        $('#btnDoccan').click(function() {
            $count = $(".trdoc").length;
            if ($count > 1) {
                $('#childtable tbody').children("tr:last-child").remove();
                $('#doc_child').val(parseInt($count - 1));
            }

        });
        $('#btn_document_add').click(function() {
            var rowlen = ($('.trdoc').length);
            for (i = 1; i <= rowlen; i++) {
                if ($('#txt_doc_value_' + i).val().trim() == '') {
                    $(function() {
                        toastr.error('Please enter document id in' + i + ' row')
                    });
                    return false;
                    break;
                }
                if ($('#txt_doc_name_' + i).val() == '') {
                    $(function() {
                        toastr.error('Please select document file for ' + i + ' row')
                    });
                    return false;
                    break;
                }
            }


        });

        $('#submitHead').click(function() {
            $validate = 0;
            if ($.trim($('#issue_comment').val()) == '') {
                $('#issue_comment').addClass('has-error');
                $validate = 1;

            }

            if ($.trim($('#issue_comment').val()).length < 150) {
                $('#issue_comment').addClass('has-error');
                $validate = 1;
                if ($('#sremark1').size() == 0) {
                    $('<span id="sremark1" class="help-block">Remark should be greater than 150 character.</span>').insertAfter('#issue_comment');
                }
            }

            if (checkRepeat($('#issue_comment').val())) {
                $('#issue_comment').addClass('has-error');
                $validate = 1;
                if ($('#sremark').size() == 0) {
                    $('<span id="sremark" class="help-block">Remark should not contain Repeat character.</span>').insertAfter('#issue_comment');
                }
            }

            if ($validate == 1) {
                return false;
            }

        });

        function checkRepeat(str) {
            var repeats = /(.)\1{3,}/;
            return repeats.test(str)
        }

    });
</script>
<script>
    $(document).ready(function() {
        $('#issued_date').datetimepicker({
            format: 'Y-m-d',
            timepicker: false
        });


        $('#myTable').DataTable({
            dom: 'Bfrtip',
            scrollX: '100%',
            "iDisplayLength": 25,
            scrollCollapse: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: [
                /*  
                {
                    extend: 'csv',
                    text: 'CSV',
                    extension: '.csv',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    },
                    title: 'table'
                }, 						         
                'print',*/
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
                , 'pageLength'

            ]
            // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
        });


    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>