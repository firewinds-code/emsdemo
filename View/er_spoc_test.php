<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$emp_location = '';
if (isset($_SESSION)) {
	$user_logid = clean($_SESSION['__user_logid']);
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {


		$isPostBack = false;
		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST['txt_dept'])) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$dept = cleanUserInput($_POST['txt_dept']);
			}
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">ER SPOC</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>ER SPOC</h4>
            <style>
            .area1 {
                width: 100%;
                padding-bottom: 30px;
            }

            .area2 {
                width: 50%;
                float: left;
                text-align: right;
                padding-right: 10px;
                font-weight: bold;
                font-size: 14px;
            }

            .area3 {
                width: 50%;
                float: left;
                padding-left: 5px;
                font-size: 14px
            }

            .card-body {
                padding: 15px;
            }

            .card-body p {
                text-align: center;
            }

            .card-content {

                border-bottom: 1px solid #f0f0f0;
            }
            </style>
            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php
				$_SESSION["token"] = csrfToken();
				?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <?php

				$name1 = $desig1 = $con1 = ' - ';
				$cm_id = clean($_SESSION["__cm_id"]);
				$getDetails = 'SELECT er_scop, er_spoc2,er_spoc3,er_spoc4 FROM ems.new_client_master where cm_id=?';
				$selectQr = $conn->prepare($getDetails);
				$selectQr->bind_param("i", $cm_id);
				$selectQr->execute();
				$chk_task = $selectQr->get_result();
				$result_all = $chk_task->fetch_row();
				if (!empty($chk_task) && $chk_task->num_rows > 0 && $chk_task) {
					$er_scop = $result_all[0];
					$er_spoc2 = $result_all[1];
					$er_spoc3 = $result_all[2];
					$er_spoc4 = $result_all[3];
				}

				//  ############################### FOR ER SPOC 1 ###############################

				$cmid = clean($_SESSION["__cm_id"]);
				if ($er_scop && $er_scop != "") {
					$geterspoc1 = 'SELECT er_scop,t2.EmpName as er_scop_name,t5.Designation,t6.mobile,t7.location,t2.img,t2.loc FROM ems.new_client_master t1
					join EmpID_Name t2 on t1.er_scop=t2.EmpID
					join employee_map t3 on t2.EmpID=t3.EmployeeID
					join df_master t4 on t3.df_id=t4.df_id
					join designation_master t5 on t4.des_id=t5.ID
					join contact_details t6 on t2.EmpID=t6.EmployeeID 
					left join location_master t7 on t7.id=t2.loc where t1.cm_id=? ';
					$selectQ = $conn->prepare($geterspoc1);
					$selectQ->bind_param("i", $cmid);
					$selectQ->execute();
					$chk_tasks = $selectQ->get_result();
					$result_all1 = $chk_tasks->fetch_row();
					if (!empty($chk_tasks) && $chk_tasks->num_rows > 0 && $chk_tasks) {
						$erspoc1 = $result_all1[0];
						$name1 = $result_all1[1];
						$desig1 = $result_all1[2];
						$con1 = $result_all1[3];
						$loc1 = $result_all1[4];
						$er_spoc1_img = $result_all1[5];
						$ofc_loc = $result_all1[6];
					}

					// $ofc_loc = clean($_SESSION['__location']);
					if ($ofc_loc == "1" || $ofc_loc == "2") {
						$locationdir = "";
					} else if ($ofc_loc == "3") {
						$locationdir = "Meerut/";
					} else if ($ofc_loc == "4") {
						$locationdir = "Bareilly/";
					} else if ($ofc_loc == "5") {
						$locationdir = "Vadodara/";
					} else if ($ofc_loc == "6") {
						$locationdir = "Manglore/";
					} else if ($ofc_loc == "7") {
						$locationdir = "Bangalore/";
					} else if ($ofc_loc == "8") {
						$locationdir = "Nashik/";
					} else if ($ofc_loc == "9") {
						$locationdir = "Anantapur/";
					} else if ($ofc_loc == "10") {
						$locationdir = "Gurgaon/";
					}
					$img = '<img alt="user" style="height: 7.5rem;width: 90px;position: relative;right: 10px;border-radius: 10px;" src="';
					if ($er_spoc1_img != '') {
						$img .= URL . $locationdir . 'Images/' . $er_spoc1_img;
					} else {
						//$img .= URL . $locationdir . 'Images/' . $value['img'];
						$img .= "../Style/images/agent-icon.png";
					}
					$img .= '"/>';

				?>

                <div class="col s5 m5"
                    style="box-shadow: 0px 5px 10px 0px rgb(0 0 0 / 50%);margin: 8px 8px;height:200px;border-radius: 16px;margin-left:4rem;">
                    <div class="row">
                        <h4 style="text-align: center;">ER SPOC L1 </h4>
                        <div class="col s3 m3" style="margin: 10px 0;"><?php echo $img ?>
                        </div>
                        <div class="col s9 m9" style="font-size:10px;">

                            <table>

                                <tr>
                                    <td><b>Name</b></td>
                                    <td style="padding:0"><?php echo $name1 ?></td>
                                </tr>

                                <tr>
                                    <td><b>Location</b></td>
                                    <td style="padding:0"><?php echo $loc1 ?></td>
                                </tr>
                                <tr>
                                    <td><b>Designation</b></td>
                                    <td style="padding:0"><?php echo $desig1 ?></td>
                                </tr>
                                <tr>
                                    <td><b>Contact</b></td>
                                    <td style="padding:0"><?php echo $con1 ?></td>
                                </tr>

                            </table>

                        </div>
                    </div>
                </div>

                <?php } ?>




                <?php
				// ############################### FOR ER SPOC 2 ###############################
				if ($er_spoc2 && $er_spoc2 != "") {
					$geterspoc2 = 'SELECT er_spoc2,t2.EmpName as er_spoc_name2,t5.Designation,t6.mobile,t7.location,t2.img,t2.loc FROM ems.new_client_master t1
					join EmpID_Name t2 on t1.er_spoc2=t2.EmpID
					join employee_map t3 on t2.EmpID=t3.EmployeeID
					join df_master t4 on t3.df_id=t4.df_id
					join designation_master t5 on t4.des_id=t5.ID
					join contact_details t6 on t2.EmpID=t6.EmployeeID 
					left join location_master t7 on t7.id=t2.loc where t1.cm_id=? ';

					$selectQ = $conn->prepare($geterspoc2);
					$selectQ->bind_param("i", $cmid);
					$selectQ->execute();
					$chk_tasks = $selectQ->get_result();
					$result_all2 = $chk_tasks->fetch_row();
					if (!empty($chk_tasks) && $chk_tasks->num_rows > 0 && $chk_tasks) {
						$erspoc2 = $result_all2[0];
						$name2 = $result_all2[1];
						$desig2 = $result_all2[2];
						$con2 = $result_all2[3];
						$loc2 = $result_all2[4];
						$er_spoc2_img = $result_all2[5];
						$ofc_loc = $result_all2[6];
					}
					// echo $ofc_loc;
					if ($ofc_loc == "1" || $ofc_loc == "2") {
						$locationdir = "";
					} else if ($ofc_loc == "3") {
						$locationdir = "Meerut/";
					} else if ($ofc_loc == "4") {
						$locationdir = "Bareilly/";
					} else if ($ofc_loc == "5") {
						$locationdir = "Vadodara/";
					} else if ($ofc_loc == "6") {
						$locationdir = "Manglore/";
					} else if ($ofc_loc == "7") {
						$locationdir = "Bangalore/";
					} else if ($ofc_loc == "8") {
						$locationdir = "Nashik/";
					} else if ($ofc_loc == "9") {
						$locationdir = "Anantapur/";
					} else if ($ofc_loc == "10") {
						$locationdir = "Gurgaon/";
					}
					$img2 = '<img alt="user" style="height: 7.5rem;width: 90px;position: relative;right: 10px;border-radius: 10px;" src="';
					if ($er_spoc2_img != '') {
						$img2 .= URL . $locationdir .  'Images/' . $er_spoc2_img;
					} else {
						//$img2 .= URL . $locationdir . 'Images/' . $value['img'];
						$img2 .= "../Style/images/agent-icon.png";
					}
					$img2 .= '"/>';
				?>
                <div class="col s5 m5"
                    style="box-shadow: 0px 5px 10px 0px rgb(0 0 0 / 50%);margin: 8px 8px;height:200px;border-radius: 16px;margin-left:3rem;">
                    <div class="row">
                        <h4 style="text-align: center;">ER SPOC L2 </h4>
                        <div class="col s3 m3" style="margin: 10px 0;"><?php echo $img2 ?>
                        </div>
                        <div class="col s9 m9" style="font-size:10px;">

                            <table>

                                <tr>
                                    <td><b>Name</b></td>
                                    <td style="padding:0"><?php echo $name2 ?></td>
                                </tr>

                                <tr>
                                    <td><b>Location</b></td>
                                    <td style="padding:0"><?php echo $loc2 ?></td>
                                </tr>
                                <tr>
                                    <td><b>Designation</b></td>
                                    <td style="padding:0"><?php echo $desig2 ?></td>
                                </tr>
                                <tr>
                                    <td><b>Contact</b></td>
                                    <td style="padding:0"><?php echo $con2 ?></td>
                                </tr>

                            </table>

                        </div>
                    </div>
                </div>

                <?php
				} ?>

                <?php
				// ################################ FOR ER SPOC 3 ################################
				if ($er_spoc3 && $er_spoc3 != "") {
					$geterspoc3 = 'SELECT er_spoc3,t2.EmpName as er_spoc_name3,t5.Designation,t6.mobile,t7.location,t2.img,t2.loc FROM ems.new_client_master t1
					join EmpID_Name t2 on t1.er_spoc3=t2.EmpID
					join employee_map t3 on t2.EmpID=t3.EmployeeID
					join df_master t4 on t3.df_id=t4.df_id
					join designation_master t5 on t4.des_id=t5.ID
					join contact_details t6 on t2.EmpID=t6.EmployeeID
					left join location_master t7 on t7.id=t2.loc where t1.cm_id=? ';

					$selectQ = $conn->prepare($geterspoc3);
					$selectQ->bind_param("i", $cmid);
					$selectQ->execute();
					$chk_tasks = $selectQ->get_result();
					$result_all3 = $chk_tasks->fetch_row();
					if (!empty($chk_tasks) && $chk_tasks->num_rows > 0 && $chk_tasks) {
						$erspoc3 = $result_all3[0];
						$name3 = $result_all3[1];
						$desig3 = $result_all3[2];
						$con3 = $result_all3[3];
						$loc3 = $result_all3[4];
						$er_spoc3_img = $result_all3[5];
						$ofc_loc = $result_all3[6];
					}
					// echo $ofc_loc;
					if ($ofc_loc == "1" || $ofc_loc == "2") {
						$locationdir = "";
					} else if ($ofc_loc == "3") {
						$locationdir = "Meerut/";
					} else if ($ofc_loc == "4") {
						$locationdir = "Bareilly/";
					} else if ($ofc_loc == "5") {
						$locationdir = "Vadodara/";
					} else if ($ofc_loc == "6") {
						$locationdir = "Manglore/";
					} else if ($ofc_loc == "7") {
						$locationdir = "Bangalore/";
					} else if ($ofc_loc == "8") {
						$locationdir = "Nashik/";
					} else if ($ofc_loc == "9") {
						$locationdir = "Anantapur/";
					} else if ($ofc_loc == "10") {
						$locationdir = "Gurgaon/";
					}
					$img3 = '<img alt="user" style="height: 7.5rem;width: 90px;position: relative;right: 10px;border-radius: 10px;" src="';
					if ($er_spoc3_img != '') {
						$img3 .= URL . $locationdir .  'Images/' . $er_spoc3_img;
					} else {
						//$img3 .= URL . $locationdir . 'Images/' . $value['img'];
						$img3 .= "../Style/images/agent-icon.png";
					}
					$img3 .= '"/>';
				?>
                <div class="col s5 m5"
                    style="box-shadow: 0px 5px 10px 0px rgb(0 0 0 / 50%);margin: 8px 8px;height:200px;border-radius: 16px; margin-left:4rem;">
                    <div class="row">
                        <h4 style="text-align: center;">ER SPOC L3 </h4>
                        <div class="col s3 m3" style="margin: 10px 0;"><?php echo $img3 ?>
                        </div>
                        <div class="col s9 m9" style="font-size:10px;">

                            <table>

                                <tr>
                                    <td><b>Name</b></td>
                                    <td style="padding:0"><?php echo $name3 ?></td>
                                </tr>

                                <tr>
                                    <td><b>Location</b></td>
                                    <td style="padding:0"><?php echo $loc3 ?></td>
                                </tr>
                                <tr>
                                    <td><b>Designation</b></td>
                                    <td style="padding:0"><?php echo $desig3 ?></td>
                                </tr>
                                <tr>
                                    <td><b>Contact</b></td>
                                    <td style="padding:0"><?php echo $con3 ?></td>
                                </tr>

                            </table>

                        </div>
                    </div>
                </div>

                <?php } ?>

                <?php
				// ################################ FOR ER SPOC 4 ################################
				if ($er_spoc4 && $er_spoc4 != "") {
					$geterspoc4 = 'SELECT er_spoc4,t2.EmpName as er_spoc_name3,t5.Designation,t6.mobile,t7.location,t2.img,t2.loc FROM ems.new_client_master t1
					join EmpID_Name t2 on t1.er_spoc4=t2.EmpID
					join employee_map t3 on t2.EmpID=t3.EmployeeID
					join df_master t4 on t3.df_id=t4.df_id
					join designation_master t5 on t4.des_id=t5.ID
					join contact_details t6 on t2.EmpID=t6.EmployeeID 
					left join location_master t7 on t7.id=t2.loc where t1.cm_id=? ';

					$selectQ = $conn->prepare($geterspoc4);
					$selectQ->bind_param("i", $cmid);
					$selectQ->execute();
					$chk_tasks = $selectQ->get_result();
					$result_all4 = $chk_tasks->fetch_row();
					if (!empty($chk_tasks) && $chk_tasks->num_rows > 0 && $chk_tasks) {
						$erspoc4 = $result_all4[0];
						$name4 = $result_all4[1];
						$desig4 = $result_all4[2];
						$con4 = $result_all4[3];
						$loc4 = $result_all4[4];
						$er_spoc4_img = $result_all4[5];
						$ofc_loc = $result_all4[6];
					}

					if ($ofc_loc == "1" || $ofc_loc == "2") {
						$locationdir = "";
					} else if ($ofc_loc == "3") {
						$locationdir = "Meerut/";
					} else if ($ofc_loc == "4") {
						$locationdir = "Bareilly/";
					} else if ($ofc_loc == "5") {
						$locationdir = "Vadodara/";
					} else if ($ofc_loc == "6") {
						$locationdir = "Manglore/";
					} else if ($ofc_loc == "7") {
						$locationdir = "Bangalore/";
					} else if ($ofc_loc == "8") {
						$locationdir = "Nashik/";
					} else if ($ofc_loc == "9") {
						$locationdir = "Anantapur/";
					} else if ($ofc_loc == "10") {
						$locationdir = "Gurgaon/";
					}
					$img4 = '<img alt="user" style="height: 7.5rem;width: 90px;position: relative;right: 10px;border-radius: 10px;" src="';
					if ($er_spoc4_img != '') {
						$img4 .= URL . $locationdir .  'Images/' . $er_spoc4_img;
					} else {
						//$img4 .= URL . $locationdir . 'Images/' . $value['img'];
						$img4 .= "../Style/images/agent-icon.png";
					}
					$img4 .= '"/>';
				?>
                <div class="col s5 m5"
                    style="box-shadow: 0px 5px 10px 0px rgb(0 0 0 / 50%);margin: 8px 8px;height:200px;border-radius: 16px;margin-left:3rem;">
                    <div class="row">
                        <h4 style="text-align: center;">ER SPOC L4 </h4>
                        <div class="col s3 m3" style="margin: 10px 0;"><?php echo $img4 ?>
                        </div>
                        <div class="col s9 m9" style="font-size:10px;">

                            <table>

                                <tr>
                                    <td><b>Name</b></td>
                                    <td style="padding:0"><?php echo $name4 ?></td>
                                </tr>

                                <tr>
                                    <td><b>Location</b></td>
                                    <td style="padding:0"><?php echo $loc4 ?></td>
                                </tr>
                                <tr>
                                    <td><b>Designation</b></td>
                                    <td style="padding:0"><?php echo $desig4 ?></td>
                                </tr>
                                <tr>
                                    <td><b>Contact</b></td>
                                    <td style="padding:0"><?php echo $con4 ?></td>
                                </tr>

                            </table>

                        </div>
                    </div>
                </div>
                <?php
				} ?>

            </div>
            <!--Form container End -->
        </div>
        <!--Main Div for all Page End -->
    </div>
    <!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>