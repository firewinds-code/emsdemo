<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$locationid = $EmpName = $address = $fathername = $desig = '';

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmployeeID = clean($_SESSION['__user_logid']);
$EmployeeName = clean(strtoupper($_SESSION['__user_Name']));
$sqlquery = "select t1.EmployeeID,t1.EmployeeName,address_p,FatherName,case when df_id=74 or df_id=77 then 'Agent' else 'Support Staff' end as desig,t1.Gender from personal_details t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID join address_details t5 on t2.EmployeeID=t5.EmployeeID where t2.EmployeeID= ?";
$stmt = $conn->prepare($sqlquery);
$stmt->bind_param('s', $EmployeeID);
$stmt->execute();
$result = $stmt->get_result();
$resultraw = $result->fetch_row();
$count = $result->num_rows;
if ($count > 0) {
    $address = $resultraw[2];
    $fathername = $resultraw[3];
    $desig = $resultraw[4];
    $Gender = $resultraw[5];
    if ($Gender == "Male") {
        $gender_pre = "S/O";
    } else if ($Gender == "Female") {
        $gender_pre = "D/O";
    } else {
        $gender_pre = "S/O";
    }
}

$date_day = date('d');
$date_year = date('y');
$date_month = date('F');


if (isset($_POST['btn_ack'])) {
    $ackvalue = $_POST['ack_check'];
    $source = 'Web';
    $query = "insert into bajaj_finance_decl (EmployeeID, EmpName,Address,fathername,designation,acknowledge,source) values(?,?,?,?,?,?,?) ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $EmployeeID, $EmployeeName, $address, $fathername, $desig, $ackvalue, $source);

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

$remark = $empname = $empid = $searchBy = $msg = '';
$classvarr = "'.byID'";

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">BFL Model Code Of Conduct Declaration</span>

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
                <p style="width:100%;padding-top: 30px;"><span style="padding-left: 300px;font-size: 18px;">Model Code of Conduct (“Code”) for the Service Provider</span></p>
                <p>INDEX</p>
                <p>1. Applicability</p>
                <p>2. Tele-calling a prospect</p>
                <p>3. When you may contact a prospect on telephone</p>
                <p>4. Can the prospect's interest be discussed with anybody else?</p>
                <p>5. Leaving messages and contacting persons other than the prospect</p>
                <p>6. No misleading statements/misrepresentations permitted</p>
                <p>7. Telemarketing etiquettes</p>
                <p>8. Gifts or bribes or Unethical behavior</p>
                <p>9. Handling of letters & other communication</p>
                <p>10. Declaration cum undertaking</p>

                <p><b>1. Applicability</b></p>

                <p>Upon adoption and inclusion as part of agreement between Bajaj Finance Limited (“<b>BFL</b>”) and the Service Provider (“<b>Service Provider</b>”), this code will apply to all persons involved in marketing and distribution of any loan or other financial product of BFL. The direct selling agent (“<b>Service Provider</b>”) and its tele-marketing executives (“TMEs”) and field sales personnel, namely, business development executives (“BDEs”) must agree to abide by this code prior to undertaking any direct marketing operation on behalf of BFL. Any TME/BDE found to be violating this code may be blacklisted and such action taken be reported to BFL from time to time by the DSA. Failure to comply with this requirement may result in permanent termination of business of the DSA with BFL and may even lead to permanent blacklisting by the industry.</p>

                <p>A declaration to be obtained from TMEs and BDEs by the <b>Service Provider</b> before assigning them their duties is annexed to this Code.</p>

                <p><b>2. Tele-calling a prospect (a prospective customer)</b></p>

                <p>A prospect is to be contacted for sourcing a BFL product or BFL related product only under the following circumstances:</p>

                <ul>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) When prospect has expressed a desire to acquire a product through BFL's internet site/call centre/branch or through the relationship manager at BFL or has &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;been referred to by another prospect/customer or is an existing customer of BFL who has given consent for accepting calls on other products of BFL.</p>

                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) When the prospect's name/telephone no/ address is available and has been taken from one of the lists/directories/databases approved by the Service Provider &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; manager/team leader, after taking his/ her consent.</p>
                </ul>

                <p>The TME should not call a person whose name/number is flagged in any "do not disturb" list made available to him/her.</p>

                <p><b>3. When you may contact a prospect on telephone</b></p>

                <p>Telephonic contact must normally be limited between 0900 Hrs and 2100 Hrs. (TRAI Calling Window Adherence) However, it may be ensured that a prospect is contacted only when the call is not expected to inconvenience him/her.</p>

                <p>Calls earlier or later than the prescribed time period may be placed only under the following conditions:</p>

                <ul>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) When the prospect has expressly authorized TME/BDE to do so either in writing or orally</p>
                </ul>

                <p><b>4. Can the prospect's interest be discussed with anybody else?</b></p>

                <p>Service Provider should respect a prospect's privacy. The prospect's interest may normally be discussed only with the prospect and any other individual/family member such as prospect's accountant/secretary /spouse, authorized by the prospect.</p>

                <p><b>5. Leaving messages and contacting persons other than the prospect.</b></p>

                <p>Calls must first be placed to the prospect. In the event the prospect is not available, a message may be left for him/her. The aim of the message should be to get the prospect to return the call or to check for a convenient time to call again. Ordinarily, such messages may be restricted to:</p>

                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) Please leave a message that (Name of officer) representing Service Provider called and requested to call back at (phone number)".</p>

                <p>As a general rule, the message must indicate:</p>

                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) That the purpose of the call is regarding selling or distributing a financial product of BFL</p>

                <p><b>6. No misleading statements/misrepresentations permitted</b></p>
                <p>TME/BDE should not -</p>

                <p>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) Mislead the prospect on any service / product offered;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b) Mislead the prospect about their business or organization's name, or falsely represent themselves.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c) Make any false / unauthorized commitment on behalf of BFL for any facility/service.
                </p>

                <p>7. Telemarketing Etiquettes</p>
                <p>PRE CALL<br>
                    No calls prior to 0900 Hrs or post 2100 Hrs (TRAI Calling Window Adherence)
                </p>

                <p>
                    DURING CALL <br>
                    -Identify yourself, your company and your principal <br>
                    -Request permission to proceed <br>
                    -If denied permission, apologize and politely disconnect. <br>
                    -State reason for your call <br>
                    -Always offer to call back on landline, if call is made to a cell number <br>
                    -Never interrupt or argue <br>
                    -To the extent possible, talk in the language which is most comfortable to the prospect <br>
                    -Keep the conversation limited to business matters <br>
                    -Check for understanding of "Most Important Terms and Conditions" by the customer if he plans to buy the product <br>
                    -Thank the customer for his/her time <br>
                </p>

                <p>
                    POST CALL <br>
                    -Provide feedback to BFL on customers who have expressed their desire to be flagged "Do Not Disturb" <br>
                    -Never call or entertain calls from customers regarding products already sold. Advise them to contact the Customer Service Staff of BFL.
                </p>

                <p><b>8. Gifts or bribes</b></p>

                <p>TME/BDE's must not accept gifts from prospects or bribes of any kind. Any TME/BDE offered a bribe or payment of any kind by a customer must report the offer to his/her management.</p>

                <p><b>9.Handling of letters & other communication</b></p>

                <p>Any communication sent to the prospect should be only in the mode and format approved by BFL.</p>

                <p><b>10. Declaration cum undertaking</b> to be obtained by the Service Provider from TMEs/ BDEs employed by them.</p>

                <p>Dear Sir,</p>

                <p> I am working in your company as a <?php echo $desig; ?> . My job profile, inter-alia, includes offering, explaining, sourcing, and assisting documentation of products and linked services to prospects of BFL.</p>

                <p>In the discharge of my duties, I am obligated to follow the Code attached to this document.</p>

                <p>I confirm that I have been explained the contents of the Code and I have read and understood and agree to abide by the Code.</p>

                <p>In case of any violation, non-adherence to the said Code, you shall be entitled to take such action against me as you may deem appropriate.</p>

                <p>Signed on this <?php echo $date_day; ?> day of <?php echo $date_month ?> 20<?php echo $date_year ?> </p>

                <p>
                    Employee Name : <?php echo $EmployeeName; ?> <br>
                    Address : <?php echo $address; ?> and <?php echo $gender_pre; ?> <?php echo $fathername ?> <br>
                    Employee ID : <?php echo $EmployeeID; ?>
                </p>

                <p><br />Date <br><b><span><?php echo date('Y-m-d'); ?></b></span></p><br><br>

                <input type="radio" name="ack_check" id="accept" value="accept">
                <label id="lbl" for="accept">I Accept</label>

                <input type="radio" name="ack_check" id="decline" value="decline">
                <label for="decline">I Decline</label>

                <div class="input-field col s10 m10 right-align">
                    <button type="submit" name="btn_ack" id="btn_ack" onclick="return radioValidation();" class="btn waves-effect waves-green">Acknowledge</button>
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

    <script>
        function radioValidation() {

            var acknowledge = document.getElementsByName('ack_check');
            var genValue = false;

            for (var i = 0; i < acknowledge.length; i++) {
                if (acknowledge[i].checked == true) {
                    genValue = true;
                }
            }
            if (!genValue) {
                alert("Please Choose Either Accept or Decline");
                return false;
            }

        }
    </script>