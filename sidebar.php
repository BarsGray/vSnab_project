<aside class="left-sidebar">
	<div class="leftmenu">
		<?php
		$categories = get_terms([
			'taxonomy' => 'category',
			'parent' => 3,
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => false
		]);
		echo '<ul class="level-1">';
			foreach( $categories as $cat ){
				$active=is_category($cat) && get_queried_object_id() === $cat->term_id ? ' class="active" ' : '';
				echo '<li><a '. $active .' href="'. get_term_link($cat) .'">'. $cat->name.'</a>';
					$categories_2 = get_terms([
						'taxonomy' => 'category',
						'parent' => $cat->term_id,
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false
					]);
					echo '<ul class="level-2">';
						foreach( $categories_2 as $cat_2 ){
							$active=is_category($cat_2) && get_queried_object_id() === $cat_2->term_id ? ' class="active" ' : '';
							echo '<li>';
								echo '<a '. $active .' href="'. get_term_link($cat_2) .'"><span style="background-image:url('. get_field('icon_pc', $cat_2) .');"></span>'. $cat_2->name .'</a>';
							echo '</li>';
						}
					echo '</ul>';
				echo '</li>';
			}
		echo '</ul>';
		?>
	</div>
</aside><!-- .left-sidebar -->