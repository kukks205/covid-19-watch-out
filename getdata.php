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

$ID=$_GET['ID'];
$type=$_GET['TYPE'];

if($type=='province'){
    //$sql="select * from cchangwat where changwatcode<>'' order by changwatname";
    $sql="select * from cchangwat where changwatcode = '$proveID'   order by changwatname";//
}else if($type=='district') {
    //$sql="select * from campur where changwatcode='".$ID."' and flag_status=0";
    $sql="select * from campur where ampurcodefull='$ampID' and flag_status=0
    union
    select * from campur where changwatcode='$proveID' and flag_status=0 and ampurcodefull<>'$ampID'";
} else if($type=='subdistrict'){
    $sql="select * from ctambon where  flag_status=0 and ampurcode='".$ID."' and flag_status=0";
}else if($type=='villages'){
    $sql="select concat(villagecode,' บ้าน',villagename) as moo,villagecodefull from cvillage where tamboncode='".$ID."'";
}else if($type=='hospital'){
    $sql="select * from (select hoscode,concat('[',hoscode,'] ',hosname) as  hospname,
    concat(provcode,distcode) as districtcode,
    concat(provcode,distcode,subdistcode) as subdistrictcode
    from chospital where hostype in (18,13,08,07,06,05,03) and status=1) as  hos
    where districtcode='$ID'";
}else if($type=='place'){
    $sql = "SELECT * from core_riskplace  where province_id='$ID' or province_id is null order by id";
}



$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);

}
?>