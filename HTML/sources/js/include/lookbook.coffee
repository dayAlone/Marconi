# Lookbook
@getElem = (fotorama, direction) ->
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

@initLookbook = ->
	tag = document.createElement('script');
	tag.src = "https://www.youtube.com/iframe_api";
	firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

	next = $('.lookbook__slider-preview--next').html()
	prev = $('.lookbook__slider-preview--prev').html()

	$('.row.enter').isotope
		itemSelector : "[class*='col-']"
		masonry:
			columnWidth: $('.row.enter').width()/4
  
	$('.lookbook').elem('slider-preview').click (e)->
		slider = $('.lookbook').elem('slider').data('fotorama')
		slider.show $(this).data('direction')
		e.preventDefault()

	
	window.onYouTubeIframeAPIReady = ->
		window.player = false
		$('.lookbook').elem('slider')
			.on('fotorama:show', (e, fotorama, extra) ->
				if window.player
					player.destroy()
					window.player = false
				video = $(fotorama.data[fotorama.activeIndex].html).find('.lookbook__slider-video')
				if video.length > 0
					window.player = new YT.Player video.find('div:first').attr('id'), { 
						videoId    : video.data('id')
						playerVars :
							showinfo : 0
						events     : 
							onReady : (e)->
								e.target.playVideo()
				}
          
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
					slider = $(fotorama.data[fotorama.activeIndex].html).find('.catalog')
					if slider
						slider.slick
							infinite       : true
							draggable      : false
							slidesToShow   : 4
							slidesToScroll : 1
							nextArrow      : "<button type=\"button\" class=\"slick-next\">#{next}</button>"
							prevArrow      : "<button type=\"button\" class=\"slick-prev\">#{prev}</button>"
							onInit: ()->
								initProducts()
					size()
					fotorama.resize
						height : $(fotorama.activeFrame.html).outerHeight()
			)
			.fotorama()