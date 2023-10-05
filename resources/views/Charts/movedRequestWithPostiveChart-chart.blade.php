<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">
                            <div id="movedWithPostiveClass-chart" style="width:100%; height:400px;"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {

                                    var name = [];
                                    var total = [];

                                    @foreach($data_for_chart as $data)

                                    name.push("{{$data['name']}}");
                                    total.push(parseInt("{{$data['total']}}"));
                                    
                                    @endforeach


                                    Highcharts.chart('movedWithPostiveClass-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'طلبات (مرفوع ، مكتمل)'
                                        },
                                        xAxis: {
                                            categories: name
                                        },
                                        yAxis: {
                                            min: 0,
                                            title: {
                                                text: 'المجموع'
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
                                                name: 'المجموع',
                                                color: '#ff4d4d',
                                                data: total
                                            },
                                          
                                          
                                            
                                        ]
                                    })

                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
