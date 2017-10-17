$(function () {

  var windowWidth = $(window).width();
  var windowHeight = $(window).height();
  var $header = $('.js-header');
  var $fullScreen = $('.js-window-height');
  var $menuCall = $('.js-call-menu');
  var $menuOvarlay = $('.js-menu-overlay');
  var $body = $('body');
  var $scrollToTopLink = $('.js-scroll-top');
  var $indexBg = $('.js-index-bg');
  var $pageBar = $('.js-page-bar');
  var $content = $('.js-content');
  var $animationBgStopBox = $('.js-bg-animation-stop');
  var $indexTitle = $('.js-index-title');
  var magicOffset = 120;

  /**
   * scroll page top before reload
   */
  $(window).on('beforeunload', function(){
    $(window).scrollTop(0);
  });

  /**
   * def set
   */
  function defSet() {
    $fullScreen.css('height', windowHeight+'px');
    if (!$('.js-index-marker').length){
      $header.removeClass('b-header__center');
    }
    
    $header.css('top', '0px').addClass('show');
    setTimeout(function () {
      $indexBg.addClass('show');
      if (!$('.js-index-marker').length)
        hideFirstStep();
    }, 500);
  }
  defSet();

  /**
   * animate scrolling depends to products section
   */
  function animateIndex() {
    if ($animationBgStopBox.length){

      var indexProductSection_top = $animationBgStopBox[0].getBoundingClientRect().top;
      var indexProductSection_offset = $animationBgStopBox.offset().top;

      if (indexProductSection_top > 0 && indexProductSection_top > magicOffset){
        var perCent = (indexProductSection_offset - magicOffset) / 100;

        var opacityVal = 1 - ( ((indexProductSection_offset - magicOffset) - (indexProductSection_top - magicOffset) ) / perCent ) / 100;
        $('.b-index-bg', $indexBg).css('opacity', opacityVal);
        $indexTitle.css('opacity', opacityVal);

        if (!$header.hasClass('fixed'))
          $header.addClass('fixed');

      } else if (indexProductSection_top <= magicOffset){
        $('.b-index-bg', $indexBg).css('opacity', '0');
        $indexTitle.css('opacity', '0');

        if ($header.hasClass('fixed'))
          $header.removeClass('fixed');
      }

    }
  }
  animateIndex();

  /**
   * init first screen hide
   */
  function hideFirstStep() {
    $pageBar.removeClass('b-page-bar__hide');
    $content.addClass('show');
    setTimeout(function () {
      $body.removeClass('overflow');
    }, 400)
  }

  /**
   * scroll top page on click link
   */
  $scrollToTopLink.on('click', function (e) {
    e.preventDefault();
    scrollToBlock('body');
  });

  /**
   * show or hide scroll to top link
   */
  function showScrollToTopLink() {
    var scrollTop = $(window).scrollTop();
    if (scrollTop >= windowHeight && $scrollToTopLink.hasClass('hidden')){
      $scrollToTopLink.removeClass('hidden')
    } else if (scrollTop < windowHeight && !$scrollToTopLink.hasClass('hidden')){
      $scrollToTopLink.addClass('hidden')
    }
  }
  showScrollToTopLink();

  /**
   * open menu
   */
  $menuCall.on('click', function (e) {
    e.preventDefault();
    $body.addClass('menu-open');
  });

  /**
   * close menu on overlay
   */
  $menuOvarlay.on('click', function (e) {
    e.preventDefault();
    $body.removeClass('menu-open');
  });

  /**
   * close popup if esc
   */
  $(document).keyup(function (e) {
    if (e.which == 27 && $body.hasClass('menu-open')) {
      $body.removeClass('menu-open');
    }
  });

  $(window).on('scroll', function () {
    showScrollToTopLink();
    animateIndex();
  });

  $(window).on('resize', function () {
    windowWidth = $(window).width();
    windowHeight = $(window).height();
    defSet();
    showScrollToTopLink();
    animateIndex();
  });

});

/**
 * format price
 * @param val
 * @returns {string}
 */
formating = function(val) {
  return String(val).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, '$1 ');
};

/**
 * declension words
 */
declension = function(number, one, two, five) {
  number = Math.abs(number);
  number %= 100;
  if (number >= 5 && number <= 20) {
    return five;
  }
  number %= 10;
  if (number == 1) {
    return one;
  }
  if (number >= 2 && number <= 4) {
    return two;
  }
  return five;
};

/**
 * scroll to block
 * @param selector
 */
scrollToBlock = function(selector, offset, scrollElem, position) {
  setTimeout(function () {
    !offset ? offset = 0 : offset;
    if (!position == true){
      var scroll = $(selector).offset().top - offset;
    } else {
      var scroll = $(selector).position().top - offset;
    }

    if (!scrollElem){
      scrollElem = $('html,body');
    }
    scrollElem.animate({
      scrollTop: scroll
    }, 500);
  }, 10);
};
