<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
//Code Snipt
require(ROOT_PATH . 'AppCode/nHead.php');

$EmployeeID = $imsrc = $employeeName = '';
$imsrc_ah = $imsrc_er = $imsrc_rt = $imsrc_oh = $imsrc_qh = $imsrc_th = $imsrc_qa = $vhname = $ctc = $imsrc_vh = $contact_vh = $esi_no = $ctcRange = '';
$locationdir = '';
$a = $b = $c = $d = $e = $f = $g = $h = $n2 = $n1 = $n3 = $n4 = $gender = $rrrid = $rrid = $rid = $rrtname = $rrrtname = $rtname = '';
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	$EmployeeID = $_POST['EmployeeID'];
} else {
	$EmployeeID = $_REQUEST['empid'];
}
if ($_SESSION['__user_logid'] == $EmployeeID) {

	//print_r($_SESSION);
	$ofc_loc = $_SESSION['__location'];
	$qrloc = "Noida";
	if ($ofc_loc == "1") {
		$qrloc = "Noida/";
	} else if ($ofc_loc == "2") {
		$qrloc = "Mumbai/";
	} else if ($ofc_loc == "3") {
		$locationdir = "Meerut/";
		$qrloc = "Meerut/";
	} else if ($ofc_loc == "4") {
		$locationdir = "Bareilly/";
		$qrloc = "Bareilly/";
	} else if ($ofc_loc == "5") {
		$locationdir = "Vadodara/";
		$qrloc = "Vadodara/";
	} else if ($ofc_loc == "6") {
		$locationdir = "Manglore/";
		$qrloc = "Mangalore/";
	} else if ($ofc_loc == "7") {
		$locationdir = "Bangalore/";
		$qrloc = "Bangalore/";
	} else if ($ofc_loc == "8") {
		$locationdir = "Nashik/";
		$qrloc = "Nashik/";
	} else if ($ofc_loc == "9") {
		$locationdir = "Anantapur/";
		$qrloc = "Anantapur/";
	}
	// if ($locationdir != "") {
	// $qrloc = $locationdir;
	// }
} else {
	$location = URL;
	echo "<script>location.href='" . $location . "'</script>";
}

/*if($_SESSION['__user_logid'] != $EmployeeID && ($_SESSION["__user_type"] == 'ADMINISTRATOR' || $_SESSION["__user_type"] == 'HR'))
{
	
}
else if($_SESSION['__status_ah'] == $_SESSION['__user_logid'] || $_SESSION['__user_logid'] == 'CE04146339')
{
	
}*/

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee Information <?php echo $EmployeeID; ?></span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<style>
			td>.emp_image {
				float: left;
			}

			.responsive-table td:nth-child(2n - 1) {
				min-width: 35%;
			}
		</style>
		<div class="form-div">
			<h4>Employee Individual Information</h4>
			<div class="schema-form-section row">
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
				<div id="row" class="row had-container">
					<div class="col s3" id="div_me" align="center">
						<div class="col s12">
							<div class="card">
								<div class="card-image" id="div_picture">
									<img id="imgToempl" name="imgToempl" class="emp_image" src="../Style/images/agent-icon.png" style="height: 200px;width: 100%;margin-top: 8px;" />
									<span class="card-title white-text text-darken-4" style="background: #06060636;"><?php echo $EmployeeID; ?></span>
								</div>
								<div class="card-content">
									<p id="empname_h4"></p>
								</div>

								<?php
								$en_empid = "";
								$sqlstr = "select empid from employee_qrcode where EmployeeID='" . $EmployeeID . "' ";
								$myDB = new MysqliDb();
								$result = $myDB->rawQuery($sqlstr);
								$MysqliError = $myDB->getLastError();
								if (empty($MysqliError)) {
									$en_empid = $result[0]['empid'];
								}
								//$en_empid=base64_encode($EmployeeID);									
								// if(preg_match('/[a-z]/', $en_empid))
								// {
								// $en_empid=strtoupper($en_empid);
								// }
								?>
								<div style="display:block;" class="card-image" id="div_picture">
									<img id="imgQrCode" name="imgQrCode" class="emp_image" src="../QrSetup/<?php echo "$qrloc/$en_empid-Ref.png"; ?>" class="img-thumbnail" />
									<!-- style="height: 230px;width: 100%;margin-top: -20px;"-->
									<a style="position: relative;top: -30px;text-decoration: underline;font-size: 16px;" href="../QrSetup/<?php echo "$qrloc/$en_empid-Ref.png"; ?>" download>Download QR Code</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col s9">
						<ul class="collapsible">

							<li>
								<div class="collapsible-header topic"> Personal Details</div>
								<div class="collapsible-body">
									<div id="personal_div" class="list-container">
										<?php

										if (isset($_REQUEST['empid']) || $EmployeeID != '') {
											$getDetails = 'call get_personal("' . $EmployeeID . '")';
											$myDB = new MysqliDb();
											$result = $myDB->rawQuery($getDetails);
											$MysqliError = $myDB->getLastError();
											if (empty($MysqliError)) {
												foreach ($result as $key => $value) {
													$employeeName = $value['EmployeeName'];
													$DOB = $value['DOB'];
													$FatherName = $value['FatherName'];
													$MotherName = $value['MotherName'];
													$Gender = $value['Gender'];
													$BloodGroup = $value['BloodGroup'];
													$MarriageStatus = $value['MarriageStatus'];
													$Spouse = $value['Spouse'];
													$MarriageDate = $value['MarriageDate'];
													$ChildStatus = $value['ChildStatus'];
													$ctc = $value['ctc'];
													$esi_no = $value['esi_no'];
													$ctcRange = '21050';
												}
												echo '<table class="responsive-table"><tr>';
												echo "<td>Name </td><td><b>" . $result[0]['EmployeeName'] . '</b></td></tr><tr>';
												//echo "<td>Name </td><td><b>".$employeeName."</b></td></tr><tr>";
												echo "<td>Date Of Birth </td><td><b>" . $DOB . "</b></td></tr><tr>";
												echo "<td>Father`s Name </td><td><b>" . $FatherName . "</b></td></tr><tr>";
												echo "<td>Mother`s Name </td><td><b>" . $MotherName . "</b></td></tr><tr>";
												echo "<td>Gender </td><td><b>" . $Gender . "</b></td></tr><tr>";
												echo "<td>Blood Group </td><td><b>" . $BloodGroup . "</b></td></tr><tr>";
												if ($ctc <= $ctcRange) {
													echo "<td>ESIS No. </td><td><b>" . $esi_no . "</b></td></tr><tr>";
												}

												echo "<td>Marital Status </td><td><b>" . $MarriageStatus . "</b></td></tr><tr>";
												echo "<td>Spouse </td><td><b>" . $Spouse . "</b></td></tr><tr>";
												echo "<td>Marriage Date </td><td><b>" . $MarriageDate . "</b></td></tr><tr>";
												echo "<td>Child Status </td><td><b>" . $ChildStatus . "</b></td>";
												echo '</tr></table>';
												if ($value['img'] != '') {
													$imsrc = URL . $locationdir . 'Images/' . $value['img'];
												}
												$btnShow = ' hidden';
												if ($ChildStatus == 'Yes') {

													//$sqlConnect = array('table' => 'child_details','fields' => '*','condition' =>"EmployeeID='".$EmployeeID."'"); 
													$sqlConnect = " select * from child_details where EmployeeID='" . $EmployeeID . "'";
													$myDB = new MysqliDb();
													$result = $myDB->rawQuery($sqlConnect);
													$MysqliError = $myDB->getLastError();
													if (empty($MysqliError)) { ?>
														<div class="had-container">
															<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
																<thead>
																	<tr>
																		<th>Child Name</th>
																		<th>Child DOB</th>
																		<th>Child Gender</th>

																	</tr>
																</thead>
																<tbody>
																	<?php
																	foreach ($result as $key => $value) {
																		echo '<tr>';
																		echo '<td>' . $value['ChildName'] . '</td>';
																		echo '<td>' . $value['ChildDob'] . '</td>';
																		echo '<td>' . $value['ChildGender'] . '</td>';
																		echo '</tr>';
																	}
																	?>
																</tbody>
															</table>
														</div>
										<?php
													}
												}
											} else {
												echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
											}
										}
										?>
									</div>
								</div>
							</li>

							<li>
								<div class="collapsible-header topic"> Contact Details</div>
								<div class="collapsible-body">
									<div id="contact_div" class="list-container">
										<?php
										if (isset($_REQUEST['empid']) || $EmployeeID != '') {
											$getDetails = 'call get_contact("' . $EmployeeID . '")';
											$myDB = new MysqliDb();
											$result = $myDB->rawQuery($getDetails);
											$MysqliError = $myDB->getLastError();
											if (empty($MysqliError)) {
												foreach ($result as $key => $value) {
													echo '<table class="responsive-table"><tr>';
													echo "<td>Mobile No </td><td><b>" . $value['mobile'] . '</b></td></tr><tr>';
													echo "<td>Alternate No </td><td><b>" . $value['altmobile'] . '</b></td></tr><tr>';
													echo "<td>Email ID </td><td><b>" . $value['emailid'] . '</b></td></tr>';
													echo '</table>';
												}
												$btnShow = ' hidden';
											} else {
												echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
											}
										}
										?>
										<?php

										//$sqlConnect = array('table' => 'doc_details','fields' => '*','condition' =>"EmployeeID='".$EmployeeID."'"); 
										$sqlConnect = "select * from doc_details where EmployeeID='" . $EmployeeID . "'";
										$myDB = new MysqliDb();
										$result = $myDB->rawQuery($sqlConnect);
										$MysqliError = $myDB->getLastError();
										if (empty($MysqliError)) { ?>
											<div class="had-container">
												<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Document Type</th>
															<th>Document List</th>
															<th>Document ID</th>
															<!--<th style="width:100px;">Download </th>-->
														</tr>
													</thead>
													<tbody>
														<?php
														foreach ($result as $key => $value) {
															echo '<tr>';
															echo '<td>' . $value['doc_type'] . '</td>';
															echo '<td>' . $value['doc_stype'] . '</td>';
															echo '<td>' . $value['dov_value'] . '</td>';
															/*echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  onclick="javascript:return Download(this);"  title="View Data Item" data="'.$value['doc_file'].'" /></td>';*/
															echo '</tr>';
														}
														?>
													</tbody>
												</table>
											</div>
										<?php
										}
										?>
									</div>
								</div>
							</li>
							<li>
								<div class="collapsible-header topic"> Address Details</div>
								<div class="collapsible-body">
									<div id="Address_div" class="list-container">
										<?php
										if (isset($_REQUEST['empid']) || $EmployeeID != '') {

											$getDetails = 'call get_address("' . $EmployeeID . '")';
											$myDB = new MysqliDb();
											$result = $myDB->rawQuery($getDetails);
											$MysqliError = $myDB->getLastError();
											if (empty($MysqliError)) {
												foreach ($result as $key => $value) {
													$address = $value['address'];
													$country = $value['country'];
													$state = $value['state'];
													$district = $value['district'];
													$city = $value['city'];
													$tehsil = $value['tehsil'];
													$other = $value['other'];
													$zip = $value['zip'];

													$address_p = $value['address_p'];
													$country_p = $value['country_p'];
													$state_p = $value['state_p'];
													$district_p = $value['district_p'];
													$city_p = $value['city_p'];
													$tehsil_p = $value['tehsil_p'];
													$other_p = $value['other_p'];
													$zip_p = $value['zip_p'];
												}

												echo '<table class="responsive-table"><tr>';

												echo '<tr><th colspan="2">Current Address</th></tr>';


												echo "<td>Address </td><td><b>" . $address . '</b></td></tr><tr>';
												echo "<td>Country </td><td><b>" . $country . '</b></td></tr><tr>';
												echo "<td>State </td><td><b>" . $state . '</b></td></tr><tr>';
												echo "<td>District </td><td><b>" . $district . '</b></td></tr><tr>';
												echo "<td>City </td><td><b>" . $city . '</b></td></tr><tr>';
												echo "<td>Tehsil </td><td><b>" . $tehsil . '</b></td></tr><tr>';
												echo "<td>Landmark   </td><td><b>" . $other . '</b></td></tr><tr>';
												echo "<td>Pin Code </td><td><b>" . $zip . '</b></td></tr><tr>';

												echo '<tr><th  colspan="2">Permanent Address</th></tr>' . '</b></td></tr><tr>';

												echo "<td>Address </td><td><b>" . $address_p . '</b></td></tr><tr>';
												echo "<td>Country </td><td><b>" . $country_p . '</b></td></tr><tr>';
												echo "<td>State </td><td><b>" . $state_p . '</b></td></tr><tr>';
												echo "<td>District </td><td><b>" . $district_p . '</b></td></tr><tr>';
												echo "<td>City </td><td><b>" . $city_p . '</b></td></tr><tr>';
												echo "<td>Tehsil </td><td><b>" . $tehsil_p . '</b></td></tr><tr>';
												echo "<td>Landmark  </td><td><b>" . $other_p . '</b></td></tr><tr>';
												echo "<td>Pin Code </td><td><b>" . $zip_p . '</b></td></tr>';
												echo '</table><br />';
											} else {
												echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
											}
										}
										?>
									</div>
								</div>
							</li>
							<li>
								<div class="collapsible-header topic"> Education Details</div>
								<div class="collapsible-body">
									<div id="Education_div" class="list-container">
										<?php
										$myDB = new MysqliDb();
										$myDB->where("EmployeeID", $EmployeeID);
										$result = $myDB->get("education_details");

										$MysqliError = $myDB->getLastError();
										if (empty($MysqliError)) { ?>
											<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th>Education Level</th>
														<th>Education Name</th>
														<th>Specialization</th>
														<th>Board/Univercity</th>
														<th>College</th>
														<th>Type</th>
														<th>PassingYear</th>
														<th class="hidden">Division</th>
														<th class="hidden">Percentage</th>
														<th class="hidden">EduFile</th>
														<!-- <th style="width:100px;">Download </th>-->
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ($result as $key => $value) {
														echo '<tr>';
														echo '<td class="edu_level">' . $value['edu_level'] . '</td>';
														echo '<td class="edu_name">' . $value['edu_name'] . '</td>';
														echo '<td class="specialization">' . $value['specialization'] . '</td>';
														echo '<td class="board">' . $value['board'] . '</td>';
														echo '<td class="college">' . $value['college'] . '</td>';
														echo '<td class="edu_type">' . $value['edu_type'] . '</td>';
														echo '<td class="passing_year">' . $value['passing_year'] . '</td>';
														echo '<td class="division hidden">' . $value['division'] . '</td>';
														echo '<td class="percentage hidden">' . $value['percentage'] . '</td>';
														echo '<td class="edu_file hidden">' . $value['edu_file'] . '</td>';
														/*echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  onclick="javascript:return Download(this);"  title="View Data Item" data="'.$value['edu_file'].'" /></td>';*/

														echo '</tr>';
													}
													?>
												</tbody>
											</table><br />
										<?php
										} else {
											echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
										}
										?>

									</div>
								</div>
							</li>
							<li>
								<div class="collapsible-header topic"> Experience Details</div>
								<div class="collapsible-body">
									<div id="Experience_div" class="list-container">
										<?php

										$myDB = new MysqliDb();
										$myDB->where("EmployeeID", $EmployeeID);
										$result = $myDB->get("experince_details");

										$MysqliError = $myDB->getLastError();
										if (empty($MysqliError)) { ?>
											<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
												<thead>
													<tr>

														<th>Exp Type</th>
														<th>Organization</th>
														<th>Location</th>
														<th>From</th>
														<th>To</th>
														<th>Designation</th>
														<th>Last Drawn CTC(Monthly)</th>
														<!--<th>File</th>						            
							            <th style="width:100px;">Manage Doc </th>-->
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ($result as $key => $value) {
														echo '<tr>';
														if ($value['exp_type'] == 'Fresher') {
															echo '<td class="exp_type" colspan="8">' . $value['exp_type'] . '</td>';
														} else {
															echo '<td class="exp_type">' . $value['exp_type'] . '</td>';
															echo '<td class="employer">' . $value['employer'] . '</td>';
															echo '<td class="location">' . $value['location'] . '</td>';
															echo '<td class="from">' . $value['from'] . '</td>';
															echo '<td class="to">' . $value['to'] . '</td>';
															echo '<td class="designation">' . $value['designation'] . '</td>';
															echo '<td class="discription">' . $value['discription'] . '</td>';
															/*echo '<td class="file">'.$value['file'].'</td>';*/
														}

														/*echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png" onclick="javascript:return Download(this);"  title="View Data Item" data="'.$value['file'].'" /></td>';*/

														echo '</tr>';
													}
													?>
												</tbody>
											</table><br />
										<?php
										} else {
											echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
										}
										?>

									</div>
								</div>

							</li>
							<li>
								<div class="collapsible-header topic"> Employee Map</div>
								<div class="collapsible-body">
									<div id="empmap_div" class="list-container">
										<?php
										if (isset($_REQUEST['empid']) || $EmployeeID != '') {
											$getDetails = 'call get_empmap_forme("' . $EmployeeID . '")';
											$myDB = new MysqliDb();
											$result = $myDB->rawQuery($getDetails);
											$MysqliError = $myDB->getLastError();
											if (empty($MysqliError)) {

												foreach ($result as $key => $value) {
													echo '<table class="highlight bordered" cellspacing="0" width="100%">
								<tr>';
													echo "<td>Department </td><td><b>" . $value['dept'];
													echo '</b></td></tr><tr>';
													echo "<td>Client </td><td><b>" . $value['client_name'];
													echo '</b></td></tr><tr>';
													echo "<td>Process </td><td><b>" . $value['process'];
													echo '</b></td></tr><tr>';
													echo "<td>SubProcess </td><td><b>" . $value['sub_process'];
													echo '</b></td></tr><tr>';
													echo "<td>Designation </td><td><b>" . $value['Designation'];
													echo '</b></td></tr><tr>';
													echo "<td>Date Of Joining </td><td><b>" . $value['dateofjoin'];
													echo '</b></td></tr><tr>';
													echo "<td>Function </td><td><b>" . $value['function'];
													echo '</b></td></tr><tr>';
													if ($value['df_id'] != '74' && $value['df_id'] != '77' && $value['df_id'] != '146' && $value['df_id'] != '147' && $value['df_id'] != '148' && $value['df_id'] != '149') {
														echo "<td>Appraisal Month </td><td><b>" . $value['AppraisalMonth'];
														echo '</b></td></tr><tr>';
													}

													//echo "<td>Emp Level </td><td><b>".$value['emp_level'];
													echo '</b></td></tr></table><br />';
												}
											} else {
												echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
											}
										}
										?>
									</div>
								</div>

							</li>
							<li>
								<div class="collapsible-header topic"> Reporting Map</div>
								<div class="collapsible-body">
									<div id="reporting_div" class="list-container">
										<?php
										if (isset($_REQUEST['empid']) || $EmployeeID != '') {
											$getDetails = 'call get_reporting_map_byempid("' . $EmployeeID . '")';
											$myDB = new MysqliDb();
											$result_all = $myDB->rawQuery($getDetails);
											$MysqliError = $myDB->getLastError();
											if (empty($MysqliError)) {
												echo '<table class="responsive-table">';
												echo '<tr>';

												if (!empty($result_all[0]['ReportTo'])) {
													$myDB = new MysqliDb();
													$result_rt = $myDB->query('select img,EmployeeName,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "' . $result_all[0]['ReportTo'] . '" limit 1');

													if (count($result_rt) > 0 && $result_rt) {
														$contact_rt = $result_rt[0]['mobile'];
														$imsrc_rt = $result_rt[0]['img'];
														$rtname = $result_rt[0]['EmployeeName'];
													}
												}
												if (!empty($imsrc_rt)) {
													$imsrc_rt = URL . $locationdir . 'Images/' . $imsrc_rt;
												}
												echo "<td >Reporting </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_rt" name="imgToempl_rt" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $rtname;
												//$result_all[0]['ReportTo']

												if (!empty($contact_rt)) {
													echo '&nbsp;&nbsp;(&nbsp;' . $contact_rt . '&nbsp;)';
												}
												echo '</b></td></tr>';



												$srQ = 'select function_id from employee_map inner join df_master on df_master.df_id = employee_map.df_id where EmployeeID = "' . $EmployeeID . '"';
												$myDB = new MysqliDb();
												$funcitonID = $myDB->query($srQ);


												if ($funcitonID[0]['function_id'] == 7 || $funcitonID[0]['function_id'] == 8 || $funcitonID[0]['function_id'] == 10) {

													echo '<tr>'; //status_table.Qa_ops

													if (!empty($result_all[0]['Qa_ops'])) {

														$myDB = new MysqliDb();
														$result_qa = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "' . $result_all[0]['Qa_ops'] . '" limit 1');
														if (count($result_qa) > 0 && $result_qa) {
															$contact_qa = $result_qa[0]['mobile'];
															$imsrc_qa = $result_qa[0]['img'];
														}
													}
													if (!empty($imsrc_qa)) {
														$imsrc_qa = URL . $locationdir . 'Images/' . $imsrc_qa;
													}
													echo "<td >Quality Analyst </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_qa" name="imgToempl_qa" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $result_all[0]['QA_OPS'];
													if (!empty($contact_qa)) {
														echo '&nbsp;&nbsp;(&nbsp;' . $contact_qa . '&nbsp;)';
													}

													echo '</b></td></tr>';

													echo '<tr>';
													if (!empty($result_all[0]['oh'])) {
														$myDB = new MysqliDb();
														$result_oh = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "' . $result_all[0]['oh'] . '" limit 1');
														if (count($result_oh) > 0 && $result_oh) {
															$contact_oh = $result_oh[0]['mobile'];
															$imsrc_oh = $result_oh[0]['img'];
														}
													}
													if (!empty($imsrc_oh)) {
														$imsrc_oh = URL . $locationdir . 'Images/' . $imsrc_oh;
													}
													echo "<td>Operation Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_oh" name="imgToempl_oh" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $result_all[0]['OH'];
													if (!empty($contact_oh)) {
														echo '&nbsp;&nbsp;(&nbsp;' . $contact_oh . '&nbsp;)';
													}

													echo '</b></td></tr>';


													echo '<tr>';
													if (!empty($result_all[0]['qh'])) {
														$myDB = new MysqliDb();
														$result_qh = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "' . $result_all[0]['qh'] . '" limit 1');
														if (count($result_qh) > 0 && $result_qh) {
															$contact_qh = $result_qh[0]['mobile'];
															$imsrc_qh = $result_qh[0]['img'];
														}
													}
													if (!empty($imsrc_qh)) {
														$imsrc_qh = URL . $locationdir . 'Images/' . $imsrc_qh;
													}
													echo "<td>Quality Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_qh" name="imgToempl_qh" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $result_all[0]['QH'];
													if (!empty($contact_qh)) {
														echo '&nbsp;&nbsp;(&nbsp;' . $contact_qh . '&nbsp;)';
													}

													echo '</b></td></tr>';



													echo '<tr>';
													if (!empty($result_all[0]['th'])) {
														$myDB = new MysqliDb();
														$result_th = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "' . $result_all[0]['th'] . '" limit 1');
														if (count($result_th) > 0 && $result_th) {
															$contact_th = $result_th[0]['mobile'];
															$imsrc_th = $result_th[0]['img'];
														}
													}
													if (!empty($imsrc_th)) {
														$imsrc_th = URL . $locationdir . 'Images/' . $imsrc_th;
													}
													echo "<td>Training Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_th" name="imgToempl_th" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $result_all[0]['TH'];
													if (!empty($contact_th)) {
														echo '&nbsp;&nbsp;(&nbsp;' . $contact_th . '&nbsp;)';
													}

													echo '</b></td></tr>';
												}

												echo '</b></td></tr>';

												if (!empty($result_all[0]['account_head'])) {
													$myDB = new MysqliDb();
													$result_ah = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "' . $result_all[0]['account_head'] . '" limit 1');
													if (count($result_ah) > 0 && $result_ah) {
														$contact_ah = $result_ah[0]['mobile'];
														$imsrc_ah = $result_ah[0]['img'];
													}
												}
												if (!empty($imsrc_ah)) {
													$imsrc_ah = URL . $locationdir . 'Images/' . $imsrc_ah;
												}
												echo "<td >Account Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_ah" name="imgToempl_ah" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $result_all[0]['AH'];
												if (!empty($contact_ah)) {
													echo '&nbsp;&nbsp;(&nbsp;' . $contact_ah . '&nbsp;)';
												}
												echo '</b></td></tr>';

												if ($result_all[0]['process_head'] != NULL) {
													if (!empty($result_all[0]['process_head'])) {
														$myDB = new MysqliDb();
														$result_vh = $myDB->query('select img,EmployeeName,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "' . $result_all[0]['process_head'] . '" limit 1');


														if (count($result_vh) > 0 && $result_vh) {
															$contact_ph = $result_vh[0]['mobile'];
															$imsrc_ph = $result_vh[0]['img'];
															$phname = $result_vh[0]['EmployeeName'];
														}
													}

													if (!empty($imsrc_ph)) {
														$imsrc_ph = URL . $locationdir . 'Images/' . $imsrc_ph;
													}
													echo "<td >Process Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_ph" name="imgToempl_ph" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $phname;


													if (!empty($contact_ph)) {
														echo '&nbsp;&nbsp;(&nbsp;' . $contact_ph . '&nbsp;)';
													}
													echo '</b></td></tr><tr>';

													echo '</b></td></tr>';
												}

												echo '</b></td></tr>';
												if ($result_all[0]['vh'] != NULL) {
													if (!empty($result_all[0]['vh'])) {
														$myDB = new MysqliDb();
														$result_vh = $myDB->query('select img,EmployeeName,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "' . $result_all[0]['vh'] . '" limit 1');


														if (count($result_vh) > 0 && $result_vh) {
															$contact_vh = $result_vh[0]['mobile'];
															$imsrc_vh = $result_vh[0]['img'];
															$vhname = $result_vh[0]['EmployeeName'];
														}
													}

													if (!empty($imsrc_vh)) {
														$imsrc_vh = URL . $locationdir . 'Images/' . $imsrc_vh;
													}
													echo "<td >Vertical Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_vh" name="imgToempl_vh" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $vhname;


													if (!empty($contact_vh)) {
														echo '&nbsp;&nbsp;(&nbsp;' . $contact_vh . '&nbsp;)';
													}
													echo '</b></td></tr><tr>';

													echo '</b></td></tr></table><br/>';
												}
												echo '</table><br/>';
											} else {
												echo "<script>$(function(){ toastr.error('No Record exist.'); }); </script>";
											}
										}
										?>
									</div>
								</div>

							</li>

							<!--Management Map-->
							<li>
								<div class="collapsible-header topic"> Management Contact</div>
								<div class="collapsible-body">
									<div id="management_div" class="list-container">
										<?php
										if (isset($_REQUEST['empid']) || $EmployeeID != '') {

											$getGenderEmp = "select Gender from personal_details where EmployeeID='" . $EmployeeID . "'";
											$myDB = new MysqliDb();
											$resultgetGenderEmp = $myDB->query($getGenderEmp);
											$getDetails2 = "select EmployeeName,designation,EmployeeID,mobile,img from emp_dt_map where EmployeeID in ('CE03070003','CE07147134','CE05101779')";
											$myDB = new MysqliDb();
											$result_all = $myDB->rawQuery($getDetails2);

											$site_spoc = "Select c.SiteSpoc, c.cm_id, e.EmployeeName, e.mobile, e.designation,e.img from new_client_master c join emp_dt_map e on c.SiteSpoc=e.EmployeeID where c.cm_id='" . $_SESSION['__cm_id'] . "'";
											$myDB = new MysqliDb();
											$SiteSpoc = $myDB->query($site_spoc);

											if (isset($result_all[0]['EmployeeName'])) {
												$a = $result_all[0]['EmployeeName'];
												$aimg = $result_all[0]['img'];
											}
											if (isset($result_all[0]['mobile'])) {
												$b = $result_all[0]['mobile'];
											}
											if (isset($result_all[1]['EmployeeName'])) {
												$c = $result_all[1]['EmployeeName'];
												$cimg = $result_all[1]['img'];
											}
											if (isset($result_all[1]['mobile'])) {
												$d = $result_all[1]['mobile'];
											}
											if (isset($result_all[2]['EmployeeName'])) {
												$e = $result_all[2]['EmployeeName'];
												$eimg = $result_all[2]['img'];
											}
											if (isset($result_all[2]['mobile'])) {
												$f = $result_all[2]['mobile'];
											}
											if (isset($result_all[3]['EmployeeName'])) {
												$g = $result_all[3]['EmployeeName'];
											}
											if (isset($result_all[3]['mobile'])) {
												$h = $result_all[3]['mobile'];
											}
											if (isset($result_all[0]['designation'])) {
												$n1 = $result_all[0]['designation'];
											}
											if (isset($result_all[1]['designation'])) {
												$n2 = $result_all[1]['designation'];
											}
											if (isset($result_all[2]['designation'])) {
												$n3 = $result_all[2]['designation'];
											}
											if (isset($result_all[3]['designation'])) {
												$n4 = $result_all[3]['designation'];
											}
											if (isset($resultgetGenderEmp[0]['Gender'])) {
												$gender = $resultgetGenderEmp[0]['Gender'];
											}

											if (isset($SiteSpoc[0]['SiteSpoc'])) {
												$sitespoc = $SiteSpoc[0]['mobile'];
												$s = $SiteSpoc[0]['EmployeeName'];
												$sitespocimg = $SiteSpoc[0]['img'];
												//$desig = $SiteSpoc[0]['designation'];
											}
											if (!empty($sitespocimg)) {
												$sitespocimg = URL . $locationdir . 'Images/' . $sitespocimg;
											}

											$MysqliError = $myDB->getLastError();
											if (empty($MysqliError) && strtoupper($gender) == 'FEMALE') {
												echo '<table class="responsive-table">';
												echo '<tr>';
												echo "<td >" . $n1 . "</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="designation_id" name="designation_id" class="emp_image"  src="../Images/' . $aimg . '"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $a;
												if (!empty($b)) {
													echo '&nbsp;&nbsp;(&nbsp;' . $b . '&nbsp;)';
												}
												echo '</b></td></tr>';
												if ($funcitonID[0]['function_id'] == 7 || $funcitonID[0]['function_id'] == 8 || $funcitonID[0]['function_id'] == 10) {
													echo '<tr>';
													echo '</b></td></tr>';
												}
												echo "<td >" . $n2 . "</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="designation2_id" name="designation2_id" class="emp_image" src="../Images/' . $cimg . '"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $c;
												if (!empty($d)) {
													echo '&nbsp;&nbsp;(&nbsp;' . $d . '&nbsp;)';
												}
												echo '</b></td></tr>';
												echo "<td >" . $n3 . "</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="designation2_id" name="designation2_id" class="emp_image" src="../Images/' . $eimg . '"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $e;
												if (!empty($f)) {
													echo '&nbsp;&nbsp;(&nbsp;' . $f . '&nbsp;)';
												}
												echo '</b></td></tr>';

												if (!empty($sitespoc)) {
													echo "<td >Site Spoc</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="sitespoc_id" name="sitespoc_id" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $s;
													echo '&nbsp;&nbsp;(&nbsp;' . $sitespoc . '&nbsp;)';
													echo '</b></td></tr>';
												}

												echo '</b></tr></table><br />';
											} else if (empty($MysqliError) && strtoupper($gender) != 'FEMALE') {
												echo '<table class="responsive-table">';
												echo '<tr>';
												echo "<td >" . $n1 . "</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="designation_id" name="designation_id" class="emp_image" src="../Images/' . $aimg . '"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $a;
												if (!empty($b)) {
													echo '&nbsp;&nbsp;(&nbsp;' . $b . '&nbsp;)';
												}
												echo '</b></td></tr>';
												if ($funcitonID[0]['function_id'] == 7 || $funcitonID[0]['function_id'] == 8 || $funcitonID[0]['function_id'] == 10) {
													echo '<tr>';
													echo '</b></td></tr>';
												}
												echo "<td >" . $n3 . "</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="designation2_id" name="designation2_id" class="emp_image" src="../Images/' . $eimg . '"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $e;
												if (!empty($f)) {
													echo '&nbsp;&nbsp;(&nbsp;' . $f . '&nbsp;)';
												}
												echo '</b></td></tr>';

												if (!empty($sitespoc)) {
													echo "<td >Site Spoc</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="sitespoc_id" name="sitespoc_id" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $s;
													echo '&nbsp;&nbsp;(&nbsp;' . $sitespoc . '&nbsp;)';
													echo '</b></td></tr>';
												}
												echo '</table><br />';
											} else {
												echo "<script>$(function(){ toastr.error('No Record exist.'); }); </script>";
											}
										}
										?>
									</div>
								</div>
							</li>

							<!--Management Map last-->

							<!--ER Map-->
							<!--<li>
		      <div class="collapsible-header topic"> ER Contact</div>
		      <div class="collapsible-body">
		      <div id="reporting_div" class="list-container">
					<?php
					if (isset($_REQUEST['empid']) || $EmployeeID != '') {
						$getDetails = 'select t1.er_scop,t2.EmployeeName from new_client_master t1 join whole_details_peremp t2 on t1.er_scop=t2.employeeid where t1.cm_id="' . $_SESSION["__cm_id"] . '"';
						$myDB = new MysqliDb();
						$result_all = $myDB->rawQuery($getDetails);
						$MysqliError = $myDB->getLastError();
						if (empty($MysqliError)) {
							echo '<table class="responsive-table">';
							echo '<tr>';
							if (!empty($result_all[0]['er_scop'])) {
								$myDB = new MysqliDb();
								$result_ah = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "' . $result_all[0]['er_scop'] . '" limit 1');
								if (count($result_ah) > 0 && $result_ah) {
									$contact_er = $result_ah[0]['mobile'];
									$imsrc_er = $result_ah[0]['img'];
								}
							}
							if (!empty($imsrc_er)) {
								$imsrc_er = URL . $locationdir . 'Images/' . $imsrc_er;
							}
							echo "<td >ER Spoc </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_er" name="imgToempl_er" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $result_all[0]['EmployeeName'];
							if (!empty($contact_er)) {
								echo '&nbsp;&nbsp;(&nbsp;' . $contact_er . '&nbsp;)';
							}
							echo '</b></td></tr>';



							echo '</table><br/>';
						} else {
							echo "<script>$(function(){ toastr.error('No Record exist.'); }); </script>";
						}
					}
					?>
				</div>
		      </div>
		    </li>-->
							<!--ER Map list-->

							<!--Process Map-->
							<li>
								<div class="collapsible-header topic"> Process Map</div>
								<div class="collapsible-body">
									<div id="process_div" class="list-container">
										<?php
										if (isset($_REQUEST['empid']) || $EmployeeID != '') {
											$rptToEmp = "select ReportTo from status_table   where employeeid='" . $EmployeeID . "'";
											$myDB = new MysqliDb();
											$resultrptToEmp = $myDB->query($rptToEmp);
											$MysqliError = $myDB->getLastError();
											if (isset($resultrptToEmp[0]['ReportTo'])) {
												$rid = $resultrptToEmp[0]['ReportTo'];
											}
											$rptTortid = "select ReportTo from status_table   where employeeid='" . $rid . "'";
											$myDB = new MysqliDb();
											$resultrptTortid = $myDB->query($rptTortid);
											$MysqliError1 = $myDB->getLastError();
											if (isset($resultrptTortid[0]['ReportTo'])) {
												$rrid = $resultrptTortid[0]['ReportTo'];
											}
											$rrrptTortid = "select ReportTo from status_table   where employeeid='" . $rrid . "'";
											$myDB = new MysqliDb();
											$resultrrrto = $myDB->query($rrrptTortid);
											$MysqliError2 = $myDB->getLastError();
											if (isset($resultrrrto[0]['ReportTo'])) {
												$rrrid = $resultrrrto[0]['ReportTo'];
											}
											if (empty($MysqliError)) {
												echo '<table class="responsive-table">';


												if (!empty($rid)) {
													echo '<tr>';
													$myDB = new MysqliDb();
													$result_rt = $myDB->query('select personal_details.img,emp_dt_map.designation,personal_details.EmployeeName,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID inner join emp_dt_map on personal_details.EmployeeID=emp_dt_map.EmployeeID where personal_details.EmployeeID = "' . $rid . '" limit 1');
													if (count($result_rt) > 0 && $result_rt) {
														$contact_rt = $result_rt[0]['mobile'];
														$imsrc_rt1 = $result_rt[0]['img'];
														$rtname = $result_rt[0]['EmployeeName'];
														$rdesignation = $result_rt[0]['designation'];
													}


													if (!empty($imsrc_rt1)) {
														$imsrc_rt1 = URL . $locationdir . 'Images/' . $imsrc_rt1;
													}
													echo "<td >$rdesignation</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_r" name="imgToempl_r" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $rtname;
													if (!empty($contact_rt)) {
														echo '&nbsp;&nbsp;(&nbsp;' . $contact_rt . '&nbsp;)';
													}
													echo '</b></td></tr>';
												}

												if (!empty($rrid) && $rid != $rrid && empty($MysqliError1)) {
													echo '<tr>';
													$myDB = new MysqliDb();
													$result_rrt = $myDB->query('select personal_details.img,emp_dt_map.designation,personal_details.EmployeeName,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID inner join emp_dt_map on personal_details.EmployeeID=emp_dt_map.EmployeeID where personal_details.EmployeeID = "' . $rrid . '" limit 1');
													if (count($result_rrt) > 0 && $result_rrt) {
														$contact_rrt = $result_rrt[0]['mobile'];
														$imsrc_rrt = $result_rrt[0]['img'];
														$rrtname = $result_rrt[0]['EmployeeName'];
														$rrdesignation = $result_rrt[0]['designation'];
													}


													if (!empty($imsrc_rrt)) {
														$imsrc_rrt = URL . $locationdir . 'Images/' . $imsrc_rrt;
													}
													echo "<td>$rrdesignation</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_rr" name="imgToempl_rr" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $rrtname;
													if (!empty($contact_rrt)) {
														echo '&nbsp;&nbsp;(&nbsp;' . $contact_rrt . '&nbsp;)';
													}
													echo '</b></td></tr>';
												}

												if (!empty($rrrid) && $rrrid != $rrid && $rrrid != $rid  && empty($MysqliError2)) {
													echo '<tr>';
													$myDB = new MysqliDb();
													$result_rrrt = $myDB->query('select personal_details.img,personal_details.EmployeeName,emp_dt_map.designation,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID inner join emp_dt_map on personal_details.EmployeeID=emp_dt_map.EmployeeID where personal_details.EmployeeID = "' . $rrrid . '" limit 1');
													if (count($result_rrrt) > 0 && $result_rrrt) {
														$contact_rrrt = $result_rrrt[0]['mobile'];
														$imsrc_rrrt = $result_rrrt[0]['img'];
														$rrrtname = $result_rrrt[0]['EmployeeName'];
														$rrrdesignation = $result_rrrt[0]['designation'];
													}


													if (!empty($imsrc_rrrt)) {
														$imsrc_rrrt = URL . $locationdir . 'Images/' . $imsrc_rrrt;
													}
													echo "<td >$rrrdesignation</td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>" . '<img  id="imgToempl_rrr" name="imgToempl_rrr" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>' . "&nbsp;&nbsp;<b>" . $rrrtname;
													if (!empty($contact_rrrt)) {
														echo '&nbsp;&nbsp;(&nbsp;' . $contact_rrrt . '&nbsp;)';
													}
													echo '</b></td></tr>';
												}

												echo '</table>';
											} else {
												echo "<script>$(function(){ toastr.error('No Record exist.'); }); </script>";
											}
										}
										?>
									</div>
								</div>
							</li>
							<!--Process Map end-->
							<li>
								<div class="collapsible-header topic">Warning Docs</div>
								<div class="collapsible-body">
									<div id="Education_div" class="list-container">
										<?php
										$myDB = new MysqliDb();
										//$myDB->where("EmployeeID",$EmployeeID);

										$sqlConnect = "SELECT i.ah_status,i.ah_subtype,i.ah_Datetime,d.Title,d.Document FROM warning_rth i inner join warning_rth_documents d on i.EmployeeID=d.EmployeeID and i.id=d.DataId where i.EmployeeID='" . $EmployeeID . "' group by d.DataId;";
										$result = $myDB->query($sqlConnect);
										$MysqliError = $myDB->getLastError();
										if (empty($MysqliError)) { ?>
											<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th colspan=2>Warning and Refer to HR Letter</th>
														<th>Issued</th>
														<th>Document</th>
														<th>Action</th>
													</tr>
													<tr>
														<th>Type</th>
														<th>Sub Type</th>
														<th>Date</th>
														<th>Name</th>
														<th>Download </th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ($result as $key => $value) {
														echo '<tr>';
														echo '<td class="ah_status">' . $value['ah_status'] . '</td>';
														echo '<td class="ah_subtype">' . $value['ah_subtype'] . '</td>';
														echo '<td class="ah_Datetime">' . $value['ah_Datetime'] . '</td>';
														echo '<td class="Title">' . $value['Title'] . '</td>';
														if ($_SESSION['__user_logid'] == 'CE03070003') {
															echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  
								onclick="javascript:return Download2(this);"  title="Download File" data="' . $value['Document'] . '" /></td>';
														} else {
															echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  
								title="Download File" data="' . $value['Document'] . '" /></td>';
														}
														echo '</tr>';
													}
													?>
												</tbody>
											</table><br />
										<?php
										} else {
											echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
										}
										?>

									</div>
								</div>
							</li>

							<li>
								<div class="collapsible-header topic"> Bank,PF & ESIC Details</div>
								<div class="collapsible-body">
									<div id="empmap_div" class="list-container">
										<?php
										if (isset($_REQUEST['empid']) || $EmployeeID != '') {
											$getDetails = 'call get_salarydetails("' . $EmployeeID . '")';
											$myDB = new MysqliDb();
											$result = $myDB->rawQuery($getDetails);
											$MysqliError = $myDB->getLastError();
											if (empty($MysqliError)) {

												foreach ($result as $key => $value) {
													echo '<table class="highlight bordered" cellspacing="0" width="100%">
								<tr>';
													/*echo "<td>CTC </td><td><b>".$value['ctc'];echo '</b></td></tr><tr>';
								echo "<td>Take Home </td><td><b>".$value['net_takehome'];echo '</b></td></tr><tr>';
								echo "<td>Provident Fund </td><td><b>".$value['pf'];echo '</b></td></tr><tr>';
								echo "<td>ESIC </td><td><b>".$value['esis'];echo '</b></td></tr><tr>';
								echo "<td>PF Number </td><td><b>".$value['pf_account'];echo '</b></td></tr><tr>';*/
													if ((float)$value['ctc1'] < 21050) {
														echo "<td>ESIC Number </td><td><b>" . $value['esi_no'];
														echo '</b></td></tr><tr>';
													}
													if (strtoupper($value['pf_status']) == 'YES') {
														echo "<td>UAN Number </td><td><b>" . $value['uan_no'];
														echo '</b></td></tr><tr>';
													}
													echo "<td>Bank Name </td><td><b>" . $value['BankName'];
													echo '</b></td></tr><tr>';
													echo "<td>Bank Account Number </td><td><b>" . $value['AccountNo'];
													echo '</b></td></tr><tr>';
													echo "<td>Name as per Bank </td><td><b>" . $value['Name'];
													echo '</b></td></tr><tr>';
													echo "<td>IFSC </td><td><b>" . $value['IFSC'];
													echo '</b></td></tr><tr>';
													//echo "<td>Emp Level </td><td><b>".$value['emp_level'];
													echo '</b></td></tr></table><br />';
												}
											} else {
												echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
											}
										}
										?>
									</div>
								</div>

							</li>


						</ul>

					</div>
				</div>

				<input type="hidden" name="imgname" id="imgname" value="<?php echo $imsrc; ?>" />
				<input type="hidden" name="empnameinput" id="empnameinput" value="<?php echo $employeeName; ?>" />
			</div>
		</div>
	</div>
</div>
<script>
	$(function() {

		var employeeName = <?php echo '"' . $employeeName . '"'; ?>;
		$('#empname_h4').text(employeeName);
		var imgsrc = <?php echo '"' . $imsrc . '"'; ?>;

		if (imgsrc == "") {
			$('#imgToempl').attr('src', '../Style/images/agent-icon.png');
		} else {
			$('#imgToempl').attr('src', imgsrc);
		}


		$("#imgToempl").error(function() {
			$(this).attr('src', '../Style/images/agent-icon.png');
		});


		var imgsrc_ah = <?php echo '"' . $imsrc_ah . '"'; ?>;

		if (imgsrc_ah == "") {
			$('#imgToempl_ah').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_ah').attr('src', imgsrc_ah);
		}

		var imgsrc_er = <?php echo '"' . $imsrc_er . '"'; ?>;

		if (imgsrc_er == "") {
			$('#imgToempl_er').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_er').attr('src', imgsrc_er);
		}

		$("#imgToempl_ah").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});


		var imgsrc_oh = <?php echo '"' . $imsrc_oh . '"'; ?>;

		if (imgsrc_oh == "") {
			$('#imgToempl_oh').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_oh').attr('src', imgsrc_oh);
		}


		$("#imgToempl_oh").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});


		var imgsrc_qh = <?php echo '"' . $imsrc_qh . '"'; ?>;

		if (imgsrc_qh == "") {
			$('#imgToempl_qh').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_qh').attr('src', imgsrc_qh);
		}


		$("#imgToempl_qh").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});

		var imgsrc_th = <?php echo '"' . $imsrc_th . '"'; ?>;

		if (imgsrc_th == "") {
			$('#imgToempl_th').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_th').attr('src', imgsrc_th);
		}


		$("#imgToempl_th").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});

		var imgsrc_qa = <?php echo '"' . $imsrc_qa . '"'; ?>;

		if (imgsrc_qa == "") {
			$('#imgToempl_qa').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_qa').attr('src', imgsrc_qa);
		}


		$("#imgToempl_qa").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});

		var imsrc_rt = <?php echo '"' . $imsrc_rt . '"'; ?>;

		if (imsrc_rt == "") {
			$('#imgToempl_rt').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_rt').attr('src', imsrc_rt);
		}

		var imsrc_site = <?php echo '"' . $sitespocimg . '"'; ?>;

		if (imsrc_site == "") {
			$('#sitespoc_id').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#sitespoc_id').attr('src', imsrc_site);
		}


		$("#imgToempl_rt").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});
		var imsrc_vh = <?php echo '"' . $imsrc_vh . '"'; ?>;
		if (imsrc_vh == "") {
			$('#imgToempl_vh').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_vh').attr('src', imsrc_vh);
		}
		$("#imgToempl_vh").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});

		var imsrc_ph = <?php echo '"' . $imsrc_ph . '"'; ?>;
		if (imsrc_ph == "") {
			$('#imgToempl_ph').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_ph').attr('src', imsrc_ph);
		}
		$("#imgToempl_ph").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});

		var imsrc_rt1 = <?php echo '"' . $imsrc_rt1 . '"'; ?>;
		if (imsrc_rt1 == "") {
			$('#imgToempl_r').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_r').attr('src', imsrc_rt1);
		}
		$("#imgToempl_r").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});
		var imsrc_rrt = <?php echo '"' . $imsrc_rrt . '"'; ?>;
		if (imsrc_rrt == "") {
			$('#imgToempl_rr').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_rr').attr('src', imsrc_rrt);
		}
		$("#imgToempl_rr").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});


		var imsrc_rrrt = <?php echo '"' . $imsrc_rrrt . '"'; ?>;

		if (imsrc_rrrt == "") {
			$('#imgToempl_rrr').attr('src', '../Style/images/User-icon.png');
		} else {
			$('#imgToempl_rrr').attr('src', imsrc_rrrt);
		}
		$("#imgToempl_rrr").error(function() {
			$(this).attr('src', '../Style/images/User-icon.png');
		});

	});

	function Download(el) {
		if ($(el).attr('data') != '') {
			window.open("../OtDocs/" + $(el).attr("data"));
		} else {
			alert('No File Exist');
		}
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>