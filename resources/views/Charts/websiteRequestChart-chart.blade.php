<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">
                            <div id="website-chart" style="width:100%; height:400px;"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var region = [];
                                    var count = [];
                                
                                  

                                    @foreach($data_for_chart as $data)

                                    region.push("{{$data['region']}}");

                                    count.push(parseInt("{{$data['count']}}"));
                                    
                                
                                    @endforeach
                                    Highcharts.chart('website-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'طلبات الموقع الإلكتروني'
                                        },
                                        xAxis: {
                                            categories: region
                                        },
                                        yAxis: {
                                            min: 0,
                                            title: {
                                                text: 'العدد'
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
                                                name: 'العدد',
                                                color: '#191966',
                                                data: count
                                            },
                                          
                                          
                                            
                                        ]
                                    })

                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>