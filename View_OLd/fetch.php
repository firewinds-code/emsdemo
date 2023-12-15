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

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">DataTable</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>DataTable</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<script>
					$(function() {
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
							//"sScrollY": "192",
							"sScrollX": "100%",
							"bScrollCollapse": true,
							"bLengthChange": false,
							"fnDrawCallback": function() {

								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
							//buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'pageLength']
						});
						$('.buttons-copy').attr('id', 'buttons_copy');
						$('.buttons-csv').attr('id', 'buttons_csv');
						$('.buttons-excel').attr('id', 'buttons_excel');
						$('.buttons-pdf').attr('id', 'buttons_pdf');
						$('.buttons-print').attr('id', 'buttons_print');
						$('.buttons-page-length').attr('id', 'buttons_page_length');
					});
				</script>
				<?php
				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => 'http://mybcd.live/api/client/reports?reference_number=COGE-0000001118',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs',
						'Cookie: XSRF-TOKEN=eyJpdiI6IlR5Sm5RSWlMUm9NdmdMS0FEVSttOFE9PSIsInZhbHVlIjoiUVI0NjlFL2JLYlV0MXJOb3BaUHVoUC82SkY1bkV6RitMWlFNcHZzemNXdmovZ3JNWTJ1QUlZMXR0cTBRU3JWWEZyUjZSZmxqRHB5ZXBrOURtSmJlcVZhNXc0dldzUU5TODV5NENYdlVYc1JIQVY2NTE5bG50b2ZDOGF0VFBaZWgiLCJtYWMiOiJmOTUzMTAyMTEwM2IyYTM2NzYxMzJkYjNiMTcwOGZkYmI0NjNiOWM1NWU1OGVmMDg5ZjY1YWJjNWVkMmE3NGM5In0%3D; bcd_session=eyJpdiI6IkowMDdtYkxvVlE0Z0FsdGxSL3ZSWEE9PSIsInZhbHVlIjoiR1NkWmZ6ajJ0Zy9SR3p1TCtnSmJpbnNjYWp6T241Rmliejd6OURQOEJWTXEwR3lWK1JiU3RZOHdEMFQ3NmF2b0xxaWdoeFRBZ1BFMEJhVXVKT3MwanlzdlF2cUdmMVNIUDFPangvWFhycnBFTGExTGptdUQyNkV6UjFwTDNWOTQiLCJtYWMiOiI3MWIzYTAzODRhYmYxOWFjNTBlNTFmYjk2NmY3NzNkNDFlYWZjYzlkMDU4MGRlMTNlNGZmMzJmMDg4NGJkM2VjIn0%3D'
					),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				// echo $response;
				// $data=json_decode($response);
				$data = json_decode($response, true);
				//print_r($data['data']['data']);die;
				?>
				<?php
				$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                <div class=""><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
				$table .= '<th>Sr No.</th>';
				$table .= '<th>Candidate_ID</th>';
				$table .= '<th>Name</th>';
				$table .= '<th>Company_Name</th>';
				$table .= '<th>Reference_Number</th>';
				$table .= '<th>Phone_Number</th>';
				$table .= '<th>Sla_Name</th>';
				$table .= '<th>Status</th>';
				$table .= '<th>Created_at</th>';
				$table .= '<th>Color_code</th></tr></thead><tbody>';


				$count = 1;
				foreach ($data['data']['data'] as  $value) {
					if ($count != '101') {
						$table .= '<tr><td>' . $count . '</td>';
						$table .= '<td>' . $value['candidate_id'] . '</td>';
						$table .= '<td>' . $value['name'] . '</td>';
						$table .= '<td>' . $value['company_name'] . '</td>';
						$table .= '<td>' . $value['reference_number'] . '</td>';
						$table .= '<td>' . $value['phone_number'] . '</td>';
						$table .= '<td>' . $value['sla_name'] . '</td>';
						$table .= '<td>' . $value['status'] . '</td>';
						$table .= '<td>' . $value['created_at'] . '</td>';
						$table .= '<td>' . $value['color_code'] . '</td></tr>';
						$count++;
					}
				}
				$table .= '</tbody></table></div></div>';
				echo $table;
				?>

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>