{{include adminpage/header}}

<section class="content">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Detail User</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <br/>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>RBB ID</h5>
                        <p><?=$withdraw["id_member"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>WA</h5>
                        <p><?=$withdraw["wa"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Name</h5>
                        <p><?=$withdraw["fullname"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Bank</h5>
                        <p><?=$withdraw["name"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Nomor akun bank</h5>
                        <p><?=$withdraw["bank_account_number"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Nama akun bank</h5>
                        <p><?=$withdraw["bank_account_name"]?></p>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <h5>Nominal withdrawl</h5>
                        <p><?=$withdraw["cash"]?></p>
                    </div>
                    <button id="submit-user-verified" type="button" class="btn btn-block btn-lg btn-primary waves-effect">Verified</button>
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
<script>
    var id_withdraw = <?= $withdraw['id'] ?>;
    var wa_number = "<?= $withdraw["wa"] ?>";
    var id_member = "<?= $withdraw['id_member']?>";
</script>
{{include adminpage/footer}}