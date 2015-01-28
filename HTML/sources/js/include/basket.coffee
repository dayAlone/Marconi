# Basket
@basketCalc = (el)->
	total  = 0
	sale   = 0
	
	if $('.basket').elem('count').length == 0
		location.href = $('.catalog__back').trigger('click')
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
updateTimer = false
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
		url = "/include/basket.php?action=delete&id=#{id}"
		$.get url, (data)->
			if data == 'success'
				getOrderDate()
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
			$.get url, ->
				getOrderDate()