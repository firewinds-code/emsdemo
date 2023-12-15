<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');


$locationid='';

$myDB = new MysqliDb();	
$EmployeeID=strtoupper($_SESSION['__user_logid']);
 $sqlquery="select location from personal_details where EmployeeID = '".$EmployeeID."' ";
$result=$myDB->query($sqlquery);
if(count($result)>0)
{
	$locationid = $result[0]['location'];
}
if(isset($_POST['btnSave'])){
	if($_POST['empID']!=""  )
	{
		$myDB=new MysqliDb();
			
			$covidQuery ="SELECT id FROM `signup_policy_ack` where EmployeeID = '".$_SESSION['__user_logid']."' ";
			$rscovidack= $myDB->query($covidQuery);
			if(count($rscovidack)<1){
				 $query="Insert into `signup_policy_ack` set  EmployeeID='".$_POST['empID']."' ";
				$myDB = new MysqliDb();	
				$result=$myDB->query($query);
			}else{
				echo "<script>$(function(){ toastr.warning('Allready acknowledged'); }); </script>";
			}
		echo "<script>location.href='index.php'; </script>";
		
		
	}
}

$remark=$empname=$empid=$searchBy=$msg='';
$classvarr="'.byID'";

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">CODE OF CONDUCT POLICY</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

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
				
			<p colspan='2'style="padding-top:15px;"><b>Policy brief & purpose</b> </p>
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
				<p>I <?php echo $_SESSION['__user_Name']; ?> , do hereby declare that I have fully read and understood the Code of Conduct policy of Cogent E Services and agree to comply to the same. I understand that any non-compliance to the above policies may lead to disciplinary sanctions that can include up to termination of employment and even criminal prosecution under applicable laws.</p>
				<input type='hidden'	name='empID' id="empID" value="<?php echo $_SESSION['__user_logid']; ?>" >
				<div class="input-field col s12 m12 right-align" >
				   <button type="submit" name="btnSave" id="btnSave1" class="btn waves-effect waves-green">Acknowledge</button>
				</div> 
</div>	
		    
		   
<!--Form container End -->	

</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>

<script>
$(document).ready(function(){
		$('#btnSave1').click(function(){
			validate=0;
			alert_msg='';
			
				
				var empname= $('#empname').val().trim();
				if(empname==""){
				
	     			$(function(){ toastr.success('Employee Name should not be empty'); });
	     			return false;
				}
				 var empID= $('#empID').val().trim();
				if(empID==""){
					
	     			$(function(){ toastr.success('EmployeeID should not be empty'); });
	     			return false;
				} 
				var mobilenum= $('#mobilenum').val().trim();
				if(mobilenum==""){
					$('#mobilenum').focus();
	     			$(function(){ toastr.success('Mobile Number should not be empty'); });
	     			return false;
				}else
				{
					
			        if(($('#mobilenum').val().length)<10){
			        	$('#mobilenum').focus();
			        	$(function(){ toastr.success('Mobile Number should not be lees 10 digit'); });
	     					return false;
			        }
					
				}
				var address=$('#address').val().trim();
				if(address==""){
					$('#address').focus();
	     			$(function(){ toastr.success('Address should not be empty'); });
	     			return false;
				}
			
				
			
			
		});	
		
		
		
		
		$('.fadeIn').removeAttr('id','rmenu');
		
		
});
function isNumber(evt) 
{
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
return true;
}

</script>
<?php 
//if($induction_popup_flag==1 && $totalDays!=0 && $flag1==0){
?>
<script src="../Script/bootstrap2.min.js"></script>
<style> .disablediv {
    pointer-events:none;
    opacity:70% !important;
}</style>

<?php
//} 
?>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
