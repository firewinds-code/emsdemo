<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
//Interview DB main Config / class file     $myDB=new MysqliDb($db_int_config_i);
// require_once(__dir__ . '/../Config/DBConfig_interview_array.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$alert_msg = '';
$imsrc = URL . 'Style/images/agent-icon.png';
$EmployeeID = $btnShow = '';
$emptype = $paytype = $newctc = $pfaccount = $esiNo = $file_esi = $MinWages = $basic = $hra = $convence = $sp_allow = $gorss_sal = $pf_count = $pf_empl_count = $esis_count = $esis_empl_count = $professional_tex = $takehome = $pli = $pli_percent = $pli_ammount = $nt_takehome = $pli_status = $pf_status = $pli_mode = $uino = $pli_effected = $pli = $bonus = $gratuity = $medical_allowance = $sal_type = '';
$professional_tex = 0;
$rt_type = 1;
$clientid = 0;
$cm_id = "";
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	$emptype = cleanUserInput($_POST['txt_sal_emptype']);
	$paytype = cleanUserInput($_POST['txt_sal_paytype']);
	$newctc = cleanUserInput($_POST['txt_sal_ctc']);
	$pfaccount = cleanUserInput($_POST['txt_sal_pf']);
	$esiNo = cleanUserInput($_POST['txt_sal_esi']);
	$file_esi = cleanUserInput($_POST['file_esi']);

	$MinWages = cleanUserInput($_POST['txt_MinWages']);
	$basic = cleanUserInput($_POST['txt_basic']);
	$hra = cleanUserInput($_POST['txt_hra']);
	$convence = cleanUserInput($_POST['txt_convence']);
	$sp_allow = cleanUserInput($_POST['txt_sp_allow']);
	$gorss_sal = cleanUserInput($_POST['txt_gross_sal']);
	$pf_count =  cleanUserInput($_POST['txt_pf_count']);
	$pf_empl_count =  cleanUserInput($_POST['txt_pf_empl_count']);
	$esis_count =  cleanUserInput($_POST['txt_esis_count']);
	$esis_empl_count =  cleanUserInput($_POST['txt_esis_empl_count']);
	$professional_tex =  cleanUserInput($_POST['txt_professional_tex']);
	$takehome =  cleanUserInput($_POST['txt_takehome']);
	$sal_type = cleanUserInput($_POST['txt_sal_type']);
	//$ppf = cleanUserInput($_POST['txt_ppf']);
	$ppf = filter_var($_POST['txt_ppf'], FILTER_SANITIZE_NUMBER_INT);
	if ($ppf == '')
		$ppf = 0;
	$pli_percent =  cleanUserInput($_POST['txt_pli_percent']);
	$pli_ammount = cleanUserInput($_POST['txt_pli_ammount']);
	$nt_takehome = cleanUserInput($_POST['txt_nt_takehome']);
	$rt_type = cleanUserInput($_POST['rt_type']);
	$pli_effected = cleanUserInput($_POST['txt_pli_effected']);
	$pli_status =  cleanUserInput($_POST['txt_pli_deduct']);
	$pf_status = cleanUserInput($_POST['txt_pf_deduct']);
	$pli_mode =  cleanUserInput($_POST['txt_pli_mode']);
	$uino =  cleanUserInput($_POST['txt_uino']);
	//$gratuity =  cleanUserInput($_POST['txt_gratuity']);
	$gratuity = filter_var($_POST['txt_gratuity'], FILTER_SANITIZE_NUMBER_INT);
	if ($gratuity == '')
		$gratuity = 0;
	//$medical_allowance =  cleanUserInput($_POST['txt_medical_allow']);
	$medical_allowance = filter_var($_POST['txt_medical_allow'], FILTER_SANITIZE_NUMBER_INT);
	if ($medical_allowance == '')
		$medical_allowance = 0;
	//$bonus =  cleanUserInput($_POST['txt_bonus']);
	$bonus = filter_var($_POST['txt_bonus'], FILTER_SANITIZE_NUMBER_INT);
	if ($bonus == '')
		$bonus = 0;
	// $sal_type = $_POST['sal_type'];
}


//Check Employee is exist or not

if (isset($_REQUEST['empid']) && $EmployeeID == '' && !isset($_POST['EmployeeID'])) {
	$EmployeeID = clean($_REQUEST['empid']);
	$getDetails = 'select * from salary_details where EmployeeID = ? limit 1';
	$selectQury = $conn->prepare($getDetails);
	$selectQury->bind_param("s", $EmployeeID);
	$selectQury->execute();
	$result_all = $selectQury->get_result();

	// $myDB = new MysqliDb();
	// $result_all = $myDB->rawQuery($getDetails);
	// $mysql_error = $myDB->getLastError();
	if (!empty($result_all)) {
		foreach ($result_all as $key => $value) {
			$emptype = $value['emptype'];
			$paytype = $value['payrolltype'];
			$newctc = $value['ctc'];
			$pfaccount = $value['pf_account'];
			$esiNo = $value['esi_no'];
			$file_esi = $value['esi_file'];

			$MinWages = $value['min_wages'];
			$basic = $value['basic'];
			$hra = $value['hra'];
			$convence = $value['convence'];
			$sp_allow = $value['sp_allow'];
			$gorss_sal = $value['gross_sal'];
			$pf_count = $value['pf'];
			$pf_empl_count = $value['pf_employer'];
			$esis_count = $value['esis'];
			$esis_empl_count = $value['esi_employer'];
			$professional_tex = $value['professional_tex'];
			$takehome = $value['takehome'];
			$ppf = $value['ppf'];
			//$pli =$value['pli'];
			$pli_percent = $value['pli_percent'];
			$pli_ammount = $value['pli_ammount'];
			$pli_effected = $value['pli_effected'];
			$nt_takehome = $value['net_takehome'];

			$pli_status = $value['pli_status'];
			$pf_status = $value['pf_status'];
			$pli_mode = $value['pli_mode'];
			$uino =  $value['uan_no'];
			$rt_type = $value['rt_type'];
			$gratuity = $value['gratuity'];
			$medical_allowance = $value['medical_allowance'];
			$bonus = $value['bonus'];
			$sal_type = $value['sal_type'];
		}
	}

	$professional_tex = 0;
	$getDetails = 'call get_personal("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);

	if ($result_all) {
		if ($result_all[0]['location'] == "1" || $result_all[0]['location'] == "3" || $result_all[0]['location'] == "4") {
			$location = URL . 'View/salemp?empid=' . $EmployeeID;
			echo "<script>location.href='" . $location . "'</script>";
			exit();
		} else if ($result_all[0]['location'] == "5" || $result_all[0]['location'] == "2" || $result_all[0]['location'] == "8") {
			$location = URL . 'View/SalEmpV?empid=' . $EmployeeID;
			echo "<script>location.href='" . $location . "'</script>";
			exit();
		} else if ($result_all[0]['location'] == "7" || $result_all[0]['location'] == "9") {
			$location = URL . 'View/SalEmpSU?empid=' . $EmployeeID;
			echo "<script>location.href='" . $location . "'</script>";
			exit();
		}
	} else {
		echo "<script>$(function(){ toastr.error('Wrong Employee To Search') });window.location='" . URL . "'</script>";
	}
} elseif (isset($_POST['EmployeeID']) && $_POST['EmployeeID'] != '') {
	$EmployeeID = $_POST['EmployeeID'];
}

if (isset($_POST['btn_sal_Save']) && $EmployeeID != '') {
	$myDB = new MysqliDb();
	$createBy = clean($_SESSION['__user_logid']);
	$validate = 0;
	$min_value = $max_value = 0;
	if ($emptype == '' || $paytype  == '' || $newctc  == '' || $MinWages  == '' || $basic   == '' || $hra   == '' || $convence   == '' || $sp_allow   == '' || $gorss_sal  == '' || $pf_count  == '' || $pf_empl_count  == '' || $esis_count  == '' || $esis_empl_count   == '' || $professional_tex  == '' || $takehome  == '' || $pli_percent  == '' || $pli_ammount  == '' || $nt_takehome  == '' || $pli_status  == '' || $pf_status  == '' || $pli_mode  == '' || $rt_type == '' || $gratuity == '' || $medical_allowance == '' || $bonus == '' || $sal_type == '') {
		$validate = 1;
	}
	// echo $pli_ammount;
	// echo $validate;
	// die;

	if (substr($EmployeeID, 0, 2) != 'TE') {
		// $myDB = new MysqliDb();
		$sql = 'select tbl_salary_slab_by_cps.* from employee_map inner join tbl_salary_slab_by_cps on tbl_salary_slab_by_cps.cm_id = employee_map.cm_id where EmployeeID = ? and df_id in (74,77,146,147,148,149) limit 1';
		$selectQury = $conn->prepare($sql);
		$selectQury->bind_param("s", $EmployeeID);
		$selectQury->execute();
		$res = $selectQury->get_result();
		$rsp = $res->fetch_row();
		//if (count($rsp) > 0 && $rsp) {
		if ($res->num_rows > 0 && $res) {
			$min_value = $rsp[4];
			$max_value = $rsp[5];
			$avgSalary = $rsp[6];
			// $min_value = $rsp[0]['min_lim'];
			// $max_value = $rsp[0]['max_lim'];
			// $avgSalary = $rsp[0]['avg_sal'];
			$sum = 1;
			if ($newctc < $min_value || $newctc > $max_value) {
				$validate = 1;
			} else {

				/*$myDB = new MysqliDb();
				$rsp1 = $myDB->query('select sum(ctc) as CTC,count(*) emp from salary_details inner join employee_map on salary_details.EmployeeID = employee_map.EmployeeID where cm_id = '.$rsp[0]['cm_id'].' and emp_status  = "Active" and salary_details.EmployeeID != "'.$EmployeeID.'" and dateofjoin >= "2017-02-21"');
				if(!empty($rsp1[0][0]['CTC']))
				{
					$sum = $newctc + $rsp1[0][0]['CTC'];	
					$countEmp = 1 + $rsp1[0][0]['emp'];
					
					$avgSal  = round($sum/$countEmp,2);
					
					if($avgSal > $rsp[0]['avg_sal'])	
					{
						$validate = 1;
					}
				}*/
			}
		}



		if ($validate == 0) {
			if (isset($_FILES["FileUpload1"]) && !empty($_FILES["FileUpload1"]["name"])) {
				$target_dir = ROOT_PATH . 'ESIDocs/';
				$target_file = $target_dir . basename($_FILES["FileUpload1"]["name"]);
				$FileType = pathinfo($target_file, PATHINFO_EXTENSION);
				// Check file size
				if ($_FILES['FileUpload1']['size'] > 200000) {
					echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
					$uploadOk = 0;
				}
				// Allow certain file formats
				if ($FileType != "jpg" && $FileType != "png" && $FileType != "pdf" && $FileType != "doc" && $FileType != "docx") {
					echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
					$uploadOk = 0;
				}
				if ($uploadOk == 1) {
					if (move_uploaded_file($_FILES["FileUpload1"]["tmp_name"], $target_file)) {
						$ext = pathinfo(basename($_FILES["FileUpload1"]["name"]), PATHINFO_EXTENSION);
						$filename = 'ESIDoc_' . $EmployeeID . '.' . $ext;
						$file = rename($target_file, $target_dir . '' . $filename);
						if (file_exists(ROOT_PATH . 'ESIDocs/' . $filename)) {
							echo "<script>$(function(){ toastr.success('The file " . $filename . " has been uploaded.') });</script>";
							$file_esi = $filename;
						} else {
							echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
						}
					} else {
						echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
					}
				}
			}

			echo $sqlInsertDoc = 'call manage_salary("' . $EmployeeID . '","' . $emptype . '","' . $paytype . '","' . $sal_type . '","' . $ppf . '","' . $newctc . '","' . $takehome . '","' . $hra . '","' . $convence . '","' . $bonus . '","' . $sp_allow . '","' . $gorss_sal . '","' . $pf_count . '","' . $esis_count . '","' . $createBy . '","' . $pfaccount . '","' . $esiNo . '","' . $basic . '","' . $pf_empl_count . '","' . $esis_empl_count . '","' . $file_esi . '","' . $professional_tex . '","' . $MinWages . '","' . $pf_status . '","' . $pli_status . '","' . $pli_mode . '","' . $pli_percent . '","' . $pli_ammount . '","' . $nt_takehome . '","' . $uino . '","' . $rt_type . '","' . $pli_effected . '","' . $gratuity . '","' . $medical_allowance . '")';
			die;

			$result = $myDB->query($sqlInsertDoc);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				//$alert_msg="<span class='text-success'>Date Saved Successfully <b> Provident Fund = <kbd>".$pf_count." Rs</kbd> ESIS = <kbd>".$esis_count."  Rs</kbd>  Take HOME = <kbd>".$takehome."  Rs</kbd></b></span>".$alert_msg;		
				echo "<script>$(function(){ toastr.success('Date Saved Successfully  Provident Fund = <b>" . $pf_count . " <i class=\'fa fa-inr\'></i></b> ESIS = <b>" . $esis_count . "  <i class=\'fa fa-inr\'></i></b>  Take HOME = <b>" . $takehome . "  <i class=\'fa fa-inr\'></i></b>') });</script>";
			} else {
				echo "<script>$(function(){ toastr.error('Data Not Saved " . $mysql_error . "') });</script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Any of the field not contain valid data') });</script>";

			//Minimum Slab [".$min_value."] , Maximum Slab [".$max_value."] ,Average Slab Saved [".$avgSalary."], Salary Slab calculated [".$avgSal."] 
		}
	} else {
		echo "<script>$(function(){ toastr.error('Data Not Saved. Not available for Temp Employee ID.') });</script>";
	}
}
$date_of_join = date('Y-m-d');
// $myDB = new  MysqliDb();
$sqlBy = "select des_id,dateofjoin,nc.client_name,nc.cm_id from employee_map inner join df_master on  employee_map.df_id  =  df_master.df_id inner join designation_master on  designation_master.ID  =  df_master.des_id join new_client_master nc on employee_map.cm_id=nc.cm_id where EmployeeID = ?";
// $myDB = new MysqliDb();
// $resultBy = $myDB->rawQuery($sqlBy);
$selectQury = $conn->prepare($sqlBy);
$selectQury->bind_param("s", $EmployeeID);
$selectQury->execute();
$resultBy = $selectQury->get_result();
if ($resultBy) {
	foreach ($resultBy as $key => $value) {
		$des_id = $value['des_id'];
		$date_of_join = $value['dateofjoin'];
		$clientid = $value['client_name'];
		$cm_id = $value['cm_id'];
	}
} else {
	$des_id = 0;
}

?>

<script>
	$(document).ready(function() {
		$("#txt_pli_effected").datepicker({
			dateFormat: 'yy-mm-dd',
			//changeMonth: true,
			//changeYear: true,	        
			beforeShowDay: function(date) {
				if (date.getDate() == 1) {
					return [true, ''];
				}
				return [false, ''];
			},
			minDate: new Date(<?php echo date('Y,n,1', strtotime('previous month ' . date('Y-m-01', strtotime($date_of_join)))); ?>)

		});


		var usrtype = <?php echo "'" . clean($_SESSION["__user_type"]) . "'"; ?>;
		var usrtype_tmp = <?php echo "'" . clean($_SESSION["__ut_temp_check"]) . "'"; ?>;
		var usrid = <?php echo "'" . clean($_SESSION["__user_logid"]) . "'"; ?>;

		if ((usrtype === 'ADMINISTRATOR' && usrtype_tmp === 'ADMINISTRATOR') || usrtype === 'HR' || usrid == 'CE12102224') {} else if (usrtype === 'AUDIT' || (usrtype === 'ADMINISTRATOR' && usrtype_tmp === 'SITEADMIN')) {
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('button:not(.drawer-toggle)').remove();

			$('.imgbtnEdit').remove();
			$('.imgBtnUploadDelete').remove();

		} else if (usrtype === 'CENTRAL MIS') {

			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
		} else {
			$('input,button:not(.drawer-toggle),select,textarea').remove();
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
			window.location = <?php echo '"' . URL . '/undefined"'; ?>;
		}
		$('#txt_sal_ctc').keydown(function(event) {
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

		// $('.EmployeeDetail').on('click', function() {

		// 	var tval = $(this).text();

		// 	$.ajax({
		// 		url: <?php echo '"' . URL . '"'; ?> + "Controller/GetEmployee.php?empid=" + tval
		// 	}).done(function(data) { // data what is sent back by the php page
		// 		$('#myDiv').html(data).removeClass('hidden');
		// 		$('.imgBtn_close').on('click', function() {
		// 			var el = $(this).parent('div').parent('div');
		// 			el.addClass('hidden');
		// 		});
		// 		// display data
		// 	});

		// });

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Payroll Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Payroll Details</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				?>
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
				<input type="hidden" name="hidden_clientid" id="hidden_clientid" value="<?php echo $clientid; ?>" />
				<!-- <input type="hidden" name="sal_type" id="sal_type" value="<?php echo $sal_type; ?>" /> -->

				<div class="col s12 m12">
					<div class="col s4 m4">

						<div class="card cyan accent-3 white-text" style="padding: 10px;text-align: center;font-weight: bold;">

							<div>CTC : &nbsp;<i class="fa fa-inr"></i> &nbsp; <label id="ctc_hoveDiv" class="white-text">0</label></div>

						</div>
					</div>

					<div class="col s4 m4">
						<div class="card orange darken-2 white-text" style="padding: 10px;text-align: center;font-weight: bold;">
							<div>Take Home : &nbsp;<i class="fa fa-inr"></i> &nbsp;<label id="take_hoveDiv" class="white-text">0</label> </div>
						</div>
					</div>


					<div class="col s4 m4">
						<div class="card green accent-3 white-text" style="padding: 10px;text-align: center;font-weight: bold;">
							<div>&nbsp;&nbsp;&nbsp;Net Take Home : &nbsp;<i class="fa fa-inr"></i> &nbsp; <label id="ntake_hoveDiv" class="white-text">0</label> </div>
						</div>
					</div>
				</div>

				<div class="col s12 m12">

					<div class="input-field col s4 m4">
						<select id="txt_sal_emptype" name="txt_sal_emptype" required>
							<option value="NA" <?php echo ($emptype == 'NA') ? 'selected' : ''; ?>>---Select---</option>
							<option value="FT" <?php echo ($emptype == 'FT') ? 'selected' : ''; ?>>FULL TIME</option>
							<!--<option value="PT"  <?php echo ($emptype == 'PT') ? 'selected' : ''; ?>>PART TIME</option>-->
						</select>
						<label for="txt_sal_emptype" class="active-drop-down active">Employee Type *</label>
					</div>

					<div class="input-field col s4 m4">
						<select id="txt_sal_paytype" name="txt_sal_paytype" required>
							<option value="NA" <?php echo ($paytype == 'NA') ? 'selected' : ''; ?>>---Select---</option>
							<option value="INPE" <?php echo ($paytype == 'INPE') ? 'selected' : ''; ?>>On Roll Under PF & ESI Slab</option>
							<option value="OUTPE" <?php echo ($paytype == 'OUTPE') ? 'selected' : ''; ?>>On Roll Above PF & ESI Slab</option>
						</select>
						<label for="txt_sal_paytype" class="active-drop-down active">Payroll Type *</label>
					</div>

					<div class="input-field col s4 m4">
						<select id="rt_type" name="rt_type" required>
							<option value="NA" <?php echo ($rt_type == 'NA') ? 'selected' : ''; ?>>---Select---</option>
							<?php
							if (clean($_SESSION["__user_type"]) == 'HR' && ($takehome == '')) {
								if ($rt_type == 1 || $rt_type == 3 || $rt_type == 4) {
									if ($rt_type == '1') {
							?>
										<option value="1" selected>Full Time</option>
									<?php
									} elseif ($rt_type == '4') {
									?>
										<option value="4" selected>Split Time</option>
									<?php
									} elseif ($rt_type == 3) {
									?>
										<option value="3" selected>Part Time</option>
									<?php
									} else {
									?>
										<option value="1">Full Time</option>
									<?php
									}
								} else {
									?>
									<option value="1">Full Time</option>
								<?php
								}
							} else {
								if ($rt_type == '1' || empty($rt_type)) {
								?>
									<option value="1" selected>Full Time</option>
									<option value="4">Split Time</option>
									<option value="3">Part Time</option>
								<?php
								} elseif ($rt_type == '4') {
								?>
									<option value="1">Full Time</option>
									<option value="4" selected>Split Time</option>
									<option value="3">Part Time</option>
								<?php
								} elseif ($rt_type == 3) {
								?>

									<option value="1">Full Time</option>
									<option value="4">Split Time</option>
									<option value="3" selected>Part Time</option>
							<?php
								}
							}
							?>
						</select>
						<label for="rt_type" class="active-drop-down active">Roster Type *</label>
					</div>

				</div>

				<div class="col s12 m12">

					<div class="input-field col s4 m4">
						<input type="text" value="<?php echo ($newctc); ?>" id="txt_sal_ctc" name="txt_sal_ctc" required />
						<label for="txt_sal_ctc">New CTC *</label>
					</div>
					<div class="input-field col s4 m4">
						<select id="txt_sal_type" name="txt_sal_type" required>
							<option value="NA" <?php echo ($sal_type == 'NA') ? 'selected' : ''; ?>>---Select---</option>
							<option value="Cogent" <?php echo ($sal_type == 'Cogent') ? 'selected' : ''; ?>>Cogent</option>
							<option value="NATS" <?php echo ($sal_type == 'NATS') ? 'selected' : ''; ?>>NATS</option>
							<option value="NAPS" <?php echo ($sal_type == 'NAPS') ? 'selected' : ''; ?>>NAPS</option>
						</select>
						<label for="txt_sal_type" class="active-drop-down active">Salary Type *</label>
					</div>
					<div class="input-field col s4 m4">
						<input type="text" title="Minimum Wages" value="<?php echo ($MinWages); ?>" id="txt_MinWages" name="txt_MinWages" readonly="true" />
						<label for="txt_MinWages">Min. Wages *</label>
					</div>

				</div>

				<div class="col s12 m12">
					<div class="input-field col s4 m4">
						<input type="text" title="Basic 60% " value="<?php echo ($basic); ?>" id="txt_basic" name="txt_basic" readonly="true" />
						<label for="txt_basic">Basic *</label>
					</div>
					<div class="input-field col s4 m4">
						<input type="text" title="HRA 30% " title="" value="<?php echo ($hra); ?>" id="txt_hra" name="txt_hra" readonly="true" />
						<label for="txt_hra"> HRA *</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" title="Conveyence Allownce" value="<?php echo ($convence); ?>" id="txt_convence" name="txt_convence" readonly="true" />
						<label for="txt_convence"> Conveyence *</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" id="txt_gratuity" name="txt_gratuity" readonly="true" />
						<label for="txt_gratuity">Gratuity</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" title="Medical Allownce" id="txt_medical_allow" name="txt_medical_allow" readonly="true" />
						<label for="txt_medical_allow">Medical Allownce</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" title="Bonus" id="txt_bonus" name="txt_bonus" readonly="true" />
						<label for="txt_bonus"> Bonus</label>
					</div>
				</div>

				<div class="col s12 m12">

					<div class="input-field col s4 m4">
						<input type="text" title="Other Allownces" value="<?php echo ($sp_allow); ?>" id="txt_sp_allow" name="txt_sp_allow" readonly="true" />
						<label for="txt_sp_allow"> Other *</label>
					</div>
					<div class="input-field col s4 m4">
						<input type="text" value="<?php echo ($gorss_sal); ?>" id="txt_gross_sal" name="txt_gross_sal" readonly="true">
						<label for="txt_gross_sal"> Gross Salary *</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" title="Employee PF Contribution 12%" value="<?php echo ($pf_count); ?>" id="txt_pf_count" name="txt_pf_count" readonly="true">
						<label for="txt_pf_count"> Employee PF *</label>
					</div>

				</div>
				<div class="col s12 m12">
					<div class="input-field col s4 m4">
						<input type="text" title="Employee ESIC Contribution 0.75% " value="<?php echo ($esis_count); ?>" id="txt_esis_count" name="txt_esis_count" readonly="true" />
						<label for="txt_esis_count"> Employee ESIC *</label>
					</div>
					<!-- </div> -->


					<div class="input-field col s4 m4">
						<input type="text" title="Employer PF Contribution 13%" value="<?php echo ($pf_empl_count); ?>" id="txt_pf_empl_count" name="txt_pf_empl_count" readonly="true" />
						<label for="txt_pf_empl_count"> Employer PF *</label>
					</div>

					<div class="input-field col s4 m4">

						<input type="text" title=" Employer ESIC Contribution 3.25%" value="<?php echo ($esis_empl_count); ?>" id="txt_esis_empl_count" name="txt_esis_empl_count" readonly="true" />
						<label for="txt_esis_empl_count"> Employer ESIC *</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" value="<?php echo ($professional_tex); ?>" id="txt_professional_tex" name="txt_professional_tex" readonly="true" />
						<label for="txt_professional_tex"> Professional Tax *</label>
					</div>

					<div class="input-field col s4 m4 hidden pf_deduct col s4 m4">

						<select id="txt_pf_deduct" name="txt_pf_deduct">
							<option <?php echo ($pf_status == 'No') ? 'selected' : ''; ?>>No</option>
							<option <?php echo ($pf_status == 'Yes') ? 'selected' : ''; ?>>Yes</option>
						</select>
						<label for="txt_pf_deduct" class="active-drop-down active"> PF Deduction *</label>
					</div>
					<!-- </div>

				<div class="col s12 m12"> -->


					<div class="input-field col s4 m4">
						<input type="text" value="<?php echo ($takehome); ?>" id="txt_takehome" name="txt_takehome" readonly="true" />
						<label for="txt_takehome"> Take Home -1 *</label>
					</div>

					<div class="input-field col s4 m4">
						<select id="txt_pli_deduct" name="txt_pli_deduct">
							<?php
							if (($clientid == '128' || $cm_id == '547') && ($des_id == 9 || $des_id == 12 || $des_id == 33 || $des_id == 34)) {
							?>
								<option selected>No</option>

								<?php
							} else {
								if ((clean($_SESSION["__user_type"]) == 'HR' || clean($_SESSION["__user_type"]) == 'ADMINISTRATOR')) {
									if ($newctc != 13500 && $newctc != 14000 && $newctc != 14500 && $newctc != 15000) {
										if ($des_id != 9 && $des_id != 12 && $des_id != 33 && $des_id != 34) {
								?>
											<option selected>Yes</option>
										<?php
										} else {
										?>
											<option selected>No</option>
										<?php
										}
									} else {
										?>
										<option selected>Yes</option>
									<?php
									}
								} else {
									?>
									<option>No</option>
									<option <?php echo ($pli_status == 'Yes') ? 'selected' : ''; ?>>Yes</option>
							<?php
								}
							}
							?>
						</select>
						<label for="txt_pli_deduct" class="active-drop-down active"> PLI Deduction *</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" value="<?php echo ($nt_takehome); ?>" id="txt_nt_takehome" name="txt_nt_takehome" readonly="true" />
						<label for="txt_nt_takehome"> Net Take Home *</label>
					</div>

					<div class="input-field col s4 m4">
						<?php if ((clean($_SESSION["__user_type"])) == 'ADMINISTRATOR') { ?>
							<input type="number" value="<?php echo ($ppf); ?>" id="txt_ppf" name="txt_ppf" />
							<label for="txt_ppf"> PPF *</label>
						<?php } ?>
					</div>
				</div>

				<div class="col s12 m12">
					<div class="input-field col s4 m4 pli_div">
						<?php
						if (clean($_SESSION["__user_type"]) == 'HR' && ($takehome == '')) {
							if ($des_id != 9 && $des_id != 12 && $des_id != 33 && $des_id != 34) {
								$pli_mode = 'Y';
							}
						}
						?>
						<select id="txt_pli_mode" name="txt_pli_mode">
							<option value="Y" <?php echo ($pli_mode == 'Y') ? 'selected' : ''; ?>>Yearly</option>
							<option value="H" <?php echo ($pli_mode == 'H') ? 'selected' : ''; ?>>Half Yearly</option>
							<option value="Q" <?php echo ($pli_mode == 'Q') ? 'selected' : ''; ?>>Quarterly</option>
							<option value="M" <?php echo ($pli_mode == 'M') ? 'selected' : ''; ?>>Monthly</option>

						</select>
						<label for="txt_pli_mode" class="active-drop-down active"> PLI Mode *</label>
					</div>

					<div class="input-field col s4 m4 pli_div">
						<?php
						if (clean($_SESSION["__user_type"]) == 'HR' && ($takehome == '')) {
							$pli_percent = '10';
						}
						?>
						<input type="number" max="100" value="<?php echo ($pli_percent); ?>" id="txt_pli_percent" name="txt_pli_percent" step="0.01" />
						<label for="txt_pli_percent"> PLI Percentage *</label>
					</div>

					<div class="input-field col s4 m4 pli_div">
						<input type="text" value="<?php echo ($pli_ammount); ?>" id="txt_pli_ammount" name="txt_pli_ammount" readonly="true" />
						<label for="txt_pli_ammount"> PLI Amount *</label>
					</div>
				</div>

				<div class="col s12 m12">
					<div class="input-field col s4 m4 pli_div">
						<input type="text" value="<?php echo ($pli_effected); ?>" id="txt_pli_effected" name="txt_pli_effected" readonly="true" />
						<label for="txt_pli_effected"> PLI Effective *</label>
					</div>

					<div class="input-field col s4 m4 ">
						<input type="text" value="<?php echo ($pfaccount); ?>" id="txt_sal_pf" name="txt_sal_pf" />
						<label for="txt_sal_pf">PF Account No *</label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" value="<?php echo ($esiNo); ?>" id="txt_sal_esi" name="txt_sal_esi" />
						<label for="txt_sal_esi">ESIC No </label>
					</div>

					<div class="input-field col s4 m4">
						<input type="text" value="<?php echo ($uino); ?>" id="txt_uino" name="txt_uino" />
						<label for="txt_uino">UI No</label>
					</div>
				</div>

				<div class="col s12 m12">
					<div class="file-field input-field col s4 m4">
						<div class="btn">
							<span>ESIC Document</span>
							<input id="FileUpload1" name="FileUpload1" style="text-indent: -99999em;" type="file">
							<input type="hidden" id="file_esi" name="file_esi" value="<?php echo $file_esi; ?>" />
							<br>
							<span class="file-size-text">Accepts up to 200 KB</span>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path" style="" type="text">
						</div>
					</div>
				</div>

				<div class="col s12 m12">
					<div class="input-field col s4 m4">
						<?php
						if ($file_esi != '') {
							//echo '<a href="../Docs/'.$file_esi.'" style="color:royalblue;text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.52);" target="_blank"><i class="fa fa-download" style="background: linear-gradient(#ffaa2c,#f78c07);padding: 5px;border: 1px solid #8c7b02;color: white;border-radius: 6px;box-shadow: 2px 2px 3px 0px gray;text-shadow: 1px 1px 1px black;"></i>'.$file_esi.'</a>';
						}
						?>
					</div>
				</div>

				<div class="input-field col s12 m12 right-align">
					<button type="submit" title="Update Details" name="btn_sal_Save" id="btn_sal_Save" class="btn waves-effect waves-green">Save</button>
				</div>

				<style>
					.modelbackground {
						position: fixed;
						height: 100%;
						width: 100%;
						top: 0px;
						left: 0px;
						background: rgba(0, 0, 0, 0.3);
						z-index: 1000;
					}

					.PopUp {
						position: absolute;
						float: left;
						width: 60%;
						overflow: auto;
						top: 25%;
						background: rgba(255, 255, 255, 0.7);
						left: 20%;
						box-shadow: 0px 0px 6px 0px gray inset, 0px 0px 10px 0px rgba(255, 255, 255, 0.95);
						border: 1px solid #67A1AD;
						border-radius: 10px;
						padding: 10px;
						text-shadow: 1px 1px 0px #FFF8F8, 1px 2px 0px rgba(0, 0, 0, 0.28);
					}

					.imgBtn_close {
						position: absolute;
						top: 0;
						right: 0;
					}

					#empinfo_tab td:nth-child(odd) {
						border: 1px solid #A3CCA3;
						color: black;
						text-shadow: none;
						padding-left: 30px;
					}

					#empinfo_tab td:nth-child(even) {
						border: 1px solid #A3CCA3;
						color: #033313;
						font-weight: bold;
						text-transform: uppercase;
						padding-left: 10px;
					}
				</style>
				<div class="hidden modelbackground" id="myDiv"></div>

				<script>
					$(document).ready(function() {
						<?php
						if (substr($EmployeeID, 0, 2) != 'TE' && clean($_SESSION["__user_type"]) != 'ADMINISTRATOR') {
						?>
							if ($('#txt_sal_ctc').val() != '' && $('#txt_sal_emptype').val() != 'NA') {
								$('#txt_sal_ctc').attr('readonly', true);
								$("#btn_sal_Save").hide();
							}
						<?php
						} else if (clean($_SESSION["__user_type"]) != 'ADMINISTRATOR') {
						?>

							$('#txt_sal_ctc').attr('readonly', true);
							$("#btn_sal_Save").hide();
						<?php
						}
						?>

						$('#txt_sal_emptype').change(function() {
							/*if($(this).val()=='PT')
							{
								$('#txt_sal_paytype').empty().append('<option value="NA">---Select---</option><option value="RT">Retainership</option>');
							}
							else */
							if ($(this).val() == 'FT') {
								/*$('#txt_sal_paytype').empty().append('<option value="NA">---Select---</option><option value="RT">Retainership</option><option value="INPE">On Roll Under PF / ESI Slab</option><option value="OUTPE">On Roll Above PF / ESI Slab</option>');*/
								$('#txt_sal_paytype').empty().append('<option value="NA">---Select---</option><option value="INPE">On Roll Under PF / ESI Slab</option><option value="OUTPE">On Roll Above PF / ESI Slab</option>');
								$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
									if ($(element).val().length > 0) {
										$(this).siblings('label, i').addClass('active');
									} else {
										$(this).siblings('label, i').removeClass('active');
									}
								});
								$('select').formSelect();
							} else {
								$('#txt_sal_paytype').empty().append('<option value="NA">---Select---</option>');
								$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
									if ($(element).val().length > 0) {
										$(this).siblings('label, i').addClass('active');
									} else {
										$(this).siblings('label, i').removeClass('active');
									}
								});
								$('select').formSelect();
							}
						});

						var check_validate_sal = function() {
							var validate = 0;
							var alert_msg = '';
							$('#txt_sal_emptype').removeClass('has-error');
							$('#txt_sal_paytype').removeClass('has-error');
							$('#txt_sal_ctc').removeClass('has-error');
							$('#txt_sal_type').removeClass('has-error');
							$('#rt_type').removeClass('has-error');
							if ($('#rt_type').val() == 'NA' || $('#rt_type').val() == '') {
								$('#rt_type').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								validate = 1;
								alert_msg += '<li> Roster Type can not be Empty </li>';
							}
							if ($('#txt_sal_emptype').val() == 'NA') {
								$('#txt_sal_emptype').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								validate = 1;
								alert_msg += '<li> Employee Type can not be Empty </li>';
							}
							if ($('#txt_sal_paytype').val() == 'NA') {
								$('#txt_sal_paytype').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								validate = 1;
								alert_msg += '<li> Pay-Roll Type can not be Empty </li>';
							}
							if ($('#txt_sal_ctc').val() == '') {
								$('#txt_sal_ctc').addClass('has-error');
								validate = 1;
								alert_msg += '<li> CTC  can not be Empty </li>';
							}
							if ($('#txt_pli_percent').val() == '') {
								$('#txt_pli_percent').addClass('has-error');
								validate = 1;
								alert_msg += '<li> Pli Percent  can not be Empty </li>';
							}
							if ($('#txt_sal_type').val() == 'NA') {
								$('#txt_sal_type').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								validate = 1;
								alert_msg += '<li> Salary Type can not be Empty </li>';
							}
							/*if($('#txt_sal_pf').val()=='')
	        {
				$('#txt_sal_pf').addClass('has-error');
				validate=1;
				alert_msg+='<li> PF account No can not be Empty, If you not have PF account then Fill NA in text </li>';
			}
			if($('#txt_sal_esi').val()=='')
	        {
				$('#txt_sal_esi').addClass('has-error');
				validate=1;
				alert_msg+='<li> ESI No can not be Empty, If you not have ESI No then Fill NA in text  </li>';
			}*/
							if (!isNaN($('#txt_sal_ctc').val())) {
								if ($('#txt_sal_emptype').val() == 'FT') {
									if ($('#txt_sal_paytype').val() == 'RT') {
										if (parseInt($('#txt_sal_ctc').val()) >= 8200 || parseInt($('#txt_sal_ctc').val()) < 5000) {
											validate = 1;
											alert_msg += '<li> CTC  can not be Greater Than 8099 and Less Than 5000 for this Pay-Roll Type </li>';
										}


									} else if ($('#txt_sal_paytype').val() == 'INPE') {
										var arr = [8200, 8500, 8700, 9000, 9500, 10000, 10500, 11000, 11500, 12000, 12500, 13000, 13500, 14000, 14500, 15000, 15500, 15800, 16000, 16500, 17000, 17500, 18000, 18500, 19000, 19500, 20000, 20500, 21000];


										if (parseInt($('#txt_sal_ctc').val()) < 8200 || parseInt($('#txt_sal_ctc').val()) > 21000) {
											validate = 1;
											alert_msg += '<li> CTC  can not be Less Than 8200 and Greater Than  21000 for this Pay-Roll Type </li>';
										}
										var valo = jQuery.inArray(parseInt($('#txt_sal_ctc').val()), arr);

										if ((valo == -1 || valo == '-1') && <?php echo "'" . clean($_SESSION['__user_logid']) . "'" ?> != 'CE03070003') {
											validate = 1;
											alert_msg += '<li> CTC can not be other than given salary slab for this Pay-Roll Type </li>';

										}
									} else if ($('#txt_sal_paytype').val() == 'OUTPE') {
										if (parseInt($('#txt_sal_ctc').val()) < 21001) {
											validate = 1;
											alert_msg += '<li> CTC can not be Less Than 21001 for this Pay-Roll Type </li>';
										}
									} else {
										validate = 1;
										alert_msg += '<li> Pay-Roll Type can not be Empty </li>';
									}
								} else if ($('#txt_sal_emptype').val() == 'PT') {
									if (parseInt($('#txt_sal_ctc').val()) < 2500 || parseInt($('#txt_sal_ctc').val()) > 8000) {
										validate = 1;
										alert_msg += '<li> For slected slab salary should be between Rs.2500 to Rs.8000 </li>';
									}
								} else {
									validate = 1;
									alert_msg += '<li> Employee Type can not be Empty </li>';
								}
							} else {
								validate = 1;
								alert_msg += '<li> Wrong Value For New, CTC <code> can be a Number Only</code></li>';
							}

							<?php
							if (clean($_SESSION["__user_type"]) != 'ADMINISTRATOR') {
							?>
								if ($('#txt_pli_deduct').val().toUpperCase() == 'YES') {
									if (parseInt($('#txt_pli_percent').val()) != '10') {
										validate = 1;
										alert_msg += '<li> Only 10% PLI allowed for this kind of Employee Type. </li>';
									}

									/*if($('#txt_pli_mode').val() != 'Y')
									{
										validate=1;
										alert_msg+='<li> Only Yearly Mode allowed for this PLI. </li>';
									}*/
								}

							<?php
							}
							?>

							if ($('#txt_pli_deduct').val().toUpperCase() == 'YES') {
								if ($('#txt_pli_effected').val() == '') {
									validate = 1;
									alert_msg += '<li> PLI effective date should not be empty. </li>';
								}
							}
							if (validate == 1) {

								if (alert_msg != "") {
									$(function() {
										toastr.error(alert_msg)
									});
								}
								return false;
							}
						}

						$('#btn_sal_Save').on('click', function() {
							var validate = 0;
							var alert_msg = '';

							if ($('#rt_type').val() == 'NA' || $('#rt_type').val() == '') {
								$('#rt_type').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								if ($('#srt_type').size() == 0) {
									$('<span id="srt_type" class="help-block">Roster Type can not be Empty.</span>').insertAfter('#rt_type');
								}
								validate = 1;
							}
							if ($('#txt_sal_emptype').val() == 'NA') {
								$('#txt_sal_emptype').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								if ($('#stxt_sal_emptype').size() == 0) {
									$('<span id="stxt_sal_emptype" class="help-block">Employee Type can not be Empty.</span>').insertAfter('#txt_sal_emptype');
								}
								validate = 1;
							}
							if ($('#txt_sal_paytype').val() == 'NA') {
								$('#txt_sal_paytype').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								if ($('#stxt_sal_paytype').size() == 0) {
									$('<span id="stxt_sal_paytype" class="help-block">Pay-Roll Type can not be Empty.</span>').insertAfter('#txt_sal_paytype');
								}
								validate = 1;
							}
							if ($('#txt_sal_ctc').val() == '') {
								$('#txt_sal_ctc').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								if ($('#stxt_sal_ctc').size() == 0) {
									$('<span id="stxt_sal_ctc" class="help-block">CTC  can not be Empty.</span>').insertAfter('#txt_sal_ctc');
								}
								validate = 1;
							}
							if ($('#txt_pli_percent').val() == '') {
								$('#txt_pli_percent').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								if ($('#stxt_pli_percent').size() == 0) {
									$('<span id="stxt_pli_percent" class="help-block">Pli Percent can not be Empty.</span>').insertAfter('#txt_pli_percent');
								}
								validate = 1;
							}
							if ($('#txt_sal_type').val() == 'NA') {
								$('#txt_sal_type').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
								if ($('#stxt_sal_type').size() == 0) {
									$('<span id="stxt_sal_type" class="help-block">Salary Type can not be Empty.</span>').insertAfter('#txt_sal_type');
								}
								validate = 1;
							}
							/*if($('#txt_sal_pf').val()=='')
	        {
				$('#txt_sal_pf').addClass('has-error');
				validate=1;
				alert_msg+='<li> PF account No can not be Empty, If you not have PF account then Fill NA in text </li>';
			}
			if($('#txt_sal_esi').val()=='')
	        {
				$('#txt_sal_esi').addClass('has-error');
				validate=1;
				alert_msg+='<li> ESI No can not be Empty, If you not have ESI No then Fill NA in text  </li>';
			}*/
							if (!isNaN($('#txt_sal_ctc').val())) {
								if ($('#txt_sal_emptype').val() == 'FT') {
									if ($('#txt_sal_paytype').val() == 'RT') {
										if (parseInt($('#txt_sal_ctc').val()) >= 8200 || parseInt($('#txt_sal_ctc').val()) < 5000) {
											validate = 1;
											alert_msg += '<li> CTC  can not be Greater Than 8200 and Less Than 5000 for this Pay-Roll Type </li>';
										}


									} else if ($('#txt_sal_paytype').val() == 'INPE') {
										var arr = [8200, 8500, 8700, 9000, 9500, 10000, 10500, 11000, 11500, 12000, 12500, 13000, 13500, 14000, 14500, 15000, 15500, 15800, 16000, 16500, 17000, 17500, 18000, 18500, 19000, 19500, 20000, 20500, 21000];


										if (parseInt($('#txt_sal_ctc').val()) < 8200 || parseInt($('#txt_sal_ctc').val()) > 21000) {
											validate = 1;
											alert_msg += '<li> CTC  can not be Less Than 8200 and Greater Than  21000 for this Pay-Roll Type </li>';
										}
										var valo = jQuery.inArray(parseInt($('#txt_sal_ctc').val()), arr);

										if ((valo == -1 || valo == '-1') && <?php echo "'" . clean($_SESSION['__user_logid']) . "'" ?> != 'CE03070003') {
											validate = 1;
											alert_msg += '<li> CTC  can not be other than given salary slab for this Pay-Roll Type </li>';

										}
									} else if ($('#txt_sal_paytype').val() == 'OUTPE') {
										if (parseInt($('#txt_sal_ctc').val()) < 21001) {
											validate = 1;
											alert_msg += '<li> CTC  can not be Less Than 21001 for this Pay-Roll Type </li>';
										}
									} else {
										validate = 1;
										alert_msg += '<li> Pay-Roll Type can not be Empty </li>';
									}
								} else if ($('#txt_sal_emptype').val() == 'PT') {
									if (parseInt($('#txt_sal_ctc').val()) < 2500 || parseInt($('#txt_sal_ctc').val()) > 8000) {
										validate = 1;
										alert_msg += '<li> For slected slab salary should be between Rs.2500 to Rs.8000 </li>';
									}
								} else {
									validate = 1;
									alert_msg += '<li> Employee Type can not be Empty </li>';
								}
							} else {
								validate = 1;
								alert_msg += '<li> Wrong Value Fot New CTC <code> can be a Number Only</code></li>';
							}

							<?php
							if (clean($_SESSION["__user_type"]) != 'ADMINISTRATOR') {
							?>
								if ($('#txt_pli_deduct').val().toUpperCase() == 'YES') {
									if (parseInt($('#txt_pli_percent').val()) != '10') {
										validate = 1;
										alert_msg += '<li> Only 10% PLI allowed for this kind of Employee Type. </li>';
									}
									/*if($('#txt_pli_mode').val() != 'Y')
									{
										validate=1;
										alert_msg+='<li> Only Yearly Mode allowed for this PLI. </li>';
									}*/
								}

							<?php
							}
							?>
							if ($('#txt_pli_deduct').val().toUpperCase() == 'YES') {
								if ($('#txt_pli_effected').val() == '') {
									validate = 1;
									alert_msg += '<li> PLI effective date should not be empty. </li>';
								}
							}
							if (validate == 1) {

								$(function() {
									toastr.error(alert_msg);
								});
								return false;
							}
							$('#txt_pli_deduct').prop('disabled', false);
							$('#txt_pli_mode').prop('disabled', false);
							$('#txt_pli_percent').prop('disabled', false);

						});

						var calculate = function() {
							check_validate_sal();
							$('.pf_deduct').addClass('hidden');
							if (parseInt($("#txt_sal_ctc").val()) > 0) {

								// CTC

								var CTC = parseFloat($("#txt_sal_ctc").val());

								var sal_type = $("#txt_sal_type").val();
								var MinVages = 0;
								var Basic = 0;
								var HRA = 0;
								var ConveyenceAllownce = 0;
								var OtherAllownces = 0;
								var OtherAllownces1 = 0;
								var GrossSalary = 0;
								var EmployeePF = 0;
								var EmployeeESIC = 0;
								var EmployerPF = 0;
								var EmployerESIC = 0;
								var ProfessionalTax = 0;
								var TkHome_1 = 0;
								var PLI_percent = 0;
								var PLI_ammount = 0;
								var NetTakeHome = 0;
								var Gratuity = 0;
								var Bonus = 0;
								var MedicalAllowance = 0;
								// MinVages
								var salflag = 0;
								var ppf = 0;
								//alert($('#hidden_clientid').val());

								if ($('#hidden_clientid').val() != '128') {
									if (CTC == 13500 || CTC == 14000 || CTC == 14500 || CTC == 15000) {
										salflag = 0;
									}
								}

								if (salflag == 1) {
									CTC1 = CTC;
									CTC = CTC - (((CTC * 10) / 100) + 209);

									$('#txt_pli_deduct').prop('disabled', true);
									$('#txt_pli_mode').prop('disabled', true);
									$('#txt_pli_percent').val('10');
									$('#txt_pli_percent').prop('disabled', true);
								} else {
									$('#txt_pli_deduct').prop('disabled', false);
									$('#txt_pli_mode').prop('disabled', false);
									$('#txt_pli_percent').prop('disabled', false);
								}

								if ($('#hidden_clientid').val() == '128') {
									$('#txt_pli_deduct').prop('disabled', true);
									$('#txt_pli_mode').prop('disabled', true);
									$('#txt_pli_percent').prop('disabled', true);
								}


								if (CTC < 8200) {
									$("#txt_MinWages").val(MinVages);
								} else {
									if (CTC < 15800) {
										MinVages = 7254;
										$("#txt_MinWages").val(MinVages);
									} else {
										MinVages = parseFloat($("#txt_sal_ctc").val());
										$("#txt_MinWages").val(MinVages);
									}
								}


								// Basic


								if (MinVages < 15800) {
									Basic = (MinVages * 60) / 100;
									$("#txt_basic").val(Basic);
								} else {
									Basic = (CTC * 50) / 100;
									$("#txt_basic").val(Basic.toFixed(2));

								}

								// HRA

								if (MinVages < 15800) {
									HRA = (MinVages * 30) / 100;
									$("#txt_hra").val(HRA.toFixed(2));
								} else {
									HRA = (Basic * 40) / 100;
									$("#txt_hra").val(HRA.toFixed(2));
								}


								// Conveyence Allownce


								if (MinVages < 15800) {
									ConveyenceAllownce = (MinVages * 10) / 100;
									$('#txt_convence').val(ConveyenceAllownce.toFixed(2));
								} else {
									ConveyenceAllownce = 1600;
									$('#txt_convence').val(ConveyenceAllownce);
								}

								// Employee PF and Employer PF

								if (sal_type != 'NATS' && sal_type != 'NAPS') {
									if (CTC < 15800) {
										EmployeePF = (Basic * 12) / 100;
										EmployerPF = (Basic * 13) / 100;
									} else if (CTC >= 15800 && $('#txt_pf_deduct').val() == "Yes") {
										EmployeePF = (Basic * 12) / 100;
										EmployerPF = (Basic * 13) / 100;
									}
								}

								if (salflag == 1) {
									Gratuity = 209;
									Bonus = 363;
									MedicalAllowance = 1250;


								} else {
									Gratuity = 0;
									Bonus = 0;
									MedicalAllowance = 0;

								}

								$("#txt_gratuity").val(Gratuity);
								$("#txt_medical_allow").val(MedicalAllowance);
								$("#txt_bonus").val(Bonus);

								//Other Allownces 

								if (CTC > 21000 && $('#txt_pf_deduct').val() == 'Yes') {
									OtherAllownces = (CTC - (HRA + Basic + ConveyenceAllownce) - EmployerPF);
								} else if (CTC >= 15800 && $('#txt_pf_deduct').val() == 'Yes') {
									OtherAllownces = CTC - (HRA + Basic + ConveyenceAllownce) - EmployerPF - (CTC * 4.23170731707317) / 100;
								} else if (CTC < 8200) {
									OtherAllownces = 0;
								} else if (CTC < 15800) {
									//OtherAllownces = (CTC - 8015)/1.0325;
									OtherAllownces = (CTC - 8180) / 1.0325;

								} else if (CTC <= 21000) {
									OtherAllownces = (CTC * 100 / 103.25) - (HRA + Basic + ConveyenceAllownce);
								} else {

									OtherAllownces = CTC - (HRA + Basic + ConveyenceAllownce);
								}

								if (salflag == 1) {

									OtherAllownces1 = OtherAllownces - (MedicalAllowance + Bonus);

									$("#txt_sp_allow").val(OtherAllownces1.toFixed(2));
								} else {
									$("#txt_sp_allow").val(OtherAllownces.toFixed(2));
								}

								//$("#txt_sp_allow").val(OtherAllownces.toFixed(2));

								if (CTC < 8200) {
									GrossSalary = CTC;
									//$("#txt_gross_sal").val(GrossSalary.toFixed(2));
								} else {
									if (CTC < 15800) {
										if (salflag == 1) {
											GrossSalary = Basic + HRA + ConveyenceAllownce + OtherAllownces1 + MedicalAllowance + Bonus;
										} else {
											GrossSalary = Basic + HRA + ConveyenceAllownce + OtherAllownces;
										}

										//$("#txt_gross_sal").val(GrossSalary.toFixed(2));
									} else {
										if (CTC >= 15800) {
											GrossSalary = Basic + HRA + ConveyenceAllownce + OtherAllownces;
											//$("#txt_gross_sal").val(GrossSalary.toFixed(2));
										} else {
											GrossSalary = (CTC * 100) / 103.25;
											//$("#txt_gross_sal").val(GrossSalary.toFixed(2));
										}
									}
								}
								$("#txt_gross_sal").val(GrossSalary.toFixed(2));
								/*alert(GrossSalary);
								if(salflag == 1)
								{
									GrossSalary1 = GrossSalary + MedicalAllowance + Bonus;
									$("#txt_gross_sal").val(GrossSalary1.toFixed(2));
								}
								else
								{
									$("#txt_gross_sal").val(GrossSalary.toFixed(2));
								}*/
								// Professional Tax

								ProfessionalTax = 0;
								if (CTC > 15000) {
									ProfessionalTax = 200;
								}
								$("#txt_professional_tex").val(ProfessionalTax);


								// Employee ESIC and Employer ESIC

								if (CTC < 8200) {

									$("#txt_esis_count").val(EmployeeESIC);
									$("#txt_esis_empl_count").val(EmployerESIC);
								} else {
									if (CTC > 21000) {
										$("#txt_esis_count").val(EmployeeESIC);
										$("#txt_esis_empl_count").val(EmployerESIC);
									} else {
										if (sal_type != 'NATS' && sal_type != 'NAPS') {
											EmployeeESIC = ($("#txt_gross_sal").val() * 0.75) / 100;
											EmployerESIC = ($("#txt_gross_sal").val() * 3.25) / 100;
											$("#txt_esis_count").val(EmployeeESIC.toFixed(2));
											$("#txt_esis_empl_count").val(EmployerESIC.toFixed(2));
										} else {
											$("#txt_esis_count").val(EmployeeESIC);
											$("#txt_esis_empl_count").val(EmployerESIC);
										}
									}
								}
								// Employer PF 


								// Take Home -1 

								if ($("#txt_sal_paytype").val() == "INPE") {
									//TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
									TkHome_1 = CTC - (EmployeePF + EmployerPF + EmployerESIC + EmployeeESIC + ProfessionalTax);

									if (CTC >= 15800) {
										$('.pf_deduct').removeClass('hidden');
										if ($('#txt_pf_deduct').val() == 'Yes') {
											//TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
											TkHome_1 = CTC - (EmployeePF + EmployerPF + EmployerESIC + EmployeeESIC + ProfessionalTax);
										} else {
											//TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
											TkHome_1 = CTC - (EmployeePF + EmployerPF + EmployerESIC + EmployeeESIC + ProfessionalTax);
											EmployeePF = 0;
											$('#txt_pf_deduct').val('No');
										}
									} else {
										$('.pf_deduct').addClass('hidden');
										$('#txt_pf_deduct').val('No');
									}

								} else if ($("#txt_sal_paytype").val() == "OUTPE") {
									$('.pf_deduct').removeClass('hidden');
									if ($('#txt_pf_deduct').val() == 'Yes') {
										//TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
										TkHome_1 = CTC - (EmployeePF + EmployerPF + EmployerESIC + EmployeeESIC + ProfessionalTax);
									} else {
										//TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
										TkHome_1 = CTC - (EmployeePF + EmployerPF + EmployerESIC + EmployeeESIC + ProfessionalTax);
										EmployeePF = 0;
									}
								} else {
									$('.pf_deduct').addClass('hidden');
									$('#txt_pf_deduct').val('No');
									//TkHome_1 = GrossSalary - (EmployeePF + EmployeeESIC + ProfessionalTax);
									TkHome_1 = CTC - (EmployeePF + EmployerPF + EmployerESIC + EmployeeESIC + ProfessionalTax);
									EmployeePF = 0;

								}

								// PF insert 

								$("#txt_pf_empl_count").val(EmployerPF.toFixed(2));
								$("#txt_pf_count").val(EmployeePF.toFixed(2));
								$("#txt_professional_tex").val(ProfessionalTax.toFixed(2));

								//$("#txt_takehome").val(CTC.toFixed(0));
								$("#txt_takehome").val(TkHome_1.toFixed(2));

								// PLI Calculation 

								if (!isNaN(parseFloat($("#txt_pli_percent").val())) && parseFloat($("#txt_pli_percent").val()) <= 100 && parseFloat($("#txt_pli_percent").val()) >= 0 && $("#txt_pli_deduct").val() == "Yes") {
									PLI_percent = parseFloat($("#txt_pli_percent").val());
								} else {
									if (parseFloat($("#txt_pli_percent").val()) > 100) {
										$("#txt_pli_percent").val("100");
										PLI_percent = 100;
									}
								}
								if (salflag == 1) {
									PLI_ammount = (CTC1 * PLI_percent) / 100;
								} else {
									PLI_ammount = (CTC * PLI_percent) / 100;
								}

								//alert(PLI_ammount);
								$("#txt_pli_ammount").val(PLI_ammount.toFixed(2));


								// Net Take Home
								NetTakeHome = TkHome_1 - PLI_ammount;
								//$("#txt_nt_takehome").val(TkHome_1.toFixed(0));





								<?php
								if (clean($_SESSION["__user_type"]) == 'ADMINISTRATOR') {
								?>
									if ($('#txt_ppf').val() != 0 || $('#txt_ppf').val() != '') {
										ppf = parseFloat($('#txt_ppf').val());
									}
									NetTakeHome = TkHome_1 - PLI_ammount - ppf;
									$('#txt_sal_type').attr("disabled", false);
								<?php } ?>

								<?php if (clean($_SESSION["__user_type"]) == 'HR') { ?>
									$('#txt_sal_type').attr("disabled", true);
								<?php	} ?>

								if ($('#txt_sal_type').val() == "NAPS" || $('#txt_sal_type').val() == "NATS") {
									// alert('sdfg')
									$('.pf_deduct').addClass('hidden');
									$('#txt_pf_deduct').val('No');
									$('#txt_professional_tex').val(0);
									//alert(PLI_percent)
									PLI_ammount = (CTC * PLI_percent) / 100;
									EmployeePF = EmployerPF = EmployerESIC = EmployeeESIC = ProfessionalTax = 0;
									TkHome_1 = CTC - (EmployeePF + EmployerPF + EmployerESIC + EmployeeESIC + ProfessionalTax);
									// alert(TkHome_1)
									NetTakeHome = TkHome_1 - PLI_ammount;
									$('#txt_pf_count').val(0);
									$('#txt_esis_count').val(0);
									$('#txt_pf_empl_count').val(0);
									$('#txt_esis_empl_count').val(0);
									$("#txt_takehome").val(TkHome_1.toFixed(2));
									$("#txt_nt_takehome").val(NetTakeHome.toFixed(0));
									<?php
									if (clean($_SESSION["__user_type"]) == 'ADMINISTRATOR') {
									?>
										if ($('#txt_ppf').val() != 0 || $('#txt_ppf').val() != '') {
											ppf = parseFloat($('#txt_ppf').val());
										}
										NetTakeHome = TkHome_1 - PLI_ammount - ppf;
									<?php } ?>

								} else {
									// $(".pli_div").removeClass('hidden');
								}
								// Net Take Home

								$("#txt_sal_type").on("change", function() {
									if ($('#txt_sal_type').val() == "NAPS" || $('#txt_sal_type').val() == "NATS") {
										$('.pf_deduct').addClass('hidden');
										$('#txt_pf_deduct').val('No');
										$('#txt_professional_tex').val(0);
										PLI_ammount = (CTC * PLI_percent) / 100;
										EmployeePF = EmployerPF = EmployerESIC = EmployeeESIC = ProfessionalTax = 0;
										TkHome_1 = CTC - (EmployeePF + EmployerPF + EmployerESIC + EmployeeESIC + ProfessionalTax);
										// alert(TkHome_1)
										NetTakeHome = TkHome_1 - PLI_ammount;
										$('#txt_pf_count').val(0);
										$('#txt_esis_count').val(0);
										$('#txt_pf_empl_count').val(0);
										$('#txt_esis_empl_count').val(0);
										$("#txt_takehome").val(TkHome_1.toFixed(2));

										<?php
										if (clean($_SESSION["__user_type"]) == 'ADMINISTRATOR') {
										?>
											if ($('#txt_ppf').val() != 0 || $('#txt_ppf').val() != '') {
												ppf = parseFloat($('#txt_ppf').val());
											}
											NetTakeHome = TkHome_1 - PLI_ammount - ppf;
										<?php } ?>
										$("#txt_nt_takehome").val(NetTakeHome.toFixed(0));
									} else {
										// $(".pli_div").removeClass('hidden');
									}
								});

								$("#txt_nt_takehome").val(NetTakeHome.toFixed(0));
								$("#ntake_hoveDiv").text("  " + NetTakeHome.toFixed(0));
								$("#ctc_hoveDiv").text("  " + CTC.toFixed(0));
								$("#take_hoveDiv").text("  " + TkHome_1.toFixed(0));

							}
						}
						$("input,select").on("keyup", calculate);
						$("input,select").on("change", calculate);
						$("input,select").on("blur", calculate);
						calculate();

						$("#txt_pli_deduct").on("change", function() {
							if ($('#txt_pli_deduct').val() == "No") {
								$(".pli_div").addClass('hidden');
								$("#txt_pli_percent").val(0);
								$("#txt_pli_ammount").val(0);
							} else {
								$(".pli_div").removeClass('hidden');
							}
						});

						$("#txt_pli_deduct").trigger("change");
					});
				</script>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>