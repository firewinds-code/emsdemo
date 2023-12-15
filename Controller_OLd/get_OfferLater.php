<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Appointment Letter</title>
	<link rel="stylesheet" href="../Style/bootstrap.css" />
	<style>
		@media print {
			body {
				font-size: 13px;
			}
		}
	</style>
</head>

<body>
	<form name="indexForm" enctype="multipart/form-data" role="form" id="indexForm" method="post" action="<?php echo ($_SERVER['PHP_SELF']); ?>">
		<?php

		require_once(__dir__ . '/../Config/init.php');
		// require_once(CLS . 'MysqliDb.php');
		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();

		// if (!($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_type'] == 'HR' || $_SESSION['__user_type'] == 'AUDIT')) {
		// 	echo 'Wrong way to access this link.';
		// 	exit();
		// }
		if (isset($_REQUEST['EmpID'])) {
			$EmployeeID = clean($_REQUEST['EmpID']);
		} else {
			$EmployeeID = cleanUserInput($_POST['Empid']);
		}
		function convertNumberToWord($num = false)
		{
			$num = str_replace(array(',', ' '), '', trim($num));
			if (!$num) {
				return false;
			}
			$num = (int) $num;
			$words = array();
			$list1 = array(
				'', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven',
				'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
			);
			$list2 = array('', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');
			$list3 = array(
				'', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion', 'Quintillion', 'Sextillion', 'Septillion',
				'Octillion', 'Nonillion', 'Decillion', 'Undecillion', 'Duodecillion', 'Tredecillion', 'Quattuordecillion',
				'Quindecillion', 'Sexdecillion', 'Septendecillion', 'Octodecillion', 'Novemdecillion', 'Vigintillion'
			);
			$num_length = strlen($num);
			$levels = (int) (($num_length + 2) / 3);
			$max_length = $levels * 3;
			$num = substr('00' . $num, -$max_length);
			$num_levels = str_split($num, 3);
			for ($i = 0; $i < count($num_levels); $i++) {
				$levels--;
				$hundreds = (int) ($num_levels[$i] / 100);
				$hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : '') . ' ' : '');
				$tens = (int) ($num_levels[$i] % 100);
				$singles = '';
				if ($tens < 20) {
					$tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
				} else {
					$tens = (int)($tens / 10);
					$tens = ' ' . $list2[$tens] . ' ';
					$singles = (int) ($num_levels[$i] % 10);
					$singles = ' ' . $list1[$singles] . ' ';
				}
				$words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
			} //end for loop
			$commas = count($words);
			if ($commas > 1) {
				$commas = $commas - 1;
			}
			return implode(' ', $words);
		}

		$sqlquery = "select location from personal_details where EmployeeID=?";
		$select = $conn->prepare($sqlquery);
		$select->bind_param("s", $EmployeeID);
		$select->execute();
		$res = $select->get_result();
		$Locationresult = $res->fetch_row();
		// $Locationresult = $myDB->query($sqlquery);
		$locationid = clean($Locationresult[0]);
		//$locationid=$_SESSION['__location'];
		$func = '';
		$gender = 'Mr.';
		$sql = 'select whole_details_peremp.EmployeeID,EmployeeName,DOJ,designation,ctc,status_table.onFloor,des_id,`function`,Gender from whole_details_peremp left outer join salary_details on salary_details.EmployeeID = whole_details_peremp.EmployeeID left outer join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID where whole_details_peremp.EmployeeID=? limit 1';
		$selectQ = $conn->prepare($sql);
		$selectQ->bind_param("s", $EmployeeID);
		$selectQ->execute();
		$result = $selectQ->get_result();
		$results = $result->fetch_row();
		// $result = $myDB->query($sql);


		$location_address = "SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master  a Inner Join location_master b on a.locationId=b.id where a.locationId =? ";
		$sel = $conn->prepare($location_address);
		$sel->bind_param("i", $locationid);
		$sel->execute();
		$Locationresult = $sel->get_result();
		$Locationresults = $Locationresult->fetch_row();

		// $Locationresult = $myDB->query($location_address);
		if ($result->num_rows > 0 && $result) {
			$EmployeeID = clean($results[0]);
			$EmployeeName = clean($results[1]);
			if (intval(clean($results[6])) == 9 || intval(clean($results[6])) == 12) {
				$DOJ = clean($results[5]);
			} else {
				$DOJ = clean($results[2]);
			}
			if (strtoupper(clean($results[8])) == 'FEMALE') {
				$gender = 'Ms.';
			} else {
				$gender = 'Mr.';
			}
			$func = clean($results[7]);
			$designation = clean($results[3]);
			$ctc = clean($results[4]);
		?>
			<div id="div_print" class="container">
				<input type="hidden" name="Empid" id="Empid" value="<?php echo $EmployeeID; ?>" />
				<div class="col-sm-12 pull-left" style="width: 100%;">
					<?php
					if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {

						if ('AE' == substr($EmployeeID, 0, 2)) {
					?>
							<div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;"><img src="../Style/images/client_logo.JPG" style="width:220px;height: 100px;" /></div>
						<?php
						} else {
						?>
							<div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;"><img src="../Style/images/newLogo cogent.jpg" style="width: 220px;height: 100px;" /></div>
						<?php
						}
					} else if ($locationid == "3") {

						if ('OC' == substr($EmployeeID, 0, 2)) {
						?>
							<div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;"><img src="../Style/images/client_logo.png" style="width:220px;height: 100px;" /></div>
						<?php
						} else {
						?>
							<div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;"><img src="../Style/images/newLogo cogent.jpg" style="width:220px;height: 100px;" /></div>
					<?php
						}
					}
					?>

				</div>
				<h4 style="text-align: center;"> Appointment Letter</h4>

				<p>Dear <?php echo '<b>' . $gender . ' ' . $EmployeeName . '</b>'; ?>,<br />
					<strong>Reference ID: <label><?php echo $EmployeeID; ?></label></strong>
				</p>
				<p>We are pleased to appoint you in <strong>
						<?php
						if ($Locationresult->num_rows > 0) {
							foreach ($Locationresult as $val) {
								if ($locationid == "1" || $locationid == "2") {
									if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
										echo $val['companyname'];
									}
								} else {
									if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
										echo $val['companyname'];
									}
								}
							}
						}
						?></strong>(&ldquo;<strong>Company</strong>&rdquo;) as <strong>&lsquo;<?php
																								if ($designation == 'CSA') {
																									echo 'Customer Support Associate';
																								} elseif ($designation == 'Senior CSA') {
																									echo 'Senior Customer Support Associate';
																								} else {
																									echo $designation . ' - ' . $func;
																								}
																								?>&rsquo;</strong> at our <strong><?php echo clean($Locationresults[5]); ?></strong> office as per the employment terms and conditions stated below. Please note that the employment terms contained in this letter (&ldquo;<strong>Appointment Letter</strong>&rdquo;) are subject to Company policy.</p>
				<p><strong>1.         Appointment </strong><br />
					Your date of appointment is effective from the date of execution of this Appointment Letter (&ldquo;<strong><?php if (date('d', strtotime($DOJ)) == '1') {

																																	echo '1<sup>st</sup> ';
																																} elseif (date('d', strtotime($DOJ)) == '2') {
																																	echo '2<sup>nd</sup> ';
																																} elseif (date('d', strtotime($DOJ)) == '3') {
																																	echo '3<sup>rd</sup> ';
																																} else {
																																	echo date('d', strtotime($DOJ)) . '<sup>th</sup> ';
																																}
																																echo date('F Y', strtotime($DOJ)); ?></strong>&rdquo;). The term of your employment with the Company shall commence on the Effective Date and shall continue unless this Appointment Letter is terminated earlier in accordance with its terms (&ldquo;<strong>Employment Term</strong>&rdquo;).You will be on probation for a period of 3 months from the date of your joining employment with the Company. There will be periodic review of your work, adaptability, acceptability and demeanor. If the review rating does not meet the acceptable standards, the Company reserves its right to extend this period of probation beyond 3 months but in no event shall the period of probation exceed 6 months. On completion of initial probation period till such time that you are intimated in writing regarding your confirmation, you shall continue to be on probation.</p>
				<p><strong>2.         Assignment / transfer</strong>
					Your usual place of work will be at our office at <b><?php echo clean($Locationresults[5]); ?></b>, India. However, the Company reserves the right to transfer/ assign you to specific projects, assignments, jobs etc. in which case you will be required to perform your services at such location, division, department or branch of the Company as the Company may deem fit.</p>
				<p><strong>3.         Compensation</strong><br />
					(a)        Your cost to the company (CTC) will be Rs.<strong style="min-width: 50px;display: inline-block;text-align: center;"> <?php echo round($ctc); ?>/-&nbsp;</strong><b>(&nbsp;</b><strong style="min-width: 150px;display: inline-block;"><?php
																																																																		echo convertNumberToWord(round($ctc));
																																																																		?>Rupees Only</strong><b>&nbsp;)</b> per month including all statutory requirements. Your salary will be reviewed periodically as per Company policy.</p>
				<p>(b)        Changes in your compensation are discretionary and will be subject to and on basis of effective performance and results during the period and other relevant criteria.</p> <label style="line-height: 15px !important;margin: 0px;padding: 0px;font-weight: normal !important; "><strong>4.         Working hours</strong><br />
					(a)       Your normal <span style="font-size: 14px;">office </span>hours shall be intimated at the time of joining. The Company reserves the right to require you to work outside your normal working hours if necessary in furtherance of your duties. Suitable remedies / remuneration will be provided by the company to you in such case.</label><br /><br /><strong>5.         Responsibilities</strong>
				You must effectively, diligently and to the best of your ability perform all responsibilities and ensure results.<br /><br /><strong>6.         Non – disclosure obligations and confidentiality</strong>
				At all times during and after the Employment Term, you will hold in strictest confidence and not use for your own purposes or the purposes of others or disclose any confidential information pertaining to the Company or its clients. Further, in consideration of the opportunities, training and access to new training and know-how that will be made available to you, you will be required to comply with the confidentiality policy of the Company and/or its clients.<p></p><strong>7.         Company property</strong>
				Any and all notes, records, other documents, in any way relating to the business or affairs of the Company or clients shall at all times remain the property of the Company and shall be returned to the Company upon you ceasing to be in the Company&rsquo;s employment or at any other time at the request of the Company.<br />In the event of the termination of your employment for any reason, and subject to any other provisions hereof, the Company reserves the right, to the extent required by law, and in addition to any other remedy the Company may have, to deduct from any monies otherwise payable to you the full amount of any specifically determined debt you owe to the Company at the time of or subsequent to the termination of your employment with the Company including but not limited to salary in lieu of notice period. <br /><br />
				<hr style="margin-top: 15px;margin-bottom: 15px;border: 1px solid #9a9a9a;" />
				<p style="text-align: center;color: #a0a0a0;"><b>
						<?php
						if ($Locationresult->num_rows > 0) {
							foreach ($Locationresult as $val) {
								if ($locationid == "1" || $locationid == "2") {
									if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
										echo $val['companyname'] . "</b> ";
										echo $val['address'];
									}
								} else {
									if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
										echo $val['companyname'] . "</b> ";
										echo $val['address'];
									}
								}
							}
						}
						?>
						Website : www.cogenteservices.com
				</p> </label>
				<p></p>
				<p><strong>8.         Termination of Employment</strong><br />
					You are required to give the Company the following period of notice, in writing, to terminate your employment, namely:-</p>
				<p>(a)        Thirty Days till you have completed your probationary period, as mentioned in clause 1.</p>
				<p>(b)        Thirty days after your confirmation as a permanent employee.</p>
				<p>(d)        The Company may, in its sole discretion, terminate your employment without cause by giving 30 day notice or salary in lieu thereof. <br />
					<br />
					(e)        Your employment is liable to be terminated forthwith by the Company without prior notice if, any declaration/statement or information forthwith given by you in your application or in connection with your appointment is at any time found to be false or untrue or any material particulars are suppressed. Further the Company reserves the right to terminate your services without any notice or salary in lieu thereof for misconduct, negligence of duty, disloyalty, dishonesty, indiscipline, disobedience, irregular attendance, long period of absence from duty due to ill-health, infirmity or accident or inefficiency as compared to other employees.
				</p>
				<p>If you absent yourself without leave or remain absent from work with no information for more than 3 days or remain absent beyond the period of leave originally granted or subsequently extended, you shall be considered as having voluntarily terminated your employment without giving any notice and as per the terms above your salary will be deducted in lieu of notice period. </p>
				<p><strong>9.         Exclusivity / Prior Commitment</strong><br />
					You agree to work exclusively for the Company, within the context of the responsibilities defined above, and not to accept or perform any other paid/ unpaid employment or consulting in addition to this, even temporary. You agree, represent and warrant to the Company that you are not subject to/party to any agreements or restrictions, including, without limitation, those arising out of any prior employments which would be breached or violated by your execution of this Appointment Letter.</p><label style="font-weight:normal !important;"><strong>10.       Jurisdiction </strong><br />The appointment shall be governed by and interpreted in accordance with the laws of India and the courts of New Delhi.</label>
				<p><strong>11.       Entire Agreement </strong><br />
					You agree and acknowledge that with effect from the Effective Date this Appointment Letter represents the entire agreement between you and the Company and supersedes any previous appointment letters/ contracts entered into between you and the Company.</p>
				<br />
				<p>We take pleasure in welcoming you to our Company and looking forward to a mutually beneficial association.</p>
				<br />
				<p>Yours truly,<br />
					<strong><?php
							if ($Locationresult->num_rows > 0) {
								foreach ($Locationresult as $val) {
									if ($locationid == "1" || $locationid == "2") {
										if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
											echo 'For ' . $val['companyname'];
										}
									} else {
										if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
											echo 'For ' . $val['companyname'];
										}
									}
								}
							}
							?></strong>
				</p>
				<br />
				<p><img src="../Style/img/sk_sign.jpeg" style="width: 120px;height: 45px;" /></p>
				<p><strong>(S.K<a name="_GoBack" id="_GoBack"></a> Garg)</strong><br />
					<strong>Authorized Signatory</strong><br />
				</p>
				<div style="border: 1px solid gray;padding: 10px;">
					<p>I have carefully read and understood the terms and conditions mentioned above. All the terms and conditions of this appointment have been accepted by me.</p>
					<p style="width: 100%;">Name: <?php echo '<b>' . $EmployeeName . '</b>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature: <br />
						Date:</p>
				</div>
			</div>
			<!--<input type="submit" id="export_btn" name="export_btn" style="display: none;float: left;" value="Download"/> -->
			<input type="button" id="print_btn" name="print_btn" onClick="printdiv('div_print');" style="display: block;" value="Print" />
	</form>
</body>
<?php

			if (isset($_POST['export_btn'])) {
				header("Content-type: application/vnd.ms-word");
				header("Content-Disposition: attachment;Filename=offerlatter" . $EmployeeID . ".doc");
			}

			echo "";
		}


?>
<script>
	function printdiv(printpage) {
		var headstr = "<html><head><title></title></head><body>";
		var footstr = "</body>";
		var newstr = document.all.item(printpage).innerHTML;
		var oldstr = document.body.innerHTML;
		document.body.innerHTML = headstr + newstr + footstr;
		window.print();
		document.body.innerHTML = oldstr;
		return false;
	}
</script>

</html>