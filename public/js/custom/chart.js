var chart = null;
var rewards = ['Nakula','Sadewa','Arjuna','Punakawan','Punta Dewa'];
function getDataChart(chart_selected){
    chart = null;
    $('.page-loader-wrapper').css("background", "transparent").fadeIn();
    $.ajax({
        url: host+"abs/getDataChart",
        method: "POST",
        dataType: "JSON",
        data: {loc_selected:chart_selected},
        success: function(data){
            var datasetchart = [];
            if(data.status === 0){
                var sakit = [];
                var alfa = [];
                var izin = [];
                var hadir = [];
                var ind = 0;
                while(ind < data.data.length){
                    sakit.push(data.data[ind].sakit);
                    alfa.push(data.data[ind].alfa);
                    izin.push(data.data[ind].izin);
                    hadir.push(data.data[ind].hadir);
                    ind++;
                }
                
                datasetchart.push({label: "Sakit", data: sakit, backgroundColor: 'rgba(0, 188, 212, 0.8)'});
                datasetchart.push({label: "Alfa", data: alfa, backgroundColor: 'rgba(233, 30, 99, 0.8)'});
                datasetchart.push({label: "Izin", data: izin, backgroundColor: 'rgba(255, 152, 0, 0.8)'});
                datasetchart.push({label: "Hadir", data: hadir, backgroundColor: 'rgba(139, 195, 74, 0.8)'});

                chart = new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar', datasetchart, data.loc_names));
            }
            $('.page-loader-wrapper').css("background", "transparent").fadeOut();
        },
        error: function(data){
            $('.page-loader-wrapper').css("background", "transparent").fadeOut();
        }
    });
}

function getRewardChart(){
    chart = null;
    $.ajax({
        url: host+"member/getRewardChart",
        method: "POST",
        dataType: "JSON",
        data: {},
        success: function(data){
            var datasetchart = [];
            if(data.status === 0){
                datasetchart.push({label: "Reward", data: [data.data[0], data.data[1], data.data[2], data.data[3], data.data[4]], backgroundColor: 'rgba(0, 188, 212, 0.8)'});

                chart = new Chart(document.getElementById("reward_chart").getContext("2d"), getChartJs('reward', datasetchart));
            }
        },
        error: function(data){

        }
    });
}
$(function () {
    getDataChart();
    getRewardChart();
});

var config = null;
function getChartJs(type, dataset, labels_input = null) {

    if (type === 'line') {
        config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    label: "My First dataset",
                    data: [65, 59, 80, 81, 56, 55, 40],
                    borderColor: 'rgba(0, 188, 212, 0.75)',
                    backgroundColor: 'rgba(0, 188, 212, 0.3)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.9)',
                    pointBorderWidth: 1
                }, {
                        label: "My Second dataset",
                        data: [28, 48, 40, 19, 86, 27, 90],
                        borderColor: 'rgba(233, 30, 99, 0.75)',
                        backgroundColor: 'rgba(233, 30, 99, 0.3)',
                        pointBorderColor: 'rgba(233, 30, 99, 0)',
                        pointBackgroundColor: 'rgba(233, 30, 99, 0.9)',
                        pointBorderWidth: 1
                    }]
            },
            options: {
                responsive: true,
                legend: false
            }
        }
    }
    else if (type === 'bar') {
        config = {
            type: 'bar',
            data: {
                labels: labels_input,
                datasets: dataset
            },
            options: {
                responsive: true,
                legend: true
            }
        }
    }
    else if(type == 'reward'){
        config = {
            type: 'bar',
            data: {
                labels: ['Nakula','Sadewa','Arjuna','Punakawan','Punta Dewa'],
                datasets: dataset
            },
            options: {
                responsive: true,
                legend: true
            }
        }

    }
    else if (type === 'radar') {
        config = {
            type: 'radar',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    label: "My First dataset",
                    data: [65, 25, 90, 81, 56, 55, 40],
                    borderColor: 'rgba(0, 188, 212, 0.8)',
                    backgroundColor: 'rgba(0, 188, 212, 0.5)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.8)',
                    pointBorderWidth: 1
                }, {
                        label: "My Second dataset",
                        data: [72, 48, 40, 19, 96, 27, 100],
                        borderColor: 'rgba(233, 30, 99, 0.8)',
                        backgroundColor: 'rgba(233, 30, 99, 0.5)',
                        pointBorderColor: 'rgba(233, 30, 99, 0)',
                        pointBackgroundColor: 'rgba(233, 30, 99, 0.8)',
                        pointBorderWidth: 1
                    }]
            },
            options: {
                responsive: true,
                legend: false
            }
        }
    }
    else if (type === 'pie') {
        config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [225, 50, 100, 40],
                    backgroundColor: [
                        "rgb(233, 30, 99)",
                        "rgb(255, 193, 7)",
                        "rgb(0, 188, 212)",
                        "rgb(139, 195, 74)"
                    ],
                }],
                labels: [
                    "Pink",
                    "Amber",
                    "Cyan",
                    "Light Green"
                ]
            },
            options: {
                responsive: true,
                legend: false
            }
        }
    }
    return config;
}