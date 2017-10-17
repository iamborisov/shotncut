$(function () {

  var windowWidth = $(window).width();
  var windowHeight = $(window).height();
  var $video = $('.js-video');
  var $backstage = $('.js-backstage');
  var $projectTextTop = $('.js-project-text-top');
  var $projectLogo = $('.js-project-logo');

  /**
   * set height to window height
   */
  function sliderPicValues() {
    var valHeight = windowHeight - 100;
    var width = 0;
    var heightBackstage = $backstage.width() * 0.7;

    $backstage.css('height', heightBackstage+'px');

    if (windowWidth > 1024){

      $('iframe', $video).css({height: valHeight+'px', width: '100%'});
      $projectTextTop.css('height', 'auto');

    } else if (windowWidth <= 1024 && windowWidth >= 768){

      width = parseInt(windowWidth*.92);
      $('iframe', $video).css('width', width+'px');
      $projectTextTop.css('height', 'auto');

    } else if (windowWidth < 768) {
      $('iframe', $video).css('width', windowWidth+'px');

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