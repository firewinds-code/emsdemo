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
$primaryLanguage='';
$secondaryLanguage='';
$doc_file='';
$adharNum='';
$file="";
$refID=$reftxt=$First=$Middle=$Last=$DOB=$Father=$Mother=$Gender=$Blood=$Marriage_Status=$Marriage_Spouse=$Marriage_Date=$Marriage_CStatus=$reftxt='';
$rf_id=0;
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
	$First=(isset($_POST['txt_Personal_First'])? $_POST['txt_Personal_First'] : null);
	$Middle=(isset($_POST['txt_Personal_Middle'])? $_POST['txt_Personal_Middle'] : null);
	$Last=(isset($_POST['txt_Personal_Last'])? $_POST['txt_Personal_Last'] : null);
	$DOB=(isset($_POST['txt_Personal_DOB'])? $_POST['txt_Personal_DOB'] : null);
	$Father=(isset($_POST['txt_Personal_Father'])? $_POST['txt_Personal_Father'] : null);
	$Mother=(isset($_POST['txt_Personal_Mother'])? $_POST['txt_Personal_Mother'] : null);
	$Gender=(isset($_POST['txt_Personal_Gender'])? $_POST['txt_Personal_Gender'] : null);
	if(isset($_POST['primaryLanguage']) && trim($_POST['primaryLanguage'])!=""){
		$primaryLanguage=trim($_POST['primaryLanguage']);
	}else{
		$primaryLanguage="";
	}
	if(isset($_POST['secondaryLanguage'])){
		
		$secondaryLanguage= implode(',',$_POST['secondaryLanguage']);
	}else{
		$secondaryLanguage="";
	}
	$Blood=(isset($_POST['txt_Personal_Blood'])? $_POST['txt_Personal_Blood'] : null);
	$Marriage_Status=(isset($_POST['txt_Personal_Marriage_Status'])? $_POST['txt_Personal_Marriage_Status'] : null);
	$Marriage_Spouse=(isset($_POST['txt_Personal_Marriage_Spouse'])? $_POST['txt_Personal_Marriage_Spouse'] : null);
	$Marriage_Date=(isset($_POST['txt_Personal_Blood'])? $_POST['txt_Personal_Marriage_Date'] : null);
	$Marriage_CStatus=(isset($_POST['txt_Personal_Marriage_CStatus'])? $_POST['txt_Personal_Marriage_CStatus'] : null);
	//$refID=(isset($_POST['txt_Personal_Ref_Type'])? $_POST['txt_Personal_Ref_Type'] : null);
	$refID='';
	$reftxt='';
	$rf_id='';
	$uploadOk = 1;
	$sourcePath = $_FILES['txt_dadhar_card']['tmp_name'];
	$targetPath = ROOT_PATH."Docs/IdentityProof/".basename($_FILES['txt_dadhar_card']['name']);
	$FileType = strtolower(pathinfo($targetPath,PATHINFO_EXTENSION));
	// Check file size
	if ($_FILES["txt_dadhar_card"]["size"] > 400000) {
	    echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if ($FileType != "jpg" && $FileType != "jpeg"  && $FileType != "png") {
	    if($_FILES["txt_dadhar_card"]["size"] != 0)
	    {
	    	echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg and png files are allowed.'); }); </script>";
		}
		
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	$adharNum=$_POST['txt_adhar_card_number'];
	if ($uploadOk == 1 || ($_FILES["txt_dadhar_card"]["size"] != 0 && !empty($_FILES["txt_dadhar_card"]["name"]) && $adharNum!="")) {
	$EmployeeID=$_POST['EmployeeID'];
		if(is_uploaded_file($_FILES['txt_dadhar_card']['tmp_name'])) {
	
			if(move_uploaded_file($sourcePath,$targetPath)) 
			{
	 			$ext = pathinfo(basename($_FILES['txt_dadhar_card']['name']), PATHINFO_EXTENSION);
				$filename=$EmployeeID.'IdentityProof_'.date("mdYhis").'.'.$ext;
				$files=rename($targetPath,ROOT_PATH.'Docs/IdentityProof/'.$filename);
				if(file_exists(ROOT_PATH.'Docs/IdentityProof/'.$filename))
				{
					$file=$filename;
					
					$myDB=new MysqliDb();
					$selectQuery=$myDB->rawQuery("select dov_value,doc_file from doc_details where doc_stype='Aadhar Card' and EmployeeID='".$EmployeeID."'");
					if(count($selectQuery)>0){
						$myDB=new MysqliDb();
						$updateQuery=$myDB->rawQuery("Update doc_details set doc_type='Proof of Identity', dov_value='".$adharNum."',doc_file='".$file."' where EmployeeID='".$EmployeeID."' ");
					}else{
							$InsertQuesry="INSERT INTO doc_details set  doc_type='Proof of Identity',doc_stype='Aadhar Card',dov_value='".$adharNum."' ,EmployeeID='".$EmployeeID."',createdon=NOW(),doc_file='".$file."'";
				$myDB->rawQuery($InsertQuesry);
					}
				}
				
			}
			
		}
	}
	 
}
//Check Employee is exist or not
if(isset($_REQUEST['empid']) && $EmployeeID=='' && !isset($_POST['txt_Personal_First']))
{
	$myDB=new MysqliDb();	
	$EmployeeID=strtoupper($_REQUEST['empid']);
	$sql='call get_personal("'.$EmployeeID.'")';
	$result=$myDB->rawQuery($sql);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		foreach($result as $key=>$value)
		{
			$First=$value['FirstName'];
			$Middle=$value['MiddleName'];
			$Last=$value['LastName'];
			$DOB=$value['DOB'];
			$Father=$value['FatherName'];
			$Mother=$value['MotherName'];
			$Gender=$value['Gender'];
			$primaryLanguage=$value['primary_language'];
			$secondaryLanguage=$value['secondary_language'];
			$Blood=$value['BloodGroup'];
			$Marriage_Status=$value['MarriageStatus'];
			$Marriage_Spouse=$value['Spouse'];
			$Marriage_Date=$value['MarriageDate'];
			$Marriage_CStatus=$value['ChildStatus'];
			if($value['img']!='')
			{
				$imsrc=URL.'Images/'.$value['img'];
				$filename=$value['img'];
		  }
		$reftxt=$value['ref_txt'];
		$rf_id=$value['ref_id'];
	
		$btnShow=' hidden';
	   }
	}
	else
	{
		echo "<script type='text/javascript'> alert('Wrong Employee To Search ....');
		window.location='".URL."'</script>";
		
	}
	
}
elseif(isset($_POST['EmployeeID']) && $_POST['EmployeeID']!='')
{
	$EmployeeID=$_POST['EmployeeID'];
}
$myDB=new MysqliDb();
//echo "select dov_value,doc_file from doc_details where doc_stype='Aadhar Card' and EmployeeID='".$EmployeeID."'";
	$selectQuery=$myDB->rawQuery("select dov_value,doc_file from doc_details where doc_stype='Aadhar Card' and EmployeeID='".$EmployeeID."'");
	if(count($selectQuery)>0){
		$doc_file=$selectQuery[0]['doc_file'];
		$adharNum=$selectQuery[0]['dov_value'];
	}
if(isset($_POST['btn_Personal_Save']) && $EmployeeID!='')
{
	$myDB=new MysqliDb();	
	if($EmployeeID)
	{
		if(isset($_FILES["FileUpload1"])&&!empty($_FILES["FileUpload1"]["name"]))																{
			$target_dir = ROOT_PATH.'Images/';
			$target_file = $target_dir . basename($_FILES["FileUpload1"]["name"]);
			$uploadOk = 1;			
			$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check file size
			if ($_FILES['FileUpload1']['size'] > 200000) {
			    echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if ($FileType != "jpg" && $FileType != "jpeg" && $FileType != "png") {
			    echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg and png files are allowed.'); }); </script>";
			    $uploadOk = 0;
			}
			if($uploadOk == 1)
			{
				
			
			if (move_uploaded_file($_FILES["FileUpload1"]["tmp_name"], $target_file)) 																{
					$ext = pathinfo( basename($_FILES["FileUpload1"]["name"]), PATHINFO_EXTENSION);
					$filename=$EmployeeID.'.'.$ext;
			     	$file=rename($target_file,$target_dir.''.$filename);
			     	if(file_exists(ROOT_PATH.'Images/'.$filename))
			     	{
						$alert_msg="The file ".$filename. " has been uploaded.<br/>";
			       		$imsrc=URL.'Images/'.$filename;
			       		
					}
					else
					{
						$alert_msg="Sorry, there was an error uploading your file.<br/>";
						$filename=$_POST['imgfile'];
						$imsrc=URL.'Images/'.$filename;
					}
		        
			}
			else 
			{
			        $alert_msg="Sorry, there was an error uploading your file.<br/>";
			        $filename=$_POST['imgfile'];
			        $imsrc=URL.'Images/'.$filename;
			}
			}
			else
			{
					$alert_msg="Sorry, there was an error uploading your file.<br/>";
			        $filename=$_POST['imgfile'];
			        $imsrc=URL.'Images/'.$filename;
			}
		}
		else
		{
			 $filename=$_POST['imgfile'];
			 $imsrc=URL.'Images/'.$filename;
		}
		$Middle=trim($Middle);
		$First=trim($First);
		$Last=trim($Last);
		$First = str_replace(' ','',$First);
		$empname=$First.' '.$Middle.' '.$Last;
		if($Middle==''||empty($Middle)&&$Last!='')
		{
			$empname=$First.' '.$Last;
		}
		else if($Last==''||empty($Last)&&$Middle!='')
		{
			$empname=$First.' '.$Middle;
		}
		else if(($Last==''||empty($Last))&&($Middle==''||empty($Middle)))
		{
			$empname=$First;
		}	
		$modifyby=$_SESSION['__user_logid'];
	$rf_id=0;

	if($primaryLanguage!=""  && $adharNum!=""){

		$Update='call save_personal("'.$EmployeeID.'","'.$empname.'","'. $First.'","'. $Middle.'","'. $Last.'","'. $DOB.'","'. $Father.'","'. $Mother.'","'. $Gender.'","'. $Blood.'","'. $Marriage_Status.'","'. $Marriage_Spouse.'","'. $Marriage_Date.'","'. $Marriage_CStatus.'","'.$rf_id.'","'.$reftxt.'","'.$modifyby.'","'.$filename.'","'.$primaryLanguage.'","'.$secondaryLanguage.'")';
		$myDB=new MysqliDb();
		
		$result = $myDB->query($Update);
		$mysql_error=$myDB->getLastError();
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Personel Details Saved Successfully'); }); </script>";
			$btnShow=' hidden ';
			$myDB =new MysqliDb();
			if($Marriage_CStatus=='Yes')
			{
				$count=$_POST['count_child'];
				for($i=1;$i<=$count;$i++)
				{
					    $child_name=$_POST['txt_child_name_'.$i];
						$child_dob=$_POST['txt_child_dob_'.$i];
						$child_blood=$_POST['txt_child_blood_'.$i];
						$child_gender=$_POST['txt_child_gen_'.$i];
					if($child_name!=''&&$child_dob!='')
					{
						$child_insert='call add_child("'.$EmployeeID.'","'.$child_name.'","'.$child_dob.'","'.$child_gender.'","'.$child_blood.'")';		
						$cresult=$myDB->query($child_insert);
						$mysql_error=$myDB->getLastError();
						if($cresult)
						{
							echo "<script>$(function(){ toastr.success('Child added ".$i."'); }); </script>";
						}
						else
						{
							echo "<script>$(function(){ toastr.error('Child Not added ".$mysql_error."'); }); </script>";
						}
					}
				}
			}
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Personel Details not Saved ".$mysql_error."'); }); </script>";
			$btnShow='';
		}
	  }
	  else
	  {
	  	echo "<script>$(function(){ toastr.error('Primary Language should not be empty'); }); </script>";
			$btnShow='';
	  }
	}
}
?>

<script>
	$(document).ready(function(){
		
		var usrtype=<?php echo "'".$_SESSION["__user_type"]."'"; ?>;
		var usrid=<?php echo "'".$_SESSION["__user_logid"]."'"; ?>;
		
		if(usrtype === 'ADMINISTRATOR'||usrtype === 'HR'|| usrid=='CE12102224' || usrid=='CE121622565')
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
		var date_now = new Date();
		var get_Year  = date_now.getFullYear();
		
		var get_Year = get_Year - 18;
		var months = date_now.getMonth();
		if(months < 12)
		{
			months =months+1;
		}
		else
		{
			months = 1;
		}
		var days = date_now.getDate();
		if(days < 10)
		{
			days = '0'+days;
		}
		if(months < 10)
		{
			months = '0'+months;
		}
		var minDates =get_Year+'/'+months+'/'+days;
		
		var defaultDatez = get_Year+'-'+months+'-'+days;
		$('#txt_Personal_DOB').datetimepicker({ format:'Y-m-d', timepicker:false,maxDate:minDates,yearEnd:get_Year,defaultDate:defaultDatez, scrollInput : false});
		$('#txt_Personal_Marriage_Date').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_Personal_DOJ').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_child_dob_1').datetimepicker({ format:'Y-m-d', timepicker:false});
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
<Style>
	.emp_image
	{
		max-width: 230px;
		max-height: 230px;
	}
</Style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Profile Details</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >
	<?php include('shortcutLinkEmpProfile.php'); ?>
<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Profile Details</h4>				

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
		
		<div class="col s6 m6 no-padding">
					
					  <div class="input-field col s12 m12 show-image" align="left">
					      <input type="hidden" name="imgname" id="imgname"  value="<?php echo $imsrc; ?>"/>
					      <input type="hidden" name="imgfile" id="imgfile"  value="<?php echo $filename; ?>"/>
					    	<img  id="imgToempl" name="imgToempl" class="emp_image" />
					    	<span  class="update large material-icons" id="editimage" >add_a_photo</<span>	 	
					      
				 	  </div>
				 	  <div class="file-field input-field col s12 m12" style="display:none;margin: 0px;" id='uploadimage'>					      
						      <div class="btn">
						        <span>Photograph</span>
						        <input type="file" id="FileUpload1" name="FileUpload1">
						        <br>
								<span class="file-size-text help-block">Accepts up to 200 KB Image File only.</span>
						      </div>
						      <div class="file-path-wrapper">
						        <input class="file-path validate" type="text">
						      </div>
					  </div>
					<style>
					#editimage {
    					font-size: 40px;
    				}
					div.show-image {
					    position: relative;
					    float:left;
					    margin:5px;
					}
					.update{
					    opacity: 0.7;
						height: 30px;
						position: absolute;
						z-index: 10;
						cursor: pointer;
						color: gray;
						border-radius: 3px;
						top: -19px;

					}
					.update:hover{
					    color: #1dadc4!important;

					}
					</style>
				
				</div>
			    
			    <div class="input-field col s6 m6">
		            <input type="text" value="<?php echo($First);?>" id="txt_Personal_First" name="txt_Personal_First" required />
			        <label for="txt_Personal_First">First Name *</label>
			    </div>
			   
			    <div class="input-field col s6 m6">
			        <input type="text" value="<?php echo($Middle);?>" id="txt_Personal_Middle"  name="txt_Personal_Middle" />
				    <label for="txt_Personal_Middle">Middle Name</label>
			    </div>
			    
			    <div class="input-field col s6 m6">
		     		<input type="text" value="<?php echo($Last);?>" id="txt_Personal_Last" name="txt_Personal_Last" />
			        <label for="txt_Personal_Last">Last Name </label>
			    </div>
			    
			    <div class="input-field col s6 m6">
		            <input type="text" readonly="true" value="<?php echo($DOB);?>" id="txt_Personal_DOB" name="txt_Personal_DOB" required/>
		            <label for="txt_Personal_DOB">DOB *</label>
			    </div>
			    
			    <div class="input-field col s6 m6">
					<input type="text" value="<?php echo($Father);?>" id="txt_Personal_Father" name="txt_Personal_Father" required/>
					<label for="txt_Personal_Father">Father's Name *</label>
			    </div>
			   
			    <div class="input-field col s6 m6">
					<input type="text" value="<?php echo($Mother);?>" id="txt_Personal_Mother" name="txt_Personal_Mother" required/>
					<label for="txt_Personal_Mother">Mother's Name *</label>
			    </div>
			    
			    <div class="input-field col s6 m6">
					    <select  id="txt_Personal_Blood" name="txt_Personal_Blood" required>
					        <option value="NA">---Select---</option>
					        <option <?php if($Blood=='A+')echo 'selected'; ?>>A+</option>
							<option <?php if($Blood=='A-')echo 'selected'; ?>>A-</option>
							<option <?php if($Blood=='B+')echo 'selected'; ?>>B+</option>
							<option <?php if($Blood=='B-')echo 'selected'; ?>>B-</option>
							<option <?php if($Blood=='AB+')echo 'selected'; ?>>AB+</option>
							<option <?php if($Blood=='AB-')echo 'selected'; ?>>AB-</option>
							<option <?php if($Blood=='O+')echo 'selected'; ?>>O+</option>
							<option <?php if($Blood=='O-')echo 'selected'; ?>>O-</option>
					    </select>
					    <label for="txt_Personal_Blood" class="active-drop-down active">Blood Group *</label>
			    </div>
			    
			    <div class="input-field col s6 m6">
					<select  id="txt_Personal_Gender" name="txt_Personal_Gender" >
						<option <?php echo ($Gender=='Male')?'selected':'';?>>Male</option>
						<option <?php echo ($Gender=='Female')?'selected':'';?>>Female</option>
					</select>
					<label for="txt_Personal_Gender" class="active-drop-down active">Gender</label>
			    </div>
			  
			    <div class="input-field col s6 m6">
			      <select  id="primaryLanguage" name="primaryLanguage" required>
						<option value="">---Select---</option>
						<?php 
				      	$sqlQuery ='SELECT * FROM language_master '; 
						$myDB=new MysqliDb();
						$resultQuery=$myDB->query($sqlQuery);
						if($resultQuery){													
							foreach($resultQuery as $key=>$value){
								if($primaryLanguage==$value['Language'])
								{
									$selected='Selected';
								}
								else
								{
									$selected='';
								}														
								echo '<option value="'.$value['Language'].'" '.$selected.' >'.$value['Language'].'</option>';
							}
						}
						?>
					</select>
					<label for="primaryLanguage" class="active-drop-down active">Primary Language *</label>
			    </div>
			    
			    	<div class="col s12 m12 no-padding">
					<div class="input-field col s12 m12 no-padding" style="margin: 09px;margin-bottom: 20px;"><p ><b>Secondary Language</b></p></div>
	     			 <?php
	     			   $i=1;
		     			$newarray=explode(',',$secondaryLanguage);
					    if($resultQuery){													
						foreach($resultQuery as $key=>$value){
							//echo '&nbsp;&nbsp;&nbsp;'.$value2['email_address'];
							?><div class="col s2 m2">
						        <input type='checkbox'  name="secondaryLanguage[]" id="secondaryLanguage<?php echo $i; ?>" value='<?php echo $value['Language']; ?>'  <?php  if(in_array($value['Language'],$newarray)){ echo "checked='checked'";} ?>>
						        <label for="secondaryLanguage<?php echo $i; ?>"><?php echo $value['Language']; ?></label>
						      </div>
						<?php	$i++;} }  ?>
		         </div> 
			<div class="input-field col s12 m12 ">
				<span id='ssecondaryLanguage' class="help-block"></span>
			</div>
			
			    <div class="input-field col s6 m6" >
					<input type="text" value="<?php echo $adharNum; ?>" id="txt_adhar_card_number" name="txt_adhar_card_number" maxlength="12" />
					<label for="txt_adhar_card_number">Adhar card number</label>
			    </div>
				<div class="input-field col s6 m6" >
					<div class="file-field input-field col s6 m6">					      
					      <div class="btn">
					        <span>Adhar Card</span>
					        <input  type="file" value="" id="txt_dadhar_card" name="txt_dadhar_card" >
					        <br>
							<span class="file-size-text help-block" id="fileid" >Accepts up to 400KB jpg / png file.</span>
							
					      </div>
					     
					      <div class="file-path-wrapper">
					        <input class="file-path validate" type="text">
					      </div>
					       <br>
					      <span id='docalert' class="help-block"></span>
					      <input type='hidden'  name='hiddenAdhar' id='hiddenAdhar' value='<?php echo $doc_file; ?>' />
			  		</div>
					
			    </div>
			  
		        <div class="input-field col s6 m6">
					<select id="txt_Personal_Marriage_Status" name="txt_Personal_Marriage_Status">
						<option <?php echo ($Marriage_Status=='Single')?'selected':'';?>>Single</option>
						<option <?php echo ($Marriage_Status=='Married')?'selected':'';?>>Married</option>
					</select>
					<label for="txt_Personal_Marriage_Status" class="active-drop-down active">Marital Status</label>
			    </div>
			    
			    <div class="input-field col s6 m6 mrgstat">
					<input type="text" value="<?php echo($Marriage_Date);?>" id="txt_Personal_Marriage_Date" name="txt_Personal_Marriage_Date" />
					<label for="txt_Personal_Marriage_Date">Marriage Date</label>
			    </div>
			    
			    <div class="input-field col s6 m6 mrgstat">
					<input type="text"  value="<?php echo($Marriage_Spouse);?>" id="txt_Personal_Marriage_Spouse" name="txt_Personal_Marriage_Spouse" />
					<label for="txt_Personal_Marriage_Spouse">Spouse Name</label>
			    </div>
			    
			    <div class="input-field col s6 m6 mrgstat">
					<select id="txt_Personal_Marriage_CStatus" name="txt_Personal_Marriage_CStatus" >
						<option <?php echo ($Marriage_CStatus=='No')?'selected':'';?>>No</option>
						<option <?php echo ($Marriage_CStatus=='Yes')?'selected':'';?>>Yes</option>
					</select>
					<label for="txt_Personal_Marriage_CStatus" class="active-drop-down active">Child Status</label>
			    </div>
			    
				
				
				<div class="container col s12 m12" id="childtables" >
					<input type="hidden" id="count_child" name="count_child" value="1"/>
					<div class="form-inline">
							<div class="form-group">
								<button type="button" name="btn_childAdd" id="btn_childAdd" title="Add Child Row in Table Down" class="btn waves-effect waves-green" ><i class="fa fa-plus"></i> Add Child Details</button>
								<button type="button" name="btnChildcan" id="btnChildcan" title="Remove Child Row in Table Down"  class="btn waves-effect modal-action modal-close waves-red close-btn" ><i class="fa fa-minus"></i> Remove Child Details</button>
								
							</div>
					</div>
					
					<table class="table table-hovered table-bordered" id="childtable">
									<thead class="bg-danger">
										<tr>
											<th class="hidden">Child ID</th>
											<th>Child Name</th>
											<th>DOB</th>
											<th>Blood Group</th>
											<th>Gender</th>
										</tr>
									</thead>
									<tbody>
										<tr class="trchild" id="trchild_1" >
									    <td class="childcount hidden">1</td>
										<td align="center"><input name="txt_child_name_1" type="text" id="txt_child_name_1" placeholder="Child Name"/></td>
										<td align="center"><input name="txt_child_dob_1" type="text" id="txt_child_dob_1" placeholder="Child DOB"/></td>
										<td align="center"><input name="txt_child_blood_1" type="text" id="txt_child_blood_1" placeholder="Blood Group"/></td>
										<td align="center">
											<select name="txt_child_gen_1" id="txt_child_gen_1"   >
												<option>Male</option>
												<option>Female</option>
											</select>
										</td>
										</tr>
									</tbody>
					</table>
					  <?php 
						
						$sqlConnect="select * from child_details where EmployeeID='".$EmployeeID."' ";
						$myDB=new MysqliDb();
						$result=$myDB->query($sqlConnect);
						if($result){?>
							<table id="myTable1" class="data dataTable no-footer" cellspacing="0" width="100%">
							    <thead>
							        <tr>
							            <th class="hidden">ChildID</th>
							            <th>Child Name</th>
							            <th>DOB</th>
							            <th> Blood Group</th>
							            <th>Gender</th>						            
							            <th>Remove Detail</th>
							        </tr>
							    </thead>
						    <tbody>					        
						       <?php
						        foreach($result as $key=>$value){
								echo '<tr>';							
								echo '<td class="ExpID hidden">'.$value['id'].'</td>';
								echo '<td align="center">'.$value['ChildName'].'</td>';
								echo '<td align="center">'.$value['ChildDob'].'</td>';
								echo '<td align="center">'.$value['BloodGroup'].'</td>';
								echo '<td align="center">'.$value['ChildGender'].'</td>';
								echo '<td class="manage_item" ><i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" onclick="javascript:return ChildDelete(this);" id="'.$value['id'].'" data-position="left" data-tooltip="Delete">ohrm_delete</i> </td>';
								echo '</tr>';
								}
									
							?>			       
					    </tbody>
						</table>
						<?php
					 } 
					?>
					
					</div>
				<?php if($_SESSION["__user_logid"]==ADMINISTRATORID){ ?>
					<div class="input-field col s12 m12 right-align">
					  	 <button type="submit" title="Update Details" name="btn_Personal_Save" id="btn_Personal_Save" class="btn waves-effect waves-green">Save</button>
					</div>
<?php }
				?>
				
			<!--<div class="input-field col s12 m12 right-align">
		  	 <button type="submit" title="Update Details" name="btn_Personal_Save" id="btn_Personal_Save" class="btn waves-effect waves-green">Save</button>
		  	  <?php 
		  	//  if(trim($adharNum)==""){  ?>
		  	  <button type="button" name="btn_check_Adhar" id="btn_check_Adhar" class="btn waves-effect waves-gree">Check Adhar Number</button>
		  	  <?php //} ?>
		    </div>	-->				
		</div>
		
		</div>
</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
    
<script>
	$(document).ready(function(){
		$('select').formSelect();
		if($('#txt_Personal_Marriage_Status').val()=='Yes')
			{
				$('.childtables').removeClass('hidden');
			}
			else
			{
				$('.mrgstat').addClass('hidden');
			}
			if($('#txt_Personal_Marriage_CStatus').val()=='Yes')
			{
				$('#childtables').removeClass('hidden');
			}
			else
			{
				$('#childtables').addClass('hidden');
				
			}
		$('#count_child').val($(".trchild").length);
		$('#txt_Personal_Marriage_CStatus').change(function(){
			
			if($('#txt_Personal_Marriage_CStatus').val()=='Yes')
			{
				$('#childtables').removeClass('hidden');
			}
			else
			{
				$('#childtables').addClass('hidden');
			}
		});
		$('#txt_Personal_Marriage_Status').change(function(){
			$('#txt_Personal_Marriage_Spouse').val('');
			$('#txt_Personal_Marriage_Date').val('');
			$('#txt_Personal_Marriage_CStatus').val('No');
			if($('#txt_Personal_Marriage_Status').val()=='Married')
			{
				$('.mrgstat').removeClass('hidden');
			}
			else
			{
				$('.mrgstat').addClass('hidden');
				$('#childtables').addClass('hidden');
			}
		});
		$('#imgToempl').attr('src',$('#imgname').val());	
		
		$("#imgToempl").error(function(){
	        $(this).attr('src', '../Style/images/agent-icon.png');
	    });
		
		$('input[type="text"]').click(function(){
			$(this).closest('div').removeClass('has-error');	       
		});
		$('select').click(function(){
			$(this).closest('div').removeClass('has-error');	       
		});		
		
	   
		
		$('#btn_Personal_Save').on('click', function(){
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
			
			
			
		});
		var pl=$('#primaryLanguage').val();
		var chks= document.getElementsByName("secondaryLanguage[]");
		if (chks.length>0) {
			for ( i = 0; i < chks.length; i++) {
				if (chks[i].checked) {
					if (pl==chks[i].value) {
						$('#ssecondaryLanguage').addClass('has-error');
						validate=1;
						$('#ssecondaryLanguage').html('Secondary language should not same as primary language, please select another one');
						return false;
					} else {
						$('#ssecondaryLanguage').html('');
						$('#ssecondaryLanguage').removeClass('has-error');
					}
				}
			}
		}
        $('#txt_adhar_card_number').removeClass("has-error");
			var adharcard=$('#txt_adhar_card_number').val().trim();
			var spanID =  "spantxt_adhar_card_number";
			validate=0;
			//alert(adharcard);
			if(adharcard==""){
				if ($('#'+spanID).size() == 0) {
		            $('<span id="'+spanID+'" class="help-block">Enter Adhar Card Number</span>').insertAfter('#'+$('#txt_adhar_card_number').attr('id'));
		        }
				validate=1;
			}else 
			if(adharcard.length<12 || adharcard.length>12 ){
				
				if ($('#'+spanID).size() == 0) {
		            $('<span id="'+spanID+'" class="help-block">Enter twelve digit of Adhar Card Number</span>').insertAfter('#'+$('#txt_adhar_card_number').attr('id'));
		        }
				validate=1;
			}else
			
			if(adharcard.match(/^\d+$/)){
				
				validate=0;
			}else{
				
				if ($('#'+spanID).size() == 0) {
		            $('<span id="'+spanID+'" class="help-block">Enter numeric value only</span>').insertAfter('#'+$('#txt_adhar_card_number').attr('id'));
		        }
				validate=1;
			}
			if(validate==1)	{
				    $('#txt_adhar_card_number').addClass('has-error');
					return false;
			}
        	if($('#txt_dadhar_card').val()=='' && $('#hiddenAdhar').val()=="")
	        {
				$('#docalert').addClass('has-error');
				validate=1;
				$('#docalert').html('Please upload Adhar Card');
				
			}else{
				$('#docalert').html('');
			}	    
      	if(validate==1)
      	{		      		
			return false;
		}
       
    });
			if($('#txt_Personal_Marriage_Status').val()=='Married')
			{
				$('.mrgstat').removeClass('hidden');
			}
			else
			{
				$('.mrgstat').addClass('hidden');
				$('#childtables').addClass('hidden');
			}
		$('#btn_childAdd').click(function(){
				$count=$(".trchild").length;
				$id="trchild_"+parseInt($count+1);
				$('#count_child').val(parseInt($count+1));
				$tr=$("#trchild_1").clone().attr("id",$id);
				$('#childtable tbody').append($tr);
				$tr.children("td:first-child").html(parseInt($count+1));
				$tr.children("td:nth-child(2)").children("input").attr({"id":"txt_child_name_"+parseInt($count+1),"name":"txt_child_name_"+parseInt($count+1)}).val('');
				
				$tr.children("td:nth-child(3)").children("input").attr({"id":"txt_child_dob_"+parseInt($count+1),"name":"txt_child_dob_"+parseInt($count+1)}).datetimepicker({ format:'Y-m-d', timepicker:false}).val('');
				$tr.children("td:nth-child(4)").children("input").attr({"id":"txt_child_blood_"+parseInt($count+1),"name":"txt_child_blood_"+parseInt($count+1)}).val('');
				$tr.children("td:nth-child(5)").children("select").attr({"id":"txt_child_gen_"+parseInt($count+1),"name":"txt_child_gen_"+parseInt($count+1)}).val('Male');
				
			});
			$('#btnChildcan').click(function(){
				$count=$(".trchild").length;
				if($count>1)
				{
					$('#childtable tbody').children("tr:last-child").remove();
					$('#count_child').val(parseInt($count-1));
				}
				
			});
			$('.imgChildDelete').click(function(){
				if(confirm('you want to delete child '))
				{
					$item=$(this);
					$.ajax({url: "../Controller/deleteChild.php?ID="+$item.attr("id"), success: function(result){
	                    $var=result.split('|');
	                    if($var[0]=="done")
	                    {
							$item.closest("tr").remove();
						}
	                    $('#alert_msg').html(result);
			      		$('#alert_message').show().attr("class","SlideInRight animated");
			      		$('#alert_message').delay(5000).fadeOut("slow");                        
	                    $('select').formSelect();                                  
	                }});
				}
				
			});
			$('#editimage').on('click', function(){
				$('#uploadimage').toggle();
				
			});
		
			

	});
	function ChildDelete(el)
	{
		if(confirm('you want to delete child '))
				{
					$item=$(el);
					$.ajax({url: "../Controller/deleteChild.php?ID="+$item.attr("id"), success: function(result){
	                    $var=result.split('|');
	                    if($var[0]=="done")
	                    {
							$item.closest("tr").remove();
							$('select').formSelect();
						}
						$('select').formSelect();
	                }});
				}
	}
	
</script>


<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
