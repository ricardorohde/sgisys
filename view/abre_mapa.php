<?php
if (isset($_GET['tipo_marker'])) {
    $tipo_marker = $_GET['tipo_marker'];
} else {
    $tipo_marker = '1';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <style type="text/css">
            html { height: 100% }
            body { height: 100%; margin: 0px; padding: 0px }
            #map_canvas { height: 100% }
        </style>
        <script type="text/javascript"
                src="https://maps.google.com/maps/api/js?sensor=false">
        </script>
        <script type="text/javascript">
            var geocoder;
            var map;
            var tipo_marker = '<?php echo $tipo_marker; ?>';

            function initialize() {
                geocoder = new google.maps.Geocoder();
                var latlng = new google.maps.LatLng(-34.397, 150.644);
                if (tipo_marker == '1') {
                    var myOptions = {
                        zoom: 16,
                        center: latlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    }
                } else {
                    var myOptions = {
                        zoom: 15,
                        center: latlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    }
                }
                map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

                codeAddress()
            }

            function codeAddress() {
                var address = document.getElementById("address").value;
                geocoder.geocode({'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        if (tipo_marker == '1') {
                            marker = new google.maps.Marker({
                                position: results[0].geometry.location,
                                map: map
                            });
                        } else {
                            marker = new google.maps.Circle({
                                strokeColor: "#1a75d3",
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: "#1a75d3",
                                fillOpacity: 0.35,
                                map: map,
                                radius: 500,
                                center: results[0].geometry.location
                            });
                        }
                    }
                });
            }
        </script>
    </head>
    <body onLoad="initialize()">
        <div id="map_canvas" style="width: 100%; height: 100%;"></div>
        <div>
            <input id="address" type="hidden" value="<?php echo $_GET['endereco']; ?>">
        </div>
    </body>
</html>
