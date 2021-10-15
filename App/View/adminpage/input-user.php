{{include maincomponent/header}}
<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Input User</h2>
                </div>
                <div class="body">
                    <form class="form-horizontal style-form" enctype="multipart/form-data" name="form1" id="input_absensi">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">NIK</label>
                            <div class="col-sm-4">
                                <input name="nik" type="text" id="nik" class="form-control" placeholder="Masukan NIK" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Status</label>
                            <div class="col-sm-4">
                                <select id="stat" name="status" class="form-control show-tick" required>
                                    <option value=""> -- Pilih -- </option>
                                    <option value="0">Hadir</option>
                                    <option value="1">Alfa</option>
                                    <option value="2">Izin</option>
                                    <option value="3">Sakit</option>
                                    <option value="4">Off</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-4">
                                <div class="form-line">
                                    <textarea name="keterangan" type="text" id="ket" class="form-control" placeholder="Keterangan" required ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2 col-sm-2 control-label">
                                <label for="level">Lokasi Kerja</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="location_val" type="hidden" id="loc_val"/>
                                    <input name="location" type="text" id="location" class="form-control" placeholder="Type location ..." required />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2 col-sm-2 control-label">
                                <label for="level">Shift</label>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-line">
                                    <select id="shift" name="shift" class="form-control show-tick" required>
                                        <option value=""> -- Pilih Shift -- </option>
                                        <option value="1">Shift 1</option>
                                        <option value="2">Shift 2</option>
                                        <option value="3">Shift 3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" value="Submit" class="btn btn-sm btn-primary" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
{{include maincomponent/footer}}