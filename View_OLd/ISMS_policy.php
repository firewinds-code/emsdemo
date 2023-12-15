<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">ISMS Policy</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>ISMS Policy</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <div class="input-field col s8 m8 8">
                    <select class="form-control" name="policy" id="policy">
                        <option value="policy1">Asset Management Policy and Procedure</option>
                        <option value="policy2">Cogent E Services Access Control Policy</option>
                        <option value="policy3">Cogent E Services Change Management Policy</option>
                        <option value="policy4">Cogent E Services Incident Management Policy</option>
                        <option value="policy5">Cogent E Services Patch Management Policy</option>
                        <option value="policy6">Procedure for Monitoring Measurement Analysis & Evaluation</option>
                        <option value="policy7">Cogent E Services Anti Virus Policy</option>
                        <option value="policy8">Cogent E Services Data Backup Policy</option>
                        <option value="policy9">Information Security Risk Management Policy</option>
                        <option value="policy10">Cogent E Services ISMS ID Management Policy</option>
                        <option value="policy11">Physical and Environmental Security Policy</option>
                        <option value="policy12">Data Protection Policy</option>
                        <option value="policy13">Data-Media Handling Policy</option>
                        <option value="policy14">Guidelines on Acceptable Use of Assets</option>
                        <option value="policy15">Human Resource Security Policy</option>
                        <option value="policy16">Information Security Policy</option>
                        <option value="policy17">ISMS-L2-A 16.1 Incident Management Procedure</option>
                        <option value="policy18">Mobile Security Policy Version</option>
                        <option value="policy19">Monitoring, Measurement, Analysis And Evaluation Policy and Procedure </option>
                    </select>
                    <label for="policy" class="active-drop-down active">ISMS Policy</label>
                </div>
                <br><br><br>

                <div class="input-field col s12 m12 12">
                    <embed id='emd1' src="../FileContainer/Polices_ISMS/Asset Management Policy and Procedure Version 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%">
                    <embed id='emd2' src="../FileContainer/Polices_ISMS/Cogent  E Services  Access Control Policy  V.3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd3' src="../FileContainer/Polices_ISMS/Cogent  E Services  Change Management Policy V3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd4' src="../FileContainer/Polices_ISMS/Cogent  E Services  Incident Management Policy V.3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd5' src="../FileContainer/Polices_ISMS/Cogent  E Services  Patch Management Policy V3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd6' src="../FileContainer/Polices_ISMS/Cogent  E Services  Procedure for Monitoring Measurement Analysis and Evaluation V.3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd7' src="../FileContainer/Polices_ISMS/Cogent  E Services Anti Virus Policy V.3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd8' src="../FileContainer/Polices_ISMS/Cogent  E Services Data Backup Policy V3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd9' src="../FileContainer/Polices_ISMS/Cogent  E Services Information Security Risk Management Policy Version.3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd10' src="../FileContainer/Polices_ISMS/Cogent  E Services ISMS  ID Management Policy 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd11' src="../FileContainer/Polices_ISMS/Cogent  E Services Physical and Environmental Security Policy  V.3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd12' src="../FileContainer/Polices_ISMS/Data Protection Policy Version 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd13' src="../FileContainer/Polices_ISMS/Data-Media Handling Policy Version 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd14' src="../FileContainer/Polices_ISMS/Guidelines on Acceptable Use of Assets  Version 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd15' src="../FileContainer/Polices_ISMS/Human Resource Security Policy Version 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd16' src="../FileContainer/Polices_ISMS/Information Security Policy Version 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd17' src="../FileContainer/Polices_ISMS/ISMS-L2-A 16.1 Incident Management Procedure Version 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd18' src="../FileContainer/Polices_ISMS/Mobile Security Policy Version 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd19' src="../FileContainer/Polices_ISMS/Monitoring, Measurement, Analysis And Evaluation Policy and Procedure Version 3.2.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                </div>
            </div>
        </div>
    </div>
</div>
<!--Content Div for all Page End -->

<script>
    $(document).ready(function() {
        $('#emd1').show();
        $('#emd2').hide();
        $('#emd3').hide();
        $('#emd4').hide();
        $('#emd5').hide();
        $('#emd6').hide();
        $('#emd7').hide();
        $('#emd8').hide();
        $('#emd9').hide();
        $('#emd10').hide();
        $('#emd11').hide();
        $('#emd12').hide();
        $('#emd13').hide();
        $('#emd14').hide();
        $('#emd15').hide();
        $('#emd16').hide();
        $('#emd17').hide();
        $('#emd18').hide();
        $('#emd19').hide();

        $('#policy').on('change', function() {
            if ($('#policy').val() == 'policy1') {
                $('#emd1').show();
                $('#emd2').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy2') {
                $('#emd2').show();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy3') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').show();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy4') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').show();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy5') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').show();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy6') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').show();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy7') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').show();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy8') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').show();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy9') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').show();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy10') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').show();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy11') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').show();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy12') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').show();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy13') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').show();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy14') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').show();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy15') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').show();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy16') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').show();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy17') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').show();
                $('#emd18').hide();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy18') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').show();
                $('#emd19').hide();
                return false;
            };
            if ($('#policy').val() == 'policy19') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();
                $('#emd7').hide();
                $('#emd8').hide();
                $('#emd9').hide();
                $('#emd10').hide();
                $('#emd11').hide();
                $('#emd12').hide();
                $('#emd13').hide();
                $('#emd14').hide();
                $('#emd15').hide();
                $('#emd16').hide();
                $('#emd17').hide();
                $('#emd18').hide();
                $('#emd19').show();
                return false;
            };
        });
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>