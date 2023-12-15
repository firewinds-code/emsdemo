<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$alert_msg = '';
if (isset($_POST['btn_Issue_Save'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$_clientid = cleanUserInput($_POST['txt_client']);
		$_client = cleanUserInput($_POST['txt_clientname']);
		$_process = cleanUserInput($_POST['txt_Process']);
		$_cm_id = cleanUserInput($_POST['txt_SubProcess']);
		$_subprocess = cleanUserInput($_POST['hidden_subprocess']);
		$createBy = clean($_SESSION['__user_logid']);
		$batch_alias =  cleanUserInput($_POST['txt_BatchAlias']);
		$validate = 0;
		$myDB = new MysqliDb();

		$resultcheck = 'SELECT Alias FROM batch_master where `clientid` =? and `process`=? and Alias =?';
		$stmt = $conn->prepare($resultcheck);
		$stmt->bind_param("sss", $_clientid, $_process, $batch_alias);
		$stmt->execute();
		$result_check = $stmt->get_result();
		if ($result_check->num_rows > 0 && $result_check) {
			$validate = 1;
		}
		if (!empty($batch_alias) && $validate == 0) {

			$_batch_name = $_client . '|' . $_process . '|' . $_subprocess . '|' . $batch_alias;
			$Insert = 'call add_bacth_by_th("' . $_clientid . '","' . $_process . '","' . $_subprocess . '","' . $createBy . '","' . $_client . '","' . $_batch_name . '","' . $batch_alias . '","' . $_cm_id . '")';

			$myDB = new MysqliDb();;

			$result = $myDB->query($Insert);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				echo "<script>$(function(){ toastr.success('Batch No ($_batch_name) Added Successfully.'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Batch not Added.$mysql_error'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Batch Alias already is exists or empty.Try again with another Batch Alias.'); }); </script>";
		}
	}
}

?>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[10, 25, 50, -1],
				['10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				{
					extend: 'csv',
					text: 'CSV',
					extension: '.csv',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				},
				'print',
				{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'copy', 'pageLength'

			],
			"order": [
				[6, "desc"]
			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Batch</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Create New Batch</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<input type="hidden" id="hidden_subprocess" name="hidden_subprocess">
				<div class="form-inline">
					<div class="input-field col s6 m6">
						<select id="txt_client" onchange="javascript:return getProcess(this);" name="txt_client">
							<option value="NA">---Select---</option>
							<?php

							$myDB = new MysqliDb();
							$conn = $myDB->dbConnect();
							$userID = clean($_SESSION['__user_logid']);
							$sqlConnect = 'select distinct client_id,client_master.client_name from new_client_master inner join `client_master` on new_client_master.client_name = `client_master`.client_id where th = ?';
							$stmt = $conn->prepare($sqlConnect);
							$stmt->bind_param("s", $userID);
							$stmt->execute();

							$resultBy = $stmt->get_result();
							if ($resultBy) {
								$selec = '';
								foreach ($resultBy as $key => $value) {

									echo '<option value="' . $value['client_id'] . '" ' . $selec . ' >' . $value['client_name'] . '</option>';
								}
							}
							?>
						</select>
						<label for="txt_client" class="active-drop-down active">Client</label>
						<input type="hidden" id="txt_clientname" name="txt_clientname" value="NA">

					</div>
					<div class="input-field col s6 m6">
						<select id="txt_Process" onchange="javascript:return getSubProcess(this);" name="txt_Process"></select>
						<label for="txt_Process" class="active-drop-down active">Process</label>
					</div>
					<div class="input-field col s6 m6">
						<select id="txt_SubProcess" onchange="javascript:return onSubProcess(this);" name="txt_SubProcess"></select>
						<label for="txt_SubProcess" class="active-drop-down active">Sub Process</label>

					</div>

					<div class="input-field col s6 m6 hidden" id="div_alias">

						<input id="txt_BatchAlias" name="txt_BatchAlias" type="number" />
						<label for="txt_BatchAlias" id="batch_alias_label"></label>
					</div>
					<div class="input-field col s12 m12 right-align">
						<button type="submit" name="btn_Issue_Save" id="btn_Issue_Save" class="btn waves-effect waves-green hidden">Add</button>
					</div>
				</div>

				<?php
				$userid = clean($_SESSION['__user_logid']);
				$sqlConnect = 'call get_batch_master_byth("' . $userid . '")';
				$myDB = new MysqliDb();
				$result = $myDB->query($sqlConnect);
				//echo $sqlConnect;
				$error = $myDB->getLastError();
				if (count($result) > 0 && $result) { ?>

					<div id="pnlTable" class="col s12 m12 card">
						<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Batch No</th>
									<th>Batch Name</th>
									<th>Batch Alias</th>
									<th>Client</th>
									<th>Process</th>
									<th>Sub Process</th>
									<th>Created On</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 0;
								foreach ($result as $key => $value) {
									$count++;
									echo '<tr>';
									echo '<td class="batch_no">' . $value['batch_no'] . '</td>';
									echo '<td class="BacthName">' . $value['BacthName'] . '</td>';
									echo '<td class="BacthAlias">' . $value['Alias'] . '</td>';
									echo '<td class="client_name">' . $value['client'] . '</td>';
									echo '<td class="process">' . $value['process'] . '</td>';
									echo '<td class="sub_process">' . $value['subprocess'] . '</td>';
									echo '<td class="createdon" style="padding:0px;">' . $value['createdon'] . '</td>';
									echo '</tr>';
								}
								?>
							</tbody>
						</table>

					</div>
				<?php
				} else {
					echo "<script>$(function(){ toastr.error('Congratulations, all employees have been aligned to concern departments. $error'); }); </script>";
				}
				?>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>


<script>
	function onSubProcess() {
		//alert($('#txt_SubProcess option:selected').text());
		if ($('#txt_SubProcess') != '' || $('#txt_SubProcess') != 'NA') {
			$('#btn_Issue_Save,#div_alias').removeClass('hidden');

			$('#batch_alias_label').text($('#txt_client option:selected').text() + "|" + $('#txt_Process').val() + "|" + $('#txt_SubProcess option:selected').text());
		} else {
			$('#batch_alias_label').text("");
		}
		$('#hidden_subprocess').val($('#txt_SubProcess option:selected').text());
		$('select').formSelect();
	}

	function getProcess(el) {
		var currentUrl = window.location.href;

		var xmlhttp;
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


				var Resp = xmlhttp.responseText;
				$('#txt_Process').html(Resp);
				$('#txt_SubProcess').html('');
				$('#btn_Issue_Save,#div_alias').addClass('hidden');
				$('#batch_alias_label').text("");
				$('select').formSelect();
			}

		}
		$('#txt_clientname').val($("#txt_client option:selected").text());
		var location = <?php echo clean($_SESSION["__location"]) ?>;
		xmlhttp.open("GET", "../Controller/getprocess.php?id=" + $('#txt_client').val() + "&loc=" + location, true);
		xmlhttp.send();

	}

	function getSubProcess(el) {
		var currentUrl = window.location.href;

		var xmlhttp;
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


				var Resp = xmlhttp.responseText;
				$('#txt_SubProcess').html(Resp);
				$('#btn_Issue_Save,#div_alias').addClass('hidden');
				$('#batch_alias_label').text("");
				$('select').formSelect();
			}
		}
		var location = <?php echo clean($_SESSION["__location"]) ?>;
		xmlhttp.open("GET", "../Controller/getsubprocess.php?proc=" + $('#txt_Process').val() + "&id=" + $('#txt_client').val() + "&loc=" + location, true);
		xmlhttp.send();

	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>