<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">

                        @if ($manager_role == 0)
                            <div id="details-chart" style="width:100%; height:500px;"></div>
                        @endif
                            <div id="servay-chart" style="width:100%; height:500px;"></div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var name = [];

                                    var move_present = [];
                                    var updateTask_present = [];
                                    var completeTask_present = [];
                                    var updateReq_present = [];
                                    var servayResult = [];
                                    var finalResult = [];



                                    @foreach($data_for_chart as $data)

                                    name.push("{{$data['name']}}");

                                    move_present.push(parseInt("{{$data['move_present']}}"));
                                    updateTask_present.push(parseInt("{{$data['updateTask_present']}}"));
                                    completeTask_present.push(parseInt("{{$data['completeTask_present']}}"));
                                    updateReq_present.push(parseInt("{{$data['updateReq_present']}}"));
                                    servayResult.push(parseInt("{{$data['servayResult']}}"));

                                    finalResult.push(parseInt("{{$data['finalResult']}}"));


                                    @endforeach


                                    @if ($manager_role == 0)

                                    Highcharts.chart('details-chart', {
                                        chart: {
                                            type: 'bar'
                                        },
                                        title: {
                                            text: 'تقييم أداء الاستشاري'
                                        },
                                        xAxis: {
                                            categories: name
                                        },
                                        yAxis: {
                                            min: 0,
                                            title: {
                                                text: 'النسبة المئوية'
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
                                                name: 'تقييم الجودة',
                                                color: '#2eb8b8',
                                                data: servayResult
                                            },
                                            {
                                                name: 'الطلبات المحولة',
                                                color: '#00cc66',
                                                data: move_present
                                            },
                                            {
                                                name: 'التجاوب مع الجودة',
                                                color: '#ff6666',
                                                data: updateTask_present
                                            },
                                            {
                                                name: 'التذاكر المكتملة',
                                                color: '#ffff66',
                                                data: completeTask_present
                                            },
                                            {
                                                name: 'التحديث على الطلب',
                                                color: '#cc6699',
                                                data: updateReq_present
                                            },


                                        ]
                                    })
                                    @endif


                                    Highcharts.chart('servay-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'النتيجة النهائية'
                                        },
                                        xAxis: {
                                            categories: name
                                        },
                                        yAxis: {
                                            min: 0,
                                            title: {
                                                text: 'النسبة المئوية'
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
                                                name: 'النتيجة النهائية',
                                                color: '#003366',
                                                data: finalResult
                                            },



                                        ]
                                    })



                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
