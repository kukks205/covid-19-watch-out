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

$mydistrict=$_SESSION['district'];

$postData = json_decode( file_get_contents("php://input") );

$slected_hosp = addslashes(strip_tags(trim($postData->hospcode)));

if($_SESSION['logged']==true){

if(strlen($slected_hosp)==5){
    $sql="select
    pr.cid,pr.fullname,pr.nation,n.nationname,pr.sex,
    pr.age,pr.address,v.villagecode as mooban,pr.subdistrict,
    t.tambonname,pr.district,a.ampurname,pr.province,r.risk_type_name,
    c.changwatname,pr.telphone,pr.hospcode,hos.hosname,
    pr.from_country,n2.name_th as countryname,c2.changwatname as from_prov_name,
    pr.from_city,pr.date_arrival,pr.date_arrival_home,
    ADDDATE(pr.date_arrival,INTERVAL 14 DAY) as date_quarantine,
    pr.fever_date,pr.symptom_ari_date,pr.refer_date,pr.culture_test_result,record_date
    from person_risk_group as pr 
    join ctambon as t on pr.subdistrict=t.tamboncodefull
    join campur as a on a.ampurcodefull=t.ampurcode
    join cchangwat as c on c.changwatcode=a.changwatcode
    join chospital as hos on hos.hoscode=pr.hospcode
    left join cnation as n on n.nationcode=pr.nation
    left join ccountry as n2 on n2.country_id=pr.from_country 
    join cvillage as v on v.villagecodefull=pr.moo
    left join cchangwat as c2 on c2.changwatcode=pr.from_prove
    left join risk_group_type as r on r.risk_type_id=pr.risk_type_id
    where pr.hospcode='$slected_hosp' order by pr.date_arrival";

}else{

    $sql="select
    pr.cid,pr.fullname,pr.nation,n.nationname,pr.sex,
    pr.age,pr.address,v.villagecode as mooban,pr.subdistrict,
    t.tambonname,pr.district,a.ampurname,pr.province,r.risk_type_name,
    c.changwatname,pr.telphone,pr.hospcode,hos.hosname,
    pr.from_country,n2.name_th as countryname,c2.changwatname as from_prov_name,
    pr.from_city,pr.date_arrival,pr.date_arrival_home,
    ADDDATE(pr.date_arrival,INTERVAL 14 DAY) as date_quarantine,
    pr.fever_date,pr.symptom_ari_date,pr.refer_date,pr.culture_test_result,record_date
    from person_risk_group as pr 
    join ctambon as t on pr.subdistrict=t.tamboncodefull
    join campur as a on a.ampurcodefull=t.ampurcode
    join cchangwat as c on c.changwatcode=a.changwatcode
    join chospital as hos on hos.hoscode=pr.hospcode
    left join cnation as n on n.nationcode=pr.nation
    left join ccountry as n2 on n2.country_id=pr.from_country 
    join cvillage as v on v.villagecodefull=pr.moo
    left join cchangwat as c2 on c2.changwatcode=pr.from_prove
    left join risk_group_type as r on r.risk_type_id=pr.risk_type_id
    where pr.district='$mydistrict' order by pr.date_arrival";
}




$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);

}
?>