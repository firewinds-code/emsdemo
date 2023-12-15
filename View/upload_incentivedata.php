<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
//Upload Excel
require_once(LIB . 'PHPExcel/IOFactory.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$__user_logid = clean($_SESSION['__user_logid']);
$__status_oh = clean($_SESSION['__status_oh']);
$__status_ah = clean($_SESSION['__status_ah']);

if ($__status_ah == $__user_logid || $__status_oh == $__user_logid) {
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}

$msgFile = '';
$insert_row = $btnUploadCheck = 0;
$count = 0;
function coordinates($x)
{
    return PHPExcel_Cell::stringFromColumnIndex($x);
}


?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">
    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Upload Incentive Data</span>
    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">
        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <!-- Header for Form If any -->
            <h4>Upload Incentive Data<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" href="../FileContainer/upload_format_Incentive.xlsx" data-position="bottom" data-tooltip="Download Format"><i class="material-icons">file_download</i></a></h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">

                <div class="input-field col s5 m5">
                    <select class="form-control" id="txt_Type_Upload" name="txt_Type_Upload">
                        <option value="Hours">Upload Incentive Data</option>
                    </select>
                    <label for="txt_Type_Upload" class="active-drop-down active">Upload For</label>
                </div>

                <div class="file-field input-field col s5 m5">
                    <div class="btn">
                        <span>Upload File</span>
                        <input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;">
                        <br>
                        <span class="file-size-text">Accepts up to 2MB</span>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path" type="text">
                    </div>
                </div>

                <div class="input-field col s12 m12 right-align">
                    <input type="submit" value="Submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green" />
                    <!-- <input type="button" value="Upload Again" name="UploadAgain" id="UploadAgain" value="Upload Again" class="btn waves-effect waves-green hidden" /> -->
                </div>
                <!--Form element model popup End-->


                <?php

                if (isset($_POST['UploadBtn'])) {
                    $btnUploadCheck = 1;
                    $target_dir = ROOT_PATH . 'Upload/';
                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                    $uploadOk = 1;
                    $uploader = clean($_SESSION['__user_logid']);
                    $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
                    // die;
                    // Check file size
                    if ($_FILES["fileToUpload"]["size"] > 5000000) {
                        echo "<script>$(function(){ toastr.error('Sorry, your file is too large " . $_FILES["fileToUpload"]["size"] . " ') }); </script>";
                        $uploadOk = 0;
                    }
                    // Allow certain file formats
                    $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    if (!in_array($_FILES['fileToUpload']['type'], $excelMimes)) {
                        // if ($_FILES['fileToUpload']['type'] != "csv" || $_FILES['fileToUpload']['type'] != "xlsx") {
                        echo "<script>$(function(){ toastr.error('Sorry, only csv and xlsx files are allowed.') }); </script>";
                        $uploadOk = 0;
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.') }); </script>";
                        // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            $document = PHPExcel_IOFactory::load($target_file);
                            // Get the active sheet as an array
                            $activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true, true, true, true, true);
                            echo "<script>$(function(){ toastr.info('Rows available In Sheet : " . (count($activeSheetData) - 1) . "') }); </script>";
                            $row_counter = 0;
                            $flag = 0;
                            // $row_counter = 0;
                            foreach ($activeSheetData as $row) {
                                $dt = explode(' ', $row['A']);
                                $newEmpId = str_replace("EMP", '', $dt[0]);
                                $empmap = "select cm_id from employee_map where EmployeeID=?";
                                $stmt = $conn->prepare($empmap);
                                $stmt->bind_param("s", $newEmpId);
                                $stmt->execute();
                                $empresult = $stmt->get_result();
                                $emprow = $empresult->fetch_assoc();
                                $cmid = $emprow['cm_id'];
                                if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
                                    $myDB = new MysqliDb();
                                    $flag = $myDB->query('call insert_otdata("' . $row['A'] . '","' . $row['B'] . '","' . $cmid . '","' . $row['C'] . '","' . $row['D'] . '","' . $row['E'] . '","' . $uploader . '")');
                                    $mysql_error = $myDB->getLastError();
                                    if (empty($mysql_error)) {
                                        $count++;
                                    }
                                }
                                $row_counter++;
                            }

                            if ($count > 0) {
                                echo "<script>$(function(){ toastr.success('The file has been uploaded') }); </script>";
                                echo "<script>$(function(){ toastr.success('Total " . $count . " Record are Updated Sucessfully.')}); </script>";
                            } else
                                echo "<script>$(function(){ toastr.error('No Data Updated') }); </script>";

                            if (file_exists($target_dir . basename($_FILES["fileToUpload"]["name"]))) {
                                $ext = pathinfo($target_file, PATHINFO_EXTENSION);
                                rename($target_file, $target_dir . time() . '_' . $uploader . "_Sal_" . $_POST['txt_Type_Upload'] . "." . $ext);
                            }
                        } else {
                            echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file') }); </script>";
                        }
                    }
                }

                ?>
                <!--Form container End -->
            </div>
            <!--Main Div for all Page End -->
        </div>
        <!--Content Div for all Page End -->
    </div>

    <script>
        $(function() {
            $('#UploadAgain').click(function() {
                $('.pannel_upload').removeClass('hidden');
                $('#UploadAgain').addClass('hidden');
                $('#txt_Type_Upload').val('NA');
            });
            $('#UploadBtn').click(function() {
                var validate = 0;
                var alert_msg = '';

                if ($('#fileToUpload').val() == '') {
                    validate = 1;
                    alert_msg += '<li> First Choose File  </li>';
                }

                if (validate == 1) {
                    $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                    $('#alert_message').show().attr("class", "SlideInRight animated");
                    $('#alert_message').delay(10000).fadeOut("slow");
                    return false;
                } else {
                    $('#UploadBtn').hide();
                    $('#alert_msg').html('<ul class="text-warning"> Wait ! Data Uploading In Process Please Do not Skip or shut down the page ...</ul>');
                    $('#alert_message').show();
                }

            });
            <?php
            if ($btnUploadCheck > 0) {
            ?>
                $('.pannel_upload').addClass('hidden');
                $('#UploadAgain').removeClass('hidden');
            <?php
            }

            ?>

        });
    </script>

    <?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>