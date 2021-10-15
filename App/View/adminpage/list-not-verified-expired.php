{{include adminpage/header}}
<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Account not verified</h2>
                </div>
                <div class="body">
	                <div class="table-responsive">
                        <br/>
	                    <table id="list-user" class="table table-bordered table-striped table-hover js-basic-example dataTable">
	                        <thead>
	                            <tr>
                                    <th>ID</th>
                                    <th>Upline</th>
                                    <th>Nama</th>
                                    <th>WA</th>
                                    <th>Kode</th>
                                    <th class="text-center">Action</th>
	                            </tr>
	                        </thead>
	                    </table>
	                </div>
	            </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Update Account</h4>
            </div>
            <div id="message" class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="updatebtn" type="button" class="btn btn-link waves-effect">Update</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Delete Account</h4>
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