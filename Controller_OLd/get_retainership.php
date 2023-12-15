<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Untitled Document</title>
  <link rel="stylesheet" href="../Style/bootstrap.css" />
  <style>
    ol.test {
      counter-reset: list;
    }

    ol.test>li {
      list-style: none;
      position: relative;
    }

    ol.test>li:before {
      counter-increment: list;
      content: counter(list, lower-alpha) ") ";
      position: absolute;
      left: -1.4em;
    }

    @media print {
      body {
        font-size: 14px;
      }
    }
  </style>
</head>

<body>
  <form name="indexForm" enctype="multipart/form-data" role="form" id="indexForm" method="post" action="<?php echo ($_SERVER['PHP_SELF']); ?>">
    <?php

    require_once(__dir__ . '/../Config/init.php');
    require_once(CLS . 'MysqliDb.php');
    $myDB = new MysqliDb();
    $conn = $myDB->dbConnect();
    $userType = clean($_SESSION['__user_type']);
    if (!($userType == 'ADMINISTRATOR' ||  $userType == 'HR' ||  $userType == 'AUDIT')) {
      echo 'Wrong way to access this link.';
      exit();
    }

    if (isset($_REQUEST['EmpID'])) {
      $EmployeeID = clean($_REQUEST['EmpID']);
    } else {
      $EmployeeID = cleanUserInput($_POST['Empid']);
    }
    function convertNumberToWord($num = false)
    {
      $num = str_replace(array(',', ' '), '', trim($num));
      if (!$num) {
        return false;
      }
      $num = (int) $num;
      $words = array();
      $list1 = array(
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven',
        'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
      );
      $list2 = array('', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');
      $list3 = array(
        '', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion', 'Quintillion', 'Sextillion', 'Septillion',
        'Octillion', 'Nonillion', 'Decillion', 'Undecillion', 'Duodecillion', 'Tredecillion', 'Quattuordecillion',
        'Quindecillion', 'Sexdecillion', 'Septendecillion', 'Octodecillion', 'Novemdecillion', 'Vigintillion'
      );
      $num_length = strlen($num);
      $levels = (int) (($num_length + 2) / 3);
      $max_length = $levels * 3;
      $num = substr('00' . $num, -$max_length);
      $num_levels = str_split($num, 3);
      for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : '') . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ($tens < 20) {
          $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
        } else {
          $tens = (int)($tens / 10);
          $tens = ' ' . $list2[$tens] . ' ';
          $singles = (int) ($num_levels[$i] % 10);
          $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
      } //end for loop
      $commas = count($words);
      if ($commas > 1) {
        $commas = $commas - 1;
      }
      return implode(' ', $words);
    }
    $gender = 'Mr.';
    $sql = 'select pd.EmployeeName,pd1.EmployeeName, whole_details_peremp.EmployeeID,whole_details_peremp.EmployeeName,DOJ,designation,ctc,address_details.address,address_details.other,address_details.city,address_details.state,address_details.district,address_details.zip,status_table.onFloor,whole_details_peremp.Gender from whole_details_peremp left outer join salary_details on salary_details.EmployeeID = whole_details_peremp.EmployeeID left outer join address_details on address_details.EmployeeID = whole_details_peremp.EmployeeID left outer join personal_details pd on pd.EmployeeID = whole_details_peremp.ReportTo left outer join personal_details pd1 on pd1.EmployeeID = whole_details_peremp.account_head left outer join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID  where whole_details_peremp.EmployeeID=? limit 1';
    $select = $conn->prepare($sql);
    $select->bind_param("s", $EmployeeID);
    $select->execute();
    $res = $select->get_result();
    $result = $res->fetch_row();
    // $result = $myDB->query($sql);

    if ($res->num_rows > 0 && $result) {
      $EmployeeID = clean($result[2]);
      $EmployeeName = clean($result[0]);
      $DOJ = clean($result[13]);


      $designation = clean($result[5]);
      if ($designation == 'CSA') {
        $designation = 'Customer Support Associate';
      } elseif ($designation == 'Senior CSA') {
        $designation = 'Senior Customer Support Associate';
      }
      if (strtoupper(clean($result[14])) == 'FEMALE') {
        $gender = 'Ms.';
      } else {
        $gender = 'Mr.';
      }
      $ctc = clean($result[6]);

      $address1 = clean($result[7]);
      $address2 = clean($result[8]);
      $city = clean($result[9]);
      $state = clean($result[10]);
      $district = clean($result[11]);
      $pincode = clean($result[12]);
      $reportTo = clean($result[3]);
      $accountHead = clean($result[1]);
    ?>
      <div id="div_print" class="container">
        <input type="hidden" name="Empid" id="Empid" value="<?php echo $EmployeeID; ?>" />
        <div class="col-sm-12 pull-left" style="width: 100%;">


          <?php
          if ('AE' == substr($EmployeeID, 0, 2)) {
          ?>
            <div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;"><img src="../Style/images/client_logo.JPG" style="width:
 220px;height: 100px;" /></div>
          <?php
          } else {
          ?>
            <div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;"><img src="../Style/images/newLogo cogent.jpg" style="width:
 220px;height: 100px;" /></div>
          <?php
          }
          ?>

        </div>
        <br />
        <span>
          <h5><strong><?php if (date('d', strtotime($DOJ)) == '1') {

                        echo '1<sup>st</sup>';
                      } elseif (date('d', strtotime($DOJ)) == '2') {
                        echo '2<sup>nd</sup>';
                      } elseif (date('d', strtotime($DOJ)) == '3') {
                        echo '3<sup>rd</sup>';
                      } else {
                        echo date('d', strtotime($DOJ)) . '<sup>th</sup>';
                      }
                      echo date(' F Y', strtotime($DOJ)); ?> </h5></strong>
        </span>

        <br />
        <p>Dear <?php echo '<b>' . $gender . ' ' . $EmployeeName . '</b>'; ?>,<br /> </p>
        <p> Reference ID: <strong><?php echo $EmployeeID; ?></strong></p>
        <P><?php echo $address1; ?></P>
        <!--<P><?php echo $address2; ?></P>-->
        <P><?php echo '<strong>City :</strong> ' . $city; ?></P>
        <P><?php echo '<strong>State :</strong> ' . $state; ?></P>
        <!--<P><?php echo $district; ?></P>-->
        <!--<P><?php echo $pincode; ?></P>-->

        <p><br />
        </p>

        <h5><u><b>Sub: Retainership Agreement for Client Interaction Services at our Office in Noida</b></u></h5>
        <br />
        <p>This refers to your discussions with our Company regarding the above agreement.<br />
          As mutually agreed, following are the terms and conditions:</p>
        <ol type="1">
          <li><strong><u>Nature of Service:</u></strong> You will provide services for Client Interaction through Voice, preparation of Computer database/ reports/ other documents.The detailed scope of your service contract is enclosed in Annexure-I.</li><br />
          <li><strong><u>Retainership fee</u></strong>
            <ol type="a" class="test">
              <li>You will be paid a maximum monthly service fee @ Rs.<strong style="min-width: 50px;text-align: center;"> <?php echo round($ctc); ?>/-&nbsp;<b>(</b><?php
                                                                                                                                                                      echo convertNumberToWord(round($ctc));
                                                                                                                                                                      ?>Rupees Only</strong><b>&nbsp;)</b> on successful delivery of designated tasks, standard IT Laws will be applicable.</li>
              <li>You will be expected to carry out the services as and when required by the company.</li>
            </ol>
          </li>
        </ol>
        <ol type="1" start="3">
          <li><strong><u>Validity of Service Agreement: </u></strong>The Service agreement will be valid for a period of one year from date of this letter, after which the same will expire automatically unless renewed by us in writing. </li>
        </ol>
        <ol type="1" start="4">
          <li><strong><u>Procedure for Payment:</u></strong> You will submit a written claim statement every month to Office in charge giving no. of service days, work done etc. The claim will be paid after verification by the Office in charge as per terms of this letter. </li><br />
          <li><strong><u>Offices assigned to you for Client Interaction Services:</u></strong> The present service contract is for our office located in Noida. However, company will be at liberty to transfer you to any other branch office.</li><br />
          <li><strong><u>Accountability for Work</u></strong><strong>:</strong> You will be accountable for your work to <strong style="min-width: 50px;display: inline-block;text-align: center;"> <?php echo $reportTo; ?></strong> and in his absence to <strong style="min-width: 50px;display: inline-block;text-align: center;"> <?php echo $accountHead; ?>.</strong></li><br />
          <li><strong><u> Other Terms of engagement:</u></strong><strong> </strong></li>

          <p>
          <ol type="a" class="test">
            <li> Please Note that this is a need based part time retainership agreement for rendering out –sourced services for specific duration. You are not an employee of the company and will make no claim of any other nature normally applicable to employees of the company.</li>
            <li> Indemnity to Company against personal Injuries etc: Company will not be responsible for any injuries or accidents met by you during the course of your work at various offices or during travel to offices or out station travel elsewhere. You will indemnify the company against any liabilities in this regard.</li>
          </ol>
        </ol>
        <br />
        <hr style="margin-top: 15px;margin-bottom: 10px;border: 1px solid #9a9a9a;" />
        <p style="text-align: center;color: #a0a0a0;font-size: 12px;">
          <?php
          if ('AE' == substr($EmployeeID, 0, 2)) {
          ?>
            Aurum E-Serve LLP address: A-23, Sector 105, Noida
          <?php
          } else if ('MU' == substr($EmployeeID, 0, 2)) { ?>
            Cogent E Services Limited</b> 5th Floor, Lodha I think, Tower A, Palava City (Phase I) Mumbai, Maharastra 421204
            <?php
          } else {
            ?>Cogent E Services Limited</b> C-100, Sector 63 Noida - 201301,India Website : www.cogenteservices.com
          <?php
          }
          ?></p>
        <br />
        Kindly send the duplicate copy of this Letter Duly Signed to us as a token of your acceptance of the above terms for our record.We look forward to having an enriching association with you.<br /><br /><br /><br />

        <table border="0" cellspacing="0" cellpadding="0" width="100%;">
          <tr>
            <td width="319" valign="top">
              <p>For <b><?php
                        if ('AE' == substr($EmployeeID, 0, 2)) {
                        ?>For Aurum E-Serve LLP </strong><?php } else {
                                                          ?>For Cogent E Services Limited<?php
                                                                                        }
                                                                                          ?></b></p>
              <p><img src="../Style/img/sk_sign.jpeg" style="width: 120px;height: 45px;" /></p>
              <p>(S. K Garg)<br />
                Authorized Signatory</p>
            </td>
            <td width="319" valign="top" style="text-align:center">
              <p>All clauses read and understood</p>
              <p>&nbsp;</p>
              <p>(<strong><?php echo ' ' . $EmployeeName . ' '; ?></strong>)</p>
            </td>
          </tr>
        </table>
        <br />
        <br />
        <p><strong><u>Annexure -I</u></strong><br />
          <strong><u>SERVICES TO BE RENDERED DURING PERIOD OF SERVICE </u></strong>
        </p>
        <ol>
          <li>To interact with customers on the phone for the purpose of providing stipulated information or receiving complaints.<u></u></li>
          <li>To make computer data entries needed for compiling a database of all the related activities of the interactions.<u></u></li>
          <li>To maintain computer database related to all the interactions conducted with the clients.<u></u></li>
          <li>To compile periodical reports in the approved  format  and circulated as advised by the Office In – Charge<u></u></li>
          <li>To assist the office in preparing the Audit related reports to be circulated as advised.<u></u></li>
          <li>To maintain computerized report of completion of designated tasks and forward the same to HR.<u></u></li>
          <li>To undertake different client interaction services (inbound or outbound)  as directed by the office in charge from time to time<u></u></li>
        </ol>
        <p>&nbsp;</p>

      </div>
      </div>
      <p>
        <!--<input type="submit" id="export_btn" name="export_btn" style="display: none;float: left;" value="Download"/> -->
        <input type="button" id="print_btn" name="print_btn" onClick="printdiv('div_print');" style="display: block;" value="Print" />
      </p>
      <br />
  </form>
</body>
<?php

      if (isset($_POST['export_btn'])) {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=offerlatter" . $EmployeeID . ".doc");
      }

      echo "";
    }
?>
<script>
  function printdiv(printpage) {
    var headstr = "<html><head><title></title></head><body>";
    var footstr = "</body>";
    var newstr = document.all.item(printpage).innerHTML;
    var oldstr = document.body.innerHTML;
    document.body.innerHTML = headstr + newstr + footstr;
    window.print();
    document.body.innerHTML = oldstr;
    return false;
  }
</script>

</html>