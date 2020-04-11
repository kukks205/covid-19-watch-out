<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once("includes/DBConn.php");
require_once("includes/function.php");
require_once("includes/dateClass.php");
require_once("lib/mobile_detect.php");

$data = new dbClass();
$fdate = new dateClass();


$data = new dbClass();
$fdate = new dateClass();

$strExcelFileName="Udon Model 3.xls";
header("Content-Type: application/x-msexcel; name=\"$strExcelFileName\"");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");

$districtID = $_SESSION['district'];

$check_hospcode = $_GET['hospcode'];


if($_SESSION['logged']==true){

if(strlen($check_hospcode)==5){

$hospcode = addslashes(strip_tags(trim($_GET['hospcode'])));

$title = $data->GetStringData("select concat('[',hoscode,'] ',hosname) as cc  from chospital where hoscode='$hospcode'");

$sql="select pr.cid,
pr.fullname,
n.nationname,
pr.sex,
pr.age,
pr.address,
pr.moo,
pr.hospcode,
pr.telphone,
t.tambonname,
a.ampurname,
c.changwatname,
hos.hosname,
n2.name_th as countryname ,
pr.from_city,
c2.changwatname as form_prove,
pr.date_arrival,
pr.date_arrival_home,
ADDDATE(pr.date_arrival_home,INTERVAL 14 DAY) as date_quarantine,
rt.risk_type_name,pr.record_date,
(select count(cid) as cc
from person as p
join home as h on h.HID=p.HID and p.HOSPCODE=h.HOSPCODE
where p.HOSPCODE='$hospcode' and p.HID=pe.HID and p.HOSPCODE=pe.HOSPCODE
group by h.HID) as member_in_home 
from person_risk_group as pr
left join person as pe on pe.CID=pr.cid and pe.HOSPCODE='$hospcode'
join chospital as hos on hos.hoscode=pr.hospcode
join ctambon as t on t.tamboncodefull=pr.subdistrict
join campur as a on a.ampurcodefull=t.ampurcode
join cchangwat as c on c.changwatcode=t.changwatcode
join cnation as n on n.nationcode=pr.nation
left join ccountry as n2 on n2.country_id=pr.from_country
left join cchangwat as c2 on c2.changwatcode=pr.from_prove
left join risk_group_type as rt on rt.risk_type_id=pr.risk_type_id
where pr.hospcode='$hospcode' order by pr.date_arrival_home";

} else if($_SESSION['role']<>'001'&&$_SESSION['role']<>'002'&&$_SESSION['role']<>'003'&&$_SESSION['role']<>'007' ) {

$hospcode =$_SESSION['office'];
$title = $data->GetStringData("select concat('[',hoscode,'] ',hosname) as cc  from chospital where hoscode='$hospcode'");

$sql="select pr.cid,
pr.fullname,
n.nationname,
pr.sex,
pr.age,
pr.address,
pr.moo,
pr.hospcode,
t.tambonname,
a.ampurname,
pr.telphone,
c.changwatname,
hos.hosname,
n2.name_th as countryname ,
pr.from_city,
c2.changwatname as form_prove,
pr.date_arrival,
pr.date_arrival_home,
ADDDATE(pr.date_arrival_home,INTERVAL 14 DAY) as date_quarantine,
rt.risk_type_name,pr.record_date,
(select count(cid) as cc
from person as p
join home as h on h.HID=p.HID and p.HOSPCODE=h.HOSPCODE
where p.HOSPCODE='$hospcode' and p.HID=pe.HID and p.HOSPCODE=pe.HOSPCODE
group by h.HID) as member_in_home 
from person_risk_group as pr
left join person as pe on pe.CID=pr.cid and pe.HOSPCODE='$hospcode'
join chospital as hos on hos.hoscode=pr.hospcode
join ctambon as t on t.tamboncodefull=pr.subdistrict
join campur as a on a.ampurcodefull=t.ampurcode
join cchangwat as c on c.changwatcode=t.changwatcode
join cnation as n on n.nationcode=pr.nation
left join ccountry as n2 on n2.country_id=pr.from_country
left join cchangwat as c2 on c2.changwatcode=pr.from_prove
left join risk_group_type as rt on rt.risk_type_id=pr.risk_type_id
where pr.hospcode='$hospcode' order by pr.date_arrival_home";

}else if($_SESSION['role']=='001'||$_SESSION['role']=='002'||$_SESSION['role']=='003'||$_SESSION['role']=='007' ){

$title = $data->GetStringData("select concat('อำเภอ',ampurname) as cc from campur where ampurcodefull='$districtID'");

$sql="select pr.cid,
pr.fullname,
n.nationname,
pr.sex,
pr.age,
pr.address,
pr.moo,
pr.hospcode,
t.tambonname,
a.ampurname,
c.changwatname,
pr.telphone,
hos.hosname,
n2.name_th as countryname ,
pr.from_city,
c2.changwatname as form_prove,
pr.date_arrival,
pr.date_arrival_home,
ADDDATE(pr.date_arrival_home,INTERVAL 14 DAY) as date_quarantine,
rt.risk_type_name,pr.record_date
from 
person_risk_group as pr 
left join person as pe on pe.CID=pr.cid and pe.HOSPCODE=pr.hospcode
left join chospital as hos on hos.hoscode=pr.hospcode
join ctambon as t on t.tamboncodefull=pr.subdistrict
join campur as a on a.ampurcodefull=t.ampurcode
join cchangwat as c on c.changwatcode=t.changwatcode
left join cnation as n on n.nationcode=pr.nation
left join ccountry as n2 on n2.country_id=pr.from_country
left join cchangwat as c2 on c2.changwatcode=pr.from_prove
left join risk_group_type as rt on rt.risk_type_id=pr.risk_type_id
where pr.district='$districtID' order by pr.date_arrival_home ";

}else{

    echo "Fail";


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
    ข้อมูล ณ วันที่ <?php echo $fdate->FormatThaiDate(date("Y-m-d"));?>
    <br>หน่วยบริการ <?php echo $title;?> </strong>
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
                    <th scope="col">มาจากประเทศ</th>
                    <th scope="col">เมือง</th>
                    <th scope="col">มาจากจังหวัด</th>
                    <th scope="col">วันที่เดินทางถึงไทย</th>
                    <th scope="col">วันที่เดินทางถึงบ้าน</th>
                    <th scope="col">วันที่พ้นระยะเฝ้าระวัง 14 วัน</th>
                    <th scope="col">มีไข้</th>
                    <th scope="col">อาการ ARI</th>
                    <th scope="col">วันส่งต่อ</th>
                    <th scope="col">ผลเพาะเชื้อ*</th>
                    <th scope="col">วันที่บันทึกข้อมูล</th>
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
                    <td scope="col"><?=$row->telphone?></td>
                    <td scope="col"><?=$row->hosname?></td>
                    <td scope="col"><?=$row->countryname?></td>
                    <td scope="col"><?=$row->from_city?></td>
                    <td scope="col"><?=$row->form_prove?></td>
                    <td><?php if(isset($row->date_arrival)&&$row->date_arrival<>'0000-00-00'){echo $fdate->FormatThaiDate($row->date_arrival);}?></td>
                    <td><?php if(isset($row->date_arrival_home)&&$row->date_arrival_home<>'0000-00-00'){echo $fdate->FormatThaiDate($row->date_arrival_home);}?></td>
                    <td><?=$fdate->FormatThaiDate($row->date_quarantine);?></td>
                    <td scope="col"><?php if(isset($row->fever_date)&&$row->fever_date<>'0000-00-00'){ echo $fdate->FormatThaiDate($row->fever_date);}?></td>
                    <td scope="col"><?php if(isset($row->symptom_ari_date)&&$row->symptom_ari_date<>'0000-00-00'){ echo $fdate->FormatThaiDate($row->symptom_ari_date);}?></td>
                    <td scope="col"><?php if(isset($row->refer_date) &&$row->refer_date<>'0000-00-00'){ echo $fdate->FormatThaiDate($row->refer_date);}?></td>
                    <td scope="col"><?php if(isset($row->culture_test_result)&&($row->culture_test_result=='Positive'||$row->culture_test_result=='Negative')){ echo $row->culture_test_result;}?></td>
                    <td><?=$fdate->FormatThaiDate($row->record_date);?></td>
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