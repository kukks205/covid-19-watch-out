<?php

function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
       if ('.' === $file || '..' === $file) continue;
       if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
       else unlink("$dir/$file");
   }
   
   rmdir($dir);
}



class upLoadFile {

    public function uploadZipFile($file,$targetPath) {

        $filename = $file['name'];
        $type = $file['type'];
        $source = $file['tmp_name'];
        $size = $file['size'];

        $name = explode(".", $filename);
        $hospcode = explode("_", $filename);
        $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
        $filedate = date('YmdHis');

        foreach ($accepted_types as $mime_type) {
            if ($mime_type == $type) {
                $checkType = true;
                break;
            }
        }

        $checkZip = strtolower($name[1]) == 'zip' ? true : false;
        if (!$checkZip) {
            $message = 0;//ไม่มีzip file
        }

        $path = $targetPath . '/';

        $filenoext = basename($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
        $filenoext = basename($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)

        $targetdir = $path . "/" . $hospcode[1];//
        $targetzip = $path . $filename; // target zip file
        chmod($targetzip, 0777);

        if (is_dir($targetdir)) {

            foreach (scandir($targetdir) as $file) {
                if ('.' === $file || '..' === $file)
                    continue;
                if (is_dir("$targetdir/$file"))
                    rmdir_recursive("$targetdir/$file");
                else
                    unlink("$targetdir/$file");
            }

            rmdir($targetdir);
        }

        mkdir($targetdir, 0777);
        chmod($targetdir, 0777);

        if (move_uploaded_file($source, $targetzip)) {

            $zip = new ZipArchive();

            $x = $zip->open($targetzip);  // open the zip file to extract
            if ($x === true) {

                $zip->extractTo($targetdir); // place in the directory with same name  
                $zip->close();

                unlink($targetzip);
            }
            
            $message=1;//สำเร็จ
            
        } else {
            $message=2;//นำเข้าไม่สำเร็จ
            
        }
        
        return $message;

    }


}




?>