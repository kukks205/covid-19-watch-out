<?php
if (!isset($_SESSION)) {
    session_start();
}


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

include 'includes/DBConn.php';
include 'includes/function.php';

$data = new dbClass();
$json_data = [];


        # ป้องกัน sql injection จาก $_GET
        foreach ($_GET as $key => $value) {
            $_GET[$key]=addslashes(strip_tags(trim($value)));
        }
          
        if ($_GET['id'] !='') { $_GET['id']=(int) $_GET['id']; }
                extract($_GET);


$cid = $_GET['cid'];

if($_SESSION['logged']==true){


$hospcode=$_SESSION['office'];


$sql="select CID as cid,concat(NAME,'  ',LNAME) as fullname,NATION as nation,SEX as sex,YEAR(CURDATE())-YEAR(BIRTH) as age,t.tambonname,concat('บ้าน',v.villagename,' ม.',VILLAGE) as villname,
h.HOUSE as address,concat(CHANGWAT,AMPUR,TAMBON,VILLAGE) as moo,concat(CHANGWAT,AMPUR,TAMBON) as subdistrict,
concat(CHANGWAT,AMPUR) as district,CHANGWAT as province,p.MOBILE as telphone,p.HOSPCODE as hospcode
from person  as p
join home as h on h.HID=p.HID and p.HOSPCODE=h.HOSPCODE
join ctambon as t on t.tamboncodefull = concat(CHANGWAT,AMPUR,TAMBON)
join cvillage as v on v.villagecodefull=concat(CHANGWAT,AMPUR,TAMBON,VILLAGE)
where p.cid='$cid' ";

$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);

}
?>