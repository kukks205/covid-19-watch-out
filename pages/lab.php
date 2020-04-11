<?php
    $ptname = $data->GetStringData("select concat(pname,fname,'   ',lname) as cc from person where cid='$cid'");


    $sql_lab_head = "select
    plh.person_lab_head_id,p.cid,concat(p.pname,p.fname,'  ',p.lname) AS ptname,
    plh.lab_order_date,
    plh.lab_order_time,
    plh.lab_order_note_text,
    plh.lab_form_name,
    plh.hospcode,
    concat(h.hosptype,' ,',h.`name`) as hospital
    from person_lab_head as plh
    join person_lab_order as plo on plo.person_lab_head_id=plh.person_lab_head_id
    join person as p on p.person_id=plh.person_id
    join lab_items as l on l.lab_items_code=plo.lab_items_code
    join hospcode as h on h.hospcode=plh.hospcode
    where p.cid='$cid'
    group by plh.person_lab_head_id limit 10";
    //    where p.cid='$cid'



    $num_lab_heads = $db->prepare($sql_lab_head);
    $num_lab_heads->execute();
    $num_lab_head = $num_lab_heads->rowCount();

    $lab_heads = $db->query($sql_lab_head, PDO::FETCH_OBJ);


    if($num_lab_head<1){

?>

<div class="alert alert-info" role="alert">
    <h3 class="alert-heading"><b>ไม่พบประวัติ LAB ในระบบบัญชี 1</b></h3>
    <hr>
    <p class="mb-0"><?=$ptname,' ไม่พบว่ามีประวัติการตรวจทางห้องปฏิบัติการในระบบบัญชี 1 ของ',$hospname?> </p>
</div>

<?php

    } else {
?>

<h3><b>ประวัติ LAB บัญชี 1 <?=$ptname?> [HN : <?=$hn?>]</b></h3>
<?php
                        $hi = 1;
                        foreach ($lab_heads as $lab_head) {

                            //echo $lab_head->person_lab_head_id,"<br>";


                            $sql_lab = "select
                            plh.person_lab_head_id,p.cid,concat(p.pname,p.fname,' ',p.lname) AS ptname,
                            plh.lab_order_date,plh.lab_order_time,plh.lab_order_note_text,
                            plh.lab_form_name,plh.hospcode,plo.lab_items_code,
                            plo.lab_result,l.lab_items_name,
                            l.lab_items_unit,
                            l.lab_items_normal_value
                            from person_lab_head as plh
                            join person_lab_order as plo on plo.person_lab_head_id=plh.person_lab_head_id
                            join person as p on p.person_id=plh.person_id 
                            join lab_items as l on l.lab_items_code=plo.lab_items_code
                            where plh.person_lab_head_id='$lab_head->person_lab_head_id' ";

                            $labs = $db->query($sql_lab, PDO::FETCH_OBJ);


?>


<div id="accordion">
    <div style="padding-bottom:3px;">
        <button class="btn btn-info text-left" type="button" data-toggle="collapse"
            data-target="#collapse<?=$lab_head->person_lab_head_id?>" aria-expanded="false"
            aria-controls="collapse<?=$lab_head->person_lab_head_id?>" style="min-width: 100%;max-width: 100%;">
            <h5>วันที่ตรวจ
                <?php echo $fdate->FormatThaiDate($lab_head->lab_order_date)," ตรวจที่ : ",$lab_head->hospital?></h5>
        </button>
        <div class="collapse <?php echo $hi===1 ? 'show':''; ?>" id="collapse<?=$lab_head->person_lab_head_id?>"
            data-parent="#accordion">
            <div class="list-group list-group-flush">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">รายการ</th>
                            <th scope="col">ผล LAB</th>
                            <th scope="col">ค่าปกติ</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $ri = 1;
                        foreach ($labs as $lab) {
                    ?>



                        <tr>
                            <th scope="row"><?=$ri?></th>
                            <td><?=$lab->lab_items_name ?></td>
                            <td><?=$lab->lab_result," ",$lab->lab_items_unit;?></td>
                            <td><?=$lab->lab_items_normal_value;?></td>
                        </tr>
                        <?php $ri = ++$ri;}  ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php

$hi=++$hi;
        }
?>

    <?php
        
        

    }

?>
</div>