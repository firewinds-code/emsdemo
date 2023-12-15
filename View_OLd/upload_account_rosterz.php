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

date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logID = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logID)) {
		$location = URL . 'Login';
		header("Location: $location");
	} else {
		if ($user_logID == '' || $user_logID == null) {
			echo '<a href="' . URL . 'Login" >Go To Login </a>';
			exit();
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}


if (isset($_POST['txt_dept'])) {

	$dept = cleanUserInput($_POST['txt_dept']);
} else {
	$dept = 'NA';
}
$msgFile = '';
$insert_row = 0;
$btnUploadCheck = 0;
$count = 0;
$mysql_error = '';

function coordinates($x)
{
	return PHPExcel_Cell::stringFromColumnIndex($x);
}
if (isset($_POST['UploadBtn'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$btnUploadCheck = 1;
		$noData_Uploadfor_down = '';
		$target_dir = ROOT_PATH . 'Upload/';
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$FileType = pathinfo($target_file, PATHINFO_EXTENSION);


		// Check if file already exists
		/*if (file_exists($target_file)) {
		$msgFile =$msgFile."<p  class='msgFile text-danger'>Sorry, file already exists.</p>";
		$uploadOk = 0;
		}*/
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 5000000) {
			echo "<script>$(function(){ toastr.error('Sorry, your file is too large of Size " . $_FILES["fileToUpload"]["size"] . " '); }); </script>";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if ($FileType != "xlsx") {
			echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed.'); }); </script>";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.'); }); </script>";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				echo "<script>$(function(){ toastr.error('The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded'); }); </script>";
				$uploader = clean($_SESSION['__user_logid']);
				$document = PHPExcel_IOFactory::load($target_file);
				// Get the active sheet as an array
				$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);

				//print_r($activeSheetData.'<br/>');
				echo "<script>$(function(){ toastr.error('Rows available In Sheet " . (count($activeSheetData) - 1) . "'); }); </script>";
				$row_counter = 0;
				$EmployeeCounter = 0;
				$flag = 0;
				$validate = 0;
				$noData_Uploadfor = '';
				$date_ho_list = array();
				$myDB = new MysqliDb();
				$date_ho_list_db = $myDB->query("SELECT distinct DateOn FROM ho_list_admin");
				if (count($date_ho_list_db) > 0 && $date_ho_list_db) {
					foreach ($date_ho_list_db as $key_ho => $val_ho) {
						$date_ho_list[] = $val_ho['DateOn'];
					}
				}
				if ($_POST['txt_Type_Upload'] == 'Next Week') // && (date('l',time()) =='Friday' or date('l',time()) =='Saturday'))
				{
					$dt_First = date('Y-m-d', strtotime('next monday'));
					$dt_Last = date('Y-m-d', strtotime('next monday +6 days'));



					foreach ($activeSheetData as $row) {

						if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {

							$begin = new DateTime($dt_First);
							$end   = new DateTime($dt_Last);
							$weekOFF = 0;
							$j = 1;
							$jj = 1;
							$daycount = 1;
							$myDB = new MysqliDb();
							/*$weekoff_pref = $myDB->query("select FirstPre,SecondPre from rosterpref where EmpID ='".$row['A']."' and WeekNo ='".$dt_First." To ".$dt_Last."' order by id desc ");
				$weekoff_pref_1='';
				if(count($weekoff_pref) > 0 )
				{
					
					$weekoff_pref_1=$weekoff_pref[0]['FirstPre'];
				}*/
							$myDB = new MysqliDb();
							$check_roster = $myDB->query("select rt_type from salary_details where EmployeeID ='" . $row['A'] . "' limit 1");
							if (count($check_roster) > 0) {
								$check_roster = $check_roster[0]['rt_type'];
								$check_roster_temp = $check_roster;
								if ($check_roster == 3) {
									$check_roster = 1;
								}
							}

							$myDB = new MysqliDb();
							$check_status = $myDB->query("select Status from status_table where EmployeeID ='" . $row['A'] . "' limit 1");
							if (count($check_status) > 0) {
								$check_status = $check_status[0]['Status'];
								/*$check_roster_temp = $check_roster;
					if($check_roster == 3 )
					{
						$check_roster = 1;
					}*/
							}


							$myDB = new MysqliDb();
							$checkGender = $myDB->query("select Gender from personal_details where EmployeeID ='" . $row['A'] . "' limit 1");
							if (count($checkGender) > 0) {
								$gender = $checkGender[0]['Gender'];
								if (empty($gender)) {
									$gender = 'Male';
								}
							}

							$myDB = new MysqliDb();
							$checkdes = $myDB->query("select des_id from whole_details_peremp where EmployeeID ='" . $row['A'] . "' limit 1");
							if (count($checkdes) > 0) {
								$desid = $checkdes[0]['des_id'];
							}

							if (in_array($desid, array(9, 12))) {
								$date1 = date('Y-m-d', strtotime('last monday'));
								$date2	= date('Y-m-d', strtotime($date1 . ' +6 days'));
								$myDB = new MysqliDb();
								$flag = $myDB->query('call totalday_excWO("' . $row['A'] . '","' . $date1 . '","' . $date2 . '")');

								if (count($flag) > 0) {
									$day = $flag[0]['day'];
								}
							}


							$myDB = new MysqliDb();
							$checkAccount = $myDB->query("select account_head,Process,sub_process from employee_map inner join new_client_master on new_client_master.cm_id = employee_map.cm_id where EmployeeID ='" . $row['A'] . "' limit 1");
							$account_head = '';
							$process_macth = '';
							if (count($checkAccount) > 0) {

								$account_head = $checkAccount[0]['account_head'];
								$process_macth = $checkAccount[0]['Process'] . '-' . $checkAccount[0]['sub_process'];
							}
							if ($account_head == $uploader && ($process_macth == $dept || $dept == 'ALL Process')) {

								$EmployeeCounter++;
								for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
									$col = "D" . intval($i->format('d'));

									$val = trim($row[coordinates($jj)]);
									$dateT_ins = $row['J'] . '-' . intval($i->format('m')) . '-' . intval($i->format('d'));
									$roster_type = 1;
									/*$str = 'call InsertRoaster_New("'.strtoupper($row['A']).'","'.$col.'","'.$val.'","'.$_SESSION['__user_logid'].'","'.date('Y-m-d').'","'.$month_row['month'].'","'.$row['J'].'")';*/
									$Time_inout = explode('-', $val);
									//For Split Shift employee roster validation
									if (($val  == 'HO-HO' && $row['K'] != 4) || ($Time_inout[0]  == 'HO|HO' && strpos($Time_inout[0], '|') !== false)) {
										//For HO Calculation
										if (!in_array($i->format('Y-m-d'), $date_ho_list)) {
											$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">HO does not permissible for this Date </td></tr>';
											$validate = 1;
										}
									} else {

										// Start Other Calculation


										if ($check_roster_temp != $row['K']) {
											$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class																				="textDanger">Wrong Roster Type</td></tr>';
											$validate = 1;
										}

										if ($val  == 'WO-WO') {
											$weekOFF++;
											if (in_array($desid, array(9, 12)) && (clean($_SESSION["__cm_id"]) == "471" || clean($_SESSION["__cm_id"]) == "472" || clean($_SESSION["__cm_id"]) == "473" || clean($_SESSION["__cm_id"]) == "474")) {
												if (in_array($begin->format("w"), array(5, 6, 0))) {
													if ($val  == 'WO-WO') {
														$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">No Week off between friday to sunday for CSA</td></tr>';
														$validate = 1;
													}
												}
											}
										} elseif ((strlen($val) == 11 && $val != 'WO-WO') && strpos($val, ':') !== false && strpos($val, '-') !== false) {
											$roster_inout = explode('-', $val);
											$begin_roster = new DateTime($begin->format('Y-m-d H:i:s'));
											if ($roster_inout[0] < $roster_inout[1]) {
												$start_date = new DateTime($begin_roster->format('Y-m-d') . ' ' . $roster_inout[0]);
												$since_start = $start_date->diff(new DateTime($begin_roster->format('Y-m-d') . ' ' . $roster_inout[1]));

												if (in_array($desid, array(9, 12))) {
													if (in_array($begin->format("w"), array(1, 2, 3, 4))) {
														if ($since_start->h == 9) {
															$roster_type = 1;
														} else {

															$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Shift should be 9 Hr from Monday to Thursday</td></tr>';
															$validate = 1;
														}
													} else {
														if ($since_start->h == 10 && $since_start->i == 30) {
															$roster_type = 1;
														} else {
															if ($check_status == 6) {
																$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Shift should be 10:30 Hr from Friday to Sunday</td></tr>';
																$validate = 1;
															}
														}
													}

													if ($check_status != '6') {
														if ($since_start->h != 9) {
															$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Shift should be 9 Hr for Training Employee</td></tr>';
															$validate = 1;
														}
													}
												} else {

													if ($since_start->h == 9) {
														$roster_type = 1;
													} else {

														$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Shift should be 9 Hr</td></tr>';
														$validate = 1;
													}

													if ($check_status != '6') {
														if ($since_start->h != 9) {
															$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Shift should be 9 Hr for Training Employee</td></tr>';
															$validate = 1;
														}
													}
												}

												if ($check_roster != $roster_type) {
													$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Wrong Roster Type</td></tr>';
													$validate = 1;
												}
											} else {
												$start_date = new DateTime($begin_roster->format('Y-m-d') . ' ' . $roster_inout[0]);
												$since_start = $start_date->diff(new DateTime($begin_roster->modify('+1 day')->format('Y-m-d') . ' ' . $roster_inout[1]));
												if (in_array($desid, array(9, 12))) {
													if (in_array($begin->format("w"), array(1, 2, 3, 4))) {
														if ($since_start->h == 9) {
															$roster_type = 1;
														} else {

															$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Shift should be 9 Hr from Monday to Thursday</td></tr>';
															$validate = 1;
														}
													} else {
														if ($since_start->h == 10 && $since_start->i == 30) {
															$roster_type = 1;
														} else {
															if ($check_status == 6) {
																$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Shift should be 10:30 Hr from Friday to Sunday</td></tr>';
																$validate = 1;
															}
														}
													}
												} else {

													if ($since_start->h == 9) {
														$roster_type = 1;
													} else {

														$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Shift should be 9 Hr</td></tr>';
														$validate = 1;
													}
												}

												if ($check_roster != $roster_type) {
													$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Wrong Roster Type</td></tr>';
													$validate = 1;
												}
											}
										}

										if ($val[0] == "0" || $val[0] == "1" || $val[0] == "2" || $val[0] == "3" || $val[0] == "4" || $val[0] == "5" || $val[0] == "6" || $val[0] == "7" || $val[0] == "8" || $val[0] == "9" || $val  == 'WO-WO') {
											if ($val  == 'WO-WO') {
												if (in_array($desid, array(9, 12))) {
													if ($day == 5 && $weekOFF > 1) {
														$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Employee Week Off MisMatch</td></tr>';
														$validate = 1;
													} else if ($day == 6 && $weekOFF < 2 && $begin == $end) {
														$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Employee Week Off MisMatch</td></tr>';
														$validate = 1;
													}
												} else {
													if ($begin == $end && $weekOFF != 1) {
														$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Employee Week Off MisMatch</td></tr>';
														$validate = 1;
													}
												}
											} elseif (strlen($val) == 11 && $val != 'WO-WO' && strpos($val, ':') !== false && strpos($val, '-') !== false) {
												if ($roster_type  == intval($row['K']) && $roster_type == 1) {
													/*if((strtoupper($gender) == 'FEMALE' && (($roster_type == 1 && intval(substr($val, 0, 2)) <= 10 ) || ($roster_type == 2 && intval(substr($val, 0, 2)) <= 8  ) ||  ($roster_type == 3 && intval(substr($val, 0, 2)) <= 14  )	)) 	|| strtoupper($gender) =='MALE')
					{
						
					}
					else
					{
						
						$noData_Uploadfor .= '<tr><td>'.strtoupper($row['A']).'</td><td>'.$dateT_ins.'</td><td>'.$i->format('l').'</td><td class="textDanger">Female Employee rostered out of window</td></tr>';
						$validate = 1;
					}*/
												} else {
													$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Wrong Roster Type</td></tr>';
													$validate = 1;
												}

												if ($check_status != '6') {
													if ($since_start->h != 9) {
														$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Shift should be 9 Hr for Training Employee</td></tr>';
														$validate = 1;
													}
												}
											} else {
												$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Wrong Roster Format</td></tr>';
												$validate = 1;
											}
										} else {
											$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Wrong Roster Format</td></tr>';
											$validate = 1;
										}

										/*if($weekOFF >= 2 )
		{
			$weekOFF --;
		}
		elseif($weekoff_pref_1 == 'No Weekoff Required' && $weekOFF >= 1)
		{
			$weekOFF =0;
		}*/






										// End Other Calculation
									}
									$j++;
									$jj++;

									if ($row['L'] != '' && ($row['L'] == "WFO" || $row['L'] == "WFH" || $row['L'] == "WFOB")) {
									} else {
										$noData_Uploadfor .= '<tr><td>' . strtoupper($row['A']) . '</td><td>' . $dateT_ins . '</td><td>' . $i->format('l') . '</td><td class="textDanger">Work From not have correct value </td></tr>';
										$validate = 1;
									}
								}
							} else {
								echo "<script>$(function(){ toastr.error('Wrong Employee Data for upload. Employee " . strtoupper($row['A']) . " Not exists in your process list'); }); </script>";
								$validate = 1;
							}
						}
						$row_counter++;
					}

					$myDB = new MysqliDb();
					$rst_emp = $myDB->query("call activeFor_accounthead('" . $uploader . "','" . $dept . "')");
					if ($EmployeeCounter != ($row_counter - 1)) {
						echo "<script>$(function(){ toastr.error('Count mismatch error,Wrong Employee Data to upload may be some Employee not in your account'); }); </script>";
						$validate = 1;
					} elseif (($row_counter - 1) != intval(count($rst_emp))) {
						echo "<script>$(function(){ toastr.error('Count mismatch error, File not contain Employee you downloaded.Download and try agian'); }); </script>";
						$validate = 1;
					}
					if ($validate == 0) {

						$row_counter = 0;
						foreach ($activeSheetData as $row) {

							if ($row_counter > 0 && !empty($row['A']) && $row['A'] != '') {

								$begin = new DateTime($dt_First);
								$end   = new DateTime($dt_Last);
								$weekOFF = 0;
								$j = 1;
								$jj = 1;
								$daycount = 1;

								for ($i = $begin; $begin <= $end; $i->modify('+1 day')) {
									$col = "D" . intval($i->format('d'));
									$val = trim($row[coordinates($jj)]);
									//$dateT_ins = $i->format('Y').'-'.intval($i->format('m')).'-'.intval($i->format('d'));
									$dateT_ins = $i->format('Y-m-d');
									$Time_inout = explode('-', $val);
									$myDB = new MysqliDb();
									$flag = $myDB->query('call insert_roster_tmp("' . strtoupper($row['A']) . '","' . $Time_inout[0] . '","' . $Time_inout[1] . '","' . $dateT_ins . '","' . $row['K'] . '","' . $row['L'] . '")');
									$mysql_error = $myDB->getLastError() . '<br />';
									if ($flag != 0) {
										$count++;
									}

									$j++;
									$jj++;
								}
							}
							$row_counter++;
						}
					} else {
						echo '<div class="alert alert-danger"> Following Data Not Uploaded due to wrong format ::' . $noData_Uploadfor_down . '</div><div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data"><thead><tr><th>Employee ID</th><th>Date</th><th>Day</th><th>Error</th></tr></thead><tbody>' . $noData_Uploadfor . '</tbody></table></div></div>';
					}
				}

				if ($count > 0 && $validate == 0) {
					echo "<script>$(function(){ toastr.success('Total " . $count . " Record  for " . ($row_counter - 1) . " Employees are Updated Sucessfully.'); }); </script>";
					if (file_exists($target_dir . basename($_FILES["fileToUpload"]["name"]))) {
						$ext = pathinfo($target_file, PATHINFO_EXTENSION);
						rename($target_file, $target_dir . time() . '_' . $uploader . "_Roster_AccountHead." . $ext);
					}
				} else
					echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file $mysql_error'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.'); }); </script>";
			}
		}
	}
}
?>

<link rel="stylesheet" href="<?php echo URL . 'FileContainer/FlipClock-master/compiled/flipclock.css' ?>">
<script src="<?php echo URL . 'FileContainer/FlipClock-master/compiled/flipclock.js'; ?>"></script>

<script>
	$(function() {
		$('.textDanger').css({
			'color': 'rgb(187, 15, 15)',
			'font-weight': 'bold'
		});
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
				}, 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"sScrollY": "192",
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Upload Roster</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Upload Roster</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				//if(date('w', time()) == 5 && date('H:i', time()) >= "14:00")
				if (date('w', time()) == 6 && date('H:i', time()) >= "14:00") {
				?>
					<div class="col s12 m12">
						<div class="input-field col s12 m12">
							<Select name="txt_dept" id="txt_dept">
								<?php
								$userLog = clean($_SESSION['__user_logid']);
								$sql = 'select distinct Process,sub_process from new_client_master where account_head=?';
								$selectQury = $conn->prepare($sql);
								$selectQury->bind_param("s", $userLog);
								$selectQury->execute();
								$rowData = $selectQury->get_result();
								if ($rowData->num_rows > 0) {
									if ($dept == 'ALL Process') {
										echo '<option selected>ALL Process</option>';
									} else {
										echo '<option>ALL Process</option>';
									}
									foreach ($rowData as $key => $value) {
										if ($dept == $value['Process'] . '-' . $value['sub_process']) {
											echo '<option selected>' . $value['Process'] . '-' . $value['sub_process'] . '</option>';
										} else {
											echo '<option>' . $value['Process'] . '-' . $value['sub_process'] . '</option>';
										}
									}
								}
								?>
							</Select>
						</div>

						<div class="input-field col s12 m12">
							<select id="txt_Type_Upload" name="txt_Type_Upload">
								<option>Next Week</option>
							</select>
							<label for="txt_Type_Upload" class="active-drop-down active">Upload For</label>
						</div>

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
							<input type="submit" value="Submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green" />
							<!--<input type="button" value="Upload Again" name="UploadAgain" id="UploadAgain" value="Upload Again" class="btn waves-effect waves-green"/>-->
						</div>
					</div>

					<style>
						.flip-clock-wrapper ul {
							position: relative;
							float: left;
							margin: 1px;
							width: 30px;
							height: 45px;
							font-size: 10px;
							font-weight: bold;
							line-height: 30;
							border-radius: 6px;
							background: #000;
						}

						.flip-clock-divider {
							float: left;
							display: inline-block;
							position: relative;
							width: 20px;
							height: 48px;
						}

						.flip-clock-wrapper ul li a div div.inn {
							position: absolute;
							left: 0;
							z-index: 1;
							height: 200%;
							color: #ccc;
							text-shadow: 0 1px 2px #000;
							text-align: center;
							background-color: #333;
							border-radius: 6px;
							font-size: 40px;
						}

						.flip-clock-wrapper ul li {
							z-index: 1;
							position: absolute;
							left: 0;
							top: 0;
							width: 100%;
							height: 100%;
							line-height: 48px;
							text-decoration: none !important;
						}

						.flip-clock-divider .flip-clock-label {
							position: absolute;
							top: -1.5em;
							right: -45px;
							color: black;
							text-shadow: none;
						}

						.flip-clock-divider.minutes .flip-clock-label {
							right: -44px;
						}

						.flip-clock-divider.seconds .flip-clock-label {
							right: -46px;
						}
					</style>

					<?php
					$idate = time();
					$date = date('Y-m-d H:i:s', $idate);
					$newdate  = strtotime(date('Y-m-d', time()) . ' 23:59:59');
					if ($idate <= $newdate) {
					?>
						<div class="container" id="count_container" style="position: fixed;bottom: 30px;margin-left: 15%;">
							<h3 style="text-shadow: 2px 2px 1px rgb(183, 183, 183);border: 1px solid #f36500;border-radius: 3px;padding: 5px;float: left;margin-right: 20px;margin-top: 30px;color: #ad0202;">Time left for Upload </h3>
							<div class="clock" style="margin:2em;"></div>
						</div>

						<script type="text/javascript">
							var clock;

							//var Datez = $.datepicker.formatDate('yy/mm/dd', dates());

							$(document).ready(function() {

								// Grab the current date
								var currentDate = new Date();

								// Set some date in the future. In this case, it's always Jan 1
								//var futureDate  = new Date(currentDate.getFullYear() + 1, 0, 1);

								var futureDate = new Date(<?php echo "'" . date('m/d/Y H:i:s', $newdate) . "'"; ?>);

								// Calculate the difference in seconds between the future and current date
								var diff = (futureDate.getTime() - currentDate.getTime()) / 1000;

								// Instantiate a coutdown FlipClock
								clock = $('.clock').FlipClock(diff, {
									clockFace: 'DailyCounter',
									countdown: true,
									callbacks: {
										stop: function() {
											// Do whatever you want to do here,
											// that may include hiding the clock 
											// or displaying the image you mentioned
											$('#main_container_upload').remove();
											$('#count_container').html('Countdown complete !Click <a href="">here</a> for open').addClass('animated slideInLeft').attr('style', 'position: initial;bottom: 30px;color: #d66708;font-size: 16px;text-shadow: 1px 1px 1px #d6a98e;font-weight: bold;');
										}
									}
								});
							});
						</script>
					<?php
					}
				} else {
					?>

					<div class="col s12 m12" id="count_container">
						<h1 style="text-shadow: 2px 2px 1px rgb(183, 183, 183);font-size: 30px;border: 1px solid #0c7696;border-radius: 3px;padding: 5px;float: left;margin-right: 20px;margin-top: 50px;color: #0c7696;"> Time left </h1>
						<div class="clock" style="margin:2em;"></div>
					</div>

					<script type="text/javascript">
						var clock;

						function dates() {
							var dayOfWeek = 6; //Saturday
							var date = new Date();
							var diff = date.getDay() - dayOfWeek;
							if (diff > 0) {
								date.setDate(date.getDate() + 6);
							} else if (diff < 0) {
								date.setDate(date.getDate() + ((-1) * diff))
							}
							return (date);
						}
						//var Datez = $.datepicker.formatDate('yy/mm/dd', dates());

						$(document).ready(function() {

							// Grab the current date
							var currentDate = new Date();

							// Set some date in the future. In this case, it's always Jan 1
							//var futureDate  = new Date(currentDate.getFullYear() + 1, 0, 1);

							var dateto = dates();

							dateto.setHours(14);
							dateto.setMinutes(0);
							dateto.setSeconds(0);
							var futureDate = dateto;

							// Calculate the difference in seconds between the future and current date
							var diff = (futureDate.getTime() - currentDate.getTime()) / 1000;

							if (diff > 0) {
								clock = $('.clock').FlipClock(diff, {
									clockFace: 'DailyCounter',
									countdown: true,
									callbacks: {
										stop: function() {
											// Do whatever you want to do here,
											// that may include hiding the clock 
											// or displaying the image you mentioned

											$('#count_container').html('Countdown complete !!<a href=""> Click here </a> to Upload').addClass('animated slideInLeft').attr('style', 'position: initial;bottom: 30px;color: #d66708;font-size: 16px;text-shadow: 1px 1px 1px #d6a98e;font-weight: bold;');
										}
									}
								});
							} else {
								dateto.setDate(dateto.getDate() + 7);
								var futureDate = dateto;

								// Calculate the difference in seconds between the future and current date
								var diff = (futureDate.getTime() - currentDate.getTime()) / 1000;

								clock = $('.clock').FlipClock(diff, {
									clockFace: 'DailyCounter',
									countdown: true,
									callbacks: {
										stop: function() {
											// Do whatever you want to do here,
											// that may include hiding the clock 
											// or displaying the image you mentioned

											$('#count_container').html('Countdown complete !!<a href=""> Click here </a> to Upload').addClass('animated slideInLeft').attr('style', 'position: initial;bottom: 30px;color: #d66708;font-size: 16px;text-shadow: 1px 1px 1px #d6a98e;font-weight: bold;');
										}
									}
								});

							}
							// Instantiate a coutdown FlipClock

						});
					</script>

				<?php
				}
				?>

			</div>
			<script>
				$(function() {
					$('#alert_msg_close').click(function() {
						$('#alert_message').hide();
					});
					if ($('#alert_msg').text() == '') {
						$('#alert_message').hide();
					} else {
						//$('#alert_message').delay(20000).fadeOut("slow");
					}

					$('#UploadAgain').click(function() {
						$('.pannel_upload').removeClass('hidden');
						$('#UploadAgain').addClass('hidden');
						$('#txt_Type_Upload').val('Next Week');
					});
					$('#UploadBtn').click(function() {
						var validate = 0;
						var alert_msg = '';
						$('#txt_Type_Upload').closest('div').removeClass('has-error');

						if ($('#txt_Type_Upload').val() == 'NA') {
							$('#txt_Type_Upload').closest('div').addClass('has-error');
							validate = 1;
							alert_msg += '<li> First Select Upload For  </li>';
						}
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
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>