<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$loginId = $_SESSION['__user_logid'];
$dt = $_REQUEST['dt'];
//$sql = "SELECT * from referral_dispo where ref_id='" . $refid . "'";
// concat(year($dt),'-', LPAD(MONTH($dt), 2, '0'))

$query = "select A.id,A.loc_id,A.site,A.createdon,A.capacity,A.mnth_year as site_month_year,C.seat,FORMAT(B.price, 0, 'en_IN') as price,l.location,FORMAT(ROUND((B.price/A.capacity),0), 0, 'en_IN') as perseat from site_master A left join (SELECT site_id, sum(price) price   FROM site_cost_master  where txt_date=?  group by site_id)B on A.id =B.site_id left join (SELECT site_id, sum(seat) seat FROM site_seat_master   where mnth_year=?  group by site_id)C on  A.id =C.site_id  left join location_master as l on A.loc_id=l.id where A.mnth_year=?";
$sql = $conn->prepare($query);
$sql->bind_param("sss", $dt, $dt,$dt);
$sql->execute();
$result = $sql->get_result();
$i = 1;
$tble = '';
$tble .= '<table id="myTable" class="data dataTable no-footer cell-border getIDTR" cellspacing="0" width="100%">
<thead>
	<tr>
		<th class="hidden">id</th>
		<th class="hidden">location</th>
		<th class="hidden">Site</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Sr. No.</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Month</th>
		
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Location</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Site</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Seat Capacity</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Vacant Seats</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Utilized Seats</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Total Cost</th>
		<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Cost Per Seat</th>';
if (in_array($loginId, $authUserSite, true)) {
	$tble .= '<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;">Action</th>';
}

$tble .= '<th style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;border-right: 1px solid #ddd;">Manage</th>

	</tr>
</thead>
<tbody>';

foreach ($result as $key => $value) {
	/* if($value['capacity'] > $value['seat']){
		$color = "color:green;font-weight: 600;";
	}else{
		$color="color:red;font-weight: 600;";
	} */
	$cId = $value['id'];
	$gSite = "'" . trim($value['site']) . "'";
	$site_month_year = "'" . trim($value['site_month_year']) . "'";
	$tble .= '<tr>
			<td class="hidden">' . $value["id"] . '</td>
			<td class="hidden">' . $value["location"] . '</td>
			<td class="hidden">' . $value["site"] . '</td>
			
			<td style="border-left: 1px solid #ddd;">' . $i++ . '</td>
			<td>' . $value["site_month_year"] . '</td>
			<td class="empid">' . $value["location"] . '</td>
			<td>' . $value["site"] . '</td>
			<td>' . $value["capacity"] . '</td>
			
			<td>' . ($value["capacity"] - $value["seat"]) . '</td>
			<td>' . $value["seat"] . '</td>
			<td>' . $value["price"] . '</td>
			<td>' . $value["perseat"] . '</td>';
	if (in_array($loginId, $authUserSite, true)) {
		$tble .= '<td>

				<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-tooltip="Edit" onclick="editFunction(' . $value['id'] . ',' . $value['loc_id'] . ',' . $gSite . ',' . $value['capacity'] .',' . $site_month_year.')">ohrm_edit</i>

				<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped modal-trigger" onclick="deleteFunction(' . $value['id'] . ');"> ohrm_delete</i>
			</td>';
	}
	$tble .= '<td>
				<i href="#myModal_content" class="imgBtn imgBtnEdit tooltipped modal-trigger" id="seatbutton" value="$cId" data-position="left" data-tooltip="Edit" onclick="getClient(' . $value['loc_id'] . ')" style="text-decoration: underline;color: blue;">Seat</i>

				<i href="#myModal_content_view" class="imgBtn imgBtnEdit tooltipped modal-trigger" id="costbutton" value="$cId" data-position="left" data-tooltip="Edit" style="text-decoration: underline;color: blue;">Cost</i>
			</td>

		</tr>';
}
$tble .= '</tbody></table>';

echo $tble;

?>
<script>
	$("#myTable").on('click', '#seatbutton', function() {
		// get the current row

		var currentRow = $(this).closest("tr");
		var col1 = currentRow.find("td:eq(0)").text(); // get current row 1st TD value

		var col2 = currentRow.find("td:eq(1)").text(); // get current row 2st TD value
		var col3 = currentRow.find("td:eq(2)").text(); // get current row 3st TD value
		var serchbtn = $('#mnth_year_dt').val() != '' ? $('#mnth_year_dt').val() : curntDateYear;
		$('#locHeader').html(col2 + ': ' + col3);
		$('#sitemasterid').val(col1);

		$.ajax({
			url: '../Controller/site_seat_controller.php',
			type: 'GET',
			data: {
				id: col1,
				curntDateYear: serchbtn,
			},
			success: function(response) {
				console.log(response);
				$('#hisseat').html(response);
			}
		});
	});
	$("#myTable").on('click', '#costbutton', function() {
		// get the current row
		var currentRow = $(this).closest("tr");
		var col1 = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
		var col2 = currentRow.find("td:eq(1)").text(); // get current row 2st TD value
		var col3 = currentRow.find("td:eq(2)").text(); // get current row 3st TD value
		var serchbtn = $('#mnth_year_dt').val() != '' ? $('#mnth_year_dt').val() : curntDateYear;

		$('#locHeaderCost').html(col2 + ': ' + col3);
		// console.log(col1);
		$('#sitemasteridcost').val(col1);

		$.ajax({
			url: '../Controller/site_cost_controller.php',
			type: 'GET',
			data: {
				id: col1,
				curntDateYear: serchbtn,
			},
			success: function(response) {
				console.log(response);
				$('#hiscost').html(response);


			}
		});
	});
	$(document).ready(function() {


	});
</script>