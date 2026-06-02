<?php
	get_header(); 
	global $query_string;
	query_posts($query_string.'&cat=3');
?>
	<div class="container">
		<main class="content">
			<?php bread_crumbs();?>
			<h1>Результаты поиска по запросу: <span><?= get_search_query(); ?></span></h1>
			<?php if(have_posts()){ ?>
				<ul class="search-posts-list">
					<?php while(have_posts()){ the_post(); ?>						
						<li><p class="bold"><a href="<? the_permalink(); ?>"><? the_title(); ?></a></p></li>
					<?php } ?>
				</ul>
			<?php } else { ?>
				<h2><?php _e( 'Ничего не найдено', 'twentyten' ); ?></h2>
				<p><?php _e( 'Извините, но ничего не соответствует вашим критериям поиска. Попробуйте еще раз с другими ключевыми словами.', 'twentyten' ); ?></p>
			<?php } ?>
		</main><!-- #content -->
	</div><!-- #container -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
