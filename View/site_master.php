<?php
// Server Config file

require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();


$loginId = $_SESSION['__user_logid'];
if (isset($_POST['submit'])) {

	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {

		$loc_id = cleanUserInput($_POST['loc_id']);
		$site = cleanUserInput($_POST['site']);
		$capacity = cleanUserInput($_POST['capacity']);
		$mnth_year = cleanUserInput($_POST['mnth_year']);



		if ($_POST['hiddenId'] == '') {

			/* Check Duplicate Entries */
			$sqlQuery = "select * from site_master where loc_id=? and site=? and mnth_year=?";
			$stmtDuplicate = $conn->prepare($sqlQuery);
			$stmtDuplicate->bind_param("sss", $loc_id, $site, $mnth_year);
			if (!$stmtDuplicate) {
				echo "failed to run";
				die;
			}
			$stmtDuplicate->execute();
			$queryDup = $stmtDuplicate->get_result();
			$countDup = $queryDup->num_rows;
			//die("bbbbbbbbbbb");
			/* Check Duplicate Entries */
			if ($countDup > 0) {
				echo "<script>$(function(){toastr.error('Duplicate Entry'); }); </script>";
			} else {
				$insQry = "INSERT into site_master(loc_id,site,capacity,mnth_year)values(?,?,?,?)";
				$stmt = $conn->prepare($insQry);
				$stmt->bind_param("ssss", $loc_id, $site, $capacity, $mnth_year);


				if (!$stmt) {
					echo "failed to run";
					die;
				}

				$inst = $stmt->execute();

				// echo $insertId = $myDB->getInsertId();
				$insertId = $conn->insert_id;


				$sql = "INSERT into site_master_logs(site_id,loc_id,site,capacity,createdBy,mnth_year)values(?,?,?,?,?,?)";
				$stmt1 = $conn->prepare($sql);
				$stmt1->bind_param("ssssss", $insertId, $loc_id, $site, $capacity, $loginId, $mnth_year);

				if (!$stmt1) {
					echo "failed to run";
					die;
				}
				$check = $stmt1->execute();
				echo "<script>$(function(){toastr.success('Inserted Successfully'); }); </script>";
			}
		} else {

			$hiddenId = $_POST['hiddenId'];

			/* Check Duplicate Entries */
			$sqlQuery = "select * from site_master where loc_id=? and site=? and mnth_year=? and id !=?";
			$stmtDuplicate = $conn->prepare($sqlQuery);
			$stmtDuplicate->bind_param("ssss", $loc_id, $site, $mnth_year, $hiddenId);
			if (!$stmtDuplicate) {
				echo "failed to run";
				die;
			}
			$stmtDuplicate->execute();
			$queryDup = $stmtDuplicate->get_result();
			$countDup = $queryDup->num_rows;
			/* Check Duplicate Entries */
			if ($countDup > 0) {
				echo "<script>$(function(){toastr.error('Duplicate Entry'); }); </script>";
			} else {
				/*  */
				$query12 = "select A.id,A.loc_id,A.site,A.createdon,A.capacity,A.mnth_year,C.seat,B.price,l.location,ROUND((B.price/C.seat),2) as perseat from site_master A left join (SELECT site_id, sum(price) price FROM site_cost_master group by site_id)B on A.id =B.site_id left join (SELECT site_id, sum(seat) seat  FROM site_seat_master group by site_id)C on  A.id =C.site_id  left join location_master as l on A.loc_id=l.id where A.id=? and A.mnth_year=?";

				$sql5 = $conn->prepare($query12);
				$sql5->bind_param("ss", $hiddenId, $mnth_year);
				$sql5->execute();
				$getResult = $sql5->get_result();
				$tSeat = $tCapacity = '';
				foreach ($getResult as $k => $row) {
					$tSeat = $row['seat'] != '' ? $row['seat'] : 0;
					$tCapacity = $row['capacity'];
				}


				/*  */
				if ($capacity >= $tSeat) {
					$updateQuery = 'UPDATE site_master SET loc_id=?,site=?,capacity=?,mnth_year=?,modifiedon=now() WHERE id=?';
					$stmt = $conn->prepare($updateQuery);
					$stmt->bind_param("ssiss", $loc_id, $site, $capacity, $mnth_year, $hiddenId);
					$stmt->execute();

					$sql = "INSERT into site_master_logs(site_id,loc_id,site,capacity,createdBy,mnth_year)values(?,?,?,?,?,?)";
					$stmt1 = $conn->prepare($sql);
					$stmt1->bind_param("ssssss", $hiddenId, $loc_id, $site, $capacity, $loginId, $mnth_year);

					if (!$stmt1) {
						echo "failed to run";
						die;
					}
					$check = $stmt1->execute();

					$resStmt = $stmt->get_result();
					if ($stmt->affected_rows === 1) {
						echo "<script>$(function(){toastr.success('Record Updated Successfully'); }); </script>";
					} else {
						echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
					}
				} else {
					echo "<script>$(function(){toastr.error('Capacity Should be greater than Total Seat'); }); </script>";
				}
			}
		}
	}
}
if (isset($_POST['btn_seat_save'])) {

	if (isset($_POST["seat_token"]) && isset($_SESSION["seat_token"]) && $_POST["seat_token"] == $_SESSION["seat_token"]) {

		$client_id = cleanUserInput($_POST['client_id']);
		$process = cleanUserInput($_POST['process']);
		$seat = cleanUserInput($_POST['seat']);
		$site_id = $_POST['sitemasterid'];
		$mnth_year = $_POST['mnth_year'];
		$dbSeat = '';
		$hiddenSeatId = $_POST['hiddenSeatId'];
		/*  */
		if ($hiddenSeatId != '') {
			$query12 = "SELECT * FROM site_seat_master where id=?";

			$sql5 = $conn->prepare($query12);
			$sql5->bind_param("s", $hiddenSeatId);
			$sql5->execute();
			//$sql5->bind_param("i", $hiddenSeatId);
			$getResult = $sql5->get_result();
			//print_r($getResult);die;
			foreach ($getResult as $k => $row) {
				$dbSeat = $row['seat'] != '' ? $row['seat'] : 0;
			}
		}

		/*  */

		/* Get Total Seat */
		$query14 = "select A.id,A.loc_id,A.site,A.createdon,A.capacity,C.seat,B.price,l.location,ROUND((B.price/C.seat),2) as perseat from site_master A left join (SELECT site_id, sum(price) price FROM site_cost_master group by site_id)B on A.id =B.site_id left join (SELECT site_id, sum(seat) seat  FROM site_seat_master where mnth_year=? group by site_id)C on  A.id =C.site_id  left join location_master as l on A.loc_id=l.id where A.id=?";

		$sql8 = $conn->prepare($query14);
		$sql8->bind_param("ss", $mnth_year, $site_id);
		$sql8->execute();
		//$sql8->bind_param("i", $site_id);
		$getResult2 = $sql8->get_result();
		$tSeat = $tCapacity = '';
		foreach ($getResult2 as $k => $row) {
			$tSeat = $row['seat'] != '' ? $row['seat'] : 0;
			$tCapacity = $row['capacity'];
		}
		// echo $tSeat.'___'.$tCapacity;die;
		if ($dbSeat == $seat) {
			$addSeat = $tSeat;
		} else if ($dbSeat > $seat) {
			$addSeat = $tSeat;
		} else if ($dbSeat < $seat) {
			$addSeat = $tSeat + $seat - $dbSeat;
		}
		// echo $tSeat.'__'.$addSeat.'___'.$tCapacity.'__'.$dbSeat;die;

		if ($addSeat <= $tCapacity) {
			if ($hiddenSeatId == '') {

				/* Check Duplicate Entries */
				$sqlQuery = "select * from site_seat_master where client_id=? and site_id=? and process=? and mnth_year=?";
				$stmtDuplicate = $conn->prepare($sqlQuery);
				$stmtDuplicate->bind_param("ssss", $client_id, $site_id, $process, $mnth_year);
				if (!$stmtDuplicate) {
					echo "failed to run";
					die;
				}
				$stmtDuplicate->execute();
				$queryDup = $stmtDuplicate->get_result();
				$countDup = $queryDup->num_rows;
				/* Check Duplicate Entries */
				if ($countDup > 0) {
					echo "<script>$(function(){toastr.error('Duplicate Entry'); }); </script>";
				} else {
					$insQry = "INSERT into site_seat_master(client_id, site_id, process, seat,mnth_year)values(?,?,?,?,?)";
					$stmt = $conn->prepare($insQry);
					$stmt->bind_param("sssis", $client_id, $site_id, $process, $seat, $mnth_year);
					if (!$stmt) {
						echo "failed to run";
						die;
					}

					$inst = $stmt->execute();

					// echo $insertId = $myDB->getInsertId();
					$insertId = $conn->insert_id;

					$sql1 = "INSERT into site_seat_master_logs(seat_id,client_id, site_id, process, seat,createdBy,mnth_year)values(?,?,?,?,?,?,?)";
					$stmt2 = $conn->prepare($sql1);
					$stmt2->bind_param("ssssiss", $insertId, $client_id, $site_id, $process, $seat, $loginId, $mnth_year);
					if (!$stmt2) {
						echo "failed to run";
						die;
					}

					$chk = $stmt2->execute();
					echo "<script>$(function(){toastr.success('Inserted Successfully'); }); </script>";
				}
			} else {

				/* Check Duplicate Entries */
				$sqlQuery = "select * from site_seat_master where client_id=? and site_id=? and process=? and mnth_year=? and id !=?";
				$stmtDuplicate = $conn->prepare($sqlQuery);
				$stmtDuplicate->bind_param("sssss", $client_id, $site_id, $process, $mnth_year, $hiddenSeatId);
				if (!$stmtDuplicate) {
					echo "failed to run";
					die;
				}
				$stmtDuplicate->execute();
				$queryDup = $stmtDuplicate->get_result();
				$countDup = $queryDup->num_rows;
				/* Check Duplicate Entries */
				if ($countDup > 0) {
					echo "<script>$(function(){toastr.error('Duplicate Entry'); }); </script>";
				} else {
					$updateQuery = 'UPDATE site_seat_master SET client_id=?,process=?,seat=?,mnth_year=?,modifiedon=now() WHERE id=?';
					$stmt = $conn->prepare($updateQuery);
					$stmt->bind_param("sssss", $client_id, $process, $seat, $mnth_year, $hiddenSeatId);
					$stmt->execute();

					/* Logs */
					$sql1 = "INSERT into site_seat_master_logs(seat_id,client_id, site_id, process, seat,createdBy,mnth_year)values(?,?,?,?,?,?,?)";
					$stmt2 = $conn->prepare($sql1);
					$stmt2->bind_param("ssssiss", $hiddenSeatId, $client_id, $site_id, $process, $seat, $loginId, $mnth_year);
					if (!$stmt2) {
						echo "failed to run";
						die;
					}

					$chk = $stmt2->execute();
					/* Logs */

					$resStmt = $stmt->get_result();
					if ($stmt->affected_rows === 1) {
						echo "<script>$(function(){toastr.success('Record Updated Successfully'); }); </script>";
					} else {
						echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
					}
				}
			}
		} else {
			echo "<script>$(function(){toastr.error('Seat Exceed'); }); </script>";
		}
		/* Get Total Seat */
	}
}
if (isset($_POST['btn_cost_save'])) {

	if (isset($_POST["cost_token"]) && isset($_SESSION["cost_token"]) && $_POST["cost_token"] == $_SESSION["cost_token"]) {
		$site_id = $_POST['sitemasteridcost'];
		//echo $site_id;die;
		$costitem = cleanUserInput($_POST['costitem']);
		$txt_date = cleanUserInput($_POST['txt_date']);
		$price = cleanUserInput($_POST['price']);
		if ($_POST['hiddenCostId'] == '') {

			/* Check Duplicate Entries */
			$sqlQuery = "select * from site_cost_master where site_id=? and costitem=? and txt_date=?";
			$stmtDuplicate = $conn->prepare($sqlQuery);
			$stmtDuplicate->bind_param("sss", $site_id, $costitem, $txt_date);
			if (!$stmtDuplicate) {
				echo "failed to run";
				die;
			}
			$stmtDuplicate->execute();
			$queryDup = $stmtDuplicate->get_result();
			$countDup = $queryDup->num_rows;
			/* Check Duplicate Entries */
			if ($countDup > 0) {
				echo "<script>$(function(){toastr.error('Duplicate Entry'); }); </script>";
			} else {
				$insQry = "INSERT into site_cost_master(site_id, costitem, txt_date,price)values(?,?,?,?)";
				$stmt = $conn->prepare($insQry);
				$stmt->bind_param("sssi", $site_id, $costitem, $txt_date, $price);
				if (!$stmt) {
					echo "failed to run";
					die;
				}
				$inst = $stmt->execute();


				// echo $insertId = $myDB->getInsertId();
				$insertId = $conn->insert_id;

				/* Logs */
				$query = "INSERT into site_cost_master_logs(cost_id,site_id, costitem, txt_date,price,createdBy) values(?,?,?,?,?,?)";
				$stmt3 = $conn->prepare($query);
				$stmt3->bind_param("isssis", $insertId, $site_id, $costitem, $txt_date, $price, $loginId);
				if (!$stmt3) {
					echo "failed to run";
					die;
				}
				$excute = $stmt3->execute();
				/* Logs */

				echo "<script>$(function(){toastr.success('Inserted Successfully'); }); </script>";
			}
		} else {
			$hiddenCostId = $_POST['hiddenCostId'];

			/* Check Duplicate Entries */
			$sqlQuery = "select * from site_cost_master where site_id=? and costitem=? and txt_date=? and id !=?";
			$stmtDuplicate = $conn->prepare($sqlQuery);
			$stmtDuplicate->bind_param("ssss", $site_id, $costitem, $txt_date, $hiddenCostId);
			if (!$stmtDuplicate) {
				echo "failed to run";
				die;
			}
			$stmtDuplicate->execute();
			$queryDup = $stmtDuplicate->get_result();
			$countDup = $queryDup->num_rows;
			/* Check Duplicate Entries */
			if ($countDup > 0) {
				echo "<script>$(function(){toastr.error('Duplicate Entry'); }); </script>";
			} else {
				$updateQuery = 'UPDATE site_cost_master SET costitem=?,txt_date=?,price=?,modifiedon=now() WHERE id=?';
				$stmt = $conn->prepare($updateQuery);
				$stmt->bind_param("ssss", $costitem, $txt_date, $price, $hiddenCostId);
				$stmt->execute();

				/* Logs */
				$query = "INSERT into site_cost_master_logs(cost_id,site_id, costitem, txt_date,price,createdBy)values(?,?,?,?,?,?)";
				$stmt3 = $conn->prepare($query);
				$stmt3->bind_param("ssssis", $hiddenCostId, $site_id, $costitem, $txt_date, $price, $loginId);
				if (!$stmt3) {
					echo "failed to run";
					die;
				}
				$excute = $stmt3->execute();
				/* Logs */
				$resStmt = $stmt->get_result();
				if ($stmt->affected_rows === 1) {
					echo "<script>$(function(){toastr.success('Record Updated Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
				}
			}
		}
	}
}


?>

<style>
	button.dt-button,
	div.dt-button,
	a.dt-button {
		color: gray;
		text-shadow: 0 0 black;
		background-position: 0;
		background-size: 0;
		background-repeat: no-repeat;
		background-color: white;
		border: 1px solid #fff;
		margin: 0;
		padding: 0;
		cursor: pointer;
		border: 1px solid #fff;
		background-color: #fff;
		background-image: #fff !important;
		font-weight: 400;
	}

	.dataTables_filter {
		width: 50%;
		float: right;
		text-align: right;
	}

	.error {
		color: red;
	}

	#data-container {
		display: block;
		background: #2a3f54;

		max-height: 250px;
		overflow-y: auto;
		z-index: 9999999;
		position: absolute;
		width: 100%;

	}

	#data-container li {
		list-style: none;
		padding: 5px;
		border-bottom: 1px solid #fff;
		color: #fff;
	}

	#data-container li:hover {
		background: #26b99a;
		cursor: pointer;
	}

	.form-control:focus {
		border-color: #d01010;
		outline: 0;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(233, 102, 139, 0.6);

	}

	#overlay {
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height: 100%;
		display: none;
		background: rgba(0, 0, 0, 0.6);
	}

	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}

	@keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}

	.is-hide {
		display: none;
	}
</style>

<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Site Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Site Master</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php if (in_array($loginId, $authUserSite, true)) { ?>
					<div class="input-field col s12 m12" id="rpt_container">
						<form method="POST" action="#">
							<?php $_SESSION["token"] = csrfToken();	?>
							<input type="hidden" name="token" value="<?php echo $_SESSION["token"]; ?>">
							<input type="hidden" name="hiddenId" id="hiddenId">
							<div class="input-field col s4 m4">
								<select id="loc_id" name="loc_id" class="form-control" required>
									<option value="">----Select----</option>
									<?php
									$sqlBy = 'select id,location from location_master';
									$myDB = new MysqliDb();
									$resultBy = $myDB->rawQuery($sqlBy);
									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										foreach ($resultBy as $key => $value) {
											echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
										}
									}
									?>
								</select>
								<label for="location" class="Active dropdown-active active">Location</label>
							</div>
							<div class="input-field col s4 m4">
								<div class="form-group">
									<input type="text" class="form-control" name="site" id="site" required />
									<label title="Select Site" for="site" class="Active">Site</label>
								</div>
							</div>
							<div class="input-field col s4 m4">
								<div class="form-group">
									<input type="number" class="form-control" name="capacity" id="capacity" required />
									<label title="Select capacity" for="capacity" class="Active">Capacity</label>
								</div>
							</div>
							<div class="input-field col s4 m4">
								<input type="text" id="mnth_year_site" name="mnth_year" class="form-control" required autocomplete="off" />
								<label for="mnth_year_site" class="Active">Month</label>
							</div><br>
							<div class="input-field col s4 m4" style="float: right;">
								<div class="form-group">
									<input type="submit" name="submit" value="Submit" class="btn waves-effect waves-light" />
								</div>
							</div>

						</form>
					</div>
				<?php } ?>
				<hr>
				<div>

					<div class="input-field col s4 m4">
						<input type="text" id="mnth_year_dt" name="mnth_year_dt" class="form-control" autocomplete="off" />
						<label for="mnth_year_dt" class="Active">Month</label>
					</div>
					<div class="input-field col s4 m3">
						<div class="form-group">
							<span name="submit123" value="submit123" class="btn waves-effect waves-light" onclick="getSiteMaster(mnth_year_dt.value)">Search</span>
						</div>
					</div>
					<div id="getIDTRdiv"></div>
					<br>
				</div>
				<!-- Modal -->
				<div id="myModal_content" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" style="height:500px;">
					<div class="modal-dialog modal-xl">
						<!-- Modal content-->
						<div class="modal-content" style="height:500px;">
							<h4 class="col s12 m12 model-h4">Seat Master </h4>
							<?php if (in_array($loginId, $authUserSite, true)) { ?>
								<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
									<div id="locHeader" style="color: #000;text-align: center;font-size: 18px;background-color: #ffc520;padding: 8px 0;font-weight: 500;"></div></br>
									<form method="post">
										<?php $_SESSION["seat_token"] = csrfToken();	?>
										<input type="hidden" name="seat_token" value="<?= $_SESSION["seat_token"] ?>">
										<input type="hidden" name="hiddenSeatId" id="hiddenSeatId">
										<input type="hidden" name="sitemasterid" id="sitemasterid">
										<input type="hidden" name="locationSeatId" id="locationSeatId">
										<div class="input-field col s4 m4">
											<select id="client_id" name="client_id" class="form-control" required onchange="changeClient(this.value,locationSeatId.value,'')">
												<option value="">----Select----</option>
												<?php
												/* $sqlBy = 'select client_id,client_name from client_master';
											$myDB = new MysqliDb();
											$resultBy = $myDB->rawQuery($sqlBy);
											$mysql_error = $myDB->getLastError();
											if (empty($mysql_error)) {
												foreach ($resultBy as $key => $value) {
													echo '<option value="' . $value['client_id'] . '"  >' . $value['client_name'] . '</option>';
												}
											} */
												?>
											</select>
											<label for="location" class="Active dropdown-active active">Client</label>
										</div>
										<div class="input-field col s4 m4">
											<select id="process" name="process" class="form-control" required>
											</select>
											<label for="process" class="Active dropdown-active active">Process</label>
										</div>
										<div class="input-field col s4 m4">
											<input type="number" id="seat" name="seat" class="form-control" required />
											<label for="seat" class="Active">Seat</label>
										</div>
										<div class="input-field col s4 m4">
											<input type="text" id="mnth_year" name="mnth_year" class="form-control" required autocomplete="off" />
											<label for="mnth_year" class="Active">Month</label>
										</div>
										<div class="input-field col s12 m12 right-align">

											<input type="submit" class="btn waves-effect waves-green" name="btn_seat_save" id="btn_seat_save" value="Submit" />

											<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Close</button>
										</div>
									</form>
									<br>
								</div>
							<?php } ?>
							<div id="hisseat"></div>
						</div>
					</div>
				</div>


				<div id="myModal_content_view" class="modal fade bs-example-modal-sm cost" tabindex="-1" role="dialog" aria-hidden="true" style="height:500px;">
					<div class="modal-dialog modal-sm">
						<!-- Modal content-->
						<div class="modal-content">
							<h4 class="col s12 m12 model-h4">Cost Master</h4>
							<?php if (in_array($loginId, $authUserSite, true)) { ?>
								<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">

									<div id="locHeaderCost" style="color: #000;text-align: center;font-size: 18px;background-color: #ffc520;padding: 8px 0;font-weight: 500;width:950; "></div></br>

									<form method="post" style="display:none;">
										<?php $_SESSION["cost_token"] = csrfToken();	?>
										<input type="hidden" name="cost_token" value="<?= $_SESSION["cost_token"] ?>">
										<input type="hidden" name="hiddenCostId" id="hiddenCostId">
										<input type="hidden" name="sitemasteridcost" id="sitemasteridcost">
										<div class="input-field col s4 m4">
											<select id="costitem" name="costitem" class="form-control" required>
												<option value="">----Select----</option>
												<?php
												$sqlBy = 'select id,item from costitem';


												$myDB = new MysqliDb();
												$resultBy = $myDB->rawQuery($sqlBy);
												$mysql_error = $myDB->getLastError();
												if (empty($mysql_error)) {
													foreach ($resultBy as $key => $value) {
														echo '<option value="' . $value['id'] . '"  >' . $value['item'] . '</option>';
													}
												}
												?>
											</select>
											<label for="location" class="Active">Cost Item</label>
										</div>
										<div class="input-field col s4 m4">
											<input type="text" class="form-control" name="txt_date" id="txt_date" autocomplete="off" />
											<label for="txt_date" class="Active">Month</label>
										</div>
										<div class="input-field col s4 m4">
											<input type="number" id="price" name="price" class="form-control" required />
											<label for="price" class="Active">Price</label>
										</div>
										<div class="input-field col s12 m12 right-align">

											<button type="submit" class="btn waves-effect waves-green" name="btn_cost_save" id="btn_cost_save" data-dismiss="modal">Submit</button>

											<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Close</button>
										</div>
									</form>
								</div>
							<?php } ?>
							<div id="hiscost"></div><br>
						</div>
					</div>
				</div>


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
		function editFunction(id, loc, site, capacity, mnth_year) {
			$('#hiddenId').val(id);
			$('#loc_id').val(loc);
			//$('#loc_id').addClass('Active');
			$('#site').val(site);
			$('#capacity').val(capacity);
			$('#mnth_year_site').val(mnth_year);
			$('select').formSelect();
		}
	});
</script>
<script>
	function getSiteMaster(dt) {
		// mnth_year_dt
		if (dt == '') {
			alert("Please select month");
			$('#mnth_year_dt').focus();
		}
		curntDateYear = dt;
		$.ajax({
			url: '../Controller/get_site_master_controller.php',
			type: 'GET',
			data: {
				dt: curntDateYear,
			},
			success: function(response) {
				//$('.defaultIDTR').hide();
				//$('.getIDTR').show();
				//console.log(response);
				$('#getIDTRdiv').html(response);
				$('#myTable').DataTable({
					dom: 'Bfrtip',
					"pageLength": 20,
					buttons: [{
							extend: 'excel',
							text: 'EXCEL',
							extension: '.xlsx',
							exportOptions: {
								columns: [3, 4, 5, 6, 7, 8, 9, 10, 11],
								modifier: {
									page: 'all'
								}
							},
							title: 'sitemaster'
						}

					]
					// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				});

			}
		});
	}

	function getClient(locId) {

		$('#locationSeatId').val(locId);
		$.ajax({
			url: '../Controller/get_client_controller.php',
			type: 'GET',
			data: {
				id: locId,
			},
			success: function(response) {

				$('#client_id').html(response);
			}
		});
	}
	$(document).ready(function() {
		var curntDateYear = '';
		var currentYear = (new Date).getFullYear();
		var currentMonth = (new Date).getMonth() + 1;

		if (currentMonth < 10) {
			currentMonth = '0' + currentMonth;
		}
		//alert(currentMonth);
		curntDateYear = currentYear + '-' + currentMonth;
		$('#mnth_year_dt').val(curntDateYear);
		getSiteMaster(curntDateYear);
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Can').trigger("click");

				$('#hiddenSeatId').val('');
				$('#sitemasterid').val('');
				$('#locationSeatId').val('');
				$('#client_id').val('');
				$('#process').val('');
				$('#seat').val('');
				$('#mnth_year').val('');
				$('#hiddenCostId').val('');
				$('#sitemasteridcost').val('');
				$('#costitem').val('');
				$('#txt_date').val('');
				$('#price').val('');

			}
		});


		// This code active label on value assign when any event trigger and value assign by javascript code.
		$("#myModal_content input,#myModal_content textarea, #myModal_content_view").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}
		});

		$('#txt_date,#mnth_year,#mnth_year_dt,#mnth_year_site').datetimepicker({
			timepicker: false,
			format: 'Y-m'
		});



	});

	function changeClient(id, locId, prcess) {
		const clientId = id;
		$.ajax({
			url: '../Controller/get_process_byid.php',
			type: 'GET',
			data: {
				clientId: clientId,
				prcess: prcess,
				locId: locId,
			},
			success: function(response) {
				//console.log("response", response);
				//alert(response);
				$("#process").html(response);
			}
		});
	}

	function editFunction(id, loc, site, capacity, mnth_year) {

		$('#hiddenId').val(id);
		$('#loc_id').val(loc);
		//$('#loc_id').addClass('Active');
		$('#site').val(site);
		$('#capacity').val(capacity);
		$('#mnth_year_site').val(mnth_year);
		$('select').formSelect();
	}

	function editSeatFunction(id, cid, prces, st, sitemasterid, loc_id, mnth_year) {
		$('#client_id').val(cid);
		$('#hiddenSeatId').val(id);
		changeClient(cid, loc_id, prces);
		$('#process').val(prces);
		$('#seat').val(st);
		$('#mnth_year').val(mnth_year);
		$('#sitemasterid').val(sitemasterid);
		$('select').formSelect();
	}

	function editCostFunction(costmasterid, costitem, txt_date, price, sitemasterid) {
		$('#hiddenCostId').val(costmasterid);
		$('#costitem').val(costitem);
		$('#txt_date').val(txt_date);
		$('#price').val(price);
		$('#sitemasteridcost').val(sitemasterid);
		$('select').formSelect();
	}

	function deleteFunction(id) {
		var confm = confirm("Are you sure to Delete ?");
		if (confm) {
			$.ajax({
				url: '../Controller/deleteSiteMaster.php',
				type: 'GET',
				data: {
					id: id,
				},
				success: function(response) {
					alert(response);
					location.reload();
				}
			});
		}
	}

	function deleteSeatFunction(id) {
		var confm = confirm("Are you sure to Delete?");
		if (confm) {
			$.ajax({
				url: '../Controller/deleteSeatMaster.php',
				type: 'GET',
				data: {
					id: id,
				},
				success: function(response) {
					toastr.success('Deleted Successfully');
					location.reload();
				}
			});
		}
	}

	function deleteCostFunction(id) {
		var confm = confirm("Are you sure to Delete?123");
		if (confm) {
			$.ajax({
				url: '../Controller/deleteCostMaster.php',
				type: 'GET',
				data: {
					id: id,
				},
				success: function(response) {
					toastr.success('Deleted Successfully');
					location.reload();
				}
			});
		}
	}
</script>