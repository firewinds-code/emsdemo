<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_REQUEST['cm_id']) && $_REQUEST['cm_id'] != "") {
    $cm_id = clean($_REQUEST['cm_id']);
    $type = clean($_REQUEST['type']);
    if ($type = 'support') {
        $sql = "select * from bgv_matrix where cm_id= ? and desig='Support'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $cm_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->num_rows;

        // $result = $myDB->rawQuery($sql);
        if ($count > 0 && $result) {
            foreach ($result as $key => $value) {
                if ($value['Addr'] == 'Yes') {
?>
                    <script>
                        $('#bgv1').prop('checked', true);
                    </script>
                <?php
                }
                if ($value['Edu'] == 'Yes') { ?>
                    <script>
                        $('#bgv2').prop('checked', true);
                    </script>
                <?php
                }
                if ($value['Emp'] == 'Yes') {
                ?>
                    <script>
                        $('#bgv3').prop('checked', true);
                    </script>
                <?php
                }
                if ($value['Crim'] == 'Yes') {
                ?>
                    <script>
                        $('#bgv4').prop('checked', true);
                    </script>
                <?php
                }
            }
        }
    }


    if ($type = 'CSA') {
        $sql = "select * from bgv_matrix where cm_id= ? and desig='CSA'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $cm_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->num_rows;
        // $myDB = new MysqliDb();
        // $result = $myDB->rawQuery($sql);
        if ($count > 0 && $result) {
            foreach ($result as $key => $value) {
                if ($value['Addr'] == 'Yes') {
                ?>
                    <script>
                        $('#bgv5').prop('checked', true);
                    </script>
                <?php
                }
                if ($value['Edu'] == 'Yes') { ?>
                    <script>
                        $('#bgv6').prop('checked', true);
                    </script>
                <?php
                }
                if ($value['Emp'] == 'Yes') {
                ?>
                    <script>
                        $('#bgv7').prop('checked', true);
                    </script>
                <?php
                }
                if ($value['Crim'] == 'Yes') {
                ?>
                    <script>
                        $('#bgv8').prop('checked', true);
                    </script>
<?php
                }
            }
        }
    }
}
?>