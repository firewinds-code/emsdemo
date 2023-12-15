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
$ceatedBy=$_SESSION['__user_logid'];
$date=date('Y-m-d H-i-s');
//print_r($_SESSION);
$clientID=$module_name=$searchBy=$id=$ddvalue='';
$classvarr="'.byID'";
if(isset($_GET['id']) && $_GET['id']!="")
{
	$id=$_GET['id'];
} 
if(isset($_POST['Alias'],$_POST['parameter']) and trim($_POST['Alias'])!="" and trim($_POST['parameter'])!="" and $_POST['id']=='' )
{	
		$Alias=trim(addslashes($_POST['Alias']));
		$parameter=trim(addslashes($_POST['parameter']));
		$type=$_POST['ddType'];
		$myDB=new MysqliDb();		
		if($Alias!=""  && $parameter!="" ){
			$select_query="select id from ctrctpram where Alias='".$Alias."' and parameters='".$parameter."'  ";			
			if($type=='DropDown'){
				$ddvalue=addslashes($_POST['ddval']);
			}
			$resultBy=$myDB->rawQuery($select_query);
			$mysql_error = $myDB->getLastError();
			if($myDB->count > 0)
			{
				echo "<script>$(function(){ toastr.info('Duplicate entry not allowed'); }); </script>";
			}
			else  
			{
				
				$insertQuery="call add_ctrctpram('".$parameter."','".$Alias."','".$type."','".$ceatedBy."','".$ddvalue."')";
				$resultBy=$myDB->rawQuery($insertQuery);
				echo "<script>$(function(){ toastr.success('Parameter Added Successfully'); }); </script>";
			} 
	}else{
		echo "<script>$(function(){ toastr.error('Please enter all fields'); }); </script>";
	}
}
//print_r($_POST);
if(isset($_POST['savemodule']) && $_POST['id']!="")
{  
	$Alias=trim(addslashes($_POST['Alias']));
	$parameter=trim(addslashes($_POST['parameter']));
	$date=DATE('Y-m-d H:i:s');
	$Status=$_POST['Status'];
	$type=$_POST['ddType'];
	$id=$_POST['id'];
	$myDB=new MysqliDb();				
	if($Alias!=""  && $parameter!="" ){
		  $updateQuery="Update  ctrctpram set parameters='".$parameter."',Alias='".$Alias."',Status='".$Status."',updatedby='".$ceatedBy."',updatedOn='".$date."',ddType='".$type."'  where id='".$id."'";
		$resultBy=$myDB->query($updateQuery);
		if($type=='DropDown'){
			$ddvalue=addslashes($_POST['ddval']);
			$myDB=new MysqliDb();
			$select_qparaq=$myDB->query("select id from contract_para_ddval where para_id='".$id."' ");				if(count($select_qparaq)>0){
				$myDB=new MysqliDb();
				$myDB->query("Update contract_para_ddval set dd_val='".$ddvalue."'  where para_id='".$id."'");
			}else{
				$myDB=new MysqliDb();
				$myDB->query("Insert into contract_para_ddval set dd_val='".$ddvalue."' , para_id='".$id."'");
			}
		
		}else{
			$ddvalue=addslashes($_POST['ddval']);
			$myDB=new MysqliDb();
			$select_qparaq=$myDB->query("select id from contract_para_ddval where para_id='".$id."' ");					if(count($select_qparaq)>0){
				$myDB=new MysqliDb();
			 	$select_qparaq=$myDB->query("DELETE from contract_para_ddval where para_id='".$id."' ");		
			}
		}
		

		echo "<script>$(function(){ toastr.success('Parameter Updated Successfully'); }); </script>";
	}
}
?>
<script>
	$(document).ready(function(){
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
						        },'pageLength'
						        
						    ]
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				    });
		
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	$('.byID').addClass('hidden');
	
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Create Parameter Master</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4> Parameter Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Parameter"><i class="material-icons">ohrm_filter</i></a> </h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	      <div id="myModal_content" class="modal">
		    <!-- Modal content-->
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4" >Add/Edit Parameter</h4>
		      
		      <div class="modal-body">	
				 	<div id="medit" >  
					     
					    <div class="input-field col s6 m6">
				      		<input type="text" name="parameter" id="parameter" title="parameter" value="" required >
					        <label for="parameter">Parameter</label>
					    </div>
					     <div class="input-field col s6 m6">
				      		<input type="text" name="Alias" id="Alias" title="Alias" value="" required  >
					        <label for="Alias">Alias</label>
					    </div>
					    <div class="input-field col s6 m6" >
				      		<select name="ddType" id="ddType" required  >
					      		<option value=""   >Select</option>
					      		<option value="Txt"   >Txt</option>
					      		<option value="Calender"   >Calender</option>
					      		<option value="File"   >File</option>
					      		<option value="DropDown"   >DropDown</option>
				      		</select>
					        <label for="ddType" class="active-drop-down active">Type</label>
					    </div>
					    
					    <div class="input-field col s6 m6 ddval" id="dddiv" >
				      		<input type="text" name="ddval" id="ddval" title="dd val" value="tt" required >
					        <label for="parameter">Drop Down Data((comma separated))</label>
					    </div>
					    <div class="input-field col s6 m6" id="editid">
				      		<select name="Status" id="Status" required  >
				      		    <option value="1" >Active</option>
				      		    <option value="0" >InActive</option>
				      		</select>
					        <label for="Status" class="active-drop-down active">Status</label>
					    </div>
					   
				    </div>
					<input type='hidden' name='id' id='id' value=''>
					<div class="input-field col s12 m12 right-align">
					<button type="submit" name="savemodule" id="savemodule" class="btn waves-effect waves-green" style="display:none;" >Save</button>
					<button type="submit" name="addmodule" id="addmodule" class="btn waves-effect waves-green" style="display:none;" >Add</button>
					<button type="button" name="cancle" id='cancelID' class="btn waves-effect waves-green"  >Cancle</button>
					</div>
			  </div>
			</div>
		</div>
				
			  	 
			  	  <?php 
					$sqlConnect = "Select * from ctrctpram "; 
					$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					//print_r($result);
					$error=$myDB->getLastError();
					if($result){?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
					    <thead>
					        <tr>
					        	<th> Srl.No.</th>
								<th>Parameter </th>
					            <th>Alias</th>
					            <th>Type</th>
					            <th> Status </th>
					            <th> Manage </th>
					        </tr>
					    </thead>
					    <tbody>					        
					       <?php
					       $i=1;
					        foreach($result as $key=>$val){
					        	$status='';
					        	if($val['Status']=='1'){
									$status='Active';
								}else{
									
									$status='InActive';
								}
					        	$ID=$val['id'];
								echo '<tr>';	
								echo '<td class="module_id" >'.$i.'</td>';	
								echo '<td class="parameters">'.$val['parameters'].'</td>';     					
								echo '<td class="Alias">'.$val['Alias'].'</td>';
								echo '<td class="Alias">'.$val['ddType'].'</td>';
								echo '<td class="Status">'.$status.'</td>';
								
								?>
								
								<td class="manage_item">
								<a onclick="editData('<?php echo $ID; ?>','<?php echo $val['parameters']; ?>','<?php echo $val['Alias']; ?>','<?php echo $val['Status']; ?>','<?php echo $val['ddType']; ?>');" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>
								
						<?php
							echo '</tr>';
							$i++;
							}	
							?>			       
					    </tbody>
						</table>
						<?php }
						else
						{
							echo "<script>$(function(){ toastr.error('No Data Found ".$error."'); }); </script>";
						}
						
					?>
					
				</div>
			 
			  
		</div>
	</div>

       
       
</div>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
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
		
		$('#ContentAdd').click(function(){
			$('#medit').show();
				$('#parameter').val('');
				$('#Alias').val('');
			$('#id').val('');
			$('#editid').hide();
			$('#ddType').val('');
			 $('#ddval').val('');
			 $('#Alias').attr('readonly',false);
			$('select').formSelect();	
			
			$('#savemodule').hide();
			$('#addmodule').show();
		});
	// This code for cancel button trigger click and also for model close
     $('#cancelID').on('click', function(){
        $('#module_name').val('');
        $('#myModal_content').modal('close');
       //$('#medit').hide();
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
    
	$('#savemodule ,#addmodule').on('click', function(){
	        var validate=0;
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
				return false;
			} 
	});
	$('#medit').hide();
	$('#editid').hide();
	$('#dddiv').hide();
	$('#Alias').attr('readonly',false);
	$('#ddType').change(function(){
		if($('#ddType').val()=='DropDown'){
			$('#dddiv').show();
			$('#ddval').val('');
			
		}else{
			$('#dddiv').hide();
			$('#ddval').val('tt');
		}
	});
});
	function editData(id,parameter,Alias,status,ddtype){
		
		$('#medit').show();
		$('#parameter').val(parameter);
		$('#Alias').val(Alias);
		$('#Alias').attr('readonly',true);
		$('#Status').val(status);
		$('#ddType').val(ddtype);
		if(ddtype=='DropDown'){
			$('#dddiv').show();
			
			 $.ajax({
			      url:"../Controller/getDdValue.php?pid="+id,
			      async: false,  
			      success:function(data) {
			         $('#ddval').val(data);
			         $('select').formSelect();	
			      }
			   });	
		}
	
		$('select').formSelect();	
		$('#id').val(id);
		$('#savemodule').show();
		$('#addmodule').hide();
		$('#editid').show();
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
</script>
<script>	
	function Check(e) {
			
		    var keyCode = (e.keyCode ? e.keyCode : e.which);
		    if (keyCode > 47 && keyCode < 58) {
		        e.preventDefault();
		    }
		}
</script>