(function() {
  var autoHeight, delay, end, getCaptcha, setCaptcha, size, spinOptions;

  spinOptions = {
    lines: 13,
    length: 21,
    width: 2,
    radius: 24,
    corners: 0,
    rotate: 0,
    direction: 1,
    color: '#0c4ed0',
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

  delay = function(ms, func) {
    return setTimeout(func, ms);
  };

  size = function() {
    if ($('.lookbook').elem('slider').length > 0) {
      $('.lookbook').elem('slider-preview').css({
        'top': $('.lookbook').elem('slider').offset().top,
        'opacity': 1,
        'width': ($(window).width() - $('.page .container').width()) / 2 + 2
      });
    }
    $('.filter').removeAttr('style').mod('loaded', false).css({
      minHeight: function() {
        return $(this).outerHeight();
      },
      maxHeight: function() {
        return $(this).outerHeight();
      }
    }).on(end, function() {
      return $(this).mod('loaded', true);
    });
  };

  autoHeight = function(el, selector, height_selector, use_padding, debug) {
    var count, heights, i, item, item_padding, items, loops, padding, step, x, _i, _ref, _results;
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
      _results = [];
      while (i < count) {
        items = {};
        for (x = _i = 0, _ref = step - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; x = 0 <= _ref ? ++_i : --_i) {
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
        _results.push(i += step);
      }
      return _results;
    }
  };

  getCaptcha = function() {
    return $.get('/include/captcha.php', function(data) {
      return setCaptcha(data);
    });
  };

  setCaptcha = function(code) {
    $('input[name=captcha_code]').val(code);
    return $('.captcha').css('background-image', "url(/include/captcha.php?captcha_sid=" + code + ")");
  };

  $(document).ready(function() {
    var closeDropdown, filterRequest, filterTimer, galleryOptions, getElem, getFilter, initFiltres, initProducts, openDropdown, scrollTimer, timer, x;
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
    scrollTimer = false;
    $(window).scroll(function() {
      clearTimeout(scrollTimer);
      if (!$('.scroll-fix').hasMod('on')) {
        $('.scroll-fix').mod('on', true);
      }
      return scrollTimer = delay(300, function() {
        return $('.scroll-fix').mod('on', false);
      });
    });
    $('.basket input[type="radio"]').iCheck();
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
    $('.news-item').elem('title').click(function(e) {
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
    getElem = function(fotorama, direction) {
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
    $('.lookbook').elem('slider').on('fotorama:show', function(e, fotorama, extra) {
      return $('.lookbook').elem('slider-preview').each(function() {
        var el;
        if ($(this).hasMod('next')) {
          el = getElem(fotorama, 'next');
        }
        if ($(this).hasMod('prev')) {
          el = getElem(fotorama, 'prev');
        }
        return $(this).css({
          'background-image': el.find('.lookbook__picture').css('background-image')
        });
      });
    }).on('fotorama:showend', function(e, fotorama, extra) {
      return delay(300, function() {
        return fotorama.resize({
          height: $(fotorama.activeFrame.html).height()
        });
      });
    }).fotorama();
    $('.about').elem('slider-arrow').click(function(e) {
      var slider;
      slider = $('.about').elem('slider').data('fotorama');
      slider.show($(this).data('direction'));
      return e.preventDefault();
    });
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
    galleryOptions = {
      history: false,
      focus: false,
      shareEl: false
    };
    $('.tabs').elem('trigger').click(function(e) {
      $('.tabs').elem('content').mod('active', false);
      $('.tabs').elem('trigger').mod('active', false);
      $(this).mod('active', true);
      $($(this).attr('href')).mod('active', true);
      return e.preventDefault();
    });
    $('.picture').elem('small').click(function(e) {
      $('.picture').elem('small').mod('active', false);
      $(this).mod('active', true);
      $('.picture').elem('big').css({
        backgroundImage: "url(" + ($(this).attr('href')) + ")"
      });
      return e.preventDefault();
    });
    $('.picture').elem('zoom').click(function(e) {
      $.prettyPhoto.open($(this).data('pictures'));
      return e.preventDefault();
    });
    initProducts = function() {
      $('.product').elem('icon').click(function(e) {
        var gallery, items, pswpElement;
        if ($(this).hasMod('zoom')) {
          pswpElement = document.querySelectorAll('.pswp')[0];
          items = $(this).data('pictures');
          gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, galleryOptions);
          gallery.init();
        }
        return e.preventDefault();
      });
      $('.product').elem('picture').lazyLoadXT();
      return $('.product').hoverIntent({
        sensitivity: 20,
        over: function() {
          $(this).mod('hover', true);
          return $(this).mod('index', true);
        },
        out: function() {
          var item;
          item = $(this);
          item.mod('hover', false);
          return $(this).find('.product__frame').one(end, function() {
            return item.mod('index', false);
          });
        }
      });
    };
    initFiltres = function() {
      $('.filter input.color').off('ifCreated').on('ifCreated', function() {
        return $(this).parents('.icheckbox_color').css('color', $(this).css('color'));
      });
      $('.filter input[type="radio"], .filter input[type="checkbox"]').off('ifChanged').on('ifChanged', function() {
        return getFilter($(this));
      });
      $('.filter input[type="radio"], .filter input[type="checkbox"]:not(.color)').iCheck();
      $('.filter input.color').iCheck({
        checkboxClass: 'icheckbox_color'
      });
      $('.filter').elem('title').click(function(e) {
        var block, content, height;
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
          height = block.css({
            minHeight: block.height() + content.height() + 16,
            maxHeight: block.outerHeight() + content.outerHeight() + 5
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
        var slider;
        if ((e.keyCode < 48 || e.keyCode > 57) && $.inArray(e.keyCode, [37, 38, 39, 40, 13, 27, 9, 8, 46]) === -1) {
          return false;
        }
        slider = $("input[name=range]").data("ionRangeSlider");
        if (parseInt($("input.range__from").val()) < slider.result.min) {
          $("input.range__from").val(slider.result.min);
        }
        if (parseInt($("input.range__to").val()) > slider.result.max) {
          $("input.range__to").val(slider.result.max);
        }
        return slider.update({
          from: parseInt($("input.range__from").val()),
          to: parseInt($("input.range__to").val())
        });
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
    initProducts();
    initFiltres();
    filterTimer = false;
    filterRequest = false;
    getFilter = function(el) {
      var inputs;
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
      el.parents('.filter').mod('loading', true);
      inputs = el.parents('form').find('input');
      return filterTimer = delay(300, function() {
        var data, values;
        if ($('.catalog').hasMod('ajax')) {
          data = el.parents('form').serialize() + "&short=Y&set_filter=Y";
          return filterRequest = $.ajax({
            type: "GET",
            url: ajaxURL,
            data: data,
            success: function(data) {
              History.pushState(null, document.title, ajaxURL + "?" + decodeURIComponent(el.parents('form').serialize()) + "&set_filter=Y");
              el.parents('.filter').mod('loading', false);
              if ($(data).filter('article').find('.pages').length > 0) {
                $('.pages').html($(data).filter('article').find('.pages').html());
              } else {
                $('.pages').html('');
              }
              $('.catalog__frame').html($(data).filter('article').find('.catalog__frame').html());
              initProducts();
              $('.page__side').html($(data).filter('article').find('.page__side').html());
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
            type: "POST",
            url: ajaxURL,
            data: values,
            success: function(data) {
              if (data) {
                data = $.parseJSON(data);
              }
              $('.catalog').elem('counter-value').text(data.ELEMENT_COUNT);
              $('.catalog').elem('counter').find('a').attr('href', data.FILTER_URL.replace(/&amp;/g, '&'));
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
          });
        }
      });
    };
    $('.brand-select .dropdown .dropdown__item').click(function(e) {
      if ($(this).data('id').length > 0) {
        $.cookie('BRAND', $(this).data('id'));
      } else {
        $.removeCookie('BRAND');
      }
      return window.location.reload();
    });
    timer = false;
    closeDropdown = function(x) {
      x.mod('open', false);
      return x.elem('frame').velocity({
        properties: "transition.slideUpOut",
        options: {
          duration: 300
        }
      });
    };
    openDropdown = function(x) {
      var text;
      clearTimeout(timer);
      text = x.elem('text').text();
      x.elem('item').show();
      x.elem('frame').find("a:contains(" + text + ")").hide();
      return x.elem('frame').velocity({
        properties: "transition.slideDownIn",
        options: {
          duration: 300,
          complete: function() {
            x.mod('open', true);
            return timer = delay(3000, function() {
              return closeDropdown(x);
            });
          }
        }
      });
    };
    $('.dropdown').elem('item').click(function(e) {
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
    $('.dropdown').elem('select').on('change', function() {
      var val;
      val = $(this).val();
      $(this).block().find("a[href='" + val + "']").trigger('click');
      return $(this).mod('open', true);
    });
    return $('.dropdown').hoverIntent({
      over: function() {
        if ($(window).width() > 970) {
          return openDropdown($(this));
        } else {
          return $(this).elem('select').focus().mod('open', true);
        }
      },
      out: function() {
        if ($(window).width() > 970) {
          return closeDropdown($(this));
        }
      }
    });
  });

}).call(this);
