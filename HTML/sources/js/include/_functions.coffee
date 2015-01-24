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

pointerEventsSupported = (->
	element          = document.createElement('x')
	documentElement  = document.documentElement
	getComputedStyle = window.getComputedStyle

	if(!('pointerEvents' in element.style))
		return false;

	element.style.pointerEvents = 'auto';
	element.style.pointerEvents = 'x';
	documentElement.appendChild(element);
	supports = getComputedStyle && 
	getComputedStyle(element, '').pointerEvents == 'auto';
	documentElement.removeChild(element);
	
	return !!supports;
)();

Array.prototype.remByVal = (val)->
	for i in [0...this.length]
        if this[i] == val
            this.splice(i, 1)
            i--;
    return this;

isJson = (str)->
	try
		JSON.parse(str);
	catch e
		return false;
	return true;

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
	$('input[name=captcha_sid]').val(code)
	$('.captcha').css 'background-image', "url(/include/captcha.php?captcha_sid=#{code})"

timer = false


rgb2hex = (rgb)->
	rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
	if (rgb && rgb.length == 4) 
		return ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) + ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) + ("0" + parseInt(rgb[3],10).toString(16)).slice(-2)
	else
		return false

@openDropdown = (x)->
	clearTimeout timer
	text = x.elem('text').text()
	x.elem('item').show()
	x.elem('frame').find("a").each ->
		if $(this).text() == text && $(this).parents('li').find('ul').length == 0
			$(this).hide()
	x.elem('frame').velocity
		properties: "transition.slideDownIn"
		options:
			duration: 300
			complete: ()->
				x.mod('open', true)
				#timer = delay 6000, ()->
					#closeDropdown x

@closeDropdown = (x)->
	x.mod('open', false)
	x.elem('frame').velocity
		properties: "transition.slideUpOut"
		options:
			duration: 300

@initDropdown = ->
	$('.dropdown').elem('item').off('change').on 'click', (e)->

		if $(this).attr('href')[0] == "#"
			$(this).block().elem('text').html($(this).text())
			$(this).block().elem('frame').velocity
					properties: "transition.slideUpOut"
					options:
						duration: 300
			e.preventDefault()
		else
			window.location.href = $(this).attr('href')

	$('.dropdown').elem('select').off('change').on 'change', ()->
		
		val = $(this).val()
		$(this).block().find("a[href='#{val}']").trigger 'click'
		$(this).mod 'open', true
		
	$('.dropdown').hoverIntent
			over : ()->
				if $(window).width() > 970
					openDropdown $(this)
				else
					$(this)
						.elem('select').focus()
						.mod 'open', true
			out : ()->
				if $(window).width() > 970
					closeDropdown $(this)