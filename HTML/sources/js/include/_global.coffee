$(document).ready ->

	if !pointerEventsSupported
		$('body').addClass 'nopointer'

	window.ParsleyValidator.setLocale('ru');

	delay 300, ()->
		size()

	x = undefined
	$(window).resize ->
		clearTimeout(x)
		x = delay 200, ()->
			size()

	$('.catalog__banner a').click (e)->
		url = $(this).attr('href')
		if $(url).hasClass 'modal'
			$(url).modal()
			e.preventDefault()

	$('.catalog__banner--slider, .catalog__banners').slick
		arrows: false
		infinite: true
		autoplay: true
		autoplaySpeed: 3000
		fade: true
		speed: 2000
		swipe: false

	$('a.captcha_refresh').click (e)->
		getCaptcha()
		e.preventDefault()

	$('a.toggle').click (e)->
		el = $("#{$(this).attr('href')}")

		if $(this).hasMod 'active'
			$(this).text 'Развернуть'
			$(this).mod 'active', false
			el.addClass 'hidden'
		else
			$(this).mod 'active', true
			$(this).text 'Свернуть'
			el.removeClass 'hidden'
		e.preventDefault()

	$('[data-toggle="tooltip"]').tooltip()
	$('[data-draggable="true"]').backgroundDraggable({ axis: 'x' });

	$('.toolbar a.search').click (e)->
		console.log $('div.search')
		$('div.search').parent().toggleClass 'hidden-xs'
		e.preventDefault()

	# pointerEvents

	if pointerEventsSupported
		scrollTimer = false
		$(window).scroll ->
			clearTimeout scrollTimer
			if !$('.scroll-fix').hasMod 'on'
				$('.scroll-fix').mod 'on', true
			scrollTimer = delay 400, ()->
				$('.scroll-fix').mod 'on', false
	else
		$('.scroll-fix').remove()

	# Profile

	if $('body').hasClass 'cabinet'
		$('input[type="radio"], input[type="checkbox"]:not(.color)').iCheck()
		$('.order').each ->
			h = $(this).outerHeight()
			$(this)
				.data 'height', h
			$(this).css
					maxHeight: ->
						return h
					minHeight: ->
						return h
		$('.stores-list .dropdown__frame').on 'mousewheel', (e)->
			if $(this).scrollTop() == 0 && e.originalEvent.wheelDelta >= 0
				e.preventDefault();
				e.stopPropagation();
		$('.order').elem('number').click (e)->
			trigger = $(this)
			block   = $(this).block()
			content = block.elem('content')
			block.mod 'disabled', true

			if block.hasMod 'open'
				height = block.data 'height'
				block.css
					minHeight: block.data('height')
					maxHeight: block.data('height')
				content.velocity
					properties: "transition.slideUpOut"
					options:
						duration: 1000
						complete: ->
							block.mod 'open', false
							block.mod 'disabled', false
			else
				content.show()
				block.css
					minHeight: block.height() + content.height() + 16
					maxHeight: block.outerHeight() + content.outerHeight() + 5
				content.velocity
					properties: "transition.slideDownIn"
					options:
						duration: 1000
						complete: ->
							block.mod 'open', true
							block.mod 'disabled', false
			e.preventDefault()

	# Achivements

	$('#achievement').on 'show.bs.modal', (e)->
		$el = $(e.relatedTarget)
		$('#achievement img').attr 'src', $el.data('image')
		$('#achievement p').html $.parseHTML($el.data('text'))

	# Login

	$('.modal').on 'shown.bs.modal', ->
		id = $(this).attr 'id'
		if id == 'register' || id == 'feedback'  || id == 'ask' || id == 'review'
			getCaptcha()

	$('.modal').on 'hidden.bs.modal', ->
		id = $(this).attr 'id'
		if $(".#{id}").elem('success')
			$(".#{id}").elem('success').hide().addClass 'hidden'
			$(".#{id}").elem('form').show().removeClass 'hidden'


	$('.s1 input[name*="WORK_PHONE"], .s1 input[name*="PERSONAL_MOBILE"], .s1 input[name*="PERSONAL_PHONE"], .s1 input[name="PERSONAL_PHONE"]').mask '+7 0000000000'

	$('#login form, #forget form, #register form, #change form').submit (e)->
		e.preventDefault()

		form  = $(this)
		modal = form.parents('.modal')
		if modal.length == 0
			modal = form.parent()

		block = modal.attr 'id'

		if block == 'register'
			$("input[name='REGISTER[EMAIL]']").val $("input[name='REGISTER[LOGIN]']").val()

		data  = $(this).serialize()
		if block == 'register'
			data += "&register_submit_button=Y"

		form.find('input[type="submit"]').attr('disabled', 'disabled')

		$.post form.data('action'), data,
			(data)->
				console.log data
				form.find('input[type="submit"]').removeAttr 'disabled'

				if data == "error"
					form.find('input[type="text"], input[type="password"]').addClass 'parsley-error'
				else if isJson data
					data = JSON.parse data
					getCaptcha()
					$.each data, (key, el)->
						$("input[name*='#{key}']").addClass 'parsley-error'
				else
					if $(".#{block}").elem('success').length > 0
						$(".#{block}").elem('success').show().removeClass 'hidden'
						$(".#{block}").elem('form').hide().addClass 'hidden'
					else
						modal.modal('hide')

					if block != "forget"
						$('.auth').mod 'active', true
						$(".toolbar__mobile a[href='#login']").attr 'href', '/profile/'

					if block == 'login' && $('body').hasClass 's2' || location.href == '/basket/'
						if location.href.indexOf('?') >= 0
							amp = "&"
						else
							amp = "?"
						location.href = location.href + amp + "login=yes"
	#Hello
	if !$.cookie('hello') && $('.hello').length > 0
		$('.hello').removeClass 'hidden'
		$('body').on 'mousewheel', (e)->
			if $(e.target).hasClass 'hello'
				e.preventDefault();
				e.stopPropagation();
		$('.hello').elem('button').click (e)->
			block = $(this).block()
			block.velocity
				properties:
					opacity    : .2
				options:
					duration: 300
					complete: ->
						$(this).remove();
						$.cookie('hello', 'Y', { path:"/", expires: .5 });
			e.preventDefault()
	if !$('.auth').hasMod 'active' && $.cookie('hello')
		$.removeCookie 'hello', { path:"/" }
	# Contacts

	$('#feedback form, #ask form, #review form').submit (e)->
		e.preventDefault()
		request = $(this).serialize()
		$.post '/include/send.php', request, (data) ->
			console.log data
			data = $.parseJSON(data)
			if data.status == "ok"
        		$('.feedback').elem('form').hide().addClass 'hidden'
        		$('.feedback').elem('success').show().removeClass 'hidden'
        	else if data.status == "error"
        		$('input[name=captcha_word]').addClass('parsley-error')
        		getCaptcha()


	if $('body.contacts').length > 0
		lang = ""
		if $('#contactsMap').data 'lang'
			lang = "&language="+$('#contactsMap').data 'lang'

		$.getScript 'https://maps.googleapis.com/maps/api/js?sensor=true&callback=contactsInit'+lang


	# About
	$('.about').elem('slider').on('fotorama:show', (e, fotorama, extra) ->
		item = $(fotorama.data[fotorama.activeIndex].html)
		if item.data('dark') == 'Y'
			$('.about').mod 'white', true
		else if $('.about').hasMod 'white'
			$('.about').mod 'white', false
		$('.about').elem('slider-arrow').off('click touchstart').on 'click touchstart', (e)->
			slider = $('.about').elem('slider').data('fotorama')
			slider.show $(this).data('direction')
			e.preventDefault()
	)
	.on('fotorama:showend', (e, fotorama, extra)->
		if $.browser.mobile == true
			h = $(fotorama.data[fotorama.activeIndex].html).find('.about__slider-item-content').height() + 200
			fotorama.resize
				height: h

		$('.about').elem('slider-sub-title').each ->
			posTitle $(this), '.about__slider-sub-title-before, .about__slider-sub-title-after'

		$('.about').elem('slider-title').each ->
			posTitle $(this), '.about__slider-title-before, .about__slider-title-after'

	)
	.fotorama()



	window.initDropdown()
