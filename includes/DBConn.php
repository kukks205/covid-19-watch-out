<?php
              
                error_reporting(0);

                include 'conf.ini.php';

                try{
                    //connect ฐานข้อมูล 
                    $db = new PDO("mysql:host=$hostname;port=$port;dbname=$dbname;", $dbusername, $dbpassword,array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset",));
                    $db->setAttribute(PDO::ATTR_TIMEOUT,30);   
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //$conn_status='true';

                    }
                    catch (Exception $e){
                    //$conn_status='false';
                    //die(header('Location:/nhsoCheck/conect_config_conn_err.php'));
                    
                    //header('Location:/nh205/') ;

                    }            
                
?>                