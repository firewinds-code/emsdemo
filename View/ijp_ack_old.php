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
$remark = $empname = $empid = $searchBy = $msg = '';
$clean_u_login = clean($_SESSION['__user_logid']);
$empname = clean($_SESSION["__user_Name"]);



if (isset($_POST['btnSave'])) {
	// echo "<pre>";
	// print_r($_POST);
	//die;
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$empid = $clean_u_login;
		$remarks = cleanUserInput($_POST['txt_Comment']);

		$appliedfor="";
		if ($empid != "" && $remarks != "") {

			if(!empty($_POST['TeamLeader'])){
				$TeamLeader = $_POST['TeamLeader'];
			}else{
				$TeamLeader=0;
			}
			if(!empty($_POST['QualityAnalyst'])){
				$QualityAnalyst = $_POST['QualityAnalyst'];
			}else{
				$QualityAnalyst=0;
			}
			if(!empty($_POST['ProcessTrainer'])){
				$ProcessTrainer = $_POST['ProcessTrainer'];
			}else{
				$ProcessTrainer=0;
			}
			if(!empty($_POST['BusinessCompliance'])){
				$BusinessCompliance = $_POST['BusinessCompliance'];
			}else{
				$BusinessCompliance=0;
			}
			
			if(!empty($_POST['MIS_WFM'])){
				$MIS_WFM = $_POST['MIS_WFM'];
			}else{
				$MIS_WFM=0;
			}
			if(!empty($_POST['HR'])){
				$HR = $_POST['HR'];
			}else{
				$HR=0;
			}
			if(!empty($_POST['IT'])){
				$IT = $_POST['IT'];
			}else{
				$IT=0;
			}

			$remarks = addslashes($remarks);
			 $query = "update ijp_ack set emp_ack=1,ack_date=now(),name=?,remarks=?, 
			TeamLeader=$TeamLeader, QualityAnalyst=$QualityAnalyst, ProcessTrainer=$ProcessTrainer, BusinessCompliance=$BusinessCompliance, MIS_WFM=$MIS_WFM, HR=$HR, IT=$IT where empid=? ";

			//die;
			//# id, empid, name, emp_ack, remarks, appliedfor, createdon, ack_date
			$insert = $conn->prepare($query);
			$insert->bind_param("sss", $empname,$remarks, $empid);
			$insert->execute();
			$result = $insert->get_result();
			// $result = $myDB->query($query);

			echo "<script>location.href='index.php'; </script>";
		}
	}
}



?>
<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">IJP Acknowledge</span>
	<div class="pim-container row" id="div_main">
		<div class="form-div">
				<div class="schema-form-section row">
					<?php
					$_SESSION["token"] = csrfToken();
					?>
					<div class="row">
						<b>
						<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
						<h3 colspan='2' style="text-align: Center;padding:24px 0px;">Congratulations! You are eligible for the IJP in Axis Bank. 
 </h3>						
						<p>Select the roles you would want to apply for</p>
						</b>
					</div>

					<div class="control-group">   					

								<?php
								$sqlQuery = "select * from ijp_req where empid='CE10091236' ";
								$myDB = new MysqliDb();

								$resultQuery = $myDB->query($sqlQuery);
								// print_r($resultQuery);
								// die;
								if (count($resultQuery) > 0) {
									$arr = array(
										'TeamLeader'=>'Team Leader',
										'QualityAnalyst'=>'Quality Analyst',
										'ProcessTrainer'=>'Process Trainer',
										'BusinessCompliance'=>'Business Compliance',
										'MIS_WFM'=>'MIS/WFM',
										'HR'=>'HR',
										'IT'=>'IT'
										);
									//for ($i = 1; $i <=7; $i++) {
									//for ($i = 0; $i <sizeof($arr); $i++) {
										foreach($arr as $k=>$v){
										$valName = $k;
										if ($resultQuery[0][$valName] == "1") {
											//$k =$i-1;
											
											?>
											<div class="span3" >

											<input type="checkbox" id="<?php echo $valName; ?>" class="cb_child" name="<?php echo $valName; ?>" value="1"><label for="<?php echo $valName; ?>" ><?php echo $v; ?></lable>

																						
												
										</br>

											</div>
								<?php
										}
									}
								}  ?>
							</div>
					
					<p><textarea id="txt_Comment" class="materialize-textarea" name="txt_Comment" maxlength="200" placeholder="Remarks if any"></textarea>
					</p>
				</div>
			<div class="input-field col s12 m12 right-align">
				<button type="submit" name="btnSave" id="btnSave1" class="btn waves-effect waves-green">Acknowledge</button>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#btnSave1').click(function() {
			validate = 0;
			alert_msg = '';

			var address = $('#txt_Comment').val().replace(/^\s+|\s+$/g);
			if (address == "") {
				$('#txt_Comment').focus();
				$(function() {
					toastr.error('Remarks should not be empty');
				});
				return false;
			}

		});
		$('.fadeIn').removeAttr('id', 'rmenu');

	});
</script>

<script src="../Script/bootstrap2.min.js"></script>
<style>
	.disablediv {
		pointer-events: none;
		opacity: 70% !important;
	}
</style>
