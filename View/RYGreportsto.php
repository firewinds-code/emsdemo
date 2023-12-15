<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
$statusdate=date('d');
//if($statusdate >='20'  && ($statusdate<='22'))
if($statusdate >='10'  && ($statusdate<='15'))
{
	$myDB=new MysqliDb();
	if(isset($_SESSION['__user_logid']) && $_SESSION['__user_logid']!=""){
		$resultrrt=$myDB->query("select EmployeeID from status_table where ReportTo='".$_SESSION['__user_logid']."' ");
		if(count($resultrrt)>0){ 
			if(isset($resultrrt[0]['EmployeeID']) && $resultrrt[0]['EmployeeID']==NULL){
				$location= URL.'Login'; 
				echo "<script>location.href='".$location."'</script>";
				exit;
			}
			
		}else{
			$location= URL.'Login'; 
			echo "<script>location.href='".$location."'</script>";
			exit;
		}
	} 
}else{
	$location= URL.'Login'; 
	echo "<script>location.href='".$location."'</script>";
}

$old_client=$new_client=$move_date=$tcid_array=$remark='';
$classvarr="'.byID'";
$searchBy='';
$client_name='';
$cm_id='';
$date=date("Y-m-d h:i:s");
$Loggin_location= $_SESSION["__location"];
$reporto= $_SESSION['__user_logid'];
//$reporto='CE121621933';
$Query='select id,substatus from ryg_substatus_master;';
$myDB= new MysqliDb();
$remark_array=array();
$result =$myDB->query($Query);
foreach($result as $lval){
	$remark_array[$lval['id']]=$lval['substatus'];
	
}
$lQuery='select id,location from location_master;';
$myDB= new MysqliDb();
$location_array=array();
$lresult =$myDB->query($lQuery);
foreach($lresult as $lval){
	$location_array[$lval['id']]=$lval['location'];
	
}
if(isset($_POST['save_status']))
{
	/*echo "<pre>";
	print_r($_POST);
	echo "</pre>";*/
	
}



?>
<link rel="stylesheet" href="../Style/ryg_style.css">
<style>

table.dataTable.row-border tbody td{
	white-space: normal !important;
	/*white-space: inherit !important;*/
}

</style>
<script>
	$(document).ready(function(){
		$('#myTable').DataTable({
				        dom: 'Bfrtip',	
				        scrollX: '100%',
				        "iDisplayLength": 10,				        
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
		   	
		   
		   //	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	$('.byID').addClass('hidden');
		   
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   	$('#searchBy').change(function(){
		   		$('.byID').addClass('hidden');
		   		if($(this).val()=='By ID')
		   		{
					$('.byID').removeClass('hidden');
				}
								
		   	});
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">RYG Status ReportsTo </span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>RYG Status ReportsTo </h4>	
	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
		<div id="pnlTable">
			  <div class="had-container pull-left row card" >
				<div class=""  >
			    <?php 
			     
			     		 $query="SELECT distinct(ep.EmployeeID),pd.EmployeeName,ryg.ryg_status,ryg_substatus,ryg_remark,pd.location,cm.client_name,ncm.`process`,ncm.`sub_process`  FROM ems.status_table st inner join employee_map ep on st.EmployeeID=ep.EmployeeID inner join personal_details pd on ep.EmployeeID=pd.EmployeeID inner join new_client_master ncm on ncm.cm_id=ep.cm_id inner Join client_master cm on cm.client_id=ncm.client_name  left join ( select * from ryg_reportto  where   Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE()) ) ryg on ep.EmployeeID=ryg.EmployeeID  where ep.emp_status='Active' and st.ReportTo='".$reporto."' and ep.EmployeeID!='".$reporto."' ";
					$myDB=new MysqliDb();
					$result=$myDB->rawQuery($query);
					$mysql_error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if(empty($mysql_error) && $rowCount>0){ ?>	
			   			 
						  <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead >
						        <tr>
						        	<!--<th >SNo.</th>-->
						        	<th> Action</th>
						        	<th>Emp Id (Emp Name)</th>
						        	<th>Status</th>
						             <th>Sub Status</th> 
						            <th>Remarks</th>      
						            
						           <!-- <th>Client</th>-->
						            <th>Process</th>
						            <th>Sub-Process</th>
						            <!--<th>Site</th>-->
						            
						                  
						        </tr>
						    </thead>
					    <tbody id="emplist">	
					    <input class='empclass hidden ' type='text' name='reporttoid'  id="reporttoid" value="<?php echo $_SESSION['__user_logid'];?>" >				        
					      	<?php
									$i=0;
									$j=1;
									foreach($result as $key=>$data_array){
										 $ryg_substatus='';
										 $ryg_location='';
							              if($data_array['ryg_substatus']!=""){
							              	 $ryg_substatus=$remark_array[$data_array['ryg_substatus']];
							              }
							              if($data_array['location']!=""){
							              	 $ryg_location=$location_array[$data_array['location']];
							              }
										
										$i++;
										echo '<tr>';	
										
										?>
										<td>
										<div class="input-field col s12 m12 right-align">
											<!--<button type='button' name='save'  class="waves-green <?php if($data_array['ryg_remark']!=""){ ?>   savedclass <?php } ?> "  onclick="rygSave(<?php echo  $j; ?>)" id="sid<?php echo  $j; ?>">save</button>-->	
											<!--<button type='button' name='save'  class="waves-green <?php if($data_array['ryg_remark']!=""){ ?>   savedclass <?php } ?> "  onclick="rygSave(<?php echo  $j; ?>)" id="sid<?php echo  $j; ?>" title='save'><i class="fa fa-lg fa-save"></i></button>-->
											<span name='save'  class="waves-green <?php if($data_array['ryg_remark']!=""){ ?>   savedclass <?php } ?> "  onclick="rygSave(<?php echo  $j; ?>)" id="sid<?php echo  $j; ?>" title='save'><i class="fa fa-lg fa-save"></i></span>	
										</div>
										</td>
										<td class="EmployeeID "><?php echo $data_array['EmployeeID']; ?> (<?php echo ucwords(strtolower($data_array['EmployeeName'])); ?>)<input class="empclass" id="empid<?php echo $j; ?>" type="hidden" name="EmployeeID[]" value="<?php echo $data_array['EmployeeID']; ?>" ></td>
									
										<td >
									
										<select name="status[<?php echo  $j; ?>]" id="status<?php echo  $j; ?>" class="rygclass" onchange="getData(<?php echo  $j; ?>);">
											<option value="" <?php if($data_array['ryg_status']==''){ echo "selected";  } ?> >Pending</option>
											<option value="Red" <?php if($data_array['ryg_status']=='Red'){ echo "selected";  } ?> >Red</option>
											<option value="Yellow"  <?php if($data_array['ryg_status']=='Yellow'){  echo "selected";  } ?>>Yellow</option>
											<option value="Green"  <?php if($data_array['ryg_status']=='Green'){  echo "selected";  } ?>>Green</option>
										</select>
										</td>
										<td ><select name="substatus[]" id="substatus<?php echo  $j; ?>" class="substatusclass">
										<?php
										if($data_array['ryg_substatus']!=null && $data_array['ryg_substatus']!="")
										{
											?>
											<option value="<?php echo $data_array['ryg_substatus'];?>"  ><?php echo $ryg_substatus;?></option>	
									<?php
										}else{ ?>
											<option value=""  >Select</option>	
									<?php	}
										?>	
										</select>
										</td>
										<td ><textarea name="remarks" id="remarks<?php echo  $j; ?>" class="remarksclass" maxlength="255"><?php echo  $data_array['ryg_remark'];?></textarea></td>
										<?php
										
										/*echo '<td class="Client">'.$data_array['client_name'].'</td>';*/
										echo '<td class="Process">'.$data_array['process'].'</td>';
										echo '<td class="Sub-Process">'.$data_array['sub_process'].'</td>';
										/*echo '<td class="Location">'.$ryg_location.'</td>';*/
										/*echo '</tr>';
										echo '<tr>';*/
										
										
									/*echo '</tr>';
									echo '</table>';*/
										echo '</tr>';
										$j++;
									}
									
									
								
								?>
							
					    </tbody>
						</table>
						<!--<div class="input-field col s12 m12 right-align">
 				<button type="submit"  name="save_status" id="save_status" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Submit</button>
  			
						  </div>-->
						  </div>
						<?php
							}else{
								echo "<script>$(function(){ toastr.error('Data not found '); }); </script>";
							}
					 	
					
			  	
				?>
					
				</div>
	</div>
			
	
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div> 
<script>
	$(document).ready(function(){
		  var table = $('#myTable').DataTable();
	   	$('#update_status').click(function(){
	   		
	    	 var validate=0;
	    	 var alert_msg='';
	    	
	    	 if($('input.cb_child:checkbox:checked').length<=0)
		     {
		     	validate=1;
		     	alert_msg+='<li> Check Atleast One Employee ....  </li>';
			 }else{
			 	
			 	var checkedValue = null; 
				var inputElements = document.getElementsByClassName('cb_child');
				var ahComment = document.getElementsByClassName('ahcomment');
				var empclass = document.getElementsByClassName('empclass');
				for(var i=0; inputElements[i]; ++i){
				      if(inputElements[i].checked){
				        checkedValue = ahComment[i].value.trim(); 
				        empname = empclass[i].value.trim();
				           if(checkedValue==""){
				           		validate=1;
		     					alert_msg+='<li> Write the comment for '+ empname +'</li>';
						   	  break;
							}
				      }
				}
			 	
			 }
	    	 if(validate==1)
		      	{		      		
		      		
		      		
		      		$(function(){ toastr.error(alert_msg) });
					return false;
				}
	   	});
	   	
		
		
	});
	
	function getData(id){
		var sval=$('#status'+id+'').val();
		if(sval!=""){
	   			$.ajax({
				  method: "GET",
				  url: '../Controller/getRYG_substatus.php',
				  data: {'getr':'getremark','rygstatus':sval}
				})
				  .done(function( msg ) {
				  //	alert(msg);
				  	$('#substatus'+id+'').html(msg);
				   // alert( "Data Saved: " + msg );
				  });

		}	
	}
	function rygSave(id){
		var sval=$('#status'+id+'').val();
		if(sval==""){
			alert('Please select status');
			return false;
		}
		var substatus=$('#substatus'+id+'').val();
		if(substatus==""){
			alert('Please select sub-status');
			return false;
		}
		var empid= $('#empid'+id+'').val();
		var reporttoid= $('#reporttoid').val();
		var remarks= $('#remarks'+id+'').val().trim();
		var rlen= remarks.trim().length;
		if(rlen<30){
			alert('Please enter mimimum 30 characters for remark');
			//$('#remarks'+id+'').css('border-color','red');
			$('#remarks'+id+'').focus();
			return false;
		}else{

			if (/[^a-zA-Z0-9\-.,: ]/.test(remarks)) {
			    alert('Remark only contain alphanumeric characters ');
			   // $('#remarks'+id+'').css('border-color','red');
			    $('#remarks'+id+'').focus();
			    return false;
			  }else{
			  	//$('#remarks'+id+'').css('border-color','');
			  }
		}
		if(sval!="" && substatus!="" && reporttoid!=""){
			if(remarks!="")
			{
	   			$.ajax({
				  method: "POST",
				  url: '../Controller/saveRYGstatus.php',
				  data: {'saves':'saves','rygstatus':sval,'substatus':substatus,'rygsr':remarks,'empid':empid,'reporto':reporttoid}
				}).done(function( msg ) {
				  	$("#sid"+id+"").addClass('savedclass');
				   alert( "Data Saved: " + msg );
				  
				  });
			} else{
				alert('Please enter remark');
			}

		}else{
			alert('Please select status and sub status');
		}	
	}
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>