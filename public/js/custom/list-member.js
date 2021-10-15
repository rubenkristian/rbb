var request = null;

// $(function(){
//     getRecord(1,"","","");
// });

// function getRecord(page, search){
//     if(request != null){
//         request.abort();
//         request = null;
//     }

//     request = $.ajax({
//         "url": host + "member/getMemberList?page="+page+"&search="+search,
//         "method": "GET",
//         "dataType":"json",
//         success: function(data){
//             console.log(data.data.html.list);
//             if(data.status == 0){
//                 $("#list-member").html(data.data.html.list);
//                 $("#list-pagination-up").html(data.data.html.pagination);
//                 $("#list-pagination-down").html(data.data.html.pagination);
//             }else{
//                 alert(data.msg);
//             }
//         },
//         error: function(data){
//             console.log(data);
//         }
//     });

//     return request;
// }

// $("#search").on('keyup', function(e){
//     getRecord(1, $(this).val());
// });

// $(document).on('click', '.page', function(e){
//     var search = $("#search").val();
//     var page = $(this).attr('data-page');
//     $(".page").parent().removeClass("active");
//     $(this).parent().addClass("active");
//     getRecord(page, search);
// });

$(document).ready(function(e){
    $("#list-member").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host + "member/getMemberList",
            "type": "POST"
        },
        "columns":[
            {"data": "nik"},
            {"data": "name"},
            {"data": "position"},
            {"data": "ec1"},
            {"data": "ec2"},
            {"data": "relation"},
            {"data": "ncc"},
            {"data": "ncc_date"},
            {"data": "cj1"},
            {"data": "cf1"},
            {"data": "cj2"},
            {"data": "cf2"},
            {"data": "training"},
            {"data": "reward"},
            {"data": "terminal"},
            {"data": "status"},
            {"data": "action", "orderable":false}
        ]
    });
});