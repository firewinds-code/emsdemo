<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH.'AppCode/nHead.php');
ini_set('display_errors', '0');
require('../TCPDF/tcpdf.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$dateformat = date('j F Y', strtotime($_date1 = date('Y-m-d')));
$EmployeeID = $EmployeeName = $Designation = $rsnofleaving = $locationid = $loc = '';
$emailid = '';
$GetData = "select c.emailid,w.EmployeeID , w.EmployeeName , w.location ,w.Gender ,w.MarriageStatus, w.designation ,e.rsnofleaving,DATE_FORMAT(e.dol,'%Y-%m-%d') as dol ,td.description,e.createdon from whole_dump_emp_data as w inner join exit_emp e on w.EmployeeID=e.EmployeeID inner Join contact_details c on e.EmployeeID=c.EmployeeID left join termination_description td on  e.rsnofleaving=td.title where  cast(DATE_SUB(e.createdon, INTERVAL -3 DAY) as date)=cast(now() as date) and  e.disposition='TER';";
$myDB = new MysqliDb();
$resultsE = $myDB->query($GetData);

if (count($resultsE) > 0) {
    foreach ($resultsE as $val) {

        $EmployeeID = $val['EmployeeID'];
        $EmployeeName = $val['EmployeeName'];
        $Designation = $val['designation'];
        $rsnofleaving = $val['rsnofleaving'];
        $locationid = $loc = $val['location'];
        $Gender = $val['Gender'];
        $emailid = $val['emailid'];
        $dol = $val['dol'];
        $MarriageStatus = $val['MarriageStatus'];
        $description = $val['description'];
        //echo $description;
        if ($Gender == 'Female') {
            if ($MarriageStatus = 'Single') {
                $title = 'Ms.';
            } else {
                $title = 'Mrs.';
            }
        } else {
            $title = 'Mr.';
        }

        if (substr($EmployeeID, 0, 2) == 'AE' || substr($EmployeeID, 0, 2) == 'RS' || substr($EmployeeID, 0, 3) == 'OCM' || substr($EmployeeID, 0, 3) == 'RSM') {
            $companyname = 'Red Stone Consulting';
        } else {
            $companyname = 'Cogent E Services Ltd.';
        }

        $fullname = $EmployeeName;
        list($firstName, $lastName) = explode(' ', $fullname);

        if (strtoupper($Gender) == 'MALE') {
            $Title = "Mr.";
        } else {
            $Title = "Mrs.";
        }
        if ($loc == "1" || $loc == "2") {
            $target_dir = ROOT_PATH . "termination_pdf/";
        }
        if ($loc == "3") {

            $target_dir = ROOT_PATH . "Meerut/termination_pdf/";
        } else if ($loc == "4") {

            $target_dir = ROOT_PATH . "Bareilly/termination_pdf/";
        } else if ($loc == "5") {
            $target_dir = ROOT_PATH . "Vadodara/termination_pdf/";
        } else if ($loc == "6") {
            $target_dir = ROOT_PATH . "Manglore/termination_pdf/";
        } else if ($loc == "7") {
            $target_dir = ROOT_PATH . "Bangalore/termination_pdf/";
        } else if ($loc == "8") {
            $target_dir = ROOT_PATH . "Nashik/termination_pdf/";
        } else if ($loc == "9") {
            $target_dir = ROOT_PATH . "Anantapur/termination_pdf/";
        } else if ($loc == "10") {
            $target_dir = ROOT_PATH . "Gurgaon/termination_pdf/";
        } else if ($loc == "11") {
            $target_dir = ROOT_PATH . "Hyderabad/termination_pdf/";
        }
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0777, true);
        }
        ///// pdf creation
        // echo $target_dir;
        $pdfhead = "<h3>TERMINATION LETTER </h3>";
        $pdf = "<p><b>Date: </b>" . $dateformat . "</p></br></br>			
	<table><tr><td><b>" . $Title . " </b>" . $EmployeeName . "</td></tr>
	<tr><td><b>Employee Code: </b>" . $EmployeeID . "</td></tr><br>
	
    <p>It has been observed that your behavior has been inconsistent with the best practices followed by the company and has been found to be in violation of the set rules and responsibilities expected from an employee of this organization. Hence, your employment with the company is hereby terminated with immediate effect.</p>

    <p>You are hereby directed to return all the company property (physical/ digital) that may be in your possession immediately to your supervisor, following which your accounts may be settled.</p><br><br>

    <p>Yours truly,</p>
	<p><b>For " . $companyname . "</b></p></br>";
        $pdf1 = "<p>(S.K Garg)</p>
            <p><b>(Authorized Signatory)</b></p>";

        if ($companyname == 'Red Stone Consulting') {
            $location = 'Red Stone Consulting, 53, Madhav Kunj, Pratap Nagar, Agra - 282010, India <br> Website: https://redstonec.in/';
        } else {
            $Locationquery = "SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master  a Inner Join location_master b on a.locationId=b.id where a.locationId =? ";
            // $myDB = new MysqliDb();
            $location_array = array();
            // $Locationresult = $myDB->query($Locationquery);
            $stmt = $conn->prepare($Locationquery);
            $stmt->bind_param("i", $locationid);
            $stmt->execute();
            $Locationresult = $stmt->get_result();
            $count = $resultQry->num_rows;
            if ($Locationresult->num_rows > 0) {

                foreach ($Locationresult as $val) {

                    if ($locationid == "1" || $locationid == "2") {

                        if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
                            $location = '';
                            echo $location = $val['companyname'] . ' ' . $val['address'] . '<br> Website : www.cogenteservices.com';
                        }
                    } else {
                        if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
                            $location = '';
                            $location = $val['companyname'] . ' ' . $val['address'] . '<br> Website : www.cogenteservices.com';
                        } else {
                            $location = '';
                            $location = $val['companyname'] . ' ' . $val['address'] . '<br> Website : www.cogenteservices.com';
                        }
                    }
                }
            }
        }

        //$pdf2 = '<p style="text-align: center"><u>' . $location . '</u></p>';
        $pdf2 = "
	        <style>
	        p{
	            color:#958d8d;
	            text-align:center;
	            font-size:13px;
	            margin:15px 15px;
	        }
	        </style>
	            <br><br><hr><p>$location </p>";

        $filename = $EmployeeID . "_Termination.pdf";

        $path = $target_dir . $filename;
        //print_r($path);
        $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $tcpdf->SetTitle('Cogent|Termination Checklist');
        $tcpdf->SetMargins(10, 10, 10, 10);
        $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $tcpdf->setPrintHeader(false);
        $tcpdf->setPrintFooter(false);
        $tcpdf->setListIndentWidth(3);
        $tcpdf->SetAutoPageBreak(TRUE, 11);
        $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $tcpdf->AddPage();

        $tcpdf->SetFont('times', '', 10.5);
        $tcpdf->Image('../Style/img/sk_sign.jpeg', 10, 150, 30, 15, 'JPEG'); //sig
        if ($companyname == "Cogent E Services Ltd.") {
            $tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); //logo right cogent logo
        } else {
            $tcpdf->Image('../Style/images/redstone.jpg', 150, 20, 40, 10, 'JPG'); //logo right cogent logo
        }

        /*if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {
            if ('AE' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.JPG', 140, 5, 60, 30, 'JPG'); //logo right Aurum
            } else {
                $tcpdf->Image('../Style/images/newLogo cogent.jpg', 140, 5, 60, 30, 'JPG'); //logo right cogent logo
            }
        } elseif ($locationid == "3") {
            if ('OC' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.png', 140, 5, 60, 30, 'PNG'); //logo right Orium
            } else {
                $tcpdf->Image('../Style/images/newLogo cogent.jpg', 140, 5, 60, 30, 'JPG'); //logo right cogent logo
            }
        }*/

        //$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 10, $pdfhead, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'c');
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 0, $y = 40, $pdfhead, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 60, $pdf, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 170, $pdf1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 255, $pdf2, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

        $tcpdf->Output($path, 'F');

        if ($emailid != "") {
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = EMAIL_HOST;
            $mail->SMTPAuth = EMAIL_AUTH;
            $mail->Username = EMAIL_USER;
            $mail->Password = EMAIL_PASS;
            $mail->SMTPSecure = EMAIL_SMTPSecure;
            $mail->Port = EMAIL_PORT;
            $mail->setFrom(EMAIL_FROM,  'Cogent | Termination Letter');
            //$mail->AddAddress('kavya.singh@cogenteservices.com');
            $mail->AddAddress($emailid);
            $mail->addBCC('vijayram.yadav@cogenteservices.com');
            $mail->Subject = "Candidate Termination Letter";
            $mail->isHTML(true);
            $msg2 = 'Hi  ' . $Title . '  ' . $EmployeeName . ',<br/><br/>
			Please find attached your Termination letter which was proceed on ' . date("j F Y", strtotime($dol)) . '.<br/><br/>
			Thanks <br/> Cogent ';
            $mail->Body = $msg2;
            $mymsg = '';
            $response = '';
            $mail->AddAttachment($path);
            if (!$mail->send()) {
                $response =  'Mailer Error:' . $mail->ErrorInfo;
            } else {

                $response =  'Mail Send successfully';
            }
        } else {
            $response =  " $emailid Your emailid is not exist.";
        }

        ///////////////end mail

        //insert data
        $dt = new datetime();
        $dt = $dt->format('Y-m-d H:i:s');
        $Inerttermination = "insert into termination_ack (EmployeeID, Mail_response,file_name,email_id, Created_date,location_id)values(?,?,?,?,?,?);";
        $stmt = $conn->prepare($Inerttermination);
        $stmt->bind_param("sssssi", $EmployeeID, $response, $filename, $emailid, $dt, $locationid);
        $insert = $stmt->execute();
        if ($stmt->affected_rows === 1) {
            echo "<script>$(function(){ toastr.success('Successfully Inserted...') });</script>";
        } else {
            echo "<script>$(function(){ toastr.error('Your request is already submitted') });</script>";
        }
    }
}

?>

<style>
    .short,
    .weak {
        color: red;
    }

    .good {
        color: #e66b1a;
    }

    .strong {
        color: green;
    }
</style>
<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">TERMINATION LETTER </span>
    <div class="pim-container" id="div_main">
        <div class="form-div">
            <div class="schema-form-section ">
                <p style="text-align: right;">
                    <?php
                    if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8" || $locationid == "9") {
                        if ('AE' == substr($EmployeeID, 0, 2)) {
                    ?>
                            <img src="../Style/images/client_logo.JPG" style="width: 200px;height: 70px;" />
                        <?php
                        } else { ?>
                            <img src="../Style/images/newLogo cogent.jpg" style="width: 200px;height: 50px;" />
                            <?php }
                    } else {

                        if ($locationid == "3") {
                            if ('OC' == substr($EmployeeID, 0, 2)) {
                            ?>
                                <img src="../Style/images/client_logo.png" style="width: 200px;height: 70px;" />
                            <?php     }
                        } else {
                            ?>
                            <img src="../Style/images/newLogo cogent.jpg" style="width: 200px;height: 50px;" />
                    <?php
                        }
                    }

                    ?>

                </p><br />

                <h3 style="text-align: center;">TERMINATION LETTER</h3><br>
                <p><b><?php echo $dateformat; ?></b></p><br /><br />
                <P><b>Mr/Ms <?php echo  $Title . "  " . $firstName . " , "; ?></b></P>
                <P><b>Employee Code - <?php echo $EmployeeID; ?></b></P><br><br>

                <p>It has been observed that your behavior has been inconsistent with the best practices followed by the company and have been found to be in violation of the set rules and responsibilities expected from an employee of the organization.</p>

                <p>You are hereby directed to return all the company property (physical/ digital) that may be in your possession immediately to your supervisor, following which your accounts may be settled.</p><br><br>

                <p>Yours truly,</p>
                <p>For Cogent E Services Ltd.</p><br /><br>
                <p><img src="../Style/img/sk_sign.jpeg" style="width: 120px;height: 45px;" /></p>
                <p>Authorized Signatory</p>

                <p style="text-align: center"><u>
                        <?php
                        if (count($Locationresult) > 0) {
                            foreach ($Locationresult as $val) {
                                if ($locationid == "1" || $locationid == "2") {
                                    if ($val['sub_location'] == substr($EmployeeID, 0, 2)) {
                                        echo $val['companyname'] . "</b> " . $val['address'] . ' Website : www.cogenteservices.com';
                                    }
                                } else {
                                    if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
                                        echo $val['companyname'] . "</b> " . $val['address'] . ' Website : www.cogenteservices.com';
                                    } else {
                                        echo $val['companyname'] . "</b> " . $val['address'] . ' Website : www.cogenteservices.com';
                                    }
                                }
                            }
                        }
                        ?>



                    </u></p><br />
            </div>
        </div>
    </div>
</div>