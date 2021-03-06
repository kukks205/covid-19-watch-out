<link rel="stylesheet" href="https://openlayers.org/en/v5.3.0/css/ol.css" type="text/css">
<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
<link rel="stylesheet" href="lib/ol3-popup/src/ol3-popup.css" />
<script src="lib/ol3-popup/src/ol3-popup.js"></script>
<style>
    .map {
        height: 90%;
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
</style>
<?php

    if($_SESSION['role']=='001'||$_SESSION['role']=='002'){

        $geojson_file = $_SESSION['province'];

    }else{

        $geojson_file = $_SESSION['district'];
    }

    $alat = $data->GetStringData("select lat as cc from tambon_gis where AMP_ID ='$districtID' order by TAM_ID limit 1");
    $alng = $data->GetStringData("select lng as cc from tambon_gis where AMP_ID ='$districtID' order by TAM_ID limit 1");

?>

<div style="padding: 20px;height:100%">
    <h2>
        <button type="button" id="sidebarCollapse" class="btn btn-info"><i
                class="fas fa-exclamation-triangle"></i></button>
        <b>ข้อมูลเฝ้าระวังผู้เดินทางจากพื้นที่เสี่ยง</b>
    </h2>
    <hr>
    <div id="map" class="map"></div>

</div>



<script>
    //info.innerHTML = '<strong>ข้อมูลรายละเอียดในพื้นที่ (ตำบล)</strong>';

    var vectorLayer = new ol.layer.Vector({

        source: new ol.source.Vector({
            url: './geoJSON/'+<?=$geojson_file?>+'.json',
            format: new ol.format.GeoJSON()
        }),

        style: function (feature) {

            var cases = getText(feature.get('subdistcode'));

            if (cases > 10) {
                var color = 'rgba(255,0,0,0.8)'
            } else if (cases > 4) {
                var color = 'rgba(246, 76, 14, 0.8)'
            } else if (cases >1) {
                var color = 'rgba(255,255,0,0.8)'
            } else {
                var color = 'rgba(54, 223, 32, 0.8)'
            }

            /*
                        if (name == '410601') {
                            var color = 'rgba(255,0,0,0.8)'
                        } else if (name == '410602') {
                            var color = 'rgba(255,255,0,0.8)'
                        } else if (name == '410614') {
                            var color = 'rgba(74, 123, 247, 0.8)'
                        } else if (name == '410617') {
                            var color = 'rgba(54, 223, 32, 0.8)'
                        } else if (name == '410611') {
                            var color = 'rgba(246, 76, 14, 0.8)'
                        } else if (name == '410606') {
                            var color = 'rgba(14, 227, 246, 0.8)'
                        } else if (name == '410607') {
                            var color = 'rgba(59, 97, 247, 0.8)'
                        } else if (name == '410618') {
                            var color = 'rgba(164, 74, 247, 0.8)'
                        } else if (name == '410609') {
                            var color = 'rgba(34, 197, 247, 0.8)'
                        } else if (name == '410610') {
                            var color = 'rgba(224, 46, 150, 0.8)'
                        } else if (name == '410605') {
                            var color = 'rgba(245, 10, 174, 0.8)'
                        } else if (name == '410612') {
                            var color = 'rgba(252, 54, 130, 0.8)'
                        } else {
                            var color = 'rgba(54, 223, 32, 0.8)'
                        }
            */


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
                    /* stroke: new ol.style.Stroke({
                       color: '#fff',
                       width: 3
                     })*/
                })
            });
            var id = feature.get('id');

            //console.log(getText(feature.get('id')));
            style.getText().setText(feature.get('subdistname') + '\r\n (' + getText(feature.get(
                'subdistcode')) + ' คน)');
            return style;
        }

    });


    function getText(id) {
        var id = id;
        var cc = 0;
        $.ajax({
            url: "count_watch.php",
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
            zoom: 11
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



    var popup = new ol.Overlay.Popup();

    map.addOverlay(popup);

    map.on('pointermove', function (evt) {
        if (evt.dragging) {
            return;
        }
        var pixel = map.getEventPixel(evt.originalEvent);
        displayFeatureInfo(pixel);
    });

    map.on('click', function (evt) {
        
        var pixel = map.getEventPixel(evt.originalEvent);
        
        var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {
            return feature;
        });



        //displayFeatureInfo(evt.pixel);
        var prettyCoord = ol.coordinate.toStringHDMS(ol.proj.transform(evt.coordinate, 'EPSG:3857','EPSG:4326'), 2);

        var mydata;
        var id = feature.get('subdistcode');
        $.ajax({
            url: "getgeojson.php",
            global: false,
            type: "GET",
            data: ({
                id: id
            }),
            success: function (data) {
                var mydata = JSON.parse(data);
                var str = '';
                Object.keys(mydata).forEach(key => {
                    var str2 = mydata[key]['title'] + ' จำนวน ' + mydata[key]['cc'] +
                        ' คน<br>';
                    str = str + str2; // key - value
                })
                popup.show(evt.coordinate,
                    '<b>' + feature.get('subdistname') + '</b>' +
                    '<div class="line"></div><b>' + str + '</b>');
            }
        });

        
    });
    
</script>

<?php


$sql ="select count(cid) as cc from person_risk_group where subdistrict='$distID'";
$riskall = $data->GetStringData($sql);

?>