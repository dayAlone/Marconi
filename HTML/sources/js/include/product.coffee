# Product

@initBigButton = ->
	$('.product').elem('big-button').off('click').on 'click', (e)->
		if $(this).hasMod 'buy'
			$el        = $(this)
			id        = $el.data 'id'
			request   = $el.data 'request'
			artnumber = $el.data 'artnumber'
			value     = parseInt $el.block('counter-input').val()

			if !request

				if $('.sizes').length > 0
					id         = $('.sizes .dropdown').data 'id'
					param_size = $('.sizes .dropdown__text').text()

				url = "/include/basket.php?a=add&id=#{id}"

				if param_size
					url += "&size=#{param_size}"

				url += "&artnumber=#{artnumber}"

				if value > 0
					$el.block('counter').mod 'disabled', true
					url += "&count=#{value}"

			else
				request.unshift {'id': id, 'quantity':value}
				request = JSON.stringify request
				url     = "/include/basket.php?a=add_set&data=#{request}"

			$.get url, (data)->
				if data == 'success'
					bx_cart_block1.refreshCart({})

			fly $('.picture'), $('.header .cart')
			$(this).mod('border', true).mod('disabled', true).on end, ->
				$(this).text('Товар в корзине')


		if $(this).hasMod 'simmilar'
			getSimmilar $(this), ->
				fly $('.picture'), $('.header .simmilar')
			e.preventDefault()

		if $(this).parents('form').length == 0 && !$(this).hasMod 'set'
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
		$('.picture__big').easyZoom
			onShow: (x)->
				width = $('.product').elem('description').width()
				if $('.picture__small').length == 0
					width += 40
				$('.easyzoom-flyout').width width
				if $('.product').elem('description').height() > $('.easyzoom-flyout').height()
					$('.easyzoom-flyout')
						.height $('.product').elem('description').height()
	initZoom()
	initProducts()
	$('.tabs__trigger:first').addClass 'tabs__trigger--active'
	$('.tabs__content:first').addClass 'tabs__content--active'

	$('.sizes .dropdown__item').click (e)->

		id = parseInt $(this).data 'id'
		set = $(this).block().data 'set'
		if set
			$button = $('.product').elem('big-button').byMod('set')
			$badge  = $('.product').elem('badge').byMod('set')
			$set    = $('.product').elem('set')
			if set[id]
				$set.removeClass 'hidden'
				$button.attr 'url', set[id].url
				if set[id].type == 2
					 text = 'неразделяемого'
				else
					text = 'разделяемого'
				$badge.text "В составе #{text} комплекта"
			else
				$set.addClass 'hidden'

		$(this).block().data 'id', id
		$(this).block().data 'size', $(this).data 'size'


		if !$(this).data 'set'
			$('.product').elem('big-button').byMod('buy').removeClass 'hidden'
			$('.product').elem('counter').removeClass 'hidden'
		else
			$('.product').elem('big-button').byMod('buy').addClass 'hidden'
			$('.product').elem('counter').addClass 'hidden'

		$('.product__big-button--buy').text('В корзину')
		$('.product').elem('big-button').byMod('buy').mod('border', false).mod('disabled', false)

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
			options = galleryOptions
			options.index = $('.picture__small--active').index()
			gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
			gallery.init();
			e.preventDefault()
