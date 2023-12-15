<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
ini_set('display_errors',0);
if(!empty($_REQUEST['EmpID'])&&!empty($_REQUEST['Date']))
{
$sql = "call sp_GetRoasterDataByDate('".$_REQUEST['EmpID']."','".$_REQUEST['Date']."')";
           // echo $sql;
            $myDB = new MysqliDb();
            $mysqlError =$myDB->getLastError();
            $roaster_arr = $myDB->query($sql); 
            $roaster='-';
            foreach($roaster_arr as $key => $val)
            {
            	foreach($val as $ke => $va)
            	{
					$roaster  = $va;
					/*foreach($va as $value)
					{
						$roaster  = $value;
					}*/
				}
				
			}           
            if ($roaster[0] == "0" || $roaster[0] == "1" ||  $roaster[0] == "2"||$roaster[0] == "4" || $roaster[0] == "4" || $roaster[0] == "5" || $roaster[0] == "6" || $roaster[0] == "7" || $roaster[0] == "8" || $roaster[0] == "9")
        	{
        		$rost = explode('-',$roaster);
               echo $rost[1];
            }
            else
            {
				echo '-';
			}
	
}
?>