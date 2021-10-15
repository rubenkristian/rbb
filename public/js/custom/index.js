var terminal_id = 1;
var selected_locations = [];
var charts_absen = [];
var shift = 1;
$(document).ready(function(){
    $("#location").change(function(e){
    	shift = $("#shift").val();
    	var terminal = $(this).val();
    	getData(terminal, shift);
    });
    
    $("#shift").change(function(e){
    	shift = $(this).val();
    	getData(terminal_id, shift);
    });
    
    $("#location_manpow").change(function(e){
    	selected_locations = $(this).val();
    	getManPower(selected_locations);
    });
    
    $("#charts_absen").change(function(e){
    	charts_absen = $(this).val();
    	getDataChart(charts_absen);
    });
    
    $(".refresh").click(function(e){
    	var content = $(this).attr("data-content");
    	switch(content){
    		case "1":
    			shift = $("#shift").val();
    			getData(terminal_id,shift);
    		break;
    		case "2":
    		    if(selected_locations.length > 0){
    			    getManPower(selected_locations);
    		    }
    		break;
    		case "3":
    		    if(charts_absen.length > 0){
    			    getDataChart(charts_absen);
    		    }
    			break;
    		case "4":
    			getRewardChart();
    			break;
    	}
    });
    
    $(function(){
    	getData($("#location").val(),1);
    	getManPower(selected_locations);
    	getContractExpired();
    });
    
    function getData(terminal, shift){
    	terminal_id = terminal;
    	$('.page-loader-wrapper').css("background", "transparent").fadeIn();
    	$.ajax({
    		url: host+"abs/getAbsensiTerminal",
    		method: "POST",
    		dataType: "JSON",
    		data: {terminal: terminal, shift: shift},
    		success: function(data){
    			if(data.status === 0){
    				$("#abs_content").html(data.data.alfa);
    				$("#sakit_content").html(data.data.sakit);
    				$("#hadir_content").html(data.data.hadir);
    				$("#izin_content").html(data.data.izin);
    			}
    			$('.page-loader-wrapper').css("background", "transparent").fadeOut();
    		},
    		error: function(data){
    			$('.page-loader-wrapper').css("background", "transparent").fadeOut();
    		}
    	});
    }
    
    function hide(){
    	$("#loc_1").hide();
    	$("#loc_2").hide();
    	$("#loc_3").hide();
    }
    
    function getManPower(locations){
    	hide();
    	$('.page-loader-wrapper').css("background", "transparent").fadeIn();
    	$.ajax({
    		url: host+"member/getManPower",
    		method: "POST",
    		dataType: "JSON",
    		data: {locations:locations},
    		success: function(data){
    			if(data.status === 0){
    				var ind = 1;
    				while(ind <= data.data.length){
    					$("#loc_"+ind).show();
    					$("#name_loc_"+ind).html(data.data[ind-1].name);
    					$("#ter_"+ind).html(data.data[ind-1].count);
    					ind++;
    				}
    				// $("#ter_1").html(data.data[0]);
    				// $("#ter_2").html(data.data[1]);
    				// $("#ter_3").html(data.data[2]);
    			}
    			$('.page-loader-wrapper').css("background", "transparent").fadeOut();
    		},
    		error: function(data){
    			$('.page-loader-wrapper').css("background", "transparent").fadeOut();
    		}
    	});
    }
    
    function getContractExpired(){
    	$('.page-loader-wrapper').css("background", "transparent").fadeIn();
    	$.ajax({
    		url: host+"member/contractExp",
    		method: "POST",
    		dataType: "JSON",
    		data: {},
    		success: function(data){
    			if(data.status === 0){
    				$("#contract_1").html(data.data[0]);
    				$("#contract_2").html(data.data[1]);
    			}
    			$('.page-loader-wrapper').css("background", "transparent").fadeOut();
    		},
    		error: function(data){
    			$('.page-loader-wrapper').css("background", "transparent").fadeOut();
    		}
    	})
	}
	
	$("#box_abs").click(function(){
        window.location = host+"abs/listAbsToday?location="+terminal_id+"&shift="+shift+"&status="+1;
	});
	
	$("#box_sakit").click(function(){
        window.location = host+"abs/listAbsToday?location="+terminal_id+"&shift="+shift+"&status="+3;
	});
	
	$("#box_hadir").click(function(){
        window.location = host+"abs/listAbsToday?location="+terminal_id+"&shift="+shift+"&status="+0;
	});
	
	$("#box_izin").click(function(){
        window.location = host+"abs/listAbsToday?location="+terminal_id+"&shift="+shift+"&status="+2;
	});
});