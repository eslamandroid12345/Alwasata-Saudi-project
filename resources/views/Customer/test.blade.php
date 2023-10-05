
<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ URL::to('website_style/frontend/images/favicon.ico') }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="HandheldFriendly" content="true" />


    <link rel="icon" type="image/x-icon" href="{{ URL::to('website_style/frontend/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/style.css') }}">


</head>

<body>

    <header class="single-head user_head">

        <div class="user-nav ">
            <div class="container">
                <div class="UserNav-cont d-flex  ">
                    <div class="userName">
                        <div class="dropdown">
                            <button class="user_btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                اسم العميل
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#"> <i class="fas fa-user mr-3"></i> بياناتي </a>
                                <a class="dropdown-item" href="#"> <i class="fas fa-power-off mr-3"></i>خروج</a>
                             </div>
                        </div>

                    </div>
                    <div class="userNote d-flex">
                        <div class="notifactions mr-5 not_bar notf_call">
                            <i class="fas fa-bell"></i>
                            <span class="note-msg">5</span>
                            <ul class="list-unstyled note_ul">
                                <li>
                                    <div class="single_Pop d-flex">
                                        <div class="popIcon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="popCont">
                                            لديك اشعار لديك اشعار
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="single_Pop d-flex">
                                        <div class="popIcon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="popCont">
                                            لديك اشعار لديك اشعار
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="single_Pop d-flex">
                                        <div class="popIcon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="popCont">
                                            لديك اشعار لديك اشعار
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="all-note text-center">
                                        <a href="#">مشاهدة جميع الرسائل</a>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="notifactions not_bar msg_call">
                            <i class="fas fa-comment-alt"></i>
                            <span class="note-msg">8</span>
                            <ul class="list-unstyled msg_ul">
                                <li>
                                    <div class="single_Pop d-flex">
                                        <div class="popIcon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="popCont">
                                            لديك اشعار لديك اشعار
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="single_Pop d-flex">
                                        <div class="popIcon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="popCont">
                                            لديك اشعار لديك اشعار
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="single_Pop d-flex">
                                        <div class="popIcon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="popCont">
                                            لديك اشعار لديك اشعار
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="all-note text-center">
                                        <a href="#">مشاهدة جميع الرسائل</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </header>
    <div class="fix-phone">
        <a class="nav-link order-price cont-phone" href="#" data-scroll="contact">
            <i class="fas fa-phone mr-2"></i>
            01234568</a>
    </div>

        <div class="container mt-5">
            <div class="privateData">
            <div class="head-div text-center wow fadeInUp mb-5">
                <h1>تعديل بياناتي</h1>

            </div>
             <div class="container">
            <div class="row flex-lg-nowrap">


              <div class="col">
                <div class="row">
                  <div class="col mb-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="e-profile">


                          <div class="tab-content pt-3">
                            <div class="tab-pane active">
                              <form class="form" novalidate="">
                                <div class="row">
                                  <div class="col">
                                    <div class="row">
                                      <div class="col">
                                        <div class="form-group">
                                          <label>الاسم</label>
                                          <input class="form-control" type="text" name="name" placeholder="John Smith" value="محمد احمد">
                                        </div>
                                      </div>
                                      <div class="col">
                                        <div class="form-group">
                                          <label>رقم الجوال</label>
                                          <input class="form-control" type="text" name="phonenumber" placeholder="johnny.s" value="0123456789">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col">
                                        <div class="form-group">
                                          <label>البريد الالكتروني</label>
                                          <input class="form-control" type="text" placeholder="user@example.com">
                                        </div>
                                      </div>
                                    </div>

                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-12 col-sm-6 mb-3">
                                    <div class="mb-2"><b>تغيير كلمة المرور</b></div>
                                    <div class="row">
                                      <div class="col">
                                        <div class="form-group">
                                          <label>كلمة المرور الحالية</label>
                                          <input class="form-control" type="password" placeholder="••••••">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col">
                                        <div class="form-group">
                                          <label>كلمة المرور الجديدة</label>
                                          <input class="form-control" type="password" placeholder="••••••">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col">
                                        <div class="form-group">
                                          <label> <span class="d-none d-xl-inline">تاكيد كلمة المرور</span></label>
                                          <input class="form-control" type="password" placeholder="••••••"></div>
                                      </div>
                                    </div>
                                  </div>

                                </div>
                                <div class="row">
                                  <div class="col d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit">حفظ التعديلات</button>
                                  </div>
                                </div>
                              </form>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3 mb-3">
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="px-xl-3">
                          <button class="btn btn-block btn-secondary">
                            <i class="fa fa-sign-out"></i>
                            <span>تسجيل خروج</span>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-body">
                        <h6 class="card-title font-weight-bold">تحتاح مساعدة ؟ </h6>
                        <p class="card-text">تواصل مع فريق الدعم الفني في حال لديك اي استقسارات</p>
                        <button type="button" class="btn btn-primary">تواصل الان</button>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            </div>

            </div>
        </div>


    <footer>

        <div class="container">


            <div class="row">

                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-desc">
                        <h3>الوساطة العقارية</h3>
                        <div class="address d-flex">
                            <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                            <p>{!! nl2br(__('global.website_address')) !!}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-desc">
                        <h3>ابقى بالقرب منا</h3>
                        <div class="icons-footer  ">
                            <span>
                                <a href="#">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </span>

                            <span>
                                <a href="#">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </span>
                            <span>
                                <a href="#">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </span>

                            <span>
                                <a href="#">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-desc">
                        <h3>ابقى على تواصل</h3>
                        <div class=" mails">
                            <input type="text" placeholder="بريدك الالكتروني">
                            <button><i class="fas fa-paper-plane ml-2"></i> ارسال</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </footer>
    <div class="container">
        <div class="row last">
            <div class="col-lg-6">
                <span class="cont-div">
                    <a href="#"><i class="fas fa-phone mr-2"></i> 012345679</a>

                </span>
                <span class="cont-div ml-4">
                    <a href="#"><i class="fas fa-envelope mr-2"></i> mail.info@gmail.com</a>

                </span>
            </div>
            <div class="col-lg-6">
                <div class="last-text text-right">
                    <p>جميع الحقوق محفوظة لـ شركة الوساطة العقارية</p>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('newWebsiteStyle/js/jQuery.js') }}"></script>
    <script src="{{ asset('newWebsiteStyle/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('newWebsiteStyle/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('newWebsiteStyle/js/owl-Function.js') }}"></script>
    <script src="{{ asset('newWebsiteStyle/js//jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('newWebsiteStyle/js/function.js') }}"></script>
    <script src="{{ asset('newWebsiteStyle/js/wow.min.js') }}"></script>


</body>

</html>
