<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');


$locationid = $EmpName = $add = $fathername = $desig = '';

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmployeeID = clean(strtoupper($_SESSION['__user_logid']));
$EmployeeName = clean(strtoupper($_SESSION['__user_Name']));
$sqlquery = "select t1.EmployeeID,t1.EmployeeName,address_p,FatherName, case when df_id=74 or df_id=77 then 'Agent' else 'Support Staff' end as desig from personal_details t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID join address_details t5 on t2.EmployeeID=t5.EmployeeID where t2.EmployeeID= ?";
$stmt = $conn->prepare($sqlquery);
$stmt->bind_param("s", $EmployeeID);

$stmt->execute();
$result = $stmt->get_result();
$resultraw = $result->fetch_row();

$count = $result->num_rows;
if ($count > 0) {
	$add = $resultraw[2];
	$fathername = $resultraw[3];
	$desig = $resultraw[4];
}



if (isset($_POST['btnSave'])) {
	$source = 'Web';
	$query = "insert into dicv_decl (EmployeeID, EmpName,Address,fathername,designation,source) values(?,?,?,?,?,?) ";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("ssssss", $EmployeeID, $EmployeeName, $add, $fathername, $desig, $source);

	$stmt->execute();
	$result = $stmt->get_result();
	if ($stmt->affected_rows === 1) {
		echo "<script>$(function(){toastr.success('Acknowledge Successfully')})</script>";
		echo "<script>location.href='index.php'; </script>";
	} else {
		echo "<script>$(function(){toastr.error('Not Acknowledge')})</script>";
	}
	//echo "<script>$(function(){ toastr.warning('Allready acknowledged in this week'); }); </script>";

	//echo "<script>location.href='index.php'; </script>";
}

$remark = $empname = $empid = $searchBy = $msg = '';
$classvarr = "'.byID'";

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">DICV Declaration</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<!-- <h4>Covid-19 Form</h4>	-->
			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--<table style="width:100%;">
				<tr>
					<td >To</td><td style="text-align: right;">Date:&nbsp;<?php echo date('d-m-Y'); ?> </td>
				</tr>
			</table>-->
				<p style="width:100%;padding-top: 30px;"><span style="padding-left: 350px;font-size: 18px;">Declaration</span></p>


				<p style="padding-top: 25px;"> This is to confirm that I <b><?php echo $EmployeeName; ?></b> resident of <b><?php echo $add  ?></b> S/O, D/O <b><?php echo  $fathername  ?></b> am working as <b><?php echo $desig  ?></b> for Daimler India Commercial Vehicles Private Limited. </p>
				<p>I understand and agree to abide confidentiality to be maintained by myself related to my work assigned by Cogent E Services Limited. I hereby declare that I don’t have any criminal records in past. During my tenure with the Company and thereafter also till perpetuity, I agree and confirm that: </p>
				<p> &nbsp; &nbsp;&nbsp;&nbsp; a) Will ensure no recording or storage device, pen, paper taken on production floor </p>
				<p> &nbsp; &nbsp;&nbsp;&nbsp; b) Will ensure no information pertaining to the process is shared to anyone who is not a part of DICV process </p>
				<p> &nbsp; &nbsp;&nbsp;&nbsp; c) Will ensure no data is taken out of the production floor </p>
				<p> &nbsp; &nbsp;&nbsp;&nbsp; d) Will not misuse any customer information </p>
				<p> &nbsp; &nbsp;&nbsp;&nbsp; e) Will not Argue with customer or exhibit rude behavior </p>
				<p> &nbsp; &nbsp;&nbsp;&nbsp; f) Will not Use Profanity / sarcastic tone with the customer. </p>
				<p> &nbsp; &nbsp;&nbsp;&nbsp; g) Will not Exchange (asking/providing) personal (non-business related) information with the customers </p>
				<p> &nbsp; &nbsp;&nbsp;&nbsp; h) Will not Educate customer on process loopholes </p>
				<p> &nbsp; &nbsp;&nbsp;&nbsp; i) Will not share credentials such as passwords with anyone, not even with supervisor. </p>
				<p>I do hereby agree that any non-compliance to the terms as defined under this declaration or any deviation from the Code of Conduct of the Company, then it may lead to disciplinary action against me that may include termination of my employment or criminal prosecution under applicable laws or financial recovery as decided by the Company in future related to my default.</p>

				<p><br />Acknowledged by <br /><br /> <b><span><?php echo $EmployeeName; ?></b></span> </p>


				<div class="input-field col s12 m12 right-align">
					<button type="submit" name="btnSave" id="btnSave" class="btn waves-effect waves-green">Acknowledge</button>
				</div>
			</div>


			<!--Form container End -->

		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>


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