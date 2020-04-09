<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

include 'includes/DBConn.php';
include 'includes/function.php';

$fp = fopen('udon.json', 'w');

$data = new dbClass();
$json_data = [];



$id=$_GET['id'];


    $sql="select type,properties,geometry 
    from (select SUBSTRING_INDEX(SUBSTRING_INDEX(properties,',',1),',',-1) as cc,g.* from gis_villages as g) as mm
    where mm.cc like '{\"DOLACODE\":\"41%' ";

   // concat('{subdistcode:',l.TAMBON_IDN,',subdistname:',l.TAM_NAM_T,',distcode:',l.AMPHOE_IDN,',distname:',l.AMPHOE_T,',provecode:',l.PROV_CODE,',provename:',l.PROV_NAM_T,'}') as properties,



$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

//$txt = json_encode(array("type"=>"FeatureCollection","features"=>$json_data));
$txt = json_encode($json_data);

//fwrite($fp, json_encode($txt));
//fclose($fp);


print($txt);

?>

