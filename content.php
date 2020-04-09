<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!empty($_REQUEST["url"])) {

    require  $_REQUEST['url'];
    
} else {
    require 'pages/dashboard.php';
}
?> 
