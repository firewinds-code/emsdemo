<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');



$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$refid = $_REQUEST['id'];
$curntDateYear = $_REQUEST['curntDateYear'];
//$sql = "SELECT * from referral_dispo where ref_id='" . $refid . "'";
$loginId = $_SESSION['__user_logid'];
//$sql = "SELECT s.id as seatmasterid,sm.id as sitemasterid, s.client_id, s.site_id, s.process, s.seat,s.createdon, sm.loc_id , c.client_name,sm.site,l.location, s.mnth_year,B.price, ROUND((B.price/C.seat)*s.seat,2) as processCost FROM site_seat_master as s left join client_master as c on s.client_id = c.client_id left join site_master as sm on  s.site_id=sm.id left join location_master as l on l.id = sm.loc_id left join (SELECT site_id, sum(price) price   FROM site_cost_master  where txt_date=?  group by site_id)B  on sm.id =B.site_id left join (SELECT site_id, sum(seat) seat FROM site_seat_master   where mnth_year=?  group by site_id)C on  sm.id =C.site_id  where s.site_id=? and s.mnth_year=?";
$sql = "SELECT s.id as seatmasterid,sm.id as sitemasterid, s.client_id, s.site_id, s.process, s.seat,DATE_FORMAT(s.createdon, '%d-%b-%Y %h:%i:%S %p') as 'createdon', sm.loc_id , c.client_name,sm.site,l.location,s.mnth_year,B.price,ROUND((B.price/C.seat)*s.seat,0) as processCost FROM site_seat_master as s left join client_master as c on s.client_id = c.client_id left join site_master as sm on  s.site_id=sm.id left join location_master as l on l.id = sm.loc_id left join (SELECT site_id, sum(price) price   FROM site_cost_master  where txt_date=?  group by site_id)B  on sm.id =B.site_id left join (SELECT site_id, sum(seat) seat FROM site_seat_master   where mnth_year=?  group by site_id)C on  sm.id =C.site_id  where s.site_id=? and s.mnth_year=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssis", $curntDateYear,$curntDateYear,$refid,$curntDateYear);
if (!$stmt) {
	echo "failed to run";
	die;
}
$stmt->execute();
$query = $stmt->get_result();
$count = $query->num_rows;

if ($count > 0) {
	$i = 1;
	echo '<script>
	$("#myTableSeat").DataTable({
				"pageLength": 20,			
		dom: "Bfrtip",
		// retrieve: true,
		// paging: false,
		// processing: true,
		// serverSide: true,
		// stateSave: true,
		buttons: [{
				extend: "excel",
				text: "Excel",
				extension: ".xlsx",
				exportOptions: {
					columns: [1,2,3,4,5,6,7],
					modifier: {
						page: "all"
					}
				},
				title: "Seattable"
			}
		],
		
	});
	
	</script>';
	$dt ='';
	$dt .='<table id="myTableSeat" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th class="hidden">ID</th>
			<th>Sr No</th>
			<th>Client</th>
			<th>Process</th>
			<th>Seat</th>
			<th>Month</th>
			<th>Process Cost</th>
			<th>Created Date</th>';
			if(in_array($loginId,$authUserSite,true)){
				$dt .='<th>Action</th>';
			}
			$dt .='</tr>
	</thead><tbody>';
	
	foreach ($query as $value) {
		$process ="'".trim($value['process'])."'";
		$seat ="'".trim($value['seat'])."'";
		$mnth_year ="'".trim($value['mnth_year'])."'";
		
		 $dt .=	'<tr>
			<td class="hidden">'.$value['sitemasterid'].'</td>
			<td>'.$i++.'</td>
			<td>'.$value['client_name'].'</td>
			<td>'.$value['process'].'</td>
			<td>'.$value['seat'].'</td>
			<td>'.$value['mnth_year'].'</td>
			<td>'.$value['processCost'].'</td>
			<td>'.$value['createdon'].'</td>';
			if(in_array($loginId,$authUserSite,true)){
				$dt .= '<td><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped modal-trigger" onclick="editSeatFunction('.$value['seatmasterid'].','.$value['client_id'].','.$process.','.$seat.','.$value['sitemasterid'].','.$value['loc_id'].','.$mnth_year.')">ohrm_edit</i><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped modal-trigger" onclick="deleteSeatFunction('.$value['seatmasterid'].')"> ohrm_delete</i>
			</td>';
			}
			$dt .= '</tr>';

	}
	$dt .='</tbody></table>';
	echo $dt;
}
