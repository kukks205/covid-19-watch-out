<?php


function loadData( $sql, $maxrows=NULL ) {
    
    include "config.inc.php";
    $list = array();
    $cnt = 0;
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $list[]=$row['cc'];
            if( $maxrows && $maxrows == $cnt++ ) {
		break;
            }
        }
    return $list;	
    $conn->close();
}


function getSubqueryData($sql) {
    $data=loadData($sql);
    $text='';
    $row=0;
    foreach($data as $key2=>$val2)
    {
                if ($row>0)
                    $text =$text.",'".$val2."'";
                    else
                    $text="'".$val2."'";	
                    $row=$row+1;
    }
    return $text;  
}

function GetStringData($sql) {
    $data=loadData($sql);
    $text='';
    $row=0;
    foreach($data as $key2=>$val2)
    {
                if ($row>0)
                    break;
                    else
                    $text=$val2;	
                    $row=$row+1;
    }
    return $text;  
}


/*function Exec($sql){
    include "config.inc.php";
    $conn->query($sql);
}*/
?>