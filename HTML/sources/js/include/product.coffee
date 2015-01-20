# Product

flyProduct = ->
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
$(document).ready ->
	if $('body').hasClass 'product'
		
		$('.breadcrumbs').elem('brand').click (e)->
			if $(this).data('value').length > 0
				$.cookie('BRAND', $(this).data('value'),{path:"/"})
			else
				$.cookie('BRAND', null)
			window.location = $(this).data('href')
			e.preventDefault()

		$('.product').elem('big-button').click (e)->
			if $(this).hasMod 'buy'
				id = $(this).data 'id'
				if $('.sizes').length > 0
					id = $('.sizes .dropdown').data 'id'
					param_size = $('.sizes .dropdown__text').text()
				url = "/include/basket.php?action=add&id=#{id}"
				if param_size
					url += "&size=#{param_size}"
				
				flyProduct()

				$.get url, (data)->
					if data == 'success'
						bx_cart_block1.refreshCart({})
			if $(this).hasMod 'simmilar'
				id       = $(this).data 'id'
				simmilar = $.cookie 'simmilar'
				if !isJson simmilar
					simmilar = [] 
				else
					simmilar = JSON.parse simmilar
				
				if $.inArray(id, simmilar) == -1
					simmilar.push(id)
					flyProduct()
				else
					simmilar.remByVal id
				
				if simmilar.length > 0
					$('.simmilar').elem('text').text "К сравнению: #{simmilar.length}"
				else
					$('.simmilar').elem('text').text "Товары не выбраны"
				
				$('.simmilar').attr 'href', '/catalog/compare.php'

				simmilar = JSON.stringify simmilar
				$.cookie 'simmilar', simmilar, { path:"/", expires: 7}

				e.preventDefault()

			if $(this).parents('form').length == 0
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