<?php
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
?>
<html>

<head>
</head>

<body>
    <table>
        <tr>
            <td>
                <h4>All Process</h4>
                <input id="myInput" type="text" placeholder="Search..">
            </td>
            <td></td>
            <td>
                <h4>Selected Process</h4>
            </td>
        </tr>
        <tr>
            <td>
                <select multiple id="source1" style="width: 430px;height: 300px;">
                    <!--<?php
                        $sqlBy = ' select t1.cm_id,concat(t2.client_name,"|",t1.process,"|",t1.sub_process," (",t3.location,")") as Process from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id join location_master t3 on t1.location=t3.id where t1.cm_id not in (select cm_id from client_status_master) order by t2.client_name;';
                        $myDB = new MysqliDb();
                        $resultBy = $myDB->query($sqlBy);
                        if ($myDB->count > 0) {
                            foreach ($resultBy as $key => $value) {
                                echo '<option  value="' . $value['cm_id'] . '" ' . $selected . '>' . $value['Process'] . '</option>';
                            }
                        }
                        ?>-->
                </select>
            </td>
            <td>
                <div style="display:inline;vertical-align:top;">
                    <button id="shift1">>></button> <br /><br />
                    <button id="rshift1">
                        << </button>
                </div>
            </td>
            <td><select multiple id="target1" name="tt1" style="width: 590px;height: 300px;"></select></td>
        </tr>
    </table>


    <!--<button type="button" name="save" id="save">Submit</button>-->

    <script src="../Script/jquery.js"></script>
    <script>
        $(document).ready(function() {

            $('#save').click(function() {
                /*$("#target1 option").each(function()
                {
                    alert($(this).val());
                });*/
                $('#source1')
                    .find('option')
                    .remove();
            });
        });
    </script>

    <script>
        var hybridSelector = function(source, target, shift, rshift) {
            var ddlSource = source;
            var ddlTarget = target;
            var btnShift = shift;
            var btnRShift = rshift;

            btnShift.addEventListener("click", function() {
                var selectedItems = getSelectOptions(source);

                if (selectedItems) {
                    for (var i = 0; i < selectedItems.length; i++) {
                        var option = new Option(selectedItems[i].text, selectedItems[i].value);

                        ddlTarget.appendChild(option);

                        selectedItems[i].remove();
                    }
                }
            });

            btnRShift.addEventListener("click", function() {
                var selectedItems = getSelectOptions(target);
                if (selectedItems) {
                    for (var i = 0; i < selectedItems.length; i++) {
                        var option = new Option(selectedItems[i].text, selectedItems[i].value);
                        ddlSource.appendChild(option);
                        selectedItems[i].remove();

                    }
                }
            });

            function getSelectOptions(select) {
                var result = [];
                var options = select.options;
                var opt;

                for (var i = 0, iLen = options.length; i < iLen; i++) {
                    opt = options[i];

                    if (opt.selected) {
                        result.push(opt);
                    }
                }
                return result;
            }

            return ddlTarget.options;
        };

        //can instantiate as many as i want
        var hybridSelector1 = new hybridSelector(document.getElementById('source1'), document.getElementById('target1'), document.getElementById('shift1'), document.getElementById('rshift1'));
        /*var hybridSelector2 = new hybridSelector(document.getElementById('source2'), document.getElementById('target2'), document.getElementById('shift2'), document.getElementById('rshift2'));

        function submit() {
            var options1 = hybridSelector1;
            var options2 = hybridSelector2;

            debugger;
            return false;
        }*/
    </script>

    <script>
        $(document).ready(function() {
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#source1 option").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

</body>

</html>