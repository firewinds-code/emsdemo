<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
$sql = "select RatingAH,PromotionMonth from apprisalmaster where year(CreatedOn) in (select year(CreatedOn)-1 from apprisalmaster where id='".$_REQUEST['ID']."')";
$myDB=new MysqliDb();
$result=$myDB->query($sql);
$mysql_error=$myDB->getLastError();
if(count($result) > 0 && $result)
{
    foreach($result as $key=>$value)
    {
    	foreach($value as $k => $Details)
		{
			echo $Details.'|$|';
		}
    }	
}
/*else
	{
		echo 'No Comment ';
		
	}*/
?>