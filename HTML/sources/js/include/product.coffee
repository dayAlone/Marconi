# Product

@initBigButton = ->
	$('.product').elem('big-button').off('click').on 'click', (e)->
		if $(this).hasMod 'buy'
			id = $(this).data 'id'
			if $('.sizes').length > 0
				id = $('.sizes .dropdown').data 'id'
				param_size = $('.sizes .dropdown__text').text()
			url = "/include/basket.php?action=add&id=#{id}"
			if param_size
				url += "&size=#{param_size}"
			
			url += "&artnumber=#{$(this).data('artnumber')}"

			fly $('.picture'), $('.header .cart')
			$(this).mod('border', true).mod('disabled', true).on end, ->
				$(this).text('Товар в корзине')

			$.get url, (data)->
				if data == 'success'
					bx_cart_block1.refreshCart({})
		if $(this).hasMod 'simmilar'

			getSimmilar $(this), ->
				fly $('.picture'), $('.header .simmilar')

			e.preventDefault()

		if $(this).parents('form').length == 0
			e.preventDefault()
	
@initProduct = ->
	$('.breadcrumbs').elem('brand').click (e)->
		if $(this).data('value').length > 0
			$.cookie('BRAND', $(this).data('value'),{path:"/"})
		else
			$.cookie('BRAND', null)
		window.location = $(this).data('href')
		e.preventDefault()

	initBigButton()
	initZoom = ->
		$('.picture').elem('big').easyZoom
			onShow: (x)->
				width = $('.product').elem('description').width()
				if $('.picture__small').length == 0
					width += 40
				$('.easyzoom-flyout').width width
				if $('.product').elem('description').height() > $('.easyzoom-flyout').height()
					$('.easyzoom-flyout')
						.height $('.product').elem('description').height()
	if !$.browser.mobile
		initZoom()
	initProducts()
	$('.tabs__trigger:first').addClass 'tabs__trigger--active'
	$('.tabs__content:first').addClass 'tabs__content--active'

	$('.sizes .dropdown__item').click (e)->
		$(this).block().data 'id', $(this).data 'id'
		$(this).block().data 'size', $(this).data 'size'
		if parseInt($(this).data('price')) > 0
			el   = $('.props__item--price strong')
			last = parseInt el.text().replace(' ','')
			val  = parseInt $(this).data('price')
			if last != val
				counter = new countUp el[0], last, val, 0, 1, countUpOptions
				counter.start()

	$('.tabs').elem('trigger').click (e)->
		if !$(this).data 'toggle'
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
		if !$.browser.mobile
			$('.picture').elem('big').data('easyZoom').swap $(this).data('middle'), $(this).attr('href')
		
		e.preventDefault()
	$('.picture').elem('zoom').click (e)->
			pswpElement = document.querySelectorAll('.pswp')[0];
			items = $(this).data('pictures')
			console.log items
			gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, galleryOptions);
			gallery.init();
			e.preventDefault()