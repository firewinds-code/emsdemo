<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$clean_user_log_id = clean($_SESSION['__user_logid']);

if ($clean_user_log_id) {
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}
if (isset($_POST['txt_dateTo'])) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $date_To = cleanUserInput($_POST['txt_dateTo']);
        $date_From = cleanUserInput($_POST['txt_dateFrom']);
    }
} else {
    $date_To = date('Y', time());
    $date_From = date('m', time());
}

?>

<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Incentive Hours Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <h4 style="background-color: #19AEC4; color: #fff;">Incentive Hours Report</h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php $_SESSION["token"] = csrfToken();                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
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

                <div class="input-field col s6 m6" id="rpt_container">

                    <select name="txt_dateFrom" id="txt_dateFrom">
                        <option value='1' <?php echo ($date_From == '1') ? ' selected' : ''; ?>>January</option>
                        <option value='2' <?php echo ($date_From == '2') ? ' selected' : ''; ?>>February</option>
                        <option value='3' <?php echo ($date_From == '3') ? ' selected' : ''; ?>>March</option>
                        <option value='4' <?php echo ($date_From == '4') ? ' selected' : ''; ?>>April</option>
                        <option value='5' <?php echo ($date_From == '5') ? ' selected' : ''; ?>>May</option>
                        <option value='6' <?php echo ($date_From == '6') ? ' selected' : ''; ?>>June</option>
                        <option value='7' <?php echo ($date_From == '7') ? ' selected' : ''; ?>>July</option>
                        <option value='8' <?php echo ($date_From == '8') ? ' selected' : ''; ?>>August</option>
                        <option value='9' <?php echo ($date_From == '9') ? ' selected' : ''; ?>>September</option>
                        <option value='10' <?php echo ($date_From == '10') ? ' selected' : ''; ?>>October</option>
                        <option value='11' <?php echo ($date_From == '11') ? ' selected' : ''; ?>>November</option>
                        <option value='12' <?php echo ($date_From == '12') ? ' selected' : ''; ?>>December</option>
                    </select>
                </div>
                <div class="input-field col s6 m6">
                    <select name="txt_dateTo" id="txt_dateTo">
                        <option value='2023' <?php echo ($date_To == '2023') ? ' selected' : ''; ?>>2023</option>
                        <option value='2024' <?php echo ($date_To == '2024') ? ' selected' : ''; ?>>2024</option>
                        <option value='2025' <?php echo ($date_To == '2025') ? ' selected' : ''; ?>>2025</option>
                        <option value='2026' <?php echo ($date_To == '2026') ? ' selected' : ''; ?>>2026</option>
                        <option value='2027' <?php echo ($date_To == '2027') ? ' selected' : ''; ?>>2027</option>
                        <option value='2028' <?php echo ($date_To == '2028') ? ' selected' : ''; ?>>2028</option>
                        <option value='2029' <?php echo ($date_To == '2029') ? ' selected' : ''; ?>>2029</option>
                        <option value='2030' <?php echo ($date_To == '2030') ? ' selected' : ''; ?>>2030</option>

                    </select>
                </div>

                <div class="input-field col s12 m12 right-align">
                    <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
                        <i class="fa fa-search"></i> Search</button>
                </div>
            </div>

            <?php if (isset($_POST['btn_view'])) { ?>
                <div id="pnlTable">
                    <?php
                    // $sqlConnect = "select Date,ot_hrs,ot_amount from ot_upload where EmpID=? and Date between '" . $date_From . "' and '" . $date_To . "' ";
                    $sqlConnect = "select Date,ot_hrs,ot_amount from ot_upload where EmpID=? and month(Date)=? and year(Date)=? ";
                    $stmt = $conn->prepare($sqlConnect);
                    $stmt->bind_param("sss", $clean_user_log_id, $date_From, $date_To);
                    $stmt->execute();
                    $resultQry = $stmt->get_result();
                    if ($resultQry->num_rows > 0) {
                    ?>
                        <table id="" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Incentive HR's</th>
                                    <th>Incentive Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($resultQry as $key => $value) { ?>
                                    <tr>
                                        <td><?php echo $value['Date']; ?></td>
                                        <td><?php echo $value['ot_hrs']; ?></td>
                                        <td><?php echo $value['ot_amount']; ?></td>
                                    </tr>
                                <?php
                                    $total_ot_hrs += $value['ot_hrs'];;
                                    $total_ot_amount += $value['ot_amount'];;
                                } ?>
                                <tr>
                                    <td></td>
                                    <td><b><?php echo $total_ot_hrs ?></b> (Total Incentive Hours)</td>
                                    <td><b><?php echo $total_ot_amount ?></b> (Total Incentive Amount)</td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } else {
                        echo "<script>$(function(){ toastr.error('No Records Found'); }); </script>";
                    } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

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


    $('#btn_view').click(function() {
        var validate = 0;
        var alert_msg = '';

        if ($('#txt_dateMonth').val() == '') {
            $('#txt_dateMonth').addClass("has-error");
            if ($('#spantxt_dateMonth').length == 0) {
                $('<span id="spantxt_dateMonth" class="help-block">Required *</span>').insertAfter('#txt_dateMonth');
            }
            validate = 1;
        }

        if ($('#txt_dateYear').val() == '') {
            $('#txt_dateYear').addClass("has-error");
            if ($('#spantxt_dateYear').length == 0) {
                $('<span id="spantxt_dateYear" class="help-block">Required *</span>').insertAfter('#txt_dateYear');
            }
            validate = 1;
        }

        if (validate == 1) {
            $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
            $('#alert_message').show().attr("class", "SlideInRight animated");
            $('#alert_message').delay(50000).fadeOut("slow");
            return false;
        }
    })
</script>