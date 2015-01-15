# Lookbook
$(document).ready ->
	if $('body.lookbook').lenght > 0
		$('.row.enter').isotope
			itemSelector : "[class*='col-']"
			masonry:
				columnWidth: $('.row.enter').width()/4
	  
	    
		$('.lookbook').elem('slider-preview').click (e)->
			slider = $('.lookbook').elem('slider').data('fotorama')
			slider.show $(this).data('direction')
			e.preventDefault()


		getElem = (fotorama, direction) ->
			if direction is "next"
				if fotorama.activeIndex is 0
					el = $(fotorama.data[fotorama.data.length - 1].html)
					i = fotorama.data[fotorama.data.length - 1].i
				else
					el = $(fotorama.data[fotorama.activeIndex - 1].html)
					i = fotorama.data[fotorama.activeIndex - 1].i
			if direction is "prev"
				if fotorama.activeIndex is fotorama.data.length - 1
					el = $(fotorama.data[0].html)
					i = fotorama.data[0].i
				else
					el = $(fotorama.data[fotorama.activeIndex + 1].html)
					i = fotorama.data[fotorama.activeIndex + 1].i
			return el
		
		$('.lookbook').elem('slider')
			.on('fotorama:show', (e, fotorama, extra) ->
				
				el = $(fotorama.data[fotorama.activeIndex].html).find('.lookbook__picture')
				if el.hasMod 'contain'
					$('.lookbook').elem('slider-preview').mod 'width', true
				else
					$('.lookbook').elem('slider-preview').mod 'width', false
				
				size()

				$('.lookbook').elem('slider-preview').each ->
					if $(this).hasMod 'next'
						el = getElem fotorama, 'prev'
					if $(this).hasMod 'prev'
						el = getElem fotorama, 'next'
					
					$(this).css
						'background-image' : el.find('.lookbook__picture').css 'background-image'

			)
			.on('fotorama:showend', (e, fotorama, extra)->
				delay 100, ->
					size()
					fotorama.resize
						height : $(fotorama.activeFrame.html).outerHeight()
			)
			.fotorama()