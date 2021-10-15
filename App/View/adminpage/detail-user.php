{{include adminpage/header}}<section class="content">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Detail User</h2>
                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li><a class="refresh" data-content="1" href="<?=$this->req->urlmain?>user/edit?id=<?=$account["id"]?>">Edit</a></li>
                            <li><a class="refresh" data-content="1" href="<?=$this->req->urlmain?>user/editpass?id=<?=$account["id"]?>">Edit password</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <br/>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>WA</h5>
                        <p><?=$account["wa"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Name</h5>
                        <p><?=$account["fullname"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Occupation</h5>
                        <p><?=$account["occupation"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Company</h5>
                        <p><?=$account["company"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Province</h5>
                        <p><?=$account["province"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>City</h5>
                        <p><?=$account["city"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Bank</h5>
                        <p><?=$account["bank"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Account Bank Name</h5>
                        <p><?=$account["bank_account_name"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Account Bank Number</h5>
                        <p><?=$account["bank_account_number"]?></p>
                    </div>
                    <div class="section">
                        <h5>Wallet</h5>
                        <p><?=$account["wallet"]?></p>
                    </div>
                    <?php foreach($generations as $generation): ?>
                    <div class="section">
                    <h5>Keturunan <?=$generation['index']?></h5>
                    <p><?=$generation['users']?> Mitra</p>
                    <?php endforeach ?>
	            </div>
            </div>
        </div>
    </div>
</section>
<!-- Small Size -->
<div class="modal fade" id="smallModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Delete User</h4>
            </div>
            <div id="message" class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="deletebtn" type="button" class="btn btn-link waves-effect">Delete</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{include adminpage/footer}}