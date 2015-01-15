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
					$.cookie('card', 'Y',{path:"/"})
		
		$('.catalog__card-frame, a.catalog__card-button, .catalog__card-text').css(
			opacity: 0
		).on end, ->
			$(this).remove()
		e.preventDefault()
	$('body').on 'mousewheel', (e)->
		if $(e.target).hasClass 'catalog__card-frame'
			e.preventDefault();
			e.stopPropagation();
	
	
	# Contacts
	$('a.captcha_refresh').click (e)->
		getCaptcha()
		e.preventDefault()
	$('.feedback').elem('form').submit (e)->
		data = $(this).serialize()
		$.post '/include/send.php', data,
	        (data) ->
	        	data = $.parseJSON(data)
	        	if data.status == "ok"
	        		$('.feedback').elem('form').hide()
	        		$('.feedback').elem('success').show()
	        	else if data.status == "error"
	        		$('input[name=captcha_word]').addClass('parsley-error')
	        		getCaptcha()
		e.preventDefault()

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
		console.log 1
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

	initDropdown()
	