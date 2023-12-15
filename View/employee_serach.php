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
ini_set('display_errors', '1'); 
ini_set('log_errors','On');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
$last_to=$last_from=$last_to=$dept=$emp_nam=$emp_empname=$status=$emp_emer=$emp_adhar=$emp_alt=$emp_ofcmail=$emp_mobile=$searchBy=$proc='';
$classvarr="'.byID'";
// Trigger Button-Save Click Event and Perform DB Action
if(isset($_POST['btn_ED_Search']))
{
	$last_from=(isset($_POST['txt_ED_joindate_to'])? $_POST['txt_ED_joindate_to'] : null);
	$last_to=(isset($_POST['txt_ED_joindate_from'])? $_POST['txt_ED_joindate_from'] : null);
	$dept=(isset($_POST['txt_ED_Dept'])? $_POST['txt_ED_Dept'] : null);
	$emp_nam=(isset($_POST['ddl_ED_Emp_Name'])? $_POST['ddl_ED_Emp_Name'] : null);
	$emp_adhar=(isset($_POST['ddl_ED_Emp_Adhar'])? $_POST['ddl_ED_Emp_Adhar'] : null);
	$emp_emer=(isset($_POST['ddl_ED_Emp_Emer'])? $_POST['ddl_ED_Emp_Emer'] : null);
	$emp_alt=(isset($_POST['ddl_ED_Emp_alt'])? $_POST['ddl_ED_Emp_alt'] : null);
	$emp_ofcmail=(isset($_POST['ddl_ED_Emp_ofcmail'])? $_POST['ddl_ED_Emp_ofcmail'] : null);
	$emp_mobile=(isset($_POST['ddl_ED_Emp_Mobile'])? $_POST['ddl_ED_Emp_Mobile'] : null);
	$emp_mail=(isset($_POST['ddl_ED_Emp_Email'])? $_POST['ddl_ED_Emp_Email'] : null);
	$emp_empname=(isset($_POST['ddl_ED_Emp_EmpName'])? $_POST['ddl_ED_Emp_EmpName'] : null);
	$proc=$_POST['txt_ED_Proc'];
	$searchBy=$_POST['searchBy'];
	$status=(isset($_POST['txt_ED_Status'])? $_POST['txt_ED_Status'] : null);
	if($_POST['searchBy']=='By ID')
	{
		$classvarr="'.byID'";
	}
	else if($_POST['searchBy']=='By Adhar')
	{
		$classvarr="'.byAdhar'";
	}
	else if($_POST['searchBy']=='By Emergency')
	{
		$classvarr="'.byEmergency'";
	}
	else if($_POST['searchBy']=='By OfcMail')
	{
		$classvarr="'.byOfcMail'";
	}
	else if($_POST['searchBy']=='By Alternate')
	{
		$classvarr="'.byAlternate'";
	}
	else if($_POST['searchBy']=='By Mobile')
	{
		$classvarr="'.byMobile'";
	}
	else if($_POST['searchBy']=='By Mail')
	{
		$classvarr="'.byMail'";
	}
	else if($_POST['searchBy']=='By Name')
	{
		$classvarr="'.byName'";
	}
	
	
	else if($_POST['searchBy']=='By Date')
	{
		
		$classvarr="'.byDate,.byStatus'";
	}
	else if($_POST['searchBy']=='By Dept')
	{
		$classvarr="'.byDept,.byStatus'";
	}
	else if($_POST['searchBy']=='By Process')
	{
		$classvarr="'.byProc,.byStatus'";
	}
	else if($_POST['searchBy']=='By Reporting')
	{
		$classvarr="'.ByRepor,.byStatus'";
	}
	else
	{
		$classvarr="'.unmap'";
	}	
}
?>
<script>
	$(document).ready(function(){
		$('#txt_ED_joindate_to').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_ED_joindate_from').datetimepicker({ format:'Y-m-d', timepicker:false});
		
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
						        /*  
						        {
						            extend: 'csv',
						            text: 'CSV',
						            extension: '.csv',
						            exportOptions: {
						                modifier: {
						                    page: 'all'
						                }
						            },
						            title: 'table'
						        }, 						         
						        'print',*/
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
		   	$('.byID').addClass('hidden');
		   	$('.byAdhar').addClass('hidden');
		   	$('.byEmergency').addClass('hidden');
		   	$('.byOfcMail').addClass('hidden');
		   	$('.byAlternate').addClass('hidden');
		   	$('.byMobile').addClass('hidden');
		   	$('.byMail').addClass('hidden');
		   	$('.byDate').addClass('hidden');
		   	$('.byDept').addClass('hidden');
		   	$('.byProc').addClass('hidden');
		   	$('.byName').addClass('hidden');
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   	$('#searchBy').change(function(){
		   		$('.byID').addClass('hidden');
		   		$('.byAdhar').addClass('hidden');
		   		$('.byEmergency').addClass('hidden');
		   		$('.byOfcMail').addClass('hidden');
		   		$('.byAlternate').addClass('hidden');
		   		$('.byMobile').addClass('hidden');
		   		$('.byMail').addClass('hidden');
			   	$('.byDate').addClass('hidden');
			   	$('.byDept').addClass('hidden');
			   	$('.byProc').addClass('hidden');
			   	$('#txt_ED_joindate_to').val('');
			   	$('#txt_ED_joindate_from').val('');
			   	$('#txt_ED_Dept').val('NA');
			   	$('#ddl_ED_Emp_Name').val('');
			   	$('#ddl_ED_Emp_Adhar').val('');
			   	$('#ddl_ED_Emp_Emer').val('');
			   	$('#ddl_ED_Emp_alt').val('');
			   	$('#ddl_ED_Emp_ofcmail').val('');
			   	$('#ddl_ED_Emp_Mobile').val('');
			   	$('#ddl_ED_Emp_Email').val('');
			   	$('#txt_ED_Proc').val('NA');
			   	$('.ByRepor').addClass('hidden');
			   	$('.byStatus').removeClass('hidden');
			   	$('.byName').addClass('hidden');
			   	$('#ddl_ED_Emp_EmpName').val('');
		   		if($(this).val()=='By ID')
		   		{
					$('.byID').removeClass('hidden');
					$('.byStatus').addClass('hidden');
				}
				else if($(this).val()=='By Adhar')
		   		{
					$('.byAdhar').removeClass('hidden');
					$('.byStatus').addClass('hidden');
				}
				else if($(this).val()=='By Emergency')
		   		{
					$('.byEmergency').removeClass('hidden');
					$('.byStatus').addClass('hidden');
				}
				else if($(this).val()=='By OfcMail')
		   		{
					$('.byOfcMail').removeClass('hidden');
					$('.byStatus').addClass('hidden');
				}
				else if($(this).val()=='By Alternate')
		   		{
					$('.byAlternate').removeClass('hidden');
					$('.byStatus').addClass('hidden');
				}
				else if($(this).val()=='By Mobile')
		   		{
					$('.byMobile').removeClass('hidden');
					$('.byStatus').addClass('hidden');
				}
				else if($(this).val()=='By Mail')
		   		{
					$('.byMail').removeClass('hidden');
					$('.byStatus').addClass('hidden');
				}
				else if($(this).val()=='By Date')
		   		{
					$('.byDate').removeClass('hidden');
				}
				else if($(this).val()=='By Dept')
		   		{
					$('.byDept').removeClass('hidden');
				}
				else if($(this).val()=='By Process')
				{
					$('.byProc').removeClass('hidden');
				}
				else if($(this).val()=='By Reporting')
				{
					$('.ByRepor').removeClass('hidden');
				}
				else if($(this).val()=='Unmapped')
				{
					$('.byStatus').addClass('hidden');
				}
				else if($(this).val()=='By Name')
				{
					
					$('.byName').removeClass('hidden');$('.byStatus').addClass('hidden');
					
				}
				
		   	});
	});
</script>

<div id="content" class="content">
<span id="PageTittle_span" class="hidden">Employee Search</span>
	<div class="pim-container">
			<div class="form-div">
			 <h4>Employee Search</h4>	
			 <div class="schema-form-section row">
			    <div class="" >
			 	<div class="input-field col s6 m6 8">
							<select name="searchBy" id="searchBy" class="input-field col s12 m12 l6" title="Select Search Option">
				     			<option <?php if($searchBy=='By ID'){echo 'selected';} ?> value="By ID">Employee ID</option>
				     			<option <?php if($searchBy=='By Name'){echo 'selected';} ?> value="By Name">Employee Name</option>
				     			<option <?php if($searchBy=='By Date'){echo 'selected';} ?> value="By Date">Joinning Date</option>
				     			<option <?php if($searchBy=='By Dept'){echo 'selected';} ?> value="By Dept">Department</option>
				     			<option <?php if($searchBy=='By Process'){echo 'selected';} ?> value="By Process">Process</option>
				     			<option <?php if($searchBy=='By Adhar'){echo 'selected';} ?> value="By Adhar">Adhar Card Number</option>
				     			<option <?php if($searchBy=='By Mobile'){echo 'selected';} ?> value="By Mobile">Mobile Number</option>
				     			<option <?php if($searchBy=='By Alternate'){echo 'selected';} ?> value="By Alternate">Alternate Number</option>
				     			<option <?php if($searchBy=='By Emergency'){echo 'selected';} ?> value="By Emergency">Emergency Contact Number</option>
				     			<option <?php if($searchBy=='By Mail'){echo 'selected';} ?> value="By Mail">Email ID</option>
				     			<option <?php if($searchBy=='By OfcMail'){echo 'selected';} ?> value="By OfcMail">Office Email ID</option>
				     			<!--<option <?php if($searchBy=='By Reporting'){echo 'selected';} ?> value="By Reporting">Reporting</option>-->
				     			
				     			<?php 
				     				if($_SESSION['__user_type']=='ADMINISTRATOR'||$_SESSION['__user_type']=='HR' ||$_SESSION['__user_type']=='CENTRAL MIS' || $_SESSION['__user_type']=='AUDIT')
				     				{
				     					if($searchBy=='Unmapped')
				     					{
				     						echo '<option selected >Unmapped</option>';
				     					} 
				     					else
				     					{
											echo '<option >Unmapped</option>';
										}
										
									}				     				
				     			?>
			     			</select> 
							<label title="" for="searchBy" class="active-drop-down active">Search By</label>
				</div>
			 </div>
			    <div class=" byID">
			      <!--<label for="ddl_ED_Emp_Name">Emp. ID :</label>-->
			      <div class="input-field col s6 m6 8">
			      	 <input type="text" id="ddl_ED_Emp_Name" name="ddl_ED_Emp_Name"  title="Enter Employee ID Must Start With CE and Not Less Then 10 Char" value="<?php echo $emp_nam;?>" >
			      	 <label for="ddl_ED_Emp_Name"> Employee ID</label>
			      </div>
			     
			</div>
			<div class="byAdhar">
			      <!--<label for="ddl_ED_Emp_Adhar">Emp. Adhar :</label>-->
			      <div class="input-field col s6 m6 8">
			      	 <input type="text" id="ddl_ED_Emp_Adhar" name="ddl_ED_Emp_Adhar"  title="Enter Employee Adhar Must  Not Less Then 12 Number" value="<?php echo $emp_adhar;?>" >
			      	 <label for="ddl_ED_Emp_Adhar"> Employee Adhar</label>
			      </div>
			     
			</div>
			<div class="byEmergency">
			      <!--<label for="ddl_ED_Emp_Emer">Emp. Emergency Number :</label>-->
			      <div class="input-field col s6 m6 8">
			      	 <input type="text" id="ddl_ED_Emp_Emer" name="ddl_ED_Emp_Emer"  title="Enter Employee Emergency Number Must  Not Less Then 10 Number" value="<?php echo $emp_emer;?>" >
			      	 <label for="ddl_ED_Emp_Emer"> Employee Emergency Number</label>
			      </div>
			     
			</div>
			<div class="byOfcMail">
			      <!--<label for="ddl_ED_Emp_ofcmail">Emp. Office Mail :</label>-->
			      <div class="input-field col s6 m6 8">
			      	 <input type="text" id="ddl_ED_Emp_ofcmail" name="ddl_ED_Emp_ofcmail"  title="Enter Employee Office Mail ID" value="<?php echo $emp_ofcmail;?>" ><span id="mailcheck1" style="color: rgb(255, 0, 0);margin: 3px;padding: 3px;line-height: 30px;text-shadow: 0px 0px 1px #8A8787;"></span>
			      	 <label for="ddl_ED_Emp_ofcmail"> Employee Office Mail</label>
			      </div>
			     
			</div>
			<div class="byAlternate">
			      <!--<label for="ddl_ED_Emp_alt">Emp. Alternate Number :</label>-->
			      <div class="input-field col s6 m6 8">
			      	 <input type="text" id="ddl_ED_Emp_alt" name="ddl_ED_Emp_alt"  title="Enter Employee Alternate Number Must  Not Less Then 10 Number" value="<?php echo $emp_alt;?>" >
			      	 <label for="ddl_ED_Emp_alt"> Employee Alternate Number</label>
			      </div>
			     
			</div>
			<div class="byMobile">
			      <!--<label for="ddl_ED_Emp_Mobile">Emp. Mobile :</label>-->
			      <div class="input-field col s6 m6 8">
			      	 <input type="text" id="ddl_ED_Emp_Mobile" name="ddl_ED_Emp_Mobile"  title="Enter Employee Mobile Number Must Not Less Then 10 Number" value="<?php echo $emp_mobile;?>" >
			      	 <label for="ddl_ED_Emp_Mobile"> Employee Mobile</label>
			      </div>
			     
			</div>
			<div class="byMail">
			      <!--<label for="ddl_ED_Emp_Email">Emp. Email :</label>-->
			      <div class="input-field col s6 m6 8">
			      	 <input type="text" id="ddl_ED_Emp_Email" name="ddl_ED_Emp_Email"  title="Enter Employee Mail ID " value="<?php echo $emp_mail;?>" >
			      	 <span id="mailcheck" style="color: rgb(255, 0, 0);margin: 3px;padding: 3px;line-height: 30px;text-shadow: 0px 0px 1px #8A8787;"></span>
			      	 <label for="ddl_ED_Emp_Email"> Employee Email</label>
			      </div>
			     
			</div>
			    <div class=" byDate" >
			      <!--<label for="txt_ED_joindate_from">Joinning Date :</label>-->
			     		<div class="input-field col s6 m6 8">
			     		    <div class="input-field col s6 m6 8" style="margin: 0px;">
			     		    <input type="text"  id="txt_ED_joindate_from"value="<?php echo $last_to ; ?>" name="txt_ED_joindate_from" title="Select Join  From Date">  
			     		    <label for="txt_ED_joindate_from"> Date From</label>
			     		    </div>
			     		    <div class="input-field col s6 m6 8" style="margin: 0px;">
				            <input type="text"  id="txt_ED_joindate_to" value="<?php echo $last_from; ?>" name="txt_ED_joindate_to" title="Select Join Date To "/> 
				            <label for="txt_ED_joindate_to"> Date To</label>
				            </div>
				             
				         </div> 
			    </div>
			    <div class=" byName">
			      <!--<label for="ddl_ED_Emp_EmpName">Emp. Name :</label>-->
			      <div class="input-field col s6 m6 8">
			      	<input type="text"  id="ddl_ED_Emp_EmpName" name="ddl_ED_Emp_EmpName" title="Enter Employee Name for Search Must have 3 Chars" value="<?php echo $emp_empname;?>" >
			      	<label for="ddl_ED_Emp_EmpName"> Employee Name</label>
			     </div>
			    </div>
			    <div class=" byDept">
			      <!--<label for="txt_ED_Dept">Dept. Name :</label>-->
			      <div class="input-field col s6 m6 8">
			      <select class="" id="txt_ED_Dept" name="txt_ED_Dept"  title="Select Department  Name">
			      <option id="txt_ED_Dept" value="NA">Select Department</option>		      
			              <?php 
					      	$sqlBy ='call get_dept_by_UserType("'.$_SESSION['__user_logid'].'","'.$_SESSION['__user_type'].'")'; 
							$myDB=new MysqliDb();
							$resultBy = $myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if(empty($mysql_error)){
								
								$selected='';													
								foreach($resultBy as $key=>$value){
								if($dept==$value['dept_id'])
								{
									$selected='selected';
								}
								else
								{
									$selected='';
								}
									echo '<option value="'.$value['dept_id'].'" '.$selected.'>'.$value['dept_name'].'</option>';
								}

							}
			      ?>
			      </select>
			      <label title="" for="txt_ED_Dept" class="active-drop-down active">Department</label>
			      </div>
			    </div>
			    <div class=" byProc">
			     <!-- <label for="txt_ED_Proc">Process Name :</label>-->
			      <div class="input-field col s6 m6 8">
			      <select  id="txt_ED_Proc" name="txt_ED_Proc"  title="Enter Process Name">
			      <option id="txt_ED_Proc" value="NA">---Select Process---</option>			      
			              <?php 
						   $sqlBy ='call get_proccess_by_UserType("'.$_SESSION['__user_logid'].'","'.$_SESSION['__user_type'].'","'.$_SESSION["__location"].'")';
							$myDB=new MysqliDb();
							$resultBy = $myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if(empty($mysql_error))
							{
								$selected='';													
								foreach($resultBy as $key=>$value){
									if($proc==$value['process'])
									{
										$selected='selected';
									}
									else
									{
										$selected='';
									}
									echo '<option value="'.$value['process'].'" '.$selected.'>'.$value['process'].'</option>';
								}
                           }
			      ?>
			      </select>
			    	<label title="" for="txt_ED_Proc" class="active-drop-down active">Process Name</label>
			    	</div>
			    </div>
			    <div class=" byStatus hidden">
			      <!--<label for="txt_ED_Status">Status :</label>-->
				    <div class="input-field col s6 m6 8">
				      
				      <select class="input-field col s12 m12 l6" id="txt_ED_Status" name="txt_ED_Status" title="Select Employee Status">
					      <option value="Active" <?php if($status=='Active'){echo ' selected';}?>>Active Employee</option>			      
					      <option value="InActive" <?php if($status=='InActive'){echo ' selected';}?>>InActive Employee</option>			      
				      </select>
				      <label title="" for="searchBy" class="active-drop-down active">Search By</label>
				    </div>
			    </div>
			    <div class="input-field col s12 m12 right-align">
			    	<button type="submit" name="btn_ED_Search" title="Click Here To Get Search Result" id="btn_ED_Search" class="btn waves-effect waves-green">Search</button>
			    	<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
			    </div>
			    
			  	 <div id="pnlTable">
			    <?php 
			    
			    if(isset($_POST['btn_ED_Search']))
			    {
					$doj1=$_POST['txt_ED_joindate_to'];
					$doj2=$_POST['txt_ED_joindate_from'];
					$name=$_POST['ddl_ED_Emp_Name'];
					$adhar=$_POST['ddl_ED_Emp_Adhar'];
					$emer=$_POST['ddl_ED_Emp_Emer'];
					$alt=$_POST['ddl_ED_Emp_alt'];
					$ofcmail=$_POST['ddl_ED_Emp_ofcmail'];
					$mobile=$_POST['ddl_ED_Emp_Mobile'];
					$mail=$_POST['ddl_ED_Emp_Email'];
					$emp_empname=$_POST['ddl_ED_Emp_EmpName'];
					$searchBy=$_POST['searchBy'];
					$dept=$_POST['txt_ED_Dept'];
					$proc=$_POST['txt_ED_Proc'];
					//$_SESSION["__user_type"]=='ADMINISTRATOR')
					$status=$_POST['txt_ED_Status'];
						if($_SESSION['__user_type']=='AUDIT')
						{
							$_user_Type ='ADMINISTRATOR';
						}
						else
						{
							$_user_Type =$_SESSION['__user_type'];	
						}
					
					
					if($_POST['searchBy']=='By ID')
					{
						$sqlConnect = 'call getemp_serach_byID("'.$name.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$_SESSION["__location"].'")'; 
					}
					else if($_POST['searchBy']=='By Adhar')
					{
						$sqlConnect = 'call getemp_serach_byAdhar("'.$adhar.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$_SESSION["__location"].'")'; 
					}
					else if($_POST['searchBy']=='By Emergency')
					{
						$sqlConnect = 'call getemp_serach_byEmergency("'.$emer.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$_SESSION["__location"].'")'; 
					}
					else if($_POST['searchBy']=='By Alternate')
					{
						$sqlConnect = 'call getemp_serach_byAlt("'.$alt.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$_SESSION["__location"].'")';
					}
					else if($_POST['searchBy']=='By Mobile')
					{
						$sqlConnect = 'call getemp_serach_byMobile("'.$mobile.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$_SESSION["__location"].'")'; 
					}
					else if($_POST['searchBy']=='By Mail')
					{
						$sqlConnect = 'call getemp_serach_byMail("'.$mail.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$_SESSION["__location"].'")'; 
					}
					else if($_POST['searchBy']=='By OfcMail')
					{
						$sqlConnect = 'call getemp_serach_byOfcmail("'.$ofcmail.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$_SESSION["__location"].'")'; 
					}
					else if($_POST['searchBy']=='By Name')	
					{
						$sqlConnect = 'call getemp_serach_byName("'.$emp_empname.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$_SESSION["__location"].'")'; 
					}
					else if($_POST['searchBy']=='By Date')
					{
						$sqlConnect = 'call getemp_serach_byDate("'.$doj2.'","'.$doj1.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$status.'","'.$_SESSION["__location"].'")'; 
					}
					else if($_POST['searchBy']=='By Dept')
					{
						$sqlConnect = 'call getemp_serach_byDept("'.$dept.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$status.'","'.$_SESSION["__location"].'")'; 
					}
					else if($_POST['searchBy']=='Unmapped')	
					{
						$sqlConnect='call get_Unmaped("'.$_SESSION["__location"].'","'.$_user_Type.'")';
					}
					else if($_POST['searchBy']=='By Process')	
					{
						$sqlConnect='call getemp_serach_byProc("'.$proc.'","'.$_user_Type.'","'.$_SESSION['__user_logid'].'","'.$status.'","'.$_SESSION["__location"].'")';
					}
					else if($_POST['searchBy']=='By Reporting')	
					{
						$sqlConnect='call getemp_serach_byreporting("'.$_user_Type.'","'.$status.'")';
					}
					
					$myDB=new MysqliDb();
					$result=$myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if(empty($mysql_error)){?>
						
			   			 <div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						  <div class=""  >																											                                     <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						        <?php 
							        if($_POST['searchBy']=='Unmapped')	
									{
						        ?>
						            <th> Employee ID </th>
						            <th> Name </th>
						            <th> Father Name </th>
						            <th> Mother Name </th>
						        <?php 
						        	}
						        	else
						        	{
										?>
										<th> Employee ID </th>
							            <th> Name </th>
							            <th> Designation </th>
							            <th> Process </th>
							            <th> Sub Process </th>
							            <th> Reports To </th>
							            <th> QA Ops</th>
										<?php 
									}
						        ?>  
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					        foreach($result as $key=>$value){
							echo '<tr>';							
							if($_POST['searchBy']=='Unmapped')	
							{
								if($_SESSION['__user_type']=='ADMINISTRATOR'||$_SESSION['__user_type']=='HR' ||$_SESSION['__user_type']=='CENTRAL MIS' ||$_SESSION['__user_type']=='AUDIT')
					     		{
					     			echo '<td class="EmployeeID"><a href="'.URL.'View/empsave?empid='.$value['EmployeeID'].'" class="blue-text darken-3"><b>'.$value['EmployeeID'].'</b></a></td>';
					     		}
					     		else
					     		{
									echo '<td class="EmployeeID"><a href="'.URL.'View/info?empid='.$value['EmployeeID'].'"  class="blue-text darken-3"><b>'.$value['EmployeeID'].'</b></a></td>';
								}
								echo '<td class="EmployeeName">'.$value['EmployeeName'].'</td>';
								echo '<td class="FatherName">'.$value['FatherName'].'</td>';
								echo '<td class="MotherName">'.$value['MotherName'].'</td>';			
							
							}
							else
							{
								if($_SESSION['__user_type']=='ADMINISTRATOR'||$_SESSION['__user_type']=='HR' ||$_SESSION['__user_type']=='CENTRAL MIS' || $_SESSION['__user_type']=='AUDIT')
					     		{
					     			echo '<td class="EmployeeID"><a href="'.URL.'View/empsave?empid='.$value['EmployeeID'].'" class="blue-text darken-3"><b>'.$value['EmployeeID'].'</b></a></td>';
					     		}
					     		else
					     		{
									echo '<td class="EmployeeID"><a href="'.URL.'View/info?empid='.$value['EmployeeID'].'" class="blue-text darken-3"><b>'.$value['EmployeeID'].'</b></a></td>';
								}
								echo '<td class="EmployeeName">'.$value['EmployeeName'].'</td>';
								echo '<td class="designation">'.$value['designation'].'</td>';
								echo '<td class="process">'.$value['process'].'</td>';
								echo '<td class="subprocess">'.$value['subprocess'].'</td>';
								echo '<td class="ReportTo">'.$value['ReportTo'].'</td>';
								echo '<td class="Quality">'.$value['Quality'].'</td>';				
							
							}
							
							echo '</tr>';
							}	
							?>			       
					    </tbody>
						</table>
						  </div>
						</div>
						<?php
							 }
						
						else
						{
							echo "<script>$(function(){ toastr.info('No Records Found ".$error."'); }); </script>";
						} 
					}
					?>
				</div>
			  </div>
		</div>
	</div>		 
</div>

<script>
	$(document).ready(function(){
		$('#alert_msg_close').click(function(){
			$('#alert_message').hide();
		});
		if($('#alert_msg').text()=='')
		{
			$('#alert_message').hide();
		}
		
		$('#btn_ED_Search').click(function(){
			var validate=0;
		        var alert_msg='';
		        
		        $('#ddl_ED_Emp_Name').removeClass('has-error');
		        $('#ddl_ED_Emp_Adhar').removeClass('has-error');
		        $('#ddl_ED_Emp_Emer').removeClass('has-error');
		        $('#ddl_ED_Emp_alt').removeClass('has-error');
		        $('#ddl_ED_Emp_ofcmail').removeClass('has-error');
		        $('#ddl_ED_Emp_Mobile').removeClass('has-error');
		        $('#ddl_ED_Emp_Email').removeClass('has-error');
		        $('#ddl_ED_Emp_EmpName').removeClass('has-error');
		        $('#txt_ED_joindate_from').removeClass('has-error');
		        $('#txt_ED_joindate_to').removeClass('has-error');
		        $('#ddl_ED_Department').removeClass('has-error');
		        $('#txt_ED_Dept').removeClass('has-error');
		        $('#txt_ED_Proc').removeClass('has-error');
		        
		        if($('#searchBy').val()=='By ID')
		        {
					 if($('#ddl_ED_Emp_Name').val()=='')
			        {
						$('#ddl_ED_Emp_Name').addClass('has-error');
						if ($('#spanMessage_empid').size() == 0) {
				            $('<span id="spanMessage_empid" class="help-block"></span>').insertAfter('#ddl_ED_Emp_Name');
				        }

				        $('#spanMessage_empid').html('Employee Id can not be Empty');
						validate=1;
						
					}
				}
				if($('#searchBy').val()=='By Dept')
		        {
					 if($('#txt_ED_Dept').val()=='NA')
			        {
						$('#txt_ED_Dept').addClass('has-error');
						if ($('#spanMessage_empdep').size() == 0) {
				            $('<span id="spanMessage_empdep" class="help-block"></span>').insertAfter('#txt_ED_Dept');
				        }

				        $('#spanMessage_empdep').html('Employee Depertment can not be Empty');
						validate=1;
						
					}
				}
				if($('#searchBy').val()=='By Process')
		        {
					 if($('#txt_ED_Proc').val()=='NA')
			        {
						$('#txt_ED_Proc').addClass('has-error');
						if ($('#spanMessage_empprc').size() == 0) {
				            $('<span id="spanMessage_empprc" class="help-block"></span>').insertAfter('#txt_ED_Proc');
				        }

				        $('#spanMessage_empprc').html('Employee Process can not be Empty');
						validate=1;
						
					}
				}
				if($('#searchBy').val()=='By Adhar')
		        {
					 if($('#ddl_ED_Emp_Adhar').val().length < 12)
			        {
						$('#ddl_ED_Emp_Adhar').addClass('has-error');
						if ($('#spanMessage_empadhar').size() == 0) {
				            $('<span id="spanMessage_empadhar" class="help-block"></span>').insertAfter('#ddl_ED_Emp_Adhar');
				        }

				        $('#spanMessage_empadhar').html('Employee Adhar atleast contains 12 numbers');
						validate=1;
						
					}
				}
				if($('#searchBy').val()=='By Emergency')
		        {
					 if($('#ddl_ED_Emp_Emer').val().length <10)
			        {
						$('#ddl_ED_Emp_Emer').addClass('has-error');
						if ($('#spanMessage_empemg').size() == 0) {
				            $('<span id="spanMessage_empemg" class="help-block"></span>').insertAfter('#ddl_ED_Emp_Emer');
				        }

				        $('#spanMessage_empemg').html('Employee Emergency Number atleast contains 10 numbers');
						validate=1;
						
					}
				}
				if($('#searchBy').val()=='By Alternate')
		        {
					 if($('#ddl_ED_Emp_alt').val().length < 10)
			        {
						$('#ddl_ED_Emp_alt').addClass('has-error');
						if ($('#spanMessage_empalt').size() == 0) {
				            $('<span id="spanMessage_empalt" class="help-block"></span>').insertAfter('#ddl_ED_Emp_alt');
				        }

				        $('#spanMessage_empalt').html('Employee Alternate Number atleast contains 10 numbers');
						validate=1;
						
					}
				}
				if($('#searchBy').val()=='By OfcMail')
		        {
					 if($('#ddl_ED_Emp_ofcmail').val()=='')
			        {
						$('#ddl_ED_Emp_ofcmail').addClass('has-error');
						if ($('#spanMessage_empofcmail').size() == 0) {
				            $('<span id="spanMessage_empofcmail" class="help-block"></span>').insertAfter('#ddl_ED_Emp_ofcmail');
				        }

				        $('#spanMessage_empofcmail').html('Employee Office Mail can not be Empty');
						validate=1;
						
					}
				}
				if($('#searchBy').val()=='By Mobile')
		        {
					 if($('#ddl_ED_Emp_Mobile').val().length < 10)
			        {
						$('#ddl_ED_Emp_Mobile').addClass('has-error');
						if ($('#spanMessage_empmobile').size() == 0) {
				            $('<span id="spanMessage_empmobile" class="help-block"></span>').insertAfter('#ddl_ED_Emp_Mobile');
				        }

				        $('#spanMessage_empmobile').html('Employee Mobile Number  atleast contains 10 numbers');
						validate=1;
						
					}
				}
				if($('#searchBy').val()=='By Mail')
		        {
					 if($('#ddl_ED_Emp_Email').val()=='')
			        {
			        	//alert('kavya');
						$('#ddl_ED_Emp_Email').addClass('has-error');
						if ($('#spanMessage_empmail').size() == 0) {
				            $('<span id="spanMessage_empmail" class="help-block"></span>').insertAfter('#ddl_ED_Emp_Email');
				        }

				        $('#spanMessage_empmail').html('Employee Mail can not be Empty');
						validate=1;
						
					}
				}
				if($('#searchBy').val()=='By Name')
		        {
					 
					if($('#ddl_ED_Emp_EmpName').val().length < 3)
			        {
						
						$('#ddl_ED_Emp_EmpName').addClass('has-error');
						validate=1;
						if ($('#spanMessage_empname').size() == 0) {
				            $('<span id="spanMessage_empname" class="help-block"></span>').insertAfter('#ddl_ED_Emp_EmpName');
				        }

				        $('#spanMessage_empname').html('Employee Name atleast contains 3 characters');
					}
				}
				else if($('#searchBy').val()=='By Date')
		        {
					if($('#txt_ED_joindate_from').val()=='')
			        {
						$('#txt_ED_joindate_from').addClass('has-error');
						validate=1;
						alert_msg+='<li> Date From can not be Empty </li>';
					}
					
					if($('#txt_ED_joindate_to').val()=='')
			        {
						$('#txt_ED_joindate_to').addClass('has-error');
						validate=1;
						alert_msg+='<li> Date To can not be Empty </li>';
					}
			      	
				}
				/*else if($('#searchBy').val()=='By Dept')
		        {
					if($('#txt_ED_Dept').val()=='NA')
			        {
						$('#txt_ED_Dept').addClass('has-error');
						validate=1;
						alert_msg+='<li> Department can not be Empty </li>';
					}
				}
				else if($('#searchBy').val()=='By Process')
		        {
					if($('#txt_ED_Proc').val()=='NA')
			        {
						$('#txt_ED_Proc').addClass('has-error');
						validate=1;
						alert_msg+='<li> Process can not be Empty </li>';
					}
				}*/
		        else if($('#searchBy').val()=='Unmapped')
				{
					if(!confirm('From This you get all the Employee which are not Maped...'))
					{
						validate=1;
						alert_msg+='<li> Control processed ...</li>';
					}
				}
				
		      	if(validate==1)
		      	{		      		
		      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
		      		$('#alert_message').show().attr("class","SlideInRight animated");
		      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;
				}
		});
		$('#div_error').removeClass('hidden');
	});
</script>


<script>
	$(document).ready(function(){
		
		$('#ddl_ED_Emp_Mobile,#ddl_ED_Emp_alt,#ddl_ED_Emp_Emer,#ddl_ED_Emp_Adhar').keydown(function(event) {
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
		function validateEmail(sEmail)
		{
			var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
			if (filter.test(sEmail)) {
				return true;
			} else {
				return false;
			}
		}
		$('#ddl_ED_Emp_ofcmail').keyup(function() {
			if ($(this).val()!="") {
				if (validateEmail($(this).val())) {
					var str=$(this).val();
					var words = str.split('@');
					if (words[1].toLowerCase()=='cogenteservices.com' || words[1].toLowerCase()=='cogenteservices.in') {
						$('#ddl_ED_Emp_ofcmail').removeClass('has-error').addClass('has-success');
						$('#mailcheck1').html('Valid Mail').css('color','green');
					} else {
						$('#ddl_ED_Emp_ofcmail').addClass('has-error');
						$('#mailcheck1').html('Invalid Mail').css('color','red');
					}
				} else {
					$('#ddl_ED_Emp_ofcmail').addClass('has-error');
					$('#mailcheck1').html('Invalid Mail').css('color','red');
				}
			}
		});
		 var str=$('#ddl_ED_Emp_ofcmail').val();
        	 if(str!=''){
		  		var words = str.split('@');
		  		if(words[1].toLowerCase()=='cogenteservices.com' || words[1].toLowerCase()=='cogenteservices.in'){
					$('#ddl_ED_Emp_ofcmail').removeClass('has-error').addClass('has-success');
					
				}else{
					$('#ddl_ED_Emp_ofcmail').addClass('has-error');
					alert_msg='Invalid office Email ID';
					validate=1;
				}
			}
			
			$('#ddl_ED_Emp_Email').keyup(function() {
			if (validateEmail($(this).val())) {
				$('#ddl_ED_Emp_Email').removeClass('has-error').addClass('has-success');
				$('#mailcheck').html('Valid Mail').css('color','green');
			} else {
				$('#ddl_ED_Emp_Email').addClass('has-error');
				$('#mailcheck').html('Invalid Mail').css('color','red');
			}
		});
	});
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
