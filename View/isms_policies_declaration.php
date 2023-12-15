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

$EmployeeID = clean(strtoupper($_SESSION['__user_logid']));
$EmployeeName = clean(strtoupper($_SESSION['__user_Name']));

if (isset($_POST['btnSave'])) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $query = "insert into isms_policies_decl (EmployeeID, EmpName) values(?,?) ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $EmployeeID, $EmployeeName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($stmt->affected_rows === 1) {
            $_SESSION['__isms_decl'] = 'No';
            echo "<script>$(function(){toastr.success('Acknowledge Successfully')})</script>";
            echo "<script>location.href='index.php'; </script>";
        } else {
            echo "<script>$(function(){toastr.error('Not Acknowledge')})</script>";
        }
        //echo "<script>$(function(){ toastr.warning('Allready acknowledged in this week'); }); </script>";

        //echo "<script>location.href='index.php'; </script>";
    }
}


?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">ISMS Policies Acknowledgement</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <!-- <h4>Covid-19 Form</h4>	-->
            <!-- Form container if any -->
            <div class="schema-form-section row">

                <?php $_SESSION["token"] = csrfToken(); ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
                <!--<table style="width:100%;">
				<tr>
					<td >To</td><td style="text-align: right;">Date:&nbsp;<?php echo date('d-m-Y'); ?> </td>
				</tr>
			</table>-->
                <p style="width:100%;padding-top: 30px;"><span style="padding-left: 350px;font-size: 18px;"><u>ISMS Policies Acknowledgement</u></span></p>


                <p style="padding-top: 25px;"><u>ISMS Do’s & Don’ts</u> </p>
                <p> • USB (Data Storage Device), Camera & Mobile Phones /Smart watches are not allowed on floor </p>
                <p> • Clear desk and clear screen: Don’t leave your computer / sensitive documents unlocked </p>
                <p> • Pen & Paper are not allowed on production floor </p>
                <p> • Don’t Share your unique password (Application / CRM etc.) with any body </p>
                <p> • Carry your Employee ID Card & Access Card </p>
                <p style="padding-top: 25px;"><u>ISMS Policies </u> </p>
                <p>Policies are available on the below mentioned link</p>
                <a href="https://ems.cogentlab.com/erpm/View/ISMS_policy" target="_blank">https://ems.cogentlab.com/erpm/View/ISMS_policy</a>
                <p>I <b><?php echo $EmployeeName; ?></b></p>
                <p>I hereby confirm that I have read the ISMS policies , ISMS Do’s & Don’ts and understand that it describes the conduct and behavior expected of me as an Employee of the Cogent E Services Limited.</p>


                <p><br />Employee Name:- <br /> <b><span><?php echo $EmployeeName; ?></b></span> </p>
                <p><br />Employee ID:- <br /> <b><span><?php echo $EmployeeID; ?></b></span> </p>


                <div class="input-field col s12 m12 right-align">
                    <button type="submit" name="btnSave" id="btnSave" class="btn waves-effect waves-green">Acknowledge</button>
                </div>
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