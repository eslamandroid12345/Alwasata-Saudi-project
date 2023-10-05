 
$(function () {
  new WOW().init();
});

 


  $( document ).ready(function () {
    $(".HomePage .sidePar ,.homeCont").addClass('sideHide');
    $(".tableBar").addClass('ss');

 
});



$(function () {
  $("#Calc").click(function () {
    $(".first-calc").fadeOut();
    $(".SlideRow").slideDown().addClass("d-flex");
  });
});

$(function () {
  $(".notf_call").click(function () {
    $(".note_ul").slideToggle();
    $(".msg_ul,.mail_ul").slideUp();
  });
});

$(function () {
  $(".msg_call").click(function () {
    $(".msg_ul").slideToggle();
    $(".note_ul,.mail_ul").slideUp();
  });
});

$(function () {
  $(".mail_call").click(function () {
    $(".mail_ul").slideToggle();
    $(".note_ul ,.msg_ul").slideUp();
  });
});


$('.tableAdminOption span').tooltip(top)
 


$(document).ready(function(){
  $("#mytable #checkall").click(function () {
          if ($("#mytable #checkall").is(':checked')) {
              $("#mytable input[type=checkbox]").each(function () {
                  $(this).prop("checked", true);
              });
  
          } else {
              $("#mytable input[type=checkbox]").each(function () {
                  $(this).prop("checked", false);
              });
          }
      });
      
      $("[data-toggle=tooltip]").tooltip();
  });
  

  $(function () {
    $(".kafilHeader .addBtn button").click(function () {
      $(".infoKafil .userFormsDetails").slideToggle();
 
    });
  });


  $(function () {
    $(".dataFromHeader .addBtn button").click(function () {
      $(".dataFrom .userFormsDetails").slideToggle();
 
    });
  });

  $(function () {
    $(".downNow").click(function () {
      $(".downOrder").slideToggle();
 
    });
  });


  $(function () {
    $(window).scroll(function () {
      if ($(window).scrollTop() >= 150) {
        $(".tableBar").addClass("tFex");
      } else {
        $(".tableBar").removeClass("tFex");
      }
    });
  });

  /////
  $(function() {
    $(".tab").click(function() {
      var myID = $(this).attr("id");
      $(this)
        .addClass("active-on")
        .siblings()
        .removeClass("active-on");
      $(".tab-body .row.hdie-show").hide();
      $("#" + myID + "-cont").css("display", "flex");
    });
  });