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
	$('input[name="ORDER_PROP_3"]').mask '+7 (000) 000 00 00'

$('.bx-ui-sls-clear').click ->
	getOrderDate()
$('.bx-sls input:hidden:first').change ->
	if parseInt($(this).val()) > 0
		getOrderDate()

getOrderDate = (confirm)->
	data = $('#ORDER_FORM').serialize()
	$('.basket').elem('block').mod 'loading', true
	$('.basket').elem('submit').attr 'disabled', 'disabled'
	console.log data
	if confirm
		data += "&confirmorder=Y"
	if $('#register_user:not(:checked)').length > 0
		data += "&delete_user=Y"
	$.ajax
		type     : "POST" 
		url      : $('#ORDER_FORM').attr('action') 
		data     : data
		success  : (data)->
			console.log data
			if !isJson data
				$('#ORDER_FORM .props').html $(data).find('.props').html()
				$('#ORDER_FORM .delivery').html $(data).find('.delivery').html()
				$('#ORDER_FORM .payment').html $(data).find('.payment').html()
				$('#ORDER_FORM .total').html  $(data).find('.total').html()
				initOrder()
				initDropdown()
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

		$('#ORDER_FORM').submit (e)->
			getOrderDate(true)
			e.preventDefault()