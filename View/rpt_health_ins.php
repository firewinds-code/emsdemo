<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

if (isset($_POST['txt_dateFrom']) && $_POST['txt_dateFrom'] != "" && isset($_POST['txt_dateTo']) && $_POST['txt_dateTo'] != "" ) {
					$fromdate = date('Y-m-d', strtotime($_POST['txt_dateFrom']));
					$todate = date('Y-m-d', strtotime($_POST['txt_dateTo']));
					
					$sqlConnect = 'select * from health_ins_mail_log where  (cast(created_at as date) between cast("' . $fromdate . '" as date) and cast("' . $todate . '" as date))';
				} else {
					$sqlConnect = 'select * from health_ins_mail_log where (cast(created_at as date) between cast("' . date('Y-m-d') . '" as date) and cast("' . date('Y-m-d') . '" as date))';
				}

				$myDB = new MysqliDb();
				$result = $myDB->query($sqlConnect);	
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
<span id="PageTittle_span" class="hidden">Health Insurance Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Health Insurance Report</h4>				

<!-- Form container if any -->
<div class="schema-form-section row">
<div class="input-field col s12 m12" id="rpt_container">
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateFrom"  id="txt_dateFrom" required value="<?php echo ((isset($_POST['status'])) ? $_POST['txt_dateFrom'] : '') ?>" autocomplete="off" placeholder="From Date"/>
		</div>
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateTo"  id="txt_dateTo" required value="<?php echo ((isset($_POST['status'])) ? $_POST['txt_dateTo'] : '') ?>" autocomplete="off" placeholder="To Date"/>
		</div>
		
		<div class="input-field col s3 m3">			
		
			<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
			<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
		</div>
		
	</div>
</div>    	
  	 <div id="pnlTable">
    <?php 
    	
    	$myDB=new MysqliDb();
    	$result = $myDB->query($sqlConnect);
    	
		?>
		<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
		<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
			<!--<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">-->
			    <thead>
			        <tr>
			           <th>SN.</th> 
			           <th>EmployeeID</th>
			        	<th>EmployeeName</th>  
			        	<th>Email Id</th>  
			            <th>Status</th>
			            <th>Send On</th> 
			           
			           
			        </tr>
			    </thead>
			    <tbody>					        
			       <?php
			       $count=1;
			       if(count($result)>0){
			        foreach($result as $key=>$value){
			        	
			        	echo '<tr>';
							echo '<td  id="countc'.$count.'">'.$count.'</td>';						
							echo '<td  id="empid'.$value['EmployeeID'].'" class="div_tempCard">'.$value['EmployeeID'].'</td>';		
							echo '<td  id="empname'.$count.'" >'.$value['EmployeeName'].'</td>';
							echo '<td  id="emailid'.$count.'" >'.$value['emailid'].'</td>';					
							echo '<td  id="email_status'.$count.'"  >'.$value['email_status'].'</td>';
							echo '<td  id="pnname'.$count.'"  >'. date("F j, Y, g:i a",strtotime($value['created_at'])) .'</td>';
							
							
						
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
	function issueIdCard(empid){
		var txtEmployeeID= empid;
			$.ajax({url: "../Controller/OLReportIssueIdCard.php?EmpID="+txtEmployeeID, success: function(result){
		            if(result)
					{	
						var abc= $('#emptext'+txtEmployeeID).html('Issued');
						 alert_msg='Id Card issued successfully';
						 $(function(){ toastr.success(alert_msg); });
					} 
				}	
			});	
			var popup = window.open("../Controller/get_tempCard.php?EmpID="+empid, "popupWindow", "width=600px,height=600px,scrollbars=yes"); 
	}
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>