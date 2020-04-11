<?php

$loginname = $_SESSION['loginname']; 

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

}else{


}


if($_POST["updateUser"]==true){

    $password=addslashes(strip_tags(trim($_POST['password'])));
    $uline_token =addslashes(strip_tags(trim($_POST['line_token'])));
    $uname =addslashes(strip_tags(trim($_POST['uname'])));

    $check_password = $data->GetStringData("select count(loginname) as cc from users where loginname='$loginname' and password='$password'");

    if($check_password<1){
        $sql_update = "update users set uname='$uname',password=md5('$password'),line_token='$uline_token' where loginname ='$loginname'";
    }else{
        $sql_update = "update users set uname='$uname',line_token='$uline_token' where loginname ='$loginname'";
    }

    $updated = $db->prepare($sql_update);
    $updated->execute();
    $count = $updated->rowCount();


    echo "<script>alert('ปรับปรุงข้อมูลผู้ใช้งานสำเร็จแล้วครับ');</script>";
    echo "<script>window.location ='index.php?url=pages/u_profile.php'</script>";
    




}

$sql_profile = "select *,concat(h.hoscode,' : ',h.hosname) as officename 
from users as u
join users_role as r on u.role=r.role_id
join chospital as h on h.hoscode=u.office
where loginname='$loginname'";


$profiles = $db->query($sql_profile, PDO::FETCH_OBJ);

?>

<div style="padding: 20px;height:100%" id="profiles" name="profiles">

<div class="clearfix">
        <h2 class="float-left"><i class="fas fa-user-friends" style="font-size: 24px;"></i> <b>ข้อมูลผู้ใช้งาน</b>
        </h2>

    </div>
    <hr>


<form name="addUser" id="addUser" method="post">
<?php foreach ($profiles as $pf) { ?>
                    <input type="hidden" name="updateUser" id="updateUser" value="true">
                    <div class="form-group row">
                        <label for="loginname" class="col-sm-4 col-form-label">Username</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="loginname" name="loginname"
                                placeholder="ชื่อผู้ใช้งาน" value="<?=$pf->loginname?>" readonly data-toggle="modal" data-target="#alertModal">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="รหัสผ่านในการเข้าใช้งานระบบ" value="<?=$pf->password?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="uname" class="col-sm-4 col-form-label">ชื่อ - สกุล</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="uname" name="uname"
                                placeholder="ชื่อหน่วยหรือชื่อผู้ใช้งาน" value="<?=$pf->uname?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="role" class="col-sm-4 col-form-label">ระดับของการเข้าถึง</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="role" name="role"
                                placeholder="ชื่อหน่วยหรือชื่อผู้ใช้งาน" value="<?=$pf->role_name?>" readonly data-toggle="modal" data-target="#alertModal">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="office" class="col-sm-4 col-form-label">หน่วยบริการ</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="office" name="office"
                                placeholder="ชื่อหน่วยบริการ" value="<?=$pf->officename?>" readonly data-toggle="modal" data-target="#alertModal">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="line_token" class="col-sm-4 col-form-label">Line Token</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="line_token" name="line_token"
                                placeholder="Line Token สำหรับรับการแจ้งเตือน" value="<?=$pf->line_token?>">
                        </div>
                    </div>
                    <?php } ?>
                    <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> บันทึก</button>
            </div>
            

                            
            </form>

            
</div>


<!-- Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><b>คุณไม่ได้รับอณุญาต</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       คุณไม่ได้รับอนุญาตให้แก้ไขข้อมูลส่วนนี้ หากคุณท้องการแก้ไขกรุณาติดต่อผู้ดูแลระบบในอำเภอของท่านเพื่อแก้ไขข้อมูลในส่วนดังกล่าว
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


