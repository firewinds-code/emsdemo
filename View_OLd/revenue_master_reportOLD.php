
<?php
// Server Config file
$currentYear =  date('Y');
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

if (isset($_SESSION)) {
	$clean_user_log_in = clean($_SESSION['__user_logid']);
	if (!isset($clean_user_log_in)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
// Global variable used in Page Cycle
$year='';
if (isset($_POST['send'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$year = cleanUserInput($_POST['year']);

		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$sqlConnect = "select l.location,cm.client_name,jan.loc_id, jan.new_location, jan.client_id, jan.process,jan.vh, jan.svh,jan.model,
		jan.mnth_year as jan_mnth_year,jan.current_rate as jan_current_rate,  jan.fte_forcast as jan_fte_forcast, jan.fte_plan as jan_fte_plan, jan.rp_plan as jan_rp_plan, jan.fte_commit as jan_fte_commit, jan.rp_commit as jan_rp_commit, jan.fte_actuals as jan_fte_actuals, jan.rp_actuals as jan_rp_actuals,
		feb.mnth_year as feb_mnth_year,feb.current_rate as feb_current_rate,  feb.fte_forcast as feb_fte_forcast, feb.fte_plan as feb_fte_plan, feb.rp_plan as feb_rp_plan, feb.fte_commit as feb_fte_commit, feb.rp_commit as feb_rp_commit, feb.fte_actuals as feb_fte_actuals, feb.rp_actuals as feb_rp_actuals,
		mar.mnth_year as mar_mnth_year,mar.current_rate as mar_current_rate,  mar.fte_forcast as mar_fte_forcast, mar.fte_plan as mar_fte_plan, mar.rp_plan as mar_rp_plan, mar.fte_commit as mar_fte_commit, mar.rp_commit as mar_rp_commit, mar.fte_actuals as mar_fte_actuals, mar.rp_actuals as mar_rp_actuals,
		apr.mnth_year as apr_mnth_year,apr.current_rate as apr_current_rate,  apr.fte_forcast as apr_fte_forcast, apr.fte_plan as apr_fte_plan, apr.rp_plan as apr_rp_plan, apr.fte_commit as apr_fte_commit, apr.rp_commit as apr_rp_commit, apr.fte_actuals as apr_fte_actuals, apr.rp_actuals as apr_rp_actuals,
		may.mnth_year as may_mnth_year,may.current_rate as may_current_rate,  may.fte_forcast as may_fte_forcast, may.fte_plan as may_fte_plan, may.rp_plan as may_rp_plan, may.fte_commit as may_fte_commit, may.rp_commit as may_rp_commit, may.fte_actuals as may_fte_actuals, may.rp_actuals as may_rp_actuals,
		jun.mnth_year as jun_mnth_year,jun.current_rate as jun_current_rate,  jun.fte_forcast as jun_fte_forcast, jun.fte_plan as jun_fte_plan, jun.rp_plan as jun_rp_plan, jun.fte_commit as jun_fte_commit, jun.rp_commit as jun_rp_commit, jun.fte_actuals as jun_fte_actuals, jun.rp_actuals as jun_rp_actuals,
		jul.mnth_year as jul_mnth_year,jul.current_rate as jul_current_rate,  jul.fte_forcast as jul_fte_forcast, jul.fte_plan as jul_fte_plan, jul.rp_plan as jul_rp_plan, jul.fte_commit as jul_fte_commit, jul.rp_commit as jul_rp_commit, jul.fte_actuals as jul_fte_actuals, jul.rp_actuals as jul_rp_actuals,
		aug.mnth_year as aug_mnth_year,aug.current_rate as aug_current_rate,  aug.fte_forcast as aug_fte_forcast, aug.fte_plan as aug_fte_plan, aug.rp_plan as aug_rp_plan, aug.fte_commit as aug_fte_commit, aug.rp_commit as aug_rp_commit, aug.fte_actuals as aug_fte_actuals, aug.rp_actuals as aug_rp_actuals,
		sep.mnth_year as sep_mnth_year,sep.current_rate as sep_current_rate,  sep.fte_forcast as sep_fte_forcast, sep.fte_plan as sep_fte_plan, sep.rp_plan as sep_rp_plan, sep.fte_commit as sep_fte_commit, sep.rp_commit as sep_rp_commit, sep.fte_actuals as sep_fte_actuals, sep.rp_actuals as sep_rp_actuals,
		oct.mnth_year as oct_mnth_year,oct.current_rate as oct_current_rate,  oct.fte_forcast as oct_fte_forcast, oct.fte_plan as oct_fte_plan, oct.rp_plan as oct_rp_plan, oct.fte_commit as oct_fte_commit, oct.rp_commit as oct_rp_commit, oct.fte_actuals as oct_fte_actuals, oct.rp_actuals as oct_rp_actuals,
		nov.mnth_year as nov_mnth_year,nov.current_rate as nov_current_rate,  nov.fte_forcast as nov_fte_forcast, nov.fte_plan as nov_fte_plan, nov.rp_plan as nov_rp_plan, nov.fte_commit as nov_fte_commit, nov.rp_commit as nov_rp_commit, nov.fte_actuals as nov_fte_actuals, nov.rp_actuals as nov_rp_actuals,
		dece.mnth_year as dece_mnth_year,dece.current_rate as dece_current_rate,  dece.fte_forcast as dece_fte_forcast, dece.fte_plan as dece_fte_plan, dece.rp_plan as dece_rp_plan, dece.fte_commit as dece_fte_commit, dece.rp_commit as dece_rp_commit, dece.fte_actuals as dece_fte_actuals, dece.rp_actuals as dece_rp_actuals
		
		from revenue_master_01 jan 
		left join revenue_master_02 feb on jan.loc_id=feb.loc_id and jan.client_id=feb.client_id and jan.process=feb.process and jan.model=feb.model and left(jan.mnth_year,4) = ? 
		left join revenue_master_03 mar on jan.loc_id=mar.loc_id and jan.client_id=mar.client_id and jan.process=mar.process and jan.model=mar.model and left(mar.mnth_year,4) = ? 
		left join revenue_master_04 apr on jan.loc_id=apr.loc_id and jan.client_id=apr.client_id and jan.process=apr.process and jan.model=apr.model and left(apr.mnth_year,4) = ? 
		left join revenue_master_05 may on jan.loc_id=may.loc_id and jan.client_id=may.client_id and jan.process=may.process and jan.model=may.model and left(may.mnth_year,4) = ? 
		left join revenue_master_06 jun on jan.loc_id=jun.loc_id and jan.client_id=jun.client_id and jan.process=jun.process and jan.model=jun.model and left(jun.mnth_year,4) = ? 
		left join revenue_master_07 jul on jan.loc_id=jul.loc_id and jan.client_id=jul.client_id and jan.process=jul.process and jan.model=jul.model and left(jul.mnth_year,4) = ? 
		left join revenue_master_08 aug on jan.loc_id=aug.loc_id and jan.client_id=aug.client_id and jan.process=aug.process and jan.model=aug.model and left(aug.mnth_year,4) = ? 
		left join revenue_master_09 sep on jan.loc_id=sep.loc_id and jan.client_id=sep.client_id and jan.process=sep.process and jan.model=sep.model and left(sep.mnth_year,4) = ? 
		left join revenue_master_10 oct on jan.loc_id=oct.loc_id and jan.client_id=oct.client_id and jan.process=oct.process and jan.model=oct.model and left(oct.mnth_year,4) = ? 
		left join revenue_master_11 nov on jan.loc_id=nov.loc_id and jan.client_id=nov.client_id and jan.process=nov.process and jan.model=nov.model and left(nov.mnth_year,4) = ? 
		left join revenue_master_12 dece on jan.loc_id=dece.loc_id and jan.client_id=dece.client_id and jan.process=dece.process and jan.model=dece.model and left(dece.mnth_year,4) = ? left join location_master as l on l.id=jan.loc_id left join client_master as cm on cm.client_id = jan.client_id where left(jan.mnth_year,4) = ?";
		$selectQury = $conn->prepare($sqlConnect);
		$selectQury->bind_param("ssssssssssss", $year,$year, $year,$year, $year,$year, $year,$year, $year,$year,$year,$year);
		$selectQury->execute();
		$result = $selectQury->get_result();
		$numRow = $result->num_rows;
	}
}

?>


<script>
	$(document).ready(function() {
		$('#from_date').datetimepicker({
			format: 'Y-m',
			timepicker: false
		});
		$('#to_date').datetimepicker({
			format: 'Y-m',
			timepicker: false
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			"scrollX":true,
			scrollCollapse: true,
			buttons: [{
				extend: 'excel',
				text: 'EXCEL',
				extension: '.xlsx',
				exportOptions: {
					modifier: {
						page: 'all'
					}
				},
				title: 'Revenue_Report'
			}, 'pageLength']
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

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Revenue Master Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Revenue Master Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div class="input-field col s12 m12">
					<form method="POST" action="#">
						<?php $_SESSION["token"] = csrfToken(); ?>
						<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

						<div class="input-field col s4 m4">						
							<select name="year" id="year" class="form-control" required	>
								<option value="">Year</option>
								<?php 
								for($i=2018;$i<$currentYear+5;$i++){
									if($i == $year){
										echo '<option value="' . $i . '" selected >' . $i . '</option>';
									}else{
										echo '<option value="' . $i . '"  >' . $i . '</option>';
									}
									
								}	 
								 ?>
							<label for="year" class="Active dropdown-active active">Year</label>
						</div>					
						<div class="input-field col s4 m4">
							<input type="submit" value="Search" name="send" id="send" class="btn waves-effect waves-green" />
						</div>
					</form>
				</div>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
						/* if ($numRow >0 && isset($clean_from_date)) {  */?>
						<div class="">
							<table id="myTable" class="data dataTable  no-footer row-border" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th rowspan="2" style="vertical-align: middle; border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Sr. No.</th>
										<th rowspan="2" style="vertical-align: middle; border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Location</th>
										<th rowspan="2" style="vertical-align: middle; border-left: 1px solid #ddd;border-top: 1px solid #ddd;">New Location</th>
										<th rowspan="2" style="vertical-align: middle; border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Client</th>
										<th rowspan="2" style="vertical-align: middle; border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Process</th>
										<th rowspan="2" style="vertical-align: middle; border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Model</th>
										<th rowspan="2" style="vertical-align: middle; border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Vertical Head</th>
										<th rowspan="2" style="vertical-align: middle; border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Sub Vertical Head</th>
										<th colspan="12">January</th>
										<th colspan="12">February </th>
										<th colspan="12">March </th>
										<th colspan="12">April </th>
										<th colspan="12">May</th>
										<th colspan="12">June</th>
										<th colspan="12">July</th>
										<th colspan="12">August</th>
										<th colspan="12">September</th>
										<th colspan="12">October</th>
										<th colspan="12">November</th>
										<th colspan="12">December</th>
									</tr>
									<tr>
									<!-- 	<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Sr. No.</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Location</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">New Location</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Client</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Process</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Model</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Vertical Head</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Sub Vertical Head</th> -->
										<!-- Jan -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;" >Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- Jan -->
										<!-- Feb -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- Feb -->
										<!-- March -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- March -->
										<!-- April -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- April -->
										<!-- May -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- May -->
										<!-- June -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- June -->
										<!-- July -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- July -->
										<!-- Aug -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- Aug -->
										<!-- Sept -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- Sept -->
										<!-- Oct -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- Oct -->
										<!-- Nov -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- Nov -->
										<!-- Dec -->
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Forecast</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Forecast Revenue</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Plan</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Commit</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">FTE Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">R&P Actuals</th>
										<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>
										<!-- Dec -->										
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;

									foreach ($result as $key => $value) {
										echo '<tr><td>' . $i++ . '</td>
										<td>' . $value["location"] . '</td>
										<td>' . $value["new_location"] . '</td>			
										<td>' .  $value["client_name"] . '</td>
										<td>' . $value["process"] . '</td>
										<td >' . $value["model"] . '</td>
										<td>' . $value["vh"] . '</td>
										<td>' . $value["svh"] . '</td>';

										/* Jan Data */
											echo '<td>' . $value["jan_current_rate"] . '</td>										
											<td>' . $value["jan_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['jan_fte_forcast'] * $value['jan_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['jan_fte_forcast'] * $value['jan_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['jan_fte_forcast'] * $value['jan_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["jan_fte_plan"] . '</td>
											<td>' . $value["jan_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['jan_fte_plan'] * $value['jan_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['jan_fte_plan'] * $value['jan_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['jan_fte_plan'] * $value['jan_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["jan_fte_commit"] . '</td>
											<td>' . $value["jan_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['jan_fte_commit'] * $value['jan_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["jan_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['jan_fte_commit'] * $value['jan_fte_forcast'])/100000)+$value["jan_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['jan_fte_commit'] * $value['jan_fte_forcast'])*90/100)/100000)+$value["jan_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['jan_fte_actuals'].'</td>
											<td>' . $value["jan_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['jan_fte_actuals'] * $value['jan_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["jan_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['jan_fte_actuals'] * $value['jan_fte_forcast'])/100000)+$value["jan_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['jan_fte_actuals'] * $value['jan_fte_forcast'])*90/100)/100000)+$value["jan_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
										/* Jan Data */
										/* Feb Data */
										
											echo '<td>' . $value["feb_current_rate"] . '</td>										
											<td>' . $value["feb_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['feb_fte_forcast'] * $value['feb_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['feb_fte_forcast'] * $value['feb_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['feb_fte_forcast'] * $value['feb_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["feb_fte_plan"] . '</td>
											<td>' . $value["feb_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['feb_fte_plan'] * $value['feb_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['feb_fte_plan'] * $value['feb_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['feb_fte_plan'] * $value['feb_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["feb_fte_commit"] . '</td>
											<td>' . $value["feb_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['feb_fte_commit'] * $value['feb_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["feb_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['feb_fte_commit'] * $value['feb_fte_forcast'])/100000)+$value["feb_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['feb_fte_commit'] * $value['feb_fte_forcast'])*90/100)/100000)+$value["feb_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['feb_fte_actuals'].'</td>
											<td>' . $value["feb_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['feb_fte_actuals'] * $value['feb_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["feb_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['feb_fte_actuals'] * $value['feb_fte_forcast'])/100000)+$value["feb_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['feb_fte_actuals'] * $value['feb_fte_forcast'])*90/100)/100000)+$value["feb_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
						
										/* Feb Data */
										/* March Data */
											
											echo '<td>' . $value["mar_current_rate"] . '</td>										
											<td>' . $value["mar_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['mar_fte_forcast'] * $value['mar_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['mar_fte_forcast'] * $value['mar_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['mar_fte_forcast'] * $value['mar_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["mar_fte_plan"] . '</td>
											<td>' . $value["mar_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['mar_fte_plan'] * $value['mar_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['mar_fte_plan'] * $value['mar_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['mar_fte_plan'] * $value['mar_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["mar_fte_commit"] . '</td>
											<td>' . $value["mar_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['mar_fte_commit'] * $value['mar_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["mar_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['mar_fte_commit'] * $value['mar_fte_forcast'])/100000)+$value["mar_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['mar_fte_commit'] * $value['mar_fte_forcast'])*90/100)/100000)+$value["mar_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['mar_fte_actuals'].'</td>
											<td>' . $value["mar_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['mar_fte_actuals'] * $value['mar_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["mar_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['mar_fte_actuals'] * $value['mar_fte_forcast'])/100000)+$value["mar_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['mar_fte_actuals'] * $value['mar_fte_forcast'])*90/100)/100000)+$value["mar_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
						
										/* March Data */
										/* April Data */
										
											echo '<td>' . $value["apr_current_rate"] . '</td>										
											<td>' . $value["apr_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['apr_fte_forcast'] * $value['apr_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['apr_fte_forcast'] * $value['apr_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['apr_fte_forcast'] * $value['apr_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["apr_fte_plan"] . '</td>
											<td>' . $value["apr_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['apr_fte_plan'] * $value['apr_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['apr_fte_plan'] * $value['apr_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['apr_fte_plan'] * $value['apr_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["apr_fte_commit"] . '</td>
											<td>' . $value["apr_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['apr_fte_commit'] * $value['apr_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["apr_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['apr_fte_commit'] * $value['apr_fte_forcast'])/100000)+$value["apr_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['apr_fte_commit'] * $value['apr_fte_forcast'])*90/100)/100000)+$value["apr_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['apr_fte_actuals'].'</td>
											<td>' . $value["apr_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['apr_fte_actuals'] * $value['apr_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["apr_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['apr_fte_actuals'] * $value['apr_fte_forcast'])/100000)+$value["apr_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['apr_fte_actuals'] * $value['apr_fte_forcast'])*90/100)/100000)+$value["apr_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
							
										/* April Data */
										/* May Data */
										
											echo '<td>' . $value["may_current_rate"] . '</td>										
											<td>' . $value["may_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['may_fte_forcast'] * $value['may_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['may_fte_forcast'] * $value['may_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['may_fte_forcast'] * $value['may_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["may_fte_plan"] . '</td>
											<td>' . $value["may_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['may_fte_plan'] * $value['may_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['may_fte_plan'] * $value['may_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['may_fte_plan'] * $value['may_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["may_fte_commit"] . '</td>
											<td>' . $value["may_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['may_fte_commit'] * $value['may_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["may_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['may_fte_commit'] * $value['may_fte_forcast'])/100000)+$value["may_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['may_fte_commit'] * $value['may_fte_forcast'])*90/100)/100000)+$value["may_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['may_fte_actuals'].'</td>
											<td>' . $value["may_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['may_fte_actuals'] * $value['may_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["may_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['may_fte_actuals'] * $value['may_fte_forcast'])/100000)+$value["may_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['may_fte_actuals'] * $value['may_fte_forcast'])*90/100)/100000)+$value["may_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
							
										/* May Data */
										/* June Data */
										
											echo '<td>' . $value["jun_current_rate"] . '</td>										
											<td>' . $value["jun_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['jun_fte_forcast'] * $value['jun_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['jun_fte_forcast'] * $value['jun_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['jun_fte_forcast'] * $value['jun_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["jun_fte_plan"] . '</td>
											<td>' . $value["jun_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['jun_fte_plan'] * $value['jun_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['jun_fte_plan'] * $value['jun_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['jun_fte_plan'] * $value['jun_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["jun_fte_commit"] . '</td>
											<td>' . $value["jun_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['jun_fte_commit'] * $value['jun_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["jun_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['jun_fte_commit'] * $value['jun_fte_forcast'])/100000)+$value["jun_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['jun_fte_commit'] * $value['jun_fte_forcast'])*90/100)/100000)+$value["jun_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['jun_fte_actuals'].'</td>
											<td>' . $value["jun_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['jun_fte_actuals'] * $value['jun_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["jun_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['jun_fte_actuals'] * $value['jun_fte_forcast'])/100000)+$value["jun_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['jun_fte_actuals'] * $value['jun_fte_forcast'])*90/100)/100000)+$value["jun_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
							
										/* June Data */
										/* July Data */
										
											echo '<td>' . $value["jul_current_rate"] . '</td>										
											<td>' . $value["jul_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['jul_fte_forcast'] * $value['jul_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['jul_fte_forcast'] * $value['jul_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['jul_fte_forcast'] * $value['jul_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["jul_fte_plan"] . '</td>
											<td>' . $value["jul_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['jul_fte_plan'] * $value['jul_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['jul_fte_plan'] * $value['jul_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['jul_fte_plan'] * $value['jul_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["jul_fte_commit"] . '</td>
											<td>' . $value["jul_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['jul_fte_commit'] * $value['jul_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["jul_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['jul_fte_commit'] * $value['jul_fte_forcast'])/100000)+$value["jul_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['jul_fte_commit'] * $value['jul_fte_forcast'])*90/100)/100000)+$value["jul_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['jul_fte_actuals'].'</td>
											<td>' . $value["jul_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['jul_fte_actuals'] * $value['jul_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["jul_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['jul_fte_actuals'] * $value['jul_fte_forcast'])/100000)+$value["jul_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['jul_fte_actuals'] * $value['jul_fte_forcast'])*90/100)/100000)+$value["jul_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
							
										/* July Data */
										/* Aug Data */
										
											echo '<td>' . $value["aug_current_rate"] . '</td>										
											<td>' . $value["aug_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['aug_fte_forcast'] * $value['aug_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['aug_fte_forcast'] * $value['aug_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['aug_fte_forcast'] * $value['aug_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["aug_fte_plan"] . '</td>
											<td>' . $value["aug_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['aug_fte_plan'] * $value['aug_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['aug_fte_plan'] * $value['aug_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['aug_fte_plan'] * $value['aug_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["aug_fte_commit"] . '</td>
											<td>' . $value["aug_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['aug_fte_commit'] * $value['aug_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["aug_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['aug_fte_commit'] * $value['aug_fte_forcast'])/100000)+$value["aug_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['aug_fte_commit'] * $value['aug_fte_forcast'])*90/100)/100000)+$value["aug_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['aug_fte_actuals'].'</td>
											<td>' . $value["aug_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['aug_fte_actuals'] * $value['aug_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["aug_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['aug_fte_actuals'] * $value['aug_fte_forcast'])/100000)+$value["aug_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['aug_fte_actuals'] * $value['aug_fte_forcast'])*90/100)/100000)+$value["aug_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
							
										/* Aug Data */
										/* Septemeber Data */
										
											echo '<td>' . $value["sep_current_rate"] . '</td>										
											<td>' . $value["sep_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['sep_fte_forcast'] * $value['sep_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['sep_fte_forcast'] * $value['sep_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['sep_fte_forcast'] * $value['sep_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["sep_fte_plan"] . '</td>
											<td>' . $value["sep_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['sep_fte_plan'] * $value['sep_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['sep_fte_plan'] * $value['sep_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['sep_fte_plan'] * $value['sep_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["sep_fte_commit"] . '</td>
											<td>' . $value["sep_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['sep_fte_commit'] * $value['sep_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["sep_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['sep_fte_commit'] * $value['sep_fte_forcast'])/100000)+$value["sep_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['sep_fte_commit'] * $value['sep_fte_forcast'])*90/100)/100000)+$value["sep_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['sep_fte_actuals'].'</td>
											<td>' . $value["sep_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['sep_fte_actuals'] * $value['sep_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["sep_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['sep_fte_actuals'] * $value['sep_fte_forcast'])/100000)+$value["sep_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['sep_fte_actuals'] * $value['sep_fte_forcast'])*90/100)/100000)+$value["sep_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
							
										/* Septemeber Data */
										/* October Data */
										
											echo '<td>' . $value["oct_current_rate"] . '</td>										
											<td>' . $value["oct_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['oct_fte_forcast'] * $value['oct_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['oct_fte_forcast'] * $value['oct_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['oct_fte_forcast'] * $value['oct_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["oct_fte_plan"] . '</td>
											<td>' . $value["oct_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['oct_fte_plan'] * $value['oct_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['oct_fte_plan'] * $value['oct_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['oct_fte_plan'] * $value['oct_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["oct_fte_commit"] . '</td>
											<td>' . $value["oct_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['oct_fte_commit'] * $value['oct_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["oct_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['oct_fte_commit'] * $value['oct_fte_forcast'])/100000)+$value["oct_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['oct_fte_commit'] * $value['oct_fte_forcast'])*90/100)/100000)+$value["oct_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['oct_fte_actuals'].'</td>
											<td>' . $value["oct_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['oct_fte_actuals'] * $value['oct_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["oct_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['oct_fte_actuals'] * $value['oct_fte_forcast'])/100000)+$value["oct_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['oct_fte_actuals'] * $value['oct_fte_forcast'])*90/100)/100000)+$value["oct_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
							
										/* October Data */
										/* November Data */
										
											echo '<td>' . $value["nov_current_rate"] . '</td>										
											<td>' . $value["nov_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['nov_fte_forcast'] * $value['nov_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['nov_fte_forcast'] * $value['nov_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['nov_fte_forcast'] * $value['nov_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["nov_fte_plan"] . '</td>
											<td>' . $value["nov_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['nov_fte_plan'] * $value['nov_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['nov_fte_plan'] * $value['nov_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['nov_fte_plan'] * $value['nov_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["nov_fte_commit"] . '</td>
											<td>' . $value["nov_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['nov_fte_commit'] * $value['nov_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["nov_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['nov_fte_commit'] * $value['nov_fte_forcast'])/100000)+$value["nov_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['nov_fte_commit'] * $value['nov_fte_forcast'])*90/100)/100000)+$value["nov_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['nov_fte_actuals'].'</td>
											<td>' . $value["nov_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['nov_fte_actuals'] * $value['nov_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["nov_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['nov_fte_actuals'] * $value['nov_fte_forcast'])/100000)+$value["nov_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['nov_fte_actuals'] * $value['nov_fte_forcast'])*90/100)/100000)+$value["nov_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
							
										/* November Data */
										/* December Data */
										
											echo '<td>' . $value["dece_current_rate"] . '</td>										
											<td>' . $value["dece_fte_forcast"] . '</td>';
											if( $value['model'] == 'CPM'){
												$forvcastRevenue = round((($value['dece_fte_forcast'] * $value['dece_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$forvcastRevenue = round(($value['dece_fte_forcast'] * $value['dece_current_rate'])/100000,2);
											}else{
												$forvcastRevenue = round((($value['dece_fte_forcast'] * $value['dece_current_rate'])*27)/100000,2);
											}
											
											echo '<td>' . $forvcastRevenue. '</td>
											<td>' . $value["dece_fte_plan"] . '</td>
											<td>' . $value["dece_rp_plan"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenuePlan = round((($value['dece_fte_plan'] * $value['dece_current_rate'] * $noOfDays)*90/100)/100000,2);
											}else if($value['model'] == 'FTE'){
												$revenuePlan = round(($value['dece_fte_plan'] * $value['dece_current_rate'])/100000,2);
											}else{
												$revenuePlan = round((($value['dece_fte_plan'] * $value['dece_current_rate'])*80/100)/100000,2);
											}
											echo '<td>' . $revenuePlan . '</td>
											<td>' . $value["dece_fte_commit"] . '</td>
											<td>' . $value["dece_rp_commit"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueComment = round(((($value['dece_fte_commit'] * $value['dece_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["dece_rp_commit"],2);
											}else if($value['model'] == 'FTE'){
												$revenueComment = round((($value['dece_fte_commit'] * $value['dece_fte_forcast'])/100000)+$value["dece_rp_commit"],2);
											}else{
												$revenueComment = round(((($value['dece_fte_commit'] * $value['dece_fte_forcast'])*90/100)/100000)+$value["dece_rp_commit"],2);
											}
											echo '<td>' . $revenueComment . '</td>
											<td>'.$value['dece_fte_actuals'].'</td>
											<td>' . $value["dece_rp_actuals"] . '</td>';
											if( $value['model'] == 'CPM'){
												$revenueActuals = round(((($value['dece_fte_actuals'] * $value['dece_fte_forcast'] * $noOfDays)*90/100)/100000)+$value["dece_rp_actuals"],2);
											}else if($value['model'] == 'FTE'){
												$revenueActuals = round((($value['dece_fte_actuals'] * $value['dece_fte_forcast'])/100000)+$value["dece_rp_actuals"],2);
											}else{
												$revenueActuals = round(((($value['dece_fte_actuals'] * $value['dece_fte_forcast'])*90/100)/100000)+$value["dece_rp_actuals"],2);
											}
											
											echo '<td>' . $revenueActuals . '</td>';
							
										/* December Data */
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					<?php
						/* } else {
							echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
						} */
					
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

<script>
	$(document).ready(function() {
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
				// alert("Please select both date  from and to");
				toastr.error('Please select both date  from and to'); 
				validate = 1;
				alert_msg += '<li> Please select both date  from and to </li>';
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