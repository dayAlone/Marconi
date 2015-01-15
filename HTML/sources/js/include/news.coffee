# News
$(document).ready ->
	if $('body').hasClass 'news'
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
			block   = $(this).block()
			content = block.elem('content')
			trigger.mod 'disabled', true
			if block.hasMod 'open'
				height = block.data 'height'
				block.css
					minHeight: block.data('height')
					maxHeight: block.data('height')
				content.velocity
					properties: "transition.slideUpOut"
					options:
						duration: 1000
						complete: ->
							block.mod 'open', false
							trigger.mod 'disabled', false
			else
				content.show()
				block.css
					minHeight: block.height() + content.height() + 16
					maxHeight: block.outerHeight() + content.outerHeight() + 5
				content.velocity
					properties: "transition.slideDownIn"
					options:
						duration: 1000
						complete: ->
							block.mod 'open', true
							trigger.mod 'disabled', false
			e.preventDefault()