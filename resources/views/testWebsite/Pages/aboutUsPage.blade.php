@extends('testWebsite.layouts.master')

@section('title') من نحن @endsection

@section('style')
    <style>
        .mapouter {
            position: relative;
            text-align: right;
        }

        .gmap_canvas {
            overflow: hidden;
            background: none !important;

        }
    </style>

@endsection

@section('pageMenu')
    @include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
    <div class="myOrders">
        <div class="container">
            <div class="head-div text-center">
                <h1>من نحن</h1>
            </div>
            <section class="goal mt-0  ">
                <div class="container">
                    <div class="row align-items-center mt-5">
                        <div class="col-md-6">
                            <div class="goal-text wow fadeInRight" data-wow-duration="2s">
                                <h3>الوساطة العقارية</h3>
                                <p>
                                    من أوائل الشركات التي أوجدت حلول لكافة شرائح المجتمع وشرفنا بخدمة آلاف العملاء
                                    بمسيرة ١٥ عامًا حظينا فيها على رضاء عملائنا وحصولهم على منزل أحلامهم .
                                </p>
                                <p>
                                    بدأت شركة الوساطة عام 2006 كأول شركة متخصصة في تقديم الخدمات الاستشارية قرابة الخمسة
                                    عشرة عامًا حققت الشركة خلالها إنجازات ونجاحات جعلتها من الشركات الرائدة في هذا
                                    المجال واستحوذت على شريحة كبيرة من السوق؛ كان ولا زال الدافع فيها هو تقديم الخدمة
                                    الأفضل لعملائها من خلال فريق عمل على قدر كبير من الكفاءة والخبرة.
                                </p>
                                <br>
                                <h4 style="text-align: center;">أوقات العمل :</h4>
                                <p style="text-align: center;"> الأحد - الخميس <br> 9 صباحا - 6 مساء</p>
                                <br>
                            </div>
                            <div class="goal-text wow fadeInRight" data-wow-duration="2s">
                                <h3>منتجاتنا</h3>
                                <p>
                                    - شراء العقار: برنامج لشراء العقارات (المكتملة، غير المكتملة) في جميع أنحاء المملكة وبالتوافق مع الشريعة الإسلامية .
                                </p>
                                <p>
                                    - الرهن العقاري: برنامج للحصول على سيولة  بضمان العقار .
                                </p>
                                <p>
                                    - تمويل البناء: برنامج للحصول على سيولة  لبناء أرض أو استكمال بناء.
                                </p>
                                <br>
                            </div>
                            <div class="goal-text wow fadeInRight" data-wow-duration="2s">
                                <h3>الأهداف</h3>
                                <p>
                                    - ابتكار مزيد من حلول التمويل العقاري لتمكين الأسر السعودية من تملك مسكن مناسب
                                    لاحتياجاتهم وتعزيز قدراتهم المادية من خلال دراسة برامج التمويل المتاحة وتوفير الحل
                                    الأمثل لهم.
                                </p>
                                <p>
                                    - عقد مزيد من الشراكات مع بنوك وجهات تمويلية كبيرة وعريقة في مجال التمويل العقاري
                                    لتبادل الخدمات والخبرات للوصول للخدمة التي يطمح لها العميل.
                                </p>

                            </div>

                            <div class="goal-text wow fadeInRight" data-wow-duration="2s">
                                <h3>الرؤية</h3>
                                <p>
                                    أن نكون الوجهة الأولى لراغبي التمويل العقاري في المملكة العربية السعودية
                                </p>
                                <br>
                            </div>
                            <div class="goal-text wow fadeInRight" data-wow-duration="2s">
                                <h3>الرسالة</h3>
                                <p>
                                    تقديم خدمة مميزة لعملائنا في المملكة.
                                </p>
                                <br>
                            </div>


                        </div>
                        <div class="col-md-6">

                            <div class="mapouter">
                                <div class="gmap_canvas">
                                    <iframe width="606" height="500" id="gmap_canvas"
                                            src="https://maps.google.com/maps?q=%D8%B4%D8%B1%D9%83%D8%A9%20%D8%A7%D9%84%D9%88%D8%B3%D8%A7%D8%B7%D8%A9%20%D8%A7%D9%84%D8%B9%D9%82%D8%A7%D8%B1%D9%8A%D8%A9&t=&z=17&ie=UTF8&iwloc=&output=embed"
                                            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                    <br>
                                    <style>
                                        .mapouter {
                                            position: relative;
                                            text-align: right;
                                            height: 500px;
                                            width: 606px;
                                        }
                                    </style>
                                    <style>
                                        .gmap_canvas {
                                            overflow: hidden;
                                            background: none !important;
                                            height: 500px;
                                            width: 606px;
                                        }
                                    </style>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
