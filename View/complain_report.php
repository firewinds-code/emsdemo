<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

// die;
$myDB = new MysqliDb();
$alert_msg = '';



if (isset($_POST['btn_ref_Edit'])) {
    // echo "<pre>";
    // print_r($_POST);
    $DataID = $_POST['hid_ref_ID'];
    // die;
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
            // $doc_evi = time() . '_' . $i . '_' . basename($_FILES["doc_evi"]["name"][$i]);
            $doc_evi = time() . '_' .  basename($_FILES["doc_evi"]["name"][$i]);
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

    $query = "update  comp_table set   case_rep_source=?, case_rep_date=?, case_rep_by=?, incident_date=?, comp_from=?, loaction=?, emp_status=?, process_name=?,comp_against=?, comp_category=?, comp_sub_category=?, case_detail=?, investigation=?, conclusion=?, call_status=?, remark=?, VH=?, AH=?, Oh=?,  emp_id=? where id=?";

    $stmts = $conn->prepare($query);

    $stmts->bind_param("ssssssssssssssssssssi", $case_rep_source, $case_rep_date, $case_rep_by, $incident_date, $comp_from, $loaction, $emp_status, $process_name,  $comp_against, $comp_category, $comp_sub_category, $case_detail, $investigation, $conclusion, $call_status, $remark, $VH, $AH, $OH, $EmployeeID, $DataID);
    $stmts->execute();
    $results = $stmts->get_result();
    // print_r($results);
    // die;
    $doc_evi = rtrim($ins_doc_evi, ',');
    $ins_doc_evi1 = explode(",", $doc_evi);
    // print_r($ins_doc_evi1);
    // die;
    // if ($stmts->affected_rows === 1) {
    // echo $DataID;
    // $delete = "delete from comp_issue_file where comp_id=?";
    // $del = $conn->prepare($delete);
    // $del->bind_param("i", $DataID);
    // $del->execute();
    // $res = $del->get_result();
    // die;
    foreach ($ins_doc_evi1 as $evid_doc) {
        $query = "INSERT INTO comp_issue_file (comp_id, doc_evi_filename) VALUES (?,?)";
        $stmts = $conn->prepare($query);
        $stmts->bind_param("is", $DataID, $evid_doc);
        $stmts->execute();
        $results = $stmts->get_result();
    }
    if ($stmts->affected_rows === 1) {
        // echo "Records inserted successfully.";
        echo "<script>$(function(){toastr.success('Records Updated successfully.')})</script>";
    } else {
        echo "<script>$(function(){toastr.error('Records Not Updated.')})</script>";
    }
    // } else {
    //     echo "<script>$(function(){toastr.error('Records Not inserted.')})</script>";
    // }
}
?>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            "iDisplayLength": 25,
            scrollCollapse: true,
            scrollX: true,
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

                , 'pageLength'

            ]

        });

        $('.buttons-copy').attr('id', 'buttons_copy');
        $('.buttons-csv').attr('id', 'buttons_csv');
        $('.buttons-excel').attr('id', 'buttons_excel');
        $('.buttons-pdf').attr('id', 'buttons_pdf');
        $('.buttons-print').attr('id', 'buttons_print');
        $('.buttons-page-length').attr('id', 'buttons_page_length');

        $('#date_to,#date_from').datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });

    });
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Site Issue Tracker Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <!--Form element model popup start-->
                <div id="myModal_content" class="modal">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <h4 class="col s12 m12 model-h4">Site Issue Tracker Report</h4>
                        <div class="modal-body" style="float:left;overflow: auto;">
                            <div class="col s12 m12">

                                <div class="input-field col s6 m6 ">
                                    <label for='option' class='active'>Employee Status</label>
                                    <input type='text' name='case_rep_source' id='case_rep_source' placeholder='Case Reported Date' readonly>
                                </div>

                                <div class="input-field col s6 m6 ">
                                    <label for='option' class='active'>Case Reported Date</label>
                                    <input type='text' name='case_rep_date' id='case_rep_date' placeholder='Case Reported Date' value="<?php echo $case_rep_date; ?>" readonly>
                                </div>

                                <div class="input-field col s6 m6 ">
                                    <label for='option' class='active'>Case Reported By</label>
                                    <input type='text' name='case_rep_by' id='case_rep_by' placeholder='Case Reported By' value="<?php echo  $case_rep_by; ?>" readonly>
                                    <!-- <span class="badge badge-primary"><input class="btn waves-effect waves-green" type="button" value="Find" id="find_case_by"></input></span> -->
                                    <div id="data-case_rep_by"></div>
                                </div>


                                <div class="input-field col s6 m6 ">
                                    <label for='option' class='active'>Incident Date</label>
                                    <input type='text' name='incident_date' id='incident_date' placeholder='Incident Date' value="" readonly>
                                </div><br>

                                <div class="input-field col s6 m6 ">
                                    <label for='option' class='active'>Complaint From</label>
                                    <input type='text' name='comp_from' id='comp_from' placeholder='Complaint From' value="" readonly>
                                    <!-- <span class="badge badge-primary"><input type="button" class="btn waves-effect waves-green" value="Find" id="find_com_from"></input></span> -->
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
                                    <label for="process_detail" class="active-drop-down active">VH</label>
                                </div>

                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="process_detail_AH" id="process_detail_AH" readonly>
                                    <label for="process_detail" class="active-drop-down active">AH</label>
                                </div>


                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="process_detail_OH" id="process_detail_OH" readonly>
                                    <label for="process_detail" class="active-drop-down active">OH</label>
                                </div>

                                <div class="input-field col s6 m6 ">
                                    <label for='option' class='active'>Complaint Against</label>
                                    <input type='text' name='comp_against' id='comp_against' placeholder='Complaint Against' value="" readonly>
                                    <!-- <span class="badge badge-primary"><input type="button" class="btn waves-effect waves-green" value="Find" id="find_comp_ag"></input></span> -->
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
                                        <!-- <option value="NA">-----select------</option> -->
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

                                    <div class="col-md-12">

                                        <div class="input-field col s6 m6 right-align">
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
                                                        <td>
                                                            <input name="doc_evi[]" type="file" id="doc_evi" class="form-control clsInput file_input"></input>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="input-field col s6 m6 left-align" id="existEMPdiv">
                                            <table border="1" id="existingTab" style="margin-left: -10px;">
                                                <thead>
                                                    <tr>
                                                        <th style='padding-left:100px;padding-right:100px;' colspan="3">
                                                            <h4>Complaint Files</h4>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th style='padding-left:100px;padding-right:100px;' class='hidden'>ID</th>
                                                        <th style='padding-left:100px;padding-right:100px;'>Files</th>
                                                        <th style='padding-left:100px;padding-right:100px;'>Created_at</th>
                                                        <th style='padding-left:100px;padding-right:100px;'>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbdID">

                                                </tbody>
                                            </table>
                                            <br>
                                        </div>

                                    </div>

                                </div>
                                <div class="input-field col s12 m12 right-align">
                                    <input type="hidden" class="form-control hidden" id="hid_ref_ID" name="hid_ref_ID" />
                                    <button type="submit" name="btn_ref_Edit" id="btn_ref_Edit" class="btn waves-effect waves-green hidden">Save</button>
                                    <button type="button" name="btn_ref_Can" id="btn_ref_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--Form element model popup End-->
            <?php
            $to = '';
            $from = '';
            if (isset($_POST['date_to']) && isset($_POST['date_from'])) {
                $date_from = $_POST['date_from'];
                $date_to = $_POST['date_to'];
            } else {
                $date_to = date('Y-m-d');
                $date_from = date('Y-m-d');
            }
            ?>

            <div class="input-field col s12 m12" id="rpt_container">
                <div class="input-field col s4 m4">
                    <span>Date From</span>
                    <input type="text" class="form-control" name="date_from" id="date_from" value="<?php echo $date_from; ?>" />
                </div>
                <div class="input-field col s4 m4">
                    <span>Date To</span>

                    <input type="text" class="form-control" name="date_to" id="date_to" value="<?php echo $date_to; ?>" />
                </div>

                <div class="input-field col s12 m12 right-align">
                    <button type="submit" class="btn waves-effect waves-green" name="get_record" id="get_record">
                        <i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            <div class="schema-form-section row">
                <!--Reprot / Data Table start -->
                <div id="pnlTable">
                    <?php

                    if (isset($_POST['date_to']) && isset($_POST['date_from'])) {
                        $date_from = date("Y-m-d", strtotime($_POST['date_from']));
                        $date_to = date("Y-m-d", strtotime($_POST['date_to']));
                        $sqlConnect = 'SELECT c.*, group_concat(ci.doc_evi_filename) as evidence fROM comp_table as c left join comp_issue_file as ci on c.id=ci.comp_id  where  cast(create_date as date) BETWEEN ? and ? and emp_id=? group by c.id';
                        $stm = $conn->prepare($sqlConnect);
                        $stm->bind_param("sss", $date_from, $date_to, $EmployeeID);
                        $stm->execute();
                        $results = $stm->get_result();
                    } else {
                        $today = date('Y-m-d');
                        $sqlConnect = 'SELECT c.*, group_concat(ci.doc_evi_filename) as evidence fROM comp_table as c left join comp_issue_file as ci on c.id=ci.comp_id where emp_id=? and DATE(create_date) = ? group by c.id';
                        $stm = $conn->prepare($sqlConnect);
                        $stm->bind_param("ss", $EmployeeID, $today);
                        $stm->execute();
                        $results = $stm->get_result();
                        // print_r($results);
                    }
                    if (!empty($results)) { ?>

                        <div class="panel panel-default" style="margin-top: 10px;">
                            <div class="panel-body">
                                <table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="hidden">ID</th>
                                            <th class="hidden">Emp Status</th>
                                            <th>EmployeeID</th>
                                            <th>Case Response Source</th>
                                            <th>Case Response Date</th>
                                            <th>Case Response By</th>
                                            <th>Incident Date</th>
                                            <th>Complaint From</th>
                                            <th>Location</th>
                                            <th>Process Name</th>
                                            <th>Complaint Against</th>
                                            <th>Complaint Category</th>
                                            <th>Complaint Sub Category</th>
                                            <th>VH</th>
                                            <th>AH</th>
                                            <th>OH</th>
                                            <th>Case Details</th>
                                            <th>Investigation</th>
                                            <th>Conclusion</th>
                                            <th>Evidence</th>
                                            <th>Call Status</th>
                                            <th>Remarks</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 0;
                                        foreach ($results as $key => $value) {
                                            $srr = explode(',', $value['evidence']);
                                            $srr = array_filter($srr);
                                            echo '<tr>';
                                            echo '<td class="ref_id hidden">' . $value['id'] . '</td>';
                                            echo '<td class="emp_status hidden">' . $value['emp_status'] . '</td>';
                                            echo '<td class="emp_id" id="' . $value['emp_id'] . '">' . $value['emp_id'] . '</td>';
                                            echo '<td class="case_rep_source" id="' . $value['case_rep_source'] . '">' . $value['case_rep_source'] . '</td>';
                                            echo '<td class="case_rep_date" id="' . $value['case_rep_date'] . '">' . $value['case_rep_date'] . '</td>';
                                            echo '<td class="case_rep_by">' . $value['case_rep_by'] . '</td>';
                                            echo '<td class="incident_date">' . $value['incident_date'] . '</td>';
                                            echo '<td class="comp_from">' . $value['comp_from'] . '</td>';
                                            echo '<td class="loaction">' . $value['loaction'] . '</td>';
                                            echo '<td class="process_name">' . $value['process_name'] . '</td>';
                                            echo '<td class="comp_against">' . $value['comp_against'] . '</td>';
                                            echo '<td class="comp_category">' . $value['comp_category'] . '</td>';
                                            echo '<td class="comp_sub_categories">' . $value['comp_sub_category'] . '</td>';
                                            echo '<td class="VH">' . $value['VH'] . '</td>';
                                            echo '<td class="AH">' . $value['AH'] . '</td>';
                                            echo '<td class="Oh">' . $value['Oh'] . '</td>';
                                            echo '<td class="case_detail">' . $value['case_detail'] . '</td>';
                                            echo '<td class="investigation">' . $value['investigation'] . '</td>';
                                            echo '<td class="conclusion">' . $value['conclusion'] . '</td>'; ?>
                                            <td class='files'> <?php foreach ($srr as $ext_image) { ?> <a href="<?= '../uploads/' . $ext_image ?>" download="download" style="color:blue;">Doc</a> &nbsp <?php } ?></td>
                                        <?php echo '<td class="call_status">' . $value['call_status'] . '</td>';
                                            echo '<td class="remark">' . $value['remark'] . '</td>';
                                            echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <!--Reprot / Data Table End -->
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#btn_ref_Edit').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            var fileExtension = ['jpeg', 'jpg', 'png', 'pdf', 'msg'];

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

            // if ($('#doc_evi').val().trim() == '') {
            //     $('#doc_evi').addClass('has-error');
            //     if ($('#spandoc_evi').size() == 0) {
            //         $('<span id="spandoc_evi" class="help-block">Required *</span>').insertAfter('#doc_evi');
            //     }
            //     validate = 1;
            // }

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
        $('#btn_ref_Can').on('click', function() {

            $('#hid_ref_ID').val('');
            $('#emp_status').val('NA');
            $('#case_rep_source').val('');
            $('#case_rep_date').val('');
            $('#case_rep_by').val('');
            $('#incident_date').val('');
            $('#comp_from').val('');
            $('#loaction').val('NA');
            $('#process_name').val('NA');
            $('#comp_against').val('');
            $('#comp_category').val('NA');
            $('#comp_sub_category').val('NA');
            $('#process_detail_VH').val('');
            $('#process_detail_AH').val('');
            $('#process_detail_OH').val('');
            $('#case_detail').val('');
            $('#investigation').val('');
            $('#conclusion').val('');
            $('#call_status').val('NA');
            $('#remark').val('NA');
            $('select').formSelect();
            $('#btn_ref_Edit').addClass('hidden');
            $('#btn_ref_Can').addClass('hidden');

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

        // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.   
        $('#btn_ref_Edit').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            // <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
            $("input,select,textarea").each(function() {
                var spanID = "span" + $(this).attr('id');
                $(this).removeClass('has-error');
                if ($(this).is('select')) {
                    $(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
                }
                var attr_req = $(this).attr('required');
                if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
                    validate = 1;
                    $(this).addClass('has-error');
                    if ($(this).is('select')) {
                        $(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
                    }
                    if ($('#' + spanID).size() == 0) {
                        $('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
                    }
                    var attr_error = $(this).attr('data-error-msg');
                    if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
                        $('#' + spanID).html('Required *');
                    } else {
                        $('#' + spanID).html($(this).attr("data-error-msg"));
                    }
                }
            })

            if (validate == 1) {
                return false;
            }

        });

    });
    // This code for trigger edit on all data table also trigger model open on a Model ID
    function EditData(el) {

        $('#btnDoccan').click(function() {
            event.stopPropagation();
            $count = $(".trdoc").length;
            // alert($count);
            if ($count > 1) {
                $('#childtable tbody').children("tr:last-child").remove();
                $('#doc_child').val(parseInt($count - 1));
            }
        });


        var tr = $(el).closest('tr');
        var ref_id = tr.find('.ref_id').text();
        // alert(ref_id);
        var emp_status = tr.find('.emp_status').text();
        var case_rep_source = tr.find('.case_rep_source').text();
        var case_rep_date = tr.find('.case_rep_date').text();
        var case_rep_by = tr.find('.case_rep_by').text();
        var incident_date = tr.find('.incident_date').text();
        var files = tr.find('.files').text();
        // alert(files);
        var comp_from = tr.find('.comp_from').text();
        var loaction = tr.find('.loaction').text();
        var process_name = tr.find('.process_name').text();
        var comp_against = tr.find('.comp_against').text();
        var comp_category = tr.find('.comp_category').text();
        var comp_sub_category = tr.find('.comp_sub_categories').text();
        var VH = tr.find('.VH').text();
        var AH = tr.find('.AH').text();
        var Oh = tr.find('.Oh').text();
        var case_detail = tr.find('.case_detail').text();
        var investigation = tr.find('.investigation').text();
        var conclusion = tr.find('.conclusion').text();
        var call_status = tr.find('.call_status').text();
        var remark = tr.find('.remark').text();


        $('#hid_ref_ID').val(ref_id);
        $('#emp_status').val(emp_status);
        $('#case_rep_source').val(case_rep_source);
        $('#case_rep_date').val(case_rep_date);
        $('#case_rep_by').val(case_rep_by);
        $('#incident_date').val(incident_date);
        $('#comp_from').val(comp_from);
        $('#loaction').val(loaction);
        $('#process_name').val(process_name);

        $.ajax({
            url: <?php echo '"' . URL . '"'; ?> + "Controller/get_compdoc.php?id=" + ref_id
        }).done(function(data) { // data what is sent back by the php page
            // alert(data); //$('#docData').html(data);
            //$('select').formSelect();
            $('#tbdID').html(data);
        });

        $('#comp_against').val(comp_against);
        $('#comp_category').val(comp_category);
        $('#comp_sub_category').append(`<option value="${comp_sub_category}">${comp_sub_category}</option>`);
        $('#process_detail_VH').val(VH);
        $('#process_detail_AH').val(AH);
        $('#process_detail_OH').val(Oh);
        $('#case_detail').val(case_detail);
        $('#investigation').val(investigation);
        $('#conclusion').val(conclusion);
        $('#call_status').val(call_status);
        $('#remark').val(remark);
        // $('#doc_evi').val(files);
        $('select').formSelect();
        $('#btn_ref_Edit').removeClass('hidden');
        $('#btn_ref_Can').removeClass('hidden');

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
</script>
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

        // $('#btnDoccan').click(function() {
        //     $count = $(".trdoc").length;
        //     // alert($count);
        //     if ($count > 1) {
        //         $('#childtable tbody').children("tr:last-child").remove();
        //         $('#doc_child').val(parseInt($count - 1));
        //     }
        // });
    });
</script>
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
    });

    $(document).ready(function() {



        $('#view').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#date_from').val() == '') {
                $('#date_from').addClass('has-error');
                if ($('#spandate_from').size() == 0) {
                    $('<span id="spandate_from" class="help-block"></span>').insertAfter('#date_from');
                }
                $('#spandate_from').html('Required');
                validate = 1;
            }
            if ($('#date_to').val() == '') {
                $('#date_to').addClass('has-error');
                if ($('#spandate_to').size() == 0) {
                    $('<span id="spandate_to" class="help-block"></span>').insertAfter('#date_to');
                }
                $('#spandate_to').html('Required');
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