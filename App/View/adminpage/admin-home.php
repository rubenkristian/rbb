{{include adminpage/header}}
<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Home</h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div id="box_expired" style="cursor: pointer;" class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-red hover-expand-effect">
                                <div class="icon">
                                    <i class="material-icons">person</i>
                                </div>
                                <div class="content">
                                    <div class="text">Not Verified Expired</div>
                                    <div id="expired" class="number count-to">0</div>
                                </div>
                            </div>
                        </div>
                        <div id="box_person" style="cursor: pointer;" class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-orange hover-expand-effect">
                                <div class="icon">
                                    <i class="material-icons">person</i>
                                </div>
                                <div class="content">
                                    <div class="text">Not Verified</div>
                                    <div id="person" class="number count-to">0</div>
                                </div>
                            </div>
                        </div>
                        <div id="box_withdraw" style="cursor: pointer;" class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-cyan hover-expand-effect">
                                <div class="icon">
                                    <i class="material-icons">attach_money</i>
                                </div>
                                <div class="content">
                                    <div class="text">Withdraw Request</div>
                                    <div id="withdraw" class="number count-to">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{include adminpage/footer}}