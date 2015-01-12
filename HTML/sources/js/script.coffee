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
	$('.basket input.date').on 'keydown', (e)->
		e.preventDefault()
	$('.basket form .dropdown').elem('item').on 'click', (e)->
		$(this).block().siblings('input').val $(this).text()
	
	$('.basket .bx-ui-sls-fake').attr 'placeholder', 'город *'
	basketCalc = (el)->
		total  = 0
		sale   = 0
		options =
			separator   : "&nbsp;"
			useEasing   : true
			useGrouping : true
			separator   : ' '
			decimal     : ' '

		if $('.basket').elem('count').length == 0
			location.href = "/catalog/"
		$('.basket').elem('count').each ->
			if parseInt($(this).val()) <= 0 || !$(this).val()
				$(this).val(1)
			row = $(this).parents('.basket__item')
			total += parseInt($(this).data('price'))*$(this).val()
			sale  += parseInt(row.find('.sale').data('value'))*$(this).val()
		
		if el
			row  = el.parents('.basket__item')
			val  = parseInt(row.find('.basket__count').data('price')) * row.find('.basket__count').val()
			last = parseInt row.find('.total').text().replace(' ','')
			if val != last
				counter = new countUp row.find('.total')[0], last, val, 0, 2, options
				counter.start()

		saleVal = parseInt $('.basket__sale-total span').text().replace(' ','')
		if saleVal != sale
			saleCounter = new countUp $('.basket__sale-total span')[0], saleVal, sale, 0, 2, options
			saleCounter.start()

		totalVal = parseInt $('.basket__total span').text().replace(' ','')
		if totalVal != total
			totalCounter = new countUp $('.basket__total span')[0], totalVal, total, 0, 2, options
			totalCounter.start()

		#$('basket').elem('total').text total
	updateTimer = false

	$('.basket').elem('delete').click (e)->
		row  = $(this).parents('.basket__item')
		id   = $(this).data 'id'
		row.css
			maxHeight: 0
		url = "/include/basket.php?action=delete&id=#{id}"
		$.get url, (data)->
			if data == 'success'
				if bx_cart_block1
					bx_cart_block1.refreshCart({})
		row.on end , ->
			$(this).remove()
			basketCalc()
		e.preventDefault()

	$('.basket').elem('count').on 'keydown', (e)->
		if (e.keyCode < 48 || e.keyCode > 57) && $.inArray(e.keyCode, [37,38,39,40,13,27,9,8,46]) == -1
			return false
		clearTimeout updateTimer
		el = $(this)
		updateTimer = delay 400, ->
			id    = el.data 'id'
			count = el.val()
			url   = "/include/basket.php?action=update&id=#{id}&count=#{count}"
			basketCalc el
			$.get url


	# Order

	initOrder = ->
		$('.basket .delivery input[type="radio"], .basket .payment input[type="radio"]')
			.iCheck()
			.one 'ifChecked', ->
				getOrderDate()
		$('.stores-list .dropdown__item').off('click').on 'click', (e)->
			$(this).block().find('select').val $(this).data 'id'
			console.log $(this).block().find('select')
			$(this).block().find('.parsley-errors-list').removeClass '.filled'
			e.preventDefault()
		$('input[name="ORDER_PROP_3"]').mask '+7 (000) 000 00 00'
	
	$('.bx-ui-sls-clear').click ->
		getOrderDate()
	$('.bx-sls input:hidden:first').change ->
		if parseInt($(this).val()) > 0
			getOrderDate()
	isJson = (str)->
		try
			JSON.parse(str);
		catch e
			return false;
		return true;
	getOrderDate = ->
		data = $('#ORDER_FORM').serialize()
		$('.basket').elem('block').mod 'loading', true
		$('.basket').elem('submit').attr 'disabled', 'disabled'
		$.ajax
			type     : "POST" 
			url      : $('#ORDER_FORM').attr('action') 
			data     : data
			success  : (data)->
				if !isJson data
					$('#ORDER_FORM .props').html $(data).find('.props').html()
					$('#ORDER_FORM .delivery').html $(data).find('.delivery').html()
					$('#ORDER_FORM .payment').html $(data).find('.payment').html()
					initOrder()
					initDropdown()
					$('.basket').elem('block').mod 'loading', false
					$('.basket').elem('submit').removeAttr 'disabled'
				else
					data = $.parseJSON data
					if data.success == 'Y'
						location.href = data.redirect
	
	if $('body.basket').length > 0
		initOrder()
		getOrderDate()
	
	$('#ORDER_FORM').submit (e)->
		getOrderDate()
		e.preventDefault()
	$('.bx-ui-sls-quick-locations a:first').trigger 'click'
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

	window.styles = [{stylers:[{visibility:"on"},{saturation:-100},{lightness:30}]},{featureType:"administrative.country",elementType:"labels",stylers:[{weight:.1},{visibility:"off"},{color:"#ffffff"}]},{featureType:"administrative",elementType:"geometry",stylers:[{visibility:"on"},{weight:.4},{color:"#646464"}]},{featureType:"poi.school",stylers:[{visibility:"off"}]},{featureType:"road.highway",elementType:"geometry",stylers:[{color:"#ffffff"},{visibility:"simplified"}]},{featureType:"road.highway",elementType:"labels.text",stylers:[{weight:.1},{color:"#ffffff"},{visibility:"on"}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#ffffff"},{visibility:"simplified"}]},{featureType:"road.arterial",elementType:"labels",stylers:[{weight:.1},{color:"#ffffff"},{visibility:"on"}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#ffffff"}]},{featureType:"road.local",elementType:"labels",stylers:[{weight:.1},{color:"#ffffff"}]},{featureType:"transit.station",elementType:"labels.icon",stylers:[{hue:"#8800ff"},{visibility:"on"},{saturation:5}]},{featureType:"road.highway",elementType:"labels.icon",stylers:[{weight:.1},{saturation:11},{lightness:50},{visibility:"off"}]},{featureType:"administrative.locality",elementType:"labels.text",stylers:[{visibility:"off"}]},{featureType:"transit.station",elementType:"labels.text",stylers:[{visibility:"on"},{weight:.1},{color:"#323232"}]},{featureType:"transit.station.bus",elementType:"labels.icon",stylers:[{gamma:.72},{weight:.1},{saturation:77},{lightness:1},{hue:"#0099ff"}]},{featureType:"transit.station",elementType:"labels.text",stylers:[{visibility:"on"},{weight:.1},{color:"#3c3c3c"}]},{elementType:"labels.text.stroke",stylers:[{visibility:"on"},{weight:.1},{color:"#464646"}]},{featureType:"administrative.land_parcel",elementType:"labels.text",stylers:[{visibility:"on"},{color:"#3c3c3c"}]},{featureType:"water",elementType:"labels",stylers:[{visibility:"off"}]},{featureType:"water",elementType:"geometry.fill",stylers:[{visibility:"on"},{color:"#eeeeee"}]},{featureType:"road",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"administrative.country",elementType:"labels",stylers:[{visibility:"off"}]}]
	
	# Contacts

	if $('body.contacts').length > 0
	
		$.getScript 'http://maps.googleapis.com/maps/api/js?sensor=true&callback=contactsInit', ->
			window.contactsInit = ->
				center     = new google.maps.LatLng(55.83666078, 37.48988550);
				mapElement = document.getElementById('contactsMap');
				mapOptions = {zoom:14,draggable:true,zoomControl:true,zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE,position: google.maps.ControlPosition.LEFT_CENTER},scrollwheel:false,disableDoubleClickZoom:false,disableDefaultUI:true,center:center,styles:window.styles}
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
	# Stores

	if $('.stores').length > 0
		
		$('.stores').elem('content').spin spinOptions
		window.mapInit = ->
			center     = new google.maps.LatLng(51.1801, 71.44598 );
			mapOptions = {zoom:4,draggable:true,zoomControl:true,zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE,position: google.maps.ControlPosition.LEFT_CENTER},scrollwheel:false,disableDoubleClickZoom:false,disableDefaultUI:true,center:center,styles:window.styles}
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
				if i.code
					map.setCenter new google.maps.LatLng parseFloat(i.coords[0])-.00245, parseFloat(i.coords[1])
					map.setZoom 16
					
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
					icon      :
						url        : "/layout/images/store-#{i.type}.png"
						size       : new google.maps.Size(82,73),
						origin     : new google.maps.Point(0, 0),
						anchor     : new google.maps.Point(20, 0),
						scaledSize : new google.maps.Size(40, 35)
					animation : google.maps.Animation.DROP
				
				markers.push marker
				
				google.maps.event.addListener marker, 'click', ->
					openModal i
					
			markerCluster = new MarkerClusterer map, markers, { styles : clusterStyle, gridSize: 50, maxZoom: 13 }
			
			if window.currentStore
				currentStore = $.parseJSON window.currentStore
				openModal currentStore
			else if window.currentCity
				currentCity = $.parseJSON window.currentCity
				goToCity currentCity.name, currentCity.code
			$('.dropdown').elem('item').click (e)->
				goToCity $(this).text(), $(this).data('code')
				e.preventDefault()
		$.getScript 'http://maps.googleapis.com/maps/api/js?sensor=true&callback=mapInit'
		
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
			
			el = $(fotorama.data[fotorama.activeIndex].html).find('.lookbook__picture')
			if el.hasMod 'contain'
				$('.lookbook').elem('slider-preview').mod 'width', true
			else
				$('.lookbook').elem('slider-preview').mod 'width', false
			
			size()

			$('.lookbook').elem('slider-preview').each ->
				if $(this).hasMod 'next'
					el = getElem fotorama, 'prev'
				if $(this).hasMod 'prev'
					el = getElem fotorama, 'next'
				
				$(this).css
					'background-image' : el.find('.lookbook__picture').css 'background-image'

		)
		.on('fotorama:showend', (e, fotorama, extra)->
			delay 100, ->
				size()
				fotorama.resize
					height : $(fotorama.activeFrame.html).outerHeight()
		)
		.fotorama()

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

	# Product
	galleryOptions =     
		history : false
		focus   : false
		shareEl : false
	$('.product').elem('big-button').click (e)->
		if $(this).hasMod 'buy'
			id = $(this).data 'id'
			if $('.sizes').length > 0
				id = $('.sizes .dropdown').data 'id'
				param_size = $('.sizes .dropdown__text').text()
			url = "/include/basket.php?action=add&id=#{id}"
			if param_size
				url += "&size=#{param_size}"
			console.log(url)
			block = $('.picture')
			offset = block.offset()
			offset.top -= $('.header .cart').offset().top - block.height()/2
			offset.left -= $('.header .cart').offset().left - block.width()/2
			
			$(this).text('Товар в корзине').mod('border', true).mod('disabled', true)

			block.clone().prependTo(block).mod('absolute', true).velocity
				properties: 
					translateX : -offset.left
					translateY : -offset.top
					opacity    : .2
					scale      : .3
				options:
					duration: 500
					complete: ->
						el = $(this)
						delay 300, ->
							el.remove()
			
			$.get url, (data)->
				if data == 'success'
					bx_cart_block1.refreshCart({})
		
		e.preventDefault()

	initZoom = ->
		$('.picture').elem('big').easyZoom
			onShow: ->
				if $('.product').elem('description').height() > $('.easyzoom-flyout').height()
					$('.easyzoom-flyout')
						.height $('.product').elem('description').height()
	initZoom()

	$('.tabs__trigger:first').addClass 'tabs__trigger--active'
	$('.tabs__content:first').addClass 'tabs__content--active'
	
	$('.sizes .dropdown').elem('item').click (e)->
		$(this).block().data 'id', $(this).data 'id'
		$(this).block().data 'size', $(this).data 'size'

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
			backgroundImage : "url(#{$(this).data('middle')})"
		$('.picture').elem('big').data('easyZoom').swap $(this).data('middle'), $(this).attr('href')
		console.log $(this).data('middle'), $(this).attr('href')
		e.preventDefault()
	$('.picture').elem('zoom').click (e)->
			pswpElement = document.querySelectorAll('.pswp')[0];
			items = $(this).data('pictures')
			console.log items
			gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, galleryOptions);
			gallery.init();
			e.preventDefault()
	
	addToCart = (el)->
		id     = el.data 'id'
		block  = el.block()
		offset = block.offset()
		offset.top -= $('.header .cart').offset().top - block.height()/2
		offset.left -= $('.header .cart').offset().left - block.width()/2
		url    = "/include/basket.php?action=add&id=#{id}"
		if el.data('size')
			url += "&size=#{el.data('size')}"
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
			el = $(this).parents('.icheckbox_color')
			el.css( 'color', $(this).css('color') )
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
		
		el.parents('.filter').mod('loading', true) if el
		
		inputs = $('.page').elem('side').find('input')
		form   = $('.page').elem('side').find('form')
		sort   = [$('.catalog__toolbar .dropdown').data('param'), $('.catalog__toolbar .dropdown').data('value')]
		if sort.length > 0
			sort = "&sort_param=#{sort[0]}&sort_value=#{sort[1]}"
		
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
		x.elem('frame').find("a").each ->
			if $(this).text() == text
				$(this).hide()
		x.elem('frame').velocity
			properties: "transition.slideDownIn"
			options:
				duration: 300
				complete: ()->
					x.mod('open', true)
					#timer = delay 6000, ()->
						#closeDropdown x

	initDropdown = ->
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

	initDropdown()
	