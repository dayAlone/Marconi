delay = (ms, func) -> setTimeout func, ms

size = ->
	if $('.lookbook').elem('slider').length > 0
		$('.lookbook').elem('slider-preview').css
			'top': $('.lookbook').elem('slider').offset().top
			'opacity': 1
			'width': ->
				width = ($(window).width()-$('.page .container').width())/2+2
				if $(this).hasMod 'width'
					f  = $('.lookbook').elem('slider').data 'fotorama'
					el = $(f.data[f.activeIndex].html).find('.lookbook__picture')
					w  = el.data 'width'
					h  = el.data 'height'
					width += ($('.lookbook').elem('picture').width() - ($('.lookbook').elem('picture').height()/h)*w)/2
				return width
	
	$('.picture').elem('big').height ->
		return $('.product').elem('description').height()

	$('.filter')
		.removeAttr('style')
		.mod('loaded', false)
		.css(
			minHeight: ->
				$(this).outerHeight()
			maxHeight: ->
				$(this).outerHeight()
		)
		.on end, ->
			$(this).mod 'loaded', true
	return

autoHeight = (el, selector='', height_selector = false, use_padding=false, debug=false)->
	if el.length > 0
		item = el.find(selector)

		if height_selector
			el.find(height_selector).removeAttr 'style'
		else
			el.find(selector).removeAttr 'style'
		
		item_padding = item.css('padding-left').split('px')[0]*2
		padding      = el.css('padding-left').split('px')[0]*2
		if debug
			step = Math.round((el.width()-padding)/(item.width()+item_padding))
		else
			step = Math.round(el.width()/item.width())
		
		count = item.length-1
		loops = Math.ceil(count/step)
		i     = 0
		
		if debug
			console.log count, step, item_padding, padding, el.width(), item.width()

		while i < count
			items = {}
			for x in [0..step-1]
				items[x] = item[i+x] if item[i+x]
			
			heights = []
			$.each items, ()->
				if height_selector
					heights.push($(this).find(height_selector).height())
				else
					heights.push($(this).height())
			
			if debug
				console.log heights

			$.each items, ()->
				if height_selector
					$(this).find(height_selector).height Math.max.apply(Math,heights)
				else
					$(this).height Math.max.apply(Math,heights)

			i += step

getCaptcha = ()->
	$.get '/include/captcha.php', (data)->
		setCaptcha data

setCaptcha = (code)->
	$('input[name=captcha_code]').val(code)
	$('.captcha').css 'background-image', "url(/include/captcha.php?captcha_sid=#{code})"