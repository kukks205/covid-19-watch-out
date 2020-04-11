<?php
$ptname = $data->GetStringData("select concat(pname,fname,'   ',lname) as cc from person where cid='$cid'");

$sql_vaccine="select p.cid,vacc_his.vaccine_date,pv.vaccine_code,pv.vaccine_name
from (select wv.wbc_vaccine_name as vaccine_name , wv.wbc_vaccine_code as vaccine_code, wv.age_min,wv.age_max,
wv.export_vaccine_code
from wbc_vaccine as wv
union
select ev.epi_vaccine_name as vaccine_name,ev.vaccine_code as vaccine_code, ev.age_min,ev.age_max,
ev.export_vaccine_code
from epi_vaccine as ev
union
select sv.student_vaccine_name as vaccine_name,sv.vaccine_code as vaccine_code, 84 as age_min, 180 as age_max,
sv.export_vaccine_code
from student_vaccine AS sv
union
select pv.vaccine_name,pv.vaccine_code,180 as age_min,1200 as age_max,pv.export_vaccine_code
from person_vaccine as pv
where pv.export_vaccine_code not in (select export_vaccine_code from wbc_vaccine)
and pv.export_vaccine_code not in (select export_vaccine_code from epi_vaccine)
and pv.export_vaccine_code not in (select export_vaccine_code from student_vaccine) 
group by vaccine_code
order by export_vaccine_code) as vacc
left join person_vaccine as pv on pv.vaccine_code=vacc.vaccine_code
join (select pvl.person_id,pvl.person_vaccine_id,pvl.vaccine_date from person_vaccine_list as pvl
union
select pve.person_id,pve.person_vaccine_id,pve.vaccine_date from person_vaccine_elsewhere as pve
union
select p.person_id,ov.person_vaccine_id,v.vstdate as vaccine_date from ovst_vaccine as ov
join vn_stat as v on v.vn=ov.vn
join person as p on p.patient_hn=v.hn
union
select pve.person_id,pve.person_vaccine_id,pve.vaccine_date from person_vaccine_elsewhere as pve) as vacc_his on
vacc_his.person_vaccine_id=pv.person_vaccine_id
join person as p on p.person_id=vacc_his.person_id
where p.cid='$cid'
group by vacc.vaccine_code
order by vacc_his.vaccine_date,vacc.export_vaccine_code";

$num_vaccine = $db->prepare($sql_vaccine);
$num_vaccine->execute();
$num_vacc = $num_vaccine->rowCount();

$vaccine = $db->query($sql_vaccine, PDO::FETCH_OBJ);


//echo $vacc->vaccine_date," ",$vacc->vaccine_name,"<br>";

if($num_vacc<1){
?>

<div class="alert alert-info" role="alert">
<h4 class="alert-heading">ไม่พบประวัติวัคซีน</h4>
<hr>
<p class="mb-0"><?=$ptname,' ไม่พบว่ามีประวัติการได้รับวัคซีนที่',$hospname?>  </p>
</div>
<?php


 }else{ ?>



    <div style="padding-bottom:3px;">
        <button class="btn btn-info text-left" type="button" data-toggle="collapse" data-target="#collapseExample"
            aria-expanded="false" aria-controls="collapseExample" style="min-width: 100%;max-width: 100%;">
            <h4><b>ประวัติการรับวัคซีน <?php  echo $ptname; ?></b></h4>
        </button>
        <div class="collapse show" id="collapseExample">
            <div class="list-group list-group-flush">
                <?php
                        $ri = 1;
                        foreach ($vaccine as $vacc) {
                    ?>
                <a class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <p class="mb-1"><?=$ri.". ".$vacc->vaccine_name ?></p>
                        <small class="text-muted"><?=$vacc->vaccine_code;?></small>
                    </div>
                    <p class="mb-1" style="font-size:0.9rem;">วันที่ได้รับ :
                        <?=$fdate->FormatThaiDate($vacc->vaccine_date);?></p>
                </a>
                <?php $ri = ++$ri;}  ?>
            </div>
        </div>
    </div>


    <?php
}
?>
