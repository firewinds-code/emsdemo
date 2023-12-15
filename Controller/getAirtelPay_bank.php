<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
require('../TCPDF/tcpdf.php');
$loc = '';

if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") {
    $EmpID = clean($_REQUEST['id']);
    $Name = $Address = $Fathername = $desig = $createdon = '';

    $sql = "select EmpName,Address,fathername,designation,created_on from airtel_pay_bank_decl where EmployeeID='" . $EmpID . "'";

    $myDB = new MysqliDb();
    $result = $myDB->query($sql);
    $mysql_error = $myDB->getLastError();
    if (count($result) > 0 && $result) {
        $Name = $result[0]['EmpName'];
        $Address = $result[0]['Address'];
        $Fathername = $result[0]['fathername'];
        $desig = $result[0]['designation'];
        $createdon = $result[0]['created_on'];
    }
    $createdon = date("j", strtotime($createdon)) . "<sup>" . date("S", strtotime($createdon)) . " </sup>" . date(" M", strtotime($createdon)) . ' ' . date("Y H:i:s", strtotime($createdon));


    $pdf1 = "<h3><u>Declaration</u></h3></br>";
    $pdf2 = "<p>This is to confirm that I <b> " . $Name . " </b> resident of <b>" . $Address . "</b> S/O, D/O <b>" . $Fathername . "</b> am working as <b>" . $desig . "</b> for Airtel Payment Bank Limited.</p>
    <p>I understand and agree to abide confidentiality to be maintained by myself related to my work assigned by Cogent E Services Limited. I hereby declare that I don’t have any criminal records in past. During my tenure with the Company and thereafter also till perpetuity, I agree and confirm that:</p>
     <p> &nbsp; &nbsp;&nbsp;&nbsp; a) Will ensure no recording or storage device, pen, paper taken on production floor  </p> 
     <p> &nbsp; &nbsp;&nbsp;&nbsp; b) Will ensure no information pertaining to the process is shared to anyone who is not a part of &nbsp; &nbsp;&nbsp;&nbsp;AIRTEL PAYMENT BANK process </p>
	<p> &nbsp; &nbsp;&nbsp;&nbsp; c) Will ensure no data is taken out of the production floor </p>
	<p> &nbsp; &nbsp;&nbsp;&nbsp; d) Will not misuse any customer information </p>
	<p> &nbsp; &nbsp;&nbsp;&nbsp; e) Will not Argue with customer or exhibit rude behavior </p>
	<p> &nbsp; &nbsp;&nbsp;&nbsp; f) Will not Use Profanity / sarcastic tone with the customer. </p>
	<p> &nbsp; &nbsp;&nbsp;&nbsp; g) Will not Exchange (asking/providing) personal (non-business related) information with the customers </p>
	<p> &nbsp; &nbsp;&nbsp;&nbsp; h) Will not Educate customer on process loopholes </p>
	<p> &nbsp; &nbsp;&nbsp;&nbsp; i) Will not share credentials such as passwords with anyone, not even with supervisor. </p>

	<p>I do hereby agree that any non-compliance to the terms as defined under this declaration or any deviation from the Code of Conduct of the Company, then it may lead to disciplinary action against me that may include termination of my employment or criminal prosecution under applicable laws or financial recovery as decided by the Company in future related to my default.</p>

    <p><br />Employee Name <br /><br /> <b><span>" . $Name . "</b></span><br/><br/>" . $createdon . "</p>";

    $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
    $target_dir = ROOT_PATH . "AirtelPayment_bank/";
    $filename = $EmpID . "_AirtelPayment.pdf";
    $path = $target_dir . $filename;


    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $tcpdf->SetTitle('Cogent|Airtel Payment Bank Decleration Form');

    $tcpdf->SetMargins(10, 10, 10, 10);

    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $tcpdf->setPrintHeader(false);
    $tcpdf->setPrintFooter(false);
    $tcpdf->setListIndentWidth(3);

    $tcpdf->SetAutoPageBreak(TRUE, 11);

    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $tcpdf->AddPage();

    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 0, $y = 20, $pdf1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');
    //$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 60, $pdf1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 55, $pdf2, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');
    ob_end_clean();
    $tcpdf->Output($path, 'D');
}
