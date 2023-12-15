<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
 if(isset($_SESSION['__user_logid']))
{
	if(!isset($_SESSION['__user_logid']) )
	{
		$location= URL.'Login'; 
		echo "<script>location.href='".$location."'</script>"; 
	}else{
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
	}
}
else
{
	$location= URL.'Login'; 
	echo "<script>location.href='".$location."'</script>"; 
}
$msg=$searchBy=$empid='';
$classvarr="'.byID'";

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
	$date_From= $d->format('Y-m-d');
	//$date_From= "2019-12-09"; 
	
}
 $sql="select * from view_contract_log where  cast(createdOn as date)  between '".$date_From."' and '".$date_To."' ";		
?>

<script>
	$(document).ready(function(){
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});
		
		$('#myTable').DataTable({
        dom: 'Bfrtip',
        "iDisplayLength": 25,				        
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
    });

		   
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Contract View Log</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Contract View Log</h4>				

<!-- Form container if any -->
<div class="schema-form-section row">
<div class="input-field col s12 m12" id="rpt_container">
		<div class="input-field col s4 m4">
			
			<input type="text" name="txt_dateFrom"  id="txt_dateFrom" value="<?php echo $date_From;?>"/>
		</div>
		<div class="input-field col s4 m4">
			
			<input type="text" name="txt_dateTo"  id="txt_dateTo" value="<?php echo $date_To;?>"/>
		</div>
	
		<div class="input-field col s4 m4">			
		
			<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
			<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
		</div>
		
	</div>
  	 <div id="pnlTable">
    <?php 
    	//$sql="select EmployeeID, EmployeeName, DOB, DOJ, concat(clientname,' | ',Process,' | ',sub_process) as newprocess,  account_head,designation, ReportTo from whole_details_peremp where DOJ>='2019-08-10' ";
    	
    	$myDB=new MysqliDb();
    	$result = $myDB->query($sql);
    	
		?>
			<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
			    <thead>
			        <tr>
			           <th>SN.</th> 
			           <th>CreateBy</th>
			        	<th>CreatedOn</th>  
			            <th>TableName</th>
			            <th>CMID</th> 
			           
			        </tr>
			    </thead>
			    <tbody>					        
			       <?php
			       $count=1;
			       if(count($result)>0){
			        foreach($result as $value){
			        	echo '<tr>';
							echo '<td  id="countc'.$count.'">'.$count.'</td>';						
							echo '<td  id="empid'.$value['createdBy'].'" class="div_tempCard">'.$value['createdBy'].'</td>';			
							echo '<td  id="empname'.$count.'" >'.$value['createdOn'].'</td>';
							echo '<td  id="table_name'.$count.'" >'.$value['table_name'].'</td>';					
							echo '<td  id="cm_id'.$count.'"  >'.$value['cm_id'].'</td>';
							
						
				
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
</div>     
<!--Content Div for all Page End -->  
</div>
<script>
	$(document).ready(function(){
		/* $('.div_tempCard').on('click',function(){
		 	var txtEmployeeID= $(this).children('a').attr('data_EmpID');
		 	alert(txtEmployeeID);
			$.ajax({url: "../Controller/OLReportIssueIdCard.php?EmpID="+$(this).children('a').attr('data_EmpID'), success: function(result){
				
		            if(result)
					{	
						var abc= $('#emptext'+txtEmployeeID).html('Issued');
						 alert_msg='Id Card issued successfully';
						 $(function(){ toastr.success(alert_msg); });
					} 
				}	
			});
	    

	    });*/
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