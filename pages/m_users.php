<?php

$prov_code =$_SESSION['province'];
$dist_code =$_SESSION['district'];

if($_GET["rmUser"]==true && isset($_GET["loginname"])){

    $rm_id=$_GET["loginname"];

    $sql_remove_user ="delete from users where loginname='$rm_id'";
    $rmuser = $db->prepare($sql_remove_user);
    $rmuser->execute();
    $rmcount = $rmuser->rowCount();
    echo "<script>alert('ลบผู้ใช้งานสำเร็จจำนวน $rmcount รายการ');</script>";
    echo "<script>window.location ='index.php?url=pages/m_users.php' </script>";
}


if($_POST["createUser"]==true){

    $loginname = addslashes(strip_tags(trim($_POST['loginname'])));
    $uname= addslashes(strip_tags(trim($_POST['uname'])));
    $office = addslashes(strip_tags(trim($_POST['office'])));
    $password = addslashes(strip_tags(trim($_POST['password'])));
    $province =$_SESSION['province'];
    $subdistrictcode = addslashes(strip_tags(trim($_POST['subdistrictcode'])));
    $role = addslashes(strip_tags(trim($_POST['role'])));
    

    $checkTeamID = $data->GetStringData("select count(loginname) as cc from users where loginname='$team_id'");

    $districtcode = $data->GetStringData("select districtcode as cc from (select hoscode,concat('[',hoscode,'] ',hosname) as  hospname,
    provcode,concat(provcode,distcode) as districtcode,
    concat(provcode,distcode,subdistcode) as subdistrictcode
    from chospital where hostype in (18,07,05)) as  hos
    where hoscode='$office' limit 1");
    $subdistrictcode = $data->GetStringData("select subdistrictcode as cc from (select hoscode,concat('[',hoscode,'] ',hosname) as  hospname,
    provcode,concat(provcode,distcode) as districtcode,
    concat(provcode,distcode,subdistcode) as subdistrictcode
    from chospital where hostype in (18,07,05)) as  hos
    where hoscode='$office' limit 1");

    if($checkTeamID>0){

        echo "<script>alert('มีชื่อผู้ใช้งานนี้อยู่ในระบบแล้วไม่สามารถเพิ่มได้ !!');</script>";

    }else{

        $sql_createuser = "insert into users(loginname,uname,office,password,province,districtcode,subdistrictcode,role) values('$loginname','$uname','$office',MD5('$password'),'$province','$districtcode','$subdistrictcode','$role')";

        $adduser = $db->prepare($sql_createuser);
        $adduser->execute();
        $count = $adduser->rowCount();
        echo "<script>alert('เพิ่มผู้ใช้งานสำเร็จ $count รายการ');</script>";
        echo "<script>window.location ='index.php?url=pages/m_users.php' </script>";
    }
}


if($_SESSION['role']=='001'){

    $row_all = $data->GetStringData("select  count(loginname) as cc from users as u ");
    $where = "province='$prov_code'";
    $where2 =  "provcode='$prov_code'";
    $sql_role = "select * from users_role order by role_name";


}else if($_SESSION['role']=='007'){

    $row_all = $data->GetStringData("select  count(loginname) as cc from users as u where districtcode='$dist_code' ");
    $where = "districtcode='$dist_code'";
    $where2 =  "districtcode='$dist_code'";
    $sql_role = "select * from users_role where role_id<>'001' order by role_name";

}



//$row_all = $data->GetStringData("select  count(loginname) as cc from users as u ");


$pagesize = 10;
$totalpage=ceil($row_all/$pagesize);
$page=$_GET['page'];
if(!isset($page)) {
   $page=1;
   $start=0;
} else {
   $start=($page-1)*$pagesize;
}

$sql_getAll = "select  loginname,uname,office,h.hosname,u.role,r.role_name
from users as u 
join users_role as r on r.role_id=u.role
join chospital as h on h.hoscode=u.office where $where  order by office limit $start,$pagesize";

$users_list = $db->query($sql_getAll, PDO::FETCH_OBJ);


if($_SESSION['role']=='001' || $_SESSION['role']=='007' ){

?>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-user-plus"></i>
                    <b>เพิ่มผู้ใช้งาน COVID-19-WO</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="addUser" id="addUser" method="post">
                    <input type="hidden" name="createUser" id="createUser" value="true">
                    <div class="form-group row">
                        <label for="loginname" class="col-sm-4 col-form-label">Username</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="loginname" name="loginname"
                                placeholder="ชื่อผู้ใช้งาน">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="รหัสผ่านในการเข้าใช้งานระบบ">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="uname" class="col-sm-4 col-form-label">ชื่อ - สกุล</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="uname" name="uname"
                                placeholder="ชื่อหน่วยหรือชื่อผู้ใช้งาน">
                        </div>
                    </div>


                    <?php
                    
                    $role_list = $db->query($sql_role, PDO::FETCH_OBJ);
                    ?>

                    <div class="form-group row">
                        <label for="role" class="col-sm-4 col-form-label">ระดับของการเข้าถึง</label>
                        <div class="col-sm-8">
                        <select class="form-control" name="role" id="role">
                            <option>
                                -- โปรดระบุระดับของการเข้าถึง --
                            </option>
                            <?php foreach ($role_list as $rn) {?>

                            <option value="<?=$rn->role_id?>"><?=$rn->role_name?></option>

                            <?php } ?>

                        </select>
                        </div>

                    </div>

                    <?php
                    $sql_office = "select * from (select hoscode,concat(hoscode,': ',hosname) as  hospname, provcode,
                    concat(provcode,distcode) as districtcode,
                    concat(provcode,distcode,subdistcode) as subdistrictcode
                    from chospital where hostype in ('01','02','05','06','07','18') and status=1) as  hos
                    where $where2";
                    $office_list = $db->query($sql_office, PDO::FETCH_OBJ);
                    ?>

                    <div class="form-group row">
                        <label for="office" class="col-sm-4 col-form-label">หน่วยบริการ</label>
                        <div class="col-sm-8">
                        <select class="form-control" name="office" id="office">
                            <option>
                                -- โปรดระบุหน่วยบริการ --
                            </option>
                            <?php foreach ($office_list as $orn) {?>

                            <option value="<?=$orn->hoscode?>"><?=$orn->hospname?></option>

                            <?php } ?>

                        </select>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="line_token" class="col-sm-4 col-form-label">Line Token</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="line_token" name="line_token"
                                placeholder="Line Token สำหรับรับการแจ้งเตือน">
                        </div>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-undo"></i>
                    ยกเลิก</button>
                <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> บันทึก</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div style="padding: 20px;height:100%">
    <div class="clearfix">
        <h2 class="float-left"><i class="fas fa-user-friends" style="font-size: 24px;"></i> <b>จัดการข้อมูลผู้ใช้งาน</b>
        </h2>
        <div class="float-right">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter">
                <i class="fas fa-user-plus"></i>
                <b>เพิ่มผู้ใช้งาน</b></button>
        </div>

    </div>
    <hr>


    <table class="table table-sm table-striped table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">username</th>
                <th scope="col">ชื่อ - สกุล</th>
                <th scope="col">รหัสหน่วยบริการ</th>
                <th scope="col">ชื่อหน่วยบริการ</th>
                <th scope="col">สิทธิ์การเข้าถึง</th>
                <th></th>
            </tr>
        </thead>
        <tbody>







            <?php

foreach ($users_list as $row) {

?>
            <tr>
                <th scope="row"><?=$row->loginname;?></th>
                <td><?=$row->uname;?></td>
                <td><?=$row->office;?></td>
                <td><?=$row->hosname;?></td>
                <td><?=$row->role_name;?></td>
                <td><a href="index.php?url=pages/m_users.php&rmUser=true&loginname=<?=$row->loginname;?>" type="button"
                        class="btn btn-danger btn-sm"><i class="fas fa-user-times"></i> ลบ</a>
                        <a href="#" type="button" class="btn btn-info btn-sm"><i class="fas fa-user-edit"></i> แก้ไข</a></td>
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
                    href="index.php?url=pages/m_users.php&page=<?php if($page>1){echo $page-1;} else {echo $page;}?>">Previous</a>
            </li>
            <?php
                if ($totalpage>0) {

                    for ($i=1; $i<=$totalpage; $i++) {

                        if($i==$page) {
                            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"index.php?url=pages/m_users.php&page=$page\">$page</a></li>";
                        }else{
                            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?url=pages/m_users.php&page=$i\">$i</a></li>";
                        }

                    }
                }
            ?>
            <li class="page-item"><a class="page-link"
                    href="index.php?url=pages/m_users.php&page=<?php if($page<$totalpage){echo $page+1;}else{echo $page;} ?>">Next</a>
            </li>


        </ul>
    </nav>




    <?php
        }else{

    ?>
    <h2><i class="fas fa-user-friends" style="font-size: 24px;"></i> <b>จัดการข้อมูลผู้ใช้งาน</b></h2>
    <hr>
    <div class="alert alert-danger" role="alert">
        <h4><b>คุณไม่มีสิทธิ์ในการเข้าใช้งานในส่วนการจัดการข้อมูลผู้ใช้งาน </b></h4>
        <hr>
        <p>หากคุณต้องการเข้าใช้งานเมนูจัดการข้อมูลผู้ใช้งาน
            กรุณาลงชื่อเข้าใช้งานด้วยชื่อผู้ใช้งานที่สิทธิ์การใช้งานเป็นผู้ดูแลระบบ(Admin)
            หรือหากต้องการแก้ไขข้อมูลส่วนบุคคลของท่านกรุณาติดต่อผู้ดูแลระบบอีกครั้งเพื่อทำการแก้ไขข้อมูลดังกล่าว</p>
    </div>

    <?php
        }
     ?>

</div>