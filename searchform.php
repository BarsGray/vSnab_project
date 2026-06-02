<div class="main_search_form">
	<span class="catalog">Каталог</span>
	<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ) ?>" >
		<input type="text" value="<?php echo get_search_query() ?>" name="s" id="s" placeholder="Поиск по каталогу" />
		<input type="submit" id="searchsubmit" value="Найти" />
	</form>
</div>