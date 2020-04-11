<link rel="stylesheet" href="https://openlayers.org/en/v5.3.0/css/ol.css" type="text/css">
<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
<link rel="stylesheet" href="./lib/ol-ext/ol-ext.css" />
<script type="text/javascript" src="./lib/ol-ext/ol-ext.js"></script>
<link rel="stylesheet" href="lib/ol3-popup/src/ol3-popup.css" />
<script src="lib/ol3-popup/src/ol3-popup.js"></script>
<style>
    .map {
        height: 85%;
        /*width: 100%;*/
    }
    .ol-popup {
        position: absolute;
        background-color: white;
        -webkit-filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
        filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #cccccc;
        bottom: 12px;
        left: -50px;
        width: 320px;
        height: 260px;
    }

    .ol-popup:after,
    .ol-popup:before {
        top: 100%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
    }

    .ol-popup:after {
        border-top-color: white;
        border-width: 10px;
        left: 48px;
        margin-left: -10px;
    }

    .ol-popup:before {
        border-top-color: #cccccc;
        border-width: 11px;
        left: 48px;
        margin-left: -11px;
    }

    .ol-popup-closer {
        text-decoration: none;
        position: absolute;
        top: 2px;
        right: 8px;
    }

    .ol-popup-closer:after {
        content: "✖";
    }

    .ol-legend ul li.ol-title > div:last-child {
        position:absolute;
        left:0;
        right:0;
        top: 0px
    }
</style>
<?php

    $geojson_file = $_SESSION['province'];

    $alat = $data->GetStringData("select lat as cc from tambon_gis where AMP_ID ='$districtID' order by TAM_ID limit 1");
    $alng = $data->GetStringData("select lng as cc from tambon_gis where AMP_ID ='$districtID' order by TAM_ID limit 1");

?>

<div style="padding: 20px;height:100%">
    <h2>
        <button type="button" id="sidebarCollapse" class="btn btn-info"><i
                class="fas fa-exclamation-triangle"></i></button>
        <b>แผนที่แสดงจำนวนกลุ่มเสี่ยงที่ยังเฝ้าระวัง</b>
    </h2>
    <hr>
    <div id="map" class="map"></div>

</div>



<script>
    var vectorLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
            url: './geoJSON/'+<?=$geojson_file?>+'.json',
            format: new ol.format.GeoJSON()
        }),
        style: function (feature) {
            var cases = getText(feature.get('distcode'));
            if (cases > 200) {
                var color = 'rgba(34, 197, 247, 0.8)'
            } else if (cases > 100) {
                var color = 'rgba(249, 255, 71, 0.8)'
            } else {
                var color = 'rgba(110, 232, 69, 0.8)'
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
            style.getText().setText(feature.get('distname') + '\r\n (' + getText(feature.get(
                'distcode')) + ' คน)');
            return style;
        }
    });


    function getText(id) {
        var id = id;
        var cc = 0;
        $.ajax({
            url: "count_district.php",
            global: false,
            async: false,
            type: "GET",
            dataType: 'json',
            data: ({
                id: id
            }),
            success: function (data) {
                cc = data[0].cc;
            },
            error: function () {
                cc = 0;
            }
        });
        return cc;
    }

    var map = new ol.Map({
        layers: [new ol.layer.Tile({
                source: new ol.source.OSM()
            }),
            vectorLayer
        ],
        target: 'map',
        view: new ol.View({
            center: ol.proj.fromLonLat([<?=$alng?>, <?=$alat?>]),
            zoom: 10
        })
    });

    var highlightStyle = new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'BLACK',
            width: 1
        }),
        fill: new ol.style.Fill({
            color: 'rgba(3, 7, 7, 0.3)'
        }),
        text: new ol.style.Text({
            font: '12px Calibri,sans-serif',
            fill: new ol.style.Fill({
                color: 'BLACK'
            }),
            stroke: new ol.style.Stroke({
                color: 'BLACK',
                width: 3
            })
        })
    });


    var featureOverlay = new ol.layer.Vector({
        source: new ol.source.Vector(),
        map: map,
        style: function (feature) {
            //highlightStyle.getText().setText(feature.get('name'));
            return highlightStyle;
        }
    });



    var highlight;
    var displayFeatureInfo = function (pixel) {
        var feature = map.forEachFeatureAtPixel(pixel, function (feature) {
            return feature;
        });

        var info = document.getElementById('info');
        if (feature) {
            //info.innerHTML = '<b>' + feature.get('id') + ': ' + feature.get('name') + '</b>';
        } else {
            //info.innerHTML = '<strong>ข้อมูลรายละเอียดในพื้นที่ (ตำบล)</strong>';
        }

        if (feature !== highlight) {
            if (highlight) {
                featureOverlay.getSource().removeFeature(highlight);
            }
            if (feature) {
                featureOverlay.getSource().addFeature(feature);
            }
            highlight = feature;
        }

    };

  /* Preload images */
  var listenerKey = vectorSource.on('change', function(e) {
    if (vectorSource.getState() == 'ready') {
      ol.Observable.unByKey(listenerKey);
      var f = vectorSource.getFeatures();
      for (var i=0; i<f.length; i++) {
        var img = new Image();
        img.src = f[i].get("img");
      }
    }
  });
  /**/
  // Control Select 
  var select = new ol.interaction.Select({});
  map.addInteraction(select);

  // On selected => show/hide popup
  select.getFeatures().on(['add'], function(e) {
    var feature = e.element;
    var content = "";
    content += "<img src='"+feature.get("img")+"'/>";
    content += feature.get("text");
    popup.show(feature.getGeometry().getCoordinates(), content); 
  })
  select.getFeatures().on(['remove'], function(e) {
    popup.hide(); 
  })

/*

    var popup = new ol.Overlay.Popup();
    map.addOverlay(popup);
*/
    map.on('pointermove', function (evt) {
        if (evt.dragging) {
            return;
        }
        var pixel = map.getEventPixel(evt.originalEvent);
        displayFeatureInfo(pixel);
    });

    map.on('click', function (evt) {
        //var pixel = map.getEventPixel(evt.originalEvent);
        var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {
            return feature;
        });
        //displayFeatureInfo(evt.pixel);
        var prettyCoord = ol.coordinate.toStringHDMS(ol.proj.transform(evt.coordinate, 'EPSG:3857',
            'EPSG:4326'), 2);
        var mydata;

        var id = feature.get('distcode');

        $.ajax({
            url: "getdata_map_prove.php",
            global: false,
            type: "GET",
            data: ({
                id: id
            }),
            success: function (data) {
                var mydata = JSON.parse(data);
                var str = '';
                Object.keys(mydata).forEach(key => {
                    str = str + mydata[key]['title'] + ' จำนวน ' + mydata[key]['p_intime'] +
                        ' คน<br>';
                    //str = str + str2; // key - value
                })
                /*
                popup.show(evt.coordinate,
                    '<div class="row"><div class="col-md-6"><b> อ.' + feature.get('distname') + '</div><div class="col-md-6"><a href="index.php?url=pages/sum_risk_dist.php&distcode='+feature.get('distcode')+'" class="btn btn-sm btn-info">รายละเอียด</a></b></div> </div>' +
                    '<div class="line"></div><b>' + str + '</b>');*/
            }
        });
    });


    
        // Define a new legend
        var legend = new ol.control.Legend({ 
            title: 'คำอธิบาย',
            margin: 10,
            collapsed: false
        });
        map.addControl(legend);

    legend.addRow({ 
        title: 'จำนวนกลุ่มเสี่ยง 0 - 100 คน', 
        typeGeom: 'Point',
        style: new ol.style.Style({
            image: new ol.style.RegularShape({
            points: 4,
            radius: 20,
            angle: Math.PI / 4,
            stroke: new ol.style.Stroke({ color: [255,128,0,1 ], width: 1.5 }),
            fill: new ol.style.Fill({ color: 'rgba(110, 232, 69, 0.8)'})
            })
        })
        });
    legend.addRow({ 
        title: 'จำนวนกลุ่มเสี่ยง 101 - 200 คน', 
        typeGeom: 'Point',
        style: new ol.style.Style({
            image: new ol.style.RegularShape({
            points: 4,
            radius: 20,
            angle: Math.PI / 4,
            stroke: new ol.style.Stroke({ color: [255,128,0,1 ], width: 1.5 }),
            fill: new ol.style.Fill({ color: 'rgba(249, 255, 71, 0.8)'})
           
            })
        })
        });

    legend.addRow({ 
        title: 'จำนวนกลุ่มเสี่ยง มากกว่า 200 คน', 
        typeGeom: 'Point',
        style: new ol.style.Style({
            image: new ol.style.RegularShape({
            points: 4,
            radius: 20,
            angle: Math.PI / 4,
            stroke: new ol.style.Stroke({ color: [255,128,0,1 ], width: 1.5 }),
            fill: new ol.style.Fill({ color:'rgba(34, 197, 247, 0.8)'})
            })
        })
        });


</script>
