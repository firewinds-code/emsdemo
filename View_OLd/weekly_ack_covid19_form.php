<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$locationid = '';

$clean_u_login = clean($_SESSION['__user_logid']);

$EmployeeID = strtoupper($clean_u_login);
$sqlquery = "select location from personal_details where EmployeeID = ? ";
$selectQ = $conn->prepare($sqlquery);
$selectQ->bind_param("s", $EmployeeID);
$selectQ->execute();
$results = $selectQ->get_result();
$result = $results->fetch_row();
// $result = $myDB->query($sqlquery);
if ($results->num_rows > 0) {
	$locationid = $result[0];
}
if (isset($_POST['btnSave'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$clean_empID = cleanUserInput($_POST['empID']);
		$clean_empname = cleanUserInput($_POST['empname']);
		$clean_address = cleanUserInput($_POST['address']);
		$clean_mob_num = cleanUserInput($_POST['mobilenum']);

		if ($clean_empID != "" && $clean_empname != "" &&  $clean_address != "" && $clean_mob_num != "") {
			$EmpID = $clean_u_login;
			$currentMondayDate = date('Y-m-d', strtotime('monday this week'));
			$covidQuery = "SELECT createdOn FROM `ack_covid_weekly_form` where EmployeeID = ? and cast(createdOn as date) between ? and cast(NOW() as date)  ";
			$selectQr = $conn->prepare($covidQuery);
			$selectQr->bind_param("ss", $EmpID, $currentMondayDate);
			$selectQr->execute();
			$rscovidack = $selectQr->get_result();
			// $rscovidack = $myDB->query($covidQuery);
			if ($rscovidack->num_rows < 1) {
				$empname = addslashes($clean_empname);
				$empAdd = addslashes($clean_address);
				$empid = $clean_empID;
				$empMobile = $clean_mob_num;
				$query = "Insert into `ack_covid_weekly_form` set  EmployeeID=?, Employeename=?, EmpMobile=?, empAddress=? ";
				$insert = $conn->prepare($query);
				$insert->bind_param("ssis", $empid, $empname, $empMobile, $empAdd);
				$insert->execute();
				$result = $insert->get_result();
				// $result = $myDB->query($query);
			} else {
				echo "<script>$(function(){ toastr.warning('Allready acknowledged in this week'); }); </script>";
			}
			echo "<script>location.href='index.php'; </script>";
		}
	}
}
$remark = $empname = $empid = $searchBy = $msg = '';
$classvarr = "'.byID'";

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Weekly Covid-19 Health Concern</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">
			<b>
				<!-- Header for Form If any -->
				<!-- <h4>Covid-19 Form</h4>	-->
				<!-- Form container if any -->
				<div class="schema-form-section row">
					<?php
					$_SESSION["token"] = csrfToken();
					?>
					<div class="row">

						<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

						<!--<table style="width:100%;">
				<tr>
					<td >To</td><td style="text-align: right;">Date:&nbsp;<?php echo date('d-m-Y'); ?> </td>
				</tr>
			</table>-->
						<p style="width:100%;padding-top: 10px;"><span>To</span><span style="float: right;">Date:&nbsp;<?php echo date('d-m-Y'); ?></span></p>
						<p>The HR Manager,<br>
							<?php if ($locationid == '1') { ?>
								Cogent E-Services Limited, <br>C-121, Sector 63, <br>Noida- 201301.<br>Uttar Pradesh.


							<?php } else
				if ($locationid == '2') { ?>
								Cogent E-Services Limited, <br>C-121, Sector 63, <br>Noida- 201301.<br>Uttar Pradesh.

							<?php } else
					if ($locationid == '3') { ?>
								Cogent E-Services Limited, <br>3rd Floor Apex Tower, <br>1/1, Mangal Pandey Nagar, Meerut<br>Uttar Pradesh.
							<?php
							} else
					if ($locationid == '4') { ?>
								Cogent E-Services Limited, <br>3rd floor, JJ Mall, Ayub khan chawraha, <br>Civil lines, Bareilly (U.P) – 243001
							<?php
							} else
					if ($locationid == '5') { ?>
								Cogent E-Services Limited, <br>Zenith tins compound, <br>Opp Ramakaka dairy chhani,<br>391740 Vadodara Gujrat.
							<?php
							} else
					if ($locationid == '6') { // Mangalore/// 
							?>
								Cogent E-Services Limited, <br>1st Floor, Raj Tower, <br>Balmatta Road, Mangalore - 575002 <br>Karnataka
							<?php }

							/*else
					if($locationid=='7'){ ?>
					
						Cogent E-Services Limited, <br>5th Floor, Krimson Square,<br> Above Vishal Mega mart <br>Rupena agrahara, Near Silk Board,<br> Bangalore-68 
				<?php 
					}else
					if($locationid=='8'){ // flipkart ?>
						Cogent E-Services Limited, <br>Gopalan Enterprise Millenium Tower<br>#133 ITPL Main Road Kundanahali<br>Bangalore-560037
				
					<?php 
					}else
					if($locationid==''){ // habbel // ?>
						Cogent E-Services Limited, <br># 44/2a, Vasant Business Park,<br>Sanjeevini Nagar, Kodigehalli signal Stop,<br>Hebbel, Bangalore, Karnataka-560092
					<?php 
					}
					*/


							?>
						</p>
						<p colspan='2' style="text-align: Center;">Subject: Employee Health Declaration </p>

						<p>This is to inform you that I am working with Cogent E-Services Limited and have resumed the services in a healthy state. I declare that my residence is not sealed /marked in any CONTAINMENT ZONE as defined under COVID guidelines. </p>
						<p>Further I also declare, if my area or residence where I reside is marked as CONTAINMENT AREA in future, I will immediately inform the status change on 9891886100. </p>
						<p>It is further stated that in case I feel unwell or come in contact with any COVID infected person, I will inform my reporting supervisor on immediate basis. I declare to take all precautions and keep social distancing at my workplace. </p>
						<p>Yours Sincerely,</p>
			</b>
			<?php
			$clean_name = clean($_SESSION['__user_Name']);
			?>
			<p><b>Employee Name:</b> <span><?php echo $clean_name; ?></span> </p>
			<p><b>Employee ID:</b><span> <?php echo $clean_u_login; ?></span></p>
			<p><b>Mobile No :</b> <input type='text' name='mobilenum' id="mobilenum" maxlength="10" placeholder="Enter Your Mobile Number" onkeypress="JavaScript:return isNumber(event)"> </p>
			<p><b>Address :</b> <input type='text' name='address' id="address" placeholder="Enter Your Address" maxlength="200"></p>
			<input type='hidden' name='empname' id="empname" value="<?php echo $clean_name; ?>">
			<input type='hidden' name='empID' id="empID" value="<?php echo $clean_u_login; ?>">
			<div class="input-field col s12 m12 right-align">
				<button type="submit" name="btnSave" id="btnSave1" class="btn waves-effect waves-green">Acknowledge</button>
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
		$('#btnSave1').click(function() {
			validate = 0;
			alert_msg = '';


			var empname = $('#empname').val().replace(/^\s+|\s+$/g);
			if (empname == "") {

				$(function() {
					toastr.success('Employee Name should not be empty');
				});
				return false;
			}
			var empID = $('#empID').val().replace(/^\s+|\s+$/g);
			if (empID == "") {

				$(function() {
					toastr.success('EmployeeID should not be empty');
				});
				return false;
			}
			var mobilenum = $('#mobilenum').val().replace(/^\s+|\s+$/g);
			if (mobilenum == "") {
				$('#mobilenum').focus();
				$(function() {
					toastr.success('Mobile Number should not be empty');
				});
				return false;
			} else {

				if (($('#mobilenum').val().length) < 10) {
					$('#mobilenum').focus();
					$(function() {
						toastr.success('Mobile Number should not be lees 10 digit');
					});
					return false;
				}

			}
			var address = $('#address').val().replace(/^\s+|\s+$/g);
			if (address == "") {
				$('#address').focus();
				$(function() {
					toastr.success('Address should not be empty');
				});
				return false;
			}




		});




		$('.fadeIn').removeAttr('id', 'rmenu');


	});

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
</script>
<?php
//if($induction_popup_flag==1 && $totalDays!=0 && $flag1==0){
?>
<script src="../Script/bootstrap2.min.js"></script>
<style>
	.disablediv {
		pointer-events: none;
		opacity: 70% !important;
	}
</style>

<?php
//} 
?>