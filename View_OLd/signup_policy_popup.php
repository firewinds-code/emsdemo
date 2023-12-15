<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
 require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/head.mpt');
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" style="padding: 20px;">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">CODE OF CONDUCT POLICY</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<!-- <h4>Covid-19 Form</h4>	-->
			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--<table style="width:100%;">
				<tr>
					<td >To</td><td style="text-align: right;">Date:&nbsp;<?php echo date('d-m-Y'); ?> </td>
				</tr>
			</table>-->

				<p colspan='2' style="padding-top:15px;"><b>Policy brief & purpose</b> </p>
				<p>Our Employee Code of Conduct company policy outlines our expectations regarding employees’ behaviour towards their colleagues, supervisors, clients and overall organization. We promote freedom of expression and open communication. But we expect all employees to follow our code of conduct. They should avoid offending, participating in serious disputes and disrupting our workplace. We also expect them to foster a well-organized, respectful and collaborative environment. </p>
				<p><b>Scope</b></p>
				<p>This policy applies to all our employees regardless of employment agreement or rank.</p>
				<p><b>1. Compliance with law</b></p>
				<p>All employees must protect our company’s legality. They should comply with all environmental, safety and fair dealing laws. We expect employees to be ethical and responsible when dealing with our company’s finances, products, partnerships and public image.</p>
				<p>Employees must not expose, disclose or endanger information of customers, employees, stakeholders or our business in external forums like social media, newspaper, television, internet, radio etc. Always follow the internal escalation matrix defined by the organization in our Employee Management System (EMS).</p>
				<p><b>2. Respect in the workplace</b></p>
				<p>All employees should respect their colleagues. We won’t allow any kind of discriminatory behaviour, harassment or victimization. This includes any harassment in workplace including Sexual Harassment – refer to our POSH guidelines. Employees should conform with our equal opportunity policy in all aspects of their work, from recruitment and performance evaluation to interpersonal relations.</p>
				<p><b>3. Job duties and authority</b></p>
				<p>All employees should fulfil their job duties with integrity and respect toward customers, stakeholders and the community. </p>
				<p>We don’t tolerate malicious, deceitful or petty conduct for e.g. data manipulation, fraudulent activity on customer accounts etc. These are huge red flags and, if you’re discovered, you may face progressive discipline or immediate termination / criminal prosecution, depending on the severity of the issue.</p>
				<p>Working under the influence of alcohol or drugs, or consuming alcohol or drugs during hours of work, including paid and unpaid breaks, is unacceptable behaviour. Employees found in possession of illegal drugs or using illegal drugs while at work will be reported to the police and their employment terminated with immediate effect.</p>
				<p><b>4. Company asset</b></p>
				<p>Employee shouldn’t misuse company equipment or use it frivolously. A company asset provided to the employee in office or at a remote / home location must be maintained properly and returned in good working condition on due completion of the assignment / project. Failure to do so may lead to financial recovery or legal action. </p>
				<p>Should respect all kinds of incorporeal property. This includes trademarks, copyright and other property (information, reports etc.) Employees should use them only to complete their job duties.</p>
				<p><b>5. Absenteeism and tardiness</b></p>
				<p>Employees should follow their schedules. We expect employees to be punctual when coming to and leaving from work.</p>
				<p><b>6. Conflict of interest</b></p>
				<p>We expect employees to avoid any personal, financial or other interests that might hinder their capability or willingness to perform their job duties.</p>
				<p><b>7. Dual Employment</b></p>
				<p>To ensure that employees provide their full time and energy to their current job, Cogent does not permit dual employment. An employee must be formally relieved of his / her services with their previous employer before taking up any employment opportunity with Cogent. Failure to do so may lead to immediate termination of employment.</p>
				<p style="align-content: center;"><b><u>EMPLOYEE DECLARATION</u></b></p><br>
				<p>I , do hereby declare that I have fully read and understood the Code of Conduct policy of Cogent E Services and agree to comply to the same. I understand that any non-compliance to the above policies may lead to disciplinary sanctions that can include up to termination of employment and even criminal prosecution under applicable laws.</p>

			</div>


			<!--Form container End -->

		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>