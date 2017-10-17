$(function () {

  var windowWidth = $(window).width();
  var windowHeight = $(window).height();
  var $aboutGallery = $('.js-about-gallery');

  /**
   * set height to window height
   */
  function sliderPicValues() {
    var valHeight = windowHeight - 100;
    if (windowWidth > 1024){
      $('img', $aboutGallery).css('height', valHeight+'px');
    } else {
      $('img', $aboutGallery).css('width', windowWidth+'px');
    }
  }
  sliderPicValues();

  $aboutGallery.slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
    arrows: true,
    touchThreshold: 20,
    centerMode: true,
    centerPadding: '0',
    variableWidth: true
  });

  $(window).on('resize', function () {
    windowWidth = $(window).width();
    windowHeight = $(window).height();
    sliderPicValues();
  });

});