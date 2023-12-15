<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
require_once(LIB . 'PHPExcel/IOFactory.php');

$EmployeeID = clean($_SESSION['__user_logid']);

if (isset($_SESSION)) {
	if (!isset($EmployeeID)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}
$alert_msg = $db_int_config_i = '';
$loc = '';
$btn_save_ = isset($_POST['btn_df_Save']);
if ($btn_save_) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		// $mySql = new MysqliDb();
		//echo "SELECT clientname,Process,sub_process FROM whole_details_peremp where EmployeeID = '".$_SESSION["__user_logid"]."'";
		$cliproc = "SELECT clientname,Process,sub_process,location FROM whole_details_peremp where EmployeeID = ? ";
		$selectQ = $conn->prepare($cliproc);
		$selectQ->bind_param("s", $EmployeeID);
		$selectQ->execute();
		$results = $selectQ->get_result();
		$result_cliproc = $results->fetch_row();

		$Client = clean($_SESSION['__user_client_ID']);
		$Process = clean($_SESSION['__user_process']);
		$SubProcess = clean($_SESSION['__user_subprocess']);

		if ($results->num_rows > 0) {
			$Client = clean($result_cliproc[0]);
			$Process = clean($result_cliproc[1]);
			$SubProcess = clean($result_cliproc[2]);
			$loc = clean($result_cliproc[3]);
		}

		$EmployeeID = clean($_SESSION["__user_logid"]);
		$EmployeeName = clean($_SESSION["__user_Name"]);
		$DateOn = cleanUserInput($_POST['txtDateOn']);
		$Designation = clean($_SESSION["__user_Desg"]);

		$CandidateName =  cleanUserInput($_POST['txtCandidateName']);
		$CandidateNumber =   cleanUserInput($_POST['txtCandidateNumber']);
		$CandidateAddress =  cleanUserInput($_POST['txtCandidateAddress']);
		$Remark = cleanUserInput($_POST['txtRemark']);
		$CandidateLevel = cleanUserInput($_POST['txtCandidateLevel']);
		$createdby = clean($_SESSION["__user_logid"]);
		$ID = 0;
		$Agreed = 'Yes';

		// $sqlConnect = 'call sp_getRefID()';
		// $myDB=new MysqliDb();
		// $result=$myDB->rawQuery($sqlConnect);
		// $mysql_error = $myDB->getLastError();

		// if(count($result) > 0 && $result)
		// {
		// 	$ID=$result[0]['ID'];
		// }			

		$query_insert = 'INSERT INTO tbl_reference_reg_detail(`EmployeeID`,`EmployeeName`,`Designation`,`Client`,`Process`,`SubProcess`,`CandidateName`,`CandidateNumber`,`CandidateAddress`,`Remark`,`CandidateLevel`,`createdby`,`RefID`,`Agreed`,source) VALUES(?,?,?,?,?,?,?,?,(select location from location_master where id=?),?,?,?,?,?,"Web");';
		// $myDB = new MysqliDb();
		// $flag =  $myDB->query($query_insert);
		// $myError = $myDB->getLastError();
		$ins = $conn->prepare($query_insert);
		$ins->bind_param("sssssssissssis", $EmployeeID, $EmployeeName, $Designation, $Client, $Process, $SubProcess, $CandidateName, $CandidateNumber, $loc, $Remark, $CandidateLevel, $createdby, $ID, $Agreed);
		$ins->execute();
		$flag = $ins->get_result();
		// if (empty($myError)) {
		if ($ins->affected_rows === 1) {
			echo "<script>$(function(){ toastr.success('Candidate Register with us successfully') }); </script>";

			// $url= 'http://192.168.220.35/outcallhiring/admin/wb_serve_as_cleint.php?mob='.urlencode($CandidateNumber).'&name='.urlencode($CandidateName).'&ds=EMS';

			// $curl = curl_init();
			// curl_setopt($curl, CURLOPT_URL, $url);
			// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($curl, CURLOPT_HEADER, false);
			// $data = curl_exec($curl);
			// curl_close($curl);
		} else {
			echo "<script>$(function(){ toastr.error('Reference not registered. Some error occurred...') }); </script>";
		}
	}
}
?>

<script>
	$(document).ready(function() {

		$('#txtDateOn').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			minDate: '+1970/01/01'
		});
		//alert('f');


		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				/*   {
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
	<span id="PageTittle_span" class="hidden">Reference Registration</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Reference Registration</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s6 m6">
					<input type="text" id="txtCandidateName" name="txtCandidateName" />
					<label for="txtCandidateName">Candidate Name</label>
				</div>

				<div class="input-field col s6 m6">
					<input type="text" id="txtCandidateNumber" name="txtCandidateNumber" maxlength="10" />
					<label for="txtCandidateNumber">Candidate Number</label>

				</div>

				<div class="input-field col s6 m6 hidden">
					<input type="text" id="txtDateOn" name="txtDateOn" readonly="true">
					<label for="txtDateOn">Expected Interview Date</label>
				</div>

				<div class="input-field col s6 m6">

					<select id="txtCandidateLevel" name="txtCandidateLevel">
						<option value="NA">--Select--</option>
						<option>Customer Support Associate</option>
						<option>Support</option>
					</select>
					<label for="txtCandidateLevel" class="active-drop-down active">Candidate Level</label>
				</div>

				<div class="input-field col s6 m6 hidden">
					<textarea id="txtCandidateAddress" name="txtCandidateAddress" class="materialize-textarea"></textarea>
					<label for="txtCandidateAddress">Candidate Address</label>
				</div>

				<div class="input-field col s6 m6 hidden">
					<textarea id="txtRemark" name="txtRemark" class="materialize-textarea"></textarea>
					<label for="txtRemark">Remark (If Any)</label>
				</div>



				<div id="divdisc">
					<div class="input-field col s12 m12">
						<br />
						<div style="overflow: auto;">
							<fieldset>
								<legend></legend>

								<p style="background-color: -moz-any; text-align: justify; font-family: verdana; font-size: 11pt;">
									The person refered above is known to me and i have check his interest in types of job we offered and he/she may be call telephonically for the same. </p>
							</fieldset>

						</div>
					</div>

					<div class="col s6 m6 right-align">
						<br />
						<input type='checkbox' name="Accept" id="Accept">
						<label for="Accept">Accept</label>
					</div>
				</div>

				<div id="divbtn" class="input-field col s6 m6 right-align">
					<input type="submit" class="btn waves-effect waves-green" value="Submit" id="btn_df_Save" name="btn_df_Save" />
				</div>

				</br>
				</br>

				<div class="alert col-sm-12 hidden" style="margin: 0px;">
					<p style="font-size: 10pt; font-family: 'Verdana',sans-serif; color: #093a42;"><b>Referral Bonanza Scheme is running from 17<sup>th</sup> to 24<sup>th</sup> Mar&rsquo;18</b><br /><br />&bull; Refer your friend or family member within above mention period and Get <span class="infinite_ammount"><b>Rs.1000/-</b></span> per employee as a reference incentive.<br />&bull; The Amount will be paid in two installments i.e. <span class="infinite_ammount"><b>Rs. 500/-</b></span> on <b>25<sup>th</sup> Apr&rsquo;18</b> &amp; <span class="infinite_ammount"><b>Rs. 500/-</b></span> on <b>25<sup>th</sup> May&rsquo;18</b>.<br />&bull; Referred employee status should be Active at the time of Amount disbursement.<br /><br /><b>**Offer is valid up to AM level, across departments.</b></p>
				</div>

				<div><br /></div>


				<div id="pnlTable">
					<?php
					// $myDB = new MysqliDb();

					$sqlConnect = "select r.RefID,r.createdon,r.CandidateName, r.CandidateNumber,r.EmployeeID ,concat(r.client,'-',r.Process,'-',r.SubProcess) as Process,r.EmployeeName from tbl_reference_reg_detail r  where cast(r.createdon as date)>date_sub(cast(now() as date), interval 70 day) and r.EmployeeID=? order by createdon desc";
					$selectQr = $conn->prepare($sqlConnect);
					$selectQr->bind_param("s", $EmployeeID);
					$selectQr->execute();
					$result = $selectQr->get_result();
					// print_r($result);
					// $result = $myDB->query($sqlConnect);
					// $mysql_error = $myDB->getLastError();

					if ($result->num_rows > 0) {

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

							$int_url = INTERVIEW_URL . "getRefData.php?type=1&param=" . $CandidateNumber;
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
							$int_url = INTERVIEW_URL . "getRefData.php?type=2&param=" . $ID;
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

							$sqlConnect = "select EmployeeID,case when INTID is null then 'No' else 'Yes' end as 'Joined Y/N', ctc as 'Salary CTC', cm_id,case when df_id=74 then 'CSA' else 'Support' end as desig, case when emp_status='Active' then 'Yes' else 'No' end as 'Current Active (Y/N)',location from (select p.EmployeeID,s.ctc,e.emp_status,e.df_id,e.cm_id,p.INTID,p.createdon,l1.location from personal_details p inner join salary_details s on s.EmployeeID=p.EmployeeID inner join employee_map e on e.EmployeeID=p.EmployeeID join location_master l1 on p.location=l1.id where emp_status='Active' and cast(p.createdon as date) >= cast(? as date))t where INTID=? and cm_id=?";

							$Joined = '';
							$cmid = '';
							$desig = '';
							// $myDB = new MysqliDb();
							// $result2 = $myDB->rawQuery($sqlConnect);
							// $mysql_error = $myDB->getLastError();

							$selectQ = $conn->prepare($sqlConnect);
							$selectQ->bind_param("ssi", $CreatedOn, $INTID, $Process1);
							$selectQ->execute();
							$results = $selectQ->get_result();
							$result2 = $results->fetch_row();
							// print_r($results);
							// die;
							if ($results->num_rows > 0 && $results && $Process1 != '') {
								$table .= '<td>' . clean($result2[1]) . '</td>';
								$table .= '<td>' . clean($result2[0]) . '</td>';
								$table .= '<td>' . clean($result2[2]) . '</td>';
								$table .= '<td>' . clean($result2[5]) . '</td>';
								//$table .='<td>'.clean($result2[0])['location'].'</td>';
								$Joined = clean($result2[1]);
								$cmid = clean($result2[3]);
								$desig = clean($result2[4]);
								$empLocation = clean($result2[6]);
							} else {
								$table .= '<td>No</td>';
								$table .= '<td>NA</td>';
								$table .= '<td>NA</td>';
								$table .= '<td>No</td>';
								//$table .='<td>NA</td>';
							}


							$sqlConnect = "select amount,1st_pay,2nd_pay,window_month from ref_amount_master where emp_type=? and cm_id=? and (cast(? as date) between ApplicableFrom and ApplicableTo)";
							$amount = '';
							$fpay = '';
							$spay = '';
							$window_month = '';
							$fpayDate = '';
							$spayDate = '';

							// $myDB = new MysqliDb();
							// $result3 = $myDB->rawQuery($sqlConnect);
							// $mysql_error = $myDB->getLastError();

							$selectQy = $conn->prepare($sqlConnect);
							$selectQy->bind_param("sis", $desig, $cmid, $CreatedOn);
							$selectQy->execute();
							$resu = $selectQy->get_result();
							$result3 = $resu->fetch_row();
							// print_r($resu);
							// die;
							if ($resu->num_rows > 0 && $resu) {
								$amount = clean($result3[0]);
								$fpay = clean($result3[1]);
								$spay = clean($result3[2]);
								$window_month = clean($result3[3]);

								// Calculate Amount and Pay date

								$sqlConnect = "select des_id from whole_details_peremp where EmployeeID=?";
								$des_id = '';
								// $myDB = new MysqliDb();
								// $result4 = $myDB->rawQuery($sqlConnect);
								// $mysql_error = $myDB->getLastError();

								$selectQuy = $conn->prepare($sqlConnect);
								$selectQuy->bind_param("s", $EmployeeID);
								$selectQuy->execute();
								$resuQ = $selectQuy->get_result();
								$result4 = $resuQ->fetch_row();

								// print_r($resuQ);
								// die;
								if ($resuQ->num_rows > 0 && $resuQ) {
									$des_id = clean($result4[0]);
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
					?>

					<!-- </tbody>
	  </table>-->

				</div>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>



<script>
	$(document).ready(function() {

		$('#divbtn').addClass('hidden');
		$('#divdisc').addClass('hidden');
		$('#Accept').change(function() {
			if ($(this).prop('checked')) {
				$('#divbtn').removeClass('hidden');
			} else {
				$('#divbtn').addClass('hidden');
			}
		});

		$('#txtCandidateNumber').keydown(function(event) {
			if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||

				// Allow: Ctrl+A
				(event.keyCode == 65 && event.ctrlKey === true) ||

				// Allow: Ctrl+V
				(event.ctrlKey == true && (event.which == '118' || event.which == '86')) ||

				// Allow: Ctrl+c
				(event.ctrlKey == true && (event.which == '99' || event.which == '67')) ||

				// Allow: Ctrl+x
				(event.ctrlKey == true && (event.which == '120' || event.which == '88')) ||

				// Allow: home, end, left, right
				(event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
			} else {
				// Ensure that it is a number and stop the keypress
				if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
					event.preventDefault();
				}
			}

		});

		$('#txtCandidateNumber').keyup(function() {
			if ($('#txtCandidateNumber').val().length == 10) {
				var number = $('#txtCandidateNumber').val();
				jQuery.ajax({
					url: <?php echo '"' . URL . '"'; ?> + "Controller/number_validation.php?number=" + number
				}).done(function(data) {
					if (data == 0) {
						$('#txtCandidateNumber').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
						if ($('#stxtCandidateNumber').length == 0) {
							$('<span id="stxtCandidateNumber" class="help-block">Allready exists</span>').insertAfter('#txtCandidateNumber');

							$('#divbtn').addClass('hidden');
							$('#divdisc').addClass('hidden');
						} else {
							$('#stxtCandidateNumber').text('Allready exists');
							$('#divbtn').addClass('hidden');
							$('#divdisc').addClass('hidden');
						}

					} else {
						//alert('');
						$('#divdisc').removeClass('hidden');
						$('#Accept').prop('checked', false);
						$('#stxtCandidateNumber').empty();
					}
				});
			} else {
				$('#divbtn').addClass('hidden');
				$('#divdisc').addClass('hidden');
			}
		});


		$('#btn_df_Save').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			if ($('#txtCandidateName').val() == '' || $('#txtCandidateName').val() == 'NA') {
				$('#txtCandidateName').addClass('has-error');
				validate = 1;
				if ($('#stxtCandidateName').length == 0) {
					$('<span id="stxtCandidateName" class="help-block">Candidate Name can\'t be blank.</span>').insertAfter('#txtCandidateName');
				}
			}
			if ($('#txtCandidateNumber').val() == '' || $('#txtCandidateNumber').val().length < 10 || $('#txtCandidateNumber').val().length > 10) {
				$('#txtCandidateNumber').addClass('has-error');
				validate = 1;
				if ($('#stxtCandidateNumber').length == 0) {
					$('<span id="stxtCandidateNumber" class="help-block">Candidate Number should be a valid mobile number.</span>').insertAfter('#txtCandidateNumber');
				}
			}

			if ($('#txtCandidateLevel').val() == 'NA') {
				$('#txtCandidateLevel').addClass('has-error');
				validate = 1;
				if ($('#spantxtCandidateLevel').length == 0) {
					$('<span id="spantxtCandidateLevel" class="help-block">Please select candidate level</span>').insertAfter('#txtCandidateLevel');
				}
			}

			if (validate == 1) {
				return false;
			}

		});
	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>