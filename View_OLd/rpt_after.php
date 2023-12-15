<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;

		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST)) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$rpt_type = cleanUserInput($_POST['txt_type']);
			}
		} else {
			$rpt_type = 'NA';
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
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
			}, 'pageLength'],
			"bProcessing": true,
			"bDestroy": true,
			"iDisplayLength": 25,
			"bAutoWidth": true,
			<?php
			if ($rpt_type == 'Contact Information Not Update' || $rpt_type == 'Exception Pending With' || $rpt_type == 'Proff Document Pending' || $rpt_type == 'Education Done/Not Done' || $rpt_type == 'Education Document Pending') {
			} else {
				echo '"sScrollX" : "100%",';
			}
			?> "bScrollCollapse": true,
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
	<span id="PageTittle_span" class="hidden">Reports</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Reports</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s10 m10" id="rpt_container">
					<select class="form-control" name="txt_type" id="txt_type">
						<option <?php echo ($rpt_type == 'NA') ? ' selected' : ''; ?> value="NA">---Select---</option>
						<option <?php echo ($rpt_type == 'Incomplete Info') ? ' selected' : ''; ?>>Incomplete Info</option>
						<option <?php echo ($rpt_type == 'Personal Information Not Update') ? ' selected' : ''; ?>>Personal Information Not Update</option>
						<option <?php echo ($rpt_type == 'Contact Information Not Update') ? ' selected' : ''; ?>>Contact Information Not Update</option>
						<option <?php echo ($rpt_type == 'Education Document Pending') ? ' selected' : ''; ?>>Education Document Pending</option>
						<option <?php echo ($rpt_type == 'Proff Document Pending') ? ' selected' : ''; ?>>Proff Document Pending</option>
						<option <?php echo ($rpt_type == 'Education Done/Not Done') ? ' selected' : ''; ?>>Education Done/Not Done</option>
					</select>
				</div>

				<div class="input-field col s2 m2">
					<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
				</div>

				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
					if ($rpt_type == 'Incomplete Info') {
						$myDB = new MysqliDb();
						$chk_task = $myDB->query('SELECT  t1.EmployeeID, EmployeeName,account_head,Process,sub_process, date_format(DOJ,"%d %M,%Y") as DOJ, date_format(DOB,"%d %M,%Y") as DOB, Gender, BloodGroup, FatherName, MotherName, MarriageStatus, Spouse, MarriageDate,ChildStatus, mobile, altmobile, em_contact, emailid, em_relation from view_incomplete_profile t1 left outer join view_incomplete_contactinfo t2 on t1.employeeid=t2.employeeid');
						$my_error = $myDB->getLastError();
						if (count($chk_task) > 0 && $chk_task) {
							$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
							$table .= '<th>EmployeeID</th>';
							$table .= '<th>EmployeeName</th>';
							$table .= '<th>FatherName</th>';
							$table .= '<th>MotherName</th>';
							$table .= '<th>Gender</th>';
							$table .= '<th>MarriageStatus</th>';
							$table .= '<th>Spouse</th>';
							$table .= '<th>MarriageDate</th>';
							$table .= '<th>Mobile</th>';
							$table .= '<th>Alt Mobile</th>';
							$table .= '<th>Emergency Contact</th>';
							$table .= '<th>EmailID</th>';
							$table .= '<th>Emergency Relation</th>';
							$table .= '<th>DOJ</th>';
							$table .= '<th>DOB</th>';
							$table .= '<th>BloodGroup</th>';
							$table .= '<th>Process</th>';
							$table .= '<th>Sub Process</th>';
							$table .= '<th>Account Head</th><thead><tbody>';


							foreach ($chk_task as $key => $value) {

								$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['EmployeeName'] . '</td>';
								$table .= '<td>' . $value['FatherName'] . '</td>';
								$table .= '<td>' . $value['MotherName'] . '</td>';
								$table .= '<td>' . $value['Gender'] . '</td>';
								$table .= '<td>' . $value['MarriageStatus'] . '</td>';
								$table .= '<td>' . $value['Spouse'] . '</td>';
								$table .= '<td>' . $value['MarriageDate'] . '</td>';
								$table .= '<td>' . $value['mobile'] . '</td>';
								$table .= '<td>' . $value['altmobile'] . '</td>';
								$table .= '<td>' . $value['em_contact'] . '</td>';
								$table .= '<td>' . $value['emailid'] . '</td>';
								$table .= '<td>' . $value['em_relation'] . '</td>';
								$table .= '<td>' . $value['DOJ'] . '</td>';
								$table .= '<td>' . $value['DOB'] . '</td>';
								$table .= '<td>' . $value['BloodGroup'] . '</td>';
								$table .= '<td>' . $value['Process'] . '</td>';
								$table .= '<td>' . $value['sub_process'] . '</td>';
								$table .= '<td>' . $value['account_head'] . '</td></tr>';
							}
							$table .= '</tbody></table></div>';
							echo $table;
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
						}
					} elseif ($rpt_type == 'Personal Information Not Update') {
						$myDB = new MysqliDb();
						$chk_task = $myDB->query("select personal_details.EmployeeID, EmployeeName,case when (FatherName='' or FatherName is null)  then 'Not Updated' else FatherName end as FatherName , case when (DOB='' or DOB is null) then 'Not Updated' 
						else date_format(DOB,'%d %M,%Y')  end as DOB , case when (MotherName='' or MotherName is null)  then 'Not Updated' else MotherName end as MotherName , case when (Gender='' or Gender is null)  then 'Not Updated' else Gender end as Gender , case when (BloodGroup='' or BloodGroup is null)  then 'Not Updated' else BloodGroup end as BloodGroup , case when (MarriageStatus ='' or MarriageStatus  is null) then 'Not Updated' else MarriageStatus end as MarriageStatus,case when MarriageStatus ='Yes' and (Spouse='' or Spouse is null)  then 'Not Updated' else Spouse end as Spouse , case when MarriageStatus ='Yes' and (MarriageDate='' or MarriageDate is null)  then 'Not Updated' else MarriageDate end as arriageDate ,  case when MarriageStatus ='Yes' and (ChildStatus='' or ChildStatus is null)  then  'Not Updated' else ChildStatus end as ChildStatus from  personal_details   inner join employee_map on personal_details.EmployeeID=employee_map.EmployeeID  WHERE (employee_map.emp_status='Active') and ((MarriageStatus ='' or MarriageStatus is null) or (MarriageStatus ='Yes' and (Spouse='' or Spouse is null)) or (FatherName='' or FatherName is null) or (DOB='' or DOB is null)  or (MotherName='' or MotherName is null) or  (Gender='' or Gender is null) or (BloodGroup='' or BloodGroup is null)  or (MarriageStatus ='Yes' and (MarriageDate='' or MarriageDate is null)) or (MarriageStatus ='Yes' and (ChildStatus='' or ChildStatus is null) ))");
						$my_error = $myDB->getLastError();
						if (count($chk_task) > 0 && $chk_task) {
							$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
							$table .= '<th>EmployeeID</th>';
							$table .= '<th>EmployeeName</th>';
							$table .= '<th>FatherName</th>';
							$table .= '<th>MotherName</th>';
							$table .= '<th>Gender</th>';
							$table .= '<th>MarriageStatus</th>';
							$table .= '<th>Spouse</th>';
							$table .= '<th>MarriageDate</th>';
							$table .= '<th>DOB</th>';
							$table .= '<th>BloodGroup</th><thead><tbody>';


							foreach ($chk_task as $key => $value) {

								$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['EmployeeName'] . '</td>';
								$table .= '<td>' . $value['FatherName'] . '</td>';
								$table .= '<td>' . $value['MotherName'] . '</td>';
								$table .= '<td>' . $value['Gender'] . '</td>';
								$table .= '<td>' . $value['MarriageStatus'] . '</td>';
								$table .= '<td>' . $value['Spouse'] . '</td>';
								$table .= '<td>' . $value['MarriageDate'] . '</td>';
								$table .= '<td>' . $value['DOB'] . '</td>';
								$table .= '<td>' . $value['BloodGroup'] . '</td></tr>';
							}
							$table .= '</tbody></table></div>';
							echo $table;
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
						}
					} elseif ($rpt_type == 'Contact Information Not Update') {
						$myDB = new MysqliDb();
						$chk_task = $myDB->query("select contact_details.EmployeeID, case when (mobile ='' or mobile is null) then 'Not Update' else mobile end as mobile,case when (altmobile ='' or altmobile is null) then 'Not Update' else altmobile end as altmobile,case when (em_contact ='' or em_contact is null) then 'Not Update' else em_contact end as em_contact,case when (emailid ='' or emailid is null) then 'Not Update' else emailid end as emailid,case when (em_relation ='' or em_relation is null) then 'Not Update' else em_relation end as em_relation from contact_details inner join employee_map on contact_details.EmployeeID=employee_map.EmployeeID WHERE (employee_map.emp_status='Active') and ((mobile ='' or mobile is null) or(altmobile ='' or altmobile is null) or(em_contact ='' or em_contact is null) or (emailid ='' or emailid is null) or (em_relation ='' or em_relation is null))");
						$my_error = $myDB->getLastError();
						if (count($chk_task) > 0 && $chk_task) {
							$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
							$table .= '<th>EmployeeID</th>';
							$table .= '<th>Mobile</th>';
							$table .= '<th>Alt Mobile</th>';
							$table .= '<th>Emergency Contact</th>';
							$table .= '<th>EmailID</th>';
							$table .= '<th>Emergency Relation</th><thead><tbody>';


							foreach ($chk_task as $key => $value) {

								$table .= '<tr><td>' . $value['contact_details']['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['mobile'] . '</td>';
								$table .= '<td>' . $value['altmobile'] . '</td>';
								$table .= '<td>' . $value['em_contact'] . '</td>';
								$table .= '<td>' . $value['emailid'] . '</td>';
								$table .= '<td>' . $value['em_relation'] . '</td></tr>';
							}
							$table .= '</tbody></table></div>';
							echo $table;
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
						}
					} elseif ($rpt_type == 'Proff Document Pending') {
						$myDB = new MysqliDb();

						$chk_task = $myDB->query("select distinct whole_details_peremp.EmployeeID,EmployeeName, designation, Process, sub_process, ReportTo from whole_details_peremp left outer join doc_details on doc_details.EmployeeID=whole_details_peremp.EmployeeID where doc_file is not null");


						//$chk_task=$myDB->query("select * from whole_details_peremp where EmployeeID not in (select distinct EmployeeID from doc_details where doc_file is not null)");
						$my_error = $myDB->getLastError();
						if (count($chk_task) > 0 && $chk_task) {
							$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
							$table .= '<th>EmployeeID</th>';
							$table .= '<th>EmployeeName</th>';
							$table .= '<th>Designation</th>';
							$table .= '<th>Process</th>';
							$table .= '<th>Sub Process</th>';
							$table .= '<th>Supervisor</th><thead><tbody>';


							foreach ($chk_task as $key => $value) {

								$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['EmployeeName'] . '</td>';
								$table .= '<td>' . $value['designation'] . '</td>';
								$table .= '<td>' . $value['Process'] . '</td>';
								$table .= '<td>' . $value['sub_process'] . '</td>';
								$table .= '<td>' . $value['ReportTo'] . '</td></tr>';
							}
							$table .= '</tbody></table></div>';
							echo $table;
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
						}
					} elseif ($rpt_type == 'Education Document Pending') {
						$myDB = new MysqliDb();
						$chk_task = $myDB->query("select * from whole_details_peremp where EmployeeID not in (select distinct EmployeeID from education_details where edu_file is not null)");
						$my_error = $myDB->getLastError();
						if (count($chk_task) > 0 && $chk_task) {
							$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
							$table .= '<th>EmployeeID</th>';
							$table .= '<th>EmployeeName</th>';
							$table .= '<th>Designation</th>';
							$table .= '<th>Process</th>';
							$table .= '<th>Sub Process</th>';
							$table .= '<th>Supervisor</th><thead><tbody>';


							foreach ($chk_task as $key => $value) {

								$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['EmployeeName'] . '</td>';
								$table .= '<td>' . $value['designation'] . '</td>';
								$table .= '<td>' . $value['Process'] . '</td>';
								$table .= '<td>' . $value['sub_process'] . '</td>';
								$table .= '<td>' . $value['ReportTo'] . '</td></tr>';
							}
							$table .= '</tbody></table></div>';
							echo $table;
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
						}
					} elseif ($rpt_type == 'Education Done/Not Done') {
						$myDB = new MysqliDb();
						$chk_task = $myDB->query(" select distinct  whole_details_peremp.EmployeeID , case when edu_level='Post Graduation' then 'Done' else 'Not Done' end as PG,case when edu_level='Graduation' then 'Done' else 'Not Done' end as Graduation,case when edu_level in ('Basic','Diploma') then 'Done' else 'Not Done' end as Basic from education_details left outer join whole_details_peremp on education_details.EmployeeID=whole_details_peremp.EmployeeID where whole_details_peremp.EmployeeID is not null");

						/*$chk_task=$myDB->query(" select distinct  EmployeeID , case when edu_level='Post Graduation' then 'Done' else 'Not Done' end as PG,case when edu_level='Graduation' then 'Done' else 'Not Done' end as Graduation,case when edu_level in ('Basic','Diploma') then 'Done' else 'Not Done' end as Basic from education_details");*/
						$my_error = $myDB->getLastError();
						if (count($chk_task) > 0 && $chk_task) {
							$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
							$table .= '<th>EmployeeID</th>';
							$table .= '<th>PG</th>';
							$table .= '<th>Graduation</th>';
							$table .= '<th>Basic</th><thead><tbody>';


							foreach ($chk_task as $key => $value) {

								$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
								$table .= '<td>' . $value['PG'] . '</td>';
								$table .= '<td>' . $value['Graduation'] . '</td>';
								$table .= '<td>' . $value['Basic'] . '</td></tr>';
							}
							$table .= '</tbody></table></div>';
							echo $table;
						} else {
							echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
						}
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
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