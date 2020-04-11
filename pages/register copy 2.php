<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-user-plus"></i>
                    <b>ลงทะเบียนกลุ่มเสี่ยง</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="addUser" id="addUser" method="post">
                    <input type="hidden" name="createUser" id="createUser" value="true">
                    <div class="form-group row">
                        <label for="cid" class="col-sm-4 col-form-label">CID</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cid" name="cid"
                                placeholder="รหัสประจำตัวประชาชน">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fullname" class="col-sm-4 col-form-label">ชื่อ-สกุล</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                placeholder="ชื่อ-สกุล">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sex" class="col-sm-4 col-form-label">เพศ</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="sex" id="sex">
                                <option>
                                    <-- เพศ -->
                                </option>
                                <option value="1">ชาย</option>
                                <option value="2">หญิง</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="age" class="col-sm-4 col-form-label">อายุ(ปี)</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="age" name="age" placeholder="อายุ(ปี)">
                        </div>
                    </div>

                    <?php

                    $sql_nation = "select * from cnation order by nationname";

                    $nation_list = $db->query($sql_nation, PDO::FETCH_OBJ);

                    ?>

                    <div class="form-group row">
                        <label for="nation" class="col-sm-4 col-form-label">สัญชาติ</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="nation" id="nation">
                                <option>
                                    <-- สัญชาติ -->
                                </option>
                                <?php foreach ($nation_list as $rn) {?>

                                <option value="<?=$rn->nationcode?>"><?=$rn->nationname?></option>

                                <?php } ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-sm-4 col-form-label">บ้านเลขที่</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="บ้านเลขที่">
                        </div>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-undo"></i>
                    ยกเลิก</button>
                <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> บันทึก</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="clearfix">
    <h2 class="float-left"><i class="fas fa-user-plus" style="font-size: 24px;"></i> <b>ลงทะเบียนกลุ่มเสี่ยง</b>
    </h2>
</div>
<hr>


<div class="card">
    <div class="card-body">
        <h4 class="modal-title"><i class="fas fa-user-plus"></i><b> ลงทะเบียนกลุ่มเสี่ยง</b></h4>
        <hr>
        <form name="addUser" id="addUser" method="post">
            <input type="hidden" name="createUser" id="createUser" value="true">

            <!--rows1--->
            <div class="row">
                <div class="col-md-4" style="padding:5px;">

                    <div class="form-group">
                    <label for="cid" class="form-label">CID</label>

                            <input type="text" class="form-control" id="cid" name="cid"
                                placeholder="รหัสประจำตัวประชาชน">
                        </div>
  <!--                      <label for="cid" class="col-lg-4 col-form-label">CID</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="cid" name="cid"
                                placeholder="รหัสประจำตัวประชาชน">
                        </div>-->
                </div>


                </div>
                <div class="col-md" style="padding:5px;">
                    <div class="form-group row">
                        <label for="fullname" class="col-lg-4 col-form-label">ชื่อ-สกุล</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                placeholder="ชื่อ-สกุล">
                        </div>
                    </div>
                </div>
                <div class="col-md-2" style="padding:5px;">
                    <div class="form-group row">
                        <label for="sex" class="col-sm-4 col-form-label">เพศ</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="sex" id="sex">
                                <option>
                                    -- ระบุเพศ --
                                </option>
                                <option value="1">ชาย</option>
                                <option value="2">หญิง</option>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2" style="padding:5px;">
                    <div class="form-group row">
                        <label for="age" class="col-sm-4 col-form-label">อายุ(ปี)</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="age" name="age" placeholder="อายุ(ปี)">
                        </div>
                    </div>
                </div>

                <div class="col-md-2" style="padding:5px;">

                    <?php
                        $sql_nation = "select * from cnation order by nationname";
                        $nation_list = $db->query($sql_nation, PDO::FETCH_OBJ);
                    ?>

                    <div class="form-group row">
                        <label for="nation" class="col-sm-4 col-form-label">สัญชาติ</label>
                        <div class="col-md-8">
                            <select class="form-control" name="nation" id="nation">
                                <option>
                                    -- ระบุสัญชาติ --
                                </option>
                                <?php foreach ($nation_list as $rn) {?>

                                <option value="<?=$rn->nationcode?>"><?=$rn->nationname?></option>

                                <?php } ?>

                            </select>
                        </div>
                    </div>


                </div>

            </div>
            <!--rows2--->
            <div class="row">
                <div class="col-sm" style="padding:10px;">



                </div>
                <div class="col-sm" style="padding:10px;">
                    <div class="form-group row">
                        <label for="fullname" class="col-lg-4 col-form-label">ชื่อ-สกุล</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                placeholder="ชื่อ-สกุล">
                        </div>
                    </div>
                </div>
                <div class="col-sm" style="padding:10px;">
                    <div class="form-group row">
                        <label for="sex" class="col-sm-4 col-form-label">เพศ</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="sex" id="sex">
                                <option>
                                    -- ระบุเพศ --
                                </option>
                                <option value="1">ชาย</option>
                                <option value="2">หญิง</option>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm" style="padding:10px;">
                    <div class="form-group row">
                        <label for="age" class="col-sm-4 col-form-label">อายุ(ปี)</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="age" name="age" placeholder="อายุ(ปี)">
                        </div>
                    </div>
                </div>
            </div>
    </div>











    <div class="form-group row">
        <label for="address" class="col-sm-4 col-form-label">บ้านเลขที่</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="address" name="address" placeholder="บ้านเลขที่">
        </div>
    </div>