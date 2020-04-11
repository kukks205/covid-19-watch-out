
<?php

$sql_getAll = "select
a.ampurcodefull,
a.ampurname,
(select count(distinct cid) as cc
from person_risk_group as p where district=a.ampurcodefull 
and hospcode in (select hoscode from chospital 
where concat(provcode,distcode)=a.ampurcodefull  and status=1  and hostype not in ('01','02','16')) 
and DATEDIFF(CURDATE(),date_arrival_home)<=14) as pw,

(select count(distinct cid) as cc
from person_risk_group as p where district=a.ampurcodefull 
and hospcode in (select hoscode from chospital 
where concat(provcode,distcode)=a.ampurcodefull  and status=1  and hostype not in ('01','02','16')) 
and DATEDIFF(CURDATE(),date_arrival_home)>14) as pq,

(select count(distinct cid) as cc
from person_risk_group as p where district=a.ampurcodefull 
and hospcode in (select hoscode from chospital 
where concat(provcode,distcode)=a.ampurcodefull  and status=1  and hostype not in ('01','02','16'))) as pall,

(select count(p.cid) as cc from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.contagion_country='Y' and p.district=a.ampurcodefull) as fcall,
(select count(p.cid) as cc from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.contagion_country='Y' and p.district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)<=14) as fcw,
(select count(p.cid) as cc from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.contagion_country='Y' and p.district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)>14) as fcq,

(select count(p.cid) as cc from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.risk_country='Y' and p.district=a.ampurcodefull) as frall,
(select count(p.cid) as cc from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.risk_country='Y' and p.district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)<=14) as frw,
(select count(p.cid) as cc from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.risk_country='Y' and p.district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)>14) as frq,

(select
count(p.cid) as cc
from person_risk_group as p
left join ccountry as c on c.country_id=p.from_country
where c.risk_country is null and c.contagion_country is null and p.from_country<>'764'  and p.district=a.ampurcodefull) as faall,
(select
count(p.cid) as cc
from person_risk_group as p
left join ccountry as c on c.country_id=p.from_country
where c.risk_country is null and c.contagion_country is null and p.from_country<>'764'  and p.district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)<=14) as faw,
(select
count(p.cid) as cc
from person_risk_group as p
left join ccountry as c on c.country_id=p.from_country
where c.risk_country is null and c.contagion_country is null and p.from_country<>'764'  and p.district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)>14) as faq,

(select
count(distinct p.cid) as cc
from person_risk_group as p
join cchangwat as c on c.changwatcode=p.from_prove
where c.risk_zone='Y' and p.from_country='764' and district=a.ampurcodefull ) as trall,
(select
count(distinct p.cid) as cc
from person_risk_group as p
join cchangwat as c on c.changwatcode=p.from_prove
where c.risk_zone='Y' and p.from_country='764' and district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)<=14) as trw,
(select
count(distinct p.cid) as cc
from person_risk_group as p
join cchangwat as c on c.changwatcode=p.from_prove
where c.risk_zone='Y' and p.from_country='764' and district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)>14) as trq,

(select
count(distinct p.cid) as cc
from person_risk_group as p
left join cchangwat as c on c.changwatcode=p.from_prove
where (c.risk_zone is null or c.risk_zone='' or c.risk_zone<>'Y') and p.from_country='764' and district=a.ampurcodefull ) as taall,
(select
count(distinct p.cid) as cc
from person_risk_group as p
left join cchangwat as c on c.changwatcode=p.from_prove
where (c.risk_zone is null or c.risk_zone='' or c.risk_zone<>'Y') and p.from_country='764' and district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)<=14 ) as taw,
(select
count(distinct p.cid) as cc
from person_risk_group as p
left join cchangwat as c on c.changwatcode=p.from_prove
where (c.risk_zone is null or c.risk_zone='' or c.risk_zone<>'Y') and p.from_country='764' and district=a.ampurcodefull and DATEDIFF(CURDATE(),date_arrival_home)>14 ) as taq
from campur as a
where a.changwatcode='$proveID' and flag_status='0' order by a.ampurcodefull ";

$title = $data->GetStringData("select concat('จังหวัด',changwatname) as cc from cchangwat where changwatcode='$proveID'  ");

$data_list = $db->query($sql_getAll, PDO::FETCH_OBJ);

?>
<div style="padding: 20px;height:100%">

<div class="clearfix">

<h2 class="float-left">
    <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-poll"
            style="font-size: 24px;"></i></button>

    <b>สรุปข้อมูลกลุ่มเสี่ยง <?=$title;?></b>
</h2>

<div>

<table class="table table-sm table-striped table-bordered table-hover">
        <thead class="thead-dark" align="center">
            <tr>
                <th class="align-middle" rowspan="3">ลำดับ</th>
                <th scope="col" class="align-middle" rowspan="3">อำเภอ</th>
                <th colspan="18">เดินทางเข้ามาจาก(คน)</th>

            </tr>
            <tr>
                <th colspan="3">เขตโรคติดต่ออันตราย<sup>1</sup></th>
                <th colspan="3">พื้นที่มีการระบาดต่อเนื่อง<sup>3</sup></th>
                <th colspan="3">ประเทศอื่นๆรวมไม่ระบุ</th>
                <th colspan="3">กรุงเทพ/ปริมณฑล<sup>4</sup></th>
                <th colspan="3">จังหวัดอื่นๆรวมไม่ระบุ</th>
                <th colspan="3">รวมทุกกลุ่ม</th>
            </tr>
            <tr>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>สะสม</th>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>สะสม</th>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>สะสม</th>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>สะสม</th>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>สะสม</th>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>สะสม</th>
            </tr>
        </thead>
        <tbody>







            <?php
$i=1;
$frw=0;
$frq=0;
$frall=0;
$faw=0;
$faq=0;
$faall=0;
$trw=0;
$trq=0;
$trall=0;
$taw=0;
$taq=0;
$taall=0;
$fcw=0;
$fcq=0;
$fcall=0;
$pw=0;
$pq=0;
$pall=0;

foreach ($data_list as $row) {

?>
            <tr>
                <td align="center"><?=$i?></td>
                <td><?=$row->ampurname?></td>
                <td align="center"><?=$row->fcw?></td>
                <td align="center"><?=$row->fcq?></td>
                <td align="center"><?=$row->fcall?></td>
                <td align="center"><?=$row->frw?></td>
                <td align="center"><?=$row->frq?></td>
                <td align="center"><?=$row->frall?></td>
                <td align="center"><?=$row->faw?></td>
                <td align="center"><?=$row->faq?></td>
                <td align="center"><?=$row->faall?></td>
                <td align="center"><?=$row->trw?></td>
                <td align="center"><?=$row->trq?></td>
                <td align="center"><?=$row->trall?></td>
                <td align="center"><?=$row->taw?></td>
                <td align="center"><?=$row->taq?></td>
                <td align="center"><?=$row->taall?></td>
                <td align="center"><?=$row->pw?></td>
                <td align="center"><?=$row->pq?></td>
                <td align="center"><?=$row->pall?></td>

            </tr>


            <?php

            $frw= $frw+$row->frw;
            $frq= $frq+$row->frq;
            $frall= $frall+$row->frall;
            $faw= $faw+$row->faw;
            $faq= $faq+$row->faq;
            $faall= $faall+$row->faall;
            $trw= $trw+$row->trw;
            $trq= $trq+$row->trq;
            $trall= $trall+$row->trall;
            $taw= $taw+$row->taw;
            $taq= $taq+$row->taq;
            $taall= $taall+$row->taall;
            $fcw= $fcw+$row->fcw;
            $fcq= $fcq+$row->fcq;
            $fcall= $fcall+$row->fcall;
            $pw=$pw+$row->pw;
            $pq=round($pq+$row->pq,0);
            $pall=$pall+$row->pall;
            $i=$i+1;
    }
?>
            <tr>
                <td align="center" colspan="2"><b>รวม</b></td>
                <td align="center"><?=$fcw?></td>
                <td align="center"><?=$fcq?></td>
                <td align="center"><?=$fcall?></td>

                <td align="center"><?=$frw?></td>
                <td align="center"><?=$frq?></td>
                <td align="center"><?=$frall?></td>
                <td align="center"><?=$faw?></td>
                <td align="center"><?=$faq?></td>
                <td align="center"><?=$faall?></td>
                <td align="center"><?=$trw?></td>
                <td align="center"><?=$trq?></td>
                <td align="center"><?=$trall?></td>
                <td align="center"><?=$taw?></td>
                <td align="center"><?=$taq?></td>
                <td align="center"><?=$taall?></td>
                <td align="center"><?=$pw?></td>
                <td align="center"><?=$pq?></td>
                <td align="center"><?=$pall?></td>

            </tr>

        </tbody>
    </table>
</div>
</div>


</div>