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
?>

 
<link rel="stylesheet" href="include/dgl_button.css">

	

<div style="margin-top: 180px;border: #19aec4; border-style: solid;height: 200px;width: 358px;">
<div style="padding-left: 70px;padding-top: 30px;align-content: center;">Click on  button to verify you adhar.</div>
	<div  style="padding-left: 90px;padding-top: 30px;" class="share_fm_dl" id="attachment_dl_poi" ></div>	
<!--<input id="attachment_poi_digiLocker" type="hidden" name="attachment_poi_digiLocker"/>-->
      
   </div>   
     
      
                                            
                                          <script type="text/javascript" src="<?php echo $config['serviceurl'] ?>/requester/api/2/dl.js" id="dlshare" data-app-id=<?php echo $requester_id; ?> data-app-hash=<?php echo $hash_key; ?> time-stamp=<?php echo $timstamp; ?> data-callback = abc></script>
                                            
                                           
<script>
function abc(data)
{
	console.log(data);
	var employeeid="CE121622565";	
	var proof_address='';		
	var t = $('#attachment_dl_poi').val();	
	//alert(t);	
	//var t = '1:t';		
	if(t !=''){
		var addressProof = t!=''?JSON.parse(t):'';
		//alert(addressProof['uri']);
		//var addressProof = '111';
		$.ajax({ url:'adharvalidation.php' ,type: 'POST',	data: {addressProof,employeeid},	
			success: function(succesData) { 
			location.href='thanks.php';
				
			}
		});
	}	
	return "SUCCESS";
}
				
	$('#getdata').click(function(){
		
		
	/*var employeeid="CE121622565";	
	var proof_address='';		
	var t = $('#attachment_dl_poi').val();	
	//alert(t);	
	//var t = '1:t';		
	if(t !=''){
		var addressProof = t!=''?JSON.parse(t):'';
		alert(addressProof['uri']);
		//var addressProof = '111';
		$.ajax({ url:'adharvalidation.php' ,type: 'POST',	data: {addressProof,employeeid},	
			success: function(succesData) { 
			alert('returndata='+succesData);
				
			}
		});
	}	*/
		
});
</script>						
														