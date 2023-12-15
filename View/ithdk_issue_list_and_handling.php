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
	/* if($_SESSION["__user_type"]!='ADMINISTRATOR' &&  $_SESSION["__user_logid"] != 'CE10091236')
	{
		$location= URL.'Login';
		$_SESSION['MsgLg'] = "You are not allowed to acces this page." ;
		echo "<script>location.href='".$location."'</script>";
	} */
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

/* 
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
} */

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



<style>
.form-control:focus {
border-color: #d01010;
outline: 0;
box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(233, 102, 139, 0.6);

}
    #overlay{	
  position: fixed;
  top: 0;
  z-index: 100;
  
  width: 100%;
  height:100%;
  display: none;
  background: rgba(0,0,0,0.6);
}
.cv-spinner {
  height: 100%;
  width:85%;
  display: flex;
  justify-content: center;
  
  align-items: center;  
}
.spinner {
  width: 40px;
  height: 40px;
  border: 4px #ddd solid;
  border-top: 4px #2e93e6 solid;
  border-radius: 100%;
  animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
  100% { 
    transform: rotate(360deg); 
  }
}
.is-hide{
  display:none;
}
</style>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">IT Help Desk</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	  <h4>Issue List</h4>
	 <!-- <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" target="_blank" href="ithdk_raise_request_web.php" data-position="bottom" data-tooltip="Raise Ticket"><i class="material-icons">add</i></a>	 -->
	  			

<!-- Form container if any -->
	<div class="schema-form-section row" >
<!--Form element model popup start-->
        <div id="myModal_content" class="modal modal_small">
		 <!-- Modal content-->
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4">Handle Issue</h4>
		      <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
		        
		        
					  <div class="input-field col s12 m12" id = "divStatus" >
						<Select name="newStatus" id="newStatus" onchange="onChangeStatus(this)" >
							<option value='na'  >Select</option>
							<option value='InProgress'  >InProgress</option>
							<option value='Close' >Close</option>
						
						</Select>
						<label for="newStatus" class="active-drop-down active" >Status</label>
					  </div>
						<div class="input-field col s12 m12"  id = "ExtTatDiv" style = "display:none">
					
						<input type="number" min="1" max="30" id="extTat" name="extTat" title="TAT(Optional)"  >
						<label for="extTat">TAT Extension(Hour)</label>			
			
						</div>
						<div class="input-field col s12 m12">
					<!--	<textarea id="remark" name="remark" rows="4" > -->
							<input type="text" id="remark" name="remark" required />
							<label for="remark">Remark</label>
						</div>
			      <div class="input-field col s6 m6 hidden">
		            <input type="text" id="rowId" name="rowId" />
		          
			    </div>
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="ticketIdIssue" name="ticketIdIssue" />
		          
			    </div>
				
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="issLocation" name="issLocation" />
		          
			    </div>
				
				
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="currStatus" name="currStatus" />
		          
			    </div>
				
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="handlerEmail" name="handlerEmail" />
		          
			    </div>
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="handlerName" name="handlerName" />
		          
			    </div>
				
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="requesterName" name="requesterName" />
		          
			    </div>
			   <div class="input-field col s6 m6 hidden">
		            <input type="text" id="requesterEmail" name="requesterEmail" />
		          
			    </div> 
				<div class="input-field col s6 m6 hidden">
		            <input type="text" id="issTat" name="issTat" />
		          
			    </div> 
				<div class="input-field col s6 m6 hidden">
		            <input type="text" id="issClient" name="issClient" />
		          
			    </div>
			   
			    
				<div class="input-field col s12 m12 right-align">
				<!--	<button type="submit" name="btn_Verifier_Save" id="btn_Verifier_Save" class="btn waves-effect waves-green hidden">Add</button> -->
					<button type="submit" name="btn_Verifier_Edit" id="btn_Verifier_Edit" class="btn waves-effect waves-green " value="Submit">Save</button>
					<button type="button" name="btn_Verifier_Can" id="btn_Verifier_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
         		</div>
       		
      		 </div>
            </div>
        </div>
<!--Form element model popup End-->


<!-- Form container if any -->
	<div class="schema-form-section row" >
<!--Form element model popup start-->
        <div id="myModal_contentRCA" class="modal modal_small">
		 <!-- Modal content-->
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4">Handle Issue RCA</h4>
		      <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
		        
		        
					  
						<div class="input-field col s12 m12">
					<!--	<textarea id="remark" name="remark" rows="4" > -->
							<input type="text" id="rcaText" name="rcaText" required />
							<label for="rcaText">RCA TEXT</label>
						</div>
						
							
						
						 <div class="file-field input-field col s5 m5">
							<div class="btn">
								<span>RCA Attachment (Optional)</span>
								<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;" >
								<br>
								<span class="file-size-text">Accepts up to 2MB</span>
							</div>
							<div class="file-path-wrapper" >
								 <input class="file-path" type="text" style="">						    
							</div>
					  </div>	
			    
				
					 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="rowIdRCA" name="rowIdRCA" />
		          
			    </div>
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="ticketIdIssueRCA" name="ticketIdIssueRCA" />
		          
			    </div>
				
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="issLocationRCA" name="issLocationRCA" />
		          
			    </div>
				
				
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="currStatusRCA" name="currStatusRCA" />
		          
			    </div>
				
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="handlerEmailRCA" name="handlerEmailRCA" />
		          
			    </div>
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="handlerNameRCA" name="handlerNameRCA" />
		          
			    </div>
				
				 <div class="input-field col s6 m6 hidden">
		            <input type="text" id="requesterNameRCA" name="requesterNameRCA" />
		          
			    </div>
			   <div class="input-field col s6 m6 hidden">
		            <input type="text" id="requesterEmailRCA" name="requesterEmailRCA" />
		          
			    </div> 
				<div class="input-field col s6 m6 hidden">
		            <input type="text" id="issTatRCA" name="issTatRCA" />
		          
			    </div> 
				<div class="input-field col s6 m6 hidden">
		            <input type="text" id="issClientRCA" name="issClientRCA" />
		          
			    </div>
			   
				
			   
			   
			    
				<div class="input-field col s12 m12 right-align">
				<!--	<button type="submit" name="btn_Verifier_Save" id="btn_Verifier_Save" class="btn waves-effect waves-green hidden">Add</button> -->
					<button type="submit" name="btn_Verifier_EditRCA" id="btn_Verifier_EditRCA" class="btn waves-effect waves-green ">Save</button>
					<button type="button" name="btn_Verifier_CanRCA" id="btn_Verifier_CanRCA" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
         		</div>
       		
      		 </div>
            </div>
        </div>
<!--Form element model popup End-->

<!--Reprot / Data Table start -->
	    <div id="pnlTable">
		    <?php 
				$sqlConnect = "SELECT id, ticket_id, process_client, process, priorty, category, issue_type, issue_disc, agent_impacted, total_agents, requester_empId, requester_name, requester_email, requester_mobile, location, tat, exten_tat, issue_status, handler_empId, handler_name, handler_mobile, handler_email, inprogress_remark, inprogress_date, closing_remark, closing_date, rca_text, rca_attachement, rca_date, created_date FROM ems.ithdk_ticket_details where rca_date is null order by id desc";
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
					<th>Ticket ID</th>
					<th>Client</th>
					<th>Process</th>
					<th>Priority</th>
					<th>Location</th>
					<th>TAT(Hour)</th>
					<th>TAT Extension(Hour)</th>
					<th>Issue Status</th>
					<th>Category</th>
					<th>Issue Type</th>
					<th>Issue Desc</th>						            
					<th>Total Agents</th>						            
					<th>Agent Impacted</th>						            
					<th>Requester EmpID</th>
					<th>Requester Name</th>
					<th>Requester Email</th>
					<th>Requester Mobile</th>
					<th>Handler EmpID</th>
					<th>Handler Name</th>
					<th>Handler Email</th>
					<th>Handler Mobile</th>
					<th>InProgrees Remark</th>
					<th>InProgress Date</th>
					<th>Closing Remark</th>
					<th>Closing Date</th>
					<th>RCA</th>
					<th>RCA Attachment</th>
					<th>RCA Date</th>
					<th>Created Date</th>
					<th>Handle</th>
					
		        </tr>
		    </thead>
		    <tbody>					        
		       <?php
		       $i=1;
		        foreach($result as $key=>$value){
				echo '<tr>';
					echo '<td >'.$i.'</td>';
					echo '<td class="id">'.$value['id'].'</td>';
					echo '<td class="ticketId">'.$value['ticket_id'].'</td>';
					echo '<td class="client">'.$value['process_client'].'</td>';
					echo '<td class="process">'.$value['process'].'</td>';
					echo '<td class="priority">'.$value['priorty'].'</td>';
					echo '<td  class="locationIssue">'.$value['location'].'</td>';
					echo '<td  class="tat">'.$value['tat'].'</td>';
					echo '<td  class="extTat">'.$value['exten_tat'].'</td>';
					echo '<td  class="issStatus">'.$value['issue_status'].'</td>';
					echo '<td class="category">'.$value['category'].'</td>';
					echo '<td class="issueType">'.$value['issue_type'].'</td>';
					echo '<td  class="issueDesc">'.$value['issue_disc'].'</td>';
					echo '<td  class="totalAgents">'.$value['total_agents'].'</td>';
					echo '<td  class="agentImpacted">'.$value['agent_impacted'].'</td>';
					echo '<td  class="reqEmpId">'.$value['requester_empId'].'</td>';
					echo '<td  class="reqName">'.$value['requester_name'].'</td>';
					echo '<td  class="reqEmail">'.$value['requester_email'].'</td>';
					echo '<td  class="reqMobile">'.$value['requester_mobile'].'</td>';	
					echo '<td  class="handEmpId">'.$value['handler_empId'].'</td>';
					echo '<td  class="handName">'.$value['handler_name'].'</td>';
					echo '<td  class="handEmail">'.$value['handler_email'].'</td>';
					echo '<td  class="handMobile">'.$value['handler_mobile'].'</td>';
					echo '<td  class="InProgRemark">'.$value['inprogress_remark'].'</td>';
					echo '<td  class="InProgDate">'.$value['inprogress_date'].'</td>';
					echo '<td  class="closingRemark">'.$value['closing_remark'].'</td>';
					echo '<td  class="closingDate">'.$value['closing_date'].'</td>';
					echo '<td  class="rcaText">'.$value['rca_text'].'</td>';
					echo '<td  class="rcaAttach">'.$value['rca_attachement'].'</td>';
					echo '<td  class="rcaDate">'.$value['rca_date'].'</td>';
					echo '<td  class="createdDate">'.$value['created_date'].'</td>';
					echo '<td class="handle" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="'.$value['id'].'"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
					/* echo '<td class="delete_verifier" ><i class="material-icons delete_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return DeleteVerifier(this);" id="'.$value['id'].'"   data-position="left" data-tooltip="Delete">ohrm_delete</i> </td>'; */
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


// This code for cancel button trigger click and also for model close
$('#btn_Verifier_Can ,#btn_Verifier_CanRCA').on('click', function() {
      //  $('#txt_EmployeeID').val('');
      //  $('#txt_Verifier_id').val('');	
      //  $('#txt_EmployeeName').val('');	
       // $('#txt_status').val('1');	
     
      //  $('#btn_Verifier_Save').removeClass('hidden');
       // $('#btn_Verifier_Edit').addClass('hidden');
      
         
         
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

/* //On Clicke Add Btn remove The Read only Property From Employee ID.
$('ContentAdd').on('click', function(){
	$("#txt_EmployeeID").prop("readonly", false);
			}); */

// This code for submit button and form submit for all model field validation if this contain a required attributes also has some manual code validation to if needed.
    /* 
$('#btn_Verifier_Edit').on('click', function(){
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
      		$('#alert_message').delay(50000).fadeOut("slow");
      		
      		$(function(){ toastr.error(alert_msg); });
			return false;
		}
       
    });
     */




// This code for trigger edit on all data table also trigger model open on a Model ID
    
function EditData(el)
{
	
	
	
	 //Get Value Of The Row On Which Clicker
		var tr = $(el).closest('tr');
		
		var rowId = tr.find('.id').text();
        var ticketId = tr.find('.ticketId').text();
        var issLocation = tr.find('.locationIssue').text();
        var currStatus = tr.find('.issStatus').text();
        var handlerName = tr.find('.handName').text();
        var handlerEmail = tr.find('.handEmail').text(); 
		var requesterName = tr.find('.reqName').text();
        var requesterEmail = tr.find('.reqEmail').text();
        var issTat = tr.find('.tat').text();
        var issClient = tr.find('.client').text();
		
			
       
       
      
      //Set Value To the Modal data to Both RCA and Status Model
	//STATUS MODEL
        $('#rowId').val(rowId);
        $('#ticketIdIssue').val(ticketId);
        $('#issLocation').val(issLocation);
        $('#currStatus').val(currStatus);
        $('#handlerEmail').val(handlerEmail);
        $('#handlerName').val(handlerName);
        $('#requesterName').val(requesterName);
        $('#requesterEmail').val(requesterEmail);
        $('#issTat').val(issTat);
        $('#issClient').val(issClient);
        
		
	////RCA MODEL
		 $('#rowIdRCA').val(rowId);
        $('#ticketIdIssueRCA').val(ticketId);
        $('#issLocationRCA').val(issLocation);
        $('#currStatusRCA').val(currStatus);
        $('#handlerEmailRCA').val(handlerEmail);
        $('#handlerNameRCA').val(handlerName);
        $('#requesterNameRCA').val(requesterName);
        $('#requesterEmailRCA').val(requesterEmail);
        $('#issTatRCA').val(issTat);
        $('#issClientRCA').val(issClient);
       
        
     //  $('#btn_Verifier_Save').addClass('hidden');
       // $('#btn_Verifier_Edit').removeClass('hidden');
        //$('#btn_Verifier_Can').removeClass('hidden');
        
		if(currStatus != 'Close'){
			$('#myModal_content').modal('open');
		  
		}else{
			
			$('#myModal_contentRCA').modal('open');
			
			 
		} 
		
			/* $("#myModal_contentRCA input,#myModal_contentRCA textarea").each(function(index, element) {
        	
	         if($(element).val().length > 0) {
	           $(this).siblings('label, i').addClass('active');
	         }
	         else
	         {
			 	$(this).siblings('label, i').removeClass('active');
			 }
			        
		});
		$('select').formSelect();  */
		
        
}




$('#btn_Verifier_Edit').on('click', function(){

		var rowId = $('#rowId').val();
        var ticketId = $('#ticketIdIssue').val();
        var issLocation = $('#issLocation').val();
        var currStatus = $('#currStatus').val();
        var handlerName = $('#handlerName').val();
        var handlerEmail = $('#handlerEmail').val();
		var requesterName = $('#requesterName').val();
        var requesterEmail = $('#requesterEmail').val();
        var issTat = $('#issTat').val();
        var issClient = $('#issClient').val();
        var handlerRemark = $('#remark').val();
        var ExtTat = $('#extTat').val();
        var newStatus = $('#newStatus').val();
		
		
	
	
	
	/* var handlerEmpId=<?php echo "'".$_SESSION["__user_logid"]."'"; ?>;
	var handlerName=<?php echo "'".$_SESSION["__user_Name"]."'"; ?>; */
	
	
	var isError = 0;
	var remarkError= '';
	var ExtError= '';
	var statusError= '';
	

	
	
	
	//Validating status
	if(isValid(newStatus)){
		isError = 1;
		statusError = 'Please select new status first.';
	}
	//Validating Remark
	if(isValid(handlerRemark)){
		isError = 1;
		remarkError = 'Please provide remark first.';
	}
	
	//Validating Exented TAT
	//Set EXt Tat To zero if not filled.
	if(isValid(ExtTat)){
		ExtTat = 0;
	}
	

	//
	if(isError == 1){
		alert(statusError  + '\n' + remarkError );
		return false;
	}else{

		$('#myModal_content').modal('close');
		
		//Show Loader
		$("#overlay").fadeIn(300);

		 $.ajax({
		   type: 'POST',
		   url: '../Controller/ithdk_submit_new_status_web.php',   
		 data: {"rowId": rowId, "currStatus": currStatus, "newStatus": newStatus,"client": issClient, "remark": handlerRemark, "handlerEmail": handlerEmail, "requesterEmail": requesterEmail, "requesterName": requesterName, "handlerName": handlerName, "ticketId": ticketId,"location": issLocation, "tat": issTat,"extTat": ExtTat, "appkey": "submitStatus"}
		}).done(function(data) {
			
			//Hide Loader
			setTimeout(function(){
			$("#overlay").fadeOut(300);
			},500);
			
			var obj = JSON.parse(data);
			
			if(obj['status'] == 1){

				toastr.success(obj['msg']);
				//Redirect to same Page.
				setTimeout(function(){ 
					window.location.replace(window.location.href); }, 2000);

			}else{
				toastr.error(obj['msg']);
				return false;
			}
			
			//consol.log(data);
			
			
		  // Optionally alert the user of success here...
		}).fail(function(data) {
		  // Optionally alert the user of an error here...
		  alert('Unable to raise issue, try again later.');
		});
			
	}
});




$('#btn_Verifier_EditRCA').on('click', function(){

		var rowId = $('#rowIdRCA').val();
        var ticketId = $('#ticketIdIssueRCA').val();
        var issLocation = $('#issLocationRCA').val();
        var currStatus = $('#currStatusRCA').val();
        var handlerName = $('#handlerNameRCA').val();
        var handlerEmail = $('#handlerEmailRCA').val();
		var requesterName = $('#requesterNameRCA').val();
        var requesterEmail = $('#requesterEmailRCA').val();
        var issTat = $('#issTatRCA').val();
        var issClient = $('#issClientRCA').val();
        var rcaText = $('#rcaText').val();
		 var handlerEmpId=<?php echo "'".$_SESSION["__user_logid"]."'"; ?>;

	var isError = 0;
	var rcaTextError= '';
	

	
	//Validating RCA Text
	if(isValid(rcaText)){
		isError = 1;
		rcaTextError = 'Please provide rca text first.';
	}
	

	//
	if(isError == 1){
		alert(rcaTextError);
		return false;
	}else{
		
		
		//alert(dd); 
		//close The Modal
		$('#myModal_contentRCA').modal('close');
		
		//Show Loader
			$("#overlay").fadeIn(300);
			
			//Check is File Attached
			var isAttached = 'no';
			var formData = new FormData();
			
			if( $('#fileToUpload')[0].files[0])
			{
				isAttached = 'yes';
				formData.append('attachFile', $('#fileToUpload')[0].files[0]);
			}
			
			
			formData.append('rcaText', rcaText);
			formData.append('isAttachement', isAttached);
			formData.append('rowId', rowId);
			formData.append('handlerEmpId', handlerEmpId);
			formData.append('appkey', 'submitRca');
			formData.append('handlerEmail', handlerEmail);
			formData.append('requesterEmail', requesterEmail);
			formData.append('requesterName', requesterName);
			formData.append('handlerName', handlerName);
			formData.append('ticketId', ticketId);
			formData.append('location', issLocation);
			formData.append('client', issClient);
			
		
		 $.ajax({
		   type: 'POST',
		    data : formData,
			processData: false,  // tell jQuery not to process the data
			contentType: false,
		   url: '../Controller/ithdk_submit_rca_text_and_attachment_web_service.php',   
		
		}).done(function(data) {
			
			//Hide Loader
			setTimeout(function(){
			$("#overlay").fadeOut(300);
			},500);
			
			var obj = JSON.parse(data);
			
			if(obj['status'] == 1){

				toastr.success(obj['msg']);
				//Redirect to same Page.
				setTimeout(function(){ 
					window.location.replace(window.location.href); }, 2000);

			}else{
				toastr.error(obj['msg']);
				return false;
			}
				
			
		  // Optionally alert the user of success here...
		}).fail(function(data) {
		  // Optionally alert the user of an error here...
		  alert('Unable to raise issue, try again later.');
		});
			
	}
	

});



function isValid(str) {
    return (!str || str.length === 0 || str== 'na' );
}



function onChangeStatus(dd){
	
	  var newStatus = $('#newStatus').val();
	  
	  if(newStatus == 'InProgress'){
		
		document.getElementById('ExtTatDiv').style.display = "block";
	  }else{
		  document.getElementById('ExtTatDiv').style.display = "none";
	  }
	
}



/* 
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
} */

	
	
	
	
function isNumber(evt){
var iKeyCode = (evt.which) ? evt.which : evt.keyCode
if(iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
    return false;
    return true;
}    


//On Click SAve Status Btn




</script>
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>