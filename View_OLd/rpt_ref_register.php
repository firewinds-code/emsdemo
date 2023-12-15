<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = $thisPage = 0;
// error_reporting(1);
if (isset($_SESSION)) {
	$clean_u_logid = clean($_SESSION['__user_logid']);
	if (!isset($clean_u_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;
		$referer = $alert_msg = "";
		$thisPage = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}
		if ($referer == $thisPage) {
			$isPostBack = true;
		}
		if ($isPostBack && isset($_POST)) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$date_To = cleanUserInput($_POST['txt_dateTo']);
				$date_From = cleanUserInput($_POST['txt_dateFrom']);
			}
		} else {
			$date_To = date('Y-m-d', time());
			$date_From = date('Y-m-d', time());
		}
	}
	$__user_type=clean($_SESSION['__user_type']);
	if (	$__user_type == 'ADMINISTRATOR' || $__user_type == 'HR' || $__user_type == 'CENTRAL MIS' || $_SESSION['__user_logid'] == 'CE10091236' || $_SESSION['__user_logid'] == 'CE12102224') {
		// proceed further
	} else {
		$location = URL;
		echo "<script>location.href='" . $location . "'</script>";
	}
	/*if($_SESSION['__user_type']=='ADMINISTRATOR' || $_SESSION['__user_type']=='HR' || $_SESSION['__user_type']=='CENTRAL MIS' || $_SESSION['__user_logid'] == 'CE10091236')
		{
			die("access denied ! It seems like you try for a wrong action.");
			exit();
		}*/
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
?>

<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
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
			"iDisplayLength": 25,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {
				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');

		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Reference Registration Reports</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Reference Registration Reports</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s5 m5">
						<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s5 m5">
						<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s2 m2">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					</div>

				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
					if (isset($_POST['btn_view'])) {

						// $myDB = new MysqliDb();
						$query_Select = "SELECT r.RefID,r.createdon,r.CandidateName, r.CandidateNumber,r.EmployeeID ,concat(r.client,'-',r.Process,'-',r.SubProcess) as Process,r.EmployeeName from tbl_reference_reg_detail r  where cast(r.createdon as date) between ? and ? ";
						$stmt = $conn->prepare($query_Select);
						$stmt->bind_param("ss", $date_From, $date_To);
						$stmt->execute();
						$result = $stmt->get_result();
						$count = $result->num_rows;
						$desig_array = mysqli_fetch_array($result);
						// $mysql_error = $myDB->getLastError();
						$mysql_error = $my_error = $myDB->getLastError();
						if ($result->num_rows > 0) {

							// $result = $myDB->query($query_Select);


							// if (count($result) > 0) {

							$table = '<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
							$table .= '<th>EmployeeID</th><th>EmployeeName</th><th>Process</th><th>createdon</th><th>CandidateName</th><th>CandidateNumber</th><th>Walkin(Y/N)</th><th>Walkin Date</th><th>Interview Cleared(Y/N)</th><th>Process Selected</th><th>Joining Date</th><th>Joined Y/N</th><th>Candidate EmployeeID</th><th>Salary CTC</th><th>Current Active (Y/N)</th><th>Total Amount</th><th>1st Pay Amount</th><th>1st Pay Date</th><th>2nd Pay Amount</th><th>2nd Pay Date</th><th>Location</th></tr></thead><tbody>	';


							foreach ($result as $key => $value) {
								$empLocation = 'NA';
								$table .= '<tr>';
								$RefID = $value['RefID'];
								$CandidateNumber = $value['CandidateNumber'];
								$EmployeeID = $value['EmployeeID'];
								$CreatedOn = $value['createdon'];
								$table .= '<td>' . $value['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['EmployeeName'] . '</td>';
								$table .= '<td>' . $value['Process'] . '</td>';
								$table .= '<td>' . $value['createdon'] . '</td>';
								$table .= '<td>' . $value['CandidateName'] . '</td>';
								$table .= '<td>' . $value['CandidateNumber'] . '</td>';

								$int_url = '';
								//$intid=$_SESSION['__interview_id'];
								$int_url = INTERVIEW_URL . "getRefData.php?type=1&Para1=" . $CandidateNumber;
								$curl = curl_init();
								curl_setopt($curl, CURLOPT_URL, $int_url);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($curl, CURLOPT_HEADER, false);
								$data = curl_exec($curl);
								$data_array1 = '';
								$data_array1 = json_decode($data);
								$ID = '';
								// print_r($data_array);
								if (count($data_array1) > 0) {
									$table .= '<td>' . $data_array1->Walkin . '</td>';
									$table .= '<td>' . $data_array1->WalkinDate . '</td>';
									$INTID = $data_array1->INTID;
									$WalkinDate = $data_array1->WalkinDate;
									$ID = $data_array1->id;
								} else {
									$table .= '<td>No</td>';
									$table .= '<td>NA</td>';
								}
								$int_url = '';

								//$intid=$_SESSION['__interview_id'];
								$int_url = INTERVIEW_URL . "getRefData.php?type=2&Para1=" . $ID;
								$curl = curl_init();
								curl_setopt($curl, CURLOPT_URL, $int_url);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($curl, CURLOPT_HEADER, false);
								$data = curl_exec($curl);
								$data_array = "";
								$data_array = json_decode($data);
								$Process1 = '';
								if (count($data_array) > 0 && $ID != '') {
									$table .= '<td>' . $data_array->Interviewclered . '</td>';
									$table .= '<td>' . $data_array->PrcessSeleceted . '</td>';
									$table .= '<td>' . $data_array->JoiningDate . '</td>';

									$DOJ = $data_array->JoiningDate;
									$Process1 = $data_array->Process1;
								} else {
									$table .= '<td>No</td>';
									$table .= '<td>NA</td>';
									$table .= '<td>NA</td>';
								}

								$sqlConnect = "SELECT EmployeeID,case when INTID is null then 'No' else 'Yes' end as 'Joined Y/N', ctc as 'Salary CTC', cm_id,case when df_id=74 then 'CSA' else 'Support' end as desig, case when emp_status='Active' then 'Yes' else 'No' end as 'Current Active (Y/N)',location from (select p.EmployeeID,s.ctc,e.emp_status,e.df_id,e.cm_id,p.INTID,p.createdon,l1.location from personal_details p inner join salary_details s on s.EmployeeID=p.EmployeeID inner join employee_map e on e.EmployeeID=p.EmployeeID join location_master l1 on p.location=l1.id where emp_status='Active' and cast(p.createdon as date) >= cast(? as date))t where INTID=? and cm_id=? ";

								$stmt2 = $conn->prepare($sqlConnect);
								$stmt2->bind_param("sss", $CreatedOn, $INTID, $Process1);
								$stmt2->execute();
								$result2 = $stmt2->get_result();
								$count = $result2->num_rows;
								$mysql_error = $myDB->getLastError();
								// if ($result->num_rows > 0) {
								// echo '<script>alert(' . print_r($result2) . ')</script>';
								$Joined = '';
								$cmid = '';
								$desig = '';
								// $myDB = new MysqliDb();
								// $result2 = $myDB->rawQuery($sqlConnect);
								$mysql_error = $myDB->getLastError();
								if ($result2->num_rows > 0 && $result2 && $Process1 != '') {
									$table .= '<td>' . $result2[0]['Joined Y/N'] . '</td>';
									$table .= '<td>' . $result2[0]['EmployeeID'] . '</td>';
									$table .= '<td>' . $result2[0]['Salary CTC'] . '</td>';
									$table .= '<td>' . $result2[0]['Current Active (Y/N)'] . '</td>';
									//$table .='<td>'.$result2[0]['location'].'</td>';
									$Joined = $result2[0]['Joined Y/N'];
									$cmid = $result2[0]['cm_id'];
									$desig = $result2[0]['desig'];
									$empLocation = $result2[0]['location'];
								} else {
									$table .= '<td>No</td>';
									$table .= '<td>NA</td>';
									$table .= '<td>NA</td>';
									$table .= '<td>No</td>';
									//$table .='<td>NA</td>';
								}


								$sqlConnect2 = "SELECT amount,1st_pay,2nd_pay,window_month from ref_amount_master where emp_type=? and cm_id=? and (cast(? as date) between ApplicableFrom and ApplicableTo) ";

								$stmt3 = $conn->prepare($sqlConnect2);
								$stmt3->bind_param("sss", $desig, $cmid, $CreatedOn);
								$stmt3->execute();
								$result3 = $stmt3->get_result();
								$count = $result3->num_rows;
								$mysql_error = $myDB->getLastError();
								// echo '<script>alert(' . $cmid . ')</script>';

								$amount = '';
								$fpay = '';
								$spay = '';
								$window_month = '';
								$fpayDate = '';
								$spayDate = '';

								// $myDB = new MysqliDb();
								// $result3 = $myDB->rawQuery($sqlConnect);
								$mysql_error = $myDB->getLastError();
								if ($result3->num_rows > 0 && $result3) {
									$amount = $result3[0]['amount'];
									$fpay = $result3[0]['1st_pay'];
									$spay = $result3[0]['2nd_pay'];
									$window_month = $result3[0]['window_month'];

									// Calculate Amount and Pay date

									$sqlConnect3 = "SELECT des_id from whole_details_peremp where EmployeeID=? ";
									$des_id = '';
									// $myDB = new MysqliDb();
									// $result4 = $myDB->rawQuery($sqlConnect3);
									$stmt4 = $conn->prepare($sqlConnect3);
									$stmt4->bind_param("s", $EmployeeID);
									$stmt4->execute();
									$result4 = $stmt4->get_result();
									$count = $result4->num_rows;
									$mysql_error = $myDB->getLastError();
									// echo '<script>alert("forth query")</script>';





									$mysql_error = $myDB->getLastError();
									if ($result4->num_rows > 0 && $result4) {
										$des_id = $result4[0]['des_id'];
									}

									$datediff = strtotime($WalkinDate) - strtotime($CreatedOn);

									if ($Joined == '' or ($des_id == '5' or $des_id == '7' or $des_id == '8' or $des_id == '10' or $des_id == '13' or $des_id == '14' or $des_id == '15' or $des_id == '16') or (round($datediff / (60 * 60 * 24)) < 0 and round($datediff / (60 * 60 * 24)) > 14) or $amount == '') {
										$amount = 'NA';
									}

									if ($Joined == '' or ($des_id == '5' or $des_id == '7' or $des_id == '8' or $des_id == '10' or $des_id == '13' or $des_id == '14' or $des_id == '15' or $des_id == '16') or (round($datediff / (60 * 60 * 24)) < 0 and round($datediff / (60 * 60 * 24)) > 14) or $fpay == '') {
										$fpay = 'NA';
									}

									$window_month = intval($window_month) + intval(1);

									if ($Joined == '' or ($des_id == '5' or $des_id == '7' or $des_id == '8' or $des_id == '10' or $des_id == '13' or $des_id == '14' or $des_id == '15' or $des_id == '16') or (round($datediff / (60 * 60 * 24)) < 0 and round($datediff / (60 * 60 * 24)) > 14) or $fpay == '') {
										$fpayDate = 'NA';
									} else {
										$fpayDate = date('Y-m-15', strtotime($DOJ . '+' . $window_month . ' month'));
									}

									if ($Joined == '' or ($des_id == '5' or $des_id == '7' or $des_id == '8' or $des_id == '10' or $des_id == '13' or $des_id == '14' or $des_id == '15' or $des_id == '16') or (round($datediff / (60 * 60 * 24)) < 0 and round($datediff / (60 * 60 * 24)) > 14) or $spay == '') {
										$spay = 'NA';
									}


									if ($Joined == '' or $spay == '' or $spay == '0' or ($des_id == '5' or $des_id == '7' or $des_id == '8' or $des_id == '10' or $des_id == '13' or $des_id == '14' or $des_id == '15' or $des_id == '16') or (round($datediff / (60 * 60 * 24)) < 0 and round($datediff / (60 * 60 * 24)) > 14) or $spay == '') {
										$spayDate = 'NA';
									} else {
										$window_month = intval($window_month) + intval(1);
										$spayDate = date('Y-m-15', strtotime($DOJ . '+' . $window_month . ' month'));
									}
								} else {
									$amount = 'NA';
									$fpay = 'NA';
									$spay = 'NA';
									$fpayDate = 'NA';
									$spayDate = 'NA';
								}

								$table .= '<td>' . $amount . '</td>';
								$table .= '<td>' . $fpay . '</td>';
								$table .= '<td>' . $fpayDate . '</td>';
								$table .= '<td>' . $spay . '</td>';
								$table .= '<td>' . $spayDate . '</td>';
								$table .= '<td>' . $empLocation . '</td>';

								$table .= '</tr>';


								//echo '</tr>'; 
							}

							$table .= '</tbody></table>';
							echo $table;
						}
					}
					?>
					<!--</tbody>
	  </table>-->


				</div>

				<!--Reprot / Data Table End -->
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>