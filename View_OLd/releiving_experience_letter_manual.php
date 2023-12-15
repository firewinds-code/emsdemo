<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
require(ROOT_PATH . 'AppCode/nHead.php');

// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
ini_set('display_errors', '0');
require('../TCPDF/tcpdf.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
// echo $target_dir = ROOT_PATH . "Meerut/";
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dateformat = date('y-m-d');
$EmployeeID = $EmployeeName = $locationid = $dol = $rsnofleaving = $Gender = $doj = $emailid = $designation = $loc = $mailerror = '';

$searchID = cleanUserInput($_POST['searchID']);
$expLetterSearchID = (isset($searchID) ? $searchID : null);
$__user_logid = clean($_SESSION['__user_logid']);
if ($__user_logid == 'CE09134997' || $__user_logid == 'CE12102224') {
	// proceed further
} else {
	$location = URL . 'error';
	echo '<script language="javascript">window.location.href ="' . $location . '"</script>';
	exit();
} ?>

<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Employee Search</span>
	<div class="pim-container">
		<div class="form-div">
			<h4>Employee Search</h4>
			<div class="schema-form-section row">
				<div class="input-field col s6 m6 8">
					<input type="text" id="searchID" name="searchID" title="Enter Employee ID Must Start With CE and Not Less Then 10 Char" value="<?php echo $expLetterSearchID; ?>">
					<label for="Search ID"> Employee ID</label>
				</div>

				<div class="input-field col s12 m12 right-align">
					<button type="submit" name="btn_ID_search" title="Click Here To Get Search Result" id="btn_ID_search" class="btn waves-effect waves-green">Search</button>

					<button type="submit" name="btn_sendLetter" id="btn_sendLetter" class="btn waves-effect modal-action modal-close waves-red close-btn">Send Letter</button>
				</div>

				<div id="pnlTable">
					<?php

					if (isset($_POST['btn_ID_search'])) {
						// $name = $_POST['searchID'];

						$sqlConnect = "select * from releiving_experience_ack where EmployeeID=?";
						$stmt1 = $conn->prepare($sqlConnect);
						$stmt1->bind_param("s", $searchID);
						$stmt1->execute();
						$getres = $stmt1->get_result();
						$result = $getres->fetch_row();
						// print_r($result[6]);
						// die;
						// $myDB = new MysqliDb();
						// $result = $myDB->rawQuery($sqlConnect);
						$locate = isset($result[6]) ? $result[6] : '';
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {
							if ($getres->num_rows > 0) { ?>
								<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
									<div class="">
										<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th> Employee ID </th>
													<th class="hidden"> Mail_response </th>
													<th> File Name </th>
													<th> Email ID </th>
													<th> Created At </th>
												</tr>
											</thead>
											<tbody>
												<?php
												// $target_dir = "";
												if ($locate == "1" || $locate == "2") {
													$target_dir =  "";
												}
												if ($locate == "3") {
													$target_dir =  "Meerut/";
												} else if ($locate == "4") {
													$target_dir =  "Bareilly/";
												} else if ($locate == "5") {
													$target_dir =  "Vadodara/";
												} else if ($locate == "6") {
													$target_dir =  "Manglore/";
												} else if ($locate == "7") {
													$target_dir =  "Bangalore/";
												} else if ($locate == "8") {
													$target_dir =  "Nashik/";
												} else if ($locate == "9") {
													$target_dir =  "Anantapur/";
												}

												foreach ($getres as $key => $value) {
													// echo "<pre>";
													// print_r($value);
													// die;
												?>
													<tr>
														<?php
														echo '<td class="EmployeeID">' . $value['EmployeeID'] . '</td>';
														echo '<td class="mailres hidden">' . $value['Mail_response'] . '</td>';
														echo '<td class="file_name"> 

													<i class="material-icons download_item imgBtn imgBtnDownload tooltipped"  data="' . $value['file_name'] . '" data-position="left" data-tooltip="Download File"><a href="../' . $target_dir . 'releiving__experience_pdf/' . $value['file_name'] . '" target="_blank">ohrm_file_download</a></i> </td>';
														echo '<td class="email_id">' . $value['email_id'] . '</td>';
														echo '<td class="Created_date">' . $value['Created_date'] . '</td>'; ?>
													</tr>

												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							<?php


							} else {
								echo "<script>$(function(){ toastr.info('No Records Found '); }); </script>";
								// echo "<script>$(function(){ $('#btn_sendLetter').show(); }); </script>"; 
								// echo "<script>$(function(){ $('#btn_ID_search').hide(); }); </script>"; 
							?>
								<script>
									$(function() {
										$('#btn_ID_search').hide();
										$('#btn_sendLetter').show();
									});
								</script>
					<?php	}
						} else {
							echo "<script>$(function(){ toastr.error('No Records Found'); }); </script>";
						}
					} else if (isset($_POST['btn_sendLetter'])) {

						$GetData = "select c.emailid, e.EmployeeID ,case when des.designation='CSA' then des.Designation else concat(des.Designation,' - ', fun.function) end as designation,ex.disposition, p.EmployeeName ,p.location,e.dateofjoin DOJ,p.Gender,p.MarriageStatus, DATE_FORMAT(ex.dol,'%Y-%m-%d') as dol ,ex.rsnofleaving ,ex.createdon,st.ctc from exit_emp ex left join  `employee_map` e on ex.EmployeeID=e.EmployeeID left JOIN `personal_details` p ON ((p.`EmployeeID` = e.`EmployeeID`)) LEFT JOIN `df_master` d ON ((d.`df_id` =e.`df_id`)) LEFT JOIN `designation_master` des ON ((des.`ID` = d.`des_id`)) left join function_master fun on d.function_id = fun.id left join contact_details c on c.`EmployeeID` = e.`EmployeeID` left join salary_details st on st.EmployeeID=e.EmployeeID where  ex.disposition in ('RES', 'IR') and e.dateofjoin < cast(date_sub(dol, interval 90 day) as date) and ex.EmployeeID =?";

						$title = '';
						$stmt1 = $conn->prepare($GetData);
						$stmt1->bind_param("s", $searchID);
						$stmt1->execute();
						$resultsE = $stmt1->get_result();
						$resultsEcount = $resultsE->num_rows;

						// $myDB = new MysqliDb();
						// $resultsE = $myDB->query($GetData);
						function dateFormatu($data)
						{
							return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date("M Y", strtotime($data));
						}
						// "count=" . count($resultsEcount);

						if ($resultsEcount > 0) {
							foreach ($resultsE as $val) {
								// echo "<pre>";
								// print_r($val);
								// die;
								$EmployeeID = $val['EmployeeID'];
								$EmployeeName = $val['EmployeeName'];
								$dol = $val['dol'];
								$dol = dateFormatu($dol);
								$rsnofleaving = $val['rsnofleaving'];
								$locationid = $loc = $val['location'];
								$doj = $val['DOJ'];
								$doj = dateFormatu($doj);
								$designation = $val['designation'];
								$MarriageStatus = $val['MarriageStatus'];
								$Gender = $val['Gender'];
								$emailid = $val['emailid'];

								if ($Gender == 'Female') {
									if ($MarriageStatus = 'Single') {
										$title = 'Ms.';
									} else {
										$title = 'Mrs.';
									}
								} else {
									$title = 'Mr.';
								}

								if (substr($EmployeeID, 0, 2) == 'AE' || substr($EmployeeID, 0, 2) == 'RS' || substr($EmployeeID, 0, 3) == 'OCM' || substr($EmployeeID, 0, 3) == 'RSM') {
									$companyname = 'Red Stone Consulting';
								} else {
									$companyname = 'Cogent E Services Ltd.';
								}

								$ctc = $val['ctc'];
								$annual = number_format($ctc * 12, 0);

								if ($loc != '') {
									if ($loc == "1" || $loc == "2") {
										$target_dir = ROOT_PATH . "releiving__experience_pdf/";
									}
									if ($loc == "3") {

										$target_dir = ROOT_PATH . "Meerut/releiving__experience_pdf/";
									} else if ($loc == "4") {

										$target_dir = ROOT_PATH . "Bareilly/releiving__experience_pdf/";
									} else if ($loc == "5") {
										$target_dir = ROOT_PATH . "Vadodara/releiving__experience_pdf/";
									} else if ($loc == "6") {
										$target_dir = ROOT_PATH . "Manglore/releiving__experience_pdf/";
									} else if ($loc == "7") {
										$target_dir = ROOT_PATH . "Bangalore/releiving__experience_pdf/";
									} else if ($loc == "8") {
										$target_dir = ROOT_PATH . "Nashik/releiving__experience_pdf/";
									} else if ($loc == "9") {
										$target_dir = ROOT_PATH . "Anantapur/releiving__experience_pdf/";
									}
									if (!is_dir($target_dir)) {
										@mkdir($target_dir, 0777, true);
									}
								}
								///// pdf creation	
								$pdf = "<h4>RELIEVING & EXPERIENCE LETTER</h4>";
								$pdf1 = "<table>
								<tr><td>Date:" . dateFormatu($dateformat) . "</td></tr><br><br>
								<tr><td><b>" . $title . "</b>" . $EmployeeName . "</td></tr>
								<tr><td><b>Employee Code: </b>" . $EmployeeID . "</td></tr>
								</table><br>
								<p>Further to your resignation from the company, we wish to inform you that you are relieved from your services effective close of working hours on " . $dol . ". While we regret your decision to leave the company. We wish you a successful career ahead.</p>

								<p>We hereby confirm the following details of your employment with " . $companyname . ":<br><br>
								<b> Designation:</b> " . $designation . "<br>
								<b> Date of Joining:</b> " . $doj . " <br>
								<b> Date of Leaving:</b> " . $dol . " <br>
								<b> Annual CTC:</b> " . $annual . " </p>
				
								<p>There are no financial dues pending.</p>
								<p>We wish you all the best for your future endeavors.</p> <br /><br />";

								$pdftruly = "<p>Yours truly,<br>
								<b>For " . $companyname . "</b></p></br>";
								$pdf2 = "<p>(S.K Garg)</p>
								<p><b>(Authorized Signatory)</b></p>";

								if ($companyname == 'Red Stone Consulting') {
									$location = 'Red Stone Consulting, 53, Madhav Kunj, Pratap Nagar, Agra - 282010, India <br> Website: https://redstonec.in/';
								} else {
									$Locationquery = "SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master  a Inner Join location_master b on a.locationId=b.id where a.locationId =?";

									$stmt1 = $conn->prepare($Locationquery);
									$stmt1->bind_param("i", $locationid);
									$stmt1->execute();
									$Locationresult = $stmt1->get_result();

									// $myDB = new MysqliDb();
									$location_array = array();
									// $Locationresult = $myDB->query($Locationquery);

									if ($Locationresult->num_rows > 0) {
										foreach ($Locationresult as $val) {

											if ($locationid == "1" || $locationid == "2") {

												if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
													$location = '';
													$location = $val['companyname'] . ' ' . $val['address'] . '<br> Website : www.cogenteservices.com';
												}
											} else {
												if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
													$location = '';
													$location = $val['companyname'] . ' ' . $val['address'] . '<br> Website : www.cogenteservices.com';
												} else {
													$location = '';
													$location = $val['companyname'] . ' ' . $val['address'] . '<br> Website : www.cogenteservices.com';
												}
											}
										}
									}
								}

								//$pdf3 = '<p style="text-align: center"><u>' . $location . '</u></p>';
								$pdf3 = "
								<style>
								p{
									color:#958d8d;
									text-align:center;
									font-size:13px;
									margin:15px 15px;
								}
								</style>
	            				<br><br><hr><p>$location </p>";

								$filename = $EmployeeID . "_RelievingExperience.pdf";
								if ($target_dir != '') {
									$path = $target_dir . $filename;
								}

								$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

								$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

								$tcpdf->SetTitle('Cogent|Relieving & Experience Letter');

								$tcpdf->SetMargins(10, 10, 10, 10);
								$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
								$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

								$tcpdf->setPrintHeader(false);
								$tcpdf->setPrintFooter(false);
								$tcpdf->setListIndentWidth(3);

								$tcpdf->SetAutoPageBreak(TRUE, 11);

								$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

								$tcpdf->AddPage();

								$tcpdf->SetFont('times', '', 10.5);

								$tcpdf->Image('../Style/img/sk_sign.jpeg', 10, 180, 30, 15, 'JPEG'); //SIG

								if ($companyname == "Cogent E Services Ltd.") {
									$tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); //logo right cogent logo
								} else {
									$tcpdf->Image('../Style/images/redstone.jpg', 150, 20, 40, 10, 'JPG'); //logo right cogent logo
								}

								/*if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {
									if ('AE' == substr($EmployeeID, 0, 2)) {
										$tcpdf->Image('../Style/images/client_logo.JPG', 140, 5, 60, 30, 'JPG'); //logo right Aurum
									} else {
										$tcpdf->Image('../Style/images/newLogo cogent.jpg', 140, 5, 60, 30, 'JPG'); //logo right cogent logo
									}
									} elseif ($locationid == "3") {
									if ('OC' == substr($EmployeeID, 0, 2)) {
										$tcpdf->Image('../Style/images/client_logo.png', 140, 5, 60, 30, 'PNG'); //logo right Orium
									} else {
										$tcpdf->Image('../Style/images/newLogo cogent.jpg', 140, 5, 60, 30, 'JPG'); //logo right cogent logo
									}
								}*/

								$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 0, $y = 40, $pdf, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');

								$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 60, $pdf1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

								$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 170, $pdftruly, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

								$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 200, $pdf2, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

								$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 255, $pdf3, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');

								if ($path != '') {
									$tcpdf->Output($path, 'F');
								}

								/////end pdf

								///////mail functionality///////////
								if ($emailid != "") {

									$mail = new PHPMailer;
									$mail->isSMTP();
									$mail->Host = EMAIL_HOST;
									$mail->SMTPAuth = EMAIL_AUTH;
									$mail->Username = EMAIL_USER;
									$mail->Password = EMAIL_PASS;
									$mail->SMTPSecure = EMAIL_SMTPSecure;
									$mail->Port = EMAIL_PORT;
									$mail->setFrom(EMAIL_FROM,  'Cogent | Relieving & Experience Letter');
									//$mail->AddAddress('srishti.rao@cogenteservices.com');
									$mail->AddAddress($emailid);
									// $mail->addBCC('vijayram.yadav@cogenteservices.com');
									$mail->addBCC('sanoj.pandey@cogenteservices.com');
									$mail->Subject = "Candidate Relieving & Experience Letter";
									$mail->isHTML(true);
									$msg2 = $title . " " . $EmployeeName . ',<br/><br/>
									Please find  attached your Relieving & Experience letter which was proceed on ' . $dol . '.</br><br/>
									Thanks <br/> Cogent ';
									$mail->Body = $msg2;
									$mymsg = '';
									$response = '';
									$mail->AddAttachment($path);
									if (!$mail->send()) {
										$response =  'Mailer Error:' . $mail->ErrorInfo;
										echo "<script>$(function(){ toastr.error('" . $response . "') });</script>";
										$mailerror = 0;
										echo "<script>
										$(function(){
												$('#myTable,#btn_ID_search').hide();
												$('#btn_sendLetter').show();
											});
										</script>";
									} else {
										$response =  'Mail Send successfully';
										echo "<script>$(function(){ toastr.success('" . $response . "') });</script>";
										$mailerror = 1;
										echo "<script>
										$(function(){
											$('#myTable,#btn_ID_search').show();
											$('#btn_sendLetter').hide();
										});
										</script>";
									}

									//insert data
									$dt = new datetime();
									$dt = $dt->format('Y-m-d H:i:s');
									$myDB = new MysqliDb();
									$Insertrelieving = "insert into releiving_experience_ack (EmployeeID, Mail_response,file_name, email_id ,Created_date,location_id)values(?,?,?,?,?,?);";
									$stmt1 = $conn->prepare($Insertrelieving);
									$stmt1->bind_param("sssssi", $EmployeeID, $response, $filename, $emailid, $dt, $locationid);
									$stmt1->execute();
									$Locationresult = $stmt1->get_result();
									// $myDB = new MysqliDb();
									// $resu = $myDB->rawQuery($Insertrelieving);
									$error = $myDB->getLastError();
									if (empty($error)) {
										echo "<script>$(function(){ toastr.success('Successfully Inserted...') });</script>";
									}
								} else {
									$response =  " $emailid Your emailid is not exist.";
									echo "<script>$(function(){ toastr.error('" . $response . "') });</script>";
								}

								///////////////end mail


							}
						} else {
							// echo "<script>$(function(){ toastr.error('Not eligible for Experince Letter.'); }); </script>";
							echo "<script>$(function(){ toastr.error('Not Eligible for Experiance Letter.'); });</script>";
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$('#btn_ID_search').show();
	$('#btn_sendLetter').hide();
</script>

<style>
	.short,
	.weak {
		color: red;
	}

	.good {
		color: #e66b1a;
	}

	.strong {
		color: green;
	}
</style>

<?php
include(ROOT_PATH . 'AppCode/footer.mpt');
?>