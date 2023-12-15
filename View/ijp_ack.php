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
	//echo "<pre>";
	//print_r($_POST);
	//die;
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$empid = $clean_u_login;
		$remarks = cleanUserInput($_POST['txt_Comment']);

		$appliedforpost = $_POST['appliedforpost'];
		$appliedfor = "";
		if ($empid != "") {

				if ($appliedforpost == 'Manager') {
					$Manager = 1;
				} else {
					$Manager = 0;
				}
				// echo $Manager;
			if ($appliedforpost == 'TeamLeader') {
				$TeamLeader = 1;
			} else {
				$TeamLeader = 0;
			}
			// echo $TeamLeader;

			if ($appliedforpost == 'QualityAnalyst') {
				$QualityAnalyst = 1;
			} else {
				$QualityAnalyst = 0;
			}
			// echo $QualityAnalyst;
			if ($appliedforpost == 'ProcessTrainer') {
				$ProcessTrainer = 1;
			} else {
				$ProcessTrainer = 0;
			}
			// echo $ProcessTrainer;

			if ($appliedforpost == 'BusinessCompliance') {
				$BusinessCompliance = 1;
			} else {
				$BusinessCompliance = 0;
			}

			// echo $BusinessCompliance;

			if ($appliedforpost == 'MIS_WFM') {
				$MIS_WFM = 1;
			} else {
				$MIS_WFM = 0;
			}
			// echo $MIS_WFM;

			if ($appliedforpost == 'HR') {
				$HR = 1;
			} else {
				$HR = 0;
			}
			// echo $HR;

			if ($appliedforpost == 'IT') {
				$IT = 1;
			} else {
				$IT = 0;
			}
			// echo $IT;

			$status = $_POST['ack_check'];

			if (isset($_POST['reason'])) {
				$reason = implode(',', $_POST['reason']);
			} else {
				$reason = "";
			}

			if (!empty($remarks)) {
				$remarks = addslashes($remarks);
			} else {
				$remarks = '';
			}

			$ack = $_POST['btnSave'];
			$dataid = $_POST['dataid1'];

			$query = "insert into ijp_ack (empid,emp_ack,ack_date,name,remarks,Manager,TeamLeader,QualityAnalyst,ProcessTrainer,BusinessCompliance,MIS_WFM,HR,IT,status,reason_not_interested,dataid)values (?,?,now(),?,?,?,?,?,?,?,?,?,?,?,?,?)";


			//die;
			//# id, empid, name, emp_ack, remarks, appliedfor, createdon, ack_date
			$insert = $conn->prepare($query);
			$insert->bind_param("ssssssssssssssi", $empid, $ack, $empname, $remarks,$Manager, $TeamLeader, $QualityAnalyst, $ProcessTrainer, $BusinessCompliance, $MIS_WFM, $HR, $IT, $status, $reason, $dataid);
			$insert->execute();
			$result = $insert->get_result();
			// $result = $myDB->query($query);

			echo "<script>location.href='index.php?param=IJP'; </script>";
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
				$msg = "Congratulations! You are eligible for the IJP";
				//$sqlQuery = "select * from ijp_req where empid='" . $_SESSION['__user_logid'] . "' ";
				$sqlQuery = "select * from ijp_req where empid='" . $_SESSION['__user_logid'] . "' and id not in (select dataid from  ijp_ack where empid='" . $_SESSION['__user_logid'] . "') and TIME_TO_SEC(TIMEDIFF(now(),CreatedOn))<259200;";
				$myDB = new MysqliDb();
				$resultQuery = $myDB->query($sqlQuery);
				if (count($resultQuery) > 0) {
					$msg = $resultQuery[0]['Msg1'];
					$dataid = $resultQuery[0]['id'];
				} else {

					echo "<script>location.href='index.php'</script>";
					exit();
				}
				?>
				<div class="row">
					<b>
						<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
						<input type="hidden" name="dataid1" value="<?= $dataid ?>">
						<h3 colspan='2' style="text-align: Center;padding:24px 0px;"><?php echo $msg ?>
						</h3>
						<p>Select the roles you would want to apply for</p>
					</b>
				</div>


				<div class="control-group" id="checkboxdata">

					<?php
					/*$sqlQuery = "select * from ijp_req where empid='" . $_SESSION['__user_logid'] . "' ";
					$myDB = new MysqliDb();
					$resultQuery = $myDB->query($sqlQuery);*/
					// print_r($resultQuery);
					// die;
					if (count($resultQuery) > 0) {
						$arr = array(
							'Manager' => 'Manager',
							'TeamLeader' => 'Team Leader',
							'QualityAnalyst' => 'Quality Analyst',
							'ProcessTrainer' => 'Process Trainer',
							'BusinessCompliance' => 'Business Compliance',
							'MIS_WFM' => 'MIS/WFM',
							'HR' => 'HR',
							'IT' => 'IT'
						);
						foreach ($arr as $k => $v) {
							$valName = $k;
							if ($resultQuery[0][$valName] == "1") {

					?>
								<div class="span3">

									<input type="radio" id="<?php echo $valName; ?>" class="cb_child" name="appliedforpost" value="<?php echo $valName; ?>"><label for="<?php echo $valName; ?>"><?php echo $v; ?></lable>
										</br>

								</div>
					<?php
							}
						}
					}  ?>
				</div>


				<br><br><br><br>
				<p><textarea id="txt_Comment" class="materialize-textarea" name="txt_Comment" maxlength="200" placeholder="Remarks if any"></textarea>
				</p>

				<div class="input-field col s12 m12 right-align">
					<button type="submit" name="btnSave" id="btnSave1" class="btn waves-effect waves-green" value="">Not Interested</button>
					<a href="https://ems.cogentlab.com/erpm/" class="btn waves-effect waves-red left" value="">Do it later</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {

		$(".cb_child").click(function() {
			//alert($(this).val());

			if ($('input[class="cb_child"]:checked').length == 0) {
				$(".waves-green").text('Not Interested');
				$("#btnSave1").val('0');
			} else {
				$(".waves-green").text('Interested');
				$("#btnSave1").val('1');
			}
		});

		$('#btnSave1').click(function() {
			/*	var address = $('#txt_Comment').val().replace(/^\s+|\s+$/g);
				if (address == "") {
					$('#txt_Comment').focus();
					$(function() {
						toastr.error('Remarks should not be empty');
					});
					return false;
				}*/

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