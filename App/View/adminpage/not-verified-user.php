{{include adminpage/header}}<section class="content">
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