<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
// Global variable used in Page Cycle
$alert_msg ='';
// Trigger Button-Save Click Event and Perform DB Action
if(isset($_POST['btn_RefMaster_Save']))
{
	$_cmid=(isset($_POST['txt_Client'])? $_POST['txt_Client'] : null);
	$_RefAmt=(isset($_POST['txt_RefAmount'])? $_POST['txt_RefAmount'] : null);
	$_FPay=(isset($_POST['txt_1st_PayAmt'])? $_POST['txt_1st_PayAmt'] : null);
	$_SPay=(isset($_POST['txt_2nd_PayAmt'])? $_POST['txt_2nd_PayAmt'] : null);
	$_WinMonth=(isset($_POST['txt_WindowMonth'])? $_POST['txt_WindowMonth'] : null);
	
	$createBy=$_SESSION['__user_logid'];
	$Insert='call sp_insert_MasterRefScheme("'.$_cmid.'","'.$_RefAmt.'","'.$_FPay.'","'.$_SPay.'","'.$_WinMonth.'","'.$createBy.'")';
	$myDB=new MysqliDb();
    $myDB->rawQuery($Insert);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		
		if($myDB->count > 0)
			{
				echo "<script>$(function(){ toastr.success('Master Reference Scheme Added Successfully'); }); </script>";
			}
			else
			{
				echo "<script>$(function(){ toastr.error('Master Reference Scheme Not Added, May be Duplicate Entry Found check manualy'); }); </script>";
			}
	}
	else
	{		
		echo "<script>$(function(){ toastr.error('Master Reference Scheme not Added. Some error occured ".$mysql_error."'); }); </script>";
	}
	
}
// Trigger Button-Edit Click Event and Perform DB Action
if(isset($_POST['btn_RefMaster_Edit']))
{	
	$DataID=$_POST['hid_ID'];
	$_cm_id=(isset($_POST['txt_Client'])? $_POST['txt_Client'] : null);
	$_RefAmount=(isset($_POST['txt_RefAmount'])? $_POST['txt_RefAmount'] : null);
	$_FPay=(isset($_POST['txt_1st_PayAmt'])? $_POST['txt_1st_PayAmt'] : null);
	$_SPay=(isset($_POST['txt_2nd_PayAmt'])? $_POST['txt_2nd_PayAmt'] : null);
	$_WinMonth=(isset($_POST['txt_WindowMonth'])? $_POST['txt_WindowMonth'] : null);
	$ModifiedBy=$_SESSION['__user_logid'];
	$Update='call sp_Update_MasterRefScheme("'.$DataID.'","'.$_cm_id.'","'.$_RefAmount.'","'.$_FPay.'","'.$_SPay.'","'.$_WinMonth.'","'.$ModifiedBy.'")';
	$myDB=new MysqliDb();
	if(!empty($DataID)|| $DataID!='')
	{
		$myDB->rawQuery($Update);
		$mysql_error = $myDB->getLastError();
		if(empty($mysql_error))
		{
			if($myDB->count > 0)
			{
				echo "<script>$(function(){ toastr.success('Master Reference Scheme Updated Successfully'); }); </script>";
			}
			else
			{
				echo "<script>$(function(){ toastr.error('Master Reference Scheme Not Updated, May be Duplicate Entry Found check manualy'); }); </script>";
			}	
		}
		else
		{
			echo "<script>$(function(){ toastr.success('Master Reference Scheme Not Updated. Some error occurred'); }); </script>";
			
			//echo "<script>$(function(){ toastr.error('Master Reference Scheme Not Updated: Some error occurred...); }); </script>";
		}
	}
	else
	{		
		echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First. If Not Resolved then contact to technical person'); }); </script>";
	}	
}
?>
<script>
	$(document).ready(function(){
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
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
						        /*,'copy'*/,
						        'pageLength'
						        
						    ]
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				    });
		   	
		   	$('.buttons-copy').attr('id','buttons_copy');
		   	$('.buttons-csv').attr('id','buttons_csv');
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-pdf').attr('id','buttons_pdf');
		   	$('.buttons-print').attr('id','buttons_print');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
	
	  $('#txt_RefAmount').keyup(function () { 
	    this.value = this.value.replace(/[^0-9.]/g,'');
		
		$('#txt_1st_PayAmt').val('');
		$('#txt_2nd_PayAmt').val('');
		
	        
	});

$('#txt_WindowDay').keyup(function () { 
    this.value = this.value.replace(/[^0-9.]/g,'');
	
	
});


 $('#txt_1st_PayAmt').keyup(function () { 
    this.value = this.value.replace(/[^0-9.]/g,'');
    var amt1=$('#txt_RefAmount').val(),amt2=0;
    
    if(amt1=='NA')
    {
		$('#txt_RefAmount').val('');
	}
    
    if(parseInt($('#txt_RefAmount').val().length) == 0 || parseInt($('#txt_1st_PayAmt').val()) > parseInt($('#txt_RefAmount').val()))
    {
		this.value='';
		$('#txt_2nd_PayAmt').val('');
		alert('1st Pay Amount should be less than or equal to total pay amount');
	}
	else
	{
		amt2=$('#txt_1st_PayAmt').val();
		//alert(parseInt(amt1) - parseInt(amt2))
		//$('#txt_2nd_PayAmt').value = parseInt(amt1) - parseInt(amt2);
		$('#txt_2nd_PayAmt').val(parseInt(amt1) - parseInt(amt2));	
		//alert($('#txt_1st_PayDate').val());
	}
		
	
	
	
});
 
  $('#txt_RefAmount').focusout(function(){
 	
    if($('#txt_RefAmount').val() == 0)
	{
		$('#txt_1st_PayAmt').val(0);
		$('#txt_2nd_PayAmt').val(0);
	}
	
});


 $('#txt_1st_PayAmt').focusout(function(){
 	
 	if(parseInt($('#txt_1st_PayAmt').val()) == parseInt(0))
 	{
		alert('1st Payout not be 0');
		$('#txt_1st_PayAmt').val('');
		$('#txt_2nd_PayAmt').val(0);
	}
 	
    if($('#txt_2nd_PayAmt').val() != 0 && $('#txt_2nd_PayAmt').val() != 'NULL')
	{
		$('#div_2ndAmt').removeClass('hidden');
	}
	else
	{
		$('#div_2ndAmt').addClass('hidden');
	}
});
	
	$('#txt_WindowMonth').keyup(function () { 
    this.value = this.value.replace(/[^0-9.]/g,'');
	
	if((parseInt(this.value)) > parseInt(3))
	 {
	 	this.value='';
	 	alert('Window is Not more than 3 month')
	 }
	 else if((parseInt(this.value)) == parseInt(0))
	 {
	 	this.value='';
	 	alert('0 is not allowed in window.')
	 }
	
});	   	
		   	
	});
</script>
<div id="content" class="content" >
<span id="PageTittle_span" class="hidden">Reference Master</span>

	<div class="pim-container row" id="div_main" >
		<div class="form-div">
			 <h4>Reference Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Department"><i class="material-icons">add</i></a></h4>				
			 <div class="schema-form-section row" >
			    <div id="myModal_content" class="modal">
		  
				    <!-- Modal content-->
				    <div class="modal-content">
				      <h4 class="col s12 m12 model-h4">Manage Reference Master</h4>
				      <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
				      <div class="col s12 m12">
				       <div class="input-field col s6 m6">
					      <select id="txt_location" name="txt_location" required>
							<option value="NA">----Select----</option>	
						    <?php 
							$location ='select id,location from location_master order by location;'; 
							$myDB=new MysqliDb();	
							$resultBy=$myDB->rawQuery($location);
							$mysql_error = $myDB->getLastError();
							if(empty($mysql_error))
							{													
								foreach($resultBy as $key=>$value)
									{							
									   echo '<option value="'.$value['id'].'"  >'.$value['location'].'</option>';
									}
							}
							?>
							</select>
							<label for="txt_location" class="active-drop-down active">Location</label> 
					    </div>
				        <div class="input-field col s6 m6">
					      <select id="txt_Client" name="txt_Client" required>
							<option value="NA">----Select----</option>	
						    <!--<?php
							$sqlBy ='select distinct concat(t2.client_name,"|",t1.process,"|",t1.sub_process) as Process,t1.cm_id from new_client_master t1 join client_master t2 on t1.client_name = t2.client_id left join client_status_master t3 on t1.cm_id=t3.cm_id where t3.cm_id is null order by process'; 
							$myDB=new MysqliDb();	
							$resultBy=$myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if(empty($mysql_error))
							{													
								foreach($resultBy as $key=>$value)
									{							
									   echo '<option value="'.$value['cm_id'].'"  >'.$value['Process'].'</option>';
									}
							}
							?>-->
							</select>
							<label for="txt_Client" class="active-drop-down active">Process</label> 
					    </div>
					     
					     <div class="input-field col s6 m6">
					      <input type="text" id="txt_RefAmount" name="txt_RefAmount" maxlength="4" required/>
						  <label for="txt_RefAmount">Reference Amount</label> 
					    </div>
					    
				      </div>
				      
				<div class="col s12 m12">
			    <div class="input-field col s3 m3">
		            <input type="text" id="txt_1st_PayAmt" name="txt_1st_PayAmt" maxlength="4" required />
		            <label for="txt_1st_PayAmt">1st Pay Amount</label>
			    </div>
			    
			     <div class="input-field col s3 m3">
		            <input type="text" id="txt_2nd_PayAmt" name="txt_2nd_PayAmt" maxlength="4" placeholder="0" readonly="true" required />
		            <label for="txt_2nd_PayAmt">2nd Pay Amount</label>
			    </div>
			    
			    
			    <div class="input-field col s6 m6">
			    	<input type="text" id="txt_WindowMonth" name="txt_WindowMonth" maxlength="1" required />
		            <label for="txt_WindowMonth">Window Month</label>
		            
			     			
			    </div>
			    
			    </div>
				      
				      <div class="input-field col s12 m12 right-align">
					
				    	<input type="hidden" class="form-control hidden" id="hid_ID"  name="hid_ID"/>
					    <button type="submit" name="btn_RefMaster_Save" id="btn_RefMaster_Save" class="btn waves-effect waves-green">Add</button>
					    <button type="submit" name="btn_RefMaster_Edit" id="btn_RefMaster_Edit" class="btn waves-effect waves-green hidden">Save</button>
					    <button type="button" name="btn_RefMaster_Can" id="btn_RefMaster_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
					    
         		</div>
         		</div>
				    </div>
				</div>
			    
			  <div id="pnlTable">
			    <?php 
					//$sqlConnect = array('table' => 'dept_master','fields' => 'dept_id,dept_name','condition' =>"1"); 
					    $sqlConnect = "select t2.id,t2.cm_id,t1.process,t1.sub_process,t2.amount,t2.1st_pay,t2.2nd_pay,t2.window_month,t2.createdby,t2.createdon,t1.location,t3.client_name,t4.location as locname  from new_client_master t1 join client_ref_master t2 on t1.cm_id= t2.cm_id join client_master t3 on t1.client_name= t3.client_id join location_master t4 on t4.id=t1.location;"; 
						$myDB=new MysqliDb();
						$result=$myDB->rawQuery($sqlConnect);
						$mysql_error = $myDB->getLastError();
						if(empty($mysql_error)){?>
                         <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						            <th class="hidden">ID</th>
						            <th class="hidden">cmid</th>
						            <th class="hidden">location</th>
						            <th class="hidden">client</th>
						            <th>Process</th>						            
						            <th>Sub Process</th>
						            <th>Location</th>
						            <th>Amount</th>
						            <th>1st Pay</th>
						            <th>2nd Pay</th>
						            <th>Window Month</th>
						            <th>Created By</th>
						            <th>Created On</th>
						            <th>Manage</th>
						        </tr>
						    </thead>
						    <tbody>					        
						       <?php
						        foreach($result as $key=>$value){
								echo '<tr>';
								echo '<td class="id hidden">'.$value['id'].'</td>';
								echo '<td class="cm_id hidden">'.$value['cm_id'].'</td>';							
								echo '<td class="loc hidden">'.$value['location'].'</td>';							
								echo '<td class="client hidden">'.$value['client_name'].'</td>';							
								echo '<td class="process">'.$value['process'].'</td>';
								echo '<td class="sub_process">'.$value['sub_process'].'</td>';								
								echo '<td class="locname">'.$value['locname'].'</td>';								
								echo '<td class="amount">'.$value['amount'].'</td>';
								echo '<td class="1st_pay">'.$value['1st_pay'].'</td>';
								echo '<td class="2nd_pay">'.$value['2nd_pay'].'</td>';
								echo '<td class="window_month">'.$value['window_month'].'</td>';
								echo '<td class="createdby">'.$value['createdby'].'</td>';
								echo '<td class="createdon">'.$value['createdon'].'</td>';
								echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="'.$value['id'].'"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
								//<i class="material-icons  imgBtn imgBtnEdit tooltipped delete_item" id="'.$value['dept_id'].'"  onclick="javascirpt:return ApplicationDataDelete(this);" data-position="left" data-tooltip="Delete">ohrm_delete</i>
								
								?>
				
			<?php
			
								echo '</tr>';
								}	
								?>			       
						    </tbody>
						</table>
						<?php }  ?>
				</div>
			</div> 
		</div>
	</div>    
<!--Content Div for all Page End -->  
</div>

<script>
	
$(document).ready(function(){
	//Model Assigned and initiation code on document load
	$('.modal').modal({
			onOpenStart:function(elm)
			{
				
				
			},
			onCloseEnd:function(elm)
			{
				$('#btn_RefMaster_Can').trigger("click");
			}
		});
	// This code for cancel button trigger click and also for model close
     $('#btn_RefMaster_Can').on('click', function(){
        $('#txt_Client').val('NA');
        //alert('can');
        //$('#txt_Client').val('NA');
        $('#txt_RefAmount').val('');
        $('#txt_1st_PayAmt').val('');
        $('#txt_2nd_PayAmt').val('');
        $('#txt_WindowMonth').val('');
        $('#hid_ID').val('');
        $('#btn_RefMaster_Save').removeClass('hidden');
        $('#btn_RefMaster_Edit').addClass('hidden');
        $('select').formSelect();
        //$('#btn_RefMaster_Can').addClass('hidden');
        
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
    });
    $('#txt_location').change(function(){
    	//alert('kavya');
    	var tval = $(this).val();
    	//alert(tval);
    	//alert(<?php echo '"'.URL.'"';?>+"Controller/getProcessNameByLocation.php?id="+tval);
    	$.ajax({
		  url: <?php echo '"'.URL.'"';?>+"Controller/getProcessNameByLocation.php?id="+tval
		}).done(function(data) { // data what is sent back by the php page
			$('#txt_Client').html(data);
			$('select').formSelect();	
		});
		});
    
    // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	$('#btn_RefMaster_Edit,#btn_RefMaster_Save').on('click', function(){
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
    	// This code for remove error span from all element contain .has-error class on listed events
	
    
    
});


// This code for trigger edit on all data table also trigger model open on a Model ID
   	
	function EditData(el)
	{
		var tr = $(el).closest('tr');
		var ID = tr.find('.ID').text();
        var cm_id = tr.find('.cm_id').text();
        var loc = tr.find('.loc').text();
        var client = tr.find('.client').text();
        var process = tr.find('.process').text();
        var subprocess = tr.find('.sub_process').text();
        var amount = tr.find('.amount').text();
        var fpay = tr.find('.1st_pay').text();
        var spay = tr.find('.2nd_pay').text();
        var wmonth = tr.find('.window_month').text();
        //alert(cm_id);
        var process=client+'|'+process+'|'+subprocess;
        //alert(process);
        $('#txt_Client').empty();
    
        $('#txt_Client')
         .append($("<option></option>")
                    .attr("value", cm_id)
                    .text(process));
                                  
        $('#hid_ID').val(ID);
        $('#txt_location').val(loc);
        $('#txt_location').attr('disabled','disabled');
        $('#txt_Client').val(cm_id);
        //$('#txt_Client').val(process);	       
        //$('#txt_Client').val(subprocess);
        $('#txt_RefAmount').val(amount);
        $('#txt_1st_PayAmt').val(fpay);
        $('#txt_2nd_PayAmt').val(spay);
        $('#txt_WindowMonth').val(wmonth);
        
        $('select').formSelect();
        
        $('#btn_RefMaster_Save').addClass('hidden');
        $('#btn_RefMaster_Edit').removeClass('hidden');
        //$('#btn_RefMaster_Can').removeClass('hidden');
        $('#myModal_content').modal('open');
        $("#myModal_content input,#myModal_content textarea").each(function(index, element)
        {
	         if($(element).val().length > 0)
	         {
	           $(this).siblings('label, i').addClass('active');
	         }
	         else
	         {
			 	$(this).siblings('label, i').removeClass('active');
			 }
			        
		});
	}

// This code for trigger del*t*
	
	function ApplicationDataDelete(el)
	{
		var currentUrl = window.location.href;
		var Cnfm=confirm("Do You Want To Delete This ");
		if(Cnfm)
		{
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
					alert(Resp);
					window.location.href = currentUrl;
					
				
			
				}
			}
		
			xmlhttp.open("GET", "../Controller/DeleteDepartment.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>