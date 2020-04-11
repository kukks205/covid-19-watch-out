<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

include 'includes/DBConn.php';
include 'includes/function.php';

if (!isset($_SESSION)) {
    session_start();
}



if($_SESSION['logged']==true){

$data = new dbClass();
$json_data = [];

$proveID= $_SESSION['province'];
$ampID=  $_SESSION['district'];



$cid=$_GET["cid"];



$sql_remove_user ="delete from person_risk_group where  cid='$cid'";
$rmuser = $db->prepare($sql_remove_user);
$rmuser->execute();
$rmcount = $rmuser->rowCount();
if($rmcount>0){
    $json_data = ["status"=>"ok","num"=>$rmcount];
}else{
    $json_data = ["status"=>"err","num"=>$cid];
}
$txt = json_encode($json_data);
print($txt);

}
?>