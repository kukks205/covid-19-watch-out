<?php
        require("includes/DBConn.php");
        require("includes/function.php");
        require("includes/dateClass.php");
        require('includes/ExportClass.php');
        require('includes/uploadClass.php');
        $data = new dbClass();
        $fdate = new dateClass();
        $export = new exportClass();
        $import = new importClass();
        $upload = new upLoadFile();

            if($_FILES["file43"]["name"]){

                $targetPath ="f43_data";
                $uploaded = $upload->uploadZipFile($_FILES["file43"],$targetPath);

                if ($uploaded == 0):
                    echo json_encode(array("status"=>"1"));
                elseif ($uploaded == 2):
                    echo json_encode(array("status"=>"2"));
                else:
                    $size = $_FILES["file43"]["size"];
                    $hospcode = explode("_", $_FILES["file43"]["name"]);
                    $filename = explode(".", $_FILES["file43"]["name"]);
                    $zipfilename = $_FILES["file43"]["name"];
                    $path = $targetPath."/";
                    $targetdir = $path . $hospcode[1]."/".$filename[0];//

                    $import->importText($targetdir,"home","home");
                    $import->importText($targetdir,"person","person");
                    $import->importText($targetdir,"village","village");

                    echo json_encode(array("status"=>"0","filename"=>$zipfilename));
                endif;
            }else{
                echo json_encode(array("status"=>"3"));
            }


?>