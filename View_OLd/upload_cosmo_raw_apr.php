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
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;

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
			$referer = clean($_SERVER['HTTP_REFERER']);
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

function coordinates($x)
{
	return PHPExcel_Cell::stringFromColumnIndex($x);
}


if (isset($_POST['UploadBtn'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$btnUploadCheck = 1;
		$target_dir = ROOT_PATH . 'Upload/';
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$uploader = $_SESSION['__user_logid'];
		$FileType = pathinfo($target_file, PATHINFO_EXTENSION);
		$noData_Uploadfor = '';
		$count = 0;
		$mysql_error = '';
		$validate = 0;
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 5000000) {
			echo "<script>$(function(){ toastr.error('Sorry, your file is too large " . $_FILES["fileToUpload"]["size"] . " ') }); </script>";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if ($FileType != "xlsx") {
			echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed.') }); </script>";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.') }); </script>";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				//echo "<script>$(function(){ toastr.error('The file ".basename( $_FILES["fileToUpload"]["name"])." has been uploaded') }); </script>";
				$document = PHPExcel_IOFactory::load($target_file);
				// Get the active sheet as an array
				$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);

				//print_r($activeSheetData.'<br/>');
				echo "<script>$(function(){ toastr.info('Rows available In Sheet : " . (count($activeSheetData) - 1) . "') }); </script>";
				$row_counter = 0;
				$flag = 0;

				$row_counter = 0;
				foreach ($activeSheetData as $row) {

					if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
						//echo 'call insert_raw_cosmo_apr("'.strtoupper($row['A']).'","'.strtoupper($row['B']).'","'.strtoupper($row['C']).'","'.strtoupper($row['D']).'","'.strtoupper($row['E']).'","'.strtoupper($row['F']).'")';						 											
						$myDB = new MysqliDb();
						$ds = $myDB->query('call insert_raw_cosmo_apr("' . strtoupper($row['A']) . '","' . strtoupper($row['B']) . '","' . strtoupper($row['C']) . '","' . strtoupper($row['D']) . '","' . strtoupper($row['E']) . '","' . strtoupper($row['F']) . '")');

						$mysql_error = $myDB->getLastError();
					}
					$row_counter++;
					$count++;
				}

				if ($count > 0) {
					echo "<script>$(function(){ toastr.success('Total " . $count . " Record are Updated Sucessfully.') }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('No Data Updated ') }); </script>";
				}
				if ($validate == 1) {
					echo '<div class="alert alert-danger"> Following Data Not Uploaded due to given reason ::' . $noData_Uploadfor . '</div><div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"></div></div>';
				}
			} else {
				echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file') }); </script>";
			}
		}
	}
}

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Upload Cosmo Raw Data</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload Cosmo Raw Data</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

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
									modifier: {
										page: 'all'
									}
								},
								title: 'table'
							}, 'pageLength'],
							"bProcessing": true,
							"bDestroy": true,
							"bAutoWidth": "50%",
							"sScrollY": "192",
							"sScrollX": "100%",
							"bScrollCollapse": true,
							"bLengthChange": false,
							"fnDrawCallback": function() {

								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
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




				<div class="file-field input-field col s6 m6">
					<div class="btn">
						<span>Upload File</span>
						<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;">
						<br>

					</div>
					<div class="file-path-wrapper">
						<input class="file-path" type="text" style="">
					</div>
					</br>
				</div>
				<div class="input-field col s6 m6 right-align">
					<input type="submit" name="UploadBtn" id="UploadBtn" value="Submit" class="btn waves-effect waves-green" />

				</div>

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
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
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>