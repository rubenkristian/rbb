{{include adminpage/header}}
<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>admin</h2>
                    <ul class="header-dropdown m-r--5">
                    </ul>
                </div>
                <div class="body">
	                <div class="table-responsive">
                        <br/>
                        <div class="list-group">
                            <a href="changepass" class="list-group-item">Change password</a>
                            <!--<a href="add" class="list-group-item">Add Admin</a>-->
                        </div>
                        <br/>
	                    <!--<table id="list-admin" class="table table-bordered table-striped table-hover js-basic-example dataTable">-->
	                    <!--    <thead>-->
	                    <!--        <tr>-->
                     <!--               <th>Id</th>-->
                     <!--               <th>Username</th>-->
                     <!--               <th class="text-center">Action</th>-->
	                    <!--        </tr>-->
	                    <!--    </thead>-->
	                    <!--</table>-->
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