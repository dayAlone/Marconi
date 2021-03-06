@delay = (ms, func) -> setTimeout func, ms

@posTitle = (title, text)->
	w = (title.width() - title.find('span').width() - 40) / 2
	el = title.find(text)
	el.css
		'width' : w
	el.show()

@size = ->
	if $('.lookbook').elem('slider').length > 0
		$('.lookbook').elem('slider-preview').css
			'top': $('.lookbook').elem('slider').position().top
			'opacity': 1
			'width': ->
				width = ($(window).width()-$('.page .container').width())/2
				if $(this).hasMod 'width'
					f  = $('.lookbook').elem('slider').data 'fotorama'
					el = $(f.data[f.activeIndex].html).find('.lookbook__picture')
					w  = el.data 'width'
					h  = el.data 'height'
					width += ($('.lookbook').elem('picture').width() - ($('.lookbook').elem('picture').height()/h)*w)/2
				return width

	if $('[data-draggable="true"]').length > 0
		$('[data-draggable="true"]').css
			'background-position-y': 0
			'background-position-x': ->
				return -(3500-$(window).width())/2




	$('.header .nav__line').width ($('.header .nav').width() - $('.header .nav__content').width())/2

	$('.picture').elem('big').height ->
		return $('.product').elem('description').height()

	if $('.pictures .picture__small').length > 5 && !$.browser.mobile
		next = $('.pictures__arrow--right').html()
		prev = $('.pictures__arrow--left').html()
		$('.pictures:not(.slick-initialized)').slick
			vertical: true
			slidesToShow: 5
			nextArrow      : "<button type=\"button\" class=\"slick-next\">#{next}</button>"
			prevArrow      : "<button type=\"button\" class=\"slick-prev\">#{prev}</button>"
	else if $.browser.mobile
		$('.pictures.slick-initialized').slick 'unslick'

	$('.filter')
		.removeAttr('style')
		.mod('loaded', false)
		.css(
			minHeight: ->
				($(this).outerHeight() < 33 ? 33 : $(this).outerHeight())
			maxHeight: ->
				($(this).outerHeight() < 33 ? 33 : $(this).outerHeight())
		)
		.on end, ->
			$(this).mod 'loaded', true
	return

@pointerEventsSupported = (->
	element = document.createElement('x');
	element.style.cssText = 'pointer-events:auto';
	return element.style.pointerEvents == 'auto';
)();

@remByVal = (val, array)->
	for i in [0...array.length]
        if array[i] == val
            array.splice(i, 1)
            i--;
    return array;

@isJson = (str)->
	try
		JSON.parse(str);
	catch e
		return false;
	return true;

@autoHeight = (el, selector='', height_selector = false, use_padding=false, debug=false)->
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

@getCaptcha = ()->
	$.get '/include/captcha.php', (data)->
		console.log data
		setCaptcha data

@setCaptcha = (code)->
	$('input[name=captcha_sid], input[name=captcha_code]').val(code)
	$('.captcha').css 'background-image', "url(/include/captcha.php?captcha_sid=#{code})"

timer = false


@rgb2hex = (rgb)->
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
	$('.dropdown').elem('item').off('click').on 'click', (e)->
		if $(this).attr('href')[0] == "#"
			$(this).block('select').val $(this).data 'id'
			$(this).block().elem('text').html($(this).text())
			$(this).block().elem('frame').velocity
					properties: "transition.slideUpOut"
					options:
						duration: 300
			e.preventDefault()
		else
			window.location.href = $(this).attr('href')

	$('.dropdown').elem('trigger').on 'click', (e)->
		if $.browser.mobile
			$(this).block()
				.elem('select').focus()
				.trigger('click')
				.mod 'open', true
		e.preventDefault()

	$('.dropdown').elem('select').off('change').on 'change', ()->
		val = $(this).val()
		$(this).block().find("a[href='#{val}']").trigger 'click'
		$(this).block().mod 'open', false
		$(this).block().elem('text').text $(this).find('option:selected').text()

	$('.dropdown input').on 'keydown', (e)->
		if e.keyCode == 13
			dropdown = $(this).parents '.dropdown'
			$(this).val dropdown.find('.dropdown__item.active:first').text()
			closeDropdown dropdown
			e.preventDefault()

	keyupTimer = false

	$('.dropdown input').on 'keyup', (e)->
		clearTimeout keyupTimer
		value = $(this).val()
		value = value.charAt(0).toUpperCase() + value.slice(1)
		dropdown = $(this).parents '.dropdown'
		if e.keyCode != 13 && value.length > 0
			keyupTimer = delay 300, ->
				dropdown.find(".dropdown__item" ).removeClass 'active'
				dropdown.find(".dropdown__item:not(:contains('#{value}'))" ).addClass 'hidden'
				dropdown.find(".dropdown__item:contains('#{value}')" ).removeClass 'hidden'
				dropdown.find(".dropdown__item:not('.hidden'):first" ).addClass 'active'

				if dropdown.find(".dropdown__item:not(.hidden)").length == 0
					closeDropdown dropdown
				else if !dropdown.hasMod 'open'
					openDropdown dropdown

	$('.dropdown').hoverIntent
			over : ()->
				if !$.browser.mobile && !$(this).hasClass 'metro-select'
					openDropdown $(this)
				else
					$(this)
						.elem('select').focus()
						.trigger('click')
						.mod 'open', true
			out : ()->
				if $(window).width() > 970
					closeDropdown $(this)

@initCitySelector = ->
	openCityDropdown = ->
		if $('.city').elem('dropdown').is ':hidden'
			$('.city').elem('dropdown').velocity
				properties: "transition.slideDownIn"
				options:
					duration: 400
	hideCityDropdown = ->
		if $('.city').elem('dropdown').is ':visible'
			$('.city').elem('dropdown').velocity
				properties: "transition.slideUpOut"
				options:
					duration: 400

	if !$.cookie('city')
		openCityDropdown()
	else if	$.cookie('city').length > 1
		$.cookie('city', 'Y', { path:"/", expires: 7 });

	$('.city').elem('trigger').off('click').on 'click', (e)->
		if $('.city').elem('dropdown').is ':visible'
			hideCityDropdown()
		else
			openCityDropdown()
			$('.city').elem('message').hide()
			$('.city').elem('select').show()
			delay 300, ->
				$('.city').elem('select').find('.bx-ui-sls-fake').focus()
		e.preventDefault()

	$('.city input[name="place"]').off('change').on 'change', ->
		if $(this).val() > 0
			val = JSON.stringify { 'id':$(this).val(), 'name': $('.city').elem('select').find('input[type="text"].bx-ui-sls-fake').val() }
			$.cookie('city', val, { path:"/", expires: 7 });
			location.href = location.href
			hideCityDropdown()

	$('.city').elem('button').byMod('true').off('click').on 'click', (e)->
		$.cookie('city', 'Y', { path:"/", expires: 7 });
		hideCityDropdown()
		e.preventDefault()

	$('.city').elem('button').byMod('false').off('click').on 'click', (e)->
		$('.city').elem('message').hide()
		$('.city').elem('select').show()
		$('.city').elem('select').find('input[type="text"]:visible').focus()
		e.preventDefault()

@contactsInit = ->
	center     = new google.maps.LatLng(55.83666078, 37.48988550);
	mapElement = document.getElementById('contactsMap');
	mapOptions = {zoom:14,draggable:true,minZoom: 3, zoomControl:true,zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE,position: google.maps.ControlPosition.LEFT_CENTER},scrollwheel:false,disableDoubleClickZoom:false,disableDefaultUI:true,center:center,styles:window.styles}
	map        = new google.maps.Map(mapElement, mapOptions);

	marker = new google.maps.Marker
		position  : center
		map       : map
		icon      :
			url        : "/layout/images/store-3.png"
			size       : new google.maps.Size(82,73),
			origin     : new google.maps.Point(0, 0),
			anchor     : new google.maps.Point(20, 0),
			scaledSize : new google.maps.Size(40, 35)
		animation : google.maps.Animation.DROP
