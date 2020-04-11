<?php
              
                error_reporting(0);

                $hostname = $_POST['host'];
                $port = $_POST['port'];
                $dbname = $_POST['dbname']; 
                $username = $_POST['dbuser']; 
                $password= $_POST['dbpassword']; 
                $charset="utf8";


                try{
                    //connect ฐานข้อมูล 
                    $db = new PDO("mysql:host=$hostname;port=$port;dbname=$dbname;", $username, $password,array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset",));
                            $db->setAttribute(PDO::ATTR_TIMEOUT,5);   
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $conn_status='true';
                            echo json_encode(array("status"=>"true"));
                    }
                    catch (Exception $e){
                            $conn_status='false';
                            echo json_encode(array("status"=>"false"));
                    }            
                
?>                