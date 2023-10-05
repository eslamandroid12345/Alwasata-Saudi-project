<div class="row">
    <div class="col-lg-12">
        <div class="au-card m-b-30 panel panel-default">
            <div class="au-card-inner panel-body">
                <div id="class-chart" style="width:100%; height:500px;"></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var categories = [];
                        let values = {};
                        let series = [];

                        @foreach($data_for_class_chart as $data)
                        categories.push("{{$data['name']}}");

                        @foreach($QualityClassificationsSelect as $value)
                        if (!values['{{$value['id']}}']) {
                            values['{{$value['id']}}'] = []
                        }
                        values['{{$value['id']}}'].push({{$data[$value['id']]}});
                        @endforeach

                        @endforeach

                        @foreach($QualityClassificationsSelect as $value)
                        series.push({
                            name: "{{$value['name']}}",
                            color: getRandomColor(),
                            data: values['{{$value['id']}}']
                        })
                        @endforeach

                        Highcharts.chart('class-chart', {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'تصنيف الطلب'
                            },
                            xAxis: {
                                categories
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
                                        color: Highcharts.defaultOptions.title.style?.color || 'gray'
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
                            series
                        })

                    });
                </script>
            </div>
        </div>
    </div>
</div>
