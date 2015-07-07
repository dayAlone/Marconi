# Basket
updateTimer = false

@basketCalc = (el)->
	total    = 0
	sale     = 0
	quantity = 0

	if $('.basket').elem('count').length == 0
		location.href = $('.catalog__back').attr('href')
	
	$('.basket').elem('count').each ->
		if parseInt($(this).val()) <= 0 || !$(this).val()
			$(this).val(1)
		row = $(this).parents('.basket__item')
		total    += parseInt($(this).data('price'))*$(this).val()
		sale     += parseInt(row.find('.sale').data('value'))*$(this).val()
		quantity += parseInt($(this).val())
	
	console.log total

	$('.basket').elem('set').byMod('calc').each ->
		setTotal = 0
		$set = $(this)
		oldVal = parseInt $set.find('.basket__item:first .total').text().replace(' ','')
		
		$set.find('.basket__count').each ->
			row = $(this).parents('.basket__item')
			setTotal    += parseInt($(this).data('price'))*$(this).val()
		
		if oldVal != setTotal
			valCounter = new countUp $set.find('.basket__item:first .total')[0], oldVal, setTotal, 0, 1, countUpOptions
			valCounter.start()
	
	$('.basket').elem('set').byMod('no-calc').each ->
		$set = $(this)
		val  = $set.find('.basket__count').val()
		$set.find('.basket__text-count').each (key, el)->
			row = $(this).parents('.basket__item')
			curVal = parseInt $(el).text()
			setTotal  = parseInt($(el).data('price')) * val
			curTotal = row.find('.total').text().replace(' ','')
			if curVal != val
				valCounter = new countUp $(el)[0], curVal, val, 0, 1, countUpOptions
				valCounter.start()
			if curTotal != setTotal
				valTotal = new countUp row.find('.total')[0], curTotal, setTotal, 0, 1, countUpOptions
				valTotal.start()
			return true

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

	if $('.basket__count-total span:first').length > 0
		countVal = parseInt $('.basket__count-total span:first').text().replace(' ','')
		if countVal != quantity
			countCounter = new countUp $('.basket__count-total span:first')[0], countVal, quantity, 0, 1, countUpOptions
			countCounter.start()

	totalVal = parseInt $('.basket__total span:first').text().replace(' ','')
	if totalVal != total
		totalCounter = new countUp $('.basket__total span:first')[0], totalVal, total, 0, 2, countUpOptions
		totalCounter.start()

	#$('basket').elem('total').text total
@checkItems = (items)->
	update = false
	$.each items, (key, elem)->
		row = $("[data-id='#{elem.id}']")
		sale = row.find('.sale')
		if sale.length > 0
			if sale.data('value') != elem.discount
				update = true
				row.data 'price', elem.price
				sale.data 'value', elem.discount
				row.find('.sale-value').html elem.percent
	if update
		basketCalc()

@basketUpdate = (url, callback = false)->
	$.get url, (data)->
		if callback
			callback data
		
		if isJson data
			data = $.parseJSON data
			if data.result == 'success'
				update = false
				checkItems data.items
			getOrderDate()


@basketInit = ->
	$('.basket input.date').on 'keydown', (e)->
		e.preventDefault()
	$('.basket form .dropdown').elem('item').on 'click', (e)->
		$(this).block().siblings('input').val $(this).text()

	$('.basket .bx-ui-sls-fake').attr 'placeholder', 'город *'
	$('.basket').elem('set').each ->
		$(this).css 'maxHeight', $(this).outerHeight()
	$('.basket').elem('delete').click (e)->
		row  = $(this).parents('.basket__item')
		id   = JSON.stringify $(this).data 'id'
		if row.hasMod 'set-title'
			row = row.parents('.basket__set')
		row.css
			maxHeight: 0
			paddingTop: 0
			borderColor: "white"
			borderWidth: 0

		url = "/include/basket.php?a=delete&id=#{id}"
		$.get url, (data)->
			
			if data != "fail"
				data = $.parseJSON data
				if data.result == 'success'
					getOrderDate()
					if data.items
						checkItems data.items
			else
				console.log data
		row.on end , ->
			$(this).remove()
			basketCalc()
			basketUpdate url
		
		e.preventDefault()

	$('.basket').elem('coupon-trigger').on 'click', (e)->
		el  = $(this).block('coupon')	
		val = el.val()
		url = "/include/basket.php?a=check&code=#{val}"
		
		basketUpdate url, (data)->
			el.mod 'true', false
			el.mod 'fail', false
			if data != 'fail'
				el.block('coupon-trigger').attr 'disabled', 'disabled'
				el.block('coupon-trigger').mod 'disabled', true
				el.mod 'true', true
				el.attr 'disabled', 'disabled'
			else
				el.mod 'fail', true


	$('.basket').elem('coupon').on 'keydown', (e)->
		if $.inArray(e.keyCode, [
			46
			8
			9
			27
			13
			110
			190
		]) != -1 or e.keyCode == 65 and (e.ctrlKey == true or e.metaKey == true) or e.keyCode >= 35 and e.keyCode <= 40
			return
		if (e.shiftKey or e.keyCode < 48 or e.keyCode > 57) and (e.keyCode < 96 or e.keyCode > 105)
			e.preventDefault()

	$('.basket').elem('count').on 'keydown', (e)->
		el    = $(this)
		if $.inArray(e.keyCode, [
			46
			8
			9
			27
			13
			110
			190
		]) != -1 or e.keyCode == 65 and (e.ctrlKey == true or e.metaKey == true) or e.keyCode >= 35 and e.keyCode <= 40
			return
		if (e.shiftKey or e.keyCode < 48 or e.keyCode > 57) and (e.keyCode < 96 or e.keyCode > 105)
			e.preventDefault()
		clearTimeout updateTimer
		updateTimer = delay 1000, ->
			id    = el.data 'id'
			count = el.val()
			url   = "/include/basket.php?a=update&id=#{id}&count=#{count}"
			basketCalc el
			basketUpdate url
		