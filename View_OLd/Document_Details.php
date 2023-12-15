<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmployeeID = $dir_location = '';
if (isset($_REQUEST['empid']) && $EmployeeID == '' && !isset($_POST['txt_Personal_First'])) {
	$EmployeeID = strtoupper(clean($_REQUEST['empid']));
} elseif (isset($_POST['EmployeeID']) && $_POST['EmployeeID'] != '') {
	$EmployeeID = cleanUserInput($_POST['EmployeeID']);
}
$EmployeeID = strtoupper(clean($_REQUEST['empid']));
// $sql = 'select location from personal_details where EmployeeID = "' . $EmployeeID . '"';
$sql = 'select location from personal_details where EmployeeID=?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $EmployeeID);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_row();
// print_r($row);
// die;
// $result = $myDB->rawQuery($sql);
// $mysql_error = $myDB->getLastError();
if ($res->num_rows > 0) {
	// $loc = $result[0]['location'];
	$loc = $row[0];
	$dir_location = '';

	if ($loc == "1" || $loc == "2") {
		$dir_location = '';
	} else if ($loc == "3") {
		$dir_location = 'Meerut/';
	} else if ($loc == "4") {
		$dir_location = "Bareilly/";
	} else if ($loc == "5") {
		$dir_location = "Vadodara/";
	} else if ($loc == "6") {
		$dir_location = "Manglore/";
	} else if ($loc == "7") {
		$dir_location = "Bangalore/";
	} else if ($loc == "8") {
		$dir_location = "Banglore_Fk/";
	}
	$dir_location = $dir_location . 'wt_docs/';
}

?>
<script>
	$(document).ready(function() {

	});
</script>
<Style>
	.emp_image {
		max-width: 230px;
		max-height: 230px;
	}
</Style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">
	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Warring & Refer to HR Documents</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Warring & Refer to HR Documents</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<input type="hidden" name="location" id="location" value="<?php echo $dir_location; ?>" />

				<?php
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				?>
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
				<div class="col s12 m12 no-padding">
					<?php
					$myDB = new MysqliDb();
					// $sqlConnect = "SELECT i.ah_status,i.ah_subtype,i.hr_status,i.ah_Datetime,d.Title,  Document  FROM warning_rth i inner join warning_rth_documents d on i.EmployeeID=d.EmployeeID and i.id=d.DataId where i.EmployeeID='" . $EmployeeID . "' group by d.DataId;";
					$sqlConnect = "SELECT i.ah_status,i.ah_subtype,i.hr_status,i.ah_Datetime,d.Title,  Document  FROM warning_rth i inner join warning_rth_documents d on i.EmployeeID=d.EmployeeID and i.id=d.DataId where i.EmployeeID=? group by d.DataId";
					$stmt1 = $conn->prepare($sqlConnect);
					$stmt1->bind_param("s", $EmployeeID);
					$stmt1->execute();
					$result = $stmt1->get_result();

					// $result = $myDB->query($sqlConnect);
					// $MysqliError = $myDB->getLastError();
					if ($result->num_rows > 0) { ?>
						<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Letter</th>
									<th>Sub Type</th>
									<th>HR Status</th>
									<th>Date</th>
									<th>Name</th>
									<th style="width:100px;">Download </th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="ah_status">' . $value['ah_status'] . '</td>';
									echo '<td class="ah_subtype">' . $value['ah_subtype'] . '</td>';
									echo '<td class="hr_status">' . $value['hr_status'] . '</td>';
									echo '<td class="ah_Datetime">' . $value['ah_Datetime'] . '</td>';
									echo '<td class="Title">' . $value['Title'] . '</td>';
									if ($_SESSION['__user_logid'] == 'CE03070003') {
										echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  
								onclick="javascript:return Download2(this);"  title="Download File" data="' . $value['Document'] . '" /></td>';
									} else {
										echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  
								title="Download File" data="' . $value['Document'] . '" /></td>';
									}
									echo '</tr>';
								}
								?>
							</tbody>
						</table><br />
					<?php
					} else {
						echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
					}
					?>
				</div>
			</div>
			<div class="hidden modelbackground" id="myDiv">
			</div>
		</div>
	</div>
</div>
</div>
<script>
	function Download2(el) {
		if ($(el).attr('data') != '') {
			window.open("../" + $('#location').val() + $(el).attr("data"));
		} else {
			alert('No File Exist');
		}
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>