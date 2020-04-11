<script>
        
    function showLoading(){
        document.getElementById('loading').style.display = 'block';
    }
</script>
<div style="padding: 20px;height:100%">
    <div class="clearfix">
        <h2 class="float-left">


            <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-search"
                    style="font-size: 24px;"></i></button>

            <b>ค้นหาข้อมูลบุคคล</b>
        </h2>
    </div>
    <hr>

    <div class="row">

        <div class="col-md-4" style="padding:5px;">
                <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">ค้นหา</label>
                            <div class="col-sm-10 input-group mb-3">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="ระบุเงื่อนไขเพื่อค้นหา" maxlength="50">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" name="search" id="search">
                                        <i class="fas fa-search"></i></button>
                                </div>
                            </div>
                </div>
        </div>
        
        <div class="col-md-8" style="padding:5px;">
        <div class="list-group list-group-flush" id="title" name="title" style="display:block;">
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start list-group-item-info">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">ผลการค้นหา</h5>
                    <strong>0 รายการ</strong>
                </div>
            </a>
            <div id="loading" name="loading" align='center' style="display:none;"><img src="img/loading.gif"></div>
        </div>
            
             <div id="result" name="result"></div>
            
        </div>
    </div>
</div>
<script>

    $("#search").click(function() {

        var theName = $.trim($("#name").val());

        if(theName.length > 0){
            document.getElementById('loading').style.display = 'block';
            document.getElementById('title').style.display = 'block';
            document.getElementById('result').style.display = 'none';

            $.ajax({
                url: "search_person.php", //ที่อยู่ของไฟล์เป้าหมาย
                global: false,
                type: "GET", //รูปแบบข้อมูลที่จะส่ง
                data: ({
                    name: $("#name").val()
                }), 
                dataType: "text", //รูปแบบข้อมูลที่ส่งกลับ xml,script,json,jsonp,text
                success: function (data) {
                    $("#result").html(data);
                    document.getElementById('result').style.display = 'block';
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('title').style.display = 'none';
            }
            });

            $("#resultLog").ajaxError(function(event, request, settings, exception) {
                        $("#resultLog").html("<h4>พบข้อผิดพลาดไม่สามารถค้นหาบุคคลได้สำเร็จ</h4>");
            });

        }else{

            alert('กรุณาระบุข้อความที่ต้องการค้นหา');
        }



    });
</script>