<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$loginId = $_SESSION['__user_logid'];

$refid = $_REQUEST['id'];
$curntDateYear = $_REQUEST['curntDateYear'];
//$sql = "SELECT * from referral_dispo where ref_id='" . $refid . "'";

$sql = "SELECT s.id as costmasterid,sm.id as sitemasterid, s.txt_date,s.costitem, s.price,DATE_FORMAT(s.createdon, '%d-%b-%Y %h:%i:%S %p') as 'createdon',sm.site,l.item FROM site_cost_master as s left join site_master as sm on  s.site_id=sm.id left join costitem as l on l.id = s.costitem where s.site_id=? and s.txt_date=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $refid,$curntDateYear);
if (!$stmt) {
	echo "failed to run";
	die;
}
$stmt->execute();
$query = $stmt->get_result();
$count = $query->num_rows;

if ($count > 0) {
	$i = 1;
	echo '<script>$("#myTableCost").DataTable({
		dom: "Bfrtip",
		"pageLength": 20,
		buttons: [{
				extend: "excel",
				text: "Excel",
				extension: ".xlsx",								
				title: "Costtable"
			}
			
		]
	});
	
	</script>';
	$dt ='<table id="myTableCost" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th class="hidden">ID</th>
			<th>Sr No</th>
			<th>Cost Item</th>
			<th>Month</th>
			<th>Amount</th>';
			if(in_array($loginId,$authUserSite,true)){
			$dt .='<th>Action</th>';
			}
			$dt .='</tr>
	</thead>
	<tbody>';
	foreach ($query as $value) {
		$costitem ="'".trim($value['costitem'])."'";
		$txt_date ="'".trim($value['txt_date'])."'";
		$dt .= '<tr>
			<td class="hidden">'.$value['sitemasterid'].'</td>
			<td>'.$i++.'</td>
			<td>'.$value['item'].'</td>
			<td>'.$value['txt_date'].'</td>
			<td>'.$value['price'].'</td>';
			if(in_array($loginId,$authUserSite,true)){
				$dt .= '<td><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped modal-trigger" onclick="editCostFunction('.$value['costmasterid'].','.$costitem.','.$txt_date.','.$value['price'].','.$value['sitemasterid'].')">ohrm_edit</i><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped modal-trigger" onclick="deleteCostFunction('.$value['costmasterid'].')"> ohrm_delete</i></td>';
			}
			$dt .= '</tr>';

	}
	$dt .='</tbody></table>';
	echo $dt;
}
?>