<div class="app-section py-sm-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="appText">
                    <h1 class="wow fadeInUp" data-wow-delay="0.2s">حمل الآن تطبيق الوساطة العقارية وتمتع بالخدمات التالية</h1>

                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <div class="download-pos d-flex align-items-center  mt-4 wow fadeInRight" data-wow-delay="0.3s">
                                <a href="https://play.google.com/store/apps/details?id=com.wasata.wasata_user&hl=en&gl=US" class="mr-3 social-buttons" target="_blank" id="android_button">
                                    {{--                            <img src="{{ asset('newWebsiteStyle/images/apple.svg') }}" alt="" class="img-fluid">--}}
                                    <img width="190" src="{{ asset('assest/images/brands/google-play.svg') }}" alt="" class="img-fluid">
                                </a>
                                <a href="https://apps.apple.com/sa/app/%D8%A7%D9%84%D9%88%D8%B3%D8%A7%D8%B7%D8%A9-%D8%A7%D9%84%D8%B9%D9%82%D8%A7%D8%B1%D9%8A%D8%A9/id1588240476#?platform=iphone" class="social-buttons" target="_blank" id="apple_button">
                                    <img width="200" src="{{ asset('assest/images/brands/app-store.png') }}" alt="" class="img-fluid">
                                    {{--                            <img src="{{ asset('newWebsiteStyle/images/google.svg') }}" alt="" class="img-fluid">--}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <p class="text-caption mt-3 wow fadeInUp" data-wow-duration="1.2s">بإمكانك تحميل تطبيقنا من خلال المتاجر الالكترونية(أبل ستور ، جوجل بلاي) </p>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <p class="wow fadeInDown" data-wow-delay="0.2s">
                                <i class="fas fa-check-circle pr-2"></i>
                                حاسبة مجانية لمعرفة أفضل تمويل مناسب لك
                            </p>
                        </li>

                        <li class="mb-3">
                            <p class="wow fadeInDown" data-wow-delay="0.3s">
                                <i class="fas fa-check-circle pr-2"></i>
                               تواصل لحظة بلحظة مع مستشارك
                            </p>
                        </li>

                        <li class="mb-3">
                            <p class="wow fadeInDown" data-wow-delay="0.4s">
                                <i class="fas fa-check-circle pr-2"></i>
                                متابعة حالة طلبك أول بأول والحصول على اشعارات بأي تحديثات على الطلب
                            </p>
                        </li>
                        <li class="mb-3">
                            <p class="wow fadeInDown" data-wow-delay="0.5s">
                                <i class="fas fa-check-circle pr-2"></i>
                               تصفح عروض عقارية مميزة ومنها الحصري لنا وإيجاد العقار المناسب بمساعدة تقنيات الذكاء الاصطناعي
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="img-app text-center wow fadeInUp py-5" data-wow-delay="0.3s">
                    <img src="{{ asset('newWebsiteStyle/images/mobile-app.svg') }}" class="img-fluid" alt="">
                </div>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <div class="d-flex download-pos align-items-center  mt-4 wow fadeInRight" data-wow-delay="0.3s">
                    <a href="https://play.google.com/store/apps/details?id=com.wasata.wasata_user&hl=en&gl=US" class="mr-3 social-buttons" target="_blank" id="android_button">
                        {{--                            <img src="{{ asset('newWebsiteStyle/images/apple.svg') }}" alt="" class="img-fluid">--}}
                        <img width="190" src="{{ asset('assest/images/brands/google-play.svg') }}" alt="" class="img-fluid">
                    </a>
                    <a href="https://apps.apple.com/sa/app/%D8%A7%D9%84%D9%88%D8%B3%D8%A7%D8%B7%D8%A9-%D8%A7%D9%84%D8%B9%D9%82%D8%A7%D8%B1%D9%8A%D8%A9/id1588240476#?platform=iphone" class="social-buttons" target="_blank" id="apple_button">
                        <img width="200" src="{{ asset('assest/images/brands/app-store.png') }}" alt="" class="img-fluid">
                        {{--                            <img src="{{ asset('newWebsiteStyle/images/google.svg') }}" alt="" class="img-fluid">--}}
                    </a>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('newWebsiteStyle/js/jQuery.js') }}"></script>
<script>
$(document).on('click', '.social-buttons', function(e) {

var button_id = $(this).attr('id');

$.get("{{ route('update_soical_clicktimes') }}", {button_id:button_id}, function(data) {
}).fail(function(data) {

});


});
</script>
