$(function () {

  var windowWidth = $(window).width();
  var windowHeight = $(window).height();

  var $popupOverlay = $('.js-popup-overlay');
  var $popupClose = $('.js-popup-close');
  var $popup = $('.js-popup');
  var $form = $('.js-calc-form');
  var $popupCall =  $('.js-call-calc');
  var $body = $('body');

  var $calcItems = $('.js-calc-form-item');
  var $checkboxOther = $('.js-checkbox-other');
  var $checkboxBox = $('.js-checkbox');
  var $calcNavLink = $('.js-calc-nav-link');

  var $sliderTime = $('.js-slider-time');
  var $calcTimeText = $('.js-calc-time-text');
  var $calcTimeInput = $('.js-calc-time-input');

  var $sliderDays = $('.js-slider-days');
  var $calcDaysText = $('.js-calc-days-text');
  var $calcDaysInput = $('.js-calc-days-input');

  var $currentStep = $('.js-current-step');
  var $allStep = $('.js-all-step');
  var $calcNext = $('.js-calc-next');
  var $calcPrev = $('.js-calc-prev');
  var $sideBarPrice = $('.js-sidebar-price');

  var $speakerShowInput = $('.js-speaker-show');
  var $speakerBox = $('.js-checkbox-speaker');
  var $showHeroes = $('.js-show-heroes');
  var $calcItemsHero = $('.js-calc-items-hero');

  var $inputIdea = $('.js-calc-idea');
  var $inputScenario = $('.js-calc-scenario');
  var $inputsHeoesAmount = $('.js-calc-heroes-amount');
  var $inputHeroSearch = $('.js-calc-hero-search');
  var $inputVisagiste = $('.js-calc-visagiste');
  var $inputStylist = $('.js-calc-stylist');
  var $inputStoryboard = $('.js-calc-storyboard');
  var $calcEquipment = $('.js-calc-equipment');
  var $calcTeam = $('.js-calc-team');
  var $calcMontage = $('.js-calc-montage');
  var $calcColor =  $('.js-calc-color');
  var $calcCound = $('.js-calc-sound');
  var $calcMusic = $('.js-calc-music');
  var $calcSpeaker = $('.js-calc-speaker');
  var $calcCopywriter = $('.js-calc-copywriter');
  var $calcCof = $('.js-calc-cof');
  var $calcPriceInput = $('.js-common-price-input');

  var $calcEmail = $('.js-calc-email');
  var $calcStepsAll = $('.js-calc-steps');
  var $calcSuccessPrice = $('.js-calc-success-price');
  var $errorField = $('.js-calc-error');
  var $successField = $('.js-calc-success');

  var $calcCall = $('.js-call-calc-block');
  var $popupFirst = $('.js-popup-first');
  var $popupCalc = $('.js-calc-box');
  var $shemeCall = $('.js-call-scheme');
  var $shemeBox = $('.js-scheme');
  var $schemeBack = $('.js-sheme-back');

  var currentIndexCalc = 0;
  var DEF_TIME = 10;
  var DEF_DAYS = 1;
  var CALC_SPEAKER_SHOW_ID = 'calc_speaker-yes';
  var cof = 1;
  var videoTime = DEF_TIME;
  var days = DEF_DAYS;
  var commonPrice = 0;
  var valFromDop = 180;
  var dopMontagePrice = 2000;
  var dopColorPrice = 1500;
  var dopSoundPrice = 1000;

  /**
   * open sheme and close all other window
   */
  $shemeCall.on('click', function (e) {
    e.preventDefault();
    $popupFirst.addClass('hidden');
    $successField.addClass('hidden');
    $popupCalc.addClass('hidden');
    $shemeBox.removeClass('hidden');
  });

  /**
   * back to popup calc from sheme
   */
  $schemeBack.on('click', function (e) {
    e.preventDefault();
    $shemeBox.addClass('hidden');
    $popupFirst.removeClass('hidden');
  });

  /**
   * close popup
   */
  function closePopup() {
    if ($popupOverlay.hasClass('open')){
      $popupOverlay.removeClass('open');
      $body.removeClass('overflow');
    }
  }
  $popupClose.on('click', function (e) {
    e.preventDefault();
    closePopup();
  });
  /**
   * close popup if esc
   */
  $(document).keyup(function (e) {
    if (e.which == 27) {
      closePopup();
    }
  });

  /**
   * call popup calc and scheme
   */
  $popupCall.on('click', function (e) {
    e.preventDefault();

    $popupOverlay.addClass('open');
    $body.addClass('overflow');

  });

  /**
   * call calc form
   */
  $calcCall.on('click', function (e) {
    e.preventDefault();
    $popupFirst.addClass('hidden');
    $popupCalc.removeClass('hidden');
    $calcItems.addClass('hidden').removeClass('current');
    $calcItems.eq(0).removeClass('hidden').addClass('current');
    firstArrow();
  });

  /**
   * fn calculation
   */
  function calculate() {
    commonPrice = 0;

    // cof
    $calcCof.each(function () {
      if ($(this).is(':checked')){
        cof = $(this).attr('data-cof');
      }
    });

    // idea
    if ($inputIdea.is(':checked')){
      commonPrice += parseInt($inputIdea.attr('data-price'));
    }

    // scenario * cof
    if ($inputScenario.is(':checked')){
      commonPrice += parseInt($inputScenario.attr('data-price')) * cof;
    }

    // heroes amount * days * amount
    $inputsHeoesAmount.each(function () {
      if ($(this).val() != ''){
        commonPrice += parseInt($(this).val()) * parseInt($(this).attr('data-price')) * days;
      }
    });

    //heroes search
    if ($inputHeroSearch.is(':checked')){
      commonPrice += parseInt($inputHeroSearch.attr('data-price'));
    }

    //visagiste
    if ($inputVisagiste.is(':checked')){
      commonPrice += parseInt($inputVisagiste.attr('data-price')) * days;
    }

    //stylist
    if ($inputStylist.is(':checked')){
      commonPrice += parseInt($inputStylist.attr('data-price')) * days;
    }

    //storyboard
    if ($inputStoryboard.is(':checked')){
      commonPrice += (videoTime / 10) * 4 * parseInt($inputStoryboard.attr('data-price'));
    }

    // Equipment
    $calcEquipment.each(function () {
      if ($(this).is(':checked')){
        commonPrice += parseInt($(this).attr('data-price')) * days * cof
      }
    });

    // team
    if ($calcTeam.is(':checked')){
      commonPrice += parseInt($calcTeam.attr('data-price')) * days;
    }

    // Montage
    if ($calcMontage.is(':checked')){
      var montageDop = (videoTime > valFromDop ) ? Math.ceil((videoTime - valFromDop) / 60) * dopMontagePrice : 0;
      commonPrice += (parseInt($calcMontage.attr('data-price')) + montageDop) * cof;
    }

    // Color correction
    if ($calcColor.is(':checked')){
      var colorDop = (videoTime > valFromDop ) ? Math.ceil((videoTime - valFromDop) / 60) * dopColorPrice : 0;
      commonPrice += (parseInt($calcColor.attr('data-price')) + colorDop) * cof;
    }

    // Sound
    if ($calcCound.is(':checked')){
      var soundDop = (videoTime > valFromDop ) ? Math.ceil((videoTime - valFromDop) / 60) * dopSoundPrice : 0;
      commonPrice += parseInt($calcCound.attr('data-price')) + soundDop;
    }

    // music
    $calcMusic.each(function () {
      if ($(this).is(':checked')){
        commonPrice += parseInt($(this).attr('data-price'));
      }
    });

    // speaker
    $calcSpeaker.each(function () {
      if ($(this).is(':checked')){
        commonPrice += parseInt($(this).attr('data-price'));
      }
    });

    // copywriter
    if ($calcCopywriter.is(':checked')){
      commonPrice += parseInt($calcCopywriter.attr('data-price'));
    }


    $sideBarPrice.html(formating(commonPrice));
    $calcPriceInput.val(formating(commonPrice)).trigger('change');
    $calcSuccessPrice.html(formating(commonPrice));
  }

  /**
   * make calculation on all changes
   */
  $('input', $form).on('keyup paste change', function () {
    if (!$(this).hasClass('js-common-price-input')){
      calculate();
    }
  });

  /**
   * check disabled items prev
   * @param $prevItem
   * @param $thisItem
   */
  function checkPrevDisabled($prevItem, $thisItem) {
    if ($prevItem.hasClass('disabled')){
      $prevItem = $prevItem.prev($calcItems);
      checkPrevDisabled($prevItem, $thisItem)
    } else {
      $thisItem.removeClass('current');
      setTimeout(function () {
        $thisItem.addClass('hidden');
        $prevItem.addClass('current').removeClass('hidden');

        firstArrow();
      }, 200);
    }
  }

  /**
   * check if next item disabled
   * @param $nextItem
   * @param $thisItem
   */
  function checkNextDisabled($nextItem, $thisItem) {
    if ($nextItem.hasClass('disabled')){
      $nextItem = $nextItem.next($calcItems);
      checkNextDisabled($nextItem, $thisItem);
    } else {
      nextStep($nextItem, $thisItem);
    }
  }

  /**
   * next step show
   * @param $nextItem
   * @param $thisItem
   */
  function nextStep($nextItem, $thisItem) {
    $thisItem.removeClass('current').addClass('hidden');
    $nextItem.removeClass('hidden');
    setTimeout(function () {
      $nextItem.addClass('current');

      firstArrow();
    }, 100);
  }

  /**
   * next calc item
   */
  $calcNext.on('click', function (e) {
    e.preventDefault();

    var $thisItem = $('.js-calc-form-item.current');

    if ($thisItem.next($calcItems).length){
      var $nextItem = $thisItem.next($calcItems);
      checkNextDisabled($nextItem, $thisItem);
    } else if (!$thisItem.next($calcItems).length && parseInt($calcPriceInput.val()) != 0) {
      $calcEmail.removeClass('hidden');
      $calcStepsAll.addClass('hidden');
    }
  });

  /**
   * prev calc item
   */
  $calcPrev.on('click', function (e) {
    e.preventDefault();

    var $thisItem = $('.js-calc-form-item.current');
    var $prevItem = $thisItem.prev($calcItems);

    checkPrevDisabled($prevItem, $thisItem);

  });

  /**
   * nav link calc click
   */
  $calcNavLink.on('click', function (e) {
    e.preventDefault();

    if (!$(this).hasClass('disabled') && !$(this).hasClass('active')){
      var thisHref = parseInt($(this).attr('href')) - 1;
      $calcItems.addClass('hidden').removeClass('current');
      $calcItems.eq(thisHref).removeClass('hidden').addClass('current');
      firstArrow();
    }
  });

  /**
   * hide first arrow
   */
  function firstArrow() {
    var minusSteps = 0;
    $calcItems.each(function (index) {
      if ($(this).hasClass('disabled')){
        index += 1;
        minusSteps++;
        $(this).attr('data-index', '')
      } else {
        index += 1 - minusSteps;
        $(this).attr('data-index', index)
      }
    });

    // index for nav change
    var indexReal = $('.js-calc-form-item.current').index() + 1;
    $calcNavLink.each(function () {
      var thisHref = parseInt($(this).attr('href'));
      if (thisHref <= indexReal){
        $(this).removeClass('disabled');
        $calcNavLink.removeClass('active');
        $(this).addClass('active');
      }
    });

    currentIndexCalc = $('.js-calc-form-item.current').attr('data-index');
    (currentIndexCalc == 1) ? $calcPrev.addClass('visibility') : $calcPrev.removeClass('visibility');
    $currentStep.html(currentIndexCalc);
  }
  firstArrow();

  /**
   * all step count
   */
  function allStepCount() {
    var count = 0;
    $calcItems.each(function () {
      if (!$(this).hasClass('disabled')){
        count++;
      }
    });
    $allStep.html(count);
  }
  allStepCount();

  /**
   * init time slider
   */
  $sliderTime.slider({
    range: "min",
    value: DEF_TIME,
    min: 10,
    max: 600,
    step: 10,
    slide: function( event, ui ) {
      getTimeFormat(ui.value);
    }
  });

  /**
   * init days slider
   */
  $sliderDays.slider({
    range: "min",
    value: DEF_DAYS,
    min: 1,
    max: 365,
    step: 1,
    slide: function( event, ui ) {
      getDaysVals(ui.value);
    }
  });

  /**
   * set days text
   */
  function getDaysVals(val) {
    days = val;
    var daysWord = declension(val, 'день', 'дня', 'дней');
    var text = (val != 0) ? (val + ' ' + daysWord) : ('');
    $calcDaysText.html(text);
    $calcDaysInput.val(text).trigger('change');
  }
  getDaysVals(DEF_DAYS);

  /**
   * formating time
   * @param val
   */
  function getTimeFormat(val) {
    videoTime = val;
    var parseMin = parseInt(val / 60);
    var parseSec = val - parseMin * 60;
    var minText = (parseMin != 0 ) ? parseMin+' мин &nbsp;&nbsp;' : '';
    var secText = (parseSec != 0 ) ? parseSec+' c' : '00 c';

    $calcTimeText.html( minText + secText);
    $calcTimeInput.val(minText + secText).trigger('change');
  }
  getTimeFormat(DEF_TIME);

  /**
   * show other input or hide
   */
  $('input', $checkboxBox).on('change', function () {
    var $thisCalcItem = $(this).closest($calcItems);
    var $thisOtherBox = $('.js-other-input', $thisCalcItem);

    // heroes items calc show | hide
    $showHeroes.each(function () {
      if ($(this).is(':checked')){
        $calcItemsHero.removeClass('disabled');
        allStepCount();
        firstArrow();
        return false
      } else {
        $calcItemsHero.addClass('disabled');
        allStepCount();
        firstArrow();
      }
    });


    // speaker show | hide
    if ($(this).hasClass('js-speaker-show') && $(this).is(':checked') && $(this).attr('id') == CALC_SPEAKER_SHOW_ID){
      $speakerBox.removeClass('hidden');
    } else if ($(this).hasClass('js-speaker-show') && $(this).is(':checked') && $(this).attr('id') != CALC_SPEAKER_SHOW_ID){
      $speakerBox.addClass('hidden');
      $('input', $speakerBox).prop('checked', false);
    }

    // other show
    if ($(this).is(':checked') && $(this).hasClass('js-checkbox-other')){

      var $thisInputBox = $(this).closest($checkboxBox);
      $thisOtherBox = $thisInputBox.next('.js-other-input');

      $('.js-other-input', $thisCalcItem).addClass('visibility');
      $('input', $('.js-other-input', $thisCalcItem)).val('').trigger('change');

      $thisOtherBox.removeClass('visibility');
      $('input', $thisOtherBox).focus();

    } else if ($(this).is(':checked') && !$(this).hasClass('js-checkbox-other') && $(this).attr('type') == 'radio') {

      $thisOtherBox.addClass('visibility');
      $('input', $thisOtherBox).val('').trigger('change');

    } else if (!$(this).is(':checked') && $(this).hasClass('js-checkbox-other')) {

      var $thisInputBox = $(this).closest($checkboxBox);
      $thisOtherBox = $thisInputBox.next('.js-other-input');

      $thisOtherBox.addClass('visibility');
      $('input', $thisOtherBox).val('').trigger('change');
    }
  });

  /**
   * set popup vals
   */
  function defVals() {
    var popupHeight = (windowHeight > 1024) ? windowHeight - 100 : windowHeight;
    $popup.css('height', popupHeight+'px');
  }
  defVals();


  // VALIDATION

  /**
   * validate form for amount of filled required felds
   * @param $form - form dom
   */
  function chkform($form) {
    var emailMask = /^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-zA-Z0-9]{1}[a-zA-Z0-9\-]{0,62}[a-zA-Z0-9]{1})|[a-zA-Z])\.)+[a-zA-Z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/;
    var validEmail = emailMask.test($('.email', $form).val());

    if ($('.js-required.js-filled', $form).length < $('.js-required', $form).length) {
      $('.b-button', $form).attr('disabled', 'disabled');
    } else if (!validEmail){
      $('.b-button', $form).attr('disabled', 'disabled');
    } else {
      $('.b-button', $form).removeAttr('disabled');
    }
  }

  /**
   * set status filled of value more than 0
   */
  $('input, textarea', $form).on('keyup paste change input', function () {
    var $this = $(this);
    this.value.length > 0 ? $this.addClass('js-filled') : $this.removeClass('js-filled');
    chkform($form);
  });

  /**
   * submit form
   */
  $form.on('submit', function(e) {
    var $this = $(this);
    e.preventDefault();
    e.stopPropagation();

    submit($this);
    return false;
  });

  function submit($this) {
    $.ajax({
      url: $this.attr('action'),
      method: "POST",
      data: $this.serialize(),
      dataType: "JSON",
      success: function (data) {
        data.result == 'ok' ? showSuccess() : showError();
      },
      error: function () {
        showError();
      }
    });
  }

  /**
   * show error text
   * @param text
   */
  function showError(text) {
    $errorField.removeClass('hidden');
    setTimeout(function () {
      $errorField.addClass('hidden');
    }, 3000)
  }

  /**
   * reset form of success submit
   * @param $this - form
   */
  function showSuccess() {
    $popupCalc.addClass('hidden');
    $successField.removeClass('hidden');
  }


  $(window).on('scroll', function () {

  });

  $(window).on('resize', function () {
    windowWidth = $(window).width();
    windowHeight = $(window).height();

    defVals();
  });

});