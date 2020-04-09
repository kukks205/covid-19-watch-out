<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

include 'includes/DBConn.php';
include 'includes/function.php';

if (!isset($_SESSION)) {
    session_start();
}


$data = new dbClass();
$json_data = [];

if($_SESSION['logged']==true){
$id=$_GET['id'];
$district = $_SESSION['district'];

$sql="select 'ยังไม่พ้นระยะ' as title,count(cid) as cc from person_risk_group as p where subdistrict='$id' and hospcode in (select hoscode from chospital where concat(provcode,distcode)='$district' and status=1 and hostype in (18,13,08,07,06,05,03)) and DATEDIFF(CURDATE(),date_arrival)<=14";

$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);

}
?>