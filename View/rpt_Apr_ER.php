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
        if ($isPostBack && isset($_POST['txt_dateMonth'])) {
            if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
                $date_To = cleanUserInput($_POST['txt_dateMonth']);
                $date_From = cleanUserInput($_POST['txt_dateYear']);
                $dept = cleanUserInput($_POST['txt_dept']);
            }
        } else {
            $date_To = date('n', time());
            $date_From = date('Y', time());
            $dept = clean($_SESSION['__user_process']);
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
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var proc = $('#txt_process').val().toLowerCase();
                var sproc = $('#txt_Subproc').val().toLowerCase();
                var process = data[37]; // use data for the age column
                var subprocess = data[38]; // use data for the age column
                if (process.toLowerCase().indexOf(proc) >= 0 && subprocess.toLowerCase().indexOf(sproc) >= 0) {
                    return true;
                } else {
                    return false;
                }
            }
        );

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
            "bLengthChange": false,
            "fnDrawCallback": function() {

                $(".check_val_").prop('checked', $("#chkAll").prop('checked'));
            }

            // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
        });;
        $('#txt_Subproc, #txt_process').keyup(function() {
            table.draw();
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
    <span id="PageTittle_span" class="hidden">APR Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>APR Report <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

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
                        <h4 class="col s12 m12 model-h4">APR Report</h4>

                        <div class="modal-body">

                            <div class="input-field col s4 m4">

                                <Select name="txt_dateMonth" id="txt_dateMonth">
                                    <?php
                                    $name = date('M');
                                    $val = date('n');
                                    echo "<option value=$val>$name</option> ";
                                    $date = date('Y-m-d', strtotime('first day of -1 month'));
                                    $name = date('M', strtotime($date));
                                    $val = date('n', strtotime($date));
                                    echo "<option value=$val>$name</option> ";
                                    $date = date('Y-m-d', strtotime('first day of -2 month'));
                                    $name = date('M', strtotime($date));
                                    $val = date('n', strtotime($date));
                                    echo "<option value=$val>$name</option> ";
                                    ?>


                                </Select>
                            </div>

                            <div class="input-field col s4 m4">

                                <Select name="txt_dateYear" id="txt_dateYear">
                                    <?php
                                    $val1 = date('Y');
                                    echo "<option value=$val1>$val1</option> ";
                                    $date = date('Y-m-d', strtotime('first day of -1 month'));

                                    $val2 = date('Y', strtotime($date));
                                    if ($val1 != $val2) {
                                        echo "<option value=$val2>$val2</option> ";
                                    }

                                    $date = date('Y-m-d', strtotime('first day of -2 month'));

                                    $val3 = date('Y', strtotime($date));
                                    if ($val1 != $val3) {
                                        echo "<option value=$val3>$val3</option> ";
                                    }
                                    ?>


                                </Select>
                            </div>


                            <div class="input-field col s4 m4">
                                <Select name="txt_dept" id="txt_dept" required>
                                    <?php
                                    if ($emp_id == 'CE021929762') {
                                        $rowData = $myDB->query('select distinct Process from new_client_master');
                                        if (count($rowData) > 0) {
                                            if ($dept == 'ALL') {
                                                echo '<option selected>ALL</option>';
                                            } else {
                                                echo '<option selected>ALL</option>';
                                            }
                                            foreach ($rowData as $key => $value) {
                                                if ($dept == $value['Process']) {
                                                    echo '<option selected>' . $value['Process'] . '</option>';
                                                } else {
                                                    echo '<option>' . $value['Process'] . '</option>';
                                                }
                                            }
                                        }
                                    } else {
                                        $data = 'select distinct Process from new_client_master where er_scop=?  or er_spoc2=? or er_spoc3=?';
                                        $selectQ = $conn->prepare($data);
                                        $selectQ->bind_param("sss", $emp_id, $emp_id, $emp_id);
                                        $selectQ->execute();
                                        $rowData = $selectQ->get_result();
                                        if ($rowData->num_rows > 0) {
                                            if ($dept == 'ALL') {
                                                echo '<option selected>ALL</option>';
                                            } else {
                                                echo '<option selected>ALL</option>';
                                            }
                                            foreach ($rowData as $key => $value) {
                                                if ($dept == $value['Process']) {
                                                    echo '<option selected>' . $value['Process'] . '</option>';
                                                } else {
                                                    echo '<option>' . $value['Process'] . '</option>';
                                                }
                                            }
                                        } else {
                                            $sesss = clean($_SESSION['__user_process']);
                                            echo '<option >' . $sesss . '</option>';
                                        }
                                    }
                                    ?>
                                </Select>
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
                                <?php if ($__status_er == $emp_id) {  ?>
                                    <button type="button" name="btn_Excel" id="btn_Excel" class="btn waves-effect modal-action modal-close waves-red close-btn" onclick="javascript:return downloadexcel(this);">Export</button>
                                <?php }  ?>
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

                        $chk_task = $myDB->rawQuery('call sp_get_APR_Report_ER("' . $emp_id . '","' . $date_To . '","' . $date_From . '","' . $dept . '")');
                        //echo 'call sp_get_APR_Report_ER("' . $emp_id . '","' . $date_To . '","' . $date_From . '","' . $dept . '")';
                    }
                    $my_error = $myDB->getLastError();
                    if (count($chk_task) > 0 && $chk_task) {
                        $table = '<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
                        $table .= '<th>EmployeeID</th>';
                        $table .= '<th>Process</th>';
                        $table .= '<th>D1</th>';
                        $table .= '<th>D2</th>';
                        $table .= '<th>D3</th>';
                        $table .= '<th>D4</th>';
                        $table .= '<th>D5</th>';
                        $table .= '<th>D6</th>';
                        $table .= '<th>D7</th>';
                        $table .= '<th>D8</th>';
                        $table .= '<th>D9</th>';
                        $table .= '<th>D10</th>';
                        $table .= '<th>D11</th>';
                        $table .= '<th>D12</th>';
                        $table .= '<th>D13</th>';
                        $table .= '<th>D14</th>';
                        $table .= '<th>D15</th>';
                        $table .= '<th>D16</th>';
                        $table .= '<th>D17</th>';
                        $table .= '<th>D18</th>';
                        $table .= '<th>D19</th>';
                        $table .= '<th>D20</th>';
                        $table .= '<th>D21</th>';
                        $table .= '<th>D22</th>';
                        $table .= '<th>D23</th>';
                        $table .= '<th>D24</th>';
                        $table .= '<th>D25</th>';
                        $table .= '<th>D26</th>';
                        $table .= '<th>D27</th>';
                        $table .= '<th>D28</th>';
                        $table .= '<th>D29</th>';
                        $table .= '<th>D30</th>';
                        $table .= '<th>D31</th>';
                        $table .= '<th>Month</th>';
                        $table .= '<th>Year</th>';
                        $table .= '<th>CreatedBy</th>';
                        $table .= '<th>CreatedOn</th>';
                        $table .= '<th>ModifiedBy</th>';
                        $table .= '<th>ModifiedOn</th><thead><tbody>';

                        foreach ($chk_task as $key => $value) {

                            $table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
                            $table .= '<td>' . $value['Process'] . '</td>';
                            $table .= '<td>' . $value['D1'] . '</td>';
                            $table .= '<td>' . $value['D2'] . '</td>';
                            $table .= '<td>' . $value['D3'] . '</td>';
                            $table .= '<td>' . $value['D4'] . '</td>';
                            $table .= '<td>' . $value['D5'] . '</td>';
                            $table .= '<td>' . $value['D6'] . '</td>';
                            $table .= '<td>' . $value['D7'] . '</td>';
                            $table .= '<td>' . $value['D8'] . '</td>';
                            $table .= '<td>' . $value['D9'] . '</td>';
                            $table .= '<td>' . $value['D10'] . '</td>';
                            $table .= '<td>' . $value['D11'] . '</td>';
                            $table .= '<td>' . $value['D12'] . '</td>';
                            $table .= '<td>' . $value['D13'] . '</td>';
                            $table .= '<td>' . $value['D14'] . '</td>';
                            $table .= '<td>' . $value['D15'] . '</td>';
                            $table .= '<td>' . $value['D16'] . '</td>';
                            $table .= '<td>' . $value['D17'] . '</td>';
                            $table .= '<td>' . $value['D18'] . '</td>';
                            $table .= '<td>' . $value['D19'] . '</td>';
                            $table .= '<td>' . $value['D20'] . '</td>';
                            $table .= '<td>' . $value['D21'] . '</td>';
                            $table .= '<td>' . $value['D22'] . '</td>';
                            $table .= '<td>' . $value['D23'] . '</td>';
                            $table .= '<td>' . $value['D24'] . '</td>';
                            $table .= '<td>' . $value['D25'] . '</td>';
                            $table .= '<td>' . $value['D26'] . '</td>';
                            $table .= '<td>' . $value['D27'] . '</td>';
                            $table .= '<td>' . $value['D28'] . '</td>';
                            $table .= '<td>' . $value['D29'] . '</td>';
                            $table .= '<td>' . $value['D30'] . '</td>';
                            $table .= '<td>' . $value['D31'] . '</td>';
                            $table .= '<td>' . $value['Month'] . '</td>';
                            $table .= '<td>' . $value['Year'] . '</td>';
                            $table .= '<td>' . $value['createdby'] . '</td>';
                            $table .= '<td>' . $value['createdon'] . '</td>';
                            $table .= '<td>' . $value['modifiedby'] . '</td>';
                            $table .= '<td>' . $value['modifiedon'] . '</td></tr>';
                        }
                        $table .= '</tbody></table>';
                        echo $table;
                    } else {
                        echo "<script>$(function(){ toastr.error('No Record found.'); }); </script>";
                    }
                }

                ?>
                <div class="input-field col s6 m6 hidden">
                    <input type="text" id="txt_process" name="txt_process">
                    <label for="txt_process">Process</label>
                </div>
                <div class="input-field col s6 m6 hidden">
                    <input type="text" id="txt_Subproc" name="txt_Subproc" />
                    <label for="txt_Subproc">Sub Process</label>
                </div>

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

        $("#btn_Excel").hide();
        $("#txt_dept").change(function() {
            var dept = $('#txt_dept').val();
            // alert(dept)
            if (dept == "ALL") {
                $("#btn_Excel").show();
            } else {
                $("#btn_Excel").hide();
            }
        })
    });

    function downloadexcel(el) {
        $item = $(el);
        var usrid = <?php echo "'" . $_SESSION["__user_logid"] . "'"; ?>;
        var date_to = $('#txt_dateMonth').val();
        var date_from = $('#txt_dateYear').val();
        var dept = $('#txt_dept').val();
        var sp = "call sp_get_APR_Report_ER('" + usrid + "','" + date_to + "','" + date_from + "','" + dept + "')";
        //alert(sp);
        //return false;
        var url = "textExport.php?sp=" + sp;
        // alert(url);
        window.location.href = url;
    }
</script>