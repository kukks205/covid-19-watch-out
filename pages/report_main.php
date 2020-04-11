<div style="padding: 20px;height:100%">
    <div class="clearfix">
        <h2 class="float-left">
            <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-folder-open"
                    style="font-size: 24px;"></i></button>
            <b>ระบบรายงาน</b>
        </h2>
    </div>
    <hr>

    <div class="list-group">
    <a href="#" class="list-group-item list-group-item-action list-group-item-primary">
        <h4>หัวข้อรายงาน</h4>
    </a>
        <a href="index.php?url=pages/report_person_trace.php" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">1. แบบรายงานติดตามประชาชนที่เดินทางมาจากประเทศที่เป็นเขตโรคติดต่ออันตราย พื้นที่ที่มีการระบาดต่อเนื่อง และจากกรุงเทพมหานคร/ปริมณฑล</p>
                <strong><i class="fas fa-angle-right"></i></strong>
            </div>
            <!--<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius
                blandit.</p>-->
        </a> 

        <a href="index.php?url=pages/sum_risk_hosp.php&hospcode=<?=$hospcode?>" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">2. สรุปจำนวนกลุ่มเสี่ยงยังเฝ้าระวังแยกรายหมู่บ้าน</p>
                <strong><i class="fas fa-angle-right"></i></strong>
            </div>
            <!--<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius
                blandit.</p>-->
        </a> 
        <a href="index.php?url=pages/person_trace_daily.php&hospcode=<?=$hospcode?>" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">3. รายงานติดตามกลุ่มเสี่ยงรายบุคคล รายวัน</p>
                <strong><i class="fas fa-angle-right"></i></strong>
            </div>
            <!--<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius
                blandit.</p>-->
        </a> 
        <!--
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">สรุปข้อมูลผู้ที่เดินทางจากประเทศเสี่ยงตามประกาศ(แยกรายตำบล)</h5>
                <small></small>
            </div>
        </a>        
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">สรุปข้อมูลผู้ที่เดินทางจากประเทศนอกเหนือจากประกาศ</h5>
                <small>3 days ago</small>
            </div>

        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">สรุปข้อมูลผู้ที่เดินทางจากพื้นที่เสี่ยงภายใจประเทศตามประกาศ</h5>
                <small class="text-muted">3 days ago</small>
            </div>

        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">สรุปข้อมูลผู้ที่เดินทางจากต่างจังหวัดอื่นๆนอกเหนือจากประกาศ</h5>
                <small class="text-muted">3 days ago</small>
            </div>
        </a>-->
    </div>

</div>