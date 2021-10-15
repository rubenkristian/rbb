{{include adminpage/header}}<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Area</h2>
                </div>
                <div class="body">
	                <div class="table-responsive">
                        <br/>
                        <!-- <div class="row">
                            <div class="form-group">
                                <div class="left col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">search</i>
                                        </span>
                                        <div class="form-line">
                                            <input name="pass" type="text" id="search" class="form-control" placeholder="Search username or fullname" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <nav class="form-group">
                                <div class="right">
                                    <ul class="pagination" id="list-pagination-up">
                                    </ul>
                                </div>
                            </nav>
                        </div> -->
	                    <table id="list-area" class="table table-bordered table-striped table-hover js-basic-example dataTable">
	                        <thead>
	                            <tr>
                                    <th>Nama Location</th>
                                    <th>Latitude</th>
                                    <th>Longlitude</th>
                                    <th>Radius</th>
                                    <th class="text-center">Action</th>
	                            </tr>
	                        </thead>
	                    </table>
                        <!-- <div class="row">
                            <nav class="form-group">   
                                <div class="right">
                                    <ul class="pagination" id="list-pagination-down">
                                    </ul>
                                </div>
                            </nav>
                        </div> -->
	                </div>
	            </div>
            </div>
        </div>
    </div>
</section>
{{include adminpage/footer}}