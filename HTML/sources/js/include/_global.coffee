$(document).ready ->
	
	window.ParsleyValidator.setLocale('ru');

	delay 300, ()->
		size()
		
	x = undefined
	$(window).resize ->
		clearTimeout(x)
		x = delay 200, ()->
			size()

	$('a.captcha_refresh').click (e)->
		getCaptcha()
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
		if id == 'register' || id == 'feedback'  || id == 'ask' 
			getCaptcha()

	$('.modal').on 'hidden.bs.modal', ->
		id = $(this).attr 'id'
		if $(".#{id}").elem('success')
			$(".#{id}").elem('success').hide().addClass 'hidden'
			$(".#{id}").elem('form').show().removeClass 'hidden'


	$('input[name*="WORK_PHONE"], input[name*="PERSONAL_MOBILE"], input[name*="PERSONAL_PHONE"], input[name="PERSONAL_PHONE"]').mask '+7 0000000000'

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

					if block == 'login' && $('body').hasClass 's2'
						location.href = location.href

	# Contacts
		
	$('#feedback form').submit (e)->
		e.preventDefault()
		data = $(this).serialize()
		$.post '/include/send.php', data,
	        (data) ->
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
		$.getScript 'http://maps.googleapis.com/maps/api/js?sensor=true&callback=contactsInit'+lang, ->
			window.contactsInit = ->
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
	
	# City selector
	openCityDropdown = ->
		$('.city').elem('dropdown').velocity
			properties: "transition.slideDownIn"
			options:
				duration: 400
	hideCityDropdown = ->
		$('.city').elem('dropdown').velocity
			properties: "transition.slideUpOut"
			options:
				duration: 400

	if !$.cookie('city')
		openCityDropdown()
	else if	$.cookie('city').length > 1
		$.cookie('city', 'Y', { path:"/", expires: 7 });

	$('.city').elem('trigger').click (e)->
		if $('.city').elem('dropdown').is ':visible'
			hideCityDropdown()
		else	
			openCityDropdown()
			$('.city').elem('message').hide()
			$('.city').elem('select').show()
			delay 300, ->
				$('.city').elem('select').find('.bx-ui-sls-fake').focus()
		e.preventDefault()

	$('.city input[name="place"]').on 'change', ->
		if $(this).val() > 0
			val = $('.city').elem('select').find('input[type="text"].bx-ui-sls-fake').val()
			$.cookie('city', val, { path:"/", expires: 7 });
			location.href = location.href
			hideCityDropdown()

	$('.city').elem('button').byMod('true').click (e)->
		$.cookie('city', 'Y', { path:"/", expires: 7 });
		hideCityDropdown()
		e.preventDefault()
	
	$('.city').elem('button').byMod('false').click (e)->
		$('.city').elem('message').hide()
		$('.city').elem('select').show()
		$('.city').elem('select').find('input[type="text"]:visible').focus()
		e.preventDefault()

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
	