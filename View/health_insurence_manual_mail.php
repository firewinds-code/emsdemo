<?php
require_once(__dir__ . '/../Config/init.php');

//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
ini_set('display_errors', '0');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

$month = date('m', strtotime('first day of last month'));
$year = date('Y', strtotime('first day of last month'));

$myDB =  new MysqliDb();
$QueryGetEmp = "select EmployeeName,t1.EmployeeID,emailid,dateofjoin,DOB from 
(select EmployeeID, dateofjoin from employee_map where emp_status='Active')t1
join health_insurence_manual t2 on t1.EmployeeID=t2.EmployeeID
left join personal_details p on t1.EmployeeID=p.EmployeeID
left join contact_details c  on t1.EmployeeID=c.EmployeeID where t2.flag=0";

//echo $QueryGetEmp;
//$QueryUpdate = $myDB->query($QueryUpdate);
$EmployeData = $myDB->query($QueryGetEmp);
if (!$myDB->getLastError()) {
    if (count($EmployeData) > 0) {
        foreach ($EmployeData as $e) {
            // echo "<pre>";
            // print_r($e);
            // die;
            if (!empty($e['emailid'])) {
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = EMAIL_HOST;
                $mail->SMTPAuth = EMAIL_AUTH;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASS;
                $mail->SMTPSecure = EMAIL_SMTPSecure;
                $mail->Port = EMAIL_PORT;
                $mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
                $mail->AddAddress($e['emailid']);
                $emailAddress = $e['emailid'];
                $DOB = date_create($e['DOB']);
                $DOB = date_format($DOB, "d-F-Y");
                $mail->Subject = 'Health Insurance ';
                $mail->isHTML(true);
                $body_ = ' Dear <strong>' . $e['EmployeeName'] . ' (' . $e['EmployeeID'] . ') </strong>';
                $body_ .= "<br /><br />This is to inform you that as per your eligibility, you are been covered under Group Health Insurance & Accidental policy with a cashless hospitalization benefit of Rs Two lacs Fifty Thousand Health cover and Rs Three Lacs of Accidental Cover. The policy covers Maternity benefit of Rs 35000/- Normal & Rs 45000/- Cesarean applicable for Female employee's first two children delivery.";
                $body_ .= '<br /><br />Hence a premium of INR 270/- monthly will be deducted from your salary.';
                $body_ .= '<br /><br />Wellness Card can be downloaded after 15th of the month.';
                $body_ .= '<br /><br />Process for downloading ICICI Lombard App for E Cards, Claims, Network Hospital and other related services';
                $body_ .= '<br /><br />ILTake Care application :';
                $body_ .= '<br /><ul><li>Android users: <a href="https://play.google.com/store/apps/details?id=icici.lombard.ghi">Play Store   or    bit.ly/ILTCaos </a></li></ul>';
                $body_ .= '<ul><li>IOS  users: <a href=" https://apps.apple.com/in/app/iltakecare/id1462026635">App Store     or   bit.ly/ILTCios</a></li></ul>';
                $body_ .= '<br /><br /><br /><b>Step 1</b>';
                $body_ .= '<br /><ul><li>Download App</li></ul>';
                $body_ .= '<ul><li>Enter your Mobile No (Personal)</li></ul>';
                $body_ .= '<ul><li>Enter OTP sent on your personal Mobile No</li></ul>';
                $body_ .= '<ul><li>Fill personal details in Policy (in health segment)</li></ul>';
                $body_ .= '<ul><li><br><ul><li>Company Name  - <b>M/s Cogent E Services Ltd * -</b> It will Auto Pop Up </li></ul><br><ul><li>Employee Id - ' . $e['EmployeeID'] . '</li></ul><br><ul><li>Date of Birth - ' . $DOB . '</li></ul><br><b>* Ensure exact name of company is filled with M/S prefix</b></li></ul>';
                $body_ .= '<br /><b>Step 2</b>';
                $body_ .= '<br /><ul><li>Policy Successful Added</li></ul>';
                $body_ .= '<br /><b>Step 3</b>';
                $body_ .= '<br /><ul><li>E-Card  download. Verify details</li></ul>';
                $body_ .= '<br /><br /><b>Reimbursement Process for Non-network hospitals Claim process and documents to be attached :</b>';
                $body_ .= '<br /><ol><li>Go to Claim Form in ICICI Lombard App.</li><br><li>Fill the Claim form Duly (Part B by Treating Doctor Only and Stamp Sign of Hospital) and attach following documents</li><br><li>Authorization Form (pre-approval from ICICI if planned surgery)</li><br><li>Discharge Summary</li><br><li>Hospital Bills </li><br><li>Hospital Payment Receipt </li><br><li>Photo Identity Proof Copy</li><br><li>Investigation Reports/Reports Name</li><br><li>Medicine/Pharmacy Bills with Doctors Prescription</li><br><li>Implant Name and Invoice (If any) </li><br><li>Indoor Case Papers (duplicate copy) </li><br><li>Others</li><br><li>Health ID Card Copy</li><br><li>Courier Address : ICICI Lombard Healthcare, ICICI Bank Tower, Plot No. 12, Financial District, Nanakram Guda, Gachibowli, Hyderabad, Telangana-500032 </li></ol>';
                $body_ .= '<br /><br />
                Note : Original Bills are mandatory to be attached with claim form.';
                $body_ .= '<br /><br />Please contact Mr Pankaj Soin <a href="tel:8400786986">8400786986</a> with complete details.';

                // $path = "C:\wamp64\www\ems\Docs\Insurance\Cogent Employee Benefit.pdf";
                // $path = "../Docs/Insurance/Cogent Employee Benefit.pdf";
                // $mail->AddAttachment($path);
                $mail->Body = $body_;
                //echo $body_;
                // die;
                //C:\wamp64\www\ems\Docs\Insurance

                if (!$mail->send()) {
                    //settimestamp('Login_NCNS','Email Not Sent');
                    $emailStatus = 'Mailer Error:' . $mail->ErrorInfo;
                } else {
                    //settimestamp('Login_NCNS','Email Sent');
                    $emailStatus =  'Mail Send successfully.';
                }
            }

            $myDB = new MysqliDb();
            $emailLogstatus = $myDB->rawQuery('update health_insurence_manual set email_id="' . $e['emailid'] . '",flag=1,status="' . addslashes($emailStatus) . '",email_sent_on=now() where EmployeeID="' . $e['EmployeeID'] . '"');


            $emailLogstatus = $myDB->rawQuery('insert into health_ins_mail_log set EmployeeID="' . $e['EmployeeID'] . '", emailid="' . $e['emailid'] . '",EmployeeName="' . $e['EmployeeName'] . '",email_status="' . addslashes($emailStatus) . '";');
        }
    }
}

exit;
