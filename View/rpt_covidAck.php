<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

	if(!isset($_SESSION['__user_logid'])  )
	{
		$location= URL.'Login'; 
		echo "<script>location.href='".$location."'</script>"; 
	}else{
		if($_SESSION['__user_logid']=='CE10091236' || $_SESSION['__user_type']=='ADMINISTRATOR' || $_SESSION['__user_type']=='CENTRAL MIS' ){
			
	
			$isPostBack = false;
			$referer = "";
			$alert_msg="";
			$thisPage = REQUEST_SCHEME."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

			if (isset($_SERVER['HTTP_REFERER'])){
			    $referer = $_SERVER['HTTP_REFERER'];
			}

			if ($referer == $thisPage){
			    $isPostBack = true;
			} 
		}else{
			$location= URL.'Login'; 
		echo "<script>location.href='".$location."'</script>"; 
		}
	}

$myDB = new MysqliDb ();
$query='select id,location from location_master;';
$myDB= new MysqliDb();
$location_array=array();
$result =$myDB->query($query);
foreach($result as $lval){
	$location_array[$lval['id']]=$lval['location'];
}
$msg=$searchBy=$empid='';
$classvarr="'.byID'";
$icardStatus='0';
if($isPostBack && isset($_POST['txt_dateTo']))
{
	$date_To = $_POST['txt_dateTo'];
	$date_From =$_POST['txt_dateFrom'];
	
}
else
{
	$date_To = date('Y-m-d',time()); 
	$d = new DateTime($date_To);
    $d->modify('first day of this month');
	//$date_From= $d->format('Y-m-d');
	$date_From= date('Y-m-d',time());
	  
	
}


 $sql="select a.id, a.EmployeeID, a.Employeename, a.EmpMobile, a.empAddress, a.createdOn,b.location from ack_covid_weekly_form a  inner join personal_details b on a.EmployeeID=b.EmployeeID where cast(a.createdOn as date) between '".$date_From."' and '".$date_To."' ";		
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
<span id="PageTittle_span" class="hidden">Covid Acknowledge Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Covid Acknowledge Report</h4>				

<!-- Form container if any -->
<div class="schema-form-section row">
<div class="input-field col s12 m12" id="rpt_container">
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateFrom"  id="txt_dateFrom" value="<?php echo $date_From;?>"/>
		</div>
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateTo"  id="txt_dateTo" value="<?php echo $date_To;?>"/>
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
			            <th>Mobile</th> 
			            <th>Address</th> 
			            <th>Location</th> 
			            <th>Acknowledge At</th> 
			           
			        </tr>
			    </thead>
			    <tbody>					        
			       <?php
			       $count=1;
			       if(count($result)>0){
			        foreach($result as $key=>$value){
			        	$locationName='';
			        	if($value['location']!="" && $value['location']!=NULL){
			        		$locationName=$location_array[$value['location']];
			        	}
			        	echo '<tr>';			        	
							echo '<td  id="countc'.$count.'">'.$count.'</td>'; 
							echo '<td  id="empid'.$count.'" >'.$value['EmployeeID'].'</td>';
							echo '<td  id="Employeename'.$count.'" >'.$value['Employeename'].'</td>';
							echo '<td  id="EmpMobile'.$count.'"  >'.$value['EmpMobile'].'</td>';
							echo '<td  id="empAddress'.$count.'"  >'.$value['empAddress'].'</td>';
							echo '<td  id="location'.$count.'"  >'.$locationName.'</td>';
							echo '<td  id="createdOn'.$count.'"  >'.$value['createdOn'].'</td>';
							
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

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>