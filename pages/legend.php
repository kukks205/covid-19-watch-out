<!DOCTYPE html>
<html>
<head>
  <title>ol-ext: control Legends</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- jQuery -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
  <!-- Openlayers -->
  <link rel="stylesheet" href="https://openlayers.org/en/latest/css/ol.css" />
  <script type="text/javascript" src="https://openlayers.org/en/latest/build/ol.js"></script>
  <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL,Object.assign"></script>

  <!-- ol-ext -->
  <link rel="stylesheet" href="../lib/ol-ext/ol-ext.css" />
  <script type="text/javascript" src="../lib/ol-ext/ol-ext.js"></script>



  <style>
  .ol-legend ul li.ol-title > div:last-child {
    position:absolute;
    left:0;
    right:0;
    top: 10px
  }
  </style>
</head>
<body >
   <!-- DIV pour la carte -->
<div id="map" style="width:600px; height:400px;"></div>

<script type="text/javascript">
  
  // Layers
  var layer = new ol.layer.Vector({ source: new ol.source.Vector({ url: '../geoJSON/41.json',format: new ol.format.GeoJSON()}) });
  var vectorLayer = new ol.layer.Vector({

source: new ol.source.Vector({ url: '../geoJSON/41.json',format: new ol.format.GeoJSON()}),

style: function (feature) {

    var cases = getText(feature.get('distcode'));

    if (cases > 200) {
        var color = 'rgba(34, 197, 247, 0.8)'
    } else if (cases > 100) {
        var color = 'rgba(54, 223, 32, 0.8)'
    } else {
        var color = 'rgba(255,255,0,0.8)'
    }


    var style = new ol.style.Style({
        fill: new ol.style.Fill({
            color: color
        }),
        stroke: new ol.style.Stroke({
            color: 'BLACK',
            width: 1
        }),
        text: new ol.style.Text({
            font: '1.3rem THSarabun,sans-serif',
            fill: new ol.style.Fill({
                color: '#000'
            }),
        })
    });
    var id = feature.get('id');

    //console.log(getText(feature.get('id')));
    style.getText().setText(feature.get('distname') + '\r\n (' + getText(feature.get(
        'distcode')) + ' คน)');
    return style;
}

});

    
  // The map
  var map = new ol.Map ({
    target: 'map',
    view: new ol.View ({
      zoom: 6,
      center: [166326, 5992663]
    }),
    layers: [layer]
  });

  // Define a new legend
  var legend = new ol.control.Legend({ 
    title: 'Legend',
    margin: 5,
    collapsed: false
  });
  map.addControl(legend);

  // Add a new one
  var legend2 = new ol.control.Legend({ 
    title: ' ',
    margin: 5,
    target: legend.element
  });
  map.addControl(legend2);

  var form = { Trianle:3, Square:4, Pentagon: 5, Hexagon: 6 };
  for (var i in form) {
    legend.addRow({ 
      title: i, 
      typeGeom: 'Point',
      style: new ol.style.Style({
        image: new ol.style.RegularShape({
          points: form[i],
          radius: 15,
          stroke: new ol.style.Stroke({ color: [255,128,0,1 ], width: 1.5 }),
          fill: new ol.style.Fill({ color: [255,255,0,.3 ]})
        })
      })
    });
    legend2.addRow({ 
      title: i, 
      typeGeom: 'Point',
      style: new ol.style.Style({
        image: new ol.style.RegularShape({
          points: form[i],
          radius: 15,
          radius2: 7,
          stroke: new ol.style.Stroke({ color: [0,128,255,1 ], width: 1.5 }),
          fill: new ol.style.Fill({ color: [0,255,255,.3 ]})
        })
      })
    });
  }


</script>
  
</body>
</html>
