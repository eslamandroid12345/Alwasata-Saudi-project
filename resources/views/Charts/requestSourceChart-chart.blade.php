<div class="row">
    <div class="col-lg-12">
        <div class="au-card m-b-30 panel panel-default">
            <div class="au-card-inner panel-body">
                <div id="source-chart" style="width:100%; height:600px;"></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var name = [];

                        var frind = [];
                        var telphone = [];
                        var missedCall = [];
                        var admin = [];

                        var webAskFunding = [];
                        var webAskCons = [];
                        var webCal = [];

                        var app_askcons = [];
                        var app_calc = [];

                        var collobrator = [];
                        var otared = [];
                        var tamweelk = [];
                        var callNotRecord = [];

                        var hasbah_net_completed = [];
                        var hasbah_net_notcompleted = [];

                       @foreach($data_for_chart as $data)

                       name.push("{{$data['name']}}");

                       frind.push(parseInt("{{$data['frind']}}"));
                       telphone.push(parseInt("{{$data['telphone']}}"));
                       missedCall.push(parseInt("{{$data['missedCall']}}"));
                       admin.push(parseInt("{{$data['admin']}}"));

                       webAskFunding.push(parseInt("{{$data['webAskFunding']}}"));
                       webAskCons.push(parseInt("{{$data['webAskCons']}}"));
                       webCal.push(parseInt("{{$data['webCal']}}"));

                       app_askcons.push(parseInt("{{$data['app_askcons']}}"));
                       app_calc.push(parseInt("{{$data['app_calc']}}"));

                       collobrator.push(parseInt("{{$data['collobrator']}}"));
                       otared.push(parseInt("{{$data['otared']}}"));
                       tamweelk.push(parseInt("{{$data['tamweelk']}}"));

                       callNotRecord.push(parseInt("{{$data['callNotRecord']}}"));

                       hasbah_net_completed.push(parseInt("{{$data['hasbah_net_completed']}}"));
                        hasbah_net_notcompleted.push(parseInt("{{$data['hasbah_net_notcompleted']}}"));


                        @endforeach
                        Highcharts.chart('source-chart', {
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
                                    name: 'صديق',
                                    color: '#ff6666',
                                    data: frind
                                },
                                {
                                    name: 'التلفون الثابت',
                                    color: '#884dff',
                                    data: telphone
                                },
                                {
                                    name: 'مكالمة فائتة',
                                    color: '#ff8533',
                                    data: missedCall
                                },
                                {
                                    name: 'مدير النظام',
                                    color: '#ace600',
                                    data: admin
                                },
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
                                    name: 'مكالمة لم تسجل',
                                    color: '#ffcc00',
                                    data: callNotRecord
                                },
                                {
                                    name: 'تطبيق - اطلب إستشارة',
                                    color: '#9966ff',
                                    data: app_askcons
                                },
                                {
                                    name: 'تطبيق - حاسبة التمويل',
                                    color: '#6666ff',
                                    data: app_calc
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
                                    name: 'متعاون',
                                    color: '#ac3939',
                                    data: collobrator
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
