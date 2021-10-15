var request = null;
var pageactive = 0;
var table = null;

$(document).ready(function() {
    
    table = $("#list-history").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host+"admin/listhistory",
            "type": "POST"
        },
        "columns":[
            {"data": "id"},
            {"data": "name"},
            {"data": "date"},
            {"data": "detail", "orderable":false},
            {"data": "ip", "orderable":false}
        ],
        dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    });
});