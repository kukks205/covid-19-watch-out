<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./images/favicon.ico">

    <title>COVID-19 Watch Out</title>

    <!-- Bootstrap core CSS -->
    <link href="./lib/bootstrap-4.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <link rel="stylesheet" href="./css/login.css">

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="./lib/bootstrap-4.3.1/dist/js/bootstrap.min.js"></script>
    <link href="./lib/mobiscroll/css/jquery.scroller-1.0.2.css" rel="stylesheet" type="text/css" />
    <script src="./lib/mobiscroll/js/jquery.scroller-1.0.2.js" type="text/javascript"></script>


</head>

<body style="background-color: #666666;">

<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors',0);
//error_reporting(E_ALL);
include('version.php');

if (!isset($_SESSION)) {
    session_start();
}


if($_SESSION['logged']==true){

    header( "location: ./index.php" );
    exit(0);   
}

?>

    <div class="limiter">

        <div class="container-login100">
            <div class="wrap-login100">

                <form class="login100-form" action="logincheck.php" method="POST" name="loginFrm">
                    <span class="login100-form-title p-b-43">
                    <img src="img/logo.png"  height="164px"><br>
                        :: COVID-19 Watch Out ::<br>
                        version <?=$version?>
                    </span>
                    <hr>

                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" class="form-control" id="username" name="username" aria-describedby="username"
                            placeholder="กรุณาระบุชื่อผู้ใช้งาน">
                        <small id="username" class="form-text text-muted">ระบุชื่อผู้ใช้งานที่จะเข้าใช้งานระบบ</small>
                    </div>


                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" aria-describedby="password"
                            placeholder="รหัสผ่านสำหรับผู้ใช้งาน">
                        <small id="password" class="form-text text-muted">ระบุรหัสผ่านของชื่อผู้ใช้งานเพื่อเข้าใช้งานระบบ</small>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>

                    <div class="text-center p-t-46 p-b-20">
                        <span class="txt2">
                            or sign up using
                        </span>
                    </div>
                </form>

                <div class="login100-more" style="background-image: url('img/bg01.png');">
                </div>
            </div>
        </div>
    </div>





</body>

</html>