<?php
require_once(__dir__ . '/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
ini_set('display_errors', 0);

// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

$date = "";
$d = "";
$dd = date('Y-m-d');
$Workday = 0;
$Request = clean($_REQUEST['Exception']);
$EmpID = clean($_REQUEST['EmpID']);
$counter_check = 0;
if (isset($_REQUEST)) {
    if ($Request == "Back Dated Leave") {

        /*while ($Workday < 2)
    {
        $dd = date('Y-m-d',strtotime($dd.' -1 day'));
        $Att = getAttndForExcep($EmpID, $dd);
        
        if ($Att['InTime'] == NULL)
        {
            $sql = "call sp_GetRoasterDataByDate('".$EmpID."','".$dd."')";
           // echo $sql;
            $myDB = new MysqliDb();
            $mysqlError = mysql_error();
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
            $counter_check++;
            if($counter_check >= 20)
            {
				$Workday++;	
			}
        }
        else
        {
			$Workday++;	
		}
        
        
       
    }*/
        $counter_check = 0;
        while ($Workday < 2) {
            $dd = date('Y-m-d', strtotime($dd . ' -1 day'));

            $Att = getCalcAttndByDate($EmpID, $dd);

            //$Workday++;
            $ss = $Att;
            if ($Att == "A" || $Att == "WO" || $Att == "WONA" || $Att == "L" || $Att == "LWP" || $Att == "LANA" || $Att == "CO") {
                if ($Att == "A" || $Att == "WONA")
                    $date = $date . $dd . ",";
            } else {
                $Workday++;
            }
            $counter_check++;
            if ($counter_check >= 20) {
                $Workday++;
            }

            //Old Code
            /*$Att = getAttndForAC($EmpID, $dd);        
        if ($Att["date"] == "" || $Att["date"] == null)                    
            $Att["date"] = "0";
        $Workday++;

        if ($Att["date"] < 9)
        {
            if ($Att["date"] >= 0)
            {
                $date = $date.$dd.",";
            }
        }*/
        }
    } else if ($Request == "Biometric issue") {
        $counter_check = 0;


        $sql = "select dateofjoin from employee_map where EmployeeID=?";
        $selectQ = $conn->prepare($sql);
        $selectQ->bind_param("s", $EmpID);
        $selectQ->execute();
        $result = $selectQ->get_result();
        $db_doj = $result->fetch_row();
        if ($result->num_rows > 0 && $result) {
            // echo  $db_doj[0];
            // die;
            $date_doj = (isset($db_doj[0])) ? $db_doj[0] : '';

            while ($Workday < 2) {
                $dd = date('Y-m-d', strtotime($dd . ' -1 day'));

                $Att = getCalcAttndByDate($EmpID, $dd);

                //$Workday++;
                $ss = $Att;
                if ($Att == "L" || $Att == "LWP" || $Att == "CO" || $Att == "H" || $Att == "HWP" || $Att == "A" || $Att == "-" || $Att == "LANA" || $Att == "WO" || $Att == "HO") {
                    if ($Att != "WO" && !empty($date_doj) && $dd >= $date_doj) {
                        $date = $date . $dd . ",";
                    }
                } else {
                    $Workday++;
                }
                $counter_check++;
                if ($counter_check >= 8) {
                    $Workday++;
                }

                //Old Code
                /*$Att = getAttndForAC($EmpID, $dd);        
        if ($Att["date"] == "" || $Att["date"] == null)                    
            $Att["date"] = "0";
        $Workday++;

        if ($Att["date"] < 9)
        {
            if ($Att["date"] >= 0)
            {
                $date = $date.$dd.",";
            }
        }*/
            }
        }
    } else if ($Request == "Working on Leave") {
        $counter_check = 0;
        while ($Workday < 2) {

            $Att = getCalcAttndByDate($EmpID, $dd);
            //var_dump($Att)
            //$Workday++;
            //echo ($Att=="-" && $dd == date('Y-m-d',time()));
            $ss = $Att;
            if ($Att == "L" || $Att == "LWP"  || $Att == "CO" || ($Att == "-" && $dd == date('Y-m-d', time()))) {
                $Atts = getAttndForExcep($EmpID, $dd);
                $app = '';
                $db_app = $myDB->query("call sp_AppLeave('" . $EmpID . "','Leave','" . $dd . "')");
                if (count($db_app) > 0) {
                    foreach ($db_app as $key => $val) {
                        foreach ($val as $k => $v) {

                            $app = $v;
                        }
                    }
                }
                unset($db_app);

                if ((is_array($Atts) && strlen($Atts['InTime']) > 0) && ($Att == "-" && $dd == date('Y-m-d', time()) && $app == "Approved")) {
                    $date = $date . $dd . ",";
                } elseif (($Att == "L" || $Att == "LWP" || $Att == "CO") && (is_array($Atts) && strlen($Atts['InTime']) > 0)) {
                    $date = $date . $dd . ",";
                } elseif ($Att == "L" || $Att == "LWP" || $Att == "CO" || $Att == "A" || $Att == "WO" || $Att == "WONA" || $Att == "LANA") {
                } else {
                    $Workday++;
                }
            } else if ($Att == "A" || $Att == "WO" || $Att == "WONA" || $Att == "HO") {
            } else {
                $db_app = "select LeaveID from leavehistry where employeeid=? and ( MngrStatusID='Approve' or MngrStatusID='Pending' ) and leavetype = 'Leave' and cast(? as date) between cast(Datefrom as date) and cast(DateTo as date)";
                $selectQ = $conn->prepare($db_app);
                $selectQ->bind_param("ss", $EmpID, $dd);
                $selectQ->execute();
                $result = $selectQ->get_result();

                if ($result->num_rows > 0 && $result) {
                    $Atts = getAttndForExcep($EmpID, $dd);
                    if (is_array($Atts) && strlen($Atts['InTime']) > 0) {
                        $date = $date . $dd . ",";
                    } else {
                        $Workday++;
                    }
                } else {
                    $Workday++;
                }
            }
            $counter_check++;
            if ($counter_check >= 5) {
                $Workday++;
            }
            $dd = date('Y-m-d', strtotime($dd . ' -1 day'));
        }
    } else if ($Request == "Working on Holiday" || $Request == "Working on WeekOff") {
        $counter  = 0;
        $dd = date('Y-m-d', strtotime($dd . ' +0 day'));
        while ($Workday < 2) {

            $Att = getAttndForExcep($EmpID, $dd);

            if (is_array($Att) && strlen($Att['InTime']) > 0) {
                $sql = "call sp_GetRoasterDataByDate('" . $EmpID . "','" . $dd . "')";
                $mysqlError = $myDB->getLastError();
                $roaster_arr = $myDB->query($sql);
                $roaster = '-';
                foreach ($roaster_arr as $key => $val) {
                    foreach ($val as $ke => $va) {
                        $roaster  = $va;
                    }
                }
                $rtpff = strpos($roaster, 'HO');
                $rtpff = strpos($roaster, 'WO');
                if (strpos($roaster, 'HO') !== false ||  strpos($roaster, 'WO') !== false) {
                    $date = $date . $dd . ",";
                }

                $Workday++;
            }
            if ($counter > 6) {
                $Workday = 3;
            }
            $counter++;

            $dd = date('Y-m-d', strtotime($dd . ' -1 day'));
        }
    } else if ($Request == "Roster Change") {
        $dd = date('Y-m-d', strtotime($dd . ' +1 day'));
        $dd1 = date('Y-m-d', strtotime($dd . ' +8 day'));


        for ($i = 1; $i <= 9; $i++) {

            $sql = "select InTime,OutTime from roster_temp where EmployeeID = ? and DateOn =?";
            $selectQ = $conn->prepare($sql);
            $selectQ->bind_param("ss", $EmpID, $dd);
            $selectQ->execute();
            $res = $selectQ->get_result();
            $roaster = '';
            foreach ($res as $key => $val) {
                foreach ($val as $ke => $va) {
                    $roaster  = $va;
                }
            }
            if ($roaster != '' && $roaster != NULL && !empty($roaster) && $roaster != '-') {
                $date = $date . $dd . ",";
            }
            $dd = date('Y-m-d', strtotime($dd . ' +1 day'));
        }
    } else if ($Request == "Shift Change") {
        $date = date('Y-m-d');
    }
}



function getAttndForExcep($EmpID, $Date)
{
    //function return an arrays
    $res = "";
    $ID = '';
    $tbl = '';

    /* if (strlen($EmpID) == 10)
    {
        $ID = substr($EmpID,2,8);
        $ID = "9".$ID;
    }
    else if (strlen($EmpID) == 11)
    {
        $ID = substr($EmpID,strlen($EmpID) -5 ,5);
        $ID = "9".$ID;
    }*/

    $resQuery = "call sp_getAttndByDt('" . $EmpID . "','" . $Date . "')";
    $mysql = new MysqliDb();
    $res = $mysql->query($resQuery);
    $mysqlError = $mysql->getLastError();
    $r = '';
    foreach ($res as $key => $val) {

        $r = $val;
    }
    return $r;
}
function getAttndForAC($EmpID, $Date)
{
    $res = "";
    $ID = '';
    $tbl = '';

    /*if (strlen($EmpID) == 10)
    {
        $ID = substr($EmpID,2,8);
        $ID = "9".$ID;
    }
    else if (strlen($EmpID) == 11)
    {
        $ID = substr($EmpID,strlen($EmpID) -5 ,5);
        $ID = "9".$ID;
    } */

    $resQuery = "call sp_getAttndACByDt('" . $EmpID . "','" . $Date . "')";
    $mysql = new MysqliDb();
    $res = $mysql->query($resQuery);
    $mysqlError = $mysql->getLastError();
    $r = '';
    foreach ($res as $key => $val) {
        foreach ($val as $k => $v) {
            $r = $v;
        }
    }
    return $r;
}

function getCalcAttndByDate($EmpID, $Date)
{
    $d = "D" . intval(date('d', strtotime($Date)));
    $m = date('m', strtotime($Date));
    $y = date('Y', strtotime($Date));
    $resQuery = "call sp_getCalcAtndByDate('" . $d . "','" . $EmpID . "','" . $m . "','" . $y . "')";
    $mysql = new MysqliDb();
    $res = $mysql->query($resQuery);
    $mysqlError = $mysql->getLastError();
    $r = '';
    foreach ($res as $key => $val) {
        foreach ($val as $k => $v) {
            $r = $v;
        }
    }
    return $r;
}


echo $date;
