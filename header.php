<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<? bloginfo('template_url'); ?>/images/favicon.png" type="image/png">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,900&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<title><?php echo wp_get_document_title(); ?></title>
	
	<meta name="yandex-verification" content="0ba2d9d6530c2a44" />
	<meta name="yandex-verification" content="8d129421992b8968" />
    <meta name="google-site-verification" content="Dg6ZWU687ENAlEQf89aSPygLXpUHNKiohKbtYLCfbHU" />
	<meta name="google-site-verification" content="itdcwu41_VfYpJeT1xqkhhM4dXdzLdS-rvI_ptWOdeE" />
    <?php wp_head();?>
	<?php if ( !is_front_page() ){?>
		<script>
			jQuery(function($) {
				setTimeout(function(){
				$('html, body').animate({scrollTop: 550}, 400, 'swing', function(){
					// if($('.top_submenu').length){
						// $('.top_submenu').css('display','table');
						// var tsmW = $('.top_submenu').width();
						// var tsmP = $('.top_submenu').offset().top;
						// $(window).scroll(function(){
							// st = $(this).scrollTop();
							// if(st > tsmP){
								// $('.top_submenu').css({position: 'fixed', top: -1, width: tsmW});
								// $('.content .es-button').css({position: 'fixed'});
							// }else{
								// $('.top_submenu').css({position: 'static', width: 'auto'});
								// $('.content .es-button').css({position: 'static'});
							// }
						// });
						// $(window).resize(function(){
							// tsmW = $('.top_submenu').width();
						// });
					// }
				})}, 500);
			});
		</script>
	<?php } ?>
</head>

<body>
<div class="wrapper <?= $wrapper_class; ?>">
	<!-- mobile_menu -->
	<div class="over"></div>
	<div class="mobile_menu">
		<span></span>
		<div class="close"></div>
		<?php wp_nav_menu('menu=Главное меню&container=false'); ?>
		<div class="info">
			<div class="item">
				<p>Телефон: <a href="tel:<?php echo clear_tel(get_field('tel', MAIN_PAGE));?>"><?php the_field('tel', MAIN_PAGE);?></a></p>
				<p>Адрес: <?php the_field('city', MAIN_PAGE);?>, <?php the_field('adres', MAIN_PAGE);?></p>
				<p>E-mail: <a href="mailto:<?php the_field('mail_prod', MAIN_PAGE);?>"><?php the_field('mail_prod', MAIN_PAGE);?></a></p>
			</div>
		</div>
	</div>
	<header>
		<div class="top_part">
			<div class="logo">
				<a href="/">Складтехника</a>
			</div>
			<div class="hdblock_1">
				<p><a class="serv" href="<?= get_category_link(217); ?>">Сервисный центр</a></p>
				<p><a class="mail" href="mailto:<?php the_field('mail_serv', MAIN_PAGE);?>"><?php the_field('mail_serv', MAIN_PAGE);?></a></p>
				<p class="text">ремонт погрузчиков в Воронеже, обслуживание складской техники</p>
			</div>
			<div class="hdblock_2">
				<p class="b-title"><?php the_field('adres', MAIN_PAGE);?></p>
				<div class="mode_work"><?php the_field('opening_hours', MAIN_PAGE);?></div>
			</div>
			<div class="hdblock_3">
				<p><a class="tel" href="tel:<?php echo clear_tel(get_field('tel', MAIN_PAGE));?>"><?php the_field('tel', MAIN_PAGE);?></a></p>
				<p><a class="feedback" href="#feedback" data-fancybox>Заказать звонок</a></p>
				<p><a class="mail" href="mailto:<?php the_field('mail_prod', MAIN_PAGE);?>"><?php the_field('mail_prod', MAIN_PAGE);?></a></p>
			</div>
		</div>
		
		<div class="mainmenu">
			<a class="link" href="https://skladt.ru" target="_blank" rel="nofollow">Перейти в интернет-магазин</a>
			<?php wp_nav_menu('menu=Главное меню&container=false'); ?>
			<a class="mob_icon_menu" href="#"><span></span><span></span><span></span><span></span></a>
		</div>
	</header><!-- .header-->
</div>

	<div class="mainpic">
		<!-- в полном экране -->
		<a href="/3D2021/" class="tur_3d-link" target="_blank" rel="nofollow">
			<span class="button_word">
				<img class="main_img" src="<? bloginfo('template_url'); ?>/images/3d_tur_png_w.png" alt="">
				<img class="hover_img" src="<? bloginfo('template_url'); ?>/images/3d_tur_png_r.png" alt="">
			</span>
			<span class="button_arr">
				<img class="main_img" src="<? bloginfo('template_url'); ?>/images/3d_tur_png_arr_w.png" alt="">
				<img class="hover_img" src="<? bloginfo('template_url'); ?>/images/3d_tur_png_arr_h.png" alt="">
			</span>
		</a>
	</div>
	
<div class="wrapper">
	<div class="middle">
	
	<?php get_search_form(); ?>