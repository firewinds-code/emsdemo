<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {

	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$classvarr = "'.byID'";

$userID = clean($_SESSION['__user_logid']);
$userType = clean($_SESSION["__user_type"]);
if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$from_date = cleanUserInput($_POST['from_date']);
	$to_date = cleanUserInput($_POST['to_date']);
}


?>
<script>
	$(document).ready(function() {
		$('#from_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#to_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});

		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

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
				}, 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');
		$('#searchBy').change(function() {
			$('.byID').addClass('hidden');
			if ($(this).val() == 'By ID') {
				$('.byID').removeClass('hidden');
			}
		});
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Briefing Coverage Report </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Briefing Coverage Report </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div class='input-field col s6 m6'>
					<input type='text' name='from_date' id='from_date' <?php if (isset($from_date)) { ?> value="<?php echo $from_date; ?>" <?php } ?> />

					<label for='from_date' class="active">From Date</label>
				</div>
				<div class='input-field col s6 m6'>
					<input type='text' name='to_date' id='to_date' <?php if (isset($to_date)) { ?> value='<?php echo $to_date; ?>' <?php } ?> />
					<label for='to_date' class='active'>From Date</label>
				</div>
				<div class='input-field col s12 m12 right-align'>
					<button type="submit" value="Go" name="send" id="send" class="btn waves-effect waves-green  ">Search</button>
				</div>
				<div id="pnlTable">
					<?php
					function getEmpName($empID)
					{
						$myDB = new MysqliDb();
						$conn = $myDB->dbConnect();
						// $select_empname_query = $myDB->rawQuery("SELECT EmployeeName from personal_details where EmployeeID='" . $empID . "'");
						$select_empname_queryQry = "SELECT EmployeeName from personal_details where EmployeeID=?";
						$stmt = $conn->prepare($select_empname_queryQry);
						$stmt->bind_param("s", $empID);
						$stmt->execute();
						$select_empname_query = $stmt->get_result();
						// $empname_array=mysql_fetch_array($select_empname_query);
						foreach ($select_empname_query as $select_empname_query_val) {
							return $select_empname_query_val['EmployeeName'];
						}
					}
					if ($from_date && $to_date && $from_date != "") {
						$date_string = "  a.AcknowledgeDate BETWEEN '" . $from_date . "' AND '" . $to_date . " 23:59:59' ";

						// $sqlConnect = " select a.AcknowledgeDate,a.EmployeeID,b.heading,b.CreatedBy,b.CreatedOn,b.fromdate,b.view_for,b.quiz,c.EmployeeName,d.Process,d.sub_process,s.ReportTo  from brf_acknowledge a INNER JOIN brf_briefing b on a.BriefingId=b.id  left outer JOIN personal_details c ON a.EmployeeID=c.EmployeeID left outer join new_client_master d ON b.cm_id=d.cm_id left outer join status_table s on s.EmployeeID=a.EmployeeID  where ";
						if ($userType != 'ADMINISTRATOR' and $userType != 'CENTRAL MIS') {
							$sqlConnect = " select a.AcknowledgeDate,a.EmployeeID,b.heading,b.CreatedBy,b.CreatedOn,b.fromdate,b.view_for,b.quiz,c.EmployeeName,d.Process,d.sub_process,s.ReportTo  from brf_acknowledge a INNER JOIN brf_briefing b on a.BriefingId=b.id  left outer JOIN personal_details c ON a.EmployeeID=c.EmployeeID left outer join new_client_master d ON b.cm_id=d.cm_id left outer join status_table s on s.EmployeeID=a.EmployeeID  where b.CreatedBy=? and $date_string order by b.heading";
							// $sqlConnect .= "  b.CreatedBy='" . $userID . "' and ";
							$stmt = $conn->prepare($sqlConnect);
							$stmt->bind_param("s", $userID);
							$stmt->execute();
						} else {
							$sqlConnect = " select a.AcknowledgeDate,a.EmployeeID,b.heading,b.CreatedBy,b.CreatedOn,b.fromdate,b.view_for,b.quiz,c.EmployeeName,d.Process,d.sub_process,s.ReportTo  from brf_acknowledge a INNER JOIN brf_briefing b on a.BriefingId=b.id  left outer JOIN personal_details c ON a.EmployeeID=c.EmployeeID left outer join new_client_master d ON b.cm_id=d.cm_id left outer join status_table s on s.EmployeeID=a.EmployeeID  where $date_string  order by b.heading ";
							$stmt = $conn->prepare($sqlConnect);
							$stmt->execute();
						}

						// echo $sqlConnect;

						$result = $stmt->get_result();
						// $sqlConnect .=  $date_string . "  order by b.heading  ";

						// $sqlConnect = " select a.AcknowledgeDate,a.EmployeeID,b.heading,b.CreatedBy,b.CreatedOn,b.fromdate,b.view_for,b.quiz,c.EmployeeName,d.Process,d.sub_process,s.ReportTo  from brf_acknowledge a INNER JOIN brf_briefing b on a.BriefingId=b.id  left outer JOIN personal_details c ON a.EmployeeID=c.EmployeeID left outer join new_client_master d ON b.cm_id=d.cm_id left outer join status_table s on s.EmployeeID=a.EmployeeID  where ";
						// if ($userType != 'ADMINISTRATOR' and $userType != 'CENTRAL MIS') {
						// 	$sqlConnect .= "  b.CreatedBy='" . $userID . "' and ";
						// }
						// $sqlConnect .=  $date_string . "  order by b.heading  ";

						// echo $sqlConnect;
						// $myDB = new MysqliDb();
						// $result = $myDB->query($sqlConnect);
						// $error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						if ($result->num_rows > 0) { ?>

							<div class="had-container pull-left row card dataTableInline" id="tbl_div">
								<div class="">
									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Employee ID </th>
												<th>Employee Name</th>
												<th>Briefing Name</th>
												<th>Created Date</th>
												<th>Start Date & Time</th>
												<th>Response Date</th>
												<th>Brefing Coverage</th>
												<th>Quiz Available</th>
												<th>Briefing For</th>
												<th>Report To</th>
												<th>Uploaded By</th>
												<th>Process</th>
												<th>Sub Process</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$i = 1;
											foreach ($result as $key => $value) {
												echo '<tr style="vertical-align:top;">';
												echo '<td class="client_name" style="vertical-align:top;">' . $value['EmployeeID'] . '</td>';
												echo '<td class="process" style="vertical-align:top;" >' . $value['EmployeeName'] . '</td>';
												echo '<td class="subprocess">' . $value['heading'] . '</td>';
												echo '<td class="subprocess">' . $value['CreatedOn'] . '</td>';
												echo '<td class="subprocess">' . $value['fromdate'] . '</td>';
												echo '<td class="subprocess">' . $value['AcknowledgeDate'] . '</td>';
												echo '<td class="subprocess">Yes</td>';
												echo '<td class="subprocess">' . $value['quiz'] . '</td>';
												echo '<td class="subprocess">' . $value['view_for'] . '</td>';
												echo '<td class="subprocess">' . $value['ReportTo'] . '</td>';
												echo '<td class="subprocess">' . getEmpName($value['CreatedBy']) . '</td>';
												echo '<td class="subprocess">' . $value['Process'] . '</td>';
												echo '<td class="subprocess">' . $value['sub_process'] . '</td>';
												echo '</tr>';
												$i++;
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
					<?php
						} else {
							//echo '<div id="div_error" class="slideInDown animated hidden">DATA NOT Found :: <code >'.$error.'</code> </div>';
							echo "<script>$(function(){ toastr.error('Data Not Found'); }); </script>";
						}
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


<script>
	$(document).ready(function() {


		// $('#div_error').removeClass('hidden');
		$('#send').on('click', function() {

			validate = 0;
			alert_msg = "";
			var from_date = $('#from_date').val().trim();
			var to_date = $('#to_date').val().trim();
			if (from_date == "" || to_date == "") {
				validate = 1;
				alert_msg += '<li> Please select both date  from and to </li>';
			}
			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;
					*/
				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			}
		});

	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>