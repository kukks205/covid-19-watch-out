<?php

class encriptStrClass {
    public function encodeUrl($str){
    $eStr=base64_encode($str);
    return $eStr;
    }
}
?>
