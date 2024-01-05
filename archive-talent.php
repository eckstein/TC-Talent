<?php get_header(); ?>
<header class="header">
  <h1 class="entry-title" itemprop="name">Find Talent</h1>
</header>
<?php 
//setup variables for template parts
$template_part_args = array(
	'post' => $post, // Pass the $post variable
);
get_template_part('parts/grid', 'filter', $template_part_args); 
?>
<div class="talent-grid">
  <?php while (have_posts()) : the_post(); ?>
  <?php if (has_post_thumbnail()) : ?>
    <?php get_template_part('parts/grid', 'card'); ?>
  <?php endif; ?>
<?php endwhile; ?>
<?php //the_posts_pagination(); ?>
</div>
<?php get_footer(); ?>
<?php 
//setup variables for template parts
$template_part_args = array(
	'post' => $post, // Pass the $post variable
);
get_template_part('parts/grid', 'quick-view', $template_part_args); 
?>


