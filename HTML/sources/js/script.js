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

  delay = function(ms, func) {
    return setTimeout(func, ms);
  };

  size = function() {
    if ($('.lookbook').elem('slider').length > 0) {
      $('.lookbook').elem('slider-preview').css({
        'top': $('.lookbook').elem('slider').offset().top,
        'opacity': 1,
        'width': function() {
          var el, f, h, w, width;
          width = ($(window).width() - $('.page .container').width()) / 2 + 2;
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
    var addToCart, closeDropdown, filterRequest, filterTimer, galleryOptions, getElem, getFilter, initFiltres, openDropdown, scrollTimer, timer, x;
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
    if ($('.stores').length > 0) {
      $('.stores').elem('content').spin(spinOptions);
      $.getScript('http://maps.googleapis.com/maps/api/js?sensor=true&callback=mapInit', function() {
        return window.mapInit = function() {
          var center, closeModal, clusterStyle, currentCity, currentStore, geocoder, goToCity, items, map, mapElement, mapOptions, markerCluster, markers, openModal;
          center = new google.maps.LatLng(63.436317234268486, 67.10492205969675);
          mapOptions = {
            zoom: 3,
            draggable: true,
            zoomControl: true,
            zoomControlOptions: {
              style: google.maps.ZoomControlStyle.LARGE,
              position: google.maps.ControlPosition.LEFT_CENTER
            },
            scrollwheel: false,
            disableDoubleClickZoom: false,
            disableDefaultUI: true,
            center: center,
            styles: [
              {
                featureType: "water",
                elementType: "geometry",
                stylers: [
                  {
                    color: "#000000"
                  }, {
                    lightness: 90
                  }
                ]
              }, {
                featureType: "landscape",
                elementType: "geometry",
                stylers: [
                  {
                    color: "#ffffff"
                  }, {
                    lightness: 100
                  }
                ]
              }, {
                featureType: "road.highway",
                elementType: "geometry.fill",
                stylers: [
                  {
                    color: "#000000"
                  }, {
                    lightness: 90
                  }
                ]
              }, {
                featureType: "road.highway",
                elementType: "geometry.stroke",
                stylers: [
                  {
                    color: "#000000"
                  }, {
                    lightness: 90
                  }, {
                    weight: .2
                  }
                ]
              }, {
                featureType: "road.arterial",
                elementType: "geometry",
                stylers: [
                  {
                    color: "#000000"
                  }, {
                    lightness: 90
                  }
                ]
              }, {
                featureType: "road.local",
                elementType: "geometry",
                stylers: [
                  {
                    color: "#000000"
                  }, {
                    lightness: 90
                  }
                ]
              }, {
                featureType: "poi",
                elementType: "geometry",
                stylers: [
                  {
                    color: "000000"
                  }, {
                    lightness: 50
                  }
                ]
              }, {
                elementType: "labels.text.stroke",
                stylers: [
                  {
                    visibility: "off"
                  }, {
                    color: "#000000"
                  }, {
                    lightness: 16
                  }
                ]
              }, {
                elementType: "labels.text.fill",
                stylers: [
                  {
                    saturation: 36
                  }, {
                    color: "#000000"
                  }, {
                    lightness: 40
                  }
                ]
              }, {
                elementType: "labels.icon",
                stylers: [
                  {
                    visibility: "on"
                  }
                ]
              }, {
                featureType: "transit",
                elementType: "geometry",
                stylers: [
                  {
                    color: "#ffffff"
                  }, {
                    lightness: 19
                  }
                ]
              }, {
                featureType: "administrative",
                elementType: "geometry.fill",
                stylers: [
                  {
                    color: "#ffffff"
                  }, {
                    lightness: 0
                  }
                ]
              }, {
                featureType: "administrative",
                elementType: "geometry.stroke",
                stylers: [
                  {
                    color: "#000000"
                  }, {
                    lightness: 90
                  }, {
                    weight: 1.2
                  }
                ]
              }
            ]
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
      });
    }
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
      var el;
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
    }).on('fotorama:showend', function(e, fotorama, extra) {
      return delay(100, function() {
        size();
        return fotorama.resize({
          height: $(fotorama.activeFrame.html).outerHeight()
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
    $('.product').elem('big-button').click(function(e) {
      var block, id, offset, param_size, url;
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
        block = $('.picture');
        offset = block.offset();
        offset.top -= $('.header .cart').offset().top - block.height() / 2;
        offset.left -= $('.header .cart').offset().left - block.width() / 2;
        block.clone().prependTo(block).mod('absolute', true).velocity({
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
        $.get(url, function(data) {
          if (data === 'success') {
            return bx_cart_block1.refreshCart({});
          }
        });
      }
      return e.preventDefault();
    });
    $('.picture').elem('big').easyZoom({
      onShow: function() {
        if ($('.product').elem('description').height() > $('.easyzoom-flyout').height()) {
          return $('.easyzoom-flyout').height($('.product').elem('description').height());
        }
      }
    });
    $('.tabs__trigger:first').addClass('tabs__trigger--active');
    $('.tabs__content:first').addClass('tabs__content--active');
    $('.sizes .dropdown').elem('item').click(function(e) {
      $(this).block().data('id', $(this).data('id'));
      return $(this).block().data('size', $(this).data('size'));
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
    $('.picture').elem('zoom').click(function(e) {
      var gallery, items, pswpElement;
      pswpElement = document.querySelectorAll('.pswp')[0];
      items = $(this).data('pictures');
      console.log(items);
      gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, galleryOptions);
      gallery.init();
      return e.preventDefault();
    });
    addToCart = function(el) {
      var block, id, offset, url;
      id = el.data('id');
      block = el.block();
      offset = block.offset();
      offset.top -= $('.header .cart').offset().top - block.height() / 2;
      offset.left -= $('.header .cart').offset().left;
      url = "/include/basket.php?action=add&id=" + id;
      if (el.data('size')) {
        url += "&size=" + (el.data('size'));
      }
      block.clone().prependTo(block).mod('absolute', true).velocity({
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
      return $.get(url, function(data) {
        if (data === 'success') {
          return bx_cart_block1.refreshCart({});
        }
      });
    };
    window.initProducts = function() {
      $('.product').elem('icon').click(function(e) {
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
      $('.product').elem('size').click(function(e) {
        $('.product').elem('size').mod('active', false);
        $(this).mod('active', true);
        return e.preventDefault();
      });
      $('.product').elem('button').click(function(e) {
        var button;
        if ($(this).hasMod('cancel')) {
          $(this).block('sizes').mod('open', false);
          e.preventDefault();
        }
        if ($(this).hasMod('buy')) {
          button = $(this);
          $(this).block('size').each(function() {
            if ($(this).hasMod('active')) {
              button.data('id', $(this).data('id'));
              return button.data('size', $(this).data('size'));
            }
          });
          addToCart(button);
          return e.preventDefault();
        }
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
          $(this).block('sizes').mod('open', false);
          return $(this).find('.product__frame').one(end, function() {
            return item.mod('index', false);
          });
        }
      });
    };
    initFiltres = function() {
      $('.filter input.color').off('ifCreated').on('ifCreated', function() {
        var el;
        el = $(this).parents('.icheckbox_color');
        el.css('color', $(this).css('color'));
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
              console.log('loaded');
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
    $('.catalog__toolbar .dropdown .dropdown__item').click(function(e) {
      $(this).block().data('value', $(this).data('value'));
      $(this).block().data('param', $(this).data('param'));
      return getFilter();
    });
    $('.brand-select .dropdown .dropdown__item').click(function(e) {
      if ($(this).data('id').length > 0) {
        $.cookie('BRAND', $(this).data('id'));
      } else {
        $.cookie('BRAND', null);
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
      x.elem('frame').find("a").each(function() {
        if ($(this).text() === text) {
          return $(this).hide();
        }
      });
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
