<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
error_reporting(0);
?>


<script>
	$(function(){
		    var table = $('#myTable').DataTable({
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
							"sScrollY" : "300",
							"bScrollCollapse" : true,
							"bLengthChange" : false
							
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
			});;
		  	
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
<span id="PageTittle_span" class="hidden">Index Edit</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Index Edit</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >

<?php
$dir = "../IndexEditPage/";

// Open a directory, and read its contents
$count = 0;
if (is_dir($dir)){
  
    $dirs = array_filter(glob($dir.'/*'), 'is_dir');
	foreach($dirs as $diretory)
	{
		if(is_dir($dir.'/'.$diretory))
		{
			if ($dh = opendir($dir.'/'.$diretory)){
			{
				$dirName = $diretory;
				$dirName = explode('/',$diretory);
				$dirName  = $dirName[count($dirName)-1];
				$dirName_check = explode('_',$dirName);
				$dirName_check  = $dirName_check[count($dirName_check) - 1];
				if($dirName =='content_current')
				{
					$dirName = "Content Current ";
				}
				else
				{
					if(date('Y-m-d',$dirName_check) != '1970-01-01')
					{
						$dirName = "Content Log [".date('d M,Y H:i:s')."]";
					}
				}
				
				
				echo '<h4 class="card">'.$dirName.'</h4>';
				echo '<div><table class="table table-bordered table-hovered"><thead><tr style="background: #a7a2a2;"><th>File Name</th><th>File Size</th></tr></thead><tbody>';
				while (($file = readdir($dh)) !== false){
					if($file != '.' && $file !='..')
					{
						$file_name = explode('.',$file);
						$without_extension = pathinfo($diretory.'/'.$file, PATHINFO_FILENAME);
						echo '<tr><td><a href="'.$diretory.'/'.$file.'" target="_blank">'.$without_extension.'</a></td><td>'.filesize($dir.'/'.$diretory.'/'.$file).'</td></tr>';
					} 
				}
				echo '</tbody></table></div>';
			}
		}
	}
    	
	}
  	if(count($dirs) <= 0)
  	{
		echo "<script>$(function(){ toastr.error('Log not found'); }); </script>";
	}
}

//echo  'Total file count till date is :'.$count;
?>

</div>  	 
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>	
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
