<?php
	/* Template Name: Главная */
	get_header();
	$page_home = get_page(11881);
	$alt_t_page = get_field('post_title_h1');
?>

<div class="container tmpl-<?php echo $tmpl;?>">
	<main class="content">
		<?php
			if(!empty($alt_t_page)){
				echo "<h1>".$alt_t_page."</h1>";
			} else {
				echo "<h1>". $page_home -> post_title ."</h1>";
			}
		?>
		
		<?php if(get_field('working_hours')){?>
			<a class="working_hours" href="<?php the_field('working_hours');?>" data-fancybox><img src="<?php the_field('working_hours');?>" alt="" /></a>
		<?php } ?>
		
		<div class="lider_block">
			<?php
				$leader_terms = get_terms_by_meta('cat_leader', 1);	//	получаем термины, у которых meta_key cat_leader = 1

				$args = array( 'numberposts' => -1, 'meta_query' => array( 'relation' => 'and', array( 'key' => 'pr_leader', 'value' => 1, ) ) );
						
				$posts = get_posts($args);
				if($leader_terms || $posts){
					echo '<p class="p1_title">Лидеры продаж</p>';
					echo '<div class="catalog_data slider">';
					if($leader_terms)
					foreach($leader_terms as $term_id => $term_name){ // выводим terms, отмеченные как "Лидеры продаж"
						?>
						<div class="item">
							<a class="img" href="<?= get_category_link($term_id); ?>" >
								<span class="l_name"><span><?= $term_name; ?></span></span>
								<span class="back">
									<?php
									$cat_thumb = get_field('cat_thumb', 'category_'.$term_id);
									if($cat_thumb){
										echo '<img src="'.$cat_thumb['sizes']['medium-frontend'].'" alt="'.$cat_thumb['alt'].'" title="'.$cat_thumb['alt'].'" />';
									}else{
										echo '<img src="'.get_bloginfo('template_url').'/images/thumb_158x232.jpg" alt="" />';
									}
									?>
								</span>
							</a>
						</div>
						<?php
					}
					
					if($posts)
					foreach($posts as $post){ setup_postdata($post); // выводим posts, отмеченные как "Лидеры продаж"
						$catinfo = get_the_category($post->ID);
						?>
						<div class="item">
							<a class="img" href="<?= get_category_link($catinfo[0]->cat_ID); ?>">
								<span class="l_name"><span><? the_title(); ?></span></span>
								<span class="back <?php if(get_field('pr_label') == 'Новинка'){ echo'new'; } elseif(get_field('pr_label') == 'Выгодно'){ echo'profitable'; }?>">
									<?php if(has_post_thumbnail()) the_post_thumbnail('medium-frontend'); else{ ?>
										<img src="<? bloginfo('template_url'); ?>/images/thumb_158x232.jpg" alt="" />
									<?php } ?>
								</span>
							</a>					
						</div>
						<?php
					}
					echo '</div>';
					wp_reset_postdata();
				}
			?>
		</div>
		
		<div class="home_articles">
			<p class="p1_title">Новости <a href="<?= get_category_link(2); ?>" class="allnews">Все новости</a></p>
			<?php query_posts('cat=2&showposts=5'); // Статьи ?>
			<?php if(have_posts()){ ?>
				<div class="news_block slider">
					<?php while(have_posts()){ the_post(); ?>				
						<div class="item">
							<a class="title" href="<?php the_permalink();?>"><?php the_title(); ?></a>
							<div class="text"><?php echo wp_trim_words( get_the_content(), 30 );?></div>
							<div class="info">
								<span class="date"><?php the_time('d.m.y'); ?></span>
								<a class="more" href="<?php the_permalink();?>">Читать</a>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>

		<div class="pop_block">
			<?php
				$args = array(
					'category' => 3, // Каталог
					'posts_per_page'   => 15,
					'meta_query' => array(
						'relation' => 'and',
						array(
							'key' => 'pr_popular', //	Популярные товары<
							'value' => 1,
						)
					)
				);
				$posts = get_posts($args);
				if($posts){
					echo '<p class="p1_title">Популярные товары</p>';
					echo '<div class="tovar_data slider">';
					foreach($posts as $post){ setup_postdata($post);
						$catinfo = get_the_category($post->ID);
						?>				
						<div class="item">
							<a href="<?= get_category_link($catinfo[0]->cat_ID); ?>" class="l_name"><span><?php the_title(); ?></span></a>
							<a class="img <?php if(get_field('pr_label') == 'Новинка'){ echo'new'; } elseif(get_field('pr_label') == 'Выгодно'){ echo'profitable'; }?>" href="<?= get_category_link($catinfo[0]->cat_ID); ?>" href="<?= get_category_link($catinfo[0]->cat_ID); ?>">
								<?php if(has_post_thumbnail()) the_post_thumbnail('medium-frontend'); else { ?>
									<img src="<?php bloginfo('template_url'); ?>/images/thumb_158x232.jpg" alt="" />
								<?php } ?>
							</a>
							<?php if($price = get_field('pr_price')){ ?><p class="price"><?= $price.RU; ?></p><?php } ?>
						</div>
						<?php
					}
					echo '</div>';
					wp_reset_postdata();
			}	
			?>
		</div>
					
		<div class="partners_block">
			<p class="p1_title">Наши клиенты</p>
			<div class="partners_list slider">
				<?php foreach(get_field('partners', $page_home) as $item){?>
					<div class="item"><img src="<?php echo $item['partner_img']; ?>" alt="" /></div>
				<?php } ?>
			</div>
		</div>
		
		<div class="home_articles">
			<p class="p1_title">Статьи <a href="<?= get_category_link(225); ?>" class="allnews">Архив статей</a></p>
			<?php query_posts('cat=225&showposts=5'); // Статьи ?>
			<?php if(have_posts()){ ?>
				<div class="news_block slider">
					<?php while(have_posts()){ the_post(); ?>				
						<div class="item">
							<a class="title" href="<?php the_permalink();?>"><?php the_title(); ?></a>
							<div class="text"><?php echo wp_trim_words( get_the_content(), 30 );?></div>
							<div class="info">
								<span class="date"><?php the_time('d.m.y'); ?></span>
								<a class="more" href="<?php the_permalink();?>">Читать</a>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		
		<?php if(!empty($page_home -> post_content)){ ?>
			<div class="text hide_text">
				<div>
					<?php echo apply_filters( 'the_content', $page_home->post_content );?>
				</div>
				<a href="#" class="more"></a>
			</div>
		<?php } ?>
		
	</main><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>