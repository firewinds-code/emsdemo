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
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
		exit();
	}
	else
	{
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
		
		if($isPostBack && isset($_POST['txt_dateTo']))
		{
		
			$date_To = $_POST['txt_dateTo'];
			$date_From =$_POST['txt_dateFrom'];
		}
		else
		{
			$date_To = date('Y-m-d',time()); 
			$date_From= date('Y-m-d',time()); 
		}
		
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}

/*if(file_exists('../Vacination/NA'))
{
	echo 'Yes';
}
else
{
	echo 'No';
}*/
?>

<script>
	$(function(){
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
							"iDisplayLength": 25,
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Batch Master Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Batch Master Report</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	



		
	
	<div class="input-field col s12 m12" id="rpt_container">
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateFrom"  id="txt_dateFrom" readonly="true" value="<?php echo $date_From;?>"/>
		</div>
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateTo"  id="txt_dateTo" readonly="true" value="<?php echo $date_To;?>"/>
		</div>
		<div class="input-field col s3 m3 hidden">
			
			<select id="txt_location" name="txt_location" required">
		            	<option value="NA">----Select----</option>	
				      	<?php		
						$sqlBy ='select id,location from location_master;'; 
						$myDB=new MysqliDb();
						$resultBy=$myDB->rawQuery($sqlBy);
						$mysql_error = $myDB->getLastError();
						if(empty($mysql_error)){
							echo '<option value="ALL"  >ALL</option>';													
							foreach($resultBy as $key=>$value)
							{						
								echo '<option value="'.$value['id'].'"  >'.$value['location'].'</option>';
							}
						}			
				      	?>
	            </select>
	            <label for="txt_location" class="active-drop-down active">Location</label> 
		</div>
		<div class="input-field col s2 m2">			
		
			<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
			<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
		</div>
		
	</div>
		<?php
			if(isset($_POST['btn_view']))
			{
				$myDB = new MysqliDb();
				//$_location=(isset($_POST['txt_location'])? $_POST['txt_location'] : null);
				//$chk_task=$myDB->query('call get_VacReport("'.$date_From.'","'.$date_To.'", "'.$_location.'")');
				//$chk_task=$myDB->query('select DATE_FORMAT(target_date,"%d-%b-%Y") as target_date,process,sub_process,target_count,batch_no,client_id from batch_status where cast(createdon as date) between ("'.$date_From.'") and ("'.$date_To.'");');
				
				//$chk_task=$myDB->query('select target_date,concat(bs.process," | ",bs.sub_process) process, target_count,bs.batch_no, bm.BacthID, cast(bm.createdon  as date)createdon,count(st.batch_id) Count from batch_status bs left join batch_master bm on bs.batch_no=bm.batch_no and concat(bs.process," | ",bs.sub_process)=concat(bm.process," | ",bm.subprocess) left join batch_mapping st on bm.BacthID=st.batch_id where bs.createdon between "'.$date_From.'" and "'.$date_To.'" group by target_date,concat(bs.process," | ",bs.sub_process), target_count,bs.batch_no, bm.BacthID, cast(bm.createdon  as date)');
				$chk_task=$myDB->query('call getbatchreport("'.$date_From.'", "'.$date_To.'", "'.$_SESSION["__user_type"].'", "'.$_SESSION["__location"].'")');
				$my_error= $myDB->getLastError();	
			
				if(count($chk_task) > 0 && $chk_task)
				{  
					$table='<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
					$table .='<th>Target Date</th>';
					$table .='<th>Process</th>';
					$table .='<th>Location</th>';
					$table .='<th>Batch No.</th>';
					$table .='<th>Req Count</th>';
					$table .='<th>Offer Count</th>';
					$table .='<th>Emp Count</th>';
					$table .='<th>Batch Creation Date</th>';
					$table .='<th>Training Start Date</th>';
					$table .='<th>P Day 1</th>';
					$table .='<th>P Day 3</th>';
					
					
				    $table .='<thead><tbody>';
    
					foreach($chk_task as $key=>$value)
					{
						$tdate = $process= $subprocess=$rcount=$ocount=$bcd=$cr='';
						$p1 = $p2 =0;
						if($value['assigndate']!='NA')
						{
							$p1 = getdays($value['BacthID'],$value['assigndate']);
						
							$date=date_create($value['assigndate']);
							date_add($date,date_interval_create_from_date_string("2 days"));
							$date = date_format($date,"Y-m-d");
							$p2 = getdays($value['BacthID'],$date);
						}
						

						//$p1=$p3=0;
						$tdate = $value['target_date'];
						$process = $value['process'];
						$rcount = $value['target_count'];
												
						$table .='<tr><td>'.$tdate.'</td>';
						$table .='<td>'.$process.'</td>';
						$table .='<td>'.$value['location'].'</td>';
						$table .='<td>'.$value['batch_no'].'</td>';
						$table .='<td>'.$value['target_count'].'</td>';
						$table .='<td>'.$value['Count'].'</td>';
						$table .='<td>'.$value['EmpCount'].'</td>';
						$table .='<td>'.$value['createdon'].'</td>';
						$table .='<td>'.$value['assigndate'].'</td>';
						$table .='<td>'.$p1.'</td>';
						$table .='<td>'.$p2.'</td>';
						
												
						$table .='</td></tr>';	
					}
					$table .='</tbody></table></div>';
					echo $table;
				}
				else
				{
					echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
					
				}
			}
			
			function getdays($bt, $dt)
			{
				$days = '';
				$myDB = new MysqliDb();
				//echo 'select count( distinct bio.EmpID) count  from status_table st left join biopunchcurrentdata bio on bio.EmpID=st.EmployeeID where BatchID ="'.$bt.'" and DateOn="'.$dt.'"';
				$chk_task=$myDB->query('select count( distinct bio.EmpID) as count from status_table st left join biopunchcurrentdata bio on bio.EmpID=st.EmployeeID where BatchID ="'.$bt.'" and DateOn="'.$dt.'"');
				//var_dump($chk_task);	
				$my_error= $myDB->getLastError();	
			
				if(count($chk_task) > 0 && $chk_task)
				{
					//echo $chk_task[0][0];
					foreach($chk_task as $key=>$value)
					{
						$days = $value['count'];	
					}
					
				}
				return $days;
			}
			
		 ?>
      
      </div>   
<!--Reprot / Data Table End -->	       
	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>		

<script>
	$("#btn_view").click(function(){
		//alert($("#txt_location").val());
			
	});
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>