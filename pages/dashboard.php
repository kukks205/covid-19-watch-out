<div class="dashboard">
    <div class="row" style="padding:5px 20px 0px 20px;">
<?php    
    if( $detect->isMobile()) {
    $height = '250px';
    }else {$height='160px';}

    if($_SESSION['role']=='001'||$_SESSION['role']=='002'){

        $title = $data->GetStringData("select concat('จังหวัด',changwatname) as cc from cchangwat where changwatcode='$proveID'");
        $where = "hospcode in (select hoscode from chospital where provcode='$proveID' and status=1 and hostype in (18,13,08,07,06,05,03))";

    }else if($_SESSION['role']=='007'||$_SESSION['role']=='003'){

        $title = $data->GetStringData("select concat('อำเภอ',ampurname) as cc from campur where ampurcodefull='$districtID'");
        $where = "district='$districtID' and hospcode in (select hoscode from chospital where concat(provcode,distcode)='$districtID' and status=1 and hostype in (18,13,08,07,06,05,03))";

    }else{
        
        $title = $data->GetStringData("select SUBSTRING_INDEX(hosname,' ',1) as cc from chospital where hoscode='".$_SESSION['office']."'");
        $where = "hospcode='".$_SESSION['office']."'";

    }



?>
        <div class="col-md-12 dash-block-title" style="height:<?=$height?>">

            <div class="float-left">
                <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-bars"></i></button>
            </div>
            <h2 style="color:white;"><b>
                    สถานการณ์ COVID-19
                    <?=$title?></b>
            </h2>
            <h2 style="color:white;">วันที่ <?=  $fdate->FormatThaiDate(date('Y-m-d'));?> เวลา <?=date('H:i:s').' น.';?>
            </h2>

        </div>
    </div>
    <div class="row" style="padding:5px 20px 0px 20px;">
        <div class="col-md-12 dash-block" style="height: 80px;">
            <h1 style="color:white;">กลุ่มเสี่ยงเดินทางมาจากต่างประเทศ</h1>
        </div>
    </div>
    <div class="row" style="padding:5px 20px 0px 20px;">
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">กลุ่มเสี่ยงสะสม(คน)</h1>
                <a href="index.php?url=pages/person_risk_summary.php&distcode=41">
                    <h2 style="color:yellow;">
                        <?php 
                        $sasom = $data->GetStringData("select count(distinct cid) as cc from person_risk_group where from_country not in ('764','999')   and  $where ");
                        echo $sasom;
                        ?>
                        คน</h2>
                </a>
                <!--font-size:1.5em;-->
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">กลุ่มเสี่ยงรายใหม่(คน)</h1>
                <h2 style="color:yellow;">
                    <?php 
                    
                        $new = $data->GetStringData("select count(distinct cid) as cc from person_risk_group where DATE_FORMAT(record_date,'%Y-%m-%d')=CURDATE() and from_country not in ('764','999') and  $where");
                        echo $new;
                        
                    ?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">พ้นระยะ 14 วัน(คน)</h1>
                <h2 style="color:yellow;">
                    <?php
                    
                    $qcase = $data->GetStringData("select count(distinct cid) as cc from person_risk_group where DATEDIFF(CURDATE(),date_arrival)>14 and from_country not in ('764','999') and  $where");
                    echo $qcase;
                    ?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">เฝ้าระวังต่อ(คน)</h1>
                <h2 style="color:yellow;">
                    <?=$sasom-$qcase;?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">กลุ่มเสี่ยงมีอาการ PUI</h1>
                <h2 style="color:yellow;">
                    <?=$data->GetStringData("select count(distinct cid) as cc from person_risk_group where  $where and ( (fever_date<>'' and fever_date<>'0000-00-00') or (symptom_ari_date<>'' and  symptom_ari_date<>'0000-00-00')) and  from_country  not in ('764','999')")?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">ได้รับการตรวจหาเชื้อ</h1>
                <h2 style="color:yellow;">
                    <?=$data->GetStringData("select count(distinct cid) as cc from person_risk_group where (culture_test_result='Positive' or culture_test_result= 'Negative') and from_country not in ('764','999') and  $where")?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">พบเชื้อ</h1>
                <h2 style="color:yellow;">
                    <?=$data->GetStringData("select count(distinct cid) as cc from person_risk_group where culture_test_result='Positive' and from_country not in ('764','999') and  $where")?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">ไม่พบเชื้อ</h1>
                <h2 style="color:yellow;">
                    <?=$data->GetStringData("select count(distinct cid) as cc from person_risk_group where culture_test_result='Negative' and from_country not in ('764','999') and  $where")?>
                    คน</h1>
        </div>
    </div>

    <div class="row" style="padding:5px 20px 0px 20px;">
        <div class="col-md-12 dash-block" style="height: 80px;">
            <h1 style="color:white;">กลุ่มเสี่ยงเดินทางมาจากพื้นที่เสี่ยงในประเทศ</h1>
        </div>
    </div>

    <div class="row" style="padding:5px 20px 0px 20px;">
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">กลุ่มเสี่ยงสะสม(คน)</h1>
                <a href="index.php?url=pages/person_risk_summary.php&distcode=41">
                    <h2 style="color:yellow;">
                        <?php
                            $tsasom = $data->GetStringData("select count(distinct cid) as cc from person_risk_group where from_country='764' and  $where");
                            echo $tsasom;
                        ?>
                        คน</h2>
                </a>
                <!--font-size:1.5em;-->
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">กลุ่มเสี่ยงรายใหม่(คน)</h1>
                <h2 style="color:yellow;">
                    <?php
                        $tnew = $data->GetStringData("select count(distinct cid) as cc from person_risk_group where DATE_FORMAT(record_date,'%Y-%m-%d')=CURDATE() and from_country='764' and  $where");
                        echo $tnew;
                    ?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">พ้นระยะ 14 วัน(คน)</h1>
                <h2 style="color:yellow;">
                    <?php   $tqcase = $data->GetStringData("select count(distinct cid) as cc from person_risk_group where DATEDIFF(CURDATE(),date_arrival)>14 and from_country='764' and  $where");
                            echo $tqcase;
                    ?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">เฝ้าระวังต่อ(คน)</h1>
                <h2 style="color:yellow;">
                    <?php  echo $tsasom-$tqcase;?>
                    คน</h1>
        </div>        
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">กลุ่มเสี่ยงมีอาการ PUI</h1>
                <h2 style="color:yellow;">
                    <?=$data->GetStringData("select count(distinct cid) as cc from person_risk_group where $where and ( (fever_date<>'' and fever_date<>'0000-00-00') or (symptom_ari_date<>'' and  symptom_ari_date<>'0000-00-00')) and from_country='764'")?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">ได้รับการตรวจหาเชื้อ</h1>
                <h2 style="color:yellow;">
                    <?=$data->GetStringData("select count(distinct cid) as cc from person_risk_group where (culture_test_result='Positive' or culture_test_result= 'Negative') and from_country='764' and  $where")?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">พบเชื้อ</h1>
                <h2 style="color:yellow;">
                    <?=$data->GetStringData("select count(distinct cid) as cc from person_risk_group where culture_test_result='Positive' and from_country='764' and  $where")?>
                    คน</h1>
        </div>
        <div class="col-md-3 dash-block">
            <h2 style="color:white;">ไม่พบเชื้อ</h1>
                <h2 style="color:yellow;">
                    <?=$data->GetStringData("select count(distinct cid) as cc from person_risk_group where culture_test_result='Negative' and from_country='764' and $where")?>
                    คน</h1>
        </div>
    </div>


</div>