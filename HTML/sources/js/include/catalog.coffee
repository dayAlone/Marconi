rangeTimer    = false
filterTimer   = false
filterRequest = false

fly = (block, target)->
	offset = block.offset()
	offset.top  -= target.offset().top - block.height()/2
	offset.left -= target.offset().left - block.width()/2
	block.clone().prependTo(block).mod('absolute', true).velocity
		properties: 
			translateX : -offset.left
			translateY : -offset.top
			opacity    : .2
			scale      : .3
		options:
			duration: 500
			complete: ->
				$(this).remove()

getSimmilar = (el, callbackOn = (-> return), callbackOff = (-> return)) ->

	block    = el.block()
	id       = el.data 'id'
	simmilar = $.cookie 'simmilar'	

	if !simmilar
		$.removeCookie 'simmilar', { path : "/" }
		simmilar = [] 
		simmilar.push(id)
	else
		simmilar = JSON.parse simmilar
		if $.inArray(id, simmilar) == -1
			simmilar.push(id)
		else
			simmilar.remByVal id	
	
	if $.inArray(id, simmilar) != -1
		callbackOn()
		el.text 'Удалить'
	else
		callbackOff()
		el.text 'Сравнить'

	if simmilar.length > 0
		$('.simmilar').elem('text').text "К сравнению: #{simmilar.length}"
		simmilar = JSON.stringify simmilar
		$.cookie 'simmilar', simmilar, { path:"/", expires: 7}
		$('.simmilar').attr 'href', '/catalog/compare.php'
		
	else
		$('.simmilar').elem('text').text "Товары не выбраны"
		$.removeCookie 'simmilar', { path : "/" }
		$('.simmilar').attr 'href', '#'
		
	
	return

addToCart = (el)->
	id     = el.data 'id'
	block  = el.block()
	
	url    = "/include/basket.php?action=add&id=#{id}"
	if el.data('size')
		url += "&size=#{el.data('size')}"
	fly block, $('.header .cart')
	
	
	$.get url, (data)->
		if data == 'success'
			bx_cart_block1.refreshCart({})

window.initProducts = ->
	$('.product').elem('icon').click (e)->
		if $(this).hasMod 'zoom'
			pswpElement = document.querySelectorAll('.pswp')[0];
			items = $(this).data('pictures')
			gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, galleryOptions);
			gallery.init();
		else if $(this).hasMod 'trigger'
			$(this).block('sizes').mod 'open', true
		else if $(this).hasMod 'cart'
			addToCart $(this)
			
		e.preventDefault()
	
	$('.product').elem('size').click (e)->
		$('.product').elem('size').mod 'active', false
		$(this).mod 'active', true
		e.preventDefault()

	$('.product').elem('button').click (e)->
		if $(this).hasMod 'cancel'
			$(this).block('sizes').mod 'open', false
			e.preventDefault()
		
		if $(this).hasMod 'simmilar'
			block = $(this).block()
			getSimmilar $(this), (->
				fly block, $('.header .simmilar')
				), ->
				if $('.catalog').hasMod 'simmilar'
					block.parent().remove()
			e.preventDefault()

		if $(this).hasMod 'buy'
			button = $(this)
			$(this).block('size').each ->
				if $(this).hasMod 'active'
					button.data 'id', $(this).data 'id'	
					button.data 'size', $(this).data 'size'	
			addToCart button
			e.preventDefault()
	$('.product').elem('picture').lazyLoadXT()
	
	$('.product').hoverIntent
			sensitivity: 40
			over : ()->
				$(this).mod 'hover', true
				$(this).mod 'index', true
			out : ()->
				item = $(this)
				item.mod 'hover', false
				$(this).block('sizes').mod 'open', false
				$(this).find('.product__frame').one end, ->
					item.mod 'index', false

checkRange = ->
	slider = $("input[name=range]").data("ionRangeSlider")
			
	if parseInt($("input.range__from").val()) < slider.result.min
		$("input.range__from").val slider.result.min

	if parseInt($("input.range__to").val()) > slider.result.max
		$("input.range__to").val slider.result.max

	slider.update
		from : parseInt $("input.range__from").val()
		to   : parseInt $("input.range__to").val()
	getFilter $("input.range__to")
initFiltres = ->
	# Checkbox
	$('.filter input.color').off('ifCreated').on 'ifCreated', ()->
		el = $(this).parents('.icheckbox_color')
		el.css( 'color', $(this).data('color') )
		color = $(this).data('color').replace('#','')
		el.addClass color
		$(this).addClass color
		delay 300, ->
			el.addClass('ready')
	
	$('.filter input[type="radio"], .filter input[type="checkbox"]').off('ifChanged').on 'ifChanged', ->
		getFilter($(this))
	
	$('.filter input[type="radio"], .filter input[type="checkbox"]:not(.color)').iCheck()
	$('.filter input.color').iCheck(checkboxClass: 'icheckbox_color')

	# Filter
	$('.filter').elem('title').click (e)->
		block   = $(this).block()
		content = block.elem('content')
		if block.hasMod 'open'
			height = block.outerHeight() - content.height()
			block.css
				maxHeight: height
			content.velocity
				properties: "transition.slideUpOut"
				options:
					duration: 300
					complete: ->
						block.mod 'open', false
						$.cookie(block.data('code'), 'N')
						block.css
							minHeight: 33

		else
			content.show()
			height = 
			block.css
				minHeight: block.height() + content.height() + 16
				maxHeight: block.outerHeight() + content.outerHeight() + 5
			content.velocity
				properties: "transition.slideDownIn"
				options:
					duration: 300
					complete: ->
						$.cookie(block.data('code'), 'Y')
						block.mod 'open', true

		e.preventDefault()	
	
	# Range
	$("input.range__from, input.range__to").on 'input', (e)->
		if (e.keyCode < 48 || e.keyCode > 57) && $.inArray(e.keyCode, [37,38,39,40,13,27,9,8,46]) == -1
			return false
		if e.keyCode == 13
			getFilter $("input.range__to")
			checkRange()
		else
			rangeTimer = delay 1000, ->
				checkRange()
			
	
	$(".filter__content input[name=range]").ionRangeSlider
		type: "double"
		onFinish: ->
			getFilter($("input.range__to"))
		onStart: (x)->
			$("input.range__from").val(x.from)
			$("input.range__to").val(x.to)
		onChange: (x)->
			$("input.range__from").val(x.from)
			$("input.range__to").val(x.to)

getFilter = (el)->
	if !$('.catalog').hasMod 'ajax'
		if $('.catalog').elem('counter').is ':visible'
			$('.catalog').elem('counter').velocity
				properties: "transition.slideUpOut"
				options:
					duration: 300
	
	filterRequest.abort() if filterRequest
	$('.filter').mod 'loading', false
	
	el.parents('.filter').mod('loading', true) if el
	
	inputs = $('.page').elem('side').find('input')
	form   = $('.page').elem('side').find('form')
	sort   = [$('.catalog__toolbar .dropdown').data('param'), $('.catalog__toolbar .dropdown').data('value')]
	if sort.length > 0
		sort = "&sort_param=#{sort[0]}&sort_value=#{sort[1]}"
	clearTimeout filterTimer
	filterTimer = delay 300, ->
		ajaxURL = form.data('url')
		if $('.catalog').hasMod 'ajax'
			data = form.serialize() + "&short=Y&set_filter=Y"
			data += sort
			filterRequest = $.ajax
				type     : "GET" 
				url      : ajaxURL 
				data     : data
				success  : (data)->
					el.parents('.filter').mod('loading', false) if el

					History.pushState(null, document.title, ajaxURL + "?" + decodeURIComponent(form.serialize()) + sort + "&set_filter=Y");
					
					if $(data).filter('article').find('.pages').length > 0
						$('.pages').html $(data).filter('article').find('.pages').html()
					else
						$('.pages').html('')
					
					$('.catalog__frame').html $(data).filter('article').find('.catalog__frame').html()
					initProducts()

					$('.page__side').html $(data).filter('article').find('.page__side').html()
					initFiltres()

					size()

					$(window).scrollTop $(window).scrollTop()+1
		else
			values = []
			values[0] = {name: 'ajax', value: 'y'}
			smartFilter.gatherInputsValues(values, inputs);
			filterRequest = $.ajax
				type     : "GET" 
				url      : ajaxURL
				data     : values
				success  : (data)->
					console.log data
					if data
						data = $.parseJSON(data)
					if data.FILTER_URL
						href = data.FILTER_URL.replace(/&amp;/g, '&')
						if sort.length > 0
							href += sort
						$('.catalog').elem('counter').find('a').attr 'href', href
						$('.catalog').elem('counter-value').text data.ELEMENT_COUNT

						if el
							el.parents('.filter').mod('loading', false)
							$('.catalog').elem('counter')
								.css(
									'top' : el.parents('.filter').position().top
								)
								.velocity
									properties: "transition.slideDownIn"
									options:
										duration: 300

getParameterByName = (name)->
	match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
	return match && decodeURIComponent(match[1].replace(/\+/g, ' '));

$(document).ready ->
	initProducts()
	initFiltres()

	$('.catalog').elem('per-page').click (e)->
		$.cookie('PER_PAGE', $(this).text(),{path:"/"})
		window.location.reload()
		e.preventDefault()

	$('.catalog__toolbar .dropdown .dropdown__item').click (e)->
		$(this).block().data 'value', $(this).data 'value'
		$(this).block().data 'param', $(this).data 'param'
		if $('.page').elem('side').find('form').length > 0
			getFilter()
		else
			if !getParameterByName('sort_param')
				location.href = location.href + "&sort_param=#{$(this).data('param')}&sort_value=#{$(this).data('value')}"
			else
				location.href = location.href.replace(getParameterByName('sort_param'), $(this).data('param')).replace(getParameterByName('sort_value'), $(this).data('value'))

	$('.brand-select .dropdown .dropdown__item').click (e)->
		if $(this).data('id').length > 0
			$.cookie('BRAND', $(this).data('id'),{path:"/"})
		else
			$.cookie('BRAND', null)
		
		window.location.reload()

	# Card
	$('a.catalog__card-button').click (e)->
		block  = $('.catalog__card')
		offset = block.offset()
		offset.top -= $('.header .cart').offset().top - block.height()/2
		offset.left -= $('.header .cart').offset().left - block.width()/2

		block.velocity
			properties: 
				translateX : -offset.left
				translateY : -offset.top
				opacity    : .2
				scale      : 0
			options:
				duration: 300
				complete: ->
					$(this).remove()
					$.cookie('card', 'Y',{path:"/", expires: 7})
		
		$('.catalog__card-frame, a.catalog__card-button, .catalog__card-text').css(
			opacity: 0
		).on end, ->
			$(this).remove()
		e.preventDefault()
	$('body').on 'mousewheel', (e)->
		if $(e.target).hasClass 'catalog__card-frame'
			e.preventDefault();
			e.stopPropagation();
	
	# Top button
	$('.catalog').elem('top').on('click', (e)->
		$('html, body').animate({'scrollTop' : 0 },300)
		e.preventDefault()
	).scrollToFixed
		marginTop: 20