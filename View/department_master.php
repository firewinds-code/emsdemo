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
if(isset($_POST['btn_Department_Save']))
{
	$_Name=(isset($_POST['txt_Department_Name'])? $_POST['txt_Department_Name'] : null);
	$createBy=$_SESSION['__user_logid'];
	$Insert='call add_department("'.$_Name.'","'.$createBy.'")';
	$myDB=new MysqliDb();
    $myDB->rawQuery($Insert);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		
		echo "<script>$(function(){ toastr.success('Department Added Successfully'); }); </script>";
	}
	else
	{		
		echo "<script>$(function(){ toastr.error('Department not Added ".$mysql_error."'); }); </script>";
	}
	
}
// Trigger Button-Edit Click Event and Perform DB Action
if(isset($_POST['btn_Department_Edit']))
{	
	$DataID=$_POST['hid_Department_ID'];
	$_Name=(isset($_POST['txt_Department_Name'])? $_POST['txt_Department_Name'] : null);
	$ModifiedBy=$_SESSION['__user_logid'];
	$Update='call save_department("'.$_Name.'","'.$ModifiedBy.'","'.$DataID.'")';
	$myDB=new MysqliDb();
	if(!empty($DataID)|| $DataID!='')
	{
		$myDB->rawQuery($Update);
		$mysql_error = $myDB->getLastError();
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Updated Successfully'); }); </script>";
			$_Comp=$_Hod=$_Name='';
			$_Hod="NA";
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Department not updated ".$mysql_error."'); }); </script>";
		}
	}
	else
	{		
		echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First :: (If Not Resolved then contact to technical person)'); }); </script>";
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
	});
</script>
<div id="content" class="content" >
<span id="PageTittle_span" class="hidden">Department Master</span>

	<div class="pim-container row" id="div_main" >
		<div class="form-div">
			 <h4>Department Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Department"><i class="material-icons">add</i></a></h4>				
			 <div class="schema-form-section row" >
			    <div id="myModal_content" class="modal">
		  
				    <!-- Modal content-->
				    <div class="modal-content">
				      <h4 class="col s12 m12 model-h4">Manage Department</h4>
				      
				      <div class="modal-body">
				        <div class="input-field col s6 m6">
					      <input type="text" id="txt_Department_Name" name="txt_Department_Name" required/>
						  <label for="txt_Department_Name">Department Name</label> 
					    </div>
					     <div class="input-field col s6 m6 right-align">
					    	<input type="hidden" class="form-control hidden" id="hid_Department_ID"  name="hid_Department_ID"/>
						    <button type="submit" name="btn_Department_Save" id="btn_Department_Save" class="btn waves-effect waves-green">Add</button>
						    <button type="submit" name="btn_Department_Edit" id="btn_Department_Edit" class="btn waves-effect waves-green hidden">Save</button>
						    <button type="button" name="btn_Department_Can" id="btn_Department_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
					    </div>
				      </div>
				    </div>
				</div>
			    
			  <div id="pnlTable">
			    <?php 
					//$sqlConnect = array('table' => 'dept_master','fields' => 'dept_id,dept_name','condition' =>"1"); 
					    $sqlConnect = "select dept_id,dept_name from dept_master"; 
						$myDB=new MysqliDb();
						$result=$myDB->rawQuery($sqlConnect);
						$mysql_error = $myDB->getLastError();
						if(empty($mysql_error)){?>
                         <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						            <th>Department ID</th>
						            <th>Department Name</th>						            
						            <th>Manage Department</th>
						        </tr>
						    </thead>
						    <tbody>					        
						       <?php
						        foreach($result as $key=>$value){
								echo '<tr>';							
								echo '<td class="dept_id">'.$value['dept_id'].'</td>';
								echo '<td class="dept_name">'.$value['dept_name'].'</td>';							
								echo '<td class="manage_item" >
								<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="'.$value['dept_id'].'"   data-position="left" data-tooltip="Edit">ohrm_edit</i></td>';
								/*<i class="material-icons  imgBtn imgBtnEdit tooltipped delete_item" id="'.$value['dept_id'].'"  onclick="javascirpt:return ApplicationDataDelete(this);" data-position="left" data-tooltip="Delete">ohrm_delete</i>*/ 
								
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
				$('#btn_Department_Can').trigger("click");
			}
		});
	// This code for cancel button trigger click and also for model close
     $('#btn_Department_Can').on('click', function(){
        $('#txt_Department_Name').val('');
        $('#hid_Department_ID').val('');
        $('#btn_Department_Save').removeClass('hidden');
        $('#btn_Department_Edit').addClass('hidden');
        //$('#btn_Department_Can').addClass('hidden');
        
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
    
    // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	$('#btn_Department_Edit,#btn_Department_Save').on('click', function(){
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
        var dept_id = tr.find('.dept_id').text();
        var dept_name = tr.find('.dept_name').text();
        $('#hid_Department_ID').val(dept_id);
        $('#txt_Department_Name').val(dept_name);		       
        $('#btn_Department_Save').addClass('hidden');
        $('#btn_Department_Edit').removeClass('hidden');
        //$('#btn_Department_Can').removeClass('hidden');
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