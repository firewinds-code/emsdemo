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
$msgFile = '';
$MSG = "";
$insert_row = $btnUploadCheck = 0;

if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$clean_u_login = clean($_SESSION['__user_logid']);
$empid = urlencode(base64_encode($clean_u_login));
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Educational Qualification</span>

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
						<p>Please click on the link below, if you want to proceed documentation from here. </p>

						<p><b><a href="upload_emp_edu_self.php?empid=<?php echo $empid ?>" target="_blank">Documentation Link </a></b></p>

						<br />
						<p>Also Documentation link has been sent on your email and whatsapp number. </p>
					</b>
				</div>




				<input type='hidden' name='empname' id="empname" value="<?php echo $clean_name; ?>">
				<input type='hidden' name='empID' id="empID" value="<?php echo $clean_u_login; ?>">

			</div>
			<!--Main Div for all Page End -->
		</div>
		<!--Content Div for all Page End -->
	</div>
</div>
<?php
include(ROOT_PATH . 'AppCode/footer.mpt');

?>