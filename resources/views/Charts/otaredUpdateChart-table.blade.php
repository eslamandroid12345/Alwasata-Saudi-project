<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>تحديث عطارد :</h4>
            </div>
            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                <div  id="dt-btns" class="tableAdminOption">
                </div>
            </div>
        </div>
    </div>
    <table id="" class="table table-bordred table-striped data-table">
        <thead>
        <tr style="text-align: center;">

            <th>استشاري <br> المبيعات</th>
            <th>عدد طلبات <br> عطارد</th>

            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</th>

            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</th>

            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'owner name') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'region') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'pursuit') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'real estate age') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</th>

            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'joint name') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'joint mobile') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'joint salary') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</th>




        </tr>
        </thead>
        <tbody style="text-align: center;">


        @foreach($data_for_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>
                <td>{{$data['otaredReqs']}}</td>

                <td>{{$data['funding_source']}}</td>
                <td>{{$data['fundDur']}}</td>
                <td>{{$data['fundPers']}}</td>
                <td>{{$data['fundPersPre']}}</td>
                <td>{{$data['fundReal']}}</td>
                <td>{{$data['fundRealPre']}}</td>
                <td>{{$data['fundDed']}}</td>
                <td>{{$data['fundMonth']}}</td>

                <td>{{$data['customerName']}}</td>
                <td>{{$data['birth_hijri']}}</td>
                <td>{{$data['work']}}</td>
                <td>{{$data['salary']}}</td>
                <td>{{$data['support']}}</td>
                <td>{{$data['obligations']}}</td>
                <td>{{$data['distress']}}</td>
                <td>{{$data['salary_source']}}</td>
                <td>{{$data['askaryWork']}}</td>
                <td>{{$data['madanyWork']}}</td>
                <td>{{$data['rank']}}</td>

                <td>{{$data['realName']}}</td>
                <td>{{$data['realMobile']}}</td>
                <td>{{$data['realCity']}}</td>
                <td>{{$data['realRegion']}}</td>
                <td>{{$data['realPursuit']}}</td>
                <td>{{$data['realAge']}}</td>
                <td>{{$data['realStatus']}}</td>
                <td>{{$data['realCost']}}</td>
                <td>{{$data['realType']}}</td>

                <td>{{$data['jointName']}}</td>
                <td>{{$data['jointMobile']}}</td>
                <td>{{$data['jointSalary']}}</td>
                <td>{{$data['jointBirth_higri']}}</td>
                <td>{{$data['jointWork']}}</td>
                <td>{{$data['jointJobTitle']}}</td>


            </tr>

        @endforeach

        </tbody>
    </table>
</div>
