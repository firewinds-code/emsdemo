<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
ini_set('display_errors',0);
$date = "";
$dd = date('Y-m-d');
$Workday = 0;
$Request = $_REQUEST['Exception'];
$EmpID = $_REQUEST['EmpID'];
if(isset($_REQUEST))
{
	if ($Request == "Back Dated Leave")
{
	
    while ($Workday < 3)
    {
        $dd = date('Y-m-d',strtotime($dd.' -1 day'));
        $Att = getAttndForExcep($EmpID, $dd);
        if ($Att['InTime'] == NULL)
        {
            $sql = "call sp_GetRoasterDataByDate('".$EmpID."','".intval(date('m',strtotime($dd)))."','".intval(date('Y',strtotime($dd)))."','".intval(date('d',strtotime($dd)))."')";
           // echo $sql;
            $myDB = new MysqliDb();
            $mysqlError =$myDB->getLastError();
            $roaster_arr = $myDB->query($sql); 
            $roaster='-';
            foreach($roaster_arr as $key => $val)
            {
            	foreach($val as $ke => $va)
            	{
					foreach($va as $value)
					{
						$roaster  = $value;
					}
				}
				
			}           
            if ($roaster[0] == "0" || $roaster[0] == "1" ||  $roaster[0] == "2"||$roaster[0] == "4" || $roaster[0] == "4" || $roaster[0] == "5" || $roaster[0] == "6" || $roaster[0] == "7" || $roaster[0] == "8" || $roaster[0] == "9")
        	{
                $date =  $date.$dd.",";
            }
             
        }
        $Workday++;
        
       
    }
}
else if ($Request == "Attendance Change")
{                                                           
    while ($Workday < 3)
    {
        $dd = date('Y-m-d',strtotime($dd.' -1 day'));
        $Att = getAttndForAC($EmpID, $dd);        
        if ($Att["date"] == "" || $Att["date"] == null)                    
            $Att["date"] = "0";
        $Workday++;

        if ($Att["date"] < 9)
        {
            if ($Att["date"] >= 0)
            {
                $date = $date.$dd.",";
            }
        }
    }
}
else if ($Request == "Working on Holiday" || $Request == "Working on WeekOff")
{
	$counter  = 0;
    $dd = date('Y-m-d',strtotime($dd.' +0 day'));
    while ($Workday < 3)
    {
        
        $Att = getAttndForExcep($EmpID, $dd);
        
        if (strlen($Att['InTime']) > 0)
        {
            $sql = "call sp_GetRoasterDataByDate('".$EmpID."','".intval(date('m',strtotime($dd)))."','".intval(date('Y',strtotime($dd)))."','".intval(date('d',strtotime($dd)))."')";
            $myDB = new MysqliDb();
            $mysqlError = $myDB->getLastError();
            $roaster_arr = $myDB->query($sql); 
            $roaster='-';
            foreach($roaster_arr as $key => $val)
            {
            	foreach($val as $ke => $va)
            	{
					foreach($va as $value)
					{
						$roaster  = $value;
					}
				}
				
			}  
			$rtpff =strpos($roaster,'HO');
			$rtpff =strpos($roaster,'WO');
            if (strpos($roaster,'HO') !== false ||  strpos($roaster,'WO') !== false)
            {
				$date = $date.$dd.",";
			}
               
			$Workday++;
        }
        if($counter > 6)
        {
			$Workday = 3;
		}
        $counter++;
		
        $dd = date('Y-m-d',strtotime($dd.' -1 day'));
        
    }
}
else if ($Request == "Roster Change")
{
    $dd = date('Y-m-d',strtotime($dd.' +1 day'));
    $dd1 = date('Y-m-d',strtotime($dd.' +8 day'));


    for ($i = 1; $i <= 9; $i++)
    {
    	
        $sql = "select InTime,OutTime from roster_temp where EmployeeID = '".$EmpID."' and str_to_date(DateOn,'%Y-%c-%e') ='".$dd."'";
        $myDB = new MysqliDb();
        $res = $myDB->query($sql);
        $mysqlError = $myDB->getLastError();
        $roaster='';
        foreach($res as $key => $val)
            {
            	foreach($val as $ke => $va)
            	{
					foreach($va as $value)
					{
						$roaster  = $value;
					}
				}
				
			} 
        if($roaster != '' && $roaster != NULL && !empty($roaster) && $roaster != '-')
        {
            $date = $date.$dd.",";
        }
        $dd = date('Y-m-d',strtotime($dd.' +1 day'));

    }

}
else if ($Request == "Shift Change")
{
	$date = date('Y-m-d');
}
}



function getAttndForExcep($EmpID,$Date)
{
    $res = "";
    $ID = '';
    $tbl = '';

    if (strlen($EmpID) == 10)
    {
        $ID = substr($EmpID,2,8);
        $ID = "9".$ID;
    }
    else if (strlen($EmpID) == 11)
    {
        $ID = substr($EmpID,strlen($EmpID) -5 ,5);
        $ID = "9".$ID;
    }
		
    $resQuery ="call sp_getAttndByDt('".$ID."','".$Date."')";
	$mysql =new MysqliDb();
	$res = $mysql->query($resQuery);	
	$mysqlError = $myDB->getLastError();
	$r ='';
	foreach($res as $key=>$val)
	{
		foreach($val as $k=>$v)
		{
			$r = $v;
		}
	}
    return $r;
}
function getAttndForAC($EmpID,$Date)
{
    $res = "";
    $ID =''; $tbl = '';
	
	if (strlen($EmpID) == 10)
    {
        $ID = substr($EmpID,2,8);
        $ID = "9".$ID;
    }
    else if (strlen($EmpID) == 11)
    {
        $ID = substr($EmpID,strlen($EmpID) -5 ,5);
        $ID = "9".$ID;
    }    
    $resQuery = "call sp_getAttndACByDt('".$ID."','".$Date."')";
	$mysql =new MysqliDb();
	$res = $mysql->query($resQuery);
	$mysqlError = $myDB->getLastError();
    $r ='';
	foreach($res as $key=>$val)
	{
		foreach($val as $k=>$v)
		{
			$r = $v;
		}
	}
    return $r;
}
			
echo $date;
?>

