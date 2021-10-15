
$(document).ready(function(e){
    $("#list-member").DataTable({
        "dom": 'Bfrtip',
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host + "member/getContractExp",
            "type": "POST"
        },
        "columns":[
            {"data": "nik"},
            {"data": "name"},
            {"data": "end_contract"},
            {"data": "status"},
            {"data": "area"}
        ],
        buttons: [ 'excel', 'print'
        ]
    });
});