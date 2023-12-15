<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
require('../TCPDF/tcpdf.php');
$loc = '';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") {
    $EmpID = clean($_REQUEST['id']);
    $Name = $Address = $Fathername = $desig = $createdon = '';

    $sql = "select t1.EmployeeID, t1.EmpName,t1.Address,t1.fathername,t1.designation,t1.created_on,t2.Gender,case when acknowledge='accept' then 'Accept' else 'Decline' end as acknowledge from bajaj_finance_decl t1
        left join EmpID_Name t2 on t1.EmployeeID=t2.EmpID
        where EmployeeID=?";
    // $sql = "select EmployeeID, EmpName,Address,fathername,designation,created_on from bajaj_finance_decl where EmployeeID=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $EmpID);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultraw = $result->fetch_row();
    // print_r($resultraw);
    // die;
    $count = $result->num_rows;
    if ($count > 0) {
        $EmployeeID  = $resultraw[0];
        $EmpName  = $resultraw[1];
        $address = $resultraw[2];
        $fathername = $resultraw[3];
        $desig = $resultraw[4];
        $createdon = $resultraw[5];
        $Gender = $resultraw[6];
        $Ack = $resultraw[7];
        if ($Gender == "Male") {
            $gender_pre = "S/O";
        } else if ($Gender == "Female") {
            $gender_pre = "D/O";
        } else {
            $gender_pre = "S/O";
        }
    }

    $date = date('Y-m-d', strtotime($createdon));
    $date_day = date('d', strtotime($date));
    $date_year = date('y', strtotime($date));
    $date_month = date('F', strtotime($date));

    $pdf = "<h3><u>Model Code of Conduct (“Code”) for the Service Provider</u></h3></br>";

    $pdf1 = "<p>INDEX</p>
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

    <p>A declaration to be obtained from TMEs and BDEs by the <b>Service Provider</b> before assigning them their duties is annexed to this Code.</p>";

    $pdf2 = "<p><b>2. Tele-calling a prospect (a prospective customer)</b></p>

    <p>A prospect is to be contacted for sourcing a BFL product or BFL related product only under the following circumstances:</p>

    
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) When prospect has expressed a desire to acquire a product through BFL's internet site/call centre/branch or through the relationship manager at BFL or has &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;been referred to by another prospect/customer or is an existing customer of BFL who has given consent for accepting calls on other products of BFL.</p>

        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) When the prospect's name/telephone no/ address is available and has been taken from one of the lists/directories/databases approved by the Service Provider &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; manager/team leader, after taking his/ her consent.</p>
    

    <p>The TME should not call a person whose name/number is flagged in any 'do not disturb' list made available to him/her.</p>

    <p><b>3. When you may contact a prospect on telephone</b></p>

    <p>Telephonic contact must normally be limited between 0900 Hrs and 2100 Hrs. (TRAI Calling Window Adherence) However, it may be ensured that a prospect is contacted only when the call is not expected to inconvenience him/her.</p>

    <p>Calls earlier or later than the prescribed time period may be placed only under the following conditions:</p>

    
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) When the prospect has expressly authorized TME/BDE to do so either in writing or orally</p>
   

    <p><b>4. Can the prospect's interest be discussed with anybody else?</b></p>

    <p>Service Provider should respect a prospect's privacy. The prospect's interest may normally be discussed only with the prospect and any other individual/family member such as prospect's accountant/secretary /spouse, authorized by the prospect.</p>

    <p><b>5. Leaving messages and contacting persons other than the prospect.</b></p>

    <p>Calls must first be placed to the prospect. In the event the prospect is not available, a message may be left for him/her. The aim of the message should be to get the prospect to return the call or to check for a convenient time to call again. Ordinarily, such messages may be restricted to:</p>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) Please leave a message that (Name of officer) representing Service Provider called and requested to call back at (phone number).</p>

    <p>As a general rule, the message must indicate:</p>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) That the purpose of the call is regarding selling or distributing a financial product of BFL</p>

    <p><b>6. No misleading statements/misrepresentations permitted</b></p>
    <p>TME/BDE should not -</p>";


    $pdf3 = " <p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) Mislead the prospect on any service / product offered;<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b) Mislead the prospect about their business or organization's name, or falsely represent themselves.<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c) Make any false / unauthorized commitment on behalf of BFL for any facility/service.
    </p>

    <p><b>7. Telemarketing Etiquettes</b></p>
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
    -Check for understanding of 'Most Important Terms and Conditions' by the customer if he plans to buy the product <br>
    -Thank the customer for his/her time <br>
    </p>

    <p>
    POST CALL <br>
    -Provide feedback to BFL on customers who have expressed their desire to be flagged 'Do Not Disturb' <br>
    -Never call or entertain calls from customers regarding products already sold. Advise them to contact the Customer Service Staff of BFL.
    </p>

    <p><b>8. Gifts or bribes</b></p>

    <p>TME/BDE's must not accept gifts from prospects or bribes of any kind. Any TME/BDE offered a bribe or payment of any kind by a customer must report the offer to his/her management.</p>

    <p><b>9.Handling of letters & other communication</b></p>

    <p>Any communication sent to the prospect should be only in the mode and format approved by BFL.</p>

    <p><b>10. Declaration cum undertaking</b> to be obtained by the Service Provider from TMEs/ BDEs employed by them.</p>";

    $pdf4 = "<p>Dear Sir,</p>

    <p> I am working in your company as a $desig . My job profile, inter-alia, includes offering, explaining, sourcing, and assisting documentation of products and linked services to prospects of BFL.</p>

    <p>In the discharge of my duties, I am obligated to follow the Code attached to this document.</p>

    <p>I confirm that I have been explained the contents of the Code and I have read and understood and agree to abide by the Code.</p>

    <p>In case of any violation, non-adherence to the said Code, you shall be entitled to take such action against me as you may deem appropriate.</p>

    <p>Signed on this $date_day day of $date_month 20$date_year </p>

    <p>
        Employee Name :  $EmpName; <br><br>
        Address :  $address;  and $gender_pre  $fathername  <br><br>
        Employee ID :$EmployeeID; <br><br>
        I $Ack
    </p>

    <p><br />Date <br><b><span>$date</b></span></p><br><br>";




    // <p><br />Employee Name <br /><br /> <b><span>" . $Name . "</b></span><br/><br/>" . $createdon . "</p>";

    $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
    $target_dir = ROOT_PATH . "Bajaj_financeLtd/";
    $filename = $EmpID . "_Bajaj_financeLtd.pdf";
    $path = $target_dir . $filename;


    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $tcpdf->SetTitle('Cogent|Bajaj Finance Decleration Form');

    $tcpdf->SetMargins(10, 10, 10, 10);

    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $tcpdf->setPrintHeader(false);
    $tcpdf->setPrintFooter(false);
    $tcpdf->setListIndentWidth(3);

    $tcpdf->SetAutoPageBreak(TRUE, 11);

    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $tcpdf->AddPage();
    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 0, $y = 20, $pdf, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');
    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 45, $pdf1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

    $tcpdf->AddPage();
    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 20, $pdf2, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');

    $tcpdf->AddPage();
    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 20, $pdf3, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');

    $tcpdf->AddPage();
    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 20, $pdf4, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');

    ob_end_clean();
    $tcpdf->Output($path, 'D');
}
