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
$remark=$empname=$empid='';
$classvarr="'.byID'";
$searchBy='';
$msg='';
$rstatus="";
//print_r($_POST);
$rowCount="";
if(isset($_POST['addSearch']) && trim($_POST['remark2'])!="" && $rstatus==0)
{
	$empid=trim($_POST['semployee']);
	$remark=trim($_POST['remark2']);
	$rstatus=trim($_POST['rstatus']);
	
	$OH=trim($_POST['oh']);
	$AH=trim($_POST['account_head']);
	$HR_ID=$_SESSION['__user_logid'];
	if(strlen($remark)>=255){
		 $insert="call Add_Rejoin_HR_remark('".$empid."','".$remark."','".$HR_ID."','".$OH."','".$AH."')";
		
		$myDB=new MysqliDb();
		$result=$myDB->rawQuery($insert);
		$error=$myDB->getLastError();
		$rowCount = $myDB->count;
	
	}else{
		//$msg='<p class="text-success">Comment should not be less than 255 characters </p>';
		echo "<script>$(function(){ toastr.success('Comment should not be less than 255 characters'); }); </script>";
	}
		if($rowCount>0)
		{
			//$msg='<p class="text-success">HR Comment Added Successfully...</p>';
			echo "<script>$(function(){ toastr.success('HR Comment Added Successfully'); }); </script>";
			$_POST['semployee']='';
			$empid="";
		}
		else
		{
			//$msg='<p class="text-danger">Comment Not Added ::Error :- <code>'.$error'</code></p>';
			echo "<script>$(function(){ toastr.success('Comment Not Added ::Error :- <code>".$error."</code>'); }); </script>";
		}
		
	}

	

if(isset($_POST['btnSearch']) && trim($_POST['semployee'])!="")
{
	
	
	$empid=trim($_POST['semployee']);
	$sqlConnect="call search_inactive_employee('".$empid."')";
	$myDB=new MysqliDb();
	$result5=$myDB->rawQuery($sqlConnect);
	$error=$myDB->getLastError();
	$rowCount = $myDB->count;
	
	if($rowCount<1){
		$msg='<p class="text-success">Employee Not Found in Inactive List...</p>';
		$_POST['semployee']="";
		$empid="";
	}
}
	
			
		
?>
<script>
	$(document).ready(function(){
		$('.statuscheck').addClass('hidden');
		
		$('#myTable').DataTable({
		  "sPaginationType": "bootstrap", 
    		"bPaginate": false, 
    		"bFilter": false, 
    		"bInfo": false,
				        dom: 'Bfrtip',
				        scrollY: '100%',
				        scrollX: '100%',			        
				        scrollCollapse: true,
				        lengthMenu: [
				            [ 5,10, 25, 50, -1 ],
				            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
				         buttons: [
						          
						         
						       
						    ]
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				    });
		
		  
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
<span id="PageTittle_span" class="hidden">Rejoin Inactive Employee</span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Rejoin Inactive Employee </h4>	
	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
		<div class="input-field col s12 m12 ">
			    <input type="text" id="search_empid"  name="semployee"  value='<?php echo $empid; ?>' />
			    <label for="search_empid" class="active"> Employee ID  </label>	
		</div>
		<?php
		if($empid!=""){
		?>
		
		<div class="input-field col s12 m12 ">
			    <textarea class="materialize-textarea" id="textremark"  name="remark2" ></textarea>
			    <label for="textremark" class="active">Comment </label>	
		</div>
		<?php } ?>	 
		        	
	    <div class="input-field col s12 m12 right-align ">
	     <?php
	     if($empid==""){
	     	?>
		 	<!-- <input  type="submit" value="Search" name="btnSearch" id="btnSave1" class="button button-3d-action button-rounded"/>--> 
		 	  <button type="submit" value="Search" name="btnSearch" id="btnSave1" class="btn waves-effect waves-green ">Search</button>
		<?php } else{ ?>
			  <!-- <input  type="submit" value="Rejoin" name="addSearch" id="rejoin" class="button button-3d-action button-rounded"/>-->
			   <button type="submit" value="Rejoin" name="addSearch" id="rejoin"  class="btn waves-effect waves-green  ">Rejoin</button>
		<?php }
	      ?>
		<!-- <a href="rejoin-emp.php">  <input  type="button" value="Cancel" name="btnCan" id="btnCancel"  class="btn waves-effect waves-red "/></a>-->
		</div>
		  	
			       
			  	 <div id="pnlTable">
			    <?php 
			     if(isset($_POST['btnSearch']) && trim($_POST['semployee'])!="")
				{
					$empid=$_POST['semployee'];	
			    	
					//echo $sqlConnect;
					$error=$myDB->getLastError();
					if($result5){?>
						
			   			<div class="had-container pull-left row card dataTableInline"  >
							<div class=""  >																																		 						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						           <th>SN.</th> 
						           <th>EmployeeID</th>
						            <th>EmployeeName</th> 
						            <th>JoiningDate</th>
						            <th>RelevingDate</th>
						            <th>Designation</th>
						             <th>ClientName</th>
						            
						            <th>Process</th>
						            <th>SubProcess</th>
						            <th>AccountHead</th>
						            <th>ReportTo</th>
						            <th>Reason of Leaving</th> 
						            <th>Rejoin Status</th>
						           <!-- <th>Action  </th><th>OH  </th><th>AH </th>-->
						            
						            
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					     ///  print_r($result);
					        foreach($result5 as $key=>$value){
					        	$doj_date="";
					        	$dol_date="";
					        	
				        		
				        		$rejoin_status="";
				        		if($value['rejoin_status']=='0'){
				        			$rejoin_status= "Applicable"; 
				        		}
				        		elseif($value['rejoin_status']=='1'){
				        			$rejoin_status= "Not Applicable"; 
				        		}
				        		$date=DATE('Y-m-d');
				        		
								
								$dol_date = ((!empty($value['dol']))?date('Y-m-d',strtotime($value['dol'])):'');
								$doj_date = ((!empty($value['DOJ']))?date('Y-m-d',strtotime($value['DOJ'])):'');
								$date1 = $dol_date;
								$date2 = $date;

								$diff = abs(strtotime($date2) - strtotime($date1));
								$days = floor($diff / (60*60*24));
					        	$count++;
								echo '<tr>';	
								echo '<td >'.$count.'</td>';						
								echo '<td class="Process" id="empid">'.$value['EmployeeID'].'</td>';			
								echo '<td class="SubProcess"  id="empname" >'.$value['EmployeeName'].'</td>';					
								echo '<td class="doj" id="doj"  >'.$doj_date.'</td>';
								echo '<td class="dol_date" id="dol_date"  >'.$dol_date.'</td>';	
								echo '<td class="designation" id="designation"  >'.$value['designation'].'</td>';	
								echo '<td class="clientname" id="clientname">'.$value['clientname'].'</td>';	
								echo '<td class="Process" id="Process">'.$value['Process'].'</td>';
								echo '<td class="sub_process" id="sub_process">'.$value['sub_process'].'</td>';	
								echo '<td class="AccountHead" id="AccountHead">'.$value['AccountHead'].'</td>';		
								echo '<td class="ReportsTo" id="ReportsTo">'.$value['ReportsTo'].'</td>';	
								echo '<td class="rsnofleaving" id="rsnofleaving">'.$value['rsnofleaving'].'</td>';
								echo '<td class="rejoin_status" id="rejoin_status">'.$rejoin_status.'</td>';
								//echo '<td class="rejoin_status" id="rejoin_status">'.$value['new_client_master']['oh'].'</td>';
								//echo '<td class="rejoin_status" id="rejoin_status">'.$value['new_client_master']['account_head'].'</td>';
									
								?>
								<input type='hidden' name="rstatus" id="rstatus" value="<?php echo $value['rejoin_status']; ?>" >
								<input type='hidden' name="account_head" id="account_head" value="<?php echo $value['account_head']; ?>" >
								<input type='hidden' name="oh" id="oh" value="<?php echo $value['oh']; ?>" >
								<input type='hidden' name="leavingDays" id="leavingDays" value="<?php echo $days; ?>" >
								<!--<td class="tbl__ID"><a  onclick="return getEditData('<?php echo $count; ?>');" ><img class="imgBtn imgBtnEdit editClass"  src="../Style/images/users_edit.png"/></a></td>	-->
						<?php 
						}	
							?>			       
					    </tbody>
						</table>
						  </div>
						</div>
						<?php
							 }
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

		$('#btnSave1').click(function(){
			validate=0;
			alert_msg="";
			var  search_empid=$('#search_empid').val().trim();
			if(search_empid==""){
				validate=1;
		     	alert_msg='<li> Employee ID should not be empty</li>';
			}
			
			
			 if(validate==1)
	      	{		      		
	      		/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;
					*/
				$(function(){ toastr.error(alert_msg) });
				return false;	
			}
		});
		
		$('#rejoin').click(function(){
			validate=0;
			alert_msg="";
			var  textremark=$('#textremark').val().trim();
			if(textremark==''){
				validate=1;
		     	alert_msg='<li> Comment should not be empty </li>';
			}else
			if(textremark.length<250){
				validate=1;
		     	alert_msg='<li> Remark should not be less than 250 characters </li>';
			}
			var  rstatus=$('#rstatus').val().trim();
		
			if(rstatus=='1'){
				validate=1;
		     	alert_msg='<li> Allready rejoined this employee</li>';
			}
			/*var  rsnofleaving=$('#rsnofleaving').html().trim();
			if(rsnofleaving=='ABSC'){
				validate=1;
		     	alert_msg='<li> Employee is ABSC, He can not rejoin within 90 days</li>';
			}*/
		/*	var  leavingDays=$('#leavingDays').val().trim();
			if(leavingDays>5){
				validate=1;
		     	alert_msg='<li>Releaving Days is more than 5 days</li>';
			}*/
			 if(validate==1)
	      	{	/*	      		
	      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(5000).fadeOut("slow");
	      		return false;*/
	      			$(function(){ toastr.error(alert_msg) });
					return false;
			}
		});
						      
		
	});
	
	
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>