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
$value = $counEmployee = $countProcess = $countClient = $countSubproc = $date_From = 0;

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
/*if(time() > strtotime('12:00:00') && time() < strtotime('12:30:00')) 
{
	$location= URL.'Login';
	echo "<script>location.href='".$location."'</script>";
}*/
if (isset($_SESSION)) {
    if (!isset($_SESSION['__user_logid'])) {
        $location = URL . 'Login';
        header("Location: $location");
        exit();
    } else {
        $isPostBack = false;
        $referer = "";
        $thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
        }
        if ($referer == $thisPage) {
            $isPostBack = true;
        }
        if ($isPostBack && isset($_POST)) {

            $date_on = $_POST['txt_dateOn'];
        } else {

            $date_on = date('Y-m-d', time());
        }
    }
} else {
    $location = URL . 'Login';
    header("Location: $location");
}

$__user_type = clean($_SESSION['__user_type']);
$__status_ah = clean($_SESSION['__status_ah']);
$emp_id = clean($_SESSION['__user_logid']);
$__status_er = clean($_SESSION["__status_er"]);
?>
<script>
    $(function() {


        // DataTable
        var table = $('#myTable').DataTable({
            dom: 'Bfrtip',
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
                }, 'pageLength'

            ],
            "bProcessing": true,
            "bDestroy": true,
            "bAutoWidth": true,
            "iDisplayLength": 25,
            "sScrollX": "100%",
            "bScrollCollapse": true,
            "bLengthChange": false

            // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
        });;
        $('#txt_dateOn').datepicker({
            dateFormat: 'yy-mm-dd',
            maxDate: 0
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
    <span id="PageTittle_span" class="hidden">MIS Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>MIS Report <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php
                $_SESSION["token"] = csrfToken();
                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <!--Form element model popup start-->
                <div id="myModal_content" class="modal">
                    <!-- Modal content-->
                    <div class="modal-content" style="height: 295px;">
                        <h4 class="col s12 m12 model-h4">MIS Report</h4>

                        <div class="modal-body">

                            <div class="input-field col s4 m4">

                                <select name="txt_Type" id="txt_Type">

                                    <option value="Total PuchInOut">Total PuchInOut</option>

                                </select>
                                <label for="txt_Type" class="active-drop-down active">Report For</label>
                            </div>




                            <div class="input-field col s4 m4">
                                <input name="txt_dateOn" value="<?php echo $date_on; ?>" id="txt_dateOn" />
                                <label for="txt_dateOn" class="Active">Date On</label>
                            </div>


                            <!-- <div class="input-field col s4 m4">
                                <Select name="txt_dept" id="txt_dept" required>
                                    <option value="NA">---Select Process---</option>
                                    <?php
                                    // $data = 'select distinct Process from new_client_master where er_scop=?';
                                    // $selectQ = $conn->prepare($data);
                                    // $selectQ->bind_param("s", $emp_id);
                                    // $selectQ->execute();
                                    // $rowData = $selectQ->get_result();
                                    // if ($rowData->num_rows > 0) {
                                    //     if ($dept == 'ALL') {
                                    //         echo '<option selected>ALL</option>';
                                    //     } else {
                                    //         echo '<option selected>ALL</option>';
                                    //     }
                                    //     foreach ($rowData as $key => $value) {
                                    //         if ($dept == $value['Process']) {
                                    //             echo '<option selected>' . $value['Process'] . '</option>';
                                    //         } else {
                                    //             echo '<option>' . $value['Process'] . '</option>';
                                    //         }
                                    //     }
                                    // } else {
                                    //     $sesss = clean($_SESSION['__user_process']);
                                    //     echo '<option >' . $sesss . '</option>';
                                    // }

                                    ?>
                                </Select>
                            </div> -->

                            <div class="input-field col s12 m12 right-align">

                                <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
                                <!--<button type="submit" class="button button-3d-action" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
                                <button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>

                            </div>

                        </div>
                    </div>
                </div>
                <!--Form element model popup End-->
                <!--Reprot / Data Table start -->

                <?php
                if (isset($_POST['btn_view'])) {
                    if ($__status_er == $emp_id) {
                        $myDB = new MysqliDb();
                        // echo 'call sp_get_MIS_Report_ER("' . $emp_id . '","' . $date_on . '")';
                        $chk_task = $myDB->rawQuery('call sp_get_MIS_Report_ER("' . $emp_id . '","' . $date_on . '")');
                        //echo 'call sp_get_APR_Report_ER("' . $emp_id . '","' . $date_To . '","' . $date_From . '","' . $dept . '")';
                    }
                    $my_error = $myDB->getLastError();
                    if (count($chk_task) > 0 && $chk_task) {
                        $table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
                        $table .= '<th>EmployeeID</th>';
                        $table .= '<th>PunchTime</th>';
                        $table .= '<th>Date </th>';
                        $table .= '<th>Location </th>';
                        $table .= '<th>Source </th>';
                        $table .= '<th>Createdon </th>';
                        $table .= '</thead><tbody>';

                        foreach ($chk_task as $key => $value) {
                            $source = '';
                            if ($value['EmployeeID'] == "") {
                                $source = "Manual";
                            } else
			    		if ($value['EmployeeID'] == "App") {
                                $source = "Mobile App";
                            } else
			    		if ($value['EmployeeID'] != "App" && $value['EmployeeID'] != "") {
                                $source = "Employee";
                            }
                            $table .= '<tr><td>' . $value['EmpID'] . '</td>';
                            $table .= '<td>' . $value['PunchTime'] . '</td>';
                            $table .= '<td>' . $value['Date'] . '</td>';
                            $table .= '<td>' . $value['location'] . '</td>';
                            $table .= '<td>' . $source . '</td>';
                            $table .= '<td>' . $value['createdon'] . '</td>';
                            $table .= '</tr>';
                        }
                        $table .= '</tbody></table>';
                        echo $table;
                    } else {
                        echo "<script>$(function(){ toastr.error('No Record found.'); }); </script>";
                    }
                }

                ?>

                <!--Reprot / Data Table End -->
            </div>
            <!--Form container End -->
        </div>
        <!--Main Div for all Page End -->
    </div>
    <!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
    $(document).ready(function() {
        //Model Assigned and initiation code on document load
        $('.modal').modal({
            onOpenStart: function(elm) {

            },
            onCloseEnd: function(elm) {
                $('#btn_Can').trigger("click");
            }
        });

        // This code for cancel button trigger click and also for model close
        $('#btn_Can').on('click', function() {
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
        $('#btn_view').on('click', function() {
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
                    if ($('#' + spanID).length == 0) {
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


    });
</script>