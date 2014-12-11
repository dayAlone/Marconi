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
    $('.filter').removeAttr('style').mod('loaded', false).css({
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
    var closeDropdown, openDropdown, scrollTimer, timer, x;
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
    $('.product').hoverIntent({
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
              return block.mod('open', true);
            }
          }
        });
      }
      return e.preventDefault();
    });
    $("input[name=range_from],input[name=range_to]").on('input', function(e) {
      var slider;
      if ((e.keyCode < 48 || e.keyCode > 57) && $.inArray(e.keyCode, [37, 38, 39, 40, 13, 27, 9, 8, 46]) === -1) {
        return false;
      }
      slider = $("input[name=range]").data("ionRangeSlider");
      if (parseInt($("input[name=range_from]").val()) < slider.result.min) {
        $("input[name=range_from]").val(slider.result.min);
      }
      if (parseInt($("input[name=range_to]").val()) > slider.result.max) {
        $("input[name=range_to]").val(slider.result.max);
      }
      return slider.update({
        from: parseInt($("input[name=range_from]").val()),
        to: parseInt($("input[name=range_to]").val())
      });
    });
    $("input[name=range]").ionRangeSlider({
      type: "double",
      onStart: function(x) {
        $("input[name=range_from]").val(x.from);
        return $("input[name=range_to]").val(x.to);
      },
      onChange: function(x) {
        $("input[name=range_from]").val(x.from);
        return $("input[name=range_to]").val(x.to);
      }
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
    $('.dropdown').hoverIntent({
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
    $('.filter input[type="radio"], .filter input[type="checkbox"]:not(.color)').iCheck();
    $('.filter input[name="color"]').on('ifCreated', function() {
      return $(this).parents('.icheckbox_color').css('color', $(this).css('color'));
    });
    return $('.filter input[name="color"]').iCheck({
      checkboxClass: 'icheckbox_color'
    });
  });

}).call(this);
