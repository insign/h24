<?php get_header() ?>
<?php if (have_posts()) : ?>
   <h1><?php
	  if (is_category()) :
		 single_cat_title();
     elseif (is_tag()) :
		 single_tag_title();
     elseif (is_author()) :
		 the_post();
		 printf(__('Escritos por %s', 'h24'), get_the_author());
		 rewind_posts();
     elseif (is_day()) :
		 printf(__('Dia %s', 'h24'), get_the_date());
     elseif (is_month()) :
		 printf(__('M s: %s', 'h24'), get_the_date('F Y'));
     elseif (is_year()) :
		 printf(__('Ano: %s', 'h24'), get_the_date('Y'));
	  else :
		 _e('Arquivos', 'h24');
	  endif;
	  ?></h1>

  <?php while (have_posts()) : the_post() ?>
	 <?php if (is_page()) : ?>
         <h2 class="entry-title"><?= get_the_title() ?></h2>
	 <?php elseif (is_category('pages')) : // Verifica se a categoria atual é 'pages' ?>
         <h2 class="entry-title">
            <a href='<?= esc_url(get_the_permalink()) ?>'><?= get_the_title() ?></a>
			  <?php // Exibe a data da última modificação para posts na categoria 'pages' ?>
            - <span class='date'><?php printf(__('Atualizado em %s', 'h24'), get_the_modified_date()) ?></span>
         </h2>
	 <?php else : // Para todas as outras categorias e tipos de arquivo ?>
         <h2 class="entry-title">
            <a href="<?= esc_url(get_the_permalink()) ?>"><?= get_the_title() ?></a>
			  <?php // Exibe a data de publicação ?>
            - <span class='date'><?= get_the_date() ?></span>
         </h2>
	 <?php endif ?>
  <?php endwhile ?> ?>

  <?php if ($GLOBALS[ 'wp_query' ]->max_num_pages > 1) : ?>
      <div class="pagination"><?= the_posts_pagination() ?></div>
  <?php endif ?>

<?php else : ?>
   <p><?= __('Nada encontrado.', 'h24') ?></p>
<?php endif ?>

<?php get_footer() ?>
