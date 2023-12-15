<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID=$CLID=$Proc=$SubProc=$DOJ=$Ageing=$Query= null;
	
$myDB=new MysqliDb();
$Query="SELECT distinct(a.account_head), b.ofc_emailid,b.mobile,c.EmployeeName FROM new_client_master a inner join contact_details b on 
a.account_head=b.EmployeeID inner Join whole_details_peremp c on a.account_head=c.EmployeeID
 where c.clientname not in ('Administration','Business Support','Compliance','Human Resource','Information Technology','MIS and WFM')";
				$res =$myDB->query($Query);
				if($res)
				{
					foreach($res as $key=>$value)																											
					{
					//	echo $value['account_head']."  ".$value['EmployeeName']."<br>";
						//$result[] = $value;
						$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://ems.cogentlab.com/billing/View/addAH_details.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'empid'."=".$value['account_head']."&empname"."=" .$value['EmployeeName']."&mobile"."=" .$value['mobile']."&email"."=" .$value['ofc_emailid']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
       // Echo $ch;
        
        // $server_output;

						
		   			}
		   			//print_r($result);
				//	$result = json_encode($result);
				//	echo $result;
				}
				else
				{
					echo NULL;
				}
		
	
?>