<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$query = 'select id,location from location_master';
$location_array = array();
$result = $myDB->query($query);
foreach ($result as $lval) {
	$location_array[$lval['id']] = $lval['location'];
}
$clean_u_logid = clean($_SESSION['__user_logid']);
if (!isset($clean_u_logid)) {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
}
$ahloginid = "";
$ohloginid = '';
$reporttsto = '';
$where = "";
$clean_status_oh = clean($_SESSION['__status_oh']);
if (isset($clean_status_oh) && $clean_status_oh == $clean_u_logid) {
	$ohloginid = $clean_status_oh;
	$whereoh = " ncm.oh='" . $ohloginid . "' ";
}
$clean_status_ah = clean($_SESSION['__status_ah']);
if (isset($clean_status_ah) && $clean_status_ah == $clean_u_logid) {
	$ahloginid = $clean_status_ah;
	$whereah = "  ncm.account_head='" . $ahloginid . "' ";
}
if (isset($clean_u_logid) && $clean_u_logid != "") {
	//echo "select EmployeeID from status_table where ReportTo='".$clean_u_logid."' ";
	$select_emp = "select EmployeeID from status_table where ReportTo=?";
	$selectQury = $conn->prepare($select_emp);
	$selectQury->bind_param("s", $clean_u_logid);
	$selectQury->execute();
	$result = $selectQury->get_result();
	$resultrrt = $result->fetch_row();
	if ($result->num_rows > 0) {
		if (isset($resultrrt[0]) && $resultrrt[0] != "") {
			$reporttsto = $clean_u_logid;
		}
	}
}
$clean_u_type = clean($_SESSION['__user_type']);
if (isset($clean_u_type) && $clean_u_type == 'ADMINISTRATOR' || $clean_u_type == 'CENTRAL MIS') {
	$condition = '';
} else {
	$condition = " where  (ncm.oh='" . $ohloginid . "' ||  ncm.account_head='" . $ahloginid . "' || st.ReportTo='" . $reporttsto . "')";
}

$Query = 'select id,substatus from ryg_substatus_master;';
$myDB = new MysqliDb();
$remark_array = array();
$result = $myDB->query($Query);
foreach ($result as $lval) {
	$remark_array[$lval['id']] = $lval['substatus'];
}

$msg = $searchBy = $empid = '';
$classvarr = "'.byID'";
$Month = $date_To = date('m', time());
$Year = $Year2 = date('Y', time());

if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
	$clean_newyear = cleanUserInput($_POST['newYear']);
	if (isset($clean_newyear)) {
		$Year = cleanUserInput($_POST['newYear']);
		$Month = cleanUserInput($_POST['newMonth']);
	}
}

$sql = " SELECT allEmp.EmployeeID,allEmp.account_head,allEmp.EmployeeName ,allEmp.location,allEmp.client_name,allEmp.process,allEmp.sub_process,ahstatus,ahsubstatus,ahremark,ahcreated_on,rstatus,rsubstatus,rremark,rcreated_on,ohstatus,ohsubstatus,ohsubstatus,ohremark,ohcreated_on,res.ResEmp,
 case when allEmp.emp_status='Active' then 'Active' when res.ResEmp is not null then 'On Notice' when allEmp.emp_status='InActive' then 'InActive' end as Status, case when allEmp.emp_status='InActive' then ifnull(emp.disposition, 'NA') else 'NA'  end as Remarks 
 from (SELECT distinct(ep.EmployeeID),ep.emp_status,ncm.account_head,pd.EmployeeName,pd.location,cm.client_name,ncm.`process`,ncm.`sub_process` from 
 employee_map ep inner join personal_details pd on ep.EmployeeID=pd.EmployeeID inner join new_client_master ncm on ncm.cm_id=ep.cm_id inner Join client_master cm on cm.client_id=ncm.client_name inner join status_table st on st.EmployeeID=ep.EmployeeID $condition ) allEmp left join ( select EmployeeID,ryg_status as ahstatus ,ryg_substatus as ahsubstatus,ryg_remark as ahremark ,created_on as ahcreated_on from ryg_ah where Month(created_on)=? and YEAR(created_on)=? ) ryg on allEmp.EmployeeID=ryg.EmployeeID left JOIN ( select EmployeeID,ryg_status as rstatus,ryg_substatus as rsubstatus,ryg_remark as rremark,created_on as rcreated_on from ryg_reportto where Month(created_on)=? and YEAR(created_on)=?) reportto on allEmp.EmployeeID=reportto.EmployeeID left JOIN ( select EmployeeID,ryg_status as ohstatus,ryg_substatus as ohsubstatus,ryg_remark as ohremark,created_on as ohcreated_on from ryg_oh where Month(created_on)=? and YEAR(created_on)=? ) rygoh on allEmp.EmployeeID=rygoh.EmployeeID left join (select EmployeeID,dol,rsnofleaving, disposition,createdon from exit_emp order by id desc) emp on emp.EmployeeID=allEmp.EmployeeID
 left join (select EmployeeID as 'ResEmp' from resign_details where (cast(now() as date) between nt_start and nt_end) and (revoke_accept is null or revoke_accept =1)) res on res.ResEmp =allEmp.EmployeeID  where allEmp.emp_status='Active' or ( Month(emp.createdon)=? and YEAR(emp.createdon)=? )  ";

?>
<link rel="stylesheet" href="../Style/ryg_style.css">
<style>
	table.dataTable.row-border tbody td {
		white-space: normal !important;
		/*white-space: inherit !important;*/
	}
</style>
<script>
	$(document).ready(function() {
		/*$('#newMonth').datetimepicker({
			timepicker:false,
			format:'Y-m'
		});*/

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
			}, 'pageLength'],
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
	<span id="PageTittle_span" class="hidden">RYG Status Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>RYG Status Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s3 m3">
						<?php
						$myDB = new MysqliDb();
						$month_result = $myDB->query('SELECT month, val FROM tbl_month');
						?>

						<!--<input type="text" name="newMonth"  id="newMonth" value="<?php echo $Month; ?>" autocomplete="off"/>-->
						<select name="newMonth" id="newMonth">
							<?php if (count($month_result) > 0) {
								foreach ($month_result as $val) {
							?>
									<option value="<?php echo $val['val']; ?>" <?php if ($Month == $val['val']) {
																					echo "selected";
																				} ?>><?php echo $val['month']; ?></option>

							<?php
								}
							} ?>
						</select>
					</div>
					<div class="input-field col s3 m3">

						<select name="newYear" id="newYear">
							<option value="<?php echo ($Year2 - 1); ?>" <?php if ($Year == ($Year2 - 1)) {
																			echo "selected";
																		}  ?>><?php echo ($Year2 - 1); ?></option>
							<option value="<?php echo $Year2; ?>" <?php if ($Year == ($Year2)) {
																		echo "selected";
																	}  ?>><?php echo $Year2; ?></option>
							<option value="<?php echo ($Year2 + 1); ?>" <?php if ($Year == ($Year2 + 1)) {
																			echo "selected";
																		}  ?>><?php echo ($Year2 + 1); ?></option>
						</select>
					</div>

					<div class="input-field col s3 m3">

						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>

				</div>
			</div>
			<div id="pnlTable">
				<?php
				$selectQu = $conn->prepare($sql);
				$selectQu->bind_param("iiiiiiii", $Month, $Year, $Month, $Year, $Month, $Year, $Month, $Year);
				$selectQu->execute();
				$result = $selectQu->get_result();
				// $result = $myDB->query($sql);
				?>
				<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
					<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						<thead>
							<tr>

								<th>EmployeeID</th>
								<th>EmployeeName</th>
								<th>Location</th>
								<th>Client</th>
								<th>Process</th>
								<th>Sub Process</th>

								<th>TL Status</th>
								<th>TL Sub-Status</th>
								<th>TL Remarks</th>
								<th>TL Status Datetime</th>

								<th>OH Status</th>
								<th>OH Sub-Status</th>
								<th>OH Remarks</th>
								<th>OH Status Datetime</th>

								<th>AH Status</th>
								<th>AH Sub-Status</th>
								<th>AH Remarks</th>
								<th>AH Status Datetime</th>
								<th>Employee Status</th>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if ($result->num_rows > 0) {
								foreach ($result as $key => $value) {
									$lcation_name = '';
									$EmployeeName = '';
									if ($value['EmployeeName'] != "") {
										$EmployeeName = ucwords(strtolower($value['EmployeeName']));
									}

									if ($value['location'] != "") {
										$lcation_name = $location_array[$value['location']];
									}
									$ahsubstatus = '';
									$rsubstatus = '';
									$ohsubstatus = '';
									if ($value['ahsubstatus'] != "") {
										$ahsubstatus = $remark_array[$value['ahsubstatus']];
									}
									if ($value['rsubstatus'] != "") {
										$rsubstatus = $remark_array[$value['rsubstatus']];
									}
									if ($value['ohsubstatus'] != "") {
										$ohsubstatus = $remark_array[$value['ohsubstatus']];
									}
									echo '<tr>';
									echo '<td  >' . $value['EmployeeID'] . '</td>';
									echo '<td>' . $EmployeeName . '</td>';
									echo '<td>' . $lcation_name . '</td>';
									echo '<td>' . $value['client_name'] . '</td>';
									echo '<td>' . $value['process'] . '</td>';
									echo '<td>' . $value['sub_process'] . '</td>';

									echo '<td  >' . $value['rstatus'] . '</td>';
									echo '<td  >' . $rsubstatus . '</td>';
									echo '<td  >' . $value['rremark'] . '</td>';
									echo '<td  >' . $value['rcreated_on'] . '</td>';

									echo '<td>' . $value['ohstatus'] . '</td>';
									echo '<td >' . $ohsubstatus . '</td>';
									echo '<td >' . $value['ohremark'] . '</td>';
									echo '<td  >' . $value['ohcreated_on'] . '</td>';

									echo '<td>' . $value['ahstatus'] . '</td>';
									echo '<td >' . $ahsubstatus . '</td>';
									echo '<td >' . $value['ahremark'] . '</td>';
									echo '<td  >' . $value['ahcreated_on'] . '</td>';
									echo '<td  >' . $value['Status'] . '</td>';
									echo '<td  >' . $value['Remarks'] . '</td>';
									echo '</tr>';
									$count++;
								}
							} else {
								echo "<tr><td colspan='6'>Data not found</td></tr>";
							}
							?>
						</tbody>
					</table>
				</div>


			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->

		<!--Content Div for all Page End -->
	</div>

	<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>