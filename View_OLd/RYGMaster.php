<?php
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);

$submit = isset($_POST['submit']);
if ($submit) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$ryg = cleanUserInput($_POST['txt_ryg']);
		$substatus = cleanUserInput($_POST['txt_issuename']);
	}
	$insert = "insert into ryg_substatus_master (`RYG`,`substatus`,`CreatedBy`)values (? , ?, ?);";

	$insQ = $conn->prepare($insert);
	$insQ->bind_param("sss", $ryg, $substatus, $user_logid);
	$insQ->execute();
	$result = $insQ->get_result();

	// $result = $myDB->rawQuery($insert);
	if ($insQ->affected_rows === 1) {
		// if ($myDB->count > 0) {
		echo "<script>$(function(){ toastr.success('RYG details added successfully.') }); </script>";
	} else {
		echo "<script>$(function(){ toastr.error('Error! Try Again..') }); </script>";
	}
}
// if (isset($_POST['submitd'])) {
// 	//aprint_r($_POST);


// }
$update = isset($_POST['update']);
if ($update) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$ryg = cleanUserInput($_POST['txt_ryg']);
		$substatus = cleanUserInput($_POST['txt_issuename']);
		$id = cleanUserInput($_POST['UpdateId']);
	}
	$update = "update  ryg_substatus_master set RYG =? ,substatus=?,updatedBy=?,updatedOn=now()  where id=?;";
	$upQ = $conn->prepare($update);
	$upQ->bind_param("sssi", $ryg, $substatus, $user_logid, $id);
	$upQ->execute();
	$result = $upQ->get_result();
	// $myDB = new MysqliDb();
	// $result = $myDB->rawQuery($update);
	// if ($myDB->count > 0) {
	if ($upQ->affected_rows === 1) {
		echo "<script>$(function(){ toastr.success('RYG details updated successfully.') }); </script>";
	} else {
		echo "<script>$(function(){ toastr.error('Error! Try Again..') }); </script>";
	}
}
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">
	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Add/ Update RYG</span>
	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<!-- Sub Main Div for all Page -->
		<div class="form-div">
			<form id="form">
				<!-- Header for Form If any -->
				<h4>Add/ Update RYG</h4>
				<!-- Form container if any -->
				<div class="schema-form-section row">
					<?php
					$_SESSION["token"] = csrfToken();
					?>
					<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

					<!--<div class="input-field col s12 m12 ">-->
					<div class="input-field col s6 m6">
						<input type="hidden" name="UpdateId" id="UpdateId" />
						<select id="txt_ryg" name="txt_ryg">
							<option value="NA">----Select----</option>
							<option value="Red">Red</option>
							<option value="Yellow">Yellow</option>
							<option value="Green">Green</option>
						</select>
						<!--<label id="txt_ryg" class="activeted_al active">Red  Yellow Green</label>-->
					</div>
					<div class="input-field col s6 m6">
						<input type="text" name="txt_issuename" id="txt_issuename" />
						<label id="txt_issuename" class="activeted_al active">Issue Name</label>
					</div>
					<div class="input-field col s6m6 right-align">
						<button type="submit" id="submit" name="submit" class="btn waves-effect waves-green">Submit</button>
					</div>
					<div class="input-field col s6m6 right-align">
						<button type="submit" id="update" name="update" class="btn waves-effect waves-green" style="display: none;">Update</button>
					</div>
					<div id="pnlTable">
						<?php
						$sqlConnect = 'select id, RYG,substatus from ryg_substatus_master;';
						$myDB = new MysqliDb();
						$result = $myDB->query($sqlConnect);
						$error = $myDB->getLastError();
						if (count($result) > 0 && $result) { ?>
							<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								<div class="">
									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>View</th>
												<th>RYG</th>
												<th>Sub Status</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$count = 0;
											foreach ($result as $key => $value) {
												echo '<tr>';
												$count++;
												echo '<td class="RYG manage_item"><a id="' . $value['id'] . '" class="a__ID" onclick="javascript:return EditData(this);"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>';
												echo '<td class="RYG1">' . $value['RYG'] . '</a><input type="hidden" name="ryg[]" value="' . $value['RYG'] . '" ></td>';
												echo '<td class="RYG2">' . $value['substatus'] . '</a> <input type="hidden" name="remarks[] value="' . $value['substatus'] . '" ></td>';
												echo '</tr>';
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						<?php
						} else {
							echo '<div class="alert alert-danger">Data Not Found :: <code >' . $error . '</code> </div>';
						}
						?>

					</div>
					<!-- <div class="input-field col s6m6 right-align">
						<button type="submit" id="submitd" name="submitd" class="btn waves-effect waves-green">Submit</button>
					</div> -->
				</div>
				<!--Form container End -->
			</form>
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"iDisplayLength": 10,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {
				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}
		});
		$('#myTable1').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"iDisplayLength": 10,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {
				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}
		});


		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');



	});

	function EditData(el) {
		$('#update').show();
		$('#submit').hide()
		$('#RYG').val('');
		var RYG = $(el).parents('td').parents('tr').find('.RYG1').text();
		var RYG2 = $(el).parents('td').parents('tr').find('.RYG2').text();
		$('#txt_ryg').val(RYG);
		$('#txt_issuename').val(RYG2);
		$('#UpdateId').val(el.id);
		//$('#bank_name').addClass("active-drop-down active");
		$('select').formSelect();
	}

	function Validate() {
		var isValid = false;
		var regex = /^[a-zA-Z\s]*$/;
		isValid = regex.test(document.getElementById("txt_bank_name").value);
		document.getElementById("spnError").style.display = !isValid ? "block" : "none";
		return isValid;
	}
	$('#submit').click(function() {
		var validate = 0;
		$('#txt_ryg').removeClass('has-error');
		$('#txt_issuename').removeClass('has-error');
		if ($('#txt_ryg').val() == 'NA') {
			$('#txt_ryg').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
			validate = 1;
			if ($('#txt_ryg').length == 0) {
				$('<span id="txt_rygspan" class="help-block">RYG can not be Empty.</span>').insertAfter('#txt_ryg');
			}
		}
		if ($('#txt_issuename').val() == '') {
			$('#txt_issuename').addClass('has-error');
			validate = 1;
			if ($('#txt_issuename').length == 0) {
				$('<span id="txt_issuenamespan" class="help-block">Issue name can not be Empty.</span>').insertAfter('#txt_issuename');
			}
		}

		if (validate == 1) {
			return false;
		} else {
			$('#submit').addClass('hidden').hide();
		}
	});
	$(document).ready(function() {
		// Handle form submission event
		$('#submitd').on('submit', function(e) {
			// Prevent actual form submission
			e.preventDefault();

			// Serialize form data
			var data = table.$('input,select,textarea').serializeArray();

			// Include extra data if necessary
			// data.push({'name': 'extra_param', 'value': 'extra_value'});

			// Submit form data via Ajax
			$.ajax({
				url: 'echo_request.php',
				data: data
			});
		});
	});
</script>
<?php
include(ROOT_PATH . 'AppCode/footer.mpt');
?>