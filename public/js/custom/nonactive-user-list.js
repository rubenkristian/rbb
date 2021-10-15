var request = null;
var pageactive = 0;
var table = null;

function activeAjax(id){
    $.ajax({
        url: host+"user/submitactive",
        method: "POST",
        dataType: "JSON",
        data: {id:id},
        success: function(data){
            alert(data.msg);
            table.ajax.reload();
        },
        error: function(data){
            console.log(data);
        }
    });
    return false;
}

function deleteAjax(id){
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
            console.log(data);
        }
    });
    return false;
}

$(document).ready(function() {
    $(document).on('click', '.mdl', function (e) {
        var name = $(this).data('name');
        var id = $(this).data('id');
        $("#message").html("Delete "+name+" ?");
        $("#deletebtn").attr('data-iddelete', id);
        $('#smallModal').modal('show');
    });
    table = $("#list-user").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host+"user/listnonactive",
            "type": "POST"
        },
        "columns":[
            {"data": "id"},
            {"data": "fullname"},
            {"data": "wa"},
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
    
    $(document).on('click', '#modalshow', function(e) {
        $('#smallModal').modal('show');
        var id = $(this).data('id');
        $("#activebtn").data("id", id);
    });
    
    $(document).on('click', '#activebtn', function(e) {
        var id = $(this).data('id');
        
        $('#smallModal').modal('hide');
        e.preventDefault();
        activeAjax(id);
    });
});