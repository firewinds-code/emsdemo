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
if (clean($_SESSION['__user_type']) == 'ADMINISTRATOR' || clean($_SESSION['__user_logid']) == 'CE12102224' || clean($_SESSION['__user_logid']) == 'CE111513513') {
    // proceed further
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_Client_Save'])) {
    $cm_id = (isset($_POST['txt_Client_subproc']) ? $_POST['txt_Client_subproc'] : null);
    $txt_AdminID = (isset($_POST['txt_AdminID']) ? $_POST['txt_AdminID'] : null);
    $txt_ITID = (isset($_POST['txt_ITID']) ? $_POST['txt_ITID'] : null);

    $Insert = "INSERT into new_client_master_spoc(cm_id,AdminID,ITID)values(?,?,?)";
    $insQ = $conn->prepare($Insert);
    $insQ->bind_param("sss", $cm_id, $txt_AdminID, $txt_ITID);
    $insQ->execute();
    if ($insQ->affected_rows === 1) {
        echo "<script>$(function(){ toastr.success('Client Added Successfully'); }); </script>";
    } else {
        echo "<script>$(function(){ toastr.error('Client not Added.'); }); </script>";
    }
}

// Trigger Button-Edit Click Event and Perform DB Action
if (isset($_POST['btn_Client_Edit'])) {
    $cmid = $_POST['hid_Client_ID'];
    $txt_AdminID = (isset($_POST['txt_AdminID']) ? $_POST['txt_AdminID'] : null);
    $txt_ITID = (isset($_POST['txt_ITID']) ? $_POST['txt_ITID'] : null);

    $Update = 'update new_client_master_spoc set AdminID=? ,ITID=? where cm_id=?';
    $up = $conn->prepare($Update);
    $up->bind_param("ssi", $txt_AdminID, $txt_ITID, $cmid);
    $up->execute();
    if ($up->affected_rows === 1) {
        echo "<script>$(function(){ toastr.success('Client Updated Successfully'); }); </script>";
    } else {
        echo "<script>$(function(){ toastr.error('Something is wrong Please Try Again.</code>'); }); </script>";
    }
}
?>

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
    <span id="PageTittle_span" class="hidden">Asset Spoc Details</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Asset Spoc Details <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Client"><i class="material-icons">add</i></a></h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">
                <!--Form element model popup start-->
                <div id="myModal_content" class="modal modal_big">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <h4 class="col s12 m12 model-h4">Manage Asset Spoc Details</h4>
                        <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
                            <div class="col s12 m12">
                                <div class="input-field col s4 m4">
                                    <!-- <input type="text" id="txt_location" name="txt_location" readonly /> -->
                                    <select class="" id="txt_location" name="txt_location" title="Select Location">
                                        <option id="txt_location" value="NA">Select Location</option>
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
                                    <label for="txt_location" class="active-drop-down active">Location</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select class="form-control" name="txt_Client_Name" id="txt_Client_Name">
                                        <option value="NA">Select Client</option>
                                    </select>
                                    <label for="txt_Client_Name" class="active-drop-down active">Client Name</label>
                                </div>
                                <div class="input-field col s4 m4">
                                    <select class="form-control" name="txt_Client_proc" id="txt_Client_proc">
                                        <option value="NA">Select Process</option>
                                    </select>
                                    <label for="txt_Client_proc" class="active-drop-down active">Process</label>
                                </div>
                            </div>
                            <div class="col s12 m12">
                                <div class="input-field col s4 m4">
                                    <select class="form-control" name="txt_Client_subproc" id="txt_Client_subproc">
                                        <option value="NA">Select Sub Process</option>
                                    </select>
                                    <label for="txt_Client_subproc" class="active-drop-down active">Sub Process</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select id="txt_AdminID" name="txt_AdminID" required>
                                        <option value="NA">Select AdminID</option>
                                    </select>
                                    <label for="txt_AdminID" class="active-drop-down active">AdminID</label>
                                </div>
                                <div class="input-field col s4 m4">
                                    <select id="txt_ITID" name="txt_ITID" required>
                                        <option value="NA">Select ITID</option>
                                    </select>
                                    <label for="txt_ITID" class="active-drop-down active">ITID</label>
                                </div>
                            </div>

                            <div class="input-field col s12 m12 right-align">
                                <input type="hidden" class="form-control hidden" id="hid_Client_ID" name="hid_Client_ID" />
                                <button type="submit" name="btn_Client_Save" id="btn_Client_Save" class="btn waves-effect waves-green">Add</button>
                                <button type="submit" name="btn_Client_Edit" id="btn_Client_Edit" class="btn waves-effect waves-green hidden">Save</button>
                                <button type="button" name="btn_Client_Can" id="btn_Client_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
                            </div>

                        </div>
                    </div>
                </div>
                <!--Form element model popup End-->
                <!--Reprot / Data Table start -->
                <div id="pnlTable">
                    <?php
                    $sqlConnect = 'select ns.cm_id,ns.AdminID,ns.ITID,c.client_name,n.process,n.sub_process,l.id,l.location from new_client_master_spoc as ns join new_client_master as n on n.cm_id=ns.cm_id join client_master as c on c.client_id=n.client_name join location_master as l on l.id =n.location';
                    $myDB = new MysqliDb();
                    $result = $myDB->rawQuery($sqlConnect);
                    $mysql_error = $myDB->getLastError();
                    if (empty($mysql_error)) {
                    ?>
                        <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Process</th>
                                    <th>Sub Process</th>
                                    <th>Location</th>
                                    <th>AdminID</th>
                                    <th>ITID</th>
                                    <th>Manage Client</th>
                                    <th class="hidden">locid</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $key => $value) {
                                    echo '<tr>';
                                    echo '<td class="cm_id">' . $value['cm_id'] . '</td>';
                                    echo '<td class="client_name">' . $value['client_name'] . '</td>';
                                    echo '<td class="process">' . $value['process'] . '</td>';
                                    echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
                                    echo '<td class="location">' . $value['location'] . '</td>';
                                    echo '<td class="AdminID">' . $value['AdminID'] . '</td>';
                                    echo '<td class="ITID">' . $value['ITID'] . '</td>';
                                    echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['cm_id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
                                    echo '<td class="locid hidden">' . $value['id'] . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
                <!--Reprot / Data Table End -->
            </div>
            <!--Form container End -->
        </div>
        <!--Main Div for all Page End -->
    </div>
    <!--Content Div for all Page End -->
</div>
<script>
    $('#txt_location').attr("disabled", false);
    $("#txt_Client_Name").attr("disabled", false);
    $("#txt_Client_proc").attr("disabled", false);
    $("#txt_Client_subproc").attr("disabled", false);
    $(document).ready(function() {
        //Model Assigned and initiation code on document load	
        $('.modal').modal({
            onOpenStart: function(elm) {

            },
            onCloseEnd: function(elm) {
                $('#btn_Client_Can').trigger("click");
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

        // This code for cancel button trigger click and also for model close
        $('#btn_Client_Can').on('click', function() {
            $('#txt_Client_Name').val('NA');
            $('#hid_Client_ID').val('');
            $('#txt_AdminID').val('NA');
            $('#txt_ITID').val('NA');
            $('#txt_Client_proc').val('NA');
            $('#txt_location').val('NA');
            $('#txt_Client_subproc').val('NA');
            $('#btn_Client_Save').removeClass('hidden');
            $('#btn_Client_Edit').addClass('hidden');
            $('#txt_location').attr("disabled", false);
            $("#txt_Client_Name").attr("disabled", false);
            $("#txt_Client_proc").attr("disabled", false);
            $("#txt_Client_subproc").attr("disabled", false);
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
            $('select').formSelect();

        });

        // This code for submit button and form submit for all model field validation if this contain a required attributes also has some manual code validation to if needed.

        $('#btn_Client_Edit,#btn_Client_Save').on('click', function() {
            var validate = 0;
            var alert_msg = '';

            if ($('#txt_location').val() == 'NA') {
                $('#txt_location').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_location').size() == 0) {
                    $('<span id="spantxt_location" class="help-block">Required *</span>').insertAfter('#txt_location');
                }
                validate = 1;
            }
            if ($('#txt_Client_Name').val() == 'NA') {
                $('#txt_Client_Name').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_Client_Name').size() == 0) {
                    $('<span id="spantxt_Client_Name" class="help-block">Required *</span>').insertAfter('#txt_Client_Name');
                }
                validate = 1;
            }
            if ($('#txt_Client_proc').val() == 'NA') {
                $('#txt_Client_proc').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_Client_proc').size() == 0) {
                    $('<span id="spantxt_Client_proc" class="help-block">Required *</span>').insertAfter('#txt_Client_proc');
                }
                validate = 1;
            }
            if ($('#txt_Client_subproc').val() == 'NA') {
                $('#txt_Client_subproc').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_Client_subproc').size() == 0) {
                    $('<span id="spantxt_Client_subproc" class="help-block">Required *</span>').insertAfter('#txt_Client_subproc');
                }
                validate = 1;
            }
            if ($('#txt_AdminID').val() == 'NA') {
                $('#txt_AdminID').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_AdminID').size() == 0) {
                    $('<span id="spantxt_AdminID" class="help-block">Required *</span>').insertAfter('#txt_AdminID');
                }
                validate = 1;
            }
            if ($('#txt_ITID').val() == 'NA') {
                $('#txt_ITID').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_ITID').size() == 0) {
                    $('<span id="spantxt_ITID" class="help-block">Required *</span>').insertAfter('#txt_ITID');
                }
                validate = 1;
            }
            if (validate == 1) {
                alert_msg = 'Please fill all required field';

                $(function() {
                    toastr.error(alert_msg);
                });
                return false;
            }
        });


    });


    // This code for trigger edit on all data table also trigger model open on a Model ID

    function EditData(el) {
        $('#txt_location').attr("disabled", true);
        $("#txt_Client_Name").attr("disabled", true);
        $("#txt_Client_proc").attr("disabled", true);
        $("#txt_Client_subproc").attr("disabled", true);

        var tr = $(el).closest('tr');
        var client_id = tr.find('.cm_id').text();
        var client_name = tr.find('.client_name').text();
        var process = tr.find('.process').text();
        var sub_process = tr.find('.sub_process').text();
        var location = $.trim(tr.find('.location').text());
        var locid = $.trim(tr.find('.locid').text());
        var AdminID = tr.find('.AdminID').text();
        var ITID = tr.find('.ITID').text();

        getProcess(locid, AdminID, ITID, client_id)
        $('#txt_location').val(locid);
        $('#hid_Client_ID').val(client_id);
        $('#txt_Client_Name').val(client_name);
        $('#txt_Client_proc').val(process);
        $('#txt_Client_subproc').val(sub_process);
        $('#txt_AdminID').val(AdminID);
        $('#txt_ITID').val(ITID);
        $('#btn_Client_Save').addClass('hidden');
        $('#btn_Client_Edit').removeClass('hidden');
        //$('#btn_Client_Can').removeClass('hidden');

        $('#myModal_content').modal('open');

        $("#myModal_content input,#myModal_content textarea").each(function(index, element) {

            if ($(element).val().length > 0) {
                $(this).siblings('label, i').addClass('active');
            } else {
                $(this).siblings('label, i').removeClass('active');
            }

        });
        $('select').formSelect();
    }


    $("#txt_location").change(function() {
        var txt_location = $(this).val();
        var AdminID = '';
        var ITID = '';
        var client_id = '';
        getProcess(txt_location, AdminID, ITID, client_id)
        $.ajax({
            url: '../Controller/selectclient.php',
            type: 'GET',
            data: {
                txt_location: txt_location,
            },
            dataType: 'json',
            success: function(response) {
                //alert(response);
                $("#txt_Client_Name").html(response.txt_Client_Name);
                $("#txt_Client_proc").html(response.txt_Client_proc);
                $("#txt_Client_subproc").html(response.txt_Client_subproc);

            }
        });
    });

    $("#txt_Client_Name").change(function() {
        var txt_location = $("#txt_location").val();
        var txt_Client_Name = $(this).val();
        $.ajax({
            url: '../Controller/getselectprocess.php',
            type: 'GET',
            data: {
                txt_location: txt_location,
                txt_Client_Name: txt_Client_Name,
            },
            dataType: 'json',
            success: function(response) {
                //   alert(response);
                $("#txt_Client_proc").html(response.txt_Client_proc);
                $("#txt_Client_subproc").html(response.txt_Client_subproc);
            }
        });
    });

    $("#txt_Client_proc").change(function() {
        var txt_location = $("#txt_location").val();
        var txt_Client_Name = $("#txt_Client_Name").val();
        var txt_Client_proc = $(this).val();
        $.ajax({
            url: '../Controller/selectsubprocess.php',
            type: 'GET',
            data: {
                txt_Client_Name: txt_Client_Name,
                txt_location: txt_location,
                txt_Client_proc: txt_Client_proc,
            },
            dataType: 'json',
            success: function(response) {
                //alert(response);
                $("#txt_Client_subproc").html(response.txt_Client_subproc);
            }
        });
    });

    function getProcess(locid, AdminID, ITID, client_id) {
        $.ajax({
            url: "../Controller/getAdminID.php?loc=" + locid + "&adminID=" + AdminID + "&ITID=" + ITID + "&client_id=" + client_id,
            success: function(result) {
                // alert(result);
                var array = result.split("||NA||");
                $("#txt_AdminID").html(array[0]);
                $('select').formSelect();
                $("#txt_ITID").html(array[1]);
                $('select').formSelect();
                $("#txt_Client_Name").html(array[2]);
                $('select').formSelect();
                $("#txt_Client_proc").html(array[3]);
                $('select').formSelect();
                $("#txt_Client_subproc").html(array[4]);
                $('select').formSelect();

            }
        });
    }
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>