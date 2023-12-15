<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
require_once(LIB . 'PHPExcel/IOFactory.php');

if (isset($_SESSION)) {
    if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE01145570') {
        // proceed further
    } else {
        $location = URL;
        echo "<script>location.href='" . $location . "'</script>";
    }
} else {
    $location = URL . 'Login';
    header("Location: $location");
}

date_default_timezone_set('Asia/Kolkata');
if (isset($_POST['UploadBtn'])) {
    $btnUploadCheck = 1;
    $target_dir = ROOT_PATH . 'Upload/';
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // echo "<pre>";
    // print_r($_FILES);
    // die;
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.'); }); </script>";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<script>$(function(){ toastr.success('The file has been uploaded'); }); </script>";
            $document = PHPExcel_IOFactory::load($target_file);
            // Get the active sheet as an array
            $activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
            // var_dump($activeSheetData);
            echo "<script>$(function(){ toastr.success('Rows available In Sheet ); }); </script>";
            $row_counter = 0;

            foreach ($activeSheetData as $row) {
                if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
                    $sqlinsert = 'call health_insurence_manual("' . strtoupper($row['A']) . '","' . $_SESSION['__user_logid'] . '")';
                    // $sqlinsert = 'insert into health_insurence_manual(EmployeeID)values("' . strtoupper($row['A']) . '")';
                    $myDB = new MysqliDb();
                    $result = $myDB->rawQuery($sqlinsert);
                    $mysqlerror = $myDB->getLastError();
                    if (empty($mysqlerror)) {
                        echo "<script>$(function(){ toastr.error('Error In Query); }); </script>";
                    }
                }
                $row_counter++;
            }

            echo "<script>$(function(){ toastr.success('No. of Row Uploaded); }); </script>";
            if (file_exists($target_dir . basename($_FILES["fileToUpload"]["name"]))) {
                $ext = pathinfo($target_file, PATHINFO_EXTENSION);
                rename($target_file, $target_dir . time() . "health." . $ext);
            }
        } else {
            echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file'); }); </script>";
        }
    }
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Upload Insurence</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Upload Insurence</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <div class="file-field input-field col s12 m12">
                    <div class="btn">
                        <span>Upload File</span>
                        <input type="file" name="fileToUpload" id="fileToUpload" style="text-indent: -99999em;">
                        <br>
                        <span class="file-size-text">Accepts up to 2MB</span>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path" type="text">
                    </div>

                </div>

                <div class="input-field col s12 m12 right-align">
                    <input type="submit" value="Submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green" />
                    <!--<input type="button" value="Upload Again" name="UploadAgain" id="UploadAgain" value="Upload Again" class="btn waves-effect waves-green"/>-->
                </div>


                <!--Form container End -->
            </div>
            <!--Main Div for all Page End -->
        </div>
        <!--Content Div for all Page End -->
    </div>
    <?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>