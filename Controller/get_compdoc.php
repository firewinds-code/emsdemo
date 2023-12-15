<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$incident = "";
if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
    $ref_id = $_REQUEST['id'];
} else {
    $ref_id = '';
}
// $sql = 'select * from comp_issue_file where comp_id=?';
$sql = 'select t1.id,t1.doc_evi_filename,t2.create_date from comp_issue_file t1 left join comp_table t2 on t1.comp_id=t2.id where comp_id=?';
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("i", $ref_id);
$selectQ->execute();
$result = $selectQ->get_result();
$res = '';
if ($result->num_rows > 0 && $result) {
    foreach ($result as $key => $value) {
        // $res .= $value['id'] . '|' . $value['doc_evi_filename'] . '|';
        $rowId = $value['id'];
        $docfile = '../uploads/' . $value['doc_evi_filename'] . '';
        $incident .= '<tr>
            <td style="padding-left:80px;padding-right:80px;" class="hidden">' . $value['id'] . '</td>
            <td> <a href="' . $docfile . '" target="_blank" download>' . $value['doc_evi_filename'] . '</a></td>
            <td style="padding-left:80px;padding-right:80px;">' . $value['create_date'] . '</td>
            <td style="padding-left:80px;padding-right:80px;"><a data-item="' . $value['id'] . '" class="btn btn-md btn-primary delbtn" 
            name="addBtn" onclick="javascript:return deleteComplaint(this);" >Delete</a></td>
        </tr>';
    }
}
// onclick="deleteComplaint(' . $rowId . ')"
echo $incident;
?>
<script>
    function deleteComplaint(el) {

        if (confirm('Are you sure want to delete??')) {
            $item = $(el);

            $.ajax({
                url: "../Controller/get_compdoc_delete.php?id=" + $(el).attr('data-item'),
                success: function(result) {
                    // alert("fffffff");
                    var data = result.split('|');
                    toastr.success(data[1]);

                    if (data[0] == 'Done') {
                        $item.closest('td').parent('tr').remove();
                    }
                }
            });
        }
    }



    // function deleteComplaint(id) {
    //     $.ajax({
    //         type: "GET",
    //         url: "../Controller/get_compdoc_delete.php?id=" + id,
    //         success: function(response) {
    //             if (response) {
    //                 alert("Delete successfully");
    //                 // $(this).closest('tr').remove();
    //                 // return false;
    //             }
    //         }
    //     });
    // }

    // $(document).on('click', 'button.delbtn', function() {
    //     $(this).closest('tr').remove();
    //     return false;
    // });
</script>