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

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

// Trigger Button-Save Click Event and Perform DB Action
if (!isset($_SESSION['__user_logid']) || empty($_SESSION['__user_logid'])) {
  $location = URL . 'Login';
  //header("Location: $location");
  echo "<script>location.href='" . $location . "'</script>";
  exit();
}

if ($_SESSION['__df_id'] == '74' || $_SESSION['__df_id'] == '77' || $_SESSION['__df_id'] == '146' || $_SESSION['__df_id'] == '147' || $_SESSION['__df_id'] == '148' || $_SESSION['__df_id'] == '149') {
  $location = URL;
  echo "<script>location.href='" . $location . "'</script>";
}

if (isset($_REQUEST['empid']) && $_REQUEST['empid'] != '') {
  $EmpID = clean($_REQUEST['empid']);
} else {
  $EmpID = clean($_SESSION['__user_logid']);
}
//$EmpID = clean($_SESSION['__user_logid']);
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

  .tdborder {
    border: 1px solid;
  }

  .tblcenter {
    text-align: center;
  }

  .tbBorderG {
    border: 1px solid #62D54A;
  }

  .tbBorderBG {
    border: 1px solid #62D54A;
    background-color: #C3E6BB !important;
  }

  .tbBorderB {
    border: 1px solid #97E8F4;
  }

  .tbBorderBRight {
    border: 1px solid #97E8F4;
    text-align: right;
  }

  .tbBorderBBold {
    border: 1px solid #97E8F4;
    font-weight: bold;
  }

  .tbBorderBBoldCenter {
    border: 1px solid #97E8F4;
    font-weight: bold;
    text-align: center;
  }

  .tbBorderBBoldRight {
    border: 1px solid #97E8F4;
    font-weight: bold;
    text-align: right;
  }

  .tbBorderBB {
    border: 1px solid #97E8F4;
    background-color: #D5F2F7 !important;
  }

  .fbold {
    font-weight: bold;
    font-size: 18px;
    ;
  }
</style>

<div id="content" class="content">
  <span id="PageTittle_span" class="hidden">Salary Structure </span>
  <div class="pim-container">
    <div class="form-div">
      <h4>Employee : <?php echo $_SESSION["__user_Name"] ?> ( <?php echo $_SESSION["__user_logid"] ?> )</h4>
      <div class="schema-form-section row">

        <script>
          $(function() {
            var makeDate = new Date(); // newly added
            makeDate = makeDate.setMonth(makeDate.getMonth() - 1); // newly added

            $('#txt_doc_name_1').datetimepicker({
              defaultDate: new Date(makeDate), // newly added
              timepicker: false,
              format: 'Y-m-d',
              maxDate: new Date('<?php echo date('Y-m-t', strtotime('-1 month')) ?>'),
              minDate: new Date('<?php echo date('Y-m-01', strtotime('-1 month')) ?>'),
              scrollInput: false
            });
            $('#txt_issueDate').datetimepicker({
              timepicker: false,
              format: 'Y-m-d',
              maxDate: '0',
              scrollInput: false
            });
            $('#myTable').DataTable({
              dom: 'Bfrtip',
              lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
              ],
              buttons: [{
                extend: 'excel',
                text: 'EXCEL',
                extension: '.xlsx',
                exportOptions: {
                  modifier: {
                    page: 'all'
                  }
                },
                title: 'table'
              }, 'pageLength'],
              "bProcessing": true,
              "bDestroy": true,
              "bAutoWidth": true,
              "sScrollY": '200px',
              "sScrollX": "100%",
              "bScrollCollapse": true,
              "bLengthChange": false,
              "fnDrawCallback": function() {

                $(".check_val_").prop('checked', $("#chkAll").prop('checked'));
              }
              // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
            });
            $('.buttons-copy').attr('id', 'buttons_copy');
            $('.buttons-csv').attr('id', 'buttons_csv');
            $('.buttons-excel').attr('id', 'buttons_excel');
            $('.buttons-pdf').attr('id', 'buttons_pdf');
            $('.buttons-print').attr('id', 'buttons_print');
            $('.buttons-page-length').attr('id', 'buttons_page_length');
          });
        </script>

        <div id="pnlTable">
          <?php
          $insert_row = 0;
          $userID = clean($_SESSION['__user_logid']);
          if (isset($_POST['btn_RaiseReq'])) {

            $myDB =  new MysqliDb();
            $conn = $myDB->dbConnect();

            $txt_doc_name = $_POST["txt_doc_name_"];

            $count = 1;
            foreach ($txt_doc_name as $key => $value) {

              $datefrom = clean(trim($value));
              $issue_type = clean(trim($_POST['txt_doc_type_' . $count]));
              $remarks = clean(trim($_POST['txt_doc_value_' . $count]));

              // $update = 'insert into salary_issue(EmpID, issue_date,issue_type,emp_remarks,issue_status)values(?,?,?,?,"Pending")';
              // $ins = $conn->prepare($update);

              // $ins->bind_param("ssss", $userID, $datefrom, $issue_type, $remarks);
              // $ins->execute();

              // $resu = $ins->get_result();
              // $count++;

              $sql_p_inert = "select EmpID from salary_issue where issue_date=? and issue_type=? ";
              $select = $conn->prepare($sql_p_inert);
              $select->bind_param("ss", $datefrom, $issue_type);
              $select->execute();
              $rst = $select->get_result();
              // $rst = $myDB->rawQuery($sql_p_inert);
              // $mysqlerror = $myDB->getLastError();

              if ($rst->num_rows < 1) {

                $emp_action = 'insert into salary_issue(EmpID, issue_date,issue_type,emp_remarks,issue_status)values(?,?,?,?,"Pending")';
                $upQ = $conn->prepare($emp_action);
                $upQ->bind_param("ssss", $userID, $datefrom, $issue_type, $remarks);
                $upQ->execute();
                $result = $upQ->get_result();

                if ($upQ->affected_rows === 1)
                  $insert_row = $insert_row + 1;
              }
            }

            echo "<script>$(function(){toastr.success('" . $insert_row . " Records Uploaded Sucessfully!')})</script>";


            // if ($ins->affected_rows === 1) {
            //   echo "<script>$(function(){toastr.success('Request raised successfully')})</script>";
            // } else {
            //   echo "<script>$(function(){toastr.error('Request not raised')})</script>";
            // }
          }

          if (isset($_POST['btn_Department_Edit'])) {

            $myDB =  new MysqliDb();
            $conn = $myDB->dbConnect();


            $ID = clean(trim($_POST['hid_ID']));
            $txt_issueDate = clean(trim($_POST['txt_issueDate']));
            $txt_issueType = clean(trim($_POST['txt_issueType']));
            $txt_Remarks = clean(trim($_POST['txt_Remarks']));

            $update = 'update salary_issue set issue_date=? ,issue_type=? , emp_remarks=? where id=?';
            $ins = $conn->prepare($update);

            $ins->bind_param("sssi", $txt_issueDate, $txt_issueType, $txt_Remarks, $ID);
            $ins->execute();
            if ($ins->affected_rows === 1) {
              echo "<script>$(function(){toastr.success('Request update successfully')})</script>";
            } else {
              echo "<script>$(function(){toastr.success('Request not update...')})</script>";
            }

            // if ($ins->affected_rows === 1) {
            //   echo "<script>$(function(){toastr.success('Request raised successfully')})</script>";
            // } else {
            //   echo "<script>$(function(){toastr.error('Request not raised')})</script>";
            // }
          }





          $DateTo = date('F Y', strtotime("last day of previous month"));
          $DateTo1 = date(strtotime("last day of previous month"));

          //$DateTo = date('F Y', strtotime("2023-01-31"));
          //$DateTo1 = date(strtotime("2023-01-31"));


          $month = date("n", strtotime($DateTo));
          $year = date("Y", strtotime($DateTo));
          $total_day = date("t", strtotime($DateTo));
          $sqlConnect = 'select * from salary_certificate where Empid=? and month=? and year=?';
          //$sqlConnect = 'select * from salary_certificate where Empid="' . $EmpID . '" and month="' . $month . '" and year="' . $year . '" ';
          // $myDB = new MysqliDb();
          // $result = $myDB->query($sqlConnect);

          $selectQ = $conn->prepare($sqlConnect);
          $selectQ->bind_param("sii", $EmpID, $month, $year);
          $selectQ->execute();
          $result = $selectQ->get_result();
          // echo ($sqlConnect);
          // die;
          if ($result->num_rows > 0) {
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
              if (strpos($bank_account_no, "AC:") !== false) {
                $bank_account_no = substr($bank_account_no, 3, strlen($bank_account_no));
              }

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
            <div class="schema-form-section row" style="margin-bottom: -43px !important;">
              <div id="myModal_content" class="modal">

                <!-- Modal content-->
                <div class="modal-content">
                  <h4 class="col s12 m12 model-h4">Salary detected day(s)</h4>

                  <div class="modal-body">
                    <div class="input-field col s12 m12" style="padding-left: 120;padding-right: 120;">
                      <?php if ($total_pay_days < $total_day) {
                        $sqlConnect = 'select D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, D12, D13, D14, D15, D16, D17, D18, D19, D20, D21, D22, D23, D24, D25, D26, D27, D28, D29, D30, D31 from calc_atnd_master where EmployeeID= ? and month= ? and year=? ';
                        //$sqlConnect = 'select * from salary_certificate where Empid="' . $EmpID . '" and month="' . $month . '" and year="' . $year . '" ';
                        // $myDB = new MysqliDb();
                        // $result = $myDB->query($sqlConnect);

                        $selectQ = $conn->prepare($sqlConnect);
                        $selectQ->bind_param("sii", $EmpID, $month, $year);
                        $selectQ->execute();
                        $result = $selectQ->get_result();

                        // echo ($sqlConnect);
                        // die;
                        if ($result->num_rows > 0) {
                          $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding-left: 120;padding-right: 120;">
                           <div class=""><table width="100%"><thead><tr>';
                          $result1 = $result->fetch_row();
                          //$table = '<table width="100%"><thead>';
                          $table .= '<th style="text-align: center;background-color:#ffc51e;" class="tbBorderB">Issue Date</th>';
                          $table .= '<th style="text-align: center;background-color:#ffc51e;" class="tbBorderB">Attendance</th></thead><tbody>';


                          for ($k = 0; $k <= 30; $k++) {

                            $kv = $k + 1;

                            if ($result1[$k] == 'A' || $result1[$k] == 'LWP' || $result1[$k] == 'HWP' || $result1[$k] == 'WONA' || $result1[$k] == 'LANA') {
                              $createdon = $kv . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                              $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                              $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[$k] . '</td></tr>';
                            }
                          }


                          /* if ($result1[0] == 'A' || $result1[0] == 'LWP' || $result1[0] == 'HWP' || $result1[0] == 'WONA' || $result1[0] == 'LANA') {
                            $createdon = "1" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[0] . '</td></tr>';
                          }
                          if ($result1[1] == 'A' || $result1[1] == 'LWP' || $result1[1] == 'HWP' || $result1[1] == 'WONA' || $result1[1] == 'LANA') {
                            $createdon = "2" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[1] . '</td></tr>';
                          }
                          if ($result1[2] == 'A' || $result1[2] == 'LWP' || $result1[2] == 'HWP' || $result1[2] == 'WONA' || $result1[2] == 'LANA') {
                            $createdon = "3" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[2] . '</td></tr>';
                          }
                          if ($result1[3] == 'A' || $result1[3] == 'LWP' || $result1[3] == 'HWP' || $result1[3] == 'WONA' || $result1[3] == 'LANA') {
                            $createdon = "4" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[3] . '</td></tr>';
                          }
                          if ($result1[4] == 'A' || $result1[4] == 'LWP' || $result1[4] == 'HWP' || $result1[4] == 'WONA' || $result1[4] == 'LANA') {
                            $createdon = "5" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[4] . '</td></tr>';
                          }
                          if ($result1[5] == 'A' || $result1[5] == 'LWP' || $result1[5] == 'HWP' || $result1[5] == 'WONA' || $result1[5] == 'LANA') {
                            $createdon = "6" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[5] . '</td></tr>';
                          }
                          if ($result1[6] == 'A' || $result1[6] == 'LWP' || $result1[6] == 'HWP' || $result1[6] == 'WONA' || $result1[6] == 'LANA') {
                            $createdon = "7" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[6] . '</td></tr>';
                          }
                          if ($result1[7] == 'A' || $result1[7] == 'LWP' || $result1[7] == 'HWP' || $result1[7] == 'WONA' || $result1[7] == 'LANA') {
                            $createdon = "8" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[7] . '</td></tr>';
                          }
                          if ($result1[8] == 'A' || $result1[8] == 'LWP' || $result1[8] == 'HWP' || $result1[8] == 'WONA' || $result1[8] == 'LANA') {
                            $createdon = "9" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[8] . '</td></tr>';
                          }
                          if ($result1[9] == 'A' || $result1[9] == 'LWP' || $result1[9] == 'HWP' || $result1[9] == 'WONA' || $result1[9] == 'LANA') {
                            $createdon = "10" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[9] . '</td></tr>';
                          }
                          if ($result1[10] == 'A' || $result1[10] == 'LWP' || $result1[10] == 'HWP' || $result1[10] == 'WONA' || $result1[10] == 'LANA') {
                            $createdon = "11" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[10] . '</td></tr>';
                          }
                          if ($result1[11] == 'A' || $result1[11] == 'LWP' || $result1[11] == 'HWP' || $result1[11] == 'WONA' || $result1[11] == 'LANA') {
                            $createdon = "12" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[11] . '</td></tr>';
                          }
                          if ($result1[12] == 'A' || $result1[12] == 'LWP' || $result1[12] == 'HWP' || $result1[12] == 'WONA' || $result1[12] == 'LANA') {
                            $createdon = "13" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[12] . '</td></tr>';
                          }
                          if ($result1[13] == 'A' || $result1[13] == 'LWP' || $result1[13] == 'HWP' || $result1[13] == 'WONA' || $result1[13] == 'LANA') {
                            $createdon = "14" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;" class="tbBorderB">' . $result1[13] . '</td></tr>';
                          }
                          if ($result1[14] == 'A' || $result1[14] == 'LWP' || $result1[14] == 'HWP' || $result1[14] == 'WONA' || $result1[14] == 'LANA') {
                            $createdon = "15" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;" class="tbBorderB">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[14] . '</td></tr>';
                          }
                          if ($result1[15] == 'A' || $result1[15] == 'LWP' || $result1[15] == 'HWP' || $result1[15] == 'WONA' || $result1[15] == 'LANA') {
                            $createdon = "16" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[15] . '</td></tr>';
                          }
                          if ($result1[16] == 'A' || $result1[16] == 'LWP' || $result1[16] == 'HWP' || $result1[16] == 'WONA' || $result1[16] == 'LANA') {
                            $createdon = "17" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[16] . '</td></tr>';
                          }
                          if ($result1[17] == 'A' || $result1[17] == 'LWP' || $result1[17] == 'HWP' || $result1[17] == 'WONA' || $result1[17] == 'LANA') {
                            $createdon = "18" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[17] . '</td></tr>';
                          }
                          if ($result1[18] == 'A' || $result1[18] == 'LWP' || $result1[18] == 'HWP' || $result1[18] == 'WONA' || $result1[18] == 'LANA') {
                            $createdon = "19" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[18] . '</td></tr>';
                          }
                          if ($result1[19] == 'A' || $result1[19] == 'LWP' || $result1[19] == 'HWP' || $result1[19] == 'WONA' || $result1[19] == 'LANA') {
                            $createdon = "20" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[19] . '</td></tr>';
                          }
                          if ($result1[20] == 'A' || $result1[20] == 'LWP' || $result1[20] == 'HWP' || $result1[20] == 'WONA' || $result1[20] == 'LANA') {
                            $createdon = "21" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[20] . '</td></tr>';
                          }
                          if ($result1[21] == 'A' || $result1[21] == 'LWP' || $result1[21] == 'HWP' || $result1[21] == 'WONA' || $result1[21] == 'LANA') {
                            $createdon = "22" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[21] . '</td></tr>';
                          }
                          if ($result1[22] == 'A' || $result1[22] == 'LWP' || $result1[22] == 'HWP' || $result1[22] == 'WONA' || $result1[22] == 'LANA') {
                            $createdon = "23" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[22] . '</td></tr>';
                          }
                          if ($result1[23] == 'A' || $result1[23] == 'LWP' || $result1[23] == 'HWP' || $result1[23] == 'WONA' || $result1[23] == 'LANA') {
                            $createdon = "24" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[23] . '</td></tr>';
                          }
                          if ($result1[24] == 'A' || $result1[24] == 'LWP' || $result1[24] == 'HWP' || $result1[24] == 'WONA' || $result1[24] == 'LANA') {
                            $createdon = "25" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[24] . '</td></tr>';
                          }
                          if ($result1[25] == 'A' || $result1[25] == 'LWP' || $result1[25] == 'HWP' || $result1[25] == 'WONA' || $result1[25] == 'LANA') {
                            $createdon = "26" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[25] . '</td></tr>';
                          }
                          if ($result1[26] == 'A' || $result1[26] == 'LWP' || $result1[26] == 'HWP' || $result1[26] == 'WONA' || $result1[26] == 'LANA') {
                            $createdon = "27" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[26] . '</td></tr>';
                          }
                          if ($result1[27] == 'A' || $result1[27] == 'LWP' || $result1[27] == 'HWP' || $result1[27] == 'WONA' || $result1[27] == 'LANA') {
                            $createdon = "28" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[27] . '</td></tr>';
                          }
                          if ($result1[28] == 'A' || $result1[28] == 'LWP' || $result1[28] == 'HWP' || $result1[28] == 'WONA' || $result1[28] == 'LANA') {
                            $createdon = "29" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[28] . '</td></tr>';
                          }
                          if ($result1[29] == 'A' || $result1[29] == 'LWP' || $result1[29] == 'HWP' || $result1[29] == 'WONA' || $result1[29] == 'LANA') {
                            $createdon = "30" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[29] . '</td></tr>';
                          }
                          if ($result1[30] == 'A' || $result1[30] == 'LWP' || $result1[30] == 'HWP' || $result1[30] == 'WONA' || $result1[30] == 'LANA') {
                            $createdon = "31" . " " . date(" M", strtotime(date('Y-m-d', $DateTo1))) . ' ' . date("Y", strtotime(date('Y-m-d', $DateTo1)));
                            $table .= '<tr><td style="text-align: center;">' . $createdon . '</td>';
                            $table .= '<td style="text-align: center;">' . $result1[30] . '</td></tr>';
                          }

                          */



                          $table .= '</tbody></table></div></div>';
                          echo $table;
                        }
                      ?>
                      <?php } ?>
                    </div>

                  </div>
                </div>
              </div>

              <div id="myModal_content_View" class="modal">

                <!-- Modal content-->
                <div class="modal-content">
                  <h4 class="col s12 m12 model-h4">Manage Department</h4>

                  <div class="modal-body">
                    <form method="POST">
                      <div class="input-field col s6 m6">
                        <input type="text" id="txt_issueDate" name="txt_issueDate" required />
                        <label for="txt_issueDate">Issue Date</label>
                      </div>
                      <div class="input-field col s6 m6">
                        <select name="txt_issueType" id="txt_issueType">
                          <option value="Attendance">Attendance</option>
                          <option value="ESIC">ESIC</option>
                          <option value="Insurance">Insurance</option>
                          <option value="PF">PF</option>
                          <option value="PLI">PLI</option>
                          <option value="Prof. Tax">Prof. Tax</option>
                          <option value="TDS">TDS</option>
                        </select>
                        <label class="active-drop-down active" for="txt_issueType">Issue Type</label>
                      </div>

                      <div class="input-field col s12 m12">
                        <textarea class="materialize-textarea" id="txt_Remarks" name="txt_Remarks"></textarea>
                        <label for="txt_Remarks">Comment</label>
                      </div>

                      <div class="input-field col s12 m12 right-align">
                        <input type="hidden" class="form-control hidden" id="hid_ID" name="hid_ID" />
                        <button type="submit" name="btn_Department_Edit" id="btn_Department_Edit" class="btn waves-effect waves-green">Save</button>
                        <button type="button" name="btn_Department_Can" id="btn_Department_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            </div>


            <h4 style="text-align: center;text-align: center;background: #19aec4;color: #FFFF;">Bank Details</h4>




            <div class="col s12 m12">
              <div class="input-field col s2 m2">

                <label>Bank Name :</label>
              </div>
              <div class="input-field col s4 m4">

                <label style="color: black;" class="fbold"><?php echo ($bank); ?></label>
              </div>



              <div class="input-field col s2 m2">

                <label>Name as per Bank :</label>
              </div>
              <div class="input-field col s4 m4">

                <label style="color: black;" class="fbold"><?php echo ($name_as_per_bank); ?></label>
              </div>
            </div>

            <div class="col s12 m12">
              <div class="input-field col s2 m2">

                <label>Bank Account Number:</label>
              </div>
              <div class="input-field col s4 m4">

                <label style="color: black;" class="fbold"><?php echo ($bank_account_no); ?></label>
              </div>



              <div class="input-field col s2 m2">

                <label>IFSC Code :</label>
              </div>
              <div class="input-field col s4 m4">

                <label style="color: black;" class="fbold"><?php echo ($IFSC); ?></label>
              </div>
            </div>

            <!-- <div class="col s12 m12">
              <h4 style="text-align: center;margin-top: 40px;background: #8cc63f;color: #FFFF;">Salary Details</h4>
            </div> -->


            <!-- <div class="col s12 m12">

              <div class="input-field col s4 m4">

                <label style="color: black;" class="fbold"><?php echo ($ctc); ?> INR</label>
                <br />
                <label style="margin-top: 27;font-size:12px;">Cost to Company (CTC)</label>
              </div>

              <div class="input-field col s4 m4">

                <label style="color: black;" class="fbold"><?php echo ($take_home); ?> INR</label>
                <br />
                <label style="margin-top: 27;font-size:12px;">Take Home (TH)</label>
              </div>


              <div class="input-field col s4 m4">

                <label style="color: black;" class="fbold"><?php echo '10-' . date("M") . '-' . date("Y"); ?></label>
                <br />
                <label style="margin-top: 27;font-size:12px;">Payroll Date</label>
              </div>

            </div> -->


            <div class="col s12 m12">
              <h4 style="text-align: center;margin-top: 40px;background: #ffc51e;">Payroll Details</h4>
            </div>
            <div class="col s12 m12">

              <table>

                <tr>
                  <td class="tbBorderBBold">Payroll Month</td>
                  <td class="tbBorderBBoldCenter"><?php echo date('M - Y', strtotime("last day of previous month")) ?></td>
                  <td class="tbBorderBBold">Deductions</td>
                  <td class="tbBorderBBold">Amount (INR)</td>
                  <td class="tbBorderBBold">Additions</td>
                  <td class="tbBorderBBold">Amount (INR)</td>
                  <td class="tbBorderBBold">Net Pay(INR)</td>
                </tr>

                <tr>

                  <td class="tbBorderBBold">Standard days</td>
                  <td class="tbBorderBBoldCenter"><?php echo date('t', date(strtotime("last day of previous month"))) ?></td>

                  <td class="tbBorderB">Insurance</td>
                  <td class="tbBorderBRight"><?php echo number_format($insurance) ?></td>
                  <td class="tbBorderB">Arrears</td>
                  <td class="tbBorderBRight"><?php echo number_format($discrepancy_arrears) ?></td>
                  <td rowspan="9" class="tdborder tblcenter tbBorderB fbold"><?php echo number_format($net_paysalary) ?></td>
                </tr>

                <tr>
                  <td class="tbBorderBBold">Total Pay Days </td>
                  <td class="tbBorderBBoldCenter"><span><?php echo $total_pay_days ?> days </span><?php if ($total_pay_days < $total_day) { ?>
                      <span><br /><br /><a href='#' onclick='return EditData()' style="color:#315C9D;text-decoration: underline;font-weight: bold;">Check Attendance</a>
                        <!-- <i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData();" data-position="left" data-tooltip="Edit">ohrm_edit</i> -->
                      </span>

                    <?php  } ?>
                  </td>
                  <td class="tbBorderB">Performance Link Incentive (PLI)</td>
                  <td class="tbBorderBRight"><?php echo number_format($PLI) ?></td>
                  <td class="tbBorderB">Training Stipend</td>
                  <td class="tbBorderBRight"><?php echo number_format($trainig_stipend) ?></td>

                </tr>

                <tr>
                  <td class="tbBorderBBold">Payroll Date</td>
                  <td class="tbBorderBBoldCenter"><?php echo '10-' . date("M") . '-' . date("Y"); ?></td>
                  <td class="tbBorderB">Tax Deducted at Source (TDS)</td>
                  <td class="tbBorderBRight"><?php echo number_format($TDS) ?></td>
                  <td class="tbBorderB">Attendance Bonus</td>
                  <td class="tbBorderBRight"><?php echo number_format($attendance_bonus) ?></td>
                </tr>

                <tr>
                  <td class="tbBorderBBold">CTC</td>
                  <td class="tbBorderBBoldCenter"><?php echo (number_format($ctc)); ?></td>
                  <td class="tbBorderB">Provident Fund (PF)</td>
                  <td class="tbBorderBRight"><?php echo number_format($EPF) ?></td>
                  <td class="tbBorderB">Split Incentive</td>
                  <td class="tbBorderBRight"><?php echo number_format($split_incentive) ?></td>
                </tr>

                <tr>
                  <td class="tbBorderBBold">Take Home</td>
                  <td class="tbBorderBBoldCenter"><?php echo (number_format($take_home)); ?></td>
                  <td class="tbBorderB">Employee State Insurance Scheme (ESIC)</td>
                  <td class="tbBorderBRight"><?php echo number_format($ESIC) ?></td>
                  <td class="tbBorderB">Sales Allowance</td>
                  <td class="tbBorderBRight"><?php echo number_format($sales_allowance) ?></td>
                </tr>

                <tr>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderB">Professional Tax</td>
                  <td class="tbBorderBRight"><?php echo number_format($provisional_tax) ?></td>
                  <td class="tbBorderB">Over Time</td>
                  <td class="tbBorderBRight"><?php echo number_format($over_time) ?></td>
                </tr>
                <tr>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderBRight"></td>
                  <td class="tbBorderB">Night Allowance</td>
                  <td class="tbBorderBRight"><?php echo number_format($night_allowance) ?></td>
                </tr>
                <tr>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderBRight"></td>
                  <td class="tbBorderB">Reference Incentive</td>
                  <td class="tbBorderBRight"><?php echo number_format($ref_Inct) ?></td>
                </tr>

                <tr>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderB"></td>
                  <td class="tbBorderB">Total Deductions</td>
                  <td class="tbBorderBRight"><?php echo number_format($total_less) ?></td>
                  <td class="tbBorderB">Total Additions</td>
                  <td class="tbBorderBRight"><?php echo number_format($total_add) ?></td>
                </tr>

                <tbody>

                </tbody>
              </table>



            </div>

            <h4>Raise Issue (Validated and approved salary corrections will be paid on 25<sup>th</sup> of the month)</h4>
            <form method="POST" action="">
              <div class="input-field col s12 m12" id="childtables">
                <input type="hidden" id="Document Details" name="doc_child" />
                <div class="form-inline addChildbutton " style="margin-bottom: 10px;">
                  <div class="form-group">
                    <button type="button" name="btn_docAdd" id="btn_docAdd" title="Add Row" class="btn waves-effect waves-green">
                      <i class="fa fa-plus"></i>Add</button>
                    <button type="button" name="btnDoccan" id="btnDoccan" title="Remove Row" class="btn waves-effect modal-action modal-close waves-red close-btn">
                      <i class="fa fa-minus"></i>Remove</button>
                  </div>
                </div>
                <table class="table table-hovered table-bordered" id="childtable">

                  <tbody>
                    <tr class="trdoc" id="trdoc_1">
                      <td class="doccount hidden">1</td>
                      <td>
                        <input type="text" class="form-control" name="txt_doc_name_[]" id="txt_doc_name_1" placeholder="Issue Date" />
                        <!-- <input name="txt_doc_name_[]" type="file" id="txt_doc_name_1" class="form-control clsInput file_input" placeholder="Issue Date" /> -->
                      </td>
                      <td>
                        <select name="txt_doc_type_1" id="txt_doc_type_1">
                          <option Value="NA">---Select---</option>
                          <option value="Attendance">Attendance</option>
                          <option value="ESIC">ESIC</option>
                          <option value="Insurance">Insurance</option>
                          <option value="PF">PF</option>
                          <option value="PLI">PLI</option>
                          <option value="Prof. Tax">Prof. Tax</option>
                          <option value="TDS">TDS</option>
                        </select>
                      </td>

                      <td>
                        <input type="text" value="" name="txt_doc_value_1" id="txt_doc_value_1" maxlength="250" minlength="25" placeholder="Remarks" />
                      </td>
                    </tr>

                  </tbody>
                </table>
              </div>

              <div class="input-field col s12 m12 16 right-align">
                <button type="submit" name="btn_RaiseReq" id="btn_RaiseReq" class="btn waves-effect waves-green">Raise Request</button>

              </div>

            </form>
          <?php
          } else {
            echo "<script>$(function(){ toastr.error('No Data Found '); }); </script>";
          }

          $sqlConnect = "select * from salary_issue where EmpID= ? order by issue_date";
          $myDB = new MysqliDb();
          $conn = $myDB->dbConnect();
          $selectQ = $conn->prepare($sqlConnect);
          $selectQ->bind_param("s", $userID);
          $selectQ->execute();
          $result = $selectQ->get_result();
          // $result = $myDB->query($sqlConnect);
          // echo ($sqlConnect);
          // die;
          // $my_error = $myDB->getLastError();
          if ($result->num_rows > 0) {
            $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
            $table .= '<th>Manage</th>';
            $table .= '<th hidden>ID</th>';
            $table .= '<th>EmployeeID</th>';
            $table .= '<th>Issue Date</th>';
            $table .= '<th>Issue Type</th>';
            $table .= '<th>Remarks</th>';
            $table .= '<th>Status</th>';
            $table .= '<th>Validator Remarks</th>';
            $table .= '<th>CreatedOn</th><thead><tbody>';

            foreach ($result as $key => $value) {
              $table .= '<tr>';
              if ($value['issue_status'] == 'Pending') {
                $table .= '<td class="manage_item"> <i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData_show(this);" id="' . $value['id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> <i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" onclick="javascript:return DeleteReq(this);" id="' . $value['id'] . '"   data-position="left" data-tooltip="Delete">ohrm_delete</i></td>';
              } else {
                $table .= '<td> </td>';
              }


              $table .= '<td class="id hidden">' . $value['id'] . '</td>';
              $table .= '<td class="EmpID">' . $value['EmpID'] . '</td>';
              $table .= '<td class="issue_date">' . $value['issue_date'] . '</td>';
              $table .= '<td class="issue_type">' . $value['issue_type'] . '</td>';
              $table .= '<td class="emp_remarks">' . $value['emp_remarks'] . '</td>';
              $table .= '<td>' . $value['issue_status'] . '</td>';
              $table .= '<td>' . $value['approver_remarks'] . '</td>';
              $table .= '<td>' . $value['createdon'] . '</td></tr>';
            }
            $table .= '</tbody></table></div></div>';
            echo $table;
          }

          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    //var date = new Date();
    //var firstDay = new Date(date.getFullYear(), date.getMonth() - 1, 1);
    //var lastDay = new Date(date.getFullYear(), date.getMonth(), 0);
    //alert(firstDay);
    //alert(lastDay);
    $('.modal').modal({
      onOpenStart: function(elm) {


      },
      onCloseEnd: function(elm) {
        $('#btn_Department_Can').trigger("click");
      }
    });

    $('#btn_docAdd').click(function() {
      var makeDate = new Date(); // newly added
      makeDate = makeDate.setMonth(makeDate.getMonth() - 1); // newly added

      $count = $(".trdoc").length;
      $id = "trdoc_" + parseInt($count + 1);
      $('#doc_child').val(parseInt($count + 1));
      $tr = $("#trdoc_1").clone().attr("id", $id);
      $('#childtable tbody').append($tr);
      $tr.children("td:first-child").html(parseInt($count + 1));
      $tr.children("td:nth-child(2)").children("input").attr({
        "id": "txt_doc_name_" + parseInt($count + 1),
        "name": "txt_doc_name_[]"
      }).datetimepicker({
        defaultDate: new Date(makeDate), // newly added
        format: 'Y-m-d',
        timepicker: false,
        maxDate: new Date('<?php echo date('Y-m-t', strtotime('-1 month')) ?>'),
        minDate: new Date('<?php echo date('Y-m-01', strtotime('-1 month')) ?>'),
        scrollInput: false
      }).val('');

      $trSelect_n3 = $tr.children("td:nth-child(3)").find("select").clone().attr({
        "id": "txt_doc_type_" + parseInt($count + 1),
        "name": "txt_doc_type_" + parseInt($count + 1)
      }).val('NA');

      $tr.children("td:nth-child(3)").html('').append($trSelect_n3);
      $(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
        if ($(element).val().length > 0) {
          $(this).siblings('label, i').addClass('active');
        } else {
          $(this).siblings('label, i').removeClass('active');
        }
      });
      $('select').formSelect();


      $tr.children("td:nth-child(4)").children("input").attr({
        "id": "txt_doc_value_" + parseInt($count + 1),
        "name": "txt_doc_value_" + parseInt($count + 1),
        "minlength": "25"
      }).val('');

    });
    $('#btnDoccan').click(function() {
      $count = $(".trdoc").length;
      if ($count > 1) {
        $('#childtable tbody').children("tr:last-child").remove();
        $('#doc_child').val(parseInt($count - 1));
      }

    });

    $('#btn_RaiseReq').click(function() {
      var rowlen = ($('.trdoc').length);
      for (i = 1; i <= rowlen; i++) {
        if ($('#txt_doc_name_' + i).val().trim() == '') {
          $(function() {
            toastr.error('Select date in ' + i + ' row')
          });
          return false;
          break;
        }
        if ($('#txt_doc_type_' + i).val() == 'NA') {
          $(function() {
            toastr.error('Select issue type in ' + i + ' row')
          });
          return false;
          break;
        }
        if ($('#txt_doc_value_' + i).val() == '') {
          $(function() {
            toastr.error('Remarks empty in ' + i + ' row')
          });
          return false;
          break;
        }
      }


    });

    $('#btn_Department_Can').on('click', function() {
      $('#txt_issueDate').val('');
      $('#hid_ID').val('');
      $('#txt_Remarks').val('');

      // This code for remove error span from input text on model close and cancel
      $(".has-error").each(function() {
        if ($(this).hasClass("has-error")) {
          $(this).removeClass("has-error");
          $(this).next("span.help-block").remove();
          if ($(this).is('select')) {
            $(this).parent('.select-wrapper').find("span.help-block").remove();
          }
          if ($(this).hasClass('select-dropdown')) {
            $(this).parent('.select-wrapper').find("span.help-block").remove();
          }

        }
      });
      // This code active label on value assign when any event trigger and value assign by javascript code.
      $("#myModal_content input,#myModal_content textarea").each(function(index, element) {

        if ($(element).val().length > 0) {
          $(this).siblings('label, i').addClass('active');
        } else {
          $(this).siblings('label, i').removeClass('active');
        }

      });
    });

    $('#btn_Department_Edit').on('click', function() {
      var validate = 0;
      var alert_msg = '';

      $('#txt_issueDate').removeClass('has-error');
      $('#txt_Remarks').removeClass('has-error');

      if ($('#txt_issueDate').val() == '') {
        $('#txt_issueDate').addClass('has-error');
        validate = 1;
        if ($('#stxt_issueDate').size() == 0) {
          $('<span id="stxt_issueDate" class="help-block">Select Date First</span>').insertAfter('#txt_issueDate');
        }
      }

      if ($('#txt_Remarks').val() == '') {
        $('#txt_Remarks').addClass('has-error');
        validate = 1;
        if ($('#stxt_Remarks').size() == 0) {
          $('<span id="stxt_Remarks" class="help-block">Remarks not be empty</span>').insertAfter('#txt_Remarks');
        }
      }


      if (validate == 1) {

        return false;
      }
    });

    $('#div_error').removeClass('hidden');
  });

  function EditData() {

    $('#myModal_content').modal('open');

  }

  function EditData_show(el) {
    var tr = $(el).closest('tr');
    var id = tr.find('.id').text();
    var issue_date = tr.find('.issue_date').text();
    var issue_type = tr.find('.issue_type').text();
    var emp_remarks = tr.find('.emp_remarks').text();
    $('#hid_ID').val(id);
    $('#txt_issueDate').val(issue_date);
    $('#txt_issueType').val(issue_type);
    $('#txt_Remarks').val(emp_remarks);
    //alert(issue_type);
    $('#myModal_content_View').modal('open');
    $("#myModal_content_View input,#myModal_content_View textarea,#myModal_content_View select").each(function(index, element) {
      if ($(element).val().length > 0) {
        $(this).siblings('label, i').addClass('active');
      } else {
        $(this).siblings('label, i').removeClass('active');
      }

    });
    $('select').formSelect();
  }

  function DeleteReq(el) {
    if (confirm("Do you Want to Delete Request")) {
      $item = $(el);
      $.ajax({
        url: "../Controller/deleteRequest_Atnd.php?ID=" + $item.attr("id"),
        success: function(result) {
          var data = result.split('|');

          toastr.info(data[1]);
          if (data[0] == 'Done') {

            $item.closest('td').parent('tr').remove();
          }
        }
      });
    }


  }
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>