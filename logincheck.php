<?php

if (!isset($_SESSION)) {
    session_start();
}


$loginname = addslashes(strip_tags(trim($_POST['username'])));
$password = addslashes(strip_tags(trim($_POST['password'])));



require_once("./includes/DBConn.php");
require_once("./includes/function.php");

$data= new dbClass();





$sqllogin = "select loginname,uname,office,role,province,districtcode,subdistrictcode from users where loginname = '$loginname' and password = md5('$password')";
$result = $db->prepare($sqllogin);
$result->execute();

$usercheck = $result->rowCount();


if(!isset($_POST['username'])||!isset($_POST['password'])){
    echo "<script> ";
    echo"alert('กรุณากรอก User name และ Password')";
    echo "</script> ";
    echo "<script>";
    echo "window.location='./index.php?login.php';";
    echo "</script>";   
}else if($usercheck==1){

    while($row = $result->fetch()) {
        $_SESSION['loginname'] = $row['loginname'];
        $_SESSION['uname'] = $row['uname'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['office'] = $row['office'];
        $_SESSION['province'] = $row['province'];
        $_SESSION['district']=$row['districtcode'];
        $_SESSION['subdistrict']=$row['subdistrictcode'];
        $_SESSION['logged'] = true;
        echo "<script>";
        echo "window.location='./index.php?url=pages/dashboard.php';";
        echo "</script>"; 
            
        }


}else{
    echo "<script>";
    echo"alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้องกรุณาลองใหม่อีกครั้ง!!');";
    echo "</script> ";
    
    echo "<script>";
    echo "window.location='login.php';";
    echo "</script>";      
}





?>