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
		scrollTimer = delay 400, ()->
			$('.scroll-fix').mod 'on', false
	
	$('a.captcha_refresh').click (e)->
		getCaptcha()
		e.preventDefault()
	
	# Login
	
	$('.modal').on 'hidden.bs.modal', ->
		id = $(this).attr 'id'
		if $(".#{id}").elem('success')
			$(".#{id}").elem('success').hide().addClass 'hidden'
			$(".#{id}").elem('form').show().removeClass 'hidden'

	$('input[name="REGISTER[PERSONAL_PHONE]"], input[name="PERSONAL_PHONE"]').mask '+7 0000000000'

	$('#login form, #forget form, #register form, #change form').submit (e)->
		e.preventDefault()
		
		form  = $(this)
		modal = form.parents('.modal')
		block = modal.attr 'id'
		if block == 'register'
			$("input[name='REGISTER[EMAIL]']").val $("input[name='REGISTER[LOGIN]']").val()

		data  = $(this).serialize()
		if block == 'register'
			data += "&register_submit_button=Y"
		
		$.post form.data('action'), data,
			(data)->
				if data == "error"
					form.find('input[type="text"], input[type="password"]').addClass 'parsley-error'
				else if data == "success"
					if $(".#{block}").elem('success').length > 0
						$(".#{block}").elem('success').show().removeClass 'hidden'
						$(".#{block}").elem('form').hide().addClass 'hidden'
					else
						modal.modal('hide')
					if block != "forget"
						$('.auth').mod 'active', true
				else if  isJson data
					data = JSON.parse data
					getCaptcha()
					$.each data, (key, el)->
						$("input[name='REGISTER[#{el}]']").addClass 'parsley-error'

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
		
		$.getScript 'http://maps.googleapis.com/maps/api/js?sensor=true&callback=contactsInit', ->
			window.contactsInit = ->
				center     = new google.maps.LatLng(55.83666078, 37.48988550);
				mapElement = document.getElementById('contactsMap');
				mapOptions = {zoom:14,draggable:true,minZoom: 3, zoomControl:true,zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE,position: google.maps.ControlPosition.LEFT_CENTER},scrollwheel:true,disableDoubleClickZoom:false,disableDefaultUI:true,center:center,styles:window.styles}
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
	
	# About
	$('.about').elem('slider').on('fotorama:show', (e, fotorama, extra) ->
		item = $(fotorama.data[fotorama.activeIndex].html)
		if item.data('dark') == 'Y'
			$('.about').mod 'white', true
		else if $('.about').hasMod 'white'
			$('.about').mod 'white', false
		$('.about').elem('slider-arrow').off('click').on 'click', (e)->
			slider = $('.about').elem('slider').data('fotorama')
			slider.show $(this).data('direction')
			e.preventDefault()
	).fotorama()
	
	$('.about').elem('slider-title').each ->
		title = $(this)
		w = (title.width() - title.find('span').width() - 40) / 2
		el = $(this).find('.about__slider-title-before, .about__slider-title-after')
		el.css
			'width' : w
		el.show()

	window.initDropdown()
	