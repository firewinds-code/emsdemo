<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 	
ini_set('display_errors', '1'); 
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$response['msg']='';
$myDB=new MysqliDb();
$EmpID=$Data['EmpID'];	
$Process=$Data['Process'];
$DateFrom=$Data['DTFrom'];
$DateTo=$Data['DTTo'];
$TotalDT=$Data['TotalDT'];
$DowntimeDate=$Data['DowntimeDate'];
$EmployeeComment=addslashes($Data['EmpComment']);
$FAStatus ="Pending";
$RTStatus ="Pending";
$RTID =$Data['ReportstoID'];
$RequestTo=$Data['Request'];
$RequestType =$Data['Request_type'];//like BMQ,Nester,Bussy support
$ITticketID =$Data['IT_ticketid'];	


	/*if(isset($Data) && count($Data)>0)
	{*/
		if(isset($Data['appkey']) && $Data['appkey']=="dwt")
		{
		  $checkActiveID="SELECT cm_id FROM ems.ActiveEmpID where EmployeeID='".$EmpID."' ";
	 	  $myDB =  new MysqliDb();
		  $response =$myDB->rawQuery($checkActiveID);
		  $error = $myDB->getLastError();
	 	  if(empty($error) && count($response)!=0)
	 	  {
	 	  	$LoginDateTime = date('Y-m-d');
	 	  	$res = calcDTTime(date('Y-m-d'),$EmpID,$DateFrom,$DateTo,$DowntimeDate);
	 	  	
	 	  	$DT_Start = date('Y-m-d H:i:s',strtotime($DateFrom));
    		$DT_End =  date('Y-m-d H:i:s',strtotime($DateTo));
	 	  	
	           	if($res[3] == date('Y-m-d',strtotime($DateFrom)) || $res[3] <  date('Y-m-d',strtotime($DateTo)))
	           	{
					if(!empty($RTID))
		           	{
						if(!empty($res[2]) && $DT_Start < $DT_End && date('Y-m-d',strtotime($DT_End)) <= date('Y-m-d',strtotime($DT_Start.' +1 days')))
						{
							$DateFrom = $res[0];
				            $DateTo = $res[1];
				            $TotalDT = $res[2];
							if(strtotime($TotalDT) > strtotime('00:00') && $RequestType != 'NA')
							{
								
		    					$Inser_downtime='call sp_InsertDTReq("'.$EmpID.'","'.$Process.'","'.$DateFrom.'","'.$DateTo.'","'.$TotalDT.'","'.$RequestTo.'","'.$DowntimeDate.'","'.$EmployeeComment.'","'.$RequestTo.'","'.$FAStatus.'","'.$RTID.'","'.$RTStatus.'","'.$RequestType.'","'.$ITticketID.'","App")'; 
								   $result = $myDB->query($Inser_downtime);
							       $mysql_error= $myDB->getLastError();
							      if(empty($mysql_error))
							         {
							         	
								        $response['status']=1;
								        $response['msg']='Successfully add Down Time request';
							         }
							     else
							         {
							         	
								        $response['status']=0;
								        $response['msg']='Please try again for this request down time '.$mysql_error;

							         }
							}
							else
							{
								$response['status']=0;
		    					$response['msg']='DownTime Not Allowed';
							}
							
						}
						else
						{
							$response['status']=0;
	    					$response['msg']='DownTime Not Allowed';
						}
					}
					else
					{
						$response['status']=0;
    					$response['msg']='DownTime Not Allowed';
					}
				}
				else
				{
					$response['status']=0;
					$response['msg']='DownTime Not Allowed';
				}
			
					
			
		   }
		   else
		   {
			  	$result['status']=0;
				$result['msg']="You are inactive.";
		   }					
        }
          
		  
        else
        {
        	
        	$response['status']=0;
		    $response['msg']='Key does not match';
        }
        
    
   /* }
    else{
    	
        	$response['status']=0;
		    $response['msg']='data not set';
        }*/
 echo json_encode($response);       
 

	function calcDTTime($dt,$empID,$DateFrom,$DateTo,$DowntimeDate)
    {
        $DTStart = ""; $DTEnd = ""; $ADT=""; $ADT1=""; $ADT2="";
        $flag=1;
        
        $myDB=new MysqliDb();
		$rst = $myDB->query('select type_ from roster_temp where EmployeeID = "'.$empID.'" and DateOn =cast("'.$dt.'" as date) order by id desc limit 1');
					$exp1 = $exp2 = 0;
					if($rst)
					{
						if(intval($rst[0]['type_']) != 0)
						{
							if($rst[0]['type_']!='4')
							{
								
								$roster = getRoster($dt,$empID);
							
				if ($roster[0] != 'WO-WO')
				{
                
				
               	$DTStart = date('Y-m-d H:i:s',strtotime($DateFrom));
                $DTEnd =  date('Y-m-d H:i:s',strtotime($DateTo));
               
						$i_rin_tmp = date('H:i:s',strtotime($roster[1]));
						$date1 = date_create($dt);
						$date2 = date_create(date('Y-m-d',strtotime($DateFrom)));
						$diff=date_diff($date2,$date1);
						
						$days = $diff->format("%a");
						
						$date2 = date_create(date('Y-m-d',strtotime($DowntimeDate)));
						$diff=date_diff($date2,$date1);
						
						$days1 = $diff->format("%a");
						
						if($i_rin_tmp >= "15:00:00")
						{
							if($days >1 || $days1>1)
							{
								$flag = 0;
							}
						}
						else
						{
							if($days >0 || $days1 >0)
							{
								$flag = 0;
							}
						}
						
						if($flag == 1)
						{
							
						
						if(($DTStart >= $roster[1] && $DTStart<= $roster[2]) || ($DTEnd >= $roster[1] && $DTEnd<= $roster[2]))
						{
							$ADT  = get_inshift_time($roster[1],$roster[2],$DTStart,$DTEnd);
						}
						else
						{
							$roster = getRoster(date('Y-m-d',strtotime($dt.' -1 days')),$empID);
							$dt = date('Y-m-d',strtotime($dt.' -1 days'));
							if ($roster[0] != 'WO-WO')
							{
								$ADT  = get_inshift_time($roster[1],$roster[2],$DTStart,$DTEnd);
								
							}
							else if($roster[0] == 'WO-WO')
							{
				            	
								$valT1 = $DTStart;
								$valT2 = $DTEnd;
								// Conver create date as new DateTime object
									
				            	$st = new DateTime($valT1);
								$et = new DateTime($valT2);
									
								
								$diff_tt = date_diff($st,$et);
								$alt_tt = $diff_tt->format('%H:%I');
								
							    $ADT = $alt_tt;	
							}
						}
						
						}



            }
				
				else if($roster[0] == 'WO-WO')
				{
	            	$DTStart = $valT1 = date('Y-m-d H:i:s',strtotime($DateFrom));
	                $DTEnd = $valT2 =  date('Y-m-d H:i:s',strtotime($DateTo));
					
					// Conver create date as new DateTime object
						
	            	$st = new DateTime($valT1);
					$et = new DateTime($valT2);
						
					
					$diff_tt = date_diff($st,$et);
					$alt_tt = $diff_tt->format('%H:%I');
					
				    $ADT = $alt_tt;	
				}
			}
							
							else
							{
								$sql='select DateOn,InTime,OutTime,type_ from roster_temp where EmployeeID ="'.$empID.'" and DateOn between "'.$dt.'" and "'.$dt.'"';
								//$sql = "call sp_GetRoasterDataByDate('".$empID."','".$dt."')";
        
					        $myDB = new MysqliDb();
					        $roster_Data = $myDB->query($sql);
					        if (count($roster_Data) > 0 && $roster_Data)
            				{
            					$shift=$roster_Data[0]['InTime'];
								$In1=substr($shift,0,strpos($shift,'|'));
								$Out1=substr($shift,strpos($shift,'|')+1,strlen($shift));
								
								$shift=$roster_Data[0]['OutTime'];
								$In2=substr($shift,0,strpos($shift,'|'));
								$Out2=substr($shift,strpos($shift,'|')+1,strlen($shift));
								            					
            				}
					        
					        $roster ='';
					        if(count($roster_Data) > 0)
					       	{
								$roster = $In1;
							}
					        
					       if ($roster[0] == "0" || $roster[0] == "1" ||  $roster[0] == "2"||$roster[0] == "3" || $roster[0] == "4" || $roster[0] == "5" || $roster[0] == "6" || $roster[0] == "7" || $roster[0] == "8" || $roster[0] == "9")
				{
	                $rin =$In1;
					$rout =$Out1;
	                $i_rosterIN = date('Y-m-d H:i:s',strtotime($dt.' '.$rin));
	                
					$i_rin_tmp = date('H:i:s',strtotime($rin));
					$i_rout_tmp = date('H:i:s',strtotime($rout));
					$i_rosterOUT ='';
					$i_rosterOUT = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));
					
					if($i_rin_tmp > $i_rout_tmp)
					{
						$i_rosterOUT = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));			
						$i_rosterOUT = date('Y-m-d H:i:s',strtotime($i_rosterOUT.' +1 days'));
					}
					else
					{
						$i_rosterOUT = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));
					}
					
	               	$DTStart = date('Y-m-d H:i:s',strtotime($DateFrom));
	                $DTEnd =  date('Y-m-d H:i:s',strtotime($DateTo));
	                
	                
	                $rin =$In2;
					$rout =$Out2;
	                $i_rosterIN2 = date('Y-m-d H:i:s',strtotime($dt.' '.$rin));
	                
					$i_rin_tmp = date('H:i:s',strtotime($rin));
					$i_rout_tmp = date('H:i:s',strtotime($rout));
					$i_rosterOUT2 ='';
					$i_rosterOUT2 = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));
					
					if($i_rin_tmp > $i_rout_tmp)
					{
						$i_rosterOUT2 = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));			
						$i_rosterOUT2 = date('Y-m-d H:i:s',strtotime($i_rosterOUT2.' +1 days'));
					}
					else
					{
						$i_rosterOUT2 = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));
					}
					
					if(($DTStart >= $i_rosterIN && $DTStart<=$i_rosterOUT)&& ($DTEnd >= $i_rosterIN2 && $DTEnd<=$i_rosterOUT2))
	               	{
						$ADT1  = get_inshift_time($i_rosterIN,$i_rosterOUT,$DTStart,$DTEnd);
						$ADT2  = get_inshift_time($i_rosterIN2,$i_rosterOUT2,$DTStart,$DTEnd);
						
						$parsed = date_parse($ADT2);
						$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
					    $tt = strtotime("+".$seconds." seconds ".$ADT1);
						$ADT = date("H:i",$tt);
					}
					
					else if($DTStart >= $i_rosterIN && $DTEnd<=$i_rosterOUT)
	               	{
						$ADT  = get_inshift_time($i_rosterIN,$i_rosterOUT,$DTStart,$DTEnd);
						
					}
					else if($DTStart >= $i_rosterIN2 && $DTEnd<=$i_rosterOUT2)
	               	{
						$ADT  = get_inshift_time($i_rosterIN2,$i_rosterOUT2,$DTStart,$DTEnd);
					
					}
					else if(($DTStart >= $i_rosterIN && $DTStart <= $i_rosterOUT) && $DTEnd < $i_rosterIN2)
	               	{
						$ADT  = get_inshift_time($i_rosterIN,$i_rosterOUT,$DTStart,$DTEnd);
						
					}
					else if($DTStart < $i_rosterIN && ($DTEnd >= $i_rosterIN && $DTEnd <= $i_rosterOUT))
					{
						$ADT  = get_inshift_time($i_rosterIN,$i_rosterOUT,$DTStart,$DTEnd);
					}
					
					else if($DTStart < $i_rosterIN2 && ($DTEnd >= $i_rosterIN2 && $DTEnd <= $i_rosterOUT2))
					{
						$ADT  = get_inshift_time($i_rosterIN2,$i_rosterOUT2,$DTStart,$DTEnd);
					}
					else if(($DTStart >= $i_rosterIN2 && $DTStart <= $i_rosterOUT2) && $DTEnd > $i_rosterOUT2)
	               	{
						$ADT  = get_inshift_time($i_rosterIN2,$i_rosterOUT2,$DTStart,$DTEnd);
						
					}
					
	               	
					
	                
	                
               		//$ADT  = get_inshift_time($i_rosterIN,$i_rosterOUT,$DTStart,$DTEnd);
            	}
            	
            				else if($roster == 'WO|WO-WO|WO')
							{
				            	$DTStart = $valT1 = date('Y-m-d H:i:s',strtotime($DateFrom));
				                $DTEnd = $valT2 =  date('Y-m-d H:i:s',strtotime($DateTo));
								
								// Conver create date as new DateTime object
									
				            	$st = new DateTime($valT1);
								$et = new DateTime($valT2);
									
								
								$diff_tt = date_diff($st,$et);
								$alt_tt = $diff_tt->format('%H:%I');
								
							    $ADT = $alt_tt;	
							}
					}	
						}
					}			
								
       if(strtotime($ADT)>strtotime("08:00"))
       {
	   		$ADT="08:00";
	   }
            
        return array($DTStart,$DTEnd,$ADT,$dt);
    }
    
    
	function getRoster($dt,$empID)
  	{
  		
		$sql = "call sp_GetRoasterDataByDate('".$empID."','".$dt."')";
        
		$myDB = new MysqliDb();
		$roster_Data = $myDB->query($sql);
		$roster ='';
		if(count($roster_Data) > 0 && $roster_Data)
		{
			$roster = $roster_Data[0]['Shift'];
		}
		$i_rosterIN=$i_rosterOUT='';			        
		if ($roster[0] == "0" || $roster[0] == "1" ||  $roster[0] == "2"||$roster[0] == "3" || $roster[0] == "4" || $roster[0] == "5" || $roster[0] == "6" || $roster[0] == "7" || $roster[0] == "8" || $roster[0] == "9")
				{
	                $rin =trim(substr($roster ,0,strpos($roster,'-')));
					$rout =trim(substr($roster ,strpos($roster,'-')+1,(strlen($roster)- (strpos($roster,'-')+1))));
	                $i_rosterIN = date('Y-m-d H:i:s',strtotime($dt.' '.$rin));
	                
					$i_rin_tmp = date('H:i:s',strtotime($rin));
					$i_rout_tmp = date('H:i:s',strtotime($rout));
					$i_rosterOUT ='';
					if($i_rin_tmp > $i_rout_tmp)
					{
						$i_rosterOUT = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));			
						$i_rosterOUT = date('Y-m-d H:i:s',strtotime($i_rosterOUT.' +1 days'));
					}
					else
					{
						$i_rosterOUT = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));
					}
				}
				
			return array($roster,$i_rosterIN,$i_rosterOUT);		
	}
	
	function get_inshift_time($r1,$r2,$b1,$b2)
	{
		$tbin = new DateTime($b1);
		$tbout = new DateTime($b2);
		$trin = new DateTime($r1);
		$trout = new DateTime($r2);
		
		
		if ($tbin <= $trin && $tbout >= $trout)
	    {
	        $tt = $trout->diff($trin);
	    }

	    else if ($tbin <= $trin && $tbout <= $trout && $tbout > $trin )
	    {
	        $tt = $tbout->diff($trin);
	        
	    }
	    else if ($tbin <= $trin && $tbout <= $trout && $tbout <= $trin )
	    {
	        $tt =$tbin->diff($tbin);
	        
	    }
	    else if ($tbin >= $trin && $tbout <= $trout)
	    {
	        $tt = $tbout->diff($tbin);
	    }
	     else if ($tbin >= $trin && $tbout >= $trout && $tbin < $trout)
	    {
	        $tt = $trout->diff($tbin);
	    }
	    else if($tbin >= $trout)
	    {
			$tt =$tbin->diff($tbin);
		}
	    else
	    {
	    		if($tbin < $tbout)
			{
				$tt = $tbin->diff($tbout);
			}
			if(date('H:i',strtotime($tt->format('%H:%i:%s'))) > '10:00')
			{
				if($tbin < $tbout)
				{
					$tt = $tbin->diff($tbin);
				}
			}
	    }
		
		
		
		return date('H:i',strtotime($tt->format('%H:%i:%s')));
		
	}
	
?>