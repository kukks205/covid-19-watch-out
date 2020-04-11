<style>
.table {
    width:3980px;
    /*table-layout: fixed;*/
    word-wrap: break-word;
}

</style>


<?php

$hospcode = $_GET['hospcode'];

$sql_getAll = "select 
pp.cid,pp.address,right(pp.moo,2) as moo,pp.telphone,
pp.fullname,
if(pp.date_arrival_home<=CURDATE(),pp.date_arrival_home,'') as d0,
if(pp.fever_date=pp.date_arrival_home,'Y','N') as fd0,
if(pp.symptom_ari_date=pp.date_arrival_home,'Y','N') as sd0,
if(pp.d1<=CURDATE(),pp.d1,'') as d1,
if(pp.fever_date=pp.d1,'Y','N') as fd1,
if(pp.symptom_ari_date=pp.d1,'Y','N') as sd1,
if(pp.d2<=CURDATE(),pp.d2,'') as d2,
if(pp.fever_date=pp.d2,'Y','N') as fd2,
if(pp.symptom_ari_date=pp.d2,'Y','N') as sd2,
if(pp.d3<=CURDATE(),pp.d3,'') as d3,
if(pp.fever_date=pp.d3,'Y','N') as fd3,
if(pp.symptom_ari_date=pp.d3,'Y','N') as sd3,
if(pp.d4<=CURDATE(),pp.d4,'') as d4,
if(pp.fever_date=pp.d4,'Y','N') as fd4,
if(pp.symptom_ari_date=pp.d4,'Y','N') as sd4,
if(pp.d5<=CURDATE(),pp.d5,'') as d5,
if(pp.fever_date=pp.d5,'Y','N') as fd5,
if(pp.symptom_ari_date=pp.d5,'Y','N') as sd5,
if(pp.d6<=CURDATE(),pp.d6,'') as d6,
if(pp.fever_date=pp.d6,'Y','N') as fd6,
if(pp.symptom_ari_date=pp.d6,'Y','N') as sd6,
if(pp.d7<=CURDATE(),pp.d7,'') as d7,
if(pp.fever_date=pp.d7,'Y','N') as fd7,
if(pp.symptom_ari_date=pp.d7,'Y','N') as sd7,
if(pp.d8<=CURDATE(),pp.d8,'') as d8,
if(pp.fever_date=pp.d8,'Y','N') as fd8,
if(pp.symptom_ari_date=pp.d8,'Y','N') as sd8,
if(pp.d9<=CURDATE(),pp.d9,'') as d9,
if(pp.fever_date=pp.d9,'Y','N') as fd9,
if(pp.symptom_ari_date=pp.d9,'Y','N') as sd9,
if(pp.d10<=CURDATE(),pp.d10,'') as d10,
if(pp.fever_date=pp.d10,'Y','N') as fd10,
if(pp.symptom_ari_date=pp.d10,'Y','N') as sd10,
if(pp.d11<=CURDATE(),pp.d11,'') as d11,
if(pp.fever_date=pp.d11,'Y','N') as fd11,
if(pp.symptom_ari_date=pp.d11,'Y','N') as sd11,
if(pp.d12<=CURDATE(),pp.d12,'') as d12,
if(pp.fever_date=pp.d12,'Y','N') as fd12,
if(pp.symptom_ari_date=pp.d12,'Y','N') as sd12,
if(pp.d13<=CURDATE(),pp.d13,'') as d13,
if(pp.fever_date=pp.d13,'Y','N') as fd13,
if(pp.symptom_ari_date=pp.d13,'Y','N') as sd13,
if(pp.d14<=CURDATE(),pp.d14,'') as d14,
if(pp.fever_date=pp.d14,'Y','N') as fd14,
if(pp.symptom_ari_date=pp.d14,'Y','N') as sd14
from (select pr.cid,pr.address,pr.moo,pr.telphone,
pr.fullname,pr.date_arrival,
pr.date_arrival_home,pr.date_quarantine,
pr.risk_type_id,pr.fever_date,
pr.symptom_ari_date,pr.refer_date,
pr.culture_test_result,pr.record_date,
DATEDIFF(CURDATE(),pr.date_arrival_home) AS cc,
DATE_ADD(pr.date_arrival_home,INTERVAL 1 Day) AS d1,
DATE_ADD(pr.date_arrival_home,INTERVAL 2 Day) AS d2,
DATE_ADD(pr.date_arrival_home,INTERVAL 3 Day) AS d3,
DATE_ADD(pr.date_arrival_home,INTERVAL 4 Day) AS d4,
DATE_ADD(pr.date_arrival_home,INTERVAL 5 Day) AS d5,
DATE_ADD(pr.date_arrival_home,INTERVAL 6 Day) AS d6,
DATE_ADD(pr.date_arrival_home,INTERVAL 7 Day) AS d7,
DATE_ADD(pr.date_arrival_home,INTERVAL 8 Day) AS d8,
DATE_ADD(pr.date_arrival_home,INTERVAL 9 Day) AS d9,
DATE_ADD(pr.date_arrival_home,INTERVAL 10 Day) AS d10,
DATE_ADD(pr.date_arrival_home,INTERVAL 11 Day) AS d11,
DATE_ADD(pr.date_arrival_home,INTERVAL 12 Day) AS d12,
DATE_ADD(pr.date_arrival_home,INTERVAL 13 Day) AS d13,
DATE_ADD(pr.date_arrival_home,INTERVAL 14 Day) AS d14
from person_risk_group as pr
where pr.hospcode='$hospcode') as pp
where pp.d14>=CURDATE()
order by pp.d1";



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

<table class="table table-xl table-striped table-bordered table-hover">
        <thead class="thead-dark" align="center">
            <tr>
                <th class="align-middle" rowspan="2" width="65px;">ลำดับ</th>
                <th scope="col" class="align-middle" rowspan="2" width="220px;">ชื่อ - สกุล</th>
                <th scope="col" class="align-middle" rowspan="2" width="120px;">เบอร์ติดต่อ</th>
                <th scope="col" class="align-middle" rowspan="2" width="65px;">ที่อยู่</th>
                <th scope="col" class="align-middle" rowspan="2" width="65px;">หมู่</th>
                <th colspan="3">วันที่ถึงบ้าน</th>
                <th colspan="3">วันที่1</th>
                <th colspan="3">วันที่2</th>
                <th colspan="3">วันที่3</th>
                <th colspan="3">วันที่4</th>
                <th colspan="3">วันที่5</th>
                <th colspan="3">วันที่6</th>
                <th colspan="3">วันที่7</th>
                <th colspan="3">วันที่8</th>
                <th colspan="3">วันที่9</th>
                <th colspan="3">วันที่10</th>
                <th colspan="3">วันที่11</th>
                <th colspan="3">วันที่12</th>
                <th colspan="3">วันที่13</th>
                <th colspan="3">วันที่14</th>
                <th rowspan="2"></th>
            </tr>
            <tr>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
                <th width="110px;">วันที่</th>
                <th width="55px;">ไข้</th>
                <th width="55px;">ARI</th>
            </tr>
        </thead>
        <tbody>







            <?php
$i=1;

foreach ($data_list as $row) {

?>
            <tr>
                <td align="center"><?=$i?></td>
                <th scope="row"><?=$row->fullname;?></th>
                <th scope="row"><?=$row->telphone;?></th>
                <td><?=$row->address;?></td>
                <td><?=$row->moo?></td>
                <td align="center"><?php if($row->d0){echo $fdate->FormatThaiDate($row->d0);}?></td>
                <td align="center"><?php if($row->d0){if($row->fd0=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d0){if($row->sd0=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d1){echo $fdate->FormatThaiDate($row->d1);}?></td>
                <td align="center"><?php if($row->d1){if($row->fd1=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d1){if($row->sd1=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d2){echo $fdate->FormatThaiDate($row->d2);}?></td>
                <td align="center"><?php if($row->d2){if($row->fd2=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d2){if($row->sd2=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d3){echo $fdate->FormatThaiDate($row->d3);}?></td>
                <td align="center"><?php if($row->d3){if($row->fd3=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d3){if($row->sd3=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d4){echo $fdate->FormatThaiDate($row->d4);}?></td>
                <td align="center"><?php if($row->d4){if($row->fd4=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d4){if($row->sd4=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d5){echo $fdate->FormatThaiDate($row->d5);}?></td>
                <td align="center"><?php if($row->d5){if($row->fd5=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d5){if($row->sd5=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d6){echo $fdate->FormatThaiDate($row->d6);}?></td>
                <td align="center"><?php if($row->d6){if($row->fd6=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d6){if($row->sd6=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d7){echo $fdate->FormatThaiDate($row->d7);}?></td>
                <td align="center"><?php if($row->d7){if($row->fd7=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d7){if($row->sd7=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d8){echo $fdate->FormatThaiDate($row->d8);}?></td>
                <td align="center"><?php if($row->d8){if($row->fd8=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d8){if($row->sd8=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d9){echo $fdate->FormatThaiDate($row->d9);}?></td>
                <td align="center"><?php if($row->d9){if($row->fd9=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d9){if($row->sd9=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d10){echo $fdate->FormatThaiDate($row->d10);}?></td>
                <td align="center"><?php if($row->d10){if($row->fd10=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d10){if($row->sd10=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d11){echo $fdate->FormatThaiDate($row->d11);}?></td>
                <td align="center"><?php if($row->d11){if($row->fd11=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d11){if($row->sd11=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d12){echo $fdate->FormatThaiDate($row->d12);}?></td>
                <td align="center"><?php if($row->d12){if($row->fd12=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d12){if($row->sd12=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>            
                <td align="center"><?php if($row->d13){echo $fdate->FormatThaiDate($row->d13);}?></td>
                <td align="center"><?php if($row->d13){if($row->fd13=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d13){if($row->sd13=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td> 
                <td align="center"><?php if($row->d14){echo $fdate->FormatThaiDate($row->d14);}?></td>
                <td align="center"><?php if($row->d14){if($row->fd14=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-thermometer-full text-danger'></i>";}}?></td>
                <td align="center"><?php if($row->d14){if($row->sd14=='N'){ echo "<i class='fas fa-user-check text-success'></i>";}else{ echo "<i class='fas fa-head-side-cough text-danger'></i>";}}?></td>  
                <td><a href="index.php?url=pages/register_detail.php&cid=<?=$row->cid?>"
            class="btn btn-success btn-sm"><i class="fas fa-file-pdf"></i> พิมพ์</a></td>               
            </tr>


            <?php
            $i = $i+1;
    }
?>


        </tbody>
    </table>
</div>
</div>


</div>