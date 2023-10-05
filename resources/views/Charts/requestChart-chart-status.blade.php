<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">
                            <div id="status-chart" style="width:100%; height:600px;"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var name = [];

                                    var newStatus = [];
                                    var openStatus = [];
                                    var archiveStatus = [];

                                    var watingSMStatus = [];
                                    var rejectedSMStatus = [];
                                   // var archiveSMStatus = [];

                                    var watingFMStatus = [];
                                    var rejectedFMStatus = [];
                                   // var archiveFMStatus = [];

                                    var watingMMStatus = [];
                                    var rejectedMMStatus = [];
                                  //  var archiveMMStatus = [];

                                    var watingGMStatus = [];
                                    var rejectedGMStatus = [];
                                  //  var archiveGMStatus = [];

                                    var canceledStatus = [];
                                    var completedStatus = [];

                                    var fundingReportStatus = [];
                                    var mortgageReportStatus = [];


                                    @foreach($data_for_status_chart as $data)

                                    name.push("{{$data['name']}}");

                                    @if(in_array('allStatus', $statuses) || in_array('newStatus', $statuses))
                                    newStatus.push(parseInt("{{$data['newStatus']}}"));
                                    @endif
                                    
                                    @if(in_array('allStatus', $statuses) || in_array('openStatus', $statuses))
                                    openStatus.push(parseInt("{{$data['openStatus']}}"));
                                    @endif
                                    @if(in_array('allStatus', $statuses) || in_array('archiveStatus', $statuses))
                                    archiveStatus.push(parseInt("{{$data['archiveStatus']}}"));
                                    @endif

                                    @if(in_array('allStatus', $statuses) || in_array('watingSMStatus', $statuses))
                                    watingSMStatus.push(parseInt("{{$data['watingSMStatus']}}"));
                                    @endif
                                    @if(in_array('allStatus', $statuses) || in_array('rejectedSMStatus', $statuses))
                                    rejectedSMStatus.push(parseInt("{{$data['rejectedSMStatus']}}"));
                                    @endif

                                   {{--
                                    @if(in_array('allStatus', $statuses) || in_array('archiveSMStatus', $statuses))
                                    archiveSMStatus.push(parseInt("{{$data['archiveSMStatus']}}"));
                                    @endif
                                    --}}
                                    

                                    @if(in_array('allStatus', $statuses) || in_array('watingFMStatus', $statuses))
                                    watingFMStatus.push(parseInt("{{$data['watingFMStatus']}}"));
                                    @endif
                                    @if(in_array('allStatus', $statuses) || in_array('rejectedFMStatus', $statuses))
                                    rejectedFMStatus.push(parseInt("{{$data['rejectedFMStatus']}}"));
                                    @endif
                                    {{--
                                    @if(in_array('allStatus', $statuses) || in_array('archiveFMStatus', $statuses))
                                    archiveFMStatus.push(parseInt("{{$data['archiveFMStatus']}}"));
                                    @endif
                                    --}}

                                    @if(in_array('allStatus', $statuses) || in_array('watingMMStatus', $statuses))
                                    watingMMStatus.push(parseInt("{{$data['watingMMStatus']}}"));
                                    @endif
                                    @if(in_array('allStatus', $statuses) || in_array('rejectedMMStatus', $statuses))
                                    rejectedMMStatus.push(parseInt("{{$data['rejectedMMStatus']}}"));
                                    @endif
                                    {{--
                                    @if(in_array('allStatus', $statuses) || in_array('archiveMMStatus', $statuses))
                                    archiveMMStatus.push(parseInt("{{$data['archiveMMStatus']}}"));
                                    @endif
                                    --}}

                                    @if(in_array('allStatus', $statuses) || in_array('watingGMStatus', $statuses))
                                    watingGMStatus.push(parseInt("{{$data['watingGMStatus']}}"));
                                    @endif
                                    @if(in_array('allStatus', $statuses) || in_array('rejectedGMStatus', $statuses))
                                    rejectedGMStatus.push(parseInt("{{$data['rejectedGMStatus']}}"));
                                    @endif
                                    {{--
                                    @if(in_array('allStatus', $statuses) || in_array('archiveGMStatus', $statuses))
                                    archiveGMStatus.push(parseInt("{{$data['archiveGMStatus']}}"));
                                    @endif
                                    --}}

                                    @if(in_array('allStatus', $statuses) || in_array('canceledStatus', $statuses))
                                    canceledStatus.push(parseInt("{{$data['canceledStatus']}}"));
                                    @endif
                                    @if(in_array('allStatus', $statuses) || in_array('completedStatus', $statuses))
                                    completedStatus.push(parseInt("{{$data['completedStatus']}}"));
                                    @endif

                                    @if(in_array('allStatus', $statuses) || in_array('fundingReportStatus', $statuses))
                                    fundingReportStatus.push(parseInt("{{$data['fundingReportStatus']}}"));
                                    @endif
                                    @if(in_array('allStatus', $statuses) || in_array('mortgageReportStatus', $statuses))
                                    mortgageReportStatus.push(parseInt("{{$data['mortgageReportStatus']}}"));
                                    @endif
                                    
                                    @endforeach
                                    Highcharts.chart('status-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'حالات الطلب'
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
                                            @if(in_array('allStatus', $statuses) || in_array('newStatus', $statuses)) {
                                                name: 'جديد',
                                                color: 'rgb(149, 228, 126)',
                                                data: newStatus
                                            },
                                            @endif
                                            @if(in_array('allStatus', $statuses) || in_array('openStatus', $statuses)) {
                                                name: 'مفتوح',
                                                color: 'rgb(149, 214, 207)',
                                                data: openStatus
                                            },
                                            @endif
                                            @if(in_array('allStatus', $statuses) || in_array('archiveStatus', $statuses)) {
                                                name: 'مؤرشف عند استشاري المبيعات',
                                                color: '#d1e0e0',
                                                data: archiveStatus
                                            },
                                            @endif

                                            @if(in_array('allStatus', $statuses) || in_array('watingSMStatus', $statuses)) {
                                                name: 'بإنتظار موافقة مدير المبيعات',
                                                color: '#00ace6',
                                                data: watingSMStatus
                                            },
                                            @endif
                                            @if(in_array('allStatus', $statuses) || in_array('rejectedSMStatus', $statuses)) {
                                                name: 'رفض من قبل مدير المبيعات',
                                                color: '#ffc2b3',
                                                data: rejectedSMStatus
                                            },
                                            @endif
                                            /*
                                            @if(in_array('allStatus', $statuses) || in_array('archiveSMStatus', $statuses)) {
                                                name: 'مؤرشف عند مدير المبيعات',
                                                color: '#a4c1c1',
                                                data: archiveSMStatus
                                            },
                                            */
                                            @endif

                                            @if(in_array('allStatus', $statuses) || in_array('watingFMStatus', $statuses)) {
                                                name: 'بإنتظار موافقة مدير التمويل',
                                                color: '#0086b3',
                                                data: watingFMStatus
                                            },
                                            @endif
                                            @if(in_array('allStatus', $statuses) || in_array('rejectedFMStatus', $statuses)) {
                                                name: 'رفض من قبل مدير التمويل',
                                                color: '#ff704d',
                                                data: rejectedFMStatus
                                            },
                                            @endif
                                            /*
                                            @if(in_array('allStatus', $statuses) || in_array('archiveFMStatus', $statuses)) {
                                                name: 'مؤرشف عند مدير التمويل',
                                                color: '#76a2a2',
                                                data: archiveFMStatus
                                            },
                                            @endif
                                            */

                                            @if(in_array('allStatus', $statuses) || in_array('watingMMStatus', $statuses)) {
                                                name: 'بإنتظار موافقة مدير الرهن',
                                                color: '#007399',
                                                data: watingMMStatus
                                            },
                                            @endif
                                            @if(in_array('allStatus', $statuses) || in_array('rejectedMMStatus', $statuses)) {
                                                name: 'رفض من قبل مدير الرهن',
                                                color: '#ff471a',
                                                data: rejectedMMStatus
                                            },
                                            @endif
                                            /*
                                            @if(in_array('allStatus', $statuses) || in_array('archiveMMStatus', $statuses)) {
                                                name: 'مؤرشف عند مدير الرهن',
                                                color: '#537979',
                                                data: archiveMMStatus
                                            },
                                            @endif
                                            */

                                            @if(in_array('allStatus', $statuses) || in_array('watingGMStatus', $statuses)) {
                                                name: 'بإنتظار موافقة المدير العام',
                                                color: '#004d66',
                                                data: watingGMStatus
                                            },
                                            @endif
                                            @if(in_array('allStatus', $statuses) || in_array('rejectedGMStatus', $statuses)) {
                                                name: 'رفض من قبل المدير العام',
                                                color: '#cc2900',
                                                data: rejectedGMStatus
                                            },
                                            @endif
                                            /*
                                            @if(in_array('allStatus', $statuses) || in_array('archiveGMStatus', $statuses)) {
                                                name: 'مؤرشف عند المدير العام',
                                                color: '#293d3d',
                                                data: archiveGMStatus
                                            },
                                            @endif
                                            */

                                            @if(in_array('allStatus', $statuses) || in_array('canceledStatus', $statuses)) {
                                                name: 'ملغي',
                                                color: '#151e1e',
                                                data: canceledStatus
                                            },
                                            @endif
                                            @if(in_array('allStatus', $statuses) || in_array('completedStatus', $statuses)) {
                                                name: 'مكتمل',
                                                color: '#009933',
                                                data: completedStatus
                                            },
                                            @endif

                                            @if(in_array('allStatus', $statuses) || in_array('fundingReportStatus', $statuses)) {
                                                name: 'في تقرير التمويل',
                                                color: '#ffd11a',
                                                data: fundingReportStatus
                                            },
                                            @endif
                                            @if(in_array('allStatus', $statuses) || in_array('mortgageReportStatus', $statuses)) {
                                                name: 'في تقرير الرهن',
                                                color: '#993366',
                                                data: mortgageReportStatus
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