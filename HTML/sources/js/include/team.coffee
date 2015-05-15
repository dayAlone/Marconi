@checkTeamArrows = ->
	if $(".team .slick-slide:last").hasClass 'active'
		$(".slick-next").addClass 'slick-disabled' 
	else
		$(".slick-next").removeClass 'slick-disabled'
	
	if $(".team .slick-slide:first").hasClass 'active'
		$(".slick-prev").addClass 'slick-disabled' 
	else
		$(".slick-prev").removeClass 'slick-disabled'

@initTeam = (el)->
	next = $("#{el}__arrow--next").html()
	prev = $("#{el}__arrow--prev").html()
	
	$("#{el}").elem('preview').click (e)->
		$("#{el} .slick-active.active").removeClass 'active'
		$(this).parents('.slick-slide').addClass 'active'
		
		$("#{el}__item").mod 'active', false
		id = $(this).attr 'href'
		$(id).addClass "#{el.replace('.','')}__item--active"

		checkTeamArrows()

		e.preventDefault()

	$("#{el}").elem('slider').on('init', (event, slick, direction)->
		
		$("#{el} .slick-active:last").addClass 'last'
		$("#{el} .slick-active:first").addClass 'active'
		
		$("#{el} button").off('click').on 'click', (e)->
			if !$("#{el}").elem('slider').data 'action'
				if !$(this).hasClass 'slick-disabled'
					index = $("#{el} .slick-active.active").data('slick-index')
					
					if $(this).hasClass 'slick-next'
						index += 2
						if $("#{el} .slick-active:last").hasClass 'active'
							$("#{el}").elem('slider').slick('slickNext')
					else if $(this).hasClass 'slick-prev'
						if $("#{el} .slick-active:first").hasClass 'active'
							$("#{el}").elem('slider').slick('slickPrev')

					$("#{el} .slick-active.active").removeClass 'active'
					$("#{el} .slick-slide:nth-child(#{index})").addClass 'active'
					
					$("#{el}__item").mod 'active', false
					
					id = $("#{el} .slick-active.active a").attr 'href'

					$(id).addClass "#{el.replace('.','')}__item--active"
				
				checkTeamArrows()

			e.preventDefault()
	)
	.on('beforeChange', (event, slick, direction)->
		$("#{el}").elem('slider').data 'action', true
		$("#{el} .slick-active.last").removeClass 'last'
		
	).on('afterChange', (event, slick, direction)->
		$("#{el}").elem('slider').data 'action', false
		$("#{el} .slick-active:last").addClass 'last'
		
		return false
	).slick
		infinite       : false
		draggable      : false
		slidesToShow   : 7
		slidesToScroll : 1
		nextArrow      : "<button type=\"button\" class=\"slick-next\">#{next}</button>"
		prevArrow      : "<button type=\"button\" class=\"slick-prev\">#{prev}</button>"
		responsive: [{
			breakpoint: 992,
			settings: {
				slidesToShow: 3
			}
		},{
			breakpoint: 768,
			settings: {
				slidesToShow: 5
			}
		}]