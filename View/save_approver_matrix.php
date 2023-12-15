<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Only for user type administrator
if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE10091236' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE01145570') {
    // proceed further
} else {
    $location = URL . 'Error';
    header("Location: $location");
    exit();
}
// Global variable used in Page Cycle
$alert_msg = '';


$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_Client_Save'])) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $location = cleanUserInput($_POST['location1']);
        $createBy = clean($_SESSION['__user_logid']);

        $exception = substr(cleanUserInput($_POST['excep_app']), strpos(cleanUserInput($_POST['excep_app']), "(") + 1, (strpos(cleanUserInput($_POST['excep_app']), ")")) - (strpos(cleanUserInput($_POST['excep_app']), "(") + 1));

        $downtime_quality = substr(cleanUserInput($_POST['downtime_quality']), strpos(cleanUserInput($_POST['downtime_quality']), "(") + 1, (strpos(cleanUserInput($_POST['downtime_quality']), ")")) - (strpos(cleanUserInput($_POST['downtime_quality']), "(") + 1));

        $downtime_training = substr(cleanUserInput($_POST['downtime_training']), strpos(cleanUserInput($_POST['downtime_training']), "(") + 1, (strpos(cleanUserInput($_POST['downtime_training']), ")")) - (strpos(cleanUserInput($_POST['downtime_training']), "(") + 1));

        $downtime_ops = substr(cleanUserInput($_POST['downtime_ops']), strpos(cleanUserInput($_POST['downtime_ops']), "(") + 1, (strpos(cleanUserInput($_POST['downtime_ops']), ")")) - (strpos(cleanUserInput($_POST['downtime_ops']), "(") + 1));

        $leave1 = substr(cleanUserInput($_POST['leave1']), strpos(cleanUserInput($_POST['leave1']), "(") + 1, (strpos(cleanUserInput($_POST['leave1']), ")")) - (strpos(cleanUserInput($_POST['leave1']), "(") + 1));

        $leave2 = substr(cleanUserInput($_POST['leave2']), strpos(cleanUserInput($_POST['leave2']), "(") + 1, (strpos(cleanUserInput($_POST['leave2']), ")")) - (strpos(cleanUserInput($_POST['leave2']), "(") + 1));

        if (isset($_POST['HRID']) and trim($_POST['HRID']) != "") {
            $HRID = trim(cleanUserInput($_POST['HRID']));
        }
        if (isset($_POST['ITID']) and trim($_POST['ITID']) != "") {
            $ITID = trim(cleanUserInput($_POST['ITID']));
        }
        if (isset($_POST['ReportsTo']) and trim($_POST['ReportsTo']) != "") {
            $ReportsTo = trim(cleanUserInput($_POST['ReportsTo']));
        }
        $cm_id = cleanUserInput($_POST['sub_process1']);

        $Insert = 'CALL update_approver_matrix("' . $createBy . '","' . $location . '","' . $cm_id . '","' . $exception . '","' . $downtime_quality . '","' . $downtime_training . '","' . $downtime_ops . '","' . $leave1 . '","' . $leave2 . '","' . $HRID . '","' . $ITID . '","' . $ReportsTo . '")';
        // die;
        $resCount = $myDB->rawQuery($Insert);
        $mysql_error = $myDB->getLastError();
        if (empty($mysql_error)) {
            echo "<script>$(function(){ toastr.success('Client Save Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){ toastr.error('Client not Added.'); }); </script>";
        }
    }
}
// echo 'sdgsdfg';
?>

<style>
    .error {
        color: red;
    }

    #data-container,
    #data-container2,
    #data-container3,
    #data-container4,
    #data-container5,
    #data-container6 {
        display: block;
        background: #2a3f54;

        max-height: 250px;
        overflow-y: auto;
        z-index: 9999999;
        position: absolute;
        width: 100%;

    }

    #data-container li,
    #data-container2 li,
    #data-container3 li,
    #data-container4 li,
    #data-container5 li,
    #data-container6 li {
        list-style: none;
        padding: 5px;
        border-bottom: 1px solid #fff;
        color: #fff;
    }

    #data-container li:hover,
    #data-container2 li:hover,
    #data-container3 li:hover,
    #data-container4 li:hover,
    #data-container5 li:hover,
    #data-container6 li:hover {
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
<script>
    //contain load event for data table and other importent rand required trigger event and searches if any
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            scrollX: '100%',
            "iDisplayLength": 25,
            scrollCollapse: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
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
                }
                /*,'copy'*/
                , 'pageLength'
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">New Client</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>New Client</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">
                <!--Form element model popup start-->
                <div id="myModal_content" class="modal">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <h4 class="col s12 m12 model-h4">New Client</h4>
                        <div class="modal-body">
                            <?php
                            $_SESSION["token"] = csrfToken();
                            ?>
                            <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                            <div class=" bylocation">
                                <div class="input-field col s6 m6 8">
                                    <select class="" id="location1" name="location1" title="Select Location">
                                        <option id="location1" value="NA">Select Location</option>
                                        <?php
                                        $sqlBy = 'select id,location from location_master';
                                        $sql = $conn->prepare($sqlBy);
                                        $sql->execute();
                                        $resultBy = $sql->get_result();

                                        foreach ($resultBy as $key => $value) { ?>
                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['location']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <label title="" for="loction" class="active-drop-down active">Location</label>
                                </div>
                            </div>
                            <div class=" byclient">
                                <div class="input-field col s6 m6 8">
                                    <div class="form-group">
                                        <select class="form-control" name="client_name1" id="client_name1">
                                            <option value="NA">Select Client</option>
                                        </select>
                                        <label title="Select Client Name" for="client_name" class="active-drop-down active">Client</label>
                                    </div>
                                </div>
                            </div>
                            <div class=" byprocess">
                                <div class="input-field col s6 m6 8">
                                    <div class="form-group">
                                        <select class="form-control" name="process1" id="process1">
                                            <option value="NA">Select Process</option>
                                        </select>
                                        <label title="Select Process" for="process" class="active-drop-down active">Process</label>
                                    </div>
                                </div>
                            </div>
                            <div class=" bysubprocess">
                                <div class="input-field col s6 m6 8">
                                    <div class="form-group">
                                        <select class="form-control" name="sub_process1" id="sub_process1">
                                            <option value="NA">Select Sub Process</option>
                                        </select>
                                        <label title="Select Sub_process" for="sub_process" class="active-drop-down active">Sub Process</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-field col s6 m6" id="exception">
                                <input type="text" id="excep_app" name="excep_app" required />
                                <label for="excep_app">Exception Approver</label>
                                <div id="data-container"></div>
                            </div>
                            <div class="input-field col s6 m6">
                                <input type="text" id="leave1" name="leave1" required />
                                <label for="leave1">Leave1 Approver</label>
                                <div id="data-container2"></div>
                            </div>
                            <div class="input-field col s6 m6">
                                <input type="text" id="leave2" name="leave2" required />
                                <label for="leave2">Leave2 Approver</label>
                                <div id="data-container3"></div>
                            </div>
                            <div class="input-field col s6 m6">
                                <input type="text" id="downtime_quality" name="downtime_quality" required />
                                <label for="downtime_quality">Downtime Quality Approver</label>
                                <div id="data-container4"></div>
                            </div>
                            <div class="input-field col s6 m6">
                                <input type="text" id="downtime_training" name="downtime_training" required />
                                <label for="downtime_training">Downtime Training Approver</label>
                                <div id="data-container5"></div>
                            </div>
                            <div class="input-field col s6 m6">
                                <input type="text" id="downtime_ops" name="downtime_ops" required />
                                <label for="downtime_ops">Downtime ops Approver</label>
                                <div id="data-container6"></div>
                            </div>
                            <div class="col s12 m12">
                                <div class="input-field col s4 m4">
                                    <input type="text" id="ITID" name="ITID" maxlength="12" required />
                                    <label for="ITID">IT</label>
                                </div>
                                <div class="input-field col s4 m4">
                                    <input type="text" id="HRID" name="HRID" maxlength="12" required />
                                    <label for="HRID">HR</label>
                                </div>
                                <div class="input-field col s4 m4">
                                    <input type="text" id="ReportsTo" name="ReportsTo" required maxlength="12" />
                                    <label for="ReportsTo">Reports To</label>
                                </div>
                            </div>
                            <div class="input-field col s12 m12 right-align">
                                <input type="hidden" class="form-control hidden" id="hid_Client_ID" name="hid_Client_ID" />
                                <button type="submit" name="btn_Client_Save" id="btn_Client_Save" class="btn waves-effect waves-green">Update</button>
                                <button type="button" name="btn_Client_Can" id="btn_Client_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="pnlTable">
                    <?php
                    $sqlConnect = 'select client_id,client_master.client_name,new_client_master.process,new_client_master.sub_process,new_client_master.cm_id,new_client_master.location,t1.location as loc, excep_spoc,leave1_empid,leave2_empid, QualityID, TrainingID, OpsID, HRID, ITID, ReportsTo from new_client_master inner join client_master on client_master.client_id = new_client_master.client_name join location_master t1 on new_client_master.location=t1.id left outer join downtimereqid1 dt on new_client_master.cm_id=dt.cm_id where new_client_master.cm_id not in (select cm_id from client_status_master)';
                    $myDB = new MysqliDb();
                    $result = $myDB->query($sqlConnect);
                    if ($result) { ?>
                        <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>cm_id</th>
                                    <th class="hidden">Client ID</th>
                                    <th class="hidden">Loc</th>
                                    <th>Location</th>
                                    <th>Client</th>
                                    <th>Process</th>
                                    <th>Sub Process</th>
                                    <th>Exception Approver</th>
                                    <th>Leave1 Approver</th>
                                    <th>Leave2 Approver</th>
                                    <th>Downtime Quality Approver</th>
                                    <th>Downtime Training Approver</th>
                                    <th>Downtime ops Approver</th>
                                    <th>IT</th>
                                    <th>HR</th>
                                    <th>Reports To</th>
                                    <th>Manage Client</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $key => $value) {
                                    echo '<tr>';
                                    echo '<td cm_id">' . $value['cm_id'] . '</td>';
                                    echo '<td class="hidden client_id">' . $value['client_id'] . '</td>';
                                    echo '<td class="hidden location">' . $value['location'] . '</td>';
                                    echo '<td class="loc">' . $value['loc'] . '</td>';

                                    echo '<td class="client_name">' . $value['client_name'] . '</td>';
                                    echo '<td class="process">' . $value['process'] . '</td>';
                                    echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
                                    echo '<td class="excep_spoc">' . $value['excep_spoc'] . '</td>';
                                    echo '<td class="leave1_empid">' . $value['leave1_empid'] . '</td>';
                                    echo '<td class="leave2_empid">' . $value['leave2_empid'] . '</td>';
                                    echo '<td class="QualityID">' . $value['QualityID'] . '</td>';
                                    echo '<td class="TrainingID">' . $value['TrainingID'] . '</td>';
                                    echo '<td class="OpsID">' . $value['OpsID'] . '</td>';
                                    echo '<td class="ITID">' . $value['ITID'] . '</td>';
                                    echo '<td class="HRID">' . $value['HRID'] . '</td>';

                                    echo '<td class="ReportsTo">' . $value['ReportsTo'] . '</td>';

                                    echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['cm_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    }
                    ?>
                    <!--Reprot / Data Table End -->
                </div>
                <!--Form container End -->
            </div>
            <!--Sub Main Div for all Page End -->
        </div>
        <!--Main Div for all Page End -->
    </div>
    <!--Content Div for all Page End -->
</div>

<script>
    $(document).ready(function() {
        //Model Assigned and initiation code on document load
        $('.modal').modal({
            onOpenStart: function(elm) {

            },
            onCloseEnd: function(elm) {
                $('#btn_Department_Can').trigger("click");
            }
        });
        // This code for cancel button trigger click and also for model close
        $('#btn_Client_Can').on('click', function() {
            $('#location1').val('NA');
            $('#hid_Client_ID').val('');
            // $("data-container").hide();
            // $('#exception').removeClass("hidden");
            // ("#exception").onkeyup = null;
            $('#btn_Client_Save').removeClass('hidden');
            //$('#btn_Client_Can').addClass('hidden');
            // This code for remove error span from input text on model close and cancel
            $(".has-error").each(function() {
                if ($(this).hasClass("has-error")) {
                    $(this).removeClass("has-error");
                    $(this).next("span.help-block").remove();
                    if ($(this).is('select')) {
                        $(this).parent('.select-wrapper').find("span.help-block").remove();
                    }
                    if ($(this).hasClass('select-dropdown')) {
                        $(this).parent('.select-wrapper').find("span.help-block").remove();
                    }
                }
            });
            // This code active label on value assign when any event trigger and value assign by javascript code.
            $("#myModal_content input,#myModal_content textarea").each(function(index, element) {
                if ($(element).val().length > 0) {
                    $(this).siblings('label, i').addClass('active');
                } else {
                    $(this).siblings('label, i').removeClass('active');
                }
            });
        });

        // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
        $('#btn_Client_Save').on('click', function() {
            var validate = 0;
            var alert_msg = '';

            if ($('#location1').val() == 'NA') {
                $('#location1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanlocation').size() == 0) {
                    $('<span id="spanlocation" class="help-block">Required *</span>').insertAfter('#location1');
                }
                validate = 1;
            }
            if ($('#client_name1').val() == 'NA') {
                $('#client_name1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanclient_name').size() == 0) {
                    $('<span id="spanclient_name" class="help-block">Required *</span>').insertAfter('#client_name1');
                }
                validate = 1;
            }
            if ($('#process1').val() == 'NA') {
                $('#process1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanprocess1').size() == 0) {
                    $('<span id="spanprocess1" class="help-block">Required *</span>').insertAfter('#process1');
                }
                validate = 1;
            }
            if ($('#sub_process1').val() == 'NA') {
                $('#sub_process1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spansub_process1').size() == 0) {
                    $('<span id="spansub_process1" class="help-block">Required *</span>').insertAfter('#sub_process1');
                }
                validate = 1;
            }
            if ($('#excep_app').val() == '') {
                $('#excep_app').addClass("has-error");
                if ($('#spanexcep_app').size() == 0) {
                    $('<span id="spanexcep_app" class="help-block">Required *</span>').insertAfter('#excep_app');
                }
                validate = 1;
            }
            if ($('#downtime_quality').val() == '') {
                $('#downtime_quality').addClass("has-error");
                if ($('#spandowntime_quality').size() == 0) {
                    $('<span id="spandowntime_quality" class="help-block">Required *</span>').insertAfter('#downtime_quality');
                }
                validate = 1;
            }
            if ($('#downtime_training').val() == '') {
                $('#downtime_training').addClass("has-error");
                if ($('#spandowntime_training').size() == 0) {
                    $('<span id="spandowntime_training" class="help-block">Required *</span>').insertAfter('#downtime_training');
                }
                validate = 1;
            }
            if ($('#downtime_ops').val() == '') {
                $('#downtime_ops').addClass("has-error");
                if ($('#spandowntime_ops').size() == 0) {
                    $('<span id="spandowntime_ops" class="help-block">Required *</span>').insertAfter('#downtime_ops');
                }
                validate = 1;
            }
            if ($('#leave1').val() == '') {
                $('#leave1').addClass("has-error");
                if ($('#spanleave1').size() == 0) {
                    $('<span id="spanleave1" class="help-block">Required *</span>').insertAfter('#leave1');
                }
                validate = 1;
            }
            if ($('#leave2').val() == '') {
                $('#leave2').addClass("has-error");
                if ($('#spanleave2').size() == 0) {
                    $('<span id="spanleave2" class="help-block">Required *</span>').insertAfter('#leave2');
                }
                validate = 1;
            }
            if ($('#ITID').val() == '') {
                $('#ITID').addClass("has-error");
                if ($('#spanITID').size() == 0) {
                    $('<span id="spanITID" class="help-block">Required *</span>').insertAfter('#ITID');
                }
                validate = 1;
            }
            if ($('#HRID').val() == '') {
                $('#HRID').addClass("has-error");
                if ($('#spanHRID').size() == 0) {
                    $('<span id="spanHRID" class="help-block">Required *</span>').insertAfter('#HRID');
                }
                validate = 1;
            }
            if ($('#ReportsTo').val() == '') {
                $('#ReportsTo').addClass("has-error");
                if ($('#spanReportsTo').size() == 0) {
                    $('<span id="spanReportsTo" class="help-block">Required *</span>').insertAfter('#ReportsTo');
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

        // This code for remove error span from input text on model close and cancel
        $(".has-error").each(function() {
            if ($(this).hasClass("has-error")) {
                $(this).removeClass("has-error");
                $(this).next("span.help-block").remove();
                if ($(this).is('select')) {
                    $(this).parent('.select-wrapper').find("span.help-block").remove();
                }
                if ($(this).hasClass('select-dropdown')) {
                    $(this).parent('.select-wrapper').find("span.help-block").remove();
                }

            }
        });
        // This code for remove error span from all element contain .has-error class on listed events
        $(document).on("click blur focus change", ".has-error", function() {
            $(".has-error").each(function() {
                if ($(this).hasClass("has-error")) {
                    $(this).removeClass("has-error");
                    $(this).next("span.help-block").remove();
                    if ($(this).is('select')) {
                        $(this).parent('.select-wrapper').find("span.help-block").remove();
                    }
                    if ($(this).hasClass('select-dropdown')) {
                        $(this).parent('.select-wrapper').find("span.help-block").remove();
                    }
                }
            });
        });
    });
    // This code for trigger edit on all data table also trigger model open on a Model ID
    function EditData(el) {
        var cmid = (el.id);
        var tr = $(el).closest('tr');
        var client_id = tr.find('.client_id').text();
        var location = tr.find('.location').text();
        var client_name = tr.find('.client_name').text();
        var process = tr.find('.process').text();
        var sub_process = tr.find('.sub_process').text();
        var excep_spoc = tr.find('.excep_spoc').text();
        var leave1_empid = tr.find('.leave1_empid').text();
        var leave2_empid = tr.find('.leave2_empid').text();
        // alert(leave2_empid);
        var QualityID = tr.find('.QualityID').text();
        var TrainingID = tr.find('.TrainingID').text();
        var OpsID = tr.find('.OpsID').text();
        var HRID = tr.find('.HRID').text();
        var ITID = tr.find('.ITID').text();
        var ReportsTo = tr.find('.ReportsTo').text();
        //alert(client_id);
        getData(location, client_id, process, cmid);
        $('#location1').val(location);
        $('#client_name1').val(client_id);
        $('#process1').val(process);
        $('#sub_process1').val(sub_process);
        $('#excep_app').val(excep_spoc);
        $('#leave1').val(leave1_empid);
        $('#leave2').val(leave2_empid);
        $('#downtime_quality').val(QualityID);
        $('#downtime_training').val(TrainingID);
        $('#downtime_ops').val(OpsID);
        $('#HRID').val(HRID);
        $('#ITID').val(ITID);
        $('#ReportsTo').val(ReportsTo);

        $('#modal-content').modal('open');
        $("#modal-content input,#modal-content textarea").each(function(index, element) {
            if ($(element).val().length > 0) {
                $(this).siblings('label, i').addClass('active');
            } else {
                $(this).siblings('label, i').removeClass('active');
            }

        });
        $('select').formSelect();
        $('#myModal_content').modal('open');
        $("#myModal_content input,#myModal_content textarea").each(function(index, element) {
            if ($(element).val().length > 0) {
                $(this).siblings('label, i').addClass('active');
            } else {
                $(this).siblings('label, i').removeClass('active');
            }

        });
    }


    function getData(location1, client_name1, process1, sub_process1) {
        // alert(location2);
        //var location1 = $("#location1").val();
        $.ajax({
            url: "../Controller/get_editData.php?location1=" + location1 + "&client_name1=" + client_name1 + "&process1=" + process1 + "&sub_process1=" + sub_process1,
            success: function(result) {
                //alert(result);
                var array = result.split("||NA||");
                $("#client_name1").html(array[0]);
                $('select').formSelect();
                $("#process1").html(array[1]);
                $('select').formSelect();
                $("#sub_process1").html(array[2]);
                $('select').formSelect();
            }
        });
        $.ajax({
            url: '../Controller/get_erspoc.php',
            type: 'GET',
            data: {
                location1: location1,
                client_name1: client_name1,
                process1: process1,
                sub_process1: sub_process1,
            },
            dataType: 'json',
            success: function(response) {
                // alert(response);
                $("#excep_app").val(response.excep_app);
                $("#downtime_quality").val(response.downtime_quality);
                $("#downtime_training").val(response.downtime_training);
                $("#downtime_ops").val(response.downtime_ops);
                $("#leave1").val(response.leave1);
                $("#leave2").val(response.leave2);
                $("#ITID").val(response.ITID);
                $("#HRID").val(response.HRID);
                $("#ReportsTo").val(response.ReportsTo);
            }
        });
    }

    $("#location1").change(function() {
        var location1 = $(this).val();
        $.ajax({
            url: '../Controller/get_client.php',
            type: 'GET',
            data: {
                location1: location1,
            },
            dataType: 'json',
            success: function(response) {
                //alert(response);
                $("#client_name1").html(response.client_name1);
                $("#process1").html(response.process1);
                $("#sub_process1").html(response.sub_process1);

            }
        });
    });
    $("#client_name1").change(function() {
        var location1 = $("#location1").val();
        var client_name1 = $(this).val();
        $.ajax({
            url: '../Controller/get_clientprocess.php',
            type: 'GET',
            data: {
                location1: location1,
                client_name1: client_name1,
            },
            dataType: 'json',
            success: function(response) {
                //   alert(response);
                $("#process1").html(response.process1);
                $("#sub_process1").html(response.sub_process1);
            }
        });
    });
    $("#process1").change(function() {
        var location1 = $("#location1").val();
        var client_name1 = $("#client_name1").val();
        var process1 = $(this).val();
        $.ajax({
            url: '../Controller/get_subprocess.php',
            type: 'GET',
            data: {
                client_name1: client_name1,
                location1: location1,
                process1: process1,
            },
            dataType: 'json',
            success: function(response) {
                //alert(response);
                $("#sub_process1").html(response.sub_process1);
            }
        });
    });
    $("#sub_process1").change(function() {
        var location1 = $("#location1").val();
        var client_name1 = $("#client_name1").val();
        var process1 = $("#process1").val();
        var sub_process1 = $(this).val();
        $.ajax({
            url: '../Controller/get_erspoc.php',
            type: 'GET',
            data: {
                location1: location1,
                client_name1: client_name1,
                process1: process1,
                sub_process1: sub_process1,
            },
            dataType: 'json',
            success: function(response) {
                // alert(response);
                $("#excep_app").val(response.excep_app);
                $("#downtime_quality").val(response.downtime_quality);
                $("#downtime_training").val(response.downtime_training);
                $("#downtime_ops").val(response.downtime_ops);
                $("#leave1").val(response.leave1);
                $("#leave2").val(response.leave2);
                $("#ITID").val(response.ITID);
                $("#HRID").val(response.HRID);
                $("#ReportsTo").val(response.ReportsTo);
            }
        });
    });



    $('#excep_app').keyup(function() {
        var term = $(this).val();
        var resp_data_format = "";
        $.ajax({
            url: "../Controller/autocomplete_employee_active.php",
            data: {
                term: term,
            },
            method: "get",
            dataType: "json",
            success: function(response) {
                for (var i = 0; i < response.length; i++) {
                    resp_data_format = resp_data_format + "<li class='select_country'>" + response[i] + "</li>";
                };
                $("#data-container").html(resp_data_format);
            }
        });
    });
    $('#leave1').keyup(function() {
        var term = $(this).val();
        var resp_data_format2 = "";
        $.ajax({
            url: "../Controller/autocomplete_employee_active.php",
            data: {
                term: term,
            },
            method: "get",
            dataType: "json",
            success: function(response) {
                for (var i = 0; i < response.length; i++) {
                    resp_data_format2 = resp_data_format2 + "<li class='select_country2'>" + response[i] + "</li>";
                };

                $("#data-container2").html(resp_data_format2);
            }
        });
    });
    $('#leave2').keyup(function() {
        var term = $(this).val();
        var resp_data_format3 = "";
        $.ajax({
            url: "../Controller/autocomplete_employee_active.php",
            data: {
                term: term,
            },
            method: "get",
            dataType: "json",
            success: function(response) {
                for (var i = 0; i < response.length; i++) {
                    resp_data_format3 = resp_data_format3 + "<li class='select_country3'>" + response[i] + "</li>";
                };
                $("#data-container3").html(resp_data_format3);
            }
        });
    });
    $('#downtime_quality').keyup(function() {
        var term = $(this).val();
        var resp_data_format4 = "";
        $.ajax({
            url: "../Controller/autocomplete_employee_active.php",
            data: {
                term: term,
            },
            method: "get",
            dataType: "json",
            success: function(response) {
                for (var i = 0; i < response.length; i++) {
                    resp_data_format4 = resp_data_format4 + "<li class='select_country4'>" + response[i] + "</li>";
                };
                $("#data-container4").html(resp_data_format4);
            }
        });
    });
    $('#downtime_training').keyup(function() {
        var term = $(this).val();
        var resp_data_format5 = "";
        $.ajax({
            url: "../Controller/autocomplete_employee_active.php",
            data: {
                term: term,
            },
            method: "get",
            dataType: "json",
            success: function(response) {
                for (var i = 0; i < response.length; i++) {
                    resp_data_format5 = resp_data_format5 + "<li class='select_country5'>" + response[i] + "</li>";
                };

                $("#data-container5").html(resp_data_format5);
            }
        });
    });
    $('#downtime_ops').keyup(function() {
        var term = $(this).val();
        var resp_data_format6 = "";
        $.ajax({
            url: "../Controller/autocomplete_employee_active.php",
            data: {
                term: term,
            },
            method: "get",
            dataType: "json",
            success: function(response) {
                for (var i = 0; i < response.length; i++) {
                    resp_data_format6 = resp_data_format6 + "<li class='select_country6'>" + response[i] + "</li>";
                };
                $("#data-container6").html(resp_data_format6);
            }
        });
    });
    $(document).on("click", ".select_country", function() {
        var selected_country = $(this).html();
        $('#excep_app').val(selected_country);
        $('#data-container').html('');
    });
    $(document).on("click", ".select_country2", function() {
        var selected_country2 = $(this).html();
        $('#leave1').val(selected_country2);
        $('#data-container2').html('');
    });
    $(document).on("click", ".select_country3", function() {
        var selected_country3 = $(this).html();
        $('#leave2').val(selected_country3);
        $('#data-container3').html('');
    });
    $(document).on("click", ".select_country4", function() {
        var selected_country4 = $(this).html();
        $('#downtime_quality').val(selected_country4);
        $('#data-container4').html('');
    });
    $(document).on("click", ".select_country5", function() {
        var selected_country5 = $(this).html();
        $('#downtime_training').val(selected_country5);
        $('#data-container5').html('');
    });
    $(document).on("click", ".select_country6", function() {
        var selected_country6 = $(this).html();
        $('#downtime_ops').val(selected_country6);
        $('#data-container6').html('');
    });
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>