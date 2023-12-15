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

$gender = $hrname = '';

$clean_u_login = clean($_SESSION['__user_logid']);
$empid = urlencode(base64_encode($clean_u_login));


if (isset($_POST['btnAck'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {

		$edu_type = clean($_POST["txt_edu_type"]);

		$query = "update emp_edu set flag=1,modifiedon=now(),edu_type=?  where EmpID=? ";
		$insert = $conn->prepare($query);
		$insert->bind_param("ss", $edu_type, $clean_u_login);
		$insert->execute();
		//$result = $insert->get_result();
		// $result = $myDB->query($query);

		echo "<script>location.href='index.php'; </script>";
	}
}


?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Acknowledgement</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

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

					<b>
						<br />
						<p>As part of continuous endeavour to keep your details updated in our records, we have found that we don’t have the details of your Graduation updated with us. </p>

						<p>We request you to please update your Graduation / Higher studies details with us by selecting the following options : </p>
					</b>
					<br />
				</div>
				<div class="input-field col s6 m6" id="selectEdu">
					<select id="txt_edu_type" name="txt_edu_type">
						<option value="NA">---Select---</option>
						<option value="Post Graduation">Post Graduation</option>
						<option value="Graduation">Graduation</option>
						<option value="Pursuing Graduation">Pursuing Graduation</option>
						<option value="Under Graduate">Under Graduate</option>
					</select>
					<label for="txt_edu_type" class="active-drop-down active">Education Type</label>
				</div>

				<?php
				$clean_name = clean($_SESSION['__user_Name']);
				?>
				<div class="input-field col s12 m12" id="divUG">
					<b>
						<p>Being a part of the leadership team, it is necessary that you have completed your Gradutaion. We would advise you to please get yourself enrolled and share the proof or enrolment latest by August 31st. </p>
						<p>In case of any issue, please reach out to us on 0120- 4356517/ 9540559955</p>
					</b>
				</div>

				<div class="input-field col s12 m12" id="divlink">
					<b>
						<p><a href="upload_emp_edu_self.php?empid=<?php echo $empid ?>" target="_blank">Please click on the link to upload your document. </a></p>

					</b>
				</div>

				<input type='hidden' name='empname' id="empname" value="<?php echo $clean_name; ?>">
				<input type='hidden' name='empID' id="empID" value="<?php echo $clean_u_login; ?>">
				<div id="divUpdate" class="input-field col s12 m12 right-align">
					<button type="button" name="btnUpdate" id="btnUpdate" class="btn waves-effect waves-green">Acknowledge</button>
				</div>
				<div id="divAck" class="input-field col s12 m12 right-align">
					<button type="submit" name="btnAck" id="btnAck" class="btn waves-effect waves-green">Acknowledge</button>
				</div>

				<div id="spinnerContainer" style="display: none;"></div>

				<!--Form container End -->
				<script src="../Script/spin.min.js"></script>
			</div>
			<!--Main Div for all Page End -->
		</div>
		<!--Content Div for all Page End -->
	</div>
</div>
<style>
	.request-overlay {
		z-index: 9999;
		position: fixed;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		display: block;
		text-align: center;
		background: rgba(200, 200, 200, 0.5)
	}
</style>

<script>
	$(document).ready(function() {
		$('#divUpdate').hide();
		$('#divAck').hide();
		$('#divUG').hide();
		$('#divlink').hide();
		$('#loader').hide();

		$('#txt_edu_type').change(function() {
			if ($(this).val() == 'Under Graduate') {
				$('#divUpdate').hide();
				$('#divAck').show();
				$('#divUG').show();
			} else if ($(this).val() == 'NA') {
				$('#divUpdate').hide();
				$('#divAck').hide();
				$('#divUG').hide();
			} else {
				$('#divUpdate').show();
				$('#divAck').hide();
				$('#divUG').hide();
			}
		});

		$('#btnUpdate').click(function() {
			var usrtype = <?php echo "'" . $_SESSION["__user_logid"] . "'"; ?>;

			$.ajax({
				url: "../Controller/update_emp_edu.php?edu=" + $('#txt_edu_type').val() + "&empid=" + usrtype,
				beforeSend: function() {
					$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
					$("#requestOverlay").show();
					var spinner = new Spinner().spin();
					$('#spinnerContainer').append(spinner.el).show();
				},
				success: function(result) {

					if (result == '1') {
						window.location.replace("emp_edu_confirmation.php");
						//$('#divUpdate').hide();
						//$('#divlink').show();

					} else {
						alert('Please try again');
					}

				},
				complete: function() {

					$('#spinnerContainer').hide();
					$("#requestOverlay").remove(); /*Remove overlay*/


				}
			});
			$("#txt_edu_type").attr("disabled", true);

		});
		$('.fadeIn').removeAttr('id', 'rmenu');


	});
</script>
<?php
//if($induction_popup_flag==1 && $totalDays!=0 && $flag1==0){
?>
<script src="../Script/bootstrap2.min.js"></script>