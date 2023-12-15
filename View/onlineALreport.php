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
		echo "<script>location.href='".$location."'</script>";
		//header("Location: $location");
	}
}
else
{
	$location= URL.'Login'; 
	//header("Location: $location");
	echo "<script>location.href='".$location."'</script>";
}
/*if(in_array($_SESSION['__user_logid'],$NotApplicable))
{
	echo "<script>location.href='".$location."'</script>";
}*/
$classvarr="'.byID'";
?>
<script>
	$(document).ready(function(){
		$('#from_date').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#to_date').datetimepicker({ format:'Y-m-d', timepicker:false});
		
		$('#myTable').DataTable({
				        dom: 'Bfrtip',	
				        scrollX: '100%',
				        "iDisplayLength": 25,				        
				        scrollCollapse: true,
				        "order":[[6,"desc"]],
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
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				    });
		   	
		   
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	$('.byID').addClass('hidden');
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   	$('#searchBy').change(function(){
		   		$('.byID').addClass('hidden');
		   		if($(this).val()=='By Date')
		   		{
					$('.byID').removeClass('hidden');
				}
		   	});  
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Appointment Letter Report </span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Received Appointment Letter Report </h4>	
	 
<!-- Form container if any -->
		<div class="schema-form-section row" >
 			<div  class='input-field col s6 m6' >
				<input type='text' name='from_date' id='from_date' <?php if(isset($_POST['from_date'])){ ?> value="<?php echo $_POST['from_date'];?>" <?php } ?>  />
				
				 <label for='from_date' class="active">From Date</label>
			</div>
			<div  class='input-field col s6 m6' > 
				<input type='text'  name='to_date' id='to_date' <?php if(isset($_POST['to_date'])){ ?> value='<?php echo $_POST['to_date'];?>' <?php } ?> />
				<label for='to_date' class='active'>From Date</label>
			</div>
			<div  class='input-field col s12 m12 right-align' > 
			<button type="submit"  value="Go" name="send" id="send" class="btn waves-effect waves-green  ">Search</button>
			</div>
			<div id="pnlTable">
			<?php 
			$myDB=new MysqliDb();
				
			
				if(isset($_POST['from_date'],$_POST['to_date']) and $_POST['from_date']!="" ){
					$date_string=" and `ReceivedDate` BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']." 23:59:59' ";
					$sqlConnect=" SELECT a.*,b.clientname,b.Process,b.sub_process FROM appointmentlonline a inner join whole_details_peremp b on a.EmployeeID=b.EmployeeID   where a.status=1 ";
					 $sqlConnect .=  $date_string."  order by a.`ReceivedDate` desc  " ; 
					 
					//echo $sqlConnect;
					$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					$error=$myDB->getLastError();
					$rowCount = $myDB->count;
					if($rowCount>0){?>
						
			   			 <div class="had-container pull-left row card dataTableInline"  id="tbl_div" >
							<div class=""  >																														
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						         <tr>
						        	<th>Employee ID </th>
									<th>Employee Name</th>
									<th>Email Address</th>
						            <th  >Client</th>
						            <th  >Process</th>
						             <th  >Sub Process</th>
						            <th  >Received Date</th>
						            
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $i=1;
					        foreach($result as $key=>$value){
								echo '<tr style="vertical-align:top;">';							
								echo '<td class="id" style="vertical-align:top;">'.$value['EmployeeID'].'</td>';
								echo '<td class="name" style="vertical-align:top;" >'.$value['EmpName'].'</td>';
								echo '<td class="name" style="vertical-align:top;" >'.$value['fetcheEmail'].'</td>';
								echo '<td class="client">'.$value['clientname'].'</td>';
								echo '<td class="subprocess">'.$value['Process'].'</td>';
								echo '<td class="subprocess">'.$value['sub_process'].'</td>';
								echo '<td class="date" style="vertical-align:top;" >'.$value['ReceivedDate'].' </td>';
							echo '</tr>';
							$i++;
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
							//echo '<div id="div_error" class="slideInDown animated hidden">DATA NOT Found :: <code >'.$error.'</code> </div>';
							echo "<script>$(function(){ toastr.error('Data Not Found.<code >".$error."</code>'); }); </script>";
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
		
		
		$('#div_error').removeClass('hidden');
		$('#send').on('click',function(){
			
			validate=0;
			alert_msg="";
			var from_date=$('#from_date').val().trim();
			var to_date=$('#to_date').val().trim();
			if(from_date=="" || to_date==""){
				validate=1;
		     	alert_msg+='<li> Please select both date  from and to </li>';
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
		
	});
	
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>