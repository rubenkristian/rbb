{{include adminpage/header}}
<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Edit admin <?= $user ?>
                    </h2>
                </div>
                <div class="body">
                    <form class="form-horizontal style-form" method="post" enctype="multipart/form-data" name="form1" id="user_input">
                          <input type="hidden" value="<?= $this->req->Get("id")?>"/>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Username</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="user" type="text" id="username" class="form-control" placeholder="Username" required />
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Name</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="fullname" type="text" id="name" class="form-control" placeholder="Fullname" required />
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label"></label>
                              <div class="col-sm-10">
                                  <input type="submit" value="Simpan" class="btn btn-sm btn-primary" />
                              </div>
                          </div>
                      </form>
                </div>
            </div>
        </div>
    </div>
</section>
{{include adminpage/footer}}