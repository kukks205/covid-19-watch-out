<?php

if($_SESSION['role']=='001'||$_SESSION['role']=='002'){

    $hospcode = addslashes(strip_tags(trim($_REQUEST['hospcode'])));

    $enable_select =true;

    $title = $data->GetStringData("select concat('จังหวัด',changwatname) as cc from cchangwat where changwatcode='". $_SESSION['province']."'");
   
    if(strlen($_REQUEST['hospcode'])==5){

        $where = "hospcode='".$hospcode."'";
        $where2 ="provcode='".$_SESSION['province']."' and hoscode='$hospcode'  and status='1' and hostype in ('01','02','03','05','06','07','08','11','12','13','15','18')";
        $row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where $where ");

    }else{
        $where = "hospcode is not null";
        $where2 ="provcode='".$_SESSION['province']."'  and status='1' and hostype in ('01','02','03','05','06','07','08','11','12','13','15','18')";
        $row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where $where ");  
    }
    
    
}else if($_SESSION['role']=='007'||$_SESSION['role']=='003'){

    $hospcode = addslashes(strip_tags(trim($_REQUEST['hospcode'])));
    $enable_select =true;
    $title = $data->GetStringData("select concat('อำเภอ',ampurname) as cc from campur where ampurcodefull='".$_SESSION['district']."'");
 
    if(strlen($_REQUEST['hospcode'])==5){
        $where = "hospcode='".$hospcode."'";
        $where2 ="provcode='".$_SESSION['province']."' and concat(provcode,distcode)='".$_SESSION['district']."'   and status='1' and hostype in ('01','02','03','05','06','07','08','11','12','13','15','18')";
        $row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where $where ");

    }else{
        $where = "district='".$_SESSION['district']."'";
        $where2 ="provcode='".$_SESSION['province']."' and concat(provcode,distcode)='".$_SESSION['district']."' and status='1' and hostype in ('01','02','03','05','06','07','08','11','12','13','15','18')";
        $row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where $where ");  
    }


}else{
    $hospcode = $_SESSION['office'];
    $enable_select =false;
    $title = $data->GetStringData("select SUBSTRING_INDEX(hosname,' ',1) as cc from chospital where hoscode='".$_SESSION['office']."'");
    $where = "hospcode='".$_SESSION['office']."'";
    $row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where $where");

}



if(strlen($_REQUEST['hospcode'])==5 && $enable_select==true ){

    
    //$row_all = $data->GetStringData("select count(cid) as cc from person_risk_group where hospcode='$hospcode'");

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
    pr.cid,pr.fullname,pr.nation,n.nationname,pr.sex,
    pr.age,pr.address,pr.moo,pr.subdistrict,
    t.tambonname,pr.district,a.ampurname,pr.province,
    c.changwatname,pr.telphone,pr.hospcode,hos.hosname,
    pr.from_country,n2.name_th as countryname,
    pr.from_city,pr.date_arrival,pr.date_arrival_home,
    ADDDATE(pr.date_arrival,INTERVAL 14 DAY) as date_quarantine,
    pr.fever_date,pr.symptom_ari_date,pr.refer_date,pr.culture_test_result,record_date
    from person_risk_group as pr 
    join ctambon as t on pr.subdistrict=t.tamboncodefull
    join campur as a on a.ampurcodefull=t.ampurcode
    join cchangwat as c on c.changwatcode=a.changwatcode
    join chospital as hos on hos.hoscode=pr.hospcode
    join cnation as n on n.nationcode=pr.nation
    left join ccountry as n2 on n2.country_id=pr.from_country 
    where pr.hospcode='$hospcode'  order by pr.date_arrival limit $start,$pagesize"; 
    


}else{

    
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
    pr.cid,pr.fullname,pr.nation,n.nationname,pr.sex,v.villagename,v.villagecode,
    pr.age,pr.address,pr.moo,pr.subdistrict,
    t.tambonname,pr.district,a.ampurname,pr.province,
    c.changwatname,pr.telphone,pr.hospcode,hos.hosname,
    pr.from_country,n2.name_th as countryname,
    pr.from_city,pr.date_arrival,pr.date_arrival_home,
    ADDDATE(pr.date_arrival,INTERVAL 14 DAY) as date_quarantine,
    pr.fever_date,pr.symptom_ari_date,pr.refer_date,pr.culture_test_result,record_date
    from person_risk_group as pr 
    join ctambon as t on pr.subdistrict=t.tamboncodefull
    join campur as a on a.ampurcodefull=t.ampurcode
    join cchangwat as c on c.changwatcode=a.changwatcode
    join chospital as hos on hos.hoscode=pr.hospcode
    join cvillage as v on v.villagecodefull=pr.moo
    join cnation as n on n.nationcode=pr.nation
    join ccountry as n2 on n2.country_id=pr.from_country where $where order by pr.date_arrival limit $start,$pagesize";


    
}

?>

<div style="padding: 20px;height:100%">

    <div class="clearfix">

        <h2 class="float-left">
            <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-user-clock"
                    style="font-size: 24px;"></i></button>

            <b>ทะเบียนกลุ่มเสี่ยงทั้งหมด <?=$title?></b>
        </h2>
    </div>
    <div class="line"></div>
    <?php

    if($enable_select==true){
        ?>
    <form name="search" id="search" method="post" action="index.php?url=pages/person_risk_all.php">
        <input type="hidden" name="search" id="search" value="true">
        <div class="row">

            <input type="hidden" name="search" id="search" value="true">

            <div class="col-md-4" style="padding:15px 20px 0px 20px;">
                <div class="form-group row">
                <?php


                    $sql_district = "select c.hoscode,concat(c.hoscode,' : ',c.hosname) as cc  from chospital as c where $where2 group by hoscode order by hoscode ";
                    $district_list = $db->query($sql_district, PDO::FETCH_OBJ);
                ?>
                    <label for="sex" class="col-sm-4 col-form-label">หน่วยบริการ</label>
                    <div class="col-sm-8">
                        <div class="input-group mb-3">
                            <select class="form-control" name="hospcode" id="hospcode">
                                <option value="41">
                                    -- ทั้งหมด --
                                </option>
                                <?php foreach ($district_list as $dl) {?>
                                <option value="<?=$dl->hoscode?>"><?=$dl->cc?></option>
                                <?php } ?>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit"><i
                                        class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2" style="padding:15px 20px 0px 20px;">
           <div class="col-sm-12">
                <div class="form-group" style="padding-top: 5px">
                 <a href="udonthani3_xls.php?hospcode=<?=$hospcode?>" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> ส่งออก Excel</a>
                </div>
                
            </div>
            </div>
        </div>
    </form>
    <div class="line"></div>
<?php

    }else{

?>

<div class="row">

<div class="col-md-2" style="padding:15px 20px 0px 20px;">
            <div class="col-sm-12">
                <div class="form-group" style="padding-top: 5px">
               <a href="udonthani3_xls.php?hospcode=<?=$hospcode?>" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> ส่งออก Excel</a>
                </div>
                
            </div>
            </div>
</div>

<?php

    }

    ?>
 <?php


 $users_list = $db->query($sql_getAll, PDO::FETCH_OBJ);

?>

<div id="SiXhEaD_Excel" align=center x:publishsource="Excel">
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
                <th scope="col">ถึงไทย</th>
                <th scope="col">ถึงบ้าน</th>
                <th scope="col">ครบเฝ้าระวัง</th>
                <th></th>
            </tr>
        </thead>
        <tbody>







            <?php

foreach ($users_list as $row) {

?>
            <tr>
                <th scope="row"><?=$row->cid;?></th>
                <td><?=$row->fullname;?></td>
                <td><?=$row->age;?></td>
                <td><?=$row->address;?></td>
                <td><?=$row->moo;?></td>
                <td><?=$row->tambonname;?></td>
                <td><?=$row->telphone;?></td>
                <td><?=$row->countryname;?></td>
                <td><?=$row->from_city;?></td>
                <td><?=$fdate->FormatThaiDate($row->date_arrival);?></td>
                <td><?=$fdate->FormatThaiDate($row->date_arrival_home);?></td>
                <td><?=$fdate->FormatThaiDate($row->date_quarantine);?></td>
                <td><a href="index.php?url=pages/register_detail.php&cid=<?=$row->cid?>" class="btn btn-sm btn-success "><i class="fas fa-user-check"></i></a></td>
            </tr>


            <?php
    }
?>
        </tbody>
    </table>
</div>
    
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link"
                    href="index.php?url=pages/person_risk_all.php&hospcode=<?=$hospcode?>&page=1">First</a>
            </li>
            <li class="page-item">
                <a class="page-link"
                    href="index.php?url=pages/person_risk_all.php&hospcode=<?=$hospcode?>&page=<?php if($page>1){echo $page-1;} else {echo $page;}?>">Previous</a>
            </li>
            <?php
                if ($totalpage>0&&$totalpage<=10) {

                    for ($i=1; $i<=$totalpage; $i++) {

                        if($i==$page) {
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                        }else{
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=$i\">$i</a></li>";
                        }

                    }
                } else if($totalpage>10) {

                    for($i=1; $i<=$totalpage; $i++){
                        if($i==$page&&$i==1&&$i<$totalpage-3){
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+2).'">'.($page+2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+3).'">'.($page+3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+4).'">'.($page+4).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+5).'">'.($page+5).'</a></li>';
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }else
                        if($i==$page&&$i==2&&$i<$totalpage-3){
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=1\">1</a></li>";
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+2).'">'.($page+2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+3).'">'.($page+3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+4).'">'.($page+4).'</a></li>';
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }else
                        if($i==$page&&$i==3&&$i<$totalpage-3){
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=1\">1</a></li>";
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=2\">2</a></li>";
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+2).'">'.($page+2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+3).'">'.($page+3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }else if($i==$page&&$i>3&&$i<$totalpage-3){

                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-3).'">'.($page-3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-2).'">'.($page-2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-1).'">'.($page-1).'</a></li>';
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+2).'">'.($page+2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+3).'">'.($page+3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }else if($i==$page&&$i<=$totalpage-3){
                            echo "<a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=<?=$hospcode?>&page=1\">First</a>";
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-3).'">'.($page-3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-2).'">'.($page-2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-1).'">'.($page-1).'</a></li>';
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+2).'">'.($page+2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }else if($i==$page&&$i<=$totalpage-2){
                            echo "<a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=<?=$hospcode?>&page=1\">First</a>";
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-4).'">'.($page-4).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-3).'">'.($page-3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-2).'">'.($page-2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-1).'">'.($page-1).'</a></li>';
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page+1).'">'.($page+1).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.$totalpage.'">'.$totalpage.'</a></li>';
                        }else if($i==$page&&$i<=$totalpage){
                            echo "<a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=<?=$hospcode?>&page=1\">1</a>";
                            echo '<li class="page-item"><a class="page-link">...</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-4).'">'.($page-5).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-4).'">'.($page-4).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-3).'">'.($page-3).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-2).'">'.($page-2).'</a></li>';
                            echo '<li class="page-item"><a class="page-link" href="index.php?url=pages/person_risk_all.php&hospcode='.$hospcode.'&page='.($page-1).'">'.($page-1).'</a></li>';
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/person_risk_all.php&hospcode=$hospcode&page=$page\">$page</a></li>";
                        }
                    }

                }
            ?>
            <li class="page-item"><a class="page-link"
                    href="index.php?url=pages/person_risk_all.php&hospcode=<?=$hospcode?>&page=<?php if($page<$totalpage){echo $page+1;}else{echo $page;} ?>">Next</a>
            </li>
            <li class="page-item">
                                        <a class="page-link"
                                        href="index.php?url=pages/person_risk_all.php&hospcode=<?=$hospcode?>&page=<?=$totalpage?>">Last</a>
             </li>

        </ul>
    </nav>
</div>
</div>