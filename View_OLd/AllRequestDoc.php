<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB =  new MysqliDb();
$conn = $myDB->dbConnect();

$createBy = clean($_SESSION['__user_logid']);
$EmployeeID = clean($_SESSION['__user_logid']);
$imsrc = URL . 'Style/images/agent-icon.png';

if (isset($_POST['documant_type']) and $_POST['documant_type'] != "") {
	$documant_type = cleanUserInput($_POST['documant_type']);
	$comment_request = cleanUserInput($_POST['comment_request']);
	// $sqlResponse = "INSERT INTO requestdoc (documant_type,comment_request, request_date,request_by,status) VALUES ('" . $documant_type  . "','" . $comment_request . "','" . date('Y-m-d') . "','" . $EmployeeID . "','pending')";
	$sqlResponse = "INSERT INTO requestdoc (documant_type,comment_request, request_date,request_by,status) VALUES (?,?,'" . date('Y-m-d') . "',?,'pending')";
	$stmt = $conn->prepare($sqlResponse);
	$stmt->bind_param("sss", $documant_type, $comment_request, $EmployeeID);
	$stmt->execute();
	$Results = $stmt->get_result();
	// exit;
	// $Results = $myDB->rawQuery($sqlResponse);
	// $mysql_error = $myDB->getLastError();
	if ($Results) {
		$location = URL . 'View/AllRequestDoc.php';
		echo "<script>$(function(){ toastr.success('Request Save Successfully '); }); </script>";
		echo '<script>history.pushState({}, "", "")</script>';
		echo "<script> window.location(" . $location . ")</script>";
	} else {
		echo "<script>$(function(){ toastr.warning('Request Not Save'); }); </script>";
	}
}

?>
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">All Request</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">


		<!-- Sub Main Div for all Page -->

		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>All Request</h4>
			<div class="row">
				<div class=" col s6 ">
					<button data-target="modal1" class="btn waves-effect waves-light btn-small modal-trigger "> <i class="fa fa-plus"> </i>Add Request</button>

				</div>
			</div>
			<div class="schema-form-section row">
				<?php
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				// $sqlConnect = "select * from requestdoc where request_by='" . $EmployeeID . "' ";
				$sqlConnect = "select * from requestdoc where request_by=? ";
				$stm = $conn->prepare($sqlConnect);
				$stm->bind_param("s", $EmployeeID);
				$stm->execute();
				$result = $stm->get_result();
				// $myDB = new MysqliDb();
				// $result = $myDB->query($sqlConnect);
				if ($result) {
				?>
					<table id="myTable1" class="data dataTable no-footer" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Request Type</th>
								<th>Employee Comment</th>
								<th>Accounts Comment</th>
								<th>Request at </th>
								<th>Status</th>
								<th>Download File</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($result as $key => $value) {
								echo '<tr>';
								echo '<td class="test_name">' . $value['documant_type'] . '</td>';
								echo '<td class="testid">' . $value['comment_request'] . '</td>';
								echo '<td class="testid">' . $value['comment_sender'] . '</td>';
								echo '<td class="doc_value">' . $value['request_date'] . '</td>';
								echo '<td class="doc_value">' . $value['status'] . '</td>';
								if ($value['request_doc'] != "") {
									echo '<td class="manage_item" style="text-align:center"><a class="btn waves-effect waves-light btn-small" href="../RequestDoc/' . $value['request_doc'] . '"> <i class="fa fa-download"></i>Download file</a>';
								} else {
									echo '<td class="manage_item" style="text-align:center">Download file';
								}


								echo '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				<?php
				}
				?>

			</div>
		</div>
		<!--Form container End -->
	</div>
	<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>
<div id="modal1" class="modal">
	<div class="modal-content ">
		<h4 style="color:#19aec4">Select File To Upload</h4>
		<form method="POST" action="<?php echo URL . 'View/RequestDoc.php'; ?>">

			<?php

			$_SESSION["token"] = csrfToken();
			?>
			<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
			<!--<div class="col s3">
			<input type="text" name="emp_id" id="emp_id" class="input-field col s12 m12 l6" placeholder="Employee Id">
		</div>-->

			<div class="row ">
				<div class=" col s6 offset-s3">
					<select class="input-field col s6 m4 l8" id="documant_type" name="documant_type" required>
						<option value="">Select Document Type</option>
						<option value="Experience Letter">Experience Letter </option>
						<option value="Reliving Letter">Reliving Letter</option>
						<option value="Termination  Letter">Termination Letter</option>
						<option value="Salary Certificate">Salary Certificate</option>
						<option value="Salary Slip">Salary Slip</option>
						<option value="Promotion">Promotion</option>
					</select>
				</div>
			</div>

			<div class="row ">
				<div class=" col s6 offset-s3">
					<textarea id="comment_request" name="comment_request" class="materialize-textarea"></textarea>
					<label for="comment_request">Comment</label>
				</div>
			</div>

			<div class=" col s6 offset-s3">
				<button type="submit" name="btn_ED_Search" title="Save" id="btn_ED_Search" class="btn waves-effect waves-green">Save</button>

			</div>
		</form>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn modal-close waves-effect waves-green btn-flat">Close</a>
	</div>
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var elems = document.querySelectorAll('.modal');
		var instances = M.Modal.init(elems, options);
	});

	$(document).ready(function() {
		$('.modal').modal();
	});
</script>