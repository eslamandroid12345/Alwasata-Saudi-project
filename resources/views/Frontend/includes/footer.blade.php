<footer>
    <section class="footerTp">
        <div class="container">
            <div class="row">
                <div style="float:left;" class="col-xs-12 col-md-12 col-lg-4 adressBlk">
                    <div class="companyInfos">
                        <h5> {{ MyHelpers::guest_trans('Contact Information') }}</h5>
                        <div class="addressDtl">
                            <div class="adrsInfo">
                                <figure><i class="fa fa-map-marker"></i></figure>
                                <address>{{ MyHelpers::guest_trans('Address') }} {!! nl2br(__('global.website_address')) !!}</address>
                            </div>
                            <div class="phoneinfo">
                                <a href='tel:'><i class="fa fa-phone"></i> {{ MyHelpers::guest_trans('phone') }}</a>
                            </div>
                            <div class="emailinfo">
                                <a href='mailto:'><i class="fa fa-envelope"></i> {{ MyHelpers::guest_trans('email') }}  info@alwasata.com</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-3 ftLinks">
                    <div class="footerLinks">
                        <h5></h5>

                        <ul>
                            <li><a href=""></a></li>
                            <li><a href=""></a></li>
                            <li><a href=""></a></li>
                            <li><a href=""></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-5 socialLink">
                    <div class="conenctus">
                        <div class="socialLinks">
                            <h5> {{ MyHelpers::guest_trans('Contact US') }}</h5>
                            <ul>
                                <li><a target="_blank" href="https://www.snapchat.com/add/alwsata"><i class="fa fa-snapchat"></i></a></li>
                                <li><a target="_blank" href="https://twitter.com/alwsatasa"><i class="fa fa-twitter"></i></a></li>
                                <li><a target="_blank" href="https://www.instagram.com/alwsatasa/"><i class="fa fa-instagram"></i></a></li>
                                <li><a target="_blank" href="https://www.linkedin.com/in/alwsatasa/"><i class="fa fa-linkedin"></i></a></li>
                                <li><a target="_blank" href="https://www.facebook.com/alwsatasa/"><i class="fa fa-facebook"></i></a></li>
                                <li><a target="_blank" href="https://www.youtube.com/channel/UC22GoF4CdghF5nv3g018IZA"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </div>
                        <div class="footerfrom">
                            <h6>{{ MyHelpers::guest_trans('Stay Informed') }}</h6>
                            <div class="formInner">
                                <form id="newsletter_from" onsubmit="return false;">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <figure><i class="fa fa-envelope"></i></figure>
                                        <input type="text" name="email" class="form-control elailft" placeholder="Email" autocomplete="off">
                                    </div>
                                    <div class="btnsect">
                                        <button id="newsLetterSaveBtn" type="button"><i class="fa fa-telegram"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

               <!--
                    <a href='tel:' class="footer-fixed-call-icon"><i class="fa fa-phone" aria-hidden="true"></i>{{ MyHelpers::guest_trans('Call us') }}</a>
-->
            </div>
        </div>
        </div>
    </section>
    <section class="footerBtm">
        <div class="container">
            <div class="row">
                <p>{{ MyHelpers::guest_trans('Copyright © 2020 Alwasat. All rights reserved.') }} {{ MyHelpers::guest_trans('Alwasat Real Estate') }}</p>
            </div>
        </div>
    </section>
</footer>
