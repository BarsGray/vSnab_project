<?php
	get_header();
	the_post();
?>
	<div class="container">
		<main class="content">
			<?php
				bread_crumbs();
				
				$alt_t_page = get_field('post_title_h1');
				if(!empty($alt_t_page)){
					echo "<h1>".$alt_t_page."</h1>";
				} else {
					the_title('<p class="h1">','</p>');
				}
				if(get_field('template_product_status')==true){
				?>
					<div class="single_product">
						<div class="left">
							<div class="gallery">
								<?php
									if(has_post_thumbnail()){
										echo '<a class="item" href="'. get_the_post_thumbnail_url( $post->ID, 'full' ) .'" data-fancybox="pic-'.$post->ID.'" data-caption="">';
											the_post_thumbnail('medium-frontend');
										echo '</a>';
										
									}
									if(!empty(get_field('_gallery'))){
										foreach(get_field('_gallery') as $pic)
											printf(
												'<a class="item" href="%s" data-fancybox="pic-%s" data-caption="%s"><img src="%s" alt="%s" /></a>',
												$pic['url'],$post->ID,$pic['caption'],$pic['sizes']['medium-frontend'],$pic['alt']
											);
									}
								?>
							</div>
						</div>
						<div class="right">
							<?php
								echo '<p class="in_stock"><span>В наличии</span></p>';
								echo '<p class="article"><strong>Артикул:</strong> '.get_field('_sku').'</p>';
								if(!empty(get_field('_params'))){
									echo '<div class="params">';
										foreach(get_field('_params') as $param)
											echo '<p><span>'.$param['_params_name'].'</span><span>'.$param['_params_val'].'</span></p>';
									echo '</div>';
								}
								if(!empty(get_field('_price'))){
									echo'<p class="price">Цена: '.cost_format(get_field('_price')).' руб.</p>';
								} else {
									echo'<p class="price">Цена: по запросу</p>';
								}
							?>
							<a class="feedback" href="#order" data-fancybox >Оформить заказ</a>
						</div>
						<?php if(!empty(get_the_content())){ ?>
							<div class="bottom">
								<p class="title h1">Описание</p>
								<div class="text"><?php the_content();?></div>
							</div>
						<?php } ?>
					</div>
				<?php
				} else 
					the_content();
			?>
		</main><!-- #content -->
	</div><!-- #container -->
<?php get_sidebar(); ?>

<!--modal_form-->
<div class="modal_form" id="order" >
	<p class="h2">Оформить заказ</p>
	<?php echo do_shortcode('[contact-form-7 id="ccf7ad1" title="Оформить заказ"]');?>
</div>
<?php get_footer(); ?>