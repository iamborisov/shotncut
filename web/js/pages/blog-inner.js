$(function () {

  var $blogInnerText = $('.js-blog-inner-text');
  var $gallery = $('.js-gallery');
  var $blogBg = $('.js-blog-bg');
  var windowWidth = $(window).width();
  var windowHeight = $(window).height();

  $blogInnerText.each(function () {
    if ( $('img', $(this)).length ){
      $('img', $(this)).each(function () {
        $(this).wrap('<div class="b-wide-pic"></div>');
      })
    }

    if ( $('iframe', $(this)).length ){
      $('iframe', $(this)).each(function () {
        $(this).wrap('<div class="b-iframe"></div>');
      })
    }
  });

  /**
   * set height to bg
   */
  function setBlogBGHeight() {
    if ($blogBg.length){
      var height = 0;
      (windowWidth > 767) ? height = parseInt(windowHeight / 2) : height = parseInt(windowHeight / 3);
      $blogBg.css('height', height+'px');
    }
  }
  setBlogBGHeight();

  /**
   * set height to window height
   */
  function sliderPicValues() {
    var valHeight = windowHeight - 100;
    if (windowWidth > 1024){
      $('img', $gallery).css('height', valHeight+'px');
      $('img', $blogInnerText).css('height', valHeight+'px');
      $('iframe', $blogInnerText).css('height', valHeight+'px');
    } else {
      $('img', $gallery).css('width', windowWidth+'px');
      $('img', $blogInnerText).css('width', windowWidth+'px');
      $('iframe', $blogInnerText).css('width', windowWidth+'px');
    }
  }
  sliderPicValues();

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

  $(window).on('resize', function () {
    windowWidth = $(window).width();
    windowHeight = $(window).height();
    sliderPicValues();
    setBlogBGHeight();
  });

});