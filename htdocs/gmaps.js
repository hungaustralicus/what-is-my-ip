var city_lat = 0;
var city_lng = 0;
var city_name = "";
var region_name = "";
var country_name = "";
var ip_address = "";
var host_name = "";
function phpValues(city_lat, city_lng, city_name, region_name, country_name, ip_address, host_name){
    window.city_lat = city_lat;
    window.city_lng = city_lng;
    window.city_name = city_name;
    window.region_name = region_name;
    window.country_name = country_name;
    window.ip_address = ip_address;
    window.host_name = host_name;
}

function initMap() {
    document.write("Gmap script is running!<br>");
    var ipcity = new google.maps.LatLng(city_lat, city_lng);

    var map = new google.maps.Map(document.getElementById('map'), {
        center: ipcity,
        zoom: 7
    });

    var coordInfoWindow = new google.maps.InfoWindow();
    coordInfoWindow.setContent(createInfoWindowContent(ipcity, map.getZoom()));
    coordInfoWindow.setPosition(ipcity);
    coordInfoWindow.open(map);

    map.addListener('zoom_changed', function() {
        coordInfoWindow.setContent(createInfoWindowContent(ipcity, map.getZoom()));
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
        'IP address: <b>' + ip_address + '</b>', 
        'Hosted by:',
        host_name,
        city_name,
        region_name,
        country_name
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


