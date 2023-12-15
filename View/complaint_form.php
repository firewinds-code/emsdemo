<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

ini_set('display_errors', '0');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

$EmployeeID = clean($_SESSION['__user_logid']);

if ($EmployeeID == 'CE03070003' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE05101779' || $EmployeeID == 'CE12102224') {
} else {
    $location = URL . 'unknown';
    echo "<script>location.href='.$location.'</script>";
    exit();
}
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();


if (isset($_POST['savebriefing'])) {
    unset($_POST['savebriefing']);
    unset($_POST['__myclipdatat']);
    unset($_POST['__Action_Grid']);
    $target_file = '';
    // $comp_no = 'COMP-COG' . time();
    $case_rep_source = ($_POST['case_rep_source']);
    $case_rep_date = ($_POST['case_rep_date']);
    $case_rep_by = ($_POST['case_rep_by']);
    $incident_date = ($_POST['incident_date']);
    $comp_from = ($_POST['comp_from']);
    $loaction = ($_POST['loaction']);
    $emp_status = ($_POST['emp_status']);
    $process_name = ($_POST['process_name']);
    // $process_detail = ($_POST['process_detail']);
    $comp_against = ($_POST['comp_against']);
    $comp_category = ($_POST['comp_category']);
    $comp_sub_category = ($_POST['comp_sub_category']);
    $VH = ($_POST['process_detail_VH']);
    $AH = ($_POST['process_detail_AH']);
    $OH = ($_POST['process_detail_OH']);
    $case_detail = ($_POST['case_detail']);
    $investigation = ($_POST['investigation']);
    $conclusion = ($_POST['conclusion']);
    $call_status = ($_POST['call_status']);
    $remark = ($_POST['remark']);
    // echo "<pre>";
    // print_r($_POST);
    // die;
    $total = count($_FILES['doc_evi']['name']);
    if ($total > 0) {
        for ($i = 0; $i < $total; $i++) {
            $doc_evi = time() . '_' . basename($_FILES["doc_evi"]["name"][$i]);
            // $ins_doc_evi .= ',' . $doc_evi;
            $ins_doc_evi .= $doc_evi . ',';
            $target_dir = "../uploads/";
            $target_file = $target_dir . $doc_evi;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


            if ($imageFileType == 'jpg' || $imageFileType == 'png' || $imageFileType == 'jpeg' || $imageFileType == 'pdf') {
                move_uploaded_file($_FILES["doc_evi"]["tmp_name"][$i], $target_file);
            } else {
                //  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }
    }

    $query = "INSERT INTO comp_table ( case_rep_source, case_rep_date, case_rep_by, incident_date, comp_from, loaction, emp_status, process_name,comp_against, comp_category, comp_sub_category, case_detail, investigation, conclusion, call_status, remark, VH, AH, OH,  emp_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmts = $conn->prepare($query);

    $stmts->bind_param("ssssssssssssssssssss", $case_rep_source, $case_rep_date, $case_rep_by, $incident_date, $comp_from, $loaction, $emp_status, $process_name,  $comp_against, $comp_category, $comp_sub_category, $case_detail, $investigation, $conclusion, $call_status, $remark, $VH, $AH, $OH, $EmployeeID);

    // echo 'dd';
    // die;
    $stmts->execute();
    $results = $stmts->get_result();

    $insertId = $conn->insert_id;
    $doc_evi = rtrim($ins_doc_evi, ',');
    $ins_doc_evi1 = explode(",", $doc_evi);
    // print_r($ins_doc_evi1);
    // die;
    if ($stmts->affected_rows === 1) {
        foreach ($ins_doc_evi1 as $evid_doc) {
            $query = "INSERT INTO comp_issue_file ( comp_id, doc_evi_filename) VALUES (?,?)";
            $stmts = $conn->prepare($query);
            $stmts->bind_param("is", $insertId, $evid_doc);
            $stmts->execute();
            $results = $stmts->get_result();
        }
        if ($stmts->affected_rows === 1) {
            // echo "Records inserted successfully.";
            echo "<script>$(function(){toastr.success('Records inserted successfully.')})</script>";

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = EMAIL_HOST;
            $mail->SMTPAuth = EMAIL_AUTH;
            $mail->Username = EMAIL_USER;
            $mail->Password = EMAIL_PASS;
            $mail->SMTPSecure = EMAIL_SMTPSecure;
            $mail->Port = EMAIL_PORT;
            $mail->setFrom(EMAIL_FROM,  'Cogent | Site Complain');
            // $mail->AddAddress('sachin.siwach@cogenteservices.com','banpreet.kaur@cogenteservices.com');
            $mail->AddAddress('sachin.siwach@cogenteservices.com');
            $mail->AddAddress('banpreet.kaur@cogenteservices.com');

            $mail->Subject = "Site Issue Tracker----$loaction";
            $mail->isHTML(true);
            $msg2 = "Dear Team,</br><br/><p>A complaint has been reported by <b>" .  $case_rep_by . "</b> from <b>" . $comp_from . '</b> against <b>' . $comp_against . '</b>, please find below the complaint details.<br/><br/><p><b>Reporting Date-- </b>' . $case_rep_date . ' </p><p><b>Incident Date-- </b>' . $incident_date . '</p><p><b><u>Complaint</u></b></p><p>' . $case_detail . '</p><p><b><u>Investigation</u></b></p><p>' .  $investigation . '</p><p><b><u>Conclusion</u></b></p><p>' . $conclusion . '</p><p><b><u>Additional Remark</u></b></p><p>' . $remark . '</p></br><br/>
					Thanks <br/> Cogent ';
            $mail->Body = $msg2;
            $mymsg = '';
            // $response = '';
            foreach ($ins_doc_evi1 as $evid_doc) {
                $target_dir = "../uploads/";
                $path = $target_dir . $evid_doc;

                $mail->AddAttachment($path);
            }
            if (!$mail->send()) {
                // echo  $response =  'Mailer Error:' . $mail->ErrorInfo;
                echo "<script>$(function(){toastr.error('Mailer Error.')})</script>";
            } else {
                // $response =  'Mail Send successfully';
                echo "<script>$(function(){toastr.success('Mail Send successfully')})</script>";
            }
        } else {
            echo "<script>$(function(){toastr.error('Records Not inserted.')})</script>";
        }
    } else {
        echo "<script>$(function(){toastr.error('Records Not inserted.')})</script>";
    }
}
?>

<style>
    .error {
        color: red;
    }

    /* case_rep_by  comp_from  comp_against */
    #data-case_rep_by,
    #data-comp_from,
    #data-comp_against {
        display: block;
        background: #2a3f54;

        max-height: 250px;
        overflow-y: auto;
        z-index: 9999999;
        position: absolute;
        width: 100%;

    }

    #data-case_rep_by li,
    #data-comp_from li,
    #data-comp_against li {
        list-style: none;
        padding: 5px;
        border-bottom: 1px solid #fff;
        color: #fff;
    }

    #data-case_rep_by li:hover,
    #data-comp_from li:hover,
    #data-comp_against li:hover {
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


    span.badge {
        background: none;
        color: black;
        min-width: 30px;
        padding: 2px;
        top: 3px;
    }
</style>

<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">Site Issue Tracker</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>Site Issue Tracker</h4>
            <div class="schema-form-section row">

                <div class="col-md-12">
                    <form class="form-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Employee Status</label>
                            <select name='case_rep_source' id='case_rep_source'>
                                <option value=''> ----- select ----- </option>
                                <option value="call">Call</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="socialmedia">Social Media</option>
                                <option value="textmsg">Text Message</option>
                            </select>
                        </div>

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Case Reported Date</label>
                            <input type='text' name='case_rep_date' id='case_rep_date' placeholder='Case Reported Date' value="">
                        </div>

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Case Reported By</label>
                            <input type='text' name='case_rep_by' id='case_rep_by' placeholder='Case Reported By' value="">
                            <span class="badge badge-primary"><input class="btn waves-effect waves-green" type="button" value="Find" id="find_case_by"></input></span>
                            <div id="data-case_rep_by"></div>
                        </div>
                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Incident Date</label>
                            <input type='text' name='incident_date' id='incident_date' placeholder='Incident Date' value="">
                        </div><br>

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Complaint From</label>
                            <input type='text' name='comp_from' id='comp_from' placeholder='Complaint From' value="">
                            <span class="badge badge-primary"><input type="button" class="btn waves-effect waves-green" value="Find" id="find_com_from"></input></span>
                            <div id="data-comp_from"></div>
                        </div>
                        <div class="input-field col s6 m6">
                            <input type="text" name="loaction" id="loaction" readonly>
                            <label for="loaction" class="active-drop-down active">Location</label>
                        </div>
                        <div class="input-field col s6 m6 ">
                            <input type="text" name="emp_status" id="emp_status" readonly>
                            <label for='option' class='active'>Employee Status</label>

                        </div>
                        <div class="input-field col s6 m6 l6">
                            <input type="text" name="process_name" id="process_name" readonly>

                            <label for="process_name" class="active-drop-down active">Process</label>
                        </div>
                        <div class="input-field col s6 m6 l6">
                            <input type="text" name="process_detail_VH" id="process_detail_VH" readonly>
                            <!-- <input type="hidden" name="process_detail_VH_id" id="process_detail_VH_id"> -->
                            <label for="process_detail" class="active-drop-down active">VH</label>
                        </div>
                        <div class="input-field col s6 m6 l6">
                            <input type="text" name="process_detail_AH" id="process_detail_AH" readonly>
                            <!-- <input type="hidden" name="process_detail_AH_id" id="process_detail_AH_id"> -->
                            <label for="process_detail" class="active-drop-down active">AH</label>
                        </div>
                        <div class="input-field col s6 m6 l6">
                            <input type="text" name="process_detail_OH" id="process_detail_OH" readonly>
                            <!-- <input type="hidden" name="process_detail_OH_id" id="process_detail_OH_id"> -->
                            <label for="process_detail" class="active-drop-down active">OH</label>
                        </div>

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Complaint Against</label>
                            <input type='text' name='comp_against' id='comp_against' placeholder='Complaint Against' value="">
                            <span class="badge badge-primary"><input type="button" class="btn waves-effect waves-green" value="Find" id="find_comp_ag"></input></span>
                            <div id="data-comp_against"></div>
                        </div>

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Complaint Category</label>
                            <select name='comp_category' id='comp_category' onchange='getSubCat(this.value)'>
                                <option value='NA'> ----- select ----- </option>
                                <option value="General Concerns">General Concerns</option>
                                <option value="Police Complaint">Police Complaint</option>
                                <option value="Medical Concerns">Medical Concerns</option>
                                <option value="Travel Related">Travel Related</option>
                                <option value="Infrastructural">Infrastructural</option>
                                <option value="Third Party Issue">Third Party Issue</option>
                            </select>
                        </div>

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Complaint Sub-Category</label>
                            <select name='comp_sub_category' id='comp_sub_category'>
                                <option value="NA">-----select------</option>
                            </select>
                        </div>

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Case Details</label>
                            <input type="text" id='case_detail' name='case_detail' value="">
                        </div>

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Investigation</label>
                            <input type="text" id='investigation' name='investigation' maxlength="250" value="">
                        </div>

                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Conclusion</label>
                            <input type="text" id='conclusion' name='conclusion' maxlength="250" value="">
                        </div>
                        <div class="input-field col s6 m6 ">
                            <label for='option' class='active'>Case Status</label>
                            <select name='call_status' id='call_status'>
                                <option value='' disabled> ----- select ----- </option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div class="input-field col s12 m12 ">
                            <label for='option' class='active'>Remark</label>
                            <input type="text" id='remark' name='remark' maxlength="250" value="">
                        </div>

                        <div class="input-field col s8 m8" id="childtables">
                            <input type="hidden" id="Document Details" name="doc_child" />
                            <div class="form-inline addChildbutton " style="margin-bottom: 10px;">
                                <div class="form-group">
                                    <button type="button" name="btn_docAdd" id="btn_docAdd" title="Add Doc Row in Table Down" class="btn waves-effect waves-green">
                                        <i class="fa fa-plus"></i> Add Document</button>
                                    <button type="button" name="btnDoccan" id="btnDoccan" title="Remove Doc Row in Table Down" class="btn waves-effect modal-action modal-close waves-red close-btn">
                                        <i class="fa fa-minus"></i> Remove Document</button>
                                </div>
                            </div>
                            <table class="table table-hovered table-bordered" id="childtable">
                                <thead class="bg-danger">
                                    <tr>
                                        <th class="hidden">Doc ID</th>
                                        <th>Documentary Evidences</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="trdoc" id="trdoc_1">
                                        <td class="doccount hidden">1</td>
                                        <td><input name="doc_evi[]" type="file" id="doc_evi" class="form-control clsInput file_input" /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="input-field col s6 m6">
                            <button type="submit" name="savebriefing" id="savebriefing" class="btn waves-effect waves-green align-right">Save</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
<!--Content Div for all Page End -->
</div>


<script>
    function getSubCat(id) {

        if (id == 'General Concerns' || id == 'Third Party Issue') {

            $('#comp_sub_category').html(
                `<option value="Supervisor Behaviour - Abusive/Loud/Arrogant/Rude">Supervisor Behaviour - Abusive/Loud/Arrogant/Rude</option>
                  <option value="Women Harassment">Women Harassment</option>
                  <option value="Fight in Office Premises">Fight in Office Premises</option>
                  <option value="Monetary Favour for hiring">Monetary Favour for hiring </option>
                  <option value="Personal favour for clearing certification">Personal favour for clearing certification</option>
                  <option value="sup_beh">Biasness basis personal relationship</option>
                  <option value="Biasness basis personal relationship">Body Shaming</option>
                  <option value="Passing Dirty comments">Passing Dirty comments</option>
                  <option value="Alcohol">Alcohol</option>
                  <option value="Salary Not Credited / Deductions">Salary Not Credited / Deductions</option>
                  <option value="Termination /Relieving/ Resignation">Termination /Relieving/ Resignation</option>
                  <option value="Missing personal items - mobile phones /bags">Missing personal items - mobile phones /bags</option>
                  <option value="Fraud Cases">Fraud Cases</option>`
            )

        }

        if (id == 'Police Complaint') {

            $('#comp_sub_category').html(
                `<option value="Supervisor Behaviour">Supervisor Behaviour</option>
                 <option value="Missing from home">Missing from home</option>
                 <option value="Fights due to personal reasons">Fights due to personal reasons</option>
                 <option value="Abscond Cases - Salary Not Credited">Abscond Cases - Salary Not Credited </option>
                 <option value="Working on Voting Days / National Holidays">Working on Voting Days / National Holidays</option>
                 <option value="Termination">Termination</option>
                 <option value="Road Accidents">Road Accidents</option>
                 <option value="Suicide">Suicide</option>
                 <option value="Criminal Cases">Criminal Cases</option>`
            )

        }


        if (id == 'Medical Concerns') {

            $('#comp_sub_category').html(
                `<option value="On-Site Medical Concerns - Taken to hospital">On-Site Medical Concerns - Taken to hospital</option>
                 <option value="Any casualty">Any casualty</option>`
            )

        }


        if (id == 'Travel Related') {

            $('#comp_sub_category').html(
                `<option value="Women Safety & Hygiene">Women Safety & Hygiene</option>
                 <option value="Local Travel Expenses excluding flight /train bookings">Local Travel Expenses excluding flight /train bookings</option>
                 <option value="Flight missed due to personal reasons">Flight missed due to personal reasons</option>
                 <option value="Accommodation /Guest House related concerns">Accommodation /Guest House related concerns</option>
                 <option value="Extra Baggage Allowance">Extra Baggage Allowance</option>
                 <option value="Accidents - Air/Road">Accidents - Air/Road</option>`
            )

        }


        if (id == 'Infrastructural') {

            $('#comp_sub_category').html(
                `<option value="Ceiling fall down">Ceiling fall down</option>
                 <option value="Glass Door / Board broken">Glass Door / Board broken</option>
                 <option value="Others">Others</option>`
            )

        }

        if (id == 'NA') {

            $('#comp_sub_category').html(
                `<option >-----select------</option>`
            )

        }
    }
    $('#case_rep_date,#incident_date').datetimepicker({
        timepicker: false,
        format: 'Y-m-d'
    });
</script>

<script>
    $(document).ready(function() {
        $('#savebriefing').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            var fileExtension = ['jpeg', 'jpg', 'png', 'pdf', 'msg'];


            if ($('#case_rep_source').val().trim() == '') {
                $('#case_rep_source').addClass('has-error');
                if ($('#spancase_rep_source').size() == 0) {
                    $('<span id="spancase_rep_source" class="help-block">Required *</span>').insertAfter('#case_rep_source');
                }
                validate = 1;
            }

            if ($('#case_rep_date').val().trim() == '') {
                $('#case_rep_date').addClass('has-error');
                if ($('#spancase_rep_date').size() == 0) {
                    $('<span id="spancase_rep_date" class="help-block">Required *</span>').insertAfter('#case_rep_date');
                }
                validate = 1;
            }

            if ($('#case_rep_by').val().trim() == '') {
                $('#case_rep_by').addClass('has-error');
                if ($('#spancase_rep_by').size() == 0) {
                    $('<span id="spancase_rep_by" class="help-block">Required *</span>').insertAfter('#case_rep_by');
                }
                validate = 1;
            }

            if ($('#incident_date').val().trim() == '') {
                $('#incident_date').addClass('has-error');
                if ($('#spanincident_date').size() == 0) {
                    $('<span id="spanincident_date" class="help-block">Required *</span>').insertAfter('#incident_date');
                }
                validate = 1;
            }

            if ($('#comp_from').val().trim() == '') {
                $('#comp_from').addClass('has-error');
                if ($('#spancomp_from').size() == 0) {
                    $('<span id="spancomp_from" class="help-block">Required *</span>').insertAfter('#comp_from');
                }
                validate = 1;
            }

            if ($('#loaction').val().trim() == '') {
                $('#loaction').addClass('has-error');
                if ($('#spanloaction').size() == 0) {
                    $('<span id="spanloaction" class="help-block">Required *</span>').insertAfter('#loaction');
                }
                validate = 1;
            }

            if ($('#emp_status').val().trim() == '') {
                $('#emp_status').addClass('has-error');
                if ($('#spanemp_status').size() == 0) {
                    $('<span id="spanemp_status" class="help-block">Required *</span>').insertAfter('#emp_status');
                }
                validate = 1;
            }

            if ($('#process_name').val().trim() == '') {
                $('#process_name').addClass('has-error');
                if ($('#spanprocess_name').size() == 0) {
                    $('<span id="spanprocess_name" class="help-block">Required *</span>').insertAfter('#process_name');
                }
                validate = 1;
            }

            // if ($('#process_detail').val().trim() == '') {
            //     $('#process_detail').addClass('has-error');
            //     if ($('#spanprocess_detail').size() == 0) {
            //         $('<span id="spanprocess_detail" class="help-block">Required *</span>').insertAfter('#process_detail');
            //     }
            //     validate = 1;
            // }

            // if ($('#comp_against').val().trim() == '') {
            //     $('#comp_against').addClass('has-error');
            //     if ($('#spancomp_against').size() == 0) {
            //         $('<span id="spancomp_against" class="help-block">Required *</span>').insertAfter('#comp_against');
            //     }
            //     validate = 1;
            // }

            if ($('#comp_category').val().trim() == 'NA') {
                $('#comp_category').addClass('has-error');
                if ($('#spancomp_category').size() == 0) {
                    $('<span id="spancomp_category" class="help-block">Required *</span>').insertAfter('#comp_category');
                }
                validate = 1;
            }

            if ($('#comp_sub_category').val().trim() == 'NA') {
                $('#comp_sub_category').addClass('has-error');
                if ($('#spancomp_sub_category').size() == 0) {
                    $('<span id="spancomp_sub_category" class="help-block">Required *</span>').insertAfter('#comp_sub_category');
                }
                validate = 1;
            }

            // if ($('#case_detail').val().trim() == '') {
            //     $('#case_detail').addClass('has-error');
            //     if ($('#spancase_detail').size() == 0) {
            //         $('<span id="spancase_detail" class="help-block">Required *</span>').insertAfter('#case_detail');
            //     }
            //     validate = 1;
            // }

            if ($('#investigation').val().trim() == '') {
                $('#investigation').addClass('has-error');
                if ($('#spaninvestigation').size() == 0) {
                    $('<span id="spaninvestigation" class="help-block">Required *</span>').insertAfter('#investigation');
                }
                validate = 1;
            }

            if ($('#conclusion').val().trim() == '') {
                $('#conclusion').addClass('has-error');
                if ($('#spanconclusion').size() == 0) {
                    $('<span id="spanconclusion" class="help-block">Required *</span>').insertAfter('#conclusion');
                }
                validate = 1;
            }

            if ($('#doc_evi').val().trim() == '') {
                $('#doc_evi').addClass('has-error');
                if ($('#spandoc_evi').size() == 0) {
                    $('<span id="spandoc_evi" class="help-block">Required *</span>').insertAfter('#doc_evi');
                }
                validate = 1;
            }

            if ($('#call_status').val().trim() == '') {
                $('#call_status').addClass('has-error');
                if ($('#spancall_status').size() == 0) {
                    $('<span id="spancall_status" class="help-block">Required *</span>').insertAfter('#call_status');
                }
                validate = 1;
            }

            if ($('#remark').val().trim() == '') {
                $('#remark').addClass('has-error');
                if ($('#spanremark').size() == 0) {
                    $('<span id="spanremark" class="help-block">Required *</span>').insertAfter('#remark');
                }
                validate = 1;
            }

            if (validate == 1) {
                //alert('1');
                return false;
            }
        });

        $('#find_case_by').click(function() {
            var term = $('#case_rep_by').val();

            var resp_data_format = "";
            $.ajax({
                url: "../Controller/autoCompleteForComplaint.php?term=" + term,
                method: "get",
                dataType: "json",
                success: function(response) {
                    if (response.status == '0') {
                        $(function() {
                            toastr.error(response.msg)
                        })
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            resp_data_format = resp_data_format + "<li class='select_data'>" + response[i] + "</li>";
                        };
                    }
                    $("#data-case_rep_by").html(resp_data_format);

                }
            });
        });

        $(document).on("click", ".select_data", function() {
            var seldata = $(this).html();
            $('#case_rep_by').val(seldata);
            $('#data-case_rep_by').html('');
            var empid = $('#case_rep_by').val().substr($('#case_rep_by').val().lastIndexOf("(") + 1, ($('#case_rep_by').val().lastIndexOf(")") - $('#case_rep_by').val().lastIndexOf("(")) - 1);

            $('iframe[name=irsc]').contents().find("#source1").find('option').remove();
            $('iframe[name=irsc]').contents().find("#target1").find('option').remove();
            $("input[name='report[]']:checkbox").prop('checked', false);
            //$('iframe[name=irsc]').contents().find("#source1").append('<option value="Masood">Masood</option>' );

        });

        $('#find_com_from').click(function() {
            var term = $('#comp_from').val();

            var resp_data_format = "";
            $.ajax({
                url: "../Controller/autoCompleteForComplaint.php?term=" + term,
                method: "get",
                dataType: "json",
                success: function(response) {
                    // alert(response);
                    if (response.status == '0') {
                        // alert(response.msg);
                        $(function() {
                            toastr.error(response.msg)
                        })
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            resp_data_format = resp_data_format + `<li class='select_data2' onclick='get_comp_data("${response[i]}")'> ${response[i]}</li>`;
                        };
                        $("#data-comp_from").html(resp_data_format);

                    }
                }
            });
        });

        $(document).on("click", ".select_data2", function() {
            var seldata2 = $(this).html();
            $('#comp_from').val(seldata2);
            $('#data-comp_from').html('');
            var empid2 = $('#comp_from').val().substr($('#comp_from').val().lastIndexOf("(") + 1, ($('#comp_from').val().lastIndexOf(")") - $('#comp_from').val().lastIndexOf("(")) - 1);

            $('iframe[name=irsc]').contents().find("#source1").find('option').remove();
            $('iframe[name=irsc]').contents().find("#target1").find('option').remove();
            $("input[name='report[]']:checkbox").prop('checked', false);
            //$('iframe[name=irsc]').contents().find("#source1").append('<option value="Masood">Masood</option>' );

        });




        $('#find_comp_ag').click(function() {
            var term = $('#comp_against').val();

            var resp_data_format = "";
            $.ajax({
                url: "../Controller/autoCompleteForComplaint.php?term=" + term,
                method: "get",
                dataType: "json",
                success: function(response) {
                    // alert(response);
                    if (response.status == '0') {
                        // alert(response.msg);
                        $(function() {
                            toastr.error(response.msg)
                        })
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            resp_data_format = resp_data_format + "<li class='select_data3' >" + response[i] + "</li>";
                        };
                        $("#data-comp_against").html(resp_data_format);

                    }
                }
            });
        });

        $(document).on("click", ".select_data3", function() {
            var seldata3 = $(this).html();
            $('#comp_against').val(seldata3);
            $('#data-comp_against').html('');
            var empid3 = $('#comp_against').val().substr($('#comp_against').val().lastIndexOf("(") + 1, ($('#comp_against').val().lastIndexOf(")") - $('#comp_against').val().lastIndexOf("(")) - 1);

            $('iframe[name=irsc]').contents().find("#source1").find('option').remove();
            $('iframe[name=irsc]').contents().find("#target1").find('option').remove();
            $("input[name='report[]']:checkbox").prop('checked', false);
            //$('iframe[name=irsc]').contents().find("#source1").append('<option value="Masood">Masood</option>' );
        });
    });


    function get_comp_data(id) {
        $.ajax({
            type: "get",
            url: "../Controller/get_dataFor_complaint.php?empid=" + id,
            dataType: "json",
            success: function(response) {
                ///<option value="NA">----Select----</option>
                // console.log(response);
                $('#loaction').val(response[0].location);
                $('#emp_status').val(response[0].emp_status);
                $('#process_name').val(response[0].Process);
                // $('#process_detail').val(response[0].Process_details);
                const myArray_Process_details_Names = response[0].Process_details_Names.split(" | ");
                const myArray_Process_details = response[0].Process_details.split(" | ");
                $('#process_detail_VH').val(myArray_Process_details_Names[0])
                $('#process_detail_AH').val(myArray_Process_details_Names[1])
                $('#process_detail_OH').val(myArray_Process_details_Names[2])

                $('#process_detail_VH_id').val(myArray_Process_details_Names[0])
                $('#process_detail_AH_id').val(myArray_Process_details_Names[1])
                $('#process_detail_OH_id').val(myArray_Process_details_Names[2])

                // console.log(response[0].location);
            }
        });


    }
</script>
<script>
    $(document).ready(function() {

        $('#doc_child').val($(".trdoc").length);


        $('#btn_docAdd').click(function() {
            $count = $(".trdoc").length;
            $id = "trdoc_" + parseInt($count + 1);
            $('#doc_child').val(parseInt($count + 1));
            $tr = $("#trdoc_1").clone().attr("id", $id);
            $('#childtable tbody').append($tr);
            $tr.children("td:first-child").html(parseInt($count + 1));
            $tr.children("td:nth-child(2)").children("input").attr({
                "id": "doc_evi" + parseInt($count + 1),
                "name": "doc_evi[]"
            }).val('');

        });
        $('#btnDoccan').click(function() {
            $count = $(".trdoc").length;
            if ($count > 1) {
                $('#childtable tbody').children("tr:last-child").remove();
                $('#doc_child').val(parseInt($count - 1));
            }

        });
    });
</script>


<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>