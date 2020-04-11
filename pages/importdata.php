<div style="padding: 20px;height:100%">

<div class="clearfix">
    <h2 class="float-left">

        <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-download"
                style="font-size: 24px;"></i></button>
        <b>นำเข้าข้อมูลจาก 43 แฟ้ม</b>


    </h2>

</div>
<div class="line"></div>
    <div>


        <form enctype="multipart/form-data" name="uploadF43" id="uploadF43" action="procress_import.php" method="post">

            <input type="hidden" name="uploading" id="uploading" value="true">
            <input type="hidden" name="fortythree_id" value="<?=date('YmdHis')?>">
            <div class="container-fluid">
                <div class="row justify-content-md-left">
                    <div class="col-sm-6 custom-file">
                        <input type="file" class="custom-file-input" id="file43" name="file43">
                        <label class="custom-file-label" for="file43">เลือกไฟล์</label>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
            <p>*** การกำหนดชื่อไฟล์ F43_[รหัสสถานบริการ 5หลัก]_.zip (zip file) ***</p>
            <div class="row justify-content-md-left">
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-info" id="save" name="save"><i class="fas fa-file-upload"></i>
                        Upload
                        Zip File</button>
                </div>

                <div class="col-sm-2">

                </div>
                <div class="col-sm-4">
                </div>
            </div>
        </form>
        <div style="display:none;text-align:center;" name="loading" id="loading">
            <h5>กรุณารอสักครู่ ระบบกำลังนำเข้าข้อมูล.....</h5>
            <img src="img/loading.gif">
        </div>

    </div>


    <hr>
    <div class="alert alert-success" role="alert" style="display:none;" name="success" id="success">
        <h4><b>นำเข้าข้อมูลสำเร็จ</b></h4>
        <hr>
        <p id="message_success"></p>
    </div>

    <div class="alert alert-danger" role="alert" style="display:none;" name="warning_filetype" id="warning_filetype">
        <h4><b>รูปแบบนามสกุลไฟล์ไม่ถูกต้อง </b></h4>
        <hr>
        <p>ไฟล์ที่ท่านต้องการนำเข้านี้ไม่ใช่ Zip file กรุณาตรวจสอบและทำการนำเข้าใหม่อีกครั้ง</p>
    </div>
    <div class="alert alert-danger" role="alert" style="display:none;" name="warning_fail" id="warning_fail">
        <h4><b>นำเข้าข้อมูลไม่สำเร็จ</b></h4>
        <hr>
        <p>Upload Zip file ไม่สำเร็จกรุณาติดต่อผู้ดูแลระบบหรือลองใหม่อีกครั้ง</p>
    </div>
    <div class="alert alert-danger" role="alert" style="display:none;" name="warning_empty" id="warning_empty">
        <h4><b>ยังไม่เลือกไฟล์</b></h4>
        <hr>
        <p>ไม่พบไฟล์ข้อมูลที่ต้องการนำเข้า กรุณาเลือกไฟล์ที่ต้องการนำเข้าก่อนกดปุ่ม upload</p>
    </div>

</div>
<script>

    
        document.querySelector('.custom-file-input').addEventListener('change',function(e){
 		    var fileName = document.getElementById("file43").files[0].name;
             console.log(fileName);
 		    var nextSibling = e.target.nextElementSibling
  		    nextSibling.innerText = fileName
	    });

       /* $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });*/


    $(document).ready(function () {

        $("#save").click(function (event) {

            event.preventDefault();
            document.getElementById('loading').style.display = 'block';
            var url = "process_import.php";

            var file_data = $('#file43').prop('files')[0];

            var formData = new FormData();
            formData.append('file43', file_data);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "process_import.php",
                data: formData,
                contentType: false,
                scache: false,
                processData: false,
                success: function (data) {

                    if (data.status == '1') {
                        $('#loading').hide();
                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('warning_filetype').style.display = 'block';
                        document.getElementById('warning_fail').style.display = 'none';
                        document.getElementById('warning_empty').style.display = 'none';
                        document.getElementById('success').style.display = 'none';
                        $('.custom-file-label').html('เลือกไฟล์');
                    } else if (data.status == '2') {
                        $('#loading').hide();
                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('warning_filetype').style.display = 'none';
                        document.getElementById('warning_fail').style.display = 'block';
                        document.getElementById('warning_empty').style.display = 'none';
                        document.getElementById('success').style.display = 'none';
                        $('.custom-file-label').html('เลือกไฟล์');
                    } else if (data.status == '3') {
                        $('#loading').hide();
                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('warning_filetype').style.display = 'none';
                        document.getElementById('warning_fail').style.display = 'none';
                        document.getElementById('warning_empty').style.display = 'block';
                        document.getElementById('success').style.display = 'none';
                        $('.custom-file-label').html('เลือกไฟล์');
                    } else if (data.status == '0') {

                        alert('นำเข้าไฟล์ '+data.filename+' สำเร็จ');
                        document.getElementById("message_success").innerHTML =
                            "นำเข้าข้อมูลจากไฟล์ " + data.filename + " เสร็จแล้วครับ";
                        $('#loading').hide();
                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('warning_filetype').style.display = 'none';
                        document.getElementById('warning_fail').style.display = 'none';
                        document.getElementById('warning_empty').style.display = 'none';
                        document.getElementById('success').style.display = 'block';
                        document.getElementById('success').style.display = 'block';
                        $('.custom-file-label').html('เลือกไฟล์');
                    } else {
                        $('#loading').hide();
                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('warning_filetype').style.display = 'none';
                        document.getElementById('warning_fail').style.display = 'block';
                        document.getElementById('warning_empty').style.display = 'none';
                        document.getElementById('success').style.display = 'none';
                        $('.custom-file-label').html('เลือกไฟล์');
                    }

                }
            });
        });





    });
</script>