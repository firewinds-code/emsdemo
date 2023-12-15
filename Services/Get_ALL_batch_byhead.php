

<label class="control-label">Batch List:</label>
<div class="controls">
<select id="batch_id_tmp" name="batch_id_tmp">	
<option value="NA">---Select---</option>
<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$Query= null;

if($_REQUEST)
{
		$myDB=new MysqliDb();
		$Query="SELECT distinct whole_details_peremp.BatchID,batch_master.BacthName FROM whole_details_peremp inner join  batch_master on batch_master.BacthID = whole_details_peremp.BatchID where ('".$_REQUEST['EmployeeID']."' in (th,qh,oh,account_head)) and cm_id = '".$_REQUEST['cm_id']."'";
			$res =$myDB->query($Query);
			if($res)
			{
				foreach($res as $key=>$value)
				{
					echo '<option value="'.$value['BatchID'].'">'.$value['BacthName'].'</option>';
				}
				
			}
			
}

?>

</select>
</div>