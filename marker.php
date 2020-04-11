<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v5.3.0/css/ol.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
    <style>
      html, body {
        height: 100%;
        width: 100%;
        padding: 0px;
        margin: 0px;
      } 
      .map {
        height: 100%;
        width: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map" class="map"></div>
    <script>
      var features = [];
      features.push(coloredSvgMarker([103.022932, 17.320032]));
      features.push(coloredSvgMarker([103.023243,17.319025]));
      features.push(coloredSvgMarker([103.022843,17.316805]));

      
      var vectorSource = new ol.source.Vector({ // VectorSource({
        features: features
      });

      var vectorLayer = new ol.layer.Vector({ // VectorLayer({
        source: vectorSource
      });

      var map = new ol.Map({
        layers: [
          new ol.layer.Tile({ // TileLayer({
            source: new ol.source.OSM()
          }), vectorLayer
        ],
        target: 'map',
        
        view: new ol.View({
          center: ol.proj.fromLonLat([103.023243,17.319025]),
          zoom: 18
        })
      });
      function coloredSvgMarker(lonLat, color, circleFill) {
         if (!color) color = 'red';
         if (!circleFill) circleFill = 'white';
         var feature = new ol.Feature({
           geometry: new ol.geom.Point(ol.proj.fromLonLat(lonLat))
         });
        var svg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve">'+    
                '<path fill="'+color+'" d="M22.906,10.438c0,4.367-6.281,14.312-7.906,17.031c-1.719-2.75-7.906-12.665-7.906-17.031S10.634,2.531,15,2.531S22.906,6.071,22.906,10.438z"/>'+
                '<circle fill="'+circleFill+'" cx="15" cy="10.677" r="3.291"/></svg>';
         feature.setStyle(
           new ol.style.Style({
             image: new ol.style.Icon({
               anchor: [0.5, 1.0],
               anchorXUnits: 'fraction',
               anchorYUnits: 'fraction',
                src: 'data:image/svg+xml,' + escape(svg),
               scale: 2,
               imgSize: [30,30],
             })
           })
         );
         return feature;
      }
    </script>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"> 
</script> 
<script type="text/javascript"> 
_uacct = "UA-162157-1";
urchinTracker();
</script> 
  </body>
</html>
