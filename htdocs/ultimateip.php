<!DOCTYPE html>
<html lang="en-US">
    <head>
        <!-- Backward compatibility added -->
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js">
            </script>
        <![endif]-->
        <style>
            header, section, footer, aside, nav, main, article, figure {
                display: block;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="ipstyler.css">
        
        <meta charset="utf-8">
        <title>Ultimate IP shows your public IP address and its geolocation</title>
        <meta name="description" content="The ultimate IP address uses GeoBytes\
              for IP based geolocation. Weather is forecasted by Yahoo, \
              Wikipedia search powered by Bing, timezone identified by Google \
              and accurate UTC is provided by Insitute Galilei.">
       
    </head>

    <body>
        <div class="wrapper">
        <nav>
            <ul>
                <li>Info</li>
                <li>Geolocation</li>
                <li>Server and Network</li>
                <li>Browser and computer</li>
            </ul>
        </nav>
        <header>
            <?php
                $client_ip_address = $_SERVER['REMOTE_ADDR'];
                echo "<h1>Your public IP address is: " . $client_ip_address . 
                    "</h1>";
            ?>
        </header>

    
        <?php
            $hostname=gethostname();
            $serverip=gethostbyname($hostname);
            $client_ip_address = $_SERVER['REMOTE_ADDR'];
            $client_host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);

            // Geobytes search to identify location of IP
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
            getIP();

            if ($client_ip_address == '127.0.0.1') {
                $data["geobyteslatitude"] = "5140676.6570480205";
                $data["geobyteslongitude"] = "278290.41696358705";
                $data["geobytescity"] = "Dombóvár";
                $data["geobytesfqcn"] = "Hungary, Transdanubia, Dombóvár";
                $data["geobytescounty"] = "Hungary";
                $data["geobytesregion"] = "Tolna";
                $data["geobytestimezone"] = "+1.00";
            }

            $city_lat = $data["geobyteslatitude"];
            $city_lng = $data["geobyteslongitude"];
            $city_name = $data["geobytescity"];
            $weather_location = $data["geobytesfqcn"];
            // echo "Weather location: " . $weather_location . "<br>";
            include 'serverservice/finetime.php';
            $finetime = simpleunixtime();
            //echo "Fine time is:" . $finetime . "<br>";
            $json_datum = file_get_contents(
                'https://maps.googleapis.com/maps/api/timezone/json?location=' 
                . $data["geobyteslatitude"] . ','
                . $data["geobyteslongitude"]
                . '&timestamp='
                . $finetime
                . '&key=AIzaSyAUAvg6CEkPY5xtCuKV1yhTJUmPSkEhhpU');

            $data_datum = json_decode($json_datum, true);
            //echo "Offset dts:" . $data_datum["dstOffset"] . "<br>";
            //echo "Raw offset:" . $data_datum["rawOffset"] . "<br>";
            $localzoneshift = intval($data_datum["rawOffset"]) + intval($data_datum["dstOffset"]);
            //echo "alltogether:" . $localzoneshift . "<br>";
            $localdatetime = $finetime + intval($data_datum["rawOffset"]) 
                + intval($data_datum["dstOffset"]);
            //echo "server time:" . date('Y-m-d H:i:s', time()) . "<br>";
            //echo "time to be passed to js:" . $localdatetime . "<br>";
            //echo "GMT:" . date('Y-m-d H:i:s', $finetime) . "<br>";
            //echo "Local:" . date('Y m d H:i:s', $localdatetime) . "<br>";

            $acctKey = 'xMNfiNnNMzx4mliiZ5h+qy+3Cl2K1rhqBc/wAzaw1L4';
            $rootUri = 'https://api.datamarket.azure.com/Bing/SearchWeb/v1';

            $serviceOp = 'Web';
            $query = $data["geobytescountry"] . " wikipedia " . $weather_location;

            $query = urlencode("'" . $query . "'");
            $requestUri = "$rootUri/$serviceOp?\$format=json&\$top=1&Query=$query";
            $auth = base64_encode("$acctKey:$acctKey");

            $data_bing = array(
                'http' => array(
                    'request_fulluri' => true,
                    // ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
                    'ignore_errors' => true,
                    'header' => "Authorization: Basic $auth")
            );  
            $context = stream_context_create($data_bing);
            // Get the response from Bing.
            $response = file_get_contents($requestUri, 0, $context);
            // Decode the response.
            $jsonObj = json_decode($response);
            $resultStr = '';                
            // Parse each result according to its metadata type.
            foreach($jsonObj->d->results as $value) {
                $resultStr .= "{$value->Url}";
            }
            
            $contents = str_replace('en.wikipedia.org', 'en.m.wikipedia.org',
                $resultStr);
            // echo "contents:" . $contents;
        ?>

        <section id="mapsection">
            <div>
                <label>Geolocation powered by GeoBytes</label>
            </div>
            <article id="map"></article>
        </section>
        


        <section id="wiki">
            <div>
                <label>Locaton based wiki search provided by Bing!</label>
            </div>
            <article id="wikiframe">
                <iframe id="location_wiki" 
                    src="<?php echo $contents; ?>#content" 
                    width=100% height =100%>
                </iframe>
            </article>
        </section>


        <section>
            <div>
                <label>Location based server time set by Istituto Nazionale di Ricerca Metrologica</label>
            </div>
            <article class="time_weather" id="browserclock">
                <h2 class="digidate">Time on your computer:</h2>            
                <p id="browserTime" class="digiclock">00 : 00 : 00</p>
                <p id="browserDate" class="digidate"></p>                
            </article>
            <article class="time_weather" id="locationclock">
                <h2 class="digidate">Time in your time zone:</h2>            
                <p id="locationTime" class="digiclock">00 : 00 : 00</p>
                <p id="locationDate" class="digidate"></p>
            </article>
        </section>
        <section id="yweather">
            <div>
                <label>Location based weather info requested from Yahoo!</label>
            </div>
            <article class="time_weather" id="weather" >
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
                <script src="jquery.zweatherfeed.js"></script>
                <script>
                    var city_weather = "<?php echo $weather_location; ?>";
                    //document.write(city_weather);

                    $(document).ready(function () {
                        $('#weather').weatherfeed(city_weather, {
                        forecast: false
                        });
                    });
                </script>
            </article>

        </section>
        
        <section id="technical">
            <div>
                <label>Technical details</label>
            </div>
            <article id="server_details">
                <h3>Information from server request</h3>
                <h4>Your public IP address</h4>
                <p><?php echo $client_ip_address ?></p>
                <h4>Your ISP host</h4>
                <p><?php echo $client_host_name ?></p>
                <h4>User agent</h4>
                <p><?php echo $_SERVER['HTTP_USER_AGENT'] ?></p>
                <h4>Referer</h4>
                <p><?php echo $_SERVER['REQUEST_URI'] ?></p>
                <h4>This URL</h4>
                <p><?php echo $_SERVER['HTTP_HOST'] ?> alias <?php echo $serverip ?></p>
                <h4>This URI</h4>
                <p><?php echo $_SERVER['REQUEST_URI'] ?></p>
                <h4>Location details by GeoBytes</h4>
                <p>City: <?php echo $data["geobytescity"] ?>, 
                    Region: <?php echo $data["geobytesregion"] ?>, 
                    Country: <?php echo $data["geobytescountry"] ?><br>
                Latitude: <?php echo $data["geobyteslatitude"] ?>, 
                    Longitude: <?php echo $data["geobyteslongitude"] ?><br>
                Time zone: <?php echo $data["geobytestimezone"] ?></p>

            </article> 
            <article id="browser_detect">
                <h3>Information from your brower</h3>
                <h4>User agent</h4>
                <p id="user_agent"></p>
                <h4>Platform</h4>
                <p id="platform"></p>
                <h4>Screen resolution</h4>
                <p id="screen_resolution"></p>
                <h4>Available screen size</h4>
                <p id="available_screen_size"></p>
                <h4>Cookie enabled</h4>
                <p id="cookie_enabled"></p>
            </article>
            <article id="good_to_know">
                <p title="What is IP address?">An <abbr title="Internet Protocol">
                    IP</abbr> address is an identifier for devices on a 
                    <abbr title="Transmittion Control Protocol">TCP</abbr>/IP 
                    network. Typical examples for a TCP/IP network are the Internet
                    or a <abbr title="Local Area Network">LAN</abbr>. A 
                    <abbr title="Wireless Fidelity">WiFi</abbr> router hosted WLAN
                    is also a LAN network.
                </p>
                <p title="Local IP and Public IP">A computer on a local network must
                    have a local IP address. If such a computer connects to the 
                    Internet its public IP addrress is probably different from its
                    local IP address.
                </p>
            </article>
        </section>
            
        <div class="push"></div>
        
        </div>
            
         <script>
            document.getElementById("user_agent").innerHTML =  navigator.userAgent;
            document.getElementById("platform").innerHTML =  navigator.platform;
            document.getElementById("screen_resolution").innerHTML =  screen.width + "x" + screen.height;
            document.getElementById("available_screen_size").innerHTML =  screen.availWidth + "x" + screen.availHeight;
            document.getElementById("cookie_enabled").innerHTML =  navigator.cookieEnabled;
        </script>

        <script src="gmaps.js"></script>
        <script>
            phpValues(<?php echo $city_lat; ?>,
                <?php echo $city_lng; ?>,
                "<?php echo $city_name; ?>",
                '<?php echo $data["geobytesregion"]; ?>',
                '<?php echo $data["geobytescountry"]; ?>',
                "<?php echo $client_ip_address; ?>",
                "<?php echo $client_host_name; ?>");
        </script>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCm6xTC0ccerZdFpe48hhjwNGoR9OLHMgo&signed_in=true&callback=initMap">
        </script>

        <script src="clocks.js?version=1"></script>
        <script>
            var timeString = 0;
            timeString = "<?php echo date('Y m d H:i:s', $localdatetime); ?>";
            setClockTimeZone(timeString);
            setInterval(browserClock, 1000); 
            setInterval(locationClock, 1000);
        </script>
            <script>
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
              })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

              ga('create', 'UA-70669342-1', 'auto');
              ga('send', 'pageview');

            </script>      
    </body>
</html>