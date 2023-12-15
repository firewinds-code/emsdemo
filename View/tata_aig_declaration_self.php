<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$locationid = $EmpName = $add = $fathername = $desig = '';

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmployeeID = clean(strtoupper($_SESSION['__user_logid']));
$EmployeeName = clean(strtoupper($_SESSION['__user_Name']));
$sqlqry = "select t1.EmpID,t1.EmpName,t2.location,t5.Designation,t6.mobile,t1.FatherName from EmpID_Name t1 
left join location_master t2 on t1.loc=t2.id
left join employee_map t3 on t1.EmpID=t3.EmployeeID
left join df_master t4 on t3.df_id=t4.df_id
left join designation_master t5 on t4.des_id=t5.ID
right join contact_details t6 on t1.EmpID=t6.EmployeeID where t1.EmpID= ?";

$stmt = $conn->prepare($sqlqry);
$stmt->bind_param("s", $EmployeeID);
$stmt->execute();
$result = $stmt->get_result();
$resultraw = $result->fetch_row();
$count = $result->num_rows;

if ($count > 0) {
    $location = $resultraw[2];
    $Designation = $resultraw[3];
    $mobile = $resultraw[4];
    $FatherName = $resultraw[5];
}
$date = date("Y-m-d");
// if (isset($_POST['ack_check']) == "accept") {
//     $status = "accept";
// } else {
//     $status = "decline";
// }

if (isset($_POST['btnSave'])) {
    $source = 'Web';
    $status = $_POST['ack_check'];
    $query = "insert into tataaig_decl_self (EmployeeID, EmployeeName,FatherName,designation,mobile,location,status,source) values(?,?,?,?,?,?,?,?) ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssisss", $EmployeeID, $EmployeeName, $FatherName, $Designation, $mobile, $location, $status, $source);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($stmt->affected_rows === 1) {
        echo "<script>$(function(){toastr.success('Acknowledge Successfully')})</script>";
        echo "<script>location.href='index.php'; </script>";
    } else {
        echo "<script>$(function(){toastr.error('Not Acknowledge')})</script>";
    }
    //echo "<script>$(function(){ toastr.warning('Allready acknowledged in this week'); }); </script>";
    //echo "<script>location.href='index.php'; </script>";
}
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">
    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">TATA AIG Self Declaration</span>
    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">
        <!-- Sub Main Div for all Page -->
        <div class="form-div">
            <!-- Header for Form If any -->
            <!-- <h4>Covid-19 Form</h4>	-->
            <!-- Form container if any -->
            <div class="schema-form-section row">
                <!--<table style="width:100%;">
				<tr>
					<td >To</td><td style="text-align: right;">Date:&nbsp;<?php echo date('d-m-Y'); ?> </td>
				</tr>
			</table>-->
                <p style="width:100%;padding-top: 30px;"><span style="padding-left: 350px;font-size: 18px;">Declaration</span></p>

                <p style="padding-top: 25px;">1. I <b><?php echo $EmployeeName; ?></b> son / daughter / wife of <b><?php echo $father_name; ?></b> have accepted the offer for the position of <b><?php echo $Designation; ?></b> for <b><?php echo $location; ?>.</b></p>

                <p>2. I do not have an active license/code with any Insurance Company. </p>

                <p>3. I do not have any relatives who are working as Agents / Point of Sale Person / Broker/MISP- Motor Insurance Service Provider/ Corporate Agency/ Web-Aggregator or Vendor with Tata AIG General Insurance Company Limited (The relatives shall include spouse, brothers, sisters, parents, sons, daughter-in-law, son-in-law, brother-in-law, and Sister-in-law.)</p>

                <p>Employee ID : <b><?php echo $EmployeeName; ?></b></p>
                <p>Mobile No : <b><?php echo $mobile; ?></b></p>
                <p>Date and Time of Action: <b><?php echo $date; ?></b></p>
                <form action="" method="post">
                    <div class="input-field col s12 m12">
                        <input type="radio" name="ack_check" id="ack_check1" value="Accept">
                        <label id="lbl" for="ack_check1">Accept</label>

                        <input type="radio" name="ack_check" id="ack_check2" value="Decline">
                        <label for="ack_check2">Decline</label>
                    </div>
                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" name="btnSave" id="btnSave" class="btn waves-effect waves-green">Acknowledge</button>
                    </div>
                </form>
            </div>


            <!--Form container End -->

        </div>
        <!--Main Div for all Page End -->
    </div>
    <!--Content Div for all Page End -->
</div>


<?php
//if($induction_popup_flag==1 && $totalDays!=0 && $flag1==0){
?>
<script src="../Script/bootstrap2.min.js"></script>
<style>
    .disablediv {
        pointer-events: none;
        opacity: 70% !important;
    }
</style>

<?php
//} 
?>