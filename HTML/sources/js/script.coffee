spinOptions = 
	lines     : 13
	length    : 21
	width     : 2
	radius    : 24
	corners   : 0
	rotate    : 0
	direction : 1
	color     : '#0c4ed0'
	speed     : 1
	trail     : 68
	shadow    : false
	hwaccel   : false
	className : 'spinner'
	zIndex    : 2e9
	top       : '50%'
	left      : '50%'

end = 'transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd'

delay = (ms, func) -> setTimeout func, ms

size = ->
	$('.filter')
		.removeAttr('style')
		.mod('loaded', false)
		.css(
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

$(document).ready ->

	delay 300, ()->
		size()
		
	x = undefined
	$(window).resize ->
		clearTimeout(x)
		x = delay 200, ()->
			size()

	scrollTimer = false
	$(window).scroll ->
		clearTimeout scrollTimer
		if !$('.scroll-fix').hasMod 'on'
			$('.scroll-fix').mod 'on', true
		scrollTimer = delay 300, ()->
			$('.scroll-fix').mod 'on', false
	
	# Product

	$('.tabs').elem('trigger').click (e)->
		$('.tabs').elem('content').mod 'active', false
		$('.tabs').elem('trigger').mod 'active', false
		$(this).mod 'active', true
		$($(this).attr('href')).mod 'active', true
		e.preventDefault()

	$('.picture').elem('small').click (e)->
		
		$('.picture').elem('small').mod 'active', false
		$(this).mod 'active', true
		
		$('.picture').elem('big').css
			backgroundImage : "url(#{$(this).attr('href')})"
		
		e.preventDefault()

	$('.product').hoverIntent
			sensitivity: 20
			over : ()->
				$(this).mod 'hover', true
				$(this).mod 'index', true
			out : ()->
				item = $(this)
				item.mod 'hover', false
				$(this).find('.product__frame').one end, ->
					item.mod 'index', false

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
						block.mod 'open', true

		e.preventDefault()

	# Range

	$("input[name=range_from],input[name=range_to]").on 'input', (e)->
		if (e.keyCode < 48 || e.keyCode > 57) && $.inArray(e.keyCode, [37,38,39,40,13,27,9,8,46]) == -1
			return false
		slider = $("input[name=range]").data("ionRangeSlider")
		
		if parseInt($("input[name=range_from]").val()) < slider.result.min
			$("input[name=range_from]").val slider.result.min

		if parseInt($("input[name=range_to]").val()) > slider.result.max
			$("input[name=range_to]").val slider.result.max

		slider.update
			from : parseInt $("input[name=range_from]").val()
			to   : parseInt $("input[name=range_to]").val()

	$("input[name=range]").ionRangeSlider
		type: "double"
		onStart: (x)->
			$("input[name=range_from]").val(x.from)
			$("input[name=range_to]").val(x.to)
		onChange: (x)->
			$("input[name=range_from]").val(x.from)
			$("input[name=range_to]").val(x.to)


	# Dropdown

	timer = false

	closeDropdown = (x)->
			x.mod('open', false)
			x.elem('frame').velocity
				properties: "transition.slideUpOut"
				options:
					duration: 300

	openDropdown = (x)->
		clearTimeout timer
		text = x.elem('text').text()
		x.elem('item').show()
		x.elem('frame').find("a:contains(#{text})").hide()
		x.elem('frame').velocity
			properties: "transition.slideDownIn"
			options:
				duration: 300
				complete: ()->
					x.mod('open', true)
					timer = delay 3000, ()->
						closeDropdown x

	$('.dropdown').elem('item').click (e)->
		if $(this).attr('href')[0] == "#"
			$(this).block().elem('text').html($(this).text())
			$(this).block().elem('frame').velocity
					properties: "transition.slideUpOut"
					options:
						duration: 300
			e.preventDefault()
		else
			window.location.href = $(this).attr('href')

	$('.dropdown').elem('select').on 'change', ()->
		
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

	$('.filter input[type="radio"], .filter input[type="checkbox"]:not(.color)').iCheck()
	$('.filter input[name="color"]').on 'ifCreated', ()->
		$(this).parents('.icheckbox_color').css 'color', $(this).css('color')
	$('.filter input[name="color"]').iCheck(checkboxClass: 'icheckbox_color')
	