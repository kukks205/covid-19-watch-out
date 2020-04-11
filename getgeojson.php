<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

include 'includes/DBConn.php';
include 'includes/function.php';

$data = new dbClass();
$json_data = [];


$id=$_GET['id'];
    $sql="select * from ( select 'กลุ่มเสี่ยงสะสม' as title,count(cid) as cc from person_risk_group as p where subdistrict='$id'
    union
    select 'ยังไม่พ้นระยะ' as title,count(cid) as cc from person_risk_group as p where subdistrict='$id' and DATEDIFF(CURDATE(),date_arrival)<=14
    union
    select 'พ้นระยะ 14 วัน' as title,count(cid) as cc from person_risk_group as p where subdistrict='$id' and DATEDIFF(CURDATE(),date_arrival)>14
    union
    select 'เข้าเกณฑ์ตรวจหาเชื้อ' as title,count(cid) as cc from person_risk_group as p where subdistrict='$id' and ((fever_date<>'' and fever_date<>'0000-00-00') or (symptom_ari_date<>'' and  symptom_ari_date<>'0000-00-00'))) as  mm";

$obj = $db->query($sql, PDO::FETCH_OBJ);

foreach ($obj as $k) {
		array_push($json_data, $k);
}

$txt = json_encode($json_data);
print($txt);


?>