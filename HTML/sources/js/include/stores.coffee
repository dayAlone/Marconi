# Stores
@mapInit = ->
	center     = new google.maps.LatLng(51.1801, 71.44598 );
	mapOptions = {zoom:4,draggable:true,minZoom: 3, zoomControl:true,zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE,position: google.maps.ControlPosition.LEFT_CENTER},scrollwheel:true,disableDoubleClickZoom:false,disableDefaultUI:true,center:center,styles:window.styles}
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
		if i.code && !$.browser.mobile
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

	$('.dropdown').elem('select').change (e)->
		id = $(this).find('option:selected').data('id')
		$('.stores__list section').hide().removeClass 'active'
		$(this).block().elem('text').text $(this).find('option:selected').text()
		$(".stores__list section[data-id='#{id}']").velocity
					properties: "transition.slideDownIn"
					options:
						duration: 400
						complete: ->
							$(this).addClass('active')
		e.preventDefault()


@initStores = ->
	$('.stores').elem('content').spin spinOptions
	lang = ""
	if $('#map').data 'lang'
		lang = "&language="+$('#map').data 'lang'
	$.getScript 'http://maps.googleapis.com/maps/api/js?sensor=true&callback=mapInit'+lang