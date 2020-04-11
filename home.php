<!DOCTYPE html>
<html>

<head>
  <title>Data Layer: GeoJSON</title>
  <meta name="viewport" content="initial-scale=1.0">
  <meta charset="utf-8">
  <style>
    /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
    #map {
      height: 100%;
    }

    /* Optional: Makes the sample page fill the window. */
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
</head>

<body>
  <div id="map"></div>
  <script>
    var map;


    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: {
          lat: 17.3466182,
          lng: 103.1534893
        }
      });



      // NOTE: This uses cross-domain XHR, and may not work on older browsers.
      map.data.loadGeoJson('./geo.json');

      map.data.setStyle(function (feature) {
        var name = feature.getProperty('name');
        if (name == '01') {
          var color = 'red'
        } else if (name == '02') {
          var color = 'yellow'
        } else if (name == '14') {
          var color = 'blue'
        } else if (name == '17') {
          var color = 'green'
        } else if (name == '11') {
          var color = 'pink'
        } else if (name == '06') {
          var color = 'GRAY'
        } else if (name == '07') {
          var color = 'TEAL'
        } else if (name == '18') {
          var color = '#800080'
        } else if (name == '09') {
          var color = 'AQUA'
        } else if (name == '10') {
          var color = 'OLIVE'
        } else if (name == '05') {
          var color = 'FUCHSIA'
        } else if (name == '12') {
          var color = '#FF1493'
        } else {
          var color = 'BLACK'
        }
        return {
          fillColor: color,
          fillOpacity: 0.2,
          strokeWeight: 1
        };
      });



      var bounds = new google.maps.LatLngBounds();
      map.data.forEach(function (feature) {
        if (feature.getGeometry().getType() === 'Polygon') {
          feature.getGeometry().forEachLatLng(function (latlng) {
            bounds.extend(latlng);
          });
        }
      });


      map.data.addListener('click', function (event) {

       // alert(JSON.stringify(event.feature.getGeometry()));
        event.feature.getGeometry().forEachLatLng(function(latlng){
          console.log(JSON.stringify(bounds.extend(latlng)));
        });
        // map.data.overrideStyle(event.feature, {
        //fillColor: 'red'
        //});

      });


    }
  </script>
  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDy43ptUO6cf9lQ1B4W9KEh39HaRmfa_go&callback=initMap">
  </script>
</body>

</html>