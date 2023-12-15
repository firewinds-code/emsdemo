<html>

<head>
  <script src="https://maps.google.com/maps/api/js?sensor=false"></script>
</head>

<body>
  <div id="map" style="width: 100vw; height: 100vh;"></div>
  <?php

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://ems.cogentlab.com/erpm/app/getShareLocation.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Cookie: PHPSESSID=t6fl18atvk559k386m4a5tctv6'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  // echo $response;

  echo "<pre>";
  $remit_response  = json_decode($response);
  //echo $remit_response;



  ?>




  <script>
    var LocationsForMap =

      [

        <?php foreach ($remit_response as $value) { ?>


          [`<?= $value->latlongaddress ?>`, `<?= $value->latitude ?>`, `<?= $value->longitude ?>`, ],

        <?php } ?>

      ];


    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 2,
      center: new google.maps.LatLng(28.704, 77.25),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < LocationsForMap.length; i++) {
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(LocationsForMap[i][1], LocationsForMap[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(LocationsForMap[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  </script>
</body>

</html>