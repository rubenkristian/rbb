{{include adminpage/header}}<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Provinsi</h2>
                </div>
                <div class="body">
	                <div class="table-responsive">
                        <br/>
	                    <table id="list-province" class="table table-bordered table-striped table-hover js-basic-example dataTable">
	                        <thead>
	                            <tr>
                                    <th>ID</th>
                                    <th>ISO</th>
                                    <th>Nama</th>
	                            </tr>
	                        </thead>
	                    </table>
	                </div>
	            </div>
            </div>
        </div>
    </div>
<button class="btn pmd-btn-fab pmd-ripple-effect btn-light" type="button"><i class="material-icons pmd-sm">add</i></button>
</section>
<!-- Small Size -->
<div class="modal fade" id="smallModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Hapus Province</h4>
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