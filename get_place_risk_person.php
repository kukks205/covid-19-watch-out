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

if($_SESSION['logged']==true){


$hospcode=$_SESSION['office'];


$sql="select
pr.cid,pr.fullname,pr.nation,n.nationname,pr.sex,v.villagename,v.villagecode,rc.name as place_name,
pr.age,pr.address,pr.moo,pr.subdistrict,
t.tambonname,pr.district,a.ampurname,pr.province,
c.changwatname,pr.telphone,pr.hospcode,hos.hosname,
pr.from_country,
pr.from_city,pr.date_arrival,pr.date_arrival_home,pr.from_prove,c2.changwatname as from_prove_name,
ADDDATE(pr.date_arrival_home,INTERVAL 14 DAY) as date_quarantine,
pr.fever_date,pr.symptom_ari_date,pr.refer_date,pr.culture_test_result,record_date
from person_risk_group as pr 
join ctambon as t on pr.subdistrict=t.tamboncodefull
join campur as a on a.ampurcodefull=t.ampurcode
join cchangwat as c on c.changwatcode=a.changwatcode
join cvillage as v on v.villagecodefull=pr.moo
join chospital as hos on hos.hoscode=pr.hospcode
join cnation as n on n.nationcode=pr.nation
left join cchangwat as c2 on c2.changwatcode=pr.from_prove
left join core_riskplace as rc on rc.id=pr.risk_place_id
where pr.hospcode='$hospcode' and from_country='764' and from_prove in (select changwatcode from cchangwat where risk_zone='Y') order by pr.date_arrival_home";

$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);

}
?>