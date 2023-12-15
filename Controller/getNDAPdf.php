<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
require('../TCPDF/tcpdf.php');
$loc = '';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") {
    $EmployeeID = clean($_REQUEST["id"]);
    $EmpName = $createdon = '';

    $sqlConnect = "select * from nda_policies_decl where EmployeeID=?";
    $stmt = $conn->prepare($sqlConnect);
    $stmt->bind_param("s", $EmployeeID);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_row();
    if ($res->num_rows > 0 && $res) {
        $EmployeeID = $result[1];
        $EmpName = $result[2];
        $createdon = $result[3];
    }
    $pdf1 = " <h3><u>NDA Policy Declaration</u></h3></br>";
    $pdf2 = '   <style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 99%;
    }

    #center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
        ALIGN=center;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: center;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
<table>
        <tr>
            <td rowspan="3"><img id="center" src="../Style/images/cogent-logo.png" width="80" height="40"></td>
            <td rowspan="2"></td>
            <td>Version No</td>
            <td>3.1</td>
        </tr>
        <tr>
            <td>Issue Date</td>
            <td>03rd Jan’21</td>
        </tr>
        <tr>
            <td>Document No: ISMS/FM /001</td>
            <td>Rev Date</td>
            <td>22 Dec 20</td>

        </tr>
        <tr>
            <td colspan="2" style="width: 50%;"><b>Employee Acknowledgement of Information Security Policy Document Form</b></td>
            <td>Security Classification</td>
            <td>Internal</td>
        </tr>
    </table><br>

    <p style="font-weight: bold; font-size:large;"><b>1.Overview</b></p>
    <p>Complete the following steps:</p>

    <p><b>i.</b> Read the Information Security Policy.</p>
    <p><b>ii.</b>Sign and date in the spaces provided below.</p>
    <p><b>iii.</b>Return the acknowledgement page only to the information security manager.</p>
    <br>
    <p style="font-weight: bold; font-size:large;"><b>Acknowledgement</b></p>
    <p>By signing below, I agree to the following terms:</p>
    <dl>
        <dt>
        <dd>
            <p><b>i.</b> I have been made aware of the Information Security Management System of Cogent which is compliant to ISO/IEC 27001:2013 Standard and the Information Security policy and practices and my roles responsibilities and contribution in maintaining and improving the organizations Information Security practices and Policies.</p>
            <p><b>ii. </b>I have read a copy of the “Information Security Policy V1.0” and accompanying Information Security policies and procedures and understood the same;</p>
            <p><b>iii.</b>I understand and agree that any computers, software, and storage media provided to me by the company contains proprietary and confidential information about Cogent and its customers or its vendors, and that this is and remains the property of the company at all times;</p>
            <p><b>iv.</b> I will protect confidential and personal information, whether on paper, microfilm or computer files, by following security procedures as established by my assigned work area.</p>
            <p><b>v.</b>I will not disclose customer information except when specifically allowed by Cogent rules, regulations and operating procedures.</p>
            <p><b>vi.</b>I agree that I shall not copy, duplicate (except for backup purposes as part of my job here at Cogent) , otherwise disclose, or allow anyone else to copy or duplicate any of this information or software;</p>
            <p><b>vii.</b> As a user of the Cogent System`s local and shared computer systems, I understand and agree to abide by the following acceptable use agreement terms. These terms govern my access to and use of the information technology applications, services and resources of the Cogent and the information they generate.</p>
            <p><b>viii.</b>Cogent has granted access to me as a necessary privilege in order to perform authorized job functions. I will not knowingly permit use of my entrusted access control mechanism for any purposes other than those required to perform authorized employment functions. These include logon identification, password, workstation identification, user identification, file protection keys or, production read or writes keys.</p>
            <p><b>ix.</b> I will not disclose information concerning any access control mechanism unless properly authorized to do so by my employer. I will not use any access mechanism that the Cogent has not expressly assigned to me. I will treat all information maintained on the Cogent computer systems as strictly confidential and will not release information to any unauthorized person.</p>
            <p><b>x.</b> I agree to abide by all applicable Cogent policies, procedures and standards that relate to the Cogent Information Security Policy and the Cogent Information Security Acceptable Use Policy. I will follow all the security procedures of the Cogent computer systems and protect the data contained therein.</p>
            <p><b>xi.</b>If I observe any incidents of non-compliance with the terms of this agreement, I am responsible for reporting them to the Information Security Officer and management.</p>
            <p><b> xii.</b>I understand that the Cogent Information Security Officer, or appropriate designated officials, reserve the right without notice to limit or restrict any individual`s access and to inspect, remove or otherwise alter any data, file, or system resource that may undermine the authorized use of any Cogent’s IT resources.</p>
            <p><b>xiii.</b> I understand that it is my responsibility to read and abide by this agreement, even if I do not agree with it. If I have any questions about the Cogent’s Information Security Acceptable Use

                Agreement, I understand that I need to contact my immediate supervisor or appropriate official for clarification.</p>
            <p><b>xiv.</b>By signing this agreement, I hereby certify that I understand the preceding terms and provisions and that I accept the responsibility of adhering to the same. I further acknowledge that should I violate this agreement, I will be subject to disciplinary action.</p>
            <p><b>xv.</b>I will not create, access, alter, delete, or release any Cogent records except as necessary to perform assigned duties.</p>
            <p><b>xvi.</b>I will follow all identification procedures and requirements before conducting transactions that alter an individual`s records or affect an individual`s eligibility status for Cogent services.</p>
            <p><b>xvii.</b>I agree that, if I leave Cogent for any reason, I shall immediately return to the company the original and copies of any and all software, computer materials, or computer equipment that I may have received from the company that is either in my possession or otherwise directly or indirectly under my control.</p>
            <p><b>xviii.</b>I will disclose confidential or personal information to another Cogent employee only if that employee has an official need to know in connection with his or her job duties.</p>
            <p><b>xix.</b>I will immediately report any knowledge of a violation of this policy to my immediate supervisor.</p>
            <p><b>xx.</b>I will complete the Information Security Awareness Training within 30 days of employment.</p>
            <p><b>xxi.</b>I understand that Cogent, as stated in the Information Security Policies, reserves the right to log, audit, and monitor any usage of company assets. I have no expectation of privacy when using corporate assets.</p>
            <p><b>xxii.</b>I understand that my failure to comply with this policy may result in disciplinary action or termination. I also understand that I may incur civil penalties and/or criminal prosecution as noted in the IT Act 2000 (amended 2008) and applicable state and central laws.</p>
        </dd>
        </dt>
    </dl><br>
    <p><b>Employee ID:</b>&nbsp;&nbsp;&nbsp;&nbsp; ' . $EmployeeID . '</p>
    <p><b>Employee name:</b>&nbsp;&nbsp;&nbsp;&nbsp; ' . $EmpName . '</p>
    <p><b>Date:</b> &nbsp;&nbsp;&nbsp;&nbsp; ' . $createdon . '</p>';

    $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
    $target_dir = ROOT_PATH . "NDA/";
    $filename = $EmployeeID . "_NDA.pdf";
    $path = $target_dir . $filename;


    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $tcpdf->SetTitle('Cogent|NDA Decleration Form');

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
