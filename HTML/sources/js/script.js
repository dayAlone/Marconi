(function() {
  var countUpOptions, end, filterRequest, filterTimer, galleryOptions, pointerEventsSupported, rangeTimer, spinOptions, timer, updateTimer,
    indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  this.delay = function(ms, func) {
    return setTimeout(func, ms);
  };

  this.size = function() {
    if ($('.lookbook').elem('slider').length > 0) {
      $('.lookbook').elem('slider-preview').css({
        'top': $('.lookbook').elem('slider').offset().top,
        'opacity': 1,
        'width': function() {
          var el, f, h, w, width;
          width = ($(window).width() - $('.page .container').width()) / 2;
          if ($(this).hasMod('width')) {
            f = $('.lookbook').elem('slider').data('fotorama');
            el = $(f.data[f.activeIndex].html).find('.lookbook__picture');
            w = el.data('width');
            h = el.data('height');
            width += ($('.lookbook').elem('picture').width() - ($('.lookbook').elem('picture').height() / h) * w) / 2;
          }
          return width;
        }
      });
    }
    $('.picture').elem('big').height(function() {
      return $('.product').elem('description').height();
    });
    $('.filter').removeAttr('style').mod('loaded', false).css({
      minHeight: function() {
        var ref;
        return (ref = $(this).outerHeight() < 33) != null ? ref : {
          33: $(this).outerHeight()
        };
      },
      maxHeight: function() {
        var ref;
        return (ref = $(this).outerHeight() < 33) != null ? ref : {
          33: $(this).outerHeight()
        };
      }
    }).on(end, function() {
      return $(this).mod('loaded', true);
    });
  };

  pointerEventsSupported = (function() {
    var documentElement, element, getComputedStyle, supports;
    element = document.createElement('x');
    documentElement = document.documentElement;
    getComputedStyle = window.getComputedStyle;
    if (!(indexOf.call(element.style, 'pointerEvents') >= 0)) {
      return false;
    }
    element.style.pointerEvents = 'auto';
    element.style.pointerEvents = 'x';
    documentElement.appendChild(element);
    supports = getComputedStyle && getComputedStyle(element, '').pointerEvents === 'auto';
    documentElement.removeChild(element);
    return !!supports;
  })();

  this.remByVal = function(val, array) {
    var i, j, ref;
    for (i = j = 0, ref = array.length; 0 <= ref ? j < ref : j > ref; i = 0 <= ref ? ++j : --j) {
      if (array[i] === val) {
        array.splice(i, 1);
        i--;
      }
    }
    return array;
  };

  this.isJson = function(str) {
    var e;
    try {
      JSON.parse(str);
    } catch (_error) {
      e = _error;
      return false;
    }
    return true;
  };

  this.autoHeight = function(el, selector, height_selector, use_padding, debug) {
    var count, heights, i, item, item_padding, items, j, loops, padding, ref, results1, step, x;
    if (selector == null) {
      selector = '';
    }
    if (height_selector == null) {
      height_selector = false;
    }
    if (use_padding == null) {
      use_padding = false;
    }
    if (debug == null) {
      debug = false;
    }
    if (el.length > 0) {
      item = el.find(selector);
      if (height_selector) {
        el.find(height_selector).removeAttr('style');
      } else {
        el.find(selector).removeAttr('style');
      }
      item_padding = item.css('padding-left').split('px')[0] * 2;
      padding = el.css('padding-left').split('px')[0] * 2;
      if (debug) {
        step = Math.round((el.width() - padding) / (item.width() + item_padding));
      } else {
        step = Math.round(el.width() / item.width());
      }
      count = item.length - 1;
      loops = Math.ceil(count / step);
      i = 0;
      if (debug) {
        console.log(count, step, item_padding, padding, el.width(), item.width());
      }
      results1 = [];
      while (i < count) {
        items = {};
        for (x = j = 0, ref = step - 1; 0 <= ref ? j <= ref : j >= ref; x = 0 <= ref ? ++j : --j) {
          if (item[i + x]) {
            items[x] = item[i + x];
          }
        }
        heights = [];
        $.each(items, function() {
          if (height_selector) {
            return heights.push($(this).find(height_selector).height());
          } else {
            return heights.push($(this).height());
          }
        });
        if (debug) {
          console.log(heights);
        }
        $.each(items, function() {
          if (height_selector) {
            return $(this).find(height_selector).height(Math.max.apply(Math, heights));
          } else {
            return $(this).height(Math.max.apply(Math, heights));
          }
        });
        results1.push(i += step);
      }
      return results1;
    }
  };

  this.getCaptcha = function() {
    return $.get('/include/captcha.php', function(data) {
      console.log(data);
      return setCaptcha(data);
    });
  };

  this.setCaptcha = function(code) {
    $('input[name=captcha_sid]').val(code);
    return $('.captcha').css('background-image', "url(/include/captcha.php?captcha_sid=" + code + ")");
  };

  timer = false;

  this.rgb2hex = function(rgb) {
    rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
    if (rgb && rgb.length === 4) {
      return ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) + ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) + ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2);
    } else {
      return false;
    }
  };

  this.openDropdown = function(x) {
    var text;
    clearTimeout(timer);
    text = x.elem('text').text();
    x.elem('item').show();
    x.elem('frame').find("a").each(function() {
      if ($(this).text() === text && $(this).parents('li').find('ul').length === 0) {
        return $(this).hide();
      }
    });
    return x.elem('frame').velocity({
      properties: "transition.slideDownIn",
      options: {
        duration: 300,
        complete: function() {
          return x.mod('open', true);
        }
      }
    });
  };

  this.closeDropdown = function(x) {
    x.mod('open', false);
    return x.elem('frame').velocity({
      properties: "transition.slideUpOut",
      options: {
        duration: 300
      }
    });
  };

  this.initDropdown = function() {
    $('.dropdown').elem('item').off('click').on('click', function(e) {
      if ($(this).attr('href')[0] === "#") {
        $(this).block().elem('text').html($(this).text());
        $(this).block().elem('frame').velocity({
          properties: "transition.slideUpOut",
          options: {
            duration: 300
          }
        });
        return e.preventDefault();
      } else {
        return window.location.href = $(this).attr('href');
      }
    });
    $('.dropdown').elem('trigger').on('click', function(e) {
      if ($.browser.mobile) {
        $(this).block().elem('select').focus().trigger('click').mod('open', true);
      }
      return e.preventDefault();
    });
    $('.dropdown').elem('select').off('change').on('change', function() {
      var val;
      val = $(this).val();
      $(this).block().find("a[href='" + val + "']").trigger('click');
      return $(this).block().mod('open', false);
    });
    return $('.dropdown').hoverIntent({
      over: function() {
        if (!$.browser.mobile) {
          return openDropdown($(this));
        } else {
          return $(this).elem('select').focus().trigger('click').mod('open', true);
        }
      },
      out: function() {
        if ($(window).width() > 970) {
          return closeDropdown($(this));
        }
      }
    });
  };

  $(document).ready(function() {
    var scrollTimer, x;
    delay(300, function() {
      return size();
    });
    x = void 0;
    $(window).resize(function() {
      clearTimeout(x);
      return x = delay(200, function() {
        return size();
      });
    });
    $('a.captcha_refresh').click(function(e) {
      getCaptcha();
      return e.preventDefault();
    });
    $('[data-toggle="tooltip"]').tooltip();
    if (pointerEventsSupported) {
      scrollTimer = false;
      $(window).scroll(function() {
        clearTimeout(scrollTimer);
        if (!$('.scroll-fix').hasMod('on')) {
          $('.scroll-fix').mod('on', true);
        }
        return scrollTimer = delay(400, function() {
          return $('.scroll-fix').mod('on', false);
        });
      });
    } else {
      $('.scroll-fix').remove();
    }
    if ($('body').hasClass('cabinet')) {
      $('input[type="radio"], input[type="checkbox"]:not(.color)').iCheck();
      $('.order').each(function() {
        var h;
        h = $(this).outerHeight();
        $(this).data('height', h);
        return $(this).css({
          maxHeight: function() {
            return h;
          },
          minHeight: function() {
            return h;
          }
        });
      });
      $('.stores-list .dropdown__frame').on('mousewheel', function(e) {
        if ($(this).scrollTop() === 0 && e.originalEvent.wheelDelta >= 0) {
          e.preventDefault();
          return e.stopPropagation();
        }
      });
      $('.order').elem('number').click(function(e) {
        var block, content, height, trigger;
        trigger = $(this);
        block = $(this).block();
        content = block.elem('content');
        block.mod('disabled', true);
        if (block.hasMod('open')) {
          height = block.data('height');
          block.css({
            minHeight: block.data('height'),
            maxHeight: block.data('height')
          });
          content.velocity({
            properties: "transition.slideUpOut",
            options: {
              duration: 1000,
              complete: function() {
                block.mod('open', false);
                return block.mod('disabled', false);
              }
            }
          });
        } else {
          content.show();
          block.css({
            minHeight: block.height() + content.height() + 16,
            maxHeight: block.outerHeight() + content.outerHeight() + 5
          });
          content.velocity({
            properties: "transition.slideDownIn",
            options: {
              duration: 1000,
              complete: function() {
                block.mod('open', true);
                return block.mod('disabled', false);
              }
            }
          });
        }
        return e.preventDefault();
      });
    }
    $('.modal').on('shown.bs.modal', function() {
      var id;
      id = $(this).attr('id');
      if (id === 'register' || id === 'feedback') {
        return getCaptcha();
      }
    });
    $('.modal').on('hidden.bs.modal', function() {
      var id;
      id = $(this).attr('id');
      if ($("." + id).elem('success')) {
        $("." + id).elem('success').hide().addClass('hidden');
        return $("." + id).elem('form').show().removeClass('hidden');
      }
    });
    $('input[name="REGISTER[PERSONAL_PHONE]"], input[name="PERSONAL_PHONE"]').mask('+7 0000000000');
    $('#login form, #forget form, #register form, #change form').submit(function(e) {
      var block, data, form, modal;
      e.preventDefault();
      form = $(this);
      modal = form.parents('.modal');
      block = modal.attr('id');
      if (block === 'register') {
        $("input[name='REGISTER[EMAIL]']").val($("input[name='REGISTER[LOGIN]']").val());
      }
      data = $(this).serialize();
      if (block === 'register') {
        data += "&register_submit_button=Y";
      }
      return $.post(form.data('action'), data, function(data) {
        if (data === "error") {
          return form.find('input[type="text"], input[type="password"]').addClass('parsley-error');
        } else if (data === "success") {
          if ($("." + block).elem('success').length > 0) {
            $("." + block).elem('success').show().removeClass('hidden');
            $("." + block).elem('form').hide().addClass('hidden');
          } else {
            modal.modal('hide');
          }
          if (block !== "forget") {
            return $('.auth').mod('active', true);
          }
        } else if (isJson(data)) {
          data = JSON.parse(data);
          getCaptcha();
          return $.each(data, function(key, el) {
            return $("input[name='REGISTER[" + el + "]']").addClass('parsley-error');
          });
        }
      });
    });
    $('#feedback form').submit(function(e) {
      var data;
      e.preventDefault();
      data = $(this).serialize();
      return $.post('/include/send.php', data, function(data) {
        data = $.parseJSON(data);
        if (data.status === "ok") {
          $('.feedback').elem('form').hide().addClass('hidden');
          return $('.feedback').elem('success').show().removeClass('hidden');
        } else if (data.status === "error") {
          $('input[name=captcha_word]').addClass('parsley-error');
          return getCaptcha();
        }
      });
    });
    if ($('body.contacts').length > 0) {
      $.getScript('http://maps.googleapis.com/maps/api/js?sensor=true&callback=contactsInit', function() {
        return window.contactsInit = function() {
          var center, map, mapElement, mapOptions, marker;
          center = new google.maps.LatLng(55.83666078, 37.48988550);
          mapElement = document.getElementById('contactsMap');
          mapOptions = {
            zoom: 14,
            draggable: true,
            minZoom: 3,
            zoomControl: true,
            zoomControlOptions: {
              style: google.maps.ZoomControlStyle.LARGE,
              position: google.maps.ControlPosition.LEFT_CENTER
            },
            scrollwheel: true,
            disableDoubleClickZoom: false,
            disableDefaultUI: true,
            center: center,
            styles: window.styles
          };
          map = new google.maps.Map(mapElement, mapOptions);
          return marker = new google.maps.Marker({
            position: center,
            map: map,
            icon: {
              url: "/layout/images/store-3.png",
              size: new google.maps.Size(82, 73),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(20, 0),
              scaledSize: new google.maps.Size(40, 35)
            },
            animation: google.maps.Animation.DROP
          });
        };
      });
    }
    $('.about').elem('slider').on('fotorama:show', function(e, fotorama, extra) {
      var item;
      item = $(fotorama.data[fotorama.activeIndex].html);
      if (item.data('dark') === 'Y') {
        $('.about').mod('white', true);
      } else if ($('.about').hasMod('white')) {
        $('.about').mod('white', false);
      }
      return $('.about').elem('slider-arrow').off('click').on('click', function(e) {
        var slider;
        slider = $('.about').elem('slider').data('fotorama');
        slider.show($(this).data('direction'));
        return e.preventDefault();
      });
    }).fotorama();
    $('.about').elem('slider-title').each(function() {
      var el, title, w;
      title = $(this);
      w = (title.width() - title.find('span').width() - 40) / 2;
      el = $(this).find('.about__slider-title-before, .about__slider-title-after');
      el.css({
        'width': w
      });
      return el.show();
    });
    return window.initDropdown();
  });

  countUpOptions = {
    separator: "&nbsp;",
    useEasing: true,
    useGrouping: true,
    separator: ' ',
    decimal: ' '
  };

  galleryOptions = {
    history: false,
    focus: false,
    shareEl: false
  };

  spinOptions = {
    lines: 13,
    length: 21,
    width: 2,
    radius: 24,
    corners: 0,
    rotate: 0,
    direction: 1,
    color: '#cf1237',
    speed: 1,
    trail: 68,
    shadow: false,
    hwaccel: false,
    className: 'spinner',
    zIndex: 2e9,
    top: '50%',
    left: '50%'
  };

  end = 'transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd';

  window.styles = [
    {
      stylers: [
        {
          visibility: "on"
        }, {
          saturation: -100
        }, {
          lightness: 30
        }
      ]
    }, {
      featureType: "administrative.country",
      elementType: "labels",
      stylers: [
        {
          weight: .1
        }, {
          visibility: "off"
        }, {
          color: "#ffffff"
        }
      ]
    }, {
      featureType: "administrative",
      elementType: "geometry",
      stylers: [
        {
          visibility: "on"
        }, {
          weight: .4
        }, {
          color: "#646464"
        }
      ]
    }, {
      featureType: "poi.school",
      stylers: [
        {
          visibility: "off"
        }
      ]
    }, {
      featureType: "road.highway",
      elementType: "geometry",
      stylers: [
        {
          color: "#ffffff"
        }, {
          visibility: "simplified"
        }
      ]
    }, {
      featureType: "road.highway",
      elementType: "labels.text",
      stylers: [
        {
          weight: .1
        }, {
          color: "#ffffff"
        }, {
          visibility: "on"
        }
      ]
    }, {
      featureType: "road.arterial",
      elementType: "geometry",
      stylers: [
        {
          color: "#ffffff"
        }, {
          visibility: "simplified"
        }
      ]
    }, {
      featureType: "road.arterial",
      elementType: "labels",
      stylers: [
        {
          weight: .1
        }, {
          color: "#ffffff"
        }, {
          visibility: "on"
        }
      ]
    }, {
      featureType: "road.local",
      elementType: "geometry",
      stylers: [
        {
          color: "#ffffff"
        }
      ]
    }, {
      featureType: "road.local",
      elementType: "labels",
      stylers: [
        {
          weight: .1
        }, {
          color: "#ffffff"
        }
      ]
    }, {
      featureType: "transit.station",
      elementType: "labels.icon",
      stylers: [
        {
          hue: "#8800ff"
        }, {
          visibility: "on"
        }, {
          saturation: 5
        }
      ]
    }, {
      featureType: "road.highway",
      elementType: "labels.icon",
      stylers: [
        {
          weight: .1
        }, {
          saturation: 11
        }, {
          lightness: 50
        }, {
          visibility: "off"
        }
      ]
    }, {
      featureType: "administrative.locality",
      elementType: "labels.text",
      stylers: [
        {
          visibility: "off"
        }
      ]
    }, {
      featureType: "transit.station",
      elementType: "labels.text",
      stylers: [
        {
          visibility: "on"
        }, {
          weight: .1
        }, {
          color: "#323232"
        }
      ]
    }, {
      featureType: "transit.station.bus",
      elementType: "labels.icon",
      stylers: [
        {
          gamma: .72
        }, {
          weight: .1
        }, {
          saturation: 77
        }, {
          lightness: 1
        }, {
          hue: "#0099ff"
        }
      ]
    }, {
      featureType: "transit.station",
      elementType: "labels.text",
      stylers: [
        {
          visibility: "on"
        }, {
          weight: .1
        }, {
          color: "#3c3c3c"
        }
      ]
    }, {
      elementType: "labels.text.stroke",
      stylers: [
        {
          visibility: "on"
        }, {
          weight: .1
        }, {
          color: "#464646"
        }
      ]
    }, {
      featureType: "administrative.land_parcel",
      elementType: "labels.text",
      stylers: [
        {
          visibility: "on"
        }, {
          color: "#3c3c3c"
        }
      ]
    }, {
      featureType: "water",
      elementType: "labels",
      stylers: [
        {
          visibility: "off"
        }
      ]
    }, {
      featureType: "water",
      elementType: "geometry.fill",
      stylers: [
        {
          visibility: "on"
        }, {
          color: "#eeeeee"
        }
      ]
    }, {
      featureType: "road",
      elementType: "labels.icon",
      stylers: [
        {
          visibility: "off"
        }
      ]
    }, {
      featureType: "administrative.country",
      elementType: "labels",
      stylers: [
        {
          visibility: "off"
        }
      ]
    }
  ];

  this.basketCalc = function(el) {
    var counter, last, row, sale, saleCounter, saleVal, total, totalCounter, totalVal, val;
    total = 0;
    sale = 0;
    if ($('.basket').elem('count').length === 0) {
      location.href = $('.catalog__back').attr('href');
    }
    $('.basket').elem('count').each(function() {
      var row;
      if (parseInt($(this).val()) <= 0 || !$(this).val()) {
        $(this).val(1);
      }
      row = $(this).parents('.basket__item');
      total += parseInt($(this).data('price')) * $(this).val();
      return sale += parseInt(row.find('.sale').data('value')) * $(this).val();
    });
    if (el) {
      row = el.parents('.basket__item');
      val = parseInt(row.find('.basket__count').data('price')) * row.find('.basket__count').val();
      last = parseInt(row.find('.total').text().replace(' ', ''));
      if (val !== last) {
        counter = new countUp(row.find('.total')[0], last, val, 0, 1, countUpOptions);
        counter.start();
      }
    }
    if ($('.basket__sale-total span:first').length > 0) {
      saleVal = parseInt($('.basket__sale-total span:first').text().replace(' ', ''));
      if (saleVal !== sale) {
        saleCounter = new countUp($('.basket__sale-total span:first')[0], saleVal, sale, 0, 1, countUpOptions);
        saleCounter.start();
      }
    }
    totalVal = parseInt($('.basket__total span:first').text().replace(' ', ''));
    if (totalVal !== total) {
      totalCounter = new countUp($('.basket__total span:first')[0], totalVal, total, 0, 2, countUpOptions);
      return totalCounter.start();
    }
  };

  updateTimer = false;

  this.basketInit = function() {
    $('.basket input.date').on('keydown', function(e) {
      return e.preventDefault();
    });
    $('.basket form .dropdown').elem('item').on('click', function(e) {
      return $(this).block().siblings('input').val($(this).text());
    });
    $('.basket .bx-ui-sls-fake').attr('placeholder', 'город *');
    $('.basket').elem('delete').click(function(e) {
      var id, row, url;
      row = $(this).parents('.basket__item');
      id = $(this).data('id');
      row.css({
        maxHeight: 0
      });
      url = "/include/basket.php?action=delete&id=" + id;
      $.get(url, function(data) {
        if (data === 'success') {
          return getOrderDate();
        }
      });
      row.on(end, function() {
        $(this).remove();
        return basketCalc();
      });
      return e.preventDefault();
    });
    return $('.basket').elem('count').on('keydown', function(e) {
      var el;
      if ((e.keyCode < 48 || e.keyCode > 57) && $.inArray(e.keyCode, [37, 38, 39, 40, 13, 27, 9, 8, 46]) === -1) {
        return false;
      }
      clearTimeout(updateTimer);
      el = $(this);
      return updateTimer = delay(400, function() {
        var count, id, url;
        id = el.data('id');
        count = el.val();
        url = "/include/basket.php?action=update&id=" + id + "&count=" + count;
        basketCalc(el);
        return $.get(url, function() {
          return getOrderDate();
        });
      });
    });
  };

  rangeTimer = false;

  filterTimer = false;

  filterRequest = false;

  this.fly = function(block, target) {
    var offset;
    offset = block.offset();
    offset.top -= target.offset().top - block.height() / 2;
    offset.left -= target.offset().left - block.width() / 2;
    return block.clone().prependTo(block).mod('absolute', true).velocity({
      properties: {
        translateX: -offset.left,
        translateY: -offset.top,
        opacity: .2,
        scale: .3
      },
      options: {
        duration: 500,
        complete: function() {
          return $(this).remove();
        }
      }
    });
  };

  this.getSimmilar = function(el, callbackOn, callbackOff) {
    var block, id, simmilar;
    if (callbackOn == null) {
      callbackOn = (function() {});
    }
    if (callbackOff == null) {
      callbackOff = (function() {});
    }
    block = el.block();
    id = el.data('id');
    simmilar = $.cookie('simmilar');
    if (!simmilar) {
      $.removeCookie('simmilar', {
        path: "/"
      });
      simmilar = [];
      simmilar.push(id);
    } else {
      simmilar = JSON.parse(simmilar);
      if ($.inArray(id, simmilar) === -1) {
        simmilar.push(id);
      } else {
        simmilar = remByVal(id, simmilar);
      }
    }
    if ($.inArray(id, simmilar) !== -1) {
      el.text('Удалить');
      callbackOn();
    } else {
      el.text('Сравнить');
      callbackOff();
    }
    console.log(simmilar);
    if (simmilar.length > 0) {
      $('.simmilar').elem('text').text("К сравнению: " + simmilar.length);
      simmilar = JSON.stringify(simmilar);
      $.cookie('simmilar', simmilar, {
        path: "/",
        expires: 7
      });
      $('.simmilar').attr('href', '/catalog/compare.php');
    } else {
      $('.simmilar').elem('text').text("Товары не выбраны");
      $.removeCookie('simmilar', {
        path: "/"
      });
      $('.simmilar').attr('href', '#');
    }
  };

  this.addToCart = function(el) {
    var block, id, url;
    id = el.data('id');
    block = el.block();
    url = "/include/basket.php?action=add&id=" + id;
    if (el.data('size')) {
      url += "&size=" + (el.data('size'));
    }
    if (el.data('artnumber')) {
      url += "&artnumber=" + (el.data('artnumber'));
    }
    fly(block, $('.header .cart'));
    return $.get(url, function(data) {
      if (data === 'success') {
        return bx_cart_block1.refreshCart({});
      }
    });
  };

  this.initProducts = function(images) {
    if (images == null) {
      images = true;
    }
    $('.product').elem('icon').off('click').on('click', function(e) {
      var gallery, items, pswpElement;
      if ($(this).hasMod('zoom')) {
        pswpElement = document.querySelectorAll('.pswp')[0];
        items = $(this).data('pictures');
        gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, galleryOptions);
        gallery.init();
      } else if ($(this).hasMod('trigger')) {
        $(this).block('sizes').mod('open', true);
      } else if ($(this).hasMod('cart')) {
        addToCart($(this));
      }
      return e.preventDefault();
    });
    $('.product').elem('size').off('click').on('click', function(e) {
      $('.product').elem('size').mod('active', false);
      $(this).mod('active', true);
      return e.preventDefault();
    });
    $('.product').elem('button').off('click').on('click', function(e) {
      var block, button;
      if ($(this).hasMod('cancel')) {
        $(this).block('sizes').mod('open', false);
        e.preventDefault();
      }
      if ($(this).hasMod('simmilar')) {
        block = $(this).block();
        getSimmilar($(this), (function() {
          return fly(block, $('.header .simmilar'));
        }), function() {
          if ($('.catalog').hasMod('simmilar')) {
            return block.parent().remove();
          }
        });
        e.preventDefault();
      }
      if ($(this).hasMod('buy')) {
        button = $(this);
        $(this).block('size').each(function() {
          if ($(this).hasMod('active')) {
            button.data('id', $(this).data('id'));
            button.data('size', $(this).data('size'));
            return button.data('artnumber', $(this).block().data('artnumber'));
          }
        });
        addToCart(button);
        return e.preventDefault();
      }
    });
    $('.product').hoverIntent({
      sensitivity: 40,
      over: function() {
        if ($(this).parents('.slick-slide').length > 0) {
          if ($(this).parents('.slick-active').length === 0) {
            return false;
          }
          if ($(this).elem('picture').data('bg')) {
            $(this).elem('picture').css({
              'background-image': "url(" + ($(this).elem('picture').data('bg')) + ")"
            });
          }
        }
        $(this).mod('hover', true);
        $(this).mod('index', true);
        if ($('body').hasClass('lookbook')) {
          return $('.lookbook').elem('slider-preview').mod('disabled', true);
        }
      },
      out: function() {
        var item;
        item = $(this);
        item.mod('hover', false);
        $(this).block('sizes').mod('open', false);
        $(this).find('.product__frame').one(end, function() {
          return item.mod('index', false);
        });
        if ($('body').hasClass('lookbook')) {
          return $('.lookbook').elem('slider-preview').mod('disabled', false);
        }
      }
    });
    if (images) {
      $('.product').elem('picture').lazyLoadXT();
    }
    if (!$.cookie('card')) {
      $('.catalog__card, .catalog__card-frame').removeClass('hidden');
      $('body').on('mousewheel', function(e) {
        if ($(e.target).hasClass('catalog__card-frame')) {
          e.preventDefault();
          return e.stopPropagation();
        }
      });
    }
    return $('a.catalog__card-button').off('click').on('click', function(e) {
      var block, offset;
      block = $('.catalog__card');
      offset = block.offset();
      offset.top -= $('.header .cart').offset().top - block.height() / 2;
      offset.left -= $('.header .cart').offset().left - block.width() / 2;
      block.velocity({
        properties: {
          translateX: -offset.left,
          translateY: -offset.top,
          opacity: .2,
          scale: 0
        },
        options: {
          duration: 300,
          complete: function() {
            $(this).remove();
            return $.cookie('card', 'Y', {
              path: "/",
              expires: 7
            });
          }
        }
      });
      $('.catalog__card-frame, a.catalog__card-button, .catalog__card-text').css({
        opacity: 0
      }).on(end, function() {
        return $(this).remove();
      });
      return e.preventDefault();
    });
  };

  this.checkRange = function() {
    var slider;
    slider = $("input[name=range]").data("ionRangeSlider");
    if (parseInt($("input.range__from").val()) < slider.result.min) {
      $("input.range__from").val(slider.result.min);
    }
    if (parseInt($("input.range__to").val()) > slider.result.max) {
      $("input.range__to").val(slider.result.max);
    }
    slider.update({
      from: parseInt($("input.range__from").val()),
      to: parseInt($("input.range__to").val())
    });
    return getFilter($("input.range__to"));
  };

  this.initFiltres = function() {
    if ($(window).width() <= 768) {
      $('.filter:not(.active)').each(function() {
        return $(this).mod('open', false);
      });
    }
    $('.icheckbox_color');
    $('.filter input.color').off('ifCreated').on('ifCreated', function() {
      var color, el;
      el = $(this).parents('.icheckbox_color');
      color = $(this).data('color').replace('#', '');
      el.css('color', $(this).data('color')).attr('title', $(this).attr('title')).addClass(color);
      $(this).addClass(color);
      return delay(300, function() {
        return el.addClass('ready');
      });
    });
    $('.filter input[type="radio"], .filter input[type="checkbox"]').off('ifChanged').on('ifChanged', function() {
      return getFilter($(this));
    });
    $('.filter input[type="radio"], .filter input[type="checkbox"]:not(.color)').iCheck();
    $('.filter input.color').iCheck({
      checkboxClass: 'icheckbox_color'
    });
    $('.filter').elem('title').click(function(e) {
      var block, content, height, maxHeight, minHeight;
      block = $(this).block();
      content = block.elem('content');
      if (block.hasMod('open')) {
        height = block.outerHeight() - content.height();
        block.css({
          maxHeight: height
        });
        content.velocity({
          properties: "transition.slideUpOut",
          options: {
            duration: 300,
            complete: function() {
              block.mod('open', false);
              $.cookie(block.data('code'), 'N');
              return block.css({
                minHeight: 33
              });
            }
          }
        });
      } else {
        content.show();
        minHeight = block.height();
        maxHeight = block.outerHeight() + content.outerHeight() + 5;
        block.css({
          minHeight: minHeight,
          maxHeight: maxHeight
        });
        content.velocity({
          properties: "transition.slideDownIn",
          options: {
            duration: 300,
            complete: function() {
              $.cookie(block.data('code'), 'Y');
              return block.mod('open', true);
            }
          }
        });
      }
      return e.preventDefault();
    });
    $("input.range__from, input.range__to").on('input', function(e) {
      if ((e.keyCode < 48 || e.keyCode > 57) && $.inArray(e.keyCode, [37, 38, 39, 40, 13, 27, 9, 8, 46]) === -1) {
        return false;
      }
      if (e.keyCode === 13) {
        getFilter($("input.range__to"));
        return checkRange();
      } else {
        return rangeTimer = delay(1000, function() {
          return checkRange();
        });
      }
    });
    return $(".filter__content input[name=range]").ionRangeSlider({
      type: "double",
      onFinish: function() {
        return getFilter($("input.range__to"));
      },
      onStart: function(x) {
        $("input.range__from").val(x.from);
        return $("input.range__to").val(x.to);
      },
      onChange: function(x) {
        $("input.range__from").val(x.from);
        return $("input.range__to").val(x.to);
      }
    });
  };

  this.getFilter = function(el) {
    var form, inputs, sort;
    if (!$('.catalog').hasMod('ajax')) {
      if ($('.catalog').elem('counter').is(':visible')) {
        $('.catalog').elem('counter').velocity({
          properties: "transition.slideUpOut",
          options: {
            duration: 300
          }
        });
      }
    }
    if (filterRequest) {
      filterRequest.abort();
    }
    $('.filter').mod('loading', false);
    if (el) {
      el.parents('.filter').mod('loading', true);
    }
    inputs = $('.page').elem('side').find('input');
    form = $('.page').elem('side').find('form');
    sort = [$('.catalog__toolbar .dropdown').data('param'), $('.catalog__toolbar .dropdown').data('value')];
    if (sort.length > 0) {
      sort = "&sort_param=" + sort[0] + "&sort_value=" + sort[1];
    }
    clearTimeout(filterTimer);
    return filterTimer = delay(300, function() {
      var ajaxURL, data, values;
      ajaxURL = form.data('url');
      if ($('.catalog').hasMod('ajax')) {
        data = form.serialize() + "&short=Y&set_filter=Y";
        data += sort;
        return filterRequest = $.ajax({
          type: "GET",
          url: ajaxURL,
          data: data,
          success: function(data) {
            if (el) {
              el.parents('.filter').mod('loading', false);
            }
            History.pushState(null, document.title, ajaxURL + "?" + decodeURIComponent(form.serialize()) + sort + "&set_filter=Y");
            if ($(data).filter('article').find('.pages').length > 0) {
              $('.pages').html($(data).filter('article').find('.pages').html());
            } else {
              $('.pages').html('');
            }
            $('.catalog__frame').html($(data).filter('article').find('.catalog__frame').html());
            initProducts();
            $('.filter__form').html($(data).filter('article').find('.filter__form').html());
            initFiltres();
            size();
            return $(window).scrollTop($(window).scrollTop() + 1);
          }
        });
      } else {
        values = [];
        values[0] = {
          name: 'ajax',
          value: 'y'
        };
        smartFilter.gatherInputsValues(values, inputs);
        return filterRequest = $.ajax({
          type: "GET",
          url: ajaxURL,
          data: values,
          success: function(data) {
            var href;
            console.log(data);
            if (data) {
              data = $.parseJSON(data);
            }
            if (data.FILTER_URL) {
              href = data.FILTER_URL.replace(/&amp;/g, '&');
              if (sort.length > 0) {
                href += sort;
              }
              $('.catalog').elem('counter').find('a').attr('href', href);
              $('.catalog').elem('counter-value').text(data.ELEMENT_COUNT);
              if (el) {
                el.parents('.filter').mod('loading', false);
                return $('.catalog').elem('counter').css({
                  'top': el.parents('.filter').position().top
                }).velocity({
                  properties: "transition.slideDownIn",
                  options: {
                    duration: 300
                  }
                });
              }
            }
          }
        });
      }
    });
  };

  this.eventBrandSelect = function(el) {
    var symbol;
    if (window.location.search.length === 0) {
      symbol = "?";
    } else {
      symbol = "&";
    }
    if (el.data('id').length > 0) {
      $.cookie('BRAND', el.data('id'), {
        path: "/"
      });
      if (!getParameterByName('brand')) {
        return location.href = location.href + symbol + ("brand=" + (el.data('id')));
      } else {
        return location.href = location.href.replace(getParameterByName('brand'), el.data('id'));
      }
    } else {
      $.removeCookie('BRAND', {
        path: "/"
      });
      if (!getParameterByName('brand')) {
        return location.href = location.href;
      } else {
        return location.href = location.href.replace("brand=" + getParameterByName('brand'), "");
      }
    }
  };

  this.initBrandSelect = function() {
    $('.brand-select .dropdown .dropdown__item').click(function(e) {
      return eventBrandSelect($(this));
    });
    return $('.brand-select .dropdown .dropdown__select').on('change', function(e) {
      $(this).block().mod('open', false);
      return eventBrandSelect($(this).find('option:selected'));
    });
  };

  this.getParameterByName = function(name) {
    var match;
    match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
  };

  this.checkSort = function(el) {
    $('.catalog__toolbar .dropdown').data('value', el.data('value'));
    $('.catalog__toolbar .dropdown').data('param', el.data('param'));
    if ($('.page').elem('side').find('form').length > 0) {
      return getFilter();
    } else {
      if (!getParameterByName('sort_param')) {
        return location.href = location.href + ("&sort_param=" + (el.data('param')) + "&sort_value=" + (el.data('value')));
      } else {
        return location.href = location.href.replace(getParameterByName('sort_param'), $(this).data('param')).replace(getParameterByName('sort_value'), $(this).data('value'));
      }
    }
  };

  this.initCatalog = function() {
    initProducts();
    initFiltres();
    initBrandSelect();
    $('.page').elem('side-trigger').click(function(e) {
      if (!$('.page').elem('side-trigger').hasMod('open')) {
        $('.page').elem('side').find('form').velocity({
          properties: "transition.slideDownIn",
          options: {
            duration: 300,
            complete: function() {
              return $('.page').elem('side-trigger').mod('open', true);
            }
          }
        });
      } else {
        $('.page').elem('side').find('form').velocity({
          properties: "transition.slideUpOut",
          options: {
            duration: 300,
            complete: function() {
              return $('.page').elem('side-trigger').mod('open', false);
            }
          }
        });
      }
      return e.preventDefault();
    });
    $('.catalog__toolbar .dropdown .dropdown__item').click(function(e) {
      return checkSort($(this));
    });
    $('.catalog__toolbar .dropdown .dropdown__select').on('change', function(e) {
      $(this).block().elem('text').html($(this).find('option:selected').text());
      return checkSort($(this).find('option:selected'));
    });
    $('.catalog').elem('per-page').click(function(e) {
      var symbol;
      if (window.location.search.length === 0) {
        symbol = "?";
      } else {
        symbol = "&";
      }
      $.cookie('PER_PAGE', $(this).text(), {
        path: "/"
      });
      if (!getParameterByName('per_page')) {
        location.href = location.href + symbol + ("per_page=" + ($(this).text()));
      } else {
        location.href = location.href.replace(getParameterByName('per_page'), $(this).text());
      }
      return e.preventDefault();
    });
    return $('.catalog').elem('top').on('click', function(e) {
      $('html, body').animate({
        'scrollTop': 0
      }, 300);
      return e.preventDefault();
    }).scrollToFixed({
      marginTop: 20
    });
  };

  this.getElem = function(fotorama, direction) {
    var el, i;
    if (direction === "next") {
      if (fotorama.activeIndex === 0) {
        el = $(fotorama.data[fotorama.data.length - 1].html);
        i = fotorama.data[fotorama.data.length - 1].i;
      } else {
        el = $(fotorama.data[fotorama.activeIndex - 1].html);
        i = fotorama.data[fotorama.activeIndex - 1].i;
      }
    }
    if (direction === "prev") {
      if (fotorama.activeIndex === fotorama.data.length - 1) {
        el = $(fotorama.data[0].html);
        i = fotorama.data[0].i;
      } else {
        el = $(fotorama.data[fotorama.activeIndex + 1].html);
        i = fotorama.data[fotorama.activeIndex + 1].i;
      }
    }
    return el;
  };

  this.initLookbook = function() {
    var firstScriptTag, next, prev, tag;
    tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    next = $('.lookbook__slider-preview--next').html();
    prev = $('.lookbook__slider-preview--prev').html();
    $('.row.enter').isotope({
      itemSelector: "[class*='col-']",
      masonry: {
        columnWidth: $('.row.enter').width() / 4
      }
    });
    $('.lookbook').elem('slider-preview').click(function(e) {
      var slider;
      slider = $('.lookbook').elem('slider').data('fotorama');
      slider.show($(this).data('direction'));
      return e.preventDefault();
    });
    return window.onYouTubeIframeAPIReady = function() {
      window.player = false;
      return $('.lookbook').elem('slider').on('fotorama:show', function(e, fotorama, extra) {
        var el, video;
        if (window.player) {
          player.destroy();
          window.player = false;
        }
        video = $(fotorama.data[fotorama.activeIndex].html).find('.lookbook__slider-video');
        if (video.length > 0) {
          window.player = new YT.Player(video.find('div:first').attr('id'), {
            videoId: video.data('id'),
            playerVars: {
              showinfo: 0
            },
            events: {
              onReady: function(e) {
                return e.target.playVideo();
              }
            }
          });
        }
        el = $(fotorama.data[fotorama.activeIndex].html).find('.lookbook__picture');
        if (el.hasMod('contain')) {
          $('.lookbook').elem('slider-preview').mod('width', true);
        } else {
          $('.lookbook').elem('slider-preview').mod('width', false);
        }
        size();
        return $('.lookbook').elem('slider-preview').each(function() {
          if ($(this).hasMod('next')) {
            el = getElem(fotorama, 'prev');
          }
          if ($(this).hasMod('prev')) {
            el = getElem(fotorama, 'next');
          }
          return $(this).css({
            'background-image': el.find('.lookbook__picture').css('background-image')
          });
        });
      }).on('fotorama:show', function(e, fotorama, extra) {
        var slider;
        slider = $(fotorama.data[fotorama.activeIndex].html).find('.catalog');
        if (slider.data('slick')) {
          return slider.slick('unslick');
        }
      }).on('fotorama:showend', function(e, fotorama, extra) {
        return delay(100, function() {
          var slider;
          slider = $(fotorama.data[fotorama.activeIndex].html).find('.catalog');
          if (slider) {
            slider.on('init', function(event, slick, direction) {
              slider.data('slick', true);
              return initProducts(false);
            }).slick({
              infinite: false,
              draggable: false,
              slidesToShow: 5,
              slidesToScroll: 1,
              nextArrow: "<button type=\"button\" class=\"slick-next\">" + next + "</button>",
              prevArrow: "<button type=\"button\" class=\"slick-prev\">" + prev + "</button>",
              responsive: [
                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 3
                  }
                }, {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 3
                  }
                }
              ]
            });
          }
          size();
          return fotorama.resize({
            height: $(fotorama.activeFrame.html).outerHeight()
          });
        });
      }).fotorama();
    };
  };

  this.initNews = function() {
    $('.news-item').each(function() {
      var h;
      h = $(this).outerHeight();
      $(this).data('height', h);
      return $(this).css({
        maxHeight: function() {
          return h;
        },
        minHeight: function() {
          return h;
        }
      });
    });
    return $('.news-item').elem('title').click(function(e) {
      var block, content, height, trigger;
      trigger = $(this);
      block = $(this).block();
      content = block.elem('content');
      trigger.mod('disabled', true);
      if (block.hasMod('open')) {
        height = block.data('height');
        block.css({
          minHeight: block.data('height'),
          maxHeight: block.data('height')
        });
        content.velocity({
          properties: "transition.slideUpOut",
          options: {
            duration: 1000,
            complete: function() {
              block.mod('open', false);
              return trigger.mod('disabled', false);
            }
          }
        });
      } else {
        content.show();
        block.css({
          minHeight: block.height() + content.height() + 16,
          maxHeight: block.outerHeight() + content.outerHeight() + 5
        });
        content.velocity({
          properties: "transition.slideDownIn",
          options: {
            duration: 1000,
            complete: function() {
              block.mod('open', true);
              return trigger.mod('disabled', false);
            }
          }
        });
      }
      return e.preventDefault();
    });
  };

  this.initOrder = function() {
    $('.basket .delivery input[type="radio"], .basket .payment input[type="radio"]').iCheck().one('ifChecked', function() {
      return getOrderDate();
    });
    $('.basket input[type="checkbox"]').iCheck();
    $('.stores-list .dropdown__item').off('click').on('click', function(e) {
      $(this).block().find('select').val($(this).data('id'));
      $(this).block().find('.parsley-errors-list').removeClass('.filled');
      return e.preventDefault();
    });
    return $('.time-select .dropdown__item').off('click').on('click', function(e) {
      $(this).block().find('input').val($(this).text());
      return e.preventDefault();
    });
  };

  this.getOrderDate = function(confirm) {
    var counter, data;
    data = $('#ORDER_FORM').serialize();
    $('.basket').elem('block').mod('loading', true);
    $('.basket').elem('submit').attr('disabled', 'disabled');
    if (confirm) {
      data += "&confirmorder=Y";
    }
    if ($('#register_user:not(:checked)').length > 0) {
      data += "&delete_user=Y";
    }
    counter = [];
    return $.ajax({
      type: "POST",
      url: $('#ORDER_FORM').attr('action'),
      data: data,
      success: function(data) {
        if (!isJson(data)) {
          $('#ORDER_FORM .props').html($(data).find('.props').html());
          $('#ORDER_FORM .delivery').html($(data).find('.delivery').html());
          $('#ORDER_FORM .payment').html($(data).find('.payment').html());
          $.each($(data).find('.total__counter'), function() {
            var current, id, old, parent, val;
            id = $(this).find('span:first-of-type').attr('id');
            parent = $(this).parents('.total__item');
            current = $("#" + id).parents('.total__item');
            if (parent.hasClass('hidden') && !current.hasClass('hidden')) {
              current.addClass('hidden');
            }
            if (!parent.hasClass('hidden') && current.hasClass('hidden')) {
              current.removeClass('hidden');
            }
            old = parseInt($("#" + id).text().replace(" ", ""));
            val = parseInt($(this).text().replace(" ", ""));
            if (old !== val) {
              counter[id] = new countUp(id, old, val, 0, 1, countUpOptions);
              counter[id].start();
            }
            return true;
          });
          initOrder();
          initDropdown();
          bx_cart_block1.refreshCart({});
          $('.basket').elem('block').mod('loading', false);
          return $('.basket').elem('submit').removeAttr('disabled');
        } else {
          data = $.parseJSON(data);
          if (data.success === 'Y') {
            return location.href = data.redirect;
          }
        }
      }
    });
  };

  this.initOrderPage = function() {
    initOrder();
    getOrderDate();
    $('.bx-sls input:hidden').on('change', function() {
      if (parseInt($(this).val()) > 0) {
        return getOrderDate();
      }
    });
    $('.bx-ui-sls-clear').click(function() {
      return getOrderDate();
    });
    return $('#ORDER_FORM').parsley().subscribe('parsley:form:validate', function(formInstance) {
      if (formInstance.isValid()) {
        getOrderDate(true);
      }
      return formInstance.submitEvent.preventDefault();
    });
  };

  this.initBigButton = function() {
    return $('.product').elem('big-button').off('click').on('click', function(e) {
      var id, param_size, url;
      if ($(this).hasMod('buy')) {
        id = $(this).data('id');
        if ($('.sizes').length > 0) {
          id = $('.sizes .dropdown').data('id');
          param_size = $('.sizes .dropdown__text').text();
        }
        url = "/include/basket.php?action=add&id=" + id;
        if (param_size) {
          url += "&size=" + param_size;
        }
        url += "&artnumber=" + ($(this).data('artnumber'));
        fly($('.picture'), $('.header .cart'));
        $(this).mod('border', true).mod('disabled', true).on(end, function() {
          return $(this).text('Товар в корзине');
        });
        $.get(url, function(data) {
          if (data === 'success') {
            return bx_cart_block1.refreshCart({});
          }
        });
      }
      if ($(this).hasMod('simmilar')) {
        getSimmilar($(this), function() {
          return fly($('.picture'), $('.header .simmilar'));
        });
        e.preventDefault();
      }
      if ($(this).parents('form').length === 0) {
        return e.preventDefault();
      }
    });
  };

  this.initProduct = function() {
    var initZoom;
    $('.breadcrumbs').elem('brand').click(function(e) {
      if ($(this).data('value').length > 0) {
        $.cookie('BRAND', $(this).data('value'), {
          path: "/"
        });
      } else {
        $.cookie('BRAND', null);
      }
      window.location = $(this).data('href');
      return e.preventDefault();
    });
    initBigButton();
    initZoom = function() {
      return $('.picture').elem('big').easyZoom({
        onShow: function(x) {
          var width;
          width = $('.product').elem('description').width();
          if ($('.picture__small').length === 0) {
            width += 40;
          }
          $('.easyzoom-flyout').width(width);
          if ($('.product').elem('description').height() > $('.easyzoom-flyout').height()) {
            return $('.easyzoom-flyout').height($('.product').elem('description').height());
          }
        }
      });
    };
    initZoom();
    initProducts();
    $('.tabs__trigger:first').addClass('tabs__trigger--active');
    $('.tabs__content:first').addClass('tabs__content--active');
    $('.sizes .dropdown__item').click(function(e) {
      var counter, el, last, val;
      $(this).block().data('id', $(this).data('id'));
      $(this).block().data('size', $(this).data('size'));
      if (parseInt($(this).data('price')) > 0) {
        el = $('.props__item--price strong');
        last = parseInt(el.text().replace(' ', ''));
        val = parseInt($(this).data('price'));
        if (last !== val) {
          counter = new countUp(el[0], last, val, 0, 1, countUpOptions);
          return counter.start();
        }
      }
    });
    $('.tabs').elem('trigger').click(function(e) {
      if (!$(this).data('toggle')) {
        $('.tabs').elem('content').mod('active', false);
        $('.tabs').elem('trigger').mod('active', false);
        $(this).mod('active', true);
        $($(this).attr('href')).mod('active', true);
      }
      return e.preventDefault();
    });
    $('.picture').elem('small').click(function(e) {
      $('.picture').elem('small').mod('active', false);
      $(this).mod('active', true);
      $('.picture').elem('big').css({
        backgroundImage: "url(" + ($(this).data('middle')) + ")"
      });
      $('.picture').elem('big').data('easyZoom').swap($(this).data('middle'), $(this).attr('href'));
      console.log($(this).data('middle'), $(this).attr('href'));
      return e.preventDefault();
    });
    return $('.picture').elem('zoom').click(function(e) {
      var gallery, items, pswpElement;
      pswpElement = document.querySelectorAll('.pswp')[0];
      items = $(this).data('pictures');
      console.log(items);
      gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, galleryOptions);
      gallery.init();
      return e.preventDefault();
    });
  };

  this.mapInit = function() {
    var center, closeModal, clusterStyle, currentCity, currentStore, geocoder, goToCity, items, map, mapElement, mapOptions, markerCluster, markers, openModal;
    center = new google.maps.LatLng(51.1801, 71.44598);
    mapOptions = {
      zoom: 4,
      draggable: true,
      minZoom: 3,
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.LARGE,
        position: google.maps.ControlPosition.LEFT_CENTER
      },
      scrollwheel: true,
      disableDoubleClickZoom: false,
      disableDefaultUI: true,
      center: center,
      styles: window.styles
    };
    mapElement = document.getElementById('map');
    map = new google.maps.Map(mapElement, mapOptions);
    geocoder = new google.maps.Geocoder();
    items = $.parseJSON(window.items);
    clusterStyle = [
      {
        url: '/layout/images/store-4.png',
        height: 67,
        width: 76,
        anchor: [24, 0],
        textColor: '#ffffff',
        textSize: 11,
        backgroundPosition: "center center",
        backgroundSize: "contain; background-repeat: no-repeat"
      }
    ];
    markers = [];
    closeModal = function() {
      return $('.stores').elem('modal').velocity({
        properties: "transition.slideDownOut",
        options: {
          duration: 300,
          complete: function() {
            $('.stores').elem('content').html("");
            return $('.stores').elem('content').spin(spinOptions);
          }
        }
      });
    };
    openModal = function(i) {
      if (i.code) {
        map.setCenter(new google.maps.LatLng(parseFloat(i.coords[0]) - .00245, parseFloat(i.coords[1])));
        map.setZoom(16);
        $.get("/stores/" + i.code + "/?short=y", function(data) {
          $('.stores').elem('content').html(data);
          History.pushState(null, document.title, "/stores/" + i.code + "/");
          $('html, body').animate({
            'scrollTop': $('#map').offset().top + $('#map').height()
          }, 300);
          return $('.stores').elem('modal').velocity({
            properties: "transition.slideUpIn",
            options: {
              duration: 300
            }
          });
        });
        return $('.stores').elem('close').one('click', function(e) {
          map.setCenter(new google.maps.LatLng(parseFloat(i.coords[0]), parseFloat(i.coords[1])));
          closeModal();
          return e.preventDefault();
        });
      }
    };
    goToCity = function(name, code) {
      closeModal();
      return geocoder.geocode({
        'address': name
      }, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
          if (results) {
            History.pushState(null, document.title, "/stores/" + code + "/");
            map.setCenter(results[0].geometry.location);
            return map.setZoom(10);
          }
        }
      });
    };
    $.each(items, function(k, i) {
      var marker;
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(i.coords[0], i.coords[1]),
        icon: {
          url: "/layout/images/store-" + i.type + ".png",
          size: new google.maps.Size(82, 73),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(20, 0),
          scaledSize: new google.maps.Size(40, 35)
        },
        animation: google.maps.Animation.DROP
      });
      markers.push(marker);
      return google.maps.event.addListener(marker, 'click', function() {
        return openModal(i);
      });
    });
    markerCluster = new MarkerClusterer(map, markers, {
      styles: clusterStyle,
      gridSize: 50,
      maxZoom: 13
    });
    if (window.currentStore) {
      currentStore = $.parseJSON(window.currentStore);
      openModal(currentStore);
    } else if (window.currentCity) {
      currentCity = $.parseJSON(window.currentCity);
      goToCity(currentCity.name, currentCity.code);
    }
    return $('.dropdown').elem('item').click(function(e) {
      goToCity($(this).text(), $(this).data('code'));
      return e.preventDefault();
    });
  };

  this.initStores = function() {
    $('.stores').elem('content').spin(spinOptions);
    return $.getScript('http://maps.googleapis.com/maps/api/js?sensor=true&callback=mapInit');
  };

}).call(this);
