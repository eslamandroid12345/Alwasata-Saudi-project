<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">

                            <div id="servay-chart" style="width:100%; height:500px;"></div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var name = [];

                                    var q1 = [];
                                    var q2 = [];
                                    var q3 = [];
                                    var q4 = [];

                                    var result = [];
                           
                                  

                                    @foreach($data_for_chart as $data)

                                    name.push("{{$data['name']}}");

                                    q1.push(parseInt("{{$data['q1']}}"));
                                    q2.push(parseInt("{{$data['q2']}}"));
                                    q3.push(parseInt("{{$data['q3']}}"));
                                    q4.push(parseInt("{{$data['q4']}}"));

                                    result.push(parseInt("{{$data['result']}}"));
                                  
                                   
                                    @endforeach

                                    Highcharts.chart('servay-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'نتائج تقييم الجودة'
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