jQuery(function($) {
	
	//menu
	$('.over,.mobile_menu .close').click(function(e){
		e.preventDefault();
		$('body').css('overflow','auto').find('.over').fadeOut().siblings('.mobile_menu').removeClass('active');
	});
	$('header a.mob_icon_menu').click(function(e){
		e.preventDefault();
		$('body').css('overflow','hidden').find('.over').fadeIn().siblings('.mobile_menu').addClass('active');
	});
	
	//view_spoiler
	$('.spoilers > div > p').click(function(){
		$(this).parent().toggleClass('open').children('div').slideToggle();
	});
	
	$(".home_articles .news_block.slider").slick({
		arrows: true,
		dots: false,
		slidesToShow: 2,
		swipeToSlide: true,
		responsive:[
			{ breakpoint: 1000,
				settings:{
					slidesToShow: 1,
				}
			},
		]
	});
	
	$(".partners_list.slider").slick({
		arrows: true,
		dots: false,
		slidesToShow: 4,
		swipeToSlide: true,
		responsive:[
			{ breakpoint: 1000,
				settings:{
					slidesToShow: 1,
				}
			},
		]
	});
	
	$(".pop_block .tovar_data.slider, .lider_block .catalog_data.slider").slick({
		arrows: true,
		dots: false,
		slidesToShow: 2,
		swipeToSlide: true,
		responsive:[
			{ breakpoint: 1000,
				settings:{
					slidesToShow: 1,
				}
			},
		]
	});
	
	$(".single_product .gallery").slick({
		arrows: false,
		dots: true,
		slidesToShow: 1,
		swipeToSlide: true,
	});
	
	// top
	var tt;
	var body = $(this);
	var st = body.scrollTop();
	
	if(st > 200) {
		$('#pageUp').fadeIn(300); tt = true;
	} else tt = false;
	
	body.scroll(function(){
		st = $(this).scrollTop();
		if(st > 200) {
			$('#pageUp').fadeIn(400); tt = true;
		} else {
			$('#pageUp').fadeOut(400); tt = false;
		}
	});
	
	$('#pageUp').click(function(e){
		e.preventDefault();
		$('html, body').animate({scrollTop: 0},200);
	});
	
	//hide_text
	if($('.hide_text > div').is('div')){
		$(window).resize(function(){
			$('.hide_text > div').each(function(i,el){
				if(!$(this).parent().hasClass('active')){
					if($(this).height()>=el.scrollHeight)
						$(this).parent().addClass('deactivate');
					else
						$(this).parent().removeClass('deactivate');
				}
			});
		});
	}
	$('.hide_text > a').click(function(e){
		e.preventDefault();
		$(this).parent().toggleClass('active');
		$(window).resize();
	});
	
	setTimeout(function(){$(window).resize();},250);
	
	
	var wid_sc=window.innerWidth;
	var wid_sc_body=$('body').width();
	if(wid_sc_body==0){
		wid_sc_body=$('html').width();
	}
	var scroll_scr=$('body').scrollTop();
	if(scroll_scr==0){
		scroll_scr=$('html').scrollTop();
	}
	$(window).resize(function(){
		wid_sc=window.innerWidth;
		wid_sc_body=$('body').width();
		if(wid_sc_body==0){
			wid_sc_body=$('html').width();
		}
	});
	
	
	//фикс таблиц для мобилки
	$(window).resize(function(){
		//if(wid_sc<551){
		if(wid_sc_body<768){
			var tables=$('.content').find('table').not('.wrap');
			if(tables.length>wid_sc_body){
				tables.each(function(){
					$(this).addClass('wrap').wrap("<div class='over_table'></div>");
				});
			}
		}
	});
	
});