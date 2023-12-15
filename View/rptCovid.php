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
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;


?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Quarantine Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4> Quarantine Report </h4>				

<!-- Form container if any -->
<div class="schema-form-section row" >

<script>
	$(function(){
	
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
									}
									,'pageLength'
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"sScrollY" : "192",
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() {
								
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





		<?php
		//if(isset($_POST['btn_view']))
		//{
			
			$sqlConnect="SELECT t1.*, concat(t2.process,'|',t2.sub_process) as process  FROM covid t1	 join new_client_master  t2 on t1.cps=t2.cm_id";
		
	
	$myDB=new MysqliDb();
	$result=$myDB->query($sqlConnect);
	if ($result) {
				?>
					<table id="myTable" class="data dataTable no-footer row-border"   style="width:100%;">

					<thead>
						<tr>
							<th>EmployeeID </th>
							<th>Employee Name</th>
							<th>Client/Process/Subprocess</th>
							<th>Reason</th>
							<th>Reporting Date</th>
							<th>Rejoining Date</th>
							<th>Certificate</th>
							<th>Remark</th>
							<th>Created By</th>
							<th>Created On</th>
							<th>Updated By</th>
							<th>Updated On</th>
							
						</tr>
					</thead>
					<tbody>
				<?php
			foreach ($result as $key=>$value) {
				echo '<tr>';
				echo '<td class="test_name">'.$value['empid'].'</td>';
				echo '<td class="test_name">'.$value['empname'].'</td>';
				echo '<td class="test_name">'.$value['process'].'</td>';
				echo '<td class="test_name">'.$value['reason'].'</td>';
				if($value['reporting_date']!="" && $value['reporting_date']!= Null){
				echo '<td class="testid">'.date("j F Y",strtotime($value['reporting_date'])).'</td>';
				}else{
						echo '<td class="testid"></td>';
				}
				if($value['rejoining_date']!="" && $value['rejoining_date']!= Null){
				echo '<td class="testid">'.date("j F Y",strtotime($value['rejoining_date'])).'</td>';
				}else{
						echo '<td class="testid"></td>';
				}
			if($value['certificate']!="")
				{
					echo '<td class="manage_item" style="text-align:center"><a class=" waves-effect waves-light btn-small" href="../MedicalCertificate/'.$value['certificate'].'" target="_blank"> <i class="fa fa-download"></i>Download </a><br><br> ';
					
						if($value['certificate2']!="")
						{
							echo '<a class=" waves-effect waves-light btn-small" href="../MedicalCertificate/'.$value['certificate2'].'" target="_blank"> <i class="fa fa-download"></i>Download </a>';
						}
				
					echo '</td>';
				}
				else{
					echo '<td class="manage_item" style="text-align:center">Download </td>';
				}
				
				echo '<td class="remarks">'.$value['remarks'].'</td>';
				echo '<td class="doc_value">'.$value['created_by'].'</td>';
				echo '<td class="testid">'.date("j F Y",strtotime($value['created_at'])).'</td>';
				echo '<td class="doc_value">ssss'.$value['updated_by'].'</td>';
			
			if($value['updated_at']!="" && $value['updated_at']!= Null){
				echo '<td class="testid">'.date("j F Y",strtotime($value['updated_at'])).'</td>';
				}else{
						echo '<td class="testid"></td>';
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
	?>
</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>
<script>
	$(function(){
		
	});
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>