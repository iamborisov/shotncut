$(function () {

  var windowHeight = $(window).height();
  var windowWidth = $(window).width();
  var $blogPic = $('.js-blog-pic');
  var $showMore = $('.js-show-blog');
  var $appendBox = $('.js-blog-append');
  var pageNum = {
    pageNum: 2
  };

  /**
   * set height to blog's pics box
   */
  function blogsHeight() {
    var heightProduct = 0;
    windowWidth > 767 ? heightProduct = parseInt($blogPic.width() / 2) : heightProduct = parseInt($blogPic.width());
    $blogPic.css('height', heightProduct+'px');
  }
  blogsHeight();


  /**
   * show more blogs
   */
  $showMore.on('click', function (e) {
    e.preventDefault();

    $.ajax({
      url: $(this).attr('data-action'),
      method: "GET",
      data: pageNum,
      dataType: "JSON",
      success: function (data) {
        data.result == 'ok' ? showSuccess(data) : showError();
      },
      error: function () {
        showError();
      }
    });
  });

  /**
   * success append blogs and hide show more button if come 0 of page
   */
  function showSuccess(data){
    if (data.html)
      $appendBox.append(data.html);

    blogsHeight();

    data.page != 0 ? pageNum.pageNum = data.page : $showMore.addClass('hidden');
  }

  function showError(){
    console.log('Ooops');
  }


  $(window).on('resize', function () {
    windowHeight = $(window).height();
    windowWidth = $(window).width();
    blogsHeight();
  });



});