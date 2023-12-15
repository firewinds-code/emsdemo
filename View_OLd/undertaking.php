<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmployeeID = $EmployeeName = '';
$user_logid = clean($_SESSION['__user_logid']);
if (isset($user_logid) && $user_logid != '') {
	$EmployeeID = clean($_SESSION['__user_logid']);
	$EmployeeName = clean($_SESSION['__user_Name']);
}
$submit = isset($_POST['submit']);
if ($submit) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$dt = new datetime();
		$dt = $dt->format('Y-m-d H:i:s');
		$location = URL . 'View/index.php';
		$userLogID = clean($_SESSION['__user_logid']);
		$sql = "SELECT EmployeeID FROM self_undertaking where EmployeeID=?";
		$selectQ = $conn->prepare($sql);
		$selectQ->bind_param("s", $userLogID);
		$selectQ->execute();
		$count_array = $selectQ->get_result();
		$Inertundertaking = NULL;
		if ($count_array->num_rows < 1) {
			$Inertundertaking = "insert into self_undertaking (EmployeeID, EmployeeName,AcknowledgeDate)values(?,?,?);";
			$ins = $conn->prepare($Inertundertaking);
			$ins->bind_param("sss", $EmployeeID, $EmployeeName, $dt);
			$ins->execute();
			$resu = $ins->get_result();
			// $resu = $myDB->rawQuery($Inertundertaking);
			// $error = $myDB->getLastError();
			if ($resu) {
				if ($ins->affected_rows === 1) {
					//echo "<script>$(function(){ toastr.success('Successfully Acknowledged') });</script>";
					echo "<script>var i=confirm('Successfully Acknowledged')
			if(i){
				location.href='" . $location . "';
			}
			;</script>";
				}
			}
		} else {
			//echo "<script>location.href='".$location."'</script>";
			echo "<script>var i=confirm('Allready acknowleged')
			if(i){
				location.href='" . $location . "';
			}
			;</script>";
		}
	}
}
//////// insert data complete
?>

<style>
	.short,
	.weak {
		color: red;
	}

	.good {
		color: #e66b1a;
	}

	.strong {
		color: green;
	}
</style>
<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Self Undertaking</span>
	<div class="pim-container row" id="div_main">
		<div class="form-div">
			<h4 style="text-align: center">Self Undertaking Letter - Fraudlent Transaction - ZOMATO</h4>
			<div class="schema-form-section row" style="text-justify: !important; width: 700px;">

				<?php $_SESSION["token"] = csrfToken(); ?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<p>I, hereby affirm and declare that I will not share my Login credentials with anyone irrespective of any situation. </p>
				<p>I, hereby declare that, I shall be solely responsible for any sort of un-authorized financial transactions in my official capacity within, as well outside the Company, if processed through my Login ID. </p>
				<p>I will be liable for any action which organization would take against me in case any fraudulent Refund or Promo is initiated using my Login ID.</p>
				<p>I, further understand that, the Organization shall in no way provide any support to me and will not be held responsible for my any such action and will proceed as per their protocol.</p>
				<p>The above statement is made by self with no force implied by my subordinates, Manager’s or any staff member from the company.</p>
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
				<input type="hidden" id="EmployeeName" name="EmployeeName" value="<?php echo $EmployeeName; ?>" />


				<div class="input-field col s12 m12 center-align">
					<button type="submit" name="submit" id="submit" class="btn waves-effect waves-green" style="text-align: center">Acknowledge</button>
				</div>

			</div>
		</div>
	</div>
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>