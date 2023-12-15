<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$result=array();
$myDB =  new MysqliDb();
  $iPL =0;
     $iCO =0;
if(isset($Data['appkey']) && $Data['appkey'] !=' ')
{	
     
			if($Data['appkey']=='pl' || $Data['appkey']=='combo' || $Data['appkey']=='all')
            {
            	
            	if(isset ($Data['EmployeeID']) && $Data['EmployeeID']!="" && isset ($Data['Date']) && $Data['Date']!="" )
            	{
            		$EmpID = $Data['EmployeeID'];
            		$date=$Data['Date'];
            	$myDB = new MysqliDb();
    			$pd_current = $myDB->query('call get_paidleave_current("'.$date.'","'.$EmpID.'")');
    			$myDB = new MysqliDb();
    			$pd_urned = $myDB->query('call get_paidleave_urned("'.$date.'","'.$EmpID.'")');
    			$mysql_error = $myDB->getLastError();
    			$iPL=0;
    			$paid_urned = 0;
    			if($pd_current)
    			{
					if(count($pd_current) > 0)
					{
						$iPL = $pd_current[0]['paidleave'];
						if(count($pd_urned) > 0)
						{
							
							$paid_urned = $pd_urned[0]['paidleave'];
							if($paid_urned == null)
							{
								$paid_urned =0;
							}
						}
						
						if($paid_urned > 0)
						{
							$iPL =$iPL - $paid_urned;
						}
						if($iPL <= 0)
						{
							$iPL =0;
						}
						
					}
				}
				$paid_leave_urned = 0;
				$iCO = 0;
				$myDB = new MysqliDb();
    			$co_current = $myDB->query('call sp_getComboCount("'.$EmpID.'")');
    			
				if($co_current)
				{
					if(count($co_current) > 0)
					{
						$iCO = $co_current[0]['CO'];
					}
					else
					{
						$iCO =0;
					}
				}
				else
				{
					$iCO = 0;
				}
            		  $result['pl']=$iPL;
                $result['co']=$iCO;
					$result['msg']="Data Found";
			         $result['status']=1;
				     
				}
				else
				{
					$result['msg']="Please set employeeId and date.";
            $result['status']=0;
				}
            	     
			}else{
				
             $result['msg']="Bad request.";
			         $result['status']=0;
			}

		
			
		        
 }
else
 {
     $result['msg']="Bad Request";
     $result['status']=0;
 }
echo  json_encode($result);



	


?>

