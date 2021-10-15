$(document).ready(function(){
    // var bulan;
    // var tahun;
    var date_start;
    var date_ended;
    var loc;
    // var shift;
    var request = null;
    $("#report_month").submit(function(e){
        e.preventDefault();
        // bulan = $("#bulan").val();
        // tahun = $("#tahun").val();
        // shift = $("#shift").val();
        date_start = $("#date_start").val();
        date_ended = $("#date_end").val();
        loc = $("#terminal").val();
        getReport(date_start, date_ended, loc);
    });
    
    // function getReport(bulan, tahun , shift, loc_area){
    //     window.location = host+"report/reportMonth?month="+bulan+"&year="+tahun+"&shift="+shift+"&location="+loc_area;
    // }
    
    function getReport(start, end, loc_area){
        window.location = host+"report/reportMonth?start_date="+start+"&end_date="+end+"&location="+loc_area+"&url_redirect="+location;
    }
});