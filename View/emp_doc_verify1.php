<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 

ini_set('memory_limit', '300M');
require(ROOT_PATH . 'AppCode/nHead.php');
$dept = $OutOJT = '';

if (isset($_SESSION)) {
    if (!isset($_SESSION['__user_logid'])) {
        $location = URL . 'Login';
        header("Location: $location");
    } else if (!($_SESSION["__ut_temp_check"] == 'COMPLIANCE' || $_SESSION["__user_type"] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE10091236' || $_SESSION['__user_logid'] == 'CE01145570' || $_SESSION['__user_logid'] == 'CE03146043')) {
        die("access denied ! It seems like you try for a wrong action.");
        exit();
    }
} else {
    $location = URL . 'Login';
    header("Location: $location");
}

if (isset($_SESSION)) {
    if (!isset($_SESSION['__user_logid'])) {
        $location = URL . 'Login';
        header("Location: $location");
        exit();
    } else {
        $isPostBack = false;

        $referer = "";
        $alert_msg = "";
        $thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
        }

        if ($referer == $thisPage) {
            $isPostBack = true;
        }

        if ($isPostBack && isset($_POST['txt_dept'])) {
            if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
                $edu_type = cleanUserInput($_POST['txt_dept']);
                $editEmpID = cleanUserInput($_POST['editEmpID']);
                $verified_flag = cleanUserInput($_POST['verifiedFlag']);
                $DocPath = cleanUserInput($_POST['DocPath']);
                $eduTypeEdit = cleanUserInput($_POST['eduTypeEdit']);
                $eduTypeNew = cleanUserInput($_POST['eduType']);
                $eduTypeNew = cleanUserInput($_POST['edutype_new']);
            }
        }
    }
} else {
    $location = URL . 'Login';
    header("Location: $location");
    exit();
}
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($editEmpID)) {

    $dir_location = $loc = $Location = '';
    $EmployeeID = strtoupper(clean($editEmpID));
    $txt_edu_Spc  = clean($_POST['txt_edu_Spc']);
    $txt_edu_bu  = clean($_POST['txt_edu_bu']);
    $txt_edu_college  = clean($_POST['txt_edu_college']);
    $txt_edu_pass  = clean($_POST['txt_edu_pass']);
    $txt_edu_name  = clean($_POST['txt_edu_name']);

    $sql = 'select location from personal_details where EmployeeID = ?';
    $selectQ = $conn->prepare($sql);
    $selectQ->bind_param("s", $EmployeeID);
    $selectQ->execute();
    $results = $selectQ->get_result();
    $result = $results->fetch_row();
    if ($results->num_rows > 0) {
        $loc = clean($result[0]);
    }
    if ($loc == "1" || $loc == "2") {
        $dir_location = 'Edu/';
        $Location = "";
    } else if ($loc == "3") {
        $dir_location = 'Meerut/Edu/';
        $Location = "Meerut/";
    } else if ($loc == "4") {
        $dir_location = 'Bareilly/Edu/';
        $Location = "Bareilly/";
    } else if ($loc == "5") {
        $dir_location = 'Vadodara/Edu/';
        $Location = "Vadodara/";
    } else if ($loc == "6") {
        $dir_location = 'Manglore/Edu/';
        $Location = "Manglore/";
    } else if ($loc == "7") {
        $dir_location = 'Bangalore/Edu/';
        $Location = "Bangalore/";
    } else if ($loc == "8") {
        $dir_location = 'Nashik/Edu/';
        $Location = "Nashik/";
    } else if ($loc == "9") {
        $dir_location = 'Anantapur/Edu/';
        $Location = "Anantapur/";
    }

    $sourcePath =  ROOT_PATH . "emp_edu/" . $DocPath;
    $targetPath = ROOT_PATH . "Education";
    $ext = pathinfo(basename($DocPath), PATHINFO_EXTENSION);

    if ($verified_flag == "1") {

        if ($eduTypeNew == "Graduation" || $eduTypeNew == "Post Graduation") {

            $getData = "select edu_level,EmployeeID,edu_file from education_details where edu_level =? and EmployeeID = ?";
            $stmt = $conn->prepare($getData);

            $stmt->bind_param("ss", $eduTypeNew, $editEmpID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $updateStmt = $conn->prepare("update education_details set edu_file=?,specialization=?,board=?,college=?,passing_year=?,modifiedon=now(),edu_name=? where EmployeeID=? and edu_level=?");
                $updateStmt->bind_param("ssssssss", $DocPath, $txt_edu_Spc, $txt_edu_bu, $txt_edu_college, $txt_edu_pass, $txt_edu_name, $editEmpID, $eduTypeNew);
                $updateStmt->execute();
            } else {
                $insertStmt = $conn->prepare("insert into education_details (EmployeeID,edu_level,edu_file,specialization,board,college,passing_year,edu_name) values(?,?,?,?,?,?,?,?)");

                $insertStmt->bind_param("ssssssss", $editEmpID, $eduTypeNew, $DocPath, $txt_edu_Spc, $txt_edu_bu, $txt_edu_college, $txt_edu_pass, $txt_edu_name);
                $insertStmt->execute();
            }
            $updateStmt = $conn->prepare("UPDATE emp_edu SET verified_flag = ?,edu_type=? WHERE EmpID = ?");
            $updateStmt->bind_param("iss", $verified_flag, $eduTypeNew, $editEmpID);
            if ($updateStmt->execute()) {
                echo "<script>$(function(){ toastr.success('Update verification Successfully'); }); </script>";
                if ($loc == "1" || $loc == "2") {
                    $targetPath = ROOT_PATH . "Edu/" . $DocPath;
                } else {
                    //$targetPath = ROOT_PATH."Meerut/Education/".basename($DocPath);
                    $targetPath = ROOT_PATH . $dir_location . $DocPath;
                }
                //echo  $DocPath;
                if ($DocPath != "") {
                    $sourcePath =  ROOT_PATH . "emp_edu/" . $DocPath;
                    $filename = $DocPath;
                    $target_dir = ROOT_PATH . $dir_location;
                    $target_file = $target_dir . $filename;
                    if (rename($sourcePath, $target_file)) {
                        echo "<script>$(function(){ toastr.success('File Moved Succesfully'); }); </script>";
                    } else {
                        echo "<script>$(function(){ toastr.error('File Not Moved'); }); </script>";
                    }
                    echo "<br>";
                } else {
                    echo "<script>$(function(){ toastr.error('File Not Found'); }); </script>";
                }
            } else {
                echo "<script>$(function(){ toastr.error('Error,updating verification status.'); }); </script>";
            }
        } else {
            $updateStmt = $conn->prepare("UPDATE emp_edu SET verified_flag = ?,edu_type=? WHERE EmpID = ?");
            $updateStmt->bind_param("iss", $verified_flag, $eduTypeNew, $editEmpID);
            if ($updateStmt->execute()) {
                echo "<script>$(function(){ toastr.success('Update verification Successfully'); }); </script>";
            } else {
                echo "<script>$(function(){ toastr.error('Error,updating verification status.'); }); </script>";
            }
        }
    } else {

        $updateStmt = $conn->prepare("UPDATE emp_edu SET verified_flag = ? WHERE EmpID = ?");
        $updateStmt->bind_param("is", $verified_flag, $editEmpID);
        if ($updateStmt->execute()) {
            echo "<script>$(function(){ toastr.success('Update verification Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){ toastr.error('Error,updating verification status.'); }); </script>";
        }
    }
}

?>
<div id="content" class="content">
    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Document Verification</span>
    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">
        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <!-- Header for Form If any -->
            <h4>Document Verification</h4>
            <div class="schema-form-section row">
                <?php
                $_SESSION["token"] = csrfToken();
                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s10 m10">
                        <select name="txt_dept" id="txt_dept">
                            <option value="null">---Select---</option>
                            <option value="ALL">ALL</option>
                            <option value="Graduation">Graduation</option>
                            <option value="Post Graduation">Post Graduation</option>
                            <option value="Pursuing Graduation">Pursuing Graduation</option>

                        </select>
                    </div>
                    <div class="input-field col s2 m2">
                        <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
                    </div>
                </div>
                <?php
                if (isset($_POST['btn_view'])) {
                    if ($edu_type == "ALL") {
                        $getData = "SELECT t1.EmpID,EmpName, edu_type, filename, flag, verified_flag FROM emp_edu t1 join EmpID_Name t2 on t1.EmpID=t2.EmpID WHERE flag=1 and verified_flag in (0,3) and edu_type!='Under Graduate'";
                    } else {
                        $getData = "SELECT t1.EmpID,EmpName, edu_type, filename, flag, verified_flag FROM emp_edu t1 join EmpID_Name t2 on t1.EmpID=t2.EmpID WHERE edu_type = ? and flag=1 and verified_flag in (0,3)";
                    }

                    $stmt = $conn->prepare($getData);

                    $stmt->bind_param("s", $edu_type);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo '<div class="had-container pull-left row card" style="margin-top: 10px; width: 100%; padding: 15px;">
                            <div>
                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>EmployeeID</th>
                                            <th>Employee Name</th>
                                            <th>Education Type</th>
                                            <th>Document</th>
                                            <th class="hidden">Document Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                        while ($row = $result->fetch_assoc()) {
                            $fPath = ROOT_PATH . "emp_edu/" . $row['filename'];
                            echo '<tr>';
                            echo '<td>' . $row['EmpID'] . '</td>';
                            echo '<td>' . $row['EmpName'] . '</td>';
                            echo '<td>' . $row['edu_type'] . '</td>';
                            echo '<td class="manage_item">';
                            if ($row['filename'] != '') {
                                echo '<a class="material-icons download_item imgBtn imgBtnDownload tooltipped" href="../emp_edu/' . $row['filename'] . '" download data-position="left" data-tooltip="Download File">edu_file_download</a>';
                                echo '</td>';
                            } else {
                                echo '</td>';
                            }

                            echo '<td class="hidden">' . $row['filename'] . '</td>';
                            echo '<td class="manage_item"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="EditData(this);" data-position="left" data-tooltip="Edit">edu_edit</i></td>';
                            echo '</tr>';
                        }

                        echo '</tbody>
                            </table>
                        </div>
                    </div>';
                    } else {
                        echo "<script>$(function(){ toastr.error('No data found.'); }); </script>";
                    }
                }
                ?>




                <!-- Modal -->
                <div id="myModal_content" class="modal">

                    <!-- Modal content-->
                    <div class="modal-content" style="height: 500px;">
                        <h4 class="col s12 m12 model-h4">Verify Document</h4>
                        <div class="modal-body" style="max-height: 100%; overflow: auto; height: 400px;">


                            <input type="hidden" name="editEmpID" id="editEmpID" value="">
                            <input type="hidden" name="DocPath" id="DocPath" value="">
                            <input type="hidden" name="eduTypeEdit" id="eduTypeEdit" value=<?php echo $edu_type ?>>

                            <div class="col s12 m12">
                                <div class="row">
                                    <div class="input-field col s6 m6">
                                        <select id="verifiedFlag" name="verifiedFlag" required>
                                            <option value="null">--Select--</option>
                                            <option value="1">Verified</option>
                                            <option value="2">Not Verified</option>
                                        </select>
                                        <label for="txt_location" class="active-drop-down active">Select Status</label>
                                    </div>

                                    <div class="input-field col s6 m6">
                                        <select id="edutype_new" name="edutype_new" required onchange="javascript:return getEduName(this);">
                                            <option value="Graduation">Graduation</option>
                                            <option value="Post Graduation">Post Graduation</option>
                                            <option value="Pursuing Graduation">Pursuing Graduation</option>
                                        </select>
                                        <label for="edutype_new" class="active-drop-down active">Select Education Type</label>
                                    </div>


                                </div>
                                <div class="row">

                                    <div class="input-field col s6 m6">
                                        <select id="txt_edu_name" name="txt_edu_name">

                                        </select>
                                        <label for="txt_edu_name" class="active-drop-down active">Education Name *</label>
                                    </div>

                                    <div class="input-field col s6 m6">
                                        <select id="txt_edu_Spc" name="txt_edu_Spc">
                                            <option value="null">---Select---</option>
                                            <?php
                                            $myDB = new MysqliDb();
                                            $rst_level = $myDB->query('select * from education_specilization order by specilization ');
                                            if ($rst_level) {
                                                foreach ($rst_level as $kye => $value) {
                                            ?>
                                                    <option <?php if ($specialization == $value['specilization']) echo "selected"; ?>><?php echo $value['specilization']; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <label for="txt_edu_Spc" class="active-drop-down active">Specialization *</label>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="input-field col s6 m6">
                                        <select id="txt_edu_bu" name="txt_edu_bu">
                                            <option value="null">---Select---</option>
                                            <?php
                                            $myDB = new MysqliDb();
                                            $rst_level = $myDB->query('select * from education_board order by board');
                                            if ($rst_level) {
                                                foreach ($rst_level as $kye => $value) {
                                            ?>
                                                    <option <?php if ($board == $value['board']) {
                                                                echo "selected";
                                                            } ?>> <?php echo $value['board']; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <label for="txt_edu_bu" class="active-drop-down active">Board / University *</label>
                                    </div>
                                    <div class="input-field col s6 m6">
                                        <input type="text" id="txt_edu_college" name="txt_edu_college" value="<?php echo $college; ?>" />
                                        <label for="txt_edu_college">College / School *</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6 m6">
                                        <select id="txt_edu_pass" name="txt_edu_pass">
                                            <option value="null">---Select---</option>
                                            <?php for ($i = 0; $i <= date("Y") - 1990 + 4; $i++) { ?>
                                                <option><?php echo 1990 + $i; ?></option>
                                            <?php  } ?>
                                        </select>
                                        <label for="txt_edu_pass" class="active-drop-down active">Passing Year *</label>
                                    </div>
                                </div>


                            </div>
                            <div class="input-field col s12 m12 right-align">
                                <input type="hidden" class="form-control hidden" id="h_dtid" name="h_dtid" />
                                <input type="hidden" class="form-control hidden" id="hid_Client_ID" name="hid_Client_ID" />
                                <input type="hidden" class="form-control hidden" id="hid_Excp_ID" name="hid_Excp_ID" />
                                <button type="submit" name="btn_Client_Save" id="btn_Client_Save" class="btn waves-effect waves-green">Update</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Form container End -->
        </div>
        <!-- Main Div for all Page End -->
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.modal').modal();


    });
    $('#verifiedFlag').change(function() {
        $('#txt_edu_college').closest('div').remove('has-error');
    });

    $('#btn_view').click(function() {
        var validate = 0;
        var alert_msg = '';
        // $('#txt_dept').closest('div').removeClass('has-error');

        if ($('#txt_dept').val() === 'null') {
            $('#txt_dept').closest('div').addClass('has-error');
            validate = 1;
            alert_msg += '<li> First Select Upload For  </li>';
        }



        if (validate == 1) {
            $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
            $('#alert_message').show().attr("class", "SlideInRight animated");
            $('#alert_message').delay(10000).fadeOut("slow");
            return false;
        }

    });

    $('#btn_Client_Save').click(function() {

        var validate = 0;
        var alert_msg = '';
        $('#verifiedFlag').closest('div').removeClass('has-error');


        if ($('#verifiedFlag').val() === 'null') {
            $('#verifiedFlag').closest('div').addClass('has-error');
            validate = 1;
            alert_msg += '<li> First Select Upload For  </li>';
        } else if ($('#verifiedFlag').val() === '1') {
            if ($('#txt_edu_Spc').val() === 'null') {
                $('#txt_edu_Spc').closest('div').addClass('has-error');
                validate = 1;
                alert_msg += '<li> First Select Upload For  </li>';
            }

            if ($('#txt_edu_bu').val() === 'null') {
                $('#txt_edu_bu').closest('div').addClass('has-error');
                validate = 1;
                alert_msg += '<li> First Select Upload For  </li>';
            }
            if ($('#txt_edu_pass').val() === 'null') {
                $('#txt_edu_pass').closest('div').addClass('has-error');
                validate = 1;
                alert_msg += '<li> First Select Upload For  </li>';
            }
            if ($('#txt_edu_name').val() === 'NA') {
                $('#txt_edu_name').closest('div').addClass('has-error');
                validate = 1;
                alert_msg += '<li> First Select Upload For  </li>';
            }

            if ($('#txt_edu_college').val() === '') {
                $('#txt_edu_college').closest('div').addClass('has-error');
                validate = 1;
                alert_msg += '<li> First Select Upload For  </li>';
            }
            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(10000).fadeOut("slow");
                return false;
            }
        }

        if (validate == 1) {
            $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
            $('#alert_message').show().attr("class", "SlideInRight animated");
            $('#alert_message').delay(10000).fadeOut("slow");
            return false;
        }

    });

    function getEduName(el) {
        var eduType = el.value;
        if (eduType == 'Pursuing Graduation') {
            eduType = 'Graduation';
        }

        $.ajax({
            url: "../Controller/getEducationName.php?ID=" + eduType,
            success: function(result) {
                $('#txt_edu_name').empty().append(result);
                $('select').formSelect();
                // $('#txt_edu_Name_1').val($('#txt_eduName').val());
                // $('#txt_eduName').val('NA');

                // if ($('#txt_edu_lvl_1').val() == 'Other') {
                //     $('#txt_edu_othlvl_1').removeClass('hidden');
                // } else if ($('#txt_edu_lvl_1').val() == 'NA') {

                //     $('#txt_edu_othlvl_1').addClass('hidden').val('');
                //     $('#txt_edu_othrName_1').addClass('hidden').val('');

                // }

            }
        });
    }



    function EditData(el, fileName) {
        var empId = $(el).closest('tr').find('td:first-child').text();
        var docPath = $(el).closest('tr').find('td:nth-child(5)').text();
        var eduType = $(el).closest('tr').find('td:nth-child(3)').text();
        $('#editEmpID').val(empId);
        $('#edutype_new').val(eduType);
        $('#DocPath').val(docPath);
        if (eduType == 'Pursuing Graduation') {
            eduType = 'Graduation';
        }

        $.ajax({
            url: "../Controller/getEducationName.php?ID=" + eduType,
            success: function(result) {
                $('#txt_edu_name').empty().append(result);
                $('select').formSelect();
                // $('#txt_edu_Name_1').val($('#txt_eduName').val());
                // $('#txt_eduName').val('NA');

                // if ($('#txt_edu_lvl_1').val() == 'Other') {
                //     $('#txt_edu_othlvl_1').removeClass('hidden');
                // } else if ($('#txt_edu_lvl_1').val() == 'NA') {

                //     $('#txt_edu_othlvl_1').addClass('hidden').val('');
                //     $('#txt_edu_othrName_1').addClass('hidden').val('');

                // }

            }
        });


        $('#myModal_content').modal('open');
    }
</script>
<script>
    $(function() {
        $('#myTable').DataTable({
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
                    columns: [0, 1, 2, 4]
                },
                title: 'Report'
            }, 'pageLength'],
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
        });
        $('.buttons-copy').attr('id', 'buttons_copy');
        $('.buttons-csv').attr('id', 'buttons_csv');
        $('.buttons-excel').attr('id', 'buttons_excel');
        $('.buttons-pdf').attr('id', 'buttons_pdf');
        $('.buttons-print').attr('id', 'buttons_print');
        $('.buttons-page-length').attr('id', 'buttons_page_length');
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
</div>