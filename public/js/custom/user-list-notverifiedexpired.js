var request = null;
var pageactive = 0;
var table = null;

function updateAjax(id){
    $.ajax({
        url: host+"user/submitupdate",
        method: "POST",
        dataType: "JSON",
        data: {id:id},
        success: function(data){
            alert(data.msg);
            table.ajax.reload();
        },
        error: function(data){
            alert(data);
        }
    });
    return false;
}

function deleteAjax(id) {
    $.ajax({
        url: host+"user/submitdelete",
        method: "POST",
        dataType: "JSON",
        data: {id:id},
        success: function(data){
            alert(data.msg);
            table.ajax.reload();
        },
        error: function(data){
            alert(data);
        }
    });
    return false;
}

$(document).ready(function() {
    
    table = $("#list-user").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host+"user/listnotverifiedexpired",
            "type": "POST"
        },
        "columns":[
            {"data": "id"},
            {"data": "id_created"},
            {"data": "fullname"},
            {"data": "wa"},
            {"data": "code"},
            {"data":"tools", "orderable":false}
        ],
        dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    });
    
    $(document).on('click', '#deleteShow', function(e) {
        $('#deleteModal').modal('show');
        var id = $(this).data('iddelete');
        $("#deletebtn").data("id", id);
    });
    
    $(document).on('click', '#deletebtn', function(e) {
        var id = $(this).data('id');
        
        $('#deleteModal').modal('hide');
        e.preventDefault();
        deleteAjax(id);
    });
    
    $(document).on('click', '#updateShow', function(e) {
        $('#updateModal').modal('show');
        var id = $(this).data('idupdate');
        $("#updatebtn").data("id", id);
    });
    
    $(document).on('click', '#updatebtn', function(e) {
        var id = $(this).data('id');
        
        $('#updateModal').modal('hide');
        e.preventDefault();
        updateAjax(id);
    });
});