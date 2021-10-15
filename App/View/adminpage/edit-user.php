{{include adminpage/header}}
<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Edit User
                    </h2>
                </div>
                <div class="body">
                    <form class="form-horizontal style-form" method="post" enctype="multipart/form-data" name="form1" id="edit_user">
                          <input type="hidden" id="userid" value="<?= $account['id']?>"/>
                          <input type="hidden" id="city_id" value="<?= $account['id_city']?>"/>
                          <input type="hidden" id="province_id" value="<?= $account['id_province']?>"/>
                          <input type="hidden" id="bank_id" value="<?= $account['id_bank']?>"/>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">WA</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="wa" type="text" id="wa" class="form-control" placeholder="WA" value="<?=$account['wa']?>" required />
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Email</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="wa" type="text" id="email" class="form-control" placeholder="Email" value="<?=$account['email']?>" required />
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Name</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="fullname" type="text" id="name" class="form-control" placeholder="Name" value="<?=$account['fullname']?>" required />
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Occupation</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="occupation" type="text" id="occupation" class="form-control" placeholder="Occupation" value="<?=$account['occupation']?>" required />
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Company</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="company" type="text" id="company" class="form-control" placeholder="Company" value="<?=$account['company']?>" required />
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Province</label>
                              <div class="col-sm-8">
                                  <select type="search" id="province" required ></select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">City</label>
                              <div class="col-sm-8">
                                  <select type="search" id="city" required >
                                      <option>Hello</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Bank</label>
                              <div class="col-sm-8">
                                  <select type="search" id="bank"></select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Bank Account Name</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="bank_name_account" type="text" id="bank_name_account" class="form-control" placeholder="Name Bank Account" value="<?=$account['bank_account_name']?>"/>
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Bank Account Number</label>
                              <div class="col-sm-8">
                                <div class="form-line">
                                    <input name="bank_number_account" type="text" id="bank_number_account" class="form-control" placeholder="Number Bank Account" value="<?=$account['bank_account_number']?>"/>
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