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
                    <form class="form-horizontal style-form" method="post" enctype="multipart/form-data" name="form1" id="change_password">
                          <input type="hidden" value="<?= $this->req->Get("id")?>"/>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Old Password</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="user" type="password" id="old_password" class="form-control" placeholder="Old password" required />
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">New Password</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="fullname" type="password" id="new_password" class="form-control" placeholder="New password" required />
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Re type New Password</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="fullname" type="password" id="new_re_password" class="form-control" placeholder="Re type new password" required />
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