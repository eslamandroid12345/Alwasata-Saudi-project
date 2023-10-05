(function ($) {
  "use-strict";

  // Show header__topbar In Mobile
  $(".header_mobile_toggle").click(function () {
    $(".main-header__topbar").toggleClass("main-header_active");
  });


  // Show Aside In Mobile
  $(".aside_mobile_toggle").click(function () {
    $(".main-aside-overlay").toggleClass("main-aside-overlay-avtive");
    $(".main-aside").toggleClass("main-aside-active");
  });


  // Show Aside In header_menu In Mobile
  $(".menu_mobile_toggle").click(function () {
    $(".main-aside-overlay").toggleClass("main-aside-overlay-avtive");
    $(".main-header_menu").toggleClass("main-header_menu-active ");
  });


  // Remove Aside In header_menu And Aside In Mobile
  $(document).on("click", ".main-aside-overlay-avtive", function () {
    $(".main-aside-overlay").removeClass("main-aside-overlay-avtive");
    $(".main-aside").removeClass("main-aside-active");
    $(".main-header_menu").removeClass("main-header_menu-active ");
  });


  // Close Aside In Mobile
  $(".aside_mobile_close").click(function () {
    $(".main-aside-overlay").removeClass("main-aside-overlay-avtive");
    $(".main-aside").removeClass("main-aside-active");
  });


  // Close header_menu In Mobile
  $(".menu_mobile_close").click(function () {
    $(".main-aside-overlay").removeClass("main-aside-overlay-avtive");
    $(".main-header_menu").removeClass("main-header_menu-active ");
  });


  // Show And Hide Fill aside
  $(document)
    .on("mouseenter", ".main-aside-menu-wrapper", function (event) {
      $('.main-aside').addClass("is-open");
    })
    .on("mouseleave", ".main-aside-menu-wrapper", function () {
      $('.main-aside').removeClass("is-open");
      $('body').removeClass("aside-open");
    });



  // Show Dropdown menu in aside 
  $('.menu-toggle').click(function (e) {
    e.preventDefault();
    $(this).closest('.main-menu__item').find('.menu-submenu').slideToggle(300)
  });





  $('.toggle-aside').click(function (e) {
    $('.main-aside').toggleClass("is-open");
    $('body').toggleClass("aside-open");
  });




  /*------------------------------------
		datetimepicker
      --------------------------------------*/
  $('.datetimepicker_1').datetimepicker({
    format: "yyyy/mm/dd",
    todayHighlight: true,
    autoclose: true,
    startView: 2,
    minView: 2,
    forceParse: 0,
    pickerPosition: 'bottom-left',
  });





  /*------------------------------------
		datetimeclock
      --------------------------------------*/
      $('.datetimeclock').datetimepicker({
        pickDate: false,
        minuteStep: 5,
        pickerPosition: 'top-right',
        format: 'HH:ii',
        autoclose: true,
        showMeridian: true,
        todayHighlight: true,
        startView: 1,
        maxView: 1,
      });
    

      
  /*------------------------------------
		selectpicker
      --------------------------------------*/
  $(".selectpicker").selectpicker({
    noneResultsText: 'لا يوجد نتائج'
  });





  /*------------------------------------
    PerfectScrollbar
  --------------------------------------*/
  $('.scroll').each(function () {
    const ps = new PerfectScrollbar($(this)[0]);
  });





  
  /*------------------------------------
    input- switch content
  --------------------------------------*/
  $('.input-switch').change(function(){
    if ($(this).is(":checked")) {
      $(this).closest('.widget-switch').find('.widget-switch-content').slideDown()
    } else {
      $(this).closest('.widget-switch').find('.widget-switch-content').slideUp()
    }
  })




  $('.input-switch-parent').change(function(){
    if ($(this).is(":checked")) {
      $(this).closest('.widget-switch-parent').find('.widget-switch.widget-switch-child').fadeIn()
    } else {
      $(this).closest('.widget-switch-parent').find('.widget-switch.widget-switch-child').fadeOut()
    }
  });





  $('.trigger-action td:not(:last-of-type)').click(function(){
    $(this).closest('tr').toggleClass('selected')
  });




  /*------------------------------------
    checkAll all
  --------------------------------------*/
  $(".checkAll").click(function(){
    $('.table input:checkbox').not(this).prop('checked', this.checked);
  });


  $('.add-to-special-orders').click(function(){
    $(this).toggleClass('active')
  });






    /*------------------------------------
    resizableColumns
  --------------------------------------*/
  $('.table-resizable').resizableColumns();







  /*------------------------------------
   input checkbox order
  --------------------------------------*/
  $('.input-checkbox').on('change' , function(){
    if ($(this).is(':checked')) {
      $(this).closest('.widget__item-order').addClass('selected')
    }else{
      $(this).closest('.widget__item-order').removeClass('selected')
    }
  });




  


  /*------------------------------------
    select2
  --------------------------------------*/
  $('.select2').select2({
    width: '100%',
    dir:"rtl"
  });


  $(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() > $(document).height() - $('.tab-pane.active.show .portlet-student').height()) {
       $('.note-student').fadeOut()
    }else{
      $('.note-student').fadeIn()
    }
 });


})(jQuery);



$(".hijri-date-input").datepicker({
  beforeShow: addhijridate,
  onChangeMonthYear: addhijridate,
  beforeShowDay: function(date) {
    var title = "" + getwriteIslamicDate(0,date,"day")
    return [true, "", title];
  }
});
addhijridate();



var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})