<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

if(isset($_SESSION))
{
	
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}

$classvarr="'.byID'";
// Global variable used in Page Cycle
$searchBy=$question_num=$id=$mysql_error2='';
$email=array();$ccemail=array();
if(isset($_GET['id']) && $_GET['id']!="")
{
	$id=$_GET['id'];
}
if(isset($_GET['delid']) && $_GET['delid']!="" && isset($_GET['locid']) && $_GET['locid']!="")
{
	$myDB=new MysqliDb();
	$delete_query="DELETE from manage_module_email  where moduleID='".$_GET['delid']."' and location='".$_GET['locid']."'";
	$resultBy=$myDB->rawQuery($delete_query);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		echo "<script>$(function(){ toastr.error('Assinged Email Deleted Successfully'); }); </script>";
	}
}

if(isset($_POST['addbriefing']))
{	$date=date('Y-m-d');
	$createdBy=$_SESSION['__user_logid'];
	$email_address='';
	if(isset($_POST['email_address']))
	{
		$email_address=$_POST['email_address'];
	}
	$cc_email='';
	if(isset($_POST['cc_email']))
	{
		$cc_email=$_POST['cc_email'];
	}
	$moduleID=$_POST['module_id'];
	$location=$_POST['location_id'];
	
	$emailID="";
	$toemail=count($email_address);
	$ccemail=count($cc_email);
	$total_email_count="";
	 $cc="";
	if($toemail>0 || $ccemail>0)
	{		$total_email_count=0;
			if($toemail>=$ccemail){
				$total_email_count=$toemail;
			}else{
				$total_email_count=$ccemail;
			}
			$myDB=new MysqliDb();
			$select_query="select id from manage_module_email where  moduleID='".$moduleID."' and location='".$location."' ";
			$resultBy=$myDB->rawQuery($select_query);
			$mysql_error = $myDB->getLastError();
			if($myDB->count > 0){
				echo "<script>$(function(){ toastr.info('Module allready assigned'); }); </script>";
			}
			else
			{
				for($i=0;$i<$total_email_count; $i++)
				{
					if(isset($email_address[$i]))
					{
						 $emailId=$email_address[$i];
					}
					else
					{
						$emailId="";
					}
					if(isset($cc_email[$i]))
					{
						 $cc=$cc_email[$i];
					}
					else
					{
						 $cc="";
					}
					if($emailId!="" || $cc!="")
					{
						if(in_array($cc,$email_address))
						{
							$cc="";
						}
						$insertQuery="INSERT INTO manage_module_email  set emailID='".$emailId."', moduleID='".$moduleID."', cc_email='".$cc."', location='".$location."' ";
						$resultBy=$myDB->rawQuery($insertQuery);
						$mysql_error2 = $myDB->getLastError();
										
					} 
				}
				if(empty($mysql_error2))
				{
					echo "<script>$(function(){ toastr.success('Data added Successfully'); }); </script>";
				}
			}
						
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Please assign email address'); }); </script>";
	}	
}


if(isset($_POST['savebriefing']) && $_POST['id']!="")
{  
	
	$moduleID=$_POST['module_id'];
	$emailID="";
	$email_address='';
	if(isset($_POST['email_address']))
	{
		$email_address=$_POST['email_address'];
	}
	$cc_email='';
	if(isset($_POST['cc_email']))
	{
		$cc_email=$_POST['cc_email'];
	}
	$toemail=count($email_address);
	$ccemail=count($cc_email);
	$location=$_POST['location_id'];
	$total_email_count=0;
	if($toemail>=$ccemail)
	{
		$total_email_count=$toemail;
	}
	else
	{
		$total_email_count=$ccemail;
	}
	if($toemail>0 || $ccemail>0)
	{		$myDB=new MysqliDb();
			$delete_query="delete from manage_module_email where  moduleID='".$moduleID."' and location='".$location."' ";
			$resultBy=$myDB->rawQuery($delete_query);
			$mysql_error = $myDB->getLastError();
			if(empty($mysql_error))
			{
				for($i=0;$i<$total_email_count; $i++)
				{
					if(isset($email_address[$i])){
							$emailId=$email_address[$i];
					}else{
						$emailId="";
					}
					if(isset($cc_email[$i])){
						 $cc=$cc_email[$i];
					}else{
						 $cc="";
					}
					if($emailId!="" || $cc!=""){
						if(in_array($cc,$email_address)){
							$cc="";
						}
						 $insertQuery="INSERT INTO manage_module_email  set emailID='".$emailId."', moduleID='".$moduleID."', cc_email='".$cc."', location='".$location."' ";
						 $resultBy=$myDB->query($insertQuery);
						 $mysql_error = $myDB->getLastError();
						
					} 
				}
				 if(empty($mysql_error)){
					echo "<script>$(function(){ toastr.success('Data Updated Successfully'); }); </script>";
				 }
			}
	}		
}
?>

<script>
	$(document).ready(function(){
		$('#from_date').datetimepicker({ format:'Y-m-d H:i',step:30 });
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        "iDisplayLength": 25,	
				        scrollX: '100%',				        
				        scrollCollapse: true,
				        lengthMenu: [
				            [ 5,10, 25, 50, -1 ],
				            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
				         buttons: [		         
						        'pageLength'
						    ]
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				    });
		   	
		   
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	$('.byID').addClass('hidden');
		   	$('.byDate').addClass('hidden');
		   	$('.byDept').addClass('hidden');
		   	$('.byProc').addClass('hidden');
		   	$('.byName').addClass('hidden');
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   
	});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Manage Module Email</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Manage Module Email</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	
			 <?php   
			 $ccemail=array();	
			 $email=array();
			 $emailId3="";
			 $emailId="";
			 	if(isset($_GET['id']) and $_GET['id']!="")
			    {
					$Id=$_GET['id'];
					$sqlConnect2 = 'select  emailID,cc_email from  manage_module_email  where moduleID="'.$Id.'" '; 
					
					$myDB=new MysqliDb();
					$result2=$myDB->query($sqlConnect2);
					foreach($result2 as $key=>$value)
					{
						if($value['emailID']!="")
						{
							$email[]=$value['emailID'];
						}
						if($value['cc_email']!="")
						{
							$ccemail[]=$value['cc_email'];
						}	
					}
				}	
			?>
			 	<div class="form-inline" id="ajaxid">  
						<div class="input-field col s12 m12">
							<select name="module_id" id="module_id">
								<option  value="NA" >Select Module</option>	
								<?php 
								$sqlBy ="select module_name,ID from module_manager where status='1'"; 	
								$myDB=new MysqliDb();
								$resultBy=$myDB->query($sqlBy);
								if($myDB->count > 0)
								{
									$selected='';													
									foreach($resultBy as $key=>$value)
									{
										if($Id==$value['ID'])
										{
											$selected='selected';
										}
										else
										{
											$selected='';
										}
										echo '<option  value="'.$value['ID'].'" '.$selected.'>'.$value['module_name'].'</option>';
									    }
								}
								?>	
							</select> 
							<label for="module_id" class="active-drop-down active">Select Module</label> 
							</div> 
							
							<div class="input-field col s12 m12">
							<select name="location_id" id="location_id" required>
								<option  value="NA" >Select Location</option>	
								<?php 
								$sqlBy ="select id,location from location_master;"; 	
								$myDB=new MysqliDb();
								$resultBy=$myDB->query($sqlBy);
								if($myDB->count > 0)
								{
									//$selected='';													
									foreach($resultBy as $key=>$value)
									{
										/*if($Id==$value['ID'])
										{
											$selected='selected';
										}
										else
										{
											$selected='';
										}*/
										echo '<option  value="'.$value['id'].'">'.$value['location'].'</option>';
									}
								}
								?>	
							</select> 
							<label for="location_id" class="active-drop-down active">Select Location</label> 
							</div> 
							
							
							<h4>To Email Address</h4>
							<div class="input-field col s12 m12">
				     			 <?php
					     			$sqlBy2 ='select email_address,ID from add_email_address  '; 	
									$myDB=new MysqliDb();
									$resultBy2=$myDB->query($sqlBy2);
									if($myDB->count > 0){									
										foreach($resultBy2 as $key=>$value2){ 
										$emailId=$value2['ID'];
										//echo '&nbsp;&nbsp;&nbsp;'.$value2['email_address'];
										?><div class="col s4 m4 l4">
									        <input type='checkbox'  name="email_address[]" id="<?php echo  $value2['ID'];?>" value="<?php echo $value2['ID']; ?>"  <?php  if(in_array($emailId,$email)){ echo 'checked'; }   ?>>
									        <label for="<?php echo  $value2['ID'];?>"><?php echo $value2['email_address']; ?></label>
									      </div>
									<?php	} }  ?>
					         </div> 
					   
					   		<h4>CC Email</h4>
							<div class="input-field col s12 m12">
							
								 <?php 
									//$myDB=new MysqliDb();
									$resultBy3=$resultBy2;//$myDB->query($sqlBy2);
									if($myDB->count > 0){									
										foreach($resultBy3 as $key=>$value3){ 
										$emailId3=$value3['ID'];
										//echo '&nbsp;&nbsp;&nbsp;'.$value3['email_address'];
										?>
										<div class="col s4 m4 l4">
									        <input type='checkbox'  name="cc_email[]" id="cc<?php echo  $value3['ID'];?>" value="<?php echo $value3['ID']; ?>"  <?php  if(in_array($emailId3,$ccemail)){ echo 'checked'; }   ?>>
									        <label for="cc<?php echo  $value3['ID'];?>"><?php echo $value3['email_address']; ?></label>
									      </div>
										
									<?php	
									} } ?> 
							</div>
				    </div>
			   
			<input type='hidden' name='id' id="mid" value='<?php echo $id; ?>'>
		    <div class="input-field col s12 m12 right-align">
		     	<button type="submit" name="savebriefing"  id="savebriefing" class="btn waves-effect waves-green" style="display:none;" >Save</button>
		    	<a href="<?php echo URL;?>View/manage_email_module.php" class="btn waves-effect modal-action modal-close waves-red close-btn" style="display:none;" id='cancelID'>Cancel</a>
		    	<button type="submit" name="addbriefing"  id="addbriefing" class="btn waves-effect waves-green">Submit</button>
		    </div>
		
			  	 
			  	  <?php 
					//$sqlConnect = "select a.*,b.module_name,b.modulename from manage_module_email a INNER JOIN module_manager b  ON  a.moduleID=b.ID  where b.status='1'"; 
					$sqlConnect = "select a.*,b.module_name,b.modulename,c.location as 'loc' from manage_module_email a INNER JOIN module_manager b  ON  a.moduleID=b.ID left outer join location_master c on a.location=c.id  where b.status='1' group By a.moduleID,a.location"; 
					$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					//print_r($result);
					$error=$myDB->getLastError();;
					if($result){?>
			   			<div id="pnlTable">
			   			  <div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						  <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						        	<th> Srl.No.</th>
									<th> Module Name</th>
									<th> Page Title</th>
									<th>Location</th>
						            <th> Edit </th>
						           <th> Delete </th>		 
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $i=1;
					        foreach($result as $key=>$val){
					        	$module_name=$val['module_name'];
					        	$modulename=$val['modulename'];
					        	$ID=$val['moduleID'];
					        	$loc=$val['loc'];
					        	$loc1=$val['location'];
								echo '<tr style="vertical-align:top;">';	
								echo '<td class="client_name" style="vertical-align:top;" >'.$i.'</td>';						
								echo '<td class="email" style="vertical-align:top;" >'.$module_name.'</td>';
								echo '<td class="email" style="vertical-align:top;" >'.$modulename.'</td>';
								echo '<td class="email" style="vertical-align:top;" >'.$loc.'</td>';
								?>
								
								<td class="manage_item">
								<a onclick="editIT('<?php echo $ID;?>','<?php echo $loc1;?>')" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>
								
								<td class="manage_item">
								<a onclick="return confirm('Do you want to detete it?');" href="manage_email_module.php?delid=<?php echo $ID; ?>&locid=<?php echo $loc1; ?>" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Delete">ohrm_delete</i></a></td>
								
						<?php
							echo '</tr>';
							$i++;
							}	
							?>			       
					    </tbody>
						</table>
						  </div>
						</div>
						<?php
							 }
						else
						{
							echo "<script>$(function(){ toastr.info('Data Not Found".$error."'); }); </script>";
						}
						
					?>
					
				</div>
		</div>
	</div>     
</div>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>

<script>
	$(document).ready(function(){
	$('#addbriefing, #savebriefing').click(function(){
		//alert('aaa');
			var validate=0;
		        var alert_msg='';
		        $('#location_id').removeClass('has-error');	
		        if($('#module_id').val()=='NA')
		        {
					$('#module_id').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
					validate=1;
					if($('#squeryto1').size() == 0)
					{
					   $('<span id="squeryto1" class="help-block">Module can not be empty.</span>').insertAfter('#module_id');
					}
					
				}
				if($('#location_id').val()=='NA')
		        {
		        	//alert('NA');
					$('#location_id').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
					validate=1;
					if($('#squeryto').size() == 0)
					{
					   $('<span id="squeryto" class="help-block">Location can not be empty.</span>').insertAfter('#location_id');
					}
					
				} 
		      	if(validate==1)
		      	{		      		
		      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
		      		$('#alert_message').show().attr("class","SlideInRight animated");
		      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;
				}
		});
		$('#div_error').removeClass('hidden');

		
	});
	function editIT(moduleID,locid){
		//alert(locid);
		$('#mid').val(moduleID);
		$('#savebriefing').show();
		$('#addbriefing').hide();
		$('#cancelID').show();
		 $("#module_id").prop('disabled', 'disabled');
		 $("#module_id").val();
		if(moduleID!=""){
			$.ajax({url: <?php echo '"'.URL.'"';?>+"Controller/getAssignedEmail.php?mid="+moduleID+"&locid="+locid }).done(function(data) {
			  		$('#ajaxid').html(data);
			  		$('select').formSelect(); 
			});
		}
	}
</script>