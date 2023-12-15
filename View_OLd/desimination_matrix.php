<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (clean($_SESSION["__status_ah"]) == 'No' && clean($_SESSION["__status_ah"]) != clean($_SESSION['__user_logid'])) {
    $location = URL . 'Error';
    header("Location: $location");
    exit();
}
$ah = clean($_SESSION["__user_logid"]);
$processQRY = "select cm_id, concat(t2.client_name,'|',t1.process,'|',t1.sub_process) as Process from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where account_head=? order by process;";
$stmt = $conn->prepare($processQRY);
$stmt->bind_param("s", $ah);
if (!$stmt) {
    echo "failed to run";
    die;
}
$stmt->execute();
$resultQry = $stmt->get_result();
// $result = $myDB->rawQuery($processQRY);
// echo "<pre>";
// print_r($result);
$btn_text_send = isset($_POST['btn_text_send']);
if (isset($_POST['btn_text_send'])) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $empid = cleanUserInput($_POST['empid']);
        $cm_id = cleanUserInput($_POST['team_process']);
        $created_by = clean($_SESSION["__user_logid"]);

        //select EmployeeID from ActiveEmpID where cm_id=56 and df_id=74;
        $insert_msg = 'INSERT INTO desimination_matrix(empid,process,created_by) values(?,?,?)'; //"'.$cmid.'"  and df_id=74
        $stmt = $conn->prepare($insert_msg);
        $stmt->bind_param("sis", $empid, $cm_id, $created_by);
        $inst = $stmt->execute();

        if ($inst) {
            echo "<script>$(function(){toastr.success('Created Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
        }
    }
}

$delete_id = isset($_GET['delete_id']);
if (isset($_GET['delete_id'])) {
    $delete_id = cleanUserInput($_GET['delete_id']);

    $deleteQRY = "delete from desimination_matrix where id=? ";
    $stmt1 = $conn->prepare($deleteQRY);
    $stmt1->bind_param("i", $delete_id);
    $Delt = $stmt1->execute();
    // $myDB = new MysqliDb();
    // $result = $myDB->query($deleteQRY);
    // $mysql_error = $myDB->getLastError();
    if ($Delt) {
        echo "<script>$(function(){toastr.success('Data Deleted'); }); </script>";
    } else {
        echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
    }
    echo "<script>setTimeout(function () {
        window . location . href = 'desimination_matrix'; 
    }, 1000);
</script>";
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
    <span id="PageTittle_span" class="hidden">Dissemination Matrix</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>Dissemination Matrix</h4>
            <div class="schema-form-section row">
                <?php
                $_SESSION["token"] = csrfToken();
                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
                <div class="input-field col s12 m12">
                    <div class="col s12 m12">

                        <div class="input-field col s8 m8 l8">
                            <select id="team_process" name="team_process">
                                <option Selected="True" Value="NA">-Select One-</option>
                                <?php

                                foreach ($resultQry as $key => $value) {
                                    echo '<option value="' . $value['cm_id'] . '">' . $value['Process'] . '</option>';
                                } ?>

                            </select>
                            <label for="team_process" class="active-drop-down active">Process</label>

                        </div>

                        <div class="input-field col s12 m12 ">
                            <input type="text" name="empid" id="empid">
                            <label for="msg">EmployeeID</label>
                            <div id="data-container"></div>
                        </div>
                    </div>

                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" name="btn_text_send" id="btn_text_send" class="btn waves-effect waves-green align-right">Send</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-div">
            <h4>Dissemination Matrix Report</h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">
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

                <div id="pnlTable">
                    <?php
                    $sqlConnect = 'SELECT * FROM desimination_matrix;';
                    $myDB = new MysqliDb();
                    $result = $myDB->rawQuery($sqlConnect);
                    $mysql_error = $myDB->getLastError();
                    if (empty($mysql_error)) {
                    ?>
                        <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Process</th>
                                    <th>EmployeeID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;

                                foreach ($result as $key => $value) {
                                    $numDate = mktime($value['acknowledge']); ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $value['process']; ?></td>
                                        <td><?php echo $value['empid']; ?></td>
                                        <td class="manage_item"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Delete" onclick="javascript:return delete_item(this);" data-item="<?php echo $value['id']; ?>">ohrm_delete</i></td>

                                        </td>
                                    </tr>
                                <?php

                                } ?>

                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
    <!--Content Div for all Page End -->
</div>


<script>
    function delete_item(el) {

        if (confirm('Are You Sure Want To Delete?? ')) {
            $item = $(el);

            $.ajax({
                url: "../Controller/deleteDesimination_matrix.php?id=" + $(el).attr('data-item'),
                success: function(result) {
                    var data = result.split('|');
                    toastr.success(data[1]);

                    if (data[0] == 'Done') {
                        $item.closest('td').parent('tr').remove();
                    }
                }
            });
        }
    }
</script>



<script>
    $(document).ready(function() {
        $('#btn_text_send').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#empid').val().replace(/^\s+|\s+$/g, '') == '') {
                $('#empid').addClass('has-error');
                if ($('#spanmsg').length == 0) {
                    $('<span id="spanmsg" class="help-block">Required *</span>').insertAfter('#empid');
                }
                validate = 1;
            }

            if ($('#team_process').val().replace(/^\s+|\s+$/g, '') == 'NA') {
                $('#team_process').addClass('has-error');
                if ($('#spanteam_process').length == 0) {
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