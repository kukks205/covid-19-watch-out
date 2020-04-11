<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGKxpbDZt-KFF6DRvJtqGC2saTW8x9cyc"></script>

<script type="text/javascript">
$.datepicker.setDefaults( $.datepicker.regional[ "th" ] );
    $(function () {
        var d = new Date();
        $("#date_arrival").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: d
        });

        $("#date_arrival_home").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: d
        });
        $("#fever_date").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: d
        });
        $("#symptom_ari_date").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: d
        });
        $("#refer_date").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: d
        });

    });

    function showHide(divID) {
        var x = document.getElementById(divID);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function editable(divID) {
        //document.getElementById('cid').readonly = false;
        var r = confirm("คุณต้องการแก้ไข CID ใช่หรือไม่?");
        if (r == true) {
            txt = "You pressed OK!";
            document.getElementById('cid').removeAttribute('readonly');
        } else {
            txt = "You pressed Cancel!";
        }
        
    };


    function resetValue(divID){
                document.getElementById(divID).value = null;
    }


    function delCase(cid){
            $.ajax({
                url: "del_case.php",
                global: false,
                type: "GET",
                data: ({
                    cid: cid
                }),
                dataType: "JSON",
                async: false,
                success: function (data) {
                    if(data.status=='ok'){
                        window.location='index.php?url=pages/person_risk_all.php';
                    }else{
                        document.getElementById('status').style.display = 'block';
                    }

                    console.log(data.status);
                    //alert(JSON.stringify(data));
                }
            });


        }


</script>


<?php


$proveID= $_SESSION['province'];

function toYdate($thDate){
    $e = explode("/",$thDate);
    $y=($e[2]);
    $cDate = $y.'-'.$e[1].'-'.$e[0];
    return $cDate;
}

function toBdate($yDate){

    $e = explode("-",$yDate);
    $y=($e[0]);
    $cDate = $e[2].'/'.$e[1].'/'.$y;
    return $cDate;

}


$ocid = $_GET['cid'];


if($_POST["register"]==true){


    if($_POST['fever_date']==''||$_POST['fever_date']=='0000-00-00'){
        $fever_date='';
    }else{
        $fever_date = toYdate($_POST['fever_date']);
    }

    if($_POST['refer_date']==''||$_POST['refer_date']=='0000-00-00'){
        $refer_date='';
    }else{
        $refer_date = toYdate($_POST['refer_date']);
    }
    
    if($_POST['symptom_ari_date']==''||$_POST['symptom_ari_date']=='0000-00-00'){
        $symptom_ari_date='';
    }else{

        $symptom_ari_date=toYdate($_POST['symptom_ari_date']);

    }

    if($_POST['culture_test_result']!='Negative'&& $_POST['culture_test_result']!='Positive' ){
       $culture_test_result='';
   }else{
        $culture_test_result = $_POST['culture_test_result'];
   }



    if($_POST['date_arrival']==''||$_POST['date_arrival']=='0000-00-00'){
        
        $date_arrival = date("Y-m-d");

    }else{
        $date_arrival=toYdate($_POST['date_arrival']);
    }

    if($_POST['date_arrival_home']==''||$_POST['date_arrival_home']=='0000-00-00'){
        $date_arrival_home = date("Y-m-d");
    }else{

        $date_arrival_home=toYdate($_POST['date_arrival_home']);

    }


    if($_POST['risk_place']<1){

        $risk_place_id=1;
    }else{

        $risk_place_id =  addslashes(strip_tags(trim($_POST['risk_place'])));

    }

    if(strlen($risk_type_id)<4){
        $risk_type_id =  addslashes(strip_tags(trim($_POST['risk_type_id'])));
    }else{
        $risk_type_id = '';
    }


        $who_record = $_SESSION['loginname'];

        $cid = addslashes(strip_tags(trim($_POST['cid'])));
        $ocid = addslashes(strip_tags(trim($_POST['ocid'])));
        $fullname = addslashes(strip_tags(trim($_POST['fullname'])));
        $sex = addslashes(strip_tags(trim($_POST['sex'])));
        $age = addslashes(strip_tags(trim($_POST['age'])));
        $nation = addslashes(strip_tags(trim($_POST['nation'])));
        $province = addslashes(strip_tags(trim($_POST['province'])));
        $district = addslashes(strip_tags(trim($_POST['district'])));
        $subdistrict = addslashes(strip_tags(trim($_POST['subdistrict'])));
        $moo = addslashes(strip_tags(trim($_POST['moo'])));
        $address = addslashes(strip_tags(trim($_POST['address'])));
        //$risk_type_id=addslashes(strip_tags(trim($_POST['risk_type_id'])));
        $telphone = addslashes(strip_tags(trim($_POST['telphone'])));
        $hospcode = addslashes(strip_tags(trim($_POST['hospcode'])));
        $from_country = addslashes(strip_tags(trim($_POST['from_country'])));
        $from_city = addslashes(strip_tags(trim($_POST['from_city'])));
        $lat = addslashes(strip_tags(trim($_POST['lat'])));
        $lng = addslashes(strip_tags(trim($_POST['lng'])));
        $from_prove =  addslashes(strip_tags(trim($_POST['from_prove'])));








$checkCID = $data->GetStringData("select count(cid) as cc from person_risk_group where cid='$ocid'");

if($checkCID<1){

    echo "<script>alert('บุคคลนี้ยังไม่เคยมีการลงทะเบียนเพื่อเฝ้าระวังครับ !!');</script>";

}else{

        $sql_register = "update person_risk_group set 
        fullname='$fullname',
        cid='$cid',
        nation='$nation',
        sex='$sex',
        age='$age',
        address='$address',
        moo='$moo',
        subdistrict='$subdistrict',
        district='$district',
        province='$province',
        telphone='$telphone',
        hospcode='$hospcode',
        risk_type_id='$risk_type_id',
        risk_place_id='$risk_place_id',
        from_country='$from_country',
        from_prove='$from_prove',
        from_city='$from_city',
        date_arrival='$date_arrival',
        date_arrival_home='$date_arrival_home',
        date_quarantine=ADDDATE('$date_arrival_home',INTERVAL 14 DAY),
        fever_date='$fever_date',
        symptom_ari_date='$symptom_ari_date',
        refer_date='$refer_date',
        culture_test_result='$culture_test_result',
        who_record='$who_record',
        lat = '$lat',
        lng='$lng'
        where cid='$ocid'";

    $register = $db->prepare($sql_register);
    $register->execute();
    $count = $register->rowCount();
    echo "<script>alert('ปรับปรุงข้อมูลสำเร็จ  $count คน');</script>";
    echo "<script>window.location ='index.php?url=pages/register_detail.php&cid=$cid'</script>";
}
}

    $cid = $_GET['cid'];

    $sql_person = "select * from person_risk_group where cid='$cid'";
    $person_detail = $db->query($sql_person, PDO::FETCH_OBJ);


?>


<div style="padding: 20px;height:100%">
    <div class="clearfix">
        <h2 class="float-left">


            <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-user-edit"
                    style="font-size: 24px;"></i></button>

            <b><a href="javascript:history.go(-1)" class="navbar-brand" href="index.php" style="font-size:1.2rem;">
                    <i class="fas fa-angle-left"></i></a>แก้ไขข้อมูลลงทะเบียนกลุ่มเสี่ยง</b>

        </h2>
    </div>
    <hr>


    <div class="card">
        <div class="card-body">
            <h5><i class="fas fa-id-card"></i><b> ข้อมูลทั่วไป</b></h5>
            <div class="line"></div>
            <?php foreach ($person_detail as $person) { 
            
            $province = $person->province;
            $district = $person->district;
            if($person->subdistrict){
                $subdistrict = $person->subdistrict;
            }else{

                $subdistrict = $_SESSION['subdistrict'];
            }
            
            if($person->moo){
                $moo = $person->moo;
            }else{
                $moo='99999999';
            }

            
            $hospcode = $person->hospcode;
        ?>
            <form name="addUser" id="addUser" method="post" action="index.php?url=pages/register_detail.php">
                <input type="hidden" name="register" id="register" value="true">
                <input type="hidden" name="ocid" id="ocid" value="<?=$ocid?>">

                <!--rows1--->
                <div class="row">
                    <div class="col-md-4" style="padding:5px;">
                        <div class="form-group">
                            <label for="cid" class="form-label">CID</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="cid" name="cid" value="<?=$person->cid?>"
                                    placeholder="เลขประจำตัวประชาชน" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="editable();"><i
                                            class="fas fa-edit"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="col-md-4" style="padding:5px;">
                        <div class="form-group">
                            <label for="fullname" class="form-label">ชื่อ-สกุล</label>
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                value="<?=$person->fullname?>" placeholder="ชื่อ-สกุล">
                        </div>
                    </div>
                    <div class="col-md-1" style="padding:5px;">
                        <div class="form-group">
                            <label for="sex" class="form-label">เพศ</label>
                            <select class="form-control" name="sex" id="sex">
                                <option value="<?=$person->sex?>">
                                    <?php if($person->sex==1){echo 'ชาย';}else{ echo 'หญิง';}?></option>
                                <option value="1">ชาย</option>
                                <option value="2">หญิง</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-1" style="padding:5px;">
                        <div class="form-group">
                            <label for="age" class="form-label">อายุ(ปี)</label>
                            <input type="number" class="form-control" id="age" name="age" placeholder="อายุ(ปี)"
                                value="<?=$person->age?>">
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">

                        <?php
                        $sql_nation = "select * from cnation order by nationname";
                        $nation_list = $db->query($sql_nation, PDO::FETCH_OBJ);
                    ?>

                        <div class="form-group">
                            <label for="nation" class="form-label">สัญชาติ</label>
                            <select class="form-control" name="nation" id="nation">

                                <option value="<?=$person->nation?>">
                                    <?php echo $data->GetStringData("select nationname as cc from cnation where nationcode='$person->nation'")?>
                                </option>
                                <?php foreach ($nation_list as $rn) {?>

                                <option value="<?=$rn->nationcode?>"><?=$rn->nationname?></option>

                                <?php } ?>

                            </select>

                        </div>


                    </div>

                </div>
                <div class="line"></div>
                <h5><i class="fas fa-landmark"></i><b> ที่อยู่และการติดต่อ</b></h5>
                <div class="line"></div>
                <!--rows2--->
                <div class="row">

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="province" class="form-label">จังหวัด</label>
                            <select name="province" id="province" class="form-control">
                                <option value="<?=$person->province?>" selected="selected">
                                    <?php echo $data->GetStringData("select changwatname as cc from cchangwat where changwatcode='$person->province'");?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                        <?php   $sql_distric= "select * from campur  where changwatcode='$proveID' and flag_status=0 order by ampurname";
                        
                                 $distric_list = $db->query($sql_distric, PDO::FETCH_OBJ);
                        ?>
                            <label for="district" class="form-label">อำเภอ</label>
                            <select name="district" id="district" class="form-control">
                                <option value="<?=$person->district?>" selected="selected">
                                    <?php echo $data->GetStringData("select ampurname as cc from campur where ampurcodefull='$person->district'");?>
                                </option>
                                <?php foreach ($distric_list as $dc) {?>
                                <option value="<?=$dc->ampurcodefull?>"><?=$dc->ampurname?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                        <?php
                        $sql_subdist = "select  tamboncodefull as subdistrict,tambonname  from ctambon where ampurcode='$person->district' and flag_status=0";

                        $subdist_list = $db->query($sql_subdist, PDO::FETCH_OBJ);
                        ?>
                            <label for="subdistrict" class="form-label">ตำบล</label>
                            <select class="form-control" name="subdistrict" id="subdistrict">
                                <option value="<?=$person->subdistrict?>" selected="selected">
                                    <?php echo $data->GetStringData("select tambonname as cc from ctambon where tamboncodefull='$person->subdistrict'");?>
                                </option>
                                <?php foreach ($subdist_list as $sd) {?>
                                <option value="<?=$sd->subdistrict?>"><?=$sd->tambonname?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">


                        <?php
                        $sql_villages = "select concat(villagecode,' บ้าน',villagename) as moo,villagecodefull from cvillage as v where tamboncode='$person->subdistrict' and flag_status=0";

                        $villages_list = $db->query($sql_villages, PDO::FETCH_OBJ);
                        ?>


                            <label for="moo" class="form-label">หมู่ที่</label>
                            <select class="form-control" name="moo" id="moo">
                                <option value="<?=$person->moo?>" selected="selected">
                                    <?php echo $data->GetStringData("select concat(villagecode,' บ้าน',villagename) as cc from cvillage where villagecodefull='$person->moo'");?>
                                </option>
                                <?php foreach ($villages_list as $vl) {?>
                                <option value="<?=$vl->villagecodefull?>"><?=$vl->moo?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="address" class="form-label">บ้านเลขที่</label>

                            <input type="text" class="form-control" id="address" name="address" placeholder="บ้านเลขที่"
                                value="<?=$person->address?>">

                        </div>
                    </div>
                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="telphone" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" class="form-control" id="telphone" name="telphone"
                                placeholder="เบอร์โทร์ศัพท์" value="<?=$person->telphone?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php

                        $sql_hid = "select p.HID as cc from person_risk_group as g 
                        join person as p on p.CID=g.cid
                        join home as h on h.HID=p.HID and h.HOSPCODE=p.HOSPCODE
                        where p.HOSPCODE=g.hospcode and g.cid='$cid' and(h.LATITUDE is not null and h.LATITUDE<>0) group by p.HID";
                        $hid = $data->GetStringData($sql_hid);



                        if($person->lat){

                            $vlat = $person->lat;
                            $vlng = $person->lng; 
                        
                        }else{

                            if($hid>0){
                                $vlat = $data->GetStringData("select h.LATITUDE as cc from person as p join home as h on p.HID=h.HID and p.HOSPCODE=h.HOSPCODE where p.cid='$cid' limit 1");
                                $vlng = $data->GetStringData("select h.LONGITUDE as cc from person as p join home as h on p.HID=h.HID and p.HOSPCODE=h.HOSPCODE where p.cid='$cid' limit 1");   
                            } else {
                                $vlat = $data->GetStringData("select lat as cc from village_coordinates where village_code='$moo'");
                                $vlng = $data->GetStringData("select lng as cc from village_coordinates where village_code='$moo'");   
                            }    

                        }
                ?>


                    <div class="col-md-4" style="padding:5px;">
                        <div class="form-group">
                            <label for="cid" class="form-label">พิกัดบ้าน</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" type="button" id="showmap" name="showmap"
                                        onclick="showHide('mapshow');">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control" id="lat" name="lat" placeholder="Latitude"
                                    value="<?=$person->lat?>">
                                <input type="text" class="form-control" id="lng" name="lng" placeholder="Longitude"
                                    value="<?=$person->lng?>">

                            </div>
                        </div>
                    </div>

                    <?php
                     $numpersonOut = $data->GetStringData("select count(distinct p.CID)  as cc from person as p
                                join (select h.HID,h.HOSPCODE from person as p join home as h on h.HID=p.HID where cid='$cid' and h.HOSPCODE=p.HOSPCODE limit 1) as h on h.HID=p.HID
                                where p.HOSPCODE=h.HOSPCODE and p.cid<>'$cid' and p.TYPEAREA not in (1,3)");
                    $numpersonIn = $data->GetStringData("select count(distinct p.CID)  as cc from person as p
                                join (select h.HID,h.HOSPCODE from person as p join home as h on h.HID=p.HID where cid='$cid' and h.HOSPCODE=p.HOSPCODE limit 1) as h on h.HID=p.HID
                                where p.HOSPCODE=h.HOSPCODE and p.cid<>'$cid' and p.TYPEAREA in (1,3)");
                    ?>
                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="cid" class="form-label">จำนวนสมาชิกในบ้าน(คน)</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" type="button" id="showmap" name="showmap"
                                        onclick="showHide('fmembers')">
                                        <i class="fas fa-users"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control"
                                    value="<?='อยู่ '.$numpersonIn.' คน ไม่อยู่ '.$numpersonOut.' คน'?>" readonly>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row" style="display:none;text-align:center;" id="mapshow" name="mapshow">
                    <div class="col-md-12">
                        <div class="row" id="dvMap" style="width: 100%; height: 400px"></div>
                    </div>
                </div>

                <?php

                    $sql_members = "select p.CID,concat(c.prename,p.NAME,'  ',p.LNAME) as mname,p.SEX,p.TYPEAREA,
                    YEAR(CURDATE()) -YEAR(p.BIRTH) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(p.BIRTH), '-', DAY(p.BIRTH)) ,'%Y-%c-%e') > CURDATE(), 1, 0) as age
                    from person as p
                    join (select h.HID,h.HOSPCODE from person as p join home as h on h.HID=p.HID where cid='$cid' and h.HOSPCODE=p.HOSPCODE limit 1) as h on h.HID=p.HID
                    join cprename as c on c.id_prename=p.PRENAME
                    where p.HOSPCODE=h.HOSPCODE and p.cid<>'$cid' and p.DISCHARGE=9 order by p.BIRTH";

                    $n = 1;
                    $members_list = $db->query($sql_members, PDO::FETCH_OBJ);

                ?>
                <div class="row" style="display:none;" id="fmembers" name="fmembers">
                    <div class="col-md-12">
                        <table class="table table-sm table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width='100px' style="text-align: center;">ลำดับ</th>
                                    <th style="text-align: center;">ชื่อ - สกุล</th>
                                    <th style="text-align: center;">อายุ</th>
                                    <th style="text-align: center;">การอยู่อาศัย</th>
                                    <th style="text-align: center;">เพิ่มเป็นผู้สัมผัส</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($members_list as $ml) {
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?=$n?></td>
                                    <td><?=$ml->mname;?></td>
                                    <td style="text-align: center;"><?=$ml->age;?></td>
                                    <td style="text-align: center;">
                                        <?php if($ml->TYPEAREA==1||$ml->TYPEAREA==3){ echo 'อยู่';}else{ echo 'ไม่อยู่';}?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" onclick="alert('OK')"
                                            id="contract" name="contract"><i class="fas fa-user-plus"></i></button>

                                    </td>
                                </tr>
                                <?php $n = $n+1; } ?>
                            </tbody>

                        </table>
                    </div>
                </div>



                <div class="line"></div>
                <h5><i class="fas fa-plane-arrival"></i><b> ข้อมูลการเดินทาง</b></h5>
                <div class="line"></div>
                <!--rows3--->
                <div class="row">
                    <div class="col-md-2" style="padding:5px;">
                        <?php
                        $sql_country = "select country_id,concat(name_th,' [',iso3166,']') as cname from ccountry order by name_th";
                        $country_list = $db->query($sql_country, PDO::FETCH_OBJ);
                    ?>
                        <div class="form-group">
                            <label for="from_country" class="form-label">เดินทางมาจากประเทศ</label>
                            <select class="form-control" name="from_country" id="from_country">
                                <option value="<?=$person->from_country?>" selected="selected">
                                    <?php echo $data->GetStringData("select concat(name_th,' [',iso3166,']') as cc from ccountry where country_id='$person->from_country'");?>
                                </option>

                                <option>
                                    --ระบุประเทศ--
                                </option>
                                <?php foreach ($country_list as $rc) {?>
                                <option value="<?=$rc->country_id?>"><?=$rc->cname?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    
                    <?php if($person->from_country==764){
                            $displayP = 'block';
                            $displayC = 'none';
                            }else{
                            $displayP ='none';
                            $displayC = 'block';
                            }
                       ?>
                    <div class="col-md-2"  style="padding:5px;display:<?=$displayC?>;"  id="city" >
                        <div class="form-group">
                            <label for="from_city" class="form-label">เมือง/รัฐ</label>
                            <input type="text" class="form-control" id="from_city" name="from_city"
                                placeholder="กรุณาพิมพ์ชื่อ เมือง/รัฐ" value="<?=$person->from_city?>">
                        </div>
                    </div>

                    
                    <div class="col-md-2" style="padding:5px;display:<?=$displayP?>;" id="prov" name="prov">
                        <?php
                        $sql_prov = "select * from cchangwat order by changwatname";
                        $prov_list = $db->query($sql_prov, PDO::FETCH_OBJ);
                         ?>
                        <div class="form-group">
                            <label for="from_country" class="form-label">จังหวัด</label>
                            <select class="form-control" name="from_prove" id="from_prove">
                                <option value="<?=$person->from_prove?>" selected="selected">
                                    <?=$data->GetStringData("select  changwatname as cc from cchangwat where changwatcode='".$person->from_prove."'");?>
                                </option>
                                <?php foreach ($prov_list as $rp) {?>
                                <option value="<?=$rp->changwatcode?>"><?=$rp->changwatname?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-2" style="padding:5px;display:<?=$display?>;" id="place" name="place">
 
                        <div class="form-group">
                            <label for="risk_place" class="form-label">จุดเสี่ยง</label>
                            <select name="risk_place" id="risk_place" class="form-control">
                                <option value="<?=$person->risk_place_id?>"><?=$data->GetStringData("select  name as cc from core_riskplace where id='".$person->risk_place_id."'");?></option>                            
                            </select>
                        </div>

                    </div>


                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="date_arrival" class="form-label">วันที่ถึงไทย</label>
                            <input type="text" class="form-control" id="date_arrival" name="date_arrival"
                                placeholder="วันที่ถึงไทย"
                                value="<?php if(isset($person->date_arrival)){ echo toBdate($person->date_arrival);}?>"
                                readonly>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="date_arrival_home" class="form-label">วันที่ถึงบ้าน</label>
                            <input type="text" class="form-control" id="date_arrival_home" name="date_arrival_home"
                                value="<?php if(isset($person->date_arrival_home)){ echo toBdate($person->date_arrival_home);}?>"
                                readonly>
                        </div>
                        
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="date_quarantine" class="form-label">วันครบเฝ้าระวัง 14 วัน</label>
                            <input type="text" class="form-control" id="date_quarantine" name="date_quarantine"
                                value="<?=toBdate($person->date_quarantine)?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">

                        <?php
                        $sql_hospcode = "select hoscode,concat('[',hoscode,'] ',hosname) as  hospname from chospital as h where h.hostype in (18,13,08,07,06,05,03) and status=1 and concat(provcode,distcode)='$person->district'";

                        $hosp_list = $db->query($sql_hospcode, PDO::FETCH_OBJ);
                        ?>



                            <label for="hospcode" class="form-label">หน่วยบริการที่รับผิดชอบ</label>
                            <select class="form-control" name="hospcode" id="hospcode">
                                <option value="<?=$person->hospcode?>" selected="selected">
                                    <?php echo $data->GetStringData("select concat('[',hoscode,'] ',hosname) as  cc from chospital where hoscode='$hospcode'");?>
                                </option>
                                <?php foreach ($hosp_list as $hl) {?>
                                <option value="<?=$hl->hoscode?>"><?=$hl->hospname?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="line"></div>
                <h5><i class="fas fa-notes-medical"></i><b> อาการเฝ้าระวัง</b></h5>
                <div class="line"></div>
                <!--rows3--->
                <div class="row">

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="risk_type_id" class="form-label">ประเภทกลุ่มเสี่ยง</label>
                            <select class="form-control" name="risk_type_id" id="risk_type_id">
                                <option value="<?=$person->risk_type_id?>">
                                    <?php echo $data->GetStringData("select risk_type_name as cc from risk_group_type where risk_type_id='$person->risk_type_id'");?>
                                </option>
                                <option value="1">ผู้กลับมาจากประเทศเสี่ยง</option>
                                <option value="2">ปอดอักเสบรุนแรง</option>
                                <option value="3">บุคลากรสาธารณสุข</option>
                                <option value="4">ผู้สัมผัส</option>
                                <option value="5">ผู้กลับมาจากประเทศกลุ่มเสี่ยงเข้าเกณฑ์ PUI</option>
                                <option value="6">ผู้กลับจากพื้นที่เสี่ยงภายในประเทศไทย</option>

                            </select>
                        </div>
                    </div>


                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="fever_date" class="form-label">วันที่มีอาการไข้</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="fever_date" name="fever_date" placeholder="วันที่มีอาการไข้" value="<?php if(isset($person->fever_date)&&$person->fever_date<>'0000-00-00'){ echo toBdate($person->fever_date);}?>"  readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick='resetValue("fever_date");' ><i class="fas fa-eraser"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="symptom_ari_date" class="form-label">วันที่มีอาการ ARI</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="symptom_ari_date" name="symptom_ari_date" placeholder="วันที่มีอาการ ARI"  value="<?php if(isset($person->symptom_ari_date)&&$person->symptom_ari_date<>'0000-00-00'){ echo toBdate($person->symptom_ari_date);}?>" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick='resetValue("symptom_ari_date");' ><i class="fas fa-eraser"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="refer_date" class="form-label">วันที่ส่งต่อ</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="refer_date" name="refer_date" placeholder="วันที่ส่งต่อ" value="<?php if(isset($person->refer__date)&&$person->refer__date<>'0000-00-00'){ echo toBdate($person->refer__date);}?>"  readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick='resetValue("refer_date");' ><i class="fas fa-eraser"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-2" style="padding:5px;">



                        <div class="form-group">
                            <label for="culture_test_result" class="form-label">ผลการเพาะเชื้อ</label>
                            <select class="form-control" name="culture_test_result" id="culture_test_result">
                                <option value="<?=$person->culture_test_result?>">
                                    <?php
                                
                                if($person->culture_test_result=='Negative'){echo "Negative[-]";}
                                else if($person->culture_test_result=='Positive'){
                                    echo "Positive[+]";
                                }else{echo "";}
                            
                            ?>
                                </option>
                                <option value="Negative">Negative[-]</option>
                                <option value="Positive">Positive[+]</option>



                            </select>

                        </div>





                    </div>


                </div>

                <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> บันทึก</button>
                <button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-user-times"></i> ลบ</button>
            </form>
            <?php }?>

        </div>
    </div>


    <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLongTitle">ยืนยันการลบข้อมูล</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p>คุณต้องการที่จะลบข้อมูลการลงทะเบียนของกลุ่มเสี่ยง ใช่หรือไม่ ?<br>
        รายละเอียดที่ต้องการลบ<br>
        CID : <?=$data->GetStringData("select  cid as cc from person_risk_group where  cid='$ocid'")?><br>
        ชื่อ - สกุล : <?=$data->GetStringData("select  fullname as cc from person_risk_group where  cid='$ocid'")?>
        </p>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="status" name="status" style="display: none">
            <strong>ลบข้อมูลไม่สำเร็จ!</strong> ข้อมูลนี้ยังไม่สามารถลบออกจากระบบได้โปรดติดต่อผู้ดูแลระบบในอำเภอของท่าน 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">ไม่ใช่</button>
        <button type="button" class="btn btn-danger" id="delCase" name="delCase" onclick="delCase('<?=$ocid?>')">ยืนยันลบ</button>
      </div>
    </div>
  </div>
</div>


    <script>
        function loadProvince() {
            $.ajax({
                url: "getdatadetail.php",
                global: false,
                type: "GET",
                data: ({
                    TYPE: "province",
                    ID: <?=$province?>
                }),
                dataType: "JSON",
                async: false,
                success: function (data) {

                    var opt = "";
                    $.each(data, function (key, val) {
                        opt += "<option value='" + val["changwatcode"] + "'>" +
                            val["changwatname"] + "</option>"
                    });
                    $("#province").html(opt);


                }
            });

        }


        function loadDistrict() {
            $.ajax({
                url: "getdatadetail.php", //ที่อยู่ของไฟล์เป้าหมาย
                global: false,
                type: "GET", //รูปแบบข้อมูลที่จะส่ง
                data: ({
                    ID: <?= $province?> ,
                    TYPE: "district",
                    IDD: "<?=$district?>"
                }), //ข้อมูลที่ส่ง  { ชื่อตัวแปร : ค่าตัวแปร }
                dataType: "JSON", //รูปแบบข้อมูลที่ส่งกลับ xml,script,json,jsonp,text
                async: false,
                success: function (jd) { //แสดงข้อมูลเมื่อทำงานเสร็จ โดยใช้ each ของ jQuery
                    var opt = "";
                    $.each(jd, function (key, val) {
                        opt += "<option value='" + val["ampurcodefull"] + "'>" +
                            val["ampurname"] + "</option>"
                    });
                    $("#district").html(opt); //เพิ่มค่าลงใน Select ของอำเภอ
                }
            });
        }

        function loadSubDistrict() {
            $.ajax({
                url: "getdatadetail.php", //ที่อยู่ของไฟล์เป้าหมาย
                global: false,
                type: "GET", //รูปแบบข้อมูลที่จะส่ง
                data: ({
                    ID: <?=$district?> ,
                    TYPE: "subdistrict",
                    IDD: <?=$subdistrict?>
                }), //ข้อมูลที่ส่ง  { ชื่อตัวแปร : ค่าตัวแปร }
                dataType: "JSON", //รูปแบบข้อมูลที่ส่งกลับ xml,script,json,jsonp,text
                async: false,
                success: function (jd) {
                    var opt = "";
                    $.each(jd, function (key, val) {
                        opt += "<option value='" + val["tamboncodefull"] +
                            "'>" +
                            val["tambonname"] + "</option>"
                    });
                    $("#subdistrict").html(opt);
                }
            });
        }



        function loadVillages() {
            $.ajax({
                url: "getdatadetail.php",
                global: false,
                type: "GET",
                data: ({
                    ID: <?=$subdistrict ?> ,
                    TYPE: "villages",
                    IDD: <?=$moo?>
                }),
                dataType: "JSON",
                async: false,
                success: function (jd) {
                    var opt = "";
                    $.each(jd, function (key, val) {
                        opt += "<option value='" + val["villagecodefull"] +
                            "'>" +
                            val["moo"] + "</option>"
                    });
                    $("#moo").html(opt);
                }
            });

            $.ajax({
                url: "getdatadetail.php",
                global: false,
                type: "GET",
                data: ({
                    ID: <?=$subdistrict?> ,
                    TYPE: 'hospital',
                    IDD: <?= $hospcode?>
                }),
                dataType: "JSON",
                async: false,
                success: function (jd) {
                    var opt = "";
                    $.each(jd, function (key, val) {
                        opt += "<option value='" + val["hoscode"] +
                            "'>" +
                            val["hospname"] + "</option>"
                    });
                    $("#hospcode").html(opt);
                }
            });

        }

        $(document).ready(function () {

            

        

            $("#from_country").change(function(){
                    if($(this).val()==764){
                        document.getElementById('city').style.display = 'none';
                        document.getElementById('prov').style.display = 'block';
                        document.getElementById('place').style.display = 'block';
                        $("#label1").text('วันที่ออกเดินทาง');
                        
                    }else{

                        document.getElementById('city').style.display = 'block';
                        document.getElementById('prov').style.display = 'none';
                        document.getElementById('place').style.display = 'none';
                        $("#label1").text('วันที่ถึงไทย');

                    }
            } );


            $("#from_prove").change(function () {
                $("#risk_place").empty();
                console.log($(this).val());
                //document.getElementById('showdistrict').style.display = 'block';
                $.ajax({
                    url: "getdata.php", //ที่อยู่ของไฟล์เป้าหมาย
                    global: false,
                    type: "GET", //รูปแบบข้อมูลที่จะส่ง
                    data: ({
                        ID: $(this).val(),
                        TYPE: "place"
                    }), //ข้อมูลที่ส่ง  { ชื่อตัวแปร : ค่าตัวแปร }
                    dataType: "JSON", //รูปแบบข้อมูลที่ส่งกลับ xml,script,json,jsonp,text
                    async: false,
                    success: function (jd) { //แสดงข้อมูลเมื่อทำงานเสร็จ โดยใช้ each ของ jQuery
                        var opt =
                            "<option value=\"0\" selected=\"selected\">--ระบุจุดเสี่ยง--</option>";
                        $.each(jd, function (key, val) {
                            opt += "<option value='" + val["id"] + "'>" +
                                val["name"] + "</option>"
                        });
                        $("#risk_place").html(opt); //เพิ่มค่าลงใน Select ของอำเภอ
                    }
                });
            });


            
            $("#from_country").change(function(){
                    if($(this).val()==764){
                        document.getElementById('city').style.display = 'none';
                        document.getElementById('prov').style.display = 'block';
                        document.getElementById('place').style.display = 'block';
                        $("#label1").text('วันที่ออกเดินทาง');
                        
                    }else{

                        document.getElementById('city').style.display = 'block';
                        document.getElementById('prov').style.display = 'none';
                        document.getElementById('place').style.display = 'none';
                        $("#label1").text('วันที่ถึงไทย');

                    }
                } );



            $("#province").change(function () {
                $("#district").empty();
                $("#subdistrict").empty(); //ล้างข้อมูล
                $("#moo").empty();
                $("#hospcode").empty();
                //document.getElementById('showdistrict').style.display = 'block';
                $.ajax({
                    url: "getdata.php", //ที่อยู่ของไฟล์เป้าหมาย
                    global: false,
                    type: "GET", //รูปแบบข้อมูลที่จะส่ง
                    data: ({
                        ID: $(this).val(),
                        TYPE: "district"
                    }), //ข้อมูลที่ส่ง  { ชื่อตัวแปร : ค่าตัวแปร }
                    dataType: "JSON", //รูปแบบข้อมูลที่ส่งกลับ xml,script,json,jsonp,text
                    async: false,
                    success: function (jd) { //แสดงข้อมูลเมื่อทำงานเสร็จ โดยใช้ each ของ jQuery
                        var opt =
                            "<option value=\"0\" selected=\"selected\">--ระบุอำเภอ--</option>";
                        $.each(jd, function (key, val) {
                            opt += "<option value='" + val["ampurcodefull"] + "'>" +
                                val["ampurname"] + "</option>"
                        });
                        $("#district").html(opt); //เพิ่มค่าลงใน Select ของอำเภอ
                    }
                });
            });


            $("#district").change(function () {
                $("#subdistrict").empty();
                $("#moo").empty();
                $("#hospcode").empty();
                $.ajax({
                    url: "getdata.php",
                    global: false,
                    type: "GET",
                    data: ({
                        ID: $(this).val(),
                        TYPE: "subdistrict"
                    }),
                    dataType: "JSON",
                    async: false,
                    success: function (jd) {
                        var opt =
                            "<option value=\"0\" selected=\"selected\">--ระบุตำบล--</option>";
                        $.each(jd, function (key, val) {
                            opt += "<option value='" + val["tamboncodefull"] +
                                "'>" +
                                val["tambonname"] + "</option>"
                        });
                        $("#subdistrict").html(opt);
                    }
                });

                
                $.ajax({
                    url: "getdata.php",
                    global: false,
                    type: "GET",
                    data: ({
                        ID: $(this).val(),
                        TYPE: "hospital"
                    }),
                    dataType: "JSON",
                    async: false,
                    success: function (jd) {
                        var opt =
                            "<option value=\"0\" selected=\"selected\">--ระบุหน่วยบริการ--</option>";
                        $.each(jd, function (key, val) {
                            opt += "<option value='" + val["hoscode"] +
                                "'>" +
                                val["hospname"] + "</option>"
                        });
                        $("#hospcode").html(opt);
                    }
                });

            });

            $("#subdistrict").change(function () {
                $("#moo").empty();

                $.ajax({
                    url: "getdata.php",
                    global: false,
                    type: "GET",
                    data: ({
                        ID: $(this).val(),
                        TYPE: "villages"
                    }),
                    dataType: "JSON",
                    async: false,
                    success: function (jd) {
                        var opt =
                            "<option value=\"0\" selected=\"selected\">--ระบุหมู่บ้าน--</option>";
                        $.each(jd, function (key, val) {
                            opt += "<option value='" + val["villagecodefull"] +
                                "'>" +
                                val["moo"] + "</option>"
                        });
                        $("#moo").html(opt);
                    }
                });

            });



        });


        window.onload = function () {
            loadProvince();
            loadDistrict();
            loadSubDistrict();
            loadVillages();
        }

        $('td').click(function (e) {
            DeleteMarkers();

            var sid = $(this).attr('id');
            var txt = $(e.target).text();
            document.getElementById("hid").value = sid;
            document.getElementById("lat").value = '';
            document.getElementById("lng").value = '';
            document.getElementById("p_txt").innerHTML = txt;
            // console.log(sid+txt);
        });


        var markers = [];

        window.onload = function () {
            //กำหนด Option ให้กัยแผนที่
            var mapOptions = {
                center: new google.maps.LatLng( <?=$vlat?>,<?=$vlng?>),
                zoom: 17,
                mapTypeId: google.maps.MapTypeId.HYBRID
            };
            var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);

            //กำหนดพิกัดเริ่มต้นให้
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng( <?=$vlat ?> , <?=$vlng ?> ),
                map: map
            });
            markers.push(marker);

            //Attach click event handler to the map.
            google.maps.event.addListener(map, 'click', function (e) {
                DeleteMarkers();
                document.getElementById("lat").value = e.latLng.lat();
                document.getElementById("lng").value = e.latLng.lng();
                //Determine the location where the user has clicked.
                var location = e.latLng;
                //Create a marker and placed it on the map.
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });

                //Attach click event handler to the marker.
                google.maps.event.addListener(marker, "click", function (e) {
                    var infoWindow = new google.maps.InfoWindow({
                        content: 'Latitude: ' + location.lat() + '<br />Longitude: ' +
                            location.lng()
                    });
                    infoWindow.open(map, marker);
                });

                //Add marker to the array.
                markers.push(marker);
            });
        };

        function DeleteMarkers() {
            //Loop through all the markers and remove
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        };
    </script>