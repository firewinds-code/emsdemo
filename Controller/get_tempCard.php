<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
//$locationid=$_SESSION['__location'];
$sql = 'SELECT personal_details.EmployeeID,personal_details.location,img,EmployeeName,BloodGroup,em_contact from personal_details left outer join contact_details on  personal_details.EmployeeID=contact_details.EmployeeID where personal_details.EmployeeID="' . $_REQUEST['EmpID'] . '" order by cont_id desc limit 1';
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$locationid = $result[0]['location'];
$empid = $result[0]['EmployeeID'];

$location_address = "SELECT a.*,b.location from  idcard_address_master  a Inner Join location_master b on a.locationId=b.id where a.locationId ='" . $locationid . "' ";
$myDB = new MysqliDb();
$Locationresult = $myDB->query($location_address);
if ($locationid == 1 || $locationid == 2) {
	$img = 'Images/' . $result[0]['img'];
} else if ($locationid == 3) {
	$img = 'Meerut/Images/' . $result[0]['img'];
} else if ($locationid == 4) {
	$img = 'Bareilly/Images/' . $result[0]['img'];
} else if ($locationid == 5) {
	$img = 'Vadodara/Images/' . $result[0]['img'];
} else if ($locationid == 6) {
	$img = 'Manglore/Images/' . $result[0]['img'];
} else if ($locationid == 7) {
	$img = 'Bangalore/Images/' . $result[0]['img'];
} else if ($locationid == "8") {
	$img = 'Nashik/Images/' . $result[0]['img'];
} else if ($locationid == "9") {
	$img = 'Anantapur/Images/' . $result[0]['img'];
} else if ($locationid == "10") {
	$img = 'Gurgaon/Images/' . $result[0]['img'];
} else if ($locationid == "11") {
	$img = 'Hyderabad/Images/' . $result[0]['img'];
}

//$img = $result[0]['img'];

$rd_proff = 'Emergency No. ';
$rd_type = ($result[0]['em_contact'] == '') ? ': N/A' : '<b>: ' . $result[0]['em_contact'] . '</b>';
$employeeName  = $result[0]['EmployeeName'];
$BloodGroup  = '<b>: ' . $result[0]['BloodGroup'] . '</b>';
?>
<script>
	function printdiv(printpage) {
		var headstr = "<html><head><title></title></head><body>";
		var footstr = "</body>";
		var newstr = document.all.item(printpage).innerHTML;
		var oldstr = document.body.innerHTML;
		document.body.innerHTML = headstr + newstr + footstr;
		window.print();
		document.body.innerHTML = oldstr;
		return false;
	}
</script>
<style>
	body {
		-webkit-print-color-adjust: exact;
	}

	label {
		min-width: 100px;
		float: left;
	}

	p {
		margin: 2px 15px;
	}

	p,
	b {
		font-family: sans-serif;
	}
</style>

<body style="overflow: hidden;">
	<div id="div_print">
		<div class="card" style="width: 250px;border: 1px solid #424040;height: auto;">
			<div style="height: 55px;width: 100%;" align="center"><img src="../Style/images/Cogent.png" alt="Avatar" style="height: 55px;width: 150px; margin-top:8px;" /></div>
			<div class="container" style="height: 150px;margin: 13px 0px;" align="center">
				<img src="../<?php echo $img; ?>" style="width:130px;height: 165px;border: 1px solid #0c0c0c;" />
			</div>
			<div class="container" style="font-size:12px;">
				<P align="center" style="padding: 5px; text-transform: uppercase;"><b style="font-weight:800"><?php echo $employeeName; ?></b></P>
				<p><label><strong>Employee ID</strong></label><?php echo $empid; ?></p>
				<p><label><strong><?php echo $rd_proff; ?></strong></label> <?php echo $rd_type; ?></p>
				<p><label><strong>Blood Group</strong></label> <?php echo $BloodGroup; ?></p>
				<p align="right" style="font-size: 8px;font-family: cursive;position: relative;padding-top: 15px;"><img src="../Style/img/sk_sign.jpeg" style="width: 50px;float: right;position: absolute;top: -10px;right: 4px;" /><span>Auth. Signatory</span></p>
			</div>
			<div class="container" style="font-size: 10px;font-weight: bold;font-family: monospace;padding: 0px;border-top: 2px solid #007380;background-color: #a0ffbe;width: 100%;height: 70px;">
				<?php
				if (count($Locationresult) > 0) {
				?>
					<p align="center" style="margin: 0px;margin-top: 1px;"><b style="font-size: 11px;"><?php echo $Locationresult[0]['companyname']; ?></b></p>
					<?php if (trim($Locationresult[0]['address_line1']) != "") {   ?>
						<p align="center" style="margin: 0px;"><?php echo $Locationresult[0]['address_line1']; ?></p>
					<?php }
					if (trim($Locationresult[0]['address_line2']) != "") {   ?>
						<p align="center" style="margin: 0px;"><?php echo $Locationresult[0]['address_line2']; ?></p>
					<?php }
					if (trim($Locationresult[0]['address_line3']) != "") {   ?>
						<p align="center" style="margin: 0px;"> <?php echo $Locationresult[0]['address_line3']; ?></p>
					<?php }
					if (trim($Locationresult[0]['company_contact_num']) != "") {  ?>

						<p align="center" style="margin: 0px;"><?php echo $Locationresult[0]['company_contact_num']; ?></p>

					<?php
					}
					if (trim($Locationresult[0]['company_URL']) != "") {  ?>

						<p align="center" style="margin: 0px;"><?php echo $Locationresult[0]['company_URL']; ?></p>
				<?php
					}
				}
				?>

			</div>

		</div>
	</div>
	<p align="center" style="margin: 5px;width: 250px;"><button type="button" onClick="printdiv('div_print');">Print</button></p>
</body>