<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__dir__ . '/../Config/init.php');

//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';

$month = date('m', strtotime('first day of last month'));
$year = date('Y', strtotime('first day of last month'));

$myDB =  new MysqliDb();
$QueryGetEmp = "select EmployeeName,t1.EmployeeID,emailid,ctc,dateofjoin from 
(select EmployeeID, dateofjoin from employee_map where emp_status='Active')t1
left join personal_details p on t1.EmployeeID=p.EmployeeID
left join contact_details c  on t1.EmployeeID=c.EmployeeID
left join salary_details s  on t1.EmployeeID=s.EmployeeID
where month(dateofjoin)=" . $month . " and year(dateofjoin)=" . $year . "
and cast(ctc as unsigned)>=21050";
//echo $QueryGetEmp; die;

//$QueryUpdate = $myDB->query($QueryUpdate);
$EmployeData = $myDB->query($QueryGetEmp);
if (!$myDB->getLastError()) {
    if (count($EmployeData) > 0) {
        foreach ($EmployeData as $e) {
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
                $mail->Subject = 'Health Insurance ';
                $mail->isHTML(true);
                $body_ = ' Dear <strong>' . $e['EmployeeName'] . ' (' . $e['EmployeeID'] . ') </strong>';
                $body_ .= '<br /><br />This is to inform you that as per your eligibility, you are been covered under Group Health Insurance & Accidental policy with a cashless hospitalization benefit of Rs Two lacs Fifty Thousand Health cover and Rs Three Lacs of Accidental Cover. The policy covers Maternity benefit of Rs 35000/- Normal & Rs 45000/- Cesarean applicable for Female employee&rsquo;s first two children delivery.';
                $body_ .= '<br /><br />Hence a premium of INR 270/- monthly will be deducted from your salary.';
                $body_ .= '<br /><br />Policy Number OG-22-1105-8402-00000007.';
                $body_ .= '<br /><br /><br />FAQs attached for your reference.';
                $body_ .= '<br /><br />Link for downloading the Health and Wellness Card is as under.';
                $body_ .= '<br /><br /><a href="https://hcm.bajajallianz.com/BagicHCM/Health_Ecard/hlth_idcrddwn.jsp">https://hcm.bajajallianz.com/BagicHCM/Health_Ecard/hlth_idcrddwn.jsp. </a>';
                $body_ .= '<br /><br />Wellness Card can be downloaded after 15th of the next month of the joining.';
                $body_ .= '<br /><br /><br />Step 1 : Please enter the Policy No  &nbsp;&nbsp;&nbsp;&nbsp;  OG-22-1105-8402-00000007';
                $body_ .= '<br /><br />Step 2 : Please enter your Employee Id   &nbsp;&nbsp;&nbsp;&nbsp;  XXXXXXXXXX';
                $body_ .= '<br /><br />Step 3 : Enter Captcha';
                $body_ .= '<br /><br /><br />Network hospital list   &nbsp;&nbsp;&nbsp;&nbsp;    <a href="https://general.bajajallianz.com/BagicNxt/hm/hmSearchState.do">https://general.bajajallianz.com/BagicNxt/hm/hmSearchState.do </a>';
                $body_ .= '<br /><br /><br />Claims can be lodged on';
                $body_ .= '<br /><br />Claim Process    <a href="https://www.bajajallianz.com/Corp/claims/health-insurance-claim-process.jsp">https://www.bajajallianz.com/Corp/claims/health-insurance-claim-process.jsp </a>';
                $body_ .= '<br /><br /><br />Request you to kindly keep a photocopy of all the documents before couriering the same at below address:';
                $body_ .= '<br /><br />Health Admin Team,';
                $body_ .= '<br /><br />Bajaj Allianz General Insurance';
                $body_ .= '<br /><br />2nd Floor, Bajaj Finserv Building, Survey No 208/1B';
                $body_ .= '<br /><br />Off Nagar Road, Behind Wikfield IT Park, Viman Nagar, Pune &#8211; 411014';
                $body_ .= '<br /><br /><br />Any Help Required &#8211; Call Toll Free No (1800-209-5858) &#8211; Share Policy No &#8211; Make a Query &#8211; Resolve';
                $body_ .= '<br /><br />OR';
                $body_ .= '<br /><br />Email : bagichelp@bajajallianz.co.in';
                $body_ .= '<br /><br /><br />If Query Still not Re-solved';
                $body_ .= '<br /><br /><br />Please contact Mr Pankaj Soin <u>8400786986</u> with complete details.';

                $body_ .= '<br /><br /><br /><div align="center" style="font-size:24px"> <strong><u>FAQ&rsquo;s on Insurance Benefits   </u></strong></div>';
                $body_ .= '<br /><br /><br /><div align="center"><strong> <u>Health Insurance</u></strong> </div>';

                $body_ .= '<br /><br /><br /><div style="font-size:14px">Q. Am I Covered from Day 1?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Yes</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Is my health insurance cashless?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Yes at network hospitals.</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Are my expenses before and prior to hospitalization covered?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Yes, 30 days in prior and 60 days post hospitalization bills can be reimbursed</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Does my health insurance cover OPD?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. No, it&rsquo;s for hospitalization for more than 24hours for treatment.</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q, Is their any capping on Room Rent or Diseases?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. No, there is no capping, however the claim is payable with Sum Insured only</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Do I need to pay any charges during hospitalization?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Yes, Admission Fees, Consumables, Registration etc (as per insurance company norms) need to be borne by employee</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Are day care procedures covered?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Yes</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Is there is any co-payment in my insurance?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. It&rsquo;s a No-Copay policy</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Can I claim for my existing disease?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Yes, you can</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. What if I have a personal health insurance, can I still avail the benefits?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Yes, you can either claim in this or your health policy</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Can I continue this policy, after leaving?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. No, this is available only during the course of your employment</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q.  Can I claim my premium under Tax Benefit?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. No, it&rsquo;s not claimable under Tax Benefit</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Is Maternity covered?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Yes, up to a limit 35000 - Normal & 45000 - Caesarian. Maternity Benefit will be applicable for first two children only</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Is pre and post Natal Charges covered under Maternity?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Pre and post natal is covered up to maternity sub-limit within maternity limit in case of hospitalization</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Is their any waiting period for Maternity?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. No, it&rsquo;s covered from Day 1</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Can my spouse claim maternity benefit if I have this policy?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. No, it&rsquo;s available for Employees</div>';

                $body_ .= '<br /><br /><br /><div align="center"><strong> <u>Accidental Insurance</u></strong></div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. What is Accidental Death Cover?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. 100% of the sum assured</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Is my cover valid outside office?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. Yes, its valid Worldwide</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q What is Education Benefit  -</div>';
                $body_ .= '<br /><div style="font-size:14px">A. 1% of SI or Rs. 5,000/- whichever is lesser per child below age of 19 years up to maximum two dependent children for 1 year.</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. Is hospitalization covered under Medical Expenses?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. First AID Reimbursement up to 40 % of the claimed amount or actual medical bills or 10% of CSI, whichever is less</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. What is Temporary Total Disability?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. 1% of the Sum Insured indicated under this benefit subject to maximum of Rs. 5,000/- per week for a maximum of 104 weeks.</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. What is Permanent Total Disability Coverage?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. 125% of the Sum Assured</div>';

                $body_ .= '<br /><br /><div style="font-size:14px">Q. What is Permanent Partial Disability?</div>';
                $body_ .= '<br /><div style="font-size:14px">A. As per the loss, there is as per the table under policy wording</div>';

                $mail->Body = $body_;
                if (!$mail->send()) {
                    //settimestamp('Login_NCNS','Email Not Sent');
                    $emailStatus = 'Mailer Error:' . $mail->ErrorInfo;
                } else {
                    //settimestamp('Login_NCNS','Email Sent');
                    $emailStatus =  'Mail Send successfully.';
                }
            }

            $myDB = new MysqliDb();
            $emailLogstatus = $myDB->rawQuery('insert into health_ins_mail_log set EmployeeID="' . $e['EmployeeID'] . '", emailid="' . $e['emailid'] . '",EmployeeName="' . $e['EmployeeName'] . '",email_status="' . addslashes($emailStatus) . '";');
        }
    }
}

exit;
