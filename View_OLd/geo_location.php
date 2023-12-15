g<?php
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
    if (isset($_POST['btn_view'])) {
        if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
            $date_To = cleanUserInput($_POST['dateTo']);
            $date_From = cleanUserInput($_POST['dateFrom']);
        }
    } else {
        $date_To = date('Y-m-d', time());
        $date_From = date('Y-m-d', time());
    }
    ?>

<script>
    //contain load event for data table and other importent rand required trigger event and searches if any
    $(document).ready(function() {
        $('#dateFrom,#dateTo').datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });
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


<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Geo Location</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <h4>Geo Location</h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php

                $_SESSION["token"] = csrfToken();
                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
                <br><br>

                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s3 m3">

                        <input type="text" name="dateFrom" id="dateFrom" value="<?php echo $date_From; ?>" />
                    </div>
                    <div class="input-field col s3 m3">

                        <input type="text" name="dateTo" id="dateTo" value="<?php echo $date_To; ?>" />
                    </div>

                    <div class="input-field col s2 m2">

                        <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>

                    </div>
                </div>


                <div id="pnlTable">
                    <?php

                    $selectQry = "SELECT t1.EmployeeID,t2.EmpName,t3.location,t6.client_name,t5.process,t5.sub_process, address, country, city, state, tehsil, district, zip, latitude, longitude, latlongaddress,  ifnull(t1.modifiedon, t1.createdon) as createdon from address_geo t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID  join location_master t3 on t2.loc=t3.id join employee_map t4 on t1.EmployeeID=t4.EmployeeID join new_client_master t5 on t4.cm_id=t5.cm_id join client_master t6 on t5.client_name=t6.client_id WHERE cast(t1.createdon as date) BETWEEN ? AND ? ";
                    // $myDB = new MysqliDb();
                    // $result = $myDB->query($selectQry);

                    $stmt = $conn->prepare($selectQry);
                    $stmt->bind_param("ss", $date_From, $date_To);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $count = $result->num_rows;
                    $my_error = $myDB->getLastError();
                    if ($result->num_rows > 0) {
                        // if (count($result) > 0 && $result) {
                    ?>
                        <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>

                                    <th>EmployeeID</th>
                                    <th>Employee Name</th>
                                    <th>Location</th>
                                    <th>Client</th>
                                    <th>Process</th>
                                    <th>Sub Process</th>
                                    <th>Address</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>District</th>
                                    <th>Tehsil</th>
                                    <th>Zip</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>LatLongAddress</th>
                                    <th>createdon</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($result as $key => $value) { ?>
                                    <tr>

                                        <td><?php echo $value['EmployeeID']; ?></td>
                                        <td><?php echo $value['EmpName']; ?></td>
                                        <td><?php echo $value['location']; ?></td>
                                        <td><?php echo $value['client_name']; ?></td>
                                        <td><?php echo $value['process']; ?></td>
                                        <td><?php echo $value['sub_process']; ?></td>
                                        <td><?php echo $value['address']; ?></td>
                                        <td><?php echo $value['country']; ?></td>
                                        <td><?php echo $value['state']; ?></td>
                                        <td><?php echo $value['city']; ?></td>
                                        <td><?php echo $value['district']; ?></td>
                                        <td><?php echo $value['tehsil']; ?></td>
                                        <td><?php echo $value['zip']; ?></td>
                                        <td><?php echo $value['latitude']; ?></td>
                                        <td><?php echo $value['longitude']; ?></td>
                                        <td><?php echo $value['latlongaddress']; ?></td>
                                        <td><?php echo $value['createdon']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php
                        echo "<script>$(function(){ toastr.success('Data Found'); }); </script>";
                    } else {
                        echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>