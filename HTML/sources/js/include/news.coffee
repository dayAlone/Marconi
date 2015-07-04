# News
@toggleNews = (trigger, block, content, full = true, duration = 1000, callback = false)->
	trigger.mod 'disabled', true
	if block.hasMod('open') && content.hasMod('open')
		if full
			height = block.data 'height'
		else
			height = trigger.data 'height'
		block.css
			minHeight: height
			maxHeight: height
		content.velocity
			properties: "transition.slideUpOut"
			options:
				duration: duration
				complete: ->
					if full
						block.mod 'open', false
					trigger.mod 'disabled', false
					content.mod 'open', false
					callback() if callback
	else
		content.show()
		trigger.data 'height', block.outerHeight()
		block.css
			minHeight: block.height() + content.height() + 16
			maxHeight: block.outerHeight() + content.outerHeight() + 5
		content.velocity
			properties: "transition.slideDownIn"
			options:
				duration: duration
				complete: ->
					block.mod 'open', true
					content.mod 'open', true
					trigger.mod 'disabled', false
					callback() if callback
@initNews = ->
	$('.news-item').each ->
		h = $(this).outerHeight()
		$(this)
			.data 'height', h
		$(this).css
				maxHeight: ->
					return h
				minHeight: ->
					return h
	$('.news-item').elem('title').click (e)->
		trigger = $(this)
		block   = trigger.block()
		content = block.elem('content')
		toggleNews trigger, block, content
		e.preventDefault()
	$('.news-item').elem('arrow').click (e)->
		trigger = $(this)
		block   = trigger.block()
		content = block.elem('content')
		toggleNews trigger, block, content
		e.preventDefault()
@initLinks = ->
	$('.news-item').elem('link').click (e)->
		pswpElement = document.querySelectorAll('.pswp')[0];
		items = $(this).data('pictures')
		options = galleryOptions
		gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		gallery.init();
		e.preventDefault()

# Conditions
@positionArrow = ->
	$('.condition-theme').elem('arrow').css
		left : ->
			active = $(this).block('nav-item').byMod('active')
			left = active.position().left + active.width()/2 + parseInt(active.css('margin-left').split('px')[0])
			return left
@initConditions = ->
	
	positionArrow()

	$('.condition').elem('theme-trigger').click (e)->
		
		trigger = ($el, callback)->
			toggleNews $el, $el.parents('.condition'), $("#{$el.attr('href')}"), false, 500, ->
				callback($el)
		
		action = ->
			trigger $current, ($el)->
				positionArrow()
				$('.condition').elem('theme-trigger').mod 'disabled', false
				if !$("#{$el.attr('href')}").is ':visible'
					$current.mod 'active', false


		$current = $(this)
		$prev    = $('.condition').elem('theme-trigger').byMod('active')
		
		$('.condition').elem('theme-trigger').mod 'disabled', true

		if $prev.length > 0 && !$current.hasMod 'active'
			trigger $prev, ->
				$prev.mod 'active', false
				action()
		else
			action()

		$current.mod 'active', true

		e.preventDefault()
	
	$('.condition-theme').elem('nav-item').click (e)->
		$self = $(this)
		
		$self.block('nav-item').mod 'active', false
		$self.mod 'active', true
		positionArrow()
		
		$self.block('content-item').mod 'active', false
		
		el = $("#{$self.attr('href')}")
		parent = el.parents('.condition')
		el.mod 'active', true
		
		delay 300, ->
			console.log el.position().top
			h = el.position().top + el.height() + 60
			if h != parent.height()
				parent.css
					maxHeight: ->
						return h
					minHeight: ->
						return h
		e.preventDefault()

	$('.condition').elem('trigger').click (e)->
		trigger = $(this)
		block   = trigger.block()
		content = block.elem('content')
		toggleNews trigger, block, content
		e.preventDefault()

	$('.condition').each ->
		h = $(this).outerHeight()
		$(this)
			.data 'height', h
		$(this).css
				maxHeight: ->
					return h
				minHeight: ->
					return h
	