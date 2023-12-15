<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb_replica.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
if (isset($_SESSION)) {

	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	}
	if ($_SESSION["__user_type"] != 'ADMINISTRATOR' &&  $_SESSION["__user_logid"] != 'CE10091236') {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
//print_r($_SESSION);
$classvarr = "'.byID'";
$searchBy = $Year = $ReportType = '';
$chk_task = array();
?>



<script>
	$(document).ready(function() {
		//$('#txt_ED_joindate_to').datetimepicker({ format:'Y-m-d', timepicker:false});
		//$('#txt_ED_joindate_from').datetimepicker({ format:'Y-m-d', timepicker:false});
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
				},
				'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		$('.byDate').addClass('hidden');
		$('.byDept').addClass('hidden');
		$('.byProc').addClass('hidden');
		$('.byName').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Asset Data Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Asset Data Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--Form element model popup start-->


				<div class="input-field col s12 m12">

					<div class="input-field col s6 m6">

						<Select name="report_for" id="report_for1">
							<option value=''>Select Report for</option>
							<option value='Verified'>Verified Asset</option>
							<option value='Active'>Different Location Asset</option>
							<option value='InActive'>Not Working</option>
						</Select>
						<label for="report_for" class="active-drop-down active">Report Type</label>
					</div>

					<!--<div class="input-field col s6 m6" id='DOJHS' style="display: none;">-->

					<div class="input-field col s6 m6 8" id="reportdate_div" style="display: none;">

						<input type="text" id="txt_report_date" name="txt_report_date" />
						<label for="txt_report_date">Verified Date</label>

					</div>

					<!--	</div>
						-->

					<div class="input-field col s12 m12 right-align">

						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"><i class="fa fa-search"></i> Search</button>
					</div>

				</div>
				<?php
				if (isset($_POST['btn_view'])) {


					/*$Year =$_POST['txt_dateYear'];	
					$didplay='none';
					if( isset($_POST['report_for']) && $_POST['report_for']=='InActive')
					{
						$didplay='block';
					}*/


					//get A Day Back for Report
					$date = date('Y-m-d');
					$day_before = date('Y-m-d', strtotime($date . ' -1 day'));

					if (isset($_POST['report_for'])) {
						$ReportType = $_POST['report_for'];
					}


					//If Selected Report For Verified.
					if ($ReportType == 'Verified') {
						$ReportDate = $_POST['txt_report_date'];

						$myDB = new MysqliDb();
						$qq = "SELECT  ac.EmployeeID, ac.empImage, ac.assetImgComplete, ac.home_img, ac.emp_signature_img, ac.asset_lat, ac.asset_lng, ac.verified_By, ac.verifiedDate,  ai.assetName, ai.assetImg, ai.isCollected, ai.isDamaged, ai.assetRemark, ai.asset_flag, ai.assignedDate, t4.EmployeeName,t3.client_name, process,sub_process,mobile,altmobile,address, address_p FROM ems.asset_coordinates ac left join asset_info ai on ai.EmployeeID = ac.EmployeeID left join employee_map t1 on  ai.EmployeeID = t1.EmployeeID left join new_client_master t2 on t1.cm_id = t2.cm_id left join client_master t3 on t2.client_name=t3.client_id left join personal_details t4 on t1.EmployeeID = t4.EmployeeID left join contact_details t5 on t1.EmployeeID=t5.EmployeeID left join address_details t6 on t1.EmployeeID=t6.EmployeeID where cast(ac.createdDate as date) = '" . $ReportDate . "' and cast(ac.verifiedDate as date) =cast(ai.verifyDate as date) ;";
						$chk_task = $myDB->rawQuery($qq);
					} else if ($ReportType == 'Active') { //If Report Type Is Diffrent Location


						$myDB = new MysqliDb();
						/*$empListQResult = $myDB->rawQuery("select distinct t1.EmployeeID,( ACOS( COS( RADIANS( t2.lat  ) ) * COS( RADIANS(t1.asset_lat ) ) * COS( RADIANS( t1.asset_lng ) - RADIANS( t2.lng ) ) + SIN( RADIANS( t2.lat  ) )* SIN( RADIANS( t1.asset_lat ) )) * 6371) AS distance_in_km  from ((select * from asset_coordinates )t1  left join (select * from login_lat_lng  where  cast(login_lat_lng.created_on as date) = '2021-01-09'  )t2 on t1.EmployeeID = t2.EmployeeID ) HAVING distance_in_km > 1");*/

						$empListQResult = $myDB->rawQuery("select distinct t1.EmployeeID,( ACOS( COS( RADIANS( t2.lat  ) ) * COS( RADIANS(t1.asset_lat ) ) * COS( RADIANS( t1.asset_lng ) - RADIANS( t2.lng ) ) + SIN( RADIANS( t2.lat  ) )* SIN( RADIANS( t1.asset_lat ) )) * 6371) AS distance_in_km  from ((select * from asset_coordinates )t1  left join (select * from login_lat_lng  where  cast(login_lat_lng.created_on as date) = '" . $day_before . "')t2 on t1.EmployeeID = t2.EmployeeID ) HAVING distance_in_km > 1");

						if (empty($myDB->getLastError()) && count($empListQResult) > 0) {

							$empList = array();

							//Ceating Employee ID List.
							foreach ($empListQResult as $empId) {
								$empList[] = $empId['EmployeeID'];
							}

							//Creading String from List to Use in Querry
							$EmplListString = implode("','", $empList);

							if (!empty($EmplListString)) {
								$myDB = new MysqliDb();
								$qq = "select t1.EmployeeID,t4.EmployeeName,t3.client_name, process,sub_process,mobile,altmobile,address, address_p from employee_map t1 join new_client_master t2 on t1.cm_id = t2.cm_id join client_master t3 on t2.client_name=t3.client_id join personal_details t4 on t1.EmployeeID = t4.EmployeeID left join contact_details t5 on t1.EmployeeID=t5.EmployeeID left join address_details t6 on t1.EmployeeID=t6.EmployeeID where t1.EmployeeID in ('" . $EmplListString . "')";
								$chk_task = $myDB->rawQuery($qq);
							}
						}
					} elseif ($ReportType == 'InActive') {




						//If Report Type Is Not Working
						$myDB = new MysqliDb();
						/*$empListQResult = $myDB->rawQuery("select t1.EmployeeID  from ((select * from roster_temp as r  where  r.work_from = 'WFH' and r.DateOn = '2020-11-09')t1  inner join (select * from calc_atnd_master as c where  c.Month ='11' and c.Year = '2020'  and  c.d10 = 'A' )t2 on t1.EmployeeID = t2.EmployeeID )");*/
						/*$empListQResult = $myDB->rawQuery("select t1.EmployeeID  from ((select * from roster_temp as r  where  r.work_from = 'WFH' and r.DateOn = '2020-11-09')t1  inner join (select * from calc_atnd_master as c where  c.Month ='11' and c.Year = '2020'  and  c.d10 = 'A' )t2 on t1.EmployeeID = t2.EmployeeID inner join (select * from asset_info where asset_info.asset_flag = 0 )t3 on t1.EmployeeID = t3.EmployeeID )");*/
						$Month = date('m', strtotime($date . ' -1 day'));
						$Year = date('Y', strtotime($date . ' -1 day'));
						$day = date('j', strtotime($date . ' -1 day'));

						$empListQResult = $myDB->rawQuery("select t1.EmployeeID  from ((select * from roster_temp as r  where  r.work_from = 'WFH' and r.DateOn = '" . $day_before . "')t1  inner join (select * from calc_atnd_master as c where  c.Month ='" . $Month . "' and c.Year = '" . $Year . "'  and  c.d" . $day . " = 'A' )t2 on t1.EmployeeID = t2.EmployeeID inner join (select * from asset_info where asset_info.asset_flag = 0 )t3 on t1.EmployeeID = t3.EmployeeID )");

						if (empty($myDB->getLastError()) && count($empListQResult) > 0) {

							$empList = array();


							//Ceating Employee ID List.
							foreach ($empListQResult as $empId) {
								$empList[] = $empId['EmployeeID'];
							}

							//Creading String from List to Use in Querry
							$EmplListString = implode("','", $empList);

							if (!empty($EmplListString)) {
								$myDB = new MysqliDb();
								$qq = "select t1.EmployeeID,t4.EmployeeName,t3.client_name, process,sub_process,mobile,altmobile,address, address_p from employee_map t1 join new_client_master t2 on t1.cm_id = t2.cm_id join client_master t3 on t2.client_name=t3.client_id join personal_details t4 on t1.EmployeeID = t4.EmployeeID left join contact_details t5 on t1.EmployeeID=t5.EmployeeID left join address_details t6 on t1.EmployeeID=t6.EmployeeID where t1.EmployeeID in ('" . $EmplListString . "')";
								$chk_task = $myDB->rawQuery($qq);
							}
						}
					}


					if (count($chk_task) > 0 && $chk_task) {
				?>
						<div id="pnlTable">
							<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">

								<thead>
									<tr>
										<th>S No</th>
										<th>Employee ID</th>
										<th>Employee Name</th>
										<th>Client</th>
										<th>Process</th>
										<th>Sub Process</th>
										<th>Mobile</th>
										<th>Alt Mobile</th>
										<th>Current Address</th>
										<th>Permanent Address</th>
										<?php
										if ($ReportType == 'Verified') {
											echo '<th>Employee Photo</th>';
											echo '<th>Home Photo</th>';
											echo '<th>Complete Asset Photo</th>';
											echo '<th>Signature Photo</th>';
											echo '<th>Asset Name</th>';
											echo '<th>Asset Photo</th>';
											echo '<th>Asset Latitude</th>';
											echo '<th>Asset Longitude</th>';
											echo '<th>Verified By</th>';
											echo '<th>Asset Damaged</th>';
											echo '<th>Asset Collected</th>';
											echo '<th>Asset Remark</th>';
											echo '<th>Asset Assigned Date</th>';
											echo '<th>Verified Date</th>';
										} ?>


									</tr>
								</thead>
								<tbody>
									<?php

									$i = 1;
									foreach ($chk_task as $key => $value) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $value['EmployeeID'] . '</td>';
										echo '<td>' . $value['EmployeeName'] . '</td>';
										echo '<td>' . $value['client_name'] . '</td>';
										echo '<td>' . $value['process'] . '</td>';
										echo '<td>' . $value['sub_process'] . '</td>';
										echo '<td>' . $value['mobile'] . '</td>';
										echo '<td>' . $value['altmobile'] . '</td>';
										echo '<td>' . $value['address'] . '</td>';
										echo '<td>' . $value['address_p'] . '</td>';
										if ($ReportType == 'Verified') {



											echo '<td><a href="https://ems.cogentlab.com/erpm/assetSingle/' . $value['empImage'] . '" target="_blank">View Image</a></td>';
											echo '<td><a href="https://ems.cogentlab.com/erpm/assetSingle/' . $value['home_img'] . '" target="_blank">View Image</a></td>';
											echo '<td><a href="https://ems.cogentlab.com/erpm/assetSingle/' . $value['assetImgComplete'] . '" target="_blank">View Image</a></td>';
											echo '<td><a href="https://ems.cogentlab.com/erpm/assetSingle/' . $value['emp_signature_img'] . '" target="_blank">View Image</a></td>';
											echo '<td>' . $value['assetName'] . '</td>';
											echo '<td><a href="https://ems.cogentlab.com/erpm/assetSingle/' . $value['assetImg'] . '" target="_blank">View Image</a></td>';
											echo '<td>' . $value['asset_lat'] . '</td>';
											echo '<td>' . $value['asset_lng'] . '</td>';
											echo '<td>' . $value['verified_By'] . '</td>';
											echo '<td>' . $value['isDamaged'] . '</td>';
											echo '<td>' . $value['isCollected'] . '</td>';
											echo '<td>' . $value['assetRemark'] . '</td>';
											echo '<td>' . $value['assignedDate'] . '</td>';
											echo '<td>' . $value['verifiedDate'] . '</td>';
										}

										echo '</tr>';
										$i++;
									}
									?>
								</tbody>
							</table>
						</div>

				<?php	} else {
						if ($ReportType != "") {
							//$alert_msg='Data not found.';
							echo "<script>$(function(){ toastr.error('Data not found.'); }); </script>";
						}
					}
				}
				?>
				<!--Form container End -->
			</div>
			<!--Sub Main Div for all Page End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>


<!--Addet Date Picker For Report Date-->
<link rel="stylesheet" href="<?php echo STYLE . 'jquery.datetimepicker.css'; ?>" />
<script src="<?php echo SCRIPT . 'jquery.datetimepicker.full.min.js'; ?>"></script>
<script>
	$('#txt_report_date').datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		//   minDate: '-3M',
		maxDate: '+1D',

	});
</script>
<script>
	$('#report_for1').change(function() {
		//console.log('Callled');
		var report_for = $(this).val();
		if (report_for == 'Verified') {
			$('#reportdate_div').css("display", "block");
			$('#txt_report_date').prop('required', true);
		} else {
			$('#reportdate_div').css("display", "none");
			$('#txt_report_date').prop('required', false);
		}
	});
	$(document).ready(function() {



		$('#btn_view').click(function() {
			validate = 0;
			var report_for = $('#report_for').val();
			if (report_for == "") {
				validate = 1;
				alert_msg = 'Please select status.';


			}


			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}
		});
	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>