<?php

class dbClass {

    var $conn;

//ใช้ connect DB เพื่อใช้ใน Class
    public function __construct() {

        include 'conf.ini.php';

        $this->conn = new PDO("mysql:host=$hostname;port=$port;dbname=$dbname;", $dbusername, $dbpassword, array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset",));
    }


//ดึงข้อมูล subquery แบบไม่มี '' 
    public function getSubIntData($sql) {
        $row = 0;
        $text = '';
        $result = $this->conn->query($sql, PDO::FETCH_ASSOC);
        while ($val = $result->fetch()) {
            if ($row > 0)
                $text = $text . ',' . $val['cc'];
            else
                $text = $text . $val['cc'];
            $row = $row + 1;
        }
        return $text;
    }



//ดึงข้อมูล subquery แบบมี '' 
    public function getSubStrData($sql) {
        $row = 0;
        $text = "";
        $result = $this->conn->query($sql, PDO::FETCH_ASSOC);
        while ($val = $result->fetch()) {
            if ($row > 0)
                $text = $text . ",'" . $val['cc'] . "'";
            else
                $text = $text . "'" . $val['cc'] . "'";
            $row = $row + 1;
        }
        return $text;
    }


//ดึงข้อมูล String data แบบตัวเดียว
    public function GetStringData($sql) {
        $row = 0;
        $result = $this->conn->query($sql, PDO::FETCH_OBJ);
        foreach ($result as $val) {
            if ($row > 0)
                break;
            else
                $str = $val->cc;
            $row = $row + 1;
        }
        return @$str;
    }

    public function GetArrData($array) {
        $text = "";
        $row = 0;
        foreach ($array as $key) {
            if ($row > 0)
                $text = $text . ",'" . $key . "'";
            else
                $text = "'" . $key . "'";
            $row = $row + 1;
        }

        return $text;
    }

    public function sendLog($loginname, $logindate, $ip, $browser, $os, $event, $menu) {
        $log = $this->conn->prepare("insert into m_log(username,login_datetime,ipaddress,browser,os,event,menu) values('" . $loginname . "','" . $logindate . "','" . $ip . "','" . $browser . "','" . $os . "','" . $event . "','" . $menu . "');");
        $log->execute();
    }

}

?>