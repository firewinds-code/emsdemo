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
$user_type = clean($_SESSION['__user_type']);

$EmployeeID = clean($_SESSION['__user_logid']);


if ($user_type == 'ADMINISTRATOR' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE12102224' || $EmployeeID == 'CE01145570' || $EmployeeID == 'CE03146043') {
    // proceed further
} else {
    $location = URL . 'Error';
    header("Location: $location");
    exit();
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Approver Matrix</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Approver Matrix </h4>

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

                    <div class=" bylocation">
                        <div class="input-field col s4 m4">
                            <select class="" id="location1" name="location1" title="Select Location">
                                <option id="location1" value="NA">Select Location</option>
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

                    <div class="input-field col s4 m4">
                        <div class="form-group">
                            <select class="form-control" name="client_name" id="client_name_el">
                                <option value=" NA">Select Client</option>

                            </select>
                            <label title="Select Client Name" for="client_name" class="active-drop-down active">Client</label>
                        </div>
                    </div>

                    <div class="input-field col s4 m4">
                        <div class="form-group">
                            <select class="form-control" name="process" id="process_el">
                                <option value="NA">Select Process</option>
                            </select>
                            <label title="Select Process Name" for="process" class="active-drop-down active">Process</label>
                        </div>
                    </div>

                    <div class="input-field col s4 m4">
                        <div class="form-group">
                            <select class="form-control" name="subprocess" id="subprocess_el">
                                <option value="NA">Select Subprocess</option>
                            </select>
                            <label title="Select Subprocess Name" for="subprocess" class="active-drop-down active">Subprocess</label>
                        </div>
                    </div>

                    <div class="input-field col s4 m4">
                        <div class="form-group">
                            <select class="form-control" name="type" id="type">
                                <option value="NA">Select Type</option>
                                <option value="Exception">Exception</option>
                                <option value="Leave">Leave</option>
                            </select>
                            <label title="Select Type Name" for="type" class="active-drop-down active">Type</label>
                        </div>
                    </div>


                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="btn_search" id="btn_search">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
                <div id="pnlTable">
                    <?php
                    if (isset($_POST['btn_search'])) {

                        $client_name = cleanUserInput($_POST['client_name']);
                        $process = cleanUserInput($_POST['process']);
                        $subprocess = cleanUserInput($_POST['subprocess']);
                        $type = cleanUserInput($_POST['type']);

                        $sqlclient = "select t1.EmployeeID,t2.EmpName,t5.Designation,t7.client_name,t6.process,t6.sub_process,t1.l1empid,t1.l1name,t1.l2empid,t1.l2name from module_master_new  t1
                        left join EmpID_Name t2 on t1.EmployeeID=t2.EmpID
                        left join employee_map t3 on t2.EmpID=t3.EmployeeID
                        left join df_master t4 on t4.df_id=t3.df_id
                        left join designation_master t5 on t5.ID=t4.des_id
                        left join new_client_master t6 on t6.cm_id=t1.cm_id
                        left join client_master t7 on t7.client_id=t6.client_name where t1.cm_id='" . $subprocess . "' and t1.module_name='" . $type . "' ";
                        $stmt = $conn->prepare($sqlclient);
                        // $stmt->bind_param("is", $subprocess, $type);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $rowCount = $result->num_rows;
                        if ($rowCount > 0) {
                    ?>
                            <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>EmployeeID</th>
                                        <th>Employee Name</th>
                                        <th>Designation</th>
                                        <th>Client</th>
                                        <th>Process</th>
                                        <th>Subprocess</th>
                                        <th>l1empid</th>
                                        <th>l1name</th>
                                        <th>l2empid</th>
                                        <th>l2name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($result as $key => $value) {
                                        // echo $ijpdata = implode(' ', $value['EmployeeID']);

                                    ?>
                                        <tr>
                                            <td class="empid"><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['EmpName']; ?></td>
                                            <td><?php echo $value['Designation']; ?></td>
                                            <td><?php echo $value['client_name']; ?></td>
                                            <td><?php echo $value['process']; ?></td>
                                            <td><?php echo $value['sub_process']; ?></td>
                                            <td><?php echo $value['l1empid']; ?></td>
                                            <td><?php echo $value['l1name']; ?></td>
                                            <td><?php echo $value['l2empid']; ?></td>
                                            <td><?php echo $value['l2name']; ?></td>
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
                        //alert(response);
                        $("#subprocess_el").html(response.sub_process1);

                    }
                });
            });
        });
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>