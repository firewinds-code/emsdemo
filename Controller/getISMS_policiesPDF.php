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
    $sql = "select EmployeeID, EmpName,createdon from isms_policies_decl where EmployeeID=? order by createdon desc limit 1";
    // die;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $EmpID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result_row = $result->fetch_row();
    // print_r($result_row);
    // $myDB = new MysqliDb();
    // $result = $myDB->query($sql);
    // $mysql_error = $myDB->getLastError();
    if (($result->num_rows > 0) && $result) {
        $EmployeeID = $result_row[0];
        $EmployeeName = $result_row[1];
        $createdon = $result_row[2];
    }
    $createdon_ = date("j", strtotime($createdon)) . "<sup>" . date("S", strtotime($createdon)) . " </sup>" . date(" M", strtotime($createdon)) . ' ' . date("Y H:i:s", strtotime($createdon));
    // die;
    $pdf1 = "<h3><u>ISMS Policies Acknowledgement</u></h3></br>";
    $pdf2 = "<p style='padding-top: 25px;'><u>ISMS Do’s & Don’ts</u> </p>
            <p> • USB (Data Storage Device), Camera & Mobile Phones /Smart watches are not allowed on floor </p>
            <p> • Clear desk and clear screen: Don’t leave your computer / sensitive documents unlocked </p>
            <p> • Pen & Paper are not allowed on production floor </p>
            <p> • Don’t Share your unique password (Application / CRM etc.) with any body </p>
            <p> • Carry your Employee ID Card & Access Card </p>
            <p style='padding-top: 25px;'><u>ISMS Policies </u> </p>
            <p>Policies are available on the below mentioned link</p>
            <a href='https://ems.cogentlab.com/erpm/View/ISMS_policy'>https://ems.cogentlab.com/erpm/View/ISMS_policy</a>
            <p>I <b> $EmployeeName  </b></p>
            <p><br />Employee Name:- <b><span> $EmployeeName </b></span> </p>
            <p><br />Employee ID:- <b><span> $EmployeeID  </b></span> </p>
            <p>Date and Time of Action:- <b><span> $createdon_  </b></span> </p>";

    $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
    $target_dir = ROOT_PATH . "ISMS_policies/";
    $filename = $EmpID . "_ISMS_policies.pdf";
    $path = $target_dir . $filename;

    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $tcpdf->SetTitle('Cogent|ISMS Policies Acknowledgement');

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
