<?php

class exportClass {

    var $conn;

        //ใช้ connect DB เพื่อใช้ใน Class
        public function __construct() {
            
            include 'conf.ini.php';

            $this->conn = new PDO("mysql:host=$hostname;port=$port;dbname=$dbname;", $dbusername, $dbpassword, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset",
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
                $this->conn->setAttribute(PDO::ATTR_TIMEOUT,90);   
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }

        public function exportText($path,$tablename,$sql) {

            //กำหนด path และชื่อไฟล์ที่จะเขียน
            $filename = $path."/$tablename.txt";
            $fp = fopen($filename, "w") or die("Unable to open file!");
            
            //กำหนดตัวแปรไว้เก็บค่าที่ต้องการ
            $json_data = [];
            $data="";
            $f43data="";

            //ดึงข้อมูลจาก SQL ที่ส่งมา
            $obj = $this->conn->query($sql, PDO::FETCH_OBJ);
            foreach($obj as $key => $value)
                {
                array_push($json_data, $value);
                }
            //นำข้อมูลที่ได้แปลงมาเป็น JSON และแปลงกลับไปเป็น Array ในรูปแบบที่จะใช้งาน
            $data = json_encode($json_data);
            $jsonArray = json_decode($data, true);
            $arrlength = count($jsonArray);
            
            //นำข้อมูลจาก Array มาเปลี่ยนเป็นข้อมูลตามรูปแบบ 43 แฟ้ม
            for($x = 0; $x < $arrlength; $x++) {
                //header
                if($x==0){
                    $columns = implode("|",array_keys($jsonArray[$x]));
                    $f43data.=$columns.PHP_EOL;
                }
                //detail
                $values  = implode("|",array_values($jsonArray[$x]));
                $f43data.= $values.PHP_EOL;
            }

            //เขียนไฟล์ข้อมูล 43 แฟ้มในรูปแบบ text Encoding UTF-8
            fwrite($fp,"\xEF\xBB\xBF".$f43data);
            fclose($fp);
            return $filename;

            $this->conn = null;

        }

}


class importClass {
        //ใช้ connect DB เพื่อใช้ใน Class
        public function __construct() {
            include 'conf.ini.php';
            $this->conn = new PDO("mysql:host=$hostname;port=$port;dbname=$dbname;", $dbusername, $dbpassword, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset",
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
                $this->conn->setAttribute(PDO::ATTR_TIMEOUT,90);   
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
        
        public function importText($path,$filename,$tablename){
            //กำหนด path และชื่อไฟล์ที่จะเขียน
            $upper_filename = strtoupper($filename);
            $lower_filename = strtolower($filename);

            $filepath = $path."/$upper_filename.txt";

            if (file_exists($filepath)) {
                $import_filepath =$filepath; 

            } else {
                $import_filepath =$path."/$lower_filename.txt"; 
            }
           
                    $sql = "LOAD DATA LOCAL INFILE '$import_filepath'";
                    $sql.= " REPLACE INTO TABLE $tablename";
                    $sql.= " CHARACTER SET UTF8";
                    $sql.= " FIELDS TERMINATED BY '|'  LINES TERMINATED BY '\\r\\n' IGNORE 1 LINES";
                    $import = $this->conn->exec($sql);

                    if(!$import){
  
                        return "Not imported data". $upper_filename.".txt";

                    }else {

                        return $import_filepath;
                        $this->conn = null;
    
                    }

        }





}




?>