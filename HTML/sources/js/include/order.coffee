initOrder = ->
	$('.basket .delivery input[type="radio"], .basket .payment input[type="radio"]')
		.iCheck()
		.one 'ifChecked', ->
			getOrderDate()
	$('.basket input[type="checkbox"]').iCheck()
	$('.stores-list .dropdown__item').off('click').on 'click', (e)->
		$(this).block().find('select').val $(this).data 'id'
		console.log $(this).block().find('select')
		$(this).block().find('.parsley-errors-list').removeClass '.filled'
		e.preventDefault()
	$('input[name="ORDER_PROP_3"]').mask '+7 0000000000'

getOrderDate = (confirm)->
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
				$('#ORDER_FORM .props').html $(data).find('.props').html()
				$('#ORDER_FORM .delivery').html $(data).find('.delivery').html()
				$('#ORDER_FORM .payment').html $(data).find('.payment').html()
				$.each $(data).find('.total__counter'), ->
					id = $(this).find('span:first-of-type').attr('id')
					old = parseInt $("##{id}").text().replace(" ","")
					val = parseInt $(this).text().replace(" ","")
					if old != val
						counter[id] = new countUp id, old, val, 0, 1, countUpOptions
						counter[id].start()
					return true
				
				initOrder()
				initDropdown()
				bx_cart_block1.refreshCart({})
				$('.basket').elem('block').mod 'loading', false
				$('.basket').elem('submit').removeAttr 'disabled'
			else
				data = $.parseJSON data
				if data.success == 'Y'
					location.href = data.redirect

$(document).ready ->
	if $('body').hasClass 'basket'
		initOrder()
		getOrderDate()
		$('.bx-sls input:hidden').on 'change', ->
			if parseInt($(this).val()) > 0
				getOrderDate()

		$('.bx-ui-sls-clear').click ->
			getOrderDate()
		$('#ORDER_FORM').submit (e)->
			getOrderDate(true)
			e.preventDefault()