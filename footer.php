	</div><!-- .middle-->
</div><!-- .wrapper -->
	<footer>
		<div class="wrapper">
			<div class="top">
				<div class="logo">
					<a href="/">Складтехника</a>
					<p><iframe src="https://yandex.ru/sprav/widget/rating-badge/9379316473" width="150" height="50" frameborder="0"></iframe></p>
				</div>
				<div class="menu">
					<?php wp_nav_menu('menu=footer_menu&container=false&menu_class=footer_menu'); ?>
				</div>
				<div class="contact">
					<p class="fcont">Наши менеджеры готовы ответить на все ваши вопросы</p>
					<a class="tel" href="tel:<?php echo clear_tel(get_field('tel', MAIN_PAGE));?>"><?php the_field('tel', MAIN_PAGE);?></a>
					<a class="mail" href="mailto:<?php the_field('mail_prod', MAIN_PAGE);?>"><?php the_field('mail_prod', MAIN_PAGE);?></a>
					<p class="b-title"><?php the_field('city', MAIN_PAGE);?>, <?php the_field('adres', MAIN_PAGE);?></p>
					<div class="mode_work">
						<?php the_field('opening_hours', MAIN_PAGE);?>
					</div>
				</div>
			</div>
			<div class="bottom">
				<p class="period_work">© 1996—<?= date('Y'); ?></p>
				<p class="text">Обращаем ваше внимание на то, что данный сайт носит исключительно информационный характер и ни при каких условиях не является публичной офертой, определяемой положениями Статьи 437 (2) Гражданского кодекса Российской Федерации.</p>
				<p class="period_work"><a href="<?php echo get_page_link(12323); ?>">Политика конфиденциальности</a></p>
				<p class="period_work"><a href="<?php echo get_page_link(12803); ?>">Политика использования файлов cookie</a></p>
				<p class="period_work"><a href="<?php echo get_page_link(12805); ?>">Согласие на обработку персональных данных</a></p>
			</div>
		</div>
	</footer><!-- .footer -->
	
<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date(); for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }} k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(40080260, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/40080260" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->

<a href="#" id="pageUp"></a>
<a href="https://t.me/skladtechnika_bot" target="_blank" rel="nofollow" class="tg_fixed"></a>

<!--modal_form-->
<div class="modal_form" id="feedback" >
	<p class="h2">Заказать звонок</p>
	<?php echo do_shortcode('[contact-form-7 id="11279" title="Заказ обратного звонка"]');?>
</div>

<?php wp_footer(); ?>
</body>
</html>