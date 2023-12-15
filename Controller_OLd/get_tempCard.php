<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
//$locationid=$_SESSION['__location'];
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$EmpID = clean($_REQUEST['EmpID']);
// $sql = 'SELECT personal_details.EmployeeID,personal_details.location,img,EmployeeName,BloodGroup,em_contact from personal_details left outer join contact_details on  personal_details.EmployeeID=contact_details.EmployeeID where personal_details.EmployeeID="' . $_REQUEST['EmpID'] . '" order by cont_id desc limit 1';
$sql = 'SELECT personal_details.EmployeeID,personal_details.location,img,EmployeeName,BloodGroup,em_contact from personal_details left outer join contact_details on  personal_details.EmployeeID=contact_details.EmployeeID where personal_details.EmployeeID=? order by cont_id desc limit 1';
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $EmpID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
// $result = $myDB->query($sql);
// $locationid = $result[0]['location'];
// $empid = $result[0]['EmployeeID'];
$locationid = clean($row[1]);
$empid = clean($row[0]);


// $location_address = "SELECT a.*,b.location from  idcard_address_master  a Inner Join location_master b on a.locationId=b.id where a.locationId ='" . $locationid . "' ";
$location_address = "SELECT a.*,b.location from  idcard_address_master  a Inner Join location_master b on a.locationId=b.id where a.locationId =? ";
$stmtLoc = $conn->prepare($location_address);
$stmtLoc->bind_param("i", $locationid);
$stmtLoc->execute();
$Locationresult = $stmtLoc->get_result();
$rowLoc = $Locationresult->fetch_row();
// echo "<pre>";
// print_r($rowLoc);
// die;
// $Locationresult = $myDB->query($location_address);
if ($locationid == 1 || $locationid == 2) {
	$img = 'Images/' . clean($row[2]);
} else if ($locationid == 3) {
	$img = 'Meerut/Images/' . clean($row[2]);
} else if ($locationid == 4) {
	$img = 'Bareilly/Images/' . clean($row[2]);
} else if ($locationid == 5) {
	$img = 'Vadodara/Images/' . clean($row[2]);
} else if ($locationid == 6) {
	$img = 'Manglore/Images/' . clean($row[2]);
} else if ($locationid == 7) {
	$img = 'Bangalore/Images/' . clean($row[2]);
} else if ($locationid == 8) {
	$img = 'Banglore_Fk/Images/' . clean($row[2]);
}

// echo $img = clean($row[2]);
// die;

$rd_proff = 'Emergency No.';
$rd_type = (clean($row[5]) == '') ? ': N/A' : '<b>: ' .  clean($row[5]) . '</b>';
$employeeName  =  clean($row[3]);
$BloodGroup  = '<b>: ' .  clean($row[4]) . '</b>';
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
				if ($Locationresult->num_rows > 0) {
				?>
					<p align="center" style="margin: 0px;margin-top: 1px;"><b style="font-size: 11px;"><?php echo clean($rowLoc[3]); ?></b></p>
					<?php if (trim(clean($rowLoc[6])) != "") {   ?>
						<p align="center" style="margin: 0px;"><?php echo clean($rowLoc[6]); ?></p>
					<?php }
					if (trim(clean($rowLoc[7])) != "") {   ?>
						<p align="center" style="margin: 0px;"><?php echo clean($rowLoc[7]); ?></p>
					<?php }
					if (trim(clean($rowLoc[8])) != "") {   ?>
						<p align="center" style="margin: 0px;"> <?php echo clean($rowLoc[8]); ?></p>
					<?php }
					if (trim(clean($rowLoc[5])) != "") {  ?>

						<p align="center" style="margin: 0px;"><?php echo clean($rowLoc[5]); ?></p>

					<?php
					}
					if (trim(clean($rowLoc[4])) != "") {  ?>

						<p align="center" style="margin: 0px;"><?php echo clean($rowLoc[4]); ?></p>
				<?php
					}
				}
				?>

			</div>

		</div>
	</div>
	<p align="center" style="margin: 5px;width: 250px;"><button type="button" onClick="printdiv('div_print');">Print</button></p>
</body>