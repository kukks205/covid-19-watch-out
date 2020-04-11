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

$sql = "select
pr.cid,
pr.fullname,
concat('เลขที่ ',pr.address,' ม.',right(pr.moo,2),' ต.',t.tambonname,' อ.',a.ampurname) as addr,
pr.district,
c.name_th,
pr.from_country,
if(pr.from_country=764,c2.changwatname,pr.from_city) as city,
pr.from_prove,
pr.date_arrival_home,
pr.date_quarantine,
pr.fever_date,
pr.risk_type_id,
r.risk_type_name,
if(CURDATE()>DATE_ADD(pr.date_arrival_home,INTERVAL 14 Day),'Q','N') as quarantine,
pr.lat,
pr.lng
from person_risk_group as pr
join ctambon as t on t.tamboncodefull=pr.subdistrict
join campur as a on a.ampurcodefull=t.ampurcode
join ccountry as c on c.country_id=pr.from_country
left join cchangwat as c2 on c2.changwatcode = pr.from_prove
left join risk_group_type as r  on r.risk_type_id=pr.risk_type_id
where lat is not null and lat<>'' and ceil(pr.lat)>0 and LENGTH(pr.lat)>8
";


$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);

}
?>