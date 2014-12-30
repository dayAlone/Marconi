spinOptions = 
	lines     : 13
	length    : 21
	width     : 2
	radius    : 24
	corners   : 0
	rotate    : 0
	direction : 1
	color     : '#cf1237'
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
	if $('.lookbook').elem('slider').length > 0
		$('.lookbook').elem('slider-preview').css
			'top': $('.lookbook').elem('slider').offset().top
			'opacity': 1
			'width': ($(window).width()-$('.page .container').width())/2+2

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
	

	# Basket
	$('.basket input[type="radio"]').iCheck()
	# News
	$('.news-item').each ->
		h = $(this).outerHeight()
		$(this)
			.data 'height', h
		$(this).css
				maxHeight: ->
					return h
				minHeight: ->
					return h
	$('.news-item').elem('title').click (e)->
		trigger = $(this)
		block   = $(this).block()
		content = block.elem('content')
		trigger.mod 'disabled', true
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
						trigger.mod 'disabled', false
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
						trigger.mod 'disabled', false
		e.preventDefault()
	
	# Stores

	if $('.stores').length > 0
		
		$('.stores').elem('content').spin spinOptions

		$.getScript 'http://maps.googleapis.com/maps/api/js?sensor=true&callback=mapInit', ->
			window.mapInit = ->
				center     = new google.maps.LatLng(63.436317234268486, 67.10492205969675);
				mapOptions = {zoom:3,draggable:true,zoomControl:true,scrollwheel:false,disableDoubleClickZoom:false,disableDefaultUI:true,center:center,styles:[{featureType:"water",elementType:"geometry",stylers:[{color:"#000000"},{lightness:90}]},{featureType:"landscape",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:100}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{color:"#000000"},{lightness:90}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{color:"#000000"},{lightness:90},{weight:.2}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#000000"},{lightness:90}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#000000"},{lightness:90}]},{featureType:"poi",elementType:"geometry",stylers:[{color:"000000"},{lightness:50}]},{elementType:"labels.text.stroke",stylers:[{visibility:"off"},{color:"#000000"},{lightness:16}]},{elementType:"labels.text.fill",stylers:[{saturation:36},{color:"#000000"},{lightness:40}]},{elementType:"labels.icon",stylers:[{visibility:"on"}]},{featureType:"transit",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:19}]},{featureType:"administrative",elementType:"geometry.fill",stylers:[{color:"#ffffff"},{lightness:0}]},{featureType:"administrative",elementType:"geometry.stroke",stylers:[{color:"#000000"},{lightness:90},{weight:1.2}]}]}
				mapElement = document.getElementById('map');
				map        = new google.maps.Map(mapElement, mapOptions);
				geocoder   = new google.maps.Geocoder();
				items      = $.parseJSON window.items
				clusterStyle = [
					url       : '/layout/images/store-4.png',
					height    : 67,
					width     : 76,
					anchor    : [24, 0],
					textColor : '#ffffff',
					textSize  : 11
					backgroundPosition : "center center"
					backgroundSize : "contain; background-repeat: no-repeat"
				]
				markers    = [] 
				closeModal = ->
					$('.stores').elem('modal').velocity
						properties: "transition.slideDownOut"
						options:
							duration: 300
							complete: ->
								$('.stores').elem('content').html("")
								$('.stores').elem('content').spin spinOptions
				openModal  = (i)->
					if i.code.length > 0
						map.setCenter new google.maps.LatLng parseFloat(i.coords[0])-.00245, parseFloat(i.coords[1])
						map.setZoom 16
						
						console.log "/stores/#{i.code}/?short=y"

						$.get "/stores/#{i.code}/?short=y", (data)->
							$('.stores').elem('content').html(data)
							History.pushState(null, document.title, "/stores/#{i.code}/");
							$('html, body').animate({'scrollTop' : $('#map').offset().top + $('#map').height() },300)
							$('.stores').elem('modal').velocity
								properties: "transition.slideUpIn"
								options:
									duration: 300
						
						$('.stores').elem('close').one 'click', (e)->
							map.setCenter new google.maps.LatLng parseFloat(i.coords[0]), parseFloat(i.coords[1])
							closeModal()
							e.preventDefault()

				goToCity = (name, code)->
					closeModal()
					geocoder.geocode {'address': name}, (results, status)->
						if (status == google.maps.GeocoderStatus.OK)
							if (results)
								History.pushState(null, document.title, "/stores/#{code}/");
								map.setCenter results[0].geometry.location
								map.setZoom 10

				$.each items, (k, i)->
					
					marker = new google.maps.Marker
						position  : new google.maps.LatLng i.coords[0], i.coords[1]
						icon      : new google.maps.MarkerImage "/layout/images/store-#{i.type}.png", new google.maps.Size(152,134), null, null, new google.maps.Size(40,35)
						animation : google.maps.Animation.DROP
					
					markers.push marker
					
					google.maps.event.addListener marker, 'click', ->
						openModal i
						
				markerCluster = new MarkerClusterer map, markers, { styles : clusterStyle, gridSize: 1, maxZoom: 9 }
				
				if window.currentStore
					currentStore = $.parseJSON window.currentStore
					openModal currentStore
				else if window.currentCity
					currentCity = $.parseJSON window.currentCity
					goToCity currentCity.name, currentCity.code
				$('.dropdown').elem('item').click (e)->
					goToCity $(this).text(), $(this).data('code')
					e.preventDefault()
	# Lookbook

	$('.row.enter').isotope
		itemSelector : "[class*='col-']"
		masonry:
			columnWidth: $('.row.enter').width()/4
  
    
	$('.lookbook').elem('slider-preview').click (e)->
		slider = $('.lookbook').elem('slider').data('fotorama')
		slider.show $(this).data('direction')
		e.preventDefault()


	getElem = (fotorama, direction) ->
		if direction is "next"
			if fotorama.activeIndex is 0
				el = $(fotorama.data[fotorama.data.length - 1].html)
				i = fotorama.data[fotorama.data.length - 1].i
			else
				el = $(fotorama.data[fotorama.activeIndex - 1].html)
				i = fotorama.data[fotorama.activeIndex - 1].i
		if direction is "prev"
			if fotorama.activeIndex is fotorama.data.length - 1
				el = $(fotorama.data[0].html)
				i = fotorama.data[0].i
			else
				el = $(fotorama.data[fotorama.activeIndex + 1].html)
				i = fotorama.data[fotorama.activeIndex + 1].i
		return el
	  

	$('.lookbook').elem('slider')
		.on('fotorama:show', (e, fotorama, extra) ->
			$('.lookbook').elem('slider-preview').each ->
				if $(this).hasMod 'next'
					el = getElem fotorama, 'next'
				if $(this).hasMod 'prev'
					el = getElem fotorama, 'prev'

				$(this).css
					'background-image' : el.find('.lookbook__picture').css 'background-image'

		)
		.on('fotorama:showend', (e, fotorama, extra)->
			delay 300, ->
				size()
				fotorama.resize
					height : $(fotorama.activeFrame.html).outerHeight()
		)
		.fotorama()

	# About
	$('.about').elem('slider-arrow').click (e)->
		slider = $('.about').elem('slider').data('fotorama')
		slider.show $(this).data('direction')
		e.preventDefault()

	$('.about').elem('slider-title').each ->
		title = $(this)
		w = (title.width() - title.find('span').width() - 40) / 2
		el = $(this).find('.about__slider-title-before, .about__slider-title-after')
		el.css
			'width' : w
		el.show()
	# Product
	galleryOptions =     
		history : false
		focus   : false
		shareEl : false
    
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
	$('.picture').elem('zoom').click (e)->
			$.prettyPhoto.open $(this).data 'pictures'
			e.preventDefault()
		
	initProducts = ->
		$('.product').elem('icon').click (e)->
			if $(this).hasMod 'zoom'
				pswpElement = document.querySelectorAll('.pswp')[0];
				items = $(this).data('pictures')

				gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, galleryOptions);

				gallery.init();
			if $(this).hasMod 'trigger'
				$(this).block('sizes').mod 'open', true
			e.preventDefault()
		$('.product').elem('size').click (e)->
			$('.product').elem('size').mod 'active', false
			$(this).mod 'active', true
			e.preventDefault()

		$('.product').elem('button').click (e)->
			if $(this).hasMod 'cancel'
				$(this).block('sizes').mod 'open', false
				e.preventDefault()
		$('.product').elem('picture').lazyLoadXT()
		
		$('.product').hoverIntent
				sensitivity: 20
				over : ()->
					$(this).mod 'hover', true
					$(this).mod 'index', true
				out : ()->
					item = $(this)
					item.mod 'hover', false
					$(this).block('sizes').mod 'open', false
					$(this).find('.product__frame').one end, ->
						item.mod 'index', false

	initFiltres = ->
		# Checkbox
		$('.filter input.color').off('ifCreated').on 'ifCreated', ()->
			$(this).parents('.icheckbox_color').css 'color', $(this).css('color')
		
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
			slider = $("input[name=range]").data("ionRangeSlider")
			
			if parseInt($("input.range__from").val()) < slider.result.min
				$("input.range__from").val slider.result.min

			if parseInt($("input.range__to").val()) > slider.result.max
				$("input.range__to").val slider.result.max

			slider.update
				from : parseInt $("input.range__from").val()
				to   : parseInt $("input.range__to").val()
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
	
	initProducts()
	initFiltres()
	

	filterTimer   = false
	filterRequest = false
	getFilter = (el)->
		if !$('.catalog').hasMod 'ajax'
			if $('.catalog').elem('counter').is ':visible'
				$('.catalog').elem('counter').velocity
					properties: "transition.slideUpOut"
					options:
						duration: 300
		
		filterRequest.abort() if filterRequest
		$('.filter').mod 'loading', false
		el.parents('.filter').mod 'loading', true
		inputs = el.parents('form').find('input')
		filterTimer = delay 300, ->
			ajaxURL = $('.page').elem('side').find('form').attr('action')
			if $('.catalog').hasMod 'ajax'
				data = el.parents('form').serialize() + "&short=Y&set_filter=Y"
				filterRequest = $.ajax
					type     : "GET" 
					url      : ajaxURL 
					data     : data
					success  : (data)->
						console.log el.parents('form').serialize()
						History.pushState(null, document.title, ajaxURL + "?" + decodeURIComponent(el.parents('form').serialize()).replace("&short=Y", "") + "&set_filter=Y");
						el.parents('.filter').mod 'loading', false
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
					type     : "POST" 
					url      : ajaxURL
					data     : values
					success  : (data)->
						if data
							data = $.parseJSON(data)
						if data.FILTER_URL
							$('.catalog').elem('counter').find('a').attr 'href', data.FILTER_URL.replace(/&amp;/g, '&')
							el.parents('.filter').mod 'loading', false
							$('.catalog').elem('counter')
								.css(
									'top' : el.parents('.filter').position().top
								)
								.velocity
									properties: "transition.slideDownIn"
									options:
										duration: 300
	

	$('.brand-select .dropdown .dropdown__item').click (e)->
		if $(this).data('id') > 0
			$.cookie('BRAND', $(this).data('id'))
		else
			$.cookie('BRAND', null)

		window.location.reload()

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

	