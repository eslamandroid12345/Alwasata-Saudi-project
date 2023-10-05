<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">
                            <div id="basket-chart" style="width:100%; height:400px;"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var name = [];
                                    var complete = [];
                                    var archived = [];
                                    var following = [];
                                    var star = [];
                                    var received = [];

                                    @foreach($data_for_basket_chart as $data)

                                    name.push("{{$data['name']}}");

                                    @if(in_array('allBaskets', $baskets) || in_array('complete', $baskets))
                                    complete.push(parseInt("{{$data['complete']}}"));
                                    @endif

                                    
                                    @if(in_array('allBaskets', $baskets) || in_array('archived', $baskets))
                                    archived.push(parseInt("{{$data['archived']}}"));
                                    @endif
                                  
                                    @if(in_array('allBaskets', $baskets) || in_array('following', $baskets))
                                    following.push(parseInt("{{$data['following']}}"));
                                    @endif

                                    @if(in_array('allBaskets', $baskets) || in_array('star', $baskets))
                                    star.push(parseInt("{{$data['star']}}"));
                                    @endif
                                    
                                    @if(in_array('allBaskets', $baskets) || in_array('received', $baskets))
                                    received.push(parseInt("{{$data['received']}}"));
                                    @endif
                                   
                                    @endforeach
                                    Highcharts.chart('basket-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'سلال الطلبات'
                                        },
                                        xAxis: {
                                            categories: name
                                        },
                                        yAxis: {
                                            min: 0,
                                            title: {
                                                text: 'عدد الطلبات'
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
                                            @if(in_array('allBaskets', $baskets) || in_array('complete', $baskets)) {
                                                name: 'مكتملة',
                                                color: 'rgb(1, 111, 0)',
                                                data: complete
                                            },
                                            @endif
                                           
                                            @if(in_array('allBaskets', $baskets) || in_array('archived', $baskets)) {
                                                name: 'مؤرشفة',
                                                color: 'rgb(249, 150, 61)',
                                                data: archived

                                            },
                                            @endif
                                            
                                            @if(in_array('allBaskets', $baskets) || in_array('following', $baskets)) {
                                                name: 'متابعة',
                                                color: 'rgb(249, 255, 61)',
                                                data: following

                                            },
                                            @endif

                                            @if(in_array('allBaskets', $baskets) || in_array('star', $baskets)) {
                                                name: 'مميزة',
                                                color: 'rgb(254, 0, 163)',
                                                data: star

                                            },
                                            @endif

                                            @if(in_array('allBaskets', $baskets) || in_array('received', $baskets)) {
                                                name: 'مستلمة',
                                                color: 'rgb(10, 255, 0)',
                                                data: received
                                            },
                                            @endif
                                            
                                        ]
                                    })

                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>