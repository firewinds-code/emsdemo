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
// Global variable used in Page Cycle
$alert_msg =$_ITID=$_HRID=$_ReportsTo=$_Stipend='';

//Trigger On Delete Btn Clicked


// Trigger Button-Save Click Event and Perform DB Action
if(isset($_POST['btn_Verifier_Save']))
{
	$EmpId=(isset($_POST['txt_EmployeeID'])? $_POST['txt_EmployeeID'] : null);
	$EmpName=(isset($_POST['txt_EmployeeName'])? $_POST['txt_EmployeeName'] : null);
	$EmployeeStatus=(isset($_POST['txt_status'])? $_POST['txt_status'] : null);
	
	if($EmpId!="" && $EmpId!=null && $EmpName!="" && $EmpName!=null && $EmployeeStatus!="" && $EmpName!=null  )
	{
		$createBy=$_SESSION['__user_logid'];
		
		 $Insert="CALL insert_asset_verifier('".$EmpId."','".$EmpName."','".$EmployeeStatus."','".$createBy."')";
		
		$myDB=new MysqliDb();
		$myDB->rawQuery($Insert);
		$mysql_error = $myDB->getLastError();
		if(empty($mysql_error))
		{
			if($myDB->count > 0)
			{
				echo "<script>$(function(){ toastr.success('Verifier Added Successfully'); }); </script>";
			}
			else
			{
				echo "<script>$(function(){ toastr.error('Verifier Not Added, May be Duplicate Entry Found check manualy'); }); </script>";
			}	
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Verifier not Added.'); }); </script>";
		}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Verifier Details Should not be empty.'); }); </script>";
	}
}

// Trigger Button-Edit Click Event and Perform DB Action
if(isset($_POST['btn_Verifier_Edit']))
{	
	$DataID=$_POST['txt_Verifier_id'];
	$EmpId=(isset($_POST['txt_EmployeeID'])? $_POST['txt_EmployeeID'] : null);
	$EmpName=(isset($_POST['txt_EmployeeName'])? $_POST['txt_EmployeeName'] : null);
	$EmployeeStatus=(isset($_POST['txt_status'])? $_POST['txt_status'] : null);
	
	$ModifiedBy=$_SESSION['__user_logid'];
	if( $DataID!=null &&   $DataID!="" && $EmpId!="" && $EmpId!=null && $EmpName!="" && $EmpName!=null && $EmployeeStatus!="" && $EmpName!=null )
	{
		
	  $Update="UPDATE `asset_verifier` SET `EmployeeName` = '".$EmpName."', `status` = '".$EmployeeStatus."' , `updatedBy` = '".$ModifiedBy."' , `updatedDate` = '".date('Y-m-d h:i:s')."' WHERE (`id` = '".$DataID."' and `EmployeeID` = '".$EmpId."');";
		$myDB=new MysqliDb();
		if(!empty($DataID) && $DataID!='')
		{
			$myDB->rawQuery($Update);
			$mysql_error = $myDB->getLastError();
			if(empty($mysql_error))
			{
				
				echo "<script>$(function(){ toastr.success('Verifier Updated Successfully'); }); </script>";
				
			}
			else
			{
				echo "<script>$(function(){ toastr.error('Verifier not updated ::  ".$mysql_error."); }); </script>";
			}
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First :: <code>(If Not Resolved then contact to technical person)</code>'); }); </script>";
		}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Verifier Details Should not be empty.); }); </script>";
	}
	
	
}
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
<span id="PageTittle_span" class="hidden">Asset verifier Details</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Asset verifier Details <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Verifier"><i class="material-icons">add</i></a></h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
<!--Form element model popup start-->
        <div id="myModal_content" class="modal modal_small">
		 <!-- Modal content-->
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4">Manage Asset Verifier Details</h4>
		      <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
		        <div class="col s12 m12">
		        
		       <div class="input-field col s6 m6">
		            <input type="text" id="txt_EmployeeID" name="txt_EmployeeID" required />
		            <label for="txt_EmployeeID">Employee ID</label>
			    </div> 
			    <div class="input-field col s6 m6">
		            <input type="text" id="txt_EmployeeName" name="txt_EmployeeName" required />
		            <label for="txt_EmployeeName">Employee name</label>
			    </div>
			    
			    </div>
			    <div class="col s12 m12">
			    
			  
			    <div class="input-field col s6 m6">
	            <select id="txt_status" name="txt_status" required >
	            	<option value="1">Active</option>
	            	<option value="0">In Active</option>	
			     
	            </select>
	            <label for="txt_status" class="active-drop-down active">Verifier Status</label>
			    </div>
			    
			     <div class="input-field col s6 m6 hidden">
		            <input type="text" id="txt_Verifier_id" name="txt_Verifier_id" />
		            <label for="txt_Verifier_id">ID</label>
			    </div>
			    
			    </div>
			    
				<div class="input-field col s12 m12 right-align">
					<button type="submit" name="btn_Verifier_Save" id="btn_Verifier_Save" class="btn waves-effect waves-green">Add</button>
					<button type="submit" name="btn_Verifier_Edit" id="btn_Verifier_Edit" class="btn waves-effect waves-green hidden">Save</button>
					<button type="button" name="btn_Verifier_Can" id="btn_Verifier_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
         		</div>
       		
      		 </div>
            </div>
        </div>
<!--Form element model popup End-->
<!--Reprot / Data Table start -->
	    <div id="pnlTable">
		    <?php 
				$sqlConnect = 'select id,EmployeeID, EmployeeName, status,added_by,updatedBy , updatedDate, createdDate from asset_verifier ; ';
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
					<th>EmployeeID</th>
					<th>Employee Name</th>
					<th>Status</th>
					<th>Added By ID</th>
					<th>Updated By ID</th>
					<th>Updated Date</th>						            
					<th>Created Date</th>						            
					<th>Edit</th>
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
					echo '<td class="empId">'.$value['EmployeeID'].'</td>';
					echo '<td class="empName">'.$value['EmployeeName'].'</td>';
					echo '<td class="empStatus">'.$value['status'].'</td>';
					echo '<td class="empAddedById">'.$value['added_by'].'</td>';
					echo '<td class="empUpdatedById">'.$value['updatedBy'].'</td>';
					echo '<td class="empUpdated">'.$value['updatedDate'].'</td>';
					echo '<td  class="empCreated">'.$value['createdDate'].'</td>';
					echo '<td class="edit_verifier" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="'.$value['id'].'"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
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
});




// This code for cancel button trigger click and also for model close
$('#btn_Verifier_Can').on('click', function() {
        $('#txt_EmployeeID').val('');
        $('#txt_Verifier_id').val('');	
        $('#txt_EmployeeName').val('');	
        $('#txt_status').val('1');	
     
        $('#btn_Verifier_Save').removeClass('hidden');
        $('#btn_Verifier_Edit').addClass('hidden');
      
         
         
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
		
        $("#myModal_content input,#myModal_content textarea").each(function(index, element) {
        	
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


//On Clicke Add Btn remove The Read only Property From Employee ID.
$('ContentAdd').on('click', function(){
	$("#txt_EmployeeID").prop("readonly", false);
			});

// This code for submit button and form submit for all model field validation if this contain a required attributes also has some manual code validation to if needed.
    
$('#btn_Verifier_Edit,#btn_Verifier_Save').on('click', function(){
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
        	if(($(this).val().trim() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown'))
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
      		/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
      		$('#alert_message').show().attr("class","SlideInRight animated");
      		$('#alert_message').delay(50000).fadeOut("slow");*/
      		
      		$(function(){ toastr.error(alert_msg); });
			return false;
		}
       
    });
    

});


// This code for trigger edit on all data table also trigger model open on a Model ID
    
function EditData(el)
{
	
	//Diabling Editing of  employee ID
	$("#txt_EmployeeID").prop("readonly", true);
	
	//Get Value Of The Row On Which Clicker
		var tr = $(el).closest('tr');
		var id = tr.find('.id').text();
        var employeeId = tr.find('.empId').text();
        var employeeName = tr.find('.empName').text();
        var empStatus = tr.find('.empStatus').text();
        var updatedDate = tr.find('.empUpdated').text();
        var createdDate = tr.find('.empCreated').text();
       
       
        //alert(location);alert(locid);
        //$("#txt_location option:contains(" + location + ")").attr('selected', 'selected');	
        
       /*$("#txt_location option").filter(function() {
    return this.text == location; 
}).attr('selected', true);*/
        
      //Set Value To the Modal data to Edit
       
        $('#txt_EmployeeID').val(employeeId);
        $('#txt_EmployeeName').val(employeeName);
        $('#txt_status').val(empStatus);
        $('#txt_Verifier_id').val(id);
       
        
        $('#btn_Verifier_Save').addClass('hidden');
        $('#btn_Verifier_Edit').removeClass('hidden');
        //$('#btn_Verifier_Can').removeClass('hidden');
        
		
		$('#myModal_content').modal('open');
        $("#myModal_content input,#myModal_content textarea").each(function(index, element) {
        	
	         if($(element).val().length > 0) {
	           $(this).siblings('label, i').addClass('active');
	         }
	         else
	         {
			 	$(this).siblings('label, i').removeClass('active');
			 }
			        
		});
		$('select').formSelect();  
}



// This code for trigger del*t*

function DeleteVerifier(el)
{
////alert(el);
var currentUrl = window.location.href;
var Cnfm=confirm("Do You Want To Delete This ? ");
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
	xmlhttp.open("GET", "../Controller/DeleteAssetVerifier.php?ID=" + id, true);
	xmlhttp.send();
	
}
}

	
	
	
	
function isNumber(evt){
var iKeyCode = (evt.which) ? evt.which : evt.keyCode
if(iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
    return false;
    return true;
}    



</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>