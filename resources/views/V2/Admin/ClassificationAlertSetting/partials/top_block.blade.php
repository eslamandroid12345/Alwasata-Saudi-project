@component("V2.app.partials.component.model.top_block")
    {!! Form::formGroup(setting()->all(),[
        "route"     => ['V2.Admin.Setting.updateSetting'],
        "method"    => "PUT",
    ])!!}
    <div class="container">
        <div class="row">
            {!! Form::radioSwitchGroup( "schedule_unable_to_communicate", __("attributes.schedule_unable_to_communicate")) !!}
        </div>
        <div class="row">
            {!! Form::radioSwitchGroup( "postponed_communication", __("attributes.postponed_communication")) !!}
        </div>
        <div class="row">
            {!! Form::radioSwitchGroup( "schedule_not_answer_to_unable", __("attributes.schedule_not_answer_to_unable")) !!}
            {!! Form::numberGroup("not_answer_to_unable_days", __("validation.attributes.not_answer_to_unable_days"))  !!}
            <div class="col-12">
                {!! Form::updateButton() !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    {{--List of Model--}}
    {{--    {!! Form::indexButtons() !!}--}}
    {{--Create New Entry--}}
    {{--    {!! Form::createButtons() !!}--}}

    <div class="container">
        {{-- <div class="row my-4">
           <div class="col-md-6">

            <input type="radio" class="bg-danger" name="classification_idd" id="" value="0">
            <label for="">الكل</label>


            <input type="radio" name="classification_idd" id="" value="1">
            <label for="">أجل التواصل</label>


            <input type="radio" name="classification_idd" id="" value="2">
            <label for="">تصنيف اخر</label>

           </div>
        </div> --}}

        <nav class="mt-3">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              {{-- <button class="nav-link buttons-tabs-filter mx-1" value="0"  data-bs-toggle="tab"  type="button" role="tab" >الكل</button> --}}
              <button class="nav-link buttons-tabs-filter mx-1 active-filter" value="1"  data-bs-toggle="tab"  type="button" role="tab" >أجل التواصل</button>
              <button class="nav-link buttons-tabs-filter mx-1" value="2"  data-bs-toggle="tab"  type="button" role="tab" >تصنيف أخر</button>
            </div>
        </nav>
    </div>
@endcomponent

@section('scripts')
    <script>
    //    $('input:radio').on('click', function(e) {
    //        console.log($(this).val());
    //        LaravelDataTables.dataTableBuilder.ajax.reload()
    //     });

        $(document).on('click','.buttons-tabs-filter',function(){
            $('.buttons-tabs-filter').each(function(){
                $(this).removeClass('active-filter')
            })

            $(this).addClass('active-filter')
            LaravelDataTables.dataTableBuilder.ajax.reload()
        })
    </script>
@endsection


@section('css_style')
<style>
   .active-filter{
        background-color: #0f5b94;
        color: #fff;
    }
</style>
@endsection
