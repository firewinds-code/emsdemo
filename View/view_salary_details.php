<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if (isset($_SESSION)) {
	/*if(isset($_REQUEST['empid']))
	{
		$_SESSION['__user_logid']=$_REQUEST['empid'];
	}*/

	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;
		$referer_base = '';
		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer_base = $_SERVER['HTTP_REFERER'];
		}
		$current_Page = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
		if ($referer_base == $current_Page) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST['txt_dateMonth'])) {
			$date_To = $_POST['txt_dateMonth'];
			$date_From = $_POST['txt_dateYear'];
			$dept = $_POST['txt_dept'];
		} else {
			$date_To = intval(date('m', time()));
			$date_From = date('Y', time());
			$dept = 'CO';
		}
	}
	if (isset($_POST['empid']) && !empty($_POST['empid'])) {
		$EmployeeID = $_POST['empid'];
	} elseif (isset($_REQUEST['empid']) && !empty($_REQUEST['empid'])) {
		$EmployeeID = $_REQUEST['empid'];
	} else {
		$EmployeeID = $_SESSION['__user_logid'];
	}
	//$EmployeeID=$_REQUEST['empid'];
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$year = null;
$month = null;
if (isset($_POST['month'])) {
	$month = $_POST['month'];
	$year = $_POST['year'];
} else {
	$month = date('m', strtotime('last day of previous month'));
	$year = date('Y', strtotime('last day of previous month'));
}




/*if(null==$year&&isset($_POST['year']) )
		{

		    $year = $_POST['year'];
		 
		}else if(null==$year)
		{
			
			$lastday = strtotime('last day of previous month');
			$ldate = date('Y-m-d',$lastday);
			$year = date('Y',strtotime($ldate));
		    //$year = date("Y",time());  
		 
		}          
		if(null==$month&&isset($_POST['date']))
		{
		$getDate  =  explode('%',$_POST['date']);

			$month = date('m',strtotime($getDate[0]));
			$year = date('Y',strtotime($getDate[0]));
			
			
		}    
		if(null==$month&&isset($_POST['month']))
		{

		    $month = $_POST['month'];
		 
		}
		else if(null==$month){
			
			$lastday = strtotime('last day of previous month');
			$ldate = date('Y-m-d',$lastday);
			$month = date('m',strtotime($ldate));
		    //$month = date("m",time());
		 
		}   */

$nextMonth = $month == 12 ? 1 : intval($month) + 1;

$nextYear = $month == 12 ? intval($year) + 1 : $year;

$preMonth = $month == 1 ? 12 : intval($month) - 1;

$preYear = $month == 1 ? intval($year) - 1 : $year;



function getDatesFromRange($start, $end, $format = 'd')
{
	$array = array();
	$interval = new DateInterval('P1D');

	$realEnd = new DateTime($end);
	$realEnd->add($interval);

	$period = new DatePeriod(new DateTime($start), $interval, $realEnd);

	foreach ($period as $date) {
		$array[] = intval($date->format($format));
	}
	sort($array);
	return $array;
}

function show($EmployeeID, $month, $year)
{



	$fdate = date('Y-m-d', strtotime($year . '-' . $month . '-01'));
	$day = cal_days_in_month(CAL_GREGORIAN, date("m", strtotime($fdate)), date("Y", strtotime($fdate)));

	$ldate = date('Y-m-d', strtotime($year . '-' . $month . '-' . $day));

	$strsql = "select ctc,net_takehome,rt_type,pli_ammount,pf+pf_employer as `pf`,esis+esi_employer as `esis`,pli_status from salary_details where EmployeeID ='" . $_SESSION['__user_logid'] . "'";
	$myDB = new MysqliDb();
	$dshr = $myDB->query($strsql);
	$nettakehome = $rt_type = $DOD = $DOJ = $df_id = $cm_id = $ADOD = $stipend = $stipendday = $plistatus = "";
	$pli = $pf = $esis = $pt = $asset = $insurance = $other = $tds = $netdeduction = $netpayble = $flag = $stipendperday = 0;
	$calc = $calcstipend = $notpay = $notpaystipend = $totalstipendday = 0;
	$totalrate = $rateperday = $totalratestipend = $rateperdaystipend = $totalcredit = 0;
	if ($dshr > 0) {
		$ctc = $dshr[0]['ctc'];
		$nettakehome = $dshr[0]['net_takehome'];
		$rt_type = $dshr[0]['rt_type'];
		$pli = $dshr[0]['pli_ammount'];
		$pf = $dshr[0]['pf'];
		$esis = $dshr[0]['esis'];
		$plistatus = $dshr[0]['pli_status'];
	}

	if ($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") {
		/////////////////  Calculate DOD ///////////////////////////////

		$strsql = "select DOD,DOJ,df_id,cm_id from whole_details_peremp where EmployeeID ='" . $_SESSION['__user_logid'] . "'";
		$myDB = new MysqliDb();
		$ds = $myDB->query($strsql);
		if ($ds > 0) {
			$DOD = $ds[0]['DOD'];
			$DOJ = $ds[0]['DOJ'];
			$df_id = $ds[0]['df_id'];
			$cm_id = $ds[0]['cm_id'];
		}

		$data_dod = $myDB->query("select first_dod,day_stpn from personal_details where EmployeeID ='" . $_SESSION['__user_logid'] . "'");

		$date_fd  = date('Y-m-d', strtotime('-1 days ' . $DOJ));


		if (isset($data_dod[0]['first_dod']) && ($df_id == 74 || $df_id == 77)) {
			if (!empty($data_dod[0]['first_dod']) && strtotime($data_dod[0]['first_dod'])) {

				$date_fd = date('Y-m-d', strtotime('-1 days ' . $data_dod[0]['first_dod']));
			}
		}
		$ADOD = $date_fd;
		//$ADOD = date('Y-m-d',strtotime('+1 days '.$date_fd));

		$strsql = "select Stipend,StipendDays from new_client_master where cm_id ='" . $cm_id . "'";
		$myDB = new MysqliDb();
		$ds = $myDB->query($strsql);
		if ($ds > 0) {
			$stipend = $ds[0]['Stipend'];
			$stipendday = $ds[0]['StipendDays'];
			$stipendperday = $stipend / $stipendday;
		}


		/////////////////////////////////////////////////////////////////////////////////////////


		if ($ADOD >= $fdate && $ADOD <= $ldate) {
			$datetime1 = date_create($ADOD);
			$datetime2 = date_create($fdate);
			$interval = date_diff($datetime2, $datetime1);
			$totalstipendday = $interval->d;
			$date_counter1 = intval(date('j', strtotime($ADOD)));
			$flag = 1;
		}
	}




	$date_range = getDatesFromRange($fdate, $ldate);
	foreach ($date_range as &$value_dc) {
		$value_dc = 'D' . $value_dc;
	}
	unset($value_dc);
	$str_t = implode(',', $date_range);
	$h_month = date('m', strtotime($ldate));
	$h_year = date('Y', strtotime($ldate));
	$date_counter = intval(date('j', strtotime($ldate)));
	//$totalrate = $nettakehome / $date_counter;

	$strsql = "select " . $str_t . " from calc_atnd_master where EmployeeID ='" . $EmployeeID . "' and month ='" . $month . "' and year ='" . $year . "' limit 1";

	$myDB = new MysqliDb();
	$result = $myDB->query($strsql);


	if ($result > 0) {
		$counter = 1;

		foreach ($result as $key => $value) {

			foreach ($value as $k => $val) {
				if ($counter <= $date_counter) {
					if (strtoupper($val)  == 'P' || strtoupper($val) == 'L' || strtoupper($val) == 'HO' || strtoupper($val) == 'CO' || strtoupper($val) == 'WO') {
						if (strtoupper($val) == 'P' && $rt_type == '3') {
						} else {
							if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {
								if ($counter <= $date_counter1) {
									$calcstipend++;
								} else {
									$calc++;
								}
							} else {
								$calc++;
							}
						}
					} else if (strtoupper($val[0]) == 'P') {
						if ($rt_type == '3') {
						} else {
							if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {
								if ($counter <= $date_counter1) {
									$calcstipend++;
								} else {
									$calc++;
								}
							} else {
								$calc++;
							}
						}
					} else if (strtoupper($val) == 'H') {
						if ($rt_type == '3') {
						} else {
							if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {
								if ($counter <= $date_counter1) {
									$calcstipend++;
								} else {
									$calc++;
								}
							} else {
								$calc++;
							}
						}
					} else if (strtoupper($val[0]) == 'H' && strtoupper($val) != 'HWP') {
						if ($rt_type == '3') {
						} else {
							if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {
								if ($counter <= $date_counter1) {
									$calcstipend++;
								} else {
									$calc++;
								}
							} else {
								$calc++;
							}
						}
					} else if (strtoupper($val) == 'HWP') {

						if ($rt_type == '3') {
							if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {
								if ($counter <= $date_counter1) {
									$calcstipend++;
								} else {
									$calc++;
								}
							} else {
								$calc++;
							}
						} else {
							if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {
								if ($counter <= $date_counter1) {

									$calcstipend = $calcstipend + 0.5;
								} else {
									$calc = $calc + 0.5;
								}
							} else {
								$calc = $calc + 0.5;
							}
						}
					} else if ($val == 'HWP(Biometric Issue)' || substr($val, 0, 3) == 'HWP') {
						if ($rt_type == '3') {
							if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {
								if ($counter <= $date_counter1) {
									$calcstipend++;
								} else {
									$calc++;
								}
							} else {
								$calc++;
							}
						} else {
							if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {
								if ($counter <= $date_counter1) {

									$calcstipend = $calcstipend + 0.5;
								} else {
									$calc = $calc + 0.5;
								}
							} else {
								$calc = $calc + 0.5;
							}
						}
					} else if ($val == 'L(Biometric Issue)' || substr($val, 0, 3) == 'L(B') {
						if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && flag == 1) {
							if ($counter <= $date_counter1) {
								$calcstipend++;
							} else {
								$calc++;
							}
						} else {
							$calc++;
						}
					}
				}
				$counter++;
			}
		}

		///// Calculate CTC Per day ////////////
		$rateperday = $ctc / $date_counter;
		$totalrate = $rateperday * $calc;

		///// Calculate PF Per day ////////////
		$rateperdaypf = $pf / $date_counter;
		$totalratepf = $rateperdaypf * $calc;

		///// Calculate ESIS Per day ////////////
		$rateperdayesis = $esis / $date_counter;
		$totalrateesis = $rateperdayesis * $calc;

		if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {

			$totalratestipend = $calcstipend * $stipendperday;
			$notpay = $date_counter - ($calc + $calcstipend);
			$totalcredit = $totalrate + $totalratestipend;
			//echo 'Stipend day - '. $calcstipend. ', salary day -'. $calc.' ,not pay day- '.$notpay ;
		} else {
			$notpay = $date_counter - $calc;
			$totalcredit = $totalrate;
			//echo 'salary day -'. $calc.' ,not pay day- '.$notpay ;
		}
	}
	if ($plistatus == "Yes")
		$pli = ($totalrate * 10) / 100;
	else
		$pli = 0;

	$netdeduction = round($pli, 0, PHP_ROUND_HALF_UP) + round($totalratepf, 0, PHP_ROUND_HALF_UP) + round($totalrateesis, 0, PHP_ROUND_HALF_UP) + round($pt, 0, PHP_ROUND_HALF_UP) + round($asset, 0, PHP_ROUND_HALF_UP) + round($insurance, 0, PHP_ROUND_HALF_UP) + round($other, 0, PHP_ROUND_HALF_UP) + round($tds, 0, PHP_ROUND_HALF_UP);
	$netpayble = round($totalcredit - $netdeduction, 0, PHP_ROUND_HALF_UP);

	$content = '<table style="width:100%">' .
		'<tr><th class="tblcolor1" style="width: 25%">Particulars</th><th class="tblcolor1">Count (in Days)</th>' .
		'<th class="tblcolor1">Amount Per Day (in Rs.)</th><th class="tblcolor1">Total (in Rs.)</th></tr>';

	$content .= '<tr><th class="tblcolor1">Days in Month</th><td class="tblcolor1" style="">';

	$content .= cal_days_in_month(CAL_GREGORIAN, date("m", strtotime($fdate)), date("Y", strtotime($fdate)));
	$content .= '</td>	<td class="tblcolor1">';
	$content .= round($rateperday, 0, PHP_ROUND_HALF_UP);
	$content .= '</td><td class="tblcolor1">';
	$content .= round($ctc, 0, PHP_ROUND_HALF_UP);
	$content .= '</td></tr>';

	if (($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") && $flag == 1) {
		$content .= '<tr><th class="tblcolor1">Stipend</th>' .
			'<td class="tblcolor1">';
		$content .=  $calcstipend;
		$content .= 	'</td><td class="tblcolor1">';
		$content .=  $stipendperday;
		$content .=	'</td><td class="tblcolor1">';
		$content .=  $totalratestipend;
		$content .=	'</td></tr> ';
	}




	$content .= '<tr><th class="tblcolor1">Payable</th><td class="tblcolor1">';
	$content .=	$calc;
	$content .=	'</td><td class="tblcolor1">';
	$content .=	round($rateperday, 0, PHP_ROUND_HALF_UP);
	$content .=  '</td><td class="tblcolor1">';
	$content .=	round($totalrate, 0, PHP_ROUND_HALF_UP);
	$content .=  '</td></tr><tr><th class="tblcolor1">without payable</th><td class="tblcolor1">';

	$content .= 	$notpay;
	$content .=	'</td><td class="tblcolor1">0</td><td class="tblcolor1">0</td></tr>';

	if ($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") {
		$content .= '<tr><th class="tblcolor1">OT Hours</th><td class="tblcolor1"></td><td class="tblcolor1"></td>' .
			'<td class="tblcolor1"></td></tr>';
	}

	$content .= '<tr><th class="tblcolor1">PLI</th><td class="tblcolor1"></td><td class="tblcolor1"></td><td class="tblcolor1"></td></tr>';

	if ($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") {
		$content .= '<tr><th class="tblcolor1">Client Inc</th><td class="tblcolor1"></td><td class="tblcolor1"></td><td class="tblcolor1"></td></tr>';
	}


	$content .= '<tr><th class="tblcolor1">Referal Inc</th><td class="tblcolor1"></td><td class="tblcolor1"></td><td class="tblcolor1"></td></tr>';

	if ($_SESSION["__df_id"] == "74" || $_SESSION["__df_id"] == "77" || $_SESSION["__df_id"] == "146" || $_SESSION["__df_id"] == "147" || $_SESSION["__df_id"] == "148" || $_SESSION["__df_id"] == "149") {
		$content .= '<tr><th class="tblcolor1">Split Payable Days</th><td class="tblcolor1"></td><td class="tblcolor1"></td><td class="tblcolor1"></td></tr>';
	}

	$content .= '<tr><th class="tblcolor1">Others</th><td class="tblcolor1"></td><td class="tblcolor1"></td><td class="tblcolor1"></td></tr>';

	$content .= '<tr><th class="tblcolor1">Total Credit</th><td class="tblcolor1"></td><td class="tblcolor1"></td><td class="tblcolor1">';
	$content .=  round($totalcredit, 0, PHP_ROUND_HALF_UP);
	$content .=  '</td></tr>';

	$content .= '<tr><th></th><td colspan="3"></td></tr>';

	$content .= '<tr><th class="tblcolor1" style="width: 25%">Deductions</th><th class="tblcolor1"></th><th class="tblcolor1"></th><th class="tblcolor1"></th></tr>';

	$content .= '<tr><th class="tblcolor1">PLI</th><td class="tblcolor1"></td><td class="tblcolor1"></td><td class="tblcolor1">';
	$content .= round($pli, 0, PHP_ROUND_HALF_UP);
	$content .=  '</td></tr>';

	$content .= '<tr><th class="tblcolor1">PF</th><td class="tblcolor1">';
	$content .= $calc;
	$content .= '</td><td class="tblcolor1">';
	$content .=  round($rateperdaypf, 0, PHP_ROUND_HALF_UP);
	$content .=  '</td><td class="tblcolor1">';
	$content .=  round($totalratepf, 0, PHP_ROUND_HALF_UP);
	$content .=  '</td></tr>';

	$content .= '<tr><th class="tblcolor1">ESIC</th><td class="tblcolor1">';
	$content .=	$calc;
	$content .=	'</td><td class="tblcolor1">';
	$content .=	round($rateperdayesis, 0, PHP_ROUND_HALF_UP);
	$content .=	'</td><td class="tblcolor1">';
	$content .=	round($totalrateesis, 0, PHP_ROUND_HALF_UP);
	$content .=	'</td></tr>';

	$content .= '<tr><th class="tblcolor1">Professional Tax</th>' .
		'<td class="tblcolor1"></td>' .
		'<td class="tblcolor1"></td>' .
		'<td class="tblcolor1">';
	$content .=	round($pt, 0, PHP_ROUND_HALF_UP);
	$content .=	'</td></tr>';

	$content .= '<tr><th class="tblcolor1">Asset Recovery</th>' .
		'<td class="tblcolor1"></td>' .
		'<td class="tblcolor1"></td><td class="tblcolor1">';
	$content .=	round($asset, 0, PHP_ROUND_HALF_UP);
	$content .=	'</td></tr>';

	$content .= '<tr><th class="tblcolor1">Insurance</th>' .
		'<td class="tblcolor1"></td><td class="tblcolor1"></td>' .
		'<td class="tblcolor1">';
	$content .=	round($insurance, 0, PHP_ROUND_HALF_UP);
	$content .=	'</td></tr>';

	$content .= '<tr><th class="tblcolor1">Others*</th>' .
		'<td class="tblcolor1"></td><td class="tblcolor1"></td>' .
		'<td class="tblcolor1">';
	$content .=	round($other, 0, PHP_ROUND_HALF_UP);
	$content .=	'</td></tr>';

	$content .= '<tr><th class="tblcolor1">TDS</th><td class="tblcolor1"></td><td class="tblcolor1"></td>' .
		'<td class="tblcolor1">';
	$content .=	round($tds, 0, PHP_ROUND_HALF_UP);
	$content .=	'</td></tr>';

	$content .= '<tr><th class="tblcolor1">Net Deduction</th><td class="tblcolor1"></td><td class="tblcolor1"></td>' .
		'<td class="tblcolor1">';
	$content .=	round($netdeduction, 0, PHP_ROUND_HALF_UP);
	$content .=	'</td></tr>';

	$content .= '<tr><th></th><td colspan="3"></td></tr>';

	$content .= '<tr><th class="tblcolor1">Net Payable Salary</th><td class="tblcolor1"></td><td class="tblcolor1"></td>' .
		'<td class="tblcolor1">';
	$content .=	round($netpayble, 0, PHP_ROUND_HALF_UP);
	$content .=	'</td> </tr>';


	$content .=  '</table>';

	return $content;
}


?>

<style>
	table,
	th,
	td {
		border: 1px solid black;
		border-collapse: collapse;
		font-size: :15pt;
	}

	th,
	td {
		padding: 5px;
		text-align: left;

	}

	th {}

	.tblcolor1 {
		background-color: rgb(29, 173, 196);
		color: #fff;
		text-align: center;
		font-size: 11pt;
	}

	.header {
		line-height: 65px;
		vertical-align: middle;
		position: absolute;
		left: 0;
		top: -5px;
		width: 100%;
		height: 65px;
		text-align: left;
	}

	div#calendar div.header span.title {

		line-height: 65px;
		letter-spacing: 0;
		font-size: 24px;
		position: relative;
		top: -9px;
	}
</style>

<script>
	function nextPage(nmonth, nyear) {
		$('#newMonth').val(nmonth);
		$('#newYear').val(nyear);
		document.getElementById('indexForm').submit();
	}
</script>



<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Salary Details</span>
	<input type="hidden" name='month' id='newMonth' value="<?php echo  date('m', strtotime('last day of previous month')); ?>">
	<input type='hidden' name='year' id='newYear' value="<?php echo  date('Y', strtotime('last day of previous month')); ?>">
	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<div>

				<?php if (date('m', strtotime('last day of previous month')) == $month) { ?>
					<span style="margin-left: 28px;"><a class="prev material-icons" onclick=" return nextPage(<?php echo $preMonth; ?>,<?php echo $preYear; ?>);" style="font-size: 43px;"> keyboard_arrow_left</a></span>
				<?php } ?>

				<?php if (date('m', strtotime('last day of previous month')) != $month) { ?>
					<span style="margin-left: 28px;"><a class="next material-icons" onclick=" return nextPage(<?php echo $nextMonth; ?>,<?php echo $nextYear; ?>);" style="font-size: 43px;"> keyboard_arrow_right</a></span>
				<?php } ?>


				<span>
					<h4 style="margin-top: -69px; margin-left: 47px; font-size: 20;"><?php echo date('F', mktime(0, 0, 0, $month, 10)) . ' - ' . $year ?> Salary Details</h4>
				</span>


			</div>


			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				echo show($_SESSION['__user_logid'], $month, $year);
				//echo $_REQUEST['empid'];
				//echo show($_REQUEST['empid'],$month,$year); 
				?>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>