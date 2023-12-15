<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
require('../TCPDF/tcpdf.php');
$loc = '';

if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") {
    $EmpID = clean($_REQUEST['id']);
    $EmployeeID = $Address = $Fathername = $desig = $createdon = '';

    $sql = "select t1.EmployeeID,t1.EmployeeName,t1.FatherName,t1.designation,t1.location,t2.mobile,t1.created_on from tataaig_decl_self t1 left join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.EmployeeID='" . $EmpID . "'";
    $myDB = new MysqliDb();
    $result = $myDB->query($sql);
    $mysql_error = $myDB->getLastError();
    if (count($result) > 0 && $result) {
        $EmployeeID = $result[0]['EmployeeID'];
        $EmployeeName = $result[0]['EmployeeName'];
        $FatherName = $result[0]['FatherName'];
        $designation = $result[0]['designation'];
        $location = $result[0]['location'];
        $mobile = $result[0]['mobile'];

        $createdon = $result[0]['created_on'];
    }

    $createdon = date("j", strtotime($createdon)) . "<sup>" . date("S", strtotime($createdon)) . " </sup>" . date(" M", strtotime($createdon)) . ' ' . date("Y H:i:s", strtotime($createdon));

    $pdf1 = "<h3><u>Declaration</u></h3></br>";

    $pdf2 = "<p>1. I <b> " . $EmployeeName . " </b> son / daughter / wife of <b>" . $FatherName . "</b> have accepted the offer for the position of <b>" . $designation . "</b> for <b>" . $location . "</b> </p> 

    <p>2. I do not have an active license/code with any Insurance Company. </p>

    <p>3. I do not have any relatives who are working as Agents / Point of Sale Person / Broker/MISP- Motor Insurance Service Provider/ Corporate Agency/ Web-Aggregator or Vendor with Tata AIG General Insurance Company Limited (The relatives shall include spouse, brothers, sisters, parents, sons, daughter-in-law, son-in-law, brother-in-law, and Sister-in-law.).</p>

    <p>Employee ID : <b><span>" . $EmployeeID . "</span></b></p>
    <p>Mobile No : <b><span>" . $mobile . "</span></b></p>
    <p>Date and Time of Action: <b><span>" . $createdon . "</span></b></p>";

    $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
    $target_dir = ROOT_PATH . "TATAAIG/";
    $filename = $EmpID . "_TATAAIG_Self.pdf";
    $path = $target_dir . $filename;

    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $tcpdf->SetTitle('Cogent|TATA AIG Self Decleration Form');

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
