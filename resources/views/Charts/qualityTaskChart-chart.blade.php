<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">

                            <div id="task-chart" style="width:100%; height:400px;"></div>

                            <br>
                            <hr>
                            <br>

                            <div id="avreage-chart" style="width:100%; height:400px;"></div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var name = [];

                                    var notcompletedTask = [];
                                    var completedTask = [];


                                    var presentComplete = [];
                                    var presentAverage = [];


                                    @foreach($data_for_chart as $data)

                                    name.push("{{$data['name']}}");

                                    notcompletedTask.push(parseInt("{{$data['notcompletedTask']}}"));
                                    completedTask.push(parseInt("{{$data['completedTask']}}"));

                                    presentComplete.push(parseInt("{{$data['presentComplete']}}"));
                                    presentAverage.push(parseInt("{{$data['presentAverage']}}"));

                                    @endforeach

                                    Highcharts.chart('task-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'مهام الجودة'
                                        },
                                        xAxis: {
                                            categories: name
                                        },
                                        yAxis: {
                                            min: 0,
                                            title: {
                                                text: 'التذاكر'
                                            },
                                            stackLabels: {
                                                enabled: true,
                                                style: {
                                                    fontWeight: 'bold',
                                                    color: ( // theme
                                                        Highcharts.defaultOptions.title.style &&
                                                        Highcharts.defaultOptions.title.style.color
                                                    ) || 'gray'
                                                }
                                            }
                                        },
                                        legend: {
                                            align: 'right',
                                            x: -30,
                                            verticalAlign: 'top',
                                            y: 25,
                                            floating: true,
                                            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
                                            borderColor: '#CCC',
                                            borderWidth: 1,
                                            shadow: false
                                        },
                                        tooltip: {
                                            headerFormat: '<b>{point.x}</b><br/>',
                                            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                                        },
                                        plotOptions: {
                                            column: {
                                                stacking: 'normal',
                                                dataLabels: {
                                                    enabled: true
                                                }
                                            }
                                        },
                                        series: [
                                            {
                                                name: 'التذاكر الغير مكتملة',
                                                color: '#ff4d4d',
                                                data: notcompletedTask
                                            },
                                            {
                                                name: ' التذاكر المكتملة',
                                                color: '#99ff33',
                                                data: completedTask
                                            },


                                        ]
                                    })


                                    Highcharts.chart('avreage-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'النسب المئوية'
                                        },
                                        xAxis: {
                                            categories: name
                                        },
                                        yAxis: {
                                            min: 0,
                                            title: {
                                                text: 'نسبة مئوية'
                                            },
                                            stackLabels: {
                                                enabled: true,
                                                style: {
                                                    fontWeight: 'bold',
                                                    color: ( // theme
                                                        Highcharts.defaultOptions.title.style &&
                                                        Highcharts.defaultOptions.title.style.color
                                                    ) || 'gray'
                                                }
                                            }
                                        },
                                        legend: {
                                            align: 'right',
                                            x: -30,
                                            verticalAlign: 'top',
                                            y: 25,
                                            floating: true,
                                            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
                                            borderColor: '#CCC',
                                            borderWidth: 1,
                                            shadow: false
                                        },
                                        tooltip: {
                                            headerFormat: '<b>{point.x}</b><br/>',
                                            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                                        },
                                        plotOptions: {
                                            column: {
                                                stacking: 'normal',
                                                dataLabels: {
                                                    enabled: true
                                                }
                                            }
                                        },
                                        series: [

                                           {
                                                name: 'سرعة الرد',
                                                color: '#33cccc',
                                                data: presentAverage

                                            },

                                            {
                                                name: 'التذاكر المكتملة',
                                                color: '#00ff99',
                                                data: presentComplete

                                            },



                                        ]
                                    })

                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
