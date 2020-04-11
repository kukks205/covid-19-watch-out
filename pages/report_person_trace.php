<script type="text/javascript">
    $(function () {

        var d = new Date();
        $("#startdate").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: d
        });
        $("#enddate").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: d
        });

    });

    function resetValue(divID) {
        document.getElementById(divID).value = null;
    }
</script>
<?php


function toYdate($thDate){
    $e = explode("/",$thDate);
    $y=($e[2]);
    $cDate = $y.'-'.$e[1].'-'.$e[0];
    return $cDate;
}

function toBdate($yDate){

    $e = explode("-",$yDate);
    $y=($e[0]);
    $cDate = $e[2].'/'.$e[1].'/'.$y;
    return $cDate;

}




if($_POST['startdate']){

    $startdate = toYdate($_POST['startdate']);

}else{

    $startdate=date("Y-m-d");
}
if($_POST['enddate']){

    $enddate=toYdate($_POST['enddate']);

}else{

    $enddate=date("Y-m-d");
}

$sql_getAll = "select
a.ampurcodefull,
a.ampurname,
frn.cc as 'fr_normal',
fra.cc as 'fr_abnormal',
fr2n.cc as 'fr2_normal',
fr2a.cc as 'fr2_abnormal',
fon.cc as 'fon_normal',
foa.cc as 'fon_abnormal',
trn.cc as 'tr_normal',
tra.cc as 'tr_abnormal',
ton.cc as 'to_normal',
toa.cc as 'to_abnormal',
(if(frn.cc,frn.cc,0)+if(fra.cc,fra.cc,0)+if(fr2n.cc,fr2n.cc,0)+
if(fr2a.cc,fr2a.cc,0)+if(trn.cc,trn.cc,0)+if(tra.cc,tra.cc,0)+if(fon.cc,fon.cc,0)+if(foa.cc,foa.cc,0)+
if(ton.cc,ton.cc,0)+if(toa.cc,toa.cc,0)) as pp
from campur as a

left join (select 
pr.district,
count(distinct pr.cid) AS cc
from 
person_risk_group as pr 
join ccountry as c on c.country_id=pr.from_country
where c.contagion_country='Y'
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)<'2020' or year(pr.symptom_ari_date) is null) and (year(pr.fever_date)<2020 or year(pr.fever_date) is null)
group by pr.district) as frn on frn.district=a.ampurcodefull
left join (select 
pr.district,
count(distinct pr.cid) AS cc
from 
person_risk_group as pr 
join ccountry as c on c.country_id=pr.from_country
where  c.contagion_country='Y'
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)>='2020' and year(pr.symptom_ari_date) is not null) and (year(pr.fever_date)>=2020 and year(pr.fever_date) is not null)
group by pr.district
order by fever_date desc,symptom_ari_date desc) as fra on fra.district=a.ampurcodefull

left join (select 
pr.district,
count(distinct pr.cid) AS cc
from 
person_risk_group as pr 
join ccountry as c on c.country_id=pr.from_country
where c.risk_country is null and c.contagion_country is null
and c.country_id<>'764'
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)<'2020' or year(pr.symptom_ari_date) is null) and (year(pr.fever_date)<2020 or year(pr.fever_date) is null)
group by pr.district) as fon on fon.district=a.ampurcodefull


left join (select 
pr.district,
count(distinct pr.cid) AS cc
from 
person_risk_group as pr 
join ccountry as c on c.country_id=pr.from_country
where c.risk_country is null and c.contagion_country is null
and c.country_id<>'764'
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)>='2020' and year(pr.symptom_ari_date) is not null) and (year(pr.fever_date)>=2020 and year(pr.fever_date) is not null)
group by pr.district) as foa on foa.district=a.ampurcodefull

left join (select 
pr.district,
count(distinct pr.cid) AS cc
from 
person_risk_group as pr 
join ccountry as c on c.country_id=pr.from_country
where c.risk_country='Y' and (c.contagion_country is null or c.contagion_country='')
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)<'2020' or year(pr.symptom_ari_date) is null) and (year(pr.fever_date)<2020 or year(pr.fever_date) is null)
group by pr.district
order by fever_date desc,symptom_ari_date desc) as fr2n on fr2n.district=a.ampurcodefull
left join (select 
pr.district,
count(distinct pr.cid) AS cc
from 
person_risk_group as pr 
join ccountry as c on c.country_id=pr.from_country
where c.risk_country='Y' and (c.contagion_country is null or c.contagion_country='')
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)>='2020' and year(pr.symptom_ari_date) is not null) and (year(pr.fever_date)>=2020 and year(pr.fever_date) is not null)
group by pr.district
order by fever_date desc,symptom_ari_date desc) as fr2a on fr2a.district=a.ampurcodefull

left join (select pr.district,
count(distinct pr.cid) AS cc 
from person_risk_group as pr
where pr.from_prove  in (select changwatcode from cchangwat where risk_zone ='Y' )
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)<'2020' or year(pr.symptom_ari_date) is null) 
and (year(pr.fever_date)<2020 or year(pr.fever_date) is null)
group by pr.district) as trn on trn.district=a.ampurcodefull

left join (select pr.district,
count(distinct pr.cid) AS cc 
from person_risk_group as pr
where pr.from_prove  in (select changwatcode from cchangwat where risk_zone ='Y' )
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)>='2020' and year(pr.symptom_ari_date) is not null) 
and (year(pr.fever_date)>=2020 and year(pr.fever_date) is not null)
group by pr.district) as tra on tra.district=a.ampurcodefull

left join (select pr.district,
count(distinct pr.cid) AS cc 
from person_risk_group as pr
where pr.from_prove  in (select changwatcode from cchangwat where risk_zone is null or risk_zone='' )
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)<'2020' or year(pr.symptom_ari_date) is null) 
and (year(pr.fever_date)<2020 or year(pr.fever_date) is null)
group by pr.district) as ton on ton.district=a.ampurcodefull

left join (select pr.district,
count(distinct pr.cid) AS cc 
from person_risk_group as pr
where pr.from_prove  in (select changwatcode from cchangwat where risk_zone is null or risk_zone='' )
and pr.record_date between '$startdate' and '$enddate'
and (year(pr.symptom_ari_date)>='2020' and year(pr.symptom_ari_date) is not null) 
and (year(pr.fever_date)>=2020 and year(pr.fever_date) is not null)
group by pr.district) as toa on toa.district=a.ampurcodefull

where a.changwatcode='$proveID' and a.flag_status=0";

$title = $data->GetStringData("select concat('จังหวัด',changwatname) as cc from cchangwat where changwatcode='$proveID'");

$data_list = $db->query($sql_getAll, PDO::FETCH_OBJ);

?>
<div style="padding: 20px;height:100%">

    <div class="clearfix">

        <h5 class="float-left">
            <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-poll"
                    style="font-size: 24px;"></i></button>

            <b><?=$title;?></b>
        </h5>
    </div>
    <div class="line"></div>
    <div>
        <h4 align="center">
            แบบรายงานติดตามประชาชนที่เดินทางมาจาก<br>
            ประเทศที่เป็นเขตโรคติดต่ออันตราย พื้นที่ที่มีการระบาดต่อเนื่อง และจากกรุงเทพมหานคร/ปริมณฑล<br>
            ข้อมูลระหว่างวันที่ <?php echo $fdate->FormatThaiDate($startdate);?> ถึงวันที่ <?php echo $fdate->FormatThaiDate($enddate);?>
        </h4>
        <form name="calc" id="calc" method="post" action="index.php?url=pages/report_person_trace.php">
            <div class="row">
            <div class="col-md-2" style="padding:5px;">
            </div>
                <div class="col-md-4" style="padding:5px;">
                    <div class="form-group row">
                        <label for="startdate" class="col-sm-2 col-form-label">ตั้งแต่วันที่</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="startdate" name="startdate"
                                placeholder="ข้อมูลทั้งแต่วันที่" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="padding:5px;">
                    <div class="form-group row">
                        <label for="enddate" class="col-sm-2 col-form-label">ถึงวันที่</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="enddate" name="enddate" placeholder="ถึงวันที่"
                                readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2" style="padding:5px;">
                    <button type="submit" class="btn btn-info"><i class="fas fa-calendar-check"></i> ตกลง</button>
                </div>
            </div>
        </form>



        <table class="table table-sm table-striped table-bordered table-hover">
            <thead class="thead-dark" align="center">
                <tr>
                    <th class="align-middle" rowspan="3">ลำดับ</th>
                    <th scope="col" class="align-middle" rowspan="3">อำเภอ</th>
                    <th colspan="10">เดินทางเข้ามาจาก(คน)</th>
                    <th rowspan="3" class="align-middle">รวม</th>
                </tr>
                <tr>
                    <th colspan="2" class="align-middle">เขตโรคติดต่ออันตราย<sup>1</sup></th>
                    <th colspan="2" class="align-middle">พื้นที่มีการระบาดต่อเนื่อง<sup>3</sup></th>
                    <th colspan="2" class="align-middle">ประเทศอื่นๆ</th>
                    <th colspan="2" class="align-middle">กรุงเทพ/ปริมณฑล<sup>4</sup></th>
                    <th colspan="2" class="align-middle">จังหวัดอื่นๆ</th>


                </tr>
                <tr>
                    <th>ปกติ(คน)</th>
                    <th>ผิดปกติ<sup>2</sup>(คน)</th>
                    <th>ปกติ(คน)</th>
                    <th>ผิดปกติ<sup>2</sup>(คน)</th>
                    <th>ปกติ(คน)</th>
                    <th>ผิดปกติ<sup>2</sup>(คน)</th>
                    <th>ปกติ(คน)</th>
                    <th>ผิดปกติ<sup>2</sup>(คน)</th>
                    <th>ปกติ(คน)</th>
                    <th>ผิดปกติ<sup>2</sup>(คน)</th>
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
$fona=0;
$fonn=0;
$tona=0;
$tonn=0;
foreach ($data_list as $row) {

?>
                <tr>
                    <th align="center"><?=$i?></th>
                    <th scope="row"><?=$row->ampurname?></th>
                    <td align="center"><?=round($row->fr_normal,0)?></td>
                    <td align="center"><?=round($row->fr_abnormal,0)?></td>
                    <td align="center"><?=round($row->fr2_normal,0)?></td>
                    <td align="center"><?=round($row->fr2_abnormal,0)?></td>
                    <td align="center"><?=round($row->fon_normal,0)?></td>
                    <td align="center"><?=round($row->fon_abnormal,0)?></td>
                    <td align="center"><?=round($row->tr_normal,0)?></td>
                    <td align="center"><?=round($row->tr_abnormal,0)?></td>
                    <td align="center"><?=round($row->to_normal,0)?></td>
                    <td align="center"><?=round($row->to_abnormal,0)?></td>
                    <td align="center"><?=round($row->pp,0)?></td>


                </tr>


                <?php

            $frw= $frw+$row->fr_normal;
            $frq= $frq+$row->fr_abnormal;

            $frall= $frall+$row->fr2_normal;
            $faw= $faw+$row->fr2_abnormal;

            $faq= $faq+$row->tr_normal;
            $faall= $faall+$row->tr_abnormal;

            $trw= $trw+$row->pp;
            $trq= $trq+$row->pp;
            $trall= $trall+$row->pp;

            $fona=$fona+$row->fon_abnormal;
            $fonn=$fonn+$row->fon_normal;
            $tona=$tona+$row->to_abnormal;
            $tonn=$tonn+$row->to_normal;

            $i=$i+1;
    }
?>
                <tr>
                    <td align="center" colspan="2"><b>รวม</b></td>
                    <td align="center"><?=number_format(intval($frw))?></td>
                    <td align="center"><?=number_format(round($frq,0))?></td>
                    <td align="center"><?=number_format(round($frall,0))?></td>
                    <td align="center"><?=number_format(round($faw,0))?></td>
                    <td align="center"><?=number_format(round($fonn,0))?></td>
                    <td align="center"><?=number_format(round($fona,0))?></td>

                    <td align="center"><?=number_format(round($faq,0))?></td>
                    <td align="center"><?=number_format(round($faall,0))?></td>
                    <td align="center"><?=number_format(round($tonn,0))?></td>
                    <td align="center"><?=number_format(round($tona,0))?></td>


                    <td align="center"><?=number_format(round($trall,0))?></td>

                </tr>

            </tbody>
        </table>
    </div>
</div>


</div>