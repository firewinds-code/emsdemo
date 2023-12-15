<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;

if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		if (!($_SESSION['__user_logid'] == 'CE03070003' || $_SESSION["__user_logid"] == 'CE10091236')) {

			$location = URL . 'Login';
			header("Location: $location");
			exit();
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}


?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Proxy Login</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Proxy Login</h4>

			<!-- Form container if any -->
			</form>
			<form method="post" action="../Controller/transfer_credential_rights.php">
				<div class="schema-form-section row">
					<div class="input-field col s6 m6 8">
						<input type="text" required name="txt_usrId" id="txt_usrId">
						<label for="txt_usrId">Employee ID</label>
					</div>
					<div class="col l12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_transfer">Transfer</button>
					</div>
				</div>


				<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>