<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
//Interview DB main Config / class file     $myDB=new MysqliDb($db_int_config_i);
require_once(__dir__.'/../Config/DBConfig_interview_array.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

$alert_msg ='';
$imsrc=URL.'Style/images/agent-icon.png';
$EmployeeID=$btnShow='';
$mrgstat='hidden';
$filename='';
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
//Check Employee is exist or not

if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
	$add=(isset($_POST['txt_address_add'])? $_POST['txt_address_add'] : null);
	$country=(isset($_POST['txt_address_country'])? $_POST['txt_address_country'] : null);
	$state=(isset($_POST['txt_address_state'])? $_POST['txt_address_state'] : null);
	$dist=(isset($_POST['txt_address_dist'])? $_POST['txt_address_dist'] : null);
	$city=(isset($_POST['txt_address_city'])? $_POST['txt_address_city'] : null);
	$tehsil=(isset($_POST['txt_address_tehsil'])? $_POST['txt_address_tehsil'] : null);
	$other=(isset($_POST['txt_address_other'])? $_POST['txt_address_other'] : null);
	$zip=(isset($_POST['txt_address_zip'])? $_POST['txt_address_zip'] : null);
	
	$add_p=(isset($_POST['txt_address_add_p'])? $_POST['txt_address_add_p'] : null);
	$country_p=(isset($_POST['txt_address_country_p'])? $_POST['txt_address_country_p'] : null);
	$state_p=(isset($_POST['txt_address_state_p'])? $_POST['txt_address_state_p'] : null);
	$dist_p=(isset($_POST['txt_address_dist_p'])? $_POST['txt_address_dist_p'] : null);
	$city_p=(isset($_POST['txt_address_city_p'])? $_POST['txt_address_city_p'] : null);
	$tehsil_p=(isset($_POST['txt_address_tehsil_p'])? $_POST['txt_address_tehsil_p'] : null);
	$other_p=(isset($_POST['txt_address_other_p'])? $_POST['txt_address_other_p'] : null);
	$zip_p=(isset($_POST['txt_address_zip_p'])? $_POST['txt_address_zip_p'] : null);
	
}
else
{
	$add=$country=$state=$dist=$city=$tehsil=$other=$zip=$add_p=$country_p=$state_p=$dist_p=$city_p=$tehsil_p=$other_p=$zip_p='';
}
 
if(isset($_REQUEST['empid']) && $EmployeeID==''  && !isset($_POST['txt_address_city']))
{
	$EmployeeID=$_REQUEST['empid'];
	$getDetails='call get_address("'.$EmployeeID.'")';
	$myDB=new MysqliDb();
	$result_all=$myDB->query($getDetails);
	if($result_all)
	{
		$add=$result_all[0]['address'];
		$country=$result_all[0]['country'];
		$state=$result_all[0]['state'];
		$dist=$result_all[0]['district'];
		$city=$result_all[0]['city'];
		$tehsil=$result_all[0]['tehsil'];
		$other=$result_all[0]['other'];
		$zip=$result_all[0]['zip'];
		$add_p=$result_all[0]['address_p'];
		$country_p=$result_all[0]['country_p'];
		$state_p=$result_all[0]['state_p'];
		$dist_p=$result_all[0]['district_p'];
		$city_p=$result_all[0]['city_p'];
		$tehsil_p=$result_all[0]['tehsil_p'];
		$other_p=$result_all[0]['other_p'];
		$zip_p=$result_all[0]['zip_p'];
		$btnShow=' hidden';
	}
	
	$getDetails='call get_personal("'.$EmployeeID.'")';
	$myDB=new MysqliDb();
	$result_all=$myDB->query($getDetails);
	if($result_all)
	{
		
		
	}else
	{
		echo "<script>$(function(){ toastr.error('Wrong Employee To Search') }); window.location='".URL."'</script>";
	}
}
elseif(isset($_POST['EmployeeID'])&&$_POST['EmployeeID']!='')
{
	$EmployeeID=$_POST['EmployeeID'];
}

if(isset($_POST['btn_address_Save']) && $EmployeeID!='')
{
	$myDB=new MysqliDb();	
	if($EmployeeID)
	{
		$modifyby=$_SESSION['__user_logid'];
		$Update='CALL manage_address("'.$EmployeeID.'","'.$add.'","'.$country.'","'.$city.'","'.$state.'","'.$tehsil.'","'.$dist.'","'.$other.'","'.$add_p.'","'.$country_p.'","'.$city_p.'","'.$state_p.'","'.$tehsil_p.'","'.$dist_p.'","'.$other_p.'","'.$zip.'","'.$zip_p.'","'.$modifyby .'")';
		//echo $Update;
		$myDB=new MysqliDb();
		$result = $myDB->query($Update);
		$mysql_error=$myDB->getLastError();
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Address Saved Successfully') });</script>";
			$btnShow=' hidden ';
			$myDB =new MysqliDb();
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Address not Saved ".$mysql_error." ') });</script>";
			$btnShow='';
		}
	}
	
}

?>

<script>
	$(document).ready(function(){
		$('select').formSelect();
		var usrtype=<?php echo "'".$_SESSION["__user_type"]."'"; ?>;
		if(usrtype === 'ADMINISTRATOR'||usrtype === 'HR')
		{
		}
		else if(usrtype === 'AUDIT')
		{
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled','true');
			$('button:not(.drawer-toggle)').remove();
			
			$('.imgbtnEdit').remove();
			$('.imgBtnUploadDelete').remove();
			
		}
		else if(usrtype === 'CENTRAL MIS')
		{
			
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled','true');
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
		}
		else
		{
			$('input,button:not(.drawer-toggle),select,textarea').remove();
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
			window.location = <?php echo '"'.URL.'/undefined"';?>;
		}
		$('#txt_Personal_DOB').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_Personal_Marriage_Date').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_Personal_DOJ').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_child_dob_1').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_address_country').change(function(){
			$.ajax({url: "../Controller/getState.php?ctr="+$(this).val(), success: function(result){
		        $("#txt_address_state").empty().append(result);
		        $(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				    if($(element).val().length > 0) 
				         {
				           $(this).siblings('label, i').addClass('active');
				         }
			         else
					     {
					 	     $(this).siblings('label, i').removeClass('active');
					     }
				});
				$('select').formSelect();
		        
		    }});
		});
		$('#txt_address_country_p').change(function(){
			$.ajax({url: "../Controller/getState.php?ctr="+$(this).val(), success: function(result){
		        $("#txt_address_state_p").empty().append(result);
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				    if($(element).val().length > 0) 
				         {
				           $(this).siblings('label, i').addClass('active');
				         }
			         else
					     {
					 	     $(this).siblings('label, i').removeClass('active');
					     }
					});
					$('select').formSelect();
		        
		    }});
		});
		$('#txt_address_state').change(function(){
			$.ajax({url: "../Controller/getDist.php?ctr="+$(this).val(), success: function(result){
		        $("#txt_address_dist").empty().append(result);
				         $(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
									         if($(element).val().length > 0) {
									           $(this).siblings('label, i').addClass('active');
									         }
									         else
							         {
									 	$(this).siblings('label, i').removeClass('active');
							 }
				         });
				         $('select').formSelect();
		        
		    }});
		});
		$('#txt_address_state_p').change(function(){
			$.ajax({url: "../Controller/getDist.php?ctr="+$(this).val(), success: function(result){
		        $("#txt_address_dist_p").empty().append(result);
				        $(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							         if($(element).val().length > 0) {
							           $(this).siblings('label, i').addClass('active');
							         }
							         else
							         {
									 	$(this).siblings('label, i').removeClass('active');
							 }
				        });
				        $('select').formSelect();
		    }});
		});
		$('.EmployeeDetail').on('click', function(){
			   
			    var tval = $(this).text();
			   
			    $.ajax({
				  url: <?php echo '"'.URL.'"';?>+"Controller/GetEmployee.php?empid="+tval
				}).done(function(data) { // data what is sent back by the php page
					$('#myDiv').html(data).removeClass('hidden');
					 $('.imgBtn_close').on('click', function(){				   
				       	var el = $(this).parent('div').parent('div');				       	
				       	el.addClass('hidden');	       
				    });
				   // display data
				});
		        	       
		    });
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Address Details</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >
	<?php include('shortcutLinkEmpProfile.php'); ?>
<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Address Details</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	<?php 
		if($EmployeeID==''&&empty($EmployeeID))
		{
			echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
			exit();
		}
	?>
		<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID;?>"/> 
			   <div class="input-field col s6 m6" >
			   		<div class="input-field col s12 m12">
			   			<h4>Current Address Details</h4>
			   		</div>
			   		
				   	 <div class="input-field col s12 m12">
					     <input type="text"  value="<?php echo($add);?>"  id="txt_address_add" name="txt_address_add" required/>
						 <label for="txt_address_add"> Address *</label>
				     </div>
				    
				     <div class="input-field col s12 m12">
				            <select id="txt_address_country" name="txt_address_country" required>				            
				            <option value="NA">---Select---</option>
							<?php 
								$sqlBy="select * from country";
								$myDB=new MysqliDb();
								$resultBy=$myDB->query($sqlBy);
								if($resultBy){													
									foreach($resultBy as $key=>$value){
										$selected='';
										if($country==$value['country'])
										{
											$selected="selected";
										}
										echo '<option value="'.$value['country'].'"  '.$selected.'>'.$value['country'].'</option>';
									}
								}		
				            ?>
				            </select>
				            <label for="txt_address_country" class="active-drop-down active">Country *</label>
				    </div>
				    
				     <div class="input-field col s12 m12">
		              <?php     
		                $myDB=new MysqliDb();
						$sql='call getState("'.$country.'")';
						$result=$myDB->query($sql);
						$mysql_error=$myDB->getLastError();
						?>
			            <select id="txt_address_state" name="txt_address_state" required>
				            <?php
							if(empty($mysql_error)){
								foreach($result as $key=>$value){ ?>
									<option <?php  if($state==$value['state']){ echo "selected"; }?> ><?php echo $value['state'];?></option>
								<?php }	
							}
							else
							{
								echo '<option value="NA" >---Select---</option>';
							}
				            ?>
			            </select>
			            <label for="txt_address_state" class="active-drop-down active">State *</label>
			         </div>  
				   
				     <div class="input-field col s12 m12">
			            <select id="txt_address_dist" name="txt_address_dist" required>
			            <?php
			             $sql='call getDist("'.$state.'")';
							$myDB=new MysqliDb();
							$result=$myDB->query($sql);
							$mysql_error=$myDB->getLastError();
							if(empty($mysql_error)){
								echo '<option value="NA" >---Select---</option>';
								foreach($result as $key=>$value){?>
										<option <?php if($dist==$value['district']){ echo "selected"; } ?>><?php echo $value['district']; ?></option>
									<?php
									}
								
							}
							else
							{
								echo '<option value="NA" >---Select---</option>';
								
							}	
						 ?>			  			            	
			            </select>
			            <label for="txt_address_dist" class="active-drop-down active">District *</label>
				    </div>
				    
				     <div class="input-field col s12 m12">
						<input type="text" value="<?php echo $city;?>" id="txt_address_city" name="txt_address_city" required/>
						<label for="txt_address_city">City *</label>
				    </div>
				    
				     <div class="input-field col s12 m12">
						<input type="text"  value="<?php echo $tehsil;?>"  id="txt_address_tehsil" name="txt_address_tehsil" required/>
						<label for="txt_address_tehsil">Tehsil *</label>
				     </div>
				    
					 <div class="input-field col s12 m12">
						<input type="text" id="txt_address_zip" name="txt_address_zip" value="<?php echo $zip?>" required/>
						<label for="txt_address_zip">Pin Code *</label>
					</div>
					 
				     <div class="input-field col s12 m12">
						<textarea id="txt_address_other" name="txt_address_other" class="materialize-textarea" required><?php echo $other; ?></textarea>
						<label for="txt_address_other">Landmark *</label>
				     </div>
				    
				    <div class="input-field col s12 m12">
				            <input type="checkbox" id="chkAll" name="chkAll" >
				            <label for="chkAll">Same as Current Address</label>
				     </div>
			   </div>
			   
			   
			   <div class="input-field col s6 m6" > 
			   
			   		<div class="input-field col s12 m12">
			   			<h4>Permanent Address Details</h4>
			   		</div>
			   		
			   		<div class="input-field col s12 m12">
			            <input type="text"  value="<?php echo($add_p);?>"  id="txt_address_add_p" name="txt_address_add_p" />
			            <label for="txt_address_add_p">Address</label>
			       </div>
			       
			     <div class="input-field col s12 m12">
		            <select id="txt_address_country_p" name="txt_address_country_p" >				            
		                <option value="NA">---Select---</option>
						<?php 
						$sqlBy="select * from country";
						$myDB=new MysqliDb();
						$resultBy=$myDB->query($sqlBy);
						if($resultBy){						
						$sl='';							
							foreach($resultBy as $key=>$value){
								$selected='';
								if($country_p==$value['country'])
								{
									$selected="selected";
								}
								$sl=$sl.$value['country'];
								echo '<option value="'.$value['country'].'"  '.$selected.'>'.$value['country'].'</option>';
							}
						}
			            ?>
			        </select>
		            <label for="txt_address_country_p" class="active-drop-down active">Country</label> 
			    </div>
			    
			    <div class="input-field col s12 m12">
			        <select id="txt_address_state_p" name="txt_address_state_p" >
		            	<option value="NA">---Select----</option>
		            </select>
		            <label for="txt_address_state_p" class="active-drop-down active">State</label>
			    </div>
			    
			    <div class="input-field col s12 m12">
					<select id="txt_address_dist_p" name="txt_address_dist_p" >
					  <option value="NA">---Select----</option>
					</select>
					<label for="txt_address_dist_p" class="active-drop-down active">District</label>
			    </div>
			    
			    <div class="input-field col s12 m12">
			          <input type="text" value="<?php echo $city_p;?>" id="txt_address_city_p" name="txt_address_city_p"/>
				      <label for="txt_address_city_p" class="active-drop-down active">City</label>
			    </div>
			    
			    <div class="input-field col s12 m12">
		            <input type="text" value="<?php echo $tehsil_p;?>"  id="txt_address_tehsil_p" name="txt_address_tehsil_p" />
		            <label for="txt_address_tehsil_p" class="active-drop-down active">Tehsil</label>
			    </div>
			    
			    <div class="input-field col s12 m12">
		            <input type="text" id="txt_address_zip_p" name="txt_address_zip_p" value="<?php echo $zip_p?>" />
		            <label for="txt_address_zip_p" class="active-drop-down active">Pin Code</label>
			    </div>
			    
			     <div class="input-field col s12 m12">
			           <textarea id="txt_address_other_p" name="txt_address_other_p" class="materialize-textarea"><?php echo $other_p; ?></textarea>
			           <label for="txt_address_other_p" class="active-drop-down active">Landmark</label>
			    </div>
			   
			   </div>
			   
			   
				<div class="input-field col s12 m12" align="right">
			  			<button type="submit" name="btn_address_Save" id="btn_address_Save" class="btn waves-effect waves-green">Save</button>		   
			    </div>
			    
					
					
				<style>
						.modelbackground
						{
							position: fixed;
							height: 100%;
							width: 100%;
							top:0px;
							left: 0px;
							background: rgba(0, 0, 0, 0.3);
							z-index: 1000;
						}
						.PopUp
						{
							position: absolute;
						    float: left;
						    width: 60%;
						    overflow: auto;
						    top: 25%;
						    background: rgba(255, 255, 255, 0.7);
						    left: 20%;
						    box-shadow: 0px 0px 6px 0px gray inset,0px 0px 10px 0px rgba(255, 255, 255, 0.95);
						    border: 1px solid #67A1AD;
						    border-radius: 10px;
						    padding: 10px;
						    text-shadow: 1px 1px 0px #FFF8F8, 1px 2px 0px rgba(0, 0, 0, 0.28);
						}
						.imgBtn_close
						{
							position: absolute;
						    top: 0;
						    right: 0;
						}
						#empinfo_tab td:nth-child(odd)	
						{
							border: 1px solid #A3CCA3;
							color: black;
							text-shadow: none;
							padding-left: 30px;
						}
						#empinfo_tab td:nth-child(even)	
						{
								border: 1px solid #A3CCA3;
								color: #033313;
								font-weight: bold;
								text-transform: uppercase;
								padding-left: 10px;
						}
				</style>
				
     <div class="hidden modelbackground" id="myDiv"></div>
         
<script>
$(document).ready(function(){
		$('#imgToempl').attr('src',$('#imgname').val());
		$('input[type="text"]').click(function(){
			$(this).closest('div').removeClass('has-error');	       
		});
		$('select').click(function(){
			$(this).closest('div').removeClass('has-error');	       
		});		

     // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	$('#btn_address_Save').on('click', function(){
	        var validate=0;
	        var alert_msg='';
	        // <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
	        $("input,select,textarea").each(function(){
	        	var spanID =  "span" + $(this).attr('id');		        	
	        	$(this).removeClass('has-error');
	        	if($(this).is('select'))
				{
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
	        	var attr_req = $(this).attr('required');
	        	if(($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown'))
	        	{
					validate=1;	
					$(this).addClass('has-error');
					if($(this).is('select'))
					{
						$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					}
					if ($('#'+spanID).size() == 0) {
				            $('<span id="'+spanID+'" class="help-block"></span>').insertAfter('#'+$(this).attr('id'));
				        }
				    var attr_error = $(this).attr('data-error-msg');
				    if(!(typeof attr_error !== typeof undefined && attr_error !== false))
				    {
						$('#'+spanID).html('Required *');	
					}
					else
					{
						$('#'+spanID).html($(this).attr("data-error-msg"));
					}
				}
	        })
	        		    
	      	if(validate==1)
	      	{		      		
	      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			} 
	});
	
 		function getit()
		{
				var country="<?php echo ($country=='')?'NA':$country;?>";
				var state="<?php echo ($state=='')?'NA':$state;?>";
				var dist="<?php echo ($dist=='')?'NA':$dist;?>";
				var country_p="<?php echo ($country_p=='')?'NA':$country_p;?>";
				var state_p="<?php echo ($state_p=='')?'NA':$state_p;?>";
				var dist_p="<?php echo ($dist_p=='')?'NA':$dist_p;?>";
				if(country=='NA'||country=='---Select---')
				{
					$('#txt_address_country').empty().append('<option  value="NA" selected>---select----</option><option >India</option>');
				}
				else
				{
					$('#txt_address_country').empty().append('<option  value="NA">---select----</option><option selected>'+country+'</option>');
				}
				
				if(country_p=='NA'||country_p=='---Select---')
				{
					$('#txt_address_country_p').empty().append('<option  value="NA" selected>---select----</option><option >India</option>');
				}
				else
				{
					$('#txt_address_country_p').empty().append('<option value="NA">---select----</option><option selected>'+country_p+'</option>');
				}
				$('#txt_address_state_p').empty().append('<option>'+state_p+'</option>');
				$('#txt_address_dist_p').empty().append('<option>'+dist_p+'</option>');
				
			//	$('#txt_address_state').empty().append('<option>'+state+'</option>');
				//$('#txt_address_dist').empty().append('<option>'+dist+'</option>');
				
		}
		getit();
	
	$('#chkAll').click(function() {
	    if ($(this).is(':checked')) {
			$('#txt_address_country_p').val($('#txt_address_country').val());
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
				$('select').formSelect();
			$('#txt_address_state_p').empty().append('<option>'+$('#txt_address_state').val()+'</option>');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
				$('select').formSelect();
			$('#txt_address_dist_p').empty().append('<option>'+$('#txt_address_dist').val()+'</option>');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
				$('select').formSelect();
			$('#txt_address_add_p').val($('#txt_address_add').val());
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
			$('#txt_address_city_p').val($('#txt_address_city').val());
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
					 if($(element).val().length > 0) {
					   $(this).siblings('label, i').addClass('active');
					 }
					 else
					 {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
			$('#txt_address_tehsil_p').val($('#txt_address_tehsil').val());
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
			$('#txt_address_other_p').val($('#txt_address_other').val());
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
			$('#txt_address_zip_p').val($('#txt_address_zip').val());
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
	    }
	    else
	    {
			$('#txt_address_add_p').val('');
			$('#txt_address_country_p').val('NA');
			    $(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
				$('select').formSelect();
			$('#txt_address_state_p').val('NA');
			    $(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
				$('select').formSelect();
			$('#txt_address_dist_p').val('NA');
			    $(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
				});
				$('select').formSelect();
			$('#txt_address_city_p').val('');
			$('#txt_address_tehsil_p').val('');
			$('#txt_address_other_p').val('');
			$('#txt_address_zip_p').val('');
			$(document).unbind('ajaxComplete');
		}
	});
});
</script>
</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div> 
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>