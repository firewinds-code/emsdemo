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
foreach($result as $lval){
	$location_array[$lval['id']]=$lval['location'];
}
if( isset($_POST['txt_dateTo']))
{
	$date_To = $_POST['txt_dateTo'];
	$date_From =$_POST['txt_dateFrom'];	
}
else
{
	$date_From=$date_To = date('Y-m-d',time()); 
}
  $sql="select w.EmployeeName,w.clientname,w.designation,w.DOJ,w.Process,w.sub_process,w.function,t.EmployeeID,t.Mail_response,t.email_id,t.Created_date,t.location_id ,DATE_FORMAT(e.dol,'%Y-%m-%d') as dol  ,e.rsnofleaving from termination_ack t inner join whole_dump_emp_data w on t.EmployeeID=w.EmployeeID inner join exit_emp e on e.EmployeeID=t.EmployeeID   where  cast(t.Created_date as date) between '".$date_From."' and '".$date_To."' ";		
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
<span id="PageTittle_span" class="hidden">Termination Letter Status Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Termination Letter Status Report</h4>				

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
			<!--<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">-->
			    <thead>
			        <tr>
			           <th>SN.</th> 
			           <th>EmployeeID</th>
			           <th>EmployeeName</th>
			           <th>Function</th>
			           <th>Designation</th>
			           <th>DOJ</th>
			        	<th>Client</th>  
			        	<th>Process</th>  
			        	<th>Sub Process</th> 
			        	<th>Created Date</th>
			        	<th>Inactive Date</th>  
			        	<th>Inactive Reason</th>  
			        	<th>Mail Response</th>  
			        	<th>Email ID</th>  
			            <th>Location Name</th>
			           
			           
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
			        	echo '<tr>';
							echo '<td  id="countc'.$count.'">'.$count.'</td>';						
							echo '<td  id="empid'.$value['EmployeeID'].'" class="div_tempCard">'.$value['EmployeeID'].'</td>';		
							echo '<td  id="empid'.$value['EmployeeName'].'" class="div_tempCard">'.$value['EmployeeName'].'</td>';	
							echo '<td  id="empid'.$value['function'].'" class="div_tempCard">'.$value['function'].'</td>';	
							echo '<td  id="empid'.$value['designation'].'" class="div_tempCard">'.$value['designation'].'</td>';	
							echo '<td  id="empid'.$value['DOJ'].'" class="div_tempCard">'.$value['DOJ'].'</td>';	
							echo '<td  id="empid'.$value['clientname'].'" class="div_tempCard">'.$value['clientname'].'</td>';	
							echo '<td  id="empid'.$value['Process'].'" class="div_tempCard">'.$value['Process'].'</td>';	
							echo '<td  id="empid'.$value['sub_process'].'" class="div_tempCard">'.$value['sub_process'].'</td>';
							echo '<td  id="Created_date'.$count.'"  >'.date("M j, Y, g:i a",strtotime($value['Created_date'])).'</td>';
							echo '<td  id="dol'.$count.'" >'.$value['dol'].'</td>';
							echo '<td  id="rsnofleaving'.$count.'" >'.$value['rsnofleaving'].'</td>';
							echo '<td  id="Mail_response'.$count.'" >'.$value['Mail_response'].'</td>';
							echo '<td  id="email_id'.$count.'" >'.$value['email_id'].'</td>';					
							echo '<td  id="location_id'.$count.'"  >'.$lcation_name.'</td>';					
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