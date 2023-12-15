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
$__user_logid = clean($_SESSION['__user_logid']);

if ($__user_logid) {
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
} ?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden"> Downtime Matrix</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Downtime Matrix </h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <script>
                    //contain load event for data table and other importent rand required trigger event and searches if any
                    $(document).ready(function() {
                        $('#myTable').DataTable({
                            dom: 'Bfrtip',
                            scrollX: '100%',
                            "iDisplayLength": 10,
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

                <div class="input-field col s12 m12" id="rpt_container">
                    <form action="" method="post" id="dform">
                        <div class=" bylocation">
                            <div class="input-field col s6 m6">
                                <select class="" id="location1" name="location1" title="Select Location" readonly>
                                    <option value="NA">Select Location</option>
                                    <option value="ALL">ALL </option>
                                    <?php
                                    $sqlBy = 'select id,location from location_master';
                                    $sql = $conn->prepare($sqlBy);
                                    $sql->bind_param("s", $name);
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

                        <div class="input-field col s6 m6">
                            <div class="form-group">
                                <select class="form-control" name="client_name" id="client_name_el" readonly>
                                    <option value="NA">Select Client</option>
                                </select>
                                <label title="Select Client Name" for="client_name" class="active-drop-down active">Client</label>
                            </div>
                        </div>

                        <div class="input-field col s6 m6">
                            <div class="form-group">
                                <select class="form-control" name="process" id="process_el" readonly>
                                    <option value="NA">Select Process</option>
                                </select>
                                <label title="Select Process Name" for="process" class="active-drop-down active">Process</label>
                            </div>
                        </div>

                        <div class="input-field col s6 m6">
                            <div class="form-group">
                                <select class="form-control" name="subprocess" id="subprocess_el" readonly>
                                    <option value="NA">Select Subprocess</option>
                                </select>
                                <label title="Select Subprocess Name" for="subprocess" class="active-drop-down active">Subprocess</label>
                            </div>
                        </div>


                        <div class="input-field col s12 m12 right-align">
                            <button type="submit" class="btn waves-effect waves-green" name="btn_search" id="btn_search">
                                <i class="fa fa-search"></i> Search</button>
                        </div>
                    </form>
                </div>
                <div id="pnlTable">
                    <?php
                    if (isset($_POST['btn_search'])) {

                        $location1 = cleanUserInput($_POST['location1']);
                        $client_name = cleanUserInput($_POST['client_name']);
                        $process = cleanUserInput($_POST['process']);
                        $subprocess = cleanUserInput($_POST['subprocess']);

                        if ($location1 == "ALL") {
                            $sqlclient = "select t3.client_name, t2.process,t2.sub_process, t1.QualityID,t1.TrainingID,t1.OpsID,t1.HRID,t1.ITID,t1.ReportsTo from downtimereqid1 t1 join new_client_master t2 on t2.cm_id=t1.cm_id join client_master t3 on t2.client_name=t3.client_id;";
                            $stmt = $conn->prepare($sqlclient);
                            $stmt->bind_param("i", $subprocess);
                            $stmt->execute();
                        } else {
                            $sqlclient = "select t3.client_name, t2.process,t2.sub_process, t1.QualityID,t1.TrainingID,t1.OpsID,t1.HRID,t1.ITID,t1.ReportsTo from downtimereqid1 t1 join new_client_master t2 on t2.cm_id=t1.cm_id join client_master t3 on t2.client_name=t3.client_id where t1.cm_id=? ";
                            $stmt = $conn->prepare($sqlclient);
                            $stmt->bind_param("i", $subprocess);
                            $stmt->execute();
                        }

                        $result = $stmt->get_result();
                        $rowCount = $result->num_rows;
                        if ($rowCount > 0) { ?>
                            <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Process</th>
                                        <th>Subprocess</th>
                                        <th>QualityID</th>
                                        <th>TrainingID</th>
                                        <th>OpsID</th>
                                        <th>HRID</th>
                                        <th>ITID</th>
                                        <th>ReportsTo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($result as $key => $value) {
                                        // echo $ijpdata = implode(' ', $value['EmployeeID']);

                                    ?>
                                        <tr>
                                            <td><?php echo $value['client_name']; ?></td>
                                            <td><?php echo $value['process']; ?></td>
                                            <td><?php echo $value['sub_process']; ?></td>
                                            <td><?php echo $value['QualityID']; ?></td>
                                            <td><?php echo $value['TrainingID']; ?></td>
                                            <td><?php echo $value['OpsID']; ?></td>
                                            <td><?php echo $value['HRID']; ?></td>
                                            <td><?php echo $value['ITID']; ?></td>
                                            <td><?php echo $value['ReportsTo']; ?></td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                    ?>

                                </tbody>
                            </table>
                    <?php } else {
                            echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
                        }
                    } ?>
                </div>
            </div>
        </div>

    </div>
    <!--Form container End -->
</div>
<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>

<script>
    $('#btn_search').on('click', function() {
        var validate = 0;
        var alert_msg = '';

        if ($('#location1').val().replace(/^\s+|\s+$/g) == 'NA') {
            $('#location1').addClass('has-error');
            if ($('#spanlocation1').length == 0) {
                $('<span id="spanlocation1" class="help-block">Required *</span>').insertAfter('#location1');
            }
            validate = 1;
        }
        if ($('#client_name_el').val().replace(/^\s+|\s+$/g) == 'NA') {
            $('#client_name_el').addClass('has-error');
            if ($('#spanclient_name_el').length == 0) {
                $('<span id="spanclient_name_el" class="help-block">Required *</span>').insertAfter('#client_name_el');
            }
            validate = 1;
        }
        if ($('#process_el').val().replace(/^\s+|\s+$/g) == 'NA') {
            $('#process_el').addClass('has-error');
            if ($('#spanprocess_el').length == 0) {
                $('<span id="spanprocess_el" class="help-block">Required *</span>').insertAfter('#process_el');
            }
            validate = 1;
        }
        if ($('#subprocess_el').val().replace(/^\s+|\s+$/g) == 'NA') {
            $('#subprocess_el').addClass('has-error');
            if ($('#spansubprocess_el').length == 0) {
                $('<span id="spansubprocess_el" class="help-block">Required *</span>').insertAfter('#subprocess_el');
            }
            validate = 1;
        }

        if (validate == 1) {

            //alert('1');
            return false;
        }
    });

    $(document).ready(function() {
        $("#location1").change(function() {
            var location1 = $(this).val();
            if (location1 == 'ALL') {
                // $('#dform').reset();
                // $('#dform').trigger("reset");
                // location.reload();
                // $('#client_name_el option[value="ALL"]').attr('selected', 'selected');
                // $('#process_el option[value="ALL"]').attr('selected', 'selected');
                // $('#subprocess_el option[value="ALL"]').attr('selected', 'selected');

                $.ajax({
                    url: '../Controller/get_client.php',
                    type: 'GET',
                    data: {
                        location1: location1,
                    },
                    dataType: 'json',
                    success: function(response) {
                        //alert(response);

                        $("#client_name_el").html(response.client_name1ALL).prop('readonly', true);
                        $("#process_el").html(response.process1ALL).prop('readonly', true);
                        $("#subprocess_el").html(response.sub_process1ALL).prop('readonly', true);
                    }
                });
            } else {

                // $('#client_name_el option[value=""]').attr('selected', 'selected');
                // $('#process_el option[value=""]').attr('selected', 'selected');
                // $('#subprocess_el option[value=""]').attr('selected', 'selected');

                // $("#client_name_el option[value='']");
                // $("#process_el option[value='']");
                // $("#subprocess_el option[value='']");

                $.ajax({
                    url: '../Controller/get_client.php',
                    type: 'GET',
                    data: {
                        location1: location1,
                    },
                    dataType: 'json',
                    success: function(response) {
                        //alert(response);

                        $("#client_name_el").html(response.client_name1);
                        // $("#process1").html(response.process1);
                        // $("#sub_process1").html(response.sub_process1)
                    }
                });

                $("#client_name_el").change(function() {
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
                            $("#process_el").html(response.process1);
                            // $("#sub_process1").html(response.sub_process1);
                        }
                    });


                    $("#process_el").change(function() {
                        var process1 = $(this).val();
                        // alert(process1)
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
                                // alert(response);
                                $("#subprocess_el").html(response.sub_process1);

                            }
                        });
                    });
                });
            }
        });
    });
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>