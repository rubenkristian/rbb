var request = null;

$(document).ready(function(e){
    $("#list-member").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host + "member/getaccountlist",
            "type": "POST"
        },
        "columns":[
            {"data": "nik"},
            {"data": "name"},
            {"data": "terminal"},
            {"data": "status"},
            {"data": "action", "orderable":false}
        ]
    });
});