@initOrder = ->
	$('.basket .delivery input[type="radio"], .basket .payment input[type="radio"]')
		.iCheck()
		.one 'ifChecked', ->
			getOrderDate()
	$('.basket input[type="checkbox"]').iCheck()
	
	$('.order__change').on 'click', (e)->
		$('#register_user').iCheck('uncheck')
		e.preventDefault()
	$('.time-select .dropdown__item').on 'click', (e)->
		$(this).block().find('input').val $(this).text()
		
	$('.stores-list .dropdown__item').on 'click', (e)->
		$('.stores-list .parsley-errors-list').removeClass 'filled'
		$(this).block('select').val $(this).block('select').find("option[data-id='#{$(this).data('id')}']").val()#.val $(this).data 'id'
		
		$('.stores-list').elem('description').mod 'active', true
		$('.stores-list').elem('item').mod 'active', false
		$('.stores-list').elem('item').filter("[data-id='#{$(this).data('id')}']").mod 'active', true

@getOrderDate = (confirm)->
	data = $('#ORDER_FORM').serialize()
	$('.basket').elem('block').mod 'loading', true
	$('.basket').elem('submit').attr 'disabled', 'disabled'
	if confirm
		data += "&confirmorder=Y"
	if $('#register_user:not(:checked)').length > 0
		data += "&delete_user=Y"
	counter = []
	$.ajax
		type     : "POST" 
		url      : $('#ORDER_FORM').attr('action') 
		data     : data
		success  : (data)->
			if !isJson data
				console.log $(data).find('.errortext')
				if $(data).find('.errortext').text().indexOf('с таким e-mail') > 0
					$("input[type='email']").removeClass().addClass 'parsley-error'
					$('.order__login-error').mod 'active', true
				$('#ORDER_FORM .props').html $(data).find('.props').html()
				$('#ORDER_FORM .delivery').html $(data).find('.delivery').html()
				$('#ORDER_FORM .payment').html $(data).find('.payment').html()
				$.each $(data).find('.total__counter'), ->
					id = $(this).find('span:first-of-type').attr('id')					
					parent = $(this).parents('.total__item')
					current = $("##{id}").parents('.total__item')
					if parent.hasClass('hidden') && !current.hasClass('hidden')
						current.addClass 'hidden'
					if !parent.hasClass('hidden') && current.hasClass('hidden')
						current.removeClass 'hidden'

					old = parseInt $("##{id}").text().replace(" ","")
					val = parseInt $(this).text().replace(" ","")
					if old != val
						counter[id] = new countUp id, old, val, 0, 1, countUpOptions
						counter[id].start()
					return true
				
				initDropdown()
				initOrder()
				
				bx_cart_block1.refreshCart({})
				$('.basket').elem('block').mod 'loading', false
				$('.basket').elem('submit').removeAttr 'disabled'
			else
				data = $.parseJSON data
				if data.success == 'Y'
					location.href = data.redirect

@initOrderPage = ->
	initOrder()
	getOrderDate()
	$('.bx-sls input:hidden').on 'change', ->
		if parseInt($(this).val()) > 0
			getOrderDate()

	$('.bx-ui-sls-clear').click ->
		getOrderDate()
	
	$('#ORDER_FORM').parsley().subscribe 'parsley:form:validate', (formInstance)->
		if formInstance.isValid()
			getOrderDate(true)
		formInstance.submitEvent.preventDefault();