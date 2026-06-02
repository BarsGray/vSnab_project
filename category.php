<?php
	get_header();
	$qo = get_queried_object();
	$cat_short_text = get_field('cat_short_text', 'category_'.$cat);
?>
	<div class="container">
		<main class="content">
			<?php if(get_field('working_hours', MAIN_PAGE)){?>
				<a class="working_hours" href="<?php the_field('working_hours', MAIN_PAGE);?>" data-fancybox><img src="<?php the_field('working_hours', MAIN_PAGE);?>" alt="" /></a>
			<?php } ?>
			<?php
				bread_crumbs();
				title_cat();
			?>
			
			<?php
			// если категория товаров и нет дочерних рубрик уберем заголовок
			// if(is_category(3) || cat_is_ancestor_of(3, $cat)) { // Каталог
				// $termchildren=get_terms(array(
					// 'taxonomy' => 'category',
					// 'hide_empty' => true,
					// 'parent' => $cat,
					// 'meta_query' => array(
						// 'relation' => 'OR',
						// array('key' => 'import','value' => 1,'compare' => '!='),
						// array('key' => 'import','compare' => 'NOT EXISTS')
					// )
				// ));
				// if(count($termchildren)){
					// title_cat();
				// }
			// }
			// else {
				// title_cat();
			// }
			?>
				
			<?php
				if( is_category(2)) { // Новости
					if(have_posts()){
						echo '<div class="news_block" itemscope itemtype="http://schema.org/Article">';
							while(have_posts()){ the_post();
								?>				
								<div class="item">
									<a class="title" itemprop="headline" href="<?php the_permalink();?>"><span itemprop="mainEntityOfPage"><?php the_title(); ?></span></a>
									<div class="text" itemprop="articleBody"><?php echo wp_trim_words( get_the_content(), 30 );?></div>
									<div class="info">
										<span itemprop="datePublished" class="date"><?php the_time('d.m.y'); ?></span>
										<a class="more" href="<?php the_permalink();?>">Читать</a>
									</div>
								</div>
							<?php
							}
						echo '</div>';
						wp_pagenavi();
					}
				} elseif ( is_category(225)) { // Статьи
					if( have_posts() ){
						echo '<div class="news_block" itemscope itemtype="http://schema.org/Article">';
							while( have_posts() ){ the_post();
							?>
							<div class="item">
								<a class="title" itemprop="headline" href="<?php the_permalink();?>"><span itemprop="mainEntityOfPage"><?php the_title(); ?></span></a>
								<div class="text" itemprop="articleBody"><?php echo wp_trim_words( get_the_content(), 30 );?></div>
								<div class="info">
									<span itemprop="datePublished" class="date"><?php the_time('d.m.y'); ?></span>
									<a class="more" href="<?php the_permalink();?>">Читать</a>
								</div>
							</div>
							<?
							}
						echo '</div>';
						wp_pagenavi();
					}
				} elseif(is_category(3) || cat_is_ancestor_of(3, $cat)){ // Каталог
					
					$categories = get_categories(array(
						'parent' => $cat,
						'orderby' => 'term_order',
						'hide_empty' => true,
						'meta_query' => array(
							'relation' => 'OR',
							array('key' => 'import','value' => 1,'compare' => '!='),
							array('key' => 'import','compare' => 'NOT EXISTS')
						)
					));
					
					$import_categories = get_categories(array(
						'parent' => $cat,
						'orderby' => 'term_order',
						'hide_empty' => true,
						'meta_query' => array(array('key' => 'import','value' => 1))
					));
					
					if( $categories ){
						$categories=array_merge($categories,$import_categories);

						//	По шаблону для всех категорий текст идет в начале страницы, перед элементами.
						//	В некоторых категориях текста в начале слишком много, 
						//	По просбе клиента для таких кат. текст переносим вниз.
						//	т.к. нет возможности это программно отследить, задаем массив id вручную
						//	МЕТАЛЛИЧЕСКИЕ ШКАФЫ (id=23) - одна их таких категорий
						//	МЕБЕЛЬ ДЛЯ АВТОСЕРВИСА (id=22)
						
						echo category_description();
						
						echo '<div class="catalog_data" data="revers">';
						foreach( $categories as $ctg ){
							$cat_thumb = get_field('cat_thumb', 'category_'.$ctg->cat_ID);
							?>
							<div class="item">
								<a class="img" href="<?= get_category_link($ctg->cat_ID); ?>">
									<span class="l_name"><span><?php echo $ctg->cat_name; ?></span></span>
									<span class="back">
										<?php
										if($cat_thumb) {
											echo '<img src="'.$cat_thumb['url'].'" alt="'.$cat_thumb['alt'].'" width="158" />';
										}else{
											$single_post = get_posts( array(
												'numberposts'     => 1,
												'category'        => $ctg->cat_ID,
												'orderby'         => 'ID',
												'order'			  => 'asc'
											) );
											if($single_post && has_post_thumbnail($single_post[0]->ID)){
												echo get_the_post_thumbnail($single_post[0]->ID, 'medium-frontend'); // pr_thumb medium
											}else{
											?>
												<img src="<? bloginfo('template_url'); ?>/images/thumb_158x232.jpg" alt="" />
											<?php
											}
										}
										?>
									</span>
								</a>
							</div>
							<?
						}
						$posts_import=get_posts(array(
							'tax_query' => array(array(
								'taxonomy' => 'category',
								'field' => 'id',
								'terms' => $cat,
								'include_children' => false
							)),
							'meta_query' => array(array('key' => 'template_product_status','value' => 1))
						));
						if(!empty($posts_import)){
							foreach($posts_import as $post_import){
								?>
								<div class="item">
									<a class="img" href="<? the_permalink($post_import); ?>">
										<span class="l_name"><span><?php echo $post_import->post_title; ?></span></span>
										<span class="back">
											<?php
											if(has_post_thumbnail($post_import))
												echo get_the_post_thumbnail($post_import,'medium-frontend');
											else
												echo '<img src="'.get_bloginfo('template_url').'/images/thumb_158x232.jpg" alt="" />';
											?>
										</span>
									</a>
								</div>
								<?php
							}
						}
						echo '</div>';
						
						echo $cat_short_text;
						
					} else {

						// global $query_string;
						// query_posts($query_string . "&order=ASC");
						
						if(have_posts()){
							// title_cat();
							echo category_description();
							
							if(!empty($import_categories)){
								echo '<div class="catalog_data" data="revers">';
								foreach($import_categories as $ctg){
									$cat_thumb=get_field('cat_thumb','category_'.$ctg->cat_ID);
									?>				
									<div class="item">
										<a class="img" href="<?= get_category_link($ctg->cat_ID); ?>">
											<span class="l_name"><span><?php echo $ctg->cat_name; ?></span></span>
											<span class="back">
												<?php
												if($cat_thumb)
													echo '<img src="'.$cat_thumb['url'].'" alt="'.$cat_thumb['alt'].'" width="158" />';
												else {
													$single_post=get_posts(array(
														'numberposts'     => 1,
														'category'        => $ctg->cat_ID,
														'orderby'         => 'ID',
														'order'			  => 'asc'
													));
													if($single_post && has_post_thumbnail($single_post[0]->ID))
														echo get_the_post_thumbnail($single_post[0]->ID,'medium-frontend');
													else
														echo '<img src="'.get_bloginfo('template_url').'/images/thumb_158x232.jpg" alt="" />';
												}
												?>
											</span>
										</a>
									</div>
									<?
								}
								echo '</div>';
							}
							?>
							
							<div class="tovar_gallery_block">
								<?php
									$gallery = array_filter(explode(',', get_field('_product_image_gallery')));
									$bxPager = '';
									if ($gallery) {
										foreach($gallery as $image) {
											$imageSrcfull = wp_get_attachment_image_src($image, 'full');
											$imageSrcLarge = wp_get_attachment_image_src($image, 'large');
											$imageSRC_thumb = wp_get_attachment_image_src($image, 'pr_thumb_298x425');
											$alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
											$srcFull = $imageSrcfull[0];
											$srcLarge = $imageSrcLarge[0];
											$src = $imageSRC_thumb[0];
											echo '<div class="single_pic"><a href="'.$srcFull.'" data-fancybox="gallery" ><img src="'.$src.'" alt="'.$alt.'"/></a></div>';
										}
									}
								?>
							</div>
							
							<?php
							// $single_full_content = count($posts) == 1;
							// $single_full_content = true;	//	true - выводить запись целиком, false - выводить блоком-ссылкой на запись: миниатюра c заголовком
							
							$single_full_content_posts=get_posts(array(
								'tax_query' => array(array(
									'taxonomy' => 'category',
									'field' => 'id',
									'terms' => $cat,
									'include_children' => false
								)),
								'meta_query' => array(
									'relation' => 'OR',
									array('key' => 'template_product_status','value' => 1,'compare' => '!='),
									array('key' => 'template_product_status','compare' => 'NOT EXISTS')
								)
							));
							$single_full_content=count($single_full_content_posts)==1 ? true : false;
							
							if($single_full_content){
								while(have_posts()){ the_post();
									// the_content(); // Спросить Костю
									
									// the_content();
								}
							}else{
								// Если это шаблон импорта отображать один дизайн
								query_posts(array(
									'tax_query' => array(array(
										'taxonomy' => 'category',
										'field' => 'id',
										'terms' => $cat,
										'include_children' => false,
										'meta_key' => '_price',
										'orderby' => 'meta_value_num',
										'order' => 'ASC'
									))
								));
								if(get_field('template_product_status')){
									echo '<div class="catalog_data_single">';
									global $query_string;
									query_posts($query_string.'&meta_key=_price&orderby=meta_value_num&order=ASC');
									while(have_posts()){
										the_post();
										?>
											<div class="item">
												<a class="link" href="<? the_permalink(); ?>">
													<span class="img">
														<?php
														if(has_post_thumbnail())
															the_post_thumbnail('medium-frontend');
														else
															echo '<img src="'.get_bloginfo('template_url').'/images/thumb_158x232.jpg" alt="" />';
														?>
													</span>
													<span class="name"><?php the_title(); ?></span>
													<span class="in_stock"><span>В наличии</span></span>
													<span class="article">Артикул: <?php the_field('_sku'); ?></span>
													<?php
														if(!empty(get_field('_price'))){
															echo'<span class="price">'.cost_format(get_field('_price')).' руб.</span>';
														} else {
															echo'<span class="price">по запросу</span>';
														}
													?>
												</a>
											</div>
										<?php
									}
									echo '</div>';
								} else { // Если нет то другой дизайн
									echo '<div class="catalog_data">';
										while(have_posts()){
											the_post();
											?>
											<div class="item">
												<a class="img" href="<? the_permalink(); ?>">
													<span class="l_name"><span><?php the_title(); ?></span></span>
													<span class="back">
														<?php
														if(has_post_thumbnail())
															the_post_thumbnail('medium-frontend');
														else
															echo '<img src="'.get_bloginfo('template_url').'/images/thumb_158x232.jpg" alt="" />';
														?>
													</span>
												</a>
											</div>
											<?php
										}
									echo '</div>';
								}
								wp_reset_query();
							}
						}
						
						echo $cat_short_text;
					}
				}
			?>
		</main><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>