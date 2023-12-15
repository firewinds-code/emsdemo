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
$alert_msg = '';
// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_RefMaster_Save'])) {
    $_cmid = (isset($_POST['txt_Client']) ? $_POST['txt_Client'] : null);
    $_RefAmt = (isset($_POST['txt_RefAmount']) ? $_POST['txt_RefAmount'] : null);
    $_from = (isset($_POST['txt_from']) ? $_POST['txt_from'] : null);
    $_to = (isset($_POST['txt_to']) ? $_POST['txt_to'] : null);
    $_FPay = (isset($_POST['txt_1st_PayAmt']) ? $_POST['txt_1st_PayAmt'] : null);
    $_SPay = (isset($_POST['txt_2nd_PayAmt']) ? $_POST['txt_2nd_PayAmt'] : null);
    $_WinMonth = (isset($_POST['txt_WindowMonth']) ? $_POST['txt_WindowMonth'] : null);
    $emp_type = (isset($_POST['emp_type']) ? $_POST['emp_type'] : null);

    $createBy = $_SESSION['__user_logid'];
    $Insert = 'call sp_insert_MasterRefScheme_VH("' . $_cmid . '","' . $_RefAmt . '","' . $_from . '","' . $_to . '","' . $_FPay . '","' . $_SPay . '","' . $_WinMonth . '","' . $emp_type . '","' . $createBy . '")';

    $myDB = new MysqliDb();
    $myDB->rawQuery($Insert);
    $mysql_error = $myDB->getLastError();
    if (empty($mysql_error)) {

        if ($myDB->count > 0) {
            echo "<script>$(function(){ toastr.success('Master Reference Scheme Added Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){ toastr.error('Master Reference Scheme Not Added, May be Duplicate Entry Found for given date'); }); </script>";
        }
    } else {
        echo "<script>$(function(){ toastr.error('Master Reference Scheme not Added. Some error occured " . $mysql_error . "'); }); </script>";
    }
}
// Trigger Button-Edit Click Event and Perform DB Action
if (isset($_POST['btn_RefMaster_Edit'])) {
    $DataID = $_POST['hid_ID'];
    $_cm_id = (isset($_POST['txt_Client']) ? $_POST['txt_Client'] : null);
    $_RefAmount = (isset($_POST['txt_RefAmount']) ? $_POST['txt_RefAmount'] : null);
    $_from = (isset($_POST['txt_from']) ? $_POST['txt_from'] : null);
    $_to = (isset($_POST['txt_to']) ? $_POST['txt_to'] : null);
    $_FPay = (isset($_POST['txt_1st_PayAmt']) ? $_POST['txt_1st_PayAmt'] : null);
    $_SPay = (isset($_POST['txt_2nd_PayAmt']) ? $_POST['txt_2nd_PayAmt'] : null);
    $_WinMonth = (isset($_POST['txt_WindowMonth']) ? $_POST['txt_WindowMonth'] : null);
    $emp_type = (isset($_POST['emp_type']) ? $_POST['emp_type'] : null);
    $ModifiedBy = $_SESSION['__user_logid'];

    if ($_POST['approver'] == "Approve") {
        $flag = 1;
    } else {
        $flag = 2;
    }
    $Update = 'call sp_Update_MasterRefScheme_ADMIN("' . $DataID . '","' . $_cm_id . '","' . $_RefAmount . '","' . $_from . '","' . $_to . '","' . $_FPay . '","' . $_SPay . '","' . $_WinMonth . '","' . $emp_type . '","' . $flag . '","' . $ModifiedBy . '")';
    // die;
    $myDB = new MysqliDb();
    if (!empty($DataID) || $DataID != '') {
        $myDB->rawQuery($Update);
        $mysql_error = $myDB->getLastError();
        if (empty($mysql_error)) {
            if ($myDB->count > 0) {
                echo "<script>$(function(){ toastr.success('Master Reference Scheme Updated Successfully'); }); </script>";

                if ($_POST['approver'] == "Approve") {
                    $insert = "insert into ref_amount_master(RefID,cm_id,amount,1st_pay,2nd_pay,window_month,emp_type,ApplicableFrom,ApplicableTo,createdby)values('" . $DataID . "','" . $_cm_id . "','" . $_RefAmount . "','" . $_FPay . "','" . $_SPay . "','" . $_WinMonth . "','" . $emp_type . "','" . $_from . "','" . $_to . "','" . $_SESSION['__user_logid'] . "')";
                    // die;
                    $myDB = new MysqliDb();
                    $res = $myDB->query($insert);
                    $mysql_error = $myDB->getLastError();
                    if (empty($mysql_error)) {
                        echo "<script>$(function(){ toastr.success('Inserted Successfully'); });</script>";
                    } else {
                        echo "<script>$(function(){ toastr.error('Not Inserted'); });</script>";
                    }
                } else {
                    // echo "<script>$(function(){ toastr.error('Decline Qry is Not Inserted'); });</script>";
                }
            } else {
                echo "<script>$(function(){ toastr.error('Master Reference Scheme Not Updated, May be Duplicate Entry Found for given date'); }); </script>";
            }
        } else {
            echo "<script>$(function(){ toastr.success('Master Reference Scheme Not Updated. Some error occurred'); }); </script>";

            //echo "<script>$(function(){ toastr.error('Master Reference Scheme Not Updated: Some error occurred...); }); </script>";
        }
    } else {
        echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First. If Not Resolved then contact to technical person'); }); </script>";
    }

    // $insert = "insert into ref_amount_master(RefID,cm_id,amount,1st_pay,2nd_pay,window_month,ApplicableFrom,ApplicableTo,createdby)values('" . $DataID . "','" . $_cm_id . "','" . $_RefAmount . "','" . $_FPay . "','" . $_SPay . "','" . $_WinMonth . "','" . $_from . "','" . $_to . "','" . $_SESSION['__user_logid'] . "')";

    // $myDB = new MysqliDb();
    // $res = $myDB->query($insert);
    // $mysql_error = $myDB->getLastError();
    // if (empty($mysql_error)) {
    //     echo "<script>$(function(){ toastr.success('Inserted Successfully'); });</script>";
    // } else {
    //     echo "<script>$(function(){ toastr.error('Not Inserted'); });</script>";
    // }
}
?>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            "iDisplayLength": 25,
            // scrollCollapse: true,
            scrollX: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
            ],
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
                ,
                'pageLength'

            ]
            // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
        });

        $('.buttons-copy').attr('id', 'buttons_copy');
        $('.buttons-csv').attr('id', 'buttons_csv');
        $('.buttons-excel').attr('id', 'buttons_excel');
        $('.buttons-pdf').attr('id', 'buttons_pdf');
        $('.buttons-print').attr('id', 'buttons_print');
        $('.buttons-page-length').attr('id', 'buttons_page_length');


        $('#txt_from').datepicker({
            maxDate: "+30d",
            minDate: "-30d",
            dateFormat: 'yy-mm-dd'
        });


        $('#txt_to').datepicker({
            maxDate: "+120d",
            minDate: "-30d",
            dateFormat: 'yy-mm-dd',

            onSelect: function(dateStr) {
                var max = $('#txt_to').datepicker('getDate'); // Get selected date
                var start = $("#txt_from").datepicker("getDate");
                var end = $("#txt_to").datepicker("getDate");

                if (start != null) {
                    var days = (end - start) / (1000 * 60 * 60 * 24);
                    txtDays = days + 1;



                    if (days < 0) {

                        alert("To Date should be greater then From Date");
                        $("#txt_to").val('');
                        return false;
                    }
                } else {
                    alert("Select From Date First...");
                    $("#txt_to").val('');
                }
                $("#AppToId").addClass("Active");
            }

        });


        $('#txt_RefAmount').keyup(function() {
            this.value = this.value.replace(/[^0-9.]/g, '');

            $('#txt_1st_PayAmt').val('');
            $('#txt_2nd_PayAmt').val('');


        });

        $('#txt_WindowDay').keyup(function() {
            this.value = this.value.replace(/[^0-9.]/g, '');


        });


        $('#txt_1st_PayAmt').keyup(function() {
            this.value = this.value.replace(/[^0-9.]/g, '');
            var amt1 = $('#txt_RefAmount').val(),
                amt2 = 0;

            if (amt1 == 'NA') {
                $('#txt_RefAmount').val('');
            }

            if (parseInt($('#txt_RefAmount').val().length) == 0 || parseInt($('#txt_1st_PayAmt').val()) > parseInt($('#txt_RefAmount').val())) {
                this.value = '';
                $('#txt_2nd_PayAmt').val('');
                alert('1st Pay Amount should be less than or equal to total pay amount');
            } else {
                amt2 = $('#txt_1st_PayAmt').val();
                //alert(parseInt(amt1) - parseInt(amt2))
                //$('#txt_2nd_PayAmt').value = parseInt(amt1) - parseInt(amt2);
                $('#txt_2nd_PayAmt').val(parseInt(amt1) - parseInt(amt2));
                //alert($('#txt_1st_PayDate').val());
            }




        });

        $('#txt_RefAmount').focusout(function() {

            if ($('#txt_RefAmount').val() == 0) {
                $('#txt_1st_PayAmt').val(0);
                $('#txt_2nd_PayAmt').val(0);
            }

        });


        $('#txt_1st_PayAmt').focusout(function() {

            if (parseInt($('#txt_1st_PayAmt').val()) == parseInt(0)) {
                alert('1st Payout not be 0');
                $('#txt_1st_PayAmt').val('');
                $('#txt_2nd_PayAmt').val(0);
            }

            if ($('#txt_2nd_PayAmt').val() != 0 && $('#txt_2nd_PayAmt').val() != 'NULL') {
                $('#div_2ndAmt').removeClass('hidden');
            } else {
                $('#div_2ndAmt').addClass('hidden');
            }
        });

        $('#txt_WindowMonth').keyup(function() {
            this.value = this.value.replace(/[^0-9.]/g, '');

            if ((parseInt(this.value)) > parseInt(3)) {
                this.value = '';
                alert('Window is Not more than 3 month')
            } else if ((parseInt(this.value)) == parseInt(0)) {
                this.value = '';
                alert('0 is not allowed in window.')
            }

        });

    });
</script>

<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">Reference Master</span>

    <div class="pim-container row" id="div_main">
        <div class="form-div">
            <h4>Reference Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Reference"><i class="material-icons">add</i></a></h4>
            <div class="schema-form-section row">
                <div id="myModal_content" class="modal">

                    <!-- Modal content-->
                    <div class="modal-content" style="height: 550px;">
                        <h4 class="col s12 m12 model-h4">Manage Reference Master</h4>
                        <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
                            <div class="col s12 m12">

                                <div class="input-field col s8 m8">
                                    <select id="txt_Client" name="txt_Client" required>
                                        <option value="NA">----Select----</option>
                                        <?php
                                        $sqlBy = "select concat(t3.location,' | ',t2.client_name,' | ',t1.process,' | ',t1.sub_process) as process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id join location_master t3 on t3.id=t1.location where cm_id not in (select cm_id from client_status_master) order by t3.location";
                                        $myDB = new MysqliDb();
                                        $resultBy = $myDB->rawQuery($sqlBy);
                                        $mysql_error = $myDB->getLastError();
                                        if (empty($mysql_error)) {
                                            foreach ($resultBy as $key => $value) {
                                                echo '<option value="' . $value['cm_id'] . '"  >' . $value['process'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="txt_Client" class="active-drop-down active">Process</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select name="emp_type" id="emp_type">
                                        <option value="NA">Select</option>
                                        <option value="CSA">CSA</option>
                                        <option value="Support">Support</option>
                                    </select>
                                    <label for="emp_type" class="active-drop-down active">Employee Type</label>
                                </div>

                            </div>

                            <div class="col s12 m12">

                                <div class="input-field col s4 m4">
                                    <input type="text" id="txt_RefAmount" name="txt_RefAmount" maxlength="4" required />
                                    <label for="txt_RefAmount">Reference Amount</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <input type="text" id="txt_from" name="txt_from" required />
                                    <label for="txt_from">Applicable From</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <input type="text" id="txt_to" name="txt_to" required />
                                    <label for="txt_to" id="AppToId">Applicable To</label>
                                </div>

                            </div>


                            <div class="col s12 m12">
                                <div class="input-field col s3 m3">
                                    <input type="text" id="txt_1st_PayAmt" name="txt_1st_PayAmt" maxlength="4" required />
                                    <label for="txt_1st_PayAmt">1st Pay Amount</label>
                                </div>

                                <div class="input-field col s3 m3">
                                    <input type="text" id="txt_2nd_PayAmt" name="txt_2nd_PayAmt" maxlength="4" placeholder="0" readonly="true" required />
                                    <label for="txt_2nd_PayAmt">2nd Pay Amount</label>
                                </div>


                                <div class="input-field col s6 m6">
                                    <input type="text" id="txt_WindowMonth" name="txt_WindowMonth" maxlength="1" required />
                                    <label for="txt_WindowMonth">Window Month</label>
                                </div>

                            </div>

                            <div class="input-field col s4 m4 hidden" id="adminDropDownForApprover">
                                <select name="approver" id="approver">
                                    <option value="NA">Select</option>
                                    <option value="Approve">Approve</option>
                                    <option value="Decline">Decline</option>
                                </select>
                            </div>

                            <div class="input-field col s12 m12 right-align">

                                <input type="hidden" class="form-control hidden" id="hid_ID" name="hid_ID" />
                                <button type="submit" name="btn_RefMaster_Save" id="btn_RefMaster_Save" class="btn waves-effect waves-green">Add</button>
                                <button type="submit" name="btn_RefMaster_Edit" id="btn_RefMaster_Edit" class="btn waves-effect waves-green hidden">Save</button>
                                <button type="button" name="btn_RefMaster_Can" id="btn_RefMaster_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="pnlTable">
                    <?php
                    $sqlConnect = "select t2.id,t2.cm_id,t1.process,t1.sub_process,t2.amount,t2.ApplicableFrom,t2.ApplicableTo,t2.1st_pay,t2.2nd_pay,t2.window_month,t2.emp_type,t2.createdby,t2.createdon,t1.location,t3.client_name,t4.location as locname,flag from new_client_master t1 join client_ref_master t2 on t1.cm_id= t2.cm_id join client_master t3 on t1.client_name= t3.client_id join location_master t4 on t4.id=t1.location where flag=0 and t1.cm_id not in (select cm_id from client_status_master) ";
                    $myDB = new MysqliDb();
                    $result = $myDB->rawQuery($sqlConnect);
                    $mysql_error = $myDB->getLastError();
                    if (empty($mysql_error)) { ?>
                        <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="hidden">ID</th>
                                    <th class="hidden">cmid</th>
                                    <th class="hidden">client</th>
                                    <th class="hidden">flag</th>
                                    <th>Process</th>
                                    <th>Sub Process</th>
                                    <th>Amount</th>
                                    <th>Applicable From </th>
                                    <th>Applicable To</th>
                                    <th>1st Pay</th>
                                    <th>2nd Pay</th>
                                    <th>Window Month</th>
                                    <th>Employee Type</th>
                                    <th>Created By</th>
                                    <th>Created On</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $key => $value) { ?>
                                    <tr>
                                        <td class="id hidden"><?php echo $value['id'] ?></td>
                                        <td class="cm_id hidden"><?php echo $value['cm_id'] ?></td>
                                        <td class="client hidden"><?php echo $value['client_name'] ?></td>
                                        <td class="client hidden"><?php echo $value['flag'] ?></td>
                                        <td class="process"><?php echo $value['process'] ?></td>
                                        <td class="sub_process"><?php echo $value['sub_process'] ?></td>
                                        <td class="amount"><?php echo $value['amount'] ?></td>
                                        <td class="ApplicableFrom"><?php echo $value['ApplicableFrom'] ?></td>
                                        <td class="ApplicableTo"><?php echo $value['ApplicableTo'] ?></td>
                                        <td class="1st_pay"><?php echo $value['1st_pay'] ?></td>
                                        <td class="2nd_pay"><?php echo $value['2nd_pay'] ?></td>
                                        <td class="window_month"><?php echo $value['window_month'] ?></td>
                                        <td class="emp_type"><?php echo $value['emp_type'] ?></td>
                                        <td class="createdby"><?php echo $value['createdby'] ?></td>
                                        <td class="createdon"><?php echo $value['createdon'] ?></td>

                                        <td class="manage_item">

                                            <i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id=<?php echo $value['id']; ?> data-position="left" data-tooltip="Edit">ohrm_edit</i>

                                            <!-- <i class="material-icons  imgBtn imgBtnEdit tooltipped delete_item" id=<?php echo $value['id']; ?> onclick="javascirpt:return ApplicationDataDelete(this);" data-position="left" data-tooltip="Delete">ohrm_delete</i> -->

                                        </td>

                                    </tr>

                                <?php }
                                ?>

                            </tbody>
                        </table>
                    <?php }  ?>
                </div>
            </div>
        </div>
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
                $('#btn_RefMaster_Can').trigger("click");
            }
        });
        // This code for cancel button trigger click and also for model close
        $('#btn_RefMaster_Can').on('click', function() {
            $('#txt_Client').val('NA');
            //alert('can');
            //$('#txt_Client').val('NA');
            $('#txt_RefAmount').val('');
            $('#txt_from').val('');
            $('#txt_to').val('');
            $('#txt_1st_PayAmt').val('');
            $('#txt_2nd_PayAmt').val('');
            $('#txt_WindowMonth').val('');
            $('#emp_type').val('NA');
            $('#hid_ID').val('');
            $('#btn_RefMaster_Save').removeClass('hidden');
            $('#btn_RefMaster_Edit').addClass('hidden');
            $('select').formSelect();
            //$('#btn_RefMaster_Can').addClass('hidden');

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


        $('#btn_RefMaster_Edit').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#approver').val() == 'NA') {
                $('#approver').addClass("has-error");
                if ($('#spanapprover').size() == 0) {
                    $('<span id="spanapprover" class="help-block">Required *</span>').insertAfter('#approver');
                }
                validate = 1;
            }

            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(50000).fadeOut("slow");
                return false;
            }
        });

        $('#btn_RefMaster_Edit,#btn_RefMaster_Save').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            // <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->

            if ($('#txt_Client').val() == 'NA') {
                $('#txt_Client').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_Client').size() == 0) {
                    $('<span id="spantxt_Client" class="help-block">Required *</span>').insertAfter('#txt_Client');
                }
                validate = 1;
            }
            if ($('#emp_type').val() == 'NA') {
                $('#emp_type').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanemp_type').size() == 0) {
                    $('<span id="spanemp_type" class="help-block">Required *</span>').insertAfter('#emp_type');
                }
                validate = 1;
            }

            if ($('#txt_RefAmount').val() == '') {
                $('#txt_RefAmount').addClass("has-error");
                if ($('#spantxt_RefAmount').size() == 0) {
                    $('<span id="spantxt_RefAmount" class="help-block">Required *</span>').insertAfter('#txt_RefAmount');
                }
                validate = 1;
            }

            if ($('#txt_from').val() == '') {
                $('#txt_from').addClass("has-error");
                if ($('#spantxt_from').size() == 0) {
                    $('<span id="spantxt_from" class="help-block">Required *</span>').insertAfter('#txt_from');
                }
                validate = 1;
            }
            if ($('#txt_to').val() == '') {
                $('#txt_to').addClass("has-error");
                if ($('#spantxt_to').size() == 0) {
                    $('<span id="spantxt_to" class="help-block">Required *</span>').insertAfter('#txt_to');
                }
                validate = 1;
            }

            if ($('#txt_1st_PayAmt').val() == '') {
                $('#txt_1st_PayAmt').addClass("has-error");
                if ($('#spantxt_1st_PayAmt').size() == 0) {
                    $('<span id="spantxt_1st_PayAmt" class="help-block">Required *</span>').insertAfter('#txt_1st_PayAmt');
                }
                validate = 1;
            }
            // if ($('#txt_2nd_PayAmt').val() == '') {
            // 	$('#txt_2nd_PayAmt').addClass("has-error");
            // 	if ($('#spantxt_2nd_PayAmt').size() == 0) {
            // 		$('<span id="spantxt_2nd_PayAmt" class="help-block">Required *</span>').insertAfter('#txt_2nd_PayAmt');
            // 	}
            // 	validate = 1;
            // }
            if ($('#txt_WindowMonth').val() == '') {
                $('#txt_WindowMonth').addClass("has-error");
                if ($('#spantxt_WindowMonth').size() == 0) {
                    $('<span id="spantxt_WindowMonth" class="help-block">Required *</span>').insertAfter('#txt_WindowMonth');
                }
                validate = 1;
            }

            // if ($('#approver').val() == 'NA') {
            //     $('#approver').addClass("has-error");
            //     if ($('#spanapprover').size() == 0) {
            //         $('<span id="spanapprover" class="help-block">Required *</span>').insertAfter('#approver');
            //     }
            //     validate = 1;
            // }

            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(50000).fadeOut("slow");
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



    });


    // This code for trigger edit on all data table also trigger model open on a Model ID

    function EditData(el) {
        $('#adminDropDownForApprover').removeClass('hidden');
        var tr = $(el).closest('tr');
        var ID = tr.find('.ID').text();
        var cm_id = tr.find('.cm_id').text();
        var client = tr.find('.client').text();
        var process = tr.find('.process').text();
        var subprocess = tr.find('.sub_process').text();
        var amount = tr.find('.amount').text();
        var ApplicableFrom = tr.find('.ApplicableFrom').text();
        var ApplicableTo = tr.find('.ApplicableTo').text();
        var fpay = tr.find('.1st_pay').text();
        var spay = tr.find('.2nd_pay').text();
        var wmonth = tr.find('.window_month').text();
        var emptype = tr.find('.emp_type').text();
        //alert(cm_id);
        var process = client + '|' + process + '|' + subprocess;
        //alert(process);
        $('#txt_Client').empty();

        $('#txt_Client')
            .append($("<option></option>")
                .attr("value", cm_id)
                .text(process));

        // $('#txt_Client').prop('disabled', true)
        $('#hid_ID').val(ID);
        $('#txt_Client').val(cm_id);
        //$('#txt_Client').val(process);	       
        //$('#txt_Client').val(subprocess);
        $('#txt_RefAmount').val(amount);
        $('#txt_from').val(ApplicableFrom);
        $('#txt_to').val(ApplicableTo);
        $('#txt_1st_PayAmt').val(fpay);
        $('#txt_2nd_PayAmt').val(spay);
        $('#txt_WindowMonth').val(wmonth);

        $('#emp_type').empty();
        $('#emp_type')
            .append($("<option></option>")
                .attr("value", emptype)
                .text(emptype));

        $('#emp_type').val(emptype);

        $('select').formSelect();

        $('#btn_RefMaster_Save').addClass('hidden');
        $('#btn_RefMaster_Edit').removeClass('hidden');
        //$('#btn_RefMaster_Can').removeClass('hidden');
        $('#myModal_content').modal('open');
        $("#myModal_content input,#myModal_content textarea").each(function(index, element) {
            if ($(element).val().length > 0) {
                $(this).siblings('label, i').addClass('active');
            } else {
                $(this).siblings('label, i').removeClass('active');
            }

        });
    }

    // function ApplicationDataDelete(el) {
    //     // alert(el.id);
    //     var currentUrl = window.location.href;
    //     if (confirm('Are you sure want to delete??')) {
    //         $item = $(el);

    //         $.ajax({
    //             url: "../Controller/Delete_Client_ref_master_VH.php?id=" + $(el).attr('id'),
    //             success: function(result) {
    //                 var data = result.split('|');
    //                 toastr.success(data[1]);

    //                 if (data[0] == 'Done') {
    //                     $item.closest('td').parent('tr').remove();
    //                     window.location.href = currentUrl;
    //                 }
    //             }
    //         });
    //     }
    // }
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>