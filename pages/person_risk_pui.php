<?php


if($_SESSION['role']=='001'||$_SESSION['role']=='002'){

  $enable_select =true;
  $title = $data->GetStringData("select concat('จังหวัด',changwatname) as cc from cchangwat where changwatcode='". $_SESSION['province']."'");
  $where = "hospcode is not null";
  $row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where $where and ((fever_date<>'' and fever_date<>'0000-00-00') or (symptom_ari_date<>'' and  symptom_ari_date<>'0000-00-00'))");

}else if($_SESSION['role']=='007'||$_SESSION['role']=='003'){

  $enable_select =true;
  $title = $data->GetStringData("select concat('อำเภอ',ampurname) as cc from campur where ampurcodefull='".$_SESSION['district']."'");
  $where = "district='".$_SESSION['district']."'";
  $row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where $where and ((fever_date<>'' and fever_date<>'0000-00-00') or (symptom_ari_date<>'' and  symptom_ari_date<>'0000-00-00'))");

}else{
  $enable_select =false;
  $title = $data->GetStringData("select SUBSTRING_INDEX(hosname,' ',1) as cc from chospital where hoscode='".$_SESSION['office']."'");
  $where = "hospcode='".$_SESSION['office']."'";
  $row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where $where and ((fever_date<>'' and fever_date<>'0000-00-00') or (symptom_ari_date<>'' and  symptom_ari_date<>'0000-00-00'))");

}


//$row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where fever_date<>'' or symptom_ari_date<>''");

$pagesize = 20;
$totalpage=ceil($row_all/$pagesize);
$page=$_GET['page'];
if(!isset($page)) {
   $page=1;
   $start=0;
} else {
   $start=($page-1)*$pagesize;
}

$sql_getAll = "select
pr.cid,
pr.fullname,
pr.nation,
n.nationname,
pr.sex,
pr.age,
pr.address,
pr.moo,
pr.subdistrict,
t.tambonname,
pr.district,
a.ampurname,
pr.province,
c.changwatname,
pr.telphone,
pr.hospcode,
hos.hosname,
pr.from_country,
n2.name_th as countryname,
pr.from_city,
pr.date_arrival,
pr.date_arrival_home,
ADDDATE(pr.date_arrival,INTERVAL 14 DAY) as date_quarantine,
pr.fever_date,
pr.symptom_ari_date,
pr.refer_date,
pr.culture_test_result
from person_risk_group as pr 
join ctambon as t on pr.subdistrict=t.tamboncodefull
join campur as a on a.ampurcodefull=t.ampurcode
join cchangwat as c on c.changwatcode=a.changwatcode
join chospital as hos on hos.hoscode=pr.hospcode
join cnation as n on n.nationcode=pr.nation
join ccountry as n2 on n2.country_id=pr.from_country where $where and ( (pr.fever_date<>'' and pr.fever_date<>'0000-00-00') or (pr.symptom_ari_date<>'' and  pr.symptom_ari_date<>'0000-00-00'))   order by pr.date_arrival limit $start,$pagesize";

//$sql_getAll = "select * from wru_teams where team_id!='".$_SESSION["team_id"]."' order by team_id limit $start,$pagesize";

$users_list = $db->query($sql_getAll, PDO::FETCH_OBJ);


?>

<div style="padding: 20px;height:100%">

<div class="clearfix">
    <h2 class="float-left">
    <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-procedures"
                    style="font-size: 24px;"></i></button>
     <b>ทะเบียนกลุ่มเสี่ยงเข้าเกณฑ์ตรวจหาเสมหะ <?=$title?></b>
    </h2>
</div>
<hr>


<table class="table table-sm table-striped table-bordered table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">CID</th>
      <th scope="col">ชื่อ-สกุล</th>
      <th scope="col">อายุ</th>
      <th scope="col">ที่อยู่</th>
      <th scope="col">หมู่</th>
      <th scope="col">ตำบล</th>
      <th scope="col">เบอร์ติดต่อ</th>
      <th scope="col">มาจากประเทศ</th>
      <th scope="col">เมือง</th>
      <th scope="col">ครบเฝ้าระวัง</th>
      <th scope="col">วันที่ส่งต่อ</th>
      <th scope="col">ผลตรวจเชื้อ</th>
      <th></th>
    </tr>
  </thead>
  <tbody>







    <?php

foreach ($users_list as $row) {

?>
    <tr>
      <td scope="row"><?=$row->cid;?></td>
      <td><?=$row->fullname;?></td>
      <td><?=$row->age;?></td>
      <td><?=$row->address;?></td>
      <td><?=$row->moo;?></td>
      <td><?=$row->tambonname;?></td>
      <td><?=$row->telphone;?></td>
      <td><?=$row->countryname;?></td>
      <td><?=$row->from_city;?></td>
      <td><?=$fdate->FormatThaiDate($row->date_quarantine);?></td>
      <td><?php if($row->refer_date<>'0000-00-00'&&$row->refer_date<>''){echo $fdate->FormatThaiDate($row->refer_date);}?></td>
      <td <?php if($row->culture_test_result=='Positive'){echo "style='color:red;'";}else{echo "style='color:green;'";}?>>
      <b>
        <?php if($row->culture_test_result=='Positive'){

          echo '<img src="./img/red-ripple.svg" width="42px">'.$row->culture_test_result;
        }
        else{
          echo '<i class="fas fa-check"></i> '.$row->culture_test_result;
          }?>

    </b>
    </td>
      <td><a href="index.php?url=pages/register_detail.php&cid=<?=$row->cid?>" class="btn btn-success btn-sm"><i class="fas fa-user-check"></i></a></td>
    </tr>


    <?php
    }
?>
  </tbody>
</table>

   <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link"
                    href="index.php?url=pages/person_risk_pui.php&hospcode=<?=$hospcode?>&page=1">First</a>
            </li>
            <li class="page-item">
                <a class="page-link"
                    href="index.php?url=pages/person_risk_pui.php&hospcode=<?=$hospcode?>&page=<?php if($page>1){echo $page-1;} else {echo $page;}?>">Previous</a>
            </li>
            <?php
                if ($totalpage>0&&$totalpage<=10) {

                    for ($i=1; $i<=$totalpage; $i++) {

                        if($i==$page) {
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_pui.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                        }else{
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_pui.php&hospcode=$hospcode&page=$i\">$i</a></li>";
                        }

                    }
                } else if($totalpage>10) {

                    for($i=1; $i<=$totalpage; $i++){
                        if($i==$page&&$i==1&&$i<$totalpage){
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_pui.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+2).'">'.($page+2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+3).'">'.($page+3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+4).'">'.($page+4).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+5).'">'.($page+5).'</a></li>';
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }else
                        if($i==$page&&$i==2&&$i<$totalpage){
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_pui.php&hospcode=$hospcode&page=1\">1</a></li>";
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_pui.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+2).'">'.($page+2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+3).'">'.($page+3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+4).'">'.($page+4).'</a></li>';
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }else
                        if($i==$page&&$i==3&&$i<$totalpage){
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_pui.php&hospcode=$hospcode&page=1\">1</a></li>";
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_pui.php&hospcode=$hospcode&page=2\">2</a></li>";
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_pui.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+2).'">'.($page+2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+3).'">'.($page+3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }else if($i==$page&&$i>3&&$i<$totalpage){

                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page-3).'">'.($page-3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page-2).'">'.($page-2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page-1).'">'.($page-1).'</a></li>';
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_pui.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+2).'">'.($page+2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.($page+3).'">'.($page+3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_pui.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }
                    }

                }
            ?>
            <li class="page-item"><a class="page-link"
                    href="index.php?url=pages/person_risk_pui.php&hospcode=<?=$hospcode?>&page=<?php if($page<$totalpage){echo $page+1;}else{echo $page;} ?>">Next</a>
            </li>
            <li class="page-item">
                                        <a class="page-link"
                                        href="index.php?url=pages/person_risk_pui.php&hospcode=<?=$hospcode?>&page=<?=$totalpage?>">Last</a>
             </li>

        </ul>
    </nav>    

              </div>