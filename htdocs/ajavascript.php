<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <div id="map"></div>
        <?php 
            $city_lat = 1000;
            $city_lng = 200;
            $city_name = "Nagykanizsa";
            $region = "Zala";
            $country = "Pannónia";
            $client_ip_address = "1.1.1.1";
            $client_host_name = "city.of.ryde";
        ?>
        <script src="gmaps.js"></script>
        <script>
            document.write("javascript start <br>");
            function variableToTest(city1, city2) {
               document.write(city1 + " " + city2); 
            }
            phpValues(<?php echo $city_lat; ?>,
                <?php echo $city_lng; ?>,
                "<?php echo $city_name; ?>",
                "<?php echo $region; ?>",
                "<?php echo $country; ?>",
                "<?php echo $client_ip_address; ?>",
                "<?php echo $client_host_name; ?>");
            initWelcomeText();
            
        </script>
        
            
        
    </body>
</html>