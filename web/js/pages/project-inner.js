$(function () {

  var windowWidth = $(window).width();
  var windowHeight = $(window).height();
  var $video = $('.js-video');
  var $projectTextTop = $('.js-project-text-top');
  var $projectLogo = $('.js-project-logo');

  var $gallery = $('.js-gallery');

  $gallery.slick({
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

  /**
   * set height to window height
   */
  function sliderPicValues() {
    var valHeight = windowHeight - 100;
    var width = 0;

    if (windowWidth > 1024){

      $('img', $gallery).css('height', valHeight+'px');

      $('iframe', $video).css({height: valHeight+'px', width: '100%'});
      $projectTextTop.css('height', 'auto');

    } else if (windowWidth <= 1024 && windowWidth >= 768){

      width = parseInt(windowWidth*.92);
      $('iframe', $video).css('width', width+'px');
      $projectTextTop.css('height', 'auto');

      $('img', $gallery).css('width', windowWidth+'px');

    } else if (windowWidth < 768) {
      $('iframe', $video).css('width', windowWidth+'px');

      $('img', $gallery).css('width', windowWidth+'px');

      var heightLogo = $projectLogo.height();
      $projectTextTop.css('height', heightLogo+'px');
    }
  }
  sliderPicValues();


  $(window).on('resize', function () {
    windowWidth = $(window).width();
    windowHeight = $(window).height();
    sliderPicValues();
  });

});