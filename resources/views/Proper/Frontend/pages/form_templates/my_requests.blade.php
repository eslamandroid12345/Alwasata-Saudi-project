<div class="ask-conslutant requstform">
    <div class="requstform-inner">
        <p>{{ MyHelpers::guest_trans('Enter the order number in the box below to send the full details') }}.</p>
        <div class="clearfix"></div>

        <form dir="rtl">
            <p class="message-box alert hide"></p>

            @if ($id != '' &&  $id != null)
            <p class="dublicate-alert alert alert-danger">
           {{ MyHelpers::guest_trans('You already have a request')}}   #<span class="text-danger">{{$id}}</span>
            </p>

            @endif

            {{ csrf_field() }}

            <div class="form-group">
                <input type="text" placeholder="{{ MyHelpers::guest_trans('Order Number') }}" name="order_number" class="form-control">
            </div>
            <div class="form-group">
                <div class="sumitbtn">
                    <button id="sendBtn" type="button" class="srchbtn">{{ MyHelpers::guest_trans('Send') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(function(e) {

        $(document).on('keypress', '[name=order_number]', function(e) {
            if (e.which == 10 || e.which == 13) {
                e.preventDefault();
                $('#sendBtn').click();
            }
        });

        $(document).on('click', '#sendBtn', function(e) {
            e.preventDefault();
            var dublicate = $('.dublicate-alert');
            var loader = $('.message-box');
            var btn = $(this);

            dublicate.addClass('hide');

          
            $.ajax({
                type: 'GET',
                url: "{{ route('frontend.page.check_order_status') }}",
                data: $('.requstform form').serialize(),
                beforeSend: function() {
                    btn.attr('disabled', true);
                    loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> {{ MyHelpers::guest_trans('loading') }}").removeClass('hide alert-danger').addClass('alert-success');
                },
                error: function(jqXHR, exception) {
                    btn.attr('disabled', false);
                    loader.html("{{MyHelpers::guest_trans('The selected order number is invalid.') }}").removeClass('alert-success').addClass('alert-danger');
                },
                success: function(data) {
                    btn.attr('disabled', false);
                    if (data.status == 1) {
                        loader.html(data.msg).removeClass('alert-danger').addClass('alert-success');
                    } else {
                        loader.html(data.msg).removeClass('alert-success').addClass('alert-danger');
                    }

                }
            });
           
        })
    });
</script>