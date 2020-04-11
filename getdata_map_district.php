<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

if (!isset($_SESSION)) {
    session_start();
}

include 'includes/DBConn.php';
include 'includes/function.php';

$data = new dbClass();
$json_data = [];
if($_SESSION['logged']==true){

    $district = $_SESSION['district'];

$id=$_GET['id'];

    $sql="select
    'ประเทศเสี่ยง' as title,
    count(p.cid) as cc
    from person_risk_group as p
    join ccountry as c on c.country_id=p.from_country
    where c.risk_country='Y' and p.from_country<>'764' and p.subdistrict='$id' and hospcode in (select hoscode from chospital where concat(provcode,distcode)='$district' and status=1 and hostype in (18,13,08,07,06,05,03)) and DATEDIFF(CURDATE(),date_arrival_home)<=14
    union 
    select
    'ประเทศอื่นๆ' as title,
    count(p.cid) as cc
    from person_risk_group as p
    join ccountry as c on c.country_id=p.from_country
    where c.risk_country is null and p.from_country<>'764' and c.country_id<>'999'  and p.subdistrict='$id' and hospcode in (select hoscode from chospital where concat(provcode,distcode)='$district' and status=1 and hostype in (18,13,08,07,06,05,03)) and DATEDIFF(CURDATE(),date_arrival_home)<=14
    union
    select
    'ไม่ระบุประเทศ' as title,
    count(p.cid) as cc
    from person_risk_group as p
    left join ccountry as c on c.country_id=p.from_country
    where (c.country_id is null or c.country_id='999') and p.from_country<>'764' and p.subdistrict='$id' and hospcode in (select hoscode from chospital where concat(provcode,distcode)='$district' and status=1 and hostype in (18,13,08,07,06,05,03)) and DATEDIFF(CURDATE(),date_arrival_home)<=14
    union 
    select 
    'จังหวัดเสี่ยง'  as title,
    count(distinct p.cid) as cc
    from person_risk_group as p
    join cchangwat as c on c.changwatcode=p.from_prove
    where c.risk_zone='Y' and c.changwatcode<>'' and p.from_country='764' and p.subdistrict='$id' and hospcode in (select hoscode from chospital where concat(provcode,distcode)='$district' and status=1 and hostype in (18,13,08,07,06,05,03)) and DATEDIFF(CURDATE(),date_arrival_home)<=14 
    union 
    select 
    'จังหวัดอื่นๆ'  as title,
    count(distinct p.cid) as cc
    from person_risk_group as p
    join cchangwat as c on c.changwatcode=p.from_prove
    where (c.risk_zone is null or c.risk_zone='') and p.from_country='764'  and c.changwatcode<>'' and p.subdistrict='$id' and hospcode in (select hoscode from chospital where concat(provcode,distcode)='$district' and status=1 and hostype in (18,13,08,07,06,05,03)) and DATEDIFF(CURDATE(),date_arrival_home)<=14
    union 
    select 
    'ไม่ระบุจังหวัด'  as title,
    count(distinct p.cid) as cc
    from person_risk_group as p
    left join cchangwat as c on c.changwatcode=p.from_prove
    where p.from_country='764'  and (c.changwatcode is null or c.changwatcode='' or c.changwatcode='99' ) and p.subdistrict='$id' and hospcode in (select hoscode from chospital where concat(provcode,distcode)='$district' and status=1 and hostype in (18,13,08,07,06,05,03)) and DATEDIFF(CURDATE(),date_arrival_home)<=14";

$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);

}
?>