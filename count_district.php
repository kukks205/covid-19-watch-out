<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

include 'includes/DBConn.php';
include 'includes/function.php';

$data = new dbClass();
$json_data = [];


$id=$_GET['id'];

$sql="select 'ยังไม่พ้นระยะ' as title,count(cid) as cc from person_risk_group as p where district='$id' and hospcode in (select hoscode from chospital where concat(provcode,distcode)='$id' and status=1 and hostype not in ('01','02','16')) and DATEDIFF(CURDATE(),date_arrival_home)<=14";

$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);


?>