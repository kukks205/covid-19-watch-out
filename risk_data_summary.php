<?php
if (!isset($_SESSION)) {
    session_start();
}


include 'includes/DBConn.php';
include 'includes/function.php';
require_once("includes/dateClass.php");
require_once("lib/mobile_detect.php");

$data = new dbClass();
$fdate = new dateClass();

$strExcelFileName="Udon Model 3.xls";
header("Content-Type: application/x-msexcel; name=\"$strExcelFileName\"");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");



if($_SESSION['logged']==true){


if(isset($_GET['hospcode'])){

    $hospcode = addslashes(strip_tags(trim($_GET['hospcode'])));

    $sql="select     
pr.cid,pr.fullname,pr.nation,n.nationname,pr.sex,
pr.age,pr.address,pr.moo,pr.subdistrict,
t.tambonname,pr.district,a.ampurname,pr.province,
c.changwatname,pr.telphone,pr.hospcode,hos.hosname,
pr.from_country,n2.name_th as countryname,
pr.from_city,pr.date_arrival,pr.date_arrival_home,
ADDDATE(pr.date_arrival_home,INTERVAL 14 DAY) as date_quarantine,
pr.fever_date,pr.symptom_ari_date,pr.refer_date,pr.culture_test_result,record_date,h2.cc as member_in_home 
from person_risk_group as pr
left join person as p on p.cid=pr.cid and p.HOSPCODE=pr.hospcode
left join home as h on h.HID=p.HID and h.HOSPCODE=p.HOSPCODE
left join (select ho.HID,ho.HOSPCODE,count(distinct pe.CID) as cc 
from home as ho 
join person as pe on pe.HID=ho.HID and pe.HOSPCODE=ho.HOSPCODE 
where pe.DISCHARGE=9 group by ho.HID,ho.HOSPCODE) as h2 on h2.HID=h.HID and h2.HOSPCODE=h.HOSPCODE
join ctambon as t on pr.subdistrict=t.tamboncodefull
join campur as a on a.ampurcodefull=t.ampurcode
join cchangwat as c on c.changwatcode=a.changwatcode
join chospital as hos on hos.hoscode=pr.hospcode
join cnation as n on n.nationcode=pr.nation
join ccountry as n2 on n2.country_id=pr.from_country where pr.hospcode='$hospcode' order by pr.date_arrival_home  ";


} else {
 $sql="select     
pr.cid,pr.fullname,pr.nation,n.nationname,pr.sex,
pr.age,pr.address,pr.moo,pr.subdistrict,
t.tambonname,pr.district,a.ampurname,pr.province,
c.changwatname,pr.telphone,pr.hospcode,hos.hosname,
pr.from_country,n2.name_th as countryname,
pr.from_city,pr.date_arrival,pr.date_arrival_home,
ADDDATE(pr.date_arrival_home,INTERVAL 14 DAY) as date_quarantine,
pr.fever_date,pr.symptom_ari_date,pr.refer_date,pr.culture_test_result,record_date,h2.cc as member_in_home 
from person_risk_group as pr
left join person as p on p.cid=pr.cid and p.HOSPCODE=pr.hospcode
left join home as h on h.HID=p.HID and h.HOSPCODE=p.HOSPCODE
left join (select ho.HID,ho.HOSPCODE,count(distinct pe.CID) as cc 
from home as ho 
join person as pe on pe.HID=ho.HID and pe.HOSPCODE=ho.HOSPCODE 
where pe.DISCHARGE=9 group by ho.HID,ho.HOSPCODE) as h2 on h2.HID=h.HID and h2.HOSPCODE=h.HOSPCODE
join ctambon as t on pr.subdistrict=t.tamboncodefull
join campur as a on a.ampurcodefull=t.ampurcode
join cchangwat as c on c.changwatcode=a.changwatcode
join chospital as hos on hos.hoscode=pr.hospcode
join cnation as n on n.nationcode=pr.nation
join ccountry as n2 on n2.country_id=pr.from_country  order by pr.date_arrival_home  ";


}




$exportData = $db->query($sql, PDO::FETCH_OBJ);


?>



<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <strong>ทะเบียนผู้เดินทางมาจากประเทศเสี่ยงและเขตติดโรค ไวรัสโคโรนาสายพันธุ์ใหม่ 2019</strong><br>
    วันที่ <?php echo $fdate->FormatThaiDate(date("d/m/Y"));?>
    <br>
    <div id="SiXhEaD_Excel" align=center x:publishsource="Excel">

        <table class="table table-sm table-striped table-bordered table-hover" x:str border=1 cellpadding=0 cellspacing=1 width=100% style="border-collapse:collapse">
            <thead class="thead-dark">
                <tr>
                    <th>ลำดับที่</th>
                    <th scope="col">เลขบัตรประชาชน</th>
                    <th scope="col">ชื่อ-สกุล</th>
                    <th scope="col">สมาชิก<br>ในบ้าน</th>
                    <th scope="col">สัญชาติ</th>
                    <th scope="col">เพศ</th>
                    <th scope="col">อายุ</th>
                    <th scope="col">ที่อยู่</th>
                    <th scope="col">หมู่</th>
                    <th scope="col">ตำบล</th>
                    <th scope="col">อำเภอ</th>
                    <th scope="col">เบอร์โทรที่ติดต่อ</th>
                    <th scope="col">รพ.สต.ที่รับผิดชอบ</th>
                    <th scope="col">มาจาก</th>
                    <th scope="col">เมือง</th>
                    <th scope="col">วันที่เดินทางถึงไทย</th>
                    <th scope="col">วันที่เดินทางถึง จ.อุดรธานี</th>
                    <th scope="col">วันที่พ้นระยะเฝ้าระวัง 14 วัน</th>
                    <th scope="col">มีไข้</th>
                    <th scope="col">อาการ ARI</th>
                    <th scope="col">วันส่งต่อ</th>
                    <th scope="col">ผลเพาะเชื้อ*</th>
                </tr>
            </thead>
            <tbody>







                <?php
                    $i = 1;
                    foreach ($exportData as $row) {

                ?>
                <tr>
                    <td><?=$i?></td>
                    <td scope="col"><?=$row->cid?></td>
                    <td scope="col"><?=$row->fullname?></td>
                    <td scope="col"><?=$row->member_in_home?></td>
                    <td scope="col"><?=$row->nationname?></td>
                    <td scope="col"><?php if($row->sex==1){echo 'ชาย';} else{ echo 'หญิง';}  ?></td>
                    <td scope="col"><?=$row->age?></td>
                    <td scope="col"><?=$row->address?></td>
                    <td scope="col"><?=substr($row->moo,6)?></td>
                    <td scope="col"><?=$row->tambonname?></td>
                    <td scope="col"><?=$row->ampurname?></td>
                    <td scope="col"><?=$row->changwatname?></td>
                    <td scope="col"><?=$row->hosname?></td>
                    <td scope="col"><?=$row->countryname?></td>
                    <td scope="col"><?=$row->from_city?></td>
                    <td><?php if(isset($row->date_arrival)&&$row->date_arrival<>'0000-00-00'){echo $fdate->FormatThaiDate($row->date_arrival);}?></td>
                    <td><?php if(isset($row->date_arrival_home)&&$row->date_arrival_home<>'0000-00-00'){echo $fdate->FormatThaiDate($row->date_arrival_home);}?></td>
                    <td><?=$fdate->FormatThaiDate($row->date_quarantine);?></td>
                    <td scope="col"><?php if(isset($row->fever_date)&&$row->fever_date<>'0000-00-00'){ echo $fdate->FormatThaiDate($row->fever_date);}?></td>
                    <td scope="col"><?php if(isset($row->symptom_ari_date)&&$row->symptom_ari_date<>'0000-00-00'){ echo $fdate->FormatThaiDate($row->symptom_ari_date);}?></td>
                    <td scope="col"><?php if(isset($row->refer_date) &&$row->refer_date<>'0000-00-00'){ echo $fdate->FormatThaiDate($row->refer_date);}?></td>
                    <td scope="col"><?php if(isset($row->culture_test_result)&&($row->culture_test_result=='Positive'||$row->culture_test_result=='Negative')){ echo $row->culture_test_result;}?></td>
                </tr>


                <?php
                $i=$i+1;
    }
            ?>
            </tbody>
        </table>
    </div>

<?php }?>
    <script>
        window.onbeforeunload = function () {
            return false;
        };
        setTimeout(function () {
            window.close();
        }, 10000);
    </script>
</body>

</html>