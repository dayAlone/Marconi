# Basket
updateTimer = false

@basketCalc = (el)->
	total  = 0
	sale   = 0
	
	if $('.basket').elem('count').length == 0
		location.href = $('.catalog__back').attr('href')
	$('.basket').elem('count').each ->
		if parseInt($(this).val()) <= 0 || !$(this).val()
			$(this).val(1)
		row = $(this).parents('.basket__item')
		total += parseInt($(this).data('price'))*$(this).val()
		sale  += parseInt(row.find('.sale').data('value'))*$(this).val()
	
	if el
		row  = el.parents('.basket__item')

		if row.length > 0
			val  = parseInt(row.find('.basket__count').data('price')) * row.find('.basket__count').val()
			last = parseInt row.find('.total').text().replace(' ','')
			if val != last
				counter = new countUp row.find('.total')[0], last, val, 0, 1, countUpOptions
				counter.start()
	if $('.basket__sale-total span:first').length > 0
		saleVal = parseInt $('.basket__sale-total span:first').text().replace(' ','')
		if saleVal != sale
			saleCounter = new countUp $('.basket__sale-total span:first')[0], saleVal, sale, 0, 1, countUpOptions
			saleCounter.start()

	totalVal = parseInt $('.basket__total span:first').text().replace(' ','')
	if totalVal != total
		totalCounter = new countUp $('.basket__total span:first')[0], totalVal, total, 0, 2, countUpOptions
		totalCounter.start()

	#$('basket').elem('total').text total

@basketUpdate = (url, callback = false)->
		$.get url, (data)->
			if callback
				callback data
			
			if isJson data
				data = $.parseJSON data
				if data.result == 'success'
					update = false
					$.each data.items, (key, elem)->
						row = $("[data-id='#{elem.id}']")
						sale = row.find('.sale')
						if sale.data('value') != elem.discount
							update = true
							row.data 'price', elem.price
							sale.data 'value', elem.discount
							row.find('.sale-value').html elem.percent
					if update
						basketCalc()
				getOrderDate()


@basketInit = ->
	$('.basket input.date').on 'keydown', (e)->
		e.preventDefault()
	$('.basket form .dropdown').elem('item').on 'click', (e)->
		$(this).block().siblings('input').val $(this).text()

	$('.basket .bx-ui-sls-fake').attr 'placeholder', 'город *'

	$('.basket').elem('delete').click (e)->
		row  = $(this).parents('.basket__item')
		id   = $(this).data 'id'
		row.css
			maxHeight: 0
		url = "/include/basket.php?a=delete&id=#{id}"
		$.get url, (data)->
			data = $.parseJSON data
			if data.result == 'success'
				getOrderDate()
		row.on end , ->
			$(this).remove()
			basketCalc()
		e.preventDefault()

	$('.basket').elem('coupon').on 'keydown', (e)->
		el  = $(this)
		if (e.keyCode < 48 || e.keyCode > 57) && $.inArray(e.keyCode, [37,38,39,40,13,27,9,8,46]) == -1
			return false
		clearTimeout updateTimer

		updateTimer = delay 1000, ->
			val = el.val()
			url = "/include/basket.php?a=check&code=#{val}"
			
			basketUpdate url, (data)->
				el.mod 'true', false
				el.mod 'fail', false
				if data != 'fail'
					el.mod 'true', true
					el.attr 'disabled', 'disabled'
				else
					el.mod 'fail', true

	$('.basket').elem('count').on 'keydown', (e)->
		el    = $(this)
		if (e.keyCode < 48 || e.keyCode > 57) && $.inArray(e.keyCode, [37,38,39,40,13,27,9,8,46]) == -1
			return false
		clearTimeout updateTimer
		updateTimer = delay 1000, ->
			
			id    = el.data 'id'
			count = el.val()
			url   = "/include/basket.php?a=update&id=#{id}&count=#{count}"
			basketCalc el
			basketUpdate url
		