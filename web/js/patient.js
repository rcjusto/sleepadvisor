$(function () {

    var i = 0;
    $.each(datasets, function (key, val) {
        val.color = colors[i++];
    });

    var choiceContainer = $("#choices");
    $.each(datasets, function (key, val) {
        choiceContainer.append('<li><a href="#" rel="'+key+'" class="active change-chart"><span style="background-color: '+val.color+'"><span>&nbsp;</span></span>' + val.label + '</a></li>');
    });

    choiceContainer.find("input").click(plotAccordingToChoices);

    function plotAccordingToChoices() {

        var data = [];

        choiceContainer.find("a.active").each(function () {
            var key = $(this).attr("rel");
            if (key && datasets[key]) {
                data.push(datasets[key]);
            }
        });

        if (data.length > 0) {
            console.log(data);
            $.plot("#chart_div", data, {
                legend: {
                    show: false
                },
                yaxis: {
                    label: 'Hours',
                    min: 0,
                    tickSize: 60,
                    tickFormatter: function (v) {
                        var h = Math.floor(v / 60);
                        return h + ' Hrs';
                    }
                },
                xaxis: {
                    tickSize: 24 * 60 * 60,
                    tickFormatter: function (v) {
                        var d = new Date();
                        d.setTime(v * 1000);
                        d.setDate(d.getDate() + 1);
                        return months[d.getMonth()] + '<br/>' + d.getDate();
                    }
                },
                series: {
                    lines: {
                        show: true,
                        lineWidth: 4
                    },
                    points: {
                        show: true,
                        radius: 6
                    }
                }
            });
        }
    }

    plotAccordingToChoices();

    $('.change-chart').on('click', function(){
        $(this).toggleClass('active');
        plotAccordingToChoices();
        return false;
    });

    function plotChart1(data) {
        $.plot("#chart_1", data, {
            legend: {
                show: false
            },
            yaxis: {
                label: 'Hours',
                min: 0,
                tickSize: 60,
                tickFormatter: function (v) {
                    var h = Math.floor(v / 60);
                    return h;
                }
            },
            xaxis: {
                tickSize: 24 * 60 * 60,
                tickFormatter: function (v) {
                    var d = new Date();
                    d.setTime(v * 1000);
                    d.setDate(d.getDate() + 1);
                    return d.getDate();
                }
            },
            series: {
                lines: {
                    show: true,
                    lineWidth: 3,
                    borderColor: '#3a9c1c'
                },
                points: {
                    show: true,
                    radius: 5,
                    fill: true,
                    lineWidth: 0,
                    fillColor: '#3a9c1c'
                }
            },
            colors: ['#3a9c1c']
        });
    }

    function plotChart2(data) {
        $.plot("#chart_2", data, {
            legend: {
                show: false
            },
            yaxis: {
                label: 'mg',
                min: 0
            },
            xaxis: {
                tickSize: 24 * 60 * 60,
                tickFormatter: function (v) {
                    var d = new Date();
                    d.setTime(v * 1000);
                    d.setDate(d.getDate() + 1);
                    return d.getDate();
                }
            },
            series: {
                bars: {
                    show: true,
                    align: 'center',
                    barWidth: 60*60*20,
                    fill: true,
                    lineWidth: 0,
                    fillColor:  "#2c94d6"
                }
            }
        });
    }

    function plot_1(dir) {
        var url = $('#plot_1').attr('data-url');
        var month = $('#plot_1').data('month');
        if (month==undefined) month = 0;
        month -= eval(dir);
        $.getJSON(url,{months: month},function(res){
            $('#plot_1_title').html(res.title);
            $('#plot_1').data('month', res.month);
            plotChart1([res.data1]);
            plotChart2([res.data2]);
        });
    }

    $('.plot_1_link').click(function(){
        plot_1($(this).attr('data-dir'));
        return false;
    });

    plot_1(0);

});