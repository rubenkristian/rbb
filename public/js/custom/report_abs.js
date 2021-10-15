$(document).ready(function(){
    var request = null;
    var alfa;
    var sakit;
    var izin;
    var hadir;
    var bulan;
    var tahun;
    var terminal;
    var page = 1;
    function getRecord(page, bulan, tahun,alfa, izin, sakit, hadir, terminal){
        if(request !== null){
            request.abort();
            request = null;
        }
    
        request = $.ajax({
            "url": host + "report/laporanabs",
            "method": "POST",
            "dataType":"json",
            "data": {page: page,bulan: bulan, tahun: tahun, alfa: alfa, izin: izin, sakit: sakit, hadir: hadir, terminal: terminal},
            success: function(data){
                console.log(data);
                if(data.status === 0){
                    $("#list-abs").html(data.data.html);
                    $("#list-pagination-up").html(data.data.pagination);
                    $("#list-pagination-down").html(data.data.pagination);
                }else{
                    alert(data.msg);
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    
        return request;
    }
    
    $("#report_abs").submit(function(e){
        e.preventDefault();
        alfa = $("#md_checkbox_a").is(":checked") ? 1 : null;
        izin = $("#md_checkbox_i").is(":checked") ? 1 : null;
        sakit = $("#md_checkbox_s").is(":checked") ? 1 : null;
        hadir = $("#md_checkbox_h").is(":checked") ? 1 : null;
        bulan = $("#bulan").val();
        tahun = $("#tahun").val();
        terminal = $("#terminal").val();
        getRecord(1, bulan, tahun, alfa, izin, sakit, hadir, terminal);
    
    });
    
    $(document).on('click', '.page', function(e){
        page = $(this).attr('data-page');
        getRecord(page, bulan, tahun, alfa, izin, sakit, hadir, terminal);
    });
    
    $("#download").click(function(e){
        e.preventDefault();
        window.location = host+"report/reportAbsExcel?page="+page+"&bulan="+bulan+"&tahun="+tahun+"&alfa="+alfa+"&izin="+izin+"&sakit="+sakit+"&hadir="+hadir+"&terminal="+terminal;
    });
});