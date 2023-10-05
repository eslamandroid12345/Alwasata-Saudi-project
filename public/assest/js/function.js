
$(function () {
  new WOW().init();
});


$(function () {
    $(".client_message_call").click(function () {
        $(".message_ul_client").slideToggle();
        $(".note_ul,.mail_ul,.notfi_ul").slideUp();
    });
});

$(function () {
    $("#usersStatusClick").click(function () {
        $("#usersStatusContainer").slideToggle();

    });
});
$(function () {
  $(".toogle").click(function () {
    $(".HomePage .sidePar ,.homeCont").toggleClass('sideHide');
    $(".tableBar").toggleClass('ss');
});
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
    $(".msg_ul,.mail_ul,.notfi_ul,.message_ul_client").slideUp();
  });
});

$(function () {
    $(".notfi_call").click(function () {
        $(".notfi_ul").slideToggle();
        $(".msg_ul,.mail_ul,.note_ul,.message_ul_client").slideUp();
    });
});

$(function () {
  $(".msg_call").click(function () {
    $(".msg_ul").slideToggle();
    $(".note_ul,.mail_ul,.notfi_ul,.message_ul_client").slideUp();
  });
});

$(function () {
  $(".mail_call").click(function () {
    $(".mail_ul").slideToggle();
    $(".note_ul ,.msg_ul,.message_ul_client").slideUp();
  });
});


$('.tableAdminOption span').tooltip(top)
$('button.dt-button').tooltip(top)

$('.has-tooltip').tooltip(top)


$(document).ready(function(){
  $("#checkall").click(function () {
          if ($("#checkall").is(':checked')) {
              $("input[type=checkbox]").each(function () {
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
    $(".prepayHeader .addBtn button").click(function () {
        $(".prepayInfo .prepaydiv").slideToggle();

    });
});

$(function () {
    $(".mortgageInfoHeader .addBtn button").click(function () {
        $(".mortgageInfo .mortgageDiv").slideToggle();

    });
});



$(function () {
    $(".tsaheelHeader .addBtn button").click(function () {
        $(".tsaheelInfo .tsaheeldiv").slideToggle();

    });
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
        $("#fixedTop").addClass("tFex");
      } else {
        $("#fixedTop").removeClass("tFex");
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
      $(".hdie-show").hide();
      $("#" + myID + "-cont").css("display", "flex");
    });
  });



  // $('#myModal').on('shown.bs.modal', function () {
  //   $('#myInput').trigger('focus')
  // })



  $(function () {
    $("#usersStatusClick").click(function () {
      $("#usersStatusContainer").slideToggle();

    });
  });

///////////
$(function () {
  $(".dataFromHeader .result button").click(function () {
    $(".dataFrom .userFormsResult").slideToggle();

  });
});


/*

$(function() {
  $(".resultBtn").click(function() {
    var myID = $(this).attr("id");
    $(this)
      .addClass("active-on")
      .siblings()
      .removeClass("active-on");
    $(".result-body .row.hdie-show").hide();
    $("#" + myID + "-cont").css("display", "flex");
  });
});



$(function () {
  $(".toggleBankResult").click(function () {
    $(this).next().slideToggle();

  });
});
*/
$(function () {
  $(".toggleBankResult").click(function () {
    $(this).next().slideToggle();

  });
});








