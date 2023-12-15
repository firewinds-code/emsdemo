<?php
// Server Config file

use LDAP\Result;

require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$EmployeeID = clean($_SESSION['__user_logid']);
$empName = clean($_SESSION['__user_Name']);

$sqlfood = "select * from expense_food ";
$resultfood = $myDB->query($sqlfood);
$foodimg = $resultfood[0]['receipt_image'];

$sqltravel = 'SELECT * FROM expense_travel;';
$resulttravel = $myDB->rawQuery($sqltravel);
$modeOftravel = $resulttravel[0]['modeOftravel'];
$travelimg = $resulttravel[0]['receipt_image'];


$sqlhotel = 'SELECT * FROM expense_hotel;';
$resulthotel = $myDB->rawQuery($sqlhotel);
$hotelimg = $resulthotel[0]['receipt_image'];

$sqlmiscellaneous = 'SELECT * FROM expense_miscellaneous;';
$resultmiscellaneous = $myDB->rawQuery($sqlmiscellaneous);



// START FOOD UPDATE
$btn_edit_expenseFood = isset($_POST['btn_edit_expenseFood']);
if ($btn_edit_expenseFood) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $foodid = $_POST['hid_expense_foodID'];

        // $update_food = 'UPDATE expense_food SET reviewerStatus="' . $_POST['reviewerStatus'] . '",reviewComment="' . $_POST['reviewComment'] . '",modified_at=now() WHERE id="' . $foodid . '"';
        $rstatus = cleanUserInput($_POST['reviewerStatus']);
        $rcomment = cleanUserInput($_POST['reviewComment']);
        $update_food = 'UPDATE expense_food SET reviewerStatus=?,reviewComment=?,modified_at=now() WHERE id=?';
        $stmt = $conn->prepare($update_food);
        $stmt->bind_param("sss", $rstatus, $rcomment, $foodid);
        $stmt->execute();
        $resStmt = $stmt->get_result();
        // print_r($resStmt);
        // die;
        // $myDB->query($update_food);
        // $mysql_error = $myDB->getLastError();
        if ($stmt->affected_rows === 1) {
            echo "<script>$(function(){toastr.success('Record Updated Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
        }
    }
}
// END FOOD UPDATE


// START TRAVEL UPDATE
$btn_edit_expenseTravel = isset($_POST['btn_edit_expenseTravel']);
if ($btn_edit_expenseTravel) //for food submit
{
    if (isset($_POST["token1"]) && isset($_SESSION["token1"]) && $_POST["token1"] == $_SESSION["token1"]) {
        $travelid = cleanUserInput($_POST['hid_expense_travelID']);
        // $update_food = 'UPDATE expense_travel SET reviewerStatus="' . $_POST['reviewerStatus'] . '",reviewComment="' . $_POST['reviewComment'] . '",modified_at=now() WHERE id="' . $travelid . '"';
        $rstatus = cleanUserInput($_POST['reviewerStatus']);
        $rcomment = cleanUserInput($_POST['reviewComment']);
        $update_travel = 'UPDATE expense_travel SET reviewerStatus=?,reviewComment=?,modified_at=now() WHERE id=?';
        $stmt = $conn->prepare($update_travel);
        $stmt->bind_param("sss", $rstatus, $rcomment, $travelid);
        $stmt->execute();
        $resStmt = $stmt->get_result();
        // print_r($resStmt);
        // die;
        if ($stmt->affected_rows === 1) {
            echo "<script>$(function(){toastr.success('Record Updated Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
        }
    }
}
// END TRAVEL UPDATE


// START HOTEL UPDATE
$btn_edit_expenseHotel = isset($_POST['btn_edit_expenseHotel']);
if ($btn_edit_expenseHotel) //for food submit
{
    if (isset($_POST["token2"]) && isset($_SESSION["token2"]) && $_POST["token2"] == $_SESSION["token2"]) {
        $hotelid = cleanUserInput($_POST['hid_expense_hotelID']);
        // $update_hotel = 'UPDATE expense_hotel SET reviewerStatus="' . $_POST['reviewerStatus'] . '",reviewComment="' . $_POST['reviewComment'] . '",modified_at=now() WHERE id="' . $hotelid . '"';
        $rstatus = cleanUserInput($_POST['reviewerStatus']);
        $rcomment = cleanUserInput($_POST['reviewComment']);
        $update_hotel = 'UPDATE expense_hotel SET reviewerStatus=?,reviewComment=?,modified_at=now() WHERE id=?';
        $stmt = $conn->prepare($update_hotel);
        $stmt->bind_param("sss", $rstatus, $rcomment, $hotelid);
        $stmt->execute();
        $resStmt = $stmt->get_result();
        // print_r($resStmt);
        // die;
        if ($stmt->affected_rows === 1) {
            echo "<script>$(function(){toastr.success('Record Updated Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
        }
    }
}
// END HOTEL UPDATE


// START MISCELLANEOUS UPDATE
$btn_edit_expenseMiscellaneous = isset($_POST['btn_edit_expenseMiscellaneous']);
if ($btn_edit_expenseMiscellaneous) //for food submit
{
    if (isset($_POST["token3"]) && isset($_SESSION["token3"]) && $_POST["token3"] == $_SESSION["token3"]) {
        $miscellaneousID = cleanUserInput($_POST['hid_expense_miscellaneousID']);
        // $update_food = 'UPDATE expense_miscellaneous SET reviewerStatus="' . $_POST['reviewerStatus'] . '",reviewComment="' . $_POST['reviewComment'] . '",modified_at=now() WHERE id="' . $miscellaneousID . '"';
        $rstatus = cleanUserInput($_POST['reviewerStatus']);
        $rcomment = cleanUserInput($_POST['reviewComment']);
        $update_misc = 'UPDATE expense_miscellaneous SET reviewerStatus=?,reviewComment=?,modified_at=now() WHERE id=?';
        $stmt = $conn->prepare($update_misc);
        $stmt->bind_param("sss", $rstatus, $rcomment, $miscellaneousID);
        $stmt->execute();
        $resStmt = $stmt->get_result();
        // print_r($resStmt);
        // die;
        if ($stmt->affected_rows === 1) {
            echo "<script>$(function(){toastr.success('Record Updated Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
        }
    }
}
// END MISCELLANEOUS UPDATE
?>

<style>
    .error {
        color: red;
    }

    #data-container {
        display: block;
        background: #2a3f54;

        max-height: 250px;
        overflow-y: auto;
        z-index: 9999999;
        position: absolute;
        width: 100%;

    }

    #data-container li {
        list-style: none;
        padding: 5px;
        border-bottom: 1px solid #fff;
        color: #fff;
    }

    #data-container li:hover {
        background: #26b99a;
        cursor: pointer;
    }

    .form-control:focus {
        border-color: #d01010;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(233, 102, 139, 0.6);

    }

    #overlay {
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height: 100%;
        display: none;
        background: rgba(0, 0, 0, 0.6);
    }

    .cv-spinner {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px #ddd solid;
        border-top: 4px #2e93e6 solid;
        border-radius: 50%;
        animation: sp-anime 0.8s infinite linear;
    }

    @keyframes sp-anime {
        100% {
            transform: rotate(360deg);
        }
    }

    .is-hide {
        display: none;
    }
</style>

<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">Reimbursement Reviewer</span>
    <div class="pim-container">

        <div class="form-div">
            <h4>Reimbursement</h4>
            <div class="schema-form-section row">
                <div class="input-field col s12 m12">
                    <div class="col s12 m12">

                        <div class="input-field col s12 m12 l12">
                            <select id="expenses1" name="expenses1">
                                <option value="NA">__Select__</option>
                                <option value="Food Expenses">Food </option>
                                <option value="Travel Expenses">Travel </option>
                                <option value="Hotel Expenses">Hotel </option>
                                <option value="Miscellaneous Expenses">Miscellaneous </option>
                            </select>
                            <label for="expenses1" class="active-drop-down active">Reimbursement</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>



        <!-- START FOOD DATA TABLE -->
        <div class="form-div" id="divfood">
            <h4>Food Report</h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">

                <!-- START FOOD MODEL -->
                <div id="myModal_content" class="modal">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <h4 class="col s12 m12 model-h4">Food Expense</h4>
                        <div class="modal-body">
                            <form method="POST">
                                <?php

                                $_SESSION["token"] = csrfToken();
                                ?>
                                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
                                <input type="hidden" class="form-control hidden" id="hid_expense_foodID" name="hid_expense_foodID" />
                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="food_date" id="food_date" readonly="true">
                                    <label class="Active" for="food_date">Date</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="amount" id="food_amount" readonly>
                                    <label class="Active" for="amount">Amount</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="receipt_no" id="food_receipt_no" readonly>
                                    <label class="Active" for="receipt_no">Receipt No</label>
                                </div>

                                <div class="input-field col s4 m4 l4" id="divfoodImg">
                                    <!-- <input type="text" name="receipt_image" id="food_receipt_image" readonly> -->
                                    <span class="Active">Receipt Image</span><br>
                                    <!-- <a id="food_receipt_image" href="../ExpenseFood/<?php echo $resultfood[0]['receipt_image']; ?>" target="_blank" download><?php echo $resultfood[0]['receipt_image']; ?></a> -->
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <input type="text" name="remarks" id="food_remarks" readonly>
                                    <label class="Active" for="remarks">Remarks</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select id="reviewerStatus" name="reviewerStatus">
                                        <option value="NA">Select</option>
                                        <!-- <option value="Pending">Pending</option> -->
                                        <option value="Approve">Approve</option>
                                        <option value="Decline">Decline</option>
                                    </select>
                                    <label for="reviewerStatus" class="dropdown-active active">Reviewer:</label>
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <!-- <input type="text" name="reviewComment" id="reviewComment"> -->
                                    <textarea class="materialize-textarea" id="reviewComment" name="reviewComment" maxlength="200"></textarea>
                                    <label for="reviewComment">Reviewer Comment</label>
                                </div>

                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_edit_expenseFood" id="btn_edit_expenseFood" class="btn waves-effect waves-green ">Save</button>

                                    <button type="button" name="btn_can_expenseFood" id="btn_can_expenseFood" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END FOOD MODEL -->

                <div id="pnlTable">
                    <?php
                    // $sqlConnect = 'SELECT t1.* FROM ems.expense_food t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID="' . $_SESSION['__user_logid'] . '" and is_reviewer="Yes"';
                    $sqlConnect = 'SELECT t1.* FROM ems.expense_food t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID=? and is_reviewer="Yes"';
                    $select = $conn->prepare($sqlConnect);
                    $select->bind_param("s", $EmployeeID);
                    $select->execute();
                    $result = $select->get_result();
                    if ($result->num_rows > 0) { ?>
                        <table id="foodTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <!-- <th>Sr No</th> -->
                                    <th>EmployeeID</th>
                                    <th>Employee Name</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Receipt No</th>
                                    <th>Receipt Image</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th class="hidden">ID</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($result as $key => $value) {
                                    if ($value['reviewerStatus'] != 'Approve' && $value['reviewerStatus'] != 'Decline') { ?>
                                        <tr>
                                            <td>
                                                <i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData_food(this);" id="<?php echo $value['id'] ?>" data-position="left" data-tooltip="Edit">ohrm_edit</i>
                                            </td>
                                            <!-- <td><?php //echo $i++; 
                                                        ?></td> -->
                                            <td class="EmployeeID"><?php echo $value['EmployeeID']; ?></td>
                                            <td class="empName"><?php echo $value['empName']; ?></td>
                                            <td class="food_date"><?php echo $value['date']; ?></td>
                                            <td class="foodamount"><?php echo $value['amount']; ?></td>
                                            <td class="foodreceipt_no"><?php echo $value['receipt_no']; ?></td>
                                            <td class="foodreceipt_image"><?php echo $value['receipt_image']; ?></td>
                                            <td class="foodremarks"><?php echo $value['remarks']; ?></td>
                                            <td class="req_status"><?php echo $value['req_status']; ?></td>
                                            <td class="req_status"><?php echo $value['created_at']; ?></td>

                                            <td class="foodID hidden"><?php echo $value['id']; ?></td>

                                        <?php } ?>

                                        </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>

            </div>
        </div>
        <!-- END FOOD DATA TABLE -->


        <!-- START TRAVEL DATA TABLE -->
        <div class="form-div" id="divTravel">
            <h4>Travel Report</h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">
                <!-- START TRAVEL MODEL -->
                <div id="myModal_content2" class="modal">

                    <div class="modal-content">
                        <h4 class="col s12 m12 model-h4">Travel Expense</h4>
                        <div class="modal-body">
                            <form method="POST">
                                <?php
                                $_SESSION["token1"] = csrfToken();
                                ?>
                                <input type="hidden" name="token1" value="<?= $_SESSION["token1"] ?>">
                                <input type="hidden" class="form-control hidden" id="hid_expense_travelID" name="hid_expense_travelID" />

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="date" id="travel_date" readonly>
                                    <label class="Active" for="travel_date">Date</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="placeFrom" id="placeFrom" readonly>
                                    <label class="Active" for="placeFrom">Place from</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="placeTO" id="placeTO" readonly>
                                    <label class="Active" for="placeTO">Place To</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="modeOftravel" id="modeOftravel" readonly>
                                    <label class="Active" for="modeOftravel">Mode Of Travel</label>
                                </div>

                                <div class="input-field col s4 m4 l4" id="retDate">
                                    <input type="text" name="returnDate" id="returnDate" readonly>
                                    <label class="Active" for="returnDate">Return Date</label>
                                </div>


                                <div class="input-field col s3 m3 l3" id="kiloMeter">
                                    <input type="text" name="car_km" id="car_km" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                                    <label class="Active" for="kilometer">Kilometer</label>
                                </div>

                                <!-- <div class="input-field col s4 m4 l4" id="kmAmount">
                                    <input type="text" name="km_amount" id="km_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                                    <label class="Active" for="km_amount">KM Amount</label>
                                </div> -->

                                <div class="input-field col s4 m4 l4 travelCarRcpt" id="carReceipt">
                                    <!-- <input type="text" name="car_km_receipt" id="travel_car_km_receipt">
                                    <span>KM_Receipt</span> -->
                                </div>

                                <div class="input-field col s6 m6 l6 travelParkRcpt" id="parkReceipt">
                                    <!-- <input type="text" name="car_parking_receipt" id="travel_car_parking_receipt">
                                    <span>Parking_Receipt</span> -->
                                </div>

                                <div class="input-field col s6 m6 l6 travelTollRcpt" id="tollReceipt">
                                    <!-- <input type="text" name="car_toll_receipt" id="travel_car_toll_receipt">
                                    <span>Toll_Receipt</span> -->
                                </div>

                                <!-- <div class="input-field col s4 m4 l4" id="parkAmount">
                                    <input type="text" name="parking_amount" id="parking_amount" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                                    <label for="parking_amount">Parking Amount</label>
                                </div>

                                <div class="input-field col s4 m4 l4" id="tollAmount">
                                    <input type="text" name="toll_amount" id="toll_amount" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                                    <label for="toll_amount">Toll_Amount</label>
                                </div> -->


                                <div class="input-field col s4 m4 l4" id="">
                                    <input type="text" name="amount" id="travel_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                                    <label id="lableAmount" class="Active" for="amount">Amount</label>
                                </div>

                                <!-- <div class="input-field col s4 m4 l4">
                                    <input type="text" name="amount" id="travel_amount" readonly>
                                    <label class="Active" for="amount">Amount</label>
                                </div> -->

                                <!-- <input type="hidden" name="amount" id="actAmount" value="<?php //echo $_POST['amount'] 
                                                                                                ?>"> -->

                                <div class="input-field col s4 m4 l4" id="receiptNoDIV">
                                    <input type="text" name="receipt_no" id="travel_receipt_no" readonly>
                                    <label class="Active" for="receipt_no">Receipt No</label>
                                </div>

                                <div class="input-field col s4 m4 l4 receiptImageDIV" id="divtravelImg">
                                    <!-- <input type="text" name="receipt_image" id="travel_receipt_image" readonly> -->
                                    <!-- <span class="Active">Receipt Image</span><br> -->
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <input type="text" name="remarks" id="travel_remarks" readonly>
                                    <label class="Active" for="remarks">Remarks</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select id="travel_reviewerStatus" name="reviewerStatus">
                                        <option value="NA">Select</option>
                                        <!-- <option value="Pending">Pending</option> -->
                                        <option value="Approve">Approve</option>
                                        <option value="Decline">Decline</option>
                                    </select>
                                    <label for="reviewerStatus" class="dropdown-active active">Reviewer:</label>
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <!-- <input type="text" name="reviewComment" id="travel_reviewComment"> -->
                                    <textarea class="materialize-textarea" id="travel_reviewComment" name="reviewComment" maxlength="200"></textarea>
                                    <label for="reviewComment">Reviewer Comment</label>
                                </div>


                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_edit_expenseTravel" id="btn_edit_expenseTravel" class="btn waves-effect waves-green ">Save</button>
                                    <button type="button" name="btn_can_expensetravel" id="btn_can_expensetravel" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END TRAVEL MODEL -->

                <div id="pnlTable">
                    <?php
                    // $sqlConnect = 'SELECT t1.* FROM ems.expense_travel t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID="' . $_SESSION['__user_logid'] . '" and is_reviewer="Yes"  and reviewerStatus="Pending"';
                    $sqlConnect = 'SELECT t1.* FROM ems.expense_travel t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID=? and is_reviewer="Yes"  and reviewerStatus="Pending"';
                    $select = $conn->prepare($sqlConnect);
                    $select->bind_param("s", $EmployeeID);
                    $select->execute();
                    $result = $select->get_result();
                    if ($result->num_rows > 0) {
                    ?>
                        <table id="travelTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <!-- <th>Sr No</th> -->
                                    <th class="hidden">ID</th>
                                    <th>Action</th>
                                    <th>EmployeeID</th>
                                    <th>Employee Name</th>
                                    <th>Date</th>
                                    <th>Place From</th>
                                    <th>Place To</th>
                                    <th>Mode Of Travel</th>
                                    <th>returnDate</th>
                                    <th>car_km</th>
                                    <th>car_km_receipt</th>
                                    <th>car_parking_receipt</th>
                                    <th>car_toll_receipt</th>
                                    <th>Amount</th>
                                    <th>Receipt No</th>
                                    <th>Receipt Image</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($result as $key => $value) {
                                    // echo "<pre>";
                                    // print_r($value);
                                    // die;
                                    if ($value['reviewerStatus'] != 'Approve' && $value['reviewerStatus'] != 'Decline') { ?>

                                        <tr>
                                            <!-- <td><?php //echo $i++; 
                                                        ?></td> -->
                                            <td class="travelID hidden"><?php echo $value['id']; ?></td>
                                            <td>
                                                <i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="EditData_travel(this);" data-position="left" data-tooltip="Edit">ohrm_edit</i>
                                            </td>
                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['empName']; ?></td>
                                            <td class="traveldate"><?php echo $value['date']; ?></td>
                                            <td class="travelplacefrom"><?php echo $value['placeFrom']; ?></td>
                                            <td class="travelplaceTO"><?php echo $value['placeTO']; ?></td>
                                            <td class="travelmodeOftravel"><?php echo $value['modeOftravel']; ?> </td>

                                            <td class="travelreturnDate"><?php echo $value['returnDate']; ?> </td>
                                            <td class="travelcar_km"><?php echo $value['car_km']; ?> </td>
                                            <td class="travelcar_km_receipt"><?php echo $value['car_km_receipt']; ?> </td>
                                            <td class="travelcar_parking_receipt"><?php echo $value['car_parking_receipt']; ?> </td>
                                            <td class="travelcar_toll_receipt"><?php echo $value['car_toll_receipt']; ?> </td>

                                            <td class="travelamount"><?php echo $value['amount']; ?></td>
                                            <td class="travelreceipt_no"><?php echo $value['receipt_no']; ?></td>
                                            <td class="travelreceipt_image"><?php echo $value['receipt_image']; ?></td>
                                            <td class="travelremarks"><?php echo $value['remarks']; ?></td>
                                            <td class=""><?php echo $value['req_status']; ?></td>
                                            <td class=""><?php echo $value['created_at']; ?></td>

                                        <?php } ?>
                                        </tr>
                                    <?php

                                } ?>

                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- END TRAVEL DATA TABLE -->


        <!-- START HOTEL DATA TABLE -->
        <div class="form-div" id="divHotel">
            <h4>Hotel Report</h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">

                <!-- START HOTEL MODEL -->
                <div id="myModal_content3" class="modal">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <h4 class="col s12 m12 model-h4">Hotel Expense</h4>
                        <div class="modal-body">
                            <form method="POST">
                                <?php

                                $_SESSION["token2"] = csrfToken();
                                ?>
                                <input type="hidden" name="token2" value="<?= $_SESSION["token2"] ?>">
                                <input type="hidden" class="form-control hidden" id="hid_expense_hotelID" name="hid_expense_hotelID" />


                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="dateFrom" id="hotel_dateFrom" readonly>
                                    <label class="Active" for="hotel_dateFrom">Date From</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="dateTo" id="hotel_dateTo" readonly>
                                    <label class="Active" for="hotel_dateTo">Date To</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="noOfdays" id="noOfdays" readonly>
                                    <label class="Active" for="noOfdays">No Of Days</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="hotelName" id="hotelName" readonly>
                                    <label class="Active" for="hotelName">Hotel Name</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="amount" id="hotel_amount" readonly>
                                    <label class="Active" for="amount">Amount</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="receipt_no" id="hotel_receipt_no" readonly>
                                    <label class="Active" for="receipt_no">Receipt No</label>
                                </div>

                                <div class="input-field col s4 m4 l4" id="divhotelImg">
                                    <!-- <input type="text" name="receipt_image" id="hotel_receipt_image" readonly> -->
                                    <!-- <span class="Active">Receipt Image</span><br>
                                    <a href="../ExpenseHotel/<?php echo $resulthotel[0]['receipt_image']; ?>" target="_blank" download><?php echo $resulthotel[0]['receipt_image']; ?></a> -->
                                </div>


                                <div class="input-field col s12 m12 l12">
                                    <input type="text" name="remarks" id="hotel_remarks" readonly>
                                    <label class="Active" for="remarks">Remarks</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select id="hotel_reviewerStatus" name="reviewerStatus">
                                        <option value="NA">Select</option>
                                        <!-- <option value="Pending">Pending</option> -->
                                        <option value="Approve">Approve</option>
                                        <option value="Decline">Decline</option>
                                    </select>
                                    <label for="reviewerStatus" class="dropdown-active active">Reviewer:</label>
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <!-- <input type="text" name="reviewComment" id="hotel_reviewComment"> -->
                                    <textarea class="materialize-textarea" id="hotel_reviewComment" name="reviewComment" maxlength="200"></textarea>
                                    <label for="reviewComment">Reviewer Comment</label>
                                </div>


                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_edit_expenseHotel" id="btn_edit_expenseHotel" class="btn waves-effect waves-green ">Save</button>
                                    <button type="button" name="btn_can_expenseHotel" id="btn_can_expenseHotel" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END HOTEL MODEL -->

                <div id="pnlTable">
                    <?php

                    // $sqlConnect = 'SELECT t1.* FROM ems.expense_hotel t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID="' . $_SESSION['__user_logid'] . '" and is_reviewer="Yes"';
                    $sqlConnect = 'SELECT t1.* FROM ems.expense_hotel t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID=? and is_reviewer="Yes"';
                    $select = $conn->prepare($sqlConnect);
                    $select->bind_param("s", $EmployeeID);
                    $select->execute();
                    $result = $select->get_result();
                    if ($result->num_rows > 0) { ?>
                        <table id="hotelTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <!-- <th>Sr No</th> -->
                                    <th class="hidden">ID</th>
                                    <th>EmployeeID</th>
                                    <th>Employee Name</th>
                                    <th>DateFrom</th>
                                    <th>Date To</th>
                                    <th>No Of Days</th>
                                    <th>Hotel Name</th>
                                    <th>Amount</th>
                                    <th>Receipt No</th>
                                    <th>Receipt Image</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($result as $key => $value) {
                                    if ($value['reviewerStatus'] != 'Approve' && $value['reviewerStatus'] != 'Decline') { ?>

                                        <tr>
                                            <!-- <td><?php //echo $i++; 
                                                        ?></td> -->
                                            <td class="hotelID hidden"><?php echo $value['id']; ?></td>
                                            <td>
                                                <i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData_hotel(this);" id="<?php echo $value['id'] ?>" data-position="left" data-tooltip="Edit">ohrm_edit</i>
                                            </td>
                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['empName']; ?></td>
                                            <td class="dateFrom"><?php echo $value['dateFrom']; ?></td>
                                            <td class="dateTo"><?php echo $value['dateTo']; ?></td>
                                            <td class="noOfdays"><?php echo $value['noOfdays']; ?></td>
                                            <td class="hotelName"><?php echo $value['hotelName']; ?></td>
                                            <td class="hotelamount"><?php echo $value['amount']; ?></td>
                                            <td class="hotelreceipt_no"><?php echo $value['receipt_no']; ?></td>
                                            <td class="hotelreceipt_image"><?php echo $value['receipt_image']; ?></td>
                                            <td class="hotelremarks"><?php echo $value['remarks']; ?></td>
                                            <td class=""><?php echo $value['req_status']; ?></td>
                                            <td class=""><?php echo $value['created_at']; ?></td>

                                        <?php } ?>

                                        </td>
                                        </tr>
                                    <?php

                                } ?>

                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- END HOTEL DATA TABLE -->


        <!-- START MISCELLANEOUS DATA TABLE -->
        <div class="form-div" id="divMiscellaneous">
            <h4>Miscellaneous Report</h4>
            <!-- Form container if any -->
            <div class="schema-form-section row">

                <!-- START MISCELLANEOUS MODEL -->
                <div id="myModal_content4" class="modal">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <h4 class="col s12 m12 model-h4">Miscellaneous Expense</h4>
                        <div class="modal-body">
                            <form method="POST">
                                <?php

                                $_SESSION["token3"] = csrfToken();
                                ?>
                                <input type="hidden" name="token3" value="<?= $_SESSION["token3"] ?>">
                                <input type="hidden" class="form-control hidden" id="hid_expense_miscellaneousID" name="hid_expense_miscellaneousID" />

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="miscellaneous_date" id="miscellaneous_date" readonly>
                                    <label class="Active" for="miscellaneous_date">Date</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="amount" id="miscellaneous_amount" readonly>
                                    <label class="Active" for="amount">Amount</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="receipt_no" id="misc_receipt_no" readonly>
                                    <label class="Active" for="receipt_no">Receipt No</label>
                                </div>

                                <div class="input-field col s4 m4 l4" id="divmiscImg">
                                    <!-- <input type="text" name="receipt_image" id="misc_receipt_image" readonly> -->
                                    <!-- <span class="Active">Receipt Image</span><br>
                                    <a href="../ExpenseMiscellaneous/<?php echo $resultmiscellaneous[0]['receipt_image']; ?>" target="_blank" download><?php echo $resultmiscellaneous[0]['receipt_image']; ?></a> -->
                                </div>

                                <!-- 
                                <div class="file-field input-field col s4 m4">
                                    <div class="btn">
                                        <span>Browse File</span>
                                        <input type="file" id="misc_receipt_image" name="receipt_image" style="text-indent: -99999em;" readonly>
                                        <br>
                                        <span class="file-size-text">Accepts up to 2MB</span>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path" type="text">
                                    </div>
                                </div> -->


                                <div class="input-field col s12 m12 l12">
                                    <input type="text" name="remarks" id="miscellaneous_remarks" readonly>
                                    <label class="Active" for="remarks">Remarks</label>
                                </div>

                                <div class="input-field col s4 m4">
                                    <select id="miscellaneous_reviewerStatus" name="reviewerStatus">
                                        <option value="NA">Select</option>
                                        <!-- <option value="Pending">Pending</option> -->
                                        <option value="Approve">Approve</option>
                                        <option value="Decline">Decline</option>
                                    </select>
                                    <label for="reviewerStatus" class="dropdown-active active">Reviewer:</label>
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <!-- <input type="text" name="reviewComment" id="miscellaneous_reviewComment"> -->
                                    <textarea class="materialize-textarea" id="miscellaneous_reviewComment" name="reviewComment" maxlength="200"></textarea>
                                    <label for="reviewComment">Reviewer Comment</label>
                                </div>


                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_edit_expenseMiscellaneous" id="btn_edit_expenseMiscellaneous" class="btn waves-effect waves-green ">Save</button>
                                    <button type="button" name="btn_can_expenseMiscellaneous" id="btn_can_expenseMiscellaneous" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END MISCELLANEOUS MODEL -->

                <div id="pnlTable">
                    <?php
                    // $sqlConnect = 'SELECT t1.* FROM ems.expense_miscellaneous t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID="' . $_SESSION['__user_logid'] . '" and is_reviewer="Yes"';
                    $sqlConnect = 'SELECT t1.* FROM ems.expense_miscellaneous t1 join EmpID_Name t2 on t1.EmployeeID=t2.empid join expense_matrix t3 on t2.loc=t3.location where t3.EmployeeID=? and is_reviewer="Yes"';
                    $select = $conn->prepare($sqlConnect);
                    $select->bind_param("s", $EmployeeID);
                    $select->execute();
                    $result = $select->get_result();
                    if ($result->num_rows > 0) { ?>
                        <table id="miscellaneousTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <!-- <th>Sr No</th> -->
                                    <th class="hidden">Id</th>
                                    <th>View</th>
                                    <th>EmployeeID</th>
                                    <th>Employee Name</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Reciept No</th>
                                    <th>Receipt Image</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($result as $key => $value) {
                                    if ($value['reviewerStatus'] != 'Approve' && $value['reviewerStatus'] != 'Decline') { ?>

                                        <tr>
                                            <!-- <td><?php //echo $i++; 
                                                        ?></td> -->
                                            <td class="miscellaneousID hidden"><?php echo $value['id']; ?></td>
                                            <td>
                                                <i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData_miscellaneous(this);" id="<?php echo $value['id'] ?>" data-position="left" data-tooltip="Edit">ohrm_edit</i>
                                            </td>
                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['empName']; ?></td>
                                            <td class="miscellaneousdate"><?php echo $value['date']; ?></td>
                                            <td class="miscellaneousamount"><?php echo $value['amount']; ?></td>
                                            <td class="miscellaneousreceipt_no"><?php echo $value['receipt_no']; ?></td>
                                            <td class="miscellaneousreceipt_image"><?php echo $value['receipt_image']; ?></td>
                                            <td class="miscellaneousremarks"><?php echo $value['remarks']; ?></td>
                                            <td><?php echo $value['req_status']; ?></td>
                                            <td><?php echo $value['created_at']; ?></td>

                                        <?php } ?>

                                        </tr>
                                    <?php

                                } ?>

                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- END MISCELLANEOUS DATA TABLE -->

    </div>
    <!--Content Div for all Page End -->
</div>

<script>
    $('#food_date, #travel_date, #hotel_dateFrom, #hotel_dateTo, #miscellaneous_date').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        scrollMonth: false,
    });
</script>
<script>
    $('#divfood').hide();
    $('#divTravel').hide();
    $('#divHotel').hide();
    $('#divMiscellaneous').hide();

    $('#expenses1').change(function() {
        var expvalue = $(this).val();
        if (expvalue == 'Food Expenses') {
            $('#divfood').show();
            $('#divTravel').hide();
            $('#divHotel').hide();
            $('#divMiscellaneous').hide();

            $(document).ready(function() {
                $('#foodTable').DataTable({
                    dom: 'Bfrtip',
                    scrollX: '100%',
                    "iDisplayLength": 25,
                    scrollCollapse: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                    ],
                    "order": [
                        [1, "Asc"]
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
                        }
                        /*,'copy'*/
                        , 'pageLength'
                    ]
                    // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
                });
                $('.buttons-copy').attr('id', 'buttons_copy');
                $('.buttons-csv').attr('id', 'buttons_csv');
                $('.buttons-excel').attr('id', 'buttons_excel');
                $('.buttons-pdf').attr('id', 'buttons_pdf');
                $('.buttons-print').attr('id', 'buttons_print');
                $('.buttons-page-length').attr('id', 'buttons_page_length');

            });

        } else if (expvalue == 'Travel Expenses') {
            $('#divTravel').show();
            $('#divfood').hide();
            $('#divHotel').hide();
            $('#divMiscellaneous').hide();

            $(document).ready(function() {
                $('#travelTable').DataTable({
                    dom: 'Bfrtip',
                    fixedHeader: true,
                    "iDisplayLength": 25,
                    scrollX: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                    ],
                    "order": [
                        [0, "asc"]
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
                        }
                        /*,'copy'*/
                        , 'pageLength'
                    ]
                    // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
                });
                $('.buttons-copy').attr('id', 'buttons_copy');
                $('.buttons-csv').attr('id', 'buttons_csv');
                $('.buttons-excel').attr('id', 'buttons_excel');
                $('.buttons-pdf').attr('id', 'buttons_pdf');
                $('.buttons-print').attr('id', 'buttons_print');
                $('.buttons-page-length').attr('id', 'buttons_page_length');

            });

        } else if (expvalue == 'Hotel Expenses') {
            $('#divHotel').show();
            $('#divfood').hide();
            $('#divTravel').hide();
            $('#divMiscellaneous').hide();

            $(document).ready(function() {
                $('#hotelTable').DataTable({
                    dom: 'Bfrtip',
                    scrollX: '100%',
                    "iDisplayLength": 25,
                    scrollCollapse: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
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
                        }
                        /*,'copy'*/
                        , 'pageLength'
                    ]
                    // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
                });
                $('.buttons-copy').attr('id', 'buttons_copy');
                $('.buttons-csv').attr('id', 'buttons_csv');
                $('.buttons-excel').attr('id', 'buttons_excel');
                $('.buttons-pdf').attr('id', 'buttons_pdf');
                $('.buttons-print').attr('id', 'buttons_print');
                $('.buttons-page-length').attr('id', 'buttons_page_length');

            });

        } else if (expvalue == 'Miscellaneous Expenses') {
            $('#divMiscellaneous').show();
            $('#divfood').hide();
            $('#divTravel').hide();
            $('#divHotel').hide();

            $(document).ready(function() {
                $('#miscellaneousTable').DataTable({
                    dom: 'Bfrtip',
                    scrollX: '100%',
                    "iDisplayLength": 25,
                    scrollCollapse: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
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
                        }
                        /*,'copy'*/
                        , 'pageLength'
                    ]
                    // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
                });
                $('.buttons-copy').attr('id', 'buttons_copy');
                $('.buttons-csv').attr('id', 'buttons_csv');
                $('.buttons-excel').attr('id', 'buttons_excel');
                $('.buttons-pdf').attr('id', 'buttons_pdf');
                $('.buttons-print').attr('id', 'buttons_print');
                $('.buttons-page-length').attr('id', 'buttons_page_length');

            });

        } else if (expvalue == 'NA') {
            alert('Please Select A Specific')
        }

    });
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
<!-- START FOOD VALUE -->
<script>
    $(document).ready(function() {
        //Model Assigned and initiation code on document load
        $('.modal').modal({
            onOpenStart: function(elm) {},
            onCloseEnd: function(elm) {
                $('#btn_can_expenseFood').trigger("click");
            }
        });


        $('#btn_edit_expenseFood').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#reviewerStatus').val() == 'NA') {
                $('#reviewerStatus').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_ERSPOC').length == 0) {
                    $('<span id="spantxt_ERSPOC" class="help-block">Required *</span>').insertAfter('#reviewerStatus');
                }
                validate = 1;
            }

            if ($('#reviewComment').val() == '') {
                $('#reviewComment').addClass("has-error");
                if ($('#spanreviewComment').length == 0) {
                    $('<span id="spanreviewComment" class="help-block">Required *</span>').insertAfter('#reviewComment');
                }
                validate = 1;
            }

            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(50000).fadeOut("slow");
                return false;
            }
        });
    });

    function EditData_food(el) {
        var tr = $(el).closest('tr');
        var foodID = tr.find('.foodID').text();
        var food_date = tr.find('.food_date').text();
        var amount = tr.find('.foodamount').text();
        var receipt_no = tr.find('.foodreceipt_no').text();
        var receipt_image = tr.find('.foodreceipt_image').text();
        var divfood = ' <span class="Active">Receipt Image</span><br><a href="../ExpenseFood/' + receipt_image + '"target="_blank" download>' + receipt_image + '</a>';
        var remarks = tr.find('.foodremarks').text();

        $('#hid_expense_foodID').val(foodID);
        $('#food_date').val(food_date);
        $('#food_amount').val(amount);
        $('#food_receipt_no').val(receipt_no);
        $('#food_receipt_image').val(receipt_image);
        $('#divfoodImg').html(divfood);
        $('#food_remarks').val(remarks);

        $('#btn_edit_expenseFood').removeClass('hidden');
        $('#btn_can_expenseFood').removeClass('hidden');
        $('#myModal_content').modal('open');

    }
</script>
<!-- END FOOD VALUE -->


<!-- START TRAVEL VALUE -->
<script>
    $('#retDate').hide()
    $('#modeOftravel').change(function() {
        var travelValue = $(this).val()
        //alert(travelValue)
        if (travelValue == 'flight') {
            $('#retDate').show();
        } else {
            $('#retDate').hide();
        }
    });

    $(document).ready(function() {
        //Model Assigned and initiation code on document load
        $('.modal').modal({
            onOpenStart: function(elm) {},
            onCloseEnd: function(elm) {
                $('#btn_can_expenseTravel').trigger("click");
            }
        });

        $('#btn_edit_expenseTravel').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#travel_reviewerStatus').val() == 'NA') {
                $('#travel_reviewerStatus').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_ERSPOC').length == 0) {
                    $('<span id="spantxt_ERSPOC" class="help-block">Required *</span>').insertAfter('#travel_reviewerStatus');
                }
                validate = 1;
            }

            if ($('#travel_reviewComment').val() == '') {
                $('#travel_reviewComment').addClass("has-error");
                if ($('#spanreviewComment').length == 0) {
                    $('<span id="spanreviewComment" class="help-block">Required *</span>').insertAfter('#travel_reviewComment');
                }
                validate = 1;
            }

            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(50000).fadeOut("slow");
                return false;
            }
        });
    });


    // $('#carReceipt').hide();
    // $('#parkReceipt').hide();
    // $('#parkAmount').hide();
    // $('#tollReceipt').hide();
    // $('#tollAmount').hide();
    // $('#kiloMeter').hide();
    // $('#kmAmount').hide();

    function EditData_travel(el) {
        var tr = $(el).closest('tr');
        var travelID = tr.find('.travelID').text();
        var traveldate = tr.find('.traveldate').text();
        var placefrom = tr.find('.travelplacefrom').text();
        var placeTO = tr.find('.travelplaceTO').text();

        var modeOftravel = tr.find('.travelmodeOftravel').text().toLowerCase();
        // modeOftravel = $.replace(/^\s+|\s+$/g, modeOftravel);
        modeOftravel = $.trim(modeOftravel);
        var modeOftravel_match = 'car';
        // modeOftravel_match = $.replace(/^\s+|\s+$/g, modeOftravel_match);
        modeOftravel_match = $.trim(modeOftravel_match);

        // alert(modeOftravel);
        // console.log('1');
        // console.log(modeOftravel);
        // console.log('2');
        // console.log(modeOftravel_match);

        if (modeOftravel == modeOftravel_match) {
            // console.log('dfdfdfd');
            $('#lableAmount').text('Total Amount');
            $("#travel_amount").prop("disabled", true);
            $('#receiptNoDIV').hide();
            $('.receiptImageDIV').hide();
            $('#carReceipt').show();
            $('#parkReceipt').show();
            $('#tollReceipt').show();
            $('#kiloMeter').show();
            // $('#parkAmount').show();
            // $('#tollAmount').show();
            // $('#kmAmount').show();
        } else {
            $('#lableAmount').text('Amount');
            $("#travel_amount").prop("disabled", false);
            $('#carReceipt').hide();
            $('#parkReceipt').hide();
            $('#parkAmount').hide();
            $('#tollReceipt').hide();
            $('#tollAmount').hide();
            $('#kiloMeter').hide();
            $('#kmAmount').hide();
            $('#receiptNoDIV').show();
            $('.receiptImageDIV').show();
        }


        var returndate = tr.find('.travelreturnDate').text();
        var carkm = tr.find('.travelcar_km').text();

        var carkmreceipt = tr.find('.travelcar_km_receipt').text();
        // alert(carkmreceipt)
        var divtravelKMR = '<span class="Active">Car KM Image</span><br><a href="../ExpenseTravel/' + carkmreceipt + '"target="_blank" download>' + carkmreceipt + '</a>';
        // alert(divtravelKMR)

        var carparkreceipt = tr.find('.travelcar_parking_receipt').text();
        var divtravelCARR = '<span class="Active">Car Park Image</span><br><a href="../ExpenseTravel/' + carparkreceipt + '"target="_blank" download>' + carparkreceipt + '</a>';
        // alert(divtravelCARR)

        var cartollreceipt = tr.find('.travelcar_toll_receipt').text();
        var divtravelTOLLR = '<span class="Active">Car Toll Image</span><br><a href="../ExpenseTravel/' + cartollreceipt + '"target="_blank" download>' + cartollreceipt + '</a>';
        // alert(divtravelTOLLR)

        var travelamount = tr.find('.travelamount').text();
        var travelreceipt_no = tr.find('.travelreceipt_no').text();
        var travelreceipt_img = tr.find('.travelreceipt_image').text();
        var divtravel = '<span class="Active">Receipt Image</span><br><a href="../ExpenseTravel/' + travelreceipt_img + '"target="_blank" download>' + travelreceipt_img + '</a>';
        var travelremarks = tr.find('.travelremarks').text();

        $('#hid_expense_travelID').val(travelID);
        $('#travel_date').val(traveldate);
        $('#placeFrom').val(placefrom);
        $('#placeTO').val(placeTO);
        $('#modeOftravel').val(modeOftravel);
        // alert($('#modeOftravel').val());

        $('#returnDate').val(returndate);
        $('#car_km').val(carkm);

        $('#travel_car_km_receipt').val(carkmreceipt);
        $('.travelCarRcpt').html(divtravelKMR);

        $('#travel_car_parking_receipt').val(carparkreceipt);
        $('.travelParkRcpt').html(divtravelCARR);

        $('#travel_car_toll_receipt').val(cartollreceipt);
        $('.travelTollRcpt').html(divtravelTOLLR);

        $('#travel_amount').val(travelamount);
        $('#travel_receipt_no').val(travelreceipt_no);

        $('#travel_receipt_image').val(travelreceipt_img);
        $('#divtravelImg').html(divtravel);

        $('#travel_remarks').val(travelremarks);

        $('#btn_edit_expensetravel').removeClass('hidden');
        $('#btn_can_expenseTravel').removeClass('hidden');
        $('#myModal_content2').modal('open');

        $('#modeOftravel').find('option[value=""]').attr('selected', 'selected');

    }
</script>
<!-- END TRAVEL VALUE -->


<!-- START HOTEL VALUE -->
<script>
    $(document).ready(function() {
        //Model Assigned and initiation code on document load
        $('.modal').modal({
            onOpenStart: function(elm) {},
            onCloseEnd: function(elm) {
                $('#btn_can_expenseHotel').trigger("click");
            }
        });

        $('#btn_edit_expenseHotel').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#hotel_reviewerStatus').val() == 'NA') {
                $('#hotel_reviewerStatus').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_ERSPOC').length == 0) {
                    $('<span id="spantxt_ERSPOC" class="help-block">Required *</span>').insertAfter('#hotel_reviewerStatus');
                }
                validate = 1;
            }

            if ($('#hotel_reviewComment').val() == '') {
                $('#hotel_reviewComment').addClass("has-error");
                if ($('#spanreviewComment').length == 0) {
                    $('<span id="spanreviewComment" class="help-block">Required *</span>').insertAfter('#hotel_reviewComment');
                }
                validate = 1;
            }

            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(50000).fadeOut("slow");
                return false;
            }
        });
    });


    function EditData_hotel(el) {
        var tr = $(el).closest('tr');
        var hotelID = tr.find('.hotelID').text();
        var datefrom = tr.find('.dateFrom').text();
        var dateto = tr.find('.dateTo').text();
        var noofdays = tr.find('.noOfdays').text();
        var hotelname = tr.find('.hotelName').text();
        var hotelamount = tr.find('.hotelamount').text();
        var hotelreceipt_no = tr.find('.hotelreceipt_no').text();
        var hotelreceipt_img = tr.find('.hotelreceipt_image').text();
        var divhotel = '<span class="Active">Receipt Image</span><br><a href="../ExpenseHotel/' + hotelreceipt_img + '"target="_blank" download>' + hotelreceipt_img + '</a>';
        var travelremarks = tr.find('.travelremarks').text();
        var hotelremarks = tr.find('.hotelremarks').text();

        $('#hid_expense_hotelID').val(hotelID);
        $('#hotel_dateFrom').val(datefrom);
        $('#hotel_dateTo').val(dateto);
        $('#noOfdays').val(noofdays);
        $('#hotelName').val(hotelname);
        $('#hotel_amount').val(hotelamount);
        $('#hotel_receipt_no').val(hotelreceipt_no);
        $('#hotel_receipt_image').val(hotelreceipt_img);
        $('#divhotelImg').html(divhotel);
        $('#hotel_remarks').val(hotelremarks);

        $('#btn_edit_expenseHotel').removeClass('hidden');
        $('#btn_can_expenseHotel').removeClass('hidden');
        $('#myModal_content3').modal('open');

    }
</script>
<!-- END HOTEL VALUE -->


<!-- START MISCELLANEOUS VALUE -->
<script>
    $(document).ready(function() {
        //Model Assigned and initiation code on document load
        $('.modal').modal({
            onOpenStart: function(elm) {},
            onCloseEnd: function(elm) {
                $('#btn_can_expenseMiscellaneous').trigger("click");
            }
        });

        $('#btn_edit_expenseMiscellaneous').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#miscellaneous_reviewerStatus').val() == 'NA') {
                $('#miscellaneous_reviewerStatus').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spantxt_ERSPOC').length == 0) {
                    $('<span id="spantxt_ERSPOC" class="help-block">Required *</span>').insertAfter('#miscellaneous_reviewerStatus');
                }
                validate = 1;
            }

            if ($('#miscellaneous_reviewComment').val() == '') {
                $('#miscellaneous_reviewComment').addClass("has-error");
                if ($('#spanreviewComment').length == 0) {
                    $('<span id="spanreviewComment" class="help-block">Required *</span>').insertAfter('#miscellaneous_reviewComment');
                }
                validate = 1;
            }

            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(50000).fadeOut("slow");
                return false;
            }
        });
    });


    function EditData_miscellaneous(el) {
        var tr = $(el).closest('tr');
        var miscellaneousID = tr.find('.miscellaneousID').text();
        var miscellaneousdate = tr.find('.miscellaneousdate').text();
        var miscellaneousamount = tr.find('.miscellaneousamount').text();
        var miscellaneousreceipt_no = tr.find('.miscellaneousreceipt_no').text();
        var miscellaneousreceipt_image = tr.find('.miscellaneousreceipt_image').text();
        var divmisc = '<span class="Active">Receipt Image</span><br><a href="../ExpenseMiscellaneous/' + miscellaneousreceipt_image + '"target="_blank" download>' + miscellaneousreceipt_image + '</a>';
        var miscellaneousremarks = tr.find('.miscellaneousremarks').text();

        $('#hid_expense_miscellaneousID').val(miscellaneousID);
        $('#miscellaneous_date').val(miscellaneousdate);
        $('#miscellaneous_amount').val(miscellaneousamount);
        $('#misc_receipt_no').val(miscellaneousreceipt_no);
        $('#misc_receipt_image').val(miscellaneousreceipt_image);
        $('#divmiscImg').html(divmisc);
        $('#miscellaneous_remarks').val(miscellaneousremarks);

        $('#btn_edit_expenseMiscellaneous').removeClass('hidden');
        $('#btn_can_expenseMiscellaneous').removeClass('hidden');
        $('#myModal_content4').modal('open');

    }
</script>
<!-- END MISCELLANEOUS VALUE -->