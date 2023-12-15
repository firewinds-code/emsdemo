<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID=$CLID=$Proc=$SubProc=$DOJ=$Ageing=$Query= null;
	
$myDB=new MysqliDb();
$Query="select distinct  cm_id, sub_process, Process, account_head, clientname
 from whole_details_peremp  where clientname not in ('Administration','Business Support','Compliance','Human Resource','Information Technology','MIS and WFM')
";
				$res =$myDB->query($Query);
				if($res)
				{
					foreach($res as $key=>$value)															{
					
	$process= $value['clientname'].' | '.$value['Process'].' | '.$value['sub_process'];
	$location="Noida";
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://ems.cogentlab.com/billing/View/add_dataFromApi.php");
        curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,'cm_id'."=".$value['cm_id']."&process"."=" .$process."&AH_ID"."=" .$value['account_head']."&location"."=" .$location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
					
		   			}
		   			//print_r($result);
					//$result = json_encode($result);
					//echo $result;
				}
				else
				{
					echo NULL;
				}
		
	
?>