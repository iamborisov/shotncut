$(function () {

  var $header = $('.js-header');
  var $indexBg = $('.js-index-bg');
  var $indexScroll = $('.js-index-scroll');
  var windowHeight = $(window).height();
  var windowWidth = $(window).width();
  var $body = (windowWidth > 1024) ? $('body') : $('body, html');
  var $pageBar = $('.js-page-bar');
  var stateFirst = false;
  var $content = $('.js-content');
  var $indexProduct = $('.js-index-product');
  var $indexProductBox = $('.js-index-product-box');
  var $clientCarousel = $('.js-clients-carousel');
  var $clientSection = $('.js-clients-section');
  var $clientBg = $('.js-clients-bg');
  var heightClientBg = $clientBg.height();
  var $canvasVideoBox = $('.js-canvas-box');

  var isIOS = /iPad|iPhone|iPod/.test(navigator.platform);

  if (isIOS) {
    var canvasVideo = new CanvasVideoPlayer({
      videoSelector: '#indexVideo',
      canvasSelector: '#canvasVideo',
      timelineSelector: false,
      hideVideo: true, // should script hide the video element
      autoplay: false,
      makeLoop: true,
      // IMPORTANT On iOS can't be used together with autoplay, autoplay will be disabled
      audio: false, // can be true/false (it will use video file for audio), or selector for a separate audio file
      loop: true,
      resetOnLastFrame: true
    });
    canvasVideo.play();
  } else {
    $canvasVideoBox.addClass('hidden');
  }

  function getSizeCanvas() {
    $canvasVideoBox.each(function () {
      var widthCanvas = $('img', $(this)).width();
      $('.js-canvas', $(this)).css('width', widthCanvas+'px');
    })
  }
  getSizeCanvas();

  /**
   * set def animation and box's sizes
   */
  function setDef() {
    $body.addClass('overflow');
    $("#indexVideo").get(0).play();

    var topHeader = parseInt(windowHeight / 2 - 100);
    var topArrow = ( windowWidth > 768 ) ? parseInt(windowHeight - $indexScroll.outerHeight(true)) : parseInt(windowHeight - $indexScroll.outerHeight(true)) + 35;

    $header.css('top', topHeader+'px').addClass('show');
    $indexScroll.css('top', topArrow+'px');
    setTimeout(function () {
      $indexBg.addClass('show');
      $indexScroll.addClass('show');
      stateFirst = true;
    }, 500);
  }
  setDef();

  /**
   * scroll clients bg text
   */
  function scrollBgClietns() {
    var clientSectionVals = $clientSection[0].getBoundingClientRect();

    if (clientSectionVals.top <= windowHeight && clientSectionVals.top >= 0 ){
      var coef = parseInt(heightClientBg / windowHeight);
      var move = parseInt(( windowHeight - clientSectionVals.top ) * coef);
      $clientBg.css('transform', 'translate(0, -'+move+'px)');
    }
  }
  scrollBgClietns();
  
  /**
   * set height to product's box
   */
  function productsHeight() {
    var heightProduct = 0;

    if (windowWidth > 1024) {
      heightProduct = parseInt($indexProductBox.width() / 4);
    } else if (windowWidth <=1024 && windowWidth > 767 ){
      heightProduct = parseInt($indexProductBox.width() / 3);
    } else if (windowWidth < 768 ){
      heightProduct = parseInt($indexProductBox.width() / 1.5);
    }
    $indexProduct.css('height', heightProduct+'px');
  }
  productsHeight();

  /**
   * init first screen hide
   */
  function hideFirstStep() {
    stateFirst = false;
    $header.removeClass('b-header__center').css('top', 0);
    $indexScroll.removeClass('show');
    $pageBar.removeClass('b-page-bar__hide');
    $content.addClass('show');
    setTimeout(function () {
      $body.removeClass('overflow');
    }, 400)
  }

  /**
   * show page on scroll if status
   */
  $body.on('mousewheel', function () {
    if (stateFirst){
      hideFirstStep();
    }
  });

  $indexScroll.on('click', function (e) {
    e.preventDefault();
    if (stateFirst){
      hideFirstStep();
    }
  });


  $clientCarousel.slick({
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    dots: false,
    arrows: true,
    touchThreshold: 20,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1
        }
      }
    ]
  });


  $(window).on('scroll', function () {
    scrollBgClietns();
  });

  $(window).on('resize', function () {
    windowHeight = $(window).height();
    windowWidth = $(window).width();
    productsHeight();
    scrollBgClietns();
  });

});