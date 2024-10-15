<?php get_header() ?>
<h1 class='entry-title'><?= get_the_title() ?></h1>

<h2 class='date'><?= get_the_date() ?></h2>
<div class="entry-content">
  <?= get_the_content() ?>
</div>
<?php get_footer() ?>
