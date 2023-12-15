<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);
if ($id && $id != "") {
    $sql = "select EmployeeID,Emp_Name,Designation,pro_month,pro_year from appraisal_promotion where EmployeeID=? order by wef";

    $selectQ = $conn->prepare($sql);
    $selectQ->bind_param("s", $id);
    $selectQ->execute();
    $result = $selectQ->get_result();
    $table = '';
    if ($result->num_rows > 0 && $result) {
        $table .= '<style>
       
        .trborder{
            border: 1px solid;
        }
        </style><table style="width: 100%; text-align: center; " align="center" class="trborder"><thead ><tr><th class="trborder">EmployeeID</th><th class="trborder">Employee Name</th><th class="trborder">Designation</th ><th class="trborder">Month</th><th class="trborder">Year</th></tr></thead>';
        foreach ($result as $key => $value) {

            $table .= '<tr ><td class="trborder">' . $value['EmployeeID'] . ' </td><td class="trborder">' . $value['Emp_Name'] . ' </td><td class="trborder">' . $value['Designation'] . ' </td><td class="trborder">' . $value['pro_month'] . ' </td><td class="trborder">' . $value['pro_year'] . ' </td></tr>';
        }
        $table .= '</table>';
    } else {
        $table = '<p><span style="color: #218ea0; font-weight: bold;"> No Record Found </span>  </p>';
    }
    echo $table;
}
