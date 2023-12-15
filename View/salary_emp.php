<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
ini_set('display_errors', '1');
ini_set('log_errors', 'On');
ini_set('display_errors', 'Off');
ini_set('error_reporting', E_ALL);
$last_to = $last_from = $last_to = $emp_nam = $emp_empname =  $searchBy =  '';
$classvarr = "'.byID'";
// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['btn_ED_Search'])) {
  $emp_nam = (isset($_POST['ddl_ED_Emp_Name']) ? $_POST['ddl_ED_Emp_Name'] : null);
  $searchBy = $_POST['ddl_ED_Emp_Name'];
}
?>
<style>
  input[disabled]:not(.dropdown-trigger),
  input[readonly]:not(.dropdown-trigger),
  input[disabled]:not(.dropdown-trigger) {
    color: #000 !important;
  }

  input[disabled]:not(.dropdown-trigger)+label.active,
  input[readonly]:not(.dropdown-trigger)+label.active,
  input[disabled]:not(.dropdown-trigger)+label.active {
    color: #1dadc4 !important;
  }
</style>
<div id="content" class="content">
  <span id="PageTittle_span" class="hidden">Salary Helpdesk </span>
  <div class="pim-container">
    <div class="form-div">
      <h4>Salary Helpdesk</h4>
      <div class="schema-form-section row">
        <div class=" byID">
          <div class="input-field col s4 m4 4">
            <input type="text" id="ddl_ED_Emp_Name" name="ddl_ED_Emp_Name" title="Enter Employee ID Must Start With CE and Not Less Then 10 Char" value="<?php echo $emp_nam; ?>">
            <label for="ddl_ED_Emp_Name"> Employee ID</label>
          </div>
        </div>
        <div class=" byMonth">
          <div class="input-field col s4 m4 4">
            <div class="form-group">
              <?php

              echo '<select id="month" name="month">' . "\n";
              echo '<option value="NA">Select Month</option>' . "\n";
              for ($i_month = 1; $i_month <= 12; $i_month++) {
                $selected = ($_POST['month']  == $i_month ? ' selected' : '');
                echo '<option value="' . $i_month . '"' . $selected . '>' . date('F', mktime(0, 0, 0, $i_month, 10)) . '</option>' . "\n";
              }
              echo '</select>' . "\n";
              ?>
              <label for="month" class="active-drop-down active">Month</label>
            </div>
          </div>
        </div>
        <div class=" byYear">
          <div class="input-field col s4 m4 4">
            <div class="form-group">
              <?php
              $year_start  = 2021;
              $year_end = date('Y'); // current Year
              echo '<select id="Years" name="Years">' . "\n";
              echo '<option value="NA">Select Year</option>' . "\n";

              for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
                $selected = ($_POST['Years'] == $i_year ? ' selected' : '');
                echo '<option value="' . $i_year . '"' . $selected . '>' . $i_year . '</option>' . "\n";
              }
              echo '</select>' . "\n";
              ?>
              <label for="year" class="active-drop-down active">Year</label>
            </div>
          </div>
        </div>
        <div class="input-field col s12 m12 right-align">
          <button type="submit" name="btn_ED_Search" title="Click Here To Get Search Result" id="btn_ED_Search" class="btn waves-effect waves-green">Search</button>
        </div>
        <div id="pnlTable">
          <?php
          if (isset($_POST['btn_ED_Search'])) {
            $EmpID = $_POST['ddl_ED_Emp_Name'];
            $month = $_POST['month'];
            $year = $_POST['Years'];
            $user = $_SESSION['__user_logid'];
            $location = 'select location from personal_details where EmployeeID="' . $EmpID . '"';
            $myDB = new MysqliDb();
            $res = $myDB->query($location);
            if (count($res) > 0) {
              foreach ($res as $key => $val) {
                $loc = $val['location'];
              }
              $sql = "select location from salary_master where EmpID='" . $user . "' and location like '%" . $loc . "%'";
              $myDB = new MysqliDb();
              $results = $myDB->query($sql);
              if (count($results) > 0) {
				  $sqlConnect="";				  
                $sqlConnect = 'select * from salary_certificate where Empid="' . $EmpID . '" and month="' . $month . '" and year="' . $year . '" and designation="CSA"';
				if($user=="CE10091236" || $user=="CE03070003"){
				$sqlConnect = 'select * from salary_certificate where Empid="' . $EmpID . '" and month="' . $month . '" and year="' . $year . '" ';
				}
                $myDB = new MysqliDb();
                $result = $myDB->query($sqlConnect);
                // echo ($sqlConnect);
                // die;
                if (count($result) > 0) {
                  foreach ($result as $key => $value) {
                    $empid = $value['Empid'];
                    $empname = $value['EmpName'];
                    $year = $value['year'];
                    $month = $value['month'];
                    $des = $value['designation'];
                    $DOJ = $value['DOJ'];
                    $DOD = $value['DOD'];
                    $function = $value['function'];
                    $client = $value['client'];
                    $process = $value['process'];
                    $sub_pro = $value['sub_process'];
                    $bank = $value['bank'];
                    $bank_account_no = $value['bank_account_number'];
                    $name_as_per_bank = $value['name_as_per_bank'];
                    $IFSC = $value['IFSC_code'];
                    $ctc = $value['ctc'];
                    $take_home = $value['take_home'];
                    $extra_pay_days = $value['extra_pay_days'];
                    $total_pay_days = $value['total_pay_days'];
                    $net_salary = $value['net_salary'];
                    $deduction = $value['deduction'];
                    $net_paysalary = $value['net_paysalary'];
                    $trainig_stipend = $value['trainig_stipend'];
                    $attendance_bonus = $value['attendance_bonus'];
                    $split_incentive = $value['split_incentive'];
                    $sales_allowance = $value['sales_allowance'];
                    $total_Inct = $value['total_Inct'];
                    $over_time = $value['over_time'];
                    $night_allowance = $value['night_allowance'];
                    $ref_Inct = $value['ref_Inct'];
                    $PLI_payable = $value['PLI_payable'];
                    $discrepancy_arrears = $value['discrepancy_arrears'];
                    $remarks = $value['remarks'];
                    $total_add = $value['total_add'];
                    $head_phone = $value['head_phone'];
                    $Id_card = $value['Id_card'];
                    $insurance = $value['insurance'];
                    $TDS = $value['TDS'];
                    $provisional_tax = $value['provisional_tax'];
                    $NP_recovery = $value['NP_recovery'];
                    $ESIC = $value['ESIC'];
                    $EPF = $value['EPF'];
                    $PLI = $value['PLI'];
                    $remark = $value['remark'];
                    $total_less = $value['total_less'];
                  }
          ?>

                  <h4>Basic Details</h4>
                  <div class="col s12 m12">
                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($empid); ?>" id="empid" name="empid" readonly="true" />
                      <label for="empid">EmployeeID</label>
                    </div>

                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($empname); ?>" id="empname" name="empname" readonly=true />
                      <label for="empname">Employee Name</label>
                    </div>

                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($DOJ); ?>" id="DOJ" name="DOJ" readonly="true" />
                      <label for="DOJ">DOJ</label>
                    </div>
                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($DOD); ?>" id="DOD" name="DOD" readonly="true" />
                      <label for="DOD">DOD</label>
                    </div>

                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($des); ?>" id="des" name="des" readonly="true" />
                      <label for="des">Designation</label>
                    </div>

                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($function); ?>" id="function" name="function" readonly="true" />
                      <label for="function">Function</label>
                    </div>
                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($client); ?>" id="client" name="client" readonly="true" />
                      <label for="client">Client</label>
                    </div>

                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($process); ?>" id="process" name="process" readonly="true" />
                      <label for="process">Process</label>
                    </div>

                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($sub_pro); ?>" id="sub_pro" name="sub_pro" readonly="true" />
                      <label for="sub_pro">Sub Process</label>
                    </div>
                  </div>

                  <h4>Bank Details</h4>
                  <div class="col s12 m12">
                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($bank); ?>" id="bank" name="bank" readonly="true" />
                      <label for="bank">Bank Name</label>
                    </div>

                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($bank_account_no); ?>" id="bank_account_no" name="bank_account_no" readonly="true" />
                      <label for="bank_account_no">Bank Account Number</label>
                    </div>

                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($name_as_per_bank); ?>" id="name_as_per_bank" name="name_as_per_bank" readonly="true" />
                      <label for="name_as_per_bank">Name as per Bank</label>
                    </div>
                    <div class="input-field col s4 m4">
                      <input type="text" value="<?php echo ($IFSC); ?>" id="IFSC" name="IFSC" readonly="true" />
                      <label for="IFSC">IFSC Code</label>
                    </div>
                  </div>


                  <div class="col s12 m12">
                    <?php if ($value['ctc'] != null && $value['ctc'] != 0) {
                      echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="ctc" value="' . $value["ctc"] . '" name="ctc" readonly="true"/><label for="ctc" >CTC</label>
                  </div1>' .  '</div>';
                    } else {
                      echo '<div class="null"></div>';
                    } ?>


                    <?php if ($value['take_home'] != null && $value['take_home'] != 0) {
                      echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="take_home" value="' . $value["take_home"] . '" name="take_home" readonly="true"/><label for="take_home" >Take Home</label>
                  </div1>' .  '</div>';
                    } else {
                      echo '<div class="null"></div>';
                    } ?>


                    <?php if ($value['extra_pay_days'] != null && $value['extra_pay_days'] != 0) {
                      echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="extra_pay_days" value="' . $value["extra_pay_days"] . '" name="extra_pay_days" readonly="true"/><label for="extra_pay_days" >Extra Pay Days</label>
                  </div1>' .  '</div>';
                    } else {
                      echo '<div class="null"></div>';
                    } ?>


                    <?php if ($value['total_pay_days'] != null && $value['total_pay_days'] != 0) {
                      echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="total_pay_days" value="' . $value["total_pay_days"] . '" name="total_pay_days" readonly="true"/><label for="total_pay_days" >Total Pay Days</label>
                  </div1>' .  '</div>';
                    } else {
                      echo '<div class="null"></div>';
                    } ?>

                    <!-- <?php if ($value['net_salary'] != null && $value['net_salary'] != 0) {
                            echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="net_salary" value="' . $value["net_salary"] . '" name="net_salary" readonly="true"/><label for="net_salary" >Net Salary</label>
                  </div1>' .  '</div>';
                          } else {
                            echo '<div class="null"></div>';
                          } ?> -->

                    <?php if ($value['total_less'] != null && $value['total_less'] != 0) {
                      echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="deduction" value="' . $value["total_less"] . '" name="deduction" readonly="true"/><label for="deduction" >Deduction</label>
                  </div1>' .  '</div>';
                    } else {
                      echo '<div class="null"></div>';
                    } ?>

                    <?php if ($value['total_add'] != null && $value['total_add'] != 0) {
                      echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="total_add" value="' . $value["total_add"] . '" name="total_add" readonly="true"/><label for="total_add" >Addition</label>
                  </div1>' .  '</div>';
                    } else {
                      echo '<div class="null"></div>';
                    } ?>

                    <?php if ($value['net_paysalary'] != null && $value['net_paysalary'] != 0) {
                      echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="net_paysalary" value="' . $value["net_paysalary"] . '" name="net_paysalary" readonly="true"/><label for="net_paysalary" >Payable Salary</label>
                  </div1>' .  '</div>';
                    } else {
                      echo '<div class="null"></div>';
                    } ?>

                    <?php if ($value['trainig_stipend'] != null && $value['trainig_stipend'] != 0) {
                      echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="trainig_stipend" value="' . $value["trainig_stipend"] . '" name="trainig_stipend" readonly="true"/><label for="trainig_stipend" >Training Stipend</label>
                  </div1>' .  '</div>';
                    } else {
                      echo '<div class="null"></div>';
                    } ?>
                  </div>

                  <?php if ($value['total_add'] != null && $value['total_add'] != 0) { ?>
                    <h4>Addition Details</h4>
                    <div class="col s12 m12">
                      <?php if ($value['attendance_bonus'] != null && $value['attendance_bonus'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="attendance_bonus" value="' . $value["attendance_bonus"] . '" name="attendance_bonus" readonly="true"/><label for="attendance_bonus" >Attendance Bonus</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['split_incentive'] != null && $value['split_incentive'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="split_incentive" value="' . $value["split_incentive"] . '" name="split_incentive" readonly="true"/><label for="split_incentive" >Split Incentive</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['sales_allowance'] != null && $value['sales_allowance'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="sales_allowance" value="' . $value["sales_allowance"] . '" name="sales_allowance" readonly="true"/><label for="sales_allowance" >Sales Allowance</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['over_time'] != null && $value['over_time'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="over_time" value="' . $value["over_time"] . '" name="over_time" readonly="true"/><label for="over_time" >Over Time</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['night_allowance'] != null && $value['night_allowance'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="night_allowance" value="' . $value["night_allowance"] . '" name="night_allowance" readonly="true"/><label for="night_allowance" >Night Allowance</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['ref_Inct'] != null && $value['ref_Inct'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="ref_Inct" value="' . $value["ref_Inct"] . '" name="ref_Inct" readonly="true"/><label for="ref_Inct" >Reference Incentive</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>



                      <?php if ($value['PLI_payable'] != null && $value['PLI_payable'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="PLI_payable" value="' . $value["PLI_payable"] . '" name="PLI_payable" readonly="true"/><label for="PLI_payable" >PLI Payable</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['discrepancy_arrears'] != null && $value['discrepancy_arrears'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="discrepancy_arrears" value="' . $value["discrepancy_arrears"] . '" name="discrepancy_arrears" readonly="true"/><label for="Discrepancy Arrears" >Discrepancy Arrears</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['remarks'] == 0 && empty($value['remarks'])) {
                        echo '<div class="null"></div>';
                      } else {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
               <input type="text" id="remarks" value="' . $value['remarks'] . '" name="remarks" readonly="true"/><label for="remarks" >Remarks</label>
             </div1>' .  '</div>';
                      } ?>


                    </div>
                  <?php  } ?>

                  <?php if ($value['total_less'] != null && $value['total_less'] != 0) { ?>

                    <h4>Deduction Details</h4>
                    <div class="col s12 m12">
                      <?php if ($value['head_phone'] != null && $value['head_phone'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="head_phone" value="' . $value["head_phone"] . '" name="head_phone" readonly="true"/><label for="head_phone" >Head Phone</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['Id_card'] != null && $value['Id_card'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="Id_card" value="' . $value["Id_card"] . '" name="Id_card" readonly="true"/><label for="Id_card" >Id Card</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['insurance'] != null && $value['insurance'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="insurance" value="' . $value["insurance"] . '" name="insurance" readonly="true"/><label for="insurance" >Insurance</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['TDS'] != null && $value['TDS'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="TDS" value="' . $value["TDS"] . '" name="TDS" readonly="true"/><label for="TDS" >TDS</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['provisional_tax'] != null && $value['provisional_tax'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="provisional_tax" value="' . $value["provisional_tax"] . '" name="provisional_tax" readonly="true"/><label for="provisional_tax" >Provisional Tax</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['NP_recovery'] != null && $value['NP_recovery'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="NP_recovery" value="' . $value["NP_recovery"] . '" name="NP_recovery" readonly="true"/><label for="NP_recovery" >NP Recovery</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['ESIC'] != null && $value['ESIC'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="ESIC" value="' . $value["ESIC"] . '" name="ESIC" readonly="true"/><label for="ESIC" >ESIC</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['EPF'] != null && $value['EPF'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="EPF" value="' . $value["EPF"] . '" name="EPF" readonly="true"/><label for="EPF" >EPF</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>

                      <?php if ($value['PLI'] != null && $value['PLI'] != 0) {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="PLI" value="' . $value["PLI"] . '" name="PLI" readonly="true"/><label for="PLI" >PLI</label>
                  </div1>' .  '</div>';
                      } else {
                        echo '<div class="null"></div>';
                      } ?>


                      <?php if ($value['remark'] == 0 && empty($value['remark'])) {
                        echo '<div class="null"></div>';
                      } else {
                        echo '<div class="">' . '<div1 class="input-field col s4 m4">
               <input type="text" id="remark" value="' . $value['remark'] . '" name="remark" readonly="true"/><label for="remark" >Remarks</label>
             </div1>' .  '</div>';
                      } ?>


                      <!-- <?php if ($value['total_less'] != null && $value['total_less'] != 0) {
                              echo '<div class="">' . '<div1 class="input-field col s4 m4">
                   <input type="text" id="total_less" value="' . $value["total_less"] . '" name="total_less" readonly="true"/><label for="total_less" >Total Less</label>
                  </div1>' .  '</div>';
                            } else {
                              echo '<div class="null"></div>';
                            } ?> -->

                    </div>
          <?php  }
                } else {
                  echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
                }
              } else {
                echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
              }
            } else {
              echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
            }
          } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    $('#btn_ED_Search').click(function() {
      var validate = 0;
      var alert_msg = '';

      $('#ddl_ED_Emp_Name').removeClass('has-error');
      // $('#ddl_ED_Emp_EmpName').removeClass('has-error');
      if ($('#ddl_ED_Emp_Name').val() == '') {
        $('#ddl_ED_Emp_Name').addClass('has-error');
        if ($('#spanMessage_empid').size() == 0) {
          $('<span id="spanMessage_empid" class="help-block"></span>').insertAfter('#ddl_ED_Emp_Name');
        }
        $('#spanMessage_empid').html('Required *');
        validate = 1;
      }

      if ($('#month').val() == 'NA') {
        $('#month').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
        if ($('#spanmonths').size() == 0) {
          $('<span id="spanmonths" class="help-block">Required *</span>').insertAfter('#month');
        }
        validate = 1;
      }
      if ($('#Years').val() == 'NA') {
        $('#Years').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
        if ($('#spanYears').size() == 0) {
          $('<span id="spanYears" class="help-block">Required *</span>').insertAfter('#Years');
        }
        validate = 1;
      }

      if (validate == 1) {
        $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
        $('#alert_message').show().attr("class", "SlideInRight animated");
        $('#alert_message').delay(5000).fadeOut("slow");
        return false;
      }
    });
    $('#div_error').removeClass('hidden');
  });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>