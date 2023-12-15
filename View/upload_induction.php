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
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$msgFile = '';
$MSG = "";
$insert_row = $btnUploadCheck = 0;

$UploadBtn = isset($_POST['UploadBtn']);
if (isset($_POST['UploadBtn'])) {
	//echo $_SESSION["token"];
	//echo $_POST["token"];
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$btnUploadCheck = 1;
		$target_dir = ROOT_PATH . 'Upload/';
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$FileType = pathinfo($target_file, PATHINFO_EXTENSION);

		$date1_ = new DateTime();
		$date2 = $date1_->format('YmdHis');
		$str = 'Induction';
		$string = str_replace(' ', '', $str);
		$FileName = $string . '_' . $date2 . '.' . $FileType;
		$target_file = $target_dir . basename($FileName);

		// Check if file already exists
		/*if (file_exists($target_file)) {
				    		$msgFile =$msgFile."<p  class='msgFile text-danger'>Sorry, file already exists.</p>";
				   			 $uploadOk = 0;
							}*/
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 5000000) {
			echo "<script>$(function(){ toastr.info('Sorry, your file is too large.'); }); </script>";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if ($FileType != "xlsx") {
			echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed.'); }); </script>";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "<script>$(function(){ toastr.info('Sorry, your file was not uploaded.'); }); </script>";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

				//echo "<script>$(function(){ toastr.success('The file ".basename( $_FILES["fileToUpload"]["name"])." has been uploaded'); }); </script>";
				$document = PHPExcel_IOFactory::load($target_file);
				// Get the active sheet as an array
				$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
				//var_dump($activeSheetData);
				//echo "<script>$(function(){ toastr.info('Rows available In Sheet : ".(count($activeSheetData)-1)."'); }); </script>";
				$row_counter = 0;
				foreach ($activeSheetData as $row) {

					if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
						$sql_p_inert = "select t1.EmpID from induction_master t1  join employee_map t2 on t1.EmpID=t2.EmployeeID where EmpFlag=0 and emp_status='Active' and t1.EmpID=? ";
						$select = $conn->prepare($sql_p_inert);
						$select->bind_param("s", clean($row['A']));
						$select->execute();
						$rst = $select->get_result();
						// $rst = $myDB->rawQuery($sql_p_inert);
						// $mysqlerror = $myDB->getLastError();

						if ($rst->num_rows > 0) {

							$hrID = clean($_SESSION['__user_logid']);
							$emp_action = "Update induction_master set EmpFlag=1, HRID=?, HR_modified=now() where EmpID=?";
							$upQ = $conn->prepare($emp_action);
							$upQ->bind_param("ss", $hrID, clean($row['A']));
							$upQ->execute();
							$result = $upQ->get_result();

							if ($upQ->affected_rows === 1)
								$insert_row = $insert_row + 1;
						}
					}
					$row_counter++;
				}
				// die;
				$MSG = " toastr.success('" . $insert_row . " Records Uploaded Sucessfully!');";
				//$MSG="<script>$(function(){ toastr.success('No of Row Uploaded - ".$insert_row." has been uploaded'); }); </script>";
			} else {
				$MSG = "toastr.error('Sorry, there was an error uploading your file.');";
				//echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.'); }); </script>";
			}
		}
	}
}
echo "<script>$(function(){  $MSG }); </script>";

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Upload Induction Data</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload Induction Data</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div class="file-field input-field col s12 m12">
					<div class="btn">
						<span>Upload File</span>
						<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;">
						<br>
						<span class="file-size-text">Accepts up to 2MB</span>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path" type="text" style="">
					</div>
				</div>

				<div class="input-field col s12 m12 right-align">
					<input type="submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green" />
				</div>

				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<div id="pnlTable">


				</div>
				<!--Reprot / Data Table End -->

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php
include(ROOT_PATH . 'AppCode/footer.mpt');
die;
?>