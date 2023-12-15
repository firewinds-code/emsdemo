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

	$msgFile = '';
	$month = $year = $day = 0;
	if (isset($_SESSION)) {
		if (!isset($_SESSION['__user_logid']) || $_SESSION['__user_logid'] != 'CE03070003' || $_SESSION['__user_logid'] != 'CE12102224') {
			$location = URL . 'Login';
			header("Location: $location");
		} else {
			if (isset($_POST['txtMonth'])) {
				$month = $_POST['txtMonth'];
				$year = $_POST['txtYear'];
				$day = $_POST['txtdtc'];
			} else {
				$month = date('m', time());
				$year = date('Y', time());
				$day = 10;
			}
		}
	} else {
		$location = URL . 'Login';
		header("Location: $location");
	}
	$alert_msg = '';
	$DateFrom = (isset($_POST['dateFrom'])) ? $_POST['dateFrom'] : date('Y-m-d', strtotime('-10 days'));
	if (isset($_POST['btn_ExcCalculation_Save'])) {
		$myDB = new MysqliDb();
		$res_ext  =  $myDB->query('select * from exception_calculation_biometric');
		if (count($res_ext) > 0 && $res_ext) {
			foreach ($res_ext as $key => $value) {
				if (!empty($_POST['dateFrom'])) {
					$empid = preg_replace('/\s+/', '', $value['EmployeeID']);

					$url = '';
					//$url = URL.'View/calcAtnd_for_empid.php?empid='.$EmpID.'&month='.date('m',strtotime($DateFrom)).'&year='.date('Y',strtotime($DateFrom));	
					$myDB = new MysqliDb();
					$rst_cmid = $myDB->query('select cm_id from employee_map where EmployeeID= "' . $empid . '"');
					$cmid = $rst_cmid[0]['cm_id'];

					$DateFrom = $_POST['dateFrom'];

					$iTime_in = new DateTime($DateFrom);
					$iTime_out = new DateTime();
					$interval = $iTime_in->diff($iTime_out);
					/*if($interval->format("%a") <= 10)
				{
					$url = URL.'View/calcRange.php?empid='.$empid.'&type=one&from='.date('Y-m-d',strtotime($DateFrom));

				}
				else
				{
					$url = URL.'View/calcRange.php?empid='.$empid.'&type=one';

				}*/
					if ($cmid != '88') {
						$url = URL . 'View/calcRange.php?empid=' . $empid . '&type=one&from=' . date('Y-m-d', strtotime($DateFrom));
					} else {
						$url = URL . 'View/calcRange_zomato.php?empid=' . $empid . '&type=one&from=' . date('Y-m-d', strtotime($DateFrom));
					}

					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HEADER, false);
					$data = curl_exec($curl);
					curl_close($curl);
					echo "<script>$(function(){ toastr.success('Calculation is done for " . $value['EmployeeID'] . " from " . $DateFrom . " Days.'); });</script>";
				} else {
					echo "<script>$(function(){ toastr.success('Not Run for " . $value['EmployeeID'] . ".'); }); </script>";
				}
			}
		}
	}

	?>

 <script>
 	$(document).ready(function() {
 		$('#myTable').DataTable({
 			dom: 'Bfrtip',
 			"iDisplayLength": 25,
 			"sScrollX": "100%",
 			scrollCollapse: true,
 			lengthMenu: [
 				[5, 10, 25, 50, -1],
 				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
 			],
 			buttons: [
 				/*     
 				   {
 				       extend: 'csv',
 				       text: 'CSV',
 				       extension: '.csv',
 				       exportOptions: {
 				           modifier: {
 				               page: 'all'
 				           }
 				       },
 				       title: 'table'
 				   }, 						         
 				   'print',*/
 				{
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

 <!-- This div not contain a End on this Page because this activity already done in footer Page -->
 <div id="content" class="content">

 	<!-- Header Text for Page and Title -->
 	<span id="PageTittle_span" class="hidden">Attendance Calculation</span>

 	<!-- Main Div for all Page -->
 	<div class="pim-container row" id="div_main">

 		<!-- Sub Main Div for all Page -->
 		<div class="form-div">

 			<!-- Header for Form If any -->
 			<h4>Attendance Calculation</h4>

 			<!-- Form container if any -->
 			<div class="schema-form-section row">

 				<div class="input-field col s10 m10">
 					<input type="file" name="fileToUpload" id="fileToUpload" />
 					<label for="fileToUpload" class="active-drop-down active" style="margin-top: -15px;"></label>
 				</div>
 				<div class="input-field col s2 m2 right-align">
 					<input type="submit" value="Submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green" />
 				</div>
 				<?php
					if (isset($_POST['UploadBtn'])) {

						$btnUploadCheck = 1;
						$target_dir = ROOT_PATH . 'Upload/';
						$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
						$uploadOk = 1;
						$uploader = $_SESSION['__user_logid'];
						$FileType = pathinfo($target_file, PATHINFO_EXTENSION);

						if ($_FILES["fileToUpload"]["size"] > 5000000) {
							$msgFile = $msgFile . "<p  class='msgFile text-danger'>Sorry, your file is too large of Size " . $_FILES["fileToUpload"]["size"] . ".</p>";
							echo "<script>$(function(){ toastr.error('" . $msgFile . "'); }); </script>";
							$uploadOk = 0;
						}
						// Allow certain file formats
						if ($FileType != "xlsx") {
							$msgFile = $msgFile . " <p  class='msgFile text-danger'> Sorry, only XLS and XLSX files are allowed.</p>";
							echo "<script>$(function(){ toastr.error('" . $msgFile . "'); }); </script>";
							$uploadOk = 0;
						}
						// Check if $uploadOk is set to 0 by an error
						if ($uploadOk == 0) {
							$msgFile = $msgFile . " <p class='msgFile text-danger'> Sorry, your file was not uploaded.</p>";
							echo "<script>$(function(){ toastr.error('" . $msgFile . "'); }); </script>";
							// if everything is ok, try to upload file
						} else {
							if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
								$msgFile = $msgFile . "<p  class='msgFile text-success'>The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. </p>";
								echo "<script>$(function(){ toastr.success('" . $msgFile . "'); }); </script>";

								$document = PHPExcel_IOFactory::load($target_file);
								// Get the active sheet as an array
								$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
								//print_r($activeSheetData.'<br/>');
								$msgFile = $msgFile . "<p  class='msgFile text-success'>Rows available In Sheet : <code>" . (count($activeSheetData) - 1) . "</code></p>";
								echo "<script>$(function(){ toastr.success('" . $msgFile . "'); }); </script>";
								$row_counter = 0;
								$flag = 0;
								$row_counter = 0;
								$count = 0;
								if ((count($activeSheetData) - 1) <= 100) {
									$myDB = new MysqliDb();
									$tab_trucate = $myDB->query('delete from  exception_calculation_biometric;');
									echo 'there';
									foreach ($activeSheetData as $row) {
										if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {
											$EmployeeID = $row['A'];
											$myDB = new MysqliDb();

											$rst_insert_bkd = $myDB->query('INSERT INTO exception_calculation_biometric (EmployeeID)VALUES("' . $EmployeeID . '")');

											if ($rst_insert_bkd != 0) {
												$count++;
											}
										}
										$row_counter++;
									}
									if ($count > 0) {
										$msgFile = $msgFile . "<p  class='msgFile text-danger'>Total " . $count . " Record are Updated Sucessfully.</p>";
										echo "<script>$(function(){ toastr.error('" . $msgFile . "'); }); </script>";
									} else {
										$msgFile = $msgFile . "<p  class='msgFile text-danger'>No Data Updated " . $mysql_error . "</p>";
										echo "<script>$(function(){ toastr.error('" . $msgFile . "'); }); </script>";

										if (file_exists($target_dir . basename($_FILES["fileToUpload"]["name"]))) {
											$ext = pathinfo($target_file, PATHINFO_EXTENSION);
											if (!empty($_POST['fileToUpload'])) {
												rename($target_file, $target_dir . time() . '_' . $uploader . "_ExcAttendance_" . $_POST['fileToUpload'] . "." . $ext);
											}
										}
									}
								} else {
									$msgFile = $msgFile . "<p  class='msgFile text-danger'>No Data Updated because file have more then 10 Employee Data</p>";
									echo "<script>$(function(){ toastr.error('" . $msgFile . "'); }); </script>";
								}
							} else {
								$msgFile = $msgFile . "<p  class='msgFile text-danger'>Sorry, there was an error uploading your file. </p> ";
								echo "<script>$(function(){ toastr.error('" . $msgFile . "'); }); </script>";
							}
						}
						echo $msgFile;
					}
					?>

 				<div class="input-field col s10 m10">
 					<input type="text" name="dateFrom" id="dateFrom" value="<?php echo $DateFrom; ?>" readonly="true" />
 					<label for="dateFrom">Date From</label>
 				</div>

 				<div class="input-field col s2 m2 right-align">
 					<input type="hidden" class="form-control hidden" id="hid_Exc Calculation_ID" name="hid_Exc Calculation_ID" />
 					<button type="submit" name="btn_ExcCalculation_Save" id="btn_ExcCalculation_Save" class="btn waves-effect waves-green">Calculate</button>
 				</div>
 				<br>

 				<!--Form element model popup End-->
 				<!--Reprot / Data Table start -->

 				<div id="pnlTable">
 					<?php
						$sqlConnect = 'SELECT e.EmployeeID,EmployeeName,DOD,designation,dept_name,Process,sub_process,clientname,`function` FROM exception_calculation_biometric e inner join whole_details_peremp w on e.EmployeeID = w.EmployeeID';
						$myDB = new MysqliDb();
						$result = $myDB->rawQuery($sqlConnect);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) { ?>
 						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
 							<thead>
 								<tr>

 									<th>Employee ID</th>
 									<th>Employee Name</th>
 									<th>DOD</th>
 									<th>Designation</th>
 									<th>Dept Name</th>
 									<th>Process</th>
 									<th>Sub Process</th>
 									<th>Client</th>
 									<th>Function</th>

 								</tr>
 							</thead>
 							<tbody>
 								<?php
									foreach ($result as $key => $value) {

										echo '<tr>';
										echo '<td>' . $value['EmployeeID'] . '</td>';
										echo '<td>' . $value['EmployeeName'] . '</td>';
										echo '<td>' . $value['DOD'] . '</td>';
										echo '<td>' . $value['designation'] . '</td>';
										echo '<td>' . $value['dept_name'] . '</td>';
										echo '<td>' . $value['Process'] . '</td>';
										echo '<td>' . $value['sub_process'] . '</td>';
										echo '<td>' . $value['clientname'] . '</td>';
										echo '<td>' . $value['function'] . '</td>';

										echo '</tr>';
									}
									?>
 							</tbody>
 						</table>

 					<?php
						}
						?>
 				</div>


 				<!--Reprot / Data Table End -->
 			</div>
 			<!--Form container End -->
 		</div>
 		<!--Main Div for all Page End -->
 	</div>
 	<!--Content Div for all Page End -->
 </div>

 <script>
 	$(document).ready(function() {

 		//Model Assigned and initiation code on document load	
 		$('.modal').modal({
 			onOpenStart: function(elm) {

 			},
 			onCloseEnd: function(elm) {
 				$('#btn_Client_Can').trigger("click");
 			}
 		});

 		// This code for remove error span from all element contain .has-error class on listed events
 		$(document).on("click blur focus change", ".has-error", function() {
 			$(".has-error").each(function() {
 				if ($(this).hasClass("has-error")) {
 					$(this).removeClass("has-error");
 					$(this).next("span.help-block").remove();
 					if ($(this).is('select')) {
 						$(this).parent('.select-wrapper').find("span.help-block").remove();
 					}
 					if ($(this).hasClass('select-dropdown')) {
 						$(this).parent('.select-wrapper').find("span.help-block").remove();
 					}
 				}
 			});
 		});



 		$('#dateFrom').datepicker({
 			minDate: '-40',
 			maxDate: '-1D',
 			dateFormat: 'yy-mm-dd'
 		});
 	});
 </script>



 <?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>