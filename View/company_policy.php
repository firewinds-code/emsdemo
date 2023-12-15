<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

?>
<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">Company Policy</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>Company Policy</h4>
            <center>
                <div class="schema-form-section row">
                    <style>
                        div.ex3 {
                            width: 99%;
                            height: 500px;
                            overflow: auto;
                        }

                        table {
                            font-family: arial, sans-serif;
                            border-collapse: collapse;
                            width: 99%;
                        }

                        td,
                        th {
                            border: 1px solid #dddddd;
                            text-align: left;
                            padding: 8px;
                        }

                        tr:nth-child(even) {
                            background-color: #dddddd;
                        }
                    </style>

                    <body>
                        <div class="ex3">
                            <h4 style="background-color: #E8E8E8;text-align: center;    font-weight: bolder;    font-size: x-large;    text-decoration: underline;">INTRODUCTION TO EMPLOYEE HANDBOOK </h4>
                            <p style=" text-align: center; font-weight: bold; font-size: x-large; ">Welcome to the Cogent E Services</p>
                            <p>This Employee Handbook has been developed to provide you with an information source for
                                common questions and concerns. The information in this handbook is important to all our
                                employees. Make sure you read the manual thoroughly. You will want to refer to your
                                handbook when you have questions about company policies and benefits.</p>
                            <p>While preparing this Employee Handbook, we have attempted to present a summary of some
                                of the most important policies. No written statement, no matter how complete, can be a
                                substitute for direct daily contact with your immediate supervisor. Throughout your
                                Employee Handbook, you will be able to check the complete information on employee
                                policies and benefits. This advice is continually repeated because its importance can’t be
                                overemphasized. If you have questions or concerns about the policies outlined here, you
                                should contact your manager or Human Resources. </p>
                            <p>Circumstances will obviously require that the policies, practices and benefits described in the
                                Employee Handbook change from time to time. The company reserves the right to amend,
                                modify, delete, supplement or add to the provisions of this handbook as it deems appropriate
                                from time to time in its sole and absolute discretion. Company will attempt to provide you with
                                notification of any other changes as they occur.</p>
                            <p>We are presenting this Employee Handbook because we feel that if you understand basically
                                what is expected of you, and what you may expect of the company, we shall have an
                                organization which better meets the needs of our customers.</p>
                            <p>The statements as set forth in this book have not been arbitrarily established. Each of them
                                has a strong basis in the best practices prevalent in the industry and are also based on the
                                experiences of this company. Employee suggestions have been incorporated over time and
                                are further welcome that will aid in maintaining a constructive and harmonious relationship</p>
                            <p>Our single most common goal must be to work together to meet the needs of our customers
                                to their complete satisfaction.</p><br>
                            <p style=" text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; "><b>Contents</b></p>
                            <p>A. Code of Conduct</p>
                            <p>B. Joining Process</p>
                            <p>C. Attendance Management</p>
                            <p>D. Working Hours and Leave Policy</p>
                            <p>E. Performance Review System</p>
                            <p>F. Employee Separation – Notice Period</p>
                            <p>G. Information Technology Security</p>
                            <p>H. Intellectual Property Security</p>
                            <p>I. Loss or Damage to Company Property</p>
                            <p>J. Personal Phone Usage</p>
                            <p>K. Internal Communication</p>
                            <p>L. Sexual Harassment (POSH)</p>
                            <p>M. Zero Tolerance Policy</p><br>

                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; "><b>Code of Conduct</b></p>

                            <p><b>1. Applicability of the Policy</b></p>

                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”). </p>


                            <p><b>2. Scope of the Policy</b></p>

                            <p>Our Employee Code of Conduct policy outlines our expectations in terms of employee behavior
                                towards their colleagues, supervisors, clients and overall organization. It is expected that all
                                employees follow our particular code of conduct which is as follows:</p>
                            <dl>
                                <dt>
                                <dd>
                                    <p><b>2.1</b> No dual employment. An employee must be formally relieved of his / her services with
                                        the previous employer before taking up any employment opportunity with Cogent.</p>
                                    <p><b>2.2 </b>Conduct all dealings with our customers and suppliers with honesty and integrity.</p>
                                    <p><b> 2.3</b> Respect the rights of all employees to fair treatment and equal opportunity, free from
                                        discrimination or harassment of any type.</p>
                                    <p><b>2.4</b> Know, understand, and comply with the laws, regulations, and codes of conduct
                                        governing the operation of our business-both domestic and foreign.</p>
                                    <p><b>2.5</b> Ensure that all transactions are handled honestly and recorded accurately.</p>
                                    <p><b>2.6</b> Protect information that belongs to the Company, our customers, suppliers, and fellow
                                        workers.</p>
                                    <p><b>2.7</b> Avoid conflict of interest, both real and perceived.</p>
                                    <p><b>2.8</b> Never use Company assets or information for personal gain.</p>
                                    <p><b>2.9</b> Maintain high standards of personal cleanliness and to present a neat, professional
                                        appearance at all times. Company has adopted “Smart Casual” as its Dress Code.
                                        Employees irrespective of gender should ensure that they are attired in a decent wear
                                        to appear professional to internal as well as external customers.</p>
                                    <p><b>2.10</b> All employees to conduct themselves in a professional, mature and responsible
                                        manner. </p>
                                    <p><b>2.11</b> All employees will access company/client systems or data using their own login
                                        credentials and for the purpose for which the data is intended to be used. They may
                                        get in touch with their supervisors in case of any ambiguity or clarifications.</p>
                                    <p><b>2.12</b> With regards to health concerns, Company has designated smoking areas. We seek
                                        your cooperation in refraining from smoking in offices and make use of smoking areas
                                        only.</p>
                                    <p><b>2.13</b> The receipt of any inappropriate gifts or excessive entertainment from any company
                                        with which Cogent has (or will have) business dealings are against the business
                                        principles and prohibited.</p>
                                    <p><b>2.14</b> Avoid the following as conducting, similar to, but not limited to the following may
                                        result in disciplinary action, including termination:</p>
                                    <dl>
                                        <dt>
                                        <dd>
                                            <p><b>2.14.1</b> Engaging in fraud, embezzlement, defalcations, or other dishonest practices</p>
                                            <p><b>2.14.2</b> Violating Company policies and/or laws.</p>
                                            <p><b>2.14.3</b> Threatening, intimidating or insubordinate behavior or physical violence.</p>
                                            <p><b>2.14.4</b> Removing or destroying company records or property, releasing confidential
                                                or proprietary information without appropriate approval.</p>
                                            <p><b>2.14.5</b> Taking or sale of drugs / smoking marijuana (any harmful intoxicating
                                                substances) in the premises or coming in to work under the influence of these
                                                substances</p>
                                            <p><b>2.14.6</b> Possessing weapons or firearms or gambling within Company premises.</p>
                                            <p><b>2.14.7</b> Breaching Customer and/ or Company confidentiality.</p>
                                            <p><b>2.14.8</b> Sexual Harassment</p>
                                            <p><b>2.14.9</b> Dishonesty and Theft</p>
                                            <p><b>2.14.10</b> Failure to meet performance goals</p>
                                            <p><b>2.14.11</b> Excessive absenteeism</p>
                                            <p><b>2.14.12</b> Unauthorized absence</p>
                                            <p><b>2.14.13</b> Discrimination based on caste, creed, color, religion, etc.</p>
                                        </dd>
                                        </dt>
                                    </dl>
                                </dd>
                                </dt>
                            </dl><br>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; "><b>JOINING PROCESS AT COGENT</b></p>
                            <p><b>1. Applicability of the Policy</b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”).</p>
                            <p><b>2. Scope of the Policy</b></p>
                            <p>The Company is committed to establish a proper talent acquisition system whereby all the
                                suitable candidates are selected and recruited as when required, as per the business need against
                                the manpower requirement of the Company.</p>
                            <dd>
                                <p><b>2.1</b> This policy is designed to guide all the employees to understand the Talent Acquisition
                                    Process at Cogent.</p>
                                <p><b>2.2</b> This policy applies to all employees of the Company across locations, pan India.</p>
                                <p><b>2.3</b> The Company has ensured that the established recruitment and selection practices are
                                    consistent with the applicable state employment laws.</p>
                                <p><b>2.4</b> The Company believes and will aim to optimally use the existing manpower, therefore
                                    encourages, and communicates IJP (Internal Job Opportunities).</p>
                                <p><b>2.5</b> Incase suitable manpower is not available internally, the organization identifies and
                                    selects the resources from external sources through various channels like database
                                    search, walk-ins, job portals, LinkedIn, referrals, vendors, NGO, advertisement, etc.</p>
                                <p><b>2.6</b> Interview panel is committed to make recruitment and selection decisions in a fair
                                    and objective manner, based on the merit, by assessing the applicant’s skill,
                                    knowledge, education qualifications, behavior and attitude against the key selection
                                    criteria defined in the job description.
                                <p>
                            </dd>
                            <p><b>3. Process of Talent Acquisition:</b></p>
                            <p><b>3.1 External Hiring</b></p>
                            <p><b>3.1.1 Step-1:</b> Candidate walk-ins for a suitable open position at the Company along with his
                                updated CV & Aadhar card which are the mandatory documents for appearing in the
                                interview at the Company.</p>
                            <p><b>3.1.2 Step-2:</b> Candidate fills the basic details in the “Add Interviewee Page” post which the
                                Interview Id for the candidate is generated which is sent to the candidates registered
                                mobile number and e-mail id.</p>
                            <p><b>3.1.3 Step-3:</b> The Talent Acquisition Team then conducts the first round of interview with the
                                candidate. If the candidate clears the 1st round, he/she is sent for the 2nd round of
                                interview which is either conducted by Operations Team or the Client as required, else
                                he/she is rejected and not eligible for further hiring until 15 days. On successful
                                completion of the 2nd round, the candidate appears for 3rd round which is Versant
                                (Writing/Voice)/ PMAPS / MCAT /Typing /Grammar Assessment Test as required by client
                                process.</p>
                            <p><b>3.1.4 Step-4:</b> Post completion of above 3 rounds, the selected candidate gets a link for
                                documentation and salary will be closed as per permissible salary capping available in the
                                EMS (Employee Management System).</p>
                            <p><b>3.1.5 Step-5:</b> Selected candidate will get
                            </p>
                            <dd>
                                <p><b>3.1.5.1 Letter of Intent (LOI)</b> through a link on the registered mobile number (SMS)
                                    and e-mail address.</p>
                                <p><b>3.1.5.2 Documentation Link</b> to upload profile information & supported documents.
                                    Candidate is expected to provide genuine documents / details at the time of
                                    joining as failure to do the same may lead to termination.</p>
                                <p><b>3.1.5.3 Biometric enrollment</b> on the same day of selection.</p>
                            </dd>
                            <p><b>3.1.6 Step-6:</b> Post selection the candidate must upload all the documents using the shared
                                documentation link before the date of joining mentioned in the LOI. Also, the selected
                                candidate must bring the original documents to the office for physical verification by the
                                Hiring Team on the date of joining.</p>
                            <p><b>3.1.7 Step-7:</b> Post successful verification of all the documents by the Compliance Team,
                                Employee ID is created by the Hiring Team at the respective locations.</p>

                            <p><b>3.1.8 Step-8: Training & Certification Process:</b></p>
                            <dd>
                                <p><b>3.1.8.1 </b>Post the Employee ID generation, the new joinee is aligned to a
                                    Client/Process/Sub Process as “Trainee” and mapped to Training Head for
                                    batch creation.</p>
                                <p><b>3.1.8.2</b> The Training Head further aligns the “Trainee” to the Trainer who leads
                                    training batch as per the defined Classroom / OJT days for the process.</p>
                                <p><b>3.1.8.3</b> Post training, the “Trainee” undergoes the Certification Process and after its
                                    successful completion, gets on floor and is handed over to Operations Team.</p>
                                <p><b>3.1.8.4 </b>If the “Trainee” fails to clear the Certification Process, he/she may be
                                    referred to the HR for an exit.</p>
                                <p><b>3.1.8.5</b> Appointment Letter: Post successful completion of the Certification the
                                    “Trainee” gets the Appointment letter on the EMS, which can be sent to
                                    personal e-mail id for later reference.</p>
                                <p><b>3.1.8.6</b> Please refer to Annexure -1.</p><br>
                            </dd>

                            <table>
                                <tr>
                                    <td colspan=" 2" style="text-align: center;"><b>Annexure - 1</b></td>
                                </tr>
                                <tr>
                                    <td><b>Employee Stages</b></td>
                                    <td><b>Remarks</b></td>
                                </tr>
                                <tr>
                                    <td style="width: 30%;"><b>To Training Head</b></td>
                                    <td>Employee ID is created and trainee is mapped to training for batch alignment</td>
                                </tr>
                                <tr>
                                    <td><b> In Training</b></td>
                                    <td>Trainee is aligned to a batch during class room training</td>
                                </tr>
                                <tr>
                                    <td><b>In OJT</b></td>
                                    <td>Trainee is aligned to a batch during OJT (Login as per date wise capped login hours)</td>
                                </tr>
                                <tr>
                                    <td><b>On Floor</b></td>
                                    <td>Employee is certified and moved to floor for service delivery (8 hours Login for productivity)</td>
                                </tr>
                                <tr>
                                    <td><b>To HR</b></td>
                                    <td> Employee is decertified at any stage and referred to HR for Exit</td>
                                </tr>
                                <tr></tr>
                            </table><br>
                            <p><b>3.2 Internal Hiring (IJP):</b></p>
                            <p><b>3.2.1</b> Internal Job Policy at Cogent outlines the instructions for posting and communicating
                                vacancies internally before doing hiring externally. Company is committed in investing in
                                its employees and helping them advance their careers within the organization.</p>
                            <p><b>3.2.2</b> This policy applies to all employees of the Company across locations.</p>
                            <p><b>3.2.3</b> Business Support Team and Hiring Managers are responsible for communicating internal
                                job postings across locations.</p>
                            <p><b>3.2.4 </b>Company believes in providing equal opportunity to all and does not support any
                                favoritism or discrimination. </p>
                            <p><b>4. Employee Assistance on Joining</b></p>
                            <p><b>4.1 Employee Induction</b></p>
                            <p><b>4.1.1 </b>Induction is a formal process that is designed to welcome the new employees to be
                                informed about the policies & procedures of the Company. Employees are presented with
                                all the required source and procedure needed to navigate within the workplace.</p>
                            <p><b>4.1.2</b> All account / process heads or the authorized person of the new joinee have to spare time
                                as per the program and help the new joinee to understand the Company</p>
                            <p><b>4.2 Employee ID & Access Card </b></p>
                            <p><b>4.2.1</b> A multi-purpose photo ID card is issued to each new joinee by the HR.</p>
                            <p><b>4.2.2</b> The Employee ID Card helps in identifying the employee status, enables facility access
                                and maintains the attendance.</p>
                            <p><b>4.2.3</b> All employees are required to display their Employee ID Cards within the company
                                premises. For the ease of identification, the lanyard colors for Employee ID Card have
                                been differentiated.</p>
                            <p><b>4.2.4</b> An employee using a card that does not belong to him/her may have that card confiscated
                                and may be referred to HR for a disciplinary action.</p>
                            <p><b>5. Background Verification (BGV)</b></p>
                            <dd>
                                <p><b>5.1</b> Company does 100% Back Ground Verification (BGV) for support level hiring and client
                                    specific BGV’s for CSA’s which includes Criminal / Education / Address / Employment
                                    checks.</p>
                                <p><b>5.2</b> Background verification is executed by an external agency.</p>
                                <p><b>5.3</b> In case of any negative outcome, an explanation is sought from the concerned
                                    employee. Consequence management is initiated, if required, leading to exit of the
                                    employee.</p>
                            </dd>
                            <p><b>6. Rehiring </b></p>
                            <p>As a policy Cogent encourages former employees to rejoin the organization. Former employees
                                could have made career decisions due to some specific reasons or concerns & rehiring them may
                                be considered as good business move, since they already know who’s who in the organization,
                                are familiar with the internal policies, practices, corporate culture and they are well versed with
                                the business of the organization. The cost & time of hiring, inducting & training is significantly
                                reduced by recruiting former employees. </p><br>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; "><b>Attendance Management</b></p>
                            <p><b>1. Applicability of the Policy</b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”). </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <p>Attendance Management has been established for the employees to understand the
                                process of marking the daily attendance and working hours. This policy is applicable to all
                                employees at Cogent.</p>
                            <dd>
                                <p><b>2.1 </b>For attendance monitoring Company maintains a Biometric Attendance
                                    System. Employees should follow the working hours, guidelines and mark their
                                    attendance on Biometric daily, which is linked to their EMS (Employee
                                    Management System).</p>
                                <p><b>2.2</b> Attendance shall be tracked on EMS (Employee Management System) Portal.
                                    Please refer to <b>Annexure-1 & 2.</b></p>
                                <p><b>2.3</b> Misappropriation of the attendance shall be considered as violation of Code of
                                    Conduct, leading to appropriate corrective actions.</p>
                                <p><b>2.4</b> Based on business requirement in case any employee is required to work on a
                                    weekly-off or a company declared holiday, he/she shall be eligible for a
                                    Compensatory Off, subjected to approval by Reporting Manager (Refer to the
                                    Leave Policy).</p>
                                <p><b>2.5</b> In case the employee faces any of the below issues-</p>
                                <dl>
                                    <dd>
                                        <p><b>2.5.1</b> Forgot to Punch IN / Out</p>

                                        <p><b> 2.5.2</b> Punched IN / OUT but not showing on EMS / Biometric error</p>
                                        In all the above cases, employee needs to regularize/ correct his/her attendance
                                        within the <b>next 24 hours</b> by raising a ticket on EMS under – “<b>Biometric Issue</b>”
                                        which will be approved/ rejected by his/her supervisor.
                                    </dd>
                                </dl>
                                <p><b> 2.6</b> For all requests related to roster / shift change employee must raise request on EMS<b> within 24 hours</b> of the need and get it approved by the immediate Manager.</p>
                            </dd>
                            <p style="text-align: center;"><b>Annexure -1</b></p>
                            <table>
                                <tr>
                                    <td colspan="4" style="text-align: center; background-color:#E8E8E8;"><b>Payable Days –</b> Salary shall be paid for such days</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;"><b>Abbreviation</b></td>
                                    <td style="width: 20%;"><b>Full Form</b></td>
                                    <td style="width: 10%;"><b>Day Count</b></td>
                                    <td style="width: 40%;"><b>Condition</b></td>
                                </tr>
                                <tr>
                                    <td>P</td>
                                    <td>Present</td>
                                    <td>1</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>L</td>
                                    <td>Leave</td>
                                    <td>1</td>
                                    <td>Approved & Leave Balance Available</td>
                                </tr>
                                <tr>
                                    <td>H</td>
                                    <td>Half-Day</td>
                                    <td>1</td>
                                    <td>Approved & Leave Balance Available</td>
                                </tr>
                                <tr>
                                    <td>HWP</td>
                                    <td>P Half-Day Without Pay</td>
                                    <td>0.5</td>
                                    <td>Approved/Not Approved & Leave
                                        Balance Not Available
                                    </td>
                                </tr>
                                <tr>
                                    <td>WO</td>
                                    <td>Week-Off</td>
                                    <td>1</td>
                                    <td>-
                                    </td>
                                </tr>
                                <tr>
                                    <td>HO</td>
                                    <td>Holiday</td>
                                    <td>1</td>
                                    <td>-
                                    </td>
                                </tr>
                                <tr>
                                    <td>CO</td>
                                    <td>Compensatory Off</td>
                                    <td>1</td>
                                    <td>Generated if worked on Week Off
                                    </td>
                                </tr>
                                <tr>
                                    <td>P (Biometric)</td>
                                    <td>Present (Biometric)</td>
                                    <td>1</td>
                                    <td>Biometric punch in & out not reflecting
                                    </td>
                                </tr>
                                <tr>
                                    <td>P (Short Login)</td>
                                    <td>Present (Short Login)</td>
                                    <td>1</td>
                                    <td>Late login exception 3 times a month
                                        (within 15 minutes of shift timing)
                                    </td>
                                </tr>
                                <tr>
                                    <td>P (Short Leave)</td>
                                    <td>Present (Short Leave)</td>
                                    <td>1</td>
                                    <td><b>(For Support Staff only)</b> 2h 30min
                                        exception once in a month
                                    </td>
                                </tr>
                            </table><br>
                            <p style="text-align: center;"><b>Annexure -2</b></p>
                            <table>
                                <tr>
                                    <td colspan="4" style="text-align: center; background-color:#E8E8E8;"><b>Non - Payable Days –</b> Salary shall not be paid for such days</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%;"><b>Abbreviation</b></td>
                                    <td style="width: 20%;"><b>Full Form</b></td>
                                    <td style="width: 10%;"><b>Day Count</b></td>
                                    <td style="width: 40%;"><b>Condition</b></td>
                                </tr>
                                <tr>
                                    <td>A</td>
                                    <td>Absent</td>
                                    <td>0</td>
                                    <td><b>Uninformed Leave
                                    </td>
                                </tr>
                                <tr>
                                    <td>LWP</td>
                                    <td>Leave Without Pay</td>
                                    <td>0</td>
                                    <td><b>Approved & Leave Balance not Available
                                    </td>
                                </tr>
                                <tr>
                                    <td>WONA</td>
                                    <td>Week-off Not Applicable</td>
                                    <td>0</td>
                                    <td><b>4 payable days not complete
                                    </td>
                                </tr>
                                <tr>
                                    <td>LANA</td>
                                    <td>Leave applied not approved</td>
                                    <td>0</td>
                                    <td><b>-
                                    </td>
                                </tr>
                            </table><br>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">Working Hours and Leave Policy</p>
                            <p><b>1. Applicability of the Policy</b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”). </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <p>The purpose of the<b> Working Hours Policy</b> is to define the normal service hours of the various
                                departments that are staffed by employees in the Company. </p>
                            <p>The organization also recognizes the need for employees to take leaves & that they may require
                                absenting themselves from normal or usual work hours/day to meet their personal exigency. In
                                consideration to the same, employees shall be granted leaves as well as be eligible for national
                                holidays/festivals. This
                                <b>Leave Policy</b> is applicable to all employees at Cogent.
                            </p>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">WORKING HOURS POLICY</p>
                            <dd>
                                <p><b>2.1</b> For an employee, the business normal working hours must be 9 hours as per the
                                    biometric and 8:00 hours as per the APR (Agent Performance Report) within the roster.
                                    The 9 hours include 1 hour of lunch break.</p>
                                <p><b> 2.2</b> For an employee, 5 hours as per biometric and 4:30 hours as per APR (Agent
                                    Performance Report) for a half day present is required.</p>
                                <p><b>2.3</b> For Support staff, the business normal working hours must be 9:00 hours as per the
                                    biometric. The 9:00 hours include 1 hour of lunch break.</p>
                                <p><b> 2.4</b> For Support staff, 5 hours as per biometric is required for a half day present.</p>
                                <p><b>2.5 </b>Rotational Shifts</p>
                                <dl>
                                    <dd>
                                        <p><b>2.5.1</b> Depending on project requirements employees may be required to work in
                                            shifts and on timings different from the normal business hours.</p>
                                        <p><b>2.5.2</b> Any shift which involves a date change will be considered as a Night Shift.</p>
                                    </dd>
                                </dl>
                            </dd>

                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">LEAVE POLICY</p>
                            <p><b>2.6 Availing of Leaves
                                </b></p>
                            <dd>
                                <p><b>2.6.1</b> The organization believes that the employees will administer discretion while
                                    applying for leaves considering that their unplanned or abrupt absence from
                                    work may hamper business continuity.</p>
                                <p><b>2.6.2</b> The employee must provide advance information for availing leaves, except for
                                    unforeseen circumstances. In case of any emergency, employee must call/send
                                    an e-mail to inform the Reporting Manager about the reason of taking leave.</p>
                                <p><b>2.6.3</b> In cases of approved leaves on medical grounds, the employee must provide all
                                    relevant medical documents to HR within 7 days from the start of such leave.</p>
                                <p><b>2.6.4</b> Sanctioning leaves is a matter of business/ work priority & not a matter of right.</p>
                                <p><b>2.6.5</b> Leave without sanction will be treated as misconduct, for which strict
                                    disciplinary action may be initiated, which may also include termination of
                                    services.</p>
                            </dd>
                            <p><b>2.7 Applicability of Leaves
                                </b></p>
                            <dd>
                                <p><b>2.7.1</b> An employee is eligible for paid leaves basis joining date.</p>
                                <p><b>2.7.2</b> The basis of computation of leaves will be calendar year (January to December)</p>
                                <p><b>2.7.3</b> Leaves are credited in employees’ EMS account every month after completing
                                    15 working days. If anyone fails to complete 15 working days in a month, he/she
                                    will not be eligible for a leave for that month. </p>
                            </dd>
                            <p><b>2.8 Categories of Leaves (Refer to Annexure-1)</b></p>
                            <dd>
                                <p><b>2.8.1 Paid Leaves</b></p>
                                <p><b> 2.8.1.1</b> All employees are entitled for 1 paid leave per month</p>
                                <p><b> 2.8.1.2</b> Managers & above are entitled for 1.5 paid leaves per month</p>
                                <p></b>2.8.1.3</b> Paid leave is credited into the employee account within 15 working days.</p>
                                <p><b> 2.8.1.4</b> Up to Assistant Managerial level 12 leaves will be carry forward to next year
                                    post completion of calendar year; for Manager & above 18 leaves will be
                                    carry forward to next year post completion of calendar year. </p>
                            </dd>
                            <p><b>2.8.2 Compensatory Off</b></p>
                            <dd>
                                <p><b>2.8.2.1</b> If an employee is required to work on a weekly off/ company declared
                                    holiday he/she will be compensated with compensatory off for that day.</p>
                                <p><b>2.8.2.2 </b>The expiry of the compensatory off would be 90 days from the date of
                                    generation.</p>
                                <p><b>2.8.2.3</b> In-case the employee is unable to avail the compensatory off within the 90
                                    days period of its generation, the same will get lapsed automatically. </p>
                            </dd>
                            <p><b>2.8.3 Maternity Leaves</b></p>
                            <dd>
                                <p><b>2.8.3.1</b> Maternity Benefit shall be extended to a woman employee in case she has
                                    worked with the organization for a continuous period of not less than 80
                                    days (excluding weekly off, holidays and other leaves) in the 12 months
                                    immediately preceding the date of her expected delivery.</p>
                                <p><b>2.8.3.2</b> All women employees shall be entitled to a paid leave of 180 days weeks.</p>
                                <p><b>2.8.3.3</b> All intervening holidays & weekends falling during this period of maternity
                                    leave are counted as leave.</p>
                                <p><b>2.8.3.4</b> Employee must inform the company 28 days before she decides to start
                                    maternity leave in written along with medical documents.</p>
                                <p><b>2.8.3.5</b> Maternity Leave needs to be applied through the standard leave request
                                    process on EMS. Also submit the required medical reports to HR
                                    Department.</p>
                                <p><b>2.8.3.6 </b>Maternity leave is non-accumulable, non-encashable and must be used in
                                    one go (not in installments).</p>
                                <p><b>2.8.3.7 </b>Any approved leave beyond eligibility/accumulation will be treated as Leave
                                    without Pay (LWP).</p>
                                <p><b>2.8.3.8</b> In case of any sickness arising out of & connected to maternity / pregnancy,
                                    company will follow the provisions of Maternity Benefits Act as in force from
                                    time to time.</p>
                                <p><b>2.8.3.9</b> All the other terms & conditions will be as per Maternity Benefits Act.</p>
                            </dd>
                            <p><b>2.8.4 Paternity Leaves</b></p>
                            <dd>
                                <p><b>2.8.4.1</b> All regular and confirmed male employees who are married can avail 7 days
                                    of paternity leave at the time of childbirth. </p>
                            </dd>
                            <p><b>2.8.5 Exceptions</b></p>
                            <dd>
                                <p><b>2.8.5.1</b> Any leave availed by an employee without approval /consent / on rejection
                                    by the Reporting Manager will be considered as unauthorized leave and will
                                    be marked as “LWP-Leave Without Pay”.</p>
                                <p><b>2.8.5.2</b> If an employee remains absent without approved leave or overstays without
                                    approval from the sanctioning authority for a period of three (3) consecutive
                                    days or more will be considered as <b>NCNS (No Call No Show)</b> and disciplinary
                                    action may be initiated against the employee. </p>
                            </dd>
                            <p><b>2.9 Process of Applying Leaves </b></p>
                            <dd>
                                <p><b>2.9.1</b> Employees are required to apply leaves through EMS only.</p>
                                <p><b>2.9.2</b> Unapplied leaves shall be treated as absent. </p>
                            </dd>
                            <p><b>2.10 Leave Encashment</b></p>
                            <dd>
                                <p><b>2.10.1</b>There is no policy of encashment of accumulated leave balance either at the
                                    time of an employee leaving the organization or anytime during the course of
                                    employment.</p>
                            </dd>

                            <p style="text-align: center;"><b>Annexure -1</b></p>
                            <p style="text-align: center;"><b>Categories of Leaves </b></p>
                            <table>
                                <tr>
                                    <td style="width: 10%;background-color:#E8E8E8;"><b>Leave Type</b></td>
                                    <td style="width: 10%;background-color:#E8E8E8;"><b>No. of Days</b></td>
                                    <td style="width: 40%;background-color:#E8E8E8;"><b>Snapshot</b></td>

                                </tr>
                                <tr>
                                    <td><b>Paid Leave (L)</b></td>
                                    <td>1 per month</td>
                                    <td>These leaves may be availed to attend personal work, time off, etc.
                                        These leaves cannot be encased.</td>

                                </tr>
                                <tr>
                                    <td><b>Compensatory Off (CO)</b></td>
                                    <td>NA</td>
                                    <td>For working on non-working days (weekly off/company declared
                                        holidays)</td>

                                </tr>
                                <tr>
                                    <td><b>Maternity Leave (ML)</b></td>
                                    <td>180 days </td>
                                    <td>For only those married female employees who have completed not
                                        less than 80 days (excluding weekly off, holidays and other leaves)
                                        in the 12 months immediately preceding the date of her expected
                                        delivery</td>

                                </tr>
                                <tr>
                                    <td><b>Paternity Leave (PTL)</b></td>
                                    <td>7 Days</td>
                                    <td>For those married male employees who have been confirmed.</td>
                                </tr>
                                <tr>
                                    <td><b>Holidays (H)</b></td>
                                    <td>7</td>
                                    <td>Annual calendar released to the employees by HR Department
                                        region wise.</td>
                                </tr>
                                <tr>
                                    <td><b>LWP
                                            (Leave Without Pay)</b></td>
                                    <td>NA</td>
                                    <td>In case the employee has no leaves in his kitty the same will be
                                        marked as “LWP-Leave Without Pay”, subjected to approval from
                                        the Manager. If not approved the same will be marked as “Absent
                                        (A)”. Such cases will be treated as “LWP”, and salary will be
                                        deducted accordingly</td>
                                </tr>
                                <tr>
                                    <td><b>HWP
                                            (Half Day Without Pay) </b></td>
                                    <td>NA</td>
                                    <td>In case the employee has no leaves in his kitty and is absent for 0.5
                                        days the same will be marked as “HWP-Half Day Without Pay”,
                                        subjected to approval from the Manager. If not approved the same
                                        will be marked as “Absent (A)”. Such cases will be treated as “HWP”,
                                        and salary will be deducted accordingly</td>
                                </tr>
                            </table><br>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">PERFORMANCE REVIEW SYSTEM</p>
                            <p><b>1. Applicability of the Policy</b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”). </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <p>The strength of an organization is its people. It is important to recognize the employee talent,
                                develop their capabilities, and utilize them appropriately, so that they will contribute to the longterm excellence of the organization. </p>
                            <p>The Objective of Performance Appraisal Review System at Cogent is to assess the performance
                                and potential of the employees, to develop them and to determine their career path. The
                                Performance Appraisal Review System has the following features:</p>
                            <dd>
                                <p><b>2.1</b> Focus on the development and utilization of an employee.</p>
                                <p><b>2.2</b> Transparency and openness in the system.</p>
                                <p><b>2.3</b> Emphasis on the potential assessment and career plan of an employee.</p>
                                <p><b>2.4</b> Identification of training and development needs.</p>
                                <p><b>2.5</b> Review job expectations.</p>
                                <p><b>2.6 </b>Recognize individuals.</p>
                            </dd>
                            <p><b>3. Eligibility</b></p>
                            <dd>
                                <p><b>3.1 </b>The employee will be eligible for appraisal only after completion of 1 year of services
                                    with the Company.</p>
                                <p><b>3.2</b> Appraisal is applicable for Support Function only i.e., Executive / Assistant Team Leader
                                    and Above (all departments).</p>
                                <p><b>3.3</b> If the joining date of any employee is between <b>1st to 15th of a given month,</b> than the
                                    appraisal will be in the same month as the joining month after completion of 1 year with
                                    the company.</p>
                                <p><b>3.4 </b>If the joining date of any employee is between <b>16th to 30/31st of a given month,</b> than
                                    the appraisal will be in the next month to the joining month, after completion of 1 year
                                    with the company.</p>
                            </dd>
                            <p><b>4. Promotion Guidelines </b></p>
                            <dd>
                                <p><b>4.1</b> Employee should perform well consistently and continuously, should have the requisite
                                    experience, qualification, potential, and ability to perform for higher level job. ‘Right
                                    person for Right Job‛ – This is the mantra for promotions.</p>
                                <p><b>4.2</b> Promotion is purely discretion of the management.</p>
                            </dd>
                            <p><b>5. Performance Review Process</b></p>
                            <dd>
                                <p><b>5.1</b> Post completion of 1 year with the Company the eligible employee fills a “Self-Appraisal
                                    Form” and submits for review to the Evaluator/ Approver/Supervisor.</p>
                                <p><b>5.2</b> Evaluator can be Location Head / Account Head / Process Head / HR Head.</p>
                                <p><b>5.3</b> Once submitted by the employee, the “Self -Appraisal Form” will be sent to the
                                    immediate manager who will submit his rating against the employee’s rating and put
                                    comments on promotion recommendation and appraisal %.</p>
                                <p><b>5.4</b> Post submission of the duly filled form by both employee and manager, the form will be
                                    reviewed by Business Support – Head along with the Location Heads / Account Head /
                                    Process Head / HR Head in presence of Senior Vice President-Operations for salary
                                    increment, promotion or any other such decisions.</p>
                                <p><b>5.5</b> Some exceptions:</p>
                                <dl>
                                    <dd>
                                        <p><b>5.5.1</b> In such cases where there is delay from evaluator/ approver side, then increment
                                            amount will be paid as an arear from due months.</p>
                                        <p><b>5.5.2 </b>In such cases where an employee has got warning in last 3 months or has been in
                                            PIP (Performance Improvement Plan), the appraisal cycle will be pushed for
                                            further 3 months.</p>
                                        <p><b>5.5.3</b> Evaluator/ Approver can hold appraisal of a given employee for multiple times, if
                                            he/she feels that the performance of the employee is not meeting the required
                                            expectations and criteria.</p>
                                        <p><b>5.5.4 </b>For IJP Promoted Support Staff, increment / salary revision would be done as per
                                            the eligible appraisal cycle only. </p>
                                    </dd>
                                </dl>
                            </dd>
                            <p><b>6. Performance Improvement Plan</b></p>
                            <p>Based upon the performance review and actual ratings of an employee, the Low Performer will
                                be counseled for improvement and good performer will be considering for recognitions</p>
                            <p>The Performance Improvement Plan (PIP) is designed to facilitate constructive discussion
                                between the low performer and his /her supervisor to clarify the work performance to be
                                improved. The process involves:
                            </p>
                            <dd>
                                <p><b>6.1</b> Meet the employee and discuss performance gaps.</p>
                                <p><b>6.2 </b>Discuss /counsel the employee to fill the performance gaps.</p>
                                <p><b>6.3</b> Draft a PIP (written), with defined targets and timeline to perform.</p>
                                <p><b>6.4</b> Review at designated intervals and ensure regular follow-up.</p>
                                <p><b>6.5</b> If the employee’s performance improves to the satisfaction level, continue with
                                    performance improvement / monitoring, else proceed for consequence management.</p>
                            </dd>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">Employee Separation & Notice Period Policy</p>
                            <p><b>1. Applicability of the Policy</b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”).
                            </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <dd>
                                <p><b>2.1 Resignation</b></p>
                                <dl>
                                    <dd>
                                        <p><b>2.1.1</b> An employee will have to submit a resignation and serve the 30 days’ notice
                                            period, if they wish to leave the Company,</p>
                                        <p><b>2.1.2 </b>The resignation letter must be submitted to the respective Reporting Manager
                                            and/or Account Head. Written resignation is mandatory to submit, and Account
                                            Head approval is mandatory.</p>
                                        <p><b>2.1.3</b> Resignation date will be the date on which it is uploaded in EMS. Reporting
                                            Manager and/or Account Head will define notice start date (max day -3) and
                                            notice end date will auto populated from 30 days of notice start date.</p>
                                        <p><b>2.1.4</b> Leaves are generally not allowed during notice period.</p>
                                        <p><b>2.1.5 </b>Prior to separation from the Company, the employee must return all property,
                                            equipment, materials, records & documents, borrowed from the company and
                                            obtain clearance of all outstanding dues (NDC) from the Company.</p>
                                    </dd>
                                </dl>
                                <p><b>2.2 Acceptance of Resignation</b></p>
                                <dl>
                                    <dd>
                                        <p><b>2.2.1</b> Reporting Manager and/or Account Head can grant acceptance of the
                                            resignation. This is to be done after the Reporting Manager and/or Account
                                            Head has had a meeting with the employee who wishes to resign.</p>
                                        <p><b>2.2.2</b> Resignations are accepted taking into consideration the replacement plans for
                                            the position and planned for an effective handover.</p>
                                        <p><b>2.2.3 </b>Once the Account Head accepts the resignation it is transferred to the HR Head.
                                            HR Head can edit resignation within 3 days of notification else it will be auto accepted. HR Head has the rights to Revoke Resignation or Accept depending
                                            upon the discussion with employee. </p>
                                        <p><b>2.2.4</b> Employee has an option to revoke resignation up to <b>15 days </b>of declaration date.</p>
                                        <p><b>2.2.5</b> If employee opts to revoke resign that request will go to Account Head and then
                                            HR Head for Approval. In case of approval, resignation will be considered as
                                            canceled and employee will continue services & in case of rejection last working
                                            day will remain same.</p>
                                    </dd>
                                </dl>
                                <p><b>2.3 Waiving Of Notice Period</b></p>
                                <dl>
                                    <dd>
                                        <p><b>2.3.1</b> In certain cases, the notice period agreement term can be considered on case to case basis. </p>
                                    </dd>
                                </dl>
                                <p><b>2.4 Clearance Procedure</b></p>
                                <dl>
                                    <dd>
                                        <p><b>2.4.1</b> A resignation acceptance / acknowledgement letter is made available to the
                                            employee along with the necessary No Dues Certificate for obtaining clearance
                                            from relevant Departments. </p>
                                    </dd>
                                </dl>
                                <p><b>2.5 Full & Final Settlements of Individual Accounts
                                    </b></p>
                                <dl>
                                    <dd>
                                        <p><b>2.5.1</b> The full and final settlements will be done within 45 working days of the
                                            employee leaving the organization.</p>
                                        <p><b>2.5.2</b> In full and final settlements any dues payable by the employee to the employer
                                            by way of advances taken, notice period compensation amount, leaves
                                            adjustment, non-serving of notice period, etc. will be deducted and if any
                                            amount payable / receivable to / from employee has to be settled and only then
                                            the F&F amount will be credited.
                                        </p>
                                    </dd>
                                </dl>
                                <p><b>2.6 Cessation of services</b></p>
                                <dl>
                                    <dd>
                                        <p>Under certain circumstances not limited to process ramp down initiated by the
                                            client, change in business model, etc., the company might not need the services of
                                            a set of resources engaged in the delivery of the said process. In such scenarios, the
                                            company strives to redeploy such redundant resources elsewhere which may
                                            include a change in the locations both inter and intra city. Where such options are
                                            not available, the company may choose to relieve such resources from their services
                                            after giving due notice of 30 days/ compensation in lieu thereof. This is done in the best practices prevalent in the industry, however in certain cases
                                            where the employee/s may reject these redeployment options, the company
                                            reserves the right to relieve them with immediate effect without any additional
                                            compensation.
                                        </p>
                                    </dd>
                                </dl>
                                <p><b>2.7 Termination of services
                                    </b></p>
                                <dl>
                                    <dd>
                                        <p><b>2.7.1</b> Under exceptional circumstances, if it comes to the notice of the Company that an
                                            employee is indulging in unacceptable professional behavior not limited to, not
                                            following the systems and procedures defined for executing his / her duties,
                                            responsibilities and work and if such actions are likely to cause harm to the
                                            business of the Company or cause loss of revenue or loss of client or adversely
                                            affect the Company’s reputation or revenue or business in any way, then the
                                            Company can terminate the services of the employee without any notice and with
                                            immediate effect and without any liability towards the Company. These decisions
                                            are taken basis due acceptance in writing from the impacted employee for such
                                            unacceptable behavior.</p>
                                    </dd>
                                </dl>
                                <p><b>2.8 Retirement</b></p>
                                <dl>
                                    <dd>
                                        <p>The normal retirement age shall be 58 years. An extension of appointment beyond this
                                            age requires approval from the Board and prevailing government rules at the time of
                                            the decision.</p>
                                    </dd>
                                </dl>
                            </dd>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">Information Technology Security Policy</p>
                            <p><b>1. Applicability of the Policy </b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”). </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <p>It is the policy of Company as to prohibit the unauthorized use of the network itself and those
                                computing resources connected to the network along with the below actions:</p>
                            <dd>
                                <p><b>2.1</b> Availability - ensure that systems, networks, applications, utilities, and data are on-line
                                    and accessible when authorized users need them.</p>
                                <p><b>2.2</b> Integrity - protect user information, data or software from improper modification or
                                    access (i.e., virus or unauthorized access/modifications.</p>
                                <p><b>2.3</b> Confidentiality - assure that sensitive data is read only by authorized individuals and is
                                    not disclosed to unauthorized individuals or to the public.</p>
                                <p><b>2.4</b> Propriety - ensure that technology at Company is used only for its intended purposes
                                    and not for any prohibited activities and uses.</p>
                            </dd>
                            <p><b>3. Conditions and Procedures for Use</b></p>
                            <p>Company shall adhere to the conditions and procedures set forth in this policy. Violation of this
                                policy will result in the loss of network privileges and may result in criminal or civil prosecution
                                and/or disciplinary action for employees up to and including discharge. All employees of
                                Company shall abide by the following conditions and procedures:</p>
                            <dl>
                                <dd>
                                    <p><b>3.1 </b>Employees shall not make unauthorized copies of data or software; however, the user
                                        is responsible for ensuring that data under their purview is adequately and routinely
                                        backed up.</p>
                                    <p><b>3.2</b> Employees are to choose passwords wisely and to keep them confidential.</p>
                                    <p><b>3.3 </b>Users shall not allow access or use of their account to any other individual or group.
                                        These accounts include Windows account, E-mail accounts, Phone accounts or any
                                        other accounts that are introduced by.</p>
                                    <p><b>3.4</b> Users shall not leave their computer logged in to networked services and unattended.
                                        Users shall use password protected screen savers and/or log out of applications before
                                        leaving the computer.</p>
                                    <p><b>3.5</b>Users shall not give system, accounts or any site related information to an
                                        unauthorized person either in person in any manner, by telephone, e-mail, written
                                        material, etc.</p>
                                    <p><b> 3.6</b> Users shall not type a command or a password for an unauthorized person.</p>
                                    <p><b> 3.7 </b>Users shall not send security related information (i.e., a password) over E- mail.</p>
                                    <p><b> 3.8</b> The networked servers are not for personal use and the organization will not be held
                                        liable for safeguarding any personal data or programs placed on the servers.</p>
                                    <p><b> 3.9</b> Users are not to install or execute any programs or processes which are designed to
                                        gather information about the Company network, the servers, or other machines on the
                                        Internet, both inside and outside of Company.</p>
                                    <p><b> 3.10</b> Users are not to purposefully access any servers or computers in a manner which
                                        disguises the user’s identity, computer name, address, location, or other identification
                                        of the electronic source.</p>
                                    <p><b> 3.11 </b>Systems Department reserves the right to remove user accounts and/or revoke
                                        network access privileges for cause.</p>
                                    <p><b> 3.12</b> For purposes of this policy, "cause" is defined as the user's failure to adhere to the
                                        conditions or procedures set forth in this policy or engaging in any other inappropriate
                                        conduct with respect to the Company.</p>
                                    <p><b> 3.13 </b>Security violations or unusual activity should be reported immediately to IT
                                        Department of the Company.</p>
                                </dd>
                            </dl>
                            <p><b>4. Prohibited Activities and Uses</b></p>
                            <dd>
                                <p><b>4.1 </b>The network shall not be used to transmit any communication where the meaning of
                                    the message, the content of the file, or the operation of the application, including its transmission or distribution, would violate any applicable law or regulation or would
                                    likely be offensive to the recipient or recipients thereof.</p>
                                <p><b>4.2 </b>For example, the use of foul, obscene, discriminatory language or images when sending
                                    or displaying messages on e-mail is prohibited.</p>
                                <p><b> 4.3</b> Also, it is unacceptable to use the Internet to send, display, download or print offensive
                                    messages or pornographic materials or sexually explicit pictures, derogatory religious
                                    or racial or defamatory material.
                                </p>
                            </dd>
                            <p><b>5. Monitoring</b></p>
                            <p>The Company does intend, as a matter of policy, to randomly monitor the use of technology
                                (including e-mail) and will consider the individual user's limited interest in privacy to the
                                extent feasible and consistent with Company’s interests and goals set forth herein.</p>
                            <p><b>6. Virus Control and other Security Compromises</b></p>
                            <dd>
                                <p><b>6.1</b> Users must ensure that any media (i.e., disks, CDs or any computer equipment)
                                    brought into the Company from outside is free of viruses, worms, or other
                                    compromises before used in a PC or connected to the network. If a user is uncertain
                                    how to check a disk or computer, he or she should contact the helpdesk.</p>
                                <p><b> 6.2</b> If a virus, worm, or compromise is detected or suspected, the user should contact the
                                    helpdesk immediately.</p>
                                <p><b> 6.3</b> Users should use EXTREME CAUTION when opening e-mail attachments. E-mail has
                                    become the most likely way viruses are spread. If a user does not know the recipient
                                    or is not expecting the attachment, then the attachment should NOT be opened.</p>
                                <p><b> 6.4</b> Another frequently used "social virus" is in the form of an e-mail that urges the
                                    recipient to send everyone he or she knows a copy of the e-mail. Often it proposes to
                                    protect against a new virus or serious incident.</p>
                                <p><b> 6.5 </b>Users should NOT forward copies of such an e-mail, which often is a hoax. Users can,
                                    however, forward one copy to the helpdesk to verify the claim.</p>
                            </dd>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">Intellectual Property & Security</p>
                            <p><b>1. Applicability of the Policy</b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”). </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <dd>
                                <p><b>2.1</b> During their employment with Company all intellectual property developed by the
                                    employees, discoveries or inventions made by them in the performance of duties
                                    related in any way to the business of Company or any related entities will be the
                                    intellectual property of the Company or its related entities.</p>
                                <p><b> 2.2</b>Employees will be required to do everything necessary to ensure Company or its
                                    related entities have ownership of such intellectual property.</p>
                                <p><b> 2.3 </b>Failure to properly look after company information or property will result in
                                    disciplinary proceedings including dismissal.</p>
                                <p><b>2.4</b> Wise and limited use of Company’s Logos, Trademarks & Stationery. We seek
                                    employee cooperation in protecting the company’s interest by ensuring that Company
                                    logos are used only with the formal consent of the company. The company’s
                                    letterheads, business cards and other stationery are to be used only by Company staff
                                    and only for officially sanctioned business correspondence. </p>
                            </dd>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">Loss of Damage to Company Property</p>
                            <p><b>1. Applicability of the Policy </b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”). </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <dd>
                                <p><b>2.1</b> Employee (s) may be issued certain tools or equipment in order to perform their jobs
                                    like computers, laptops, cell phones, data storage drives, etc. These items belong to the
                                    Company but are placed in employees’ care and custody.</p>
                                <p><b>2.2 </b> In certain cases, company hardware has been made available to some employees. This
                                    is not an entitlement and is dependent on the nature of the job assigned by the
                                    management. In case of loss of hardware, you are expected to register FIR with the
                                    police; complete the necessary insurance formalities and follow-up on the same. A copy
                                    of the FIR should be handed over to the HR Department. In such case, where the cost of
                                    the hardware is more than the cost of the insurance receivable, the difference amount
                                    will be deducted from the employee’s salary. If the allotted hardware is damaged and
                                    the circumstances/sequence of events displays malafide intentions, the cost of the
                                    damage/replacement may be recovered from the employee’s salary.</p>
                                <p><b> 2.3</b>The employee is expected to return the hardware in good condition on cessation of your
                                    service. The company reserves the right to make deductions from their salary for any
                                    damages based on the evaluation rate determined by the Finance Department.</p>
                                <p><b> 2.4</b> Employees will be required to sign for these items, which include an authorization to
                                    deduct their replacement value from your paycheck, if they fail to return them to the
                                    Company.</p>
                                <p><b>2.5</b> Any loss, damage or misuse of company equipment like change in the original condition,
                                    download or upload of unauthorized material / software(s), sharing with unauthorized
                                    person(s) shall result in disciplinary proceedings including dismissal.</p>
                            </dd>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">Personal Phone Usage Policy</p>
                            <p><b>1. Applicability of the Policy </b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”). </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <p>The use of personal cell phones while at work presents a disturbance & distraction to the user
                                and their colleagues. This policy is to ensure that cell phone use while at work is limited and does
                                not disrupt the work environment. Any employee at the Company needs to ensure the below:</p>
                            <dd>
                                <p><b>2.1 </b>While at work, employees are expected to exercise discretion in using personal
                                    cellphones. Excessive personal calls during the workday can interfere with employee
                                    productivity and be distracting to others. Employees are encouraged to make any
                                    personal calls during non-work time when possible and to ensure that friends and
                                    family members are aware of Company policy.</p>
                                <p><b> 2.2 </b>For Agents, NO cell phones are allowed on the operations floor. Agents will be provided
                                    with lockers to store their phones during working hours.</p>
                                <p><b>2.3</b> Personnel permitted use of their cell phones on the operations floor are expected to
                                    always keep their devices on vibration or silent mode. Avoid ringtones during office
                                    hours.</p>
                                <p><b> 2.4 </b>Cellphones are discouraged during team or client meetings.</p>
                                <p><b> 2.5 </b>This policy is applicable to all (except the various department heads).</p>
                                <p><b> 2.6 </b>Team leaders and Managers are requested to give instructions to their team members
                                    to follow the same and report those who violate the policy to the HR.</p>
                                <p><b> 2.7 </b>Improper use of cell phones may result in disciplinary action.</p>
                                <p><b> 2.8 </b>Continued use of cell phones at inappropriate times or in ways that distract from work
                                    may lead to having cell phone privileges revoked.</p>
                                <p><b>2.9 </b>Cell phone usage for illegal or dangerous activity, for purposes of harassment, or in
                                    ways that violate the company confidentiality policy may result in employee
                                    termination.</p>
                                <p><b> 2.10 </b>Due to the sensitive nature of this work, the company reserves the right to audit
                                    cellphones/ laptops/ tablets or any other digital equipment of the employee (with due
                                    cooperation from the employ where a data breach has been reported or suspected. In
                                    such circumstances, the employee from whom this hardware is recovered and
                                    subjected to scrutiny, will be kept abreast of all procedural actions and reasons
                                    thereof. The purpose of these investigations is to close such reported breaches
                                    adequately through internal checks and to avoid involvement of local authorities.</p>
                            </dd>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">Internal Communication Policy</p>
                            <p><b>1. Applicability of the Policy </b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”).
                            </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <dd>
                                <p><b>2.1</b> Company believes in, and encourages an environment where all employees feel free
                                    to talk openly. This means that people would be accessible easily and would encourage
                                    communication in an open manner.</p>
                                <p><b> 2.2 </b>The Company shares the information related to various areas like organizational plans,
                                    latest developments in various projects, policies, etc. on a continuous basis with the
                                    employees through the EMS (Employee Management System).</p>
                                <p><b> 2.3 </b>Employee suggestions, ideas and feedback are welcome and appreciated.</p>
                                <p><b> 2.4</b> All employees are encouraged to discuss their concerns and suggestions with their
                                    Immediate Manager/Supervisor, Team Lead/ HR or any other concerned person.</p>
                                <p><b> 2.5 </b>The Company has created an easy to access platform called “Happy to Help” on the
                                    EMS, for all employees to raise their concerns related to attendance, policies, technical
                                    or administrative issues.</p>
                                <p><b> 2.6 </b>The Company firmly believes in an Open-Door Policy and recognizes that in any
                                    employee group, problems, difficulties, and misunderstandings may arise. Contact
                                    numbers of the reporting chain up to the level of the COO is duly published on company
                                    intranet and is accessible to all individuals. The objective is to provide free and
                                    transparent communication within the organisation.
                                    Similarly, all lady employees have access on the intranet, of the contact numbers of
                                    the Internal complaints committee mandated under the POSH policy, to address their
                                    grievances which fall under this category. It is the desire of the company to see that
                                    every problem is handled promptly and effectively. To this end, the company will
                                    endeavour:</p>
                                <dl>
                                    <dd>
                                        <p><b>2.6.1 </b>To invite employees to talk frankly with their supervisor or to anyone else in
                                            authority, when they have a problem of any kind, with the assurance that it will
                                            not be held against them by their supervisor or anyone else in authority.</p>
                                        <p><b> 2.6.2 </b>To provide an open door at all times for employees to discuss with upper
                                            management any decision they feel to be unfair.</p>
                                        <p><b>2.6.3 </b>The company is most sincere in encouraging any employee who feels he or she
                                            has not been treated properly, or who has a problem of any kind, to make it
                                            known to management through the “open door policy”.</p>
                                    </dd>
                                </dl>
                            </dd>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">The Prevention of Sexual Harassment Policy for
                                Women at the Workplace </p>
                            <p><b>1. Applicability of the Policy </b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”).
                            </p>
                            <p>Every employee deserves and has the right to be protected against sexual harassment. In
                                accordance with the law as well as the ethics of this organization there shall be zero tolerance of
                                sexual harassment of women at the workplace. <b>No person shall indulge or cause to be indulged
                                    in sexual harassment of women at the workplace.</b> </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <p><b>Sexual Harassment </b>means and includes any one or more of the following unwelcome actions:</p>
                            <dd>
                                <p><b>2.1 </b>Physical contact and/or advances; For e.g., unwelcome grabbing or deliberate touching
                                    or brushing against any part of the body of another person and the like, staring intently,
                                    whistling, winking, stroking private parts.</p>
                                <p><b>2.2 </b>Soliciting sexual favors; For e.g., forcing a subordinate to provide sexual favors by
                                    promising promotion, pay increases or an easier work life, or threatening demotion
                                    unless, i.e. implied/ explicit threat of present or future employment status.</p>
                                <p><b>2.3 </b>Making sexually colored remarks; For e.g., making enquiries about anyone’s sex life,
                                    making sexual inferences or gender-based sexist remarks or jokes which are sexually
                                    colored, lewd statements about one’s appearance/ body/ clothing.</p>
                                <p><b> 2.4 </b>Showing or dissemination of pornography.</p>
                                <p><b> 2.5 </b>Any other unwelcome physical, verbal or non-verbal conduct of sexual nature; For e.g.,
                                    forcing a woman to drink or dance or hug against her wishes at a corporate event,
                                    sharing or emailing/ SMS-ing/ MMS-ing/ Whatsapp-ing sexist jokes or making sexist
                                    remarks, pursuing a woman Employee with “romantic” intentions and forcing
                                    reciprocation, clicking pictures of a woman Employee without their consent.</p>
                                <p><b>2.6</b> Humiliating treatment based on sexual remarks likely to affect health and safety.</p>
                                <p><b>2.7</b> Interfering with work, creating a hostile or offensive work environment directly or
                                    indirectly based on sexual remarks or sexual advances.</p>
                            </dd>
                            <p><b>Workplace</b> includes the Company premises, or any other place visited by the Employee during
                                the course of employment. This includes Company offsite, office meetings held outside the office
                                premises whether in a hotel, restaurant or at an Employee’s residence or any other place. A
                                restaurant where an Employee stops by for a meal during the course of visiting or returning from
                                a business meeting outside the office would be deemed to be a Workplace.</p>
                            <p><b>Internal Complaints Committee</b></p>
                            <p>In keeping with the Sexual Harassment of Women at Workplace (Prevention, Prohibition and
                                Redressal) Act, 2013 (“<b>Act</b>”) and the Rules under the Act, the Company has constituted an
                                Internal Complaints Committee to receive and enquire into any complaints made by female
                                Employees of this Company and follow due procedure as laid down by the law.</p>
                            <p>Should a female Employee wish to report any incident that is in violation of this policy, she may
                                approach any member of the Internal Complaints Committee listed below. All complaints and
                                proceedings before the Internal Complaints Committee shall be completely confidential. </p>
                            <p>Internal Complaints Committee:</p><br>
                            <table>
                                <tr>
                                    <td>Name</td>
                                    <td>Designation</td>
                                    <td>Contact Details</td>
                                </tr>
                                <tr>
                                    <td style="width: 30%;"><b>Ms. Banpreet</b> <br><br>PRESIDING OFFICER</td>
                                    <td>General Manager</td>
                                    <td rowspan="4">complaints@cogenteservices.com</td>
                                </tr>

                                <tr>
                                    <td><b>Ms. Sapna Verma</b><br> <br>MEMBER OF COMMITTEE </td>
                                    <td>Manager</td>
                                </tr>
                                <tr>
                                    <td><b>Mr. S.K Garg</b><br> <br>MEMBER OF COMMITTEE </td>
                                    <td>General Manager</td>
                                </tr>
                                <tr>
                                    <td><b>Ms. Surbhi Gupta</b><br> <br>MEMBER OF COMMITTEE </td>
                                    <td> External Member</td>
                                </tr>
                            </table><br>
                            ,<p>The Internal Committee, may, before initiating an enquiry,<b> at the request of the aggrieved
                                    employee</b> take steps to settle the matter between them and the Respondent through conciliation
                                provided that no monetary settlement shall be made as a basis of conciliation.</p>
                            <p>Upon the receipt of complaint and completion of an enquiry, if the Internal Complaints
                                Committee establishes that the allegation against the Respondent has been proved, they shall
                                recommend to the Employer to take any of the following, one or combined actions including:
                            </p>
                            <dd>
                                <p><b>1. </b>Written apology,</p>
                                <p><b>2. </b>Warning, reprimand, or censure</p>
                                <p><b> 3. </b>Withholding of promotion</p>
                                <p><b> 4. </b>Withholding of pay rise or increments</p>
                                <p><b> 5. </b>Terminating the Respondent from service</p>
                                <p><b> 6.</b> Undergoing a counseling session or carrying out community service</p>
                            </dd>
                            <p>The law also provides that where the Internal Complaints Committee arrives at the conclusion
                                that the allegation against the Respondent is malicious or the complainant has made the
                                complaint knowing it to be false or if the complainant has produced any forged or misleading
                                document, it may recommend to the employer to take appropriate action. </p>
                            <p style="text-align: center; font-weight: bold; font-size: x-large;text-decoration: underline; ">ZTP - Zero Tolerance Policy</p>
                            <p><b>1. Applicability of the Policy </b></p>
                            <p>This Policy applies to all persons employed at Cogent E Services (hereinafter referred to as the
                                “Company”) for any work on regular, temporary, ad hoc or daily wages and includes co-workers,
                                a contract worker, probationer, trainee, apprentice, intern or any other person called by any
                                other such name (“<b>Employee</b>”).
                            </p>
                            <p><b>2. Scope of the Policy</b></p>
                            <p>Zero Tolerance Policy (ZTP) is implemented by the Company to proactively prevent and manage
                                employee behavior that is illegal, inappropriate, or against your organization’s Code of Conduct.
                                This policy: </p>
                            <dd>
                                <p><b>2.1</b> Provides clarity to employees on topics such as workplace behavior and disciplinary
                                    processes.</p>
                                <p><b> 2.2 </b>Improves workplace culture and employee performance by making employees feel
                                    more psychologically and physically safe at work.</p>
                                <p><b> 2.3 </b>Minimizes organizational risk by reducing unwanted workplace behavior and providing
                                    leaders with a method to quickly and effectively resolve conflicts.</p>
                                <p><b>2.4</b> The policy applies to any individual who engages in the following behavior (s), not
                                    limited to: </p>
                                <dl>
                                    <dd>
                                        <p><b>2.4.1 Integrity -</b> Making derogatory remarks about the Company or its client’s
                                            product/ processes or Company’s products/ process; intentionally sharing
                                            wrong information or misleading the client about the products/ processes,
                                            asking for personal information from the client which may not be required for
                                            the business.</p>
                                        <p><b>2.4.2 Unfair Practices - </b>Examples of unfair practices include, but are not limited to:</p>
                                        <p> i. Call disconnection</p>
                                        <p>ii. Dialing of personal number or unethical use of dialer</p>
                                        <p> iii. Holding the call without any reason</p>
                                        <p> iv. Interfering in any fashion with the system</p>
                                        <p>v. Using mobile phone on floor</p>
                                        <p>vi. Being rude or use of foul language or sarcastic/rude tone on the call</p>
                                        <p> vii. Giving waivers or any concession to customers without process approval </p>
                                        <p><b>2.4.3 Harassment - </b>Harassment is any one-time or repeated unwanted physical,
                                            verbal, or non-verbal conduct that violates a person’s dignity or creates an
                                            intimidating, hostile, degrading, uncomfortable, or toxic environment.
                                            Examples of harassment include, but are not limited to:</p>
                                        <p> i. Making threatening remarks</p>
                                        <p> ii. Sexual assault</p>
                                        <p> iii. Gender-based insults or jokes causing embarrassment or humiliation</p>
                                        <p> iv. Repeated unwanted social or sexual invitations</p>
                                        <p>v. Inappropriate or unwelcome comments on a person’s physical attributes
                                            or appearance </p>
                                        <p><b>2.4.4 Bullying – </b>Bullying is any physical, verbal, and non-verbal conduct that is
                                            malicious or insulting. Bullying can make a person feel vulnerable, excluded,
                                            humiliated, undermined, fearful, or threatened. Bullying can take the form of
                                            physical, verbal, and non-verbal conduct. Examples of bullying include, but are
                                            not limited to:</p>
                                        <p> i. Physical threats</p>
                                        <p> ii. Psychological threats</p>
                                        <p> iii. Overbearing or intimidating levels of supervision</p>
                                        <p> iv. Shouting at colleagues in public or private</p>
                                        <p> v. Spreading malicious rumours</p>
                                        <p><b>2.4.5 Discriminatory Behavior – </b>Discrimination refers to behavior that treats
                                            people differently or adversely because of one or more of the facets of their
                                            identity, including race, color, ethnic origin, gender expression, religion, age,
                                            sex, sexual orientation, marital status, family status, physical or mental disability, or genetic characteristics. Examples of discrimination include, but
                                            are not limited to:</p>
                                        <p>i. Making insensitive jokes</p>
                                        <p> ii. Factoring an individual’s identity into a hiring decision</p>
                                        <p> iii. Purposefully excluding a colleague on the basis of their gender</p>
                                        <p>iv. Using a racial insult</p>
                                        <p><b>2.4.6 Micro-aggressions – </b>Micro-aggressions refers to obvious or subtle, direct or
                                            indirect behaviors and comments which reference an individual’s personal
                                            identity, such as their race, gender, ethnic origin, religion, or age. Over time,
                                            micro-aggressions can have lasting emotional and mental effects on the
                                            individual or individuals targeted and can contribute to a toxic and noninclusive workplace. Examples of micro-aggressions in the workplace can
                                            include, but are not limited to:</p>
                                        <p> i. Calling a woman “bossy”</p>
                                        <p> ii. Repeatedly calling a racialized employee by the name of a different
                                            person of the same race</p>
                                        <p> iii. Commenting on a person’s physical appearance in reference to racial
                                            characteristics such as skin tone </p>
                                    </dd>
                                </dl>
                            </dd>
                            <p><b>3. Disciplinary Action</b></p>
                            <dd>
                                <p><b>3.1</b> Employees who are found to be in violation of the Zero Tolerance Policy may face a
                                    variety of disciplinary actions, up to and including immediate termination.</p>
                                <p><b>3.2</b> Disciplinary action may be recommended by an independent investigator and will be
                                    determined by senior leadership. The severity of the disciplinary action depends on the
                                    type of misconduct, which is based on the following framework:
                                </p>
                                <dl>
                                    <dd>
                                        <p><b>3.2.1 Unintentional instances</b> of bullying, micro-aggressions and discriminatory
                                            behaviour like unintentionally making an offensive comment about a
                                            colleague’s appearance. Discipline of such actions includes but is not limited
                                            to - mandatory warning and mandatory training programs.</p>
                                        <p><b>3.2.2 Intentional but minor instances</b> of harassment, bullying, or discrimination,
                                            such as making sexist, racist or homophobic jokes or propositioning a
                                            colleague. Discipline for a such includes but is not limited to: temporary leave
                                            with pay, temporary leave without pay, and permanent dismissal.
                                        <p><b> 3.2.3 Intentional & major instances </b>of harassment, bullying, or discrimination, such
                                            as making threatening remarks, engaging in unwanted physical contact, or
                                            using racial slurs. Discipline for such misconduct includes, but is not limited to:
                                            immediate dismissal and legal recourse.</p>
                                    </dd>
                                </dl>
                            </dd>
                        </div>

                    </body>
                </div>
            </center>
        </div>
    </div>
</div>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>