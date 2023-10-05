$(document).ready(function () {

    // About Count Value Start

    if($('.count').length > 0) {

        $('.count').each(function () {

            $(this).prop('Counter', 0).animate({

                Counter: $(this).text()

            }, {

                duration: 4000,

                easing: 'swing',

                step: function (now) {

                    $(this).text(Math.ceil(now));

                }

            });

        });

    }

if ($(window).width() < 1023) {
  $('.mobileMenu').click(function(){
     $('.navigation').toggle();
    });
}



    if($('.serviceList').length > 0) {

        $('.serviceList').slick({

            dots: false,

            infinite: false,

            speed: 300,

            slidesToShow: 3,

            slidesToScroll: 1,

            responsive: [{

                    breakpoint: 1024,

                    settings: {

                        slidesToShow: 3,

                        slidesToScroll: 1,

                        infinite: true,

                        dots: true

                    }

                },
                {

                    breakpoint: 1023,

                    settings: {

                        slidesToShow: 2,

                        slidesToScroll: 1,

                        infinite: true,

                        dots: true

                    }

                },
                {

                    breakpoint: 767,

                   settings: {

                        slidesToShow: 2,

                        slidesToScroll: 1,

                        infinite: true,

                        dots: true

                    }
                },

                {

                    breakpoint: 580,

                    settings: {

                        slidesToShow: 1,

                        slidesToScroll: 1,

                        infinite: true,

                        dots: true

                    }

                }

    

            ]

        });

    }



    if($('.detailSlider').length > 0) {

        $('.detailSlider').slick({

            dots: false,

            infinite: false,

            autoplay: false,

            arrows: true,

            speed: 300,

            slidesToShow: 1,

            slidesToScroll: 1

    

        });

    }



    wow = new WOW({

        animateClass: 'animated',

        offset: 100,

        callback: function (box) {

            //console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")

        }

    });

    wow.init();

});