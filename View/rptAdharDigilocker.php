<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');	
$query='select id,location from location_master';
$myDB= new MysqliDb();
$location_array=array();
$result =$myDB->query($query);
$date_To=$date_From='';
if( isset($_POST['txt_dateTo']))
{
	$date_To = $_POST['txt_dateTo'];
	$date_From =$_POST['txt_dateFrom'];	
}
else
{
	$date_From=$date_To = date('Y-m-d',time()); 
}
foreach($result as $lval){
	$location_array[$lval['id']]=$lval['location'];
}
    $sql="SELECT distinct(p.EmployeeID),p.EmployeeName,p.location as location_id,e.dateofjoin DOJ, c.Process,des.designation,cm.client_name clientname,c.sub_process,dc.aadhar_source,dc.doc_stype,dc.createdon FROM ems.doc_details dc left join   `employee_map` e on dc.EmployeeID=e.EmployeeID 
left JOIN `personal_details` p ON ((p.`EmployeeID` = e.`EmployeeID`))
 LEFT JOIN `new_client_master` c ON ((c.`cm_id` = e.`cm_id`))
  LEFT JOIN `df_master` d ON ((d.`df_id` =e.`df_id`))
  LEFT JOIN `designation_master` des ON ((des.`ID` = d.`des_id`))
  LEFT JOIN `client_master` cm ON ((cm.`client_id` = c.`client_name`))
where dc.doc_stype = 'Aadhar Card'  ";	
 //and (dc.aadhar_source='chooseFromdigilocker' or dc.aadhar_source='FromdigilockerApp')
 
if(isset($_POST['txt_source']) && $_POST['txt_source']=='App')	
{
	 $sql.="  and dc.aadhar_source='FromdigilockerApp' ";
}
else if(isset($_POST['txt_source']) && $_POST['txt_source']=='Portal')	
{
	 $sql.="  and dc.aadhar_source='chooseFromdigilocker' ";
}else
{
	 $sql.=" and (dc.aadhar_source='chooseFromdigilocker' or dc.aadhar_source='FromdigilockerApp') ";
}


 $sql.=" and cast(dc.createdon as date) between '".$date_From."' and '".$date_To."' ";
 
// echo $sql;
?>

<script>
	$(document).ready(function(){
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});
		
		$('#myTable').DataTable({
		        dom: 'Bfrtip',
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
				    ],
				    "bProcessing" : true,
					"bDestroy" : true,
					"bAutoWidth" : true,
					"iDisplayLength": 10,
					"sScrollX" : "100%",
					"bScrollCollapse" : true,
					"bLengthChange" : false,
					"fnDrawCallback":function()
					{
						$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
					}
					
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Aadhar Upload From Digilocker Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Aadhar Upload From Digilocker Report</h4>				

<!-- Form container if any -->
<div class="schema-form-section row">
<div class="input-field col s12 m12" id="rpt_container">
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateFrom"  id="txt_dateFrom" value="<?php echo $date_From;?>" autocomplete="off"/>
		</div>
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateTo"  id="txt_dateTo" value="<?php echo $date_To;?>" autocomplete="off"/>
		</div>
		<div class="input-field col s3 m3">
			<Select name="txt_source"  id="txt_source" >
				<option value="All"  <?php if(isset($_POST['txt_source']) && $_POST['txt_source']=='All'){ echo "selected";  } ?>>All</option>
				<option value="App" <?php if(isset($_POST['txt_source']) && $_POST['txt_source']=='App'){ echo "selected";  } ?>>App</option>
				<option value="Portal"<?php if(isset($_POST['txt_source']) && $_POST['txt_source']=='Portal'){ echo "selected";  } ?>>Portal</option>
			</Select>
		</div>
		
		<div class="input-field col s3 m3">			
		
			<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
		</div>
		
	</div>
</div>  	
  	 <div id="pnlTable">
    <?php 
    	$myDB=new MysqliDb();
    	$result = $myDB->query($sql);
		?>
		<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
		<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
			    <thead>
			        <tr>
			           <th>SN.</th> 
			           <th>Location</th>
			           <th>EmployeeID</th>
			           <th>EmployeeName</th>
			           <th>DOJ</th>
			           <th>Designation</th>
			           <th>Client</th>
			           <th>Process</th>
			        	<th>Sub Process</th>  
			        	<th>Source</th>   
			        	<th>Datetime</th>   
			        </tr>
			    </thead>
			    <tbody>					        
			       <?php
			       $count=1;
			       if(count($result)>0){
			        foreach($result as $key=>$value)
			        {
			        	$lcation_name='';
			        	if($value['location_id']!=""){
			        		$lcation_name=$location_array[$value['location_id']];
			        		
			        	}
			        	if($value['aadhar_source']=="chooseFromdigilocker")
			        	{
			        		$aadhaarSource='Portal';
			        	}
			        	else if($value['aadhar_source']=="FromdigilockerApp")
			        	{
							$aadhaarSource='App'; 
						}
						else
						{
							$aadhaarSource='';
						}
			        	
			        	echo '<tr>';
							echo '<td  id="countc'.$count.'">'.$count.'</td>';
							echo '<td  id="location_id'.$count.'"  >'.$lcation_name.'</td>';						
							echo '<td   class="div_tempCard">'.$value['EmployeeID'].'</td>';		
							echo '<td  class="div_tempCard">'.$value['EmployeeName'].'</td>';
							echo '<td class="div_tempCard">'.$value['DOJ'].'</td>';
							echo '<td  class="div_tempCard">'.$value['designation'].'</td>';
							echo '<td  class="div_tempCard">'.$value['clientname'].'</td>';
							echo '<td   class="div_tempCard">'.$value['Process'].'</td>';	
							echo '<td  class="div_tempCard">'.$value['sub_process'].'</td>';		
							echo '<td  class="div_tempCard">'.$aadhaarSource.'</td>';		
							echo '<td  class="div_tempCard">'.$value['createdon'].'</td>';		
															
				echo '</tr>';
				$count++;
				}	
					}else{
						echo "<tr><td colspan='6'>Data not found</td></tr>";
					}
					?>			       
			    </tbody>
			</table>
		</div>
				
	
</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
 
<!--Content Div for all Page End -->  
</div>
<script>
	$(document).ready(function(){
	});
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>