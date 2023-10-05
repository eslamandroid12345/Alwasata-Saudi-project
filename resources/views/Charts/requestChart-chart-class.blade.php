<div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default">
                        <div class="au-card-inner panel-body">
                            <div id="class-chart" style="width:100%; height:500px;"></div>
                            <script>

function getRandomColor() { // to generate randomly colors ^^
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

                                 document.addEventListener('DOMContentLoaded', function() {
                                    var name = [];

                                    @foreach($agent_class as $class)

                                    var afnan{{$class->id}} = [];

                                    @endforeach

                                    var colors = []


                                    @foreach($data_for_class_chart as $data)

                                        name.push("{{$data['name']}}");

                                        @foreach($agent_class as $class)
                                            @if(in_array('allClass', $classes) || in_array('class-'.$class->id, $classes))
                                            afnan{{$class->id}}.push(parseInt("{{ $data['class-'.$class->id] }}"));
                                            @endif
                                        @endforeach


                                    @endforeach

                                    Highcharts.chart('class-chart', {
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'تصنيف الطلب'
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

                                            @foreach($agent_class as $class)

                                            @if(in_array('allClass', $classes) || in_array('class-'.$class->id, $classes)) {
                                                name: "{{$class->value}}",
                                                color: getRandomColor(),
                                                data: afnan{{$class->id}}
                                            },
                                            @endif



                                            @endforeach

                                        ]
                                    })
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
