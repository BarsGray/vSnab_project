<?php

define('VERSION','1.0.7');
define('MAIN_PAGE',get_option('page_on_front'));
define('THEME',get_bloginfo('template_directory'));

add_action('wp_enqueue_scripts','add_remove_js_css');
function add_remove_js_css(){
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('global-styles');
	wp_deregister_style('wc-block-editor');
	wp_deregister_style('wc-block-style');
	wp_deregister_style('wc-blocks-style');
	wp_deregister_style('classic-theme-styles');
	wp_enqueue_script('jquery');
	wp_enqueue_script('fancybox3',THEME.'/js/jquery.fancybox.js',array('jquery'),VERSION,true);
	wp_enqueue_script('slick',THEME.'/js/slick.min.js',array('jquery'),VERSION,true);
	wp_enqueue_script('site',THEME.'/js/site.js',array('jquery'),VERSION,true);
	wp_enqueue_style('fancybox3',THEME.'/css/jquery.fancybox.css',array(),VERSION,'all');
	wp_enqueue_style('slick',THEME.'/css/slick.css',array(),VERSION,'all');
	wp_enqueue_style('site',THEME.'/style.css',array(),VERSION,'all');
}

add_image_size('img_gallery',370,270,true);
add_image_size('img_gallery_vertical',370,523,true);
add_shortcode('acf_gal','gallery_post');

function gallery_post($atts){
  $acf_gal=get_field('acf_gal',get_queried_object());
  if(!empty($acf_gal))
   foreach($acf_gal as $item)
    if($item['name_gal']==$atts['name']){
     $ret_gal='';
     foreach($item['img_gal'] as $foto_single)
      $ret_gal.='<a href="'.$foto_single['url'].'" data-fancybox="gallery_'.$item['name_gal'].'" data-caption="'.$foto_single['alt'].'"><img src="'.$foto_single['sizes'][$item['view_gal']].'" alt="'.$foto_single['alt'].'" /></a>';
     return '<div class="acf_gal clr">'.$ret_gal.'</div>';
    }
 }

function cost_format($price){
	return number_format($price,0,'.',' ');
}

function title_cat(){
	// $title = single_cat_title("", 0);
	// $alternate_name = get_field('alternate_name', 'category_'.$cat);
	// $post_title_h1 = get_field('post_title_h1', 'category_'.$cat);
	// if(!empty($alternate_name)){
		// $title=$alternate_name;
	// } else if(!empty($post_title_h1)){
		// $title=$post_title_h1;
	// }
	// echo '<h1>'. $title.'</h1>';
	
	$qo=get_queried_object();
	$cat_title=get_field('post_title_h1',$qo)!='' ? get_field('post_title_h1',$qo) : $qo->name;
	echo '<h1>'.$cat_title.'</h1>';
}

add_action('template_redirect','template_redirect');
function template_redirect(){
	if((is_post_type_archive() || is_category(1) || is_attachment())){
		wp_redirect('/',301); exit;
	}
}

add_filter( 'site_status_persistent_object_cache_thresholds', function( $thresholds ) { 
	$thresholds = array( 
		'alloptions_count' => 600, 
		'alloptions_bytes' => 200000, 
		'comments_count' => 2000, 
		'options_count' => 2000, 
		'posts_count' => 2000, 
		'terms_count' => 2000, 
		'users_count' => 2000, 
	); 
	return $thresholds; 
});

add_shortcode('contact','contact_post');
function contact_post(){
	ob_start();
	?>
	<div class="contact_block" itemscope itemtype="http://schema.org/Organization">
		<div class="left">
			<?php the_field('map', MAIN_PAGE);?>
		</div>
		<div class="right">
			<meta itemprop="name" content="<?php echo bloginfo('name'); ?>">
			<div class="adres"  itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<span>Адрес:</span>
				<p><span  itemprop="addressLocality"><?php the_field('city', MAIN_PAGE);?></span>, <span itemprop="streetAddress"><?php the_field('adres', MAIN_PAGE);?></span></p>
				<p><?php the_field('way', MAIN_PAGE);?></p>
			</div>
			<div class="tel">
				<span>Телефон:</span>
				<p><a href="tel:<?php echo clear_tel(get_field('tel', MAIN_PAGE));?>"><span itemprop="telephone"><?php the_field('tel', MAIN_PAGE);?></span></a></p>
				<p><a href="tel:<?php echo clear_tel(get_field('tel_2', MAIN_PAGE));?>"><span itemprop="telephone"><?php the_field('tel_2', MAIN_PAGE);?></span></a> - отдел продаж вилочных погрузчиков и техники</p>
			</div>
			<div class="mail">
				<span>E-mail:</span>
				<p>Отдел продаж: <a href="mailto:<?php the_field('mail_prod', MAIN_PAGE);?>"><span itemprop="email"><?php the_field('mail_prod', MAIN_PAGE);?></span></a></p>
				<p>Сервисный центр: <a href="mailto:<?php the_field('mail_serv', MAIN_PAGE);?>"><?php the_field('mail_serv', MAIN_PAGE);?></a></p>
			</div>
			<div class="opening_hours">
				<span>Время работы:</span>
				<?php the_field('opening_hours', MAIN_PAGE);?>
			</div>
		</div>
	</div>
	<?php
	$buffer=ob_get_contents();
	ob_end_clean();
	return $buffer;
}

add_shortcode('spoiler','spoiler_post');
function spoiler_post($atts){
	$acf_spoil=get_field('acf_spoil',get_queried_object());
	if(!empty($acf_spoil))
		foreach($acf_spoil as $item)
			if($item['name_sp']==$atts['name']){
				$ret_sp='';
				foreach($item['blocks_sp'] as $spoiler)
					$ret_sp.='<div><p>'.$spoiler['zag_sp'].'</p><div>'.apply_filters('the_content',$spoiler['data_sp']).'</div></div>';
				return '<div class="spoilers">'.$ret_sp.'</div>';
			}
}

function clear_tel($tel){
	return strip_tags(str_replace(array(' ','(',')','-'),'',$tel));
}

// Стили в админку
add_action('admin_head', 'moy_style');
function moy_style(){
	print '<style>
		#wpwrap #edittag{max-width:100%;}
	</style>';
}

add_filter('site_transient_update_plugins','filter_plugin_updates');
function filter_plugin_updates($value){
	unset($value->response['all-in-one-seo-pack/all_in_one_seo_pack.php']);
	unset($value->response['advanced-custom-fields/acf.php']);
	return $value;
}

// define(RU, ' <span class="rubl">a</span>');

add_image_size('pr_thumb', 158, 232, true);
add_image_size('pr_thumb_172x224', 172, 224, true);
add_image_size('pr_thumb_298x425', 298, 425, true);
add_image_size('pr_thumb_550x425', 550, 425, true);
add_image_size('pr_thumb_118x93', 118, 93, true);
add_image_size('pr_thumb_170x135', 170, 135, true);
add_image_size('medium-frontend', 295, 295, false);

add_shortcode('mikrorazmetka','mikrorazmetka_post');
function mikrorazmetka_post($atts){
	$ret_mr='';
	while(have_rows('acf_mikrorazmetka',$post->ID)){
		the_row();
		$name_sp=get_sub_field('mr_name');
		if($name_sp==$atts['name'])
			$ret_mr.=get_sub_field('mr_blok');
	}
	return $ret_mr;
}

add_filter( 'image_size_names_choose', 'user_custom_sizes' );

function user_custom_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'medium-frontend' => 'Средний пользовательский',
	) );
}

function get_tag_title($title, $num){
	if($num == 1) $tag = 'h1';
	elseif($num > 1 && $num <= 3) $tag = 'h2';
	elseif($num > 3 && $num <= 6) $tag = 'h3';
	elseif($num > 6 && $num <= 10) $tag = 'h4';
	elseif($num > 10 && $num <= 15) $tag = 'h5';
	elseif($num > 15 && $num <= 21) $tag = 'h6'; else $tag = 'h6';

	return '<'.$tag.'>'.$title.'</'.$tag.'>';
}

// fancybox_activate
function fancybox_activate($content){
  $pattern="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
  $replacement='<a$1href=$2$3.$4$5 data-fancybox="content" $6>';
  $content=preg_replace($pattern,$replacement,$content);
  return $content;
 }
add_filter('the_content','fancybox_activate');

// function clear_func( $atts ) {return '<div class="clear"></div>';}
// add_shortcode('clear', 'clear_func');

// add_action('wp_ajax_ajax_menu','ajax_menu');
// add_action('wp_ajax_nopriv_ajax_menu','ajax_menu');
// function ajax_menu(){
	// $menu_site = '';
	// $menu_data=build_menu($_POST['id'],$_POST['current'],'');
	// echo $menu_data;
	// wp_die();
// }

// $menu_site = '';
// function build_menu($cat_id, $curcat = 1, $menu_class = '', $isMobile = false, $level = 1){	//	Реккурсивная ф-ция, формирует меню категорий в Sidebar
	// $args = array(
		// 'parent' => $cat_id,
		// 'orderby' => 'term_order',       
		// 'hide_empty' => false
	// );
	
	// $categories = get_categories( $args );
	
	// if( $categories ){
		// $menu_site .= '<ul class="level-'.$level.' '.$menu_class.'">'; $menu_class = ''; $level++;
		// foreach( $categories as $ctg ){
		
			// $icon_data_1 = get_field('icon_pc', 'category_'.$ctg->cat_ID);
			// $icon_data_2 = get_field('icon_pc_2', 'category_'.$ctg->cat_ID);

			// $icon_data_mob_1 = get_field('icon_mobile', 'category_'.$ctg->cat_ID);
			
			// $mouseover = '';
			// $mouseout = '';
			// $style = '';
			
			// $current_class = ($ctg->cat_ID == $curcat) ? $current_class = " current-cat" : $current_class = "";
			// $parent_class = cat_is_ancestor_of($ctg->cat_ID, $curcat) ? $parent_class = " current-cat-parent" : $parent_class = "";
			
			// if($isMobile){
				// if($level == 3){
					// if($icon_data_mob_1['url']){
						// $style = 'style="background: #902c00 url('.$icon_data_mob_1['url'].') 15px center no-repeat;"';
					// }else $style = 'style="background: #902c00 url('.get_bloginfo('template_url').'/images/icons/m_icon-'.$ctg->cat_ID.'.png) 15px center no-repeat;"';
				// }
			// }else{
				// if($level == 3 && !$isMobile){
					// if($icon_data_1['url']){
						// $style = 'style="background: #f85b16 url('.$icon_data_1['url'].') 15px center no-repeat;"';
						// $mouseout = 'onmouseout="javascript:this.style.backgroundImage=\'url('.$icon_data_1['url'].')\';"';
						// if($current_class || $parent_class) $mouseout = '';
					// }else{
						// $style = 'style="background: #f85b16 url('.get_bloginfo('template_url').'/images/icons/icon-'.$ctg->cat_ID.'.png) 15px center no-repeat;"';
						// $mouseover = ' onmouseover="javascript:this.style.backgroundImage=\'url('.get_bloginfo('template_url').'/images/icons/icon-'.$ctg->cat_ID.'_h.png)\';"';
						// $mouseout = ' onmouseout="javascript:this.style.backgroundImage=\'url('.get_bloginfo('template_url').'/images/icons/icon-'.$ctg->cat_ID.'.png)\';"';
						// if($current_class || $parent_class){
							// $mouseout = ' onmouseout="javascript:this.style.backgroundImage=\'url('.get_bloginfo('template_url').'/images/icons/icon-'.$ctg->cat_ID.'_h.png)\';"';
						// }
					// }
					
					// if($icon_data_2['url']){
						// $mouseover = ' onmouseover="javascript:this.style.backgroundImage=\'url('.$icon_data_2['url'].')\';"';
						// if($current_class || $parent_class)
							// $style = 'style="background: #f85b16 url('.$icon_data_2['url'].') 15px center no-repeat;"';
					// }else{
						
					// }
				// }
			// }
			
			// $menu_site .= '<li class="cat-item-'.$ctg->cat_ID . $current_class . $parent_class.'"><a href="'.get_category_link($ctg->cat_ID).'" '.$style.$mouseover.$mouseout.' class="closed" data-catid="'.$ctg->cat_ID.'" data-icon="icon-'.$ctg->cat_ID.'">'.$ctg->cat_name.'</a>';
			// $menu_site .= build_menu($ctg->cat_ID, $curcat, $menu_class, $isMobile, $level);
			// if(!$isMobile && $level == 4) $menu_site .= get_tovars_block($ctg->cat_ID);
		// }
		// $menu_site .= '</ul>';
	// }
	// return $menu_site;
// }

function get_tovars_block($cat_id){
	$posts = get_posts( array(
		'numberposts'     => -1,
		'category'        => $cat_id
	));
	
	$html = '';
	if($posts){
		$html .= '<div class="category_popup category_popup-'.$cat_id.'">';
		$i = 1;
		foreach($posts as $pst){
			//if($i % 3 == 0) $clear = 'clear'; else $clear = ''; $i++;
			
			if(strlen($pst->post_title) > 45)
				$post_name_text = kama_excerpt('text='.$pst->post_title.'&maxchar=45&echo=return').'...';
			else	
				$post_name_text = $pst->post_title;
			
			$html .= 	'<div class="tovar_block_container '.$clear.'">';
			$html .= 		'<div class="tovar_block_content">';
			$html .= 			'<p class="block-title"><a href="/?p='.$pst->ID.'" class="pu_tovar">'.$post_name_text.'<br/>';
			//$html .= 			'<span>'.get_the_post_thumbnail($pst->ID, 'pr_thumb_118x93').'</span></a></p>';
			$html .= 			'<span>'.get_the_post_thumbnail($pst->ID, 'medium').'</span></a></p>';
			$html .= 		'</div>';
			$html .= 	'</div>';
			if($i % 3 == 0) $html .= '<div class="clear"></div>'; $i++;
		}
		
		$html .= '</div>';
		//$html .= '</div>';
	}
	return $html;
}

function get_terms_by_meta($meta_key, $meta_value){
	$all_terms = get_terms(array(
		'taxonomy'      => array('category'),
		'hide_empty'    => false,
		//'child_of' => 3,
		'fields' => 'id=>name'
	));

	$return = array();
	foreach($all_terms as $term_id => $term_name){
		$cat_leader = get_field($meta_key, 'category_'.$term_id);
		if($cat_leader == $meta_value) $return[$term_id] = $term_name;
	}
	return $return;
}	

/* Хлебные крошки для WordPress (breadcrumbs) */

function bread_crumbs($sep = ' » ', $l10n = array(), $args = array()){
	$kb=new Breadcrumbs();
	echo $kb->get_crumbs($sep, $l10n, $args);
}
class Breadcrumbs {

	public $arg;

	// Локализация
	static $l10n = array(
		'home'       => 'Главная',
		'paged'      => 'Страница %d',
		'_404'       => 'Ошибка 404',
		'search'     => 'Результаты поиска по запросу - <b>%s</b>',
		'author'     => 'Архив автора: <b>%s</b>',
		'year'       => 'Архив за <b>%d</b> год',
		'month'      => 'Архив за: <b>%s</b>',
		'day'        => '',
		'attachment' => 'Медиа: %s',
		'tag'        => 'Записи по метке: <b>%s</b>',
		'tax_tag'    => '%1$s из "%2$s" по тегу: <b>%3$s</b>',
		// tax_tag выведет: 'тип_записи из "название_таксы" по тегу: имя_термина'.
		// Если нужны отдельные холдеры, например только имя термина, пишем так: 'записи по тегу: %3$s'
	);

	// Параметры по умолчанию
	static $args = array(
		'on_front_page'   => true,  // выводить крошки на главной странице
		'show_post_title' => true,  // показывать ли название записи в конце (последний элемент). Для записей, страниц, вложений
		'show_term_title' => true,  // показывать ли название элемента таксономии в конце (последний элемент). Для меток, рубрик и других такс
		'title_patt'      => '<span class="kb_title">%s</span>', // шаблон для последнего заголовка. Если включено: show_post_title или show_term_title
		'last_sep'        => true,  // показывать последний разделитель, когда заголовок в конце не отображается
		'markup'          => 'schema.org', // 'markup' - микроразметка. Может быть: 'rdf.data-vocabulary.org', 'schema.org', '' - без микроразметки
										   // или можно указать свой массив разметки:
										   // array( 'wrappatt'=>'<div class="kama_breadcrumbs">%s</div>', 'linkpatt'=>'<a href="%s">%s</a>', 'sep_after'=>'', )
		'priority_tax'    => array('category'), // приоритетные таксономии, нужно когда запись в нескольких таксах
		'priority_terms'  => array(), // 'priority_terms' - приоритетные элементы таксономий, когда запись находится в нескольких элементах одной таксы одновременно.
									  // Например: array( 'category'=>array(45,'term_name'), 'tax_name'=>array(1,2,'name') )
									  // 'category' - такса для которой указываются приор. элементы: 45 - ID термина и 'term_name' - ярлык.
									  // порядок 45 и 'term_name' имеет значение: чем раньше тем важнее. Все указанные термины важнее неуказанных...
		'nofollow' => false, // добавлять rel=nofollow к ссылкам?

		// служебные
		'sep'             => '',
		'linkpatt'        => '',
		'pg_end'          => '',
	);

	function get_crumbs( $sep = '', $l10n = array(), $args = array() ){
		global $post, $wp_query, $wp_post_types;

		self::$args['sep'] = $sep;

		// Фильтрует дефолты и сливает
		$loc = (object) array_merge( apply_filters('kama_breadcrumbs_default_loc', self::$l10n ), $l10n );
		$arg = (object) array_merge( apply_filters('kama_breadcrumbs_default_args', self::$args ), $args );

		$arg->sep = '<span class="divider">'. $arg->sep .'</span>'; // дополним

		// упростим
		$sep = & $arg->sep;
		$this->arg = & $arg;

		// микроразметка ---
		if(1){
			$mark = & $arg->markup;

			// Разметка по умолчанию
			if( ! $mark ) $mark = array(
				'wrappatt'  => '<div class="bread">%s</div>',
				'linkpatt'  => '<a href="%s">%s</a>',
				'sep_after' => '',
			);
			// rdf
			elseif( $mark === 'rdf.data-vocabulary.org' ) $mark = array(
				'wrappatt'   => '<div class="bread" prefix="v: http://rdf.data-vocabulary.org/#">%s</div>',
				'linkpatt'   => '<span typeof="v:Breadcrumb"><a href="%s" rel="v:url" property="v:title">%s</a>',
				'sep_after'  => '</span>', // закрываем span после разделителя!
			);
			// schema.org
			elseif( $mark === 'schema.org' ) $mark = array(
				'wrappatt'   => '<div class="bread" itemscope itemtype="http://schema.org/BreadcrumbList">%s</div>',
				'linkpatt'   => '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="%s" itemprop="item"><span itemprop="name">%s</span><span itemprop="position" content="$d"></span></a></span>',
				'sep_after'  => '',
			);

			elseif( ! is_array($mark) )
				die( __CLASS__ .': "markup" parameter must be array...');

			$wrappatt  = $mark['wrappatt'];
			$arg->linkpatt  = $arg->nofollow ? str_replace('<a ','<a rel="nofollow"', $mark['linkpatt']) : $mark['linkpatt'];
			$arg->sep      .= $mark['sep_after']."\n";
		}

		$linkpatt = $arg->linkpatt; // упростим

		$q_obj = get_queried_object();

		// может это архив пустой таксы?
		$ptype = null;
		if( empty($post) ){
			if( isset($q_obj->taxonomy) )
				$ptype = & $wp_post_types[ get_taxonomy($q_obj->taxonomy)->object_type[0] ];
		}
		else $ptype = & $wp_post_types[ $post->post_type ];

		// paged
		$arg->pg_end = '';
		if( ($paged_num = get_query_var('paged')) || ($paged_num = get_query_var('page')) )
			$arg->pg_end = $sep . sprintf( $loc->paged, (int) $paged_num );

		$pg_end = $arg->pg_end; // упростим

		$out = '';

		if( is_front_page() ){
			return $arg->on_front_page ? sprintf( $wrappatt, ( $paged_num ? sprintf($linkpatt, get_home_url(), $loc->home) . $pg_end : $loc->home ) ) : '';
		}
		// страница записей, когда для главной установлена отдельная страница.
		elseif( is_home() ) {
			$out = $paged_num ? ( sprintf( $linkpatt, get_permalink($q_obj), esc_html($q_obj->post_title) ) . $pg_end ) : esc_html($q_obj->post_title);
		}
		elseif( is_404() ){
			$out = $loc->_404;
		}
		elseif( is_search() ){
			$out = sprintf( $loc->search, esc_html( $GLOBALS['s'] ) );
		}
		elseif( is_author() ){
			$tit = sprintf( $loc->author, esc_html($q_obj->display_name) );
			$out = ( $paged_num ? sprintf( $linkpatt, get_author_posts_url( $q_obj->ID, $q_obj->user_nicename ) . $pg_end, $tit ) : $tit );
		}
		elseif( is_year() || is_month() || is_day() ){
			$y_url  = get_year_link( $year = get_the_time('Y') );

			if( is_year() ){
				$tit = sprintf( $loc->year, $year );
				$out = ( $paged_num ? sprintf($linkpatt, $y_url, $tit) . $pg_end : $tit );
			}
			// month day
			else {
				$y_link = sprintf( $linkpatt, $y_url, $year);
				$m_url  = get_month_link( $year, get_the_time('m') );

				if( is_month() ){
					$tit = sprintf( $loc->month, get_the_time('F') );
					$out = $y_link . $sep . ( $paged_num ? sprintf( $linkpatt, $m_url, $tit ) . $pg_end : $tit );
				}
				elseif( is_day() ){
					$m_link = sprintf( $linkpatt, $m_url, get_the_time('F'));
					$out = $y_link . $sep . $m_link . $sep . get_the_time('l');
				}
			}
		}
		// Древовидные записи
		elseif( is_singular() && $ptype->hierarchical ){
			$out = $this->_add_title( $this->_page_crumbs($post), $post );
		}
		// Таксы, плоские записи и вложения
		else {
			$term = $q_obj; // таксономии

			// определяем термин для записей (включая вложения attachments)
			if( is_singular() ){
				// изменим $post, чтобы определить термин родителя вложения
				if( is_attachment() && $post->post_parent ){
					$save_post = $post; // сохраним
					$post = get_post($post->post_parent);
				}

				// учитывает если вложения прикрепляются к таксам древовидным - все бывает :)
				$taxonomies = get_object_taxonomies( $post->post_type );
				// оставим только древовидные и публичные, мало ли...
				$taxonomies = array_intersect( $taxonomies, get_taxonomies( array('hierarchical' => true, 'public' => true) ) );

				if( $taxonomies ){
					// сортируем по приоритету
					if( ! empty($arg->priority_tax) ){
						usort( $taxonomies, function($a,$b)use($arg){
							$a_index = array_search($a, $arg->priority_tax);
							if( $a_index === false ) $a_index = 9999999;

							$b_index = array_search($b, $arg->priority_tax);
							if( $b_index === false ) $b_index = 9999999;

							return ( $b_index === $a_index ) ? 0 : ( $b_index < $a_index ? 1 : -1 ); // меньше индекс - выше
						} );
					}

					// пробуем получить термины, в порядке приоритета такс
					foreach( $taxonomies as $taxname ){
						if( $terms = get_the_terms( $post->ID, $taxname ) ){
							// проверим приоритетные термины для таксы
							$prior_terms = & $arg->priority_terms[ $taxname ];
							if( $prior_terms && count($terms) > 2 ){
								foreach( (array) $prior_terms as $term_id ){
									$filter_field = is_numeric($term_id) ? 'term_id' : 'slug';
									$_terms = wp_list_filter( $terms, array($filter_field=>$term_id) );

									if( $_terms ){
										$term = array_shift( $_terms );
										break;
									}
								}
							}
							else
								$term = array_shift( $terms );

							break;
						}
					}
				}

				if( isset($save_post) ) $post = $save_post; // вернем обратно (для вложений)
			}

			// вывод

			// все виды записей с терминами или термины
			if( $term && isset($term->term_id) ){
				$term = apply_filters('kama_breadcrumbs_term', $term );

				// attachment
				if( is_attachment() ){
					if( ! $post->post_parent )
						$out = sprintf( $loc->attachment, esc_html($post->post_title) );
					else {
						if( ! $out = apply_filters('attachment_tax_crumbs', '', $term, $this ) ){
							$_crumbs    = $this->_tax_crumbs( $term, 'self' );
							$parent_tit = sprintf( $linkpatt, get_permalink($post->post_parent), get_the_title($post->post_parent) );
							$_out = implode( $sep, array($_crumbs, $parent_tit) );
							$out = $this->_add_title( $_out, $post );
						}
					}
				}
				// single
				elseif( is_single() ){
					if( ! $out = apply_filters('post_tax_crumbs', '', $term, $this ) ){
						$_crumbs = $this->_tax_crumbs( $term, 'self' );
						$out = $this->_add_title( $_crumbs, $post );
					}
				}
				// не древовидная такса (метки)
				elseif( ! is_taxonomy_hierarchical($term->taxonomy) ){
					// метка
					if( is_tag() )
						$out = $this->_add_title('', $term, sprintf( $loc->tag, esc_html($term->name) ) );
					// такса
					elseif( is_tax() ){
						$post_label = $ptype->labels->name;
						$tax_label = $GLOBALS['wp_taxonomies'][ $term->taxonomy ]->labels->name;
						$out = $this->_add_title('', $term, sprintf( $loc->tax_tag, $post_label, $tax_label, esc_html($term->name) ) );
					}
				}
				// древовидная такса (рибрики)
				else {
					if( ! $out = apply_filters('term_tax_crumbs', '', $term, $this ) ){
						$_crumbs = $this->_tax_crumbs( $term, 'parent' );
						$out = $this->_add_title( $_crumbs, $term, esc_html($term->name) );                     
					}
				}
			}
			// влоежния от записи без терминов
			elseif( is_attachment() ){
				$parent = get_post($post->post_parent);
				$parent_link = sprintf( $linkpatt, get_permalink($parent), esc_html($parent->post_title) );
				$_out = $parent_link;

				// вложение от записи древовидного типа записи
				if( is_post_type_hierarchical($parent->post_type) ){
					$parent_crumbs = $this->_page_crumbs($parent);
					$_out = implode( $sep, array( $parent_crumbs, $parent_link ) );
				}

				$out = $this->_add_title( $_out, $post );
			}
			// записи без терминов
			elseif( is_singular() ){
				$out = $this->_add_title( '', $post );
			}
		}

		// замена ссылки на архивную страницу для типа записи
		$home_after = apply_filters('kama_breadcrumbs_home_after', '', $linkpatt, $sep, $ptype, $q_obj);

		if( '' === $home_after ){
			// Ссылка на архивную страницу типа записи для: отдельных страниц этого типа; архивов этого типа; таксономий связанных с этим типом.
			if( $ptype && $ptype->has_archive && ! in_array( $ptype->name, array('post','page','attachment') )
				&& ( is_post_type_archive() || is_singular() || (is_tax() && in_array($term->taxonomy, $ptype->taxonomies)) )
			){
				$pt_title = $ptype->labels->name;

				// первая страница архива типа записи
				if( is_post_type_archive() && ! $paged_num )
					$home_after = sprintf( $this->arg->title_patt, $pt_title );
				// singular, paged post_type_archive, tax
				else{
					$home_after = sprintf( $linkpatt, get_post_type_archive_link($ptype->name), $pt_title );

					$home_after .= ( ($paged_num && ! is_tax()) ? $pg_end : $sep ); // пагинация
				}
			}
		}

		$before_out = sprintf( $linkpatt, home_url(), $loc->home ) . ( $home_after ? $sep.$home_after : ($out ? $sep : '') );

		$out = apply_filters('kama_breadcrumbs_pre_out', $out, $sep, $loc, $arg );

		$out = sprintf( $wrappatt, $before_out . $out );
		
		//fix_position
		$iii=1;
		$replace_out='';
		foreach(explode('$d',$out) as $value)
            $replace_out.=$value.$iii++;
        $out= substr($replace_out,0,-1);

		return apply_filters('kama_breadcrumbs', $out, $sep, $loc, $arg );
	}

	function _page_crumbs( $post ){
		$parent = $post->post_parent;

		$crumbs = array();
		while( $parent ){
			$page = get_post( $parent );
			$crumbs[] = sprintf( $this->arg->linkpatt, get_permalink($page), esc_html($page->post_title) );
			$parent = $page->post_parent;
		}

		return implode( $this->arg->sep, array_reverse($crumbs) );
	}

	function _tax_crumbs( $term, $start_from = 'self' ){
		$termlinks = array();
		$term_id = ($start_from === 'parent') ? $term->parent : $term->term_id;
		while( $term_id ){
			$term       = get_term( $term_id, $term->taxonomy );
			$termlinks[] = sprintf( $this->arg->linkpatt, get_term_link($term), esc_html($term->name) );
			$term_id    = $term->parent;
		}

		if( $termlinks )
			return implode( $this->arg->sep, array_reverse($termlinks) ) /*. $this->arg->sep*/;
		return '';
	}

	// добалвяет заголовок к переданному тексту, с учетом всех опций. Добавляет разделитель в начало, если надо.
	function _add_title( $add_to, $obj, $term_title = '' ){
		$arg = & $this->arg; // упростим...
		$title = $term_title ? $term_title : esc_html($obj->post_title); // $term_title чиститься отдельно, теги моугт быть...
		$show_title = $term_title ? $arg->show_term_title : $arg->show_post_title;

		// пагинация
		if( $arg->pg_end ){
			$link = $term_title ? get_term_link($obj) : get_permalink($obj);
			$add_to .= ($add_to ? $arg->sep : '') . sprintf( $arg->linkpatt, $link, $title ) . $arg->pg_end;
		}
		// дополняем - ставим sep
		elseif( $add_to ){
			if( $show_title )
				$add_to .= $arg->sep . sprintf( $arg->title_patt, $title );
			elseif( $arg->last_sep )
				$add_to .= $arg->sep;
		}
		// sep будет потом...
		elseif( $show_title )
			$add_to = sprintf( $arg->title_patt, $title );

		return $add_to;
	}

}
// end breadcrumbs

function category_has_children() {
global $wpdb;
$term = get_queried_object();
$category_children_check = $wpdb->get_results(" SELECT * FROM wp_term_taxonomy WHERE parent = '$term->term_id' ");
     if ($category_children_check) {
          return true;
     } else {
          return false;
     }
}

/**
 * TwentyTen functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyten_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development and
 * https://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyten_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see https://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

/*
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/* Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support()        To add support for post thumbnails, custom headers and backgrounds, and automatic feed links.
 * @uses register_nav_menus()       To add support for navigation menus.
 * @uses add_editor_style()         To style the visual editor.
 * @uses load_theme_textdomain()    For translation/localization support.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size()  To set a custom post thumbnail size.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory
	 */
	load_theme_textdomain( 'twentyten', get_template_directory() . '/languages' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', array(
		// Let WordPress know what our default background color is.
		'default-color' => 'f1f1f1',
	) );

	// The custom header business starts here.

	$custom_header_support = array(
		/*
		 * The default image to use.
		 * The %s is a placeholder for the theme template directory URI.
		 */
		'default-image' => '%s/images/headers/path.jpg',
		// The height and width of our custom header.
		/**
		 * Filter the Twenty Ten default header image width.
		 *
		 * @since Twenty Ten 1.0
		 *
		 * @param int The default header image width in pixels. Default 940.
		 */
		'width' => apply_filters( 'twentyten_header_image_width', 940 ),
		/**
		 * Filter the Twenty Ten defaul header image height.
		 *
		 * @since Twenty Ten 1.0
		 *
		 * @param int The default header image height in pixels. Default 198.
		 */
		'height' => apply_filters( 'twentyten_header_image_height', 198 ),
		// Support flexible heights.
		'flex-height' => true,
		// Don't support text inside the header image.
		'header-text' => false,
		// Callback for styling the header preview in the admin.
		'admin-head-callback' => 'twentyten_admin_header_style',
	);

	add_theme_support( 'custom-header', $custom_header_support );

	if ( ! function_exists( 'get_custom_header' ) ) {
		// This is all for compatibility with versions of WordPress prior to 3.4.
		define( 'HEADER_TEXTCOLOR', '' );
		define( 'NO_HEADER_TEXT', true );
		define( 'HEADER_IMAGE', $custom_header_support['default-image'] );
		define( 'HEADER_IMAGE_WIDTH', $custom_header_support['width'] );
		define( 'HEADER_IMAGE_HEIGHT', $custom_header_support['height'] );
		add_custom_image_header( '', $custom_header_support['admin-head-callback'] );
		add_custom_background();
	}

	/*
	 * We'll be using post thumbnails for custom header images on posts and pages.
	 * We want them to be 940 pixels wide by 198 pixels tall.
	 * Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	 */
	set_post_thumbnail_size( $custom_header_support['width'], $custom_header_support['height'], true );

	// ... and thus ends the custom header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'berries' => array(
			'url' => '%s/images/headers/berries.jpg',
			'thumbnail_url' => '%s/images/headers/berries-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Berries', 'twentyten' )
		),
		'cherryblossom' => array(
			'url' => '%s/images/headers/cherryblossoms.jpg',
			'thumbnail_url' => '%s/images/headers/cherryblossoms-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Cherry Blossoms', 'twentyten' )
		),
		'concave' => array(
			'url' => '%s/images/headers/concave.jpg',
			'thumbnail_url' => '%s/images/headers/concave-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Concave', 'twentyten' )
		),
		'fern' => array(
			'url' => '%s/images/headers/fern.jpg',
			'thumbnail_url' => '%s/images/headers/fern-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Fern', 'twentyten' )
		),
		'forestfloor' => array(
			'url' => '%s/images/headers/forestfloor.jpg',
			'thumbnail_url' => '%s/images/headers/forestfloor-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Forest Floor', 'twentyten' )
		),
		'inkwell' => array(
			'url' => '%s/images/headers/inkwell.jpg',
			'thumbnail_url' => '%s/images/headers/inkwell-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Inkwell', 'twentyten' )
		),
		'path' => array(
			'url' => '%s/images/headers/path.jpg',
			'thumbnail_url' => '%s/images/headers/path-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Path', 'twentyten' )
		),
		'sunset' => array(
			'url' => '%s/images/headers/sunset.jpg',
			'thumbnail_url' => '%s/images/headers/sunset-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Sunset', 'twentyten' )
		)
	) );
}
endif;

if ( ! function_exists( 'twentyten_admin_header_style' ) ) :
/**
 * Style the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyten_setup().
 *
 * @since Twenty Ten 1.0
 */
function twentyten_admin_header_style() {
?>
<style type="text/css" id="twentyten-admin-header-css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If header-text was supported, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/**
 * Show a home link for our wp_nav_menu() fallback, wp_page_menu().
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 *
 * @param array $args An optional array of arguments. @see wp_page_menu()
 */
function twentyten_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

/**
 * Set the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 *
 * @param int $length The number of excerpt characters.
 * @return int The filtered number of excerpt characters.
 */
function twentyten_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyten_excerpt_length' );

if ( ! function_exists( 'twentyten_continue_reading_link' ) ) :
/**
 * Return a "Continue Reading" link for excerpts.
 *
 * @since Twenty Ten 1.0
 *
 * @return string "Continue Reading" link.
 */
function twentyten_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) . '</a>';
}
endif;

/**
 * Replace "[...]" with an ellipsis and twentyten_continue_reading_link().
 *
 * "[...]" is appended to automatically generated excerpts.
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 *
 * @param string $more The Read More text.
 * @return string An ellipsis.
 */
function twentyten_auto_excerpt_more( $more ) {
	if ( ! is_admin() ) {
		return ' &hellip;' . twentyten_continue_reading_link();
	}
	return $more;
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

/**
 * Add a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 *
 * @param string $output The "Coninue Reading" link.
 * @return string Excerpt with a pretty "Continue Reading" link.
 */
function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() && ! is_admin() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 * @since Twenty Ten 1.2
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 * @since Twenty Ten 1.0
 * @deprecated Deprecated in Twenty Ten 1.2 for WordPress 3.1
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
// Backwards compatibility with WordPress 3.0.
if ( version_compare( $GLOBALS['wp_version'], '3.1', '<' ) )
	add_filter( 'gallery_style', 'twentyten_remove_gallery_css' );

if ( ! function_exists( 'twentyten_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 *
 * @param object $comment The comment object.
 * @param array  $args    An array of arguments. @see get_comment_reply_link()
 * @param int    $depth   The depth of the comment.
 */
function twentyten_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, 40 ); ?>
				<?php printf( __( '%s <span class="says">says:</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
			</div><!-- .comment-author .vcard -->
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em>
				<br />
			<?php endif; ?>

			<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php
					/* translators: 1: date, 2: time */
					printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
				?>
			</div><!-- .comment-meta .commentmetadata -->

			<div class="comment-body"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 *
 * @uses register_sidebar()
 */
function twentyten_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'twentyten' ),
		'id' => 'primary-widget-area',
		'description' => __( 'Add widgets here to appear in your sidebar.', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'twentyten' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'An optional secondary widget area, displays below the primary widget area in your sidebar.', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'twentyten' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'An optional widget area for your site footer.', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'twentyten' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'An optional widget area for your site footer.', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'twentyten' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'An optional widget area for your site footer.', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'twentyten' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'An optional widget area for your site footer.', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'twentyten_widgets_init' );

/**
 * Remove the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * This function uses a filter (show_recent_comments_widget_style) new in WordPress 3.1
 * to remove the default style. Using Twenty Ten 1.2 in WordPress 3.0 will show the styles,
 * but they won't have any effect on the widget in default Twenty Ten styling.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'twentyten_remove_recent_comments_style' );

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Print HTML with meta information for the current post-date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'twentyten' ), get_the_author() ) ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'twentyten_posted_in' ) ) :
/**
 * Print HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

/**
 * Retrieve the IDs for images in a gallery.
 *
 * @uses get_post_galleries() First, if available. Falls back to shortcode parsing,
 *                            then as last option uses a get_posts() call.
 *
 * @since Twenty Ten 1.6.
 *
 * @return array List of image IDs from the post gallery.
 */
function twentyten_get_gallery_images() {
	$images = array();

	if ( function_exists( 'get_post_galleries' ) ) {
		$galleries = get_post_galleries( get_the_ID(), false );
		if ( isset( $galleries[0]['ids'] ) )
			$images = explode( ',', $galleries[0]['ids'] );
	} else {
		$pattern = get_shortcode_regex();
		preg_match( "/$pattern/s", get_the_content(), $match );
		$atts = shortcode_parse_atts( $match[3] );
		if ( isset( $atts['ids'] ) )
			$images = explode( ',', $atts['ids'] );
	}

	if ( ! $images ) {
		$images = get_posts( array(
			'fields'         => 'ids',
			'numberposts'    => 999,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'post_mime_type' => 'image',
			'post_parent'    => get_the_ID(),
			'post_type'      => 'attachment',
		) );
	}

	return $images;
}



/*------------------Галерея-------------------*/

// wp_enqueue_script('simpleGallery', get_template_directory_uri().'/js/jquery.admin.js', array('jquery'), '0.1');
// wp_enqueue_style('simpleGallery', get_template_directory_uri().'/css/admin-style.css', false, '0.1');

add_action('save_post', 'saveImages', 10, 2);
add_action('add_meta_boxes', 'addMetaBoxes', 10, 2);

/* Метабокс галереи */

function addMetaBoxes($postType, $post) {
	$galleryPostTypes = array('post');
	if (in_array($postType, $galleryPostTypes)) {
		add_meta_box('productImagesBox', 'Галерея', 'productImagesBox', null, 'side', 'low');
	}
}

function productImagesBox() {
	global $post;
	?>
	<div id="product_images_container">
		<ul class="product_images">
			<?
			$product_image_gallery = get_post_meta( $post->ID, '_product_image_gallery', true );
			$attachments = array_filter( explode( ',', $product_image_gallery ) );
			if ( $attachments )
				foreach ( $attachments as $attachment_id ) {
					echo '<li class="image" data-attachment_id="' . $attachment_id . '">
						' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
						<ul class="actions">
							<li><a href="#" class="delete" title="Удалить изображение">Удалить</a></li>
						</ul>
					</li>';
				}
			?>
		</ul>
		<input type="hidden" id="product_image_gallery" name="product_image_gallery" value="<?= esc_attr( $product_image_gallery ); ?>" />
	</div>
	<p class="add_product_images hide-if-no-js">
		<a href="#">Добавить изображения в галерею</a>
	</p>
<?
}

/*
 * Проверка принадлежности поста к категории
 */
function in_this_child_cat($cats,$_post){
    $check = false;
    if(in_category( $cats, $_post)) {
        $check = true;
    }
    else {
        foreach ( (array) $cats as $cat ) {
            // get_term_children() accepts integer ID only
            $descendants = get_term_children( (int) $cat, 'category');
            if ( $descendants && in_category( $descendants, $_post ) )
                $check = true;
        }
    }

    return $check;
}

/**
 * Сохранение галереи
 * @param $post_id
 * @param $post
 */
function saveImages($post_id, $post) {
	if(isset($_POST['product_image_gallery'])) {
		$attachment_ids = array_filter( explode( ',', sanitize_text_field($_POST['product_image_gallery']) ) );
		update_post_meta( $post_id, '_product_image_gallery', implode( ',', $attachment_ids ) );
	}
}

add_filter('excerpt_more', function($more) {
	return '...';
});