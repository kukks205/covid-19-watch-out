<link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
<script src="./lib/leaflet.ajax.min.js"></script>

<style>
    #map {
        width: 100%;
        height:75%;
    }


    /*Legend specific*/
    .legend {
    padding: 6px 8px;
    font: 14px Arial, Helvetica, sans-serif;
    background: white;
    background: rgba(255, 255, 255, 0.8);
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    line-height: 24px;
    color: #000000;
    }
    .legend h4 {
    text-align: center;
    font-size: 16px;
    margin: 2px 12px 8px;
    color: #000000;
    }

    .legend span {
    position: relative;
    bottom: 3px;
    }

    .legend i {
    width: 18px;
    height: 18px;
    float: left;
    margin: 0 8px 0 0;
    opacity: 0.7;
    }

    .legend i.icon {
    background-size: 18px;
    background-color: rgba(255, 255, 255, 1);
    }

</style>
<div style="padding: 20px;height:100%">
    <h2>
        <button type="button" id="sidebarCollapse" class="btn btn-info"><i
                class="fas fa-exclamation-triangle"></i></button>
        <b>แผนที่แสดงหลังคาเรือนกลุ่มเสี่ยง</b>
    </h2>
    <hr>
    <div id="map" class="map"></div>

</div>

<?php   

    $geojson_file = $_SESSION['province'];

    $alat = $data->GetStringData("select lat as cc from tambon_gis where AMP_ID ='$districtID' order by TAM_ID limit 1");
    $alng = $data->GetStringData("select lng as cc from tambon_gis where AMP_ID ='$districtID' order by TAM_ID limit 1");

?>

<script>

    
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


    var map = L.map('map').setView([<?=$alat?>,<?=$alng?>], 10);

    L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; COVID-19 Watch out &  OpenStreetMap contributors'
    }).addTo(map);

    var LeafIcon = L.Icon.extend({
        options: {
            //shadowUrl: 'leaf-shadow.png',
            iconSize: [38, 42],
            shadowSize: [50, 64],
            iconAnchor: [22, 94],
            shadowAnchor: [4, 62],
            popupAnchor: [-3, -76]
        }
    });

    function onEachFeature(feature, layer) {
		var popupContent ="<h4>อ. "+ feature.properties.distname+"</h4>";

		layer.bindPopup(popupContent);


        var center = layer.getBounds().getCenter();
        console.log(center);
        var nlat = center.lat-0.0018;
        var nlng = center.lng+0.095;

        var label = L.marker([nlat,nlng], {
            icon: L.divIcon({
                className: 'header',
                html: '<p style="color:white;font-size:24px;">อ.'+feature.properties.distname+'</p>',
                iconSize: [180,30],
                direction:'left',
                offset: [0, 0],
                //iconAnchor: [105,14],
	           // labelAnchor: [6, 0] 
            })
        }).addTo(map);

	}

    var url ='./geoJSON/'+<?=$geojson_file?>+'.json';


    var geojsonLayer = new L.GeoJSON.AJAX(url,{

        

        style: function (feature) {

            var cases = getText(feature.properties.distcode);

            if (cases > 200) {
                var color = 'rgba(34, 197, 247, 1.0)';
            } else if (cases > 100) {
                var color = 'rgba(249, 255, 71, 1.0)';
            } else {
                var color = 'rgba(110, 232, 69, 1.0)';
            }

            var style = {
                weight: 2,
                color: '#000',
                opacity: 2,
                fillColor: color,
                fillOpacity: 0.8
            }

            return style;
        },
        onEachFeature: onEachFeature,

    });       
    
    geojsonLayer.addTo(map);


        var greenIcon = new LeafIcon({
            iconUrl: './img/light-green.png'
        }),
        redIcon = new LeafIcon({
            iconUrl: './img/pin.png'
        });

        $.ajax({
            url: "lmap_data.php",
            global: false,
            type: "GET",
            success: function (data) {
                var mydata = JSON.parse(data);
                var str = '';
                Object.keys(mydata).forEach(key => {
                    var icon;
                    if(mydata[key]['quarantine']=='Q'){
                        icon =greenIcon; 
                    }else{
                        icon =redIcon; 
                    }

                    var msg = "<b>ข้อมูลกลุ่มเสี่ยง</b><br>"+mydata[key]['fullname']+"<br>ที่อยู่ "+mydata[key]['addr']+"<br>มาจากประเทศ"+mydata[key]['name_th']+
                    "<br>เมือง "+mydata[key]['city']+"<br>ถึงบ้านวันที่ "+mydata[key]['date_arrival_home']

                    L.marker([mydata[key]['lat'] ,mydata[key]['lng'] ], {
                        icon: icon
                    }).bindPopup(msg).addTo(map);

                })

            }
        });

        var legend = L.control({ position: "bottomleft" });

        legend.onAdd = function(map) {
        var div = L.DomUtil.create("div", "legend");
        div.innerHTML += "<h4><b>คำอธิบายสัญลักษณ์</b></h4>";
        div.innerHTML += "<hr>";
        div.innerHTML += '<img src="./img/green_pin.png" width="32px"><span>  ครบ 14 วัน</span><br>';
        div.innerHTML += "<br>";
        div.innerHTML += '<img src="./img/pin.png" width="32px"><span> ยังไม่ครบ 14 วัน</span><br>';
    
            return div;
        };

        legend.addTo(map);
</script>