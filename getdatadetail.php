<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

include 'includes/DBConn.php';
include 'includes/function.php';

$data = new dbClass();
$json_data = [];


$ID=$_GET['ID'];
$IDD=$_GET['IDD'];
$type=$_GET['TYPE'];

if($type=='province'){
    $sql="select * from cchangwat where changwatcode='$ID' union (select * from cchangwat where changwatcode<>'' order by changwatname)";
}else if($type=='district') {
    $sql="select * from campur where ampurcodefull='$IDD' and flag_status=0 union (select * from campur where changwatcode='".$ID."' and flag_status=0)";
} else if($type=='subdistrict'){
    $sql="select * from ctambon where tamboncodefull='$IDD' and flag_status=0 union (select * from ctambon where  flag_status=0 and ampurcode='$ID' and flag_status=0 order by tambonname)";
}else if($type=='villages'){
    $sql = "select concat(villagecode,' บ้าน',villagename) as moo,villagecodefull 
    from cvillage where villagecodefull='$IDD' and flag_status=0
    union
    (select concat(villagecode,' บ้าน',villagename) as moo,villagecodefull 
    from cvillage where tamboncode='$ID')";
    //$sql="select concat(villagecode,' บ้าน',villagename) as moo,villagecodefull from cvillage where tamboncode='".$ID."'";
}else if($type=='hospital'){
    $sql="select * from (select hoscode,concat('[',hoscode,'] ',hosname) as  hospname,
    concat(provcode,distcode) as districtcode,
    concat(provcode,distcode,subdistcode) as subdistrictcode
    from chospital where hostype in (18,07,05)) as  hos
    where hoscode='$IDD'
    union
    (select * from (select hoscode,concat('[',hoscode,'] ',hosname) as  hospname,
    concat(provcode,distcode) as districtcode,
    concat(provcode,distcode,subdistcode) as subdistrictcode
    from chospital where hostype in (18,07,05)) as  hos
    where subdistrictcode='$ID')";

}



$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);


?>