 <?php  
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');

// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
require(ROOT_PATH.'AppCode/nHead.php');
require('../TCPDF/tcpdf.php');
include_once("../Services/sendsms_API1.php");
$EmployeeID=strtoupper($_SESSION["__user_logid"]);  
$location= URL.'Error';
	if(in_array($_SESSION['__user_logid'],$NotApplicable))
	{
		header("Location: $location");
		exit();
	}
	else if(substr($_SESSION['__user_logid'],0,3)=='CCE' || substr($_SESSION['__user_logid'],0,3)=='EXT')
	{
		header("Location: $location");
		exit();
	}
	else
	{

		$query="Select status,fetcheEmail from appointmentlonline where EmployeeID='".$EmployeeID."'";
		$myDB = new MysqliDb();
		$dataArray = $myDB->rawQuery($query);
		$disable='';

		if(count($dataArray)<1)
		{
			$myDB=new MysqliDb();
			$select_email_array=$myDB->rawQuery("select EmployeeName,b.cm_id from personal_details a inner Join employee_map b on a.EmployeeID=b.EmployeeID where  a.EmployeeID='".$EmployeeID."'");
			$cm_id='';
			if(isset($select_email_array[0]['cm_id']))
			{
				$cm_id=$select_email_array[0]['cm_id'];
				$EmployeeName=$select_email_array[0]['EmployeeName'];
			}
			$myDB=new MysqliDb();
			$insert_Query=$myDB->rawQuery("Insert Into appointmentlonline set EmployeeID='".$EmployeeID."',EmpName='".$EmployeeName."',cm_id='".$cm_id."',CreateDate='".date('Y-m-d')."',status='0',source_from='web427'");
			
			
			echo "<input type='hidden' name='hempid' id='hempid' value='".$EmployeeID."'>";
			 //echo "<input type='hidden' name='dirloc' id='dirloc' value='".$dir_location."'>";
			?>
			<!--<script>
				var txtEmployeeID=$('#hempid').val();
				var dirLoc=$('#dirloc').val();
				var Comment='Validated_by_emp';
				//alert('ALetter_download_multipdf2.php?EmpID='+txtEmployeeID+"&dirloc="+dirLoc+'&Comment='+Comment);
				window.open('ALetter_download_multipdf2.php?EmpID='+txtEmployeeID+"&dirloc="+dirLoc+'&Comment='+Comment, '_blank');
			</script>-->
		<?php
		}
	}
$disable="";
		$message="";
if(count($dataArray)>0)
{
	if($dataArray[0]['status']==1)
	{
		
		$message="You have already received your Appointment Letter on your email ".$dataArray[0]['fetcheEmail'];
		$disable="disabled='disabled'";
	}
	else if($dataArray && $dataArray[0]['status']==0)
	{
		$disable="";
		$message="";
	}
}/*else{
	$message='You are not eligible for Appointment Letter';
	$disable="disabled='disabled'";
}*/

	if(isset($_POST['emailAddress']) && trim($_POST['emailAddress'])!="" )
	{
	
		#region
		
		$GetData = "select whole_details_peremp.EmployeeID,EmployeeName,location,DOJ,designation,ctc,status_table.onFloor,des_id,function,Gender,tehsil from whole_details_peremp left outer join salary_details on salary_details.EmployeeID = whole_details_peremp.EmployeeID left outer join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID left join address_details on whole_details_peremp.EmployeeID = address_details.EmployeeID where whole_details_peremp.EmployeeID='".$EmployeeID."'";
//Flag=0";
	$myDB = new MysqliDb();
	function dateFormatu($data)
	{
	    return date("j", strtotime($data)) . "<sup>" . date("S", strtotime($data)) . " </sup>" . date(" M Y", strtotime($data));
	}
	$dateformat = dateFormatu($date = date('Y-m-d'));
	$resultsE = $myDB->query($GetData);
	if (count($resultsE) > 0) 
	{
    	foreach ($resultsE as $val)
    	{
        $EmployeeID = $val['EmployeeID'];
        $EmployeeName = $val['EmployeeName'];
        $locationid = $loc = $val['location'];
        $designation = $val['designation'];
        $ctc = $val['ctc'];
        $annual = number_format($ctc * 12,0);
        $monthly = number_format($ctc,0);
        $tehsil = $val['tehsil'];
        $var_desg_id = intval($val['des_id']);
        if($var_desg_id ==9 || $var_desg_id == 12)
        {
			$doj = $val['onFloor'];
		}
		else
		{
			$doj = $val['DOJ'];	
		}
        
        
        //$DOJ = date('j M Y', strtotime($doj));
        $DOJ = dateFormatu($date = $doj);

        $Gender = $val['Gender'];
        if ($val['Gender'] == 'Male') {
            $Gender = 'Mr';
        } else {
            $Gender = 'Ms';
        }
		
		if($var_desg_id ==9 || $var_desg_id == 12)
		{
			if(substr($EmployeeID,0,2)=='AE' || substr($EmployeeID,0,2)=='RS' || substr($EmployeeID,0,3)=='OCM' || substr($EmployeeID,0,3)=='RSM')
			{
				$companyname='Red Stone Consulting';
				$company = 'Redstone';
				$company1='Red Stone Consulting';
							
			}
			else
			{
				$companyname='Cogent E Services Limited';
				$company = 'Cogent';
				$company1='Cogent E Services Ltd';
			}
		}
		else if ($var_desg_id == 30) //for field executive
        {
            $companyname = 'Red Stone Consulting';
            $company = 'Redstone';
            $company1 = 'Red Stone Consulting';
        }
		
		else
		{
			$companyname='Cogent E Services Limited';
			$company = 'Cogent';
			$company1='Cogent E Services Ltd';
		}
		
		
        $filename = $EmployeeID . ".pdf";

        $Locationquery = "SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master a Inner Join location_master b on a.locationId=b.id where a.locationId ='" . $locationid . "' ";


        $myDB = new MysqliDb();
        $location_array = array();
        $Locationresult = $myDB->query($Locationquery);
        // var_dump($Locationresult);
        // exit;
        if ($loc == "1" || $loc == "2") {
            $target_dir = ROOT_PATH . "appointpdf/";
        }
        if ($loc == "3") {

            $target_dir = ROOT_PATH . "Meerut/appointpdf/";
        } else if ($loc == "4") {

            $target_dir = ROOT_PATH . "Bareilly/appointpdf/";
        } else if ($loc == "5") {
            $target_dir = ROOT_PATH . "Vadodara/appointpdf/";
        } else if ($loc == "6") {
            $target_dir = ROOT_PATH . "Manglore/appointpdf/";
        } else if ($loc == "7") {
            $target_dir = ROOT_PATH . "Bangalore/appointpdf/";
        }
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0777, true);
        }

		$flag=0;
		if(in_array($var_desg_id,array(9,12)))//For CSA Only 
		{
			$flag = 1;
			//$page1="<p>Date:".$dateformat."</p>";
         $pdfheading = "<h3>APPOINTMENT LETTER</h3></br>";

        $page1 .= "<table>
	<tr><td><b>Date : </b>" . $dateformat . "</td></tr><br>
	<tr><td><b>" . $Gender . " : </b>" . $EmployeeName . "</td></tr><br></table>
	
 
<p>We are pleased to appoint you in <b>" . $companyname . "</b> (<i>hereafter referred to as</i> <b>“Company”</b>) as  '" . $designation . "' at our organization as per the employment terms and conditions stated below. Please note that the employment terms contained in this letter are subject to Company policy.</p>

<p>Your effective date of appointment is " . $DOJ . ". The term of your employment with the Company shall commence on the effective date and shall continue unless this Appointment Letter is terminated by either party in accordance with the terms of separation mentioned in this letter. </p>

<p><b>Your employment with us will be governed by certain terms & conditions of employment which are mentioned below-</b></p>

<style>
dt,dd{
    font-size: 14px;
    text-align:justify;
}
p{
    font-size: 14px;
    text-align:justify;
}
</style>

    <dt><b>1. Compensation-</b>Your cost to the company (CTC) will be Rs. (" . $annual . ") annually.</dt>
    <br>
    
    <dt><b>2. Service Conditions-</b> You shall be governed by the rules and regulations and such other practices, systems, procedures, and policies in existence or established by the Company from time to time. 
    </dt>
    <br>

    <dt><b>3. E-Induction-</b> You will be a part of the e-induction procedure to make you familiarize with the Company policies and day-to-day working. 
    </dt>
    <br>

    <dt><b>4. Assignment / Transfer-</b>Your usual place of work will be " . $Locationresult[0]['location'] . ". However, during your service with the Company you shall be liable to be posted/ transferred to specific projects, assignments, jobs, etc. in which case you will be required to perform your services at such location, division, department, or branch of the Company as the Company may deem fit.
    </dt>
    <br>

    <dt><b>5. Duties & Obligation-</b></dt>      
    <dd>5.1 You must effectively, diligently and to the best of your ability perform all responsibilities and obligations.
    </dd>

    <dd>5.2 You will be in whole time service /employment of the Company and shall not engage directly or indirectly in any other work either part-time or fully.
    </dd>

    <dd>5.3 You shall act loyally and faithfully to the Company and obey the orders or instructions of the management of the Company.
    </dd>

    <dd>5.4 You shall always maintain high standards of secrecy of confidential records, documents and information relating to the business which may be known to you and shall use them always in the best interest of the company. You shall upon end of your services to the company for any </dd>
    <br>";

        $page2 = " <style>
        dt,dd{
            font-size: 14px;
            text-align:justify;
        }
        p{
            font-size: 14px;
            text-align:justify;
        }
        </style>

        <dd>reason, return all such records in your possession and shall not attempt to retain copies of any data records or information of the Company.
        </dd>
    
        <dd>5.5 You shall always maintain the Company property in good condition, which may be given to you for official use during your employment and shall return the same to the Company immediately at the end of your services for any reason, failing which the Company reserves the right to recover the cost of the same from you.
        </dd>
         
    <br>
    <dt><b>6. Code of Conduct- </b>You shall always abide by the rules and regulations as per the code of conduct of the Company presently applicable and amended from time to time.</dt>
    <br>
    <dt><b>7. Dress Code-</b> Company has adopted “Smart Casual” as its Dress Code. Employees irrespective of gender should ensure that they are dressed in a decent wear to appear professional.</dt>
    <br>
    <dt><b>8. Working hours-</b> Your normal office hours shall be intimated at the time of joining. The Company reserves the right to require you to work outside your normal working hours, if necessary, in furtherance of your duties. Suitable remedies / remuneration will be provided by the company to you in such case.</dt>
    <br>
    <dt><b>9. Leave-</b> You will be eligible for the benefits of the leave as per the Company policy available on the EMS. </dt>
    <br>
    <dt><b>10. Termination on account fraud, misconduct or ZTP:</b></dt>
            <dd>10.1 Under exceptional circumstances if comes to the notice of the Company that an employee is not abiding by the prescribed Code of Conduct or is not executing his/her duties and if such action is likely to cause harm to the business or adversely affect the Company’s reputation, then the Company on its own discretion can terminate the services of the employee without notice. 
            </dd>
            <dd>10.2 The decision of the Company with regards to your termination will be final and legal binding on you. In all such cases, Company shall not be liable to pay any dues and termination letter will be issued. 
            </dd>
            <dd>10.3 If at any time in the opinion of the Company an employee is found guilty on any of the grounds mentioned below, the company may terminate the services immediately and has rights to claim the damages caused, if any -
                    <dd>a. Dishonesty in carrying out duties or deliberate commission of a crime against the Company.
                    </dd>
                    <dd>b. Intentionally or due to negligence, causing the Company to suffer serious damage.
                    </dd>
                    <dd>c. Fraud, theft, or gross malfeasance on the part of the Employee; conduct of any activity which is criminal in     nature; conduct or involve in misappropriation of Company assets.
                    </dd>
                    <dd>d. The habitual use of drugs and intoxicants.
                    </dd>
                    <dd>e. Violation of any terms of this letter of Appointment.
                    </dd>
                    <dd>f. Repeated violation by the Employee of any of the written work rules or written policies of the Company.
                    </dd>
               
            </dd>
<br> ";

        $page3 = " <style>
        dt,dd{
            font-size: 14px;
            text-align:justify;
        }
        p{
            font-size: 14px;
            text-align:justify;
        }
        </style>
           
        <dt><b>11. Unauthorized Absence- </b>If an employee absents himself /herself without information for more than 3 days or remains absent beyond the period of the originally granted leave or subsequently extended, he/she shall be considered as absconding and company shall not be liable to pay any dues or documents. 
        </dt>
        <br>
        <dt><b>12. Resignation / Separation-</b> When an employee shows the willingness to pursue work outside the Company, he/she shall submit a written resignation and provide services of minimum 30 days as per the defined notice period. Post the manager’s and HR department’s approval the employee's exit from the Company will be conducted. In all such cases, Company shall, be liable to pay all dues and relieving & experience letter will be issued.
        </dt>
        <br>
        <dt><b>13. Notice Period-</b> As per the Company policy, any employee in the event of resignation due to any reason will be required to serve 30 days’ notice period. The Company may, in its sole discretion, terminate the employment on business contingencies, by giving 30 days’ notice or salary in lieu thereof.
        </dt>
        <br>
        <dt><b>14. Restrictions for representing Company after end of employment -</b> You shall not anywhere at any time after the end of employment with company either personally or through your agents/friends / relatives directly or indirectly represent yourself as being connected in any way with the business of the Company.
        </dt>
        <br>
        <dt><b>15. Handing over the Company’s Property at the time of separation-</b> In the event of separation for any reason whatsoever, you must return all the Company’s property & stationery including identity card, visiting cards, all details, and records of customers as maintained by you, laptop /desktop (if issued), reports, letters, notebooks, programs, proposal and any documents / copies or any confidential information concerning the Company’s business. This data may be physical or digital in nature.
        </dt>
        <br>
        <dt><b>16. Indemnity-</b> You shall indemnify the Company for all the losses caused to the Company due to negligence, which shall be recovered from you. 
        </dt>
        <br>
        <dt><b>17. Jurisdiction- </b>This is agreed by both parties (Employees and Company) that only the New Delhi courts shall have the exclusive jurisdiction in respect of any matter, claim, dispute arising out of or in any way, relating to this letter. 
        </dt>
        <br>
        <dt><b>18. Exclusive Service-</b> While with the Company you will not work for any other Company or person, nor carry any material / service for promotion of any other except the Company. 
        </dt>
        <br>
        <dt><b>19. Bank Account & Salary Credit Process-</b> The salary will be credited every month in the employee bank account only. No other means of payment will be used for crediting the salary to an employee. In case an employee fails to open his/her bank account within 30 days of joining, the company reserves the right to hold or not to process employee salary for the given month, till such time that the bank account is opened by the employee. 
        </dt>
        <br>
        <br><br><br><br>";

        $page4 = " <style>
        dt,dd{
            font-size: 14px;
            text-align:justify;
        }
        p{
            font-size: 14px;
            text-align:justify;
        }
        </style>
        
        <dt><b>20.</b> Your appointment is based on the information furnished by you. However, if there is a discrepancy in the copies of documents or certificates or information given by you, the Company retains the right to review or withdraw the appointment. 
        </dt>
        <br>
    

    <p>\t We <b> Congratulate</b> you on your appointment and wish you a long and successful career with ".$company." and assure you of our support for your professional development and growth.</p>
    <br><br><br>
    <p>Yours truly,<br><b>For ".$companyname."</b></p></br><br><br>";

        $pdflastRED = " <p><b>Authorized Signatory</b></p><br><br><br><br>";

        $pdfhr = '  <hr size="2" width="100%" align="center" style="border-color:red; ">';
        
		}
        else if (in_array($var_desg_id, array(30)))  //for field executive
                {
                    $flag = 1;
                    //$page1="<p>Date:".$dateformat."</p>";
                    $pdfheading = "<h3>APPOINTMENT LETTER</h3></br>";

                    $page1 .= "<table>
                    <tr><td><b>Date : </b>" . $dateformat . "</td></tr><br>
                    <tr><td><b>" . $Gender . " : </b>" . $EmployeeName . "</td></tr><br></table>
                    
                    
                    <p>We are pleased to appoint you as $designation at our Organization as per the employment terms and conditions stated below. Please note that the employment terms contained in this letter are subject to Organisation’s HR and other polic(ies).</p>
                    
                    <p>Your effective date of appointment is $DOJ. The term of your employment with the Organisation shall commence on the effective date and shall continue unless this Appointment Letter is terminated by either party in accordance with the terms of termination mentioned below. </p>
                    
                    <p><b>Your employment with us will be governed by certain terms & conditions of employment which are mentioned below-</b></p>
                    
                    <style>
                    dt,dd{
                        font-size: 14px;
                        text-align:justify;
                    }
                    p{
                        font-size: 14px;
                        text-align:justify;
                    }
                    </style>
                    
                        <dt><b>1. Compensation-</b>Your Monthly Cost To The Company (CTC) will be INR ($monthly).</dt>
                        <br>
                        
                        <dt><b>2. Service Conditions-</b> You shall be governed by the rules and regulations and such other practices, systems, procedures, and policies in existence or established by the Organisation from time to time. 
                        </dt>
                        <br>
                    
                        <dt><b>3. E-Induction-</b> You will be a part of the e-induction procedure to make you familiarize with the Organisation policies and day-to-day working.   
                        </dt>
                        <br>
                    
                        <dt><b>4. Assignment / Transfer-</b> Your usual place of work will be $tehsil. However, during your service with the Organisation you shall be liable to be posted/ transferred to specific projects, assignments, jobs, etc. in which case you will be required to perform your services at such location, division, department, or branch of the Organisation as the Organisation may deem fit.
                        </dt>
                        <br>
                    
                        <dt><b>5. Duties & Obligation-</b></dt>      
                        <dd>5.1 You must effectively, diligently and to the best of your ability perform all responsibilities and obligations.
                        </dd>
                    
                        <dd>5.2 You will be in whole time service /employment of the Organisation and shall not engage directly or indirectly in any other work either part-time or fully.
                        </dd>
                    
                        <dd>5.3 You shall act loyally and faithfully to the Organisation and obey the orders or instructions of the management of the Organisation.
                        </dd>
                    
                        <dd>5.4 You shall always maintain high standards of secrecy of confidential records, documents and information relating to the business which may be known to you and shall use them always in the best interest of the Organisation. You shall upon end of your services to the Organisation for</dd>
                        <br>";

                    $page2 = " <style>
                            dt,dd{
                                font-size: 14px;
                                text-align:justify;
                            }
                            p{
                                font-size: 14px;
                                text-align:justify;
                            }
                            </style>
                    		<dd>any reason, return all such records in your possession and shall not attempt to retain copies of any data records or information of the Organisation.               		
                    		</dd>
                                                    
                            <dd>5.5 You shall always maintain the Organisation property in good condition, which may be given to you for official use during your employment and shall return the same to the Organisation immediately at the end of your services for any reason, failing which the Organisation reserves the right to recover the cost of the same from you.
                            </dd>
                            
                        <br>
                        <dt><b>6. Code of Conduct- </b>You shall always abide by the rules and regulations as per the code of conduct of the Organisation presently applicable and amended from time to time. </dt>
                        <br>
                        <dt><b>7. Dress Code-</b> Organisation has adopted “Smart Casual” as its Dress Code. Employees irrespective of gender should ensure that they are dressed in a decent wear to appear professional.</dt>
                        <br>
                        <dt><b>8. Working hours-</b> Your normal office hours shall be intimated at the time of joining. The Organisation reserves the right to require you to work outside your normal working hours, if necessary, in furtherance of your duties. Suitable remedies / remuneration will be provided by the Organisation to you in such case.</dt>
                        <br>
                        <dt><b>9. Leave-</b> You will be eligible for the benefits of the leave as per the Organisation policy available on the EMS.  </dt>
                        <br>
                        <dt><b>10. Termination:</b></dt>
                                <dd>10.1 If any time it comes to the notice of the Organisation that an employee is not abiding by the prescribed Code of Conduct or is not executing his/her duties and if such action is likely to cause harm to the business or adversely affect the Organisation’s reputation, then the Organisation at its sole discretion can terminate the services of the employee without notice. 
                                </dd>
                                <dd>10.2 The decision of the Organisation with regards to your termination will be final and legal binding on you. In all such cases, Organisation shall not be liable to pay any dues and termination letter will be issued.  
                                </dd>
                                <dd>10.3 If at any time in the opinion of the Organisation an employee is found guilty on any of the grounds mentioned below, the Organisation may terminate the services immediately and has rights to claim the damages caused, if any -
                                        <dd>a. Dishonesty in carrying out duties or deliberate commission of a crime against the Organisation.
                                        </dd>
                                        <dd>b. Intentionally or due to negligence, causing the Organisation to suffer serious damage.
                                        </dd>
                                        <dd>c. Fraud, theft, or gross malfeasance on the part of the Employee; conduct of any activity which is criminal in nature; conduct or involve in misappropriation of Organisation assets.
                                        </dd>
                                        <dd>d. The habitual use of drugs and intoxicants.
                                        </dd>
                                        <dd>e. Violation of any terms of this letter of Appointment.
                                        </dd>
                                        <dd>f. Repeated violation by the Employee of any of the written work rules or written policies of the Organisation.
                                        </dd>
                                
                                </dd>
                                <dd>10.4 The Organization shall have the right to terminate the employment of any employee without cause, by giving a prior notice of 15 days or payment in lieu thereof.  
                                </dd>
                    <br> ";

                    $page3 = " <style>
                            dt,dd{
                                font-size: 14px;
                                text-align:justify;
                            }
                            p{
                                font-size: 14px;
                                text-align:justify;
                            }
                            </style>
                            
                            <dt><b>11. Unauthorized Absence- </b>If an employee absents himself /herself without information for more than 3 days or remains absent beyond the period of the originally granted leave or subsequently extended, he/she shall be considered as absconding and Organisation shall not be liable to pay any dues or documents.  
                            </dt>
                            <br>
                            <dt><b>12. Resignation-</b>When an employee shows the willingness to pursue work outside the Organisation, he/she shall submit a written resignation and provide services of minimum 30 days as per the defined notice period. Post the manager’s and HR department’s approval the employee's exit from the Organisation will be conducted. 
                            </dt>
                            <br>
                            <dt><b>13. Notice Period-</b>As per the Organisation policy, any employee in the event of resignation due to any reason will be required to serve 30 days notice period. The Organisation may, in its sole discretion, terminate the employment on business contingencies, by giving 30 days notice or salary in lieu thereof.
                            </dt>
                            <br>
                            <dt><b>14. Restrictions for representing Organisation after end of employment -</b> You shall not anywhere at any time after the end of employment with Organisation either personally or through your agents/friends / relatives directly or indirectly represent yourself as being connected in any way with the business of the Organisation.
                            </dt>
                            <br>
                            <dt><b>15. Handing over the Organisation’s Property at the time of separation-</b> In the event of separation for any reason whatsoever, you must return all the Organisation property & stationery including identity card, visiting cards, all details, and records of customers as maintained by you, laptop /desktop (if issued), reports, letters, notebooks, programs, proposal and any documents / copies or any confidential information concerning the Organisation business. This data may be physical or digital in nature.
                            </dt>
                            <br>
                            <dt><b>16. Indemnity-</b> You shall indemnify the Organisation for all the losses caused to the Organisation or its clients due to any act, omission, misconduct of negligence, which shall be recovered from you.  
                            </dt>
                            <br>
                            <dt><b>17. Jurisdiction- </b>This is agreed by both parties (Employees and Organisation) that only the New Delhi courts shall have the exclusive jurisdiction in respect of any matter, claim, dispute arising out of or in any way, relating to this letter.  
                            </dt>
                            <dt><b>18. Exclusive Service-</b> While with the Organisation you will not work for any other Organisation or person, nor carry any material / service for promotion of any other except the Organisation.
                            </dt>
                            <br>
                            <dt><b>19. Bank Account & Salary Credit Process-</b> The salary will be credited every month in the employee bank account only. No other means of payment will be used for crediting the salary to an employee. In case an employee fails to open his/her bank account within 30 days of joining, the Organisation reserves the right to hold or not to process employee salary for the given month, till such time that the bank account is opened by the employee. 
                            </dt>
                            <br>
                            <br><br><br><br>";

                    $page4 = " <style>
                            dt,dd{
                                font-size: 14px;
                                text-align:justify;
                            }
                            p{
                                font-size: 14px;
                                text-align:justify;
                            }
                            </style>
                            
                            <dt><b>20.</b> Your appointment is based on the information furnished by you. However, if there is a discrepancy in the copies of documents or certificates or information given by you, the Organisation retains the right to review or withdraw the appointment. 
                            </dt>
                            <br>
        

                    <p>\t We <b> Congratulate</b> you on your appointment and wish you a long and successful career with " . $company . " and assure you of our support for your professional development and growth.</p>
                    <br><br><br>
                    <p>Yours truly,<br><b>For " . $companyname . "</b></p></br><br><br>";

                    $pdflastRED = " <p><b>Authorized Signatory</b></p><br><br><br><br>";

                    $pdfhr = '  <hr size="2" width="100%" align="center" style="border-color:red; ">';
                }
        else if(in_array($var_desg_id,array(2,3,4,6,11,18,19,20,21,25,26,27,28)))//For Non CSA upto below AM
        {
        	$flag = 1;
			$pdfheading = "<h3>APPOINTMENT LETTER</h3></br>";

        $page1 .= "<table>
	<tr><td><b>Date : </b>" . $dateformat . "</td></tr><br>
	<tr><td><b>" . $Gender . " : </b>" . $EmployeeName . "</td></tr><br></table>
	
 
<p>We are pleased to appoint you in <b>Cogent E Services Limited</b> (<i>hereafter referred to as</i> <b>“Company”</b>) as  '" . $designation . "' at our organization as per the employment terms and conditions stated below. Please note that the employment terms contained in this letter are subject to Company policy.</p>

<p>Your effective date of appointment is " . $DOJ . ". The term of your employment with the Company shall commence on the effective date and shall continue unless this Appointment Letter is terminated by either party in accordance with the terms of separation mentioned in this letter. </p>

<p><b>Your employment with us will be governed by certain terms & conditions of employment which are mentioned below-</b></p>
<style>
dt,dd{
    font-size: 14px;
    text-align:justify;
}
p{
    font-size: 14px;
    text-align:justify;
}
</style>

    <dt><b>1. Compensation-</b></dt>
            <dd>1.1 Your cost to the company (CTC) will be Rs. (" . $annual . ") annually.</dd>
            <dd>1.2 Your salary will be reviewed and changes in your compensation will be subjected to effective performance during the defined appraisal period.</dd>
    
    <br>
    
    <dt><b>2. Performance Linked Incentives (PLI)-</b> Your Cost to Company (CTC) will include a 10% Performance Linked Incentive which shall be paid out basis successful achievement of the set performance standards. The PLI shall be paid Half yearly.<br>Please note that the PLI will be payable only if you are still on the company rolls on the date of disbursement.<br>PLI will not be payable, if you are not on Company rolls, have resigned, serving notice period on the date of disbursement.</dt>
    
    <br>
    <dt><b>3. Service Conditions-</b> You shall be governed by the rules and regulations and such other practices, systems, procedures, and policies in existence or established by the Company from time to time. 
    </dt>
    <br>
    <dt><b>4. E-Induction-</b> You will be a part of the e-induction procedure to make you familiarize with the Company policies and day-to-day working.</dt>
    <br>
    <dt><b>5. Assignment / Transfer-</b>Your usual place of work will be " . $Locationresult[0]['location'] . ". However, during your service with the Company you shall be liable to be posted/ transferred to specific projects, assignments, jobs, etc. in which case you will be required to perform your services at such location, division, department, or branch of the Company as the Company may deem fit.
    </dt>
    <br>
    
    <br>
    ";


        $page2 = " <style>
        dt,dd{
            font-size: 14px;
            text-align:justify;
        }
        p{
            font-size: 14px;
            text-align:justify;
        }
        </style>
        
        
        <dt><b>6. Duties & Obligation-</b>    
            <dd>6.1 You must effectively, diligently and to the best of your ability perform all responsibilities and obligations.
            </dd>

            <dd>6.2 You will be in whole time service /employment of the Company and shall not engage directly or indirectly in any other work either part-time or fully.
            </dd>

            <dd>6.3 You shall act loyally and faithfully to the Company and obey the orders or instructions of the management of the Company.
            </dd>

            <dd>6.4 You shall always maintain high standards of secrecy of confidential records, documents and information relating to the business which may be known to you and shall use them always in the best interest of the company. You shall upon end of your services to the company for any reason, return all such records in your possession and shall not attempt to retain copies of any data records or information of the Company.
            </dd>

            <dd>6.5 You shall always maintain the Company property in good condition, which may be given to you for official use during your employment and shall return the same to the Company immediately at the end of your services for any reason, failing which the Company reserves the right to recover the cost of the same from you.
            </dd>
        </dt>
        
    <br>
    <dt><b>7. Code of Conduct- </b>You shall always abide by the rules and regulations as per the code of conduct of the Company presently applicable and amended from time to time.
    </dt>
    <br>
    <dt><b>8. Dress Code-</b> Company has adopted “Smart Casual” as its Dress Code. Employees irrespective of gender should ensure that they are dressed in a decent wear to appear professional.
    </dt>
    <br>
    <dt><b>9. Working hours-</b> Your normal office hours shall be intimated at the time of joining. The Company reserves the right to require you to work outside your normal working hours, if necessary, in furtherance of your duties. Suitable remedies / remuneration will be provided by the company to you in such case.
    </dt>
    <br>
    <dt><b>10. Leave-</b> You will be eligible for the benefits of the leave as per the Company policy available on the EMS.
    </dt>
    <br>
    <dt><b>11. Termination on account fraud, misconduct or ZTP:</b></dt>
            <dd>11.1 Under exceptional circumstances if comes to the notice of the Company that an employee is not abiding by the prescribed Code of Conduct or is not executing his/her duties and if such action is likely to cause harm to the business or adversely affect the Company’s reputation, then the Company on its own discretion can terminate the services of the employee without notice. 
            </dd>
            <dd>11.2 The decision of the Company with regards to your termination will be final and legal binding on you. In all such cases, Company shall not be liable to pay any dues and termination letter will be issued. 
            </dd>
            <dd>11.3 If at any time in the opinion of the Company an employee is found guilty on any of the grounds mentioned below, the company may terminate the services immediately and has rights to 
            </dd> ";

        $page3 = " <style>
        dt,dd{
            font-size: 14px;
            text-align:justify;
        }
        p{
            font-size: 14px;
            text-align:justify;
        }
        </style>

       <dd> claim the damages caused, if any -</dd>
                    <dd>a. Dishonesty in carrying out duties or deliberate commission of a crime against the Company.
                    </dd>
                    <dd>b. Intentionally or due to negligence, causing the Company to suffer serious damage.
                    </dd>
                    <dd>c. Fraud, theft, or gross malfeasance on the part of the Employee; conduct of any activity which is criminal in nature; conduct or involve in misappropriation of Company assets.
                    </dd>
                    <dd>d. The habitual use of drugs and intoxicants.
                    </dd>
                    <dd>e. Violation of any terms of this letter of Appointment.
                    </dd>
                    <dd>f. Repeated violation by the Employee of any of the written work rules or written policies of the Company.
                    </dd>
              
<br>
        <dt><b>12. Unauthorized Absence- </b>If an employee absents himself /herself without information for more than 3 days or remains absent beyond the period of the originally granted leave or subsequently extended, he/she shall be considered as absconding and company shall not be liable to pay any dues or documents. 
        </dt>
        <br>
        <dt><b>13. Resignation / Separation-</b> When an employee shows the willingness to pursue work outside the Company, he/she shall submit a written resignation and provide services of minimum 30 days as per the defined notice period. Post the manager’s and HR department’s approval the employee's exit from the Company will be conducted. In all such cases, Company shall, be liable to pay all dues and relieving & experience letter will be issued.
        </dt>
        <br>
        <dt><b>14. Notice Period-</b> As per the Company policy, any employee in the event of resignation due to any reason will be required to serve 30 days’ notice period. The Company may, in its sole discretion, terminate the employment on business contingencies, by giving 30 days’ notice or salary in lieu thereof.
        </dt>
        <br>
        <dt><b>15. Restrictions for representing Company after end of employment -</b> You shall not anywhere at any time after the end of employment with company either personally or through your agents/friends / relatives directly or indirectly represent yourself as being connected in any way with the business of the Company.
        </dt>
        <br>
        <dt><b>16. Handing over the Company’s Property at the time of separation-</b> In the event of separation for any reason whatsoever, you must return all the Company’s property & stationery including identity card, visiting cards, all details, and records of customers as maintained by you, laptop /desktop (if issued), reports, letters, notebooks, programs, proposal and any documents / copies or any confidential information concerning the Company’s business. This data may be physical or digital in nature.
        </dt>
        <br>
        <dt><b>17. Indemnity-</b> You shall indemnify the Company for all the losses caused to the Company due to negligence, which shall be recovered from you. 
        </dt>
        <br>
        <dt><b>18. Jurisdiction- </b>This is agreed by both parties (Employees and Company) that only the New Delhi courts shall have the exclusive jurisdiction in respect of any matter, claim, dispute arising out of or in any way, relating to this letter. 
        </dt>
        <br><br><br><br>";



        $page4 = " <style>
        dt,dd{
            font-size: 14px;
            text-align:justify;
        }
        p{
            font-size: 14px;
            text-align:justify;
        }
        </style>
        
        <dt><b>19. Exclusive Service-</b> While with the Company you will not work for any other Company or person, nor carry any material / service for promotion of any other except the Company. 
        </dt>
        <br>
        <dt><b>20. Bank Account & Salary Credit Process-</b> The salary will be credited every month in the employee bank account only. No other means of payment will be used for crediting the salary to an employee. In case an employee fails to open his/her bank account within 30 days of joining, the company reserves the right to hold or not to process employee salary for the given month, till such time that the bank account is opened by the employee. 
        </dt>
        <br>
        <dt><b>21.</b> Your appointment is based on the information furnished by you. However, if there is a discrepancy in the copies of documents or certificates or information given by you, the Company retains the right to review or withdraw the appointment. 
        </dt>
        <br>
    

    <p>\tWe <b> Congratulate</b> you on your appointment and wish you a long and successful career with Cogent and assure you of our support for your professional development and growth.</p>
    <br><br><br>
    <p>Yours truly,<br><b>For Cogent E Services Ltd.</b></p></br><br><br>";

        $pdflast = " <p><br><b>Authorized Signatory</b></p><br><br><br><br>";

        $pdfhr = '<hr>';
		}
		 else if(in_array($var_desg_id,array(1,5,7,8,10,13,15,16,22,23)))//For AM & Above
        {
        	$flag = 1;
			$pdfheading = "<h3>APPOINTMENT LETTER</h3></br>";

        $page1 .= "<table>
	<tr><td><b>Date : </b>" . $dateformat . "</td></tr><br>
	<tr><td><b>" . $Gender . " : </b>" . $EmployeeName . "</td></tr><br></table>
	
 
<p>We are pleased to appoint you in <b>Cogent E Services Limited</b> (<i>hereafter referred to as</i> <b>“Company”</b>) as  '" . $designation . "' at our organization as per the employment terms and conditions stated below. Please note that the employment terms contained in this letter are subject to Company policy.</p>

<p>Your effective date of appointment is " . $DOJ . ". The term of your employment with the Company shall commence on the effective date and shall continue unless this Appointment Letter is terminated by either party in accordance with the terms of separation mentioned in this letter. </p>

<p><b>Your employment with us will be governed by certain terms & conditions of employment which are mentioned below-</b></p>
<style>
dt,dd{
    font-size: 14px;
    text-align:justify;
}
p{
    font-size: 14px;
    text-align:justify;
}
</style>

    <dt><b>1. Compensation-</b></dt>
            <dd>1.1 Your cost to the company (CTC) will be Rs. (" . $annual . ") annually.</dd>
            <dd>1.2 Your salary will be reviewed and changes in your compensation will be subjected to effective performance during the defined appraisal period.</dd>
    
    <br>
    
    <dt><b>2. Performance Linked Incentives (PLI)–</b> Your Cost to Company (CTC) will include a 10% Performance Linked Incentive which shall be paid out basis successful achievement of the set performance standards.<br>The PLI shall be paid Yearly. Please note that the PLI will be payable only if you are still on the company rolls on the date of disbursement.<br>PLI will not be payable, if you are not on Company rolls, have resigned, serving notice period on the date of disbursement.</dt>
    
    <br>
    <dt><b>3. Service Conditions-</b> You shall be governed by the rules and regulations and such other practices, systems, procedures, and policies in existence or established by the Company from time to time. 
    </dt>
    <br>
    <dt><b>4. E-Induction-</b> You will be a part of the e-induction procedure to make you familiarize with the Company policies and day-to-day working. 
    </dt>
    <br>
    <dt><b>5. Assignment / Transfer-</b>Your usual place of work will be " . $Locationresult[0]['location'] . ". However, during your service with the Company you shall be liable to be posted/ transferred to specific projects, assignments, jobs, etc. in which case you will be required to perform your services at such location, division, department, or branch of the Company as the Company may deem fit.
    </dt>
    <br>
    
    
    <br>
    ";


        $page2 = " <style>
        dt,dd{
            font-size: 14px;
            text-align:justify;
        }
        p{
            font-size: 14px;
            text-align:justify;
        }
        </style>
        
        
        <dt><b>6. Duties & Obligation-</b>    
            <dd>6.1 You must effectively, diligently and to the best of your ability perform all responsibilities and obligations.
            </dd>

            <dd>6.2 You will be in whole time service /employment of the Company and shall not engage directly or indirectly in any other work either part-time or fully.
            </dd>

            <dd>6.3 You shall act loyally and faithfully to the Company and obey the orders or instructions of the management of the Company.
            </dd>

            <dd>6.4 You shall always maintain high standards of secrecy of confidential records, documents and information relating to the business which may be known to you and shall use them always in the best interest of the company. You shall upon end of your services to the company for any reason, return all such records in your possession and shall not attempt to retain copies of any data records or information of the Company.
            </dd>

            <dd>6.5 You shall always maintain the Company property in good condition, which may be given to you for official use during your employment and shall return the same to the Company immediately at the end of your services for any reason, failing which the Company reserves the right to recover the cost of the same from you.
            </dd>
        </dt>
        
    <br>
    <dt><b>7. Code of Conduct- </b>You shall always abide by the rules and regulations as per the code of conduct of the Company presently applicable and amended from time to time.
    </dt>
    <br>
    <dt><b>8. Dress Code-</b> Company has adopted “Smart Casual” as its Dress Code. Employees irrespective of gender should ensure that they are dressed in a decent wear to appear professional.
    </dt>
    <br>
    <dt><b>9. Working hours-</b> Your normal office hours shall be intimated at the time of joining. The Company reserves the right to require you to work outside your normal working hours, if necessary, in furtherance of your duties. Suitable remedies / remuneration will be provided by the company to you in such case.
    </dt>
    <br>
    <dt><b>10. Leave-</b> You will be eligible for the benefits of the leave as per the Company policy available on the EMS.
    </dt>
    <br>
    <dt><b>11. Termination on account fraud, misconduct or ZTP:</b></dt>
            <dd>11.1 Under exceptional circumstances if comes to the notice of the Company that an employee is not abiding by the prescribed Code of Conduct or is not executing his/her duties and if such action is likely to cause harm to the business or adversely affect the Company’s reputation, then the Company on its own discretion can terminate the services of the employee without notice. 
            </dd>
            <dd>11.2 The decision of the Company with regards to your termination will be final and legal binding on you. In all such cases, Company shall not be liable to pay any dues and termination letter will be issued. 
            </dd>
            <dd>11.3 If at any time in the opinion of the Company an employee is found guilty on any of the grounds mentioned below, the company may terminate the services immediately and has rights to claim the damages caused, if any -
            </dd> ";

        $page3 = " <style>
        dt,dd{
            font-size: 14px;
            text-align:justify;
        }
        p{
            font-size: 14px;
            text-align:justify;
        }
        </style>
            <dl>
                <dt>    
                    <dd>a. Dishonesty in carrying out duties or deliberate commission of a crime against the Company.
                    </dd>
                    <dd>b. Intentionally or due to negligence, causing the Company to suffer serious damage.
                    </dd>
                    <dd>c. Fraud, theft, or gross malfeasance on the part of the Employee; conduct of any activity which is criminal in nature; conduct or involve in misappropriation of Company assets.
                    </dd>
                    <dd>d. The habitual use of drugs and intoxicants.
                    </dd>
                    <dd>e. Violation of any terms of this letter of Appointment.
                    </dd>
                    <dd>f. Repeated violation by the Employee of any of the written work rules or written policies of the Company.
                    </dd>
                </dt>
            </dl>
<br>
        <dt><b>12. Unauthorized Absence- </b>If an employee absents himself /herself without information for more than 3 days or remains absent beyond the period of the originally granted leave or subsequently extended, he/she shall be considered as absconding and company shall not be liable to pay any dues or documents. 
        </dt>
        <br>
        <dt><b>13. Resignation / Separation-</b> When an employee shows the willingness to pursue work outside the Company, he/she shall submit a written resignation and provide services of minimum 30 days as per the defined notice period. Post the manager’s and HR department’s approval the employee's exit from the Company will be conducted. In all such cases, Company shall, be liable to pay all dues and relieving & experience letter will be issued.
        </dt>
        <br>
        <dt><b>14. Notice Period-</b> As per the Company policy, any employee in the event of resignation due to any reason will be required to serve 30 days’ notice period. The Company may, in its sole discretion, terminate the employment on business contingencies, by giving 30 days’ notice or salary in lieu thereof.
        </dt>
        <br>
        <dt><b>15. Restrictions for representing Company after end of employment -</b> You shall not anywhere at any time after the end of employment with company either personally or through your agents/friends / relatives directly or indirectly represent yourself as being connected in any way with the business of the Company.
        </dt>
        <br>
        <dt><b>16. Handing over the Company’s Property at the time of separation-</b> In the event of separation for any reason whatsoever, you must return all the Company’s property & stationery including identity card, visiting cards, all details, and records of customers as maintained by you, laptop /desktop (if issued), reports, letters, notebooks, programs, proposal and any documents / copies or any confidential information concerning the Company’s business. This data may be physical or digital in nature.
        </dt>
        <br>
        <dt><b>17. Indemnity-</b> You shall indemnify the Company for all the losses caused to the Company due to negligence, which shall be recovered from you. 
        </dt>
        <br>
        <dt><b>18. Jurisdiction- </b>This is agreed by both parties (Employees and Company) that only the New Delhi courts shall have the exclusive jurisdiction in respect of any matter, claim, dispute arising out of or in any way, relating to this letter. 
        </dt>
        <br><br><br><br>";



        $page4 = " <style>
        dt,dd{
            font-size: 14px;
            text-align:justify;
        }
        p{
            font-size: 14px;
            text-align:justify;
        }
        </style>
        
        <dt><b>19. Exclusive Service-</b> While with the Company you will not work for any other Company or person, nor carry any material / service for promotion of any other except the Company. 
        </dt>
        <br>
        <dt><b>20. Bank Account & Salary Credit Process-</b> The salary will be credited every month in the employee bank account only. No other means of payment will be used for crediting the salary to an employee. In case an employee fails to open his/her bank account within 30 days of joining, the company reserves the right to hold or not to process employee salary for the given month, till such time that the bank account is opened by the employee. 
        </dt>
        <br>
        <dt><b>21.</b> Your appointment is based on the information furnished by you. However, if there is a discrepancy in the copies of documents or certificates or information given by you, the Company retains the right to review or withdraw the appointment. 
        </dt>
        <br>
    

    <p>\t We <b> Congratulate</b> you on your appointment and wish you a long and successful career with Cogent and assure you of our support for your professional development and growth.</p>
    <br><br><br>
    <p>Yours truly,<br><b>For Cogent E Services Ltd.</b></p></br><br><br>";

        $pdflast = " <p><br><b>Authorized Signatory</b></p><br><br><br><br>";

        $pdfhr = '  <hr size="2" width="100%" align="center" style="border-color:red; ">';
		}
		
		if($flag !=0)
		{
			
		

		if($company == "Redstone")
		{
			$location = 'Red Stone Consulting, 53, Madhav Kunj, Pratap Nagar, Agra - 282010, India <br> Website: https://redstonec.in/';
		}
		else
		{
			if (count($Locationresult) > 0)
         {
            foreach ($Locationresult as $val) {
                if ($locationid == "1" || $locationid == "2") 
                {
					
                    if ($val['sub_location'] == substr($EmployeeID, 0, 2)) 
                    {
                        $location = '';
                        $location = $val['companyname'] . ', ' . $val['address'] . '<br> Website : www.cogenteservices.com';
                        //echo $location;exit;

                    }
                     else
                      {
                        $location = '';
                        $location = $val['companyname'] . ', ' . $val['address'] . '<br> Website : www.cogenteservices.com';
                    }
                } 
                else 
                {
                    if ($val['sub_location'] == substr($EmployeeID, 0, 3)) {
                        $location = '';
                        $location = $val['companyname'] . ', ' . $val['address'] . '<br> Website : www.cogenteservices.com';
                    } 
                    else
                    {
                        $location = '';
                        $location = $val['companyname'] . ', ' . $val['address'] . ' <br>Website : www.cogenteservices.com';
                    }
                }
            }
        }
		}
        
        $pdfaddress = '<p style="color:#958d8d;text-align:center;font-size:13px;">
        ' . $location . '</p>';

        //$page1 = '<p style="color:#958d8d;text-align:center;font-size:10px;margin:15px 15px;"><u>' . $location . '</u></p>';
        $filename = $EmployeeID . ".pdf";
        $path = $target_dir . $filename;

        $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
        //$tcpdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, 'marks', 'header string');
        $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $tcpdf->SetTitle('Cogent|appointment_letter Checklist');
        $tcpdf->SetMargins(10, 10, 10, 10);
        $tcpdf->setCellPaddings(7, 7, 7, 7);
        $tcpdf->setCellHeightRatio(1.4);
        $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $tcpdf->setPrintHeader(false);
        $tcpdf->setPrintFooter(false);
        //$tcpdf->setListIndentWidth(3);
        //$tcpdf->SetAutoPageBreak(TRUE, 11);
        $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $tcpdf->AddPage();
		
		if($company == "Cogent")
		{
			$tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); //logo right cogent logo
		}
		else
		{
			$tcpdf->Image('../Style/images/redstone.jpg', 150, 20, 40, 10, 'JPG'); //logo right cogent logo
		}
		
        /*if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {
            if ('AE' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.jpg', 160, 5, 40, 30, 'JPG'); //logo right Aurum
            } else {
                $tcpdf->Image('../Style/images/cogent-logoNW.png', 160, 5, 40, 30, 'PNG'); //logo right cogent logo
            }
        } elseif ($locationid == "3") {
            if ('OC' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.png', 160, 5, 40, 30, 'PNG'); //logo right Orium
            } else {
                $tcpdf->Image('../Style/images/cogent-logoNW.png', 160, 5, 40, 30, 'JPG'); //logo right cogent logo
            }
        }*/


        $tcpdf->SetFont('times', '', 10);
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 0, $y = 30, $pdfheading, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');

        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 40, $page1, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '', $autopadding = true, $spacing = +0.254);

		

        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 250, $pdfhr, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 251, $pdfaddress, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');




        $tcpdf->AddPage();

        /*if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {
            if ('AE' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.jpg', 160, 5, 40, 30, 'JPG'); //logo right Aurum
            } else {
                $tcpdf->Image('../Style/images/cogent-logoNW.png', 160, 5, 40, 30, 'JPG'); //logo right cogent logo
            }
        } elseif ($locationid == "3") {
            if ('OC' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.png', 160, 5, 40, 30, 'PNG'); //logo right Orium
            } else {
                $tcpdf->Image('../Style/images/cogent-logoNW.png', 160, 5, 40, 30, 'JPG'); //logo right cogent logo
            }
        }*/
		
		if($company == "Cogent")
		{
			$tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); //logo right cogent logo
		}
		else
		{
			$tcpdf->Image('../Style/images/redstone.jpg', 150, 20, 40, 10, 'JPG'); //logo right cogent logo
		}
		
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 30, $page2, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '', $padding = 10);

        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 250, $pdfhr, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 251, $pdfaddress, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');




        $tcpdf->AddPage();

        /*if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {
            if ('AE' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.jpg', 160, 5, 40, 30, 'JPG'); //logo right Aurum
            } else {
                $tcpdf->Image('../Style/images/cogent-logoNW.png', 160, 5, 40, 30, 'JPG'); //logo right cogent logo
            }
        } elseif ($locationid == "3") {
            if ('OC' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.png', 160, 5, 40, 30, 'PNG'); //logo right Orium
            } else {
                $tcpdf->Image('../Style/images/cogent-logoNW.png', 160, 5, 40, 30, 'JPG'); //logo right cogent logo
            }
        }*/
		
		if($company == "Cogent")
		{
			$tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); //logo right cogent logo
		}
		else
		{
			$tcpdf->Image('../Style/images/redstone.jpg', 150, 20, 40, 10, 'JPG'); //logo right cogent logo
		}
		
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 30, $page3, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 250, $pdfhr, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 251, $pdfaddress, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');




        $tcpdf->AddPage();

        /*if ($locationid == "1" || $locationid == "2" || $locationid == "4" || $locationid == "5" || $locationid == "6" || $locationid == "7" || $locationid == "8") {
            if ('AE' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.jpg', 160, 5, 40, 30, 'JPG'); //logo right Aurum
            } else {
                $tcpdf->Image('../Style/images/cogent-logoNW.png', 160, 5, 40, 30, 'JPG'); //logo right cogent logo
            }
        } elseif ($locationid == "3") {
            if ('OC' == substr($EmployeeID, 0, 2)) {
                $tcpdf->Image('../Style/images/client_logo.png', 160, 5, 40, 30, 'PNG'); //logo right Orium
            } else {
                $tcpdf->Image('../Style/images/cogent-logoNW.png', 160, 5, 40, 30, 'JPG'); //logo right cogent logo
            }
        }*/
        
        if($company == "Cogent")
		{
			$tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); //logo right cogent logo
		}
		else
		{
			$tcpdf->Image('../Style/images/redstone.jpg', 150, 20, 40, 10, 'JPG'); //logo right cogent logo
		}
		

        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 40, $page4, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

        

        if (in_array($var_desg_id, array(9, 12))) //For CSA Only 
        {
            $tcpdf->Image('../Style/img/sk_sign.jpeg', 15, 120, 30, 15, 'JPEG'); //sig for red
            $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 8, $y = 130, $pdflastRED, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
        }
        else if (in_array($var_desg_id, array(30))) //for field executive
        {
            $tcpdf->Image('../Style/img/sk_sign.jpeg', 15, 120, 30, 15, 'JPEG'); //sig for red
            $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 8, $y = 130, $pdflastRED, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = ''); 
        }
        else if (in_array($var_desg_id, array(2, 3, 4, 6, 11, 18, 19, 20, 21, 25, 26, 27, 28))) {

            $tcpdf->Image('../Style/img/sk_sign.jpeg', 15, 165, 30, 15, 'JPEG'); //sig 

        } else if (in_array($var_desg_id, array(1,5, 7, 8, 10, 13, 15, 16, 22, 23))) //For AM & Above
        {
            $tcpdf->Image('../Style/img/sk_sign.jpeg', 15, 165, 30, 15, 'JPEG'); //sig 

        }

        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 8, $y = 170, $pdflast, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
		
		$tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 250, $pdfhr, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
        $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 251, $pdfaddress, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');






        $tcpdf->Output($path, 'F');

        /////end pdf

        ///////mail functionality
        	$myDB=new MysqliDb();
			$select_email_array=$myDB->rawQuery("select mobile,b.cm_id from contact_details a inner Join employee_map b on a.EmployeeID=b.EmployeeID where  a.EmployeeID='".$EmployeeID."'");
			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = EMAIL_HOST; 
			$mail->SMTPAuth = EMAIL_AUTH;
			$mail->Username = EMAIL_USER;   
			$mail->Password = EMAIL_PASS;                        
			$mail->SMTPSecure = EMAIL_SMTPSecure;
			$mail->Port = EMAIL_PORT; 
			$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
			$contactNum='';
			if($rowCount>0){	
				$contactNum=$select_email_array[0]['mobile'];
				$email_address=$_POST['emailAddress'];
				$mail->AddAddress($email_address);
	
			}	
			if(file_exists($path))
			{	
			
			$mail->AddAttachment($path,"appointmentLetter.pdf");
			$mail->Subject = 'Appointment letter';
			$mail->isHTML(true);
			
			$mysqlError = '';
			
			$body='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Hello ,<br/><br/><span><b> Please find your attached appointment letter</b></span><br /><br/><div style="float:left;width:100%;"><br/> Thanks.<div>';
			
			$mail->Body = $body;
			$mymsg="";
			if(!$mail->send())
		 	{
		 		$mymsg .='.Mailer Error:'. $mail->ErrorInfo;
		 		echo "<script>$(function(){ toastr.error('".$mymsg."') }); </script>";
		  	} 
			else
			 {
			 	$myDB = new MysqliDb();
				$myDB->rawQuery("update appointmentlonline set status=1,source_from='Web-146byemp',fetcheEmail='".addslashes($_POST['emailAddress'])."',ReceivedDate=now() where EmployeeID='".$EmployeeID."'");				
				$emailaddress= $_POST['emailAddress'];
			 	echo "<script>$(function(){ toastr.success('Appointment Letter sent successfully on your email address ".$emailaddress." ') }); </script>";
			 	
			 }
				/* SMS on mobile */
				$mobilenum=$contactNum;
				 if(!empty($mobilenum))
				{
					$templateid='1707161526693491472';
					$msg="Hi , your appointment letter has been sent on your given Email ID : ".$_POST['emailAddress']."  - Cogent E Services";
				 	$url = SMS_URL;
					$token = SMS_TOKEN;
					$credit = SMS_CREDIT;
					$sender = SMS_SENDER;
					$message = $msg;
					$number = $mobilenum;
					$sendsms = new sendsms($url,$token);
					$message_id = $sendsms->sendmessage($credit,$sender,$message,$number,$templateid);
					
					
				}
				
				//$validateBy = $_SESSION['__user_logid'];
				$myDB = $myDB=new MysqliDb();
				$Update='update doc_al_status set validate=1,validateby="'.$EmployeeID.'",validatetime = now(),comment="'.$Comment.'" where EmployeeID="'.$EmpID.'" ';
				$body="";
				$myDB->rawQuery($Update);
				$mysql_error = $myDB->getLastError();
				if($myDB->count>0)
				{
					$myDB=new MysqliDb();
					$selectCount=$myDB->rawQuery("Select EmployeeID from appointmentlonline Where EmployeeID='".$EmployeeID."' ");
					if($myDB->count<1)
					{
						
					}
				}
			}
			else
			{
				echo "<script>$(function(){ toastr.success('Appointment Letter not found') }); </script>";
			}
			
			}
			else
			{
				echo "<script>$(function(){ toastr.success('Please try again later') }); </script>";
			}
			
			}
	}
	else
	{
		echo "<script>alert('Onfloor/Doj Date should not be empty');</script>";
		
	}
        ///////////////end mail
    
}

		
		#end Region
	
		

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Get Appointment Letter</span>

		<!-- Main Div for all Page -->
		<div class="pim-container row" id="div_main" >

			<!-- Sub Main Div for all Page -->
			<div class="form-div">

			<!-- Header for Form If any -->
				 <h4>Get Appointment Letter on Email Id</h4>				

			<!-- Form container if any -->
				<div class="schema-form-section row" >
					<!--<div class="input-field col s12 m12 ">-->
						<div class="input-field col s6 m6">
							<input type="text" name="emailAddress" id="emailAddress" <?php echo $disable; ?> />
							
							<?php
							if($myDB->count>0){ ?>
							<span style="color: red;" id='appid'><?php echo $message; ?></span>
							<?php }else{ ?>
								<span style="color: red;" id='appid'><?php echo $message; ?></span>
							<?php	} ?>
							<label>Email Address</label>
							
						</div>
						<div class="input-field col s6 m6">
							<input type="text" name="cemailAddress" id="cemailAddress" <?php echo $disable; ?> />
							<label>Confirm Email Address</label>
							
						</div>
						<div class="input-field col s12 m12 right-align">
							<button type="submit" id="btnSave" name="btnSave" class="btn waves-effect waves-green" <?php echo $disable; ?>>Send</button>
						
				 		</div>
					<!--</div>-->		
			    </div>
			<!--Form container End -->	
			</div>     
	<!--Main Div for all Page End -->
	</div>     
<!--Content Div for all Page End -->  
</div>					

<script>
$(document).ready(function(){
	/*var txtEmployeeID='CE121622565';
			var dirLoc='';
			var Comment='Testing';
			var txtEmployeeName='Rinku Kumari';
			//alert('ALetter_download_multipdf2.php?EmpID='+txtEmployeeID+"&dirloc="+dirLoc+'&Comment='+Comment+'&txtEmployeeName='+txtEmployeeName);
	window.open('ALetter_download_multipdf2.php?EmpID='+txtEmployeeID+"&dirloc="+dirLoc+'&Comment='+Comment+'&txtEmployeeName='+txtEmployeeName, '_blank');*/
	//event.preventDefault();
	 $('#emailAddress, #cemailAddress').on("cut copy paste",function(e) {
      e.preventDefault();
   });
		$('#btnSave').click(function(){
	
	        var validate=0;
	        var alert_msg='';		
	        $('#emailAddress').removeClass('has-error');	
	        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	        if($('#emailAddress').val()=='')
	        {  
	        	$('#emailAddress').addClass('has-error');
				validate=1;
				if($('#squeryto').size() == 0)
				{
				   $('<span id="squeryto" class="help-block">Please enter you email address for get your Appointment Letter.</span>').insertAfter('#emailAddress');
				}
				return false;
				
			}else{
				
				
				var emaiAddress=$('#emailAddress').val();
  			 	email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
   				if(!email_regex.test(emaiAddress)){ 
   				validate=1;
				if($('#squeryto').size() == 0)
				{
				   $('<span id="squeryto" class="help-block">Please enter valid email address.</span>').insertAfter('#emailAddress');
				}
				return false;
   				 }
			}
			
		   
			if($('#cemailAddress').val()=='')
	        {  
	       	 validate=1;
				if($('#csqueryto').size() == 0)
				{
				   $('<span id="squeryto" class="help-block">Please enter you confirm email address.</span>').insertAfter('#cemailAddress');
				}
				return false;
	        }else{
				if($('#cemailAddress').val()===$('#emailAddress').val()){
					return true;
				}else{
					if($('#csqueryto').size() == 0)
					{
					   $('<span id="csqueryto" class="help-block">Confirm email address not match .</span>').insertAfter('#cemailAddress');
					}
					return false;
				}
			}
			
			
			//ALetter_download_multipdf_self.php?EmpID=CE081930130&dirloc=1
			//window.open('ALetter_download_multipdf2.php?EmpID='+txtEmployeeID+"&dirloc="+dirLoc+'&Comment='+Comment+'&txtEmployeeName='+txtEmployeeName, '_blank');
	});
		event.preventDefault();
});	
</script>
<?php 
	


include(ROOT_PATH.'AppCode/footer.mpt'); ?>