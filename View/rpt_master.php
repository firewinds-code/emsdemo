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
	if($_SESSION["__user_type"]!='ADMINISTRATOR' &&  $_SESSION["__user_logid"] != 'CE10091236')
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
//print_r($_SESSION);
$classvarr="'.byID'";
$searchBy=$Year=$chk_task=$EmpStatus='';
?>



<script>
	$(document).ready(function(){
		//$('#txt_ED_joindate_to').datetimepicker({ format:'Y-m-d', timepicker:false});
		//$('#txt_ED_joindate_from').datetimepicker({ format:'Y-m-d', timepicker:false});
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
						        },
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
		   	$('.byID').addClass('hidden');
		   	$('.byDate').addClass('hidden');
		   	$('.byDept').addClass('hidden');
		   	$('.byProc').addClass('hidden');
		   	$('.byName').addClass('hidden');
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Master Data Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Master Data Report</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
<!--Form element model popup start-->
	
			  
			    	<div class="input-field col s12 m12" >
		
						<div class="input-field col s6 m6">
							
							<Select name="emp_status" id="emp_status" >
								<option value=''  >Select Status</option>
								<option value='Active' <?php if(isset($_POST['emp_status']) && $_POST['emp_status']=='Active') { echo "selected"; } ?> >Active</option>
								<option value='InActive' <?php if(isset($_POST['emp_status']) && $_POST['emp_status']=='InActive') { echo "selected"; } ?> >InActive</option>
							</Select>
							<label for="emp_status" class="active-drop-down active" >Status</label>
						</div>	
						
						<div class="input-field col s6 m6" id='DOJHS' style="display: none;">
							
							<Select  name="txt_dateYear"  id="txt_dateYear">
								<?php
								$year_range=date('Y');
								for($i=0;$i<=18;$i++){
									$year_range=($year_range-$i);
								?>
									<option <?php if($Year == $year_range) echo ' selected ' ;?> value='<?php echo $year_range; ?>' ><?php echo $year_range; ?></option>	
								<?php
								}
								?>	
								<option <?php if($Year == $year_range) echo ' selected ' ;?>  value='All'>All</option>	
							</Select>
							<label for="txt_dateYear" class="active-drop-down active" >DOJ Year </label>
								
						</div>
						
						
						<div class="input-field col s12 m12 right-align">			
						
							<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"><i class="fa fa-search"></i> Search</button>
						</div>
						
					</div>
				<?php
				IF(isset($_POST['btn_view']))
				{
					
					$Year =$_POST['txt_dateYear'];	
					$didplay='none';
					if( isset($_POST['emp_status']) && $_POST['emp_status']=='InActive')
					{
						$didplay='block';
					}
					$myDB=new MysqliDb();	
					if(isset($_POST['emp_status']))
					{
						$EmpStatus=$_POST['emp_status'];
					}
					if($EmpStatus=='Active')
					{
						$chk_task=$myDB->query("select distinct t1.*,concat('\'',adhar.dov_value,'\'') as AdharCard,pan.dov_value as PanCard from View_EmpinfoActive t1 LEFT JOIN  (select distinct EmployeeID,dov_value from doc_details where doc_stype='Aadhar Card' group by EmployeeID order by createdon desc) adhar on t1.EmployeeID=adhar.EmployeeID LEFT JOIN (select distinct EmployeeID, dov_value from doc_details where doc_stype='PAN Card' group by EmployeeID order by createdon desc) pan  on t1.EmployeeID=pan.EmployeeID");
					}
					elseif($EmpStatus=='InActive')
					{
						if($Year=="")
						{
							$Year=date('Y');
						}
						$chk_task=$myDB->query("select distinct t1.*,concat('\'',adhar.dov_value,'\'') as AdharCard,pan.dov_value as PanCard from View_EmpinfoInActive t1 LEFT JOIN  (select distinct EmployeeID,dov_value from doc_details where doc_stype='Aadhar Card' group by EmployeeID order by createdon desc) adhar on t1.EmployeeID=adhar.EmployeeID LEFT JOIN (select distinct EmployeeID, dov_value from doc_details where doc_stype='PAN Card' group by EmployeeID order by createdon desc) pan  on t1.EmployeeID=pan.EmployeeID where Year(DOJ)='".$Year."'");
						
					}
				if(count($chk_task) > 0 && $chk_task)
				{
					?>
				<div id="pnlTable">
					<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
	 	 			
	 	 			<thead>
					<tr>
					<th>S No</th>
					<th>Employee ID</th>
					<th>Employee Name</th>
					<th>DOJ</th>
					<th>Considered Date of Deployment</th>
					<th>Designating</th>
					<th>Client</th>
					<th>Process</th>
					<th>Sub Process</th>
					<th>Employee Type</th>
					<th>Payroll Type</th>
					<th>Gender</th>
					<th>Father's Name</th>
					<th>Mother's Name</th>
					<th>Contact Number</th>
					<th>DOB</th>
					<th>Current Address</th>
					<th>Permanent Address</th>
					<th>CTC</th>
					<th>Take Home</th>
					<th>Adhar Card Number</th>
					<th>Pan Card Number</th>
					</tr>
					</thead>
					<tbody>	
				<?php
						
				$i=1;
				foreach($chk_task as $key=>$value)
				{	if($value['rt_type']=='1'){
						$emptype='Full Timer';
					}else
					if($value['rt_type']=='3'){
						$emptype='Part Timer';
					}
					else
					if($value['rt_type']=='4'){
						$emptype='Split';
					}
					echo '<tr>';
					echo '<td>'.$i.'</td>';
					echo '<td>'.$value['EmployeeID'].'</td>';
					echo '<td>'.$value['EmployeeName'].'</td>';
					echo '<td>'.$value['DOJ'].'</td>';
					echo '<td>'.$value['DOD'].'</td>';
					echo '<td>'.$value['designation'].'</td>';
					echo '<td>'.$value['client_name'].'</td>';
					echo '<td>'.$value['Process'].'</td>';
					echo '<td>'.$value['sub_process'].'</td>';
					echo '<td>'.$emptype.'</td>';
					echo '<td>'.$value['emptype'].'</td>';
					echo '<td>'.$value['Gender'].'</td>';
					echo '<td>'.$value['FatherName'].'</td>';
					echo '<td>'.$value['MotherName'].'</td>';
					echo '<td>'.$value['mobile'].'</td>';
					echo '<td>'.$value['DOB'].'</td>';
					echo '<td>'.$value['address'].'</td>';
					echo '<td>'.$value['address_p'].'</td>';
					echo '<td>'.$value['ctc'].'</td>';
					echo '<td>'.$value['takehome'].'</td>';
					echo '<td>'.$value['AdharCard'].'</td>';
					//echo '<td>'.'\''.$value['AdharCard'].'\''.'</td>';
					echo '<td>'.$value['PanCard'].'</td>';
								
					echo '</tr>';
					$i++;
				}
				?>
				</tbody>
				</table>		
				</div>
			
<?php	}
		else
		{
			if($EmpStatus!=""){
				$alert_msg='Data not found.';
			}
			
		}
}
					?>
<!--Form container End -->	
    </div>     
<!--Sub Main Div for all Page End -->
  </div>     
<!--Main Div for all Page End --> 
</div> 
<!--Content Div for all Page End -->  
</div>					
					
<script>
	$(document).ready(function(){
		
		$('#emp_status').change(function(){
				
				var emp_status=$('#emp_status').val();
				if(emp_status=='InActive'){
					$('#txt_dateYear').hide();
					$('#DOJHS').show();
				}else{
					$('#txt_dateYear').hide();
					$('#DOJHS').hide();
				}
		} )
		$('#btn_view').click( function(){
			validate=0;
			var emp_status=$('#emp_status').val();
			if(emp_status==""){
					validate=1;
					alert_msg='Please select status.';	  		    		
		      		
				
			}
			
			
			if(validate==1)
	      	{		      		
	      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}
		});	
	});
</script>		

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>