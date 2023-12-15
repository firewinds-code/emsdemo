<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
// Only for user type administrator
if(isset($_SESSION))
{
	
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
	}
	if($_SESSION["__user_type"]!='ADMINISTRATOR' &&  $_SESSION["__user_logid"] != 'CE10091236')
	{
		$location= URL.'Login';
		$_SESSION['MsgLg'] = "You are not allowed to acces this page." ;
		echo "<script>location.href='".$location."'</script>";
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}
/*
ALTER TABLE `new_client_master` 
ADD COLUMN `VH` VARCHAR(45) NULL DEFAULT NULL AFTER `days_of_rotation`;

*/


//Trigger On Delete Btn Clicked


// Trigger Button-Save Click Event and Perform DB Action
if(isset($_POST['btn_add_new']))
{
	$emailType=(isset($_POST['mailType'])? $_POST['mailType'] : null);
	$email=(isset($_POST['emailIT'])? $_POST['emailIT'] : null);
	$locationC21= (isset($_POST['CogentC121'])? $_POST['CogentC121'] : null);
	$CogentC100= (isset($_POST['CogentC100'])? $_POST['CogentC100'] : null);
	$CogentA61= (isset($_POST['CogentA61'])? $_POST['CogentA61'] : null);
	$BangaloreGopalna= (isset($_POST['BangaloreGopalna'])? $_POST['BangaloreGopalna'] : null);
	$BangaloreHebbal= (isset($_POST['BangaloreHebbal'])? $_POST['BangaloreHebbal'] : null);
	$Vadodara= (isset($_POST['Vadodara'])? $_POST['Vadodara'] : null);
	$MangaloreRajTower= (isset($_POST['MangaloreRajTower'])? $_POST['MangaloreRajTower'] : null);
	$MangaloreFortune= (isset($_POST['MangaloreFortune'])? $_POST['MangaloreFortune'] : null);
	$Meerut= (isset($_POST['Meerut'])? $_POST['Meerut'] : null);
	$Bareilly= (isset($_POST['Bareilly'])? $_POST['Bareilly'] : null);
	
		
	
	
	
	 if($emailType!="" && $emailType!=null && $email!="" && $email!=null  )
	{
		$createBy=$_SESSION['__user_logid'];
		$myDB=new MysqliDb();
		
		//Cogent C121
		if($locationC21!="" && $locationC21!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Noida-Cogent C121')";
	
		$myDB->rawQuery($Insert);
		}
		
		//Cogent C100
		if($CogentC100!="" && $CogentC100!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Noida-Cogent C100')";
	
		$myDB->rawQuery($Insert);
		}
		
		//Cogent A61
		if($CogentA61!="" && $CogentA61!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Noida-Cogent A61')";
	
		$myDB->rawQuery($Insert);
		}
		
		//Cogent BangaloreGopalna
		if($BangaloreGopalna!="" && $BangaloreGopalna!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Bangalore Gopalna')";
	
		$myDB->rawQuery($Insert);
		}
		//Cogent BangaloreHebbal
		if($BangaloreHebbal!="" && $BangaloreHebbal!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Bangalore Hebbal')";
	
		$myDB->rawQuery($Insert);
		}
		
		//Vadodara
		if($Vadodara!="" && $Vadodara!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Vadodara')";
	
		$myDB->rawQuery($Insert);
		}
		
		//MangaloreRajTower
		if($MangaloreRajTower!="" && $MangaloreRajTower!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Mangalore Raj Tower')";
	
		$myDB->rawQuery($Insert);
		}
		
		//MangaloreFortune
		if($MangaloreFortune!="" && $MangaloreFortune!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Mangalore Fortune')";
	
		$myDB->rawQuery($Insert);
		}
		
		//Meerut
		if($Meerut!="" && $Meerut!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Meerut')";
	
		$myDB->rawQuery($Insert);
		}
		//Bareilly
		if($Bareilly!="" && $Bareilly!=null){
			 $Insert="CALL ithdk_insert_email('".$createBy."','".$email."','".$emailType."','Bareilly')";
	
		$myDB->rawQuery($Insert);
		}
		
		
		$mysql_error = $myDB->getLastError();
		if(empty($mysql_error))
		{
			if($myDB->count > 0)
			{
				echo "<script>$(function(){ toastr.success('Email Added Successfully'); }); </script>";
			}
			else
			{
				echo "<script>$(function(){ toastr.error('Email Not Added, May be Duplicate Entry Found check manualy'); }); </script>";
			}	
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Email not Added.'); }); </script>";
		}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Email Details Should not be empty.'); }); </script>";
	} 
} 

// Trigger Button-Edit Click Event and Perform DB Action

?>

<script>
//contain load event for data table and other importent rand required trigger event and searches if any
$(document).ready(function(){
$('#myTable').DataTable({
		dom: 'Bfrtip',
		scrollX: '100%',
		"iDisplayLength": 25,
		scrollCollapse: true,
		lengthMenu: [
	            [ 5,10, 25, 50, -1 ],
	            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
	        ],
	         buttons: [
			        {
			            extend: 'excel',
			            text: 'EXCEL',
			            extension: '.xlsx',
			            exportOptions: {
			                modifier: {
			                    page: 'all'
			                }
			            },
			            title: 'table'
			        }
			        /*,'copy'*/
			        ,'pageLength'     
			    ]
	       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
	    });
$('.buttons-copy').attr('id','buttons_copy');
$('.buttons-csv').attr('id','buttons_csv');
$('.buttons-excel').attr('id','buttons_excel');
$('.buttons-pdf').attr('id','buttons_pdf');
$('.buttons-print').attr('id','buttons_print');
$('.buttons-page-length').attr('id','buttons_page_length');

});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">IT Help Desk</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	  <h4>Handle Emails
	  <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd"  onclick="javascript:return openModelToAdd();" data-position="bottom" data-tooltip="Add Email"><i class="material-icons">add</i></a></h4>	
	  			

<!-- Form container if any -->
	<div class="schema-form-section row" >
<!--Form element model popup start-->
        <div id="myModal_content" class="modal modal_small">
		 <!-- Modal content-->
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4">Add Email</h4>
		      <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
		        
					
					
					<div class="input-field col s12 m12" id = "locations">
							<div class="col s3 m3">
							<input type="checkbox" id="allLocation" name="allLocation" value="all" onchange="onSelectDeselectAllLocation(this)">
							<label for="allLocation">All</label>
							</div>
							<div class=" col s3 m3">
							<input type="checkbox" id="CogentC121" name="CogentC121" value="Noida-Cogent C121">
							<label for="CogentC121">Noida-Cogent C121</label>
							</div>
							<div class="col s3 m3">
							<input type="checkbox" id="CogentC100" name="CogentC100" value="Noida-Cogent C100">
							<label for="CogentC100">Noida-Cogent C100</label>
							</div>
							<div class="col s3 m3">
							<input type="checkbox" id="CogentA61" name="CogentA61" value="Noida-Cogent A61">
							<label for="CogentA61">Noida-Cogent A61</label>
							</div>
							
					</div>
					
					<div class="input-field col s12 m12">
							<div class="input-field col s3 m3">
							<input type="checkbox" id="BangaloreGopalna" name="BangaloreGopalna" value="Bangalore Gopalna">
							<label for="BangaloreGopalna">Bangalore Gopalna</label>
							</div>
							<div class="input-field col s3 m3">
							<input type="checkbox" id="BangaloreHebbal" name="BangaloreHebbal" value="Bangalore Hebbal">
							<label for="BangaloreHebbal">Bangalore Hebbal</label>
							</div>
							<div class="input-field col s3 m3">
							<input type="checkbox" id="Vadodara" name="Vadodara" value="Vadodara">
							<label for="Vadodara">Vadodara</label>
							</div>
							<div class="input-field col s3 m3">
							<input type="checkbox" id="MangaloreRajTower" name="MangaloreRajTower" value="Mangalore Raj Tower">
							<label for="MangaloreRajTower">Mangalore Raj Tower</label>
							</div>
							
							
					</div>
					
					
					<div class="input-field col s12 m12">
							<div class="input-field col s3 m3">
							<input type="checkbox" id="MangaloreFortune" name="MangaloreFortune" value="Mangalore Fortune">
							<label for="MangaloreFortune">Mangalore Fortune</label>
							</div>
							<div class="input-field col s3 m3">
							<input type="checkbox" id="Meerut" name="Meerut" value="Meerut">
							<label for="Meerut">Meerut</label>
							</div>
							<div class="input-field col s3 m3">
							<input type="checkbox" id="Bareilly" name="Bareilly" value="Bareilly">
							<label for="Bareilly">Bareilly</label>
							</div>
							
					</div>
					
					
				<!--	<div>				
				
					<select class="select" name="locationOfEmail" id="locationOfEmail"  multiple>
						<option value=''  >Select</option>
						<option value='Noida-Cogent C121'  >Noida-Cogent C121</option>
						<option value='Noida-Cogent C100' >Noida-Cogent C100</option>
						<option value='Noida-Cogent A61' >Noida-Cogent A61</option>
						<option value='Bangalore Gopalna' >Bangalore Gopalna</option>
						<option value='Bangalore Hebbal' >Bangalore Hebbal</option>
						<option value='Vadodara' >Vadodara</option>
						<option value='Mangalore Raj Tower' >Mangalore Raj Tower</option>
						<option value='Mangalore Fortune' >Mangalore Fortune</option>
						<option value='Meerut' >Meerut</option>
						<option value='Bareilly' >Bareilly</option>
					</Select>
					<label for="locationOfEmail" class="form-label select-label" >Location</label>
				</div>	 -->
					
					
					  <div class="input-field col s12 m12" id = "divStatus" >
						<Select name="mailType" id="mailType" >
							<option value=''  >Select</option>
							<option value='TO'  >TO</option>
							<option value='CC' >CC</option>
						
						</Select>
						<label for="mailType" class="active-drop-down active" >Email Type</label>
					  </div>	
						<div class="input-field col s12 m12">
					<!--	<textarea id="remark" name="remark" rows="4" > -->
							<input type="text" id="emailIT" name="emailIT" required />
							<label for="emailIT">Email</label>
						</div>
			     
			   
			    
				<div class="input-field col s12 m12 right-align">
				<!--	<button type="submit" name="btn_Verifier_Save" id="btn_Verifier_Save" class="btn waves-effect waves-green hidden">Add</button> -->
					<button type="submit" name="btn_add_new" id="btn_add_new" class="btn waves-effect waves-green " value="Submit">Submit</button>
					<button type="button" name="btn_add_new_Can" id="btn_add_new_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
         		</div>
       		
      		 </div>
            </div>
        </div>
<!--Form element model popup End-->


<!--Reprot / Data Table start -->
	    <div id="pnlTable">
		    <?php 
				$sqlConnect = "SELECT id, email, emailType, location,createdBy, createdDate FROM ems.ithdk_master_email_address";
				$myDB=new MysqliDb();
				$result=$myDB->rawQuery($sqlConnect);
				$mysql_error = $myDB->getLastError();
				if(empty($mysql_error)){
			?>
			<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
		    <thead>
		        <tr>
					<th>S No</th>
					<th>ID</th>
					<th>Email</th>
					<th>Email Type</th>
					<th>Location</th>
					<th>Created BY</th>
					<th>Created Date</th>
					<th>Delete</th>
					
		        </tr>
		    </thead>
		    <tbody>					        
		       <?php
		       $i=1;
		        foreach($result as $key=>$value){
				echo '<tr>';
					echo '<td >'.$i.'</td>';
					echo '<td class="id">'.$value['id'].'</td>';
					echo '<td class="email">'.$value['email'].'</td>';
					echo '<td class="emailType">'.$value['emailType'].'</td>';
					echo '<td class="location">'.$value['location'].'</td>';
					echo '<td class="createdBy">'.$value['createdBy'].'</td>';
					echo '<td class="createdDate">'.$value['createdDate'].'</td>';
					
					 echo '<td class="delete_verifier" ><i class="material-icons delete_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return DeleteVerifier(this);" id="'.$value['id'].'"   data-position="left" data-tooltip="Delete">ohrm_delete</i> </td>'; 
			?>
				
			<?php
				echo '</tr>'; 
				$i++;
				}	
				?>			       
		    </tbody>
	  </table>
		    <?php } ?>
	    </div>
<!--Reprot / Data Table End -->	
	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>
<script>
$(document).ready(function(){
	
//Model Assigned and initiation code on document load	
	$('.modal').modal(
	{
		onOpenStart:function(elm)
		{
			
		},
		onCloseEnd:function(elm)
		{
			$('#btn_Verifier_Can').trigger("click");
		}
	});
	
/* 		
// This code for remove error span from all element contain .has-error class on listed events
$(document).on("click blur focus change",".has-error",function(){
	$(".has-error").each(function(){
		if($(this).hasClass("has-error"))
		{
			$(this).removeClass("has-error");
			$(this).next("span.help-block").remove();
			if($(this).is('select'))
			{
				$(this).parent('.select-wrapper').find("span.help-block").remove();
			}
			if($(this).hasClass('select-dropdown'))
			{
				$(this).parent('.select-wrapper').find("span.help-block").remove();
			}
		}
	});
}); */


});

function onSelectDeselectAllLocation(){
	
	var allLoc = document.getElementById("allLocation").checked;
	if(allLoc){
		document.getElementById("CogentC121").checked = true;
		document.getElementById("CogentC100").checked = true;
		document.getElementById("CogentA61").checked = true;
		document.getElementById("BangaloreGopalna").checked = true;
		document.getElementById("BangaloreHebbal").checked = true;
		document.getElementById("Vadodara").checked = true;
		document.getElementById("MangaloreRajTower").checked = true;
		document.getElementById("MangaloreFortune").checked = true;
		document.getElementById("Meerut").checked = true;
		document.getElementById("Bareilly").checked = true;
	}else{
		document.getElementById("CogentC121").checked = false;
		document.getElementById("CogentC100").checked = false;
		document.getElementById("CogentA61").checked = false;
		document.getElementById("BangaloreGopalna").checked = false;
		document.getElementById("BangaloreHebbal").checked = false;
		document.getElementById("Vadodara").checked = false;
		document.getElementById("MangaloreRajTower").checked = false;
		document.getElementById("MangaloreFortune").checked = false;
		document.getElementById("Meerut").checked = false;
		document.getElementById("Bareilly").checked = false;
	
		
	}
	
}


/* // This code for cancel button trigger click and also for model close
$('#btn_Verifier_Can ,#btn_Verifier_CanRCA').on('click', function() {
      
      
         
         
        // This code for remove error span from input text on model close and cancel
        $(".has-error").each(function(){
			if($(this).hasClass("has-error"))
			{
				$(this).removeClass("has-error");
				$(this).next("span.help-block").remove();
				if($(this).is('select'))
				{
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}
				if($(this).hasClass('select-dropdown'))
				{
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}
				
			}
		});
		// This code active label on value assign when any event trigger and value assign by javascript code.
		
        $("#myModal_content input,#myModal_content textarea ,#myModal_contentRCA input,#myModal_contentRCA textarea").each(function(index, element) {
        	
	         if($(element).val().length > 0) {
	           $(this).siblings('label, i').addClass('active');
	         }
	         else
	         {
			 	$(this).siblings('label, i').removeClass('active');
			 }
			        
		});
		$('select').formSelect();
       
    });
 */



// This code for trigger edit on all data table also trigger model open on a Model ID
    
function openModelToAdd()
{
	$('#myModal_content').modal('open');
		
        
}


function isValid(str) {
    return (!str || str.length === 0 || str== 'na' );
}



// This code for trigger del*t*

function DeleteVerifier(el)
{
////alert(el);
var currentUrl = window.location.href;
var Cnfm=confirm("Do You Want To Delete This Email ? ");
if(Cnfm)
{
	var tr = $(el).closest('tr');
	var id = tr.find('.id').text();
      
        
      
    var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var Resp=xmlhttp.responseText;
			
			$(function(){ toastr.success(Resp); });
			
			window.location.href = currentUrl;
			
		}
	}
	xmlhttp.open("GET", "../Controller/ithdk_delete_email.php?ID=" + id, true);
	xmlhttp.send();
	
}
} 

	
	
	
	
function isNumber(evt){
var iKeyCode = (evt.which) ? evt.which : evt.keyCode
if(iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
    return false;
    return true;
}    


//On Click SAve Status Btn




</script>


<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>