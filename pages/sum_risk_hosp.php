
<?php

$hospcode = $_GET['hospcode'];



$sql_getAll = "select
cv.villagecode,
cv.villagename as villname,
cv.villagecodefull,
h.hosname,
h.hoscode,
t.tambonname,
a.ampurname,
(select count(p.cid) as cc from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.risk_country='Y' and p.hospcode='$hospcode' and p.moo = cv.villagecodefull) as frall,
(select count(p.cid) as cc from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.risk_country='Y' and p.hospcode='$hospcode' and DATEDIFF(CURDATE(),date_arrival_home)<=14  and p.moo = cv.villagecodefull) as frw,
(select count(p.cid) as cc from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.risk_country='Y' and p.hospcode='$hospcode' and DATEDIFF(CURDATE(),date_arrival_home)>14  and p.moo = cv.villagecodefull) as frq,
(select
count(p.cid) as cc
from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.risk_country is null and p.from_country<>'764' and c.country_id<>'999' and p.hospcode='$hospcode'  and p.moo = cv.villagecodefull) as faall,
(select
count(p.cid) as cc
from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.risk_country is null and p.from_country<>'764' and c.country_id<>'999' and p.hospcode='$hospcode' and DATEDIFF(CURDATE(),date_arrival_home)<=14  and p.moo = cv.villagecodefull) as faw,
(select
count(p.cid) as cc
from person_risk_group as p
join ccountry as c on c.country_id=p.from_country
where c.risk_country is null and p.from_country<>'764' and c.country_id<>'999' and p.hospcode='$hospcode' and DATEDIFF(CURDATE(),date_arrival_home)>14  and p.moo = cv.villagecodefull) as faq,
(select
count(distinct p.cid) as cc
from person_risk_group as p
join cchangwat as c on c.changwatcode=p.from_prove
where c.risk_zone='Y' and p.hospcode='$hospcode'  and p.moo = cv.villagecodefull) as trall,
(select
count(distinct p.cid) as cc
from person_risk_group as p
join cchangwat as c on c.changwatcode=p.from_prove
where c.risk_zone='Y' and p.hospcode='$hospcode' and DATEDIFF(CURDATE(),date_arrival_home)<=14  and p.moo = cv.villagecodefull) as trw,
(select
count(distinct p.cid) as cc
from person_risk_group as p
join cchangwat as c on c.changwatcode=p.from_prove
where c.risk_zone='Y'  and p.hospcode='$hospcode' and DATEDIFF(CURDATE(),date_arrival_home)>14  and p.moo = cv.villagecodefull) as trq,
(select
count(distinct p.cid) as cc
from person_risk_group as p
join cchangwat as c on c.changwatcode=p.from_prove
where (c.risk_zone is null or c.risk_zone<>'' or c.risk_zone<>'Y') and p.from_country='764'  and p.hospcode='$hospcode'  and p.moo = cv.villagecodefull) as taall,
(select
count(distinct p.cid) as cc
from person_risk_group as p
join cchangwat as c on c.changwatcode=p.from_prove
where (c.risk_zone is null or c.risk_zone<>'' or c.risk_zone<>'Y') and p.from_country='764'  and p.hospcode='$hospcode' and DATEDIFF(CURDATE(),date_arrival_home)<=14   and p.moo = cv.villagecodefull) as taw,
(select
count(distinct p.cid) as cc
from person_risk_group as p
join cchangwat as c on c.changwatcode=p.from_prove
where (c.risk_zone is null or c.risk_zone<>'' or c.risk_zone<>'Y') and p.from_country='764'  and p.hospcode='$hospcode' and DATEDIFF(CURDATE(),date_arrival_home)>14  and p.moo = cv.villagecodefull) as taq
from village as v
join cvillage as cv on cv.villagecodefull=v.VID
join chospital as h on v.HOSPCODE=h.hoscode
join ctambon as t on t.tamboncodefull=cv.tamboncode
join campur as a on a.ampurcodefull=t.ampurcode
where h.hoscode='$hospcode' and cv.flag_status=0 order by v.VID";



$data_list = $db->query($sql_getAll, PDO::FETCH_OBJ);

?>
<div style="padding: 20px;height:100%">

<div class="clearfix">

<h2 class="float-left">
    <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-poll"
            style="font-size: 24px;"></i></button>

    <b><?=$hospname;?></b>
</h2>

<div>

<table class="table table-sm table-striped table-bordered table-hover">
        <thead class="thead-dark" align="center">
            <tr>
                <th class="align-middle" rowspan="2">หมู่</th>
                <th scope="col" class="align-middle" rowspan="2">villname</th>
                <th scope="col" class="align-middle" rowspan="2">ตำบล</th>
                <th scope="col" class="align-middle" rowspan="2">อำเภอ</th>
                <th colspan="3">เดินทางมาจากประเทศกลุ่มเสี่ยง</th>
                <th colspan="3">เดินทางมาจากประเทศอื่นๆ</th>
                <th colspan="3">กรุงเทพฯและปริมณฑล</th>
                <th colspan="3">ต่างจังหวัดอื่นๆ</th>
            </tr>
            <tr>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>รวม</th>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>รวม</th>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>รวม</th>
                <th>ยังเฝ้าระยัง</th>
                <th>เฝ้าระวังครบ</th>
                <th>รวม</th>
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
foreach ($data_list as $row) {

?>
            <tr>
                <td align="center"><?=$row->villagecode?></td>
                <th scope="row"><?=$row->villname;?></th>
                <td><?=$row->tambonname;?></td>
                <td><?=$row->ampurname?></td>
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
            $i=$i+1;
    }
?>
            <tr>
                <td align="center" colspan="4"><b>รวม</b></td>
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

            </tr>

        </tbody>
    </table>
</div>
</div>


</div>