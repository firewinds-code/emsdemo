<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$loginId = $_SESSION['__user_logid'];
$dt = $_REQUEST['dt'];
$dtArr = explode('-',$dt);
$tbleName= 'revenue_master_'.$dtArr[1];
$moTb = "'".$dtArr[1]."'";

$noOfDays =  cal_days_in_month(CAL_GREGORIAN,$dtArr[1], $dtArr[0]);
//$sql = "SELECT * from referral_dispo where ref_id='" . $refid . "'";
// concat(year($dt),'-', LPAD(MONTH($dt), 2, '0'))

$query = "select rm.id,rm.loc_id, rm.new_location, rm.client_id, rm.process, rm.model, rm.current_rate, rm.vh, rm.svh, rm.fte_forcast, rm.fte_plan, rm.rp_plan, rm.fte_commit, rm.rp_commit, rm.fte_actuals, rm.rp_actuals, rm.mnth_year,l.location,cm.client_name
from $tbleName as rm left join location_master as l on l.id=rm.loc_id left join client_master as cm on cm.client_id = rm.client_id where rm.mnth_year=?";
$sql = $conn->prepare($query);
$sql->bind_param("s", $dt);
$sql->execute();
$result = $sql->get_result();
$i = 1;
$tble = '';
$tble .= '<table id="myTable" class="data dataTable no-footer cell-border getIDTR" cellspacing="0" width="100%">
<thead>
	<tr>';
	if (in_array($loginId, $authUserSite, true)) {
		$tble .= '<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;width:67px !important;">Action</th>';
	}

	$tble .='<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Sr. No.</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Location</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">New Location</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Client</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Process</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Model</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Current Rate</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Vertical Head</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Sub Vertical Head</th>							
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
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Revenue Actuals</th>';


$tble .= '</tr>
</thead>
<tbody>';
$i=1;
foreach ($result as $key => $value) {
	$new_location = "'".$value['new_location']."'";
	$process = "'".$value['process']."'";
	$model = "'".$value['model']."'";
	$vh = "'".$value['vh']."'";
	$svh = "'".$value['svh']."'";
	$mnth_year = "'".$value['mnth_year']."'";
	$rp_actuals = "'".$value['rp_actuals']."'";
	$client_id = "'".$value['client_id']."'";
	$fte_actuals = "'".$value['fte_actuals']."'";
	$current_rate = "'".$value['current_rate']."'";
	$fte_forcast = "'".$value['fte_forcast']."'";
	$fte_plan = "'".$value['fte_plan']."'";
	$rp_plan = "'".$value['rp_plan']."'";
	$fte_commit = "'".$value['fte_commit']."'";
	$rp_commit = "'".$value['rp_commit']."'";
	$tble .= '<tr>';
	if (in_array($loginId, $authUserSite, true)) {
		$tble .= '<td>
				<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-tooltip="Edit" onclick="editFunction(' . $value['id'] . ',' . $value['loc_id'] . ',' . $new_location . ',' . $client_id.',' .$process.',' .$model.',' .$current_rate.',' .$vh.',' .$svh.',' .$fte_forcast.',' .$fte_plan.',' .$rp_plan.',' .$fte_commit.',' .$rp_commit.',' .$fte_actuals.',' .$mnth_year.',' .$rp_actuals.')">ohrm_edit</i>
				<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped modal-trigger" onclick="deleteFunction(' . $value['id'] . ','.$moTb.');"> ohrm_delete</i>
			</td>';
	}
	$tble .='<td>' . $i++ . '</td>
			<td>' . $value["location"] . '</td>
			<td>' . $value["new_location"] . '</td>			
			<td style="border-left: 1px solid #ddd;">' .  $value["client_name"] . '</td>
			<td>' . $value["process"] . '</td>
			<td >' . $value["model"] . '</td>
			<td>' . $value["current_rate"] . '</td>
			<td>' . $value["vh"] . '</td>
			<td>' . $value["svh"] . '</td>
			<td>' . $value["fte_forcast"] . '</td>';
			if( $value['model'] == 'CPM'){
				$forvcastRevenue = (($value['fte_forcast'] * $value['current_rate'] * $noOfDays)*90/100)/100000;
			}else if($value['model'] == 'FTE'){
				$forvcastRevenue = ($value['fte_forcast'] * $value['current_rate'])/100000;
			}else{
				$forvcastRevenue = (($value['fte_forcast'] * $value['current_rate'])*27)/100000;
			}
			
			$tble .='<td>' . $forvcastRevenue. '</td>
			<td>' . $value["fte_plan"] . '</td>
			<td>' . $value["rp_plan"] . '</td>';
			if( $value['model'] == 'CPM'){
				$revenuePlan = (($value['fte_plan'] * $value['current_rate'] * $noOfDays)*90/100)/100000;
			}else if($value['model'] == 'FTE'){
				$revenuePlan = ($value['fte_plan'] * $value['current_rate'])/100000;
			}else{
				$revenuePlan = (($value['fte_plan'] * $value['current_rate'])*80/100)/100000;
			}
			$tble .='<td>' . $revenuePlan . '</td>
			<td>' . $value["fte_commit"] . '</td>
			<td>' . $value["rp_commit"] . '</td>';
			if( $value['model'] == 'CPM'){
				$revenueComment = ((($value['fte_commit'] * $value['fte_forcast'] * $noOfDays)*90/100)/100000)+$value["rp_commit"];
			}else if($value['model'] == 'FTE'){
				$revenueComment = (($value['fte_commit'] * $value['fte_forcast'])/100000)+$value["rp_commit"];
			}else{
				$revenueComment = ((($value['fte_commit'] * $value['fte_forcast'])*90/100)/100000)+$value["rp_commit"];
			}
			$tble .='<td>' . $revenueComment . '</td>
			<td>'.$value['fte_actuals'].'</td>
			<td>' . $value["rp_actuals"] . '</td>';
			if( $value['model'] == 'CPM'){
				$revenueActuals = ((($value['fte_actuals'] * $value['fte_forcast'] * $noOfDays)*90/100)/100000)+$value["rp_actuals"];
			}else if($value['model'] == 'FTE'){
				$revenueActuals = (($value['fte_actuals'] * $value['fte_forcast'])/100000)+$value["rp_actuals"];
			}else{
				$revenueActuals = ((($value['fte_actuals'] * $value['fte_forcast'])*90/100)/100000)+$value["rp_actuals"];
			}
			
			$tble .='<td>' . $revenueActuals . '</td></tr>';
	
	
}
$tble .= '</tbody></table>';

echo $tble;

?>
<script>
	
</script>