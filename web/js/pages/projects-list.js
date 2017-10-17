$(function () {

  var $projects = $('.js-project');
  var $sortLink = $('.js-sort-link');
  var windowHeight = $(window).height();
  var windowWidth = $(window).width();
  var allSort = 'all';

  /**
   * set height to product's box
   */
  function productsHeight() {
    var heightProduct = 0;

    if (windowWidth > 1024) {
      heightProduct = parseInt($projects.width() * .7);
    } else if (windowWidth <=1024 && windowWidth > 767 ){
      heightProduct = parseInt($projects.width() * .7);
    } else if (windowWidth < 768 ){
      heightProduct = parseInt($projects.width() * .7);
    }
    $projects.css('height', heightProduct+'px');
  }
  productsHeight();

  function getHash() {
    var hash = window.location.hash.replace('#','');
    if ($('.js-sort-link[data-sort='+hash+']').length )
      sort(hash, $('.js-sort-link[data-sort='+hash+']'));
  }
  getHash();

  /**
   * sorting
   */
  $sortLink.on('click', function (e) {
    e.preventDefault();
    window.location.hash = '';

    if (!$(this).hasClass('active')){
      var thisSort = $(this).attr('data-sort');
      sort(thisSort, $(this));
    }
  });

  function sort(thisSort, $thisLink) {
    $sortLink.removeClass('active');
    $thisLink.addClass('active');
    $projects.addClass('hidden');

    if (thisSort != allSort){
      $projects.each(function () {
        var thisTypes = $(this).attr('data-project');
        if (thisTypes.indexOf(thisSort) >= 0)
          $(this).removeClass('hidden');
      })
    } else {
      $projects.removeClass('hidden');
    }
  }


  $(window).on('resize', function () {
    windowHeight = $(window).height();
    windowWidth = $(window).width();
    productsHeight();
  });


});