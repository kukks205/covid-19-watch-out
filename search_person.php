<?php
if (!isset($_SESSION)) {
    session_start();
}


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

include 'includes/DBConn.php';
include 'includes/function.php';

$data = new dbClass();


# ป้องกัน sql injection จาก $_GET
foreach ($_GET as $key => $value) {
    $_GET[$key]=addslashes(strip_tags(trim($value)));
}
          
if($_GET['id'] !=''){ $_GET['id']=(int) $_GET['id']; }
extract($_GET);



$pn = explode(" ", $_REQUEST["name"]);
$fn=$pn[0];
$ln=$pn[1];


if($_SESSION['logged']==true){


$hospcode=$_SESSION['office'];


$sql="select * from (select 
p.HOSPCODE as hospcode,
p.CID as cid,pr.cid as reg_cid,
concat(NAME,'  ',LNAME) as fullname,
YEAR(CURDATE())-YEAR(BIRTH) as age,
h.HOUSE as address,
h.VILLAGE as moo,
t.tambonname,
a.ampurname
from person as p
join home as h on h.HID=p.HID and h.HOSPCODE=p.HOSPCODE 
join ctambon as t on t.tamboncodefull = concat(h.CHANGWAT,h.AMPUR,h.TAMBON)
join campur as a on a.ampurcodefull=concat(h.CHANGWAT,h.AMPUR)
left join person_risk_group as pr on pr.cid=p.CID
where p.CID ='$fn'
or concat(p.NAME,' ',p.LNAME) like '%$fn% $ln%'

union 

select
pr.hospcode,
pr.cid,pr.cid as reg_cid,
pr.fullname,
pr.age,
pr.address,
right(pr.moo,2) as moo,
t.tambonname,
a.ampurname
from person_risk_group as pr
left join ctambon as t on t.tamboncodefull=pr.subdistrict
left join campur as a on a.ampurcodefull=pr.district
where pr.cid ='$fn'
or pr.fullname like '%$fn% $ln%') as p
group by cid
order by fullname";
//or p.NAME like '$fn%'

$nm = $db->prepare($sql);
$nm->execute();
$count = $nm->rowCount();
$lists = $db->query($sql, PDO::FETCH_OBJ);

?>



<div class="list-group list-group-flush" id="result" name="result">
    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start list-group-item-info">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">ผลการค้นหา</h5>
            <strong><?=$count?> รายการ</strong>
        </div>
    </a>


<?php

foreach($lists as $row) {


    if($row->reg_cid) {

?>

        <a href="index.php?url=pages/register_detail.php&cid=<?=$row->cid?>" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">CID : <?=$row->reg_cid;?><br> ชื่อ - สกุล : <?=$row->fullname;?><br>
                ที่อยู่: <?=$row->address.'  ม.'.$row->moo.' ต.'.$row->tambonname.' อ.'.$row->ampurname;?>
                </p>
                <button type="button" class="btn btn-success"><i class="fas fa-clipboard-check"></i></button>
                </div>
        
        </a>
        <?php } else {
        ?>
        <a href="index.php?url=pages/register.php&cid=<?=$row->cid?>" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">CID : <?=$row->cid;?><br> ชื่อ - สกุล : <?=$row->fullname;?><br>
                ที่อยู่: <?=$row->address.'  ม.'.$row->moo.' ต.'.$row->tambonname.' อ.'.$row->ampurname;?>
                </p>
                <button type="button" class="btn btn-danger"><i class="fas fa-clipboard-check"></i></button>
                </div>
        </a>
        <?php

        } ?>

<?php
    }
?>

</div>

<?php  } ?>