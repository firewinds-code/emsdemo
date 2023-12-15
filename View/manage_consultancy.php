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
/*if($_SESSION['__user_type']!='ADMINISTRATOR')
{
	die("access denied ! It seems like you try for a wrong action.");
			exit();
}*/
// Global variable used in Page Cycle
$alert_msg ='';

//print_r($_POST);
//Array ( [txt_ref_Type] => 1 [txt_empmap_client] => 1 [txt_empmap_process] => Administration [txt_empmap_subprocess] => Administration [txt_payout] => 3433 [txt_tenure] => 23 [hid_ref_ID] => [btn_ref_Save] => )
if(isset($_POST['btn_ref_Save']))
{
	$consultancy_id=(isset($_POST['txt_ref_Type'])? $_POST['txt_ref_Type'] : null);
	$txt_empmap_subprocess=(isset($_POST['txt_empmap_subprocess'])? $_POST['txt_empmap_subprocess'] : null);
	$txt_payout=(isset($_POST['txt_payout'])? $_POST['txt_payout'] : null);
	$txt_tenure=(isset($_POST['txt_tenure'])? $_POST['txt_tenure'] : null);
	$txt_location=(isset($_POST['txt_location'])? $_POST['txt_location'] : null);
	$txt_startdate=(isset($_POST['txt_startdate'])? $_POST['txt_startdate'] : null);
	$txt_enddate=(isset($_POST['txt_enddate'])? $_POST['txt_enddate'] : null);
	$txt_status=(isset($_POST['txt_status'])? $_POST['txt_status'] : null);
	//$txt_empmap_client=(isset($_POST['txt_empmap_client'])? $_POST['txt_empmap_client'] : null);
	//$txt_empmap_process=(isset($_POST['txt_empmap_process'])? $_POST['txt_empmap_process'] : null);
	$createdBy=$_SESSION['__user_logid'];
	 $Insert='call add_manage_consultancy("'.$consultancy_id.'","'.$txt_empmap_subprocess.'","'.$txt_payout.'","'.$txt_tenure.'","'.$createdBy.'","'.$txt_location.'","'.$txt_startdate.'","'.$txt_enddate.'","'.$txt_status.'")';
	$myDB=new MysqliDb();
	
	$myDB->rawQuery($Insert);
	$mysql_error = $myDB->getLastError();
	$rowCount = $myDB->count;
	if(empty($mysql_error))
	{
		if($rowCount > 0 )
		{
			echo "<script>$(function(){ toastr.success('Added Successfully.'); }); </script>";
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Data already exists.'); }); </script>";
		}
		
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Not Added. $mysql_error'); }); </script>";		
	}
}
// Trigger Button-Edit Click Event and Perform DB Action
if(isset($_POST['btn_ref_Edit']))
{	
	$DataID=$_POST['hid_ref_ID'];
	$consultancy_id=(isset($_POST['txt_ref_Type'])? $_POST['txt_ref_Type'] : null);
	$txt_empmap_subprocess=(isset($_POST['txt_empmap_subprocess'])? $_POST['txt_empmap_subprocess'] : null);
	$txt_payout=(isset($_POST['txt_payout'])? $_POST['txt_payout'] : null);
	$txt_tenure=(isset($_POST['txt_tenure'])? $_POST['txt_tenure'] : null);
	$txt_location=(isset($_POST['txt_location'])? $_POST['txt_location'] : null);
	$txt_startdate=(isset($_POST['txt_startdate'])? $_POST['txt_startdate'] : null);
	$txt_enddate=(isset($_POST['txt_enddate'])? $_POST['txt_enddate'] : null);
	$txt_status=(isset($_POST['txt_status'])? $_POST['txt_status'] : null);
	$createBy=$_SESSION['__user_logid'];
	
	$ModifiedBy=$_SESSION['__user_logid'];
	$Update='call save_manage_consultancy("'.$consultancy_id.'","'.$txt_empmap_subprocess.'","'.$txt_payout.'","'.$txt_tenure.'","'.$ModifiedBy.'","'.$DataID.'","'.$txt_location.'","'.$txt_startdate.'","'.$txt_enddate.'","'.$txt_status.'")';
	
	
	$myDB=new MysqliDb();
	if(!empty($DataID)||$DataID!='')
	{
		$result=$myDB->rawQuery($Update);
			$rowCount = $myDB->count;
		$mysql_error = $myDB->getLastError();
		
		if($rowCount > 0 )
		{
			echo "<script>$(function(){ toastr.success('Updated Successfully.'); }); </script>";
			$_Comp=$_Hod=$_Name='';
			$_Hod="NA";
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Data already exists'); }); </script>";
		}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First.If Not Resolved then contact to technical person.'); }); </script>";
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
						        
						        ,'pageLength'
						        
						    ]
				       
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
<span id="PageTittle_span" class="hidden">Manage Consultancy</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Manage Consultancy <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Reference"><i class="material-icons" style="margin-top: 8;">add</i></a></h4>				

        <!-- Form container if any -->
		<div class="schema-form-section row" >
		
		<!--Form element model popup start-->
		  <div id="myModal_content" class="modal">
		   <!-- Modal content-->
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4">Manage Consultancy Details</h4>
		        <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
		        <div class="col s12 m12">
			     
			     <div class="input-field col s6 m6 ">
				    <select class="form-control"  id="txt_ref_Type" name="txt_ref_Type" required>
				    	<option  value="NA">Select Consultancy</option>
				     	<?php		
							$sqlBy="select ConsultancyName,id from consultancy_master where status='1' order by ConsultancyName";	 
							$myDB=new MysqliDb();
							$resultBy=$myDB->query($sqlBy);
							if($resultBy){													
								$selec='';	
								$Consultancy_id='';
								foreach($resultBy as $key=>$value)
								{
									if($value['id']==$Consultancy_id)	
									{
										$selec=' selected ';
									}
									else
									{
										$selec='';
									}														
									echo '<option value="'.$value['id'].'" '.$selec.' >'.$value['ConsultancyName'].'</option>';
								}

							}
							
				      	?>	
		            </select>
				    
				    <label for="txt_ref_Type" class="active-drop-down active" >Consultancy *</label>
			     </div>
			    
			    <div class="input-field col s6 m6">
			            <select id="txt_location" name="txt_location" required">
			            	<option value="NA">----Select----</option>	
					      	<?php		
							$sqlBy ='select id,location from location_master;'; 
							$myDB=new MysqliDb();
							$resultBy=$myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if(empty($mysql_error)){													
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
			     
		            <select id="txt_empmap_subprocess" name="txt_empmap_subprocess"   required>
	     				<!--<option  value="NA">Select Subprocess</option>	
		     		 <?php
						if($resultBy){
							
							$selected='';													
							foreach($resultBy as $key=>$value){
								if($cm_id==$value['cm_id'])
								{
									$selected='selected';
								}
								else
								{
									$selected='';
								}
								echo '<option id="'.$value['client_id'].'_'.$value['sub_process'].'" value="'.$value['cm_id'].'" '.$selected.'>'.$value['Client_info'].'</option>';
							}

						}
			      ?>-->
		     			
	     			</select>
	     			 <label for="txt_empmap_subprocess" class="active-drop-down active">Process * </label>	  
		            
			     </div>
			    
			   
			     <div class="input-field col s6 m6">
		            <input type="text" class="form-control" id="txt_payout" name="txt_payout" maxlength="5"  required />
		            <label for="txt_payout">Payout *</label>
			     </div>	
			     <div class="input-field col s6 m6">
		            <input type="text" class="form-control" id="txt_tenure" name="txt_tenure" maxlength="3"  required />
		            <label for="txt_tenure">Tenure *</label>
			    </div>
			    <div class="input-field col s6 m6">
		            <input type="text" class="datepicker" id="txt_startdate" name="txt_startdate" readonly="true" required />
		            <label for="txt_startdate">Start Date *</label>
			    </div>
			    <div class="input-field col s6 m6">
		            <input type="text" class="datepicker" id="txt_enddate" name="txt_enddate" readonly="true" required />
		            <label for="txt_enddate">End Date *</label>
			    </div>
			    <div class="input-field col s6 m6">
		            <select id="txt_status" name="txt_status" required">
			            	<option value="1">Active</option>	
					      	<option value="2">InActive</option>	
			            </select>
			          <label for="txt_status" class="active-drop-down active">Status</label>
			    </div>
			    </div>
			      <div class="input-field col s12 m12 right-align">
				    <input type="hidden" class="form-control hidden" id="hid_ref_ID"  name="hid_ref_ID"/>
				    <button type="submit" name="btn_ref_Save" id="btn_ref_Save" class="btn waves-effect waves-green ">Add</button>
				    <button type="submit" name="btn_ref_Edit" id="btn_ref_Edit" class="btn waves-effect waves-green hidden">Save</button>
				    <button type="button" name="btn_ref_Can" id="btn_ref_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
				  </div>
			    </div>
             </div>
		   </div>  
	     <!--Form element model popup End-->
		   
           <!--Reprot / Data Table start --> 
			    <div id="pnlTable">
			    <?php 
					
					$sqlConnect = 'select a.id,a.consultancy_id,a.cm_id,a.payout,a.tenure,a.process,a.client_id,b.ConsultancyName,concat(d.client_name,"|",c.process,"|",c.sub_process) as Process,c.sub_process,c.location as locid,a.start_date,a.end_date,case when a.Active=1 then "Active" else "InActive" end as status,a.Active,l.location from manage_consultancy a inner join  consultancy_master b on a.consultancy_id=b.id inner join new_client_master c on a.cm_id=c.cm_id join client_master d on c.client_name = d.client_id join location_master l on c.location=l.id order by status;';
					$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					if($result){?>
						
			   			 <div class="panel panel-default" style="margin-top: 10px;">
						  <div class="panel-body"  >																																					<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						            
						            <th class="hidden">ID</th>
						            <th class="hidden">locid</th>
						            <th class="hidden">Active</th>
						            <th>Consultancy Name</th>						            
						            <th>Process</th>						            
						            <th>Location</th>						            
						            <th>Payout</th>
						            <th>Tenure</th>	
						            <th>Start Date</th>	
						            <th>End Date</th>	
						            <th>Status</th>	
						            <th>Action</th>	
						            
						           
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       	$count=0;
					        foreach($result as $key=>$value){
					        	$count++;
							echo '<tr>';							
							echo '<td class="ref_id hidden">'.$value['id'].'</td>';		
							echo '<td class="locid hidden">'.$value['locid'].'</td>';		
							echo '<td class="Active hidden">'.$value['Active'].'</td>';		
							echo '<td class="cname" id="'.$value['consultancy_id'].'">'.$value['ConsultancyName'].'</td>';	
							echo '<td class="cmid" id="'.$value['cm_id'].'">'.$value['Process'].'</td>';
							echo '<td class="location" id="'.$value['location'].'">'.$value['location'].'</td>';
							echo '<td class="Payout">'.$value['payout'].'</td>';	
							echo '<td class="tenure">'.$value['tenure'].'</td>';
							echo '<td class="start_date">'.$value['start_date'].'</td>';
							echo '<td class="end_date">'.$value['end_date'].'</td>';
							echo '<td class="status">'.$value['status'].'</td>';
							
													
							echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="'.$value['id'].'"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
							
							/*<td><img alt="Edit" class="imgBtn imgBtnEdit" onclick="javascript:return EditData(this);" src="../Style/images/users_edit.png" id="'.$value['ref_master']['ref_id'].'" /> <img alt="Delete" class="imgBtn" src="../Style/images/users_delete.png" id="'.$value['ref_master']['ref_id'].'" onclick="javascirpt:return ApplicationDataDelete(this);"/> </td>*/
							
							
							
							
							
							
							echo '</tr>';
							}	
							?>			       
					    </tbody>
						</table>
						  </div>
						</div>
						<?php
					 } 
					?>
				</div>
		   <!--Reprot / Data Table End -->		  
			</div>
		</div>
	</div>   
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
		$('#btn_Client_Can').trigger("click");
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
$('#btn_ref_Can').on('click', function(){
 
	    $('#hid_ref_ID').val('');
        $('#txt_ref_Type').val('NA');
        $('#txt_location').val('NA');
        $('#txt_empmap_subprocess').empty();
        
        $('#txt_payout').val('');
        $('#txt_tenure').val('');
       $('select').formSelect();
	    $('#btn_ref_Save').removeClass('hidden');
	    $('#btn_ref_Edit').addClass('hidden');
	    //$('#btn_ref_Can').addClass('hidden');
	    
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
		    
// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.   
$('#btn_ref_Edit,#btn_ref_Save').on('click', function(){
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
			return false;
		}
       
    });		    
		    

	
	  	$('#txt_tenure,#txt_payout').keydown(function(event) {
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
			} else {
				// Ensure that it is a number and stop the keypress
				if ( event.shiftKey|| (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ) ) {
					event.preventDefault();
				}
			}
		});	

		$('#txt_location').change(function(){
			getProcess($(this).val());	
		});
});		    
// This code for trigger edit on all data table also trigger model open on a Model ID
function EditData(el)
{		
        var tr = $(el).closest('tr');
        var ref_id = tr.find('.ref_id').text();		
        var cname = tr.find('.cname').attr('id');
        var cmid = tr.find('.cmid').attr('id');
        var tenure = tr.find('.tenure').text();
        var Payout = tr.find('.Payout').text();
        var locid = tr.find('.locid').text();
        var start_date = tr.find('.start_date').text();
        var end_date = tr.find('.end_date').text();
        var status = tr.find('.Active').text();
       
       
        $('#hid_ref_ID').val(ref_id);
        $('#txt_status').val(status);
        $('#txt_ref_Type').val(cname);
        $('#txt_location').val(locid);
        $('#txt_empmap_subprocess').empty();
        
        $.ajax({
		  url: <?php echo '"'.URL.'"';?>+"Controller/getProcessNameByLocation.php?id="+locid
		}).done(function(data) { // data what is sent back by the php page
			$('#txt_empmap_subprocess').html(data);
			$('#txt_empmap_subprocess').val(cmid);
			$('select').formSelect();	
		});
		
        $('#txt_payout').val(Payout);
        $('#txt_tenure').val(tenure);
        $('#txt_startdate').val(start_date);
        $('#txt_enddate').val(end_date);
       $('select').formSelect();
        		       
        $('#btn_ref_Save').addClass('hidden');
        $('#btn_ref_Edit').removeClass('hidden');
        //$('#btn_ref_Can').removeClass('hidden');
    
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
	
		xmlhttp.open("GET", "../Controller/deleteRef.php?ID=" + el.id, true);
		xmlhttp.send();
	}
}

	function getProcess(elid)
	{
		//alert(elid);
		$.ajax({
		  url: <?php echo '"'.URL.'"';?>+"Controller/getProcessNameByLocation.php?id="+elid
		}).done(function(data) { // data what is sent back by the php page
			$('#txt_empmap_subprocess').html(data);
			$('select').formSelect();	
		});
	}
	
	$('.datepicker').datepicker({
     dateFormat: 'yy-mm-dd'
	});
	
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>

