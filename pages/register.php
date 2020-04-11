<script type="text/javascript">
		  $(function () {

		    var d = new Date();
            $("#date_arrival").datepicker({dateFormat: 'dd/mm/yy', defaultDate: d});
            $("#date_arrival_home").datepicker({dateFormat: 'dd/mm/yy', defaultDate: d});
            $("#fever_date").datepicker({dateFormat: 'dd/mm/yy', defaultDate: d});
            $("#symptom_ari_date").datepicker({dateFormat: 'dd/mm/yy', defaultDate: d});
            $("#refer_date").datepicker({dateFormat: 'dd/mm/yy', defaultDate: d});
			});


            function resetValue(divID){
                document.getElementById(divID).value = null;
            }

</script>
<?php

$proveID= $_SESSION['province'];

$ucid=$_GET['cid'];

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



function notify_message($message,$token){
 $queryData = array('message' => $message);
 $queryData = http_build_query($queryData,'','&');
 $headerOptions = array( 
         'http'=>array(
            'method'=>'POST',
            'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                      ."Authorization: Bearer ".$token."\r\n"
                      ."Content-Length: ".strlen($queryData)."\r\n",
            'content' => $queryData
         ),
 );

 $context = stream_context_create($headerOptions);
 $result = file_get_contents(LINE_API,FALSE,$context);
 $res = json_decode($result);
 return $res;
}


if($_POST["register"]==true){

    $who_record = $_SESSION['loginname'];

    if($_POST['fever_date']==''||$_POST['fever_date']=='0000-00-00'){
        $fever_date='null';
    }else{
        $fever_date = toYdate($_POST['fever_date']);
    }

    if($_POST['refer_date']==''||$_POST['refer_date']=='0000-00-00'){
        $refer_date='null';
    }else{
        $refer_date = toYdate($_POST['refer_date']);
    }
    
    if($_POST['symptom_ari_date']==''||$_POST['symptom_ari_date']=='0000-00-00'){
        $symptom_ari_date='null';
    }else{
        $date_arrival=toYdate($_POST['symptom_ari_date']);
    }

    if($_POST['culture_test_result']!='Negative'||$_POST['culture_test_result']!='Positive' ){
        $culture_test_result='null';
    }else{
        $culture_test_result = toYdate($_POST['culture_test_result']);
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
    
    $cid = addslashes(strip_tags(trim($_POST['cid'])));
    $fullname = addslashes(strip_tags(trim($_POST['fullname'])));
    $sex = addslashes(strip_tags(trim($_POST['sex'])));
    $age = addslashes(strip_tags(trim($_POST['age'])));
    $nation = addslashes(strip_tags(trim($_POST['nation'])));
    $province = addslashes(strip_tags(trim($_POST['province'])));
    $district = addslashes(strip_tags(trim($_POST['district'])));
    $subdistrict = addslashes(strip_tags(trim($_POST['subdistrict'])));
    $moo = addslashes(strip_tags(trim($_POST['moo'])));
    $address = addslashes(strip_tags(trim($_POST['address'])));
    $telphone = addslashes(strip_tags(trim($_POST['telphone'])));
    $hospcode = addslashes(strip_tags(trim($_POST['hospcode'])));
    $from_country = addslashes(strip_tags(trim($_POST['from_country'])));
    $from_city = addslashes(strip_tags(trim($_POST['from_city'])));
    $from_prove =  addslashes(strip_tags(trim($_POST['from_prove'])));
   

if(strlen($cid)!=13){
    echo "<script>alert('เลขประจำตัวประชาชนไม่ครบ $date_arrival หลัก');</script>";
}else if($_POST['date_arrival']==''||$_POST['date_arrival']=='0000-00-00') {
    echo "<script>alert('คุณยังไม่ระบุวันที่เดินทางมาถึงไทย');</script>";
}else if(strlen($_POST['hospcode'])<5){
    echo "<script>alert('กรุณาระบุหน่วยบริการที่รับผิดชอบ');</script>";
}else{

    
$checkCID = $data->GetStringData("select count(cid) as cc from person_risk_group where cid='$cid'");

if($checkCID>0){

    echo "<script>alert('บุคคลนี้มีการลงทะเบียนเพื่อเฝ้าระวังไปแล้วครับ !!');</script>";

}else{

    echo "<script>alert()</script>";

    
    $sql_register = "insert into person_risk_group(cid,fullname,nation,sex,age,address,moo,subdistrict,district,province,
    telphone,hospcode,from_country,from_city,from_prove,risk_place_id,
    date_arrival,date_arrival_home,date_quarantine,risk_type_id,
    fever_date,symptom_ari_date,refer_date,culture_test_result,record_date,who_record) 
    values('$cid','$fullname','$nation','$sex','$age',
    '$address','$moo','$subdistrict','$district',
    '$province','$telphone','$hospcode','$from_country',
    '$from_city','$from_prove','$risk_place_id','$date_arrival','$date_arrival_home',
    ADDDATE('$date_arrival',INTERVAL 14 DAY),'$risk_type_id','$fever_date','$symptom_ari_date','$refer_date','$culture_test_result',now(),'$who_record')";

    $register = $db->prepare($sql_register);
    $register->execute();
    $count = $register->rowCount();

    $cdate = $fdate->FormatThaiDate(date("Y-m-d"));
    $adate = $fdate->FormatThaiDate($date_arrival);
    $country = $data->GetStringData("select name_th as cc from ccountry where country_id='$from_country'");
    $cmoo = $data->GetStringData("select concat('บ้าน',villagename,' ม.',villagecode) as cc  from cvillage where villagecodefull='$moo' limit 1");
    $ctambon = $data->GetStringData("select tambonname as cc from ctambon where tamboncodefull='$subdistrict' limit 1");
    $camphur = $data->GetStringData("select ampurname as cc from campur where ampurcodefull='$district' limit 1");
    
    $chos = $data->GetStringData("select hosname as cc from chospital where hoscode='$hospcode'");

    if($_SESSION['loginname']){

        $record_type=$who_record;
    }else{

        $record_type='ภาคประชาชน';
    }

    if($from_country=='764'){

        $cprov = $data->GetStringData("select changwatname as cc from cchangwat where changwatcode='$from_prove' limit 1");
        $rplace = $data->GetStringData("select name as cc from core_riskplace  where id='$risk_place_id' limit 1");

        $str =$fullname."\r\nได้รับการลงทะเบียนกลุ่มเสี่ยงภายในประเทศ\r\nวันที่: $cdate \r\nเดินทางมาจากจังหวัด: $cprov\r\nจุดเสี่ยง : $rplace \r\nวันที่ $adate \r\nที่อยู่: $address  $cmoo \r\nต.$ctambon อ.$camphur\r\nหน่วยบริการ: $chos\r\nบันทึกโดย : $record_type";

    }else{

        $str =$fullname ."\r\nได้รับการลงทะเบียนกลุ่มเสี่ยงจากต่างประเทศ \r\nวันที่: $cdate \r\nเดินทางมาจากประเทศ:  $country\r\nเมือง : $from_city  \r\nวันที่ $adate \r\nที่อยู่: $address  $cmoo \r\nต.$ctambon อ.$camphur\r\nหน่วยบริการ: $chos\r\nบันทึกโดย : $record_type";
    }


    

    if(strlen($aToken)>5 && $aToken<>$uToken){
        $sendA = notify_message($str,$aToken);
        $sendU = notify_message($str,$uToken);
    }else if(strlen($aToken)>5 && $aToken==$uToken){
        $sendA = notify_message($str,$aToken);
    }else if(strlen($aToken)<5 && strlen($uToken)>5){
        $sendU = notify_message($str,$uToken);
    }

    //print_r($res);

    echo "<script>alert('ลงทะเบียนสำเร็จ $sql_register คน');</script>";
    echo "<script>window.location ='index.php?url=pages/register_detail.php&cid=$cid'</script>";


}


}



}

?>

<div style="padding: 20px;height:100%">
    <div class="clearfix">
        <h2 class="float-left">


            <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-user-plus"
                    style="font-size: 24px;"></i></button>

            <b>ลงทะเบียนกลุ่มเสี่ยง</b>
        </h2>
    </div>
    <hr>


    <div class="card">
        <div class="card-body">
            <h5><i class="fas fa-id-card"></i><b> ข้อมูลทั่วไป</b></h5>
            <div class="line"></div>
            <form name="addUser" id="addUser" method="post" action="index.php?url=pages/register.php">
                <input type="hidden" name="register" id="register" value="true">

                <!--rows1--->
                <div class="row">
                    <div class="col-md-3" style="padding:5px;">
                        <div class="form-group">
                            <label for="cid" class="form-label">CID</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="cid" name="cid"
                                    placeholder="เลขประจำตัวประชาชน" maxlength="13" value="<?=$ucid?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" name="search" id="search"><i
                                            class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="col-md-4" style="padding:5px;">
                        <div class="form-group">
                            <label for="fullname" class="form-label">ชื่อ-สกุล</label>
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                placeholder="ชื่อ-สกุล">
                        </div>
                    </div>
                    <div class="col-md-1" style="padding:5px;">
                        <div class="form-group">
                            <label for="sex" class="form-label">เพศ</label>
                            <select class="form-control" name="sex" id="sex">
                                <option>
                                    -- เพศ --
                                </option>
                                <option value="1">ชาย</option>
                                <option value="2">หญิง</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="age" class="form-label">อายุ(ปี)</label>
                            <input type="number" class="form-control" id="age" name="age" placeholder="อายุ(ปี)">
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
                                <option>
                                    -- ระบุสัญชาติ --
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
                            <option value="<?=$proveID?>" selected="selected">
                                    <?php echo $data->GetStringData("select changwatname as cc from cchangwat where changwatcode='$proveID'");?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                        <?php   $sql_distric= "select * from campur where ampurcodefull='$districtID' and flag_status=0
                                union
                                select * from campur where changwatcode='$proveID' and flag_status=0 and ampurcodefull<>'$districtID'";
                                 $distric_list = $db->query($sql_distric, PDO::FETCH_OBJ);
                        ?>
                            <label for="district" class="form-label">อำเภอ</label>
                            <select name="district" id="district" class="form-control">
                            <option>---ระบุอำเภอ---</option>                                
                            <?php foreach ($distric_list as $dc) {?>
                                <option value="<?=$dc->ampurcodefull?>"><?=$dc->ampurname?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="subdistrict" class="form-label">ตำบล</label>
                            <select class="form-control" name="subdistrict" id="subdistrict">
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="moo" class="form-label">หมู่ที่</label>
                            <select class="form-control" name="moo" id="moo">
                            </select>

                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="address" class="form-label">บ้านเลขที่</label>

                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="บ้านเลขที่">

                        </div>
                    </div>
                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="telphone" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" class="form-control" id="telphone" name="telphone"
                                placeholder="เบอร์โทร์ศัพท์">
                        </div>
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
                                <option>
                                    --ระบุประเทศ--
                                </option>
                                <?php foreach ($country_list as $rc) {?>
                                <option value="<?=$rc->country_id?>"><?=$rc->cname?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;display:block;" id="city">
                        <div class="form-group">
                            <label for="from_city" class="form-label">เมือง/รัฐ</label>
                            <input type="text" class="form-control" id="from_city" name="from_city"
                                placeholder="กรุณาพิมพ์ชื่อ เมือง/รัฐ">
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;display:none;" id="prov" name="prov">
                        <?php
                        $sql_prov = "select * from cchangwat order by changwatname";
                        $prov_list = $db->query($sql_prov, PDO::FETCH_OBJ);
                         ?>
                        <div class="form-group">
                            <label for="from_country" class="form-label">จังหวัด</label>
                            <select class="form-control" name="from_prove" id="from_prove">
                                <option>
                                    --ระบุจังหวัด--
                                </option>
                                <?php foreach ($prov_list as $rp) {?>
                                <option value="<?=$rp->changwatcode?>"><?=$rp->changwatname?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;display:none;" id="place" name="place">
 
                        <div class="form-group">
                            <label for="risk_place" class="form-label">จุดเสี่ยง</label>
                            <select name="risk_place" id="risk_place" class="form-control">
                            </select>
                        </div>

                    </div>
                    
                    

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">

                            <label for="date_arrival" class="form-label" name="label1" id="label1">วันที่ถึงไทย</label>
                            <input type="text" class="form-control" id="date_arrival" name="date_arrival"
                                placeholder="วันที่ถึงไทย"  readonly>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                            <label for="date_arrival_home" class="form-label">วันที่ถึงบ้าน</label>
                            <input type="text" class="form-control" id="date_arrival_home" name="date_arrival_home"
                                placeholder="วันที่ถึงบ้าน" readonly>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding:5px;">
                        <div class="form-group">
                        <?php
                        $sql_hos = "select hoscode,concat('[',hoscode,'] ',hosname) as  hospname,
                        concat(provcode,distcode) as districtcode,
                        concat(provcode,distcode,subdistcode) as subdistrictcode
                        from chospital where hostype in (18,13,08,07,06,05,03)
                        and concat(provcode,distcode)='$districtID' and status=01;";
                        $hos_list = $db->query($sql_hos, PDO::FETCH_OBJ);
                         ?>
                            <label for="hospcode" class="form-label">หน่วยบริการที่รับผิดชอบ</label>
                            <select class="form-control" name="hospcode" id="hospcode">
                            <option>
                                    --หน่วยบริการ--
                                </option>
                                <?php foreach ($hos_list as $rh) {?>
                                <option value="<?=$rh->hoscode?>"><?=$rh->hospname?></option>
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
                                <option value="0">
                                    -- ระบุประเภทกลุ่มเสี่ยง --
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
                                <input type="text" class="form-control" id="fever_date" name="fever_date" placeholder="วันที่มีอาการไข้"  readonly>
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
                                <input type="text" class="form-control" id="symptom_ari_date" name="symptom_ari_date" placeholder="วันที่มีอาการ ARI"  readonly>
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
                                <input type="text" class="form-control" id="refer_date" name="refer_date" placeholder="วันที่ส่งต่อ"  readonly>
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
                                <option value="0">
                                    -- ผลการเพาะเชื้อ --
                                </option>
                                <option value="Negative">Negative[-]</option>
                                <option value="Positive">Positive[+]</option>
                            </select>
                        </div>
                    </div>



                </div>

                <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> บันทึก</button>
            </form>

        </div>
    </div>
    <script>

                                    

        function loadProvince() {
            $.ajax({
                url: "getdata.php",
                global: false,
                type: "GET",
                data: ({
                    TYPE: "province"
                }),
                dataType: "JSON",
                async: false,
                success: function (data) {
                    var opt =
                        "<option value=\"0\" selected=\"selected\">--ระบุจังหวัด--</option>";
                    $.each(data, function (key, val) {
                        opt += "<option value='" + val["changwatcode"] + "'>" +
                            val["changwatname"] + "</option>"
                    });
                    $("#province").html(opt);


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



            $("#search").click(function () {

                $.ajax({
                    url: "get_person.php", //ที่อยู่ของไฟล์เป้าหมาย
                    global: false,
                    type: "GET", //รูปแบบข้อมูลที่จะส่ง
                    data: ({
                        cid: $("#cid").val()
                    }), //ข้อมูลที่ส่ง  { ชื่อตัวแปร : ค่าตัวแปร }
                    dataType: "JSON", //รูปแบบข้อมูลที่ส่งกลับ xml,script,json,jsonp,text
                    async: false,
                    success: function (jd) {
                        
                        $.each(jd, function (key, val) {
                            $("#fullname").val(val["fullname"]); 
                            $("#sex").val(val["sex"]); 
                            $("#age").val(val["age"]); 
                            $("#nation").val(val["nation"]); 
                            $("#address").val(val["address"]); 
                            $("#moo").val(val["moo"]); 
                            $("#province").val(val["province"]); 
                            $("#district").val(val["district"]); 
                            $("#telphone").val(val["telphone"]); 
                            $("#hospcode").val(val["hospcode"]); 
                            var opt= "<option value='" + val["subdistrict"] + "'  selected=\"selected\">" +
                                val["tambonname"] + "</option>"
                            var opv= "<option value='" + val["moo"] + "'  selected=\"selected\">" +
                                val["villname"] + "</option>"
                        });

                        $("#subdistrict").html(opt);
                        $("#moo").html(opv);
                       
                    }
                });
            });



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
                $("#ProID").val($(this).val()); //กำหนดค่า ID ของจังหวัดที่เลือกให้กับ Textfield ของจังหวัด
            });


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

               /* $.ajax({
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
                });*/

            });



        });


        window.onload = function () {
            loadProvince();
        }
    </script>