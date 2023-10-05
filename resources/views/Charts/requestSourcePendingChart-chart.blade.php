<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">
                            <div id="pending-chart" style="width:100%; height:600px;"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var name = ['afnan','عطارد'];

                           

                                    var webAskFunding = [];
                                    var webAskCons = [];
                                    var webCal = [];

                                    var collobrator = [];
                                    var otared = [];
                                    var tamweelk = [];
                                   
                                    var hasbah_net_completed = [];
                                    var hasbah_net_notcompleted = [];

                                    @foreach($data_for_total as $data)

                    

                                    webAskFunding.push(parseInt("{{$data['webAskFunding']}}"));
                                    webAskCons.push(parseInt("{{$data['webAskCons']}}"));
                                    webCal.push(parseInt("{{$data['webCal']}}"));
                                    otared.push(parseInt("{{$data['otared']}}"));
                                    tamweelk.push(parseInt("{{$data['tamweelk']}}"));
                                    hasbah_net_completed.push(parseInt("{{$data['hasbah_net_completed']}}"));
                                    hasbah_net_notcompleted.push(parseInt("{{$data['hasbah_net_notcompleted']}}"));
                                    
                                    @endforeach
                                    Highcharts.chart('pending-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'مصادر المعاملات'
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
                                        
                                            {
                                                name: 'ويب - اطلب تمويل',
                                                color: '#66ccff',
                                                data: webAskFunding
                                            },
                                            {
                                                name: 'موقع الحاسبة العقارية - مكتمل',
                                                color: '#00b359',
                                                data: hasbah_net_completed
                                            },
                                            {
                                                name: 'موقع الحاسبة العقارية - غير مكتمل',
                                                color: '#4d0099',
                                                data: hasbah_net_notcompleted
                                            },
                                            {
                                                name: 'ويب - اطلب استشارة',
                                                color: '#3399ff',
                                                data: webAskCons
                                            },
                                            {
                                                name: 'ويب - الحاسبة العقارية',
                                                color: '#4747d1',
                                                data: webCal
                                            },
                                          
                                            {
                                                name: 'عطارد',
                                                color: '#001f4d',
                                                data: otared
                                            },
                                            {
                                                name: 'تمويلك',
                                                color: '#009973',
                                                data: tamweelk
                                            },
                                          
                                            
                                           
                                        ]
                                    })

                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>