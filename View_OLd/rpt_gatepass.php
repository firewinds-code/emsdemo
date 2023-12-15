<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = $thisPage = 0;
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;
		$referer = $alert_msg = "";
		$thisPage = URL . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}
		if ($referer == $thisPage) {
			$isPostBack = true;
		}
		if ($isPostBack && isset($_POST)) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$date_To = cleanUserInput($_POST['txt_dateTo']);
				$date_From = cleanUserInput($_POST['txt_dateFrom']);
			}
		} else {
			$date_To = date('Y-m-d', time());
			$date_From = date('Y-m-d', time());
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$txt_dept = cleanUserInput($_POST['txt_dept']);
	$txt_dateTo = cleanUserInput($_POST['txt_dateTo']);
}
?>
<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
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
			"iDisplayLength": 25,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		//$('.buttons-copy').attr('id','buttons_copy');
		//	$('.buttons-csv').attr('id','buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		//	$('.buttons-pdf').attr('id','buttons_pdf');
		//$('.buttons-print').attr('id','buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Gate Pass Reports</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Gate Pass Reports</h4>
			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s5 m5">
						<Select name="txt_dept" id="txt_dept" required>
							<?php
							/*if($_SESSION['__user_type']=='ADMINISTRATOR' || $_SESSION['__user_type']=='CENTRAL MIS' || $_SESSION['__user_type']=='HR' || ($_SESSION['__user_type']=='HR' &&  $_SESSION['__status_ah']==$user_logid) )*/
							if ($user_logid == 'CE10091236' || $user_logid == 'CE03070003') {

								$myDB = new MysqliDb();
								$rowData = $myDB->query("select distinct concat(Process,'|',sub_process) as Process,cm_id from new_client_master order by process;");
								if (count($rowData) > 0) {
									echo '<option value="ALL">ALL</option>';
									foreach ($rowData as $key => $value) {
										/*if($dept == $value['Process'])
							{
					echo '<option selected>'.$value['Process'].'</option>';
							}
							else
							{*/
										echo '<option value=' . $value['cm_id'] . '>' . $value['Process'] . '</option>';
										//}

									}
								}
							} else {
								echo '<option selected>NA</option>';
							}
							/*{
					$myDB =new MysqliDb();
					$rowData = $myDB->query('select distinct Process,cm_id from new_client_master where account_head="'.$user_logid.'" or th="'.$user_logid.'" or qh="'.$user_logid.'" or oh="'.$user_logid.'"');
					
					if(count($rowData) > 0)
					{
						foreach($rowData as $key=>$value)
						{
							if($dept == $value['Process'])
							{
								echo '<option selected>'.$value['Process'].'</option>';
							}
							else
							{
								echo '<option value='.$value['cm_id'].'>'.$value['Process'].'</option>';
							}
						}
					}
					else
					{
						echo '<option >'.$_SESSION['__user_process'].'</option>';	
					}	
				}*/
							?>
						</Select>
					</div>
					<div class="input-field col s5 m5">
						<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s2 m2">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php
					if (isset($_POST['btn_view'])) {
						$myDB = new MysqliDb();
						$in = $out = $Punchin1 = $Punchin2 = $time = $totaltime = '';
						$EmployeeID = '';
						$EmployeeName = '';
						$i = 0;
						$table = '<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th><th>EmployeeName</th><th>Process</th><th>DateOn</th><th>Total Time</th><th>Out Time</th><th>In Time</th></tr></thead><tbody>';
						//if cm id in this intervel then create table other wise hide it.
						// $txt_dept = cleanUserInput($_POST['txt_dept']);
						if ($txt_dept == 'ALL') {
							$Query = "select EmployeeID,EmployeeName,cm_id,Process from whole_details_peremp where emp_status='Active' limit 50 ";
							$stmt = $conn->prepare($Query);
							$stmt->execute();
						} else {
							// $Query = "select EmployeeID,EmployeeName,cm_id,Process from whole_details_peremp where emp_status='Active' and cm_id='" . $_POST['txt_dept'] . "'";
							$Query = "select EmployeeID,EmployeeName,cm_id,Process from whole_details_peremp where emp_status='Active' and cm_id=?";
							$stmt = $conn->prepare($Query);
							$stmt->bind_param("s", $txt_dept);
							$stmt->execute();
						}
						$result = $stmt->get_result();
						// print_r($result);
						// die;
						// $result = $myDB->rawQuery($Query);
						// $my_error = $myDB->getLastError();
						if ($result->num_rows > 0 && $result) {
							foreach ($result as $key => $value) {
								$EmployeeID = "'" . $value['EmployeeID'] . "'";
								$EmployeeName = $value['EmployeeName'];
								$Process = $value['Process'];
								$time = $FinalTime = $i = 0;
								// $query_Select = "select EmployeeID, PunchTime,Type,DateOn from biopunch_inout where EmployeeID=$EmployeeID and dateon='" . $_POST['txt_dateTo'] . "' order by PunchTime;";
								// $txt_dateTo = cleanUserInput($_POST['txt_dateTo']);
								$query_Select = "select EmployeeID, PunchTime,Type,DateOn from biopunch_inout where EmployeeID=? and  dateon=? order by PunchTime;";
								$stmt = $conn->prepare($query_Select);
								$stmt->bind_param("ss", $EmployeeID, $txt_dateTo);
								$stmt->execute();
								$result = $stmt->get_result();
								// print_r($result);
								// die;
								//echo $query_Select;
								// $result = $myDB->rawQuery($query_Select);
								// $my_error = $myDB->getLastError();
								// $count = $myDB->count;
								$Punchin1 = $Punchin2 = '';
								if ($result->num_rows > 0 && $result) {
									foreach ($result as $key => $value) {
										if ($value['Type'] == 'Out') {
											$Punchin1 = $value['PunchTime'];
										}
										if ($value['Type'] == 'In' && $Punchin1 != '') {
											$Punchin2 = $value['PunchTime'];
											$time = strtotime($Punchin2) - strtotime($Punchin1) + $FinalTime;
											$FinalTime = $time;
											$Punchin1 = $Punchin2 = '';
										}
										$i++;
										if ($i == 1) {
											$in = $value['PunchTime'];
										}
										if ($i == $count) {
											$out = $value['PunchTime'];
											$totaltime = strtotime($out) - strtotime($in);
											$totaltime = gmdate("H:i:s", $totaltime);
											$time = gmdate("H:i:s", $FinalTime);
										}
									}
									$table .= '<tr>';
									$table .= '<td>' . $value['EmployeeID'] . '</td>';
									$table .= '<td>' . $EmployeeName . '</td>';
									$table .= '<td>' . $Process . '</td>';
									$table .= '<td>' . $value['DateOn'] . '</td>';
									$table .= '<td>' . $totaltime . '</td>';
									$table .= '<td>' . $time . '</td>';
									$table .= '<td>' . gmdate("H:i:s", strtotime($totaltime) - strtotime($time)) . '</td>';
									$table .= '</tr>';
								}
							}
						}

						$table .= '</tbody></table>';
						echo $table;
					}
					?>
				</div>
				<!--Reprot / Data Table End -->
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>