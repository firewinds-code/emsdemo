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


?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Self Undertaking Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4> Self Undertaking Report </h4>				

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
			
			$sqlConnect="select EmployeeID, EmployeeName, AcknowledgeDate from self_undertaking;";
		
	
	$myDB=new MysqliDb();
	$result=$myDB->query($sqlConnect);
	if ($result) {
				?>
					<table id="myTable" class="data dataTable no-footer row-border"   style="width:100%;">

					<thead>
						<tr>
							<th>EmployeeID </th>
							<th>Employee Name</th>
							<th>Acknowledge Date</th>
							
						</tr>
					</thead>
					<tbody>
				<?php
			foreach ($result as $key=>$value) {
				echo '<tr>';
				echo '<td class="test_name">'.$value['EmployeeID'].'</td>';
				echo '<td class="test_name">'.$value['EmployeeName'].'</td>';
				echo '<td class="test_name">'.$value['AcknowledgeDate'].'</td>';
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