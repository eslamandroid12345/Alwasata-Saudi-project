$(function () {
  $(window).scroll(function () {
    if ($(window).scrollTop() >= 250) {
      $("nav").addClass("fex");
    } else {
      $("nav").removeClass("fex");
    }
  });
});

$(function () {
  new WOW().init();
});

$(function () {
  "use strict";
  $(".ScrollBtn ,.slide-scroll").click(function () {
    $("html, body").animate(
      {
        scrollTop: $("#" + $(this).data("scroll")).offset().top,
      },
      1500
    );
  });
});

$(function () {
  $("#Calc").click(function () {
    $(".first-calc").fadeOut();
    $(".SlideRow").slideDown().addClass("d-flex");
  });
});
