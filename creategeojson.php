<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

include 'includes/DBConn.php';
include 'includes/function.php';

$fp = fopen('udon.json', 'w');

$data = new dbClass();
$json_data = [];



$id=$_GET['id'];


    $sql="select 
    'Feature' as 'type' ,
    concat('{\"subdistcode\":\"',l.TAMBON_IDN,'\",\"subdistname\":\"',l.TAM_NAM_T,'\",\"distcode\":\"',l.AMPHOE_IDN,'\",\"distname\":\"',l.AMPHOE_T,'\",\"provecode\":\"',l.PROV_CODE,'\",\"provename\":\"',l.PROV_NAM_T,'\"}') as properties,
    l.geojson as 'geometry'
    from layer_map_coordinates as  l where l.AMPHOE_IDN='4106'  order by l.AMPHOE_IDN,l.TAMBON_IDN ";

   // concat('{subdistcode:',l.TAMBON_IDN,',subdistname:',l.TAM_NAM_T,',distcode:',l.AMPHOE_IDN,',distname:',l.AMPHOE_T,',provecode:',l.PROV_CODE,',provename:',l.PROV_NAM_T,'}') as properties,



$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode(array("type"=>"FeatureCollection","features"=>$json_data));

//fwrite($fp, json_encode($txt));
//fclose($fp);


print($txt);

?>

