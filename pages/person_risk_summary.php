<?php


$province = $_SESSION['province'];

if($_SESSION['role']=='001'){

    $enable_select =true;
    $title = $data->GetStringData("select concat('จังหวัด',changwatname) as cc from cchangwat where changwatcode='". $_SESSION['province']."'");
    if($_REQUEST["search"]==false){
        $discode = $_SESSION['province'];
        $where = "t.ampurcode like '$discode%'";

    }else{
        $discode = addslashes(strip_tags(trim($_REQUEST['distcode'])));
        $where = "t.ampurcode = '$discode'";
    }
    
    
    $row_all = $data->GetStringData("select count(h.hoscode) as cc
    from ctambon as t 
    join chospital as h on h.tambonfullcode=t.tamboncodefull
    where $where and t.flag_status=0 and h.hostype in ('18','07')  and h.status=1");
   

}else if($_SESSION['role']=='007'){

    $enable_select =false;
    $title = $data->GetStringData("select concat('อำเภอ',ampurname) as cc from campur where ampurcodefull='".$_SESSION['district']."'");
    $where = "t.ampurcode='".$_SESSION['district']."'";
    $discode =$_SESSION['district']; 
    $row_all = $data->GetStringData("select count(h.hoscode) as cc
    from ctambon as t 
    join chospital as h on h.tambonfullcode=t.tamboncodefull
    where $where and t.flag_status=0 and h.hostype in ('18','07')  and h.status=1");
   

}else{
    $enable_select =false;
    $title = $data->GetStringData("select concat('อำเภอ',ampurname) as cc from campur where ampurcodefull='".$_SESSION['district']."'");
    $where = "t.ampurcode='".$_SESSION['district']."'";
    $row_all = $data->GetStringData("select count(h.hoscode) as cc
    from ctambon as t 
    join chospital as h on h.tambonfullcode=t.tamboncodefull
    where $where and t.flag_status=0 and h.hostype in ('18','07')  and h.status=1");


}


$pagesize =20;
$totalpage=ceil($row_all/$pagesize);
$page=$_GET['page'];
if(!isset($page)) {
   $page=1;
   $start=0;
} else {
   $start=($page-1)*$pagesize;
}

if($_REQUEST["search"]==true){

$sql_getAll = "select t.tamboncode,t.tambonname,t.tamboncodefull,h.hoscode,h.hosname,a.ampurcodefull,a.ampurname,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode) as p_all,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and record_date<>CURDATE()) as p_old,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and record_date=CURDATE()) as p_new,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and DATEDIFF(CURDATE(),date_arrival)>14) as p_qa,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and DATEDIFF(CURDATE(),date_arrival)<14) as p_wa,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and  ((fever_date<>'' and fever_date<>'0000-00-00') or (symptom_ari_date<>'' and  symptom_ari_date<>'0000-00-00')))  as p_pui,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and  (p.fever_date<>'' or p.symptom_ari_date<>'') and p.culture_test_result='Positive' )  as p_pos
from ctambon as t 
join chospital as h on h.tambonfullcode=t.tamboncodefull
join campur as a on a.ampurcodefull=t.ampurcode
where $where  and t.flag_status=0 and h.hostype in ('18','07')  and h.status=1 order by a.ampurcodefull,h.hoscode  limit $start,$pagesize";

}else if($_REQUEST["search"]==false && $enable_select==true ){

$sql_getAll = "select t.tamboncode,t.tambonname,t.tamboncodefull,h.hoscode,h.hosname,a.ampurcodefull,a.ampurname,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode) as p_all,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and record_date<>CURDATE()) as p_old,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and record_date=CURDATE()) as p_new,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and DATEDIFF(CURDATE(),date_arrival)>14) as p_qa,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and DATEDIFF(CURDATE(),date_arrival)<14) as p_wa,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and ((fever_date<>'' and fever_date<>'0000-00-00') or (symptom_ari_date<>'' and  symptom_ari_date<>'0000-00-00'))) as p_pui,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and  (p.fever_date<>'' or p.symptom_ari_date<>'') and p.culture_test_result='Positive' )  as p_pos,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and  (p.fever_date<>'' or p.symptom_ari_date<>'') and p.culture_test_result='Negative' )  as p_neg
from ctambon as t 
join chospital as h on h.tambonfullcode=t.tamboncodefull
join campur as a on a.ampurcodefull=t.ampurcode
where t.ampurcode like '$province%' and t.flag_status=0 and h.hostype in ('18','07')  and h.status=1 order by a.ampurcodefull,h.hoscode  limit $start,$pagesize";

}else if($_REQUEST["search"]==false && $enable_select==false){

$sql_getAll = "select t.tamboncode,t.tambonname,t.tamboncodefull,h.hoscode,h.hosname,a.ampurcodefull,a.ampurname,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode) as p_all,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and record_date<>CURDATE()) as p_old,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and record_date=CURDATE()) as p_new,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and DATEDIFF(CURDATE(),date_arrival)>14) as p_qa,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and DATEDIFF(CURDATE(),date_arrival)<14) as p_wa,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and ((fever_date<>'' and fever_date<>'0000-00-00') or (symptom_ari_date<>'' and  symptom_ari_date<>'0000-00-00'))) as p_pui,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and  (p.fever_date<>'' or p.symptom_ari_date<>'') and p.culture_test_result='Positive' )  as p_pos,
(select count(cid) as cc from person_risk_group as p where p.hospcode=h.hoscode and  (p.fever_date<>'' or p.symptom_ari_date<>'') and p.culture_test_result='Negative' )  as p_neg
from ctambon as t 
join chospital as h on h.tambonfullcode=t.tamboncodefull
join campur as a on a.ampurcodefull=t.ampurcode
where $where and t.flag_status=0 and h.hostype in ('18','07')  and h.status=1 order by a.ampurcodefull,h.hoscode  limit $start,$pagesize";

}



//$sql_getAll = "select * from wru_teams where team_id!='".$_SESSION["team_id"]."' order by team_id limit $start,$pagesize";

$users_list = $db->query($sql_getAll, PDO::FETCH_OBJ);


?>

<div style="padding: 20px;height:100%">

    <div class="clearfix">

        <h2 class="float-left">
            <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-poll"
                    style="font-size: 24px;"></i></button>

            <b>สรุปข้อมูลกลุ่มเสี่ยง <?=$title?></b>
        </h2>
    </div>
    <?php

    if($enable_select==true){
?>


<div class="line"></div>
    <div class="row" style="padding:5px 20px 5px 20px;">
        <div class="col-md-2" style="padding:5px;">
            <form name="search" id="search" method="post" action="index.php?url=pages/person_risk_summary.php">
                <input type="hidden" name="search" id="search" value="true">
                <div class="form-group row">

                    <?php
        $sql_district = "select *  from campur where changwatcode='$proveID' and flag_status=0 order by ampurname ";
        $district_list = $db->query($sql_district, PDO::FETCH_OBJ);
?>
                    <label for="sex" class="col-sm-2 col-form-label">อำเภอ</label>
                    <div class="col-sm-10">
                    <div class="input-group mb-3">
                        <select class="form-control" name="distcode" id="distcode">
                            <option value="41">
                                -- ทั้งหมด --
                            </option>
                            <?php foreach ($district_list as $dl) {?>
                            <option value="<?=$dl->ampurcodefull?>"><?=$dl->ampurname?></option>
                            <?php } ?>

                        </select>
                        <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    </div>
                </div>
            </form>

        </div>
    </div>


    <hr>


<?php


    }

    ?>



    <table class="table table-sm table-striped table-bordered table-hover">
        <thead class="thead-dark" align="center">
            <tr>
                <th class="align-middle">ลำดับ</th>
                <th scope="col" class="align-middle">หน่วยบริการ</th>
                <th scope="col" class="align-middle">ตำบล</th>
                <th scope="col" class="align-middle">อำเภอ</th>
                <th scope="col" class="align-middle">กลุ่มเสี่ยง<br>สะสม</th>
                <th scope="col" class="align-middle">กลุ่มเสี่ยง<br>รายเก่า</th>
                <th scope="col" class="align-middle">กลุ่มเสี่ยง<br>รายใหม่</th>
                <th scope="col" class="align-middle">กลุ่มเสี่ยง<br>พ้นระยะ</th>
                <th scope="col" class="align-middle">กลุ่มเสี่ยง<br>เฝ้าระวังต่อ</th>
                <th scope="col" class="align-middle">กลุ่มเสี่ยง<br>เข้าเกณฑ์ PUI</th>
                <th scope="col" class="align-middle">ตรวจพบเชื้อ</th>
                <th scope="col" class="align-middle">ตรวจไม่พบเชื้อ</th>
            </tr>
        </thead>
        <tbody>







            <?php
$i=1;
foreach ($users_list as $row) {

?>
            <tr>
                <td align="center"><?=$i?></td>
                <th scope="row"><?=$row->hosname;?></th>
                <td><?=$row->tambonname;?></td>
                <td><?=$row->ampurname?></td>
                <td align="center"><?=$row->p_all;?></td>
                <td align="center"><?=$row->p_old;?></td>
                <td align="center"><?=$row->p_new;?></td>
                <td align="center"><?=$row->p_qa;?></td>
                <td align="center"><?=$row->p_wa;?></td>
                <td align="center"><?=$row->p_pui;?></td>
                <td align="center"><?=$row->p_pos;?></td>
                <td align="center"><?=$row->p_neg;?></td>
            </tr>


            <?php
            $i=$i+1;
    }
?>
        </tbody>
    </table>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link"
                    href="index.php?url=pages/person_risk_summary.php&distcode=<?=$discode?>&page=<?php if($page>1){echo $page-1;} else {echo $page;}?>">Previous</a>
            </li>
            <?php
                if ($totalpage>0) {

                    for ($i=1; $i<=$totalpage; $i++) {

                        if($i==$page) {
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_summary.php&distcode=$discode&page=$page\">$page</a></li>";
                        }else{
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_summary.php&distcode=$discode&page=$i\">$i</a></li>";
                        }

                    }
                }
            ?>
            <li class="page-item"><a class="page-link"
                    href="index.php?url=pages/person_risk_summary.php&distcode=<?=$discode?>&page=<?php if($page<$totalpage){echo $page+1;}else{echo $page;} ?>">Next</a>
            </li>


        </ul>
    </nav>
</div>