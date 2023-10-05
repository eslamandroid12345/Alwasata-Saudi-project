<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">

                            <div id="update-chart" style="width:100%; height:400px;"></div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var name = [];
                                    var result = [];
                           
                                  

                                    @foreach($data_for_chart as $data)

                                    name.push("{{$data['name']}}");

                                    result.push(parseInt("{{$data['present']}}"));
                                  
                                   
                                    @endforeach

                                    Highcharts.chart('update-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'التحديث على الطلب'
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
                                                name: 'نسبة مئوية',
                                                color: '#00cc44',
                                                data: result
                                            },
                                           
                                            
                                        ]
                                    })



                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>