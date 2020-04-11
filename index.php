<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>COVID-19 Watch Out</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="./node_modules/angular-datatables/dist/css/angular-datatables.css">
    <link rel="stylesheet" href="./node_modules/angular-datatables/dist/plugins/bootstrap/datatables.bootstrap.min.css">
    <link href="./lib/bootstrap-4.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="./css/style.css">

    <!-- Font Awesome JS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="./lib/jquery.dataTables.min.js"></script>
    <script src="./node_modules/angular/angular.js"></script>
    <script src="./node_modules/angular-datatables/dist/angular-datatables.min.js"></script>
    <script src="./node_modules/angular-datatables/dist/plugins/bootstrap/angular-datatables.bootstrap.min.js"></script>
    <script src="./node_modules/angular-dynamic-locale/dist/tmhDynamicLocale.js"></script>

    <script src="./lib/bootstrap-4.3.1/dist/js/bootstrap.min.js"></script>
    <link type="text/css" href="lib/jquery-ui-1.12.1/jquery-ui.css" rel="stylesheet" />
    <script type="text/javascript" src="lib/jquery-ui-1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="./lib/jquery-ui-1.12.1/datepicker-th.js"></script>


    <?php
        session_start();
 
        // กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        set_time_limit(0);
        ini_set("post_max_size", "30M");
        ini_set("upload_max_filesize", "30M");


        define('LINE_API',"https://notify-api.line.me/api/notify");
  

        // กรณีมีการเชื่อมต่อกับฐานข้อมูล
        require_once("includes/DBConn.php");
        require_once("includes/function.php");
        require_once("includes/dateClass.php");
        require_once("lib/mobile_detect.php");

        $data = new dbClass();
        $fdate = new dateClass();
        $detect = new Mobile_Detect;
        $url = $_REQUEST['url'];

        # ป้องกัน sql injection จาก $_GET
        foreach ($_GET as $key => $value) {
            $_GET[$key]=addslashes(strip_tags(trim($value)));
        }
          
        if ($_GET['id'] !='') { $_GET['id']=(int) $_GET['id']; }
                extract($_GET);


                $proveID= $_SESSION['province'];
                $districtID = $_SESSION['district'];
                $loginname = $_SESSION['loginname'];
                $hospcode = $_SESSION['office'];
                $role = $_SESSION['role'];
                $hospname = $data->GetStringData("select concat('[',hoscode,'] ',hosname) as cc from chospital where hoscode='$hospcode'");

                $aToken =  $data->GetStringData("select line_token as cc from district_line_token where districtcode='$districtID' limit 1");
                $uToken = $data->GetStringData("select  line_token as cc from users where loginname='$loginname' limit 1");


    
                if($_SESSION['logged']!=true){
                    header( "location: ./login.php" );
                    exit(0);   
                }
    
                
    ?>

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header" style="padding-bottom: 0px;padding-top: 0px;">
                <h1><b>COVID-19 WO</b></h1>
            </div>
            <div align="center">
                
                <img src="img/user.png" width="82px"><br>
               <b><a href="index.php?url=pages/u_profile.php"><i class="fas fa-user-edit"></i></a> <?php echo $_SESSION['uname'];?></b> 
                
            </div>
            <ul class="list-unstyled components">
                <li <?php echo $url=="pages/dashboard.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/dashboard.php"><i class="fas fa-chart-line"></i> สรุปข้อมูล</a>
                </li>
                <li <?php echo $url=="pages/map_province.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/map_province.php"><i class="fas fa-layer-group"></i> แผนที่จังหวัด(กลุ่มเสี่ยง)</a>
                </li>
                <?php
                if( $role!='001'&&$role!='002' ){
                ?>
                <li <?php echo $url=="pages/map_district.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/map_district.php"><i class="fas fa-map"></i> ข้อมูลกลุ่มเสี่ยง(แผนที่)</a>
                </li>
                <?php
                }
                ?>
                <li <?php echo $url=="pages/search.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/search.php"><i class="fas fa-search"></i>
                        ค้นหาบุคคล</a>
                </li>
                <li <?php echo $url=="pages/register.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/register.php"><i class="fas fa-user-plus"></i>
                    ลงทะเบียนกลุ่มเสี่ยง</a>
                </li>


                <?php
                        if($role=='004'){

                ?>
                    <li <?php echo $url=="pages/risk_all_hosp.php" ? "class='active'" :''  ?>>
                        <a href="index.php?url=pages/risk_all_hosp.php"><i class="fas fa-user-friends"></i>
                            กลุ่มเสี่ยงทั้งหมด</a>
                    </li>
                <?php
                        } else if($role=='003'||$role=='007'){
                ?>
                    <li <?php echo $url=="pages/risk_all_dist.php" ? "class='active'" :''  ?>>
                        <a href="index.php?url=pages/risk_all_dist.php"><i class="fas fa-user-friends"></i>
                            กลุ่มเสี่ยงทั้งหมด</a>
                    </li>
                <?php

                        }
                ?>



                <li <?php echo $url=="pages/country_risk_person.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/country_risk_person.php"><i class="fas fa-user-shield"></i>
                        เดินทางจากประเทศเสี่ยง</a>
                </li>
                <li <?php echo $url=="pages/place_risk_person.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/place_risk_person.php"><i class="fas fa-user-shield"></i>
                        เดินทางจากพื้นที่เสี่ยง</a>
                </li>

                <li <?php echo $url=="pages/person_risk_watch.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/person_risk_watch.php"><i class="fas fa-user-clock"></i>
                        กลุ่มเสี่ยงยังเฝ้าระวัง</a>
                </li>

                <li <?php echo $url=="pages/person_risk_qa.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/person_risk_qa.php"><i class="fas fa-user-check"></i>
                        กลุ่มเสี่ยงเฝ้าระวังครบ</a>
                </li>

                <li <?php echo $url=="pages/person_risk_pui.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/person_risk_pui.php"><i class="fas fa-procedures"></i>
                        กลุ่มเสี่ยงมีอาการ PUI</a>
                </li>
                <li <?php echo $url=="pages/map_main.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/map_main.php"><i class="fas fa-map-marked"></i> แผนที่แบบพิกัดบ้าน</a>
                </li>
                
                <li <?php echo $url=="pages/report_main.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/report_main.php"><i class="fas fa-folder-open"></i> ระบบรายงาน</a>
                </li>



                <hr>
                <?php 
                if($_SESSION['role']=='001' || $_SESSION['role']=='007'){
                ?>
                <li <?php echo $url=="pages/m_users.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/m_users.php"><i class="fas fa-user-cog"></i>
                        จัดการผู้ใช้งาน</a>
                </li>
                <?php } ?>

                <li <?php echo $url=="pages/importdata.php" ? "class='active'" :''  ?>>
                    <a href="index.php?url=pages/importdata.php"><i class="fas fa-file-upload"></i>
                        นำเข้าข้อมูลพื้นฐาน</a>
                </li>

                <li <?php echo $url=="pages/importdata.php" ? "class='active'" :''  ?>>
                    <a href="./logout.php"><i class="fas fa-power-off"></i>
                        ออกจากระบบ</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <?php
           
                require "content.php";
            ?>
        </div>
    </div>

    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>

</html>