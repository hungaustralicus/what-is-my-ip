<html>
<head>
<title>What I know about you</title>
</head>
<body>
<br>Welcome Guest!</br>
<?php
$hostname=gethostname();
// echo "My server name is: " . $hostname . "<br>";
$serverip=gethostbyname($hostname);
echo "My server IP is: " . $serverip . "<br>";
echo "HTTP host: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "Your public IP address is: " . $_SERVER['REMOTE_ADDR'] . "<br>";
echo "You are visiting the following URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "You accessed this page from: " . $_SERVER['HTTP_REFERER'] . "<br>";
echo "You are using: " . $_SERVER['HTTP_USER_AGENT'] . "<br>";
$hostname2 = gethostbyaddr($_SERVER['REMOTE_ADDR']);
echo "Host name is: " . $hostname2 . "<br>";
echo "HTTP accept language: " . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "<br>";

function getIP() {
      foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 
                'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
         if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
               if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                  return $ip;
               }
            }
         }
      }
   }
   
   $json = file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn='. getIP()); 
   $data = json_decode($json, true);
//   echo '<b>'. getIP() .'</b> resolves to:'. var_dump($data);
 getIP();
echo "end of data<br>";


echo "Country: " . $data["geobytescountry"] . "<br>";
echo "Region: " . $data["geobytesregion"] . "<br>";
echo "City: " . $data["geobytescity"] . "<br>";
echo "Latitude: " . $data["geobyteslatitude"] . "<br>";
echo "Longitude: " . $data["geobyteslongitude"] . "<br>";
echo "Time zone: " . $data["geobytestimezone"] . "<br>";


?>
   <div id="map"></div>
    <script>

function initMap() {
  var chicago = new google.maps.LatLng(41.850, -87.650);

  var map = new google.maps.Map(document.getElementById('map'), {
    center: chicago,
    zoom: 3
  });

  var coordInfoWindow = new google.maps.InfoWindow();
  coordInfoWindow.setContent(createInfoWindowContent(chicago, map.getZoom()));
  coordInfoWindow.setPosition(chicago);
  coordInfoWindow.open(map);

  map.addListener('zoom_changed', function() {
    coordInfoWindow.setContent(createInfoWindowContent(chicago, map.getZoom()));
    coordInfoWindow.open(map);
  });
}

var TILE_SIZE = 256;

function createInfoWindowContent(latLng, zoom) {
  var scale = 1 << zoom;

  var worldCoordinate = project(latLng);

  var pixelCoordinate = new google.maps.Point(
      Math.floor(worldCoordinate.x * scale),
      Math.floor(worldCoordinate.y * scale));

  var tileCoordinate = new google.maps.Point(
      Math.floor(worldCoordinate.x * scale / TILE_SIZE),
      Math.floor(worldCoordinate.y * scale / TILE_SIZE));

  return [
    'Chicago, IL',
    'LatLng: ' + latLng,
    'Zoom level: ' + zoom,
    'World Coordinate: ' + worldCoordinate,
    'Pixel Coordinate: ' + pixelCoordinate,
    'Tile Coordinate: ' + tileCoordinate
  ].join('<br>');
}

// The mapping between latitude, longitude and pixels is defined by the web
// mercator projection.
function project(latLng) {
  var siny = Math.sin(latLng.lat() * Math.PI / 180);

  // Truncating to 0.9999 effectively limits latitude to 89.189. This is
  // about a third of a tile past the edge of the world tile.
  siny = Math.min(Math.max(siny, -0.9999), 0.9999);

  return new google.maps.Point(
      TILE_SIZE * (0.5 + latLng.lng() / 360),
      TILE_SIZE * (0.5 - Math.log((1 + siny) / (1 - siny)) / (4 * Math.PI)));
}

    </script>
    <script async defer
         src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4sfBxZ2suUqnFku0B-L3q4xvA-hlAqSU&signed_in=true&callback=initMap">
    </script>

</body>
</html>