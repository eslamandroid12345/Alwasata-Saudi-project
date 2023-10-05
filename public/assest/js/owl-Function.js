var owl_1 = $(".owl_1");
owl_1.owlCarousel({
  rtl: true,
  loop: true,
  responsiveClass: true,
  autoplay: true,
  autoplayTimeout: 3500,
  autoplayHoverPause: true,

  responsive: {
    0: {
      items: 1,
      nav: false
    },
    600: {
      items: 1,
      nav: false
    },
    850: {
      items: 2,
      nav: false
    },
    1000: {
      items: 3,
      nav: false
    }
  }
});
var owl_2 = $(".owl_2");
owl_2.owlCarousel({
  rtl: true,
  loop: true,
  responsiveClass: true,
  autoplay: true,
  autoplayTimeout: 3500,
  autoplayHoverPause: true,

  responsive: {
    0: {
      items: 2,
      nav: false
    },
    600: {
      items: 2,
      nav: false
    },
    850: {
      items: 3,
      nav: false
    },
    1000: {
      items: 5,
      nav: false
    }
  }
});