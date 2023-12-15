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

$sachinSirId='CE03070003';
$Incentive_Type=$StartDate=$Rate=$criteria1=$criteria2=$cm_id=$process=$Request_Status=$BaseCriteria=$EndDate='';
$classvarr="'.byID'";
$searchBy='';
$Id="";
if(isset($_SESSION['__user_logid']) &&  ($_SESSION['__status_ah']!='No' && $_SESSION['__status_ah']==$_SESSION['__user_logid'])){
	$userProcess=$_SESSION['__user_process'];
	$userClientId=$_SESSION['__user_client_ID'];
	

	if(isset($_GET['delid']))
	{
		$delid=base64_decode($_GET['delid']);
		if($delid!=""){
			$myDB=new MysqliDb();
			$myDB->rawQuery("delete from inc_incentive_criteria where id=$delid");
			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;
		}
	}	
	if(isset($_POST['btnSave']))
	{
		$createdBy=$_SESSION['__user_logid'];
		$Incentive_Type=$_POST['Incentive_Type'];
		$StartDate=trim($_POST['StartDate']);
		$EndDate=trim($_POST['EndDate']);
		$Rate=trim($_POST['Rate']);
		$criteria1=$_POST['criteria1'];
		$criteria2=$_POST['criteria2'];
		$Rate2=trim($_POST['Rate2']);
		$criteria12=$_POST['criteria12'];
		$criteria22=$_POST['criteria22'];
		$Rate3=trim($_POST['Rate3']);
		$criteria13=$_POST['criteria13'];
		$criteria23=$_POST['criteria23'];
		$ApplicableFor=$_POST['ApplicableFor'];
		$cm_id=$_POST['cm_id'];
		$process=$_POST['userProcess2'];
		//$Request_Status=$_POST['Request_Status'];
		$Request_Status='Reviewed';
		$BaseCriteria=$_POST['BaseCriteria2'];
		$resulti=0;
		$month=date('m', strtotime($StartDate));
			if($createdBy!="" && $Incentive_Type!="" && $StartDate!="" && $EndDate!="" && $Rate!="" && $BaseCriteria!="" && $criteria1!="" && $criteria2!="" && $cm_id!="" && $process!="" && $Request_Status!="" && $ApplicableFor!="" )
			{
				//$empID=$val;
				$myDB=new MysqliDb();
				$select_query=$myDB->rawQuery("select id,EndDate,incentiveStatus from inc_incentive_criteria where Incentive_Type='".$Incentive_Type."' and CreatedBy='".$createdBy."' and incentiveStatus=1 and Request_Status!='Decline' and cm_id='".$cm_id."' and ApplicableFor='".$ApplicableFor."' and ((month(EndDate)>= month('".$StartDate."')) and (year(EndDate)=year('".$StartDate."'))  || (month(EndDate)< month('".$StartDate."') and year(EndDate)>year('".$StartDate."')))order by id desc ");
				$my_error= $myDB->getLastError();	
				$rowCount = $myDB->count;
				if($rowCount<1)
				{
					$insert="call inc_AddRequest('".$createdBy."','".$Incentive_Type."','".$StartDate."','".$EndDate."','".$Rate."','".$BaseCriteria."','".$criteria1."','".$criteria2."','".$cm_id."','".$process."','".$Request_Status."','".$Rate2."','".$criteria12."','".$criteria22."','".$Rate3."','".$criteria13."','".$criteria23."','".$ApplicableFor."')";
						$resulti = $myDB->rawQuery($insert);
						$resulti = 1;
						$mysql_error=$myDB->getLastError();	
						$rowCount = $myDB->count;
						if($resulti && $rowCount>0)
						{
							 echo "<script>$(function(){ toastr.success('Incentive Added Successfully.') }); </script>";
							
						}else
						{
							echo "<script>$(function(){ toastr.error('Incentive Not Added ::Error :- <code>".$mysql_error."</code>') }); </script>";
						}
					}else{
						echo "<script>$(function(){ toastr.error('Incentive Scheme is already going on for this month .') }); </script>";
					}

			}else{
				echo "<script>$(function(){ toastr.error('Please enter data correctly.') }); </script>";
			}
				
				
		}
		if(isset($_POST['btnEdit']))
		{
			
			$createdBy=$_SESSION['__user_logid'];
			$Incentive_Type=$_POST['Incentive_Type'];
			$StartDate=trim($_POST['StartDate']);
			$EndDate=trim($_POST['EndDate']);
			$Rate=trim($_POST['Rate']);
			$criteria1=$_POST['criteria1'];
			$criteria2=$_POST['criteria2'];
			$Rate2=trim($_POST['Rate2']);
			$criteria12=$_POST['criteria12'];
			$criteria22=$_POST['criteria22'];
			$Rate3=trim($_POST['Rate3']);
			$criteria13=$_POST['criteria13'];
			$criteria23=$_POST['criteria23'];
			$ApplicableFor=$_POST['ApplicableFor'];
			$cm_id=$_POST['cm_id'];
			$process=$_POST['userProcess2'];
			$Request_Status=$_POST['Request_Status'];
			$BaseCriteria=$_POST['BaseCriteria2'];
			$Id=$_POST['editId'];
			$incentiveStatus="";
		
			if($Id!=""  && $createdBy!="" && $Incentive_Type!="" && $StartDate!="" && $EndDate!="" && $Rate!="" && $BaseCriteria!="" && $criteria1!="" && $criteria2!="" && $cm_id!="" && $process!="" && $Request_Status!="" )
			{
				//$empID=$val;
				$myDB=new MysqliDb();
				$select_query=$myDB->rawQuery("select id,EndDate,incentiveStatus from inc_incentive_criteria where Incentive_Type='".$Incentive_Type."' and CreatedBy='".$createdBy."' and incentiveStatus=1  and cm_id='".$cm_id."' and ApplicableFor='".$ApplicableFor."' and ((month(EndDate)>= month('".$StartDate."')) and (year(EndDate)=year('".$StartDate."'))  || (month(EndDate)< month('".$StartDate."') and year(EndDate)>year('".$StartDate."'))) and id!=$Id ");
				$mysql_error=$myDB->getLastError();	
				$rowCount = $myDB->count;
				if($rowCount<1)
				{
					 $insert="call inc_UpdateRequest('".$Id."','".$incentiveStatus."','".$createdBy."','".$Incentive_Type."','".$StartDate."','".$EndDate."','".$Rate."','".$BaseCriteria."','".$criteria1."','".$criteria2."','".$cm_id."','".$process."','".$Request_Status."','AH','".$sachinSirId."','".$Rate2."','".$criteria12."','".$criteria22."','".$Rate3."','".$criteria13."','".$criteria23."','".$ApplicableFor."')";
					 
					$resulti = $myDB->rawQuery($insert);
					$mysql_error=$myDB->getLastError();	
					$rowCount = $myDB->count;
					if($rowCount>0)
					{
						 echo "<script>$(function(){ toastr.success('Incentive Updated Successfully.') }); </script>";
						
					}
					else
					{
						 echo "<script>$(function(){ toastr.error('Incentive Not Updated ::Error :- <code>.".$mysql_error."</code>') }); </script>";
					}
				}else{
						 echo "<script>$(function(){ toastr.success('Incentive Scheme is already going on for this month .') }); </script>";
				}
			}else{
				 echo "<script>$(function(){ toastr.error('Please enter data correctly..') }); </script>";
			}
				
				
		}
		
}		
		
?>
<script>
	$(document).ready(function(){

		$('#StartDate, #EndDate').datetimepicker({
				timepicker:false,
				format:'Y-m-d',
				minDate:new Date(),
				scrollInput:false,
		});
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
		   	$('.byDate').addClass('hidden');
		   	$('.byDept').addClass('hidden');
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   	$('#searchBy').change(function(){
	   		$('.byID').addClass('hidden');
		   	$('.byDate').addClass('hidden');
		   	$('.byDept').addClass('hidden');
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
<span id="PageTittle_span" class="hidden">Manage Incentive : Request </span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Manage Incentive : Request </h4>	
	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
	<div class="input-field col s12 m12 ">
		<div class="input-field col s4 m4 ">	   
	      <select  id="Incentive_Type"  name="Incentive_Type" >
	     	 <option value="">---Select---</option>
	     	 <option value="Split">Split</option>
	     	 <option value="Attendance">Attendance</option>
	     	 <option value="Night/Late Evening">Night/Late Evening</option>
	     	 <option value="Morning">Morning</option>
	     	 <option value="Woman">Woman</option>
	      </select>
	       <label for="Incentive_Type" class="active-drop-down active">Incentive Type</label>
	    </div>
		<div class="input-field col s4 m4 ">
	     	<input type="text"  id="StartDate"  name="StartDate"   />  
	     	<label for="StartDate" class="active-drop-down active">Start Date</label>
	    </div>
	    <div class="input-field col s4 m4 ">
	     	<input type="text"  id="EndDate"  name="EndDate"   />  
	     	<label for="EndDate" class="active-drop-down active">End Date</label>
	    </div> 
	</div>
	<div class="input-field col s12 m12 ">
	    <div class="input-field col s4 m4 ">
	     	 <select disabled='disabled'   id="BaseCriteria"  name="BaseCriteria"   >
			      	<option value="">---Select---</option>
			      	<option value="Login Window"  >Login Window</option> 
					<option value="Present Days" >Present Days</option> 
		      </select> 
	     	<label for="BaseCriteria" class="active-drop-down active">Base Criteria</label>
	     	<input type='hidden' name='BaseCriteria2' id='BaseCriteria2'>
	    </div>
					
	  	<div class="input-field col s4 m4 ">
	  	<?php $processQuery="call inc_ProcessForAH('".$_SESSION['__user_logid']."')"; 
	    $myDB=new MysqliDb();
		$resultBy=$myDB->rawQuery($processQuery);
		$my_error = $myDB->getLastError();
		$rowCount = $myDB->count;
	    ?>
	     	<select  id="cm_id"  name="cm_id"  >
		      	<option  value=" ">---Select Process---</option>	
     		 <?php
				if($resultBy && $rowCount>0){
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
						echo '<option id="'.$value['process'].'" value="'.$value['cm_id'].'" '.$selected.'>'.$value['ProcessInfo'].'</option>';
					}

				}
	      ?>
	      	</select>
	      	<label for="cm_id" class="active-drop-down active">Process</label>
	      	<input type='hidden' id='userProcess' name='userProcess2'  >
	      	<input type='hidden' id='editId' name='editId'  >
	      	<input type='hidden'  id="Request_Status"  name="Request_Status" value="Pending"  >
	    </div>

		<div class="input-field col s4 m4 "> 
	     	<select  id="ApplicableFor"  name="ApplicableFor"  >
	      		<option value="CSA">CSA</option> 
	      		<option value="Support">Support</option> 
	     	 </select>
	     	 <label for="ApplicableFor" class="active-drop-down active">Applicable for</label>
	    </div>
	</div>
	<div class="input-field col s12 m12 ">				    
	    <div class="input-field col s4 m4 "> 
	      <input type="text"  id="Rate"  name="Rate" onkeypress="return isNumber(event)"  />
	       <label for="Rate" class=" active">Incentive Amount </label>
	    </div>
	   <div class="input-field col s4 m4 "> 
	      <select  id="criteria1"  name="criteria1"  >
		      	<option value="">---Select---</option>
	      </select>
	     <label for="criteria1" class="active-drop-down active" id='c1' >Shift IN</label>
	    </div>
	   <div class="input-field col s4 m4 "> 
	      <select  id="criteria2"  name="criteria2" >
		      	<option value="">---Select---</option>
	      </select>
	       <label for="criteria2" class="active-drop-down active" id='c2'>Shift OUT </label>
	    </div>
	  </div>  
	    <div  id="newField" style="display:none;">
	     <div class="input-field col s12 m12 ">	
	     	 <div class="input-field col s4 m4 "> 
		      <input type="text"   id="Rate2"  name="Rate2" value="" onkeypress="return isNumber(event)" />
		       <label for="Rate2" class="active-drop-down active">Incentive Amount2 </label>
		    </div>
		   <div class="input-field col s4 m4 ">
		      <select    id="criteria12"  name="criteria12"  >
			      	<option value="">---Select---</option>
		      </select>
		     <label for="criteria12" class="active-drop-down active" id='c12' >Present </label>
		    </div>
		    <div class="input-field col s4 m4 ">
		      <select  id="criteria22"  name="criteria22"   >
			      	<option value="">---Select---</option>
		      </select>
		       <label for="criteria22" class="active-drop-down active" id='c22' >Absent </label>
		    </div>
		</div>
     	 <div class="input-field col s12 m12 ">	
     	 	<div class="input-field col s4 m4 ">
		      <input type="text"   id="Rate3"  name="Rate3" value="" onkeypress="return isNumber(event)"  />
		       <label for="Rate3" class="active-drop-down active">Incentive Amount3 </label>
		    </div>
		   	<div class="input-field col s4 m4 ">
		      <select id="criteria13" name="criteria13"  >
			      	<option value="">---Select---</option>
		      </select>
		     <label for="criteria13" class="active-drop-down active">Present </label>
		    </div>
		    <div class="input-field col s4 m4 ">
		      <select  id="criteria23"  name="criteria23"   >
			      	<option value="">---Select---</option>
		      </select>
		       <label for="criteria23" class="active-drop-down active">Absent </label>
		    </div>
		    </div>
	     </div>
				     
			<div class="input-field col s12 m12 right-align ">
				<button type="submit"  value="Save" name="btnSave" id="btnSave1"  class="btn waves-effect waves-green  ">Save</button>
				<button type="submit" value="Update" name="btnEdit" id="btnEdit"  class="btn waves-effect waves-green " style="display:none;">Update</button>
				<button type="submit"  value="Cancel" name="btnCan" id="btnCancel" class="btn waves-effect waves-red close-btn " onclick="location.href='inc_IncentiveCriteriaRequest.php'">Cancel</button>
			 </div>	

			  	 <div id="pnlTable">
			    <?php 
			    	$sqlConnect="call  inc_GetIncentiveCriteria('".$_SESSION['__user_logid']."','".$sachinSirId."')";
			    	$myDB=new MysqliDb();
					$result=$myDB->rawQuery($sqlConnect);
					//echo $sqlConnect;
					$error=$myDB->getLastError();
					$rowCount = $myDB->count;
					if($result && $rowCount>0){?>
						<div class="had-container pull-left row card dataTableInline"  id="tbl_div" >
							<div class=""  >
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						           <th>SN.</th> 
						           <th>Incentive_Type</th>
						            <th>StartDate</th> 
						            <th>EndDate</th>
						            <th>BaseCriteria</th>
						            <th>Rate</th>
						             <th>Criteria 1</th>
						             <th>Criteria 2</th>
						            <th>Applicable For</th>
						            <th>Process</th>
						             <th>Sub-Process</th>
						            <th>RequestStatus</th>
						            <th>RequestedOn</th>
						            <th>Status</th> 
						            <th>Action  </th>
						            <th>Delete  </th> 
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					     ///  print_r($result);
					        foreach($result as $key=>$value){
					        	$count++;
					        	$level="";
					        	if($value['Incentive_Type']=='Attendance' || $value['Incentive_Type']=='Woman'){
									$level=' Days';
								}
								echo '<tr>';	
								echo '<td id="countc'.$count.'">'.$count.'</td>';						
								echo '<td class="Incentive_Type" id="Incentive_Type'.$count.'">'.$value['Incentive_Type'].'</td>';			
								echo '<td class="StartDate"  id="StartDate'.$count.'" >'.$value['StartDate'].'</td>';					
								echo '<td class="EndDate" id="EndDate'.$count.'"  >'.$value['EndDate'].'</td>';	
								echo '<td class="BaseCriteria" id="BaseCriteria'.$count.'"  >'.$value['BaseCriteria'].'</td>';	
								echo '<td class="Rate" id="Rate_edit'.$count.'">'.$value['Rate'].'</td>';	
								echo '<td class="criteria1" id="criteria1_edit'.$count.'">'.$value['criteria1'].'</td>';
								echo '<td class="criteria2" id="criteria2_edit'.$count.'">'.$value['criteria2'].'</td>';	
								echo '<td class="ApplicableFor" id="ApplicableFor'.$count.'">'.$value['ApplicableFor'].'</td>';	
								echo '<td class="Process" id="Process'.$count.'">'.$value['Process'].'</td>';		
								echo '<td class="Process" id="Process'.$count.'">'.$value['sub_process'].'</td>';		
								echo '<td class="Request_Status" id="Request_Status'.$count.'">'.$value['Request_Status'].'</td>';	
								echo '<td class="CreatedOn" id="CreatedOn'.$count.'">'.$value['CreatedOn'].'</td>';	
								echo '<td class="incentiveStatus" id="incentiveStatus'.$count.'">';
									if($value['incentiveStatus']=='1')echo 'Active'; else echo 'Inactive';
								echo '</td>';	
								?>
								<input type='hidden'  id='cm_id<?php echo $count; ?>' value='<?php echo $value['cm_id']; ?>'>
								<input type='hidden'  id='id<?php echo $count; ?>' value='<?php echo $value['id']; ?>'>
								<input type='hidden'  id='incStatus<?php echo $count; ?>' value='<?php echo $value['incentiveStatus']; ?>'>
								<input type='hidden'  id='criteria12<?php echo $count; ?>' value='<?php echo $value['criteria12']; ?>'>
								<input type='hidden'  id='criteria22<?php echo $count; ?>' value='<?php echo $value['criteria22']; ?>'>
								<input type='hidden'  id='criteria13<?php echo $count; ?>' value='<?php echo $value['criteria13']; ?>'>
								<input type='hidden'  id='criteria23<?php echo $count; ?>' value='<?php echo $value['criteria23']; ?>'>
								<input type='hidden' id='Rate2<?php echo $count; ?>' value='<?php echo $value['Rate2']; ?>'>
								<input type='hidden'  id='Rate3<?php echo $count; ?>' value='<?php echo $value['Rate3']; ?>'>
								
								
						<?php  if($value['Request_Status']=='Reviewed' && $value['incentiveStatus']=='1' ){ ?>
								<td class="tbl__ID"><a  onclick="return getEditData('<?php echo $count; ?>');" >
							
								<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i>
								</a></td>  
								<td class="tbl__ID"><a 	 onclick=" return  deleteData('<?php echo base64_encode($value['id']); ?>');" >
								<i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" data-position="left" data-tooltip="Delete">ohrm_delete</i>
								</a></td>  
								<?php } else{ ?>
								<td class="tbl__ID">
								
								<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Can't Edit">ohrm_edit</i>
								</a></td>
								<td class="tbl__ID">
							
								<i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" data-position="left" data-tooltip="Can't Delete">ohrm_delete</i>
								</a></td>
								<?php } ?>
							</tr>		
						<?php }	
							?>			       
					    </tbody>
						</table>
						  </div>
						</div>
						<?php
							 }
						
						else
						{
							//echo '<div id="div_error" class="slideInDown animated hidden">Data Not Found :: <code >'.$error.'</code> </div>';
							echo "<script>$(function(){ toastr.error('Data Not Found.:: <code >".$error."</code>') }); </script>";
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
function deleteData(editID){
	
	if(editID!=""){
		var result = confirm('Are you want to proceed?');
		if (result) {
		    location.href='inc_IncentiveCriteriaRequest.php?delid='+editID+')';
		}
		
	}
}
function getEditData(editID){
		$('#datastatus').show();
		$('#btnEdit').show();
		$('#btnSave1').hide();
		$('select').formSelect();
		var Incentive_Type=incType= $('#Incentive_Type'+editID).html();
		$('#Incentive_Type').val(Incentive_Type);
		//alert('Incentive_Type='+Incentive_Type);
		if(incType=='Split' || incType=='Night/Late Evening' || incType=='Morning'){
				$("#BaseCriteria").val('Login Window');	
				$("#BaseCriteria2").val('Login Window');
				$('select').formSelect();
			}else{
				$("#BaseCriteria").val('Present Days');
				$("#BaseCriteria2").val('Present Days');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							         if($(element).val().length > 0) 
		                                                {
							             $(this).siblings('label, i').addClass('active');
							         }
							         else
							         {
							$(this).siblings('label, i').removeClass('active');
						 }
				       });
				$('select').formSelect();
			}
			
		
		if(incType=='Split'){
				criteria1="<option value=''>---Select---</option><option value='5 AM'>5 AM</option><option value='6 AM'>6 AM</option> <option value='7 AM'>7 AM</option> <option value='8 AM'>8 AM</option>"; 
				$("#c1").html('Shift IN');	
				$("#criteria1").html(criteria1);	
				$('select').formSelect();	
				criteria2="<option value=''>---Select---</option><option value='8 PM'>8 PM</option> <option value='9 PM'>9 PM</option> <option value='10 PM'>10 PM</option><option value='11 PM'>11 PM</option> ";
				$("#c2").html('Shift OUT');	
				$("#criteria2").html(criteria2);	
				$('select').formSelect();
			}else
			if(incType=='Attendance'){
				$('#newField').show();
				criteria1=" <option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24 Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>"; 
				$("#c1").html('Present');
				$("#criteria1").html(criteria1);
				$('select').formSelect();
				$("#criteria12").html(criteria1);
				$('select').formSelect();
				$("#criteria13").html(criteria1);
				$('select').formSelect();
				criteria2="<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
				//alert(criteria2);	
				$("#c2").html('Absent');
				$("#criteria2").html(criteria2);
				$("#criteria22").html(criteria2);
				$("#criteria23").html(criteria2);
				$('select').formSelect();
				
			}else
			if(incType=='Night/Late Evening'){
				criteria1="	<option value=''>---Select---</option>";
				for(i=1;i<=12;i++){ 
					criteria1+="<option value='"+i+" PM'>"+i+" PM</option>";  
				} 
					criteria1+="<option value='12 AM'>12 AM</option>";  
				$("#c1").html('Shift IN');	
				$("#criteria1").html(criteria1);
				
				criteria2="	<option value=''>---Select---</option><option value='9 PM'>9 PM</option>  <option value='10 PM'>10 PM</option>  <option value='11 PM'>11 PM</option>  <option value='12 PM'>12 PM</option>";  
						      		
		      	for(j=1;j<=9;j++){ 
					criteria2+="<option value='"+j+" AM'>"+j+" AM</option>";  
			
				}
				$("#c2").html('Shift OUT:');		
				$("#criteria2").html(criteria2);
				$('select').formSelect();
				
					 	
			}else
			if(incType=='Morning'){
				criteria1="	<option value=''>---Select---</option>";
				for(i=4;i<=7;i++){ 
					criteria1+="<option value='"+i+" AM'>"+i+" AM</option>";  
				}
				$("#c1").html('Shift IN');	 
				$("#criteria1").html(criteria1);
				criteria2="<option value=''>---Select---</option><option value='1 PM'>1 PM</option>  <option value='2 PM'>2 PM</option>  <option value='3 PM'>3 PM</option>  <option value='4 PM'>4 PM</option>";  	
				$("#c2").html('Shift OUT');	
				$("#criteria2").html(criteria2);
			}else
			if(incType=='Woman'){
			 $('#newField').show();
					criteria1="<option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";  
				$("#c1").html('Present');
				$("#criteria1").html(criteria1);
				$("#criteria12").html(criteria1);
				$("#criteria13").html(criteria1);
				criteria2="<option value=''>---Select---</option><option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";	
				$("#c2").html('Absent');
				$("#criteria2").html(criteria2);	
				$("#criteria22").html(criteria2);	
				$("#criteria23").html(criteria2);	
			}
			
		var StartDate= $('#StartDate'+editID).html();
		var EndDate= $('#EndDate'+editID).html();
		var Rate= $('#Rate_edit'+editID).html();
		var Rate2= $('#Rate2'+editID).val();
		var Rate3= $('#Rate3'+editID).val();
		var criteria1= $('#criteria1_edit'+editID).html();
		if (criteria1.indexOf(";") >= 0){
			ct=criteria1.split(';');
			criteria1='>'+ct[1];
		}
		var criteria12=$('#criteria12'+editID).val();
		var criteria22=$('#criteria22'+editID).val();
		var criteria13=$('#criteria13'+editID).val();
		var criteria23=$('#criteria23'+editID).val();
		//alert(criteria12);
		var criteria2= $('#criteria2_edit'+editID).html();
		var cm_id= $('#cm_id'+editID).val();
		var process= $('#Process'+editID).html();
		var ApplicableFor= $('#ApplicableFor'+editID).html();
		var Request_Status= $('#Request_Status'+editID).html();
		var incSstatus= $('#incStatus'+editID).val();
		var editId= $('#id'+editID).val();
		
		$('#StartDate').val(StartDate);
		$('#EndDate').val(EndDate);
		
		$('#Rate').val(Rate);
		$('#Rate2').val(Rate2);
		$('#Rate3').val(Rate3);
		$('#criteria1').val(criteria1);
		$('#criteria12').val(criteria12);
		$('#criteria13').val(criteria13);
		$('#criteria2').val(criteria2);
		$('#criteria22').val(criteria22);
		$('#criteria23').val(criteria23);
		$('#cm_id').val(cm_id);
		$('#userProcess').val(process);
		$('#ApplicableFor').val(ApplicableFor);
		$('#Request_Status').val(Request_Status);
		$('#incentiveStatus').val(incSstatus);
		$('#editId').val(editId);
		$('select').formSelect();
	}


$(document).ready(function(){
		
	
		$('#alert_msg_close').click(function(){
			$('#alert_message').hide();
		});
		if($('#alert_msg').text()=='')
		{
			$('#alert_message').hide();
		}else
		{
			$('#alert_message').delay(5000).fadeOut("slow");
		}
		
		$('#div_error').removeClass('hidden');
		
		
		$('#btnCancel').click(function(){
			$('.statuscheck').addClass('hidden');	
		});
		
		
$("#Incentive_Type").on('change',function(){
			var incType=$("#Incentive_Type").val();
			if(incType=='Split' || incType=='Night/Late Evening' || incType=='Morning'){
				
				//$( "#BaseCriteria option:selected" ).text('Login Window');
				$("#BaseCriteria").val('Login Window');
				//$('#BaseCriteria').val('Login Window').prop('selected', true);	
				$("#BaseCriteria2").val('Login Window');
				
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							         if($(element).val().length > 0) 
		                                                {
							             $(this).siblings('label, i').addClass('active');
							         }
							         else
							         {
							$(this).siblings('label, i').removeClass('active');
						 }
				       });
				       $('select').formSelect();
				$("#c1").html('Shift IN');
				$("#c2").html('Shift OUT');
				$('#newField').hide();
				$("#criteria12").val('');
				$("#criteria13").val('');
				$("#criteria23").val('');
				$("#criteria22").val('');
				$("#Rate2").val('');
				$("#Rate3").val('');
				$('select').formSelect();
				
			}else{
				$("#BaseCriteria").val('Present Days');
				$("#BaseCriteria2").val('Present Days');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							         if($(element).val().length > 0) 
		                                                {
							             $(this).siblings('label, i').addClass('active');
							         }
							         else
							         {
							$(this).siblings('label, i').removeClass('active');
						 }
				       });
				       $('select').formSelect();
				$("#c1").html('Present');
				$("#c2").html('Absent');
				$('#newField').show();
				$('select').formSelect();
			}
			
			var criteria1="";
			var criteria2="";
			if(incType=='Split'){
				criteria1="<option value=''>---Select---</option><option value='5 AM'>5 AM</option><option value='6 AM'>6 AM</option> <option value='7 AM'>7 AM</option> <option value='8 AM'>8 AM</option>"; 
				$("#criteria1").html(criteria1);	
				criteria2="<option value=''>---Select---</option><option value='8 PM'>8 PM</option> <option value='9 PM'>9 PM</option> <option value='10 PM'>10 PM</option><option value='11 PM'>11 PM</option> ";	
				$("#criteria2").html(criteria2);	
				$('select').formSelect();
			}else
			if(incType=='Attendance'){
				$("#c1").html('Present');
				criteria1=" <option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24 Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>"; 
				$("#criteria1").html(criteria1);
				$("#criteria12").html(criteria1);
				$("#criteria13").html(criteria1);
				$("#c2").html('Absent');
				criteria2="<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";
				//alert(criteria2);	
				$("#criteria2").html(criteria2);
				$("#criteria22").html(criteria2);
				$("#criteria23").html(criteria2);
				$('select').formSelect();
			}else
			if(incType=='Night/Late Evening'){
				criteria1="<option value=''>Select</option>";  
				criteria1+="<option value='12 PM'>12 PM</option>";  
				for(i=1;i<12;i++){ 
					criteria1+="<option value='"+i+" PM'>"+i+" PM</option>";  
				} 
					criteria1+="<option value='12 AM'>12 AM</option>";  
				$("#criteria1").html(criteria1);
				
				criteria2="	<option value=''>Select</option><option value='9 PM'>9 PM</option><option value='10 PM'>10 PM</option><option value='11 PM'>11 PM</option><option value='12 PM'>12 PM</option>";  
						      		
		      	for(j=1;j<=9;j++){ 
					criteria2+="<option value='"+j+" AM'>"+j+" AM</option>";  
			
				}	
				$("#criteria2").html(criteria2);
				
			$('select').formSelect();		 	
			}else
			if(incType=='Morning'){
				criteria1="<option value=''>---Select---</option>";
				for(i=4;i<=7;i++){ 
					criteria1+="<option value='"+i+" AM'>"+i+" AM</option>";  
				} 
				$("#criteria1").html(criteria1);
				criteria2="	<option value=''>---Select---</option><option value='1 PM'>1 PM</option>  <option value='2 PM'>2 PM</option>  <option value='3 PM'>3 PM</option>  <option value='4 PM'>4 PM</option>";
			$("#criteria2").html(criteria2);
			$('select').formSelect();
			}else
			if(incType=='Woman'){
			 		  
					criteria1="<option value=''>---Select---</option><option value='23'>>23 Days</option><option value='24'>>24Days</option><option value='25'>>25 Days</option><option value='26'>>26 Days</option><option value='27'>>27 Days</option>";  
				$("#c1").html('Present');
				$("#criteria1").html(criteria1);
				$("#criteria12").html(criteria1);
				$("#criteria13").html(criteria1);
				
				criteria2="<option value=''>---Select---</option><option value='0'>A=0</option> <option value='1'>A=1</option> <option value='2'>A=2</option>";		
				$("#c2").html('Absent');
				$("#criteria2").html(criteria2);
				$("#criteria22").html(criteria2);
				$("#criteria23").html(criteria2);
				$('select').formSelect();
			}
			
		});
	
		$('#cm_id').change(function(){
			var Process=	$('#cm_id option:selected').attr('id');
			$('#userProcess').val(Process);
			$('select').formSelect();
				
		});
		$('#criteria1').change(function(){
			$('#criteria2').val('');	
			var incType=$("#Incentive_Type").val();
			if(incType=='Attendance'){
				$('#criteria2').val('');
				$('select').formSelect();
			}else
			if(incType=='Night/Late Evening'){
				var criteria1=	$('#criteria1').val();
				//alert(criteria1);
				if(criteria1=='1 PM'){
					$('#criteria2').val('10 PM');	
				}else
				if(criteria1=='2 PM'){
					$('#criteria2').val('11 PM');	
				}else
				if(criteria1=='3 PM'){
					$('#criteria2').val('12 PM');	
				}else
				if(criteria1=='4 PM'){
					$('#criteria2').val('1 AM');	
				}else
				if(criteria1=='5 PM'){
					$('#criteria2').val('2 AM');	
				}else
				if(criteria1=='6 PM'){
					$('#criteria2').val('3 AM');	
				}else
				if(criteria1=='7 PM'){
					$('#criteria2').val('4 AM');	
				}else
				if(criteria1=='8 PM'){
					$('#criteria2').val('5 AM');	
				}else
				if(criteria1=='9 PM'){
					$('#criteria2').val('6 AM');	
				}else
				if(criteria1=='10 PM'){
					$('#criteria2').val('7 AM');	
				}else
				if(criteria1=='11 PM'){
					$('#criteria2').val('8 AM');	
				}else
				if(criteria1=='12 PM'){
					$('#criteria2').val('9 PM');	
				}else
				if(criteria1=='12 AM'){
					$('#criteria2').val('9 AM');	
				}
				$('select').formSelect();
			}
			else
			if(incType=='Morning'){
				var criteria1=	$('#criteria1').val();
				if(criteria1=='4 AM'){
					$('#criteria2').val('1 PM');	
				}else
				if(criteria1=='5 AM'){
					$('#criteria2').val('2 PM');	
				}else
				if(criteria1=='6 AM'){
					$('#criteria2').val('3 PM');	
				}else
				if(criteria1=='7 AM'){
					$('#criteria2').val('4 PM');	
				}
				$('select').formSelect();
			}
			
				
		});
			
				
		$('#btnSave1, #btnEdit').click(function(){
			validate=0;
			alert_msg="";
			incType=$('#Incentive_Type').val();
			if($('#Incentive_Type').val() ==""){
				validate=1;
		     	//alert_msg+='<li> Please select Incentive Type</li>';
		     	$('#Incentive_Type').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if($('#span_Incentive_Type').size() == 0)
				{
				   $('<span id="span_Incentive_Type" class="help-block">Please select Incentive Type</span>').insertAfter('#Incentive_Type');
				}
		     	
			}
			if(incType=='Split' || incType=='Night/Late Evening' || incType=='Morning'){
				var level1='Shift IN';
				var level2='Shift OUT';
			}else{
				var level1='Present day';
				var level2='Absent day';
			}
			var StartDate= $('#StartDate').val().trim();
			if(StartDate=='')
			{
				validate=1;
				$('#StartDate').addClass('has-error');
				if($('#stxt_StartDate').size() == 0)
				{
				   $('<span id="stxt_StartDate" class="help-block">Please select Start Date</span>').insertAfter('#StartDate');
				}

			
			}
			var EndDate= $('#EndDate').val().trim();
			if(EndDate=='')
			{
				validate=1;
     			
     			$('#EndDate').addClass('has-error');
				if($('#stxt_EndDate').size() == 0)
				{
				   $('<span id="stxt_EndDate" class="help-block">Please select End Date</span>').insertAfter('#EndDate');
				}
			
			}
			var cm_id=$('#cm_id').val().trim();
			if(cm_id==""){
				validate=1;
		     	//alert_msg+='<li> Please select process</li>';
		     	$('#cm_id').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if($('#span_cm_id').size() == 0)
				{
				   $('<span id="span_cm_id" class="help-block">Please select process</span>').insertAfter('#cm_id');
				}
			}
			var Process=	$('#cm_id option:selected').attr('id');
			$('#userProcess').val(Process);
			var  Rate=$('#Rate').val().trim();
			if(Rate==""){
				validate=1;
		     	//alert_msg+='<li> Incentive Amount should not be empty</li>';
		     	$('#Rate').addClass('has-error');
				if($('#stxt_Rate').size() == 0)
				{
				   $('<span id="stxt_Rate" class="help-block">Incentive Amount should not be empty</span>').insertAfter('#Rate');
				}
			}
			var  criteria1=$('#criteria1').val().trim();
			if(criteria1==""){
				validate=1;
		     	$('#criteria1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if($('#span_criteria1').size() == 0)
				{
				   $('<span id="span_criteria1" class="help-block"> Please select '+level1+'</span>').insertAfter('#criteria1');
				}
			}
			var  criteria2=$('#criteria2').val().trim();
			if(criteria2==""){
				validate=1;
		     	//alert_msg+='<li> Please select '+level2+'</li>';
		     	
		     	$('#criteria2').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if($('#span_criteria2').size() == 0)
				{
				   $('<span id="span_criteria2" class="help-block">Please select '+level2+'</span>').insertAfter('#criteria2');
				}
			}
			var  Rate2=$('#Rate2').val().trim();
			var  criteria12=$('#criteria12').val().trim();
			var  criteria22=$('#criteria22').val().trim();
			if(!((Rate2=="" && criteria12=="" && criteria22=="") || (Rate2!="" && criteria12!="" && criteria22!=""))){
				validate=1;
		     	//alert_msg+='<li>Incentive Amount2, Present day and Absent day should not be empty</li>';
		     	if(Rate2==""){
					$('#Rate2').addClass('has-error');
					if($('#stxt_Rate2').size() == 0)
					{
					   $('<span id="stxt_Rate2" class="help-block">Incentive Amount should not be empty</span>').insertAfter('#Rate2');
					}
				}
				if(criteria12=="")
		     	{
			     	$('#criteria12').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if($('#span_criteria12').size() == 0)
					{
					   $('<span id="span_criteria12" class="help-block"> Please select present day </span>').insertAfter('#criteria12');
					}
				}
				if(criteria22=="")
		     	{
			     	$('#criteria22').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if($('#span_criteria22').size() == 0)
					{
					   $('<span id="span_criteria22" class="help-block"> Please select absent day </span>').insertAfter('#criteria22');
					}
				}
				
			}else{
				$('#span_criteria22').html('');
				$('#span_criteria12').html('');
				$('#stxt_Rate2').html('');
				$('#criteria22').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
				$('#criteria12').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
				$('#Rate2').removeClass('has-error');
			}
			var  Rate3=$('#Rate3').val().trim();
			var  criteria13=$('#criteria13').val().trim();
			var  criteria23=$('#criteria23').val().trim();
			if(!((Rate3=="" && criteria13=="" && criteria23=="") || (Rate3!="" && criteria13!="" && criteria23!=""))){
				validate=1;
		     	//alert_msg+='<li>Incentive Amount3, Present day and Absent day should not be empty</li>';
		     	if(Rate3==""){
					$('#Rate3').addClass('has-error');
					if($('#stxt_Rate3').size() == 0)
					{
					   $('<span id="stxt_Rate3" class="help-block">Incentive Amount should not be empty</span>').insertAfter('#Rate3');
					}
				}
				if(criteria13=="")
		     	{
			     	$('#criteria13').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if($('#span_criteria13').size() == 0)
					{
					   $('<span id="span_criteria13" class="help-block"> Please select present day </span>').insertAfter('#criteria13');
					}
				}
				if(criteria23=="")
		     	{
			     	$('#criteria23').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
					if($('#span_criteria23').size() == 0)
					{
					   $('<span id="span_criteria23" class="help-block"> Please select absent day </span>').insertAfter('#criteria23');
					}
				}
		     	
			}else{
				$('#span_criteria23').html('');
				$('#span_criteria13').html('');
				$('#stxt_Rate3').html('');
				$('#criteria23').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
				$('#criteria13').parent('.select-wrapper').find('input.select-dropdown').removeClass("has-error");
				$('#Rate3').removeClass('has-error');
			}
			
			if(validate==1)
	      	{		      		
	      		/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;*/
				if(alert_msg!="")	{
					$(function(){ toastr.error(alert_msg) });
				}	
				return false;
			}
				
		});
		

	});
	
	function checklistdata(){
			//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
			$('.statuscheck').removeClass('hidden');
			
	}
	function isNumber(evt) {
	    evt = (evt) ? evt : window.event;
	    var charCode = (evt.which) ? evt.which : evt.keyCode;
	    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	        return false;
	    }
	    return true;
	}
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>