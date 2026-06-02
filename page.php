<?php get_header(); ?>
	<div class="container">
		<main class="content">
			<?php if(get_field('working_hours', MAIN_PAGE)){?>
				<a class="working_hours" href="<?php the_field('working_hours', MAIN_PAGE);?>" data-fancybox><img src="<?php the_field('working_hours', MAIN_PAGE);?>" alt="" /></a>
			<?php } ?>
			<?php
				bread_crumbs();
				
				$alt_t_page = get_field('post_title_h1');
				if(!empty($alt_t_page)){
					echo "<h1>".$alt_t_page."</h1>";
				} else {
					the_title('<p class="h1">','</p>');
				}
				if(have_posts()){ the_post();
					the_content();
				}
			?>
		</main><!-- #content -->
	</div><!-- #container -->
<?php get_sidebar(); ?>
<?php get_footer();?>
