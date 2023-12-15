<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

$batchID = clean($_REQUEST['batchID']);
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_REQUEST['batchID'])) {
    $sql = "select nc.client_name,location from batch_master bt join new_client_master nc on bt.cm_id=nc.cm_id where bt.BacthID=?";
    $sel1 = $conn->prepare($sql);
    $sel1->bind_param("i", $batchID);
    $sel1->execute();
    $result = $sel1->get_result();
    $res = $result->fetch_row();
    $clint_name = $res[0];
    $loc = $res[1];

    $userLOg = clean($_SESSION['__user_logid']);
    $sqlConnect = 'call get_thchecklist("' . $userLOg . '","' . $clint_name . '","' . $loc . '")';
    $myDB = new MysqliDb();
    $result = $myDB->query($sqlConnect);
    // echo $sqlConnect;
    // die;
    $error = $myDB->getLastError();
    if (count($result) > 0 && $result) { ?>

        <div class="flow-x-scroll">

            <table id="myTable" class="data dataTable no-footer" cellspacing="0">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="cbAll" name="cbAll" value="ALL">
                            <label for="cbAll">Employee ID</label>
                        </th>
                        <th class="hidden">Employee ID</th>
                        <th>Employee Name</th>
                        <th>Client</th>
                        <th>Process</th>
                        <th>Sub Process</th>
                        <th>DOJ</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 0;
                    foreach ($result as $key => $value) {
                        $count++;
                        echo '<tr>';
                        echo '<td class="EmployeeID"><input type="checkbox" id="cb' . $count . '" class="cb_child" name="cb[]" value="' . $value['EmployeeID'] . '"><label for="cb' . $count . '" style="color: #059977;font-size: 14px;font-weight: bold;}">' . $value['EmployeeID'] . '</label></td>';
                        echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
                        echo '<td class="FullName">' . $value['employeename'] . '</td>';
                        echo '<td class="client_name">' . $value['client_name'] . '</td>';
                        echo '<td class="process">' . $value['process'] . '</td>';
                        echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
                        echo '<td class="doj">' . $value['dateofjoin'] . '</td>';
                        echo '<td class="Remark no-padding input-field"><textarea name="txt_Remark_' . $value['EmployeeID'] . '" id="txt_Remark_' . $value['EmployeeID'] . '" style="min-width:300px;" class="materialize-textarea" placeholder="Remark for ' . $value['employeename'] . '"></textarea></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

        </div>

<?php
    } else {
        echo "<script>$(function(){ toastr.info('Congratulations have been aligned to concern departments.'); }); </script>";
    }
} ?>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            lengthMenu: [
                [-1],
                ['Show all']
            ],
            buttons: [],
            "sScrollY": "400",
            "sScrollX": "100%",
            "bPaginate": false,
            "bInfo": false,
        });
    });

    $("#cbAll").change(function() {
        $("input.cb_child:checkbox").prop('checked', $(this).prop("checked"));
        $('select').formSelect();
        $(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
            if ($(element).val().length > 0) {
                $(this).siblings('label, i').addClass('active');
            } else {
                $(this).siblings('label, i').removeClass('active');
            }

        });
    });
    $("input:checkbox").change(function() {
        $('#checkInfo_lbl').text($('input.cb_child:checkbox:checked').length);
    });
</script>