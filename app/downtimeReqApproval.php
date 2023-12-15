<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 	
ini_set('display_errors', '1'); 
ini_set('log_errors','On');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$response['msg']=$EmpID=$ReportsTo='';
$myDB=new MysqliDb();
$ExpID = $Data['ReqID'];	
if(isset($Data) && count($Data)>0)
{
	if(isset($Data['appkey']) && $Data['appkey']=="dwtapprove")
	{
			$len_check = 0;
			if($ExpID > 0 && $len_check === 0 )
		      {
		      	$FAID=$ReportsTo=$approvedID=$Data['approvedID'];
		      	$DateFrom=$Data['DTFrom'];
                $DateTo=$Data['DTTo'];
				$EmpID = $Data['EmployeeID'];
				$LoginDateTime = $Data['LoginDate'];
				$TotalDT=$Data['TotalDT'];           
	            $RequestType = $Data['ReqType'];//  that is IT/Client traing
	            $EmployeeComment =addslashes($Data['EmpComment']);
	            $Status = "Pending";
	            $RTStatus = "Pending";
	            $MngrStatusID = $Data['mgrstatus'];//FA status
	            $HeadStatusID = $Data['hdstatus'];//RT status
	            $approvedby = $Data['approvedby'];
				$res = calcDTTime($LoginDateTime,$EmpID);
				$billable = $Data['type'];
				$ITticketID =$Data['IT_ticketid'];
							$myDB = new MysqliDb();           
				             $sqlUpdatereq = 'call UpdateDTRequest("'.$ExpID.'","'.$DateFrom.'","'.$DateTo.'","'.$TotalDT.'","'.$FAID.'","'.$EmployeeComment.'","'.$FAID.'","'.$MngrStatusID.'","","'.$HeadStatusID.'","","'.$approvedby.'","'.$approvedID.'","'.$RequestType.'","'.$billable.'","'.$ITticketID.'","App")';
							$flag = $myDB->query($sqlUpdatereq);
							$error = $myDB->getLastError();
							if(empty($error))
							{
										
								if($ReportsTo == $approvedID&& $HeadStatusID != 'Pending')
								{
									if($ReportsTo == $approvedID && $HeadStatusID == 'Approve')
									{
											$url='';
											$iTime_in = new DateTime($DateFrom);
											$iTime_out =new DateTime();
											$interval = $iTime_in->diff($iTime_out);
											if($interval->format("%a") <= 10)
											{
												$url = URL.'View/calcRange.php?empid='.$EmpID.'&type=one&from='.date('Y-m-d',strtotime($DateFrom));
											}
											else
											{
												$url = URL.'View/calcRange.php?empid='.$EmpID.'&type=one';
											}

											$curl = curl_init();
											curl_setopt($curl, CURLOPT_URL, $url);
											curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($curl, CURLOPT_HEADER, false);
											$data = curl_exec($curl);
											curl_close($curl);
											$response['status']=1;
		                                    $response['msg']='Request Saved.';
									}
								}
								else if($FAID == $approvedID && $MngrStatusID != 'Approve')
								{
								     $response['status']=1;
		                             $response['msg']='Request Saved.';
								}
								else if($FAID == $approvedID && $MngrStatusID == 'Approve')
								{
									 $response['status']=1;
		                             $response['msg']='Request Saved.';
								}
								else
								{
									 $response['status']=1;
		                             $response['msg']='Request Saved';
								}
								
							}
							else
							{
								     $response['status']=0;
		                             $response['msg']='Request not Saved';
							}
			  }
			  else
			  {							
				 $response['status']=0;
			     $response['msg']='Request not Exist.';
			  } 
		}
		else
		{
			$response['status']=0;
		     $response['msg']='Key does not match.';
		}
}
else
{	
  $response['status']=0;
  $response['msg']='data not set.';
 }
 echo json_encode($response);       


function calcDTTime($dt,$empID)
    {
    	$DateFrom="";
    	$DateTo="";
        $DTStart = ""; $DTEnd = ""; $ADT=""; $ADT1=""; $ADT2="";
        
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
				            	$st = new DateTime($valT1);
								$et = new DateTime($valT2);
									
								
								$diff_tt = date_diff($st,$et);
								$alt_tt = $diff_tt->format('%H:%I');
								
							    $ADT = $alt_tt;	
							}
						}
            }
				
				else if($roster[0] == 'WO-WO')
				{
	            	$DTStart = $valT1 = date('Y-m-d H:i:s',strtotime($DateFrom));
	                $DTEnd = $valT2 =  date('Y-m-d H:i:s',strtotime($DateTo));
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
					
	               	$DTStart = date('Y-m-d H:i:s',strtotime($_POST['txt_DateFrom']));
	                $DTEnd =  date('Y-m-d H:i:s',strtotime($_POST['txt_DateTo']));
	                
	                
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
            	}
            	
            				else if($roster == 'WO|WO-WO|WO')
							{
				            	$DTStart = $valT1 = date('Y-m-d H:i:s',strtotime($DateFrom));
				                $DTEnd = $valT2 =  date('Y-m-d H:i:s',strtotime($DateTo));
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