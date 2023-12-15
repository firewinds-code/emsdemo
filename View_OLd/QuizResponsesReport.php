<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

if (isset($_SESSION)) {

	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
//print_r($_SESSION);
$clientID = $process = $subprocess = $bheading = $remark1 = $remark2 = $remark3 = '';
$classvarr = "'.byID'";
$searchBy = '';

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$from_date = clean($_POST['from_date']);
	$to_date = clean($_POST['to_date']);
}
$userType = clean($_SESSION["__user_type"]);
$userID = clean($_SESSION['__user_logid']);
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
			"iDisplayLength": 25,
			scrollX: '100%',
			scrollCollapse: true,
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

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Briefing Quiz Responses Report </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Briefing Quiz Responses Report </h4>

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
					<button type="submit" value="Go" name="send" id="send" class="btn waves-effect waves-green">Search</button>
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
						$select_empname_query = $stmt->get_result();
						$select_empname_queryRow = $stmt->get_result();
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						return $select_empname_queryRow[0]; //['EmployeeName'];
					}
					if ($from_date && $to_date && $from_date != "") {
						$date_string = "  b.AttemptedDate BETWEEN '" . $from_date . "' AND '" . $to_date . " 23:59:59' ";

						// $sqlConnect = "select a.id,a.heading,a.CreatedBy,a.CreatedOn,a.fromdate,b.AttemptedDate, b.EmployeeID,e.EmployeeName ,p.Process,p.sub_process,s.ReportTo from brf_briefing a inner join brf_quiz_attempted b on a.id=b.BriefingId left outer JOIN personal_details e ON e.EmployeeID=b.EmployeeID left outer join new_client_master p ON a.cm_id=p.cm_id left outer join status_table s on s.EmployeeID=b.EmployeeID  where  ";

						// if ($userType != 'ADMINISTRATOR' and $userType != 'CENTRAL MIS') {
						// 	$sqlConnect .= " a.CreatedBy='" . $userID . "' and ";
						// }
						// $sqlConnect .=  $date_string;
						// $sqlConnect .= "  group by b.EmployeeID,b.BriefingId  ";

						// echo $sqlConnect;
						// die;
						// $myDB = new MysqliDb();
						// $result = $myDB->rawQuery($sqlConnect);
						// $error = $myDB->getLastError();
						// $rowCount = $myDB->count;

						if ($userType != 'ADMINISTRATOR' && $userType != 'CENTRAL MIS') {
							$sqlConnect = "select a.id,a.heading,a.CreatedBy,a.CreatedOn,a.fromdate,b.AttemptedDate, b.EmployeeID,e.EmployeeName ,p.Process,p.sub_process,s.ReportTo from brf_briefing a inner join brf_quiz_attempted b on a.id=b.BriefingId left outer JOIN personal_details e ON e.EmployeeID=b.EmployeeID left outer join new_client_master p ON a.cm_id=p.cm_id left outer join status_table s on s.EmployeeID=b.EmployeeID  where  a.CreatedBy=? and $date_string group by b.EmployeeID,b.BriefingId";
							$stmt = $conn->prepare($sqlConnect);
							$stmt->bind_param("s", $userID);
							$stmt->execute();
						} else {
							$sqlConnect = "select a.id,a.heading,a.CreatedBy,a.CreatedOn,a.fromdate,b.AttemptedDate, b.EmployeeID,e.EmployeeName ,p.Process,p.sub_process,s.ReportTo from brf_briefing a inner join brf_quiz_attempted b on a.id=b.BriefingId left outer JOIN personal_details e ON e.EmployeeID=b.EmployeeID left outer join new_client_master p ON a.cm_id=p.cm_id left outer join status_table s on s.EmployeeID=b.EmployeeID where $date_string group by b.EmployeeID,b.BriefingId";
							$stmt = $conn->prepare($sqlConnect);
							$stmt->execute();
						}

						// echo $sqlConnect;

						$result = $stmt->get_result();
						if ($result->num_rows > 0) { ?>

							<div class="had-container pull-left row card dataTableInline" id="tbl_div">
								<div class="">
									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Employee ID </th>
												<th>Employee Name</th>
												<th style="vertical-align:top;text-align:center;">Briefing Name</th>
												<th style="vertical-align:top;text-align:center;">Created Date</th>
												<th style="vertical-align:top;text-align:center;">Start Date & Time</th>
												<th style="vertical-align:top;text-align:center;">Response Date</th>
												<th style="vertical-align:top;text-align:center;">Total Question</th>
												<th style="vertical-align:top;text-align:center;">Correct</th>
												<th style="vertical-align:top;text-align:center;">In Correct</th>
												<th style="vertical-align:top;text-align:center;">ReportTo</th>
												<th style="vertical-align:top;text-align:center;">CreatedBy</th>
												<th style="vertical-align:top;text-align:center;">Process</th>
												<th style="vertical-align:top;text-align:center;">Sub Process</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$i = 1;

											foreach ($result as $key => $value) {
												$Briefing_id = $value['id'];
												$EmployeeID = $value['EmployeeID'];
												// $attemted_query = "select sum(case when c.Answer=d.Answer then 1 else 0 end) as CorrectAns,sum(case when c.Answer<>d.Answer then 1 else 0 end) as WrongAns  ,count(d.BriefingID) as TotalQ from brf_quiz_attempted c Left OUTER JOIN brf_question d ON  c.QuestionId=d.QuestionId where c.EmployeeID='" . $EmployeeID . "' and c.BriefingID='" . $Briefing_id . "' ";
												$attemted_query = "select sum(case when c.Answer=d.Answer then 1 else 0 end) as CorrectAns,sum(case when c.Answer<>d.Answer then 1 else 0 end) as WrongAns  ,count(d.BriefingID) as TotalQ from brf_quiz_attempted c Left OUTER JOIN brf_question d ON  c.QuestionId=d.QuestionId where c.EmployeeID=? and c.BriefingID=? ";
												$stmt = $conn->prepare($attemted_query);
												$stmt->bind_param("si", $EmployeeID, $Briefing_id);
												$stmt->execute();
												$totalQuestion = 0;
												$CorrectAns = 0;
												$WrongAns = 0;
												$result_attempted = $stmt->get_result();
												// $myDB = new MysqliDb();
												// $result_attempted = $myDB->rawQuery($attemted_query);
												//print_r($result_attempted);
												foreach ($result_attempted as $key => $val) {
													$totalQuestion = $val['TotalQ'];
													$CorrectAns = $val['CorrectAns'];
													$WrongAns = $val['WrongAns'];
												}

												echo '<tr style="vertical-align:top;">';
												echo '<td class="client_name" style="vertical-align:top;">' . $value['EmployeeID'] . '</td>';
												echo '<td class="process" style="vertical-align:top;" >' . $value['EmployeeName'] . '</td>';
												echo '<td class="process" style="vertical-align:top;" >' . $value['heading'] . '</td>';
												echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >' . $value['CreatedOn'] . '</td>';
												echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >' . $value['fromdate'] . '</td>';
												echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >' . $value['AttemptedDate'] . '</td>';

												echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >' . $totalQuestion . '</td>';
												echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >' . $CorrectAns . '</td>';
												echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >' . $WrongAns . '</td>';
												echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >' . $value['ReportTo'] . '</td>';

												echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >' . getEmpName($value['CreatedBy']) . '</td>';
												echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >' . $value['Process'] . '</td>';
												echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >' . $value['sub_process'] . '</td>';
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
							//	echo '<div id="div_error" class="slideInDown animated hidden" >DATA NOT Found :: <code >'.$error.'</code> </div>';
							echo "<script>$(function(){ toastr.error('Data Not Found.<code >" . $error . "</code>'); }); </script>";
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
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		$('#div_error').click(function() {
			$('#div_error').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		}

		$('#div_error').removeClass('hidden');
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