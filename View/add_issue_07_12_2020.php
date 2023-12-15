<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
$value=$counEmployee=$countProcess=$countClient=0;
$mymsg='';

if(isset($_POST['btnSave']))
{
	$belongsto=$_POST['queryto'];
	$issue=$_POST['querysub'];
	$handler=$_POST['handler'];
	$remark=trim($_POST['remark']);
	$MobNo =$_POST['txtMob']; 
	$concern_off =$_POST['concern_off'];
	$committed_with =$_POST['committed_with'];
	$createdby=$_SESSION['__user_logid'];
	if(strlen($remark)>250 && strlen($remark)<1000 ){
		if(strstr($remark,'>') ){
			$remark=str_replace('>','greater than',$remark);
		}
		if(strstr($remark,'<')){
			$remark=str_replace('<','less than',$remark);
		}
		$myDB=new MysqliDb();	
		if($belongsto!="" && $belongsto!="NA"  && $issue!="" && $issue!="NA" &&  $handler!="" &&  $handler!="NA" && $remark!="" && $MobNo!="" && $concern_off!="" && $committed_with!="" && $createdby!=""){
			$query='call add_issueticket("'.$createdby.'","'.$belongsto.'","'.$issue.'","'.$handler.'","'.addslashes($remark).'","Manual","'.$MobNo.'","'.$committed_with.'","'.$concern_off.'");';
			$myDB->query($query);
			$error = $myDB->getLastError();
			//echo $query;
			if(empty($error))
			{
				$myDB = new MysqliDb();
				$gender_f = $myDB->query("call getGender('".$createdby."')");
				$gender_m = $gender_f[0]['Gender'];
				if(strtoupper($gender_m) == 'FEMALE')
		 	{
				$gender_last = 'Mrs.';
			}
			else
			{
				$gender_last = 'Mr.';
			} 	
			
			echo "<script>$(function(){ toastr.success('This issue has been registered with us. We will try our best to resolve it as soon as possible. Thank You ".$gender_last.". ".$_SESSION['__user_Name']." ') }); </script>";
			
			
			
			$myDB = new MysqliDb();
			$dataContact = $myDB->query("call get_contact('".$createdby."')");
			$mailID = $dataContact[0]['emailid'];
			
			//if(!empty($mailID)) //for Employee E-Mail ID Existence 
			if(true)
			{
				$myDB=new MysqliDb();
				$pagename='add_issue';
				
				$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$_SESSION["__location"]."'");	
				$emailid=$mailID;
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = EMAIL_HOST; 
				$mail->SMTPAuth = EMAIL_AUTH;
				$mail->Username = EMAIL_USER;   
				$mail->Password = EMAIL_PASS;                        
				$mail->SMTPSecure = EMAIL_SMTPSecure;
				$mail->Port = EMAIL_PORT; 
				$mail->setFrom(EMAIL_FROM,  'EMS:Cogent Grievance System');
				//$mail->AddAddress('md.masood@cogenteservices.com');
				if(count($select_email_array) > 0 && $select_email_array){
					foreach($select_email_array as $Key=>$val)
		        	{
		        		$email_address = $val['email_address'];
						
						if($email_address!=""){
							$mail->AddAddress($email_address);
						}
						$cc_email=$val['ccemail'];
						
						if($cc_email!=""){
							$mail->addCC($cc_email);
						}			
							
						
					}
									
				}			
				$myDB = new MysqliDb();
				$refID = $myDB->query("select max(id) as id from issue_tracker  where requestby ='".$createdby."' and cast(request_date as date)= curdate() limit 1;");
				$refID_id = $refID[0]['id'];
				$EMS_CenterName = "";
				//$mail->Subject = 'Happy to help '.EMS_CenterName.', Reference #'.$refID_id;
				
				if($_SESSION["__location"]=="1")
				{
					$EMS_CenterName = "Noida";
				}
				else if($_SESSION["__location"]=="2")
				{
					$EMS_CenterName = "Mumbai";
				}
				else if($_SESSION["__location"]=="3")
				{
					$EMS_CenterName = "Meerut";
				}
				else if($_SESSION["__location"]=="4")
				{
					$EMS_CenterName = "Bareilly";
				}
				else if($_SESSION["__location"]=="5")
				{
					$EMS_CenterName = "Vadodara";
				}
				else if($_SESSION["__location"]=="6")
				{
					$EMS_CenterName = "Mangalore";
				}
				else if($_SESSION["__location"]=="7")
				{
					$EMS_CenterName = "Bangalore";
				}
				else if($_SESSION["__location"]=="8")
				{
					$EMS_CenterName = "Bangalore Flipkart";
				}
				
				$mail->Subject = 'Happy to help '.$EMS_CenterName.', Reference #'.$refID_id;
				
				$mail->isHTML(true);
				$myDB = new MysqliDb();
				$info_emp = $myDB->query('call get_info_for_Issue_tracker("'.$createdby.'")');
				$error = $myDB->getLastError();
		//echo $query;
				if(empty($error))
				{
				
				$pwd_='<span>Dear Sir,<br/><br/><span><b>Please find below the concern raised in happy to help.</b></span>.<br /><br/> <b>Concern Subject: '.$issue.'</b>.<br /><br /><b>Concern:</b> '.$remark.'.<br/><br/><br/> Thank You</b>.<br/>Regard,<br/>'.strtoupper($_SESSION['__user_Name']).'(<b>&nbsp;'.$_SESSION['__user_logid'].'&nbsp;</b>)<br/><b>Designation  &nbsp;:&nbsp;</b>'.strtoupper($_SESSION['__user_Desg']).'<br/><b>Process &nbsp;:&nbsp;</b>'.$info_emp[0]['Process'].'&nbsp;(&nbsp;'.$info_emp[0]['sub_process'].'&nbsp;)<br /><b>Account Head &nbsp;:&nbsp;</b>'.$info_emp[0]['AccountHead'].'<br /><b>Report To &nbsp;:&nbsp;</b>'.$info_emp[0]['ReportTo'].'<br />';
				$mail->Body = $pwd_;
				}
				if(!$mail->send())
				 	{
				 		$mymsg .='.Mailer Error:'. $mail->ErrorInfo;
				 		$module='Happy to Help : Add Issue';
				 		$error_message=$mail->ErrorInfo;
						$error_log_add="call Add_email_error_log('".$module."','".$error_message."','".$_SESSION['__user_logid']."')";
						$myDB = new MysqliDb();
						$myDB->query($error_log_add);
				
				  	} 
				else
				 {
				    	$mymsg .='.Mail Send successfully.';
				   		$module='Happy to Help : Add Issue';
				 		$error_message="email sent successfully";
						$error_log_add="call Add_email_error_log('".$module."','".$error_message."','".$_SESSION['__user_logid']."')";
						$myDB = new MysqliDb();
						$myDB->query($error_log_add);
				 }
			}
			echo "<script>$(function(){ toastr.success('Query Submited Successfully') }); </script>";
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Query Not Submited') }); </script>";
		}
	  }	else{
	  	echo "<script>$(function(){ toastr.error('Please enter reqest carefully') }); </script>";
	  }
	}else
	{
		echo "<script>$(function(){ toastr.error('Remark should greater than 250 character and lesser than 1000 character') }); </script>";
	}	
}
?>

<script src="<?php echo SCRIPT.'jquery-ui.multidatespicker.js' ;?>"></script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Happy to Help</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Happy to Help</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	
				<div class="input-field col s6 m6">
					<select name="queryto" id="queryto" >
						<option value="NA">---Select---</option>
						<option value="Human Resource">Human Resource</option>
			            <option value="Information Technology">Information Technology</option>
			            <option value="Operation">Operation</option>
			            <option value="Administration">Administration</option>
						 <?php
						      					
												/*$sqlBy = array(
													'table' => 'dept_master',
													'fields' => 'dept_id,dept_name',
													'condition' =>"1"); 
												$myDB=new mysql();
												$resultBy=$myDB->select($sqlBy);
												if($resultBy){													
													$selec='';	
													foreach($resultBy as $key=>$value){
																											
														echo '<option value="'.$value['dept_master']['dept_id'].'" '.$selec.' >'.$value['dept_master']['dept_name'].'</option>';
													}
		
												}*/
												
						      	?>
					</select>
					<label for="queryto" class="active-drop-down active">Belongs To</label>
				</div>
				
				<div class="input-field col s6 m6">
					<select name="querysub" id="querysub" >
					<option value="NA">---Select---</option>
					</select>
					<label for="querysub" class="active-drop-down active">Issue</label>
				</div>
				
				<div class="input-field col s6 m6">

					<!--<select type="text" name="committed_with" id="committed_with"  >
					<option value="NA">---Select---</option>
					
					</select>-->
					<input type="text" name="committed_with" id="committed_with" />
					<label>Communicated With</label>
				</div>
				
				<div class="input-field col s6 m6">
		            <input type="text" maxlength="10" name="txtMob" id="txtMob" >
		            <label for="txtMob">Mobile No</label>
			    </div>
				
				<div class="input-field col s6 m6 hidden" >
		            <select id="handler" name="handler">
		            <!--<?php if($_SESSION["__location"]=="1") { ?>
		            <option value="CE03070003">Sachin Siwach</option>
		            <?php } else if($_SESSION["__location"]=="3") { ?>
		            <option value="CEM071712012">Vikas Bhandari</option>
		            <?php }  ?>-->
		            </select>
				    <label for="handler"  class="active-drop-down active">Handler</label>
			    </div>
			    
			    <div class="input-field col s12 m12">
					<input type="text" name="concern_off" id="concern_off"  />
					<label for="concern_off">Concern of</label>
				</div>
			    
				<div class="input-field col s12 m12">
					<textarea name="remark" id="remark" maxlength="1000" class="materialize-textarea"></textarea>
					<label for="remark">Remark</label>
				</div>
			
				<div class="input-field col s12 m12 right-align">
					<button type="submit" id="btnSave" name="btnSave" class="btn waves-effect waves-green">Request</button>
         		</div>
				
    </div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>					

<script>
function checkRepeat(str)
{
    var repeats = /(.)\1{3,}/;
    return repeats.test(str)
}
//$('#concern_off').datetimepicker({
//	timepicker:false,
//	format:'Y-m-d'
//});
$('#concern_off').multiDatesPicker();
	
$(function(){
$('#txtMob').keydown(function(event) {
    if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 

        // Allow: Ctrl+A
    (event.keyCode == 65 && event.ctrlKey === true) ||

        // Allow: Ctrl+V
    (event.ctrlKey == true && (event.which == '118' || event.which == '86')) ||

        // Allow: Ctrl+c
    (event.ctrlKey == true && (event.which == '99' || event.which == '67')) ||

        // Allow: Ctrl+x
    (event.ctrlKey == true && (event.which == '120' || event.which == '88')) ||

        // Allow: home, end, left, right
    (event.keyCode >= 35 && event.keyCode <= 39)) {
    // let it happen, don't do anything
        return;
    }
    else {
    // Ensure that it is a number and stop the keypress
        if ( event.shiftKey|| (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ) ){
        event.preventDefault(); 
        }
    }
    });
    
    // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
$('#btnSave').click(function(){
	        var validate=0;
	        var alert_msg='';		
	        $('#queryto').removeClass('has-error');	
	        $('#querysub').removeClass('has-error');
	        $('#querybody').removeClass('has-error');
	        $('#remark').removeClass('has-error');
	        
	        if($('#queryto').val()=='NA')
	        {
				$('#queryto').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
				validate=1;
				if($('#squeryto').size() == 0)
				{
				   $('<span id="squeryto" class="help-block">Header of Query can not be Empty.</span>').insertAfter('#queryto');
				}
			}
			if($('#querysub').val()=='NA')
	        {
				$('#querysub').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
				validate=1;
				if($('#squerysub').size() == 0)
				{
				   $('<span id="squerysub" class="help-block">Issue can not be Empty.</span>').insertAfter('#querysub');
				}
			}
			
			if($('#handler').val()=='NA')
	        {
				$('#handler').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
				validate=1;
				if($('#shandler').size() == 0)
				{
				   $('<span id="shandler" class="help-block">Handler can not be Empty.</span>').insertAfter('#handler');
				}
			}
			
			
			if(checkRepeat($('#remark').val()))
	        {
				$('#remark').addClass('has-error');
				validate=1;
				if($('#sremark').size() == 0)
				{
				   $('<span id="sremark" class="help-block">Remark should not contain Repeat character.</span>').insertAfter('#remark');
				}
			}
			if($('#remark').val().length < 250)
	        {
				$('#remark').addClass('has-error');
				validate=1;
				if($('#sremark1').size() == 0)
				{
				   $('<span id="sremark1" class="help-block">Remark should be greater than 250 character.</span>').insertAfter('#remark');
				}
			}
			if($('#remark').val().length > 1000)
	        {
				$('#remark').addClass('has-error');
				validate=1;
				if($('#sremark2').size() == 0)
				{
				   $('<span id="sremark2" class="help-block">Remark should be greater than 1000 character.</span>').insertAfter('#remark');
				}
			}else{
				var remarkStr = $('#remark').val();
				if((remarkStr.indexOf('>') >= 0) || (remarkStr.indexOf('<') >= 0) ){
					$('#remark').addClass('has-error');
				    if($('#sremark2').size() == 0)
					{
					   $('<span id="sremark2" class="help-block"> >  sign  and  <  sign not allow. </span>').insertAfter('#remark');
					   validate=1;
					}
				}
				
			}
	        if($('#txtMob').val() == '' )
	        {
				$('#txtMob').addClass('has-error');
				validate=1;
				if($('#stxtMob').size() == 0)
				{
				   $('<span id="stxtMob" class="help-block">Enter Current Mobile No.</span>').insertAfter('#txtMob');
				}
			}
			if($.trim($('#txtMob').val()).length<10)
	        {
				$('#txtMob').addClass('has-error');
				validate=1;
				if($('#stxtMob').size() == 0)
				{
				   $('<span id="stxtMob" class="help-block">Enter Valid Mobile No.</span>').insertAfter('#txtMob');
				}
			}
			if($('#committed_with').val() == '' )
	        {
				$('#committed_with').addClass('has-error');
				validate=1;
				if($('#scommitted_with').size() == 0)
				{
				   $('<span id="scommitted_with" class="help-block">Enter Communicated Person Name.</span>').insertAfter('#committed_with');
				}
			}
			if($('#concern_off').val() == '' )
	        {
				$('#concern_off').addClass('has-error');
				validate=1;
				if($('#sconcern_off').size() == 0)
				{
				   $('<span id="sconcern_off" class="help-block">Enter Concern Date.</span>').insertAfter('#concern_off');
				}
			}
	      	if(validate==1)
	      	{	
				return false;
			}
			else
			{
				$('#btnSave').addClass('hidden').hide();
			}
	       
	    });
    
 $('#querysub').change(function(){
	var tval = $(this).val();
	    $.ajax({
		  url: <?php echo '"'.URL.'"';?>+"Controller/getHandler.php?id="+tval+"&loc="+<?php echo '"'.$_SESSION['__location'].'"';?>
		}).done(function(data) { // data what is sent back by the php page
			$('#handler').html(data);
			$('select').formSelect();	
		});
	  });
	 $('#queryto').change(function(){
		var tval = $(this).val();
		    $.ajax({
			  url: <?php echo '"'.URL.'"';?>+"Controller/getIssue.php?id="+tval+"&loc="+<?php echo '"'.$_SESSION['__location'].'"';?>
			}).done(function(data) { // data what is sent back by the php page
				$('#querysub').html(data);
				$('#querysub').val('NA');
				$('select').formSelect();
		});
		
	  });
	   /*$('#queryto').change(function(){
		var tval = $(this).val();

		    $.ajax({
		    url: <?php echo '"'.URL.'"';?>+"Controller/getPersonName.php?id="+tval+"&action=getperson&id1="+<?php echo '"'.$_SESSION['__user_client_ID'].'"';?>
		    //url: <?php echo '"'.URL.'"';?>+"Controller/getPersonName.php?id="+tval+"&action=getperson"
			}).done(function(data) { // data what is sent back by the php page
			//alert(data);
				$('#committed_with').html(data);
				$('#committed_with').val('NA');
		});
	  });*/
});
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>