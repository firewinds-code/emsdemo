<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
//require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH.'AppCode/nHead.php');
include(ROOT_PATH.'AppCode/head.mpt'); 
$config = array (
 'serviceurl' => 'https://services.digitallocker.gov.in',
  'lockerurl' => 'https://partners.digitallocker.gov.in'
 );
 	$orgid = 'com.cogenteservices';
 	$requester_id = "ems1";
    $date = date_create();
    $timstamp = date_timestamp_get($date);
    $secret_key = "8374c6bb12b5f0439acb693d8281a35a";
    $hash_key = hash('sha256', $requester_id.$secret_key.$timstamp);
$employeeid=$loc='';
if(isset($_GET['empid']) && $_GET['empid']!="" && $_GET['loc']!=""){
	$employeeid=trim($_GET['empid']);
	$loc=$_GET['loc'];
 ?>
<link rel="stylesheet" href="include/dgl_button.css">

	<input type="hidden" name="empid" id="empid" value="<?php echo $employeeid;?>">
	<input type="hidden" name="loc" id="loc" value="<?php echo $loc;?>">

<div style="margin-left: 20px;margin-top: 180px;border: #19aec4; border-style: solid;height: 200px;width: 358px;">
<div id="preloader"  style="text-align: center; position: absolute;display: none;">
	<img src="loading.gif" height="100" width="100"/>
</div>
<div style="padding-left: 70px;padding-top: 30px;align-content: center;">Click on  button to verify your Aadhar.</div>
	<div  style="padding-left: 90px;padding-top: 30px;" class="share_fm_dl" id="attachment_dl_poi" ></div>	
<input id="attachment_poi_digiLocker" type="hidden" name="attachment_poi_digiLocker"/>
      
   </div>   
     
 <?php 
 } else{
 	echo "Parameter not found";
 }
 ?>     
                                            
<script type="text/javascript" src="<?php echo $config['serviceurl'] ?>/requester/api/2/dl.js" id="dlshare" data-app-id=<?php echo $requester_id; ?> data-app-hash=<?php echo $hash_key; ?> time-stamp=<?php echo $timstamp; ?> data-callback = abc></script>
                                            
                                           
<script>
function abc(data)
{
	console.log(data);
	var employeeid= $('#empid').val();
	var loc=$('#loc').val();
	var proof_address='';		
	var t = $('#attachment_dl_poi').val();	
	
	//alert(t);	
	//var t = '1:t';		
	if(t !=''){
		var addressProof = t!=''?JSON.parse(t):'';
		$('#preloader').show();
		$.ajax({ url:'adharvalidation.php' ,type: 'POST',	data: {addressProof,employeeid,loc},	
			success: function(succesData) { 
			if(succesData==1){
				location.href='thanks.php';
			}else{
				alert(succesData);
			}
			
			$('#preloader').hide();
			//
				
			}
		});
	}	
	return "SUCCESS";
}
				
</script>						
														